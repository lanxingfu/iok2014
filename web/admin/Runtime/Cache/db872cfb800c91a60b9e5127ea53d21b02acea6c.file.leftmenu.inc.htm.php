<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:15:59
         compiled from ".\admin\Tpl\Department\leftmenu.inc.htm" */ ?>
<?php /*%%SmartyHeaderCode:142235485502fd543a8-90661672%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'db872cfb800c91a60b9e5127ea53d21b02acea6c' => 
    array (
      0 => '.\\admin\\Tpl\\Department\\leftmenu.inc.htm',
      1 => 1387722620,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '142235485502fd543a8-90661672',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5485502fd57670_76071376',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5485502fd57670_76071376')) {function content_5485502fd57670_76071376($_smarty_tpl) {?>			<!-- end content / left -->
			<div id="left">
				<div id="menu">
					<h6 id="h-menu-products" class="selected"><a href="#products"><span>部门管理</span></a></h6>
					<ul id="menu-products" class="opened">
						<li><a href="/iokadmin.php?m=Department">部门列表</a></li>
						<li><a href="/iokadmin.php?m=Department&a=edit">添加部门</a></li>
					</ul>
				</div>
				<div id="date-picker"></div>
			</div>			
			<!-- end content / left --><?php }} ?>