<template>
	<view class="content">
		<view class="search">
			<van-search @change='search_chang' shape='round' v-model="value"  placeholder="请输入搜索关键词" use-action-slot >
				<view slot="action" @click="onClick">搜索</view>
			</van-search>
		</view>
		<view class="hot_search">
			<view class="hot_t">
				热门搜索
			</view>
			<view class="hot_main">
				
				<view @click="click_word(item.name)" v-for="(item,index) in search_data" :key='index' class="hot_list">
					<van-icon color='red' name="fire" />
					<view class="texy=t">
						{{item.name}}
					</view>
				</view>
			</view>
		</view>
		<van-toast id="van-toast" />
	</view>
</template>

<script>
	import Toast from '../../wxcomponents/dist/toast/toast';
	export default {
		data() {
			return {
				value:'',
				search_data:[]
			}
		},
		onLoad() {

		},
		watch: {


		},
		methods: {
			//输入框内容发生变化
			search_chang(e){
				
				this.value=e.detail
			},
			//点击搜索
			onClick(){
				if(this.value==''){
					Toast('请输入搜索关键字');
				}else{
					let that=this
					console.log(this.value,999)
					uni.redirectTo({
						url: `../shop_show_serch/index?word=${this.value}`,
					});
				}
				
			},
			//点击关键词搜索
			click_word(e){
				this.value=e
				
			},
			//查询所有搜索推荐
			create_1() {
				this.$myRequest({
					url: "Shop/searchReferral",
					method: 'get',
				}).then((res) => {
			
					console.log(res)
					this.search_data = res.data
				
				}).catch((res) => {
					console.log(res)
				})
			},
		},

		created() {
			this.create_1()
		}
	}
</script>

<style>
	.hot_list{
		margin-top: 20rpx;
		color: red;
		width:fit-content;
		width:-webkit-fit-content;
		width:-moz-fit-content;
		padding-left: 20rpx;
		padding-right: 20rpx;
		line-height: 70rpx;
		border-radius: 40rpx;
		display: flex;
		justify-content: space-between;
		border: 2rpx solid #ccc;
	}
	.hot_main{
		display: flex;
		flex-wrap: wrap;
		justify-content: space-around;
		margin-top: 50rpx;
		}
	.hot_search{
		padding-left: 30rpx;
		padding-right: 30rpx;
	}
	.hot_t{
		font-size: 20px;
		font-weight: bold;
		margin-top: 50rpx;
		
		}
	.content {
		padding-bottom: 30rpx;
	}


	.search {
		width: 90%;
		height: 110rpx;
		margin: 8rpx auto 0 auto;
	}
</style>
