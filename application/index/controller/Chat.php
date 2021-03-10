<?php
/**
 * Copyright: yanjianfei
 * date: 2021/3/10
 * time: 5:04 下午
 */

namespace app\index\controller;


use app\index\model\Chat as ChatModel;
use app\index\model\GroupChat;
use think\Cache;
use think\Controller;

class Chat extends Controller
{
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
}