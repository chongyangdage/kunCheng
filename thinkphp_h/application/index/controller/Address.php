<?php

namespace app\index\controller;

use think\Request;
use app\index\model\Address as Address_m;

class Address
{
    //添加地址
    public function index()
    {
        $userAddress = new Address_m();
        $request = Request::instance();
        $word = $request->param();
        $address = $word["address"];
        $phone = $word["phone"];
        $name = $word["name"];
        $userId = $word["userId"];
        $address_details = $word["address_details"];
        $state = $word["state"];
        $province = $word["province"];
        $city = $word["city"];
        $county = $word["county"];
        $data_address_all = $userAddress->select();
        if ($state == 1) {
            if (count($data_address_all) != '') {

                for ($i = 0; $i < count($data_address_all); $i++) {
                    $data_address_all[$i]->state = 0;
                    $data_address_all[$i]->save();
                }
            }
        } else {
            if (count($data_address_all) == '') {
                $state = 1;
            }
        }
        $userAddress->address = $address;
        $userAddress->phone = $phone;
        $userAddress->name = $name;
        $userAddress->userId = $userId;
        $userAddress->address_details = $address_details;
        $userAddress->state = $state;
        $userAddress->province = $province;
        $userAddress->city = $city;
        $userAddress->county = $county;
        $userAddress->save();
        return ['data' => $state, 'code' => 200, 'message' => '操作完成'];
    }


    //查询地址
    public function get_address()
    {
        $request = Request::instance();
        $word = $request->param();
        $userId = $word["userId"];
        $userAddress = new Address_m();
        $data = $userAddress->where('userId', $userId)->where('state', 1)->find();
        return ['data' => $data, 'code' => 200, 'message' => '操作完成'];
    }
    //查询所有
    public function get_address_all()
    {
        $request = Request::instance();
        $word = $request->param();
        $userId = $word["userId"];
        $userAddress = new Address_m();
        $data = $userAddress->where('userId', $userId)->select();
        return ['data' => $data, 'code' => 200, 'message' => '操作完成'];
    }
    //根据id查询地址
    public function get_address_ById()
    {
        $request = Request::instance();
        $word = $request->param();
        $Id = $word["id"];
        $userAddress = new Address_m();
        $data = $userAddress->where('id', $Id)->find();
        return ['data' => $data, 'code' => 200, 'message' => '操作完成'];
    }

    //更新收货地址
    public function uolode_address_ById()
    {
        $request = Request::instance();
        $word = $request->param();
        $Id = $word["id"];
        $address = $word["address"];
        $phone = $word["phone"];
        $name = $word["name"];
        $userId = $word["userId"];
        $address_details = $word["address_details"];
        $state = $word["state"];
        $province = $word["province"];
        $city = $word["city"];
        $county = $word["county"];
        $userAddress = new Address_m();
        $data_address_all = $userAddress->select();
        if ($state == 1) {
            if (count($data_address_all) != '') {

                for ($i = 0; $i < count($data_address_all); $i++) {
                    $data_address_all[$i]->state = 0;
                    $data_address_all[$i]->save();
                }
            }
        } else {
            if (count($data_address_all) == '') {
                $state = 1;
            }  
        }
        $userAddress = $userAddress->where('id', $Id)->find();
        $userAddress->address = $address;
        $userAddress->phone = $phone;
        $userAddress->name = $name;
        $userAddress->userId = $userId;
        $userAddress->address_details = $address_details;
        $userAddress->state = $state;
        $userAddress->province = $province;
        $userAddress->city = $city;
        $userAddress->county = $county;
        $userAddress->save();
        $data_address_all1 = $userAddress->select();
        $index = false;
        for ($i = 0; $i < count($data_address_all1); $i++) {
            if ($data_address_all1[$i]['state'] == 1) {
                $index = true;
            }
            if ($index == false) {
                $data_address_all1[0]->state = 1;
                $data_address_all1[0]->save();
            }
        }
        return ['data' => '保存成功', 'code' => 200, 'message' => '操作完成'];
    }

    //修改默认地址
    public function revise_address_default()
    {
        $request = Request::instance();
        $word = $request->param();
        $Id = $word["id"];
        $userAddress = new Address_m();
        $data = $userAddress->where('id', $Id)->find();

        if ($data['state'] == 0) {
            $data_address_all = $userAddress->select();
            for ($i = 0; $i < count($data_address_all); $i++) {
                $data_address_all[$i]->state = 0;
                $data_address_all[$i]->save();
            }
            $data->state = 1;
            $data->save();
        } else {
            $data->state = 0;
            $data->save();
            $data_address_all = $userAddress->select();
            $index = false;
            for ($i = 0; $i < count($data_address_all); $i++) {
                if ($data_address_all[$i]['state'] == 1) {
                    $index = true;
                }
                if ($index == false) {
                    $data_address_all[0]->state = 1;
                    $data_address_all[0]->save();
                }
            }
           
        }

        return ['data' => '修改成功', 'code' => 200, 'message' => '操作完成'];
    }


    //根据id删除地址
    public function delete_address_ById()
    {
        $request = Request::instance();
        $word = $request->param();
        $Id = $word["id"];
        $userAddress = new Address_m();
        $data = $userAddress->where('id', $Id)->find();
        $data->delete();
        $data_address_all = $userAddress->select();
        if($data_address_all){
            $index = false;
            for ($i = 0; $i < count($data_address_all); $i++) {
                if ($data_address_all[$i]['state'] == 1) {
                    $index = true;
                }
                if ($index == false) {
                    $data_address_all[0]->state = 1;
                    $data_address_all[0]->save();
                }
            }
        }
           
        return ['data' => '删除成功', 'code' => 200, 'message' => '操作完成'];
    }
}
