/*
Navicat MySQL Data Transfer

Source Server         : master
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : tp5_chat

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2020-12-09 19:15:26
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for cm_chat
-- ----------------------------
DROP TABLE IF EXISTS `cm_chat`;
CREATE TABLE `cm_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromid` int(11) DEFAULT '0',
  `toid` int(11) DEFAULT NULL,
  `content` text,
  `type` tinyint(4) DEFAULT NULL,
  `is_read` tinyint(4) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_fromid` (`fromid`) USING BTREE,
  KEY `idx_toid` (`toid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=154 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cm_chat
-- ----------------------------
INSERT INTO `cm_chat` VALUES ('1', '1', '2', '哈哈哈哈', '1', '1', '1600324504', '1600324504');
INSERT INTO `cm_chat` VALUES ('2', '2', '1', '嘻嘻嘻', '1', '1', '1600324524', '1600324524');
INSERT INTO `cm_chat` VALUES ('3', '1', '2', '你好啊', '1', '1', '1600324584', '1600324584');
INSERT INTO `cm_chat` VALUES ('4', '2', '1', '你好', '1', '1', '1600324603', '1600324603');
INSERT INTO `cm_chat` VALUES ('5', '1', '2', '略', '1', '1', '1600326387', '1600326387');
INSERT INTO `cm_chat` VALUES ('6', '2', '1', '哈哈哈', '1', '1', '1600326703', '1600326703');
INSERT INTO `cm_chat` VALUES ('7', '3', '1', '哈哈哈哈', '1', '1', '1600326937', '1600326937');
INSERT INTO `cm_chat` VALUES ('9', '1', '4', 'hi hi hi\n', '1', '1', '2147483647', '1600328417');
INSERT INTO `cm_chat` VALUES ('10', '1', '2', '4444', '1', '1', '1600330769', '1600330769');
INSERT INTO `cm_chat` VALUES ('11', '4', '1', 'HELLO', '1', '1', '1600331197', '1600331197');
INSERT INTO `cm_chat` VALUES ('12', '2', '4', '呀呀呀呀', '1', '1', '1600331257', '1600331257');
INSERT INTO `cm_chat` VALUES ('13', '4', '3', '大撒大撒', '1', '1', '1600332194', '1600332194');
INSERT INTO `cm_chat` VALUES ('14', '3', '4', '正常秩序', '1', '1', '1600332348', '1600332348');
INSERT INTO `cm_chat` VALUES ('15', '1', '4', '现场Z操作', '1', '1', '1600332412', '1600332412');
INSERT INTO `cm_chat` VALUES ('16', '4', '3', '啊搜索', '1', '1', '1600332521', '1600332521');
INSERT INTO `cm_chat` VALUES ('17', '4', '3', '撒大苏打', '1', '1', '1600332631', '1600332631');
INSERT INTO `cm_chat` VALUES ('18', '4', '2', '非常的舒服的', '1', '1', '1600332645', '1600332645');
INSERT INTO `cm_chat` VALUES ('19', '2', '1', '3333', '1', '1', '1600332681', '1600332681');
INSERT INTO `cm_chat` VALUES ('20', '1', '2', '自行车行政村行政村', '1', '1', '1600333070', '1600333070');
INSERT INTO `cm_chat` VALUES ('21', '4', '1', '自行车支持下', '1', '1', '1600333271', '1600333271');
INSERT INTO `cm_chat` VALUES ('22', '1', '3', '爱心大使的', '1', '1', '1600333327', '1600333327');
INSERT INTO `cm_chat` VALUES ('23', '4', '2', '小妹妹', '1', '1', '1600333387', '1600333387');
INSERT INTO `cm_chat` VALUES ('24', '3', '6', '啊大苏打是', '1', '1', '1600333527', '1600333527');
INSERT INTO `cm_chat` VALUES ('25', '3', '1', '啊SA是', '1', '1', '1600333556', '1600333556');
INSERT INTO `cm_chat` VALUES ('26', '2', '4', 'xcxcxz', '1', '1', '1600334373', '1600334373');
INSERT INTO `cm_chat` VALUES ('27', '3', '1', 'hahahah', '1', '1', '1600334585', '1600334585');
INSERT INTO `cm_chat` VALUES ('28', '1', '4', '撒旦士大夫士大夫', '1', '1', '1600334701', '1600334701');
INSERT INTO `cm_chat` VALUES ('29', '3', '1', '啊啊啊啊', '1', '1', '1600335447', '1600335447');
INSERT INTO `cm_chat` VALUES ('30', '4', '1', '哈哈哈哈哈啊', '1', '1', '1600335457', '1600335457');
INSERT INTO `cm_chat` VALUES ('31', '4', '2', '扭扭捏捏', '1', '1', '1600336613', '1600336613');
INSERT INTO `cm_chat` VALUES ('32', '4', '2', '扭扭捏捏', '1', '1', '1600336620', '1600336620');
INSERT INTO `cm_chat` VALUES ('33', '4', '2', '扭扭捏捏', '1', '1', '1600336621', '1600336621');
INSERT INTO `cm_chat` VALUES ('34', '4', '2', '扭扭捏捏', '1', '1', '1600336621', '1600336621');
INSERT INTO `cm_chat` VALUES ('35', '4', '2', '扭扭捏捏', '1', '1', '1600336622', '1600336622');
INSERT INTO `cm_chat` VALUES ('36', '4', '1', '你好啊', '1', '1', '1600337544', '1600337544');
INSERT INTO `cm_chat` VALUES ('37', '4', '2', '女孩你很酷我', '1', '1', '1600337730', '1600337730');
INSERT INTO `cm_chat` VALUES ('38', '2', '4', '哈哈哈哈', '1', '1', '1600337822', '1600337822');
INSERT INTO `cm_chat` VALUES ('39', '2', '4', '大大实打实', '1', '1', '1600337879', '1600337879');
INSERT INTO `cm_chat` VALUES ('40', '4', '2', '发放', '1', '1', '1600337982', '1600337982');
INSERT INTO `cm_chat` VALUES ('41', '4', '2', 'Z大撒大撒', '1', '1', '1600338025', '1600338025');
INSERT INTO `cm_chat` VALUES ('42', '4', '2', '1111', '1', '1', '1600338265', '1600338265');
INSERT INTO `cm_chat` VALUES ('43', '2', '4', '22222', '1', '1', '1600338305', '1600338305');
INSERT INTO `cm_chat` VALUES ('44', '4', '2', '哈哈哈', '1', '1', '1600395333', '1600395333');
INSERT INTO `cm_chat` VALUES ('45', '4', '2', '嗯嗯嗯', '1', '1', '1600395358', '1600395358');
INSERT INTO `cm_chat` VALUES ('46', '4', '2', '哈哈哈哈', '1', '1', '1600395380', '1600395380');
INSERT INTO `cm_chat` VALUES ('47', '1', '4', '阿斯顿撒旦', '1', '1', '1600395420', '1600395420');
INSERT INTO `cm_chat` VALUES ('48', '1', '2', '你好啊', '1', '1', '1600395472', '1600395472');
INSERT INTO `cm_chat` VALUES ('49', '1', '2', '在干嘛呢', '1', '1', '1600395488', '1600395488');
INSERT INTO `cm_chat` VALUES ('50', '1', '4', '阿达', '1', '1', '1600395559', '1600395559');
INSERT INTO `cm_chat` VALUES ('51', '1', '2', '萨达打撒打撒·1', '1', '1', '1600396021', '1600396021');
INSERT INTO `cm_chat` VALUES ('52', '4', '2', '那你', '1', '1', '1600397800', '1600397800');
INSERT INTO `cm_chat` VALUES ('53', '4', '2', '松大', '1', '1', '1600397862', '1600397862');
INSERT INTO `cm_chat` VALUES ('54', '1', '4', 'HAHAHA', '1', '1', '1600401426', '1600401426');
INSERT INTO `cm_chat` VALUES ('55', '1', '3', '在干嘛', '1', '1', '1600401458', '1600401458');
INSERT INTO `cm_chat` VALUES ('56', '1', '3', '打游戏嘛', '1', '1', '1600401480', '1600401480');
INSERT INTO `cm_chat` VALUES ('57', '3', '1', '在吗', '1', '1', '1600404597', '1600404597');
INSERT INTO `cm_chat` VALUES ('58', '3', '1', '在干嘛呢', '1', '1', '1600404633', '1600404633');
INSERT INTO `cm_chat` VALUES ('59', '4', '1', '嘿嘿', '1', '1', '1600404685', '1600404685');
INSERT INTO `cm_chat` VALUES ('60', '4', '1', '爱你哟', '1', '1', '1600404713', '1600404713');
INSERT INTO `cm_chat` VALUES ('61', '2', '1', '你不爱我了吗', '1', '1', '1600404973', '1600404973');
INSERT INTO `cm_chat` VALUES ('62', '2', '1', '回答我啊', '1', '1', '1600404994', '1600404994');
INSERT INTO `cm_chat` VALUES ('63', '4', '1', '[em_17]', '1', '1', '1600406255', '1600406255');
INSERT INTO `cm_chat` VALUES ('64', '4', '1', '[em_17]', '1', '1', '1600406256', '1600406256');
INSERT INTO `cm_chat` VALUES ('65', '2', '4', '[em_5][em_20]', '1', '1', '1600406359', '1600406359');
INSERT INTO `cm_chat` VALUES ('66', '2', '4', '[em_5][em_20]', '1', '1', '1600406360', '1600406360');
INSERT INTO `cm_chat` VALUES ('67', '4', '2', '啊啊啊', '1', '1', '1600406390', '1600406390');
INSERT INTO `cm_chat` VALUES ('68', '4', '2', '啊啊啊', '1', '1', '1600406391', '1600406391');
INSERT INTO `cm_chat` VALUES ('69', '2', '4', '[em_48]', '1', '1', '1600406541', '1600406541');
INSERT INTO `cm_chat` VALUES ('70', '2', '4', '[em_48]', '1', '1', '1600406542', '1600406542');
INSERT INTO `cm_chat` VALUES ('71', '2', '3', '[em_19]你好啊', '1', '1', '1600406601', '1600406601');
INSERT INTO `cm_chat` VALUES ('72', '3', '2', '-webkit-line-clamp 用来限制在一个块元素显示的文本的行数,这是一个不规范的属性（unsupported WebKit property），它没有出现在 CSS 规范草案中。\n\ndisplay: -webkit-box 将对象作为弹性伸缩盒子模型显示 。\n\n-webkit-box-orient 设置或检索伸缩盒对象的子元素的排列方式 。\n\ntext-overflow: ellipsis 以用来多行文本的情况下，用省略号“…”隐藏超出范围的文本。', '1', '1', '1600406683', '1600406683');
INSERT INTO `cm_chat` VALUES ('73', '3', '1', '哈哈哈', '1', '1', '1600409657', '1600409657');
INSERT INTO `cm_chat` VALUES ('74', '4', '1', '/uploads/chat/chat_img_5f6453c17ff0f.png', '2', '1', '1600410561', '1600410561');
INSERT INTO `cm_chat` VALUES ('75', '2', '1', '/uploads/chat/chat_img_5f64552e71163.png', '2', '1', '1600410926', '1600410926');
INSERT INTO `cm_chat` VALUES ('76', '1', '6', '[em_32]', '1', '1', '1600411565', '1600411565');
INSERT INTO `cm_chat` VALUES ('77', '2', '1', '', '1', '1', '1600412694', '1600412694');
INSERT INTO `cm_chat` VALUES ('78', '1', '2', 'aaaaa', '1', '1', '1600412760', '1600412760');
INSERT INTO `cm_chat` VALUES ('79', '1', '4', '[em_62]', '1', '1', '1600412813', '1600412813');
INSERT INTO `cm_chat` VALUES ('80', '1', '4', '[em_49]', '1', '1', '1600412829', '1600412829');
INSERT INTO `cm_chat` VALUES ('81', '1', '4', 'nnnn', '1', '1', '1600417543', '1600417543');
INSERT INTO `cm_chat` VALUES ('82', '1', '4', 'nb', '1', '1', '1600417553', '1600417553');
INSERT INTO `cm_chat` VALUES ('83', '4', '2', '[em_19]', '1', '1', '1600417949', '1600417949');
INSERT INTO `cm_chat` VALUES ('84', '2', '4', '/uploads/chat/chat_img_5f6470cbc201f.png', '2', '1', '1600417995', '1600417995');
INSERT INTO `cm_chat` VALUES ('85', '2', '3', 'hahaha', '1', '1', '1600418582', '1600418582');
INSERT INTO `cm_chat` VALUES ('86', '1', '2', '[em_3]', '1', '1', '1600418613', '1600418613');
INSERT INTO `cm_chat` VALUES ('87', '2', '4', '[em_49][em_71]', '1', '1', '1600418660', '1600418660');
INSERT INTO `cm_chat` VALUES ('88', '2', '4', '[em_48]', '1', '1', '1600418721', '1600418721');
INSERT INTO `cm_chat` VALUES ('89', '3', '2', 'daada', '1', '1', '1600418820', '1600418820');
INSERT INTO `cm_chat` VALUES ('90', '3', '2', 'asdasad', '1', '1', '1600418829', '1600418829');
INSERT INTO `cm_chat` VALUES ('91', '2', '3', '[em_6]', '1', '1', '1600419131', '1600419131');
INSERT INTO `cm_chat` VALUES ('92', '1', '4', '撒旦撒', '1', '1', '1600419854', '1600419854');
INSERT INTO `cm_chat` VALUES ('93', '1', '4', '撒旦撒', '1', '1', '1600419855', '1600419855');
INSERT INTO `cm_chat` VALUES ('94', '1', '4', '撒旦撒', '1', '1', '1600419855', '1600419855');
INSERT INTO `cm_chat` VALUES ('95', '1', '4', '撒旦撒', '1', '1', '1600419855', '1600419855');
INSERT INTO `cm_chat` VALUES ('96', '1', '4', '啊啊', '1', '1', '1600419868', '1600419868');
INSERT INTO `cm_chat` VALUES ('97', '1', '4', '啊啊', '1', '1', '1600419868', '1600419868');
INSERT INTO `cm_chat` VALUES ('98', '1', '4', '啊啊', '1', '1', '1600419868', '1600419868');
INSERT INTO `cm_chat` VALUES ('99', '1', '4', '啊啊', '1', '1', '1600419869', '1600419869');
INSERT INTO `cm_chat` VALUES ('100', '2', '4', '[em_17]', '1', '1', '1600655290', '1600655290');
INSERT INTO `cm_chat` VALUES ('101', '2', '4', '[em_49]', '1', '1', '1600655371', '1600655371');
INSERT INTO `cm_chat` VALUES ('102', '2', '1', '[em_6]', '1', '1', '1600655549', '1600655549');
INSERT INTO `cm_chat` VALUES ('103', '4', '3', '[em_33]', '1', '1', '1600657064', '1600657064');
INSERT INTO `cm_chat` VALUES ('104', '4', '3', '111', '1', '1', '1600657576', '1600657576');
INSERT INTO `cm_chat` VALUES ('105', '4', '3', '11122', '1', '1', '1600657649', '1600657649');
INSERT INTO `cm_chat` VALUES ('106', '1', '2', '[em_51]', '1', '1', '1600658593', '1600658593');
INSERT INTO `cm_chat` VALUES ('107', '1', '3', '[em_41]', '1', '1', '1600658673', '1600658673');
INSERT INTO `cm_chat` VALUES ('108', '2', '1', '[em_52]', '1', '1', '1600658855', '1600658855');
INSERT INTO `cm_chat` VALUES ('109', '2', '1', '/uploads/chat/chat_img_5f681dec57852.png', '2', '1', '1600658924', '1600658924');
INSERT INTO `cm_chat` VALUES ('110', '4', '2', '阿斯顿撒旦', '1', '1', '1600659806', '1600659806');
INSERT INTO `cm_chat` VALUES ('111', '4', '2', '脚后跟脚后跟', '1', '1', '1600659809', '1600659809');
INSERT INTO `cm_chat` VALUES ('112', '4', '2', '[em_47]', '1', '1', '1600659813', '1600659813');
INSERT INTO `cm_chat` VALUES ('113', '4', '2', '[em_72]', '1', '1', '1600659818', '1600659818');
INSERT INTO `cm_chat` VALUES ('114', '4', '2', '[em_28]', '1', '1', '1600659821', '1600659821');
INSERT INTO `cm_chat` VALUES ('115', '4', '2', '/uploads/chat/chat_img_5f682170597fc.png', '2', '1', '1600659824', '1600659824');
INSERT INTO `cm_chat` VALUES ('116', '4', '2', '[em_17]', '1', '1', '1600659827', '1600659827');
INSERT INTO `cm_chat` VALUES ('117', '2', '4', '[em_33]', '1', '1', '1600659839', '1600659839');
INSERT INTO `cm_chat` VALUES ('118', '2', '4', '钱钱钱', '1', '1', '1600659842', '1600659842');
INSERT INTO `cm_chat` VALUES ('119', '2', '4', '1111222', '1', '1', '1600659845', '1600659845');
INSERT INTO `cm_chat` VALUES ('120', '2', '4', '[em_25]', '1', '1', '1600659849', '1600659849');
INSERT INTO `cm_chat` VALUES ('121', '2', '4', '[em_41]', '1', '1', '1600659851', '1600659851');
INSERT INTO `cm_chat` VALUES ('122', '2', '4', '[em_5]', '1', '1', '1600659855', '1600659855');
INSERT INTO `cm_chat` VALUES ('123', '4', '2', '哈哈哈笑死我了', '1', '1', '1600660580', '1600660580');
INSERT INTO `cm_chat` VALUES ('124', '5', '1', '[em_3]', '1', '1', '1600848206', '1600848206');
INSERT INTO `cm_chat` VALUES ('125', '1', '2', '[em_9]', '1', '1', '1600998458', '1600998458');
INSERT INTO `cm_chat` VALUES ('126', '4', '1', '11111', '1', '1', '1607412194', '1607412194');
INSERT INTO `cm_chat` VALUES ('127', '4', '2', 'fffffff', '1', '1', '1607412206', '1607412206');
INSERT INTO `cm_chat` VALUES ('128', '4', '2', '111122222', '1', '1', '1607412230', '1607412230');
INSERT INTO `cm_chat` VALUES ('129', '4', '1', '[em_1]', '1', '1', '1607428056', '1607428056');
INSERT INTO `cm_chat` VALUES ('130', '4', '6', '[em_48]', '1', '1', '1607433315', '1607433315');
INSERT INTO `cm_chat` VALUES ('131', '5', '4', '[em_17]', '1', '1', '1607433447', '1607433447');
INSERT INTO `cm_chat` VALUES ('132', '4', '5', '/uploads/chat/chat_img_5fcf7cf31c2be.png', '2', '1', '1607433459', '1607433459');
INSERT INTO `cm_chat` VALUES ('133', '6', '4', '[em_12]', '1', '1', '1607434021', '1607434021');
INSERT INTO `cm_chat` VALUES ('134', '5', '4', '/uploads/chat/chat_img_5fcf80687633b.png', '2', '1', '1607434344', '1607434344');
INSERT INTO `cm_chat` VALUES ('135', '5', '4', '[em_49]', '1', '1', '1607434364', '1607434364');
INSERT INTO `cm_chat` VALUES ('136', '3', '4', '[em_56]', '1', '1', '1607441949', '1607441949');
INSERT INTO `cm_chat` VALUES ('137', '2', '1', '[em_34]', '1', '1', '1607488637', '1607488637');
INSERT INTO `cm_chat` VALUES ('138', '2', '3', '[em_42]', '1', '1', '1607488652', '1607488652');
INSERT INTO `cm_chat` VALUES ('139', '3', '2', '[em_38]', '1', '1', '1607488690', '1607488690');
INSERT INTO `cm_chat` VALUES ('140', '1', '2', '[em_19]', '1', '1', '1607488822', '1607488822');
INSERT INTO `cm_chat` VALUES ('141', '6', '1', '在干嘛', '1', '0', '1607489305', '1607489305');
INSERT INTO `cm_chat` VALUES ('142', '2', '1', '[em_52]', '1', '0', '1607489490', '1607489490');
INSERT INTO `cm_chat` VALUES ('143', '6', '1', '[em_61]', '1', '0', '1607489596', '1607489596');
INSERT INTO `cm_chat` VALUES ('144', '5', '4', '[em_48]', '1', '1', '1607499257', '1607499257');
INSERT INTO `cm_chat` VALUES ('145', '5', '3', '[em_17]', '1', '1', '1607500678', '1607500678');
INSERT INTO `cm_chat` VALUES ('146', '3', '5', '[em_19]', '1', '1', '1607500838', '1607500838');
INSERT INTO `cm_chat` VALUES ('147', '6', '3', '[em_35]', '1', '1', '1607500959', '1607500959');
INSERT INTO `cm_chat` VALUES ('148', '6', '3', '[em_36]', '1', '1', '1607500971', '1607500971');
INSERT INTO `cm_chat` VALUES ('149', '6', '4', '[em_75]', '1', '0', '1607501173', '1607501173');
INSERT INTO `cm_chat` VALUES ('150', '6', '5', '[em_63]', '1', '1', '1607501555', '1607501555');
INSERT INTO `cm_chat` VALUES ('151', '5', '6', '[em_27]', '1', '1', '1607501838', '1607501838');
INSERT INTO `cm_chat` VALUES ('152', '5', '6', '[em_28]', '1', '1', '1607501853', '1607501853');
INSERT INTO `cm_chat` VALUES ('153', '5', '6', '[em_34]', '1', '1', '1607510706', '1607510706');

-- ----------------------------
-- Table structure for cm_group
-- ----------------------------
DROP TABLE IF EXISTS `cm_group`;
CREATE TABLE `cm_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `master_id` int(11) DEFAULT NULL COMMENT '群主id',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cm_group
-- ----------------------------
INSERT INTO `cm_group` VALUES ('23', 'hahaha', null, '4', '1607423246', null);
INSERT INTO `cm_group` VALUES ('24', '燥起来', null, '4', '1607423345', null);
INSERT INTO `cm_group` VALUES ('25', '午夜嗨歌', null, '2', '1607444179', null);
INSERT INTO `cm_group` VALUES ('26', '开黑1群', null, '2', '1607444641', null);
INSERT INTO `cm_group` VALUES ('27', '开黑2群', null, '2', '1607444741', null);
INSERT INTO `cm_group` VALUES ('28', '小猪佩奇', null, '5', '1607445296', null);
INSERT INTO `cm_group` VALUES ('29', '一夜暴富', null, '4', '1607445639', null);
INSERT INTO `cm_group` VALUES ('30', '午夜K歌1群', null, '5', '1607445877', null);

-- ----------------------------
-- Table structure for cm_group_chat
-- ----------------------------
DROP TABLE IF EXISTS `cm_group_chat`;
CREATE TABLE `cm_group_chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromid` int(11) DEFAULT NULL,
  `groupid` int(11) DEFAULT NULL,
  `content` text,
  `type` tinyint(3) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cm_group_chat
-- ----------------------------
INSERT INTO `cm_group_chat` VALUES ('1', '5', '24', '[em_19]', '1', '1607438749', '1607438749');
INSERT INTO `cm_group_chat` VALUES ('2', '6', '24', '[em_21]', '1', '1607438852', '1607438852');
INSERT INTO `cm_group_chat` VALUES ('3', '4', '24', '[em_20]', '1', '1607439947', '1607439947');
INSERT INTO `cm_group_chat` VALUES ('4', '3', '24', '[em_17]', '1', '1607440308', '1607440308');
INSERT INTO `cm_group_chat` VALUES ('5', '6', '24', '[em_20]', '1', '1607440391', '1607440391');
INSERT INTO `cm_group_chat` VALUES ('6', '6', '24', '[em_19]', '1', '1607440545', '1607440545');
INSERT INTO `cm_group_chat` VALUES ('7', '3', '24', '[em_49]', '1', '1607440565', '1607440565');
INSERT INTO `cm_group_chat` VALUES ('8', '3', '24', '[em_35]', '1', '1607440909', '1607440909');
INSERT INTO `cm_group_chat` VALUES ('9', '6', '24', '/uploads/chat/chat_img_5fcf9a1b05150.png', '2', '1607440923', '1607440923');
INSERT INTO `cm_group_chat` VALUES ('10', '4', '23', '[em_21]', '1', '1607441904', '1607441904');
INSERT INTO `cm_group_chat` VALUES ('11', '3', '23', '哈哈哈哈', '1', '1607441915', '1607441915');
INSERT INTO `cm_group_chat` VALUES ('12', '2', '23', '[em_61]', '1', '1607487089', '1607487089');
INSERT INTO `cm_group_chat` VALUES ('13', '6', '30', '[em_68]', '1', '1607498759', '1607498759');
INSERT INTO `cm_group_chat` VALUES ('14', '6', '30', '[em_41]', '1', '1607501100', '1607501100');
INSERT INTO `cm_group_chat` VALUES ('15', '4', '29', '[em_55]', '1', '1607501127', '1607501127');
INSERT INTO `cm_group_chat` VALUES ('16', '5', '24', '[em_63]', '1', '1607508693', '1607508693');
INSERT INTO `cm_group_chat` VALUES ('17', '5', '24', '早起来啦', '1', '1607510722', '1607510722');
INSERT INTO `cm_group_chat` VALUES ('18', '5', '24', '哈哈哈哈', '1', '1607510736', '1607510736');

-- ----------------------------
-- Table structure for cm_member
-- ----------------------------
DROP TABLE IF EXISTS `cm_member`;
CREATE TABLE `cm_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mobile` varchar(20) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `salt` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `email_verify` tinyint(3) DEFAULT '0',
  `nickname` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `autograph` varchar(128) DEFAULT NULL,
  `status` tinyint(3) DEFAULT '1',
  `birthday` int(11) DEFAULT NULL,
  `sex` varchar(4) DEFAULT NULL,
  `birthplace` varchar(128) DEFAULT NULL,
  `qq` varchar(50) DEFAULT NULL,
  `qq_unique` varchar(50) DEFAULT NULL,
  `qq_nickname` varchar(50) DEFAULT NULL,
  `wechat` varchar(50) DEFAULT NULL,
  `wechat_unique` varchar(50) DEFAULT NULL,
  `wechat_nickname` varchar(50) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL COMMENT 'token认证',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cm_member
-- ----------------------------
INSERT INTO `cm_member` VALUES ('1', null, null, null, null, '0', '酷男孩', '/static/images/avatar/coolboy.jpg', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `cm_member` VALUES ('2', null, null, null, null, '0', '酷女孩', '/static/images/avatar/coolgirl.jpg', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `cm_member` VALUES ('3', null, null, null, null, '0', '帅男孩', '/static/images/avatar/admin.jpg', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `cm_member` VALUES ('4', null, null, null, null, '0', '帅女孩', '/static/images/avatar/user4.png', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `cm_member` VALUES ('5', null, null, null, null, '0', '丑女孩', '/static/images/avatar/user3.jpg', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `cm_member` VALUES ('6', null, null, null, null, '0', '丑男孩', '/static/images/avatar/user1.jpg', null, '1', null, null, null, null, null, null, null, null, null, null, null, null, null);

-- ----------------------------
-- Table structure for cm_member_group
-- ----------------------------
DROP TABLE IF EXISTS `cm_member_group`;
CREATE TABLE `cm_member_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) DEFAULT NULL,
  `member_id` int(11) DEFAULT NULL,
  `member_role` tinyint(3) DEFAULT '0' COMMENT '用户所在群组角色，2为群主，1为管理员，0为普通成员',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of cm_member_group
-- ----------------------------
INSERT INTO `cm_member_group` VALUES ('42', '23', '4', '2', '1607423246', null);
INSERT INTO `cm_member_group` VALUES ('43', '23', '1', '0', '1607423246', null);
INSERT INTO `cm_member_group` VALUES ('44', '23', '2', '0', '1607423246', null);
INSERT INTO `cm_member_group` VALUES ('45', '23', '3', '0', '1607423246', null);
INSERT INTO `cm_member_group` VALUES ('46', '24', '4', '2', '1607423345', null);
INSERT INTO `cm_member_group` VALUES ('47', '24', '3', '0', '1607423345', null);
INSERT INTO `cm_member_group` VALUES ('48', '24', '5', '0', '1607423345', null);
INSERT INTO `cm_member_group` VALUES ('49', '24', '6', '0', '1607423345', null);
INSERT INTO `cm_member_group` VALUES ('50', '25', '2', '2', '1607444179', null);
INSERT INTO `cm_member_group` VALUES ('51', '25', '1', '0', '1607444179', null);
INSERT INTO `cm_member_group` VALUES ('52', '25', '3', '0', '1607444179', null);
INSERT INTO `cm_member_group` VALUES ('53', '25', '4', '0', '1607444179', null);
INSERT INTO `cm_member_group` VALUES ('54', '25', '5', '0', '1607444179', null);
INSERT INTO `cm_member_group` VALUES ('55', '25', '6', '0', '1607444179', null);
INSERT INTO `cm_member_group` VALUES ('56', '26', '2', '2', '1607444641', null);
INSERT INTO `cm_member_group` VALUES ('57', '26', '1', '0', '1607444641', null);
INSERT INTO `cm_member_group` VALUES ('58', '26', '3', '0', '1607444641', null);
INSERT INTO `cm_member_group` VALUES ('59', '26', '4', '0', '1607444641', null);
INSERT INTO `cm_member_group` VALUES ('60', '26', '5', '0', '1607444641', null);
INSERT INTO `cm_member_group` VALUES ('61', '26', '6', '0', '1607444641', null);
INSERT INTO `cm_member_group` VALUES ('62', '27', '2', '2', '1607444741', null);
INSERT INTO `cm_member_group` VALUES ('63', '27', '1', '0', '1607444741', null);
INSERT INTO `cm_member_group` VALUES ('64', '27', '3', '0', '1607444741', null);
INSERT INTO `cm_member_group` VALUES ('65', '27', '4', '0', '1607444741', null);
INSERT INTO `cm_member_group` VALUES ('66', '27', '5', '0', '1607444741', null);
INSERT INTO `cm_member_group` VALUES ('67', '27', '6', '0', '1607444741', null);
INSERT INTO `cm_member_group` VALUES ('68', '28', '5', '2', '1607445296', null);
INSERT INTO `cm_member_group` VALUES ('69', '28', '1', '0', '1607445296', null);
INSERT INTO `cm_member_group` VALUES ('70', '28', '2', '0', '1607445296', null);
INSERT INTO `cm_member_group` VALUES ('71', '29', '4', '2', '1607445639', null);
INSERT INTO `cm_member_group` VALUES ('72', '29', '2', '0', '1607445639', null);
INSERT INTO `cm_member_group` VALUES ('73', '29', '3', '0', '1607445639', null);
INSERT INTO `cm_member_group` VALUES ('74', '29', '5', '0', '1607445639', null);
INSERT INTO `cm_member_group` VALUES ('75', '30', '5', '2', '1607445877', null);
INSERT INTO `cm_member_group` VALUES ('76', '30', '2', '0', '1607445877', null);
INSERT INTO `cm_member_group` VALUES ('77', '30', '3', '0', '1607445877', null);
INSERT INTO `cm_member_group` VALUES ('78', '30', '4', '0', '1607445877', null);
INSERT INTO `cm_member_group` VALUES ('79', '30', '6', '0', '1607445877', null);
