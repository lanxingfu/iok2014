<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class TongClientAction extends Action
{
	// index 代表 list
	function index()
	{}
	private function valid($memberid, $appcode)
	{
		if($memberid && $appcode)
		{
			$sql = "select 
				m.account,m.appcode,m.gradeid,m.agentareaid,a.parentid,a.pparentid 
				from iok_member m inner join iok_area a on m.agentareaid=a.id
				where m.id='" . $memberid . "' and m.enabled=1 limit 1";
			$rec = $this->rec($sql);
			if($rec)
			{
				if($rec['appcode'] == $appcode)
				{
					if(!in_array($rec['gradeid'], array(1, 2, 3, 4, 5)))
					{
						$msg = '您的账号权限出现变动，请重新登录';
					}else
					{
						return array('gradeid'=>$rec['gradeid'], 'areaid'=>$rec['agentareaid']);
					}
				}else
				{
					$sql = "select terminal,ip,logintime from iok_loglogin where account='" . $rec['account'] . "' and state='success' order by id desc limit 1";
					$logrec = $this->rec($sql);
					if($logrec)
					{
						$msg = '你的账号于' . date('Y-m-d H:i:s', $logrec['logintime']) . '在' . ($rec['termainal'] ? $rec['terminal'] : '其他终端') . '上登录，您将自动退出';
					}else
					{
						$msg = '系统检测到您在其他终端登录，请重新登录';
					}
				}
			}else
			{
				$msg = '您的账号出现异常，请重新登录';
			}
		}else
		{
			$msg = '您的账号出现问题，请重新登录';
		}
		return $msg;
	}
	function login()
	{
		$account = getvar('account');
		$passwd = getvar('passwd');
		$devicetype = getvar('devicetype');
		$devicename = getvar('devicename');
		$devicetoken = getvar('devicetoken');
		$msg = '';
		$status = 0;
		$data = array();
		if(!$account || !$passwd)
		{
			$msg = '账号密码不能为空';
		}else
		{
			if(get_magic_quotes_gpc()) $passwd = stripslashes($passwd);
			$sql = "
				select 
					m.id,
					m.account,
					m.oldpasshash,
					m.passhash,
					m.saltcode,
					m.gradeid,
					m.enabled,
					mi.prettyname
				from
					iok_member m inner join iok_memberinfo mi on m.id=mi.memberid
				where
					m.account='" . $account . "' and m.deleted=0
				limit 1";
			$rec = $this->rec($sql);
			if(!$rec)
			{
				$msg = '无此用户';
			}elseif(!in_array($rec['gradeid'], array(1, 2, 3, 4, 5)))
			{
				$msg = '用户类型不正确';
			}elseif(!$rec['enabled'])
			{
				$msg = '该用户已经被禁用';
			}else
			{
				$ipaddr = get_client_ip();
				$time = time();
				if($rec['passhash'])
				{
					if(sha1($passwd . $rec['saltcode']) != $rec['passhash'])
					{
						$msg = '密码错误';
						$insdata = array('logintype'=>'tongclient', 'account'=>$account, 'ip'=>$ipaddr, 'logintime'=>$time, 'loginstate'=>'wrongpassword');
						$this->ins('iok_loglogin', $insdata);
					}else
					{
						$status = 1;
					}
				}else
				{
					if($rec['oldpasshash'])
					{
						if(md5(md5($passwd)) != $rec['oldpasshash'])
						{
							$msg = '密码错误';
							$insdata = array('logintype'=>'tongclient', 'account'=>$account, 'ip'=>$ipaddr, 'logintime'=>$time, 'loginstate'=>'wrongpassword');
							$this->ins('iok_loglogin', $insdata);
						}else
						{
							$status = 1;
							$saltcode = randcode(8);
							$sql = "
								update 
									iok_member 
								set 
									passhash='" . sha1($passwd . $saltcode) . "',
									saltcode='" . $saltcode . "'
								where
									id=" . $rec['id'] . "
								limit 1";
							$this->exec($sql);
								#TODO add change oldpassword to new password log
						}
					}else
					{
						$msg = '用户数据不完整，请联系客服';
							#TODO fatal error, add fatal error log
					}
				}
			}
			if($status == 1)
			{
				$appcode = randcode(32);
				$data['id'] = $rec['id'];
				$data['account'] = $rec['account'];
				$data['prettyname'] = $rec['prettyname'];
				$data['appcode'] = $appcode;
				$data['gradeid'] = $rec['gradeid'];
				$sql = "
					update 
						iok_member 
					set 
						appcode='" . $appcode . "',
						logincount=logincount+1,
						loginip='" . $ipaddr . "',
						logintime='" . $time . "'
					where
						id='" . $rec['id'] . "'
					limit 1";
				$this->exec($sql);
				$insdata = array('logintype'=>'tongclient', 'account'=>$account, 'ip'=>$ipaddr, 'logintime'=>$time, 'loginstate'=>'success');
				$this->ins('iok_loglogin', $insdata);
				if($devicename || $devicetoken)
				{
					$sql = "
						select 
							id 
						from 
							iok_appdevice
						where 
							appname='tong' and
							devicetoken='" . $devicetoken . "'
						limit 1";
					$deviceid = $this->res($sql);
					if($deviceid)
					{
						$sql = "
							update 
								iok_appdevice
							set
								devicename='" . $devicename . "'
							where 
								id=" . $deviceid . "
							limit 1";
						$this->exec($sql);
					}else
					{
						$insdata = array('appname'=>'tong', 'devicetoken'=>$devicetoken, 'devicetype'=>$devicetype, 'devicename'=>$devicename, 'addtime'=>$time);
						$this->ins('iok_appdevice', $insdata);
					}
				}
			}
		}
		echo artn($data, $status, $msg);
	}
	// 资金管理
	function funds()
	{
		$memberid = getvar('memberid', 0, 'integer');
		$appcode = getvar('appcode');
		$rtn = $this->valid($memberid, $appcode);
		//$rtn = array('grade'=>1, 'areaid'=>1244, 'parentid'=>119, 'pparentid'=>11);
		if(!is_array($rtn))
		{
			echo artn(array(), 0, $rtn);
		}else
		{
			$type = getvar('type', 1, 'integer');
			$page = getvar('page', 1, 'integer');
			$record = getvar('record', 20, 'integer');
			$type = ($type == 2 ? 2 : 1);
			$recordstart = ($page - 1) * $record;
			$sql = "select name,prettyname as value from iok_dictionary where tablename='iok_logfinance' and fieldname='type'";
			$dict = $this->ass($sql);
			$typedict = array_combine($dict['name'], $dict['value']);
			if($type == 2)
			{
				$sql = "
					select 
						id, 
						prettyname,
						parentid,
						pparentid 
					from 
						iok_area 
					where 
						id='" . $rtn['parentid'] . "' or id='" . $rtn['pparentid'] . "' or id='" . $rtn['areaid'] . "' or parentid='" . $rtn['areaid'] . "' or pparentid='" . $rtn['areaid'] . "' 
					order by parentid,pparentid,id";
				$area = $this->ass($sql);
				$sql = "
					select 
						f.id,
						f.type,
						f.amount,
						f.addtime,
						f.areaid,
						mi.prettyname
					from 
						iok_logfinance f inner join iok_memberinfo mi on f.memberid=mi.memberid
					where 
						f.areaid in(" . implode(',', $area['id']) . ") and
						f.areaid!='" . $rtn['parentid'] . "' and f.areaid!='" . $rtn['pparentid'] . "'
					order by f.addtime desc 
					limit $recordstart, $record";
				$arr = $this->arr($sql);
				foreach($arr as $key => $val)
				{
					$arr[$key]['type'] = $typedict[$val['type']];
					$arr[$key]['addtime'] = todatetime($val['addtime']);
					$pos = array_search($val['areaid'], $area['id']);
					$area1 = $area2 = $area3 = '';
					if($pos !== false)
					{
						if(in_array($area['pparentid'][$pos], $area['id']))
						{
							$area1 = $area['prettyname'][array_search($area['pparentid'][$pos], $area['id'])];
						}
						if(in_array($area['parentid'][$pos], $area['id']))
						{
							$area2 = $area['prettyname'][array_search($area['parentid'][$pos], $area['id'])];
						}
						$area3 = $area['prettyname'][$pos];
					}
					$arr[$key]['areaid'] = $area1 . $area2 . $area3;
				}
			}else
			{
				$sql = "select id, type, amount, addtime from iok_logfinance where memberid='" . $memberid . "' order by id desc limit $recordstart, $record";
				$arr = $this->arr($sql);
				foreach($arr as $key => $val)
				{
					$arr[$key]['type'] = $typedict[$val['type']];
					$arr[$key]['addtime'] = todatetime($val['addtime']);
				}
			}
			echo artn($arr);
		}
	}
	function fundsdetail()
	{
		$memberid = getvar('memberid', 0, 'integer');
		$appcode = getvar('appcode');
		$rtn = $this->valid($memberid, $appcode);
		if(!is_array($rtn))
		{
			echo artn(array(), 0, $rtn);
		}else
		{
			$id = getvar('id', 0, 'integer');
			$type = getvar('type', 1, 'integer');
			$type = ($type == 2 ? 2 : 1);
			$sql = "select name,prettyname as value from iok_dictionary where tablename='iok_logfinance' and fieldname='type'";
			$dict = $this->ass($sql);
			$typedict = array_combine($dict['name'], $dict['value']);
			if($type == 2)
			{
				$sql = "
					select 
						id, 
						prettyname,
						parentid,
						pparentid 
					from 
						iok_area 
					where 
						id='" . $rtn['parentid'] . "' or id='" . $rtn['pparentid'] . "' or id='" . $rtn['areaid'] . "' or parentid='" . $rtn['areaid'] . "' or pparentid='" . $rtn['areaid'] . "' 
					order by parentid,pparentid";
				$area = $this->ass($sql);
				$sql = "
					select 
						f.id,
						f.type,
						f.amount,
						f.balance,
						f.addtime,
						f.areaid,
						f.bank,
						f.reason,
						f.note,
						mi.prettyname
					from 
						iok_logfinance f inner join iok_memberinfo mi on f.memberid=mi.memberid
					where 
						f.id = '" . $id . "' and
						f.areaid in(" . implode(',', $area['id']) . ") and
						f.areaid!='" . $rtn['parentid'] . "' and f.areaid!='" . $rtn['pparentid'] . "'
					limit 1";
				$rec = $this->rec($sql);
				if($rec)
				{
					$rec['type'] = $typedict[$rec['type']];
					$rec['addtime'] = todatetime($rec['addtime']);
					$pos = array_search($rec['areaid'], $area['id']);
					$area1 = $area2 = $area3 = '';
					if($pos !== false)
					{
						if(in_array($area['pparentid'][$pos], $area['id']))
						{
							$area1 = $area['prettyname'][array_search($area['pparentid'][$pos], $area['id'])];
						}
						if(in_array($area['parentid'][$pos], $area['id']))
						{
							$area2 = $area['prettyname'][array_search($area['parentid'][$pos], $area['id'])];
						}
						$area3 = $area['prettyname'][$pos];
					}
					$rec['areaid'] = $area1 . $area2 . $area3;
				}
			}else
			{
				$sql = "select id, type, amount, balance, bank, addtime, reason, note from iok_logfinance where id='" . $id . "' and memberid='" . $memberid . "' limit 1";
				$rec = $this->rec($sql);
				if($rec)
				{
					$rec['type'] = $typedict[$rec['type']];
					$rec['addtime'] = todatetime($rec['addtime']);
				}
			}
			echo artn($rec);
		}
	}
	// 招商补贴
	function merchant()
	{}
	// 地区管理
	function region()
	{
		$memberid = getvar('memberid', 0, 'integer');
		$appcode = getvar('appcode');
		$rtn = $this->valid($memberid, $appcode);
		if(!is_array($rtn))
		{
			echo artn(array(), 0, $rtn);
		}else
		{
			$type = getvar('type', 1, 'integer');
			$page = getvar('page', 1, 'integer');
			$record = getvar('record', 20, 'integer');
			$recordstart = ($page - 1) * $record;
			$sql = "
				select 
					id, 
					prettyname,
					parentid,
					pparentid 
				from 
					iok_area 
				where 
					id='" . $rtn['parentid'] . "' or id='" . $rtn['pparentid'] . "' or id='" . $rtn['areaid'] . "' or parentid='" . $rtn['areaid'] . "' or pparentid='" . $rtn['areaid'] . "' 
				order by parentid,pparentid";
			$area = $this->ass($sql);
			switch($type)
			{
				case '2':
					$mtype = '县级业务中心';
					$sql = "
						select 
							m.agentareaid as areaid,
							mi.gender,
							mi.mobile,
							mi.telephone,
							mi.prettyname,
							mi.email,
							mi.addrareaid,
							mi.address
						from 
							iok_member m inner join iok_memberinfo mi on m.id=mi.memberid
						where 
							m.gradeid='3' and
							m.agentareaid in(" . implode(',', $area['id']) . ") and
							m.agentareaid!='" . $rtn['parentid'] . "' and m.agentareaid!='" . $rtn['pparentid'] . "'
						order by m.agentareaid desc 
						limit $recordstart, $record";
					$arr = $this->arr($sql);
					break;
				case '3':
					$mtype = '商务代表';
					$sql = "
						select
							m.agentareaid as areaid,
							mi.gender,
							mi.mobile,
							mi.telephone,
							mi.prettyname,
							mi.email,
							mi.addrareaid,
							mi.address,
							mp.imageurl as headpic
						from 
							iok_member m 
								inner join iok_memberinfo mi on m.id=mi.memberid
								left join iok_memberproof mp on m.id=mp.memberid
						where
							m.gradeid=4 and
							mp.prooftypeid=4 and
							m.agentareaid in(" . implode(',', $area['id']) . ") and
							m.agentareaid!='" . $rtn['parentid'] . "' and m.agentareaid!='" . $rtn['pparentid'] . "'
						order by m.agentareaid desc 
						limit $recordstart, $record";
					$arr = $this->arr($sql);
					break;
				case '4':
					$mtype = '商务服务站';
					$sql = "
						select
							m.agentareaid as areaid,
							mi.gender,
							mi.mobile,
							mi.telephone,
							mi.prettyname,
							mi.email,
							mi.addrareaid,
							mi.address,
							mp.imageurl as headpic
						from 
							iok_member m 
								inner join iok_memberinfo mi on m.id=mi.memberid
								left join iok_memberproof mp on m.id=mp.memberid
						where
							m.gradeid=5 and
							mp.prooftypeid=4 and
							m.agentareaid in(" . implode(',', $area['id']) . ") and
							m.agentareaid!='" . $rtn['parentid'] . "' and m.agentareaid!='" . $rtn['pparentid'] . "'
						order by m.agentareaid desc 
						limit $recordstart, $record";
					$arr = $this->arr($sql);
					break;
				default:
					$mtype = '市级管理中心';
					$sql = "
						select 
							m.agentareaid as areaid,
							mi.gender,
							mi.mobile,
							mi.telephone,
							mi.prettyname,
							mi.email,
							mi.addrareaid,
							mi.address
						from 
							iok_member m inner join iok_memberinfo mi on m.id=mi.memberid
						where 
							m.gradeid='2' and
							m.agentareaid in(" . implode(',', $area['id']) . ") and
							m.agentareaid!='" . $rtn['parentid'] . "' and m.agentareaid!='" . $rtn['pparentid'] . "'
						order by m.agentareaid desc 
						limit $recordstart, $record";
					$arr = $this->arr($sql);
			}
			foreach($arr as $key => $val)
			{
				$pos = array_search($val['areaid'], $area['id']);
				$area1 = $area2 = $area3 = '';
				if($pos !== false)
				{
					if(in_array($area['pparentid'][$pos], $area['id']))
					{
						$area1 = $area['prettyname'][array_search($area['pparentid'][$pos], $area['id'])];
					}
					if(in_array($area['parentid'][$pos], $area['id']))
					{
						$area2 = $area['prettyname'][array_search($area['parentid'][$pos], $area['id'])];
					}
					$area3 = $area['prettyname'][$pos];
				}
				$arr[$key]['areaid'] = $area1 . $area2 . $area3 . $mtype;
				$pos1 = array_search($val['addrareaid'], $area['id']);
				$area11 = $area12 = $area13 = '';
				if($pos1 !== false)
				{
					if(in_array($area['pparentid'][$pos1], $area['id']))
					{
						$area11 = $area['prettyname'][array_search($area['pparentid'][$pos1], $area['id'])];
					}
					if(in_array($area['parentid'][$pos1], $area['id']))
					{
						$area12 = $area['prettyname'][array_search($area['parentid'][$pos1], $area['id'])];
					}
					$area13 = $area['prettyname'][$pos1];
				}
				$arr[$key]['addrareaid'] = $area11 . $area12 . $area13;
				$arr[$key]['gender'] = ($val['gender'] == 'male' ? '男' : ($val['gender'] == 'female' ? '女' : '未知'));
				$arr[$key]['headpic'] = ($val['headpic'] ? $val['headpic'] : '');
			}
			echo artn($arr);
		}
	}
	// 推荐管理
	function invitation()
	{}
	// 公司
	function company()
	{
		$memberid = getvar('memberid', 0, 'integer');
		$appcode = getvar('appcode');
		$rtn = $this->valid($memberid, $appcode);
		//$rtn = array('grade'=>1, 'areaid'=>1244, 'parentid'=>119, 'pparentid'=>11);
		if(!is_array($rtn))
		{
			echo artn(array(), 0, $rtn);
		}else
		{
			$type = getvar('type', 1, 'integer');
			$page = getvar('page', 1, 'integer');
			$record = getvar('record', 20, 'integer');
			$type = ($type == 2 ? 2 : 1);
			$recordstart = ($page - 1) * $record;
			$sql = "
					select 
						id,
						parentid,
						pparentid 
					from 
						iok_area 
					where 
						id='" . $rtn['areaid'] . "' or parentid='" . $rtn['areaid'] . "' or pparentid='" . $rtn['areaid'] . "' 
					order by parentid,pparentid,id";
			$area = $this->ass($sql);
			$sql = "
					select 
						m.id,
						c.company as prettyname
					from 
						iok_member m inner join iok_membercompany c on m.id=c.memberid
					where 
						m.enabled=".($type == 1 ? 1: 0)." and
						m.areaid in(" . implode(',', $area['id']) . ")
					order by m.id desc 
					limit $recordstart, $record";
			$arr = $this->arr($sql);
			echo artn($arr);
		}
	}
	function companydetail()
	{
		$memberid = getvar('memberid', 0, 'integer');
		$appcode = getvar('appcode');
		$rtn = $this->valid($memberid, $appcode);
		if(!is_array($rtn))
		{
			echo artn(array(), 0, $rtn);
		}else
		{
			$id = getvar('id', 0, 'integer');
			$sql = "select name,prettyname as value from iok_dictionary where tablename='iok_logfinance' and fieldname='type'";
			$dict = $this->ass($sql);
			$typedict = array_combine($dict['name'], $dict['value']);
			if($type == 2)
			{
				$sql = "
					select 
						id, 
						prettyname,
						parentid,
						pparentid 
					from 
						iok_area 
					where 
						id='" . $rtn['parentid'] . "' or id='" . $rtn['pparentid'] . "' or id='" . $rtn['areaid'] . "' or parentid='" . $rtn['areaid'] . "' or pparentid='" . $rtn['areaid'] . "' 
					order by parentid,pparentid";
				$area = $this->ass($sql);
				$sql = "
					select 
						f.id,
						f.type,
						f.amount,
						f.balance,
						f.addtime,
						f.areaid,
						f.bank,
						f.reason,
						f.note,
						mi.prettyname
					from 
						iok_logfinance f inner join iok_memberinfo mi on f.memberid=mi.memberid
					where 
						f.id = '" . $id . "' and
						f.areaid in(" . implode(',', $area['id']) . ")
					limit 1";
				$rec = $this->rec($sql);
				if($rec)
				{
					$rec['type'] = $typedict[$rec['type']];
					$rec['addtime'] = todatetime($rec['addtime']);
					$pos = array_search($rec['areaid'], $area['id']);
					$area1 = $area2 = $area3 = '';
					if($pos !== false)
					{
						if(in_array($area['pparentid'][$pos], $area['id']))
						{
							$area1 = $area['prettyname'][array_search($area['pparentid'][$pos], $area['id'])];
						}
						if(in_array($area['parentid'][$pos], $area['id']))
						{
							$area2 = $area['prettyname'][array_search($area['parentid'][$pos], $area['id'])];
						}
						$area3 = $area['prettyname'][$pos];
					}
					$rec['areaid'] = $area1 . $area2 . $area3;
				}
			}else
			{
				$sql = "select id, type, amount, balance, bank, addtime, reason, note from iok_logfinance where id='" . $id . "' and memberid='" . $memberid . "' limit 1";
				$rec = $this->rec($sql);
				if($rec)
				{
					$rec['type'] = $typedict[$rec['type']];
					$rec['addtime'] = todatetime($rec['addtime']);
				}
			}
			echo artn($rec);
		}
	}
	// 供应
	function product()
	{}
	// 求购
	function buy()
	{}
	// 订单
	function order()
	{}
}
?>