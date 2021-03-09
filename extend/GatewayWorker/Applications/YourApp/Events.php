<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {
        echo "\n".$client_id."上线了\n";
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {
        // 向所有人发送
       echo "\n".$message."\n";
       $data = json_decode($message,true);
       switch ($data['type']){
           case "init":
               //绑定用户id
               Gateway::bindUid($client_id,$data['fromid']);
               $_SESSION['uid_'.$client_id] = $data['fromid'];
               //加入群聊
               $joinGroupIds = explode(',',$data['join_group_ids']);
               foreach ($joinGroupIds as $joinGroupId){
                   Gateway::joinGroup($client_id,'group_'.$joinGroupId);
               }
               $_SESSION['leave_group_ids_'.$client_id] = json_encode($joinGroupIds);//用户离线后离开的群聊ID
               //在线通知
               $onlineData = ['type'=>'online','uid'=>$data['fromid'],'uname'=>$data['from_nickname']];
               Gateway::sendToAll(json_encode($onlineData));
               break;
           case "check_online":
               //检测在线情况
               $ids = explode(',',$data['ids']);
               $data['ids'] = [];
               foreach ($ids as $id){
                   if(Gateway::isUidOnline($id)){
                       array_push($data['ids'],$id);
                   }
               }
               $data['type'] = "check_online_result";
               Gateway::sendToUid($data['fromid'],json_encode($data));
               break;
           case "send":
               $data['type'] = "accept";
               if($data['chat_type'] === 'chat'){
                   Gateway::sendToUid($data['toid'],json_encode($data));
               }else{
                   $groupid = $data['toid'];
                   $exclude_client_id = Gateway::getClientIdByUid($data['fromid']);
                   Gateway::sendToGroup('group_' .$groupid,json_encode($data),$exclude_client_id);
               }
               break;
           case "createGroup":
               $ids = explode(',',$data['ids']);
               foreach ($ids as $id){
                   $clientid = Gateway::getClientIdByUid($id);
                   if($clientid){
                       Gateway::joinGroup($clientid,$data['group_id']);
                   }
               }
               $idList = Gateway::getAllGroupIdList();
               Gateway::sendToUid($data['fromid'],json_encode($idList));

       }
   }
   
   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {
       //离线通知
       echo "\n".$client_id."离线了\n";
       $uid = $_SESSION['uid_'.$client_id];
       $leveGroupIds = json_decode($_SESSION['leave_group_ids_'.$client_id],true);
       foreach ($leveGroupIds as $leveGroupId){
           Gateway::leaveGroup($client_id,'group_'.$leveGroupId);//离开群聊
       }
       $data = ['type'=>'offline','uid'=>$uid];
       Gateway::sendToAll(json_encode($data));
       //释放session
       unset($_SESSION['uid_'.$client_id]);
       unset($_SESSION['leave_group_ids_'.$client_id]);
   }
}
