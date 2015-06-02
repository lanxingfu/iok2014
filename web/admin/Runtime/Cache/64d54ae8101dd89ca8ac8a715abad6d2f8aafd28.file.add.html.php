<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:12:15
         compiled from ".\admin\Tpl\Ad\add.html" */ ?>
<?php /*%%SmartyHeaderCode:2667554854f4f4ea120-42229767%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '64d54ae8101dd89ca8ac8a715abad6d2f8aafd28' => 
    array (
      0 => '.\\admin\\Tpl\\Ad\\add.html',
      1 => 1387722620,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2667554854f4f4ea120-42229767',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'id' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54854f4f550314_47107284',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f4f550314_47107284')) {function content_54854f4f550314_47107284($_smarty_tpl) {?>﻿<?php echo $_smarty_tpl->getSubTemplate ("header.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<?php echo $_smarty_tpl->getSubTemplate ("top.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<!-- content -->
		<div id="content">
			<!-- end content / left -->
			<?php echo $_smarty_tpl->getSubTemplate ("Ad/leftmenu.inc.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

			<!-- end content / left -->
			<!-- content / right -->
			<div id="right">
				<!-- forms -->
				<div class="box">
					<!-- box / title -->
					<div class="title">
						<h5>增加广告</h5>
					</div>
					<!-- end box / title -->
					<form id="form" action="/iokadmin.php/Ad/submit" method="post">
					<div class="form">
						<div class="fields">
							<div class="field  field-first">
								<div class="label">
									<label for="input-small">广告位：</label>
								</div>
								<div class="input">
									<input type="text" name="adplaceid" class="small" value="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"/>
								</div>
							</div>
							<div class="field">
								<div class="label">
									<label for="select">广告价格：</label>
								</div>
								<div class="input">
									<input type="text" name="price" class="small" />元
								</div>
							</div>
							<div class="field">
								<div class="label">
									<label for="input-medium">广告名称：</label>
								</div>
								<div class="input">
									<input type="text" name="prettyname" class="medium" />
								</div>
							</div>
							
							<div class="field">
								<div class="label label-textarea">
									<label for="textarea">广告介绍：</label>
								</div>
								<div class="textarea textarea-editor">
									<textarea name="introduce" style='height:50px;' class="editor"></textarea>
								</div>
							</div>
							<!-- <div class="field">
								<div class="label">
									<label for="date">投放时段：</label>
								</div>
								<div class="input">
									<input style='width:80px;' type="text" name="fromtime" class="date" />
								
								至
								
									<input style='width:80px;float:none' type="text" name="totime" class="date" />
								</div>
							</div> -->
							<div class="field">
								<div class="label">
									<label for="input-small">备注：</label>
								</div>
								<div class="input">
									<input type="text" class="medium" name="note"/>
								</div>
							</div>
							<!-- <div class="field">
								<div class="label label-radio">
									<label>点击统计：</label>
								</div>
								<div class="radios">
									<div class="radio">
										<input type="radio" id="radio-1" name="radioex" />
										<label for="radio-1">开启</label>
									
										<input type="radio" id="radio-2" name="radioex" />
										<label for="radio-2">关闭</label>
									</div>
								</div>
							</div> -->
							<div class="field">
								<div class="label">
									<label for="input-small">排序：</label>
								</div>
								<div class="input">
									<input type="text" name="listorder" class="small" />
								</div>
							</div>
							<div class="field">
								<div class="label label-radio">
									<label>是否启用：</label>
								</div>
								<div class="radios">
									<div class="radio">
										<input type="radio" id="radio-1" name="enabled" value="1" checked="checked"/>
										<label for="radio-1">启用</label>
									
										<input type="radio" id="radio-2" name="enabled" value="0"/>
										<label for="radio-2">关闭</label>
									</div>
								</div>
							</div> 
							
							<div class="buttons">
								<input type="reset" name="reset" value="Reset" />
								<div class="highlight">
									<input type="submit" name="submit.highlight" value="Submit" />
								</div>
							</div>
						</div>
					</div>
					</form>
				</div>
				<!-- end forms -->
			</div>
			<!-- end content / right -->
		</div>
		<!-- end content -->
<?php echo $_smarty_tpl->getSubTemplate ("footer.inc.htm", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>
<?php }} ?>