<?php
/**
 * Created by PhpStorm.
 * User: suppo
 * Date: 2020/9/17
 * Time: 9:45
 */

namespace app\index\controller;


use app\index\model\GroupChat;
use app\index\model\MemberGroup;
use think\Cache;
use think\Controller;
use app\index\model\Member;
use app\index\model\Chat as ChatModel;
use app\index\model\Group;
use GatewayClient\Gateway;

class Index extends Controller
{
    /**
     * @return mixed
     * 聊天室首页
     */
    public function index()
    {
        $id = input('id');

        $memberGroup = new MemberGroup();
        $memberModel = new Member();
        $groupChat = new GroupChat();
        $chatModel = new ChatModel();

        $uidList = Gateway::getAllUidList();
        if (!$id) {
            $members = $memberModel->field('id')->select()->toArray();
            $memberIds = array_column($members, 'id');
            $diffIds = array_diff($memberIds, $uidList);
            if (empty($diffIds)) {
                exit('数据库中的用户都处于在线状态，没有离线用户使用了');
            } else {
                $id = $diffIds[array_rand($diffIds)];
            }
        } else {
            if (in_array($id, $uidList)) {
                exit('该用户处于在线状态');
            }
        }
        $member = $memberModel->where('id', $id)->field('id,avatar,nickname')->find();
        if (!$member) {
            exit('用户不存在');
        }
        $member = $member->toArray();
        //获取用户群聊列表
        $groups = $memberGroup->with('gp')->where('member_id', $id)->select()->toArray();
        foreach ($groups as &$group) {
            $members = $memberGroup->with('member')->where('group_id', $group['group_id'])->order('member_role desc,create_time asc')->limit(4)->field('member_id')->select()->toArray();
            $group['members'] = $members;
            $newestGroupChat = $groupChat->with('fromer')->where('groupid', $group['group_id'])->order('create_time desc')->field('fromid,content,create_time,type')->find();
            if ($newestGroupChat) {
                $group['create_time'] = $newestGroupChat['create_time'];
                switch ($newestGroupChat['type']) {
                    case 2:
                        $newestGroupChat['content'] = "[图片消息]";
                        break;
                }
                $group['content'] = $newestGroupChat['fromer']['nickname'] . '：' . $newestGroupChat['content'];
            } else {
                $master = $memberGroup->with('member')->where(['group_id' => $group['group_id'], 'member_role' => 2])->field('member_id')->find();
                $group['content'] = $master['member']['nickname'] . '于 ' . $group['gp']['create_time'] . ' 创建了' . $group['gp']['name'] . '群聊';
            }
            $group['count'] = $memberGroup->where('group_id', $group['group_id'])->count();
            $group['sort'] = strtotime($group['create_time']);
        }
        unset($group);
        unset($newestGroupChat);
        //上线后需要加入的群聊
        $joinGroupIds = array_column($groups, 'group_id');
        $friends = $memberModel->whereNotIn('id', $id)->field('id,avatar,nickname,create_time')->select()->toArray();
        $ids = array_column($friends, 'id');
        //获取用户私聊列表
        foreach ($friends as &$friend) {
            $toid = $friend['id'];
            $newestChat = $chatModel->where(['fromid' => $id, 'toid' => $toid])->whereOrRaw("fromid = $toid AND toid = $id")->order('id desc')->field('content,create_time,type')->find();
            if ($newestChat) {
                switch ($newestChat['type']) {
                    case 2:
                        $newestChat['content'] = "[图片消息]";
                        break;
                }
                $friend['content'] = $newestChat['content'];
                $friend['create_time'] = $newestChat['create_time'];
            } else {
                $friend['content'] = '';
            }
            $friend['count'] = $chatModel->where(['fromid' => $toid, 'toid' => $id, 'is_read' => 0])->count();
            if ($friend['count']) {
                $friend['is_read'] = 0;
            } else {
                $friend['is_read'] = 1;
            }
            $friend['sort'] = strtotime($friend['create_time']);
        }
        unset($friend);
        unset($newestChat);
        //群聊私聊列表合并排序
        $myChats = array_merge($groups, $friends);
        usort($myChats, function ($a, $b) {
            if ($a['sort'] === $b['sort']) {
                return 0;
            }
            return $b['sort'] - $a['sort'];
        });
        unset($groups);
        unset($friends);
        $this->assign('member', $member);
        $this->assign('myChats', $myChats);
        $options = [
            'from_id' => $member['id'],
            'to_id' => 0,
            'from_nickname' => $member['nickname'],
            'from_avatar' => $member['avatar'],
            'check_online_ids' => $ids,
            'join_group_ids' => $joinGroupIds
        ];
        $this->assign('options', json_encode($options));
        return $this->fetch();
    }

    /**
     * @return array
     * 图片上传
     */
    public function uploadFile()
    {
        $res = ['status' => false, 'data' => ['message' => '非法请求']];
        if (request()->isAjax()) {

            $file = $_FILES['file'];
            $suffix = strtolower(strrchr($file['name'], '.'));
            $filepath = ROOT_PATH . "public" . DS . "uploads" . DS . "chat" . DS;
            if (!file_exists($filepath)) {
                mkdir($filepath, 755, true);
            }
            $filename = uniqid('chat_img_');
            $accept = ['.jpg', '.jpeg', '.png', '.gif'];
            if (!in_array($suffix, $accept)) {
                $res['data']['message'] = "不支持文件格式";
            }
            if ($file['size'] / 1024 > 4) {
                $res['data']['message'] = "图片不能大于4MB";
            }
            $result = move_uploaded_file($file['tmp_name'], $filepath . $filename . $suffix);
            if ($result) {
                $res['data']['message'] = "上传成功";
                $res['status'] = true;
                $res['data']['content'] = DS . "uploads" . DS . "chat" . DS . $filename . $suffix;
            } else {
                $res['data']['message'] = "服务器繁忙";
            }
            return $res;
        }
        return $res;
    }
}