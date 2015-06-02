<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class AreaAction extends CommonAction
{	
	/* 地区列表  for  wb20131126 */
	public function index()
	{
		// 排序
		$sortby = getvar('sortby', 'parentid');
		$sort = getvar('sort', 'asc');
		if(!in_array($sortby, array('parentid', 'prettyname', 'grade'))) $sortby = 'parentid';
		
		if(!in_array($sort, array('asc', 'desc'))) $sort = 'asc';
		switch($sortby)
		{
			case 'prettyname':
				$orderby = 'a.prettyname';
				break;
			case 'grade':
				$orderby = 'a.grade';
				break;
			default:
				$orderby = 'a.parentid';
		}
		$this->assign('sortby', $sortby);
		$this->assign('sort', $sort);
		// 搜索
		$item = getvar('item');
		$keyword = getvar('keyword');
		$search = '';
		$items = array('prettyname', 'grade', 'parentname');
		if($keyword)
		{
			if(!in_array($item, $items)) $item = 'prettyname';
			switch($item)
			{
				case 'parentname':
					$search = "pa.prettyname like '%" . $keyword . "%'";
					break;
				case 'grade':
					$search = "a.grade like '%" . $keyword . "%'";
					break;
				default:
					$search = "a.prettyname like '%" . $keyword . "%'";
			}
		}
		$this->assign('item', $item);
		$this->assign('items', getitems($items));
		$this->assign('keyword', $keyword);
		// 额外搜索（例如时间，类别等）
		
		
		// 分页处理
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 10, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		
		// 
		$sql = "
			select 
				count(a.id) as cnt 
			from 
				iok_area a
					left join iok_area pa on a.parentid = pa.id
			where 
				a.deleted=0" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		// 获取数据
		$sql = "
			select 
				a.id,
				a.listorder,
				a.prettyname,
				a.grade,
				a.parentid,
				pa.prettyname as parentname
			from 
				iok_area a
					left join iok_area pa on a.parentid = pa.id
			where 
				a.deleted=0" . ($search ? " and $search" : '') . " 
			order by 
				$orderby $sort,
				a.id,a.listorder
			limit $recordstart, $pagerecords";
		$ass = $this->ass($sql);
		
		$sql = "select name,prettyname from iok_dictionary where tablename='iok_area' and fieldname='grade'";
		$dictass = $this->ass($sql);
		
		$ass['grade'] = str_replace($dictass['name'], $dictass['prettyname'],$ass['grade']);
		// 数据处理
		$this->assign('ass', $ass);
		$this->display();
	}
	function select_area($id){
		$sql = "select
					* 
				from 
					iok_area 
				where 
					id = '".$id."'";
		$area = $this->rec($sql);
		return $area;
	
	
	}
	
	function addarea()
	{
		// 判断权限
		$id = getvar('id', 0, 'integer');
		$rec = $this->mget();
		if($id)
		{
			$area = $this->select_area($id);
			$pname = $this->select_area($area['parentid']);
			$parentname = $pname['prettyname'];
			$this->assign('area', $area);
			$this->assign('pname', $parentname);
			$this->display('addarea');
		}
		$this->assign('data', $rec);
		$this->display('addarea');
		
	}
	function submit()
	{	
		$id = getvar("id");
		$d = array();
		$parentid = getvar('parentid');
		$d['parentid'] = $parentid;
		$d['prettyname'] = getvar('prettyname');
		$d['listorder'] = getvar('listorder');
		$d['grade'] = getvar('grade');
		$d['note'] = getvar('note');
		$d['addtime'] = time();
		$d['adduserid'] = $_SESSION['user']['id'];

		
		$sql = "select
					paygrade,parentid, pparentid
				from 
					iok_area 
				where 
					id = '".$parentid."'";
		$pid = $this->rec($sql);
		if($pid['paygrade'] == 3)
		{
			js_alert('不能在县区级以下添加地区', '', 'window.history.back(-1)');
		}
		if($parentid == 0)
		{
			$d['pparentid'] = 0;
			$d['paygrade'] = 1;
		}
		elseif($parentid != 0 && $pid['parentid'] == 0)
		{
			$d['pparentid'] = 0;
			$d['paygrade'] = 2;
		}
		elseif($parentid != 0 && $pid['parentid'] != 0)
		{
			$d['pparentid'] = $pid['parentid'];
			$d['paygrade'] = 3;
		}else{
			die;
		}
		if(!itemcheck($d['prettyname']))
		{
			$this->mset('emptycontent', 'prettyname');
		}
		if(!itemcheck($d['grade']))
		{
			$this->mset('emptycontent', 'grade');
		}
		if(isset($_SESSION['_TIPS_']))
		{
			$this->mset(null, null, $d);
			$this->addarea();
		}else{
			$id = $this->ins('iok_area', $d);
			if($id){
				echo 'ok';
			}else{
				echo 'no';
			}
		
		}
	}
	
	/* //取出上级地区
		$sql = "
			select 
				a.id,
				a.prettyname,
				a.grade,
				a.parentid,
				pa.prettyname as parentname
			from 
				iok_area a
					left join iok_area pa on a.parentid = pa.id
			where 
				a.parentid=0";
		$ass = $this->arr($sql);
		$this->assign('pcity', $ass);
		// 查询数据
		if($id && !$rec)
		{
			$sql = "
			select 
				a.id,
				a.listorder,
				a.prettyname,
				a.grade,
				a.parentid,
				pa.prettyname as parentname
			from 
				iok_area a
			where 
					a.parentid=0";
			$rec = $this->rec($sql);
			$this->assign('rec', $rec);
			
			$rec = $this->submit();
			if(!$rec)
			{
				js_alert('norecord', '', 'window.history.back(-1)');
			}
		} */
	
}
?>