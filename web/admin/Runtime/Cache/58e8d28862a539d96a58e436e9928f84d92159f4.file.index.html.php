<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:52:47
         compiled from ".\admin\Tpl\Profile\index.html" */ ?>
<?php /*%%SmartyHeaderCode:8622548558cf9b80c8-32447531%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '58e8d28862a539d96a58e436e9928f84d92159f4' => 
    array (
      0 => '.\\admin\\Tpl\\Profile\\index.html',
      1 => 1387722618,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8622548558cf9b80c8-32447531',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'rec' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_548558cfb6b875_03983584',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_548558cfb6b875_03983584')) {function content_548558cfb6b875_03983584($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'D:\\WorkSpace\\PHP\\iok2014\\web\\ThinkPHP\\Extend\\Vendor\\Smarty\\plugins\\modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Profile/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- forms -->
				<div class="box">
					<!-- box / title -->
					<div class="title">
						<h5>我的账号</h5>
					</div>
					<!-- end box / title -->
					<div class="form">
						<div class="fields">
							<div class="field field-first">
								<div class="fname">账号</div>
								<div class="fvalue"><?php echo $_SESSION['user']['account'];?>
</div>
							</div>
							<div class="field">
								<div class="fname">真实姓名</div>
								<div class="fvalue"><?php echo (($tmp = @$_SESSION['user']['prettyname'])===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">email</div>
								<div class="fvalue"><?php echo (($tmp = @$_SESSION['user']['email'])===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">手机号码</div>
								<div class="fvalue"><?php echo (($tmp = @$_SESSION['user']['mobile'])===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">QQ号码</div>
								<div class="fvalue"><?php echo (($tmp = @$_SESSION['user']['qq'])===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">职位名称</div>
								<div class="fvalue"><?php echo (($tmp = @$_SESSION['user']['position'])===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">所属部门</div>
								<div class="fvalue"><?php echo (($tmp = @$_SESSION['user']['department'])===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">用户组</div>
								<div class="fvalue"><?php echo (($tmp = @$_SESSION['user']['usergroup'])===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">角色</div>
								<div class="fvalue"><?php echo (($tmp = @$_SESSION['user']['rolename'])===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="label label-textarea">个人介绍</div>
								<div class="textarea textarea-editor">
									<textarea name="introduce" cols="50" rows="12" class="editor"><?php echo htmlspecialchars(nl2br($_smarty_tpl->tpl_vars['rec']->value['introduce']), ENT_QUOTES, 'UTF-8', true);?>
</textarea>
								</div>
							</div>
							<div class="field">
								<div class="fname">登陆时间</div>
								<div class="fvalue"><?php echo (($tmp = @smarty_modifier_date_format($_SESSION['user']['logintime'],'%Y-%m-%d %H:%M:%S'))===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">登陆ip</div>
								<div class="fvalue"><?php echo (($tmp = @$_SESSION['user']['loginip'])===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">添加时间</div>
								<div class="fvalue"><?php echo smarty_modifier_date_format($_SESSION['user']['addtime'],'%Y-%m-%d %H:%M:%S');?>
</div>
							</div>
							<div class="field">
								<div class="fname">添加人员</div>
								<div class="fvalue"><?php echo (($tmp = @$_SESSION['user']['adduser'])===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">添加ip</div>
								<div class="fvalue"><?php echo (($tmp = @$_SESSION['user']['addip'])===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">更新时间</div>
								<div class="fvalue"><?php echo (($tmp = @smarty_modifier_date_format($_SESSION['user']['updatetime'],'%Y-%m-%d %H:%M:%S'))===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">更新人员</div>
								<div class="fvalue"><?php echo (($tmp = @$_SESSION['user']['updateuser'])===null||$tmp==='' ? '-' : $tmp);?>
</div>
							</div>
							<div class="field">
								<div class="fname">当前状态</div>
								<div class="fvalue"><?php if ($_SESSION['user']['enabled']){?>正常<?php }else{ ?>禁用<?php }?></div>
							</div>
							<div class="buttons">
								<input type="reset" value="编辑" onclick="javascript:gotoUrl('/iokadmin.php?m=Profile&a=edit')" />
								<input type="reset" value="修改密码" onclick="javascript:gotoUrl('/iokadmin.php?m=Profile&a=passwd')" />
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