<template>
	<view class="my_wrap">
		<view class="header">
			<view class="header_img">
				<view class="header_view">
					<open-data class="img" type="userAvatarUrl"></open-data>
				</view>
				
				<open-data class="text" type="userNickName"></open-data>
			</view>
			
		</view>
		<view class="main">
			<view  class="main_tit">
				我的订单
			</view>
			<view class="icon">
				<view @click="order(0)" class="icon_main">
					<view class="icon_img">
						<image class="icon_img" src="../../static/my_icon/daifukuan.svg" mode=""></image>
					</view>
					<view class="icon_text">
						待付款
					</view>
				</view>
			
	
				<view @click="order(1)" class="icon_main">
					<view class="icon_img">
						<image class="icon_img" src="../../static/my_icon/daifahuo.svg" mode=""></image>
					</view>
					<view class="icon_text">
						待发货
					</view>
				</view>
			
			
				<view @click="order(2)" class="icon_main">
					<view class="icon_img">
						<image class="icon_img" src="../../static/my_icon/daishouhuo.svg" mode=""></image>
					</view>
					<view class="icon_text">
						待收货
					</view>
				</view>
			
			
				<view @click="order(3)" class="icon_main">
					<view class="icon_img">
						<image class="icon_img" src="../../static/my_icon/yiwancheng-copy.svg" mode=""></image>
					</view>
					<view class="icon_text">
						已完成
					</view>
				</view>
			</view>
		</view>
		
		
		<view class="main">
			<view class="main_tit">
				我的服务
			</view>
			<view class="icon">
				
				<view @click="my_address" class="icon_main">
					<view class="icon_img">
						<image class="icon_img" src="../../static/my_icon/shouhuoxinxidizhi.svg" mode=""></image>
					</view>
					<view class="icon_text">
						收货信息
					</view>
				</view>

				<view @click="shop_car" class="icon_main">
					<view class="icon_img">
						<image class="icon_img" src="../../static/my_icon/gouwuche1.svg" mode=""></image>
					</view>
					<view class="icon_text">
						购物车
					</view>
				</view>
			</view>
		</view>
		<tab active=3></tab>
	</view>
</template>

<script>
	import tab from '../../components/tab.vue';
	export default {
		components:{
			tab
		},
		data() {
			return {
					show_test:'',
					url_icon:''
				  }
				 
		},
		
		onLoad() {
	
		},
		methods: {
			//跳转到购物车
			shop_car(){
				uni.redirectTo({
					  url:"../shop_car/index",
				  });
			},
		
		
			//跳转到我的订单
			order(e){
				uni.redirectTo({
					  url:`../order/index?active=${e}`,
				  });
			},
			//收货信息跳转
			my_address(){
				uni.redirectTo({
					  url:"../my_address/index",
				  });
			},

				   
		},
		created(){
			this.show_test=getApp().globalData.mytest
			this.url_icon=getApp().globalData.imgs_icon
		},
	}
</script>

<style>
	.header_view{
		width: 150rpx;
		height: 150rpx;
		padding: 5rpx;
		margin: 100rpx 0rpx 0 57rpx;
		border-radius: 50%;
		overflow: hidden;
		border: 2rpx solid #fff;
	}
	.text{
		color: #fff;
		height: 150rpx;
		line-height: 150rpx;
		margin: 100rpx 0rpx 0 33rpx;
	}
	.icon_main{
		margin-top: 10rpx;
		width: 183rpx;
		height: 120rpx;
		
	}
	.icon_text{
		margin-top: 9rpx;
		font-size: 13px;
		width: 100%;
		text-align: center;
	}
	.icon_img{
		display: block;
		height: 65rpx;
		margin: 0 auto;
		width: 65rpx;
		object-fit: cover;
	}
	.icon{
		display: flex;
		
		flex-wrap: wrap;
	}
	.main_tit{
		padding-left: 23rpx;
		height: 65rpx;
		line-height: 65rpx;
		font-weight: bold;
		border-bottom: 6rpx #E5E5E5 solid;
	}
	.main{
		width: 740rpx;
		margin: 15rpx auto 0 auto;
		padding-top: 17rpx;
		padding-bottom: 17rpx;
		border-radius: 30rpx;
		overflow: hidden;
		background-color: #fff;
	}
	.img{
		border-radius: 50%;
		
		display: block;
		overflow: hidden;
		object-fit: cover;
		width: 150rpx;
		height: 150rpx;
	}
	.header_img{
		display: flex;
		
		
	}
	.my_wrap{
		min-height: 100vh;
		background-color: #E5E5E5;
	}
	.header{
		background: url('http://m.qpic.cn/psc?/V111yKEY0i00Yh/ruAMsa53pVQWN7FLK88i5iRDXcHHD97mAzxDcxei7BQ0hchnb.XoBzcgAvFkK7CXje1BWQMFQL7gwYic3bEIY0sRv4Xf0AhHzzQq1dhCFkY!/b&bo=OAQ4AjgEOAIDCSw!&rf=viewer_4');
		overflow: hidden;
		margin: 0 auto;
		width: 740rpx;
		height: 376rpx;
		border-top-left-radius: 30rpx;
		border-top-right-radius: 30rpx;
		background-size: 100% 100%;
		background-repeat: no-repeat;
	}
</style>
