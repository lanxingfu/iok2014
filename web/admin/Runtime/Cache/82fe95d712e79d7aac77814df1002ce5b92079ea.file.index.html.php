<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:11:07
         compiled from ".\admin\Tpl\Channel\index.html" */ ?>
<?php /*%%SmartyHeaderCode:2733954854f0b873141-54027549%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '82fe95d712e79d7aac77814df1002ce5b92079ea' => 
    array (
      0 => '.\\admin\\Tpl\\Channel\\index.html',
      1 => 1387988158,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2733954854f0b873141-54027549',
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
  'unifunc' => 'content_54854f0b9b6470_47660028',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f0b9b6470_47660028')) {function content_54854f0b9b6470_47660028($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>


<script type="text/javascript">
	function del(id){
		var submit = function (v, h, f) {
		if (v == 'ok')
			window.location.href='/iokadmin.php?m=Channel&a=del&id='+id;
			//jBox.tip(v, 'info');
		else if (v == 'cancel')
			jBox.tip('已取消', 'info');

			return true; //close
		};

		$.jBox.confirm("确定删除吗？", "提示", submit);
	}
</script>


		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("Channel/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box">
					<!-- box / title -->
					<div class="title" style="margin-bottom:10px;">
						<h5>渠道商列表</h5>
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
									<th>代理级别</th>
									<th>地区</th>
									<th>推荐人</th>
									<th>登陆次数</th>
									<th>注册时间</th>
									<th>注册IP</th>
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
									<td><a href="?m=Channel&a=detail&id=<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['account'];?>
</a></td>
									<td><?php if ($_smarty_tpl->tpl_vars['item']->value['gradeid']==1){?>省级代理
										<?php }elseif($_smarty_tpl->tpl_vars['item']->value['gradeid']==2){?>市级代理
										<?php }elseif($_smarty_tpl->tpl_vars['item']->value['gradeid']==3){?>县级代理
										<?php }else{ ?>未知级别<?php }?>
									</td>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['areaid'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['inviterid'];?>

										<!-- <?php if (is_array($_smarty_tpl->tpl_vars['item']->value['inviterid']['account'])){?><?php echo $_smarty_tpl->tpl_vars['item']->value['inviterid']['account'];?>

										<?php }elseif($_smarty_tpl->tpl_vars['item']->value['inviterid']!=''){?><?php echo $_smarty_tpl->tpl_vars['item']->value['inviterid'];?>

										<?php }else{ ?>未知<?php }?> -->
									</td>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['logincount'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['registertime'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['registerip'];?>
</td>
									<td class="category">
										<a href="?m=Channel&a=cadd&id=<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
">编辑</a>/
										<a href="javascript:del(<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
);">删除</a>
									</td>
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
								<input type="reset" value="执行操作" onclick="javascript:void(0);" />
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