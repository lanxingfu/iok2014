<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class RoleAction extends CommonAction
{
	// index 代表 list
	function index()
	{
		// 排序
		$sortby = getvar('sortby', 'addtime');
		$sort = getvar('sort', 'desc');
		if(!in_array($sortby, array('addtime', 'name', 'state'))) $sortby = 'addtime';
		if(!in_array($sort, array('asc', 'desc'))) $sort = 'desc';
		switch($sortby)
		{
			case 'name':
				$orderby = 'r.prettyname';
				break;
			case 'state':
				$orderby = 'r.enabled';
				break;
			default:
				$orderby = 'r.addtime';
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
					$search = "r.prettyname like '%" . $keyword . "%'";
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
				count(r.id) as cnt 
			from 
				iok_role r
			where 
				r.deleted=0" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		// 获取数据
		$sql = "
			select 
				r.id,
				r.prettyname,
				r.listorder,
				r.addtime,
				r.enabled,
				ui.prettyname as adduser
			from 
				iok_role r
					inner join iok_userinfo ui on r.adduserid=ui.userid
			where 
				r.deleted=0" . ($search ? " and $search" : '') . " 
			order by 
				$orderby $sort,
				r.listorder,r.id
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
					r.id,
					r.prettyname,
					r.listorder,
					r.introduce,
					r.enabled,
					r.addtime,
					r.updateuserid,
					r.updatetime,
					ui.prettyname as adduser
				from 
					iok_role r
						inner join iok_userinfo ui on r.adduserid=ui.userid
				where 
					r.id='" . $id . "' and
					r.deleted=0
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
		$role = array();
		$sql = "
			select 
				rn.nodeid,
				n.code,
				n.parentid,
				n.prettyname
			from 
				iok_rolenode rn 
					inner join iok_node n on rn.nodeid=n.id
			where 
				rn.roleid='" . $id . "' 
			order by 
				n.parentid,n.id";
		$noderec = $this->arr($sql);
		foreach($noderec as $val)
		{
			if($val['parentid'] == 0)
			{
				$role[$val['nodeid']]['prettyname'] = $val['prettyname'];
			}elseif(isset($role[$val['parentid']]))
			{
				$role[$val['parentid']]['child'][$val['nodeid']] = $val['prettyname'];
			}
		}
		$this->assign('role', $role);
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
					r.id,
					r.prettyname,
					r.listorder,
					r.introduce
				from 
					iok_role r
				where 
					r.id='" . $id . "' and
					r.id!=1 and 
					r.deleted=0
				limit 1";
			$rec = $this->rec($sql);
			if(!$rec)
			{
				js_alert('norecord', '', 'window.history.back(-1)');
			}
		}
		$sql = "
			select 
				n.id,
				n.prettyname,
				n.parentid,
				rn.roleid
			from 
				iok_node n 
					left join iok_rolenode rn on (n.id=rn.nodeid and rn.roleid = '" . $id . "')
			order by 
				n.parentid,n.id";
		$rolerec = $this->arr($sql);
		$role = array();
		foreach($rolerec as $val)
		{
			if($val['parentid'] == 0)
			{
				$role[$val['id']]['prettyname'] = $val['prettyname'];
				$role[$val['id']]['checked'] = (!empty($rec['role']) ? (in_array($val['id'], $rec['role']) ? ' checked' : '') : ($val['roleid'] ? ' checked' : ''));
			}else
			{
				$role[$val['parentid']]['child'][$val['id']]['prettyname'] = $val['prettyname'];
				$role[$val['parentid']]['child'][$val['id']]['checked'] = (!empty($rec['role']) ? (in_array($val['id'], $rec['role']) ? ' checked' : '') : ($val['roleid'] ? ' checked' : ''));
			}
		}
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
		$darr['listorder'] = getvar('listorder', 0, 'integer');
		$darr['introduce'] = getvar('introduce');
		$darr['role'] = getvar('role', array(), 'array');
		// 数据合法性及逻辑性判断
		if(!itemcheck('require', $darr['prettyname']))
		{
			$this->mset('invalidname', 'prettyname');
		}else
		{
			$sql = "select id from iok_role where prettyname='" . $darr['prettyname'] . "' and id!='" . $darr['id'] . "' limit 1";
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
						iok_role 
					set 
						prettyname='" . $darr['prettyname'] . "',
						listorder='" . $darr['listorder'] . "',
						introduce='" . $darr['introduce'] . "',
						updateuserid='" . $_SESSION['user']['id'] . "',
						updatetime='" . time() . "' 
					where 
						id='" . $darr['id'] . "' and
						id!=1 
					limit 1";
				$this->exec($sql);
				$sql = "delete from iok_rolenode where roleid='" . $darr['id'] . "'";
				$this->exec($sql);
				$rolecnt = count($darr['role']);
				if($rolecnt)
				{
					$insarr = array('roleid'=>array_pad(array(), $rolecnt, $darr['id']), 'nodeid'=>$darr['role']);
					$this->ins('iok_rolenode', $insarr);
				}
			}else
			{
				$insdata = array('prettyname'=>$darr['prettyname'], 'listorder'=>$darr['listorder'], 'introduce'=>$darr['introduce'], 'enabled'=>1, 'addtime'=>time(), 'adduserid'=>$_SESSION['user']['id']);
				$id = $this->ins('iok_role', $insdata);
				if(!$id)
				{
					$this->mset('submiterr', 'submit');
					$this->mget();
				}else
				{
					$rolecnt = count($darr['role']);
					if($rolecnt)
					{
						$insarr = array('roleid'=>array_pad(array(), $rolecnt, $id), 'nodeid'=>$darr['role']);
						$this->ins('iok_rolenode', $insarr);
					}
				}
			}
			$this->assign('id', $darr['id']);
			$this->display('submit');
		}
	}
}
?>