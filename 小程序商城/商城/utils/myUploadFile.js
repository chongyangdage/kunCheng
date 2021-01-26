//请求类

const BASE_URL = 'http://39.104.60.13/index.php/index/';
// const BASE_URL = 'http://127.0.0.1/thinkphp5/public/index.php/index/';

export const myRequest = (options) => {
    let headerlist = options.header;
	return new Promise((resolve, reject) => {
		uni.request({
			url: BASE_URL + options.url,//地址拼接
			method: options.method || 'GET',
			data: options.data,
			header: headerlist || {},
			success: (res) => {
					resolve(res.data)
			},
			fail: (err) => {
				uni.showToast({
					title: '获取失败',
					icon: "none"
				})
				reject(err)
			}
		})
	})
}

//上传类
export const myUploadFile = (options) => {
    let headerlist = options.header;
	return new Promise((resolve, reject) => {
	 uni.uploadFile({
			url: BASE_URL + options.url,
			files: options.files,
			filePath: options.filePath,
			name: options.name,
			formData: options.formData,
			header: headerlist || {},
			success: (res) => {
				console.log(res)
				if (res.statusCode == 200) {
					var data = JSON.parse(res.data)
					resolve(data)
				} else {
					resolve(res)
				}
			},
			fail: (err) => {
				console.log(err)
				uni.showToast({
					title: '获取失败',
					icon: "none"
				})
				reject(err)
			}
		})
	})
}
