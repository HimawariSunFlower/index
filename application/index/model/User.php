<?php
namespace app\index\model;

use think\Model;

class User extends Model
{
    protected $autoWriteTimestamp = true;     //开启自动写入时间戳
    protected $createTime = true;            //数据添加的时候，create_time 这个字段不自动写入时间戳
    protected $updateTime = ”update_at“;     //数据更新的时候，update_at 这个字段自动写入时间戳

}