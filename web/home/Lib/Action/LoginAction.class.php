<?php
/** 
 * author: 					wuzhijie
 * description: 			登录/登出 功能
 * last modified by:		wuzhijie  
 * last modified date: 		2013年11月25日 09:33:58
 **/
class LoginAction extends CommonAction
{
	public function index(){
		//提示信息输出
		$postdata = $this->mget();
		$this->assign('data',$postdata);
		
		$this->assign("title","会员登录中心_我行网");
		$this->assign("keywords","");
		$this->assign("description","");
		$this->display('login');
	}
	public function login()
	{
		//获得用户信息
		$datas['username'] = getVar('username');
		$password = getVar('password');
		$verifycode = getVar('verify');
		//判断是否数据为空
		if(empty($datas['username'])){
			$errcode = '必填项，不能为空！';
			$this->mset($errcode,'tusername');
			$this->index();
			return false;
		}elseif(empty($password)){
			$errcode = '必填项，不能为空！';
			$this->mset($errcode,'tpassword',$datas);
			$this->index();
			return false;
		}elseif(empty($verifycode)){
			$errcode = '必填项，不能为空！';
			$this->mset($errcode,'verifycode',$datas);
			$this->index();
			return false;
		}
			
		//判断验证码是否正确 一次 md5 加密
		if($_SESSION['verify'] != md5($verifycode))
		{
			$errcode = '请正确填写验证码';
			$this->mset($errcode,'verifycode',$datas);
			$this->index();
			return false;
		}
		//登录密码判断
		$sql_p = "select id,account,oldpasshash,passhash,saltcode,gradeid from iok_member where account = '".$datas['username']."' limit 1";
		$mate = $this->rec($sql_p);
		
		if($mate['passhash']!=''){
			$password = md5($password.$mate['saltcode']);
			if($password != $mate['passhash'])
			{
				$errcode = '帐号或密码不正确！';
				$this->mset($errcode,'tpassword',$datas);
				$this->index();
				return false;
			}else{
				//登录成功 根据不同帐号获取不同信息，存入session
				if($mate['gradeid']==6){
					//个人帐号
					$sql_info = "
						select 
							m.id,
							m.account,
							m.saltcode,
							m.membertype,
							m.gradeid,
							m.areaid,
							m.servicestaffid,
							i.prettyname,
							i.gender,
							a.totalmoney 
						from iok_member m,iok_memberinfo i,iok_memberaccount a 
						where m.id=i.memberid and m.id=a.memberid and m.account = '".$datas['username']."' 
						limit 1
					";
					
				}elseif(in_array($mate['gradeid'],array(1,2,3,7,8,9))){
					//企业帐号
					$sql_info = "
						select 
							m.id,
							m.account,
							m.saltcode,
							m.membertype,
							m.gradeid,
							m.areaid,
							m.servicestaffid,
							i.prettyname,
							i.gender,
							a.totalmoney,
							c.companytypeid,
							c.company
							
						from iok_member m,iok_memberinfo i,iok_memberaccount a,iok_membercompany c 
						where m.id=i.memberid and m.id=a.memberid and m.id = c.memberid and m.account = '".$datas['username']."' 
						limit 1
					";
				}elseif(in_array($mate['gradeid'],array(4,5))){
					//商代、服务站帐号
					$sql_info = "
						select 
							m.id,
							m.account,
							m.saltcode,
							m.membertype,
							m.gradeid,
							m.areaid,
							m.servicestaffid,
							i.prettyname,
							i.gender,
							a.totalmoney 
						from iok_member m,iok_memberinfo i,iok_memberaccount a 
						where m.id=i.memberid and m.id=a.memberid and m.account = '".$datas['username']."' 
						limit 1
					";
				}
				$_memberinfo = $this->rec($sql_info);
				$_SESSION['member'] = $_memberinfo;
			}
		}else{
			//老帐号登录，并生成新密码规则
			$oldpassword = md5(md5($password));
			$saltcode = randcode(8);
			$newpassword = md5($password.$saltcode);
			//验证密码登录
			if($oldpassword != $mate['oldpasshash'])
			{
				$errcode = '帐号或密码不正确！';
				$this->mset($errcode,'tpassword',$datas);
				$this->index();
				return false;
			}else{
				//更新 新密码
				$result = $this->exec("update iok_member set passhash = '".$newpassword."',saltcode='".$saltcode."'  where id=".$mate['id'] );
				
				//登录成功 根据不同帐号获取不同信息，存入session
				if($mate['gradeid']==6){
					//个人帐号
					$sql_info = "
						select 
							m.id,
							m.account,
							m.saltcode,
							m.membertype,
							m.gradeid,
							m.areaid,
							m.servicestaffid,
							i.prettyname,
							i.gender,
							a.totalmoney 
						from iok_member m,iok_memberinfo i,iok_memberaccount a 
						where m.id=i.memberid and m.id=a.memberid and m.account = '".$datas['username']."' 
						limit 1
					";
					
				}elseif(in_array($mate['gradeid'],array(1,2,3,7,8,9))){
					//企业帐号
					$sql_info = "
						select 
							m.id,
							m.account,
							m.saltcode,
							m.membertype,
							m.gradeid,
							m.areaid,
							m.servicestaffid,
							i.prettyname,
							i.gender,
							a.totalmoney,
							c.companytypeid,
							c.company
							
						from iok_member m,iok_memberinfo i,iok_memberaccount a ,iok_membercompany c 
						where m.id=i.memberid and m.id=a.memberid and m.account = '".$datas['username']."' 
						limit 1
					";
				}elseif(in_array($mate['gradeid'],array(4,5))){
					//商代、服务站帐号
					$sql_info = "
						select 
							m.id,
							m.account,
							m.saltcode,
							m.membertype,
							m.gradeid,
							m.areaid,
							m.servicestaffid,
							i.prettyname,
							i.gender,
							a.totalmoney 
						from iok_member m,iok_memberinfo i,iok_memberaccount a 
						where m.id=i.memberid and m.id=a.memberid and m.account = '".$datas['username']."' 
						limit 1
					";
				}
				$_memberinfo = $this->rec($sql_info);
				$_SESSION['member'] = $_memberinfo;
				
			}
		}
		//登录成功后的跳转路径
		redirect('/Index');
		return false;
		
		//是否进入商务室
		//$is_enter = getVar('enterhome');
		//是否保存一周的用户信息
		$login_cookietime = getVar('login_cookietime');
		//查询用户是否存在
		$sql_member = "select * from iok_member where account ='".$datas['username']."'";
		$member_data = $this->rec($sql_member);
		
		if($member_data)
		{
			//密码：原密码+干扰码
			$md5pw = md5(md5($password));
			
			
			if($member_data['password'] == $md5pw)
			{
				if($member_data['groupid'] == 2)
				{
					$this->error('该用户已被禁用！！');		
					
				}elseif($member_data['groupid'] == 15){
					/* 如果是商务代表，存入商务代表信息，判断资料步骤  */
					$representdata = M('represent')->where("userid ='".$member_data['userid']."'")->field('grade,iokid,state,star')->find();
					if(!$representdata)
					{
						$this->error('数据不完整，请联系客服，您的ID：'.$member_data['userid']);
					}
					$member_data['usertype'] = ($representdata['grade'] == 1 ? 'repstation' :'represent');
					session('userdata', array_merge($member_data,$representdata));				

					if($login_cookietime)
					{
						cookie('iok_user', $member_data['username'], 604800); 
						cookie('iok_pwd', $member_data['password'], 604800); 
					}
					/*商务代表登录积分*/
					$logintime = $member_data['logintime'];
					$username = $member_data['username'];
					$logintimes = $member_data['logintimes'];
					$mstates=M('member')->where("username='$username'")->find();
					if(date('Ymd', $logintime) != date('Ymd') || ($mstates['logintime'] == $mstates['regtime']))//去掉限制天数
					{
						
						$balance = M('finance_credit')->where("username = '$username'")->find();
						if($balance)
						{
							$sum = M('finance_credit')->where("username = '$username'")->sum('amount');
							$data = array('username'=>$username, 'amount'=>2, 'balance'=>$sum + 2, 'addtime'=>time(), 'reason'=>'登录奖励', 'note'=>get_client_ip(), 'editor'=>'system');
							$map['credit']=$sum +2;
							$_SESSION['userdata']['credit']=$sum +2;//更新session中积分
						}else
						{
							$data = array('username'=>$username, 'amount'=>2, 'balance'=>2, 'addtime'=>time(), 'reason'=>'登录奖励', 'note'=>get_client_ip(), 'editor'=>'system');
							$map['credit']=2;
							$_SESSION['userdata']['credit']=2;//更新session中积分
						}
						M('finance_credit')->data($data)->add(); //1376648154
						$map['logintime'] = time();
						$map['logintimes'] = $logintimes + 1;
						M('member')->where("username = '$username'")->data($map)->save();
					}
					group_url($member_data['groupid'], $url,true);
				
				}else{
					if($member_data['groupid'] == 5)
					{
						$member_data['usertype'] = 'member';
						session('userdata', $member_data);
						$mstates=M('member')->where("username='$username'")->find();
							if($mstates['groupid']==5){ //个人会员加登录积分
								$logintime = $member_data['logintime'];
								$username = $member_data['username'];
								$logintimes=$member_data['logintimes'];
								$regtime =$member_data['regtime'];
								if(date('Ymd', $logintime) != date('Ymd')  || ($mstates['logintime'] == $mstates['regtime'])) //不限制登录天数;
								{
									
									$balance = M('finance_credit')->where("username = '$username'")->find();
									if($balance)
									{
										$sum = M('finance_credit')->where("username = '$username'")->sum('amount');
										$data = array('username'=>$username, 'amount'=>2, 'balance'=>$sum + 2, 'addtime'=>time(), 'reason'=>'登录奖励', 'note'=>get_client_ip(), 'editor'=>'system');
										$map['credit']=$sum +2;
										$_SESSION['userdata']['credit']=$sum +2;//更新session中积分
									}else
									{
										$data = array('username'=>$username, 'amount'=>2, 'balance'=>2, 'addtime'=>time(), 'reason'=>'登录奖励', 'note'=>get_client_ip(), 'editor'=>'system');
										$map['credit']=2; //更新member表中的积分
										$_SESSION['userdata']['credit']=2;
									}
									M('finance_credit')->data($data)->add(); //1376648154
									$map['logintime'] = time();
									$map['logintimes'] = $logintimes + 1;
									M('member')->where("username = '$username'")->data($map)->save();
								}
							}
					}elseif(in_array($member_data['groupid'],array(6,8,9)))
					{
						$companydata = M('company')->where("userid ='".$member_data['userid']."'")->field('groupid as companygroupid,company as companyname, representid,representname,status as state')->find();
//						if(!$companydata)
//						{
//							js_alert('信息不完整，请联系客服，您的ID：'.$member_data['userid'],'','/Public/login');
//						}
							$cstate=M('company')->where("username='$username'")->find();
							$mstates=M('member')->where("username='$username'")->find();
							if(($cstate['status']==5) && ($cstate['groupid']==8)){ //若符合认证的企业加登录积分
								//$companys=M('memeber')->where("username='$username'")->find();
								$logintime = $mstates['logintime'];
								$username = $member_data['username'];
								$logintimes=$mstates['logintimes'];
								if(date('Ymd', $logintime) != date('Ymd') || ($mstates['logintime'] == $mstates['regtime'])) //不限制登录天数;
								{
									
									$balance = M('finance_credit')->where("username = '$username'")->find();
									if($balance)
									{
										$sum = M('finance_credit')->where("username = '$username'")->sum('amount');
										$data = array('username'=>$username, 'amount'=>2, 'balance'=>$sum + 2, 'addtime'=>time(), 'reason'=>'登录奖励', 'note'=>get_client_ip(), 'editor'=>'system');
										$map['credit']=$sum +2;
										$_SESSION['userdata']['credit']=$sum +2;//更新session中积分
									}else
									{
										$data = array('username'=>$username, 'amount'=>2, 'balance'=>2, 'addtime'=>time(), 'reason'=>'登录奖励', 'note'=>get_client_ip(), 'editor'=>'system');
										$map['credit']=2; //更新member表中的积分
										$_SESSION['userdata']['credit']=2;
									}
									M('finance_credit')->data($data)->add(); //1376648154
									$map['logintime'] = time();
									$map['logintimes'] = $logintimes + 1;
									M('member')->where("username = '$username'")->data($map)->save();
								}
							}
						if($companydata)
						{
							$member_data['usertype'] = 'company';
							session('userdata', array_merge($member_data,$companydata));
						}else{
							$this->error('数据错误！请联系管理员！');exit;
						}
					}elseif($member_data['groupid']=1)//渠道商groupid=1,前台不能登录。 for wb 20131029
					{
						$this->right('您是渠道商！请登录后台！','forward',"/Admins/index.php/Public/channel");
					}else{
						$this->error('用户不存在！');
						exit;
					}
					//if(!$fromurl) $fromurl='/Index';
					//如果前台勾选了记住一周，那要将用户信息加密后存入用户的cookie中
					if($login_cookietime)
					{
						cookie('iok_user', $member_data['username'], 604800); // 指定用户名cookie保存时间
						cookie('iok_pwd', $member_data['password'], 604800); // 指定密码cookie保存时间
					}
				
					// get banword
					$bw = M('banword');
					$bwrec = $bw->field('replacefrom,replaceto')->select();
					if($bwrec)
					{
						$bwarr = array();
						foreach($bwrec as $val)
						{
							array_push($val['replacefrom'], $bwarr['replacefrom']);
							array_push($val['replaceto'], $bwarr['replaceto']);
						}
						$_SESSION['banword'] = $bwarr;
					}
					header('Content-Type:application/json; charset=utf-8');
					group_url($member_data['groupid'], $url,true);
				}
			}else
			{
				$this->error('密码错误！！！');
			}
		}
	}
	//判断COOKIE中是否已经有用户信息
	public function get_cook()
	{
		$c_username = cookie('iok_user');
		$c_pwd = cookie('iok_pwd');
		if($username && $pwd)
		{
			$userinfo = M('Member')->where("username = '$c_username' and password = '$c_pwd'")->find();
			session('userdata', $userinfo);
		}
	}

