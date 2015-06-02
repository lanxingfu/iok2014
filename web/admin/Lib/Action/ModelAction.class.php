<?php
/**
 * 模块管理
 * @author jia
 * 2014/1/2
 */

class ModelAction extends CommonAction{
	public function  index(){
		// 排序
		$sortby = getvar('sortby', 'addtime');
		$sort = getvar('sort', 'desc');
		if(!in_array($sortby, array('addtime', 'state'))) $sortby = 'addtime';
		if(!in_array($sort, array('asc', 'desc'))) $sort = 'desc';
		switch($sortby)
		{
			case 'state':
				$orderby = 'id';
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
				it_module 
			where 
				prettyname!=''" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		// 获取数据
		$sql = "
			select 
				id,projectid,prettyname,note,addtime,updatetime,deletetime
			from 
				it_module
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
			$rec[$key]['updatetime'] = date('Y-m-d H:i:s', $val['updatetime']);
			$rec[$key]['deletetime'] = date('Y-m-d H:i:s', $val['deletetime']);
		}
		$this->assign('rec', $rec);
		// 读取模板
		$this->display();
	}
	public function edit(){
		$id = getvar('id',0,'integer');
		// 获取post数据, 并显示错误信息
		$rec = $this->mget();
		// 查询数据
		if($id && !$rec)
		{
			$sql = "
				select 
					b.prettyname as name,a.prettyname,a.note,a.addtime,a.id
				from 
					it_module as a, it_project as  b 
				where 
					a.id='" . $id . "' and  a.projectid = b.id 
				limit 1";
			$rec = $this->rec($sql);
			//dump($rec);
			/*if(!$rec)
			{
				js_alert('norecord', '', 'window.history.back(-1)');
			}*/
			$this->assign('rec', $rec);
			//dump($rec);
		}else{
		
			$sql = "
				select 
					*
				from 
					it_project 
				where  
					islocked = 0
				";
			$rec = $this->arr($sql);
			//dump($rec);
			$this->assign('res', $rec);
		}
		$this->display('edit');
	}
	public function submit(){
		//dump($_POST);exit;
		$darr = array();
		$darr['id'] = getvar('id', 0, 'integer');
		// 获取数据
		$darr['prettyname'] = getvar('prettyname');//模块名
		$darr['projectid']= getvar('modelid',0,'integer');
		$darr['note'] = getvar('note');
		$darr['addtime']= time();
		if(empty($darr['prettyname'])){
			$emptymodel ='不能为空';
			$this->mset($emptymodel,'nameid',$darr);
		}
		
		// 表示 如果使用了mset，表示有错误提示
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
						it_module
					set 
						prettyname='" . $darr['prettyname'] . "',
						addtime='" . $darr['addtime'] . "',
						note='" . $darr['note'] . "',
						updatetime ='" . $darr['updatetime'] . "'				
					where 
						id='" . $darr['id'] ."'
						
					limit 1";
				$this->exec($sql);
			}else
			{
				$insdata = array('prettyname'=>$darr['prettyname'],'projectid'=>$darr['projectid'] ,'note'=>$darr['note'], 'addtime'=>time());
				$id = $this->ins('it_module', $insdata);
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
	function detail()
	{
		// 查询数据
		$id = getvar('id', 0, 'integer');
		$rec = array();
		if($id)
		{
			$sql = "
				select
					a.prettyname as name, a.id,b.prettyname,a.note,a.addtime
				from 
					it_module as a, it_project as b
				where
					a.id='" . $id . "' and a.projectid = b.id
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
}