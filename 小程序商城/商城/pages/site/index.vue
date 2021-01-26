<template>
	<view class="my_wrap">
		<form @submit="submit_2" @reset="">



			<view class="input">
				<van-cell-group>
					<van-field :value='value_name' custom-style='border-bottom:2rpx solid #e5e5e5' name='name' label="收货人"
					 placeholder="请输入联系人姓名" />
					<van-field :value='value_phone' custom-style='border-bottom:2rpx solid #e5e5e5' name='phone' label="联系电话"
					 placeholder="请输入手机号" />
					<view @click='choose_city' class="choose_address">
						<!-- <van-field label="所在地区" input-class='text_class'  readonly :value='address' placeholder="请选择地区"  /> -->
						<view class="choose_left">
							所在地区
						</view>
						<view class="choose_value">
							<view :class="value1=='请选择地区'?'color':''" class="v_1">
								{{value1}}
							</view>
							<view class="v_1">
								{{value2}}
							</view>
							<view class="v_1">
								{{value3}}
							</view>
							<view class="v_1">
								{{value4}}
							</view>
						</view>
					</view>
					<view @click='choose_city' class="icon_arrow">
						<van-icon size='23' custom-style='margin-top:22rpx;margin-left:125rpx;' color='#e5e5e5' name="arrow" />
					</view>
					<van-field name='address_details' custom-style='border-bottom:2rpx solid #e5e5e5' :value='value_details' label="详细地址"
					 type="textarea" :autosize='{  minHeight: 60 }' placeholder="请输入详细地址信息,如道路,门牌号,小区,楼栋号,单元室等" />
				</van-cell-group>
				<view class="address">
					<view class="address_text">
						设置默认地址
					</view>
					<view class="address_button">
						<van-switch name='switch' custom-class='address_b' @change='onChange' :checked="checked" active-color="#07c160"
						 inactive-color="#ee0a24" />
					</view>
				</view>
				<view class="footer">
					<van-popup closeable position='bottom' round custom-style="height: 40%" :show="popup" @close="onClose">
						<ehPicker @conceal="conceal" v-if="true" />

					</van-popup>

				</view>
			</view>
			<view class="footer_b">
				<van-button round custom-class='footer_b' form-type='submit' type="primary" size="large">保存</van-button>
			</view>

		</form>
		<van-notify id="van-notify" />
	</view> 
</template>

<script>
	import ehPicker from '../../components/components/erha-picker/erha-picker.vue';
	import Notify from '../../wxcomponents/dist/notify/notify';
	export default {
		components: {
			ehPicker
		},
		data() {
			return {
				back: '',
				value_name: '',
				value_phone: '',
				value1: '请选择地区',
				value2: '',
				value3: '',
				value4: '',
				popup: false,
				id: '',
				checked: false,
				address: '',
				value_details: ''
			}

		},

		onLoad(e) {
			console.log(e)
			this.id = e.id
			console.log(this.id)
			this.edit_address(e.id)
			if (e.back == 'true') {
				this.back = 1
			}
		},
		methods: {
			//提交表单
			submit_2(e) {
				console.log('ddddddd')
				// console.log(e.detail.value)
				let obj = e.detail.value
				
				obj.id = parseInt(this.id)
				obj.province = this.value1
				obj.city = this.value2
				obj.userId = parseInt(getApp().globalData.userId)
				obj.county = this.value3
				obj.address = this.value4
				console.log(obj, 'fffff')
				if (obj.consignee == '') {

					Notify({
						type: 'warning',
						message: "请输入姓名"
					});
					return
				} else if (obj['phone'] == '') {
					Notify({
						type: 'warning',
						message: "请输入手机号"
					});
					return
				} else if (obj['province'] == '') {
					Notify({
						type: 'warning',
						message: "请选择地区"
					});
					return
				} else if (obj['address_details'] == '') {
					Notify({
						type: 'warning',
						message: "请输入详细地址"
					});
					return
				}





				if (obj['name'].length > 5) {
					Notify({
						type: 'warning',
						message: "姓名长度不能大于5"
					});
					return
				} else if (obj['phone'].length > 11) {
					Notify({
						type: 'warning',
						message: "手机号长度不能超过11位"
					});
					return
				}
				console.log(e.detail)
				if (e.detail.value.switch == false) {
					obj.state = 0
				} else {
					obj.state = 1
				}
				this.$myRequest({
					url: "/address/uolode_address_ById",
					method: 'post',
					header: {
						'Content-Type': 'application/json'
					},
					data: obj,
				}).then((res) => {
					console.log(88888)
					if (res.code == 200) {
						if (this.back == 1) {
							let pages = getCurrentPages(); // 当前页面
							let beforePage = pages[pages.length - 2]; // 前一个页面
							uni.navigateBack({

								success: function() {
									beforePage.onLoad(); // 执行前一个页面的onLoad方法
								},
							})
						} else {
							uni.redirectTo({
								url: `../my_address/index`,

							});
						}

					}


					console.log(res)
				}).catch((res) => {
					console.log(res)
				})
			},
			//根据id查地址
			edit_address(id) {

				this.$myRequest({
					url: "/address/get_address_ById",
					method: 'post',
					header: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					data: {
						id
					},
				}).then((res) => {
					this.data_one = res.data
					if (res.data.state == 0) {
						this.checked = false
					} else {
						this.checked = true
					}
					this.value1 = res.data.province
					this.value2 = res.data.city
					this.value3 = res.data.county
					this.value4 = res.data.address
					this.value_details = res.data.address_details
					this.value_name = res.data.name
					this.value_phone = res.data.phone
					console.log(res)
				}).catch((res) => {
					console.log(res)
				})
			},

			//弹出层
			onClose() {
				this.popup = false
			},
			//点击选择城市
			choose_city() {
				this.popup = true
			},
			//省市区选择
			conceal(param) {
				// 获取到传过来的 省 市 区 县数据
				console.log(param);
				this.value1 = param.province
				this.value2 = param.city
				this.value3 = param.area
				this.value4 = param.street
				// 选择完毕后隐藏
				this.popup = false;

			},
			//是否设置默认地址
			onChange({
				detail
			}) {
				// 需要手动对 checked 状态进行更新
				console.log(detail)
				this.checked = detail
			},


		},
		created() {

		},
	}
</script>

<style>
	.footer_b {
		width: 400rpx;
		margin: 0 auto;
	}

	.color {
		color: #a6a6a6;
	}

	.choose_value {
		margin-left: 70rpx;
		line-height: 90rpx;
	}

	.choose_left {
		color: #a6a6a6;
		margin-left: 32rpx;
		line-height: 90rpx;
	}

	.text_class {
		font-size: 13px;

	}

	.footer {
		height: 100rpx;
		margin-top: 60rpx;
	}

	.address_b {
		margin-top: -19rpx;
	}

	.address_button {
		margin-top: 6rpx;
	}

	.address {

		overflow: hidden;
		display: flex;
		justify-content: space-between;
		background-color: #fff;
		padding-left: 34rpx;
		padding-right: 34rpx;
		padding-top: 40rpx;
		padding-bottom: 30rpx;
	}

	.icon_arrow {
		background-color: #fff;
		/* flex-grow: 1; */
		position: absolute;
		right: 20rpx;
		top: 180rpx;
	}

	.choose_address {
		background-color: #fff;
		display: flex;
		border-bottom: 2rpx solid #e5e5e5;

	}

	.input {
		background-color: #E5E5E5;

	}

	.my_wrap {
		background-color: #e5e5e5;
		min-height: 100vh;
	}
</style>
