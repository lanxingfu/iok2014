<?php

class Propertyaction extends CommonAction
{	
	function index()
	{	
	
		// 排序
		$sortby = getvar('sortby', 'addtime');
		$sort = getvar('sort', 'desc');
		if(!in_array($sortby, array('addtime', 'state'))) $sortby = 'addtime';
		if(!in_array($sort, array('asc', 'desc'))) $sort = 'desc';
		switch($sortby)
		{
			case 'state':
				$orderby = 'pn.enabled';
				break;
			default:
				$orderby = 'pn.addtime';
		}
		$this->assign('sortby', $sortby);
		$this->assign('sort', $sort);
		// 搜索
		$item = getvar('id');
		$keyword = getvar('keyword');
		$search = '';
		$items = array('prettyname', 'propertyvalue');
		if($keyword)
		{
			if(!in_array($item, $items)) $item = 'account';
			switch($item)
			{
				case 'prettyname':
					$search = "ui.prettyname like '%" . $keyword . "%'";
					break;
				case 'propertyvalue':
					$search = "d.prettyname like '%" . $keyword . "%'";
					break;
				default:
					$search = "u.propertyvalue like '%" . $keyword . "%'";
			}
		}
		$this->assign('item', $item);
		$this->assign('items', getitems($items));
		$this->assign('keyword', $keyword);
		// 额外搜索（例如时间，类别等）
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 10, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		
		// 分页
		$sql = "
			select 
				count(pv.id) as cnt 
			from 
				iok_propertyname pn
				left join iok_propertyvalue pv on pn.id = pv.propertynameid
				join iok_category cat on pn.categoryid = cat.id
			";
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = "
			select
				pn.*,pv.*,cat.prettyname as catname
			from
				iok_propertyname pn
				left join iok_propertyvalue pv on pn.id = pv.propertynameid
				join iok_category cat on pn.categoryid = cat.id
			
			order by 
				$orderby $sort,
				pn.id  
			limit $recordstart, $pagerecords
			";
		
		$data = $this->arr($sql);
		foreach($data as $key=>$val)
		{
			$data[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
			$data[$key]['updatetime'] = date('Y-m-d H:i:s', $val['updatetime']);
		}
		$this->assign('data', $data);
		
		
		$this->display();
	}
	
	//****添加属性名方法
	function edit()
	{	
		$id = getvar('id');
		$searchitems = array('name','title');
		$data =  $this->mget();
		if( $id && !$data ) {
		   $sql = "select 
						rettyname,categoryid
					FROM 
						iok_propertyname
					WHERE 
						id = ".$id."";
			$data = $this->rec($sql);
		}  	
		$sql = "select 
		* 
		from 
			iok_category 
		where 
			categorytype=8 and
			parentid=0";//查询数据库 iok_category  顶级分类
		$catData = $this->arr($sql);
		
		$this->assign('data',$data);
		$this->assign('catData',$catData);
		$this->display('edit');
	}

	// ****此处根据所选择的分类id存入要添加的属性名
	function submit()
	{	
		$d = array();
		$d['categoryid'] = getvar("catid");
		$d['prettyname'] = getvar("prettyname");
		$d['adduserid'] = $_SESSION['user']['id'];
		$d['addtime'] = time();
		
		
		if( !$d['categoryid'] || $d['categoryid']==0 ) {
			$errcode1 = 'emptycontent';
			$this->mset($errcode1,'catid');
		}
		if( !$d['prettyname'] ){
			$errcode = 'emptycontent';
			$this->mset($errcode,'prettyname');
		}
		if( isset($_SESSION['_TIPS_']) ) {
		
			$this->mset(null,null,$d);
			$this->edit();
		} else {
			$id = $this->ins('iok_propertyname', $d);
			$this->assign('id',$id);
			$this->display('submit');
		}
		
	}
	
	// ****循环获取产品顶级分类的所有下级分类
	function datasp()
	{
		$parentid = getvar("parentid");
		$sql="select * from iok_category  where categorytype=8 and parentid=$parentid";
		$result=mysql_query($sql);
		$row=mysql_fetch_row($result);
		$provincecode=$row[2];
		$dengji=$row[1]+1;//获取下一级等级代码

		$sql="select * from iok_category  where categorytype=8 and parentid=$parentid";
		$result=mysql_query($sql);
		$num=mysql_num_rows($result);
		if($num<1) 
		{
			
		die;
		}
		$data = array();
		while ($row=mysql_fetch_array($result)){
			$data[] = $row;
		}
		$this->assign('result', $data);
		$this->assign('parentid', $parentid);
		$this->display();
	}
	
	//****添加属性值方法
	function addval()
	{	
		$id = getvar('id');
		$searchitems = array('name','title');
		$data =  $this->mget();
		if( $id && !$data ) {
		   $sql = "select 
						pv.propertynameid,
						pv.propertyvalue,
						pn.prettyname
					FROM 
						iok_propertyvalue pv
						left join iok_propertyname pn on pv.propertynameid = pn.id
					WHERE 
						pv.id = ".$id."";
			$data = $this->rec($sql);
		}  	
		$sql = "select 
					pn.*,pv.* ,cat.prettyname as catname
				from 
					iok_propertyname pn
						left join iok_propertyvalue pv on pn.id = pv.propertynameid
					join iok_category cat on pn.categoryid = cat.id
				where 
					pn.enabled = 1
				group by 
					pn.categoryid";
		$cdata = $this->arr($sql);
		$this->assign('data', $data);
		$this->assign('cdata', $cdata);
		$this->display('addval');
	}
	
	// ****根据所选择的属性名存入要添加的属性值
	function submitval()
	{
		
		$d = array();
		$d['propertynameid'] = getvar("prettynameid");
		$d['propertyvalue'] = getvar("propertyvalue");
		$d['adduserid'] = $_SESSION['user']['id'];
		$d['addtime'] = time();

		if( !$d['propertynameid'] || $d['propertynameid']==0 ) {
			$errcode1 = 'emptycontent';
			$this->mset($errcode1,'propertynameid');
		}
		if( !$d['propertyvalue'] ){
			$errcode = 'emptycontent';
			$this->mset($errcode,'propertyvalue');
		}
		if( isset($_SESSION['_TIPS_']) ) {
			
			$this->mset(null,null,$d);//dmp($d);die;
			$this->addval();
		} else {
			$id = $this->ins('iok_propertyvalue', $d);
			$this->assign('id',$id);
			$this->display('submit');
		}
		
	
	}
	
	// ****循环获取所选择的分类的所有已添加属性名
	function dataspval()
	{
		$categoryid = getvar("categoryid");
		$sql="select * from iok_propertyname  where enabled = 1 and categoryid=$categoryid";
		$result=mysql_query($sql);
		$row=mysql_fetch_row($result);
		$provincecode=$row[2];
		$dengji=$row[1]+1;

		$sql="select * from iok_propertyname  where enabled = 1 and categoryid=$categoryid";
		$result=mysql_query($sql);
		$num=mysql_num_rows($result);
		if($num<1) 
		{
			
		die;
		}
		$data = array();
		while ($row=mysql_fetch_array($result)){
			$data[] = $row;
		}
		$this->assign('result', $data);
		$this->display();
	}
}
?>