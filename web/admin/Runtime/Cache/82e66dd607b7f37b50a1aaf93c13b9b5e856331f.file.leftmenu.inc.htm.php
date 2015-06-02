<?php /* Smarty version Smarty-3.1.14, created on 2014-12-10 14:27:56
         compiled from ".\admin\Tpl\Category\leftmenu.inc.htm" */ ?>
<?php /*%%SmartyHeaderCode:70245487e7eccbaac9-66047474%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '82e66dd607b7f37b50a1aaf93c13b9b5e856331f' => 
    array (
      0 => '.\\admin\\Tpl\\Category\\leftmenu.inc.htm',
      1 => 1389582124,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '70245487e7eccbaac9-66047474',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5487e7eccfe132_74880034',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5487e7eccfe132_74880034')) {function content_5487e7eccfe132_74880034($_smarty_tpl) {?>			<!-- end content / left -->
			<div id="left">
				<div id="menu">		
					<h6 id="h-menu-products" class="selected"><a href="#products"><span>系统管理</span></a></h6>
					<ul id="menu-products" class="opened">
						<li class="collapsible last">
							<a href="?m=Category" class="minus">类别管理</a>
							<ul class="expanded">
								<li class="last"><a href="?m=Category&type=1">资讯分类</a></li>
								<li class="last"><a href="?m=Category&type=2">视频分类</a></li>
								<li class="last"><a href="?m=Category&type=3">下载分类</a></li>
								<li class="last"><a href="?m=Category&type=4">专题分类</a></li>
								<li class="last"><a href="?m=Category&type=5">关于我们分类</a></li>
								<li class="last"><a href="?m=Category&type=6">帮助分类</a></li>
								<li class="last"><a href="?m=Category&type=7">商学院分类</a></li>
								<li class="last"><a href="?m=Category&type=8">产品分类</a></li>
								<li class="last"><a href="?m=Category&type=9">自定义分类</a></li>
							</ul>
							<a href="?m=Category&a=add" class="minus">添加类别</a>
						</li>
					</ul>
				</div>
				<div id="date-picker"></div>
			</div>			

			<!-- end content / left --><?php }} ?>