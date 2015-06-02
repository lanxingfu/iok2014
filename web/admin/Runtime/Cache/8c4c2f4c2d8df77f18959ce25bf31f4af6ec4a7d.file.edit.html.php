<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:43:15
         compiled from ".\admin\Tpl\Property\edit.html" */ ?>
<?php /*%%SmartyHeaderCode:23429548556930de111-27329997%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8c4c2f4c2d8df77f18959ce25bf31f4af6ec4a7d' => 
    array (
      0 => '.\\admin\\Tpl\\Property\\edit.html',
      1 => 1387722620,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '23429548556930de111-27329997',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'catData' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_548556934c54e8_37455200',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_548556934c54e8_37455200')) {function content_548556934c54e8_37455200($_smarty_tpl) {?><?php if (!is_callable('smarty_function_tip')) include 'D:\\WorkSpace\\PHP\\iok2014\\web\\ThinkPHP\\Extend\\Vendor\\Smarty\\plugins\\function.tip.php';
?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<script src="public/script/clienthint_ajax.js"></script>
<script type="text/javascript">
function queryCity11(citycode,dengji,nowValue)
{
	queryCity("?m=Property&a=datasp&parentid="+citycode+"&n="+Math.random(),dengji,nowValue);
}
 

</script>
<!-- content -->
<div id="content">
	<?php echo $_smarty_tpl->getSubTemplate ("Syscfg/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<!-- content / right -->
	<div id="right">
		<div class="box">
			<!-- box / title -->
			<div class="title">
				<h5>添加属性</h5>
			</div>
			<form id="form" action="?m=Property&a=submit" method="post">
				<div class="form">
					<div class="fields">
						<div class="field  field-first">
							<div class="label">
								<label for="input-small">所属分类 :</label>
							</div>
							<div class="select">
								<!--输出下拉列表框，并设定onchange响应事件，把省值传递过去-->
								<input type="hidden" id='catid' name='catid' value="<?php if ($_smarty_tpl->tpl_vars['data']->value['categoryid']){?><?php echo $_smarty_tpl->tpl_vars['data']->value['categoryid'];?>
<?php }else{ ?>0<?php }?>" />
								<select id='province' name='pparentid' onChange="queryCity11(this.options[this.selectedIndex].value,'city',this.value)">
								<!--输出下拉列表项值-->
								<option value='-1' selected>选择分类</option>
									<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['catData']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
										<option value='<?php echo $_smarty_tpl->tpl_vars['item']->value['id'];?>
'><?php echo $_smarty_tpl->tpl_vars['item']->value['prettyname'];?>
</option>
									<?php } ?>
								</select><!--下拉列表项尾数-->	
								<span id='city'></span>
							</div>
							<div class="tip"><?php echo smarty_function_tip(array('id'=>"catid"),$_smarty_tpl);?>
</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">属性名:</label>
							</div>
							<div class="input">
								<input type="text" id="input-small" name="prettyname" class="small" value="<?php echo $_smarty_tpl->tpl_vars['data']->value['prettyname'];?>
"/> 
							</div>
						</div>
						<div class="tip"><?php echo smarty_function_tip(array('id'=>'prettyname'),$_smarty_tpl);?>
</div>
						<div class="field">
							<div class="buttons">
								<div class="highlight">
									<input type="submit" name="submit" value="保存" />
								</div>
								<input type="reset" name="reset" value="取消" />
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	<!-- end content / right -->
	</div>
</div>
<!-- end content -->
<?php echo $_smarty_tpl->getSubTemplate ("footer.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>