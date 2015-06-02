<?php
/**
 * 项目管理
 * @author jia
 * 2014/1/2
 */
class ProjectAction extends CommonAction{
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
				$orderby = 'islocked';
				break;
			default:
				$orderby = 'addtime';
		}
		$this->assign('sortby', $sortby);
		$this->assign('sort', $sort);
		// 搜索
		$item = getvar('item');
		$keyword = getvar('keyword');
		$search = '';
		$items = array('name');
		if($keyword)
		{
			if(!in_array($item, $items)) $item = 'name';
			switch($item)
			{
				default:
					$search = "prettyname like '%" . $keyword . "%'";
			}
		}
		$this->assign('item', $item);
		$this->assign('items', getitems($items));
		$this->assign('keyword', $keyword);
		// 分页处理
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 10, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		// 
		$sql = "
			select 
				count(id) as cnt 
			from 
				it_project 
			where 
				prettyname!=''" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		// 获取数据
		$sql = "
			select 
				id,prettyname,baseurl,note,addtime,islocked
			from 
				it_project
			where 
				prettyname!='' " . ($search ? " and $search" : '') . " 
			order by 
				$orderby $sort
				
			limit $recordstart, $pagerecords";
		$rec = $this->arr($sql);
		// 数据处理
		foreach($rec as $key => $val)
		{
			$rec[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
		}
		$this->assign('rec', $rec);
		// 读取模板
		$this->display();
	}
	// detail 表示详细页面
	function detail()
	{
		// 查询数据
		$id = getvar('id', 0, 'integer');
		$rec = array();
		if($id)
		{
			$sql = "
				select
					id,prettyname,baseurl,note,addtime,islocked
				from 
					it_project
				where
					id='" . $id . "'
				limit 1";
			$rec = $this->rec($sql);
		}
		if(!$rec)
		{
			js_alert('norecord', '', 'window.history.back(-1)');
		}
		// 查询相关联数据
		$this->assign('rec', $rec);
		$this->display();
	}
	function edit()
	{
		// 判断权限
		$id = getvar('id', 0, 'integer');
		
		// 获取post数据, 并显示错误信息
		$rec = $this->mget();
		// 查询数据
		if($id && !$rec)
		{
			$sql = "
				select 
					id,prettyname,baseurl,note,addtime,islocked
				from 
					it_project
				where 
					id='" . $id . "'
				limit 1";
			$rec = $this->rec($sql);
			if(!$rec)
			{
				js_alert('norecord', '', 'window.history.back(-1)');
			}
		}
		$this->assign('rec', $rec);
		$this->display('edit');
	}
	function submit()
	{
		// 权限判断
		$darr = array();
		$darr['id'] = getvar('id', 0, 'integer');
		// 获取数据
		$darr['prettyname'] = getvar('prettyname');
		$darr['baseurl'] = getvar('baseurl');
		$darr['note'] = getvar('note');
		$darr['islocked'] = getvar('state');//dump($darr);DIE;
		// 表示 如果使用了mset，表示有错误提示
		
		if(empty($darr['prettyname'])){
			$this->mset('emptyaccount','trttr',$darr);
		}
		if(empty($darr['baseurl'])){
			$this->mset('emptybaseurl','url',$darr);
		}
		if(isset($_SESSION['_TIPS_']))
		{
			// 将post的数据传回去
			$this->mset(null, null, $darr);
			// 再调用edit方法，重新返回编辑或添加页面
			$this->edit();
		}else
		{
			if($darr['id'])
			{
				$sql = "
					update 
						it_project
					set 
						prettyname='" . $darr['prettyname'] . "',
						baseurl='" . $darr['baseurl'] . "',
						note='" . $darr['note'] . "',
						islocked='" . $darr['islocked'] . "'
						
					where 
						id='" . $darr['id'] ."'
						
					limit 1";
				$this->exec($sql);
			}else
			{
				$insdata = array('prettyname'=>$darr['prettyname'], 'baseurl'=>$darr['baseurl'], 'note'=>$darr['note'], 'islocked'=>$darr['islocked']);
				$id = $this->ins('it_project', $insdata);
				if(!$id)
				{
					$this->mset('submiterr', 'submit');
					$this->mget();
				}
			}
			$this->assign('id', $darr['id']);
			$this->display('submit');
		}
	}
	function lock(){
		$id=getvar("id");
		$id=trim($id,',');
		$sql="
			update
				it_project
			set	
				islocked=1
							
			where
				id in(".$id.")
			";
		$result=$this->exec($sql);
		if($result){
			echo "<script>alert('禁用成功');</script>";
			echo "<script>window.location.href='/iokadmin.php/Project/index';</script>";
		}
	
	}
	
}