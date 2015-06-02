<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class CommonAction extends Action
{
	public function _initialize()
	{
		$this->checklogin();
		$this->varfilter();
	}
	protected function checklogin()
	{
		if(isset($_SESSION['user']['id']) && $_SESSION['user']['id'] > 0)
		{
			if(($_SESSION['user']['time'] + C('ONLINETIME')) > time())
			{
				$webcode = $this->res('SELECT webcode FROM iok_user WHERE id=' . $_SESSION['user']['id'] . ' and enabled=1 LIMIT 1;');
				if($webcode && isset($_SESSION['user']['webcode']) && $_SESSION['user']['webcode'] == $webcode)
				{
					$_SESSION['user']['time'] = time();
					return true;
				}
			}
		}
		gotourl('/iokadmin.php?m=Misc&a=logout');
	}
	protected function varfilter()
	{
		if(isset($_POST['GLOBALS']) || isset($_FILES['GLOBALS']) || isset($_GET['GLOBALS']) || isset($_COOKIE['GLOBALS']))
		{
			die('illegal invade detected');
		}
		if(isset($_SESSION) && !is_array($_SESSION))
		{
			die('illegal invade detected');
		}
		if(!get_magic_quotes_gpc())
		{
			if(is_array($_GET))
			{
				$_GET = addslashes_deep($_GET);
			}
			if(is_array($_POST))
			{
				$_POST = addslashes_deep($_POST);
			}
			if(is_array($_COOKIE))
			{
				$_COOKIE = addslashes_deep($_COOKIE);
			}
		}
	}
	protected function checkaccess($code = null, $urlback = false)
	{
		if(empty($code)) $code = MODULE_NAME . "." . ACTION_NAME;
		$hasprv = false;
		if(isset($_SESSION['user']['node'][$code]))
		{
			$hasprv = true;
		}
		if(!$hasprv && $urlback)
		{
			js_alert('nopermission', '', 'window.history.back(-1)');
		}
		return $hasprv;
	}
	/*
	 * @description
	 * 		记录审核日志
	 * @parameter
	 * 		$type: 审核日志类型 (1,2,3,4,5,6,7,8,9)
	 * 		$itemid: 审核对应的项目id
	 * 		$stafftype: 审核人员类型 (member, user)
	 * 		$staffid: 审核人员id
	 * 		$result: 操作结果，通过和拒绝(0,1)
	 * 		$currentstateid: 当前操作的状态
	 * 		$finalstateid: 此次操作之后的最终状态，一般为currentstateid，如果为拒绝操作一般为待审核
	 * 		$laststateid: 上一个操作状态
	 * 		$note: 备注，可为空
	 */
	protected function logaudit($type, $itemid, $stafftype = 'user', $staffid, $result, $currentstateid, $finalstateid, $laststateid = 1, $note = '')
	{
		if(!$type) return '类型不能为空';
		if(!$itemid) return '操作id不能为空';
		if(!$stafftype) $stafftype = 'user';
		if(!$staffid) $statffid = 1;
		if(!$result) $return = 1;
		if(!$currentstateid) return '当前状态不能为空';
		if(!$laststateid) $laststateid = 1;
		if(!$finalstateid) $finalstateid = $laststateid;
		$insarr = array('audittype'=>$type, 'itemid'=>$itemid, 'auditstafftype'=>$stafftype, 'auditstaffid'=>$staffid, 'result'=>$result, 'laststateid'=>$laststateid, 'currentstateid'=>$currentstateid, 'finalstateid'=>$finalstateid, 'note'=>$note, 'ip'=>get_client_ip(), 'addtime'=>time());
		$this->ins('iok_logaudit', $insarr);
	}
	/* 
	 * @description
	 * 		记录操作日志
	 */
	protected function logaction()
	{
		$code = MODULE_NAME . "." . ACTION_NAME;
		if(isset($_SESSION['user']['node'][$code]))
		{
			if($_SESSION['user']['node']['isloged'])
			{
				$nodeid = $_SESSION['user']['node'][$code]['id'];
				$insarr = array('nodeid'=>$nodeid, 'operaterid'=>$_SESSION['user']['id'], 'ip'=>get_client_ip(), 'addtime'=>time());
				$this->ins('iok_logaction', $insarr);
			}
		}
	}
	function getdictdata($tablename, $fieldname)
	{
		$sql="
			select
				name,
				prettyname
			from
				iok_dictionary
			where
				tablename = '".$tablename."' and
				fieldname = '".$fieldname."' and 
				enabled=1";
		$data = $this->idx($sql,'name',true);
		return $data;
	}
	 
	function get_areaname($id)//传入一个地区ID查出其prettyname
	{
		$sql = "select prettyname from iok_area where id = '".$id."'";
		$data = $this->rec($sql);
		return $data;
	
	}
	function get_areanames($id,$is_ajax=false){	//传入一个地区ID查出所有上级prettyname并与自身拼接成字符串
		$mm=new Model();
		$sql="select prettyname,parentid,pparentid from iok_area where id=".$id;//自身ID
		$res=$mm->query($sql);
		if($res[0]['parentid']){
			$sql="select prettyname from iok_area where id=".$res[0]['parentid'];//父ID
			$pres[0]=$mm->query($sql);
		}
		if($res[0]['pparentid']){
			$sql="select prettyname from iok_area where id=".$res[0]['pparentid'];//父父ID
			$pres[1]=$mm->query($sql);
		}
		if($is_ajax){
			echo $pres[1][0]['prettyname']." ".$pres[0][0]['prettyname']." ".$pres[0][1]['prettyname']." ".$res[0]['prettyname'];
		}else{
			return $pres[1][0]['prettyname']." ".$pres[0][0]['prettyname']." ".$pres[0][1]['prettyname']." ".$res[0]['prettyname'];	
		}
	}
	function get_username($id)
	{
		$sql = "select account from iok_member where id = '".$id."'";
		$data = $this->rec($sql);
		return $data;
	
	}
}
?>