	/*
	 *  商务代表后面流程
	 *
	 */
	public function reg_flow()
	{			
		is_login();	
		//rep_to_step();
		$this->assign("uid", ($_SESSION['userdata']['iokid']));
		$repname =  ($_SESSION['userdata']['usertype']=='represent' ? '商务代表' : '商务服务站');
		$this->assign("money", ($_SESSION['userdata']['usertype']=='repstation' ? '15000' : '5000'));
		$this->assign("repname", $repname);
		$this->assign("title", $repname. "注册_我行网-中国商品交易所");
		$this->display('reg_flow');
		
	}
	
	/*
	 *  商务代表后面流程 - 完善资料
	 *
	 */
	public function reg_data(){		
		is_login();	
		rep_to_step();
		$tab_res_education = M('res_education');
		$tab_res_work = M('res_work');
		$tab_member = M('member');
		$tab_represent = M('represent');
		
		$uid = $_SESSION['userdata']['iokid'];
		$userid = $_SESSION['userdata']['userid'];		
		$file = $this->_param('file') ? $this->_param('file') : 'pic';
		$sub = $this->_param('sub');
		switch($file)
		{				
			/*case 'shenhe': //审核	
				/*
				$member_data=$db->get_one("select r.username,r.userid,m.email from {$db->pre}represent as r,{$db->pre}member as m where r.userid=m.userid and r.iokid='{$uid}'");
				$username=$member_data[username];
				//echo $member_data['email'];exit;
				$title='有一位新的商务代表入驻,等待审核';
				$content ='有一位新的商务代表入驻,请尽快根据该用户的注册信息审核';
				send_message($username,$title,$content);
				//发送至用户注册的邮箱			
				//send_mail($mail_to, $mail_subject, $mail_body);
				//状态值state为6：通过，7不通过
				message('您的信息已经提交请耐心等待审核', DT_PATH,3);exit;
				
				$this->success('您的信息已经提交请耐心等待审核', '/Represent');
			break;	*/
			case 'edu': 
				switch($sub)
				{	
					case 'add': 
						$rep['iokid'] = $uid;
						$rep['degree'] = $_POST['xuewei'];
						$rep['starttime'] = $_POST['start'];
						$rep['overtime'] = $_POST['stop'];
						$rep['school'] = $_POST['name'];
						$rep['major'] = $_POST['yuanxi'];
						$tab_res_education->create();
						$repid = $tab_res_education->add($rep);
						if($repid){
							echo $repid;
							exit;					
						}				
					break;
					case 'del': 
						$id = $this->_param('id');
						$del = $tab_res_education->where("id='".$id."'")->delete(); 
						if($del){
							echo 1;
							exit;					
						}	
					break;
				}
			break;	
			case 'work': 
				switch($sub)
				{	
					case 'add': 
						$woek['iokid'] = $uid;
						$woek['starttime'] = $_POST['start'];
						$woek['overtime'] = $_POST['stop'];
						$woek['company'] = $_POST['danwei'];
						$woek['job'] = $_POST['zhiye'];
						$tab_res_work->create();
						$worid = $tab_res_work->add($woek);
						if($worid){
							echo $worid;
							exit;					
						}							
					break;
					case 'del': 
						$id = $this->_param('id');
						$del = $tab_res_work->where("id='".$id."'")->delete(); 
						if($del){
							echo 1;
							exit;					
						}						
					break;
				}
			break;	
			case 'pic': //上传图片
				if($_POST['submit'])
				{						
					$data['headpic'] = $_POST['headpic'];
					$data['state'] = 3; //状态
					$dm = $tab_represent->where("userid='".$userid."'")->save($data); 						
					if($dm){
						$_SESSION['userdata']['state'] = 3;
						redirect('/Public/reg_flow/uid/'.$userid, 3, '页面跳转中...');								
					}else{									
						$this->error('提交失败！', '/Public/reg_data/file/pic/uid/'.$uid);								
					}		
				}else{
					//信息
					$results = $tab_represent->where("iokid='".$uid."'")->find();					
					$this->assign("results", $results);
				}						
			break;
			case 'news': 
				if(isset($_POST['tokencode']) && $_POST['tokencode'] == '1')
				{			
					$dam['truename'] = $_POST['ture_name'];
					$dam['gender'] = $_POST['gender'];
					$dam['address'] = $_POST['juzhudi']; 
					$dam['idcard'] = $_POST['shenfenzheng'];					
					$tab_member->where("userid='".$userid."'")->save($dam);
					$dar['state'] = 5; //状态
					$dar['birthday'] = $_POST['sui'];						
					$dar['star'] = 1;
					$dar['idcardimg'] = $_POST['idcardimg'];	
					$dar['idcardimg2'] = $_POST['idcardimg2'];	
					if($_SESSION['userdata']['grade'] == 1){
						$dar['licence']=$_POST['idcardimg3'];
						if($dar['licence']==''){
							$this->error('营业执照不能为空!');
						}
					}
					if($dar['birthday']==''||$dar['idcardimg']==''||$dar['idcardimg2']==''){
						$this->error('必填项不能为空');
					}
					$dr = $tab_represent->where("userid='".$userid."'")->save($dar);					
					$_SESSION['userdata']['state'] = 5;
					$this->right('提交成功！,请耐心等待审核','forward', '/Represent');									
				}else{
					//教育	
					$edu = $tab_res_education->where("iokid='".$uid."'")->order("id ASC")->select();
					//工作
					$work = $tab_res_work->where("iokid='".$uid."'")->order("id ASC")->select();				
					//所有信息
					$results = $tab_represent->table("destoon_represent a, destoon_member b")->where("a.userid=b.userid and iokid='".$uid."'")->find();
					//print_r($results);exit;
					$this->assign("edu", $edu);
					$this->assign("work", $work);				
					$this->assign("results", $results);
				}			
			break;	
		}
		
		$this->assign("uid", $uid);
		$this->assign("file", $file);
		$this->assign("title", "商务代表注册_我行网-中国商品交易所");
		$this->display('reg_data');
		EXIT;
	}	
	
	
	
