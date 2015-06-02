<?php
/**
 * author: jia
 * description: 首页公共搜索框
 * last modified by: jia
 * last modified date: 2013/12/3
 * last modified content:2013/12/5
 */
 class SearchAction extends CommonAction{
	public function index(){
	
	}

	/**
	 * 字符串截取
	 * $str 		需要转换的字符串
	 * $start 		开始位置
	 * $length 		截取长度
	 * $charset	 	编码格式
	 * $suffix	 	截断显示字符
	 */
	public function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true,$maxlen=20,$cut=false)
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
	public function listy()
	{
		$type = getvar('type');//搜索类别（'product'-供应,'buy'--求购,'news'--资讯）
		$keyword = mysql_real_escape_string(trim(stripslashes(getvar('keyword'))));
		$sortby = getvar('sortby');
		$orderby = getvar('orderby');
		if(!in_array($type, array('product', 'buy', 'news')))
		{
			$type = 'product';
		}
		if(!in_array($sortby, array('price', 'sales', 'hits', 'editdate')))
		{
			$sortby = 'price';
		}
		if($orderby == 'asc')
		{
			$orderby = 'asc';
			$newby = 'desc';
		}else
		{
			$orderby = 'desc';
			$newby = 'asc';
		}
		$this->assign('keyword', $keyword);
		$this->assign('type', $type);
		$this->assign('sortby', $sortby);
		$this->assign('orderby', $newby);
		switch($type){
			case 'buy': //求购搜索;
			break;
			case 'news'://资讯搜索;
			break;
			default://供应搜索;
				import("@.ORG.Util.Page");
				$sq =" select 
						count(id) as cnt
					from 	
						iok_product 
					where 	
						status='audit' and  title like '%".$keyword."%'
					order by
						$sortby $orderby
					";
				$totalrecords = $this->res($sq);	
				$Page = new Page($totalrecords, 8);
				$showpage = $Page->show();
				$sql2 ="
					select 
						id,title,thumb,price,unit,moq,inventory,hits
					from 	
						iok_product 
					where 	
						status='audit' and title like '%" . $keyword . "%'
					order by
						$sortby $orderby
					limit 	$Page->firstRow, $Page->listRows ";
				$rec = $this->arr($sql2);
				foreach($rec as $k => $t)
				{
					$t['title'] = $t['title'] ? $this->msubstr($t['title'], 0, 16, 'utf-8', false) : "暂无";
					$rec[$k] = $t;
				}
				//热销商品	
				//$dataty =  $product->field("id,title,thumb,price,unit,inventory,moq,hits")->where("status=3")->order('addtime DESC')->limit(6)->select();
				
				
				
				foreach($dataty as $k=>$t){
					$t['title'] = $t['title']?$this->msubstr($t['title'],0,8,'utf-8',false):"暂无";
					$tongcate[$k] = $t;
				}			
				$this->assign("nav", $nav); //导航		
				$this->assign("showpage", $showpage); //分页		
				$this->assign("data", $rec); //列表
				$this->assign("tongcate", $tongcate); //热销				
				$this->assign("title", $keyword."_".$keyword."供应_".$keyword."行情/价格_".$keyword."搜索信息_我行网");
				$this->display('listproduct');
		}
	}
}