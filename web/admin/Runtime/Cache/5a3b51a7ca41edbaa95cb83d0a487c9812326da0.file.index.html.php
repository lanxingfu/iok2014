<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:42:50
         compiled from ".\admin\Tpl\Property\index.html" */ ?>
<?php /*%%SmartyHeaderCode:56155485567a8d1f84-26795227%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5a3b51a7ca41edbaa95cb83d0a487c9812326da0' => 
    array (
      0 => '.\\admin\\Tpl\\Property\\index.html',
      1 => 1387722620,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '56155485567a8d1f84-26795227',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'v' => 0,
    'ass' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5485567aa4c688_25485649',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5485567aa4c688_25485649')) {function content_5485567aa4c688_25485649($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!-- content -->
<div id="content">
	<?php echo $_smarty_tpl->getSubTemplate ("Syscfg/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<!-- content /  right -->
	<div id="right">
		<div class="box">
			<div class="title">
					<h5>属性列表</h5>
					<?php echo $_smarty_tpl->getSubTemplate ("record.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			</div>
			<?php echo $_smarty_tpl->getSubTemplate ("search.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					<!-- end box / title -->
			<div class="table">
				<table>
					<thead>
						<tr>
							<th class="left">编号</th>
							<th>排序</th>
							<th>所属分类</th>
							<th>属性名</th>
							<th>属性值</th>
							<th>是否禁用</th>
							<th>添加时间</th>
							<th>添加作者</th>
							<th>操作</th>
							<th class="selected last"><input type="checkbox" class="checkall" /></th>
						</tr>
					</thead>
					<tbody>
					<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
?>
						<tr>
							<td><?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['v']->value['listorder'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['v']->value['catname'];?>
</td><!-- <?php echo $_smarty_tpl->tpl_vars['v']->value['categoryid'];?>
 -->
							<td><?php echo $_smarty_tpl->tpl_vars['v']->value['prettyname'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['v']->value['propertyvalue'];?>
</td>
							<td><?php if ($_smarty_tpl->tpl_vars['v']->value['enabled']==1){?>已启用<?php }else{ ?>已禁用<?php }?></td>
							<td><?php echo $_smarty_tpl->tpl_vars['v']->value['addtime'];?>
</td>
							<td><?php echo $_smarty_tpl->tpl_vars['v']->value['userid'];?>
</td>
							<td class="category"><a href="?m=Property&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['ass']->value['id'][$_smarty_tpl->getVariable('smarty')->value['section']['loop']['index']];?>
">编辑</a>/<a href="?m=Property&a=delarea&pname=<?php echo $_smarty_tpl->tpl_vars['ass']->value['id'][$_smarty_tpl->getVariable('smarty')->value['section']['loop']['index']];?>
">删除</a></td>
							<td class="selected last"><input type="checkbox" /></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
				<!-- pagination -->
				<?php echo (($tmp = @$_smarty_tpl->tpl_vars['page']->value)===null||$tmp==='' ? '' : $tmp);?>

				<!-- end pagination -->
				<!-- table action -->
				<div class="action">
					<select name="action">
						<option value="" class="locked">Set status to Deleted</option>
						<option value="" class="unlocked">Set status to Published</option>
						<option value="" class="folder-open">Move to Category</option>
					</select>
					<div class="button">
						<input type="submit" name="submit" value="Apply to Selected" />
					</div>
				</div>
				
				<!-- end table action -->
			</div>
		</div>
	</div>
</div>
<!-- end content -->
<?php echo $_smarty_tpl->getSubTemplate ("footer.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>