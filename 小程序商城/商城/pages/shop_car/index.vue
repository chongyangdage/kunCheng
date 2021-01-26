<template>
	<view class="wrap_car">
		<view v-if="show_shopCar" class="no_shop">
			<view class="text1">
				您没有相关订单
			</view>
			<view class="text2">
				可以去看看有哪些需要买的
			</view>
			<view @click="to_index" class="button">
				随便逛逛
			</view>
		</view>
		<view v-if="show_shopCar==false?true:false" class="shop_main_wrap">
			<van-checkbox-group :value="result" @change="onChange">

				<view v-for="(item,index) in data" :key='index' class="shops_list">

					<view class="choose">
						<van-checkbox :name="item.id"></van-checkbox>
					</view>
					<view class="imgs">
						<view class="imgs_l">
							<image class="imgs_l" :src="item.head_picture" mode=""></image>
						</view>
						<view class="imgs_r">
							<view class="r_1">
								{{item.name}}
							</view>
							<view class="r_3">
								<view class="r_3_1">
									￥ {{item.present_price}}
								</view>
								<view class="r_3_2">
									x{{item.num}}
								</view>
							</view>
						</view>
					</view>

				</view>
			</van-checkbox-group>
		</view>
		<van-submit-bar v-if="show_shopCar==false?true:false" :price="total" button-text="提交订单" @submit="onClickButton">
			<!-- <van-tag type="primary">标签</van-tag> -->
			<view class="box">
				<van-checkbox @change='check' :value="checked" icon-size="25px">全选</van-checkbox>
			</view>
		</van-submit-bar>
		<van-toast id="van-toast" />
		<tab active=2></tab>
	</view>

	</view>
</template>

<script>
	import tab from '../../components/tab.vue';
	import Toast from '../../wxcomponents/dist/toast/toast';
	export default {
		components:{
			tab
		},
		data() {
			return {
				show_shopCar: false,
				total: 0,
				result: [],
				checked: false,
				data: '',
				url: ''
			}

		},
		onLoad() {

		},
		methods: {

			//没有订单，跳转index
			to_index() {
				uni.redirectTo({
					url: `../index/index`,
					success: res => {
						console.log(res)
					},
					fail: (msg) => {
						console.log(msg)
					}

				});
			},
			//提交订单
			onClickButton(e) {
				if (!this.result[0]) {
					Toast.fail({
						message: '请选择商品',
						position: 'bottom'
					});
					return
				}
				let arr = []
				for (let i = 0; i < this.result.length; i++) {
					for (let j = 0; j < this.data.length; j++) {
						if (this.result[i] == this.data[j].id) {
							arr.push(this.data[j])
						}
					}
				}

				console.log(arr)

				let data = JSON.stringify(arr)
				uni.navigateTo({
					url: `../submit_order/index?data=${data}&&total=${this.total}`,
					success: res => {
						console.log(res)
					},
					fail: (msg) => {
						console.log(msg)
					}

				});

			},
			//查询所有商品
			get_data() {
				this.$myRequest({
					url: "Shopcar/allShopCar",
					data: {
						userId: getApp().globalData.userId
					},
					method: 'get',
				}).then((res) => {
					console.log(res)
					if (!res.data[0]) {
						this.show_shopCar = true
					}
					this.data = res.data
				}).catch((res) => {
					console.log(res)
				})
			},
			//全选
			check(e) {
				this.checked = e.detail
				console.log(e.detail)
				if (e.detail == true) {
					let arr = []
					for (let i = 0; i < this.data.length; i++) {
						arr.push(String(this.data[i].id))
					}
					this.result = arr
					console.log(this.result)
				} else {
					let arr = []
					this.result = arr
				}

			},
			//选择改变
			onChange(e) {


				this.result = e.detail
				console.log(e)
				console.log(this.result)
			}

		},
		created() {
			this.url = getApp().globalData.url
			this.get_data()
		},
		watch: {
			result(val) {
				console.log(val)
				let total = 0
				for (let i = 0; i < val.length; i++) {
					for (let j = 0; j < this.data.length; j++) {
						if (val[i] == this.data[j].id) {
							total += parseFloat(this.data[j].present_price) * parseFloat(this.data[j].num)
						}
					}
				};

				this.total = total * 100
				console.log(this.total)
			}
		}
	}
</script>

<style>
	.r_1 {
		line-height: 54rpx;
	}

	.r_2 {
		line-height: 54rpx;
	}

	.choose {
		margin-top: 60rpx;
		margin-right: 30rpx;
	}

	.r_3_1 {
		color: red;
		font-weight: bold;
	}

	.r_3_2 {
		color: #525355;
		font-size: 13px;
		margin-left: 157rpx;
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
