<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:52:44
         compiled from ".\admin\Tpl\Profile\submitpasswd.html" */ ?>
<?php /*%%SmartyHeaderCode:10793548558cc200829-55763560%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1489b2c489e22a879e4d8c216b89da75612c90a9' => 
    array (
      0 => '.\\admin\\Tpl\\Profile\\submitpasswd.html',
      1 => 1387722618,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10793548558cc200829-55763560',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_548558cc2a7e33_21732577',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_548558cc2a7e33_21732577')) {function content_548558cc2a7e33_21732577($_smarty_tpl) {?><?php if (!is_callable('smarty_function_tip')) include 'D:\\WorkSpace\\PHP\\iok2014\\web\\ThinkPHP\\Extend\\Vendor\\Smarty\\plugins\\function.tip.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Profile/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- end content / left -->
			<!-- content / right -->
			<div id="right">
				<!-- forms -->
				<div class="box">
					<!-- box / title -->
					<div class="title">
						<h5>我的账号 》修改密码</h5>
					</div>
					<!-- end box / title -->
					<div class="box">
						<!-- box / title -->
						<div id="box-messages">
							<div class="messages">
								<div style="width:100%;text-align:center;margin:50px 0px 100px 0px;clear:both;">
								<?php echo smarty_function_tip(array('id'=>'submit','default'=>'信息修改成功！'),$_smarty_tpl);?>

								<a href="/iokadmin.php?m=Profile" title="返回">点击返回</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- end forms -->
			</div>
			<!-- end content / right -->
		</div>
		<!-- end content -->
<?php echo $_smarty_tpl->getSubTemplate ("footer.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>