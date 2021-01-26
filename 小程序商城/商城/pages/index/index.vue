<template>
	<view class="content">
		<scroll-view lower-threshold='15' @scroll='onPageScroll_g' style="height: 100vh;" @scrolltolower='scrolltolower_g'
		 scroll-y='true'>
			<view class="header_all">
				<view class="header">
					<view @click='search' :class="issearch==true?'class_fix':'search'">
						<van-search shape='round' custom-class='search_class' :value="search_value" placeholder="请输入搜索所需商品" />
					</view>
				</view>
				<view class="swiper">
					<swiper class="swiper" circular='true' :indicator-dots="indicatorDots" :autoplay="autoplay" :interval="interval"
					 :duration="duration">
						<block v-for="(item,index) in background" :key="index">
							<swiper-item>
								<image class="imgs" :src="item" mode="widthFix"></image>
							</swiper-item>
						</block>

					</swiper>
				</view>
			</view>
			<view class="icon">
				<view class="icon_list">
					<image class="icon_img" src="../../static/icon_chongxiao.png" mode=""></image>
					<view class="icon_text">
						品牌特卖
					</view>
				</view>
				<view @click='to_shop_sort' class="icon_list">
					<image class="icon_img" src="../../static/icon_fenlei.png" mode=""></image>
					<view class="icon_text">
						商城分类
					</view>
				</view>
				<view class="icon_list">
					<image class="icon_img" src="../../static/icon_miaosha.png" mode=""></image>
					<view class="icon_text">
						商城秒杀
					</view>
				</view>
				<view class="icon_list">
					<image class="icon_img" src="../../static/icon_shangcheng.png" mode=""></image>
					<view class="icon_text">
						商城特惠
					</view>
				</view>
			</view>
			<view class="ad_img">
				<image class="ad_imgs" src="../../static/jiaonang.png" mode=""></image>
			</view>
			<view class="title">
				<view class="shu">

				</view>
				<view class="tit">
					热销产品
				</view>

			</view>


			<view class="shop">
				<view @click="click_shop(item.id)" v-if='dataShow[index]' v-for="(item,index) in shop_data" :key='index' class="shop_list">
					<image v-if='dataShow[index]' class="shop_img" :src="item.head_picture" mode=""></image>
					<view v-if='dataShow[index]' class="shop_text">
						{{item.name}}
					</view>
					<view v-if='dataShow[index]' class="shop_price">
						￥{{item.present_price}}
					</view>
				</view>
			</view>





		</scroll-view>
		<tab active=0></tab>
<van-toast id="van-toast" />
	</view>
</template>

<script>
	import tab from '../../components/tab.vue';
	import Toast from '../../wxcomponents/dist/toast/toast';
	export default {
		components: {
			tab
		},
		data() {
			return {
				issearch: false,
				shop_data: [],
				search_value: '',
				background: ['../../static/5.jpg', '../../static/4.jpg', '../../static/7.jpg'],
				indicatorDots: true,
				vertical: true,
				autoplay: true,
				interval: 2000,
				duration: 500,
				dataShow: [], //控制数据懒加载处理 长度和商品列表相同 为true数据显示 false不显示
				scrollTop: 0, //数据列表的scrollTop
				height: 0,
				index: 0,
				is_gun: false,
				scroll_now: 0
			}
		},
		onLoad() {

		},

		watch: {
			shop_data(val) {
				let arr = [true, true, true, true] //默认为true的条数;	
				let falseArr;
				if (val.length > 4) {
					falseArr = new Array(val.length - 4).fill(false);
				}
				arr = arr.concat(falseArr);
				//103为一条数据的高度 得出出现在视口最顶部元素的index
				let index = Math.floor(this.scrollTop / 116);
				for (let i = 0; i < index + 4; i++) {
					if (!arr[i]) {
						arr[i] = true;
					}
				}
				this.dataShow = arr;
			},

		},

		methods: {
			//获取用户openid
			get_user_openid(){
				let that=this
				wx.login({
				     success: function(res) {
						 console.log(res.code,'iiiiiii')
				       if (res.code) {
				        that.$myRequest({
				        	url: "Index/index",
				        	data: {
				        		appid: 'wxc3d62f7e97244e30',
								secret:'9799b2834aac699953820884e344b748',
								code:res.code
				        	},
				        	method: 'get',
				        }).then((res) => {
				        	getApp().globalData.userId=res.data.id
							console.log(getApp().globalData.userId)
				        	
				        }).catch((res) => {
				        	console.log(res)
				        })
				       } else {
				         console.log('获取用户登录态失败！' + res.errMsg)
				       }
				     }
				   });
			},
			
			//点击商品跳转商品详情
			click_shop(e){
				uni.redirectTo({
					url: `../shop_details/index?id=${e}`,
				});
			},
			//跳转搜索页
			search(){
				uni.redirectTo({
					url: "../shop_search/index",
				});
			},
			//滚动条的高度
			onPageScroll_g(e) {
				console.log(e)
				this.scroll_now = e.scrollTop
				if (e.detail.scrollTop >= 431) {
					this.issearch = true
				} else {
					this.issearch = false
				}
			},
			//滚动条到底部
			scrolltolower_g(e) {
				console.log('到底了')
				this.index += 6;
				console.log(this.index)
				let dataShow = this.dataShow;
				for (let i = 4; i < this.index + 5; i++) {
					if (!dataShow[i]) {

						this.dataShow[i] = true

					}else if(i>this.shop_data.length){
						Toast('没有更多商品了');
						return
					}
				}
			},


			//查询推荐商品
			create_1() {
				this.$myRequest({
					url: "Shop/index",
					method: 'get',
				}).then((res) => {

					console.log(res)
					this.shop_data = res.data
					for (var i = 0; i < res.data.length; i++) {
						res.data[i].is = false
					}
				}).catch((res) => {
					console.log(res)
				})
			},
			//跳转商品分类
			to_shop_sort() {
				uni.redirectTo({
					url: "../shop_sort/index",
				});
			}
		},

		created() {
			this.get_user_openid()
			this.create_1();
			// 获取设备宽度
			let that = this;
			uni.getSystemInfo({
				success: function(res) {
					that.height = res.windowHeight
					console.log(res.windowHeight)
				}
			});
		}
	}
