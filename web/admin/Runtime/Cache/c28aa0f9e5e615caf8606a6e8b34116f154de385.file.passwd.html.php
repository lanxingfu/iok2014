<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:49:09
         compiled from ".\admin\Tpl\Profile\passwd.html" */ ?>
<?php /*%%SmartyHeaderCode:13043548557f5835e55-98828724%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c28aa0f9e5e615caf8606a6e8b34116f154de385' => 
    array (
      0 => '.\\admin\\Tpl\\Profile\\passwd.html',
      1 => 1387722618,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '13043548557f5835e55-98828724',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_548557f58c3193_25164550',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_548557f58c3193_25164550')) {function content_548557f58c3193_25164550($_smarty_tpl) {?><?php if (!is_callable('smarty_function_tip')) include 'D:\\WorkSpace\\PHP\\iok2014\\web\\ThinkPHP\\Extend\\Vendor\\Smarty\\plugins\\function.tip.php';
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
					<form id="form" method="post">
					<div class="form">
						<div class="fields">
							<div class="field field-first">
								<div class="label">原密码</div>
								<div class="input">
									<input type="password" name="passwd" class="small" />
								</div>
								<div class="tip"><?php echo smarty_function_tip(array('id'=>'passwd','default'=>'请输入您原来的密码'),$_smarty_tpl);?>
</div>
							</div>
							<div class="field">
								<div class="label">新密码</div>
								<div class="input">
									<input type="password" name="passwd1" class="small" />
								</div>
								<div class="tip"><?php echo smarty_function_tip(array('id'=>'passwd1','default'=>'请输入您需要更改的密码'),$_smarty_tpl);?>
</div>
							</div>
							<div class="field">
								<div class="label">重复输入</div>
								<div class="input">
									<input type="password" name="passwd2" class="small" />
								</div>
								<div class="tip"><?php echo smarty_function_tip(array('id'=>'passwd2','default'=>'请再次输入您要更改的密码'),$_smarty_tpl);?>
</div>
							</div>
							<div class="buttons">
								<input type="hidden" name="m" value="Profile" />
								<input type="hidden" name="a" value="submitpasswd" />
								<input type="submit" value="提交" />
								<input type="reset" value="返回" onclick="javascript:gotoUrl('/iokadmin.php?m=Profile')" />
							</div>
						</div>
					</div>
					</form>
				</div>
				<!-- end forms -->
			</div>
			<!-- end content / right -->
		</div>
		<!-- end content -->
<?php echo $_smarty_tpl->getSubTemplate ("footer.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>