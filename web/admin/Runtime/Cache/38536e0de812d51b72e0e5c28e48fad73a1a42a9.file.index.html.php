<?php /* Smarty version Smarty-3.1.14, created on 2014-12-10 14:27:56
         compiled from ".\admin\Tpl\Category\index.html" */ ?>
<?php /*%%SmartyHeaderCode:188675487e7ecad53b5-22907046%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '38536e0de812d51b72e0e5c28e48fad73a1a42a9' => 
    array (
      0 => '.\\admin\\Tpl\\Category\\index.html',
      1 => 1389582124,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '188675487e7ecad53b5-22907046',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'model' => 0,
    'category' => 0,
    'id' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5487e7ecc46fd9_24933950',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5487e7ecc46fd9_24933950')) {function content_5487e7ecc46fd9_24933950($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Category/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box">
					<!-- box / title -->
					<div class="title" style="margin-bottom:10px;">
						<h5><?php echo $_smarty_tpl->tpl_vars['model']->value;?>
</h5>
						<?php echo $_smarty_tpl->getSubTemplate ("record.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					</div>
					<?php echo $_smarty_tpl->getSubTemplate ("search.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					<!-- end box / title -->
					<div class="table"> 
						<table>
							<thead>
								<tr>
									<th class="left">编号</th>
									<th>所属模块</th>
									<th>分类名</th>
									<th>类别链接地址</th>
									<th>父级分类</th>
									<th>是否禁用</th>
									<th>添加时间</th>
									<th>添加用户</th>
									<th class="selected last"><input type="checkbox" class="checkall" /></th>
								</tr>
							</thead>
							<tbody>
								<?php  $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['id']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['category']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['id']->key => $_smarty_tpl->tpl_vars['id']->value){
$_smarty_tpl->tpl_vars['id']->_loop = true;
?>
								<tr>
									<td class="price"><?php echo $_smarty_tpl->tpl_vars['id']->value['id'];?>
</td>
									<td class="price"><?php echo $_smarty_tpl->tpl_vars['id']->value['categorytype'];?>
</td>
									<td class="price"><?php echo $_smarty_tpl->tpl_vars['id']->value['prettyname'];?>
</td>
									<td class="price"><?php echo $_smarty_tpl->tpl_vars['id']->value['linkurl'];?>
</td>
									<td class="price"><?php echo $_smarty_tpl->tpl_vars['id']->value['parentid'];?>
</td>
									<td class="price"><?php echo $_smarty_tpl->tpl_vars['id']->value['enabled'];?>
</td>
									<td class="price"><?php echo $_smarty_tpl->tpl_vars['id']->value['addtime'];?>
</td>
									<td class="price"><?php echo $_smarty_tpl->tpl_vars['id']->value['adduserid'];?>
</td>
									<td class="price"><a href="?m=Category&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['id']->value['id'];?>
">编辑</a>/<a href="?m=Banword&a=del">删除</a></td>
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
				<!-- end table -->
			</div>
			<!-- end content / right -->
		</div>
		<!-- end content -->
<?php echo $_smarty_tpl->getSubTemplate ("footer.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>