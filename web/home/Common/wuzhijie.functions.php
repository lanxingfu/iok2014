<?php
/* wuzhijie 的方法 哈哈 2013年12月30日 15:50:37 */


/**
 * 
 * Ajax地区下拉列表 wuzhijie 2013年12月30日 15:37:52 
 * @param unknown_type $name
 * @param unknown_type $title
 * @param unknown_type $areaid
 * @param unknown_type $extend
 * @param unknown_type $deep
 */
function ajax_area_select($name = 'areaid', $title = '', $areaid = 0, $extend = '', $deep = 0)
{
	global $area_id;
	if($area_id)
	{
		$area_id++;
	}else
	{
		$area_id = 1;
	}
	$areaid = intval($areaid);
	$deep = intval($deep);
	$select = '';
	$select .= '<input name="' . $name . '" id="areaid_' . $area_id . '" type="hidden" value="' . $areaid . '"/>';
	$select .= '<span id="load_area_' . $area_id . '">' . get_area_select($title, $areaid, $extend, $deep, $area_id) . '</span>';
	$select .= '<script type="text/javascript">';
	if($area_id == 1) $select .= 'var area_title = new Array;';
	$select .= 'area_title[' . $area_id . ']=\'' . $title . '\';';
	if($area_id == 1) $select .= 'var area_extend = new Array;';
	$select .= 'area_extend[' . $area_id . ']=\'' . $extend . '\';';
	if($area_id == 1) $select .= 'var area_areaid = new Array;';
	$select .= 'area_areaid[' . $area_id . ']=\'' . $areaid . '\';';
	if($area_id == 1) $select .= 'var area_deep = new Array;';
	$select .= 'area_deep[' . $area_id . ']=\'' . $deep . '\';';
	$select .= '</script>';
	/* if($area_id == 1) $select .= '<script type="text/javascript" src="/public/script//area.js"></script>'; */
	return $select;
}

/**
 * 
 * 得到地区下拉列表 wuzhijie 2013年12月30日 15:21:34 
 * @param unknown_type $title
 * @param unknown_type $areaid
 * @param unknown_type $extend
 * @param unknown_type $deep
 * @param unknown_type $id
 */
function get_area_select($title = '', $areaid = 0, $extend = '', $deep = 0, $id = 1)
{
	/* $areaDB = M('area'); */
	$areamodel = new Model();
	
	$parents = array();
	if($areaid)
	{
		/* $r = $areaDB->where("areaid=$areaid")->field('child,arrparentid')->find(); */
		$sql = "
			select id,parentid,pparentid from iok_area where id = '".$areaid."' 
		";
		$r = $areamodel->query($sql);
		if($r[0]['pparentid']!=0){
			$parents = array(0,$r[0]['pparentid'],$r[0]['parentid']);
		}elseif($r[0]['parentid']!=0 && $r[0]['pparentid']==0){
			$parents = array(0,$r[0]['parentid']);
			if(!in_array($r[0]['parentid'],array(1,2,9,22))){
				$parents[] = $areaid;
			}
		}else{
			$parents = array(0,$r[0]['id']);
		}
	}else
	{
		$parents[] = 0;
	}
	$select = '';
	foreach($parents as $k => $v)
	{
		if($deep && $deep <= $k) break;
		$v = intval($v);
		$select .= '<select onchange="load_areas(this.value,' . $id . ');" ' . $extend . ' >';
		if($title) $select .= '<option value="0">' . $title . '</option>';
		
		/* $result = $areaDB->where("parentid=$v")->field('areaid,areaname')->order('listorder ASC,areaid ASC')->select(); */
		$sqlt = "
			select id as areaid,prettyname as areaname from iok_area where parentid = '".$v."' order by listorder asc,areaid asc 
		";
		$result = $areamodel->query($sqlt);
		foreach($result as $a)
		{
			$selectid = isset($parents[$k + 1]) ? $parents[$k + 1] : $areaid;
			$selected = $a['areaid'] == $selectid ? ' selected' : '';
			$select .= '<option value="' . $a['areaid'] . '"' . $selected . '>' . $a['areaname'] . '</option>';
		}
		$select .= '</select> ';
	}
	return $select;
}


/**
 * 
 * Ajax加载分类下拉列表
 * @param string $name	 	select的name属性
 * @param string $title 	select的标题
 * @param int 	 $catid		选项的value属性
 * @param int	 $categorytypeid	模块ID
 * @param string $extend	扩展信息
 * @param int	 $deep		
 */
