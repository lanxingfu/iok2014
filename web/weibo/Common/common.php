<?php
/**
 * @author wayne
 * 
 */

header("Content-type:text/html;charset=utf-8");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");

/* for dubug functions */
function dmp($arr, $var = "")
{
	echo "file:" . __FILE__ . "<br>line:" . __LINE__ . "<br>var:" . $var;
	echo "<pre>";
	if(is_array($arr))
	{
		print_r($arr);
	}else
	{
		var_dump($arr);
	}
	echo "</pre><br><br>";
}

function getvar($vartag, $dval = NULL, $vartype = "string")
{
	if(isset($_POST[$vartag]))
	{
		$return = $_POST[$vartag];
	}elseif(isset($_GET[$vartag]))
	{
		$return = $_GET[$vartag];
	}else
	{
		$return = $dval;
	}
	switch($vartype)
	{
		case "integer":
			if(!ctype_digit($return))
			{
				$return = ($dval ? $dval : 0);
			}
			break;
		case "list":
			$arr = array();
			foreach(explode(',', $return) as $val)
			{
				if(ctype_digit($val))
				{
					$arr[] = $val;
				}
			}
			$return = implode(',', $arr);
			break;
		case 'array':
			if(empty($return))
			{
				$return = $dval ? $dval : array();
			}
			break;
		default:
			if(strlen($return) == 0)
			{
				$return = $dval;
			}
	}
	return $return;
}
function getitems($searchitems)
{
	$items = C('ITEMS');
	$rtn = array();
	if(is_array($searchitems) && $searchitems)
	{
		foreach($searchitems as $val)
		{
			if(isset($items[MODULE_NAME][$val]))
			{
				$rtn[$val] = $items[MODULE_NAME][$val];
			}elseif(isset($items['public'][$val]))
			{
				$rtn[$val] = $items['public'][$val];
			}else
			{
				$rtn[$val] = $val;
			}
		}
	}
	return $rtn;
}
function randcode($length = 32, $type = 'all')
{
	if(!preg_match('/^\d+$/', $length))
	{
		$length = 32;
	}
	$char['number'] = '1234567890';
	$char['lower'] = 'abcdefghijklmnopqrstuvwxyz';
	$char['upper'] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$char['hex'] = $char['number'] . 'abcdef';
	$char['real'] = '23456789ABDEFGHJMNQRTYabcdefghjmnqrty';
	$char['all'] = $char['number'] . $char['lower'] . $char['upper'];
	if(!isset($type) || !isset($char[$type])) $type = 'all';
	$hash = '';
	for($i = 0; $i < $length; $i++)
	{
		$hash .= $char[$type][mt_rand(1, strlen($char[$type])) - 1];
	}
	return $hash;
}
function totimestamp($datetime)
{
	$tmp = explode(' ', $datetime);
	list($year, $month, $day) = explode('-', $tmp[0]);
	list($hour, $min, $sec) = explode(':', $tmp[1]);
	return mktime($hour, $min, $sec, $month, $day, $year, -1);
}
function todatetime($timestamp)
{
	return date("Y-m-d H:i:s", $timestamp);
}
function gotourl($url = '', $delay = 0)
{
	$delay = (int)$delay;
	if(headers_sent() || $delay > 0)
	{
		echo '<html><head><meta http-equiv="refresh" content="' . $delay . ';URL=' . ($url ? $url : "/") . '" /></head></html>';
	}else
	{
		header('Location: ' . ($url ? $url : "/"));
	}
	exit();
}
/* use js to send a alert and redirect a new url */
function js_alert($message = '', $url = '', $after_action = '')
{
	global $errors;
	$out = "<script language=\"javascript\" type=\"text/javascript\">\n";
	if(!empty($message))
	{
		if(isset($errors['js_alert'][$message]))
		{
			$message = $errors['js_alert'][$message];
		}elseif(isset($errors['public'][$message]))
		{
			$message = $errors['public'][$message];
		}
		$out .= "alert(\"";
		$out .= str_replace("\\\\n", "\\n", str_replace(array("\r", "\n"), array('', '\n'), addslashes((addslashes($message)))));
		$out .= "\");\n";
	}
	if(!empty($after_action))
	{
		$out .= $after_action . "\n";
	}
	if(!empty($url))
	{
		$out .= "document.location.href=\"";
		$out .= $url;
		$out .= "\";\n";
	}
	$out .= "</script>";
	echo $out;
	exit();
}

