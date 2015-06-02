<?php
	class AjaxAction extends CommonAction {
		/* wuzhijie 2014年1月7日 15:25:37 */
		public function get_category($moduleid){
			$moduleid = $moduleid ? $moduleid : $this->_param('moduleid');	//模块ID
			$catid = getvar('catid');		//分类ID
			$ischilds = getvar('ischilds');	//是否要查寻子类信息
			$condition = "moduleid = ".$moduleid;
			
			//如果catid存在，那就查询当前分类的单条数据，否则查寻所有顶级分类的列表信息
			if($catid){
				$condition .= " and catid = ".$catid;
			}else{
				$condition .= " and parentid = 0";
			}
			
			$category = M("category");
			$list = $category->where($condition)->field('catid,catname,parentid')->select();
			
			//是否查询子类
			if($ischilds){				
				$condition = "parentid = ".$list[0]['catid'];
				$list = $category->where($condition)->field('catid,catname,parentid')->select();
			}
			
			//是否以Ajax方式输出
			if(IS_AJAX){
				if($list){
					$this->ajaxReturn($list,'success',1);
				}else{
					$this->ajaxReturn($list,'error',0);
				}
			}else{
				return $list;
			}			
		}
		
		public function email_exists(){
			$email = $this->_param('email');
			$userid = $this->_param('userid');
			$result = D('Member')->is_email($email,$userid);
			if(!$result) echo 1;
		}
		
		/**
		 +----------------------------------------------------------
		 * 通过catid和moduleid 获得select
		 +----------------------------------------------------------
		 * @param  int  $catid     分类ID
		 +----------------------------------------------------------
		 * @return int	moduleid   模块ID
		 +----------------------------------------------------------
		 * @return int	extends    扩展（你可以在这里加一些关于样式的东西）
		 +----------------------------------------------------------
		 */
		public function search_category($moduleid,$catid=0,$extends = "size='2' style='height:120px;width:180px;'"){
			$moduleid = $this->_param('moduleid');
			$catid = $this->_param('catid');
			$condition = "moduleid = ".$moduleid;
			//如果catid存在，列出所在分类的列表信息，否则只列根分类
			if($catid){
				$condition .= " and catid = ".$catid;				
			}else{
				$condition .= " and parentid = 0";
			}
			
			$category = M('category');
			$list = $category->where($condition)->field('catid,catname,parentid,arrparentid,child')->find();			
			
			//根据查询得到catid的上级分类
			$selectedid = explode(',',$list['arrparentid'].','.$catid);
			$arrparentid = explode(',',$list['arrparentid']);
			//按级别循环分类列表
			for($i=0;$i<count($arrparentid);$i++){
				$list = $category->where('moduleid = '.$moduleid.' and parentid = '.$arrparentid[$i])->field('catid,catname,parentid,child')->select();
				$n = $i+1;
				$select_start = "<span id='load_category_{$n}'><select class='test1' onclick='get_category(this.value)' ".$extends.">";
				foreach($list as $val){
					$selected = $val['catid']==$selectedid[$n]? 'selected' : '';
					$select_option .= "<option value='".$val['catid']."' ".$selected.">".$val['catname']."</option>";
				}									
				$select_end = "</select></span>";
				$selects .= $select_start.$select_option.$select_end;
				$select_option = '';
			}
			//输出select 列表
			echo $selects;
		}
		
		public function tab_pro(){
			$attr = $this->_param('attr');
			$table_str = out_tab($attr);			
			$this->ajaxReturn($table_str,'success',1);
		}
		
	
		/**
		+----------------------------------------------------------
		* 分类select
		*
		+----------------------------------------------------------
		* @access public
		+----------------------------------------------------------
		* @return Ajax
		+----------------------------------------------------------
		* @author Micheal
		+----------------------------------------------------------
		* @method GET
		+----------------------------------------------------------
		*/	
		function cate_selects(){
			$adds=M("category");
			$cate=$adds->where("parentid=".$_GET['pid'])->field(array('catid','catname','parentid'))->select();
			if(!empty($cate)){
				$rank ='catorder_'.($_GET['ranks']+1);
				$data['content']=' <select id=\''.$rank.'\' onchange=\'get_cate_selects(this.value,"'.$_GET['nid'].'","'.($_GET['ranks']+1).'","'.$_GET['subid'].'")\'>';
				$data['content'].="<option value=''>请选择</option>";
				foreach($cate as $v){
					$data['content'].="<option value=".$v['catid'].">".$v['catname']."</option>";
				}
				$data['content'].="</select>";
				$data['ranks']=$rank;
				$this->ajaxReturn($data,'ok',1);
			}else{
				$data['content']="零数据";
				$this->ajaxReturn($data,'没有数据',0);
			}			
		}
	
		/**
		+----------------------------------------------------------
		* 地区select
		*
		+----------------------------------------------------------
		* @access public
		+----------------------------------------------------------
		* @return Ajax
		+----------------------------------------------------------
		* @author Micheal
		+----------------------------------------------------------
		* @method GET
		+----------------------------------------------------------
		*/	
		function area_selects(){
			$adds=M("area");
			$area=$adds->where("parentid=".$_GET['pid'])->field(array('areaid','areaname','parentid'))->select();
			if(!empty($area)){
				$rank ='area_'.($_GET['ranks']+1);
				$data['contents']=' <select id=\''.$rank.'\' onchange=\'get_area_selects(this.value,"'.$_GET['nid'].'","'.($_GET['ranks']+1).'","'.$_GET['subid'].'")\'>';
				$data['contents'].="<option value=''>请选择</option>";
				foreach($area as $v){
					$data['contents'].="<option value=".$v['areaid'].">".$v['areaname']."</option>";
				}
				$data['contents'].="</select>";
				$data['ranks']=$rank;
				$this->ajaxReturn($data,'ok',1);
			}else{
				$data['contents']="零数据";
				$this->ajaxReturn($data,'没有数据',0);
			}			
		}

		//验证码
		public function verify(){
			$ajax = $this->_param('ajax');
			if($ajax){
				if($_SESSION['verify'] != md5($_POST['verify'])) {
					$this->ajaxReturn('验证码错误！','error',0);
				}else{
					$this->ajaxReturn('输入正确！','success',1);
				}
			}else{
				import('ORG.Util.Image');
				Image::buildImageVerify();
			}
		}
		
		//验证邮件验证码
		public function email_code(){
			$mail_verify = session('mail_verify');
			$verify = $this->_param('verify');
			
			if($verify==$mail_verify){
				$this->ajaxReturn('输入正确！','success',1);
			}else{
				$this->ajaxReturn('验证码错误！','error',0);
			}
		}
		
		//联系方式验证
		public function user_mobile(){
			$type = $this->_param('type');
			$mobile = $this->_param('mobile');
			$member = M('Member');
			$company = M('company');
			
			if($type=='mobile'){
				$userdata = $member->field('userid')->where("mobile = '$mobile'")->find();					
			}else{
				$userdata = $company->field('userid')->where("telephone = '$mobile'")->find();					
			}
			
			if($type && $userdata){
				$this->ajaxReturn('用户名存在','success',1);
			}else{
				$this->ajaxReturn('用户名不存在','error',0);
			}
		}
	
		//邮箱验证
		public function user_email(){
			$email = $this->_param('email');
			$member = M('Member');
			$rr = $member->field('userid')->where("email = '".$email."'")->find();

			if($rr && $email){
				$this->ajaxReturn('邮箱存在','success',1);
			}else{
				$this->ajaxReturn('邮箱不存在','error',0);
			}
		}
		
		/* 地区ajax请求  wuzhijie 2013年12月30日 15:26:20 */
		public function AJAXrequirearea(){
			//$area_title = convert($area_title, 'UTF-8', DEFAULT_CHARSET);
			$area_extend = isset($_POST['area_extend']) ? stripslashes($_POST['area_extend']) : '';
			$areaid = isset($_POST['areaid']) ? intval($_POST['areaid']) : 0;
			$area_deep = isset($_POST['area_deep']) ? intval($_POST['area_deep']) : 0;
			$area_id= isset($_POST['area_id']) ? intval($_POST['area_id']) : 1;
			
			echo get_area_select($_POST['area_title'], $areaid, $area_extend, $area_deep, $area_id);
		}
		/* 分类ajax请求 wuzhijie 2014年1月7日 17:13:55 */
		public function AJAXrequirecate(){
			$category_extend = isset($_POST['category_extend']) ? stripslashes($_POST['category_extend']) : '';
			$category_categorytypeid = isset($_POST['category_categorytypeid']) ? intval($_POST['category_categorytypeid']) : 1;
			if(!$_POST['category_categorytypeid']) exit;
			$category_deep = isset($_POST['category_deep']) ? intval($_POST['category_deep']) : 0;
			$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 1;
			echo get_category_select($_POST['category_title'], $_POST['catid'], $category_categorytypeid, $category_extend, $category_deep, $cat_id);
		}
	
	//商务代表（工作教育经历）
	public function work(){
		$id = $this->_param('id');
		$action = $this->_param('action');
		$sub = $this->_param('sub');
		if($sub==1){
			$work = M('res_education');
		}else{
			$work = M('res_work');
		}
		switch($action){
			case 'add':		
				$work->create();
				$id = $work->add();		
				if($id){
					echo $id;
					//$this->ajaxReturn($id,'success',1);
				}else{
					echo $this->ajaxReturn(0,'error',0);
				}
			break;
			case 'edit':
				$result = $work->where('id='.$id)->find();
				if($result){
					$this->ajaxReturn($result,'success',1);
				}else{
					$this->ajaxReturn(0,'error',0);
				}				
			break;
			case 'update':
				$data = $_POST;		
				$result = $work->where('id='.$id)->save($data);
				if($result){
					echo 1;
				}else{
					echo 0;
				}
			break;
			case 'del':
				$result = $work->where('id='.$id)->delete();
				if($result){
					echo 1;
				}else{
					echo 0;
				}
			break;
		}
	}
	/*贾敬婷企业商务室,商务代表商务室中的收件人用户检测是否存在*/
	public function checkonly(){
		$param =$this->_param('param');
		$member = M('Member');
		$map="username = '".$param."' ";
		$rr = $member->field('userid')->where($map)->find();
		if($param!=$_SESSION['userdata']['username']){
			if($rr['userid'] && $param){
				//$this->ajaxReturn(1,'用户名存在','y');
				echo 'PASS';
			}else{
				//$this->ajaxReturn(0,'用户名不存在','n');
				echo '用户名不存在';
			}
		}else{
			//$this->ajaxReturn(0,'不能给自己发站内信','n');
			echo '不能给自己发站内信';
		}
	}

	
	//站内支付密码验证 蓝星福  2013-08-29
	public function checkPaypassword() {
		$paypasswrod = md5(md5($_REQUEST['paywrod']));
		$minfo = M('member')->where('userid='.$_SESSION[userdata][userid])->field('payword')->find();
		 
		if( $paypasswrod == $minfo['payword'] ) {
			echo 'payok';
		} else{
		  echo 'errorpassword';	
		}
	}
	
	
	//注册邮件验证 发送 wuzhijie 2013年12月30日 15:25:41 
	public function mail()
	{
		import('@.ORG.Util.Mail'); // 导入邮寄类
		$mailname = $this->_param('mailaddress'); //目标邮件地址;
		//电子邮箱
		if(!preg_match('/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/',$mailname)){
			$this->ajaxReturn('请填写正确的邮箱地址！', '失败', 0);
			return false;
		}
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
			$this->ajaxReturn('发送成功', '成功', 1);//data,info,status
		}else
		{
			$this->ajaxReturn('发送失败', '失败', 0);
		}
	}
	/* 
		注册成功 发送邮寄提示成功 
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
	
?>