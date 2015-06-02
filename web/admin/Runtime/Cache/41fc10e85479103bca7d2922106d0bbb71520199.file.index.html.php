<?php /* Smarty version Smarty-3.1.14, created on 2014-12-10 14:27:42
         compiled from ".\admin\Tpl\Company\index.html" */ ?>
<?php /*%%SmartyHeaderCode:193505487e7de75aa80-45813163%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '41fc10e85479103bca7d2922106d0bbb71520199' => 
    array (
      0 => '.\\admin\\Tpl\\Company\\index.html',
      1 => 1387988158,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '193505487e7de75aa80-45813163',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'item' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5487e7de8c8d36_45443160',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5487e7de8c8d36_45443160')) {function content_5487e7de8c8d36_45443160($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Channel/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box">
					<!-- box / title -->
					<div class="title" style="margin-bottom:10px;">
						<h5>企业列表</h5>
						<?php echo $_smarty_tpl->getSubTemplate ("record.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					</div>
					<?php echo $_smarty_tpl->getSubTemplate ("search.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					<!-- end box / title -->
					<div class="table">
						<table>
							<thead>
								<tr>
									<th class="left">编号</th>
									<th>用户名</th>
									<th>类型</th>
									<th>地区</th>
									<th>公司名</th>
									<th>注册资本</th>
									<th>注册时间</th>
									<th>商务代表</th>
									<th>操作</th>
									<th class="selected last"><input type="checkbox" class="checkall" /></th>
								</tr>
							</thead>
							<tbody>
							<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['id']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
								<tr>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
</td>
									<td><a href="?m=Company&a=detail&id=<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['account'];?>
</td>
									<td><?php if ($_smarty_tpl->tpl_vars['item']->value['gradeid']==7){?>普通企业会员
										<?php }elseif($_smarty_tpl->tpl_vars['item']->value['gradeid']==8){?>认证企业会员
										<?php }elseif($_smarty_tpl->tpl_vars['item']->value['gradeid']==9){?>双重认证企业会员
										<?php }else{ ?>未知级别<?php }?>
									</td>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['areaid'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['company'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['capital'];?>
万</td>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['registertime'];?>
</td>
									<td><?php if ($_smarty_tpl->tpl_vars['item']->value['servicestaffid']['account']!=''){?><?php echo $_smarty_tpl->tpl_vars['item']->value['servicestaffid']['account'];?>

										<?php }else{ ?><a href="?m=Company&a=getservice&id=<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">分配商务代表</a><?php }?></td>
									<td class="category"><a href="?m=Company&a=edit&id=<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">编辑</a>/<a href="?m=Servicestaff&a=delarea&pname=<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
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