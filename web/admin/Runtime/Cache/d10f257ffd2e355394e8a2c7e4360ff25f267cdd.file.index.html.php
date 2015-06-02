<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:42:47
         compiled from ".\admin\Tpl\Syscfg\index.html" */ ?>
<?php /*%%SmartyHeaderCode:1940154855677bddef2-65117055%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd10f257ffd2e355394e8a2c7e4360ff25f267cdd' => 
    array (
      0 => '.\\admin\\Tpl\\Syscfg\\index.html',
      1 => 1387722620,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1940154855677bddef2-65117055',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sitename' => 0,
    'app_name' => 0,
    'logo' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54855677c4a130_77491923',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54855677c4a130_77491923')) {function content_54855677c4a130_77491923($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!-- content -->
<div id="content">
	<?php echo $_smarty_tpl->getSubTemplate ("Syscfg/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<!-- content / right -->
	<div id="right">
		<div class="box">
			<!-- box / title -->
			<div class="title">
				<h5>基本设置</h5>
			</div>
			<form id="form" action="" method="post">
				<div class="form">
					<div class="fields">
						<div class="field  field-first">
							<div class="label">
								<label for="input-small">网站名称:</label>
							</div>
							<div class="input">
								<input type="text" id="input-small" name="input.small" class="small" value="<?php echo $_smarty_tpl->tpl_vars['sitename']->value;?>
"/>
								<div class="button highlight">
									<input type="submit" name="submit.highlight" value="检测" />
								</div>
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">网站地址:</label>
							</div>
							<div class="input">
								<input type="text" id="input-small" name="input.small" class="small" value="<?php echo $_smarty_tpl->tpl_vars['app_name']->value;?>
"/>
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="file">网站LOGO:</label>
							</div>
							<div class="input input-file">
								<input type="file" id="file" name="file" size="40" value="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
"/>
							</div>
							<div class="input input-file">
								<img src="<?php echo $_smarty_tpl->tpl_vars['logo']->value;?>
" alt="Ginger" class="left" />
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">客服电话:</label>
							</div>
							<div class="input">
								<input type="text" id="input-small" name="input.small" class="small" value="010-87162577" />
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">ICP备案序号:</label>
							</div>
							<div class="input">
								<input type="text" id="input-small" name="input.small" class="small" value="京ICP备12014158号-2" />
							</div>
						</div>
						<div class="field">
							<div class="label label-textarea">
								<label for="textarea">版权信息:</label>
							</div>
							<div class="textarea">
								<textarea id="textarea1" name="textarea"  style="height:100px;" cols="40" rows="8">Copyright © 2013 美名威（北京）国际网络科技有限公司 
																												www.iokokok.com 版权所有：由我行网开发团队专属权限</textarea>
							</div>
						</div>
						<div class="field">
						<div class="buttons">
							<div class="highlight">
								<input type="submit" name="submit" value="保存" />
							</div>
							<input type="reset" name="reset" value="取消" />
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- end content / right -->
</div>
<!-- end content -->
<?php echo $_smarty_tpl->getSubTemplate ("footer.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>