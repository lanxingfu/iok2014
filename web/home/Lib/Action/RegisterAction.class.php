<?php
/** 
 * author:wuzhijie
 * description:register
 * last modified by:wuzhijie
 * last modified date:2013-11-26 09:44:08
 * last modified content:
 **/
class RegisterAction extends CommonAction
{
	/*
	 *  注册引导
	 *	wuzhijie
	 *	2013年11月26日 09:52:28
	*/
	Public function index(){
		//提示信息输出
		$postdata = $this->mget();
		$_types = $postdata['types'] ? $postdata['types'] :  getVar('types');
		$types = $_types ? $_types : 'company' ;
		switch($types){
			case 'member':
				$postdatap['username'] = $postdata['username'];
				$postdatap['password'] = $postdata['password'];
				$postdatap['cpassword'] = $postdata['cpassword'];
				$postdatap['relation'] = $postdata['relation'];
				$this->assign('datas',$postdatap);
			break;
			case 'company':
				$postdatac['username'] = $postdata['username'];
				$postdatac['password'] = $postdata['password'];
				$postdatac['cpassword'] = $postdata['cpassword'];
				$postdatac['relation'] = $postdata['relation'];
				$this->assign('datas',$postdatac);
			break;
			case 'represent':
				
				$postdatar['username'] = $postdata['username'];
				$postdatar['password'] = $postdata['password'];
				$postdatar['cpassword'] = $postdata['cpassword'];
				$postdatar['relation'] = $postdata['relation'];
				$postdatar['prettyname'] = $postdata['prettyname'];
				$postdatar['inviter'] = $postdata['inviter'];
				$postdatar['email'] = $postdata['email'];
				$postdatar['idnumber'] = $postdata['idnumber'];
				
				if($postdata['areaid']){
					$postdatar['areaid'] = $postdata['areaid'];
				}else{
					$postdatar['areaid'] = 1;
				}
				
				$this->assign('datas',$postdatar);
			break;
			case 'repstation':
				
				$postdatas['username'] = $postdata['username'];
				$postdatas['password'] = $postdata['password'];
				$postdatas['cpassword'] = $postdata['cpassword'];
				$postdatas['relation'] = $postdata['relation'];
				$postdatas['prettyname'] = $postdata['prettyname'];
				$postdatas['inviter'] = $postdata['inviter'];
				$postdatas['email'] = $postdata['email'];
				$postdatas['idnumber'] = $postdata['idnumber'];
				
				if($postdata['areaid']){
					$postdatas['areaid'] = $postdata['areaid'];
				}else{
					$postdatas['areaid'] = 1;
				}
				
				$this->assign('datas',$postdatas);
			break;
			default:
				$types = 'company';
				
				$postdatac['username'] = $postdata['username'];
				$postdatac['password'] = $postdata['password'];
				$postdatac['cpassword'] = $postdata['cpassword'];
				$postdatac['relation'] = $postdata['relation'];
				$this->assign('datas',$postdatac);
			break;
		}
		$this->assign('types',$types);
		$this->display($types);
	}
	/*
	 *  注册
	*/
	/*
	 *	member 个人
	*/
	Public function member_sub(){
		$datas['username'] = getVar('username');
		$datas['password'] = getVar('password');
		$datas['cpassword'] = getVar('cpassword');
		$datas['relation'] = getVar('relation');
		$datas['types'] = 'member';
		$verify = md5(getVar('verify'));
		
		if($datas['username'])
		{
			/* 注册用户名检测 符号、字符是否合法*/
			if(!$this->check_name($datas['username']))
			{
				$errcode = '请规范输入用户名！';
				$this->mset($errcode,'tusername',$datas);
				$this->index();
				return false;
			}
			//长度
			if(strlen($datas['username'])<4 || strlen($datas['username'])>20 )
			{
				$errcode = '请将用户名长度定在4-20位之间！';
				$this->mset($errcode,'tusername',$datas);
				$this->index();
				return false;
			}
			//判断用户名是否存在
			$sql_a = "select id from iok_member where account = '".$datas['username']."'";
			$rec_a = $this->exec($sql_a);
			if($rec_a)
			{
				$errcode = '该用户名已被注册！';
				$this->mset($errcode,'tusername',$datas);
				$this->index();
				return false;
			}
			//检测密码是否符合规则
			if(strlen($datas['password'])<6 || strlen($datas['password'])>20){
				
				$errcode = '密码长度在6-20之间！';
				$this->mset($errcode,'tpassword',$datas);
				$this->index();
				return false;
			}
			if($datas['password']!==$datas['cpassword']){
				
				$errcode = '两次密码需保持一致！';
				$this->mset($errcode,'tcpassword',$datas);
				$this->index();
				return false;
			}
			//检测联系方式是否被注册过
			if(!$this->check_mobile($datas['relation'])){
				$errcode = '请正确输入您的手机号码！';
				$this->mset($errcode,'trelation',$datas);
				$this->index();
				return false;
			}
			$sql_r = "select memberid from iok_memberinfo where mobile = '".$datas['relation']."' ";
			$rec_r = $this->exec($sql_r);
			if($rec_r){
				$errcode = '该联系方式已被注册过！';
				$this->mset($errcode,'trelation',$datas);
				$this->index();
				return false;
			}
			
			//判断验证码是否正确
			if($_SESSION['verify'] != $verify)
			{
				$errcode = '验证码错误！';
				$this->mset($errcode,'tverify',$datas);
				$this->index();
				return false;
			}
			
			//验证合格，入库
			$indata['account'] = $datas['username'];	//账户名
			$indata['saltcode'] = randcode(8);	//干扰码
			$indata['passhash'] = md5($datas['password'].$indata['saltcode']);	//new pw
			$indata['membertype'] = 'person'; //账户类型
			$indata['registerip'] = get_client_ip(); //注册ip
			$indata['registertime'] = time();	//注册时间
			$indata['gradeid'] = 6;
			//注册入库 table：member 、memberinfo
			$infodata['mobile'] = $datas['relation']; //联系方式
			$memberid = $this->ins('iok_member',$indata);
			//是否注册成功
			if($memberid > 0)
			{
				//信息表入库（如果会员注册成功，而信息表没有插入成功，怎么处理呢？）
				$infodata['memberid'] = $memberid;
				$memberinfoid = $this->ins('iok_memberinfo',$infodata);
				//用户账户信息
				$memberaccount['memberid'] = $memberid;
				$memberaccount['paypasshash'] = $indata['passhash'];
				$memberaccount['paysaltcode'] = $indata['saltcode'];
				$memberacc = $this->ins('iok_memberaccount',$memberaccount);
				redirect('/Login');
				
				
				//注册奖励+通知，，，暂未开通
				if(null)
				{
					$data = array('username'=>$username, 'amount'=>20, 'balance'=>20, 'addtime'=>$_POST['regtime'], 'reason'=>'注册奖励', 'note'=>$_POST['regip'], 'editor'=>'system');
					$tab_finance = $finance->data($data)->add();
					$_SESSION['userdata']['credit']=20;//更新session;
					$map['credit']=20;
					$tab_member->where("username='$username'")->data($map)->save();//更新member表中积分字段;
						$message['title']='欢迎您入驻我行网！';    //个人注册欢迎站内信;
						$message['touser'] = $username;
						$message['fromuser'] = 'system';
						$message['content'] = '<table cellpadding="0" cellspacing="0" width="600" style="margin:10px;font-family:Verdana,Arial;">
												<tr>
												<td style="background:#6B85DC;line-height:24px;font-weight:bold;color:#FFFFFF;">&nbsp;&nbsp;欢迎您入驻我行网!</td>
												</tr>
												<tr>
												<td style="border:#CCCCCC 1px solid;padding:20px 20px 20px 20px;line-height:200%;font-size:12px;">
												<strong>欢迎登录帐号为'.$username.'的用户成为我行网个人会员，我行网将竭诚为您服务。<br/>
												（如果您忘记了密码，请点击<a href="/Public/findpw">这里</a>找回？）
												</td>
												</tr>
												<tr>
												<td style="background:#555555;padding:3px;font-size:12px;color:#FFFFFF;">&nbsp;&nbsp;请注意：此信件系 系统信使 自动发送，请勿直接回复。</td>
												</tr>
												</table>';
							$message['addtime'] = time();
							$message['ip'] = get_client_ip();
							$message['status'] = 3;
							M("message")->data($message)->add();
							M()->query( "UPDATE destoon_member SET message=message+1 WHERE username='".$username."'" );
							$_SESSION['userdata']['message']=$_SESSION['userdata']['message']+1;
					$this->right('注册成功！','forward','/Public/login');
				}
			}else{
				$errcode = '注册失败，请重试！';
				$this->mset($errcode,'total',$datas);
				$this->index();
				return false;
			}
		}else{
			$errcode = '请出入用户名！';
			$this->mset($errcode,'tusername',$datas);
			$this->index();
			return false;
		}
	}
	
