<template>
	<view class="my_wrap">
		<van-dialog id="van-dialog" />
		<van-tabs sticky @change="onChange" line-width='290' :active='active' type="card">
			<van-tab title="待付款">

				<view class="shop_wrap">
					<view v-if="no_data" class="no">
						<van-empty description="暂无订单信息" />
					</view>
					<view v-for='(item,index) in data' :style="index==0?'margin: 0rpx auto 0 auto;':''" :key='index' class="shop_list">
						<view v-for='(item1,index1) in item.products' :key='index1' class="shop_main">
							<view class="shop_img">
								<image class="shop_img" :src="item1.head_picture" mode=""></image>
							</view>
							<view class="shop_tit">
								{{item1.name}}
							</view>
							<view class="shop_price">
								<view class="price_1">
									￥{{item1.present_price}}
								</view>
								<view class="price_2">
									x{{item1.shop_num}}
								</view>
							</view>
						</view>
						<view class="shop_footer">
							<view class="f_text">
								实付金额：
							</view>
							<view class="f_text1">
								￥{{item.total_price}}
							</view>
							<view @click="delete1(item.order_num)" class="button_cancel">
								取消订单
							</view>
							<view @click="buy_shop(item.id)" class="button_payment">
								去支付
							</view>
						</view>
					</view>
				</view>
			</van-tab>
			<van-tab title="待发货">

				<view class="shop_wrap">
					<view v-if="no_data" class="no">
						<van-empty description="暂无订单信息" />
					</view>
					<view v-for='(item,index) in data' :key='index' class="shop_list">
						<view v-for='(item1,index1) in item.products' :key='index1' class="shop_main">
							<view class="shop_img">
								<image class="shop_img" :src="item1.head_picture" mode=""></image>
							</view>
							<view class="shop_tit">
								{{item1.name}}
							</view>
							<view class="shop_price">
								<view class="price_1">
									￥{{item1.present_price}}
								</view>
								<view class="price_2">
									x{{item1.shop_num}}
								</view>
							</view>
						</view>
						<view class="shop_footer">
							<view class="f_text">
								实付金额：
							</view>
							<view class="f_text1 margin">
								￥{{item.total_price}}
							</view>

							<view class="button_payment">
								待发货
							</view>
						</view>
					</view>
				</view>
			</van-tab>
			<van-tab title="待收货">

				<view class="shop_wrap">
					<view v-if="no_data" class="no">
						<van-empty description="暂无订单信息" />
					</view>
					<view v-for='(item,index) in data' :key='index' class="shop_list">
						<view v-for='(item1,index1)  in item.products' :key='index1' class="shop_main">
							<view class="shop_img">
								<image class="shop_img" :src="item1.head_picture" mode=""></image>
							</view>
							<view class="shop_tit">
								{{item1.name}}
							</view>
							<view class="shop_price">
								<view class="price_1">
									￥{{item1.present_price}}
								</view>
								<view class="price_2">
									x{{item1.shop_num}}
								</view>
							</view>
						</view>
						<view class="shop_footer">
							<view class="f_text">
								实付金额：
							</view>
							<view class="f_text1">
								￥{{item.total_price}}
							</view>
							<view class="button_cancel color">
								查看物流
							</view>
							<view @click="finsh_order(item.order_num)" class="button_payment">
								确认收货
							</view>
						</view>
					</view>
				</view>
			</van-tab>
			<van-tab title="已完成">

				<view class="shop_wrap">
					<view v-if="no_data" class="no">
						<van-empty description="暂无订单信息" />
					</view>
					<view v-for='(item,index) in data' :key='index' class="shop_list">
						<view v-for='(item1,index1) in item.products' :key='index1' class="shop_main">
							<view class="shop_img">
								<image class="shop_img" :src="item1.head_picture" mode=""></image>
							</view>
							<view class="shop_tit">
								{{item1.name}}
							</view>
							<view class="shop_price">
								<view class="price_1">
									￥{{item1.present_price}}
								</view>
								<view class="price_2">
									x{{item1.shop_num}}
								</view>
							</view>
						</view>
						<view class="shop_footer">
							<view class="f_text">
								实付金额：
							</view>
							<view class="f_text1 margin">
								￥{{item.total_price}}
							</view>

							<view class="button_payment">
								已完成
							</view>
						</view>
					</view>
				</view>
			</van-tab>
		</van-tabs>
		<tab active=1000></tab>
	</view>
</template>

