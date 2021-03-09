<?php
/**
 * Created by PhpStorm.
 * User: suppo
 * Date: 2020/9/17
 * Time: 11:54
 */

namespace app\index\model;


use think\Model;

class Chat extends Model
{
    protected $autoWriteTimestamp = true;
    protected $resultSetType = 'collection';

    public function fromer(){
        return $this->hasOne('Member','id','fromid')->field('id,avatar,nickname');
    }
    public function toer(){
        return $this->hasOne('Member','id','toid')->field('id,avatar,nickname');
    }
}