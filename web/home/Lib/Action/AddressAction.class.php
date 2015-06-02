<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class AddressAction extends CommonAction
{
	/* 个人 */
	public function profilelist(){
		$listsql = "
			select * from iok_memberaddr 
			where memberid ='".$_SESSION['member']['id']."' and deleted = 0 
			order by listorder asc 
		";
		$datalist = $this->arr($listsql);
		$this->assign('lists',$datalist);
		$this->display('profilelist');
	}
	/* add address */
	public function profileadd(){
		//提示信息输出
		$postdata = $this->mget();
		$this->assign('postdata',$postdata);
		$this->display('profileadd');
	}
	/* add insert*/
	public function profileaddsave(){
		$data['memberid'] = $_SESSION['member']['id'];
		$data['isdefault'] = 0;
		$data['areaid'] = getvar('areaid');
		$data['address'] = getvar('address');
		$data['postcode'] = getvar('postcode');
		$data['prettyname'] = getvar('prettyname');
		$data['mobile'] = getvar('mobile');
		$data['telephone'] = getvar('telephone');
		$data['listorder'] = getvar('listorder') ? getvar('listorder') : 0 ;
		$data['note'] = getvar('note');
		$data['enabled'] = 1;
		$data['deleted'] = 0;
		$data['addtime'] = time();
		
		/* 检测数据合法性 */
		if(empty($data['memberid'])){
			$errcode = '你还未登录，请先登录！';
			$this->mset($errcode,'error_top',$data);
			$this->profileadd();
			return false;
		}
		if(empty($data['areaid'])){
			$errcode = '请选择所在地区！';
			$this->mset($errcode,'tareaid',$data);
			$this->profileadd();
			return false;
		}
		if(empty($data['address'])){
			$errcode = '请填写详细地址！';
			$this->mset($errcode,'taddress',$data);
			$this->profileadd();
			return false;
		}
		if(!is_numeric($data['postcode']) || strlen($data['postcode'])!=6 ){
			$errcode = '邮编格式不正确！';
			$this->mset($errcode,'tpostcode',$data);
			$this->profileadd();
			return false;
		}
		if(empty($data['prettyname'])){
			$errcode = '请输入真实姓名！';
			$this->mset($errcode,'tprettyname',$data);
			$this->profileadd();
			return false;
		}
		if(empty($data['mobile'])){
			$errcode = '请输入手机号码！';
			$this->mset($errcode,'tmobile',$data);
			$this->profileadd();
			return false;
		}
		if(!$this->check_mobile($data['mobile'])){
			$errcode = '请输入正确的手机号码！';
			$this->mset($errcode,'tmobile',$data);
			$this->profileadd();
			return false;
		}
		
		/* 入库 */
		$res = $this->ins('iok_memberaddr',$data);
		if($res){
			redirect('/Address/profilelist');
		}else{
			$errcode = '添加失败！';
			$this->mset($errcode,'error_top',$data);
			$this->profileadd();
			return false;
		}
	}
	/* edit address */
	Public function Profileedit(){
		$this->mget();
		$id = getvar('id');
		$editsql = "
			select * from iok_memberaddr 
			where memberid ='".$_SESSION['member']['id']."' and id ='".$id."' 
		";
		$_SESSION['member']['editaddrid'] = $id;
		$datalist = $this->rec($editsql);
		$this->assign('lists',$datalist);
		$this->display('profileedit');
	}
	/* edit save */
	public function profileeditsave(){
		$data['addrid'] = getvar('id');
		if($data['addrid']!=$_SESSION['member']['editaddrid']){
			$errcode = '数据有误，修改失败！';
			$this->mset($errcode,'error_top',$data);
			$this->profileedit();
			return false;
		}
		$data['areaid'] = getvar('areaid');
		$data['address'] = getvar('address');
		$data['postcode'] = getvar('postcode');
		$data['prettyname'] = getvar('prettyname');
		$data['mobile'] = getvar('mobile');
		$data['telephone'] = getvar('telephone');
		$data['listorder'] = getvar('listorder') ? getvar('listorder') : 0 ;
		$data['note'] = getvar('note');
		$data['updatetime'] = time();
		/* 检测数据合法性 */
		if(empty($_SESSION['member']['id'])){
			$errcode = '你还未登录，请先登录！';
			$this->mset($errcode,'error_top',$data);
			$this->Profileedit();
			return false;
		}
		if(empty($data['areaid'])){
			$errcode = '请选择所在地区！';
			$this->mset($errcode,'tareaid',$data);
			$this->Profileedit();
			return false;
		}
		if(empty($data['address'])){
			$errcode = '在这里填写详细地址！';
			$this->mset($errcode,'taddress',$data);
			$this->Profileedit();
			return false;
		}
		if(!is_numeric($data['postcode']) || strlen($data['postcode'])!=6 ){
			$errcode = '这里要输入正确的邮编噢！';
			$this->mset($errcode,'tpostcode',$data);
			$this->Profileedit();
			return false;
		}
		if(empty($data['prettyname'])){
			$errcode = '要正确输入真实姓名！';
			$this->mset($errcode,'tprettyname',$data);
			$this->Profileedit();
			return false;
		}
		if(empty($data['mobile'])){
			$errcode = '这里输入有误噢~';
			$this->mset($errcode,'tmobile',$data);
			$this->Profileedit();
			return false;
		}
		if(!$this->check_mobile($data['mobile'])){
			$errcode = '请输入正确的手机号码！';
			$this->mset($errcode,'tmobile',$data);
			$this->Profileedit();
			return false;
		}
		
		/* 入库 */
		$updatesql = "
			update iok_memberaddr 
			set 
				areaid = '".$data['areaid']."' ,
				address = '".$data['address']."' ,
				postcode = '".$data['postcode']."' ,
				prettyname = '".$data['prettyname']."' ,
				mobile = '".$data['mobile']."' ,
				telephone = '".$data['telephone']."' ,
				listorder = '".$data['listorder']."' ,
				note = '".$data['note']."' ,
				updatetime = '".$data['updatetime']."' 
			where 
				id = '".$_SESSION['member']['editaddrid']."' and memberid ='".$_SESSION['member']['id']."' 
		";
		$res = $this->exec($updatesql);
		if($res){
			unset($_SESSION['member']['editaddrid']);
			redirect('/Address/profilelist');
		}else{
			$errcode = '修改失败！';
			$this->mset($errcode,'error_top',$data);
			$this->profileedit();
			return false;
		}
	}
	
	
	/* 企业 */
	Public function companylist(){
		$listsql = "
			select * from iok_memberaddr 
			where memberid ='".$_SESSION['member']['id']."' and deleted = 0 
			order by listorder asc,addtime desc 
		";
		$datalist = $this->arr($listsql);
		$this->assign('lists',$datalist);
		$this->display('companylist');
	}
	/* add show */
	public function companyadd(){
		//提示信息输出
		$postdata = $this->mget();
		$this->assign('postdata',$postdata);
		$this->display('companyadd');
	}
	/* add insert*/
	public function companyaddsave(){
		$data['memberid'] = $_SESSION['member']['id'];
		$data['isdefault'] = 0;
		$data['areaid'] = getvar('areaid');
		$data['address'] = getvar('address');
		$data['postcode'] = getvar('postcode');
		$data['prettyname'] = getvar('prettyname');
		$data['mobile'] = getvar('mobile');
		$data['telephone'] = getvar('telephone');
		$data['listorder'] = getvar('listorder') ? getvar('listorder') : 0 ;
		$data['note'] = getvar('note');
		$data['enabled'] = 1;
		$data['deleted'] = 0;
		$data['addtime'] = time();
		
		/* 检测数据合法性 */
		if(empty($data['memberid'])){
			$errcode = '你还未登录，请先登录！';
			$this->mset($errcode,'error_top',$data);
			$this->companyadd();
			return false;
		}
		if(empty($data['areaid'])){
			$errcode = '请选择所在地区！';
			$this->mset($errcode,'tareaid',$data);
			$this->companyadd();
			return false;
		}
		if(empty($data['address'])){
			$errcode = '请填写详细地址！';
			$this->mset($errcode,'taddress',$data);
			$this->companyadd();
			return false;
		}
		if(!is_numeric($data['postcode']) || strlen($data['postcode'])!=6 ){
			$errcode = '邮编格式不正确！';
			$this->mset($errcode,'tpostcode',$data);
			$this->companyadd();
			return false;
		}
		if(empty($data['prettyname'])){
			$errcode = '请输入真实姓名！';
			$this->mset($errcode,'tprettyname',$data);
			$this->companyadd();
			return false;
		}
		if(empty($data['mobile'])){
			$errcode = '请输入手机号码！';
			$this->mset($errcode,'tmobile',$data);
			$this->companyadd();
			return false;
		}
		if(!$this->check_mobile($data['mobile'])){
			$errcode = '请输入正确的手机号码！';
			$this->mset($errcode,'tmobile',$data);
			$this->companyadd();
			return false;
		}
		
		
		
		/* 入库 */
		$res = $this->ins('iok_memberaddr',$data);
		if($res){
			redirect('/Address/companylist');
		}else{
			$errcode = '添加失败！';
			$this->mset($errcode,'error_top',$data);
			$this->companyadd();
			return false;
		}
	}
	/* edit address */
	public function companyedit(){
		$this->mget();
		$id = getvar('id');
		$editsql = "
			select * from iok_memberaddr 
			where memberid ='".$_SESSION['member']['id']."' and id ='".$id."' 
		";
		$_SESSION['member']['editaddrid'] = $id;
		$datalist = $this->rec($editsql);
		$this->assign('lists',$datalist);
		$this->display('companyedit');
	}
	/* edit save */
	public function companyeditsave(){
		$data['addrid'] = getvar('id');
		if($data['addrid']!=$_SESSION['member']['editaddrid']){
			$errcode = '数据有误，修改失败！';
			$this->mset($errcode,'error_top',$data);
			$this->companyedit();
			return false;
		}
		$data['areaid'] = getvar('areaid');
		$data['address'] = getvar('address');
		$data['postcode'] = getvar('postcode');
		$data['prettyname'] = getvar('prettyname');
		$data['mobile'] = getvar('mobile');
		$data['telephone'] = getvar('telephone');
		$data['listorder'] = getvar('listorder') ? getvar('listorder') : 0 ;
		$data['note'] = getvar('note');
		$data['updatetime'] = time();
		/* 检测数据合法性 */
		if(empty($_SESSION['member']['id'])){
			$errcode = '你还未登录，请先登录！';
			$this->mset($errcode,'error_top',$data);
			$this->companyedit();
			return false;
		}
		if(empty($data['areaid'])){
			$errcode = '请选择所在地区！';
			$this->mset($errcode,'tareaid',$data);
			$this->companyedit();
			return false;
		}
		if(empty($data['address'])){
			$errcode = '在这里填写详细地址！';
			$this->mset($errcode,'taddress',$data);
			$this->companyedit();
			return false;
		}
		if(!is_numeric($data['postcode']) || strlen($data['postcode'])!=6 ){
			$errcode = '这里要输入正确的邮编噢！';
			$this->mset($errcode,'tpostcode',$data);
			$this->companyedit();
			return false;
		}
		if(empty($data['prettyname'])){
			$errcode = '要正确输入真实姓名！';
			$this->mset($errcode,'tprettyname',$data);
			$this->companyedit();
			return false;
		}
		if(empty($data['mobile'])){
			$errcode = '这里输入有误噢~';
			$this->mset($errcode,'tmobile',$data);
			$this->companyedit();
			return false;
		}
		if(!$this->check_mobile($data['mobile'])){
			$errcode = '请输入正确的手机号码！';
			$this->mset($errcode,'tmobile',$data);
			$this->companyedit();
			return false;
		}
		
		/* 入库 */
		$updatesql = "
			update iok_memberaddr 
			set 
				areaid = '".$data['areaid']."' ,
				address = '".$data['address']."' ,
				postcode = '".$data['postcode']."' ,
				prettyname = '".$data['prettyname']."' ,
				mobile = '".$data['mobile']."' ,
				telephone = '".$data['telephone']."' ,
				listorder = '".$data['listorder']."' ,
				note = '".$data['note']."' ,
				updatetime = '".$data['updatetime']."' 
			where 
				id = '".$_SESSION['member']['editaddrid']."' and memberid ='".$_SESSION['member']['id']."' 
		";
		$res = $this->exec($updatesql);
		if($res){
			unset($_SESSION['member']['editaddrid']);
			redirect('/Address/companylist');
		}else{
			$errcode = '修改失败！';
			$this->mset($errcode,'error_top',$data);
			$this->companyedit();
			return false;
		}
	}
	
	/* delete address */
	public function deleteaddress(){
		$id= getvar('id');
		$deletetime = time();
		$sql = "
			update iok_memberaddr 
			set 
				deleted = 1,
				deletetime = ".$deletetime." 
			where 
				id ='".$id."' and memberid = '".$_SESSION['member']['id']."' 
			
		";
		$res = $this->exec($sql);
		if($res){
			$this->companylist();
		}else{
			$this->companylist();
		}
		
	}
	
	
	
}
?>