	/* 企业 */
	Public function company_sub(){
		//有推荐  推荐人id ：recommendid  推荐人姓名 ：recommendnam
		//$recommend['name'] = getVar('recommendname') ? getVar('recommendname'):getVar('recuser') ;
		
		$datas['inviterid'] = getVar('recommendid') ? getVar('recommendid'):getVar('recid') ; 
		$datas['username'] = getVar('username');
		$datas['password'] = getVar('password');
		$datas['cpassword'] = getVar('cpassword');
		$datas['relation'] = getVar('relation');
		$datas['types'] = 'company';
		$verify = md5(getVar('verify'));
		
		if($datas['username'])
		{
			/* 注册用户名检测 符号、字符是否合法*/
			if(!$this->check_name($datas['username']))
			{
				$errcode = '请规范输入用户名！';
				$this->mset($errcode,'tusername',$datas);
				$this->index();
				return false;
			}
			//长度
			if(strlen($datas['username'])<4 || strlen($datas['username'])>20 )
			{
				$errcode = '请将用户名长度定在4-20位之间！';
				$this->mset($errcode,'tusername',$datas);
				$this->index();
				return false;
			}
			//判断用户名是否存在
			$sql_a = "select id from iok_member where account = '".$datas['username']."'";
			$rec_a = $this->exec($sql_a);
			if($rec_a)
			{
				$errcode = '该用户名已被注册！';
				$this->mset($errcode,'tusername',$datas);
				$this->index();
				return false;
			}
			//检测密码是否符合规则
			if(strlen($datas['password'])<6 || strlen($datas['password'])>20){
				
				$errcode = '密码长度在6-20之间！';
				$this->mset($errcode,'tpassword',$datas);
				$this->index();
				return false;
			}
			if($datas['password']!==$datas['cpassword']){
				
				$errcode = '两次密码需保持一致！';
				$this->mset($errcode,'tcpassword',$datas);
				$this->index();
				return false;
			}
			//检测联系方式是否被注册过
			if(!$this->check_mobile($datas['relation'])){
				$errcode = '请正确输入您的手机号码！';
				$this->mset($errcode,'trelation',$datas);
				$this->index();
				return false;
			}
			$sql_r = "select memberid from iok_memberinfo where mobile = '".$datas['relation']."' ";
			$rec_r = $this->exec($sql_r);
			if($rec_r){
				$errcode = '该联系方式已被注册过！';
				$this->mset($errcode,'trelation',$datas);
				$this->index();
				return false;
			}
			
			//判断验证码是否正确
			if($_SESSION['verify'] != $verify)
			{
				$errcode = '验证码错误！';
				$this->mset($errcode,'tverify',$datas);
				$this->index();
				return false;
			}
			
			
			$indata['account'] = $datas['username'];	//账户名
			$indata['saltcode'] = randcode(8);	//干扰码
			$indata['passhash'] = md5($datas['password'].$indata['saltcode']);	//new pw
			$indata['membertype'] = 'company'; //账户类型
			$indata['registerip'] = get_client_ip(); //注册ip
			$indata['registertime'] = time();	//注册时间
			$indata['gradeid'] = 7;
			//联系方式 待判断是手机还是座机
			$infodata['mobile'] = $datas['relation'];
			//注册入库 table：member 、memberinfo
			$memberid = $this->ins('iok_member',$indata);
			//是否注册成功
			if($memberid > 0)
			{
				//注册人信息表入库（如果会员注册成功，而信息表没有插入成功，怎么处理呢？）
				$infodata['memberid'] = $memberid;
				$memberinfoid = $this->ins('iok_memberinfo',$infodata);
				//用户账户表信息
				$memberaccount['memberid'] = $memberid;
				$memberaccount['paypasshash'] = $indata['passhash'];
				$memberaccount['paysaltcode'] = $indata['saltcode'];
				$memberacc = $this->ins('iok_memberaccount',$memberaccount);
				//企业表信息
				$membercompany = $this->ins('iok_membercompany',array('memberid'=>$memberid) );
				
				redirect('/Login');
				
				
			}else{
				$errcode = '注册失败，请重试！';
				$this->mset($errcode,'total',$datas);
				$this->index();
				return false;
			}
		}else{
			$errcode = '请出入用户名！';
			$this->mset($errcode,'tusername',$datas);
			$this->index();
			return false;
		}
	}
	