	//注册邮件验证 发送
	public function mail()
	{
		import('@.ORG.Util.Mail'); // 导入邮寄类
		$mailname = $this->_param('mailaddress'); //目标邮件地址;
		$title = $this->_param('title'); //'我行网用户注册邮件验证码';
		$emailcode = rand_string();
		$truename = '我行网';
		$this->assign('title', $title);
		$this->assign('sitename', $truename);
		$this->assign('emailcode', $emailcode);
		$content = $this->fetch('Public:emailcode');
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
		$back = SendMail($mailname, $title, $content, $truename);
		if($back)
		{
			session('mail_verify', $emailcode);
			$this->ajaxReturn('发送成功', '成功', 1);
		}else
		{
			$this->ajaxReturn('发送失败', '失败', 0);
		}
	}
	/* 
		注册成功 发送邮寄提示成功 
		wuzhijie
		2013年9月25日 11:10:11
		$name -- username
		$passw --password
		$mailname--目标邮件地址;
		$way --注册提示用户类别内容
	*/
	public function reg_mail($mailname,$name,$passw,$way)
	{
		import('@.ORG.Util.Mail'); // 导入邮箱类
		
		$title = '我行网用户注册成功'; //邮件title;

		$truename = '我行网'; //发件人
		
		$this->assign('title', $title);
		$this->assign('name', $name);
		$this->assign('passw', $passw);
		$this->assign('sitename', $truename);
		if($way=='represent'){
			$this->assign('content', '您好！您已成功注册我行网会员-商务代表');
		}elseif($way=='station'){
			$this->assign('content', '您好！您已成功注册我行网会员-商务服务站');
		}else{
			$this->assign('content', '您好！您已成功注册我行网会员');
		}
		$content = $this->fetch('Public:email_regist');
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
		$back = SendMail($mailname, $title, $content, $truename);
	}

	
	
	
	
	
	
	
}
