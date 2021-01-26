<template name='tab'>
	<view class="wrap">
		<van-tabbar :active="active" @change="onChange">
			<van-tabbar-item icon="wap-home">首页</van-tabbar-item>
			<van-tabbar-item icon="shop">商铺</van-tabbar-item>
			<van-tabbar-item icon="shopping-cart">购物车</van-tabbar-item>
			<van-tabbar-item icon="manager">我的</van-tabbar-item>
		</van-tabbar>
	</view>
</template>

<script>
	export default {
		name: 'tab',
		data() {
			return {

			}

		},
		props: {
			active: {

				type: Number, //多种类型随意搭配

				value: 1

			}
		},
		onLoad() {

		},
		methods: {
			
			//底部导航
			onChange(event) {
				// event.detail 的值为当前选中项的索引
				if (event.detail == 0) {
					uni.redirectTo({
						url: "../index/index",
					});
				} else if (event.detail == 1) {
					uni.redirectTo({
						url: "../store/index"
					});
				} else if (event.detail == 2) {
					uni.redirectTo({
						url: "../shop_car/index"
					});
				} else if (event.detail == 3) {
					uni.redirectTo({
						url: "../my/index"
					});
				}

				console.log(event.detail)
				console.log(this.active)
			},



		},
		created() {

		},
	}
</script>

<style>
</style>