	/* 注册商代 */
	Public function represent_sub(){
		//member表
		$datas['username'] 	= getVar('username');
		$datas['password']	= getVar('password');
		$datas['cpassword'] = getVar('cpassword');
		//推荐人 存在问题，表内只有id没有name 
		$datas['inviter'] = getVar('inviter');
		$datas['agentareaid'] = $datas['areaid'] = getVar('areaid');
		$datas['types'] 	= 'represent';
		//memberinfo表
		$datas['prettyname'] = getVar('prettyname');
		$datas['gender'] = getVar('gender');
		$datas['email'] = getVar('email');
		$datas['idnumber'] = getVar('idnumber');
		$datas['relation'] = getVar('relation');
		//验证码
		$verify = md5(getVar('verify'));
		$datas['emailcode'] = getVar('emailcode');
		if($datas['username'])
		{
			/* 匹配用户名是否合法 */
			if(!$this->check_name($datas['username']))
			{
				$errcode = '请规范输入用户名！';
				$this->mset($errcode,'tusername',$datas);
				$this->index();
				return false;
			}
			//长度
			if(strlen($datas['username'])<4 || strlen($datas['username'])>20 )
			{
				$errcode = '请将用户名长度定在4-20位之间！';
				$this->mset($errcode,'tusername',$datas);
				$this->index();
				return false;
			}
			//判断用户名是否存在
			$sql_a = "select id from iok_member where account = '".$datas['username']."'";
			$rec_a = $this->exec($sql_a);
			if($rec_a)
			{
				$errcode = '该用户名已被注册！';
				$this->mset($errcode,'tusername',$datas);
				$this->index();
				return false;
			}
			//检测密码是否符合规则
			if(strlen($datas['password'])<6 || strlen($datas['password'])>20){
				
				$errcode = '密码长度在6-20之间！';
				$this->mset($errcode,'tpassword',$datas);
				$this->index();
				return false;
			}
			if($datas['password']!==$datas['cpassword']){
				
				$errcode = '两次密码需保持一致！';
				$this->mset($errcode,'tcpassword',$datas);
				$this->index();
				return false;
			}
			
			//真实姓名
			if(!preg_match('/^[\x{4e00}-\x{9fa5}]+$/u',$datas['prettyname'])){
				$errcode = '请用您的中文名字！';
				$this->mset($errcode,'tprettyname',$datas);
				$this->index();
				return false;
			}
			
			//检查推荐人是否存在
			$inviterid = $this->res("select id from iok_member where account ='".$datas['inviter']."' limit 1 ");
			$indata['inviterid'] = $inviterid ? $inviterid : '';
			
			//服务地区
			if(empty($datas['areaid']) || $datas['areaid']=='0'){
				$errcode = '请选择地区！';
				$this->mset($errcode,'tareaid',$datas);
				$this->index();
				return false;
			}
			
			//电子邮箱
			if(!preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',$datas['email'])){
				$errcode = '请填写真确的邮箱地址！';
				$this->mset($errcode,'temail',$datas);
				$this->index();
				return false;
			}
			$email_exist = $this->res("select m.id,i.memberid,i.email from iok_member m,iok_memberinfo i where m.id=i.memberid and i.email ='".$datas['email']."' limit 1 ");
			if($email_exist){
				$errcode = '该邮箱地址已被占用！';
				$this->mset($errcode,'temail',$datas);
				$this->index();
				return false;
			}
			
			//邮箱验证码
			if($_SESSION['mail_verify']!=$datas['emailcode']){
				$errcode = '邮箱验证码不正确！';
				$this->mset($errcode,'temailcode',$datas);
				$this->index();
				return false;
			}
			//身份证
			if(empty($datas['idnumber'])){
				$errcode = '请填写身份证号！';
				$this->mset($errcode,'tidnumber',$datas);
				$this->index();
				return false;
			}
			
			//检测联系方式
			if(!$this->check_mobile($datas['relation'])){
				$errcode = '请正确输入您的手机号码！';
				$this->mset($errcode,'trelation',$datas);
				$this->index();
				return false;
			}
			$sql_r = "select memberid from iok_memberinfo where mobile = '".$datas['relation']."' ";
			$rec_r = $this->exec($sql_r);
			if($rec_r){
				$errcode = '该联系方式已被注册过！';
				$this->mset($errcode,'trelation',$datas);
				$this->index();
				return false;
			}
			
			//判断验证码是否正确
			if($_SESSION['verify'] != $verify)
			{
				$errcode = '验证码错误！';
				$this->mset($errcode,'tverify',$datas);
				$this->index();
				return false;
			}
			
			//入库数据member表
			$indata['account'] = $datas['username'];				//账户名
			$indata['saltcode'] = randcode(8);						//干扰码
			$indata['passhash'] = md5($datas['password'].$indata['saltcode']);	//new pw
			$indata['membertype'] = 'person'; 						//账户类型
			$indata['registerip'] = get_client_ip(); 				//注册ip
			$indata['registertime'] = time();						//注册时间
			$indata['areaid'] 	= $datas['areaid'];
			$indata['gradeid'] = 4;
			//memberinfo表
			$infodata['prettyname'] = $datas['prettyname'];
			$infodata['gender'] = $datas['gender'];
			$infodata['idnumber'] = $datas['idnumber'];
			$infodata['email'] = $datas['email'];
			//联系方式 只允许用手机 
			$infodata['mobile'] = $datas['relation'];
			
			//注册入库 table：member 、memberinfo
			$memberid = $this->ins('iok_member',$indata);
			//是否注册成功
			if($memberid > 0)
			{
				//注册人信息表入库（如果会员注册成功，而信息表没有插入成功，怎么处理呢？）
				$infodata['memberid'] = $memberid;
				$memberinfoid = $this->ins('iok_memberinfo',$infodata);
				//用户账户表信息
				$memberaccount['memberid'] = $memberid;
				$memberaccount['paypasshash'] = $indata['passhash'];
				$memberaccount['paysaltcode'] = $indata['saltcode'];
				$memberacc = $this->ins('iok_memberaccount',$memberaccount);
				UNSET($_SESSION ["mail_verify"]);
				redirect('/Login');
				
				
			}else{
				$errcode = '注册失败，请重试！';
				$this->mset($errcode,'total',$datas);
				$this->index();
				return false;
			}
		}else{
			$errcode = '请出入用户名！';
			$this->mset($errcode,'tusername',$datas);
			$this->index();
			return false;
		}
		
	}
	
