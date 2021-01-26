<template>
	<view class="wrap">
		<scroll-view lower-threshold='15' style="height: 100vh;" @scrolltolower='scrolltolower_g' scroll-y='true'>
			<view @click="to_shop(item.id)" v-if="dataShow[index]" v-for="(item,index) in shop_data" :key='index' class="shop_list">
				<view class="img">
					<image class="img" :src="item.image" mode=""></image>
				</view>
				<view class="text">
					<view class="shop_name">
						{{item.name}}
					</view>
					<view class="address">
						<view class="address_1">
							{{item.address}}
						</view>
						<view class="gap">
							{{item.gap}}
						</view>
					</view>
					<view class="describe">
						<van-tag type="warning">{{item.label}}</van-tag>
					</view>
				</view>
				<tab active=1></tab>
			</view>
		</scroll-view>
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
				shop_data: [],
				dataShow: [],
				index: 0,
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
			//进去店铺
			to_shop(e) {
				uni.redirectTo({
					url: `../store_shop/index?id=${e}`,
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
					} else if (i > this.shop_data.length) {
						Toast('没有更多店铺了');
						return
					}
				}
			},
			//查询所有店铺
			get_store() {
				this.$myRequest({
					url: "Store/index",
					data: {
						userId: getApp().globalData.userId
					},
					method: 'get',
				}).then((res) => {
					console.log(res)

					this.shop_data = res.data
				}).catch((res) => {
					console.log(res)
				})
			},


		},
		created() {
			this.get_store()
		},
	}
</script>

<style>
	.shop_name {
		height: 90rpx;
		line-height: 90rpx;
		font-weight: bold;
		font-size: 19px;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.text {
		flex-grow: 1;
		margin-left: 17rpx;
	}

	.wrap {
		background-color: #f6f4f5;
		padding-bottom: 120rpx;
	}

	.img {
		width: 210rpx;
		height: 170rpx;
		border-radius: 30rpx;
		overflow: hidden;
	}

	.shop_list {
		padding: 22rpx 30rpx 27rpx 30rpx;
		display: flex;
		width: 87vw;
		height: 247rpx;
		border-radius: 80rpx;
		background-color: #fff;
		margin: 15rpx auto 0 auto;
	}

	.address {

		width: 100%;
		position: relative;
		display: flex;
	}

	.address_1 {
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		width: 350rpx;
		line-height: 70rpx;
		height: 70rpx;
		color: #9b999a;
		font-size: 14px;
	}

	.gap {
		position: absolute;
		right: 0;
		top: 0rpx;
		line-height: 70rpx;
		height: 70rpx;
		color: #9b999a;
		font-size: 14px;
	}
</style>
