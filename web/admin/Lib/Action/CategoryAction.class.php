<?php

class Categoryaction extends CommonAction
{	
	 function index()
	{	
		$type = $_GET['type'];
		$type == ''?$type=8:$type=$type;
		// 分页
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 8, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		$sql = "
			select 
				count(id)
			from 
				iok_loglogin";
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = "
			select
				*
			from
				iok_category
			where
				categorytype = '".$type."'order by 
				id desc
			limit $recordstart,$pagerecords";
		$catg = $this->arr($sql);
		$categorytype_dict = $this->getdictdata('iok_category','categorytype');
		foreach($catg as $key=>$val)
		{
			$catg[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
			$catg[$key]['categorytype'] = in_array($val['categorytype'], array_keys($categorytype_dict)) ? $categorytype_dict[$val['categorytype']]['prettyname']: '-';
			if ($catg[$key]['enabled'] == '1')
			{
				$catg[$key]['enabled'] = '已开启';
			}else{
				$catg[$key]['enabled'] = '已禁用';
			}
		}
		$this->assign('category', $catg);
		$this->assign('type', $type);
		$this->display();
	}
	
	function select_prettyname($categorytype)
	{
		 $sql = "
			select
				prettyname
			from
				iok_dictionary
			where
				name = '".$categorytype."' and
				tablename = 'iok_category' and 
				fieldname = 'categorytype'
			";
		$ctype = $this->rec($sql);
		return $ctype;
	}
	function select($id)
	{	
		$sql = "
			select
				*
			from
				iok_category
			where
				id = '".$id."'";
		$data = $this->rec($sql);
		return $data;
	}
	function get_category(){
		$catpid = getvar("catpid");
		$sql = "SELECT 
					id,prettyname,parentid,arrchildid,listorder
				FROM 
					iok_category 
				WHERE 
					categorytype = 8 and parentid = '".$catpid."' 
				ORDER BY 
					id ASC";	
		$category = $this->arr($sql);
		if(!$category){die;}
		$this->assign('catpid', $catpid);
		$this->assign('arrchildid', $arrchildid);
		$this->assign('result', $category);
		$this->display();
	}
	function add()
	{	
		$sql = "
			SELECT
				name,prettyname
			FROM
				iok_dictionary 
			WHERE
				tablename = 'iok_category' and fieldname = 'categorytype' and enabled != 0";
		$data = $this->arr($sql);
		$this->assign('data', $data);
		
		$sql = "SELECT 
					id,prettyname,parentid,arrchildid 
				FROM 
					iok_category 
				WHERE 
					parentid = 0 and categorytype = 8 
				ORDER BY 
					parentid ASC";	
		$category = $this->arr($sql);
		$this->assign('category', $category);
		$this->display();
	
	}
	
	function edit()
	{		
		$id = getvar("id");
		$data = $this->select($id);
		$pdata = $this->select($data['parentid']);
		$sql = "
			select
				prettyname
			from
				iok_dictionary
			where
				name = '".$data['categorytype']."' and
				tablename = 'iok_category' and 
				fieldname = 'categorytype'
			";
		$ctype = $this->rec($sql);
		$this->assign('data', $data);	
		$this->assign('pdata', $pdata);	
		$this->assign('ctype', $ctype);	
		if(getvar("way") == 'ok'){
			$d['id'] = getvar("id");
			$d['categorytype'] = getvar("categorytype");
			$d['prettyname'] =  getvar("prettyname");
			$d['level'] =  getvar("level");
			$d['listorder'] =  getvar("listorder");
			$d['enabled'] =  getvar("enabled");
			$d['seo_title'] =  getvar("seo_title");
			$d['seo_keywords'] =  getvar("seo_keywords");
			$d['seo_description'] =  getvar("seo_description");
			$d['addtime'] = time();
			$d['adduserid'] = $_SESSION['user']['id'];
			if(empty($d['prettyname']))
			{
				$this->mset('emptycontent', 'prettyname');
			}
			$id = $this->ins('iok_area', $d);
		}
		$this->display();
	
	}
	
}
?>