	/* 商务服务站 */
	Public function repstation_sub(){
		//member表
		$datas['username'] 	= getVar('username');
		$datas['password']	= getVar('password');
		$datas['cpassword'] = getVar('cpassword');
		//推荐人 存在问题，表内只有id没有name 
		$datas['inviter'] = getVar('inviter');
		$datas['agentareaid'] = $datas['areaid'] = getVar('areaid');
		$datas['types'] 	= 'retstation';
		//memberinfo表
		$datas['prettyname'] = getVar('prettyname');
		$datas['gender'] = getVar('gender');
		$datas['email'] = getVar('email');
		$datas['idnumber'] = getVar('idnumber');
		$datas['relation'] = getVar('relation');
		//验证码
		$verify = md5(getVar('verify'));
		$datas['emailcode'] = getVar('emailcode');
		if($datas['username'])
		{
			/* 匹配用户名是否合法 */
			if(!$this->check_name($datas['username']))
			{
				$errcode = '请规范输入用户名！';
				$this->mset($errcode,'tusername',$datas);
				$this->index();
				return false;
			}
			//长度
			if(strlen($datas['username'])<4 || strlen($datas['username'])>20 )
			{
				$errcode = '请将用户名长度定在4-20位之间！';
				$this->mset($errcode,'tusername',$datas);
				$this->index();
				return false;
			}
			//判断用户名是否存在
			$sql_a = "select id from iok_member where account = '".$datas['username']."'";
			$rec_a = $this->exec($sql_a);
			if($rec_a)
			{
				$errcode = '该用户名已被注册！';
				$this->mset($errcode,'tusername',$datas);
				$this->index();
				return false;
			}
			//检测密码是否符合规则
			if(strlen($datas['password'])<6 || strlen($datas['password'])>20){
				
				$errcode = '密码长度在6-20之间！';
				$this->mset($errcode,'tpassword',$datas);
				$this->index();
				return false;
			}
			if($datas['password']!==$datas['cpassword']){
				
				$errcode = '两次密码需保持一致！';
				$this->mset($errcode,'tcpassword',$datas);
				$this->index();
				return false;
			}
			
			//真实姓名
			if(!preg_match('/^[\x{4e00}-\x{9fa5}]+$/u',$datas['prettyname'])){
				$errcode = '请用您的中文名字！';
				$this->mset($errcode,'tprettyname',$datas);
				$this->index();
				return false;
			}
			
			//检查推荐人是否存在
			$inviterid = $this->res("select id from iok_member where account ='".$datas['inviter']."' limit 1 ");
			$indata['inviterid'] = $inviterid ? $inviterid : '';
			
			//服务地区
			if(empty($datas['areaid']) || $datas['areaid']=='0'){
				$errcode = '请选择地区！';
				$this->mset($errcode,'tareaid',$datas);
				$this->index();
				return false;
			}
			
			//电子邮箱
			if(!preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',$datas['email'])){
				$errcode = '请填写真确的邮箱地址！';
				$this->mset($errcode,'temail',$datas);
				$this->index();
				return false;
			}
			$email_exist = $this->res("select m.id,i.memberid,i.email from iok_member m,iok_memberinfo i where m.id=i.memberid and i.email ='".$datas['email']."' limit 1 ");
			if($email_exist){
				$errcode = '该邮箱地址已被占用！';
				$this->mset($errcode,'temail',$datas);
				$this->index();
				return false;
			}
			
			//邮箱验证码
			if($_SESSION['mail_verify']!=$datas['emailcode']){
				$errcode = '邮箱验证码不正确！';
				$this->mset($errcode,'temailcode',$datas);
				$this->index();
				return false;
			}
			//身份证
			if(empty($datas['idnumber'])){
				$errcode = '请填写身份证号！';
				$this->mset($errcode,'tidnumber',$datas);
				$this->index();
				return false;
			}
			
			//检测联系方式
			if(!$this->check_mobile($datas['relation'])){
				$errcode = '请正确输入您的手机号码！';
				$this->mset($errcode,'trelation',$datas);
				$this->index();
				return false;
			}
			$sql_r = "select memberid from iok_memberinfo where mobile = '".$datas['relation']."' ";
			$rec_r = $this->exec($sql_r);
			if($rec_r){
				$errcode = '该联系方式已被注册过！';
				$this->mset($errcode,'trelation',$datas);
				$this->index();
				return false;
			}
			
			//判断验证码是否正确
			if($_SESSION['verify'] != $verify)
			{
				$errcode = '验证码错误！';
				$this->mset($errcode,'tverify',$datas);
				$this->index();
				return false;
			}
			
			//入库数据member表
			$indata['account'] = $datas['username'];				//账户名
			$indata['saltcode'] = randcode(8);						//干扰码
			$indata['passhash'] = md5($datas['password'].$indata['saltcode']);	//new pw
			$indata['membertype'] = 'person'; 						//账户类型
			$indata['registerip'] = get_client_ip(); 				//注册ip
			$indata['registertime'] = time();						//注册时间
			$indata['areaid'] 	= $datas['areaid'];
			$indata['gradeid'] = 5;
			//memberinfo表
			$infodata['prettyname'] = $datas['prettyname'];
			$infodata['gender'] = $datas['gender'];
			$infodata['idnumber'] = $datas['idnumber'];
			$infodata['email'] = $datas['email'];
			//联系方式 只允许用手机 
			$infodata['mobile'] = $datas['relation'];
			
			//注册入库 table：member 、memberinfo
			$memberid = $this->ins('iok_member',$indata);
			//是否注册成功
			if($memberid > 0)
			{
				//注册人信息表入库（如果会员注册成功，而信息表没有插入成功，怎么处理呢？）
				$infodata['memberid'] = $memberid;
				$memberinfoid = $this->ins('iok_memberinfo',$infodata);
				//用户账户表信息
				$memberaccount['memberid'] = $memberid;
				$memberaccount['paypasshash'] = $indata['passhash'];
				$memberaccount['paysaltcode'] = $indata['saltcode'];
				$memberacc = $this->ins('iok_memberaccount',$memberaccount);
				UNSET($_SESSION ["mail_verify"]);
				redirect('/Login');
				
				
			}else{
				$errcode = '注册失败，请重试！';
				$this->mset($errcode,'total',$datas);
				$this->index();
				return false;
			}
		}else{
			$errcode = '请出入用户名！';
			$this->mset($errcode,'tusername',$datas);
			$this->index();
			return false;
		}
	}
		
	
	/* 老板的注册，暂留作参考 */
	public function register(){
		$tab_member = M('member');
		$tab_company = M('company');
		$tab_company_prove = M('company_prove');
		$tab_represent = M('represent');
		$finance = M('finance_credit');
		$tab_res_education = M('res_education');
		$tab_res_work = M('res_work');
		$file = $_GET['file'] ? $_GET['file'] : 'company';
		$this->assign("reg_file", $file);
		switch($file)
		{
			case 'repstation':
				if($_REQUEST['username'])
				{
					/* 匹配用户名是否合法 */
					if(!$this->check_name($_POST['username']))
					{
						$this->error('请规范输入用户名！');exit;
					}
					//判断验证码是否正确
					if($_SESSION['verify'] != md5($_POST['verify']))
					{
						$this->error('验证码错误！');exit;
					}
					//判断邮箱验证是否正确
					if($_SESSION['mail_verify'] != $_POST['emailcode'])
					{
						$this->error('邮箱验证码错误！');exit;
					}
					$res_u = $tab_member->where("username='".$_REQUEST['username']."'")->getField('userid');
					if($res_u){
						$this->error('帐号已存在！！');
					}
					//
					if($_POST['truename'] =="")
					{
						$this->error('请填写真实姓名！！');exit;
					}
					/* 判断地区合法性 wuzhijie 2013年10月13日 15:18:44 */
					if($_POST['areaid']){
						$areamodel = M('area');
						$area = $areamodel->where("areaid='".$_POST['areaid']."' and areaid!='' ")->getField('child');
						if($area!==false){
							if($area==1){
								$this->error('请选择精确到区县级的地区位置！');return false;
							}
						}else{
							$this->error('地区选择有错误，请重试！');return false;
						}
					}else{
						$this->error('请选择所在地区！');return false;
					}
					
					if($_POST['email'] =="")
					{
						$this->error('请填写邮箱！');exit;
					}
					if($_POST['idcard'] =="")
					{
						$this->error('请填身份证！');exit;
					}
					
					
					$res_m = $tab_member->where("mobile='".$_REQUEST['mobile']."'")->getField('userid');
					if($res_m){
						$this->error('该手机号码已经被人占用！！');
					}
					if($_POST['password']!=$_POST['cpassword']){
						$this->error('两次密码不一致！请重新输入！');
					}
					// edit by niuyufu
					// username\passport\email唯一索引
					$_POST['grade'] = '1';			//在这里区别的服务站与代表
					$_POST['regid'] = '15';
					$_POST['groupid'] = '15';
					$_POST['passport'] = $_POST['username'];
					$_POST['payword'] = md5(md5($_POST['password']));//站内支付密码与登录密码相同;					
					$_POST['regtime'] = time();
					$_POST['logintime'] = time();
					$_POST['loginip'] = get_client_ip(); //登录ip;
					//发送邮寄密码
					$epass=$_POST['password'];
					$_POST['password'] = md5(md5($_POST['password']));
					$_POST['regip'] = get_client_ip(); //注册ip;
					unset($_POST['grade']);
					unset($_POST['cpassword']);
					unset($_POST['regip']);
					unset($_POST['emailcode']);
					unset($_POST['submit']);
					$username = $_POST['username'];
					$tab_member->create();
					//$tab_member->add();
					//$rep_userid = $tab_member->getLastInsID();
					$rep_userid = $tab_member->add();
					if($rep_userid)
					{
						$_POST['userid'] = $rep_userid;
						$member = M('Member')->where("userid='".$userid."'")->find();
						$represent = array();
						$represent['iokid'] = onlyID();
						$represent['userid'] = $rep_userid;
						$represent['username'] = $_POST['username'];
						$represent['star'] = 0;
						$represent['grade'] = $_POST['grade'];
						$represent['state'] = 1;
						$represent['grade'] = 1;
						$tab_represent->create();
						$com_userid = $tab_represent->add($represent);
						//tracefile($com_userid);
						if($com_userid)
						{							
							$data = array('username'=>$username, 'amount'=>20, 'balance'=>20, 'addtime'=>$_POST['regtime'], 'reason'=>'注册奖励', 'note'=>$_POST['regip'], 'editor'=>'system');
							$tab_finance = $finance->data($data)->add();
							$_SESSION['userdata']['credit']=20;//更新session;
							$map['credit']=20;
							$tab_member->where("username='$username'")->data($map)->save();//更新member表中积分字段;
							/* 发送邮件提示注册成功 wuzhijie 2013年9月25日 09:21:45 */
							$this->reg_mail($_POST['email'],$_POST['username'],$epass,'station');
							$this->right('注册成功！','forward','/Public/login');										
						}else
						{
							$this->error('注册失败！');
						}
					}else
					{
						$this->error('注册失败！');
					}
				}else
				{
					$this->assign("file", $file);
					//seo 1009 xiaog
					$this->assign("title","商务服务站注册_我行网");
					$this->assign("keywords","");		
					$this->assign("description","");
					$this->display('register_repstation');
				}
				break;
			case 'represent':
				if($_REQUEST['username'])
				{
					//wuzhijie 2013年9月16日 17:54:28
					/* 匹配用户名是否合法 */
					if(!$this->check_name($_POST['username']))
					{
						$this->error('请规范输入用户名！');exit;
					}
					//判断验证码是否正确
					if($_SESSION['verify'] != md5($_POST['verify']))
					{
						$this->error('验证码错误！');exit;
					}
					//判断邮箱验证是否正确
					if($_SESSION['mail_verify'] != $_POST['emailcode'])
					{
						$this->error('邮箱验证码错误！');exit;
					}
					$res_u = $tab_member->where("username='".$_REQUEST['username']."'")->getField('userid');
					if($res_u){
						$this->error('帐号已存在！！');
					}
					
					//
					if($_POST['truename'] =="")
					{
						$this->error('请填写真实姓名！！');exit;
					}
					/* 判断地区合法性 wuzhijie 2013年10月13日 15:18:44 */
					if($_POST['areaid']){
						$areamodel = M('area');
						$area = $areamodel->where("areaid='".$_POST['areaid']."' and areaid!='' ")->getField('child');
						if($area!==false){
							if($area==1){
								$this->error('请选择精确到区县级的地区位置！');return false;
							}
						}else{
							$this->error('地区选择有错误，请重试！');return false;
						}
					}else{
						$this->error('请选择所在地区！');return false;
					}
					if($_POST['email'] =="")
					{
						$this->error('请填写邮箱！');exit;
					}
					if($_POST['idcard'] =="")
					{
						$this->error('请填身份证！');exit;
					}
					
					$res_m = $tab_member->where("mobile='".$_REQUEST['mobile']."'")->getField('userid');
					if($res_m){
						$this->error('该手机号码已经被人占用！！');
					}
					if($_POST['password']!=$_POST['cpassword']){
						$this->error('两次密码不一致！请重新输入！');
					}
					// edit by niuyufu
					// username\passport\email唯一索引
					$_POST['grade'] = '0';			//在这里区别的服务站与代表
					$_POST['regid'] = '15';
					$_POST['groupid'] = '15';
					$_POST['passport'] = $_POST['username'];
					$_POST['payword'] = md5(md5($_POST['password']));//站内支付密码与登录密码相同;					
					$_POST['regtime'] = time();
					$_POST['logintime'] = time();
					$_POST['loginip'] = get_client_ip(); //注册ip;
					//发送邮件密码
					$epass = $_POST['password'];
					$_POST['password'] = md5(md5($_POST['password']));
					$_POST['regip'] = get_client_ip(); //注册ip;
					unset($_POST['grade']);
					unset($_POST['cpassword']);
					unset($_POST['regip']);
					unset($_POST['emailcode']);
					unset($_POST['submit']);
					$username = $_POST['username'];
					$tab_member->create();
					//$tab_member->add();
					//$rep_userid = $tab_member->getLastInsID();
					$rep_userid = $tab_member->add();
					if($rep_userid)
					{	
						$_POST['userid'] = $rep_userid;
						$member = M('Member')->where("userid='".$userid."'")->find();
						$represent = array();
						$represent['iokid'] = onlyID();
						$represent['userid'] = $rep_userid;
						$represent['username'] = $_POST['username'];
						$represent['star'] = 0;
						$represent['grade'] = $_POST['grade'];
						$represent['state'] = 1;
						$tab_represent->create();
						$com_userid = $tab_represent->add($represent);
						//tracefile($com_userid);
						if($com_userid)
						{							
							$tab_represent->where("userid='".$rep_userid."'")->save("state=1"); //状态值
							$data = array('username'=>$username, 'amount'=>20, 'balance'=>20, 'addtime'=>$_POST['regtime'], 'reason'=>'注册奖励', 'note'=>$_POST['regip'], 'editor'=>'system');
							$tab_finance = $finance->data($data)->add();
							$_SESSION['userdata']['credit']=20;//更新session;
							$map['credit']=20;
							$tab_member->where("username='$username'")->data($map)->save();//更新member表中积分字段;
							
							/* 发送邮件提示注册成功 wuzhijie 2013年9月25日 09:21:45 */
							$this->reg_mail($_POST['email'],$_POST['username'],$epass,'represent');
								$message['title']='欢迎您成为我行网商务代表！';    //商务代表注册欢迎站内信;
								$message['touser'] = $username;
								$message['fromuser'] = 'system';
								$message['content'] = '<table cellpadding="0" cellspacing="0" width="600" style="margin:10px;font-family:Verdana,Arial;">
														<tr>
														<td style="background:#6B85DC;line-height:24px;font-weight:bold;color:#FFFFFF;">&nbsp;&nbsp;欢迎您入驻我行网!</td>
														</tr>
														<tr>
														<td style="border:#CCCCCC 1px solid;padding:20px 20px 20px 20px;line-height:200%;font-size:12px;">
														<strong>欢迎登录帐号为'.$username.'的会员成为我行网商务代表，我行网将与您一起实现共赢。<br/>
														请耐心等待【县级业务中心】为您审核。<br/>
														（如果您忘记了密码，请点击<a href="/Public/findpw">这里</a>找回）
														</td>
														</tr>
														<tr>
														<td style="background:#555555;padding:3px;font-size:12px;color:#FFFFFF;">&nbsp;&nbsp;请注意：此信件系 系统信使 自动发送，请勿直接回复。</td>
														</tr>
														</table>';
									$message['addtime'] = time();
									$message['ip'] = get_client_ip();
									$message['status'] = 3;
									M("message")->data($message)->add();
									M()->query( "UPDATE destoon_member SET message=message+1 WHERE username='".$username."'" );
									$_SESSION['userdata']['message']=$_SESSION['userdata']['message']+1;
							$this->right('注册成功！','forward','/Public/login');								
						}else
						{
							$this->error('注册失败！');
						}
					}else
					{
						$this->error('注册失败！');
					}
				}else
				{
					$this->assign("file", $file);
					//seo 1009 xiaog
					$this->assign("title","商务代表注册_我行网");
					$this->assign("keywords","");		
					$this->assign("description","");
					$this->display('register_represent');
				}
				break;
		}
	}




}
