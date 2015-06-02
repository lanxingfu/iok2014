<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:12:36
         compiled from ".\admin\Tpl\Index\leftmenu.inc.htm" */ ?>
<?php /*%%SmartyHeaderCode:36154854f64863bd1-46154473%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '146fd1ed01e81c4c9053ad7edb9b3f18a38d05aa' => 
    array (
      0 => '.\\admin\\Tpl\\Index\\leftmenu.inc.htm',
      1 => 1387722618,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '36154854f64863bd1-46154473',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54854f64866c78_89784910',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f64866c78_89784910')) {function content_54854f64866c78_89784910($_smarty_tpl) {?>			<!-- end content / left -->
			<div id="left">
				<div id="menu">
					<h6 id="h-menu-products" class="selected"><a href="#products"><span>我的账号</span></a></h6>
					<ul id="menu-products" class="opened">
						<li class="actived"><a href="/iokadmin.php?m=profile">账号信息</a></li>
						<li><a href="/iokadmin.php?m=profile">编辑信息</a></li>
						<li><a href="/iokadmin.php?m=passwd">修改密码</a></li>
						<li><a href="/iokadmin.php?m=passwd">退出系统</a></li>
					</ul>
				</div>
				<div id="date-picker"></div>
			</div>
			<!-- end content / left --><?php }} ?>