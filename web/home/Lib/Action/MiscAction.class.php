<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class MiscAction extends CommonAction
{
	public function index()
	{
		
	}
	public function captcha()
	{
		header("Content-type: image/png");
		srand((double)microtime() * 1000000);
		$_SESSION['captcha'] = "";
		$im = imagecreate(60, 20);
		$black = ImageColorAllocate($im, 0, 0, 0);
		$gray = ImageColorAllocate($im, 200, 200, 200);
		imagefill($im, 0, 0, $gray);
		while(($authnum = rand() % 10000) < 1000);
		$_SESSION['captcha'] = $authnum;
		imagestring($im, 5, 10, 3, $authnum, $black);
		for($i = 0; $i < 200; $i++)
		{
			$randcolor = ImageColorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
			imagesetpixel($im, rand() % 70, rand() % 30, $randcolor);
		}
		ImagePNG($im);
		ImageDestroy($im);
	}
	public function logout()
	{
		unset($_SESSION['member'], $_SESSION['syscfg']);
		$_SESSION = array();
		if(isset($_COOKIE[session_name()])) setcookie(session_name(), '', time() - 42000, '/');
		session_destroy();
		redirect('/Index');
	}
	public function area()
	{
		$aid = getvar('aid');
		$pid = getvar('pid',0,'integer');
		$sid = getvar('sid',0,'integer');
		if(!in_array($sid, array(1,2,3)))
		{
			$sid = 0;
		}
		$sid ++ ;
		$html = '';
		if($aid)
		{
			if($sid == 1) $html .= "<input type=\"hidden\" id=\"".$aid."-0\" name=\"$aid\" value=\"0\" />"; 
			if($pid > 0 || ($pid == 0 && $sid == 1))
			{
				$sql = "select id,prettyname from iok_area where parentid='".$pid."'";
				$rec = $this->arr($sql);
				if($rec)
				{
					$html .= "<select id=\"".$aid."-".$sid."\" onchange=\"javascript:loadarea('$aid',this.value,'".$sid."');\">";
					$html .= "<option value=\"0\">请选择</option>";
					foreach ($rec as $val)
					{
						$html .= "<option value=\"".$val['id']."\">".$val['prettyname']."</option>";
					}
					$html .= "</select>";
				}
			}
		}
		echo $html;
	}
	
	/* 密码找回 wuzhijie 2013年12月9日 09:26:13 */
	//to passwordchangebyrelation 
	public function retrievepw(){
		//提示信息输出
		$postdata = $this->mget();
		$this->assign('datas',$postdata);
		$this->display('Public:retrieve');
	}
	//to passwordchangebyemail
	public function retrievebyemail(){
		//提示信息输出
		$postdata = $this->mget();
		$this->assign('datas',$postdata);
		$this->display('Public:retrieve_byemail');
	}
	
	
	/* 联系方式修改密码 */
	public function verifypword(){
		$verify = getVar('verify');
		$datas['username'] = getVar('username');
		$datas['mobile'] = getVar('mobile');
		if( md5($verify)==$_SESSION['verify']){
			if($datas['username'] && !empty($datas['username'])){
				if($datas['mobile'] && !empty($datas['mobile'])){
					$result = $this->rec("select m.id,m.account,m.saltcode,i.mobile,i.email from iok_member m,iok_memberinfo i where m.id=i.memberid and m.account='".$datas['username']."' and m.account!='' ");
					if($result){
							/* 数据是否 符合 联系*/
							if($datas['username']==$result['account'] && $datas['mobile']==$result['mobile']){
								$shows['name'] = $result['account'];
								$shows['id'] = $result['id'];
								$_SESSION['member']['change_name']=$result['account'];
								$_SESSION['member']['saltcode']=$result['saltcode'];
								
								$data['memberid'] = $result['id'];
								$data['account'] = $datas['username'];
								$data['usedemail'] = $datas['mobile'];
								$data['randcodes'] ='mobilenocode';
								$data['authcode']= 'mobilenoauth';
								$data['addtime'] = time();
								$data['authtime']=time(); 
								$data['ip'] = get_client_ip();
								$data['switch'] = 0;
								$res = $this->ins('iok_passwordrecover',$data); //记录用户要修改帐号
								
								$this->assign('result',$shows);
								$this->display('Public:retrieve_change');
								return false;
							}else{
								$errcode = '联系方式与用户名不匹配！';
								$this->mset($errcode,'tusername',$datas);
								$this->retrievepw();
								return false;
							}
					}else{
						$errcode = '您输入的帐号错误，请重试！';
						$this->mset($errcode,'tusername',$datas);
						$this->retrievepw();
						return false;
						
					}
				}else{
					$errcode = '请填写您的联系方式！';
					$this->mset($errcode,'tmobile',$datas);
					$this->retrievepw();
					return false;
				}
			}else{
				$errcode = '请填写您的用户名！';
				$this->mset($errcode,'tusername',$datas);
				$this->retrievepw();
				return false;
			}
		}else{
			if(empty($verify)){
				$errcode = '您还没有输入验证码！';
				$this->mset($errcode,'tverify',$datas);
				$this->retrievepw();
				return false;
			}else{
				$errcode = '验证码错误，请重新输入！';
				$this->mset($errcode,'tverify',$datas);
				$this->retrievepw();
				return false;
			}
		}
	}
	//执行修改
	public function changepword(){
		$datas['newpassword'] = getvar('newpassword');
		$datas['newpasswordt'] = getvar('newpasswordt');
		if(strlen($datas['newpassword'])>=6 && strlen($datas['newpassword'])<=20){
			if(!empty($datas['newpassword']) && !empty($datas['newpasswordt'])){
				if($datas['newpassword']===$datas['newpasswordt']){
					//新密码
					$newpass = md5($datas['newpassword'].$_SESSION['member']['saltcode']);
					$result = $this->exec("update iok_member set passhash='".$newpass."' where account='".$_SESSION['member']['change_name']."' ");
					$unsetcode = $this->exec("update iok_passwordrecover set switch=0,edittime=".time()."  where account = '".$_SESSION['member']['change_name']."' ");
					unset($_SESSION['member']);
					//修改成功
					redirect('/Login');
			
				}else{
					$errcode = '两次密码输入不一致！';
					$this->mset($errcode,'top_error');
					$this->mget();
					$this->display('Public:retrieve_change');
					return false;
				}
			}else{
				$errcode = '请填写新密码和确认密码！并一致！';
				$this->mset($errcode,'top_error',$total);
				$this->mget();
				$this->display('Public:retrieve_change');
				return false;
			}
		}else{
			$errcode = '密码不规范，请重新输入！';
			$this->mset($errcode,'top_error',$total);
			$this->mget();
			$this->display('Public:retrieve_change');
			return false;
		}
	}
	/* 邮件修改密码 */
	public function cwordemail(){
		$verify = $_SESSION['verify'];
		$verifyt= getvar('verify');
		$datas['username'] = getvar('username');
		$datas['email'] = getvar('email');
		if(!preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',$datas['email'])){
			$errcode = '请真确填写邮件地址！';
			$this->mset($errcode,'tverify',$datas);
			$this->retrievebyemail();
			return false;
		}
		$matchinfo = $this->rec("select m.id,m.account,i.email from iok_member m,iok_memberinfo i where m.id=i.memberid and m.account ='".$datas['username']."' ");
		if($datas['email']!=$matchinfo['email']){
			$errcode = '你输入的信息不正确！请重试！';
			$this->mset($errcode,'tusername',$datas);
			$this->retrievebyemail();
			return false;
		}
		if($verify==md5($verifyt)){
			//限定修改次数
			$counts = $this->res("select count(id) from iok_passwordrecover where account = '".$datas['username']."' and authtime>".time()." and switch=1 ");
			if($counts<=1){
				$data['memberid'] = $matchinfo['id'];
				$data['account'] = $datas['username'];
				$data['usedemail'] = $datas['email'];
				$data['randcodes'] = time().randcode(8);
				$auth = strtoupper(sha1(md5($datas['username'].'|'.$datas['email'].$data['randcodes'])));
				$data['authcode']= $auth;
				$data['addtime'] = time();
				$data['authtime']=time()+86400; //有效期一天
				$data['ip'] = get_client_ip();
				
				$res = $this->ins('iok_passwordrecover',$data); //记录用户要修改帐号
				if($res){
					/* 发送邮件提示激活修改密码 */
					import('@.ORG.Util.Mail'); // 导入邮箱类
					
					$title = '我行网用户找回密码'; //邮件title;

					$senduser = '我行网'; //发件人
					
					$this->assign('title', $title);
					$this->assign('name', $datas['username']);
					$this->assign('sitename', $senduser);
					
					$url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . "/Misc/email_cword/auth/".$auth;
					$this->assign('url',$url);
					$content = $this->fetch('Public:email_pass');
					//配置邮件信息
					$THINK_MAIL = array('MAIL_ADDRESS'=>'service@iokokok.com', // 邮箱地址
						'MAIL_SMTP'=>'smtp.exmail.qq.com', // 邮箱SMTP服务器
						'MAIL_LOGINNAME'=>'service@iokokok.com', //// 邮箱登录帐号
						'MAIL_PASSWORD'=>'a123456', // 邮箱密码
						'MAIL_CHARSET'=>'UTF-8', //编码
						'MAIL_AUTH'=>true, // 邮箱验证
						'MAIL_HTML'=>true
					);// 是否支持HTML
					C($THINK_MAIL);
					//发送邮件
					$back = SendMail($datas['email'], $title, $content, $senduser);
					if($back){
						$this->assign('emails', $datas['email']);
						$emailtype = trim(strstr($datas['email'],'@'),'@');
						if($emailtype=='gmail.com'){
							$goemail = "www.gmail.com";
						}else{
							$goemail = "mail.".$emailtype;
						}
						$this->assign('goemail', $goemail);
						$this->display('Public:success_pass');
					}else{
						$errcode = '邮件发送失败，请重试!';
						$this->mset($errcode,'top_error',$datas);
						$this->retrievebyemail();
						return false;
					}
					
				}else{
					$errcode = '数据错误，邮件发送失败，请重试!';
					$this->mset($errcode,'top_error',$datas);
					$this->retrievebyemail();
					return false;
				}
			}else{
				$errcode = '你的修改申请次数过多，密码修改每天限定二次！';
				$this->mset($errcode,'top_error',$datas);
				$this->retrievebyemail();
				return false;
			}
		}else{
			if(empty($verifyt)){
				$errcode = '请输入验证码！';
				$this->mset($errcode,'tverify',$datas);
				$this->retrievebyemail();
				return false;
			}else{
				$errcode = '验证码错误，请重新输入！';
				$this->mset($errcode,'tverify',$datas);
				$this->retrievebyemail();
				return false;
			}
		}
	}
	//验证链接是否有效
	public function email_cword(){
		$auth 	= getvar('auth');
		if(empty($auth)){
			js_alert('您的链接无效！如有问题，请联系客服！');
			return false;
		}
		$sql = "
			select 
				m.id,
				m.account,
				m.saltcode,
				i.email,
				p.randcodes,
				p.authcode,
				p.authtime 
			from 
				iok_member m,
				iok_memberinfo i,
				iok_passwordrecover p 
			where 
				m.id=p.memberid and 
				m.id=i.memberid and 
				p.switch = 1 and 
				p.authcode='".$auth."' 
			limit 1";
		$res = $this->rec($sql);
		if($res){
			$theauth = strtoupper(sha1(md5($res['account'].'|'.$res['email'].$res['randcodes'])));
			/* 验证是否超时 */
			$isout = $res['authtime']-time();
			if($isout>0){
				if($theauth==$auth){
					$_SESSION['member']['change_name']= $res['account'];
					$_SESSION['member']['saltcode']= $res['saltcode'];
					$_SESSION['member']['changetype'] = 'email';
					$shows['name'] = $res['account'];
					$shows['type'] = 'two';
					$this->assign('result',$shows);
					$this->display('Public:retrieve_change');
					return false;
				}else{
					js_alert('您的链接无效！如有问题，请联系客服！');
					return false;
				}
			}else{
				js_alert('您的链接已经超时！请重新申请找回密码！');
				return false;
			}
		}else{
			js_alert('您的链接无效！如有问题，请联系客服！');
			return false;
		}
	}
	
	
	
}
?>