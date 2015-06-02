<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class ProfileAction extends CommonAction
{
	//个人首页
	public function index()
	{
	
		$this->display();
	}
	//资料
	public function edit(){
		$postdata = $this->mget();
		$this->assign('postdata',$postdata);
		
		$datasql = "
			select 
				m.id,
				m.account,
				m.membertype,
				m.gradeid,
				m.agentareaid,
				m.areaid,
				i.idnumber,
				i.prettyname,
				i.gender,
				i.mobile,
				i.email,
				i.addrareaid,
				i.address
			from iok_member m,iok_memberinfo i 
			where m.id=i.memberid and m.id='".$_SESSION['member']['id']."' 
		";
		$data = $this->rec($datasql);
		$this->assign('memberinfo',$data);
		$sql_proof = "
			select 
				prooftypeid,
				imageurl,
				uploadid 
			from 
				iok_memberproof 
			where 
				memberid = '".$_SESSION['member']['id']."' 
			order by prooftypeid asc limit 2 
		";
		$proofdata = $this->arr($sql_proof);
		$this->assign('proofdata',$proofdata);
		
		$this->display('edit');
	}
	public function submit(){
		//info
		$data['prettyname'] = getvar('prettyname');
		$data['gender'] = getvar('gender');
		$data['email'] = getvar('email');
		$data['mobile'] = getvar('mobile');
		$data['idnumber'] = getvar('idnumber');
		$data['addrareaid'] = getvar('addrareaid');
		//真实姓名
		if(!preg_match('/^[\x{4e00}-\x{9fa5}]+$/u',$data['prettyname'])){
			$errcode = '请用您的中文名字！';
			$this->mset($errcode,'total',$data);
			$this->edit();
			return false;
		}
		//手机号
		if(!$this->check_mobile($data['mobile'])){
			$errcode = '请正确输入您的手机号码！';
			$this->mset($errcode,'total',$data);
			$this->edit();
			return false;
		}
		//电子邮箱
		if(!preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',$data['email'])){
			$errcode = '请填写真确的邮箱地址！';
			$this->mset($errcode,'total',$data);
			$this->edit();
			return false;
		}
		//身份证
		/* if(empty($data['idnumber'])){
			$errcode = '请填写身份证号！';
			$this->mset($errcode,'tidnumber',$data);
			$this->index();
			return false;
		} */
		$sql_up = "
			update 
				iok_memberinfo 
			set 
				prettyname='".$data['prettyname']."' ,
				gender ='".$data['gender']."' ,
				email ='".$data['email']."',
				mobile ='".$data['mobile']."',
				idnumber ='".$data['idnumber']."',
				addrareaid ='".$data['addrareaid']."' 
			where memberid ='".$_SESSION['member']['id']."' 
		";
		/* 更新 */
		$result = $this->exec($sql_up);

		//proof
		$proofp['memberid'] = $_SESSION['member']['id'];
		$proofp['prooftypeid'] = 1;
		$proofp['imageurl'] = getvar('idcart_1');
		$proofp['addtime'] = time();
		
		$proofa['memberid'] = $_SESSION['member']['id'];
		$proofa['prooftypeid'] = 2;
		$proofa['imageurl'] = getvar('idcart_2');
		$proofa['addtime'] = time();
		$exists = $this->res("select count(id) from iok_memberproof where memberid='".$_SESSION['member']['id']."' ");
		if($exists){
			$proofres1 = $this->exec("update iok_memberproof set imageurl='".$proofp['imageurl']."',updatetime=".time().",updateuserid='".$_SESSION['member']['id']."' where memberid='".$_SESSION['member']['id']."' and prooftypeid=1 ");
			$proofres2 = $this->exec("update iok_memberproof set imageurl='".$proofa['imageurl']."',updatetime=".time().",updateuserid='".$_SESSION['member']['id']."' where memberid='".$_SESSION['member']['id']."' and prooftypeid=2 ");
		}else{
			$proofres1 = $this->ins('iok_memberproof',$proofp);
			$proofres2 = $this->ins('iok_memberproof',$proofa);
		}
		
		redirect('/Profile/edit');
		
	}
	/* 密码管理 wuzhijie 2013年12月24日 10:36:29 */
	public function passwd(){
		$getdata = $this->mget();
		$this->display('passwd');
	}
	public function passwdsubmit(){
		$oldpassword = getvar('oldpassword');
		$newpassword = getvar('newpassword');
		$cnewpassword = getvar('cnewpassword');
		$oldpass = $this->res("select passhash from iok_member where id='".$_SESSION['member']['id']."' ");
		$postoldpass = md5($oldpassword.$_SESSION['member']['saltcode']);
		/* 匹配原密码 */
		if($postoldpass!=$oldpass){
			$errcode = '原密码错误！';
			$this->mset($errcode,'error');
			$this->passwd();
			return false;
		}
		/* 新密码与原密码相同 */
		if($oldpassword==$newpassword){
			$errcode = '新密码与原密码相同，未修改！';
			$this->mset($errcode,'error');
			$this->passwd();
			return false;
		}
		//检测密码是否符合规则
		if(strlen($newpassword)<6 || strlen($newpassword)>20){
			$errcode = '密码长度在6-20之间！';
			$this->mset($errcode,'error');
			$this->passwd();
			return false;
		}
		if($cnewpassword!==$newpassword){
			$errcode = '两次密码需保持一致！';
			$this->mset($errcode,'error');
			$this->passwd();
			return false;
		}
		$intnewpass = md5($newpassword.$_SESSION['member']['saltcode']);
		$res = $this->exec("update iok_member set passhash = '".$intnewpass."' where id='".$_SESSION['member']['id']."' ");
		if($res){
			redirect("/Misc/logout");
			return false;
		}else{
			$errcode = '密码修改失败，请联系客服，咨询原因！';
			$this->mset($errcode,'error');
			$this->passwd();
			return false;
		}
		
	}
	/* 支付密码 */
	public function paypass(){
		$getdata = $this->mget();
		$this->display('paypass');
	}
	public function paypasssubmit(){
		$oldpassword = getvar('oldpayword');
		$newpassword = getvar('newpayword');
		$cnewpassword = getvar('cnewpayword');
		$oldpass = $this->res("select paypasshash from iok_memberaccount where memberid='".$_SESSION['member']['id']."' ");
		$postoldpass = md5($oldpassword.$_SESSION['member']['saltcode']);
		/* 匹配原密码 */
		if($postoldpass!=$oldpass){
			$errcode = '原密码错误！';
			$this->mset($errcode,'error');
			$this->paypass();
			return false;
		}
		/* 新密码与原密码相同 */
		if($oldpassword==$newpassword){
			$errcode = '新密码与原密码相同，未修改！';
			$this->mset($errcode,'error');
			$this->paypass();
			return false;
		}
		//检测密码是否符合规则
		if(strlen($newpassword)<6 || strlen($newpassword)>20){
			$errcode = '密码长度在6-20之间！';
			$this->mset($errcode,'error');
			$this->paypass();
			return false;
		}
		if($cnewpassword!==$newpassword){
			$errcode = '两次密码需保持一致！';
			$this->mset($errcode,'error');
			$this->paypass();
			return false;
		}
		$intnewpass = md5($newpassword.$_SESSION['member']['saltcode']);
		$res = $this->exec("update iok_memberaccount set paypasshash = '".$intnewpass."' where memberid='".$_SESSION['member']['id']."' ");
		if($res){
			$this->paypass();
			return false;
		}else{
			$errcode = '密码修改失败，请联系客服，咨询原因！';
			$this->mset($errcode,'error');
			$this->paypass();
			return false;
		}
		
	}
	
	/* 个人帐号设置 */
	public function editbank(){
		echo 123456789;
	}
	
	
	
}
?>