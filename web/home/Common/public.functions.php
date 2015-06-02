<?php
/**
 * @author wayne
 * 
 */
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
	$begin .= "<div class=\"results\">";
	$begin .= "<span>显示 ".(($nowPage - 1) * $pageRows + 1)."-".($nowPage * $pageRows > $totalRows ? $totalRows : $nowPage * $pageRows)." 条记录，总 ".$totalRows." 条记录</span>";
	$begin .= "</div>";
	$end = "</div>";
	return $begin . $upPage . $leftPage . $midPage . $rightPage . $downPage . $end;
}
/**
	 +----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码
 * 默认长度6位 字母和数字混合 支持中文
	 +----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
	 +----------------------------------------------------------
 * @return string
	 +----------------------------------------------------------
 */
function rand_string($len = 6, $type = '', $addChars = '')
{
	$str = '';
	switch($type)
	{
		case 0:
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		case 1:
			$chars = str_repeat('0123456789', 3);
			break;
		case 2:
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
			break;
		case 3:
			$chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		default:
			// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
			$chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
			break;
	}
	if($len > 10)
	{ //位数过长重复字符串一定次数
		$chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
	}
	if($type != 4)
	{
		$chars = str_shuffle($chars);
		$str = substr($chars, 0, $len);
	}else
	{
		// 中文随机字
		for($i = 0; $i < $len; $i++)
		{
			$str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
		}
	}
	return $str;
}

/**
	 +------------------------------------------------------------------------------------
 * 生成复选框
	 +------------------------------------------------------------------------------------
 * @param  array  $sarray    选项内容和value属性值（value是否和选项内容相同取决于$key, 
 * 默认数组的键做value而值做内容）
 * @param  string $name 	 select的name值
 * @param  string $title     标题
 * @param  mixed  $selected  默认选中的选项
 * @param  string $extend    select的附加属性
 * @param  int    $key		  是否用数组的键值做选项的value, 默认是
 * @param  mieed  $ov		  标题的value值
 * @param  int    $abs		  是否进行严格的比较, 默认不进行
	 +------------------------------------------------------------------------------------
 * @return string 			 组合好后的select列表
	 +------------------------------------------------------------------------------------
 */
function addcheckbox($sarray, $name, $checked = '', $extend = '', $key = 1, $except = '', $abs = 0)
{
	$checked = $checked ? explode(',', $checked) : array();
	$except = $except ? explode(',', $except) : array();
	$checkbox = $sp = '';
	foreach($sarray as $k => $v)
	{
		if(in_array($key ? $k : $v, $except)) continue;
		$sp = in_array($key ? $k : $v, $checked) ? ' checked ' : '';
		$checkbox .= '<input type="checkbox" name="' . $name . '" value="' . ($key ? $k : $v) . '"' . $sp . $extend . '> ' . $v . '&nbsp;';
	}
	return $checkbox;
}
/**
	 +------------------------------------------------------------------------------------
 * 通过id获取地区名称 格式： 北京市 大兴区 经济技术开发区
	 +------------------------------------------------------------------------------------
 * @param  int     $id   		地区id
 * @param  boolean $is_ajax 	是否为ajax获取（默认为false）
	 +------------------------------------------------------------------------------------
 * @return string 			 组合好的地区名称
	 +------------------------------------------------------------------------------------
 */
function get_areaname($id,$is_ajax=false){	
	$mm=new Model();
	$sql="select prettyname,parentid,pparentid from iok_area where id=".$id;
	$res=$mm->query($sql);
	if($res[0]['parentid']){
		$sql="select prettyname from iok_area where id=".$res[0]['parentid'];
		$pres[0]=$mm->query($sql);
	}
	if($res[0]['pparentid']){
		$sql="select prettyname from iok_area where id=".$res[0]['pparentid'];
		$pres[1]=$mm->query($sql);
	}
	if($is_ajax){
		echo $pres[0][0]['prettyname']." ".$pres[0][1]['prettyname']." ".$res[0]['prettyname'];
	}else{
		return $pres[0][0]['prettyname']." ".$pres[0][1]['prettyname']." ".$res[0]['prettyname'];	
	}
	// dump($pres);
}




?>