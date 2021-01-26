<template>
	<view class="my_wrap">
		<van-radio-group :value="radio" @change="onChange">
		<view @click="jump_order(item)" v-for="(item,index) in address_data" :style="index==0?'margin-top: 0rpx; padding-top:40rpx;':''" :key='index' class="main_list">
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
		</view>
		</van-radio-group>
		
		
		
	</view>
</template>

<script>
	

	
	export default {
		
		data() {
			return {
				
			radio: '',
			id:'',//地址id
			address_data:''
		}
				 
		},
		
		onLoad(e) {
			if(e.onLoad==1){
				this.onLoad()
			}
		},
		methods: {

			//跳转订单页面
			jump_order(e){
				// let data=JSON.stringify(e)
				
				let pages = getCurrentPages();  //获取所有页面栈实例列表
				
				let nowPage = pages[ pages.length - 1];  //当前页页面实例
				
				let prevPage = pages[ pages.length - 2 ];  //上一页页面实例
				
				prevPage.$vm.data_address = e;   //修改上一页data里面的searchVal参数值为1211
				
				uni.navigateBack({  //uni.navigateTo跳转的返回，默认1为返回上一级
				
					delta: 1
				
				});
			
			},
			
			//查询所有地址
			get_address(){
				
			   this.$myRequest({
				   url:"/address/get_address_all",
				   method:'get',
				   data:{userId:getApp().globalData.userId},
			   }).then((res)=>{
				  console.log(88888)
				   this.address_data=res.data
				   for(let i=0;i<res.data.length;i++){
					   if(res.data[i].state==1){
						   this.radio=res.data[i].id
					   }
				   }
				   console.log(res)
				   }).catch((res)=>{console.log(res)})
			},
				   
		},
		created(){
			this.url=getApp().globalData.url
			this.get_address()
		},
	}
</script>

<style>
	.button{
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
	.main_list{
		margin-top: 60rpx;
		background-color: #fff;
	}
	.margin_2{
		margin-left: 35rpx;
	}
	.margin{
		margin-left: 230rpx;
	}
	.bottom_2{
		display: flex;
	}
	.bottom{
		padding-left: 38rpx;
		padding-right: 38rpx;
		padding-top: 30rpx;
		padding-bottom: 30rpx;
		display: flex;
	}
	.middle{
		padding-left: 38rpx;
		padding-right: 38rpx;
		padding-top: 30rpx;
		padding-bottom: 30rpx;
		font-size: 13px;
		color: #bebebe;
		border-bottom: 2rpx solid  #bebebe;
	}
	.top_l{
		height: 74rpx;
		line-height: 74rpx;
		font-weight: bold;
	}
	.top_r{
		height: 74rpx;
		line-height: 74rpx;
		font-weight: bold;
	}
.top{
	padding-left: 38rpx;
	padding-right: 38rpx;
	display: flex;
	justify-content: space-between;
}
.my_wrap{
	
	overflow: hidden;
	min-height: 100vh;
	background-color: #f7f7f7;
}
</style>
