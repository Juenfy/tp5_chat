<?php
/**
 * Created by PhpStorm.
 * User: suppo
 * Date: 2020/9/25
 * Time: 16:32
 */

namespace app\index\model;


use think\Model;

class Group extends Model
{
    protected $autoWriteTimestamp = true;
    protected $resultSetType = 'collection';
}