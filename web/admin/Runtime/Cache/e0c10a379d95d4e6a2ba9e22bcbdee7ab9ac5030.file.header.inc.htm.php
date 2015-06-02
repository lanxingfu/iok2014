<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:11:07
         compiled from ".\admin\Tpl\header.inc.htm" */ ?>
<?php /*%%SmartyHeaderCode:68054854f0ba3acf9-41140701%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e0c10a379d95d4e6a2ba9e22bcbdee7ab9ac5030' => 
    array (
      0 => '.\\admin\\Tpl\\header.inc.htm',
      1 => 1387722622,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '68054854f0ba3acf9-41140701',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'baseurl' => 0,
    'all' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54854f0bafe234_97223732',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f0bafe234_97223732')) {function content_54854f0bafe234_97223732($_smarty_tpl) {?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>我行网后台管理系统</title>
	<link href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/style/admin/reset.css" rel="stylesheet" type="text/css" />
	<link href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/style/admin/style.css" rel="stylesheet" type="text/css" media="screen" />
	<link id="siteskin" href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/style/admin/skins/<?php echo (($tmp = @$_COOKIE['skin'])===null||$tmp==='' ? 'blue' : $tmp);?>
.css" rel="stylesheet" type="text/css" />
	<link id="jboxskin" href="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/style/jbox/skins/<?php echo (($tmp = @$_COOKIE['skin'])===null||$tmp==='' ? 'blue' : $tmp);?>
/jbox.css" rel="stylesheet" type="text/css" />
	
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/jquery-1.4.2.min.js" type="text/javascript"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/base.js" type="text/javascript"></script>
	
  	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/jbox/jquery.jBox-2.3.min.js" type="text/javascript"></script>
  	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/jbox/i18n/jquery.jBox-zh-CN.js" type="text/javascript"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/admin/jquery-ui-1.8.custom.min.js" type="text/javascript"></script>
	<!--[if IE]>
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/agency/excanvas.min.js" language="javascript" type="text/javascript"></script>
	<![endif]-->
	<!-- scripts (custom) -->
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/admin/smooth.js" type="text/javascript"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/admin/smooth.menu.js" type="text/javascript"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/admin/smooth.table.js" type="text/javascript"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/admin/smooth.form.js" type="text/javascript"></script>
	<?php if ((($tmp = @$_smarty_tpl->tpl_vars['all']->value)===null||$tmp==='' ? 0 : $tmp)){?>
	
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/admin/jquery.ui.selectmenu.js" type="text/javascript"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/admin/jquery.flot.min.js" type="text/javascript"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/admin/tiny_mce/tiny_mce.js" type="text/javascript"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/admin/tiny_mce/jquery.tinymce.js" type="text/javascript"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/admin/smooth.chart.js" type="text/javascript"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/admin/smooth.dialog.js" type="text/javascript"></script>
	<script src="<?php echo $_smarty_tpl->tpl_vars['baseurl']->value;?>
/public/script/admin/smooth.autocomplete.js" type="text/javascript"></script>
	<?php }?>
	
	<script type="text/javascript">
		$(document).ready(function () {

			$("#date-picker").datepicker();
			$("#box-tabs, #box-left-tabs").tabs();
			/*
			$("input.focus").focus(function () {
				if (this.value == this.defaultValue) {
					this.value = "";
				}
				else {
					this.select();
				}
			});

			$("input.focus").blur(function () {
				if ($.trim(this.value) == "") {
					this.value = (this.defaultValue ? this.defaultValue : "");
				}
			});

			$("input:submit, input:reset").button();
			*/
		});
	</script>
	
</head>
<body>
<?php }} ?>