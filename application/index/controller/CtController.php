<?php
namespace app\index\controller;
use think\Request;
use think\cache\driver;
class CtController
{
    //b站主页可以抓，视频界面抓出来全是乱码，不是代码的问题
    public function ct(Request $request)
    {
        $data=$request->param("info");
        $this->metaget($data);
        return "我点了";
    }

    public function webct()//$info) //simple_html_dom抓取
    {
        //base url
        $base = 'https://www.bilibili.com/video/av40731682/';//.video/av$info;
        //echo $base;
        
        //echo $info;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $base);
        curl_setopt($curl, CURLOPT_REFERER, $base);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $str = curl_exec($curl);
        dump($str);
        curl_close($curl);
        //print_r($str);
        //echo $str;

        // Create a DOM object
        $html_base = new \think\cache\driver\simple_html_dom();
        // Load HTML from a string
        $html_base->load($str);
        //$img = $html_base->find("meta");
        //dump($html);
        //echo $img;
        //get all category links
         foreach($html_base->find('a') as $element) {
            echo "<pre>";
             print_r( $element->href );
             echo "</pre>";
             echo 233;
         }

         $html_base->clear(); 
         unset($html_base);

        //$img =$html_base->load_file($str);

        return;
    }
    public function echo()
    {
        return view();
    }
    public function metaget()//$info)//抓有name的meta
    {
        $base = 'https://www.bilibili.com/video/av40731682';//.$info;
     
        //echo $info;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_URL, $base);
        curl_setopt($curl, CURLOPT_REFERER, $base);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $str = curl_exec($curl);
        dump($str);
        curl_close($curl);
        $base1=file_get_contents($str);
        dump($base1);
        $meta_array = get_meta_tags($str);
        //var_dump($meta_array);
        echo $meta_array["a"];
    }
}