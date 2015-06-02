<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:16:09
         compiled from ".\admin\Tpl\Department\edit.html" */ ?>
<?php /*%%SmartyHeaderCode:2716454855039c281b4-56922624%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '37ec1b0bbc1a30f8e4d48ecbf77b656096593b91' => 
    array (
      0 => '.\\admin\\Tpl\\Department\\edit.html',
      1 => 1389245608,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2716454855039c281b4-56922624',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'rec' => 0,
    'leader' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54855039dc8b76_64547268',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54855039dc8b76_64547268')) {function content_54855039dc8b76_64547268($_smarty_tpl) {?><?php if (!is_callable('smarty_function_tip')) include 'D:\\WorkSpace\\PHP\\iok2014\\web\\ThinkPHP\\Extend\\Vendor\\Smarty\\plugins\\function.tip.php';
if (!is_callable('smarty_function_html_options')) include 'D:\\WorkSpace\\PHP\\iok2014\\web\\ThinkPHP\\Extend\\Vendor\\Smarty\\plugins\\function.html_options.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Department/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- end content / left -->
			<!-- content / right -->
			<div id="right">
				<!-- forms -->
				<div class="box">
					<!-- box / title -->
					<div class="title">
						<h5>部门管理 》<?php if ($_smarty_tpl->tpl_vars['rec']->value['id']){?>编辑信息<?php }else{ ?>添加部门<?php }?></h5>
					</div>
					<!-- end box / title -->
					<form id="frm" method="post">
					<div class="form">
						<div class="fields">
							<div class="field  field-first">
								<div class="label">部门名称</div>
								<div class="input">
									<input type="text" name="prettyname" value="<?php echo $_smarty_tpl->tpl_vars['rec']->value['prettyname'];?>
" class="small" />
								</div>
								<div class="tip"><?php echo smarty_function_tip(array('id'=>'prettyname','default'=>'请输入部门名称'),$_smarty_tpl);?>
</div>
							</div>
							<?php if ($_smarty_tpl->tpl_vars['rec']->value['id']){?>
							<div class="field">
								<div class="label">部门管理员</div>
								<div class="select">
									<select name="leaderid">
									<?php echo smarty_function_html_options(array('values'=>$_smarty_tpl->tpl_vars['leader']->value['id'],'output'=>$_smarty_tpl->tpl_vars['leader']->value['account'],'selected'=>$_smarty_tpl->tpl_vars['rec']->value['leaderid']),$_smarty_tpl);?>

									</select>
								</div>
								<div class="tip"><?php echo smarty_function_tip(array('id'=>"leader",'default'=>"请选择部门管理员"),$_smarty_tpl);?>
</div>
							</div>
							<?php }?>
							<div class="field">
								<div class="label">列表序号</div>
								<div class="input">
									<input type="text" name="listorder" value="<?php echo $_smarty_tpl->tpl_vars['rec']->value['listorder'];?>
" class="small" />
								</div>
								<div class="tip"><?php echo smarty_function_tip(array('id'=>'listorder','default'=>'请输入列表序号'),$_smarty_tpl);?>
</div>
							</div>
							<div class="field">
								<div class="label label-textarea">部门简介</div>
								<div class="textarea textarea-editor">
									<textarea name="introduce" cols="50" rows="12" class="editor"><?php echo htmlspecialchars(nl2br($_smarty_tpl->tpl_vars['rec']->value['introduce']), ENT_QUOTES, 'UTF-8', true);?>
</textarea>
								</div>
							</div>
							<div class="buttons">
								<input type="hidden" name="m" value="Department" />
								<input type="hidden" name="a" value="submit" />
								<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['rec']->value['id'];?>
" />
								<input type="submit" name="submit" value="提交" />
								<input type="reset" name="reset" value="返回" onclick="javascript:gotoUrl('/iokadmin.php?m=Department<?php if ($_smarty_tpl->tpl_vars['rec']->value['id']){?>&a=detail&id=<?php echo $_smarty_tpl->tpl_vars['rec']->value['id'];?>
<?php }?>')" />
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