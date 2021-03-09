<?php
/**
 * Created by PhpStorm.
 * User: juenfy
 * Date: 2020/12/8
 * Time: 18:57
 */

namespace app\index\model;


use think\Model;

class GroupChat extends Model
{
    protected $autoWriteTimestamp = true;
    protected $resultSetType = 'collection';
    public function fromer(){
        return $this->hasOne('Member','id','fromid')->field('id,nickname,avatar');
    }
}