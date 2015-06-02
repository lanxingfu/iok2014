<?php /* Smarty version Smarty-3.1.14, created on 2014-12-10 14:27:45
         compiled from ".\admin\Tpl\User\index.html" */ ?>
<?php /*%%SmartyHeaderCode:48875487e7e11eedc0-25943324%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'a3e8a3d57089ef4cc87b1d9aa30ecf0647c7f5b0' => 
    array (
      0 => '.\\admin\\Tpl\\User\\index.html',
      1 => 1387722620,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '48875487e7e11eedc0-25943324',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'rec' => 0,
    'val' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5487e7e13a5a82_42580202',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5487e7e13a5a82_42580202')) {function content_5487e7e13a5a82_42580202($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'D:\\WorkSpace\\PHP\\iok2014\\web\\ThinkPHP\\Extend\\Vendor\\Smarty\\plugins\\modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("User/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box">
					<!-- box / title -->
					<div class="title" style="margin-bottom:10px;">
						<h5>用户管理 》用户列表</h5>
						<?php echo $_smarty_tpl->getSubTemplate ("record.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					</div>
					<?php echo $_smarty_tpl->getSubTemplate ("search.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					<!-- end box / title -->
					<div class="table">
						<table>
							<thead>
								<tr>
									<th class="left" width="100">账号</th>
									<th width="80">姓名</th>
									<th>用户组</th>
									<th width="100">部门</th>
									<th width="140">登录时间</th>
									<th width="60">操作</th>
									<th class="selected last" width="40"><input type="checkbox" class="checkall" /></th>
								</tr>
							</thead>
							<tbody>
								<?php  $_smarty_tpl->tpl_vars['val'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['val']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['rec']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['val']->key => $_smarty_tpl->tpl_vars['val']->value){
$_smarty_tpl->tpl_vars['val']->_loop = true;
?>
								<tr>
									<td><a href="/iokadmin.php?m=User&a=detail&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['account'];?>
</a></td>
									<td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['val']->value['prettyname'])===null||$tmp==='' ? '-' : $tmp);?>
</td>
									<td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['val']->value['usergroup'])===null||$tmp==='' ? '-' : $tmp);?>
</td>
									<td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['val']->value['department'])===null||$tmp==='' ? '-' : $tmp);?>
</td>
									<td><?php echo (($tmp = @smarty_modifier_date_format($_smarty_tpl->tpl_vars['val']->value['logintime'],'%Y-%m-%d %H:%M:%S'))===null||$tmp==='' ? '-' : $tmp);?>
</td>
									<td>
										<?php if ($_smarty_tpl->tpl_vars['val']->value['id']=='1'){?>
										<span style="color:gray">编辑</span>
										<?php }else{ ?>
										<a href="/iokadmin.php?m=User&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
">编辑</a>
										<?php }?>
									</td>
									<td class="selected last"><?php if ($_smarty_tpl->tpl_vars['val']->value['id']!='1'){?><input type="checkbox" name="id[]" value="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
" /><?php }?></td>
								</tr>
								<?php }
if (!$_smarty_tpl->tpl_vars['val']->_loop) {
?>
								<tr><td colspan="100%">没有记录</td></tr>
								<?php } ?>
							</tbody>
						</table>
						<!-- pagination -->
						<?php echo (($tmp = @$_smarty_tpl->tpl_vars['page']->value)===null||$tmp==='' ? '' : $tmp);?>

						<!-- end pagination -->
						<!-- table action -->
						<div class="action">
							<select id="action">
								<option value="0">禁用</option>
								<option value="1">启用</option>
							</select>
							<div class="button">
								<input type="reset" value="执行操作" onclick="javascript:var ids = ischeckmore();alert(ids);" />
							</div>
						</div>
						
						<!-- end table action -->
					</div>
				</div>
				<!-- end table -->
			</div>
			<!-- end content / right -->
		</div>
		<!-- end content -->
<?php echo $_smarty_tpl->getSubTemplate ("footer.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>