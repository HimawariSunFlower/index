<?php
namespace app\index\controller;
use think\Request ;

class IndexController
{
    public function index()
    {
        //return 111;
        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
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
}