function checkaccess($code = null, $urlback = false)
{
	if(empty($code)) $code = MODULE_NAME . "." . ACTION_NAME;
	$hasprv = false;
	if(isset($_SESSION['user']['node'][$code]) && $_SESSION['user']['node'][$code])
	{
		$hasprv = true;
	}
	if(!$hasprv && $urlback)
	{
		js_alert('nopermission', '', 'window.history.back(-1)');
	}
	return $hasprv;
}
function itemcheck($item = 'require', $value = '')
{
	$regexp = array(
		'require'=>'/.+/', 
		'account'=>'/^[a-z0-9-_]{4,20}$/',
		'passwd'=>'/^.{6,20}$/', 
		'email'=>'/^\w+([\.\w-]+)*@([a-z0-9\-]+\.)+[a-z0-9]{2,4}$/i', 
		'telephone'=>'/^(0[1-2][0-9]-?)[2-8]\d{7}(-\d+)?$|^(0[3-9]\d{2}-?)[2-8]\d{6,7}(-\d+)?$/', 
		'mobile'=>'/^1[3,5,8]\d{9}$/', 
		'url'=>'/^http|https:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/', 
		'currency'=>'/^\d+(\.\d+)?$/', 
		'number'=>'/^\d+$/', 
		'zipcode'=>'/^[0-9]\d{5}$/', 
		'qq'=>'/^[1-9]\d{4,10}$/', 
		'integer'=>'/^[-\+]?\d+$/', 
		'double'=>'/^[-\+]?\d+(\.\d+)?$/', 
		'english'=>'/^[A-Za-z]+$/', 
		'chinese'=>'/^[\u0391-\uFFE5]+$/');
	if(isset($regexp[$item]))
	{
		if(preg_match($regexp[$item], trim($value)))
		{
			return true;
		}
	}
	return false;
}
function setpage($totalRows, $nowPage = 1, $pageRows = 10, $inlink = '')
{
	// must 1,3,5,7 ...
	$rollPage = 5;
	$totalPages = ceil($totalRows / $pageRows);
	if(!empty($totalPages) && $nowPage > $totalPages)
	{
		$nowPage = $totalPages;
	}
	$firstRow = $pageRows * ($nowPage - 1);
	$p = C('VAR_PAGE');
	$url = $_SERVER['REQUEST_URI'] . (strpos($_SERVER['REQUEST_URI'], '?') ? '' : "?");
	$parse = parse_url($url);
	if(isset($parse['query']))
	{
		parse_str($parse['query'], $params);
		unset($params[$p]);
		$url = $parse['path'] . '?' . http_build_query($params);
	}
	
	$upPage = "<ul class=\"pager\">";
	if($nowPage > 1)
	{
		$upPage .= "<li><a href=\"" . $url . "&" . $p . "=" . ($nowPage - 1) . $inlink . "\">&laquo; 前一页</a></li>";
	}else
	{
		$upPage .= "<li class=\"disabled\">&laquo; 前一页</li>";
	}
	
	if($nowPage < $totalPages)
	{
		$downPage = "<li><a href=\"" . $url . "&" . $p . "=" . ($nowPage + 1) . $inlink . "\">后一页 &raquo;</a></li>";
	}else
	{
		$downPage = "<li class=\"disabled\">后一页 &raquo;</li>";
	}
	$downPage .= "</ul>";
	
	// leftpage 1 ... or none
	if($totalPages <= $rollPage + 1)
	{
		$leftPage = "";
	}else
	{
		if($nowPage < $rollPage)
		{
			$leftPage = "";
		}else
		{
			$leftPage = "<li><a href=\"" . $url . "&" . $p . "=1" . $inlink . "\">1</a></li>";
			$leftPage .= "<li><a href=\"" . $url . "&" . $p . "=" . ceil(($nowPage - 1)/2) .$inlink . "\">...</a></li>";
		}
	}
	
	$midPage = "";
	$rollPages = (($rollPage + 1) >= $totalPages ? $totalPages : $rollPage);
	
	for($i = 1; $i <= $rollPages; $i++)
	{	
		if($totalPages <= $rollPage + 1)
		{		
			if($i == $nowPage)
			{
				$midPage .= "<li class=\"current\">" . $i. "</li>";
			}else
			{
				$midPage .= "<li><a href=\"" . $url . "&" . $p . "=" . $i . $inlink . "\">" . $i . "</a></li>";
			}
		}else
		{
			if($nowPage < $rollPages)
			{
				if($i == $nowPage)
				{
					$midPage .= "<li class=\"current\">" . $i. "</li>";
				}else
				{
					$midPage .= "<li><a href=\"" . $url . "&" . $p . "=" . $i . $inlink . "\">" . $i . "</a></li>";
				}
			}elseif($nowPage >= $rollPages && $nowPage < $totalPages - ceil($rollPage/2))
			{
				$coolPage = $i + $nowPage - ceil($rollPage/2);
				if($coolPage == $nowPage)
				{
					$midPage .= "<li class=\"current\">" . $coolPage. "</li>";
				}else
				{
					$midPage .= "<li><a href=\"" . $url . "&" . $p . "=" . $coolPage. $inlink . "\">" . $coolPage . "</a></li>";
				}
			}else 
			{	
				$coolPage = $i + $totalPages - $rollPage;
				if($coolPage == $nowPage)
				{
					$midPage .= "<li class=\"current\">" . $coolPage. "</li>";
				}else
				{
					$midPage .= "<li><a href=\"" . $url . "&" . $p . "=" . $coolPage. $inlink . "\">" . $coolPage . "</a></li>";
				}
			}
		}
	}
	// rightpage ... 222 or none
	
	if($totalPages < $rollPage + 2)
	{
		$rightPage = "";
	}else 
	{
		if($totalPages == $rollPage + 2 && $nowPage == $totalPages - $rollPage + 2)
		{
			$rightPage = "<li><a href=\"" . $url . "&" . $p . "=" . ($totalPages - 1) . "\">...</a></li>";
			$rightPage .= "<li><a href=\"" . $url . "&" . $p . "=" . $totalPages . $inlink . "\">".$totalPages."</a></li>";
		}else 
		{
			if($nowPage > $totalPages - $rollPage + 1)
			{
				$rightPage = "";
			}else
			{
				//XXX
				$rightPage = "<li><a href=\"" . $url . "&" . $p . "=" . (ceil(($totalPages - $nowPage)/2) + $nowPage + 1) .$inlink . "\">...</a></li>";
				$rightPage .= "<li><a href=\"" . $url . "&" . $p . "=" . $totalPages . $inlink . "\">".$totalPages."</a></li>";
			}
		}
	}
	$begin = "<div class=\"pagination pagination-left\">";
	//$begin .= "<div class=\"results\">";
	//$begin .= "<span>显示 ".(($nowPage - 1) * $pageRows + 1)."-".($nowPage * $pageRows > $totalRows ? $totalRows : $nowPage * $pageRows)." 条记录，总 ".$totalRows." 条记录</span>";
	//$begin .= "</div>";
	$end = "</div>";
	return $begin . $upPage . $leftPage . $midPage . $rightPage . $downPage . $end;
}
?>