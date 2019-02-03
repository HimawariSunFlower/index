<?php
namespace app\index\controller;
use think\Request ;
use think\Session;
use think\Db;
use think\view;

class IndexController extends \think\Controller
{
    //测试用类
    //


         //先写死,用lv2测试
    public function index(request $re)
    {
        $info=$re->param();
        if($info){
            $judge =Db::table('think_user')->where('username',$info["username"])->find();
            
            
            if($info["password"]==$judge["password"]){
                $inf = array("id"=>$judge["id"]);
                $this->redirect('index/index/echo', $inf);
                
            }else{
                $this->error('信息有误！');
            }
        
               
            
        }
        
    }
    public function home(){
        return view();
    }
    public function echo(request $re){
        $info=$re->param();
        $msg=[];
       // dump($re);
        //dump($info);
        $judge =Db::table('think_user')->where('id',$info["id"])->find();
        $persona=Db::table('think_up')->where('uid',$judge["id"])->find();
        //echo $persona["pid"];
        //dump($persona);
        $limit=Db::table('think_lp')->where('pid',$persona["pid"])->select();
         //dump($limit);
        //return;
        
        $top=Db::table('think_limit')->count();
    //    echo $top;
    //    echo count($limit);
        for($i=0;$i<$top;$i++){
            $lv =Db::table('think_limit')->where('id',500)->find();
            $msg["lv$i"]=$lv["url"];   
            for($x=0;$x<count($limit);$x++){
                if( $limit[$x]["lid"]==$i){
                    $aaa = $limit[$x]["lid"];
                    $lv =Db::table('think_limit')->where('id',$aaa)->find();
                    $msg["lv$i"]=$lv["url"];   
                    }

            }
          
        }
        $msg["username"]=$judge["username"];
        $msg["password"]= $judge["password"];
        $msg["id"]=$judge["id"];
        //$msg["aaa"]=$limit[0]["lid"];
       // $msg["bbb"] = $limit[1]["lid"];
        // dump($msg);
        // return;
        $view = new View();
        $view->assign('msg',$msg);
        return $view->fetch();
    
     
    }
    public function a1(){
        return view();
    }






    public function add()
    {
        $element =controller("UserController","controller");
        echo $element->hu();
        echo 1111111;
        return 111;
    }
    public function getmsg()
    {
        $request	=	Request::instance();
        //	获取当前域名 
        echo	'domain:	'	.	$request->domain()	.	'<br/>';
        //	获取当前入口文件 
        echo	'file:	'	.	$request->baseFile()	.	'<br/>';
        //	获取当前URL地址	不含域名
        echo	'url:	'	.	$request->url()	.	'<br/>';
        //	获取包含域名的完整URL地址 
        echo	'url	with	domain:	'	.	$request->url(true)	.	'<br/>'; 
        //	获取当前URL地址	不含QUERY_STRIN
        echo	'url	without	query:	'	.	$request->baseUrl()	.	'<br/>';
        //	获取URL访问的ROOT地址
        echo	'root:'	.	$request->root()	.	'<br/>';
        //	获取URL访问的ROOT地址 
        echo	'root	with	domain:	'	.	$request->root(true)	.	'<br/>'; 
        //	获取URL地址中的PATH_INFO信息
        echo	'pathinfo:	'	.	$request->pathinfo()	.	'<br/>'; 
        //	获取URL地址中的PATH_INFO信息	不含后缀
        echo	'pathinfo:	'	.	$request->path()	.	'<br/>'; 
        //	获取URL地址中的后缀信息
        echo	'ext:	'	.	$request->ext()	.	'<br/>';
        echo	"当前模块名称是"	.	$request->module(); 
        echo	"当前控制器名称是"	.	$request->controller(); 
        echo	"当前操作名称是"	.	$request->action();
        echo	'请求方法：'	.	$request->method()	.	'<br/>'; 
        echo	'资源类型：'	.	$request->type()	.	'<br/>';
        echo	'访问ip地址：'	.	$request->ip()	.	'<br/>'; 
        echo	'是否AJax请求：'	.	var_export($request->isAjax(),	true)	.	'<br/>';
        if($request->isAjax()){echo 00000000000000;}else{echo 111111111111;}
        echo	'请求参数：'; dump($request->param()); 
        echo	'请求参数：仅包含name'; dump($request->only(['name'])); 
        echo	'请求参数：排除name'; dump($request->except(['name']));
        return;

    }
    //layui测试
    public function cs(){
        return view();
    }
    //session的测试
    public function  ss(Request $re){
        dump($re->header()["cookie"]);
        $session=$re->header()["cookie"];
        $sessionId=substr($session,10);
        Session::set("id",$sessionId);
        $id=Session::get("id");
        $this->ss2() ;
        session("id",null);
 
    }
    public function ss2(){
        //Session_star();
        if(!Session::get("id")){
            echo "nop";
        }else{
            echo "yes";
        }
    }
   
    public function upload(Request $i){
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('image');
        
        dump($i);
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                echo $info->getFilename(); 
            }else{
                // 上传失败获取错误信息
                echo $file->getError();
            }
        }
    }
    
}
