<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:12:09
         compiled from ".\admin\Tpl\Ad\index.html" */ ?>
<?php /*%%SmartyHeaderCode:702154854f49d51074-85090601%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9924b548f88da79f8f61789b72ded08d1a88c7c1' => 
    array (
      0 => '.\\admin\\Tpl\\Ad\\index.html',
      1 => 1389245608,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '702154854f49d51074-85090601',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'search_type' => 0,
    'search_key' => 0,
    'search_adplaceid' => 0,
    'listarr' => 0,
    'l' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54854f49e8dd56_86795228',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f49e8dd56_86795228')) {function content_54854f49e8dd56_86795228($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<script language="javascript">
 function del(id)
{
   if(confirm("确实要删除吗?"))
	 window.location.href='/iokadmin.php/Ad/delete/id/'+id;
}
function alldel(){	
	var str='';
	$("input:checkbox[name='alldel']:checked").each(function(){
		str+=$(this).val()+",";
	})
	if(str){
		if(confirm("确实删除所有选中吗?"))
		window.location.href='/iokadmin.php/Ad/delete/id/'+str;
	}else{
		alert('请至少选中一项');
	}

}
</script>
		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Ad/leftmenu.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box">
					<!-- box / title -->
					<div class="title" style="margin-bottom:10px;">
						<h5>广告列表</h5>
						<?php echo $_smarty_tpl->getSubTemplate ("record.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					</div>
					<div id="search_div">
						<form action="index" method='get'>
							<div style="padding:10px 20px;">
								<select name='search_type'>
									<option <?php if ($_smarty_tpl->tpl_vars['search_type']->value=="prettyname"){?> selected <?php }?> value='prettyname'>广告名称</option>
									<option <?php if ($_smarty_tpl->tpl_vars['search_type']->value=="introduce"){?> selected <?php }?> value='introduce'>广告介绍</option>
									<option <?php if ($_smarty_tpl->tpl_vars['search_type']->value=="user"){?> selected <?php }?> value='user'>会员名</option>
								</select>
								<input name="search_key" type="text" style="width:120px;" value="<?php echo nl2br(htmlspecialchars($_smarty_tpl->tpl_vars['search_key']->value, ENT_QUOTES, 'UTF-8', true));?>
" />
								
								广告位ID：<input name="search_adplaceid" type="text" style="width:30px;" value="<?php echo $_smarty_tpl->tpl_vars['search_adplaceid']->value;?>
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
									<th style='width:156px;'>广告名称</th>
									<th>广告位ID</th>
									<th>广告价格</th>
									<th>是否启用</th>
									<th>添加时间</th>
									<th>添加用户</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<?php  $_smarty_tpl->tpl_vars['l'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['l']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['listarr']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['l']->key => $_smarty_tpl->tpl_vars['l']->value){
$_smarty_tpl->tpl_vars['l']->_loop = true;
?>
								<tr>
									<td class="selected last"><input type="checkbox" name='alldel' value="<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
"/></td>
									<td style='width:20px;' class="title"><?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
</td>
									<!-- <td>幻灯片广告</td> -->
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['prettyname'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['adplaceid'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['price'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['enabled'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['addtime'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['adduserid'];?>
</td>
									<td><a href="/iokadmin.php/Ad/edit/id/<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
">编辑</a> 
										<a href="/iokadmin.php/Schedule/add/id/<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
/adid/<?php echo $_smarty_tpl->tpl_vars['l']->value['adplaceid'];?>
">排期</a>
										<a href="javascript:del(<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
)">删除</a></td>
								</tr>
								<?php } ?>
								<tr style='height:5px;'>
									<td><input type="checkbox" class="checkall" /></td>
									<td colspan='9'><a href='javascript:alldel();'>批量删除</a></td>
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