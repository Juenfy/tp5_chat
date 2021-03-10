<?php
/**
 * Copyright: yanjianfei
 * date: 2021/3/10
 * time: 5:00 下午
 */

namespace app\index\controller;


use app\index\model\Member;
use app\index\model\MemberGroup;
use GatewayClient\Gateway;
use think\Controller;

class Group extends Controller
{
    /**
     * 创建群聊
     */
    public function create()
    {
        if (request()->isAjax() && request()->isPost()) {
            $data['name'] = input('name');
            $data['master_id'] = input('master_id');
            $ids = input('ids');
            $ids = explode(',', $ids);
            $data['create_time'] = time();
            $group = new \app\index\model\Group();
            $groupNum = $group->where('name', $data['name'])->where('master_id', $data['master_id'])->count();
            if ($groupNum) {
                show_json(0, '群名称已存在');
            } else {
                $gid = $group->insertGetId($data);
            }
            $member = new Member();
            $master = $member->where('id', $data['master_id'])->field('id,nickname,avatar')->find();
            $memberList = $member->whereIn('id', input('ids'))->field('id,nickname,avatar')->limit(4)->select()->toArray();
            array_unshift($memberList, $master);
            //群信息
            $groupData = [];
            $groupData['type'] = "createGroup";
            $groupData['groupId'] = $gid;
            $groupData['groupName'] = $data['name'];
            $groupData['count'] = count($ids) + 1;
            $groupData['members'] = $memberList;
            $groupData['createTime'] = date('Y-m-d H:i:s', $data['create_time']);
            $groupData['content'] = $master['nickname'] . '于 ' . $groupData['createTime'] . ' 创建了' . $groupData['groupName'] . '群聊';
            $mgdata = [];
            //先把群主放进去数组中
            array_push($mgdata, ['group_id' => $gid, 'member_id' => $data['master_id'], 'member_role' => 2, 'create_time' => time()]);
            //把群主加进群聊中
            $master_client_id = Gateway::getClientIdByUid($data['master_id']);
            Gateway::joinGroup($master_client_id[0], 'group_' . $gid);
            //向群主发送创建群聊信息
            Gateway::sendToUid($data['master_id'], json_encode($groupData));
            foreach ($ids as $id) {
                //把选中的好友放进去数组中
                array_push($mgdata, ['group_id' => $gid, 'member_id' => $id, 'member_role' => 0, 'create_time' => time()]);
                //把在线好友加进群聊中
                if (Gateway::isUidOnline($id)) {
                    $friend_client_id = Gateway::getClientIdByUid($id);
                    Gateway::joinGroup($friend_client_id[0], 'group_' . $gid);
                    //向好友发送创建群聊信息
                    Gateway::sendToUid($id, json_encode($groupData));
                }
            }
            $mgroup = new MemberGroup();
            $res = $mgroup->insertAll($mgdata);
            if ($gid && $res) {
                show_json(1, [
                    'id' => $gid,
                    'nickname' => $groupData['groupName'],
                    'count' => $groupData['count']
                ]);
            } else {
                show_json(0, '服务器繁忙');
            }

        }
        show_json(0, '非法请求');
    }

    /**
     * @throws \think\exception\DbException
     * 加入群聊
     */
    public function join()
    {
        $ids = input('ids');
        $groupId = input('groupId');
        $inviterId = input('inviterId');
        $joinTime = date('Y-m-d H:i:s');
        $joinMemberList = (new Member())->whereIn('id', $ids)->field('avatar,nickname')->select()->toArray();
        $ids = explode(',', $ids);
        $data = [];
        foreach ($ids as $id) {
            array_push($data, [
                'group_id' => $groupId,
                'member_id' => $id
            ]);
        }
        $res = (new MemberGroup())->insertAll($data);
        if ($res) {
            $inviter = Member::get($inviterId);
            $inviter = $inviter->nickname . '邀请';

            $joinNickNames = array_column($joinMemberList, 'nickname');

            if (count($joinMemberList) > 2) {
                $joinNickNames = implode('、', $joinNickNames) . '等' . count($joinMemberList) . '人加入群聊';
            } else {
                $joinNickNames = implode('、', $joinNickNames) . '加入群聊';
            }

            $memberGroups = (new MemberGroup())->with('member')->where('group_id', $groupId)->order('member_role desc,create_time asc')->limit(4)->field('member_id')->select()->toArray();
            $count = (new MemberGroup())->where('group_id', $groupId)->count();
            $group = \app\index\model\Group::get($groupId);
            $group['members'] = array_column($memberGroups, 'member');
            $group_data = [
                'type' => 'joinGroupTips',
                'tips' => $inviter . $joinNickNames,
                'group_id' => $groupId,
                'count' => $count,
                'join_group_ids' => $ids,
                'join_time' => $joinTime
            ];
            Gateway::sendToGroup('group_' . $groupId, json_encode($group_data), Gateway::getClientIdByUid($inviterId));
            $user_data = [
                'type' => 'joinGroup',
                'group' => $group,
                'join_time' => $joinTime,
                'count' => $count,
                'tips' => $inviter . '你加入群聊'
            ];
            foreach ($ids as $id) {
                Gateway::sendToUid($id, json_encode($user_data));
            }
            show_json(1, [
                'members' => $joinMemberList,
                'tips' => '我邀请' . $joinNickNames,
                'count' => $count,
                'join_time' => $joinTime
            ]);
        } else {
            show_json(0, '服务器繁忙');
        }

    }

    public function view()
    {
        $id = input('id');
        $this->assign('id', $id);
        return $this->fetch('index/group_view');
    }
}