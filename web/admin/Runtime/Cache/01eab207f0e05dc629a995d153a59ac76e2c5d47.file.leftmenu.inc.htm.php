<?php /* Smarty version Smarty-3.1.14, created on 2014-12-10 14:27:45
         compiled from ".\admin\Tpl\User\leftmenu.inc.htm" */ ?>
<?php /*%%SmartyHeaderCode:212525487e7e147cf30-07232168%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '01eab207f0e05dc629a995d153a59ac76e2c5d47' => 
    array (
      0 => '.\\admin\\Tpl\\User\\leftmenu.inc.htm',
      1 => 1387722618,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '212525487e7e147cf30-07232168',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5487e7e14875f8_52821993',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5487e7e14875f8_52821993')) {function content_5487e7e14875f8_52821993($_smarty_tpl) {?>			<!-- end content / left -->
			<div id="left">
				<div id="menu">
					<h6 id="h-menu-products" class="selected"><a href="#products"><span>用户管理</span></a></h6>
					<ul id="menu-products" class="opened">
						<li><a href="/iokadmin.php?m=User">用户列表</a></li>
						<li><a href="/iokadmin.php?m=User&a=edit">添加用户</a></li>
					</ul>
				</div>
				<div id="date-picker"></div>
			</div>			
			<!-- end content / left --><?php }} ?>