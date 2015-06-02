<?php /* Smarty version Smarty-3.1.14, created on 2014-12-10 14:27:32
         compiled from ".\admin\Tpl\Article\index.html" */ ?>
<?php /*%%SmartyHeaderCode:310645487e7d475a3a8-10749598%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9036dc496a82bfb3326521ebc5a45b269569bbf1' => 
    array (
      0 => '.\\admin\\Tpl\\Article\\index.html',
      1 => 1389597870,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '310645487e7d475a3a8-10749598',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'type' => 0,
    't' => 0,
    'search_type' => 0,
    'search_title' => 0,
    'search_s' => 0,
    'search_e' => 0,
    'list' => 0,
    'l' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5487e7d493f7b6_14254654',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5487e7d493f7b6_14254654')) {function content_5487e7d493f7b6_14254654($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<script language="javascript">
 function del(id)
{
   if(confirm("确实要删除吗?"))
	 window.location.href='/iokadmin.php/Article/delete/id/'+id;
}
function alldel(){	
	var str='';
	$("input:checkbox[name='alldel']:checked").each(function(){
		str+=$(this).val()+",";
	})
	if(str){
		if(confirm("确实删除所有选中吗?"))
		window.location.href='/iokadmin.php/Article/delete/id/'+str;
	}else{
		alert('请至少选中一项');
	}

}
</script>
		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Article/leftmenu.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box">
					<!-- box / title -->
					<div class="title" style="margin-bottom:10px;">
						<h5>文章列表</h5>
						<?php echo $_smarty_tpl->getSubTemplate ("record.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					</div>
					<div id="search_div">
						<form action="/iokadmin.php/Article/index" method='get'>
							<div style="padding:10px 20px;">
								文章分类：
								<select name='search_type'>
									<option value=0>选择分类</option>
									<?php  $_smarty_tpl->tpl_vars['t'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['t']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['type']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['t']->key => $_smarty_tpl->tpl_vars['t']->value){
$_smarty_tpl->tpl_vars['t']->_loop = true;
?>
										<option value="<?php echo $_smarty_tpl->tpl_vars['t']->value['id'];?>
" <?php if ($_smarty_tpl->tpl_vars['search_type']->value==$_smarty_tpl->tpl_vars['t']->value['id']){?> selected <?php }?>><?php echo $_smarty_tpl->tpl_vars['t']->value['prettyname'];?>
</option>
									<?php } ?>
								</select>
								 文章标题:
								<input name="search_title" type="text" style="width:120px;" value="<?php echo nl2br(htmlspecialchars($_smarty_tpl->tpl_vars['search_title']->value, ENT_QUOTES, 'UTF-8', true));?>
" />
								  更新日期：
								<input type="text" size="8" name="search_s" class="date" value="<?php echo $_smarty_tpl->tpl_vars['search_s']->value;?>
">
								至
								<input type="text" size="8" name="search_e" class="date" value="<?php echo $_smarty_tpl->tpl_vars['search_e']->value;?>
">
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
									<th style='width:156px;'>文章标题</th>
									<th>文章类别</th>
									<th>是否启用</th>
									<th>是否置顶</th>
									<th>添加时间</th>
									<th>添加用户</th>
									<th>操作</th>
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
									<td><?php echo nl2br(htmlspecialchars($_smarty_tpl->tpl_vars['l']->value['title'], ENT_QUOTES, 'UTF-8', true));?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['categoryid'];?>
</td>
									<td><?php if ($_smarty_tpl->tpl_vars['l']->value['enabled']==1){?> 启用 <?php }else{ ?> 关闭 <?php }?></td>
									<td><?php if ($_smarty_tpl->tpl_vars['l']->value['istop']==1){?> 置顶 <?php }else{ ?> 未置顶 <?php }?></td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['addtime'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['adduserid'];?>
</td>
									<td><a href="/iokadmin.php/Article/edit/id/<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
">编辑</a> 
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