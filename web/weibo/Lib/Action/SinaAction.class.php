<?php
// 本类由系统自动生成，仅供测试用途
class SinaAction extends Action
{
	public function auth()
	{
		require_once (THINK_PATH . '../weibo/Lib/ORG/Util/saetv2.ex.class.php');
		$o = new SaeTOAuthV2(C("SINA_WB_AKEY"), C("SINA_WB_SKEY"));
		$code_url = $o->getAuthorizeURL(C("SINA_WB_CALLBACK_URL"));
		gotourl($code_url);
	}
	// avatar_large
	// followers_count
	// friends_count
	// statuses_count
	
	public function callback()
	{
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
							uid='" . $token['uid'] . "' 
						limit 1";
					$this->exec($sql);
					js_alert('绑定成功', '', '/weibo/');
				}else
				{
					js_alert('对不起，该新浪微博账号已被其他用户绑定', '', '/weibo/index.php/Sina/auth');
				}
			}else
			{
				$ins = array('uid'=>$token['uid'], 'memberid'=>$_SESSION['memberid'], 'accesstoken'=>$token['access_token'], 'nickname'=>$userdata['name'], 'headpic'=>$userdata['avatar_large'], 'followers'=>$userdata['followers_count'], 'friends'=>$userdata['friends_count'], 'statuses'=>$userdata['statuses_count'], 'authtime'=>time());
				$this->ins('iok_sinauser', $sql);
				js_alert('绑定成功', '', '/weibo/');
			}
		}
	}
	public function send()
	{
		$wbtext = getvar('wbtext');
		$wbimage = getvar('wbimage');
		$memberid = $_SESSION['authId'];
		if($memberid)
		{
			$sql = "
				select 
					su.accesstoken,su.nickname 
				from 
					destoon_member m 
						inner join iok_sinauser su on m.userid=su.memberid
				where 
					su.memberid='" . $memberid . "'
				limit 1";
			$rec = $this->rec($sql);
			if($rec && $rec['accesstoken'])
			{
				require_once (THINK_PATH . '../weibo/Lib/ORG/Util/saetv2.ex.class.php');
				$c = new SaeTClientV2(C("SINA_WB_AKEY"), C("SINA_WB_SKEY"), $rec['accesstoken']);
				if($wbimage)
				{
					$result = $c->upload($wbtext, $wbimage);
				}else
				{
					$result = $c->update($wbtext);
				}
				if(isset($result['error']))
				{}else
				{}
			}else
			{}
		}else
		{
			gotourl('');
		}
	}
}
?>