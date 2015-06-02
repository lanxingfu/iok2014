<?php /* Smarty version Smarty-3.1.14, created on 2014-12-10 14:27:32
         compiled from ".\admin\Tpl\Article\leftmenu.inc.html" */ ?>
<?php /*%%SmartyHeaderCode:109295487e7d49ec680-69446108%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'efd8efe9d8cd9fb4744f5064878756592c2f34a2' => 
    array (
      0 => '.\\admin\\Tpl\\Article\\leftmenu.inc.html',
      1 => 1389245606,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '109295487e7d49ec680-69446108',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5487e7d49f6d47_81362059',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5487e7d49f6d47_81362059')) {function content_5487e7d49f6d47_81362059($_smarty_tpl) {?><!-- end content / left -->
<div id="left">
	<div id="menu">
		<h6 id="h-menu-products" class="selected"><a href="#products"><span>文章管理</span></a></h6>
		<ul id="menu-products" class="opened">
			<li class="collapsible">
				<ul class="expanded">
					<li><a href="/iokadmin.php/Article/index">文章列表</a></li>
					<li><a href="/iokadmin.php/Article/add">添加文章</a></li>
				</ul>
			</li>
		</ul>
	</div>
	<div id="date-picker"></div>
</div>			

<!-- end content / left --><?php }} ?>