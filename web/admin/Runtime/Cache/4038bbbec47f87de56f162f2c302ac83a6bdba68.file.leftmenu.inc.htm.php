<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:49:09
         compiled from ".\admin\Tpl\Profile\leftmenu.inc.htm" */ ?>
<?php /*%%SmartyHeaderCode:27568548557f5964ef4-30235471%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4038bbbec47f87de56f162f2c302ac83a6bdba68' => 
    array (
      0 => '.\\admin\\Tpl\\Profile\\leftmenu.inc.htm',
      1 => 1387722618,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '27568548557f5964ef4-30235471',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_548557f596a903_51081497',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_548557f596a903_51081497')) {function content_548557f596a903_51081497($_smarty_tpl) {?>			<!-- end content / left -->
			<div id="left">
				<div id="menu">
					<h6 id="h-menu-products" class="selected"><a href="#products"><span>我的账号</span></a></h6>
					<ul id="menu-products" class="opened">
						<li class="actived"><a href="/iokadmin.php?m=Profile">账号信息</a></li>
						<li><a href="/iokadmin.php?m=Profile&a=edit">编辑信息</a></li>
						<li><a href="/iokadmin.php?m=Profile&a=passwd">修改密码</a></li>
						<li><a href="/iokadmin.php?m=Misc&a=logout">退出系统</a></li>
					</ul>
				</div>
				<div id="date-picker"></div>
			</div>
			<!-- end content / left --><?php }} ?>