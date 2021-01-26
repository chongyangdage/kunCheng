<template>
	<view class="all">
		<van-search :value="value" placeholder="请输入搜索关键词" use-action-slot @:change="onChange">
			<view slot="action" @click="onSearch">搜索</view>
		</van-search>
		<view class="main_top shadow">
			<view class="img">
				<view class="main_tit">
					{{data.name}}
				</view>
				<image class="header_img" :src="data.image" mode=""></image>
			</view>
			<view class="main_phone">
				电话：{{data.phone}}
			</view>
			<view class="address">
				{{data.address}}
			</view>

			<view class="main_lable">
				<van-tag plain type="warning">{{data.label}}</van-tag>

			</view>
		</view>
		<view class="wrap">

			<view class="list">
				<van-sidebar :active-key="activeKey">
					<van-sidebar-item @click='kind_change($event,item.id)' v-for='(item,index) in shop_kind' :key='index' :title="item.name" />
				</van-sidebar>
			</view>
			<view class="main">

				<scroll-view lower-threshold='15' style="height: 100vh;" @scrolltolower='scrolltolower_g' scroll-y='true'>
					<view class="main_shop">

						<view v-if="dataShow[index]" v-for="(item,index) in shop_data" :key='index' class="shop_list">
							<image class="image" :src="item.head_picture" mode=""></image>
							<view class="shop_name">
								{{item.name}}
							</view>
						</view>

					</view>
				</scroll-view>
			</view>
		</view>
	</view>

</template>

<script>
	export default {
		components: {

		},
		data() {
			return {
				activeKey: 0,
				shop_data: [],
				dataShow: [],
				index: 0,
				value: '',
				store_id: '',
				shop_kind: [],
				data:''
			}

		},

		onLoad(e) {
			this.store_id = e.id
			this.get_store_kind()
			this.get_store_name()
		},
		watch: {

			shop_data(val) {

				let arr = [true, true, true, true, true, true, true, true, true, true, true, true] //默认为true的条数;	
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
			kind_change(e, e1) {
				console.log(e, e1)
				this.get_store(e1)
			},
			//搜索变化时
			onChange(e) {
				this.value = e.detail
			},
			//点击搜索
			onSearch() {
				console.log(this.value)
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
						Toast('此类没有更多商品了');
						return
					}
				}
			},
			//查询分类
			get_store_kind() {
				this.$myRequest({
					url: "Store/kind",
					data: {
						store_id: this.store_id
					},
					method: 'get',
				}).then((res) => {
					console.log(res)

					this.shop_kind = res.data
				this.get_store(res.data[0].id)
				}).catch((res) => {
					console.log(res)
				})
			},
			//查询店铺信息
			get_store_name() {
				this.$myRequest({
					url: "Store/name",
					data: {
						id: this.store_id
					},
					method: 'get',
				}).then((res) => {
					console.log(res,11111)
			
					this.data = res.data
					
				}).catch((res) => {
					console.log(res)
				})
			},

			//查询分类商品
			get_store(e) {
				this.$myRequest({
					url: "Store/kind_shop",
					data: {
						kind_id: e
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

		},
	}
</script>

<style>
	.all {
		height: 100vh;
		overflow: hidden;
	}

	.main_shop {
		display: flex;
		padding-left: 20rpx;
		padding-right: 20rpx;
		flex-wrap: wrap;
		justify-content: space-around;
	}

	.shop_list {
		margin-top: 63rpx;
		width: 149rpx;
	}

	.image {
		width: 149rpx;
		height: 158rpx;
		display: block;
	}

	.shop_name {
		width: 149rpx;
		text-align: center;
		line-height: 65rpx;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		font-size: 15px;
	}

	.address {
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.header_img {
		display: block;
		width: 106rpx;
		height: 106rpx;
		border-radius: 20rpx;
		position: absolute;
		right: 30rpx;
		top: 0rpx;
	}

	.img {
		width: 100%;
		position: relative;
		display: flex;


	}

	.main_phone {
		line-height: 45rpx;
		font-size: 15px;
		color: #aaa8a9;

	}

	.main_tit {

		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
		font-size: 24px;
		font-weight: bold;
		color: #000;
		line-height: 65rpx;

	}

	.main {
		flex-grow: 1;
	}

	.main_top {
		border-radius: 30rpx;
		width: 93vw;
		margin: 30rpx auto 20rpx auto;
		background-color: #fff;
		border: 2rpx solid #ccc;
		padding:15rpx;

	}

	.shadow {
		 box-shadow: 0 0 30rpx #C0C0C0;
	}

	.wrap {
		display: flex;
	}
</style>
