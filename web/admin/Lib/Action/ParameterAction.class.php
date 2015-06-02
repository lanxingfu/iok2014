<?php
class ParameterAction extends CommonAction{
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
					$search = "fieldname like '%" . $keyword . "%'";
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
				it_parameter
			where 
				fieldname!=''" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		// 获取数据
		$sql = "
			select 
				*
			from 
				it_parameter
			where 
				fieldname!='' " . ($search ? " and $search" : '') . " 
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
					a.prettyname as name ,b.prettyname as mname ,c.requesturl,d.*
				from 
					it_project as a,it_module as b ,it_interface as c , it_parameter as d  
				where 
					d.id='" . $id . "' and  a.id = b.projectid and c.id = d.interfaceid
				limit 1";
			$rec = $this->rec($sql);
			
			if(!$rec)
			{
				js_alert('norecord', '', 'window.history.back(-1)');
			}
			$this->assign('res', $rec);
			//dump($rec);
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
	public function paramfind(){
		$id = getvar('moduleid');
		$sql2 = "select * from it_interface where moduleid=".$id;
		$result=$this->arr($sql2);
		if($result){
			echo json_encode($result);
		}else{
			echo 0;
		}
	}
	public function detail(){
		// 查询数据
		$id = getvar('id', 0, 'integer');
		$rec = array();
		if($id)
		{
			$sql = "
				select 
					a.prettyname as name ,b.prettyname as mname ,c.requesturl,d.*
				from 
					it_project as a,it_module as b ,it_interface as c , it_parameter as d  
				where 
					d.id='" . $id . "' and  a.id = b.projectid and c.id = d.interfaceid
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
	public function submit(){
		$darr = array();
		$darr['id'] = getvar('id', 0, 'integer');
		// 获取数据
		$darr['prettyname'] = getvar('prettyname');//模块名
		$darr['projectid']= getvar('projectid','integer');
		$darr['moduleid']= getvar('sencend',0,'integer');
		$darr['interfaceid']= getvar('secend',0,'integer');
		$darr['fieldname']=getvar('fieldname');
		$darr['paramtype']=getvar('support');
		$darr['fieldtype']=getvar('meth');
		$darr['isoptional']=getvar('isoptional');
		$darr['note'] = getvar('note');
		$darr['addtime']= time();
		if(empty($darr['fieldname'])){
			$this->mset('emptyname','nameid');
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
						it_parameter
					set 
						projectid='" . $darr['projectid'] . "',
						moduleid='" . $darr['moduleid'] . "',
						interfaceid='".$darr['interfaceid']."',
						paramtype ='".$darr['paramtype']."',
						fieldname ='".$darr['fieldname']."',
						fieldtype= '".$darr['fieldtype']."',
						isoptional ='".$darr['isoptional']."',
						addtime='" . $darr['addtime'] . "',
						note='" . $darr['note'] . "',
						updatetime ='" . $darr['updatetime'] . "'				
					where 
						id='" . $darr['id'] ."'
						
					limit 1";
				$this->exec($sql);
			}else
			{
				$insdata = array(
						'projectid'=>$darr['projectid'],
						'moduleid'=>$darr['moduleid'] ,
						'interfaceid'=>$darr['interfaceid'] ,
						'paramtype'=>$darr['paramtype'] ,
						'fieldname'=>$darr['fieldname'] ,
						'fieldtype'=>$darr['fieldtype'] ,
						'isoptional'=>$darr['isoptional'] ,
						'note'=>$darr['note'],
						'addtime'=>time()
						);
				$id = $this->ins('it_parameter', $insdata);
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
}