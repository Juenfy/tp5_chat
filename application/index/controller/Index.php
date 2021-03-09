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
     * @return \think\response\Json
     * 获取聊天记录
     */
    public function getList()
    {
        $fromid = input('fromid');
        $toid = input('toid');
        $chatType = input('chat_type');
        if ($chatType === 'chat') {
            //私聊记录
            $chatModel = new ChatModel();
            //将未读信息标记为已读;
            if (!input('is_read'))
                $chatModel->where(['fromid' => $toid, 'toid' => $fromid, 'is_read' => 0])->setField('is_read', 1);
            //缓存处理
            $chatList = Cache::remember('member' . $fromid . '_to_member' . $toid . '_chat_list', function () use ($chatModel, $fromid, $toid) {
                return $chatModel->with('fromer')->where(['fromid' => $fromid, 'toid' => $toid])->whereOrRaw("fromid = $toid AND toid = $fromid")->order('id asc')->select()->toArray();
            });
            if (!$chatList) {
                //没查到数据就不缓存
                Cache::rm('member' . $fromid . '_to_member' . $toid . '_chat_list');
            }
            return json($chatList);
        } else {
            //群聊记录
            $groupid = $toid;
            $groupChat = new GroupChat();
            $groupChatList = Cache::remember('member_to_group' . $groupid . '_chat_list', function () use ($groupChat, $groupid) {
                return $groupChat->with('fromer')->where('groupid', $groupid)->order('id asc')->select()->toArray();
            });
            if (!$groupChatList) {
                //没查到数据就不缓存
                Cache::rm('member_to_group' . $groupid . '_chat_list');
            }
            return json($groupChatList);
        }
    }

    /**
     * 消息持久化
     */
    public function save()
    {
        $data = input();
        $fromid = input('fromid');
        $toid = input('toid');
        $chatType = input('chat_type');
        $data['type'] = ($data['text_type'] == 1) ? 1 : 2;
        if ($chatType === 'chat') {
            //私聊信息持久化
            $saveData = [
                'fromid' => $data['fromid'],
                'toid' => $data['toid'],
                'content' => $data['content'],
                'type' => ($data['text_type'] == 1) ? 1 : 2,
                'is_read' => $data['is_read']
            ];
            $res = ChatModel::create($saveData, true);
            if ($res) {
                //缓存处理
                $res['fromer'] = ['id' => $fromid, 'avatar' => $data['from_avatar'], 'nickname' => $data['from_nickname']];
                $res['is_read'] = 1;
                unset($data);
                unset($res[strtolower('/' . request()->controller() . '/' . request()->action())]);
                $fromChatList = Cache::get('member' . $fromid . '_to_member' . $toid . '_chat_list');
                $toChatList = Cache::get('member' . $toid . '_to_member' . $fromid . '_chat_list');
                if ($fromChatList) {
                    array_push($fromChatList, $res);
                    Cache::set('member' . $fromid . '_to_member' . $toid . '_chat_list', $fromChatList);
                }
                if ($toChatList) {
                    array_push($toChatList, $res);
                    Cache::set('member' . $toid . '_to_member' . $fromid . '_chat_list', $toChatList);
                }
            }

        } else {
            //群聊信息持久化
            $groupid = $data['toid'];
            $saveData = [
                'fromid' => $data['fromid'],
                'groupid' => $groupid,
                'content' => $data['content'],
                'type' => ($data['text_type'] == 1) ? 1 : 2
            ];
            $res = GroupChat::create($saveData, true);
            if ($res) {
                //缓存处理
                $res['fromer'] = ['id' => $fromid, 'nickname' => $data['from_nickname'], 'avatar' => $data['from_avatar']];
                unset($data);
                unset($res[strtolower('/' . request()->controller() . '/' . request()->action())]);
                $fromGroupChatList = Cache::get('member_to_group' . $groupid . '_chat_list');
                if ($fromGroupChatList) {
                    array_push($fromGroupChatList, $res);
                    Cache::set('member_to_group' . $groupid . '_chat_list', $fromGroupChatList);
                }
            }
        }
        if ($res) {
            show_json(1, $res);
        } else {
            show_json(0, MSG_ERROR);
        }

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
                mkdir($filepath);
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

    /**
     * 消息已读状态修改
     */
    public function read()
    {
        if (request()->isAjax() && request()->isPost()) {
            $fromid = input('fromid');
            $toid = input('toid');
            $chat = new ChatModel();
            $res = $chat->where(['fromid' => $toid, 'toid' => $fromid, 'is_read' => 0])->setField('is_read', 1);
            if ($res) {
                show_json(1, '已读');
            } else {
                show_json(0, '操作失败');
            }
        }
    }

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
        return $this->fetch();
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

    /**
     * 创建群聊
     */
    public function createGroup()
    {
        if (request()->isAjax() && request()->isPost()) {
            $data['name'] = input('name');
            $data['master_id'] = input('master_id');
            $ids = input('ids');
            $ids = explode(',', $ids);
            $data['create_time'] = time();
            $group = new Group();
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

    public function joinGroup()
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
            $group = Group::get($groupId);
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
        $type = input('type');
        $this->assign('id', $id);
        $this->assign('type', $type);
        switch ($type) {
            case "chat":
                return $this->fetch('index/chat_view');
                break;
            case "groupchat":
                return $this->fetch('index/group_chat_view');
                break;
        }
    }

    public function getChatView()
    {

    }

    public function getGroupChatView()
    {
        $groupId = input('groupId');
        $group_member_list = (new MemberGroup())->with('member')->where('group_id', $groupId)->order('member_role desc, create_time asc')->limit(20)->select()->toArray();
        show_json(1, $group_member_list);
    }
}