<script>
	import tab from '../../components/tab.vue';
	import Dialog from '../../wxcomponents/dist/dialog/dialog';
	export default {
		components: {
			tab
		},
		data() {
			return {
				no_data: false,
				url_icon: '',
				url: '',
				data: '',
				active: 0
			}

		},

		onLoad(e) {
			console.log('ddddddddddddddddd')
			console.log(e)
			this.active = parseInt(e.active)
			this.get_order(this.active)
		},
		methods: {

			//调支付页面
			pay_ment(paySign, timeStamp, nonceStr, package1) {
				uni.requestPayment({
					paySign: paySign,
					timeStamp: timeStamp,
					nonceStr: nonceStr,
					package: package1,
					signType: 'MD5',
					success(res) {
						uni.redirectTo({
							url: `../order/order?active=${2}`,
							success: res => {},
							fail: (msg) => {}

						});
					},
					fail(res) {

					},
				})
			},
			//点击确认收货
			delete1(e) {
				Dialog.confirm({
						title: '取消订单',
						message: '确认收货完成？',
						asyncClose: false
					})
					.then(() => {
						this.finsh_order(e)
			
					})
					.catch(() => {
						Dialog.close();
					});
			},
			//点击确认收货
			finsh_order(e) {
				this.$myRequest({
					url: "/Userorder/finish_order",
					method: 'post',
					header: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					data: {
						order_num: e
					},
				}).then((res) => {
					this.get_order(parseInt(this.active))
					console.log(this.data)
					// console.log(res)

				}).catch((res) => {
					console.log(res)
				})
			},
			
			//点击支付商品
			buy_shop(id) {
				let that = this
				uni.login({
					success(res) {
						if (res.code) {
							//发起网络请求
							//支付
							let code = res.code
							that.$myRequest({
								url: "/orderpay/wxPay",
								data: {
									orderId: id,
									code
								},
								header: {
									'Content-Type': 'application/x-www-form-urlencoded'
								},
								method: 'post',
							}).then((res2) => {
								console.log(res2)
								that.pay_ment(res2.paySign, res2.timeStamp, res2.nonceStr, res2.package)

							}).catch((res) => {
								console.log(res)
								Notify('支付失败');
								return
							})


						}
					}
				})
			},
			//点击取消订单按钮
			delete1(e) {
				Dialog.confirm({
						title: '取消订单',
						message: '确认取消订单?',
						asyncClose: false
					})
					.then(() => {
						this.delete_order(e)

					})
					.catch(() => {
						Dialog.close();
					});
			},



			//取消订单
			delete_order(e) {
				this.$myRequest({
					url: "/Userorder/delete_order",
					method: 'post',
					header: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					data: {
						order_num: e
					},
				}).then((res) => {
					this.get_order(parseInt(this.active))
					console.log(this.data)
					// console.log(res)

				}).catch((res) => {
					console.log(res)
				})
			},
			//tabs变化
			onChange(event) {
				this.active = event.detail.name
			},
			//查询订单
			get_order(e) {
				let id = 1
				switch (e) {
					case 0:
						id = e
						break;
					case 1:
						id = e
						break;
					case 2:
						id = e
						break;
					case 3:
						id = e
						break;
					default:
						break;
				}
				this.$myRequest({
					url: "/Userorder/get_order",
					method: 'post',
					header: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					data: {
						userId: getApp().globalData.userId,
						state: id
					},
				}).then((res) => {
					this.data = res.data
					if (!this.data[0]) {
						this.no_data = true
					} else {
						this.no_data = false
					}
					console.log(this.data)
					// console.log(res)

				}).catch((res) => {
					console.log(res)
				})
			}

		},
		created() {
			this.url = getApp().globalData.url
			this.url_icon = getApp().globalData.imgs_icon
			// this.get_order(this.active)
		},
		watch: {
			active(val) {
				this.get_order(val)
			}
		}
	}
</script>

<style>
	.no {
		height: 95vh;
		width: 100vw;
	}

	.shop_price {
		padding-right: 50rpx;
		width: 150rpx;
	}

	.color {
		background-color: #2a82e4 !important;
	}

	.margin {
		margin-left: -200rpx;
	}

	.button_cancel {
		margin-top: 10rpx;
		font-size: 12.5px;
		height: 50rpx;
		border-radius: 35rpx;
		line-height: 50rpx;
		text-align: center;
		padding: 0 18rpx 0 18rpx;
		background-color: #808080;
		color: #fff;
		font-weight: bold;
	}

	.button_payment {
		margin-top: 10rpx;
		font-size: 12.5px;
		padding: 0 18rpx 0 18rpx;
		height: 50rpx;
		line-height: 50rpx;
		border-radius: 35rpx;
		text-align: center;
		background-color: #d43030;
		color: #fff;
		font-weight: bold;
	}

	.f_text1 {
		line-height: 60rpx;
		color: red;
		font-size: 15px;
		margin-right: 80rpx;
	}

	.f_text {
		line-height: 60rpx;
		font-weight: bold;
	}

	.shop_footer {
		justify-content: space-between;
		display: flex;
		padding-top: 49rpx;
		background-color: #fff;
	}

	.price_1 {
		text-align: left;
		color: red;
		font-size: 14px;

	}

	.price_2 {
		text-align: left;
		color: #989396;
		font-size: 14px;
		margin-top: 87rpx;

	}

	.shop_tit {

		width: 370rpx;
		padding: 0 15rpx 0 60rpx;
	}

	.shop_wrap {
		background-color: #f1f1f1;
		overflow: hidden;
		min-height: 95vh;

	}

	.shop_main {

		margin-top: 20rpx;
		background-color: #fff;

		display: flex;
	}

	.shop_list {


		background-color: #fff;
		width: 91.5vw;
		margin: 10rpx auto 0 auto;
		border-radius: 30rpx;
		padding: 35rpx 25rpx 24rpx 24rpx;

	}

	.shop_img {
		margin-top: -5rpx;
		width: 172rpx;
		height: 161rpx;
		object-fit: cover;
	}

	.van-tabs__scroll--card {

		margin: unset var(--padding-md, 16px) !important;

	}

	.my_wrap {
		min-height: 100vh;
		background-color: #f1f1f1;
		overflow: hidden;
		padding-bottom: 150rpx;
	}
</style>
