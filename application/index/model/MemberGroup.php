<?php
/**
 * Created by PhpStorm.
 * User: suppo
 * Date: 2020/9/25
 * Time: 16:43
 */

namespace app\index\model;


use think\Model;

class MemberGroup extends Model
{
    protected $autoWriteTimestamp = true;
    protected $resultSetType = 'collection';
    public function gp()
    {
        return $this->hasOne('Group','id','group_id')->field('id,name,create_time');
    }
    public function member(){
        return $this->hasOne('Member','id','member_id')->field('id,nickname,avatar');
    }

}