<?php
namespace app\index\controller;
use app\index\model\Rotation_chart as Rotation_chart_m;
use think\Request;
class Rotationchart{
    public function index(){
        $request = Request::instance();
        $get_all=$request->param();
        $name=$get_all['name'];
        $shop_id=$get_all['shop_id'];
        $Rotation_chart=new Rotation_chart_m();
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $path=$info->getSaveName();
                $Rotation_chart->path=$path;
                $Rotation_chart->name=$name;
                $Rotation_chart->shop_id=$shop_id;
                $Rotation_chart->save();
                return ['data'=>'上传成功','code'=>200,'message'=>'操作完成'];
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getSaveName();
                // // 输出 42a79759f284b767dfcb2a0197904287.jpg
                // echo $info->getFilename(); 
            }else{
                // 上传失败获取错误信息
                return ['data'=>'上传失败','code'=>502,'message'=>'操作完成'];
            }
        }
    }

    public function get(){
        $Rotation_chart=new Rotation_chart_m();
        $data=$Rotation_chart->select();
        return ['data'=>$data,'code'=>200,'message'=>'操作完成'];
    }
}
