<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class CompanyAction extends CommonAction
{
	//企业首页
	public function index()
	{
		/* 
		$memmodel = M('member');
		$Company = M('company');
		$Message = M('message');
		$Represent = M('represent');
		$Formo = M('mall_order');
		$Mall = M('mall');
		$Buy = M('buy');
		$Announce =M(announce);
		*/
		$sql_com = "
			select 
				m.id,
				m.account,
				m.membertype,
				m.gradeid,
				m.areaid,
				m.servicestaffid,
				m.logintime,
				m.status as repstatus,
				i.star,
				i.status as membstatus,
				i.website,
				i.mynote,
				c.company,
				c.companytypeid,
				c.business,
				a.totalmoney
			from 
				iok_member m,iok_memberinfo i,iok_membercompany c,iok_memberaccount a 
			where 
				m.id=i.memberid and m.id = c.memberid and m.id = '".$_SESSION['member']['id']."' 
		";
		//$companydata = $this->rec($sql_com);
		$this->assign('companydata',$companydata);
		
		/* 我是卖家 */
		$sql_seller = "
			select 
				count(o.id) as cnt,
				o.status 
			from 
				iok_order o inner join iok_product p 
				on o.productid=p.id 
			where 
				o.sellerid='".$_SESSION['member']['id']."' 
			group by o.status 
			order by o.status asc 
		";
		//$sell_mydeal = $this->exec($sql_seller);
		
		/* 我是买家 */
		$sql_buyer = "
			select 
				count(o.id) as cnt,
				o.status 
			from 
				iok_order o inner join iok_product p 
				on o.productid=p.id 
			where 
				o.buyerid='".$_SESSION['member']['id']."' 
			group by o.status 
			order by o.status asc 
		";
		//$buy_mydeal = $this->exec($sql_buyer);
		
		/* 我的供应 */
		$sql_sell = "
			select 
				count(p.id) as cnt,
				p.status
			from 
				iok_product p inner join iok_member m 
				on p.memberid = m.id 
			where 
				p.memberid = '".$_SESSION['member']['id']."'
			group by p.status 
			order by p.status asc 
		";
		/* $sellcount = $this->exec($sql_sell); */
		
		/* 我的求购 */
		/*$sql_buy = "
			select 
				count(b.id) as cnt,
				b.status
			from 
				iok_buy b inner join iok_member m 
				on b.memberid = m.id 
			where 
				b.memberid = '".$_SESSION['member']['id']."'
			group by b.status 
			order by b.status asc 
		";
		$buycount = $this->exec($sql_buy);
		*/
		
		
		$this->display('index');
	}
	//资料
	public function edit(){
		$postdata = $this->mget();
		
		
		$datasql = "
			select 
				m.areaid,
				c.company,
				c.companytypeid,
				c.categoryids,
				c.mode,
				c.capital,
				c.regunit,
				c.scale,
				c.productsell,
				c.productbuy,
				c.business
			from 
				iok_member m,iok_membercompany c
			where 
				m.id = c.memberid and c.memberid = '".$_SESSION['member']['id']."' 
		";
		$company = $this->rec($datasql);
		$this->assign('company',$company);
		
		/* 公司类型 */
		$companytypes = $this->arr("select id,prettyname as typename from iok_membercompanytype order by id asc ");
		$this->assign('companytypes',$companytypes);
		/* 主营行业  */
		$cates = $company['categoryids'] ? explode(',', trim($company['categoryids'],',')) : array();
		/* 只有一个',' 会产生值为空的非空数组，用 array_filter 去掉 */
		if(count(array_filter($cates))){
			$this->assign('cates',$cates);
		}
		
		/* 预定义 经营模式	*/
		$com_modes = $this->arr("
			select 
				tablename,
				fieldname,
				name,
				prettyname 
			from 
				iok_dictionary 
			where 
				tablename='iok_membercompany' 
				and fieldname='mode' 
		");
		foreach($com_modes as $val){
			$COM_MODE[$val['name']] = $val['prettyname'];
		}
		$mode_check = addcheckbox($COM_MODE, 'mode[]', $company['mode'], 'onclick="javascript:check_mode(this,2);"', 1);
		$this->assign('mode_check',$mode_check);
		
		/* 预定义 公司规模 */
		$com_sizes = $this->arr("
			select 
				tablename,
				fieldname,
				name,
				prettyname 
			from 
				iok_dictionary 
			where 
				tablename='iok_membercompany' 
				and fieldname='scale' 
		");
		foreach($com_sizes as $vals){
			$COM_SIZE[$vals['name']] = $vals['prettyname'];
		}
		$this->assign('COM_SIZE',$COM_SIZE);
		
		/* 货币 */
		$com_units = $this->arr("
			select 
				tablename,
				fieldname,
				name,
				prettyname 
			from 
				iok_dictionary 
			where 
				tablename='iok_membercompany' 
				and fieldname='regunit' 
		");
		foreach($com_units as $valu){
			$MONEY_UNIT[$valu['name']] = $valu['prettyname'];
		}
		$this->assign('MONEY_UNIT',$MONEY_UNIT);
		
		/* 菜单按钮显示 */
		$this->assign('way','one');
		$this->display("userData_data");
	}
	public function submit(){
	
		$savememb["areaid"] = getvar('areaid');
		
		$savecomp["company"] = getvar('company');
		$savecomp["companytypeid"] = getvar('type');
		$savecomp["categoryids"] = getvar('categoryids');
		$savecomp["business"] = getvar('business');
		/* 经营模式 */
		$modes = getVar('mode',array(),'array');
		if($modes){
			if(count($modes)>2){
				$errcode = '经营模式最多可选2种！';
				$this->mset($errcode,'tmode');
				$this->edit();
				return false;
			}
			$savecomp['mode'] = implode(',',$modes);
		}
		$savecomp["scale"] = getvar('scale');
		$savecomp["regunit"] = getvar('regunit');
		$savecomp["capital"] = getvar('capital');
		$savecomp["productsell"] = getvar('productsell');
		$savecomp["productbuy"] = getvar('productbuy');
		/* 验证信息 */
		
		if(empty($savecomp["company"])){
			$errcode = '请填写公司名！';
			$this->mset($errcode,'tcompany');
			$this->edit();
			return false;
		}
		
		if(empty($savecomp["companytypeid"])){
			$errcode = '请选择公司类型！';
			$this->mset($errcode,'ttype');
			$this->edit();
			return false;
		}
		/* areaid */
		if(empty($savememb["areaid"])){
			$errcode = '请选择地区！';
			$this->mset($errcode,'tareaid');
			$this->edit();
			return false;
		}
		
		if(empty($savecomp["categoryids"]) || $savecomp["categoryids"]==','){
			$errcode = '请选择主营行业！';
			$this->mset($errcode,'tcate');
			$this->edit();
			return false;
		}
		
		if(strlen($savecomp["business"]) < 2 || strlen($savecomp["business"]) > 50){
			$errcode = '经营范围内容保持在2-50个字符之间';
			$this->mset($errcode,'tbus');
			$this->edit();
			return false;
		}
		
		/* 入库 */
		$updatesql = "
			update iok_membercompany 
			set 
				company='".$savecomp["company"]."',
				companytypeid='".$savecomp["companytypeid"]."',
				categoryids='".$savecomp["categoryids"]."',
				mode='".$savecomp["mode"]."',
				scale='".$savecomp["scale"]."',
				regunit='".$savecomp["regunit"]."',
				capital='".$savecomp["capital"]."',
				productsell='".$savecomp["productsell"]."',
				productbuy='".$savecomp["productbuy"]."',
				business='".$savecomp["business"]."'
			where 
				memberid='".$_SESSION['member']['id']."' 
		";
		$result = $this->exec($updatesql);
		$resultt = $this->exec("update iok_member set areaid='".$savememb["areaid"]."' where id='".$_SESSION['member']['id']."' ");
		redirect('/Company/edit');
		return false;
	}
	//联系方式
	public function editcontact(){
		
		$relationsql = "
			select 
				prettyname,
				gender,
				/* department, */
				/* position, */
				mobile,
				email,
				qq,
				addrareaid,
				address
				/* telephone, */
				/* postcode, */
				/* fax, */
				/* website */
			from 
				iok_memberinfo
			where 
				memberid='".$_SESSION['member']['id']."' 
			
		";
		/* error information */
		$relationerror = $this->mget();
		if(!$relationerror){
			//联系方式数据
			$relation_data = $this->rec($relationsql);
			$this->assign('relation_data',$relation_data);
		}else{
			$this->assign('relation_data',$relationerror);
		}
		/* 菜单按钮显示 */
		$this->assign('way','two');
		$this->display("userData_relat");
	}
	public function submitcontact(){
		
		$relatdatas['prettyname'] = getvar('prettyname');
		$relatdatas['gender'] = getvar('gender');
		$relatdatas['department'] = getvar('department');
		$relatdatas['position'] = getvar('position');
		$relatdatas['mobile'] = getvar('mobile');
		$relatdatas['email'] = getvar('email');
		$relatdatas['qq'] = getvar('qq');
		$relatdatas['addrareaid'] = getvar('addrareaid');
		$relatdatas['address'] = getvar('address');
		$relatdatas['postcode'] = getvar('postcode');
		$relatdatas['telephone'] = getvar('telephone');
		$relatdatas['fax'] = getvar('fax');
		$relatdatas['website'] = getvar('website');
		/* 验证数据 */
		if(empty($relatdatas['prettyname'])){
			$this->mset('姓名不能为空！','tprettyname',$relatdatas);
			$this->editcontact();
			return false;
		}
		if(empty($relatdatas['gender'])){
			$this->mset('请选择您的性别！','tgender',$relatdatas);
			$this->editcontact();
			return false;
		}
		if(empty($relatdatas['mobile'])){
			$this->mset('请正确填写您的手机号！','tmobile',$relatdatas);
			$this->editcontact();
			return false;
		}
		if(!$this->check_mobile($relatdatas['mobile'])){
			$this->mset('请正确填写您的手机号！','tmobile',$relatdatas);
			$this->editcontact();
			return false;
		}
		if(empty($relatdatas['email'])){
			$this->mset('请填写您的 email 邮箱！','temail',$relatdatas);
			$this->editcontact();
			return false;
		}
		if(!preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',$relatdatas['email'])){
			$this->mset('请按正确格式填写您的email！','temail',$relatdatas);
			$this->editcontact();
			return false;
		}
		if(empty($relatdatas['addrareaid'])){
			$this->mset('请选择公司地址！','taddrareaid',$relatdatas);
			$this->editcontact();
			return false;
		}
		if(empty($relatdatas['address'])){
			$this->mset('请填写详细地址！','taddress',$relatdatas);
			$this->editcontact();
			return false;
		}
		if(empty($relatdatas['telephone'])){
			$this->mset('请填写公司电话！','ttelephone',$relatdatas);
			$this->editcontact();
			return false;
		}
		if(!$this->check_telephone($relatdatas['telephone'])){
			$this->mset('请正确填写公司电话！','ttelephone',$relatdatas);
			$this->editcontact();
			return false;
		}
		
		/* 入库 */
		$updatesql = "
			update iok_memberinfo 
			set 
				prettyname='".$relatdatas["prettyname"]."',
				gender='".$relatdatas["gender"]."',
				department='".$relatdatas["department"]."',
				position='".$relatdatas["position"]."',
				mobile='".$relatdatas["mobile"]."',
				email='".$relatdatas["email"]."',
				qq='".$relatdatas["qq"]."',
				addrareaid='".$relatdatas["addrareaid"]."',
				address='".$relatdatas["address"]."',
				postcode='".$relatdatas["postcode"]."',
				telephone='".$relatdatas["telephone"]."',
				fax='".$relatdatas["fax"]."',
				website='".$relatdatas["website"]."'
			where 
				memberid='".$_SESSION['member']['id']."' 
		";
		$result = $this->exec($updatesql);
		redirect('/Company/editcontact');
		return false;
	}
	//介绍
	public function editintro(){
		
		$contentsql = "
			select 
				content
			from 
				iok_membercompany
			where 
				memberid='".$_SESSION['member']['id']."' 
			
		";
		/* 错误，返回数据 */
		$postdata = $this->mget();
		if(!$postdata){
			/* 无错误，返回原数据 */
			$relation_data = $this->rec($contentsql);
			$this->assign('content_data',$relation_data);
		}else{
			/* 有错误，返回错误数据 */
			$this->assign('content_data',$postdata);
		}
		
		/* 菜单按钮显示 */
		$this->assign('way','three');
		$this->display("userData_cont");
	}
	public function submitintro(){
		$content = getVar('content');
		if(empty($content)){
			$this->mset('公司简介不能为空！','tcontent',$content);
			$this->editintro();
			return false;
		}
		/* 入库 */
		$updatesql = "
			update iok_membercompany
			set 
				content='".$content."'
			where 
				memberid='".$_SESSION['member']['id']."' 
		";
		$result = $this->exec($updatesql);
		redirect('/Company/editintro');
		
	}
	//对公账号
	public function editbank(){
		$returndata = $this->mget();
		$this->assign('returndata',$returndata);
		$data_acc = $this->rec("select bankname,bankareaid,banktruename,bankaccount from iok_memberaccount where memberid ='".$_SESSION['member']['id']."' ");
		$this->assign('data_acc',$data_acc);
		$this->display('editbank');
	}
	public function submitbank(){
		$data['bankname'] = getvar('bankname');
		$data['bankareaid'] = getvar('bankareaid');
		$data['banktruename'] = $_SESSION['member']['company'];
		$data['bankaccount'] = getvar('bankaccount');
		
		if(empty($data['bankname'])){
			$error = "开户行不能为空";
			$this->mset($error,'tbankname',$data);
			$this->editbank();
			return false;
		}elseif(empty($data['bankareaid'])){
			$error = "请选择开户地区";
			$this->mset($error,'tbankareaid',$data);
			$this->editbank();
			return false;
		}elseif(empty($data['bankaccount'])){
			$error = "收款账号不能为空";
			$this->mset($error,'tbankaccount',$data);
			$this->editbank();
			return false;
		}else{
			if(!empty($_SESSION['member']['id'])){
				$result = $this->exec("
					update iok_memberaccount 
					set 
						bankname='".$data['bankname']."',
						bankareaid='".$data['bankareaid']."',
						banktruename='".$data['banktruename']."',
						bankaccount='".$data['bankaccount']."' 
					where 
						memberid='".$_SESSION['member']['id']."' 
				");
				redirect('/Company/editbank');
			}else{
				redirect('/Login');
			}
			return false;
		}
	}
	
	/* 密码管理 wuzhijie 2013年12月19日 10:28:48 */
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
			redirect('/Company/paypass');
			//$this->paypass();
			return false;
		}else{
			$errcode = '密码修改失败，请联系客服，咨询原因！';
			$this->mset($errcode,'error');
			$this->paypass();
			return false;
		}
		
	}
	
	
	
}
?>