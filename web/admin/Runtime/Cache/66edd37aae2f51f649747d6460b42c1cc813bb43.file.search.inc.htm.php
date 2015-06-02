<?php /* Smarty version Smarty-3.1.14, created on 2014-12-08 15:11:07
         compiled from ".\admin\Tpl\search.inc.htm" */ ?>
<?php /*%%SmartyHeaderCode:853254854f0bc66e14-00900365%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '66edd37aae2f51f649747d6460b42c1cc813bb43' => 
    array (
      0 => '.\\admin\\Tpl\\search.inc.htm',
      1 => 1389245608,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '853254854f0bc66e14-00900365',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'items' => 0,
    'item' => 0,
    'keyword' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.14',
  'unifunc' => 'content_54854f0bc9dc31_77637366',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_54854f0bc9dc31_77637366')) {function content_54854f0bc9dc31_77637366($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include 'D:\\WorkSpace\\PHP\\iok2014\\web\\ThinkPHP\\Extend\\Vendor\\Smarty\\plugins\\function.html_options.php';
?><div id="search_div">
	<div style="padding:10px 20px;">
		<?php if ((($tmp = @$_smarty_tpl->tpl_vars['items']->value)===null||$tmp==='' ? '' : $tmp)){?>
		搜索项目：
		<select id="searchitem">
		<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['items']->value,'selected'=>$_smarty_tpl->tpl_vars['item']->value),$_smarty_tpl);?>

		</select>
		<?php }?>
		关键字：<input id="keyword" type="text" style="width:200px;" value="<?php echo (($tmp = @stripslashes(htmlspecialchars($_smarty_tpl->tpl_vars['keyword']->value, ENT_QUOTES, 'UTF-8', true)))===null||$tmp==='' ? '' : $tmp);?>
" />
		<span>
			<a style="padding:2px 15px;" title="搜索" onclick="javascript:searchkey();">搜索</a>
		</span>
	</div>
</div><?php }} ?>