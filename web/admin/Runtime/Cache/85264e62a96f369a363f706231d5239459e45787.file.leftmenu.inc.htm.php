<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:42:47
         compiled from ".\admin\Tpl\Syscfg\leftmenu.inc.htm" */ ?>
<?php /*%%SmartyHeaderCode:1300654855677c98dc7-08806533%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '85264e62a96f369a363f706231d5239459e45787' => 
    array (
      0 => '.\\admin\\Tpl\\Syscfg\\leftmenu.inc.htm',
      1 => 1387722620,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1300654855677c98dc7-08806533',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54855677c9c448_86529778',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54855677c9c448_86529778')) {function content_54855677c9c448_86529778($_smarty_tpl) {?>			<!-- end content / left -->
			<div id="left">
				<div id="menu">		
					<h6 id="h-menu-products" class="selected"><a href="?m=Syscfg"><span>系统管理</span></a></h6>
					<ul id="menu-products" class="opened">
						<li class="collapsible">
							<a href="?Area" class="minus">地区管理</a>
							<ul class="expanded">
								<li class="last"><a href="?m=Area&a=index">地区列表</a></li>
								<li class="last"><a href="?m=Area&a=addarea">添加地区</a></li>
							</ul>
						</li>
						<li class="collapsible last">
							<a href="?m=Property" class="minus">属性管理</a>
							<ul class="expanded">
								<li class="last"><a href="?m=Property">属性列表</a></li>
								<li class="last"><a href="?m=Property&a=edit">添加属性名</a></li>
								<li class="last"><a href="?m=Property&a=addval">添加属性值</a></li>
							</ul>
						</li>
						<li class="collapsible last">
							<a href="?m=Banword" class="minus">敏感词管理</a>
							<ul class="expanded">
								<li class="last"><a href="?m=Banword">敏感词列表</a></li>
								<li class="last"><a href="?m=Banword&a=add">添加敏感词</a></li>
							</ul>
						</li>
						<li class="collapsible">
							<a href="?m=Log" class="minus">日志管理</a>
							<ul class="expanded">
								<li class="last"><a href="?m=Log">日志列表</a></li>
							</ul>
						</li>
						<li class="collapsible">
							<a href="?m=Syscfg" class="minus">系统设置</a>
							<ul class="expanded">
								<li class="last"><a href="?m=Syscfg">基本设置</a></li>
								<li class="last"><a href="?m=Syscfg&a=seoset">SEO优化</a></li>
								<li class="last"><a href="?m=Syscfg&a=banip">禁止IP</a></li>
								<li class="last"><a href="?m=Syscfg&a=node">节点列表</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div id="date-picker"></div>
			</div>			

			<!-- end content / left --><?php }} ?>