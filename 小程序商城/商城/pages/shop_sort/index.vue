<template>
	<view class="content">
		<scroll-view lower-threshold='15' @scroll='onPageScroll_g' style="height: 100vh;" @scrolltolower='scrolltolower_g'
		 scroll-y='true'>
			<view @click="search" :class="issearch==true?'class_fix':'search'">
				<van-search shape='round' custom-class='search_class' :value="search_value" placeholder="请输入搜索所需商品" />
			</view>
			<van-tabs @click='click_kind()' animated swipeable swipe-threshold='5' :active="active">
				<van-tab :name='item.id' v-for='(item,index) in Categories_data' :key='index' :title="item.shop_name">
					<view class="shop_wrap">
						<view  v-if="dataShow[index]" v-for="(item,index) in shop_data" :key='index' class="shop_list">
							<image class="shop_img" :src="item.head_picture" mode=""></image>
							<view class="shop_tit">
								{{item.name}}
							</view>
							<view class="shop_price_wrap">
								<view class="now_price">
									${{item.original_price}}
								</view>
								<text class="old_price">
									${{item.present_price}}
								</text>
								<image class="gouwu" src="../../static/gouwuche.svg" mode=""></image>
							</view>
						</view>

					</view>
				</van-tab>
			</van-tabs>
		</scroll-view>
		<van-toast id="van-toast" />
	</view>
</template>

<script>
	
	import Toast from '../../wxcomponents/dist/toast/toast';
	export default {
		data() {
			return {
				dataShow: [],
				issearch: false,
				active: 0,
				Categories_data: [],
				shop_data: [],
				index:0
			}
		},
		onLoad() {

		},
		watch: {
			shop_data(val) {
				
				let arr = [true, true, true, true, true, true] //默认为true的条数;	
				let falseArr;
				if (val.length > 6) {
					falseArr = new Array(val.length - 6).fill(false);
				}
				arr = arr.concat(falseArr);
				
				this.dataShow = arr;
				console.log(this.dataShow)
			},
		
		},
		methods: {
			//跳转搜索页
			search(){
				uni.redirectTo({
					url: "../shop_search/index",
				});
			},
			//滚动条到底部
			scrolltolower_g(e) {
				console.log('到底了')
				this.index += 6;
				console.log(this.index)
				let dataShow = this.dataShow;
				for (let i = 4; i < this.index + 4; i++) {
					if (!dataShow[i]) {
						this.dataShow[i] = true
					}else if(i>this.shop_data.length){
						Toast('没有更多商品了');
						return
					}
				}
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
			//商品分类查询
			create_1() {
				this.$myRequest({
					url: "Categories/index",
					method: 'get',
				}).then((res) => {

					console.log(res)
					this.Categories_data = res.data
					this.click_kind_create(res.data[0].id)
				}).catch((res) => {
					console.log(res)
				})
			},
			//根据商品分类查询商品
			click_kind(e) {
				this.$myRequest({
					url: "Shop/findShopById",
					data: {
						sort_id: e.detail.name
					},
					method: 'get',
				}).then((res) => {
					console.log(res)
					this.shop_data = res.data
				}).catch((res) => {
					console.log(res)
				})
			},
			//根据商品分类查询第一类商品
			click_kind_create(e) {
				this.$myRequest({
					url: "Shop/findShopById",
					data: {
						sort_id: e
					},
					method: 'get',
				}).then((res) => {
					console.log(res)
					this.shop_data = res.data
				}).catch((res) => {
					console.log(res)
				})
			}
		},
		
		created() {
			this.create_1()
		}
	}
</script>

<style>
	.content{
		padding-bottom: 30rpx;
	}
	.class_fix {
		position: fixed;
		top: -5rpx;
		z-index: 100000;
		
		right: auto;
		background: #fff;
		z-index: 10000000000;
		left: auto;
		width: 100vw;
		height: 110rpx;
		margin: 0rpx auto 0 auto;
	}
	.gouwu {
		width: 57rpx;
		height: 57rpx;
	}

	.now_price {
		font-size: 17px;
		color: red;
		font-weight: bold;
	}

	.old_price {
		margin-left: -120rpx;
		line-height: 48rpx;
		font-size: 15px;
		color: #b6b6b6;
		text-decoration: line-through;
	}

	.shop_price_wrap {
		display: flex;
		justify-content: space-between;
	}

	.shop_tit {
		font-weight: bold;
		height: 79rpx;
		line-height: 79rpx;
		text-align: left;
	}

	.shop_wrap {

		padding-left: 28rpx;
		padding-right: 28rpx;
		display: flex;
		flex-wrap: wrap;
		justify-content: space-between;
	}

	.shop_list {
		margin-top: 40rpx;
		width: 320rpx;
	}

	.shop_img {
		width: 320rpx;
		height: 320rpx;
		object-fit: cover;
	}

	.search_class {
		background-color: unset !important;
	}

	.search {
		width: 90%;
		height: 110rpx;
		margin: 8rpx auto 0 auto;
	}
</style>
