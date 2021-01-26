<template>
	<view class="wrap_car">
		<view v-if="isShow_address==false?true:false" @click="add_address" class="button_1">
			<view class="jia_wrap">
				+
			</view>
			<view class="jia_text">
				请填写收货地址
			</view>

		</view>
		<view v-if='isShow_address' @click="address" class="top_wrap">
			<view class="t1">
				<view class="icon">
					<van-icon size='28' color='red' name="location" />
				</view>
				<view class="text">
					收货人：{{data_address.name}}
				</view>
			</view>
			<view class="t2">
				手机号码：{{data_address.phone}}
			</view>
			<view class="t3">
				收货地址：{{data_address.province}}-{{data_address.city}}-{{data_address.county}}-{{data_address.address}}-{{data_address.address_details}}
			</view>
		</view>
		<view class="shop_main_wrap">


			<view v-for="(item,index) in data" :key='index' class="shops_list">
				<view class="imgs">
					<view class="imgs_l">
						<image class="imgs_l" :src="item.head_picture" mode=""></image>
					</view>
					<view class="imgs_r">
						<view class="top">
							<view class="r_1">
								{{item.name}}
							</view>
							<view class="r_3_1">
								￥ {{item.present_price}}
							</view>
						</view>

						<view class="r_3">

							<view class="r_3_2">
								<van-stepper :name='index' :value="item.num" @minus='minus(item.id,index)' @plus='plus(item.id,index)' @change="onChange_num" />
							</view>
						</view>
					</view>
				</view>

			</view>

		</view>
		<view class="f">
			<van-submit-bar :price="total" button-text="订单支付" @submit="onClickButton">
			</van-submit-bar>
		</view>
		<van-notify id="van-notify" />
	</view>

	</view>
</template>

<script>
	import Notify from '../../wxcomponents/dist/notify/notify';
	export default {
		data() {
			return {
				isShow_address: true,
				index: '',
				data_address: '',
				total: '',
				value: '',
				checked: false,
				data: '',
				url: '',
				shop_num: ''
			}

		},
		onLoad(e) {
			this.get_address()
			// console.log(e.total)
			if (e != undefined) {
				this.total = parseFloat(e.total)
				console.log(this.total)
			}
			if (e != undefined) {
				let data = JSON.parse(e.data)
				this.data = data
				console.log(this.data)
			}




		},
		methods: {
			//跳转填写收货地址
			add_address() {
				uni.navigateTo({
					url: "../site/index?back=true",
					success: res => {
						console.log(3333)
					},
					fail: (msg) => {
						console.log(msg)
					}

				});
			},
			//步进器变化
			onChange_num(e) {
				let that = this
				setTimeout(function() {
					console.log(that.index)
					that.data[that.index].num = e.detail
				}, 100)

				console.log(this.data);
				this.num = e.detail
				console.log(e);
			},
			//提交订单
			onClickButton(e) {
				if (this.isShow_address == false) {
					Notify({
						type: 'warning',
						message: "请添加邮寄地址"
					});
					return
				}
				let arr = []
				console.log(this.data,'lllllllll')
				for (let i = 0; i < this.data.length; i++) {
					let obj1 = {}
					obj1.productId =  this.data[i].id
					obj1.buyNumber = this.data[i].num
					obj1.subtotal = parseInt(this.data[i].num * this.data[i].present_price)
					arr.push(obj1)
					console.log(arr,'ggggggggggg')
				}
				let obj = {
					userId: getApp().globalData.userId,
					receiverAddressId: this.data_address.id,
					payTotal: this.total/100,
					orderSaveParams: JSON.stringify(arr)
				}
				console.log(obj, 6666666)
				this.$myRequest({
					url: "Userorder/create_order",
					data: obj,
					header: {
						'Content-Type': 'application/json'
					},
					method: 'post',
				}).then((res1) => {
					console.log(res1,'这个是eee')

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
										orderId: res1.data.id,
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


							} else {
								console.log('登录失败！' + res.errMsg)
							}
						}
					})



				}).catch((res) => {
					console.log(res)
					Notify('支付失败');
					return
				})

				console.log(this.data)
			},

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
							url: `../order/order?active=${1}`,
							success: res => {},
							fail: (msg) => {}

						});
					},
					fail(res) {

					},
				})
			},
			//步进器减少
			minus(e, e1) {
				console.log(e1)
				this.index = e1
				let total = this.total

				for (let j = 0; j < this.data.length; j++) {
					if (e == this.data[j].id) {
						total -= parseFloat(this.data[j].present_price) * 100
					}
				}


				this.total = total
			},
			//步进器增加
			plus(e, e1) {
				this.index = e1
				console.log(e1)
				let total = this.total

				for (let j = 0; j < this.data.length; j++) {
					if (e == this.data[j].id) {
						total += parseFloat(this.data[j].present_price) * 100
					}
				}


				this.total = total
			},
			//跳转编辑地址
			address() {
				uni.navigateTo({
					url: `../order_address/index`
				});
			},
			//默认地址数据
			get_address() {
				this.$myRequest({
					url: "/address/get_address",
					data: {
						userId: getApp().globalData.userId
					},
					method: 'get',
				}).then((res) => {
					console.log(res)
					if (res.data == null) {
						this.isShow_address = false
					} else {
						this.isShow_address = true
					}
					this.data_address = res.data
					console.log(this.data_address, 444444)
				}).catch((res) => {
					console.log(res)
				})
			},

		},
		created() {
			this.url = getApp().globalData.url

			this.get_address()


		},
		watch: {

		}
	}
