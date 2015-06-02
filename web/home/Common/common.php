<?php
/**
 * @author wayne
 * 
 */
header("Content-type:text/html;charset=utf-8");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");

require(APP_PATH . '/Common/public.functions.php');
require(APP_PATH . '/Common/sunwei.functions.php');
require(APP_PATH . 'Common/wuzhijie.functions.php');
require(APP_PATH . 'Common/lanxingfu.functions.php');

/**
 * 字符串截取
 * $str 		需要转换的字符串
 * $start 		开始位置
 * $length 		截取长度
 * $charset	 	编码格式
 * $suffix	 	截断显示字符
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true,$maxlen=20,$cut=false)
{
	if((mb_strlen($str,'UTF-8') <= $maxlen) && $cut){
		return $str;
	}else{
		if(function_exists("mb_substr"))
			$slice = mb_substr($str, $start, $length, $charset);
		elseif(function_exists('iconv_substr')) {
			$slice = iconv_substr($str,$start,$length,$charset);
		}else{
			$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
			$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
			$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
			$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
			preg_match_all($re[$charset], $str, $match);
			$slice = join("",array_slice($match[0], $start, $length));
		}
		return $suffix ? $slice.'...' : $slice;
	}
}
//根据现有的图片路径，获得原始图片
function get_bigimg($imgpath, $action = 'thumb')
{
	$exp = "." . $action . ".";
	$img_name = explode($exp, $imgpath);
	return $img_name[0];
}