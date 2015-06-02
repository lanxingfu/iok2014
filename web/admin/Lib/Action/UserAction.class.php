<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class UserAction extends CommonAction
{
	// index 代表 list
	function index()
	{
		// 排序
		$sortby = getvar('sortby', 'addtime');
		$sort = getvar('sort', 'desc');
		if(!in_array($sortby, array('addtime', 'account', 'logintime', 'state'))) $sortby = 'addtime';
		if(!in_array($sort, array('asc', 'desc'))) $sort = 'desc';
		switch($sortby)
		{
			case 'account':
				$orderby = 'u.account';
				break;
			case 'logintime':
				$orderby = 'u.logintime';
				break;
			case 'state':
				$orderby = 'u.enabled';
				break;
			default:
				$orderby = 'u.addtime';
		}
		$this->assign('sortby', $sortby);
		$this->assign('sort', $sort);
		// 搜索
		$item = getvar('item');
		$keyword = getvar('keyword');
		$search = '';
		$items = array('account', 'name', 'department', 'usergroup');
		if($keyword)
		{
			if(!in_array($item, $items)) $item = 'account';
			switch($item)
			{
				case 'name':
					$search = "ui.prettyname like '%" . $keyword . "%'";
					break;
				case 'department':
					$search = "d.prettyname like '%" . $keyword . "%'";
					break;
				case 'usergroup':
					$search = "ug.prettyname like '%" . $keyword . "%'";
					break;
				default:
					$search = "u.account like '%" . $keyword . "%'";
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
				count(u.id) as cnt 
			from 
				iok_user u 
					inner join iok_userinfo ui on u.id=ui.userid
					inner join iok_usergroup ug on u.usergroupid=ug.id
					inner join iok_department d on ug.departmentid=d.id
			where 
				u.deleted=0" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		// 获取数据
		$sql = "
			select 
				u.id,
				u.account,
				u.usergroupid,
				u.loginip,
				u.logintime,
				u.addtime,
				u.enabled,
				ui.prettyname,
				ug.prettyname as usergroup,
				ug.departmentid,
				d.prettyname as department
			from 
				iok_user u 
					inner join iok_userinfo ui on u.id=ui.userid
					inner join iok_usergroup ug on u.usergroupid=ug.id
					inner join iok_department d on ug.departmentid=d.id
			where 
				u.deleted=0" . ($search ? " and $search" : '') . " 
			order by 
				$orderby $sort,
				u.id 
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
					u.id,
					u.account,
					u.logincount,
					u.enabled,
					u.logintime,
					u.loginip,
					u.addtime,
					u.addip,
					u.updatetime,
					u.updateuserid,
					ui.prettyname,
					ui.email,
					ui.mobile,
					ui.qq,
					ui.position,
					ui.introduce,
					aui.prettyname as adduser,
					ug.prettyname as usergroup,
					d.prettyname as department,
					r.prettyname as rolename
				from 
					iok_user u 
						inner join iok_userinfo ui on u.id=ui.userid
						inner join iok_userinfo aui on u.adduserid=aui.userid
						inner join iok_usergroup ug on u.usergroupid=ug.id
						inner join iok_department d on ug.departmentid=d.id
						inner join iok_role r on ug.roleid=r.id
				where
					u.id='" . $id . "'
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
			$sql = "select prettyname from iok_userinfo where userid='".$rec['updateuserid']."' limit 1";
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
					u.id,
					u.account,
					u.usergroupid,
					u.loginip,
					u.logintime,
					u.addtime,
					u.enabled,
					ui.prettyname,
					ui.mobile,
					ui.qq,
					ui.email,
					ui.position,
					ui.introduce,
					ug.departmentid
				from 
					iok_user u 
						inner join iok_userinfo ui on u.id=ui.userid
						inner join iok_usergroup ug on u.usergroupid=ug.id
						inner join iok_department d on ug.departmentid=d.id
				where 
					u.id='" . $id . "' and
					u.id!=1 and 
					u.deleted=0
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
			$darr['passwd1'] = getvar('passwd1');
			$darr['passwd2'] = getvar('passwd2');
		}
		// 获取数据
		$darr['account'] = getvar('account');
		$darr['departmentid'] = getvar('departmentid',0,'integer');
		$darr['usergroupid'] = getvar('usergroupid',0,'integer');
		$darr['prettyname'] = getvar('prettyname');
		$darr['mobile'] = getvar('mobile');
		$darr['email'] = getvar('email');
		$darr['qq'] = getvar('qq');
		$darr['position'] = getvar('position');
		$darr['introduce'] = getvar('introduce');
		// 数据合法性及逻辑性判断
		if(!$darr['id'])
		{
			// 是否为空，是否为email，是否存在
			if(!itemcheck('require', $darr['account']))
			{
				$this->mset('emptyaccount', 'account');
			}elseif(!itemcheck('account', $darr['account']))
			{
				$this->mset('invalidaccount', 'account');
			}else
			{
				$sql = "select id from iok_user where account='" . $darr['account'] . "' limit 1";
				if($this->res($sql))
				{
					$this->mset('existaccount', 'account');
				}
			}
			if(!itemcheck('passwd', $darr['passwd1']))
			{
				$this->mset('invalidpasswd', 'passwd1');
			}elseif($darr['passwd1'] != $darr['passwd2'])
			{
				$this->mset('notmatch', 'passwd2');
			}
		}
		if(!$darr['departmentid'])
		{
			$this->mset('invaliddepartment','department');
		}
		if(!$darr['usergroupid'])
		{
			$this->mset('invalidusergroup','usergroup');
		}
		if($darr['mobile'] && !itemcheck('mobile', $darr['mobile']))
		{
			$this->mset('invalidmobile', 'mobile');
		}
		if($darr['email'])
		{
			if(!itemcheck('email', $darr['email']))
			{
				$this->mset('invalidemail', 'email');
			}else
			{
				$sql = "select userid from iok_userinfo where email='" . $darr['email'] . "' and userid!='" . $darr['id'] . "' limit 1";
				if($this->res($sql))
				{
					$this->mset('existemail', 'email');
				}
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
						iok_userinfo 
					set 
						prettyname='" . $darr['prettyname'] . "',
						mobile='" . $darr['mobile'] . "',
						email='" . $darr['email'] . "',
						qq='" . $darr['qq'] . "',
						position='" . $darr['position'] . "',
						introduce='" . $darr['introduce'] . "'
					where 
						userid='" . $darr['id'] . "' and 
						userid!=1 
					limit 1";
				$this->exec($sql);
				$sql = "
					update
						iok_user
					set
						usergroupid='".$darr['usergroupid']."',
						updatetime=" . time() . ",
						updateuserid=" . $_SESSION['user']['id'] . "
					where
						id='" . $darr['id'] . "'
					limit 1";
				$this->exec($sql);
			}else
			{
				$webcode = randcode(16);
				$saltcode = randcode(8);
				$insdata = array('account'=>$darr['account'], 'passhash'=>sha1(stripslashes($darr['passwd1']) . $saltcode), 'saltcode'=>$saltcode, 'webcode'=>$webcode,  'usergroupid'=>$darr['usergroupid'], 'enabled'=>1, 'addtime'=>time(), 'adduserid'=>$_SESSION['user']['id'], 'addip'=>get_client_ip());
				$id = $this->ins('iok_user', $insdata);
				if($id)
				{
					$ins = array('userid'=>$id, 'prettyname'=>$darr['prettyname'], 'email'=>$darr['email'], 'mobile'=>$darr['mobile'], 'qq'=>$darr['qq'], 'introduce'=>$darr['introduce'], 'position'=>$darr['position']);
					$this->ins('iok_userinfo', $ins);
				}else
				{
					$this->mset('submiterr', 'submit');
					$this->mget();
				}
			}
			$this->assign('id', $darr['id']);
			$this->display('submit');
		}
	}
	function passwd()
	{
		$id = getvar('id', 0, 'integer');
		if($id == 1) js_alert('noedit', '', 'window.history.back(-1)');
		$sql = "
			select 
				id 
			from 
				iok_user 
			where 
				id='" . $id . "' and 
				id!='1' 
			limit 1";
		if(!$this->res($sql))
		{
			js_alert('norecord', '', 'window.history.back(-1)');
		}
		$this->mget();
		$this->assign('id', $id);
		$this->display('passwd');
	}
	function submitpasswd()
	{
		$id = getvar('id', 0, 'integer');
		if($id == 1) js_alert('noedit', '', 'window.history.back(-1)');
		$passwd1 = getvar('passwd1');
		$passwd2 = getvar('passwd2');
		if(!itemcheck('passwd', $passwd1))
		{
			$this->mset('invalidpasswd', 'passwd1');
		}elseif($passwd1 != $passwd2)
		{
			$this->mset('notmatch', 'passwd2');
		}
		if(isset($_SESSION['_TIPS_']))
		{
			$this->passwd();
		}else
		{
			$salt = randcode(8);
			$sql = "
				update 
					iok_user 
				set 
					passhash='" . sha1($passwd1 . $salt) . "',
					saltcode='" . $salt . "' 
				where 
					id='" . $id . "' and 
					id!=1 
				limit 1";
			$this->exec($sql);
			$this->assign('id', $id);
			$this->display('submit');
		}
	}
	
	
	
	
	function map()
	{
		$id = getvar('id', 0, 'integer');
		if($id == 1) js_alert('noedit', '', 'window.history.back(-1)');
		$sql = "select u.id,u.account,up.prettyname from user u inner join userpreference up on u.id=up.userid where u.id='" . $id . "' and u.deleted=0 limit 1";
		$rec = $this->db->rec($sql);
		if(!$rec)
		{
			js_alert('norecord', '', 'window.history.back(-1)');
		}
		mget();
		$sql = "select if(ugm.userid='$id',1,0) as flag,ug.id,ug.prettyname
			from usergroup ug left join usergroupmap ugm on ugm.usergroupid=ug.id and ugm.userid='$id'
			where ug.deleted=0
			order by flag desc,ug.id";
		$ugmrec = $this->db->ass($sql);
		$ugmcnt = array_sum($ugmrec['flag']);
		if($ugmcnt)
		{
			$this->tpl->assign('ugmopt', array_combine(array_slice($ugmrec['id'], 0, $ugmcnt), array_slice($ugmrec['prettyname'], 0, $ugmcnt)));
		}
		if($ugmcnt != count($ugmrec['id']))
		{
			$this->tpl->assign('nugmopt', array_combine(array_slice($ugmrec['id'], $ugmcnt), array_slice($ugmrec['prettyname'], $ugmcnt)));
		}
		$this->tpl->assign('rec', $rec);
		$this->tpl->display('user.map.htm');
	}
	function submitmap()
	{
		$id = getvar('id', 0, 'integer');
		$members = getvar('members', array(), 'array');
		$sql = "select id from user where id='" . $id . "' and deleted=0 limit 1";
		if(!$this->res($sql))
		{
			mset('norecord', 'top');
		}elseif(!is_array($members))
		{
			mset('invalidmember', 'top');
		}
		if(isset($_SESSION['tip']))
		{
			$this->map();
		}else
		{
			$sql = "select id from usergroup where id in('" . implode("','", $members) . "') and deleted=0";
			$ids = $this->db->ass($sql);
			$sql = "delete from usergroupmap where userid='" . $id . "' and userid!=1";
			$this->db->op($sql);
			if($ids)
			{
				$ins = array('userid'=>array_pad(array(), count($ids['id']), $id), 'usergroupid'=>$ids['id']);
				$this->db->ins('usergroupmap', $ins);
			}
		}
		$this->tpl->assign('id', $id);
		$this->tpl->display('user.submitmap.htm');
	}
}
?>