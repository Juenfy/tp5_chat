<?php
/**
 * Copyright: yanjianfei
 * date: 2021/3/10
 * time: 5:03 下午
 */

namespace app\index\controller;


use app\index\model\Member;
use think\Controller;
use app\index\model\MemberGroup;

class User extends Controller
{
    /**
     * @return mixed
     * 返回好友列表视图
     */
    public function friend()
    {
        $id = input('id');
        $type = input('type');
        $groupId = input('groupId');
        $this->assign('id', $id);
        $this->assign('type', $type);
        $this->assign('groupId', $groupId);
        return $this->fetch('index/friend');
    }

    /**
     * 获取好友列表
     */
    public function getFriends()
    {
        if (request()->isAjax()) {
            $id = input('id');
            $page = request()->param('page');
            $length = request()->param('limit');
            $offset = ($page - 1) * $length;
            $keyword = request()->param('keyword');
            if (!empty($keyword)) {
                $where['nickname'] = ['like', '%' . $keyword . '%'];
            } else {
                $where = [];
            }
            $memberModel = new Member();
            $friends = $memberModel->whereNotIn('id', $id)->where($where)->field('id,avatar,nickname')->limit($offset, $length)->select()->toArray();
            $count = $memberModel->whereNotIn('id', $id)->where($where)->count();
            layui_json(0, $friends, $count);
        }
    }

    public function view()
    {
        $id = input('id');
        $this->assign('id', $id);
        return $this->fetch('index/user_view');
    }

    public function getListByGroupId()
    {
        $groupId = input('groupId');
        $group_member_list = (new MemberGroup())->with('member')->where('group_id', $groupId)->order('member_role desc, create_time asc')->limit(20)->select()->toArray();
        show_json(1, $group_member_list);
    }
}