<?php
namespace app\index\controller;
use think\Request;
use think\cache\driver;
class CtController
{
    //403 php资料太少 转战python
    public function ct(Request $request)
    {
        $data=$request->param("info");
        $this->metaget($data);
        return ;//"我点了";
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
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
        $str = curl_exec($curl);
        //str=mbconvertencoding(str, “utf-8”, “GBK”);
                 //dump($str);
         echo $str;
        curl_close($curl);
       // $base1=file_get_contents($str);
        //dump($base1);
        //$meta_array = get_meta_tags($str);
        //var_dump($meta_array);
       // echo $meta_array["a"];
     //  return $str;
    }


    //测试有没有重定向
    public function cheshi3(){
        // 测试用的URL
        $urls = array(
            "http://www.bbc.com",
            "http://www.baidu.com",
            "https://www.bilibili.com"
        );
        // 测试用的浏览器信息
        $browsers = array(
            "standard" => array (
                "user_agent" => "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 (.NET CLR 3.5.30729)",
                "language" => "en-us,en;q=0.5"
                ),

            "iphone" => array (
                "user_agent" => "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A537a Safari/419.3",
                "language" => "en"
                ),

            "french" => array (
                "user_agent" => "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; GTB6; .NET CLR 2.0.50727)",
                "language" => "fr,fr-FR;q=0.5"
                )
        );

        foreach ($urls as $url) {
        echo "URL: $url\n<br />";
        foreach ($browsers as $test_name => $browser) {
            $ch = curl_init();

            // 设置 url
            curl_setopt($ch, CURLOPT_URL, $url);

            // 设置浏览器的特定header
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    "User-Agent: {$browser['user_agent']}",
                    "Accept-Language: {$browser['language']}"
                ));

            // 页面内容我们并不需要
            curl_setopt($ch, CURLOPT_NOBODY, 1);

            // 只需返回HTTP header
            curl_setopt($ch, CURLOPT_HEADER, 1);

            // 返回结果，而不是输出它
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $output = curl_exec($ch);
            curl_close($ch);

            // 有重定向的HTTP头信息吗?
            if (preg_match("!Location: (.*)!", $output, $matches)) {
                echo "$test_name: redirects to $matches[1]\n<br />";
            } else {
                echo "$test_name: no redirection\n<br />";
            }
        }
        echo "\n\n<br /><br />";
    }
        }
}