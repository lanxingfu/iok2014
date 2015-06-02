<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class ProfileAction extends CommonAction
{
	function index()
	{
		$this->display();
	}
	function edit()
	{
		$rec = $this->mget();
		if(!$rec) $rec = $_SESSION['user'];
		$this->assign('rec', $rec);
		$this->display('edit');
	}
	function submit()
	{
		$darr = array();
		$darr['prettyname'] = getvar('prettyname');
		$darr['mobile'] = getvar('mobile');
		$darr['email'] = getvar('email');
		$darr['qq'] = getvar('qq');
		$darr['position'] = getvar('position');
		$darr['introduce'] = getvar('introduce');
		$darr['account'] = $_SESSION['user']['account'];
		if(!itemcheck('require', $darr['prettyname']))
		{
			$this->mset('requirename','prettyname');
		}
		if($darr['email'] && !itemcheck('email', $darr['email']))
		{
			$this->mset('invalidemail', 'email');
		}else
		{
			$sql = "select userid from iok_userinfo where email='" . $darr['email'] . "' and userid!='" . $_SESSION['user']['id'] . "' limit 1";
			if($this->res($sql))
			{
				$this->mset('existemail', 'email');
			}
		}
		if($darr['mobile'] && !itemcheck('mobile', $darr['mobile']))
		{
			$this->mset('invalidmobile', 'mobile');
		}
		if($darr['qq'] && !itemcheck('qq', $darr['qq']))
		{
			$this->mset('invalidqq', 'qq');
		}
		if(isset($_SESSION['_TIPS_']))
		{
			$this->mset(null, null, $darr);
			$this->edit();
		}else
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
					userid='" . $_SESSION['user']['id'] . "' 
				limit 1";
			$this->exec($sql);
			$sql = "
				update
					iok_user
				set
					updatetime=".time().",
					updateuserid=id
				where
					id='".$_SESSION['user']['id']."'
				limit 1";
			$this->exec($sql);
			$_SESSION['user']['prettyname'] = $darr['prettyname'];
			$_SESSION['user']['mobile'] = $darr['mobile'];
			$_SESSION['user']['email'] = $darr['email'];
			$_SESSION['user']['qq'] = $darr['qq'];
			$_SESSION['user']['introduce'] = $darr['introduce'];
			$_SESSION['user']['position'] = $darr['position'];
			$_SESSION['user']['updatetime'] = time();
			$_SESSION['user']['updateuserid'] = $_SESSION['user']['id'];
			$_SESSION['user']['updateuser'] = $_SESSION['user']['prettyname'];
			$this->display('submit');
		}
	}
	function passwd()
	{
		$this->mget();
		$this->display('passwd');
	}
	function submitpasswd()
	{
		$passwd = getvar('passwd');
		$passwd1 = getvar('passwd1');
		$passwd2 = getvar('passwd2');
		if(empty($passwd))
		{
			$this->mset('emptyoldpasswd', 'passwd');
		}else
		{
			$sql = "select passhash,saltcode from iok_user where id='" . $_SESSION['user']['id'] . "' limit 1";
			$rec = $this->rec($sql);
			if(sha1($passwd . $rec['saltcode']) != $rec['passhash'])
			{
				$this->mset('erroroldpasswd', 'passwd');
			}
		}
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
			$sql = "
				update 
					iok_user 
				set 
					passhash='" . sha1($passwd1 . $rec['saltcode']) . "',
					updatetime=".time().",
					updateuserid=id
				where 
					id='" . $_SESSION['user']['id'] . "' 
				limit 1";
			$this->exec($sql);
			$this->display('submitpasswd');
		}
	}
}
?>