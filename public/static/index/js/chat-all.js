;!function (w) {
    "use strict";
    let d = w.document;
    let o = w.options || [];
    let n = w.naranja;
    let l = w.layui;
    let $ = l.jquery;
    let audio = new w.Audio('/static/audio/notifications/001_Bell.ogg');//消息提示音
    let ct = null;//聊天类型，私聊或者群聊
    let gn = null;//当前打开的群聊名称
    let ws = null;
    w.chat = {
        init: (url) => {
            ws = new WebSocket(url);
            ws.onopen = (e) => {
                let initData = {
                    type: "init",
                    fromid: o.from_id,//需要绑定的用户id
                    from_nickname: o.from_nickname,//通知好友我上线了
                    join_group_ids: o.join_group_ids.join(',')//需要加入的群聊id
                };
                ws.send(JSON.stringify(initData));
                //检测用户在线情况
                var onlineData = {
                    type: "check_online",
                    fromid: o.from_id,
                    ids: o.check_online_ids.join(',')
                };
                ws.send(JSON.stringify(onlineData));
            }
        },
        run: (url) => {
            chat.init(url);
            ws.onmessage = (e) => {
                let data = JSON.parse(e.data);
                //console.log(data);
                let html = "";
                switch (data.type) {
                    case "check_online_result":
                        if (data.ids.length > 0) {//有好友在线，更改为在线状态
                            data.ids.map(function (id) {
                                chat.status(id);
                            });
                        }
                        break;
                    case "online":
                        if (data.uid != o.from_id) {
                            audio.play();
                            chat.status(data.uid);
                            //上线提示
                            chat.notification(data.uid, "#member" + data.uid, "chat", data.uname + "上线了", "聊天");
                        }
                        break;
                    case "offline":
                        //好友实时离线状态更新
                        chat.status(data.uid, 'offline');
                        break;
                    case "accept":
                        let text = data.content;
                        if (data.text_type == 2) {
                            text = "[图片消息]";
                        }
                        if (data.chat_type === 'chat') {
                            chat.update_chat(data.fromid, text, data.create_time);
                            //聊天窗口接受到信息
                            if (data.toid == o.from_id && data.fromid == o.to_id) {
                                //更新为已读状态
                                $.post('/index/read', {fromid: o.from_id, toid: o.to_id}, function (res) {
                                    console.log(res);
                                });
                                html = '<p class="nickname">' + data.from_nickname + '</p><article><div class="avatar"><img alt="avatar" src="' + data.from_avatar + '" /></div><div class="msg"><div class="tri"></div><div class="msg_inner">' + chat.replace_em(data.content) + '</div></article>';
                                if (data.text_type == 2) {
                                    html = '<p class="nickname">' + data.from_nickname + '</p><article><div class="avatar"><img alt="avatar" src="' + data.from_avatar + '" /></div><div class="msg"><div class="tri"></div><div class="msg_inner"><img src="' + data.content + '" width="120em" height="120em"/></div></article>';
                                }
                                $("#messages").append(html);
                                chat.scroll_bottom("#messages");
                            } else {
                                //消息提示
                                audio.play();
                                chat.notification(data.fromid, "#member" + data.fromid, "chat", data.from_nickname + "来消息了", "回复");
                                //更新未读消息徽章
                                chat.update_badge("#member" + data.fromid);
                                //顶置聊天列表
                                chat.chat_top("#member" + data.fromid);
                            }
                        } else {
                            chat.update_chat(data.toid, data.from_nickname + '：' + text, data.create_time, "#group");
                            //群聊聊天窗口接受到信息
                            if (data.toid == o.to_id) {
                                html = '<p class="nickname">' + data.from_nickname + '</p><article><div class="avatar"><img alt="avatar" src="' + data.from_avatar + '" /></div><div class="msg"><div class="tri"></div><div class="msg_inner">' + chat.replace_em(data.content) + '</div></article>';
                                if (data.text_type == 2) {
                                    html = '<p class="nickname">' + data.from_nickname + '</p><article><div class="avatar"><img alt="avatar" src="' + data.from_avatar + '" /></div><div class="msg"><div class="tri"></div><div class="msg_inner"><img src="' + data.content + '" width="120em" height="120em"/></div></article>';
                                    data.content = "[图片信息]";
                                }
                                $("#messages").append(html);
                                chat.scroll_bottom("#messages");
                            } else {
                                //消息提示
                                audio.play();
                                chat.notification(data.toid, "#group" + data.toid, "groupchat", data.group_name + "群聊有新消息", "查看");
                                //更新未读消息徽章
                                chat.update_badge("#group" + data.toid)
                                if (data.text_type == 2) {
                                    data.content = "[图片信息]";
                                }
                                //顶置聊天列表
                                chat.chat_top("#group" + data.toid);
                            }
                        }
                        break;
                    case "createGroup":
                        //创建群聊
                        chat.create_group(data.groupId, data.groupName, data.count, data.members, data.content, data.createTime);
                        break;
                    case "joinGroupTips":
                        if (!data.join_group_ids.includes(o.from_id)) {
                            if (o.to_id == data.group_id && ct == "groupchat") {
                                //打开了当前群聊窗口
                                chat.add_tips(data.tips);
                                chat.update_group_count(data.count);
                            }
                            chat.update_chat(data.group_id, data.tips, data.join_time, "#group", data.count);
                        }
                        break;
                    case "joinGroup":
                        //创建群聊
                        chat.create_group(data.group.id, data.group.name, data.count, data.group.members, data.tips, data.join_time);
                        break;
                    default:
                        break;
                }
            }
            //加载监听事件
            chat.listen();
        },
        listen: () => {
            //事件委托监听聊天列表点击事件
            $("#chat-area").on("click", "li", function () {
                let type = $(this).attr("data-type");
                let id = $(this).attr("data-id");
                let dataJson = JSON.stringify({id: id, type: type});
                $(".view").attr('data', dataJson);
                $(".view").attr('data-url', '/index/view');
                if (type === 'groupchat') {
                    let count = $(this).attr('data-count');
                    $(".view").attr('data-title', '聊天消息（' + count + '）');
                }
                chat.open(id, $(this), type);
            });
            //Ajax异步请求弹出层
            $(".ajaxRequest").on('click', function () {
                let str = $(this).attr('data-toggle') || '';
                if (str == 'ajaxModal') {
                    let loadIndex = l.layer.load(0, {shade: false});
                    let url = $(this).attr('data-url');
                    let title = $(this).attr('data-title') ?? false;
                    let data = $(this).attr('data');
                    $.get(url, JSON.parse(data), function (content) {
                        l.layer.close(loadIndex);
                        l.layer.open({
                            type: 1
                            , title: title //不显示标题栏
                            , closeBtn: false
                            , area: '400px;'
                            , shade: 0.8
                            , id: 'lay_friends_table' //设定一个id，防止重复弹出
                            , btn: ['确定', '取消']
                            , content: content
                        });
                    })
                }
            });
            //发送消息
            $(".send-message").click(function () {
                chat.send_message($("textarea").val());
            });
            //回车发送消息
            $(document).keydown(function (e) {
                if (e.keyCode === 13) {
                    chat.send_message($("textarea").val());
                }
            });
            //上传文件
            $(".upload-file").click(function () {
                $("#file").click();
            });
            $("#file").change(function () {
                let file = $(this)[0].files[0];
                let formData = new FormData();
                formData.append('file', file);
                $.ajax({
                    url: "/index/uploadFile",
                    type: "post",
                    cache: false,
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        if (res.status) {
                            chat.send_message(res.data.content, 2);
                        } else {
                            console.log(res.data.message);
                        }
                    }
                })
            });
            //加载qqFace
            $('.emotion').qqFace({
                assign: 'saytext',
                path: '/static/public/qqFace/arclist/'	//表情存放的路径
            });
        },
        status: (id, s = 'online') => {
            //用户上线离线状态更新
            let os = $("#member" + id + ">.avatar>.color");
            s === 'online' ? os.show() : os.hide();
        },
        open: (id, obj, chat_type = 'chat') => {
            //打开聊天窗口
            $(obj).siblings().removeClass('active');
            $(obj).addClass('active');
            let toNickname = $(obj).attr('data-nickname');
            if (chat_type === 'groupchat') {
                let count = $(obj).attr('data-count');
                toNickname += "（" + count + "）";
                gn = toNickname;
            }
            //设置聊天窗口头部好友或群聊名称
            $("#header>h4").text(toNickname);
            //处理消息徽章
            let is_read = chat.update_badge(obj, 'read');
            o.to_id = id;
            ct = chat_type;
            let html = "";
            let className = "";
            let avatar = "";
            $.post('/index/getlist', {
                fromid: o.from_id,
                toid: o.to_id,
                is_read: is_read,
                chat_type: chat_type
            }, function (res) {
                if (res) {
                    res.map(function (data) {
                        avatar = data.fromer.avatar;
                        className = data.fromid == o.from_id ? "right" : "";
                        if (data.type == 2) {
                            html += '<p class="nickname ' + className + '">' + data.fromer.nickname + '</p><article class="' + className + '"><div class="avatar"><img alt="avatar" src="' + data.fromer.avatar + '" /></div><div class="msg"><div class="tri"></div><div class="msg_inner"><img src=" ' + data.content + '" width="120em" height="120em"/></div></article>';
                        } else {
                            html += '<p class="nickname ' + className + '">' + data.fromer.nickname + '</p><article class="' + className + '"><div class="avatar"><img alt="avatar" src="' + data.fromer.avatar + '" /></div><div class="msg"><div class="tri"></div><div class="msg_inner">' + chat.replace_em(data.content) + '</div></article>';
                        }
                    });
                    $("#messages").html(html);
                }
                $("#main").show();
                chat.scroll_bottom("#messages");
            });
        },
        update_badge: (obj, op = 'accept') => {
            let badge = $(obj).find('.badge');
            if (op === 'accept') {
                //收到消息，更新消息徽章
                badge.text(badge.text() * 1 + 1);
                if (badge.hasClass('hide')) {
                    badge.removeClass('hide');
                }
            } else {
                //读取消息，更新消息徽章
                let is_read = 1;
                if (!badge.hasClass('hide')) {
                    badge.text(0);
                    badge.addClass('hide');
                    is_read = 0;
                }
                return is_read;
            }
        },
        send_message: (message, text_type = 1) => {
            //发送消息
            if (message.length <= 0) {
                return;
            }
            let data = {
                type: "send",
                text_type: text_type,
                fromid: o.from_id,
                toid: o.to_id,
                is_read: 0,
                from_nickname: o.from_nickname,
                from_avatar: o.from_avatar,
                content: message

            }
            chat.save_message(data);
        },
        save_message: (data) => {
            //消息持久化
            data.chat_type = ct;
            data.group_name = gn;
            $.post("/index/save", data, function (res) {
                res = JSON.parse(res);
                console.log(res);
                if (res.status) {
                    data.create_time = res.result.message.create_time;
                    let html = '<p class="nickname right">' + data.from_nickname + '</p><article class="right"><div class="avatar"><img alt="avatar" src="' + data.from_avatar + '" /></div><div class="msg"><div class="tri"></div><div class="msg_inner">' + chat.replace_em(data.content) + '</div></article>';
                    let text = data.content;
                    if (data.text_type == 2) {
                        html = '<p class="nickname right">' + data.from_nickname + '</p><article class="right"><div class="avatar"><img alt="avatar" src="' + data.from_avatar + '" /></div><div class="msg"><div class="tri"></div><div class="msg_inner"><img src=" ' + data.content + '" width="120em" height="120em"/></div></article>';
                        text = "[图片消息]";
                    }
                    $("#messages").append(html);
                    chat.scroll_bottom("#messages");
                    $("textarea").val("");
                    let idStr = ct === "chat" ? "#member" : "#group";
                    let nickname = ct === "groupchat" ? data.from_nickname + "：" : "";
                    $(idStr + data.toid + ">.main_li>.text").text(nickname + text);
                    $(idStr + data.toid + ">.time").text(data.create_time);
                    //让服务器通知对方有新消息
                    let dataJson = JSON.stringify(data);
                    ws.send(dataJson);

                }
            });
        },
        notification: (id, obj, chat_type, text1, text2, timeout = 5000) => {
            //通知
            n()['success']({
                title: '新消息',
                text: text1,
                timeout: timeout,
                buttons: [{
                    text: text2,
                    click: function (e) {
                        chat.open(id, obj, chat_type);
                    }
                }, {
                    text: '忽略',
                    click: function (e) {
                        e.closeNotification();
                    }
                }]
            });
        },
        chat_top: (obj) => {
            //聊天顶置
            let chatLi = $(obj).prop('outerHTML');
            $(obj).remove();
            $("#chat-area").prepend($(chatLi));
        },
        scroll_bottom: (obj) => {
            //滚到底部
            $(obj).scrollTop($(obj)[0].scrollHeight);
        },
        replace_em: (str) => {
            //em表情包替换
            str = str.replace(/\</g, '&lt;');

            str = str.replace(/\>/g, '&gt;');

            str = str.replace(/\n/g, '<br/>');

            str = str.replace(/\[em_([0-9]*)\]/g, '<img src="/static/public/qqFace/arclist/$1.gif" border="0" />');

            return str;
        },
        create_group: (id, name, count, members, content, time) => {
            //创建群聊
            let html = '<li id="group' + id + '" data-id="' + id + '" data-type="groupchat" data-nickname="' + name + '" data-count="' + count + '"><div class="avatar"><span class="badge hide"></span><div class="avatar-box">';
            members.map(function (member) {
                html += '<img alt="avatar" src="' + member.avatar + '" />';
            })
            html += '</div></div><div class="main_li"><div class="username">' + name + '</div><div class="text">' + content + '</div></div><div class="time">' + time + '</div>';
            $("#chat-area").prepend(html);
            chat.open(id, "#group" + id, 'groupchat');
            chat.add_tips(content);
            let dataJson = JSON.stringify({
                id: id,
                type: "groupchat"
            });
            $(".view").attr('data', dataJson);
            $(".view").attr('data-url', '/index/view');
            $(".view").attr('data-title', '聊天消息（' + count + '）');
        },
        add_tips: (tips) => {
            //聊天窗口添加提示
            let tipsHTML = '<article class="center"><span class="tips">' + tips + '</span></article>';
            $("#messages").append(tipsHTML);

        },
        update_chat: (id, content, time, type = "#member", count = 0) => {
            //更新聊天列表
            $(type + id + ">.main_li>.text").text(content);
            $(type + id + ">.time").text(time);
            if (type === "#group" && count > 0) {
                $(type + id).attr("data-count", count);
            }
        },
        update_group_count: (count) => {
            let to_nickname = $("#header>.to-nickname").text();
            let new_to_nickname = to_nickname.replace(/\（.*?\）/,'（'+count+'）');
            $("#header>.to-nickname").text(new_to_nickname);
            let title = $(".view").attr("data-title");
            let new_title = title.replace(/\（.*?\）/,'（'+count+'）');
            $(".view").attr("data-title", new_title);
        }
    };
}(window);
chat.run('ws://127.0.0.1:8282');