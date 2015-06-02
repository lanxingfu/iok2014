<?php
/**
 * @name items.data.php
 * @author wayne
 */
$items = array();
$items['public']['name'] = '名称';
$items['public']['description'] = '描述';
$items['public']['usergroup'] = '用户组';
$items['public']['title'] = '标题';
$items['public']['content'] = '内容';
$items['public']['username'] = '添加人员';
$items['public']['type'] = '类型';

// user module
$items['User']['account'] = '帐号';
$items['User']['name'] = '姓名';
$items['User']['email'] = 'email';
$items['User']['phone'] = '电话';
$items['User']['department'] = '部门';

// user group module
$items['Usergroup']['role'] = '角色';

//Channel module
$items['Channel']['id'] = '用户ID';
$items['Channel']['account'] = '用户名';
$items['Channel']['gradeid'] = '代理级别(省1市2县3)';
$items['Channel']['areaid'] = '所在地区';
$items['Channel']['agentareaid'] = '代理地区';

//Product module
$items['Product']['id'] = '商品ID';
$items['Product']['title'] = '商品标题';
$items['Product']['custombrandid'] = '品牌';
$items['Product']['company'] = '公司名';
$items['Product']['account'] = '用户名';
$items['Product']['memberid'] = '用户ID';
$items['Product']['telephone'] = '电话';

//Log module
$items['Log']['memberid'] = '会员名';
$items['Log']['areaid'] = '地区名';

//Order module
$items['Order']['oid'] = '订单ID';
$items['Order']['pid'] = '商品ID';
$items['Order']['title'] = '标题';
$items['Order']['sellerid'] = '卖家';
$items['Order']['buyerid'] = '买家';
$items['Order']['status'] = '订单状态';

//Buy module
$items['Buy']['id'] = '求购ID';
$items['Buy']['title'] = '求购标题';
$items['Buy']['areaid'] = '地区名';

?>
