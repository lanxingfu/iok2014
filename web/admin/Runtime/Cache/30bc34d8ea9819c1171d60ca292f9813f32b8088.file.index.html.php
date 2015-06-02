<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:15:27
         compiled from ".\admin\Tpl\Adplace\index.html" */ ?>
<?php /*%%SmartyHeaderCode:202085485500f4c0400-52274323%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '30bc34d8ea9819c1171d60ca292f9813f32b8088' => 
    array (
      0 => '.\\admin\\Tpl\\Adplace\\index.html',
      1 => 1389245608,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '202085485500f4c0400-52274323',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'prettyname' => 0,
    'adtype' => 0,
    'width' => 0,
    'height' => 0,
    'listarr' => 0,
    'l' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5485500f60c778_93378440',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5485500f60c778_93378440')) {function content_5485500f60c778_93378440($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<script language="javascript">
 function del(id)
{
   if(confirm("确实要删除吗?"))
	 window.location.href='/iokadmin.php/Adplace/delete/id/'+id;
}
function alldel(){	
	var str='';
	$("input:checkbox[name='alldel']:checked").each(function(){
		str+=$(this).val()+",";
	})
	if(str){
		if(confirm("确实删除所有选中吗?"))
		window.location.href='/iokadmin.php/Adplace/delete/id/'+str;
	}else{
		alert('请至少选中一项');
	}

}
</script>
		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Adplace/leftmenu.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box">
					<!-- box / title -->
					<div class="title" style="margin-bottom:10px;">
						<h5>广告位列表</h5>
						<?php echo $_smarty_tpl->getSubTemplate ("record.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					</div>
					<div id="search_div">
						<div style="padding:10px 20px;">
							<form action="/iokadmin.php/Adplace/index" method="get">
								广告位名称：
								<input name="prettyname" type="text" style="width:120px;" value="<?php echo nl2br(htmlspecialchars($_smarty_tpl->tpl_vars['prettyname']->value, ENT_QUOTES, 'UTF-8', true));?>
" />
								<select name="adtype">
									<option <?php if ($_smarty_tpl->tpl_vars['adtype']->value=="image"){?> selected <?php }?> value="image">图片</option>
									<option <?php if ($_smarty_tpl->tpl_vars['adtype']->value=="slideshow"){?> selected <?php }?> value="slideshow">幻灯片</option>
									<option <?php if ($_smarty_tpl->tpl_vars['adtype']->value=="text"){?> selected <?php }?> value="text">文字、文本</option>
									<option <?php if ($_smarty_tpl->tpl_vars['adtype']->value=="mobile"){?> selected <?php }?> value="mobile">手机类</option>
								</select>
								宽度：<input name="width" type="text" style="width:30px;" value="<?php echo $_smarty_tpl->tpl_vars['width']->value;?>
" />
								高度：<input name="height" type="text" style="width:30px;" value="<?php echo $_smarty_tpl->tpl_vars['height']->value;?>
" />
								<span>						
									<input type="submit" name="submit" value="搜索" />
								</span>
							</form>
						</div>
					</div>
					<!-- end box / title -->
					<div class="table">
						<table>
							<thead>
								<tr>
									<th class="selected last"><input type="checkbox" class="checkall" /></th>
									<th class="left">Id</th>
									<th>广告类型</th>
									<th style='width:156px;'>广告位名称</th>
									<th>别名</th>
									<th>宽度(px)</th>
									<th>高度(px)</th>
									<th>添加时间</th>
									<th>是否启用</th>
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
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['adtype'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['prettyname'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['name'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['width'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['height'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['addtime'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['enabled'];?>
</td>
									<td>
										<!-- <a href="/iokadmin.php/Adplace/addad/id/<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
">添加</a> 
										<a href="/iokadmin.php/Adplace/detail/adplaceid/<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
">列表  -->
										<a href="/iokadmin.php/Adplace/edit/id/<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
">编辑</a> 
										<a href="javascript:del(<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
);">删除</a></td>
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