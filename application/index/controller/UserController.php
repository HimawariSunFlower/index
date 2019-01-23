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
        $input_lines ="10";
        $input_line="0 9 9 0 5 9 9 2 3 0";
        //$i= str_split($aaa);
       // $x=0;
        $qian=array(" ","　","\t","\n","\r");
        $hou=array("","","","","");
        $aaa=str_replace($qian,$hou,$input_line);  
       echo $aaa;
        //$i= str_split($input_lines);
        $i=$input_lines*1;
        $y=str_split($aaa);
        echo $i;
        echo 'aaaaa';
        dump($y);
           $x=0;
               for($a=0;$a<$i;$a++)
               {
                   if($y[$a]>5){
                       $x ++;
                   }
               }
             
           
        echo $x;
    }
   
}