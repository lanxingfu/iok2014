<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class DepartmentAction extends CommonAction
{
	// index 代表 list
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
				$orderby = 'd.enabled';
				break;
			default:
				$orderby = 'd.addtime';
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
					$search = " and d.prettyname like '%" . likefilter($keyword) . "%'";
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
				count(id) as cnt 
			from 
				iok_department d
			where 
				id!=1 and
				deleted=0" . $search;
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		// 获取数据
		$sql = "
			select 
				d.id,
				d.prettyname,
				d.listorder,
				ul.prettyname as leader,
				d.addtime,
				d.enabled,
				ui.prettyname as adduser
			from 
				iok_department d 
					inner join iok_userinfo ui on d.adduserid=ui.userid
					left join iok_userinfo ul on d.leaderid=ul.userid
			where 
				d.deleted=0" . $search . " 
			order by 
				$orderby $sort,
				d.listorder,d.id
			limit $recordstart, $pagerecords";
		$rec = $this->arr($sql);
		// 数据处理
		
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
					d.id,
					d.prettyname,
					ul.prettyname as leader,
					d.listorder,
					d.introduce,
					d.enabled,
					d.addtime,
					d.updateuserid,
					d.updatetime,
					ui.prettyname as adduser
				from 
					iok_department d 
						inner join iok_userinfo ui on d.adduserid=ui.userid
						left join iok_userinfo ul on d.leaderid=ui.userid
				where
					d.id='" . $id . "'
				limit 1";
			$rec = $this->rec($sql);
		}
		if(!$rec)
		{
			js_alert('norecord', '', 'window.history.back(-1)');
		}
		// 查询相关联数据
		if($rec['updateuserid'])
		{
			$sql = "select prettyname from iok_userinfo where userid='" . $rec['updateuserid'] . "' limit 1";
			$rec['updateuser'] = $this->res($sql);
		}
		$this->assign('rec', $rec);
		$this->display();
	}
	function edit()
	{
		// 判断权限
		$id = getvar('id', 0, 'integer');
		if($id)
		{
			if($id == 1) js_alert('noedit', '', 'window.history.back(-1)');
				//checkaccess('USER_EDIT');
		}else
		{
			//checkaccess('USER_ADD');
		}
		// 获取post数据, 并显示错误信息
		$rec = $this->mget();
		// 查询数据
		if($id && !$rec)
		{
			$sql = "
				select 
					d.id,
					d.leaderid,
					d.prettyname,
					d.listorder,
					d.introduce
				from 
					iok_department d 
				where 
					d.id='" . $id . "' and
					d.id!=1 and 
					d.deleted=0
				limit 1";
			$rec = $this->rec($sql);
			if(!$rec)
			{
				js_alert('norecord', '', 'window.history.back(-1)');
			}
		}
		if($id)
		{
			$sql = "
				select 
					u.id, 
					u.account 
				from 
					iok_user u inner join iok_usergroup ug on u.usergroupid=ug.id
				where u.enabled=1 and ug.departmentid='" . $id . "'
				order by u.id desc";
			$leader = $this->ass($sql);
			if(!$leader)
			{
				$leader['id'] = array();
				$leader['account'] = array();
			}
			array_unshift($leader['id'], '0');
			array_unshift($leader['account'], '无');
			$this->assign('leader', $leader);
		}
		$this->assign('rec', $rec);
		$this->display('edit');
	}
	function submit()
	{
		// 权限判断
		$darr = array();
		$darr['id'] = getvar('id', 0, 'integer');
		if($darr['id'])
		{
			if($darr['id'] == 1) js_alert('noedit', '', 'window.history.back(-1)');
			$darr['leaderid'] = getvar('leaderid', 0, 'integer');
				//checkaccess('USER_EDIT', true);
		}else
		{
			//checkaccess('USER_ADD', true);
		}
		// 获取数据
		$darr['prettyname'] = getvar('prettyname');
		$darr['listorder'] = getvar('listorder', 0, 'integer');
		$darr['introduce'] = getvar('introduce');
		// 数据合法性及逻辑性判断
		if(!itemcheck('require', $darr['prettyname']))
		{
			$this->mset('invalidname', 'prettyname');
		}else
		{
			$sql = "select id from iok_department where prettyname='" . $darr['prettyname'] . "' and id!='" . $darr['id'] . "' limit 1";
			if($this->res($sql))
			{
				$this->mset('existname', 'name');
			}
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
						iok_department 
					set 
						leaderid='" . $darr['leaderid'] . "',
						prettyname='" . $darr['prettyname'] . "',
						listorder='" . $darr['listorder'] . "',
						introduce='" . $darr['introduce'] . "',
						updatetime=" . time() . ",
						updateuserid=" . $_SESSION['user']['id'] . "
					where 
						id='" . $darr['id'] . "' and 
						id!=1 
					limit 1";
				$this->exec($sql);
			}else
			{
				$insdata = array('prettyname'=>$darr['prettyname'], 'listorder'=>$darr['listorder'], 'introduce'=>$darr['introduce'], 'enabled'=>1, 'addtime'=>time(), 'adduserid'=>$_SESSION['user']['id']);
				$id = $this->ins('iok_department', $insdata);
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
?>