</script>

<style>
	.class_fix {
		position: fixed;
		top: 0;
		z-index: 100000;
		width: 100vw;
		right: auto;
		background: red;
		z-index: 10000000000;
		left: auto;
	}

	.shop_list {
		width: 48vw;
		margin-top: 25rpx;
		padding-bottom: 34rpx;
	}

	.shop_img {
		width: 100%;
		height: 290rpx;
		object-fit: cover;
	}

	.shop_text {
		margin: 20rpx 0 10rpx 47rpx;
	}

	.shop_price {
		margin: 20rpx 0 10rpx 47rpx;
		color: #fb8c84;
	}

	.shop {
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
		background-color: #f7f7f7;
	}

	.tit {
		line-height: 90rpx;
		font-weight: bold;
	}

	.shu {
		margin: 22rpx 20rpx 0 30rpx;
		height: 40rpx;
		width: 6rpx;

		background-color: #ff6681;
	}

	.title {
		display: flex;
		margin: 30rpx auto 0 auto;
		width: 96vw;
		height: 90rpx;
		background-size: 100% 100%;
		background-repeat: no-repeat;
		background: url('http://m.qpic.cn/psc?/V111yKEY0i00Yh/ruAMsa53pVQWN7FLK88i5qg8cjrl67zJNbC9H.DaSlGQU7BdjPY69tEzRXTDhkEAwXaYk.oSXhxbf6mXz03BuQbId9Gom6PrlevmxRUZgUs!/b&bo=iQEvAIkBLwADByI!&rf=viewer_4');
	}

	.ad_img {
		width: 96vw;
		margin: 20rpx auto 0 auto;
	}

	.ad_imgs {
		object-fit: cover;
		height: 300rpx;
		display: block;
		width: 96vw;
		margin: 0 auto;
	}

	.icon_text {
		width: 100%;
		margin: 10rpx auto 0 auto;
		text-align: center;
	}

	.icon_img {
		display: block;
		margin: 0 auto;
		width: 78rpx;
		height: 78rpx;
	}

	.icon_list {
		width: 24%;
	}

	.icon {
		width: 100vw;
		display: flex;
		justify-content: space-around;
	}

	.header_all {
		height: 520rpx;



		background: url('http://m.qpic.cn/psc?/V111yKEY0i00Yh/ruAMsa53pVQWN7FLK88i5q6IsUgYQAMB.XckC8Rqo8kujgf5rq5HkRuJsZaysJ*Bfh9VYkU9kbnxnlQBvl1d00s*KRjUAICxkMZtWqQQgeA!/b&bo=bQI*AW0CPwEDByI!&rf=viewer_4') no-repeat;
		background-size: 100% 99.9%;
	}

	.swiper {
		width: 100vw;
		height: 350rpx;
	}

	.imgs {
		object-fit: cover;
		width: 100%;
		height: 480rpx;
	}

	.search_class {
		background-color: unset !important;
	}

	.search {
		width: 90%;
		height: 110rpx;
		margin: 8rpx auto 0 auto;
	}

	.header {
		overflow: hidden;
		height: 120rpx;

		width: 100vw;
	}

	.content {
		padding-bottom: 130rpx;
	}
</style>
