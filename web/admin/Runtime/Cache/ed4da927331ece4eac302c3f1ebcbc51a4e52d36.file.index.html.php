<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:15:18
         compiled from ".\admin\Tpl\Drawcash\index.html" */ ?>
<?php /*%%SmartyHeaderCode:12962548550069a84f7-83269880%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ed4da927331ece4eac302c3f1ebcbc51a4e52d36' => 
    array (
      0 => '.\\admin\\Tpl\\Drawcash\\index.html',
      1 => 1389245608,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12962548550069a84f7-83269880',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'type' => 0,
    't' => 0,
    'search_key' => 0,
    'list' => 0,
    'l' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54855006aba126_78817257',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54855006aba126_78817257')) {function content_54855006aba126_78817257($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<script language="javascript">
 function del(id)
{
   if(confirm("确实要删除吗?"))
	 window.location.href='/iokadmin.php/Drawcash/delete/id/'+id;
}
function alldel(){	
	var str='';
	$("input:checkbox[name='alldel']:checked").each(function(){
		str+=$(this).val()+",";
	})
	if(str){
		if(confirm("确实删除所有选中吗?"))
		window.location.href='/iokadmin.php/Drawcash/delete/id/'+str;
	}else{
		alert('请至少选中一项');
	}

}
</script>
		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Drawcash/leftmenu.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box">
					<!-- box / title -->
					<div class="title" style="margin-bottom:10px;">
						<h5>信息列表</h5>
						<?php echo $_smarty_tpl->getSubTemplate ("record.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					</div>
					<div id="search_div">
						<form action="/iokadmin.php/Drawcash/index" method='get'>
							<div style="padding:10px 20px;">
								信息分类：
								<select name='search_type'>
									<option>选择分类</option>
									<?php  $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['t']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['type']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['t']->key => $_smarty_tpl->tpl_vars['t']->value){
$_smarty_tpl->tpl_vars['t']->_loop = true;
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['t']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['t']->value['prettyname'];?>
</option>
									<?php } ?>
								</select>
								 信息标题:
								<input name="search_title" type="text" style="width:120px;" value="<?php echo $_smarty_tpl->tpl_vars['search_key']->value;?>
" />
								
								<span>						
									<input type="submit" value="搜索" />
								</span>
							</div>
						</form>
					</div>
					<!-- end box / title -->
					<div class="table">
						<table>
							<thead>
								<tr>
									<th class="selected last"><input type="checkbox" class="checkall" /></th>
									<th class="left">Id</th>
									<!-- <th>广告类型</th> -->
									<th style='width:156px;'>开户银行</th>
									<th>开户账号</th>
									<th>开户名</th>
									<th>提现金额</th>
									<th>手续费</th>
									<th>备注</th>
									<th>ip</th>
									<th>状态</th>
									<th>时间</th>
								</tr>
							</thead>
							<tbody>
								<?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['l']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value){
$_smarty_tpl->tpl_vars['l']->_loop = true;
?>
								<tr>
									<td class="selected last"><input type="checkbox" name='alldel' value="<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
"/></td>
									<td style='width:20px;' class="title"><?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
</td>
									<!-- <td>幻灯片广告</td> -->
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['title'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['prettyname'];?>
</td>
									<td><?php if ($_smarty_tpl->tpl_vars['l']->value['enabled']==1){?> 启用 <?php }else{ ?> 关闭 <?php }?></td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['hits'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['addtime'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['addtime'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['adduserid'];?>
</td>
									<td><a href="/iokadmin.php/Drawcash/edit/id/<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
">编辑</a> 
										<a href="javascript:del(<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
)">删除</a></td>
								</tr>
								<?php } ?>
								<tr style='height:5px;'>
									<td><input type="checkbox" class="checkall" /></td>
									<td colspan='11'><a href='javascript:alldel();'>批量删除</a></td>
								</tr>
							</tbody>
						</table>
						<!-- pagination -->
						<?php echo (($tmp = @$_smarty_tpl->tpl_vars['page']->value)===null||$tmp==='' ? '' : $tmp);?>

						<!-- end pagination -->
						<!-- table action -->
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