<?php

class InterfaceAction extends CommonAction{
	public function index(){
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
					$search = "requesturl like '%" . $keyword . "%'";
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
				it_interface
			where 
				requesturl!=''" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		// 获取数据
		$sql = "
			select 
				*
			from 
				it_interface
			where 
				requesturl!='' " . ($search ? " and $search" : '') . " 
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
		$this->display();
	}
	public function edit(){
		
		//项目查询;
		$sql ="select * from it_project where islocked = 0";
		$rec=$this->arr($sql);
		$this->assign('rec',$rec);
		$id = getvar('id',0,'integer');
		// 获取post数据, 并显示错误信息
		$rec = $this->mget();
		// 查询数据
		if($id && !$rec)
		{
			$sql = "
				select 
					a.*,b.prettyname as name,c.prettyname
				from 
					it_interface as a,it_module as b,it_project as c 
				where 
					a.id = '$id' and b.id = a.moduleid and a.projectid = c.id
				limit 1";
			$rec = $this->rec($sql);
			//dump($rec);
			if(!$rec)
			{
				js_alert('norecord', '', 'window.history.back(-1)');
			}
			$this->assign('res', $rec);
			
		}
		$this->display('edit');
	}
	public function find(){
		//模块查询
		$id = getvar('projectid');
		$sql2 = "select * from it_module where projectid=".$id;
		$result=$this->arr($sql2);
		if($result){
			echo json_encode($result);
		}else{
			echo 0;
		}
	}
	public function submit(){
		$darr = array();
		$darr['id'] = getvar('id', 0, 'integer');
		// 获取数据
		$darr['projectid'] = getvar('projectid');
		$darr['moduleid'] = getvar('sencend');
		$darr['prettyname'] = getvar('prettyname');//接口地址
		$data = array('xml','json');
		$support = getvar('support');
		$darr['returnformat']=$data[$support];
		$method = getvar('meth');
		$map = array('all','get','post');
		$darr['requestmethod']= $map[$method];
		$darr['intro']=getvar('explain');
		$darr['sample']=getvar('sample');
		$darr['note'] = getvar('note');
		$darr['addtime']= time();
		$darr['needlogin'] = getvar('islogin');
		if(empty($darr['projectid'])){
			//$message='不能为空';
			$this->mset('emptyproject','tipproject',$darr);
		}
		if(empty($darr['moduleid'])){
			//$message='不能为空';
			$this->mset('emptymodule','tipmodel',$darr);
		}
		if(empty($darr['prettyname'])){
			//$message='不能为空';
			$this->mset('emptyname','tipname',$darr);
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
						it_interface
					set 
						moduleid ='".$darr['moduleid']."',
						projectid = '".$darr['projectid']."',
						requesturl ='".$darr['prettyname']."',
						returnformat = '".$darr['returnformat']."',
						requestmethod ='".$darr['requestmethod']."',
						intro = '".$darr['intro']."',
						sample = '".$darr['sample']."',
						needlogin = '".$darr['needlogin']."',
						addtime = '" . $darr['addtime'] . "',
						note = '" . $darr['note'] . "',
						updatetime = '".time()."'				
					where 
						id='" . $darr['id'] ."'
						
					limit 1";
				$this->exec($sql);
			}else
			{
				$insdata = array(
						'moduleid'=>$darr['moduleid'],
						'projectid'=>$darr['projectid'] ,
						'requesturl'=>$darr['prettyname'],
						'returnformat'=>$darr['returnformat'],
						'requestmethod'=>$darr['requestmethod'],
						'intro'=>$darr['intro'],
						'sample'=>$darr['sample'],
						'needlogin'=>$darr['needlogin'],
						'note'=>$darr['note'], 
						'addtime'=>time());
				$id = $this->ins('it_interface', $insdata);
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
					a.*,b.prettyname as name,c.prettyname
				from 
					it_interface as a,it_module as b,it_project as c 
				where 
					a.id = '$id' and b.id = a.moduleid and a.projectid = c.id
				limit 1";
			$rec = $this->rec($sql);
		}
		if(!$rec)
		{
			js_alert('norecord', '', 'window.history.back(-1)');
		}
		// 查询相关联数据
		//dump($rec);
		$this->assign('rec', $rec);
		$this->display();
	}
	
}