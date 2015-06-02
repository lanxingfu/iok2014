<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:11:07
         compiled from ".\admin\Tpl\record.inc.htm" */ ?>
<?php /*%%SmartyHeaderCode:2679154854f0bbea745-84961798%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd20f9229ab9d4f70e606e16b7948f7d9ec5c90a4' => 
    array (
      0 => '.\\admin\\Tpl\\record.inc.htm',
      1 => 1387722620,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2679154854f0bbea745-84961798',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54854f0bc3f351_79089112',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f0bc3f351_79089112')) {function content_54854f0bc3f351_79089112($_smarty_tpl) {?><div class="search">
	<span style="color:#fff">每页显示：</span>
	<select id="record" onchange="javascript:recordchange()">
		<option value="5"<?php if ($_GET['record']==5){?> selected<?php }?>>5</option>
		<option value="10"<?php if (empty($_GET['record'])||$_GET['record']==10){?> selected<?php }?>>10</option>
		<option value="20"<?php if ($_GET['record']==20){?> selected<?php }?>>20</option>
		<option value="50"<?php if ($_GET['record']==50){?> selected<?php }?>>50</option>
	</select>
</div><?php }} ?>