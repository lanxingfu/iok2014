<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action
{
	private function checkauth($redirect = true)
	{
		if(!isset($_SESSION['authId']) or !$_SESSION['authId'])
		{
			if($redirect)
			{
				$url = '/Admins/index.php/Public/' . ($_SESSION['groupid'] == 1 ? 'channel' : 'login');
				js_alert('请登录', $url);
			}else
			{
				echo '请登录';
				die();
			}
		}
	}
	public function sendlog()
	{
		$this->checkauth();
		$memberid = $_SESSION['authId'];
		if(!isset($_SESSION['weibo']['accesstoken']))
		{
			$sql = "select memberid, uid, accesstoken, sendinterval, updatetime from iok_sinauser where memberid='" . $memberid . "' limit 1";
			$rec = $this->rec($sql);
			if(!$rec)
			{
				js_alert('请先关联并授权您的新浪微博账号', '/weibo/index.php?a=auth');
			}
			$_SESSION['weibo'] = $rec;
		}
		require_once (THINK_PATH . '../weibo/Lib/ORG/Util/saetv2.ex.class.php');
		$c = new SaeTClientV2(C("SINA_WB_AKEY"), C("SINA_WB_SKEY"), $_SESSION['weibo']['accesstoken']);
		$userdata = $c->show_user_by_id($_SESSION['weibo']['uid']);
		if(isset($userdata['error']))
		{
			js_alert('您的新浪微博异常，错误：' . $userdata['error'], '', '/weibo/index.php?a=auth');
		}
		// 15 分钟数据入库
		$curtime = time();
		if($_SESSION['weibo']['updatetime'] <= $curtime - 15 * 60)
		{
			$sql = "
				update 
					iok_sinauser 
				set 
					headpic='" . $userdata['avatar_large'] . "',
					nickname='" . $userdata['name'] . "',
					followers='" . $userdata['followers_count'] . "',
					friends='" . $userdata['friends_count'] . "',
					statuses='" . $userdata['statuses_count'] . "',
					updatetime=" . $curtime . " 
				where
					uid=" . $_SESSION['weibo']['uid'] . " and
					memberid=" . $memberid . "
				limit 1";
			$this->exec($sql);
			$_SESSION['weibo']['updatetime'] = $curtime;
		}
		$_SESSION['weibo']['nickname'] = $userdata['name'];
		$_SESSION['weibo']['headpic'] = $userdata['avatar_large'];
		$_SESSION['weibo']['followers'] = $userdata['followers_count'];
		$_SESSION['weibo']['friends'] = $userdata['friends_count'];
		$_SESSION['weibo']['statuses'] = $userdata['statuses_count'];
		
		$type = getvar('type', 1, 'integer');
		if(!in_array($type, array(1, 2, 3)))
		{
			$type = 1;
		}
		$this->assign('type', $type);
		// 分页处理
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 5, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		// 
		$sql = "
				select 
					count(id) as cnt 
				from 
					iok_wbqueue
				where 
					memberid='" . $memberid . "' and status='" . $type . "'";
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		// 获取数据
		$sql = "
			select 
				id,
				message,
				imageurl,
				sendtime,
				queuetime
			from 
				iok_wbqueue
			where 
				memberid='" . $memberid . "' and status='" . $type . "'
			order by queuetime " . ($type != 1 ? ' desc' : '') . "
			limit $recordstart, $pagerecords";
		$rec = $this->arr($sql);
		foreach($rec as $key => $val)
		{
			$rec[$key]['sendtime'] = todatetime((($type == 1 || !$val['sendtime']) ? $val['queuetime'] : $val['sendtime']));
		}
		$this->assign('rec', $rec);
		$this->display();
	}
	public function index()
	{
		$this->checkauth();
		$memberid = $_SESSION['authId'];
		if(!isset($_SESSION['weibo']['accesstoken']))
		{
			$sql = "select memberid, uid, accesstoken, sendinterval, updatetime from iok_sinauser where memberid='" . $memberid . "' limit 1";
			$rec = $this->rec($sql);
			if(!$rec)
			{
				js_alert('请先关联并授权您的新浪微博账号', '/weibo/index.php?a=auth');
			}
			$_SESSION['weibo'] = $rec;
		}
		require_once (THINK_PATH . '../weibo/Lib/ORG/Util/saetv2.ex.class.php');
		$c = new SaeTClientV2(C("SINA_WB_AKEY"), C("SINA_WB_SKEY"), $_SESSION['weibo']['accesstoken']);
		$userdata = $c->show_user_by_id($_SESSION['weibo']['uid']);
		if(isset($userdata['error']))
		{
			js_alert('您的新浪微博异常，错误：' . $userdata['error'], '', '/weibo/index.php?a=auth');
		}
		// 15 分钟数据入库
		$curtime = time();
		if($_SESSION['weibo']['updatetime'] <= $curtime - 15 * 60)
		{
			$sql = "
				update 
					iok_sinauser 
				set 
					headpic='" . $userdata['avatar_large'] . "',
					nickname='" . $userdata['name'] . "',
					followers='" . $userdata['followers_count'] . "',
					friends='" . $userdata['friends_count'] . "',
					statuses='" . $userdata['statuses_count'] . "',
					updatetime=" . $curtime . " 
				where
					uid=" . $_SESSION['weibo']['uid'] . " and
					memberid=" . $memberid . "
				limit 1";
			$this->exec($sql);
			$_SESSION['weibo']['updatetime'] = $curtime;
		}
		$_SESSION['weibo']['nickname'] = $userdata['name'];
		$_SESSION['weibo']['headpic'] = $userdata['avatar_large'];
		$_SESSION['weibo']['followers'] = $userdata['followers_count'];
		$_SESSION['weibo']['friends'] = $userdata['friends_count'];
		$_SESSION['weibo']['statuses'] = $userdata['statuses_count'];
		// get weibo category
		$categoryid = getvar('categoryid', 0, 'integer');
		$sql = "select id, typename from iok_wbcategory where id not in(1,2) order by id";
		$category = $this->arr($sql);
		$this->assign('category', $category);
		$this->assign('categoryid', $categoryid);
		$search = '';
		if($categoryid)
		{
			$search = " and categoryid='" . $categoryid . "'";
		}
		// get last date time
		$sql = "select queuetime from iok_wbqueue where queuetime>=".($curtime - 300)." and memberid='" . $memberid . "' order by queuetime desc limit 1";
		$lastqueue = $this->res($sql);
		
		if(!$lastqueue)
		{
			$lastqueue = $curtime + 300;
		}else
		{
			$lastqueue = $lastqueue + $_SESSION['weibo']['sendinterval'] * 60;
		}
		$startdate = date('Y-m-d', $lastqueue);
		$starthour = date('H', $lastqueue);
		$startminute = date('i', $lastqueue);
		$this->assign('startdate', $startdate);
		$this->assign('starthour', $starthour);
		$this->assign('startminute', $startminute);
		$minute = array();
		$hour = array();
		for($i = 0; $i < 59; $i++)
		{
			if($i < 24)
			{
				array_push($hour, str_pad($i, 2, '0', STR_PAD_LEFT));
			}
			array_push($minute, str_pad($i, 2, '0', STR_PAD_LEFT));
		}
		$this->assign('minute', $minute);
		$this->assign('hour', $hour);
		// 分页处理
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 5, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		// 
		$sql = "
				select 
					count(id) as cnt 
				from 
					iok_weibodata
				where 
					categoryid not in(1,2) " . ($search ? $search : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		// 获取数据
		$sql = "
			select 
				id,
				message,
				imageurl
			from 
				iok_weibodata
			where 
				categoryid not in(1,2) " . ($search ? $search : '') . " 
			order by id desc
			limit $recordstart, $pagerecords";
		$rec = $this->arr($sql);
		$ids = array();
		foreach($rec as $val)
		{
			array_push($ids, $val['id']);
		}
		// 判断微博状态
		$sql = "select weiboid, status, queuetime, sendtime from iok_wbqueue where memberid=" . $memberid . " and weiboid in (" . implode(',', $ids) . ") order by status";
		$que = $this->ass($sql);
		foreach($rec as $key => $val)
		{
			if(in_array($val['id'], $que['weiboid']))
			{
				$pos = array_search($val['id'], $que['weiboid']);
				$rec[$key]['status'] = $que['status'][$pos];
				if($rec[$key]['status'] == 1)
				{
					$rec[$key]['msg'] = '【待发送】' . date('Y年m月d日H时i分', $que['queuetime'][$pos]);
				}elseif($rec[$key]['status'] == 2)
				{
					$rec[$key]['msg'] = '【已发送】' . date('Y年m月d日H时i分', $que['sendtime'][$pos]);
				}else
				{
					$rec[$key]['msg'] = '【发送失败】' . date('Y年m月d日H时i分', $que['sendtime'][$pos]);
				}
			}else
			{
				$rec[$key]['status'] = 0;
			}
		}
		$this->assign('rec', $rec);
		$this->display();
	}
	// ajax set send interval
	public function saveintime()
	{
		$this->checkauth(false);
		$intime = getvar('intime', 60, 'integer');
		if($intime < 10)
		{
			echo '间隔时间不能小于10分钟';
		}else
		{
			$sql = "update iok_sinauser set sendinterval='" . $intime . "' where memberid='" . $_SESSION['authId'] . "' limit 1";
			$this->exec($sql);
			$_SESSION['weibo']['sendinterval'] = $intime;
			echo '保存成功';
		}
	}
	public function sendqueue()
	{
		$scode = getvar('scode');
		if($scode == md5(md5('iokokokcom') . 'beijing2012'))
		{
			$curtime = time();
			$sql = "
				select q.id, q.message, q.imageurl, u.accesstoken 
				from iok_wbqueue q inner join iok_sinauser u on q.memberid=u.memberid
				where q.status=1 and q.queuetime<=" . ($curtime + 120) . " order by q.queuetime limit 50";
			$arr = $this->arr($sql);
			if($arr)
			{
				foreach($arr as $val)
				{
					$rtn = $this->sendweibo($val['message'], $val['imageurl'], $val['accesstoken']);
					if($rtn['status'])
					{
						$sql = "update iok_wbqueue set wid='" . $rtn['msg'] . "', status=2, sendtime=" . time() . " where id=" . $val['id'] . " limit 1";
						$this->exec($sql);
						echo "[" . date('Y-m-d H:i:s', $curtime) . "] send weibo " . $val['id'] . " ok.\n";
					}else
					{
						$sql = "update iok_wbqueue set status=3, sendtime=" . time() . " where id=" . $val['id'] . " limit 1";
						$this->exec($sql);
						echo "[" . date('Y-m-d H:i:s', $curtime) . "] send weibo " . $val['id'] . " fail. error:" . $rtn['msg']." \n";
					}
					sleep(2);
				}
			}else
			{
				echo "[" . date('Y-m-d H:i:s', $curtime) . "] no queues.\n";
			}
		}
	}
	public function delaysend()
	{
		$this->checkauth(false);
		$memberid = $_SESSION['authId'];
		$id = getvar('id', 0, 'integer');
		$startdate = getvar('date');
		$starthour = getvar('hour');
		$startminute = getvar('minute');
		$intime = getvar('intime', '60', 'integer');
		$queuetime = strtotime($startdate . " " . $starthour . ":" . $startminute . ":00");
		if(!$queuetime)
		{
			echo json_encode(array('status'=>0, 'msg'=>'您设定的发送时间不正确'));
		}elseif($queuetime < time() + 30)
		{
			echo json_encode(array('status'=>0, 'msg'=>'您设定的发送时间已经过期'));
		}else
		{
			if($intime < 10)
			{
				echo json_encode(array('status'=>0, 'msg'=>'间隔时间不能小于10分钟'));
			}else
			{
				$sql = "select id, message, imageurl from iok_weibodata where id='" . $id . "' limit 1";
				$weibo = $this->rec($sql);
				if($weibo)
				{
					$sql = "select id from iok_wbqueue where weiboid=" . $weibo['id'] . " and memberid='" . $memberid . "' and uid='" . $_SESSION['weibo']['uid'] . "' and status!=3";
					if(!$this->res($sql))
					{
						$curtime = time() + 30;
						$ins = array('memberid'=>$memberid, 'uid'=>$_SESSION['weibo']['uid'], 'weiboid'=>$weibo['id'], 'message'=>$weibo['message'], 'imageurl'=>$weibo['imageurl'], 'queuetime'=>$queuetime, 'status'=>1, 'addtime'=>time());
						$this->ins('iok_wbqueue', $ins);
						$nexttime = $queuetime + $intime * 60;
						$nextdate = date('Y-m-d', $nexttime);
						$nexthour = date('H', $nexttime);
						$nextminute = date('i', $nexttime);
						sleep(1);
						echo json_encode(array('status'=>1, 'date'=>$nextdate, 'hour'=>$nexthour, 'minute'=>$nextminute, 'msg'=>'【待发送】' . date('Y年m月d日H时i分', $queuetime)));
					}else
					{
						echo json_encode(array('status'=>0, 'msg'=>'该微博已发送或在发送队列中'));
					}
				}else
				{
					echo json_encode(array('status'=>0, 'msg'=>'无此微博，请刷新页面'));
				}
			}
		}
	}
	public function sendnow()
	{
		$this->checkauth(false);
		$memberid = $_SESSION['authId'];
		$id = getvar('id', 0, 'integer');
		if($id)
		{
			$sql = "select id, message, imageurl from iok_weibodata where id='" . $id . "' limit 1";
			$weibo = $this->rec($sql);
			if($weibo)
			{
				$sql = "select id from iok_wbqueue where weiboid=" . $weibo['id'] . " and memberid='" . $memberid . "' and uid='" . $_SESSION['weibo']['uid'] . "' and status!=3";
				if(!$this->res($sql))
				{
					$rtn = $this->sendweibo($weibo['message'], $weibo['imageurl'], $_SESSION['weibo']['accesstoken']);
					if($rtn['status'])
					{
						$curtime = time();
						$ins = array('memberid'=>$memberid, 'uid'=>$_SESSION['weibo']['uid'], 'wid'=>$rtn['msg'], 'weiboid'=>$weibo['id'], 'message'=>$weibo['message'], 'imageurl'=>$weibo['imageurl'], 'queuetime'=>$curtime, 'sendtime'=>$curtime, 'status'=>2, 'addtime'=>$curtime);
						$this->ins('iok_wbqueue', $ins);
						echo json_encode(array('status'=>1, 'msg'=>'【已发送】' . date('Y年m月d日H时i分', $curtime)));
					}else
					{
						echo json_encode(array('status'=>0, 'msg'=>'发送失败，原因：' . $rtn['msg']));
					}
				}else
				{
					echo json_encode(array('status'=>0, 'msg'=>'该微博已发送或在发送队列中'));
				}
			}else
			{
				echo json_encode(array('status'=>0, 'msg'=>'无此微博，请刷新页面'));
			}
		}
	}
	private function sendweibo($wbtext, $wbimage = '', $accesstoken)
	{
		if(!$accesstoken)
		{
			return array('status'=>0, 'msg'=>'授权失败');
		}else
		{
			require_once (THINK_PATH . '../Lib/ORG/Util/saetv2.ex.class.php');
			$c = new SaeTClientV2(C("SINA_WB_AKEY"), C("SINA_WB_SKEY"), $accesstoken);
			if($wbimage)
			{
				$result = $c->upload($wbtext, $wbimage);
			}else
			{
				$result = $c->update($wbtext);
			}
			if(isset($result['error']))
			{
				return array('status'=>0, 'msg'=>$result['error']);
			}else
			{
				return array('status'=>1, 'msg'=>$result['mid']);
			}
		}
	}
	public function auth()
	{
		$this->checkauth();
		require_once (THINK_PATH . '../weibo/Lib/ORG/Util/saetv2.ex.class.php');
		$o = new SaeTOAuthV2(C("SINA_WB_AKEY"), C("SINA_WB_SKEY"));
		$code_url = $o->getAuthorizeURL(C("SINA_WB_CALLBACK_URL"));
		gotourl($code_url);
	}
	public function callback()
	{
		$this->checkauth();
		$memberid = $_SESSION['authId'];
		require_once (THINK_PATH . '../weibo/Lib/ORG/Util/saetv2.ex.class.php');
		$o = new SaeTOAuthV2(C("SINA_WB_AKEY"), C("SINA_WB_SKEY"));
		if(isset($_REQUEST['code']))
		{
			$keys = array();
			$keys['code'] = $_REQUEST['code'];
			$keys['redirect_uri'] = C("SINA_WB_CALLBACK_URL");
			try
			{
				$token = $o->getAccessToken('code', $keys);
			}catch(OAuthException $e)
			{
				echo $e;
			}
		}
		if($token)
		{
			setcookie('weibojs_' . $o->client_id, http_build_query($token));
			$c = new SaeTClientV2(C("SINA_WB_AKEY"), C("SINA_WB_SKEY"), $token['access_token']);
			$userdata = $c->show_user_by_id($token['uid']);
			$sql = "select memberid from iok_sinauser where uid='" . $token['uid'] . "' limit 1";
			$mid = $this->res($sql);
			if($mid)
			{
				if($memberid == $mid)
				{
					$sql = "
						update 
							iok_sinauser 
						set 
							accesstoken='" . $token['access_token'] . "',
							nickname='" . $userdata['name'] . "',
							headpic='" . $userdata['avatar_large'] . "',
							followers='" . $userdata['followers_count'] . "',
							friends='" . $userdata['friends_count'] . "',
							statuses='" . $userdata['statuses_count'] . "',
							updatetime=" . time() . " 
						where 
							uid='" . $token['uid'] . "' and
							memberid='" . $mid . "'
						limit 1";
					$this->exec($sql);
					js_alert('绑定成功', '/weibo/');
				}else
				{
					js_alert('对不起，该新浪微博账号已被其他用户绑定', '/weibo/index.php?a=auth');
				}
			}else
			{
				$ins = array('uid'=>$token['uid'], 'memberid'=>$memberid, 'accesstoken'=>$token['access_token'], 'sendinterval'=>60, 'nickname'=>$userdata['name'], 'headpic'=>$userdata['avatar_large'], 'followers'=>$userdata['followers_count'], 'friends'=>$userdata['friends_count'], 'statuses'=>$userdata['statuses_count'], 'authtime'=>time());
				$this->ins('iok_sinauser', $ins);
				js_alert('绑定成功', '/weibo/');
			}
		}
	}
}