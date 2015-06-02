<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:42:55
         compiled from ".\admin\Tpl\Syscfg\seoset.html" */ ?>
<?php /*%%SmartyHeaderCode:227805485567f0ca199-62028886%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dad3ed81dd60fbd89fb32b818bc1add0207040f1' => 
    array (
      0 => '.\\admin\\Tpl\\Syscfg\\seoset.html',
      1 => 1387722620,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '227805485567f0ca199-62028886',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_5485567f122cc6_14800999',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5485567f122cc6_14800999')) {function content_5485567f122cc6_14800999($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<!-- content -->
<div id="content">
	<?php echo $_smarty_tpl->getSubTemplate ("Syscfg/leftmenu.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<!-- content / right -->
	<div id="right">
		<div class="box">
			<!-- box / title -->
			<div class="title">
				<h5>SEO优化</h5>
			</div>
			<form id="form" action="" method="post">
				<div class="form">
					<div class="fields">
						<div class="field  field-first">
							<div class="label">
								<label for="input-small">标题分隔符:</label>
							</div>
							<div class="input">
								<input type="text" id="input-small" name="input.small" class="small" value="我行网"/>
								<div class="button highlight">
									<input type="submit" name="submit.highlight" value="检测" />
								</div>
							</div>
						</div>
						<div class="field">
							<div class="label">
								<label for="input-small">Title(网站标题)：</label>
							</div>
							<div class="input">
								<input type="text" id="input-small" name="input.small" class="small" value="http://www.iokokok.com" />
							</div>
						</div>
						<div class="field">
							<div class="label label-textarea" >
								<label for="textarea">网页描述：</label>
							</div>
							<div class="textarea">
								<textarea id="textarea1" name="textarea"  style="height:50px;" cols="20" rows="4">content</textarea>
							</div>
						</div>
						<div class="field">
							<div class="label label-textarea">
								<label for="textarea">网页关键字：</label>
							</div>
							<div class="textarea">
								<textarea id="textarea1" name="textarea"  style="height:50px;" cols="20" rows="4">meta</textarea>
							</div>
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
			</form>
		</div>
	</div>
	<!-- end content / right -->
</div>
<!-- end content -->
<?php echo $_smarty_tpl->getSubTemplate ("footer.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>