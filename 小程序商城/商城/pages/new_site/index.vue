<template>
	<view class="my_wrap">
		<form @submit="submit" @reset="">
			<view class="input">
				<van-cell-group>
					<van-field :value='value_name' custom-style='border-bottom:2rpx solid #e5e5e5' name='name' label="收货人" placeholder="请输入联系人姓名" />
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

		},
		methods: {
			//提交表单
			submit(e) {
				// console.log(e.detail.value)
				let obj = e.detail.value
				obj.province = this.value1
				obj.city = this.value2
				obj.userId = parseInt(getApp().globalData.userId)
				obj.county = this.value3
				obj.address = this.value4
				console.log(e.detail)
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
				if (e.detail.value.switch == false) {
					obj.state = 0
				} else {
					obj.state = 1
				}
				console.log(obj)
				this.$myRequest({
					url: "/address/index",
					method: 'post',
					header: {
						'Content-Type': 'application/json'
					},
					data: obj,
				}).then((res) => {
					console.log(88888)
					if (res.code == 200) {
						uni.redirectTo({
							url: `../my_address/index?onLoad=1`,
						});
					}
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
