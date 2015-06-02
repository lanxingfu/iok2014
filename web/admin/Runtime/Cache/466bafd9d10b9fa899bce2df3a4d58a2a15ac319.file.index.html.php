<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:11:38
         compiled from ".\admin\Tpl\MarketImage\index.html" */ ?>
<?php /*%%SmartyHeaderCode:2669154854f2acd36b6-26574849%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '466bafd9d10b9fa899bce2df3a4d58a2a15ac319' => 
    array (
      0 => '.\\admin\\Tpl\\MarketImage\\index.html',
      1 => 1389755364,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2669154854f2acd36b6-26574849',
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
  'unifunc' => 'content_54854f2adec753_84465703',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f2adec753_84465703')) {function content_54854f2adec753_84465703($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

 
<script language="javascript" type="text/javascript" src="/public/script/admin/thickbox.js" /></script>
<link rel="stylesheet" href="/public/style/admin/thickbox.css" type="text/css" media="screen" />
<script type="text/javascript">
    KE.show({
        id : 'infocontent' ,//TEXTAREA输入框的ID
		minWidth:"650px",
		minHeight:"400px",
		resizeMode:0
	});
	<!-- js 编辑器-->
	$(function(){
		$("#istop-1").click(function(){
			$("#uploaddiv").css("display","none");
		});
		$("#istop-2").click(function(){
			var typeid=$("#categorytype option:selected").val();
			
			if(typeid == 8189 || typeid == 10002 || typeid == 10003 || typeid == 10004){
				$("#uploaddiv").css("display","block");
			}
		});
		$("#categorytype").change(function(){
			var typeid=$(this).val();
			if($("#istop-2").attr("checked") && (typeid == 8189 || typeid == 10002 || typeid == 10003 || typeid == 10004)){
				$("#uploaddiv").css("display","block");
			}else{
				$("#uploaddiv").css("display","none");
			}
		});
	});
</script>

		<!-- content -->
		<div id="content">
			<?php echo $_smarty_tpl->getSubTemplate ("MarketImage/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- content / right -->
			<div id="right">
				<!-- table -->
				<div class="box">
					<!-- box / title -->
					<div class="title" style="margin-bottom:10px;">
						<h5>焦点图列表</h5>
						<?php echo $_smarty_tpl->getSubTemplate ("record.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					</div>
					<?php echo $_smarty_tpl->getSubTemplate ("search.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

					<!-- end box / title -->
					<div class="table">
						<table>
							<thead>
								<tr>
									<th class="left">编号</th>
									<th>标题</th>
									<th>分类</th>
									<th>添加会员</th>
									<th>添加时间</th>
									<th>更新时间</th>
									<th>更新会员</th>
									<th>状态</th>
									<th>操作</th>
									<th class="selected last"><input type="checkbox" class="checkall" /></th>
								</tr>
							</thead>
							
							<tbody>
							<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
								<tr>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
</td>
									<td><a target="_blank" href="/Invest/show/itemid/<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</a></td>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['categoryid'];?>
</td>
									<!-- <td>
										<div class="layout auto wenchua" id="wenchuan">
											<div>
												<a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['thumbd'];?>
"  title="" class="thickbox">
												<img width="40px" height="20px" src="<?php echo $_smarty_tpl->tpl_vars['item']->value['thumb'];?>
"/></a>
											</div>
										</div>
									</td> -->
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['adduserid']['account'];?>
</td>
									<td><?php echo $_smarty_tpl->tpl_vars['item']->value['addtime'];?>
</td>
									<td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['updatetime'])===null||$tmp==='' ? '暂无' : $tmp);?>
</td>
									<td><?php echo (($tmp = @$_smarty_tpl->tpl_vars['item']->value['updateuserid']['account'])===null||$tmp==='' ? '暂无' : $tmp);?>
</td>
									<td><?php if ($_smarty_tpl->tpl_vars['item']->value['switchon']==1){?>已开启<?php }else{ ?>已关闭<?php }?></td>
									<td>
										<a href="?m=MarketImage&a=add&id=<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
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