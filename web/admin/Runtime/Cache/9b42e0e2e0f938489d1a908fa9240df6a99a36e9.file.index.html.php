<?php /* Smarty version Smarty-3.1.14, created on 2014-12-10 14:28:00
         compiled from ".\admin\Tpl\Banword\index.html" */ ?>
<?php /*%%SmartyHeaderCode:324895487e7f047da98-53104120%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9b42e0e2e0f938489d1a908fa9240df6a99a36e9' => 
    array (
      0 => '.\\admin\\Tpl\\Banword\\index.html',
      1 => 1387722622,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '324895487e7f047da98-53104120',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'banword' => 0,
    'id' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5487e7f059ecb2_33339871',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5487e7f059ecb2_33339871')) {function content_5487e7f059ecb2_33339871($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<script language="javascript">
 function del(id)
{
   if(confirm("确实要删除吗?"))
	 window.location.href='/iokadmin.php?m=Banword&a=del&id='+id;
}
function alldel(){	
	var str='';	
	$("input:checkbox[name='alldel']:checked").each(function(){
		str+=$(this).val()+",";
	})
	if(str){
		if(confirm("确实删除所有选中吗?"))
		window.location.href='/iokadmin.php?m=Banword&a=del&id='+str;
	}else{
		alert('请至少选中一项');
	}

}
</script>

		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Syscfg/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box">
					<!-- box / title -->
					<div class="title" style="margin-bottom:10px;">
						<h5>词语过滤</h5>
						<?php echo $_smarty_tpl->getSubTemplate ("record.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					</div>
					<!-- <?php echo $_smarty_tpl->getSubTemplate ("search.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
 -->
					<!-- end box / title -->
					<div class="table"><!-- id,replacefrom,replaceto,enabled,addtime,adduserid -->
						<table>
							<thead>
								<tr>
									<th class="left">编号</th>
									<th>查找词语</th>
									<th>替换为</th>
									<th>拦截</th>
									<th>添加时间</th>
									<th>添加用户</th>
									<th>操作</th>
									<th class="selected last"><input type="checkbox" class="checkall"/></th>
								</tr>
							</thead>
							<tbody>
								<?php  $_smarty_tpl->tpl_vars['id'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['id']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['banword']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['id']->key => $_smarty_tpl->tpl_vars['id']->value){
$_smarty_tpl->tpl_vars['id']->_loop = true;
?>
								<tr>
									<td class="category"><?php echo $_smarty_tpl->tpl_vars['id']->value['id'];?>
</td>
									<td class="category"><?php echo $_smarty_tpl->tpl_vars['id']->value['replacefrom'];?>
</td>
									<td class="category"><?php echo $_smarty_tpl->tpl_vars['id']->value['replaceto'];?>
</td>
									<td class="category"><?php if ($_smarty_tpl->tpl_vars['id']->value['enabled']==0){?> 已开启<?php }else{ ?>已关闭<?php }?></td>
									<td class="category"><?php echo $_smarty_tpl->tpl_vars['id']->value['addtime'];?>
</td>
									<td class="category"><?php echo $_smarty_tpl->tpl_vars['id']->value['adduserid']['account'];?>
</td>
									<td class="category"><a href="?m=Banword&a=add&id=<?php echo $_smarty_tpl->tpl_vars['id']->value['id'];?>
">编辑</a>/
														 <a href="javascript:del(<?php echo $_smarty_tpl->tpl_vars['id']->value['id'];?>
);">删除</a>
									</td>
									<td class="selected last"><input type="checkbox"  name='alldel' value="<?php echo $_smarty_tpl->tpl_vars['id']->value['id'];?>
"/></td>
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
								<option value="" class="unlocked">批量删除</option>
							</select>
							<div class="button">
								<a href="javascript:alldel();"><input type="submit" name="submit" value="确定" /></a>
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