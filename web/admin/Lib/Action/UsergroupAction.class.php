<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class UsergroupAction extends CommonAction
{
	// index 代表 list
	function index()
	{
		// 排序
		$sortby = getvar('sortby', 'addtime');
		$sort = getvar('sort', 'desc');
		if(!in_array($sortby, array('addtime', 'name','department', 'role','state'))) $sortby = 'addtime';
		if(!in_array($sort, array('asc', 'desc'))) $sort = 'desc';
		switch($sortby)
		{
			case 'name':
				$orderby = 'ug.prettyname';
				break;
			case 'department':
				$orderby = 'ug.departmentid';
				break;
			case 'role':
				$orderby = 'ug.roleid';
				break;
			case 'state':
				$orderby = 'ug.enabled';
				break;
			default:
				$orderby = 'ug.addtime';
		}
		$this->assign('sortby', $sortby);
		$this->assign('sort', $sort);
		// 搜索
		$item = getvar('item');
		$keyword = getvar('keyword');
		$search = '';
		$items = array('name','department','role');
		if($keyword)
		{
			if(!in_array($item, $items)) $item = 'name';
			switch($item)
			{
				case 'department':
					$search = "d.prettyname like '%" . $keyword . "%'";
					break;
				case 'role':
					$search = "r.prettyname like '%" . $keyword . "%'";
					break;
				default:
					$search = "ug.prettyname like '%" . $keyword . "%'";
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
				count(ug.id) as cnt 
			from 
				iok_usergroup ug
					inner join iok_department d on ug.departmentid=d.id
					inner join iok_role r on ug.roleid=r.id
			where 
				d.enabled=1 and
				r.enabled=1 and
				ug.deleted=0" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		// 获取数据
		$sql = "
			select 
				ug.id,
				ug.prettyname,
				ug.listorder,
				ug.addtime,
				ug.enabled,
				d.prettyname as department,
				r.prettyname as rolename,
				ui.prettyname as adduser
			from 
				iok_usergroup ug
					inner join iok_department d on ug.departmentid=d.id
					inner join iok_role r on ug.roleid=r.id
					inner join iok_userinfo ui on ug.adduserid=ui.userid
			where 
				d.enabled=1 and
				r.enabled=1 and
				ug.deleted=0" . ($search ? " and $search" : '') . " 
			order by 
				$orderby $sort,
				ug.listorder,ug.id
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
					ug.id,
					ug.prettyname,
					ug.listorder,
					ug.introduce,
					ug.enabled,
					ug.addtime,
					ug.updateuserid,
					ug.updatetime,
					ui.prettyname as adduser,
					d.prettyname as department,
					r.prettyname as rolename
				from 
					iok_usergroup ug
						inner join iok_department d on ug.departmentid=d.id
						inner join iok_role r on ug.roleid=r.id
						inner join iok_userinfo ui on ug.adduserid=ui.userid
				where 
					ug.id='" . $id . "' and
					d.enabled=1 and
					r.enabled=1 and
					ug.deleted=0
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
					ug.id,
					ug.prettyname,
					ug.listorder,
					ug.introduce,
					ug.departmentid,
					ug.roleid
				from 
					iok_usergroup ug
				where 
					ug.id='" . $id . "' and
					ug.id!=1 and 
					ug.deleted=0
				limit 1";
			$rec = $this->rec($sql);
			if(!$rec)
			{
				js_alert('norecord', '', 'window.history.back(-1)');
			}
		}
		$sql = "select id,prettyname from iok_department where enabled=1 order by listorder,id";
		$department = $this->ass($sql);
		$this->assign('department', $department);
		$sql = "select id,prettyname from iok_role where enabled=1 order by listorder,id";
		$role = $this->ass($sql);
		$this->assign('role', $role);
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
				//checkaccess('USER_EDIT', true);
		}else
		{
			//checkaccess('USER_ADD', true);
		}
		// 获取数据
		$darr['prettyname'] = getvar('prettyname');
		$darr['departmentid'] = getvar('departmentid', 0, 'integer');
		$darr['roleid'] = getvar('roleid', 0, 'integer');
		$darr['listorder'] = getvar('listorder', 0, 'integer');
		$darr['introduce'] = getvar('introduce');
		// 数据合法性及逻辑性判断
		if(!itemcheck('require', $darr['prettyname']))
		{
			$this->mset('invalidname', 'prettyname');
		}else
		{
			$sql = "select id from iok_usergroup where prettyname='" . $darr['prettyname'] . "' and id!='" . $darr['id'] . "' limit 1";
			if($this->res($sql))
			{
				$this->mset('existname', 'name');
			}
		}
		if(!$darr['departmentid'])
		{
			$this->mset('invaliddepartment','department');
		}
		if(!$darr['roleid'])
		{
			$this->mset('invalidrole','role');
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
						iok_usergroup
					set 
						prettyname='" . $darr['prettyname'] . "',
						listorder='" . $darr['listorder'] . "',
						introduce='" . $darr['introduce'] . "',
						departmentid='" . $darr['departmentid'] . "',
						roleid='" . $darr['roleid'] . "',
						updatetime=" . time() . ",
						updateuserid=" . $_SESSION['user']['id'] . "
					where 
						id='" . $darr['id'] . "' and 
						id!=1 
					limit 1";
				$this->exec($sql);
			}else
			{
				$insdata = array('prettyname'=>$darr['prettyname'], 'listorder'=>$darr['listorder'], 'departmentid'=>$darr['departmentid'], 'roleid'=>$darr['roleid'], 'introduce'=>$darr['introduce'], 'enabled'=>1, 'addtime'=>time(), 'adduserid'=>$_SESSION['user']['id']);
				$id = $this->ins('iok_usergroup', $insdata);
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