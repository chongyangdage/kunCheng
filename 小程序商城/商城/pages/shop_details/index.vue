<template>
	<view class="wrap">
		<view class="main_img">
			<image class="main_img" :src="data.head_picture"></image>
		</view>
		<view class="main_text">
			<view class="text1">
				<view class="text1_1">
					￥ {{data.present_price}}
				</view>

				<view class="text1_3">
					原价:{{data.original_price}}
				</view>
			</view>
			<view class="text2">
				{{data.name}}
			</view>
			<view class="text2 text_b">
				{{data.shop_describe}}
			</view>
		</view>
		<view class="shop_tit">
			—————— 宝贝详情 ——————
		</view>
		<view class="shop_imgs">
			<view class="imgs_list">
				<image mode="widthFix" class="imgs_list" :src="data.picture_details1"></image>
			</view>
			<view class="imgs_list">
				<image mode="widthFix" class="imgs_list" :src="data.picture_details2"></image>
			</view>
			<view class="imgs_list">
				<image mode="widthFix" class="imgs_list" :src="data.picture_details3"></image>
			</view>

		</view>
		<van-goods-action>
			<van-goods-action-icon @click='back_index' icon="wap-home" text="首页" />
			<van-goods-action-icon @click='click_shopCar' icon="cart" text="购物车" />
			<van-goods-action-button :disabled='data.stock==0' @click='add_shopCar' text="加入购物车" type="warning" />
			<van-goods-action-button :disabled='data.stock==0' @click='buy_shop' text="立即购买" />
		</van-goods-action>
		<van-popup round :show="show" closeable position="bottom" custom-style="height: 50%" @close="onClose">
			<view class="buy">
				<view class="buy_img">
					<image class="buy_img" :src="data.head_picture" mode=""></image>
				</view>
				<view class="buy_test">
					<view class="buy_price">
						￥ {{data.present_price}}
					</view>
					<view class="buy_num">
						<view class="num_tit">
							原价
						</view>
						<view class="num_main">
							{{data.original_price}}
						</view>
					</view>
					<view class="stepper">
						<view class="num">
							数量
						</view>
						<view class="num_q">
							<van-stepper :value="buy_num" @change="stepper_onChange" />
						</view>

					</view>

				</view>

			</view>
			<view class="button">
				<view @click="order_submit" class="but">
					立即购买
				</view>
			</view>
		</van-popup>
		<van-toast id="van-toast" />
	</view>
</template>

<script>
	import Toast from '../../wxcomponents/dist/toast/toast';
	export default {
		data() {
			return {
				buy_num: 1,
				id: '',
				data: '',
				url: '',
				show: false,
				num: 1,
			}

		},
		onLoad(e) {
			//商品id
			this.id = e.id
			console.log(e)
			this.creat_data(this.id)
		},
		methods: {

			//立即购买
			order_submit() {
				let total = this.data.present_price * this.num * 100
				let arr = []
				this.data.num = this.num
				arr.push(this.data)
				let data = JSON.stringify(arr)
				uni.reLaunch({
					url: `../submit_order/index?data=${data}&&total=${total}`,
					success: res => {
						console.log(res)
					},
					fail: (msg) => {
						console.log(msg)
					}

				});
			},
			//点击加入购物车
			add_shopCar() {
				this.$myRequest({
					url: "/Shopcar/index",
					data: {
						userId: getApp().globalData.userId,
						shopId: this.id,
						shopNum: 1
					},
					method: 'get'
				}).then((res) => {
					console.log(res)
					if (res.code == 200) {
						Toast.success('已加入购物车');
					}
				}).catch((res) => {
					console.log(res)
				})
			},
			//点击首页
			back_index() {
				uni.reLaunch({
					url: `../index/index`,
					success: res => {
						console.log(res)
					},
					fail: (msg) => {
						console.log(msg)
					}

				})
			},
			//步进器改变
			stepper_onChange(e) {
				console.log(e)
				this.num = e.detail
			},
			//点击购买
			buy_shop() {
				this.show = true
			},

			//详情所有数据
			creat_data(id) {
				this.$myRequest({
					url: "/Shop/findShopDetails",
					data: {
						id: id
					},
					method: 'get',
				}).then((res) => {
					console.log(res)
					this.data = res.data
				}).catch((res) => {
					console.log(res)
				})
			},
			//跳转购物车
			click_shopCar() {
				uni.navigateTo({
					url: `../shop_car/index`
				});
			},
			//点击退出图标
			onClose() {
				this.show = false
			},
		},
		created() {
			this.url = getApp().globalData.url

		},
	}
</script>

<style>
	.text_b {
		font-weight: unset !important;
		font-size: 17px !important;
		color: #999999;
	}

	.but {
		height: 80rpx;
		width: 70%;
		margin: 190rpx auto 0 auto;
		text-align: center;
		line-height: 80rpx;
		border-radius: 30rpx;
		background-color: #d43030;
		color: #fff;
		font-weight: bold;
	}

	.num {
		line-height: 60rpx;
	}

	.num_q {
		margin-left: 10rpx;
	}

	.stepper {
		display: flex;
		margin-top: 30rpx;
	}

	.buy_test {
		margin-left: 50rpx;
		margin-top: 50rpx;
	}

	.num_main {
		text-decoration: line-through;
		margin-left: 10rpx;
		line-height: 60rpx;
		color: red;
		font-size: 14px;
	}

	.num_tit {
		line-height: 60rpx;
		font-size: 14px;
	}

	.buy_img {
		margin-top: 30rpx;
		margin-left: 30rpx;
		width: 219rpx;
		height: 210rpx;
		object-fit: cover;
	}

	.buy_price {
		line-height: 80rpx;
		color: red;
		font-size: 23px;
		font-weight: bold;
	}

	.buy_num {
		display: flex;
	}

	.buy {
		display: flex;
	}

	.imgs_list {
		width: 100vw;

		object-fit: cover;
	}

	.shop_tit {
		background-color: #E5E5E5;
		color: #8b8b8b;
		font-size: 14px;
		text-align: center;
		height: 73rpx;
		line-height: 73rpx;
	}

	.text2 {
		margin-left: 13rpx;
		font-weight: bold;
		font-size: 18px;
		/* margin-top: 30rpx; */
		padding-bottom: 15rpx;
	}

	.text1 {
		height: 50rpx;
		margin-bottom: 13rpx;
		display: flex;
		margin-top: 26rpx;
	}

	.text1_1 {
		line-height: 50rpx;
		color: red;
		font-size: 17px;
	}

	.text1_2 {
		line-height: 50rpx;
		margin-left: 19rpx;
		color: #979797;
		font-size: 13px;
	}

	.text1_3 {
		line-height: 50rpx;
		text-decoration: line-through;
		color: #979797;
		font-size: 13px;
		margin-left: 140rpx;
	}

	.wrap {
		padding-bottom: 100rpx;
		background-color: #e5e5e5;
	}

	.main_img {
		width: 100vw;
		height: 755rpx;
		object-fit: cover;
	}

	.main_text {
		padding-left: 30rpx;
		padding-right: 30rpx;
		padding-bottom: 60rpx;
		overflow: hidden;
		height: 186rpx;
		border-radius: 30rpx;
		margin: 10rpx auto 0rpx auto;
		background-color: #fff;
	}
</style>
