<template>
	<view class="my_wrap">
		<van-radio-group :value="radio" @change="onChange">
			<view v-for="(item,index) in address_data" :style="index==0?'margin-top: 0rpx; padding-top:40rpx;':''" :key='index'
			 class="main_list">
				<view class="top">
					<view class="top_l">
						{{item.name}}
					</view>
					<view class="top_r">
						{{item.phone}}
					</view>
				</view>
				<view class="middle">
					{{item.province}}-{{item.city}}-{{item.county}}-{{item.address}}-{{item.address_details}}
				</view>
				<view class="bottom">
					<view class="bottom_1">
						<van-radio checked-color='#fb401e' :name="item.id">默认地址</van-radio>
					</view>
					<view class="bottom_2 margin">
						<view class="edit_icon">
							<van-icon size='21px' name="edit" />
						</view>

						<view @click="edit_car(item.id)" class="edit">
							编辑
						</view>
					</view>
					<view class="bottom_2 margin_2" @click="delete_car(item.id)">

						<view class="edit_icon">
							<van-icon size='21px' name="delete" />
						</view>

						<view class="edit">
							删除
						</view>
					</view>
				</view>
			</view>
		</van-radio-group>
		<view @click="add_address" class="button_1">
			<view class="jia_wrap">
				+
			</view>
			<view class="jia_text">
				请填写新地址
			</view>

		</view>


		<van-dialog id="van-dialog" />
		<van-notify id="van-notify" />
	</view>
</template>

<script>
	import Notify from '../../wxcomponents/dist/notify/notify';
	import Dialog from '../../wxcomponents/dist/dialog/dialog';
	export default {

		data() {
			return {

				radio: '',
				id: '', //地址id
				address_data: ''
			}

		},

		onLoad(e) {
			if (e.onLoad == 1) {
				this.onLoad()
			}

		},
		methods: {
			//添加收货地址
			add_address() {
				uni.redirectTo({
					url: `../new_site/index`
				});
			},
			//编辑地址
			edit_car(id) {
				uni.redirectTo({
					url: `../site/index?id=${id}`,
				});
			},
			//选择默认地址改变
			onChange(event) {
				this.radio = event.detail
				this.edit_address()
			},
			//修改默认地址
			edit_address() {

				this.$myRequest({
					url: "/address/revise_address_default",
					method: 'post',
					header: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					data: {
						id: this.radio
					},
				}).then((res) => {
					console.log(88888)


					console.log(res)
				}).catch((res) => {
					console.log(res)
				})
			},
			//删除地址
			delete_car(id) {
				Dialog.confirm({
						title: '提示',
						message: '是否删除该地址？',
						asyncClose: false
					})
					.then(() => {
						this.$myRequest({
							url: "/address/delete_address_ById",
							method: 'post',
							header: {
								'Content-Type': 'application/x-www-form-urlencoded'
							},
							data: {
								id
							},
						}).then((res) => {
							Notify('删除成功');
							this.get_address()

							console.log(res)
						}).catch((res) => {
							console.log(res)
						})
					})
					.catch(() => {
						Dialog.close();
					});

			},

			//查询所有地址
			get_address() {

				this.$myRequest({
					url: "/address/get_address_all",
					method: 'get',
					data: {
						userId: getApp().globalData.userId
					},
				}).then((res) => {
					this.address_data = res.data
					for (let i = 0; i < res.data.length; i++) {
						if (res.data[i].state == 1) {
							this.radio = res.data[i].id
						}
					}
					console.log(res)
				}).catch((res) => {
					console.log(res)
				})
			},

		},
		created() {
			this.url = getApp().globalData.url
			this.get_address()
		},
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
		margin: 50rpx auto 10rpx auto;
		line-height: 88rpx;


		text-align: center;
		font-size: 18px;
		background-color: #fff;
	}

	.button {
		width: 564rpx;
		height: 118rpx;
		border-radius: 50rpx;
		margin: 210rpx auto 100rpx auto;
		line-height: 118rpx;
		color: #fff;
		font-weight: bold;
		text-align: center;
		font-size: 18px;
		background-color: #ff4723;
	}

	.main_list {
		margin-top: 60rpx;
		background-color: #fff;
	}

	.margin_2 {
		margin-left: 35rpx;
	}

	.margin {
		margin-left: 230rpx;
	}

	.bottom_2 {
		display: flex;
	}

	.bottom {
		padding-left: 38rpx;
		padding-right: 38rpx;
		padding-top: 30rpx;
		padding-bottom: 30rpx;
		display: flex;
	}

	.middle {
		padding-left: 38rpx;
		padding-right: 38rpx;
		padding-top: 30rpx;
		padding-bottom: 30rpx;
		font-size: 13px;
		color: #bebebe;
		border-bottom: 2rpx solid #bebebe;
	}

	.top_l {
		height: 74rpx;
		line-height: 74rpx;
		font-weight: bold;
	}

	.top_r {
		height: 74rpx;
		line-height: 74rpx;
		font-weight: bold;
	}

	.top {
		padding-left: 38rpx;
		padding-right: 38rpx;
		display: flex;
		justify-content: space-between;
	}

	.my_wrap {

		overflow: hidden;
		min-height: 100vh;
		background-color: #f7f7f7;
	}
</style>
