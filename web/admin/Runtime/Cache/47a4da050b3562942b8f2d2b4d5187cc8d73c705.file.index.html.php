<?php /* Smarty version Smarty-3.1.14, created on 2014-12-10 14:27:53
         compiled from ".\admin\Tpl\Role\index.html" */ ?>
<?php /*%%SmartyHeaderCode:166215487e7e9e0e0d1-85766509%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '47a4da050b3562942b8f2d2b4d5187cc8d73c705' => 
    array (
      0 => '.\\admin\\Tpl\\Role\\index.html',
      1 => 1387722618,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '166215487e7e9e0e0d1-85766509',
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
  'unifunc' => 'content_5487e7e9f029c1_76324584',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5487e7e9f029c1_76324584')) {function content_5487e7e9f029c1_76324584($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include 'D:\\WorkSpace\\PHP\\iok2014\\web\\ThinkPHP\\Extend\\Vendor\\Smarty\\plugins\\modifier.date_format.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Role/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box">
					<!-- box / title -->
					<div class="title" style="margin-bottom:10px;">
						<h5>角色管理 》角色列表</h5>
						<?php echo $_smarty_tpl->getSubTemplate ("record.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					</div>
					<?php echo $_smarty_tpl->getSubTemplate ("search.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					<!-- end box / title -->
					<div class="table">
						<table>
							<thead>
								<tr>
									<th class="left">名称</th>
									<th width="40">序号</th>
									<th width="140">添加时间</th>
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
									<td><a href="/iokadmin.php?m=Role&a=detail&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['prettyname'];?>
</a></td>
									<td><?php echo $_smarty_tpl->tpl_vars['val']->value['listorder'];?>
</td>
									<td><?php echo (($tmp = @smarty_modifier_date_format($_smarty_tpl->tpl_vars['val']->value['addtime'],'%Y-%m-%d %H:%M:%S'))===null||$tmp==='' ? '-' : $tmp);?>
</td>
									<td>
										<?php if ($_smarty_tpl->tpl_vars['val']->value['id']=='1'){?>
										<span style="color:gray">编辑</span>
										<?php }else{ ?>
										<a href="/iokadmin.php?m=Role&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
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