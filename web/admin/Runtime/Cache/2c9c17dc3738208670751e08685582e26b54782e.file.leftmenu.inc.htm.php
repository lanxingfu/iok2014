<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:11:07
         compiled from ".\admin\Tpl\Channel\leftmenu.inc.htm" */ ?>
<?php /*%%SmartyHeaderCode:1571854854f0bbc4be2-68158592%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2c9c17dc3738208670751e08685582e26b54782e' => 
    array (
      0 => '.\\admin\\Tpl\\Channel\\leftmenu.inc.htm',
      1 => 1388758172,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1571854854f0bbc4be2-68158592',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54854f0bbc8319_46113862',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f0bbc8319_46113862')) {function content_54854f0bbc8319_46113862($_smarty_tpl) {?>			<!-- end content / left -->
			<div id="left">
				<div id="menu">			
					<h6 id="h-menu-products" class="selected"><a href="#"><span>会员管理</span></a></h6>
					<ul id="menu-products" class="opened">
						<li class="collapsible">
							<a href="javascript:void(0);" class="minus">渠道商管理</a>
							<ul class="expanded">
								<li class="last"><a href="?m=Channel&a=index">渠道商列表</a></li>
								<li class="last"><a href="?m=Channel&a=cadd">添加代理商</a></li>
							</ul>
						</li>
						<li class="collapsible">
							<a href="javascript:void(0);" class="minus">商代商站管理</a>
							<ul class="expanded">
								<li class="last"><a href="/iokadmin.php?m=Servicestaff&a=index">商代商站列表</a></li>
								<li class="last"><a href="/iokadmin.php?m=Servicestaff&a=cadd">添加商代商站</a></li>
							</ul>
						</li>
						<li class="collapsible last">
							<a href="javascript:void(0);" class="minus">企业管理</a>
							<ul class="expanded">
								<li class="last"><a href="/iokadmin.php?m=Company">企业列表</a></li>
							</ul>
						</li>
						<li class="collapsible last">
							<a href="javascript:void(0);" class="minus">个人管理</a>
							<ul class="expanded">
								<li class="last"><a href="/iokadmin.php?m=Person">个人列表</a></li>
							</ul>
						</li>
						<li class="collapsible">
							<a href="javascript:void(0);" class="minus">会员资质</a>
							<ul class="expanded">
								<li class="last"><a href="/iokadmin.php?m=Proof">资质列表</a></li>
							</ul>
						</li>
						<li class="collapsible">
							<a href="javascript:void(0);" class="minus">会员等级</a>
							<ul class="expanded">
								<li class="last"><a href="/iokadmin.php?m=Grade">等级设置</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div id="date-picker"></div>
			</div>			

			<!-- end content / left --><?php }} ?>