function ajax_category_select($name = 'catid', $title = '', $catid = 0, $categorytypeid = 1, $extend = '', $deep = 0)
{
	global $cat_id;
	if($cat_id)
	{
		$cat_id++;
	}else
	{
		$cat_id = 1;
	}
	$catid = intval($catid);
	$deep = intval($deep);
	$select = '';
	$select .= '<input name="' . $name . '" id="catid_' . $cat_id . '" type="hidden" value="' . $catid . '"/>';
	$select .= '<span id="load_category_' . $cat_id . '">' . get_category_select($title, $catid, $categorytypeid, $extend, $deep, $cat_id) . '</span>';
	$select .= '<script type="text/javascript">';
	if($cat_id == 1) $select .= 'var category_categorytypeid = new Array;';
	$select .= 'category_categorytypeid[' . $cat_id . ']="' . $categorytypeid . '";';
	if($cat_id == 1) $select .= 'var category_title = new Array;';
	$select .= 'category_title[' . $cat_id . ']=\'' . $title . '\';';
	if($cat_id == 1) $select .= 'var category_extend = new Array;';
	$select .= 'category_extend[' . $cat_id . ']=\'' . $extend . '\';';
	if($cat_id == 1) $select .= 'var category_catid = new Array;';
	$select .= 'category_catid[' . $cat_id . ']=\'' . $catid . '\';';
	if($cat_id == 1) $select .= 'var category_deep = new Array;';
	$select .= 'category_deep[' . $cat_id . ']=\'' . $deep . '\';';
	$select .= '</script>';
	return $select;
}

/**
 * 
 * 得到分类下拉列表
 * @param unknown_type $title
 * @param unknown_type $areaid
 * @param unknown_type $extend
 * @param unknown_type $deep
 * @param unknown_type $id
 */
function get_category_select($title = '', $catid = 0, $categorytypeid = 1, $extend = '', $deep = 0, $cat_id = 1)
{
	$cateDB = new Model();
	$_child or $_child = array();
	$parents = array();
	if($catid)
	{
		$r = $cateDB->query("select child,arrparentid from iok_category where id=$catid");
		$parents = explode(',', $r[0]['arrparentid']);
		if($r[0]['child']) $parents[] = $catid;
	}else
	{
		$parents[] = 0;
	}
	$select = '';
	foreach($parents as $k => $v)
	{
		if($deep && $deep <= $k) break;
		$select .= '<select onchange="load_categorys(this.value, ' . $cat_id . ');" ' . $extend . '>';
		if($title) $select .= '<option value="0">' . $title . '</option>';
		$condition = $v ? "parentid=$v" : "categorytype=$categorytypeid AND parentid=0 ";
		$result = $cateDB->query("
			select id,prettyname 
			from iok_category 
			where $condition 
			order by listorder asc,id asc 
		");
		
		foreach($result as $c)
		{
			$selectid = isset($parents[$k + 1]) ? $parents[$k + 1] : $catid;
			$selected = $c['id'] == $selectid ? ' selected' : '';
			$select .= '<option value="' . $c['id'] . '"' . $selected . '>' . $c['prettyname'] . '</option>';
		}
		$select .= '</select> ';
	}
	return $select;
}

/**
	 +---------------------------------------------------------- 
 * 获得分类 分类显示（企业资料修改处的主营行业分类）
	 +----------------------------------------------------------
 * @param int  $catid			类型
	 +----------------------------------------------------------
 * @return array  				返回分类编号
	 +----------------------------------------------------------
 */
function get_cat($catid)
{
	$DBS = new Model();
	$catid = intval($catid);
	$res = $DBS->query("
		select 
			id,
			prettyname,
			categorytype,
			parentid,
			arrparentid,
			child,
			arrchildid,
			listorder 
		from 
			iok_category 
		where 
			id = '".$catid."' 
			
	");
	return $catid ? $res[0] : array();
}
/**
	 +---------------------------------------------------------- 
 * 获取当前位置
	 +----------------------------------------------------------
 * @param array $CAT		栏目信息
 * @param string $str		间隔符
	 +----------------------------------------------------------
 * @return string           返回当前位置字符串
	 +----------------------------------------------------------
 */
function cat_pos($CAT, $str = ' &raquo; ')
{
	$db = new Model();
	if(!$CAT) return '';
	$arrparentids = $CAT['arrparentid'] . ',' . $CAT['id'];
	$arrparentid = explode(',', $arrparentids);
	$pos = '';
	$CATEGORY = array();
	$result = $db->query("
		select 
			id,
			prettyname,
			linkurl 
		from 
			iok_category 
		where 
			id in(".$arrparentids.") 
	");
	foreach($result as $r)
	{
		$CATEGORY[$r['id']] = $r;
	}
	foreach($arrparentid as $catid)
	{
		if(!$catid || !isset($CATEGORY[$catid])) continue;
		$pos .= $CATEGORY[$catid]['prettyname'] . $str;
	}
	return rtrim($pos, $str);
}






?>