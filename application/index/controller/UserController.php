<?php
namespace app\index\controller;
use think\Controller;
use app\index\model\User;
use think\Db;
use think\Request;
use think\captcha\Captcha;


class UserController extends Controller 
{

     // 新增用户数据
    public function add()
     {
        $user = new User;
        //print_r($user->username);
        if ($user->save(input('post.'))) 
        {
            $data;
            $data=input('post.');
            Db::table('think_user')->insert($data);
            return '用户[ ' . $user->username . ':' . $user->id . ' ]新增成功';
        } else {
            return $user->getError();
        }
     }
    public function index()
    {
        $list = User::all(function($query){
            $query->where('id', '<', 3)->order('id', 'desc');
        });
        foreach ($list as $user) {
            echo $user->nickname . '<br/>';
            echo $user->email . '<br/>';
            echo $user->birthday . '<br/>';
            echo '----------------------------------<br/>';
        }
    }
    public function update($id)
    {
        $user           = User::get($id);
        $user->nickname = '刘晨';
        $user->email    = 'liu21st@gmail.com';
        $user->save();
        return '更新用户成功';
    }
    public function create()
    {
        $captcha = new Captcha();
        return $captcha->entry();
        return view();
    }
    public  function echos()
    {
     $aa=[];
     $aa= Db::table('think_user')->where('id',1)->select();
     print_r($aa);
     return;
    }
    public function hu()
    {
        return "hhhh";
    }
   
}