</script>

<style>
	.jia_wrap {
		width: 50rpx;
		height: 50rpx;
		line-height: 50rpx;
		color: #fff;
		font-weight: bold;
		margin-top: 18rpx;
		margin-left: 40rpx;
		border-radius: 50%;
		background-color: red;
	}

	.jia_text {
		font-size: 16px;
		margin-left: 30rpx;
	}

	.button_1 {
		display: flex;
		width: 730rpx;
		height: 88rpx;
		border-radius: 50rpx;
		margin: 10rpx auto 10rpx auto;
		line-height: 88rpx;


		text-align: center;
		font-size: 18px;
		background-color: #fff;
	}

	.icon {
		margin-top: 6rpx;
	}

	.t1 {

		display: flex;
	}

	.text {
		margin-left: 15rpx;
		line-height: 60rpx;

	}

	.t3 {
		margin-left: 13rpx;
		line-height: 60rpx;
		padding-left: 60rpx;
		font-size: 14px;
	}

	.t2 {
		margin-left: 13rpx;
		line-height: 60rpx;
		padding-left: 60rpx;

	}

	.top_wrap {
		margin-bottom: 170rpx;
		width: 700rpx;
		background-color: #fff;
		margin: 15rpx auto 0 auto;
		border-radius: 35rpx;
		padding-top: 40rpx;
		padding-left: 30rpx;
		padding-bottom: 40rpx;
	}

	.van-submit-bar__text {

		flex: 1;
		text-align: unset !important;
		color: #323233;
		color: var(--submit-bar-text-color, #323233);
		padding-right: 12px;
		padding-right: var(--padding-sm, 12px);

	}

	.f {
		margin-top: 200rpx;
		width: 100vw;
	}

	.top {
		display: flex;
		justify-content: space-between;
	}

	.r_1 {
		line-height: 54rpx;
		width: 200rpx;
	}

	.r_2 {
		line-height: 54rpx;
	}

	.choose {
		margin-top: 60rpx;
		margin-right: 30rpx;
	}

	.r_3_1 {
		line-height: 54rpx;
		color: #000;
		margin-right: 100rpx;
	}

	.r_3_2 {
		color: #525355;
		font-size: 13px;
		margin-top: 50rpx;
		margin-left: 300rpx;
	}

	.r_3 {
		display: flex;
		margin-top: 29rpx;
	}

	.imgs_l {
		width: 174rpx;
		height: 174rpx;
		object-fit: cover;
	}

	.shops_list {
		width: 700rpx;
		background-color: #fff;
		margin: 15rpx auto 0 auto;
		border-radius: 20rpx;
		display: flex;
		padding-top: 55rpx;
		padding-left: 30rpx;
		padding-bottom: 55rpx;
	}

	.imgs {




		display: flex;

	}

	.no_shop {
		margin: 45% auto 0 auto;
	}

	.text1 {
		height: 80rpx;
		line-height: 80rpx;
		width: 100%;
		text-align: center;
	}

	.text2 {
		height: 80rpx;
		line-height: 80rpx;
		width: 100%;
		text-align: center;
	}

	.imgs_r {
		margin-left: 25rpx;
	}

	.button {
		width: 254rpx;
		height: 104rpx;
		line-height: 104rpx;
		text-align: center;
		border-radius: 30rpx;
		margin: 30rpx auto 0 auto;
		background-color: #d43030;
		color: #fff;
		font-weight: bold;
		font-size: 18px;
	}

	.wrap_car {
		overflow: hidden;
		min-height: 100vh;
		width: 100vw;
		background-color: #E5E5E5;
	}
</style>
