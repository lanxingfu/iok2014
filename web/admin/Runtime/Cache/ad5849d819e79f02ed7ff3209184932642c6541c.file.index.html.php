<?php /* Smarty version Smarty-3.1.14, created on 2014-12-10 14:27:26
         compiled from ".\admin\Tpl\Schedule\index.html" */ ?>
<?php /*%%SmartyHeaderCode:114665487e7ce918ab6-00631584%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ad5849d819e79f02ed7ff3209184932642c6541c' => 
    array (
      0 => '.\\admin\\Tpl\\Schedule\\index.html',
      1 => 1389245608,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '114665487e7ce918ab6-00631584',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'adplaceid' => 0,
    'adid' => 0,
    'listarr' => 0,
    'l' => 0,
    'page' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5487e7ceae1ab6_95229758',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5487e7ceae1ab6_95229758')) {function content_5487e7ceae1ab6_95229758($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<script>
	function fun(){
		$("#asd").css("display","none");
		event.preventDefault();
	}
</script>
		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Schedule/leftmenu.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box" id="asd">
					<!-- box / title -->
					<div class="title" style="margin-bottom:10px;">
						<h5><a href="http://www.baidu.com" onclick="fun()">广告位列表</a></h5>
						<?php echo $_smarty_tpl->getSubTemplate ("record.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					</div>
					<div id="search_div">
						<div style="padding:10px 20px;">
							<form action="/iokadmin.php/Schedule/index" method="get">
								广告位Id：
								<input name="adplaceid" type="text" style="width:120px;" value="<?php echo $_smarty_tpl->tpl_vars['adplaceid']->value;?>
" />
								广告Id：<input name="adid" type="text" style="width:30px;" value="<?php echo $_smarty_tpl->tpl_vars['adid']->value;?>
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
									<th >广告位Id</th>
									<th>广告Id</th>
									<th>广告标题</th>
									
									<th>开始时间</th>
									<th>结束时间</th>
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
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['adplaceid'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['adid'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['title'];?>
</td>
									
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['fromtime'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['totime'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['enabled'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['addtime'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['l']->value['adduserid'];?>
</td>
									<td>
										<a href="/iokadmin.php/Schedule/edit/id/<?php echo $_smarty_tpl->tpl_vars['l']->value['id'];?>
">编辑</a> 
										
								</tr>
								<?php } ?>
								
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