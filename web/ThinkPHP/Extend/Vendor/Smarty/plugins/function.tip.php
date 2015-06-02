<?php
/**
 * Smarty plugin
 * 
 * @package Smarty
 * @subpackage PluginsFunction
 */
/**
 * Smarty {tip} function plugin
 * 
 * Type:     function
 * Name:     tip
 * Purpose:  show tip message
 * 
 * @author Wayne Sunway 
 * @param array $params parameters
 * Input:
 *            - id       	(optional) - string for the widget
 *            - default     (optional) - default tip string
 *            - top         (optional) - top tip flag
 * @param object $smarty Smarty object
 * @param object $template template object
 * @return string 
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_tip($params, $template)
{
	require_once (SMARTY_PLUGINS_DIR . 'shared.escape_special_chars.php');
	$tip = '';
	$tip_type = '';
	$tip_text = '';
	if(isset($params['id']))
	{
		if($params['id'] != 'top' && $params['id'] != 'top_notice' && $params['id'] != 'top_warning' && $params['id'] != 'top_error' && $params['id'] != 'top_success')
		{
			$tip = 'tip';
			if(isset($template->parent->tpl_vars['tip']) && in_array($params['id'], array_keys($template->parent->tpl_vars['tip']->value)))
			{
				$tip_type = 'error';
				$tip_text = $template->parent->tpl_vars['tip']->value[$params['id']];
			}elseif(isset($params['default']))
			{
				$tip_type = 'notice';
				$tip_text = $params['default'];
			}
		}else
		{
			$tip = 'top';
			if(isset($template->parent->tpl_vars['tip']) && in_array('top_warning', array_keys($template->parent->tpl_vars['tip']->value)))
			{
				$tip_type = 'warning';
				$tip_text = $template->parent->tpl_vars['tip']->value['top_warning'];
			}elseif(isset($template->parent->tpl_vars['tip']) && in_array('top_error', array_keys($template->parent->tpl_vars['tip']->value)))
			{
				$tip_type = 'error';
				$tip_text = $template->parent->tpl_vars['tip']->value['top_error'];
			}elseif(isset($template->parent->tpl_vars['tip']) && in_array('top_notice', array_keys($template->parent->tpl_vars['tip']->value)))
			{
				$tip_type = 'notice';
				$tip_text = $template->parent->tpl_vars['tip']->value['top_notice'];
			}elseif(isset($template->parent->tpl_vars['tip']) && in_array('top_success', array_keys($template->parent->tpl_vars['tip']->value)))
			{
				$tip_type = 'success';
				$tip_text = $template->parent->tpl_vars['tip']->value['top_success'];
			}elseif(isset($template->parent->tpl_vars['tip']) && in_array('top', array_keys($template->parent->tpl_vars['tip']->value)))
			{
				$tip_type = 'error';
				$tip_text = $template->parent->tpl_vars['tip']->value['top'];
			}elseif(isset($params['default']))
			{
				$tip_text = $params['default'];
				switch($params['id'])
				{
					case 'top_error':
						$tip_type = 'error';
						break;
					case 'top_warning':
						$tip_type = 'warning';
						break;
					case 'top_success':
						$tip_type = 'success';
						break;
					default:
						$tip_type = 'notice';
				}
			}
		}
	}elseif(isset($params['default']))
	{
		$tip = 'tip';
		$tip_type = 'notice';
		$tip_text = $params['default'];
	}
	$_html_result = '';
	if($tip_text)
	{
		if($tip == 'tip')
		{
			$_html_result .= "<span id=\"tip-".$tip_type."\"><img src=\"/public/image/tip_" . $tip_type . ".gif\" title=\"" . ($tip_type == 'error' ? "错误信息" : "提示信息") . "\" style=\"vertical-align:middle\" />";
			$_html_result .= "&nbsp;<span" . ($tip_type == 'error' ? " style=\"color:red;vertial-align:middle\"" : "") . ">" . smarty_function_escape_special_chars($tip_text) . "</span></span>\n";
		}elseif($tip == 'top')
		{
			$_html_result .= "<div id=\"message-".$tip_type."\" class=\"message message-".$tip_type."\">";
			$_html_result .= "<div class=\"image\"><img src=\"/public/image/top_".$tip_type.".png\" alt=\"Error\" height=\"32\" /></div>";
			$_html_result .= "<div class=\"text\"><h6>".($tip_type == 'warning' ? "警告信息" : ($tip_type == 'error' ? "错误信息" : ($tip_type == 'success' ? "成功信息" : "提示信息")))."</h6><span>".smarty_function_escape_special_chars($tip_text)."</span></div>";
			$_html_result .= "<div class=\"dismiss\"><a href=\"#message-".$tip_type."\"></a></div></div>";
		}
	}
	return $_html_result;
}
?>