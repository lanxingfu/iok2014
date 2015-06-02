<?php /* Smarty version Smarty-3.1.14, created on 2014-12-10 14:27:26
         compiled from ".\admin\Tpl\Schedule\leftmenu.inc.html" */ ?>
<?php /*%%SmartyHeaderCode:200275487e7ceb5b1b5-64350037%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4683cc1cd1c05ac9d30b69dac7c4dcd5499fba48' => 
    array (
      0 => '.\\admin\\Tpl\\Schedule\\leftmenu.inc.html',
      1 => 1387722622,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '200275487e7ceb5b1b5-64350037',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5487e7ceb66813_04540899',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5487e7ceb66813_04540899')) {function content_5487e7ceb66813_04540899($_smarty_tpl) {?><!-- end content / left -->
<div id="left">
	<div id="menu">
		<h6 id="h-menu-products" class="selected"><a href="#products"><span>管理</span></a></h6>
		<ul id="menu-products" class="opened">
			<li class="collapsible">
				<ul class="expanded">
					<li><a href="/iokadmin.php/Schedule/index">排期列表</a></li>
					<li><a href="/iokadmin.php/Schedule/add">添加排期</a></li>
				</ul>
			</li>
		</ul>
	</div>
	<div id="date-picker"></div>
</div>			

<!-- end content / left --><?php }} ?>