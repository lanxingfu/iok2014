<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class LoginAction extends Action
{
	public function index()
	{
		$account = getvar('account');
		if(empty($account) && isset($_COOKIE['account']))
		{
			$account = cookie('account');
		}
		$this->mget();
		$this->assign('account', stripslashes($account));
		$this->display('index');
	}
	public function submit()
	{
		$account = getvar('account');
		$passwd = getvar('passwd');
		$captcha = getvar('captcha');
		$uid = 0;
		if(empty($account) || empty($passwd) || empty($captcha))
		{
			$errcode = 'allempty';
		}elseif(!isset($_SESSION['captcha']) || $captcha != $_SESSION['captcha'])
		{
			$errcode = 'errorcaptcha';
		}else
		{
			if(get_magic_quotes_gpc()) $passwd = stripslashes($passwd);
			$sql = "
				select
					u.id,
					u.account,
					u.passhash,
					u.saltcode,
					u.webcode,
					u.usergroupid,
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
					ug.departmentid,
					ug.roleid,
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
					u.account='$account' and
					ug.enabled=1 and
					d.enabled=1 and
					r.enabled=1
				limit 1";
			$rec = $this->rec($sql);
			if(empty($rec))
			{
				$this->loglogin($account, 2);
				$errcode = 'erroraccount';
			}else
			{
				if(!$rec['enabled'])
				{
					$this->loglogin($account, 2);
					$errcode = 'disabledaccount';
				}elseif($rec['passhash'] != sha1($passwd . $rec['saltcode']))
				{
					$this->loglogin($account, 3);
					$errcode = 'errorpasswd';
				}else
				{
					$uid = $rec['id'];
				}
			}
		}
		if($errcode)
		{
			$this->mset($errcode, 'top');
			$this->index();
		}else
		{
			unset($_SESSION['captcha']);
			if($rec['roleid'] == 1)
			{
				$sql = "
					select
						id,
						code,
						isloged
					from 
						iok_node 
					order by 
						parentid,
						id";
				$coderec = $this->arr($sql);
			}else
			{
				$sql = "
					select 
						n.id,
						n.code,
						n.isloged
					from 
						iok_rolenode rn
							inner join iok_role r on rn.roleid=r.id 
							inner join iok_node n on rn.nodeid=n.id
					where 
						rn.roleid='" . $rec['roleid'] . "' and 
						r.enabled=1
					order by 
						n.parentid,
						n.id";
				$coderec = $this->arr($sql);
			}
			if(!$coderec) $errcode = 'norole';
			if($errcode)
			{
				$this->loglogin($account, 4);
				$this->mset($errcode, 'top');
				$this->index();
			}else
			{
				$this->loglogin($account, 1);
				if($rec['updateuserid'])
				{
					$sql = "select prettyname from iok_userinfo where userid='" . $rec['updateuserid'] . "' limit 1";
					$rec['updateuser'] = $this->res($sql);
				}
				$rec['webcode'] = randcode(16);
				$sql = "
					update 
						iok_user 
					set 
						webcode='" . $rec['webcode'] . "',
						logintime=" . time() . ", 
						loginip='".get_client_ip()."', 
						logincount=logincount+1 
					where 
						id='$uid' 
					limit 1";
				$this->exec($sql);
				foreach($coderec as $val)
				{
					$rec['node'][$val['code']] = array();
					$rec['node'][$val['code']]['id'] = $val['id'];
					$rec['node'][$val['code']]['isloged'] = $val['isloged'];
				}
				$rec['time'] = time();
				$_SESSION['user'] = $rec;
				setcookie('account', $account, time() + 3600 * 24 * 7, "/");
				gotourl('/iokadmin.php?m=Index');
			}
		}
	}
	private function loglogin($account, $errorcode = 0)
	{
		if($account)
		{
			$insarr = array('logintype'=>2, 'account'=>$account, 'ip'=>get_client_ip(), 'logintime'=>time(), 'loginstate'=>$errorcode);
			$this->ins('iok_loglogin', $insarr);
		}
	}
}
?>