<?php

namespace app\index\controller;
use think\Request;
use app\index\model\Username;
//登录
class Index
{
    public function index()
    {
        $shop=new userName();
     
        $request = Request::instance();
        $data_all=$request->param();
        $appid= $data_all['appid'];
        $secret= $data_all['secret'];
        $code= $data_all['code'];
        $loginUrl = "https://api.weixin.qq.com/sns/jscode2session?appid=" . $appid . "&secret=" . $secret . "&js_code=" . $code . "&grant_type=authorization_code";
        $arr = $this->curl_get($loginUrl);
        $res = json_decode($arr,true);
        
        $openid_o=$res['openid'];
        $data= $shop->where('openId','=',$openid_o)->find();
        if(!$data){
            $shop ->openId = $openid_o;
            $shop->save();
        }
        $data= $shop->where('openId',$openid_o)->find();
        if($openid_o){
            return ['data'=>$data,'code'=>200,'message'=>'操作完成'];
        }else{
            return ['data'=>$data,'code'=>506,'message'=>'操作失败'];
        }
	   
        
    }
    public function curl_get($url)
{

	$info = curl_init();
	curl_setopt($info,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($info,CURLOPT_HEADER,0);
	curl_setopt($info,CURLOPT_NOBODY,0);
	curl_setopt($info,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($info,CURLOPT_SSL_VERIFYPEER,false);
	curl_setopt($info,CURLOPT_SSL_VERIFYHOST,false);
	curl_setopt($info,CURLOPT_URL,$url);
	$output = curl_exec($info);
	curl_close($info);
	return $output;
}
}
