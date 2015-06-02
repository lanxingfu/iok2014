<?php
// 本类由系统自动生成，仅供测试用途
class MemberAction extends CommonAction {

	public function _initialize(){
		$_SESSION = array(
			'verify' => '7792a0500b5aabb4b072d113a160d200',
			'userdata' => Array ( 
				'userid' => '22330', 
				'username' => 'qiye1',
				'representid' => '22325',
				'representname' => 'shangdai1',
				'passport' => '',
				'company' => '企业一', 
				'password' => '14e1b600b1fd579f47433b88e8d85291',
				'payword' => '14e1b600b1fd579f47433b88e8d85291',
				'email' => '1048409627@qq.com',
				'message' => 14,
				'chat' => 0,
				'sound' => 1,
				'online' => 1,
				'gender' => 1,
				'truename' => '北京企业1',
				'mobile' => '13599999999',
				'msn' =>'',
				'qq' =>'',
				'ali' =>'',
				'skype' =>'',
				'department' =>'',
				'career' =>'',
				'admin' => 0,
				'role' =>'',
				'aid' => 0,
				'groupid' => 8,
				'regid' => 6,
				'areaid' => 393,
				'sms' => 0,
				'credit' => 38,
				'money' => 99990218.80,
				'cash' =>'',
				'invitemoney' => 0.00,
				'locking' => 0.00,
				'bank' =>'',
				'account' =>'',
				'edittime' => 1384487627,
				'regip' =>'',
				'regtime' => 1384151011,
				'loginip' =>'',
				'logintime' => 1384757219,
				'logintimes' => 6,
				'black' => '',
				'send' => 1,
				'auth' => '',
				'authvalue' => '',
				'authtime' => 0,
				'vemail' => 0,
				'vmobile' => 0,
				'vtruename' => 0,
				'vbank' => 0,
				'vcompany' => 0,
				'vtrade' => 0,
				'trade' => '',
				'support' => '',
				'inviter' => '',
				'idcard' => '210104198403194943',
				'address' => '',
				'codeid' => '',
				'nickname' => '',
				'status' => 1,
				'mynote' => '',
				'integrateid' => 0,
				'integratename' => '',
				'usertype' => 'company',
				'companygroupid' => 8,
				'companyname' => '企业一',
				'state' => 5,
				'standmessage' => 15
			)
		);
	}
	/* 条件过滤 */
	function _filter(&$map){
		if(!empty($_SESSION['userdata']['username'])){
			$map['username']=$_SESSION['userdata']['username'];
		}
    }

	//	首页入口    动态信息
	public function index(){
		/* 默认登陆的是企业会员获取所有商务室功能 ,个人会员在前台隐藏部分入口菜单*/
		//$_SESSION['userdata']['iscompleted'] 判断用户是否完善资料

		//验证用户是否已经完善资料
		//$this->verify_data();
		$memmodel = M('member');
		$Company = M('company');
		$Message = M('message');
		$Represent = M('represent');
		$Formo = M('mall_order');
		$Mall = M('mall');
		$Buy = M('buy');
		$Announce =M(announce);
		
		$member = $memmodel->where("userid=".$_SESSION['userdata']['userid'])->find();

		$touser = $member['username'];
		$areaid = $member['areaid'];
		$representid = $member['representid'];
		$company=$Company->where("userid='".$member['userid']."'")->find();//查登录会员的公司资料
		$message=$Message->where("touser = '$touser' and isread=0")->count();//查登录会员的未读站内信个数;
		//添加至session
		$_SESSION['userdata']['standmessage']=$message;
		$represent=$Represent->where("userid='$representid'")->find();//查登录会员商务代表的资料
		$represents=M("member")->where("userid='$representid'")->find();//查登录会员商务代表的资料
		/* 我是卖家 */
		$order_status0=$Formo->where("seller = '$touser' AND status = 0")->count();
		$order_status1=$Formo->where("seller = '$touser' AND status = 1")->count();
		$order_status2=$Formo->where("seller = '$touser' AND status = 2")->count();
		$order_status3=$Formo->where("seller = '$touser' AND status = 3")->count();
		$order_status4=$Formo->where("seller = '$touser' AND status = 4 AND (order_comment&10) = 0")->count();
		$order_status5=$Formo->where("seller = '$touser' AND status = 5")->count();
		$order_status6=$Formo->where("seller = '$touser' AND status = 6")->count();
		$order_status7=$Formo->where("seller = '$touser' AND status = 7")->count();
		$order_status8=$Formo->where("seller = '$touser' AND status = 8")->count();
		$order_status9=$Formo->where("seller = '$touser' AND status = 9")->count();
		$order_status10=$Formo->where("seller = '$touser' AND status = 10")->count();
		$order_status = array($order_status0,$order_status1,$order_status2,$order_status3,$order_status4,$order_status5,$order_status6,
							$order_status7,$order_status8,$order_status9,$order_status10);
		//echo $Formo->getLastSql();dump($order_status0);
		/* 我是买家 */
		//$orders=$Formo->where("buyer = '$touser'")->select();//查登录会员作为买家的订单资料
		$orders_status0=$Formo->where("buyer = '$touser' AND status = 0")->count();
		$orders_status1=$Formo->where("buyer = '$touser' AND status = 1")->count();
		$orders_status2=$Formo->where("buyer = '$touser' AND status = 2")->count();
		$orders_status3=$Formo->where("buyer = '$touser' AND status = 3")->count();
		$orders_status4=$Formo->where("buyer = '$touser' AND status = 4  AND (order_comment&17) = 0")->count();
		$orders_status5=$Formo->where("buyer = '$touser' AND status = 5")->count();
		$orders_status6=$Formo->where("buyer = '$touser' AND status = 6")->count();
		$orders_status7=$Formo->where("buyer = '$touser' AND status = 7")->count();
		$orders_status8=$Formo->where("buyer = '$touser' AND status = 8")->count();
		$orders_status9=$Formo->where("buyer = '$touser' AND status = 9")->count();
		$orders_status10=$Formo->where("buyer = '$touser' AND status = 10")->count();
		$orders_status = array($orders_status0,$orders_status1,$orders_status2,$orders_status3,$orders_status4,$orders_status5,$orders_status6,
							$orders_status7,$orders_status8,$orders_status9,$orders_status10);
		//dump($order);
		/* 我的供应 */
		$mall0=$Mall->where("username = '$touser' AND status = 0")->count();
		$mall1=$Mall->where("username = '$touser' AND status = 1")->count();
		$mall2=$Mall->where("username = '$touser' AND status = 2")->count();
		$mall3=$Mall->where("username = '$touser' AND status = 3")->count();
		$mall4=$Mall->where("username = '$touser' AND status = 4")->count();
		$mall_status = array($mall0,$mall1,$mall2,$mall3,$mall4);
		/* 我的求购 */
		$buy0=$Buy->where("username = '$touser' AND status = 0")->count();
		$buy1=$Buy->where("username = '$touser' AND status = 1")->count();
		$buy2=$Buy->where("username = '$touser' AND status = 2")->count();
		$buy3=$Buy->where("username = '$touser' AND status = 3")->count();
		$buy4=$Buy->where("username = '$touser' AND status = 4")->count();
		$buy_status = array($buy0,$buy1,$buy2,$buy3,$buy4);

		
		/* 猜你喜欢 */
		$love_mall=$Mall->where("status = 3 and username = '$touser' and areaid = '$areaid'")->limit(4)->select();
		$love_buy =$Buy->where("status = 3 and username  = '$touser' and areaid = '$areaid'")->limit(4)->select();
		//echo $Mall->getLastSql();dump($love_mall);

		/* 公告 */
		$date=date('Y-m-d');
		$date=mktime($date);
		$announce=$Announce->where("Totime < '$date'")->limit(7)->select();
		
		
		$this->assign('member',$member);
		$this->assign('company',$company);
		$this->assign('message',$message);
		$this->assign('represent',$represent);
		$this->assign('represents',$represents);
		//$this->assign('order',$order);
		//$this->assign('orders',$orders);
		$this->assign('order_status',$order_status);
		$this->assign('orders_status',$orders_status);
		$this->assign('mall_status',$mall_status);
		$this->assign('buy_status',$buy_status);
		$this->assign('love_mall',$love_mall);
		$this->assign('love_buy',$love_buy);
		$this->assign('announce',$announce);
		
		$this->display();
	}
	
	//个人会员资料  0901 xiaog
	public function onedata(){
		$userid = $_SESSION['userdata']['userid'];		
		if(!empty($_POST)){
			$tab_member = M('member');
			$tab_company_prove = M('company_prove');
			$mem['truename'] = $_POST['truename'];
			$mem['gender'] = $_POST['gender'];
			$mem['email'] = $_POST['email'];
			$mem['mobile'] = $_POST['mobile'];		
			$mem['areaid'] = $_POST['areaid'];
			$mem['idcard'] = $_POST['idcard'];
			if($mem['truename']==''||$mem['gender']==''||$mem['email']==''||$mem['mobile']==''||$mem['areaid']==''||$mem['idcard']==''){
				$this->wrong('不能为空');
			}
			if($mem['email']!='' && !preg_match("/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$mem['email'])){
				$this->wrong('邮箱格式不正确');
			}
			if($mem['mobile']!='' && !preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/',$mem['mobile'])){
				$this->wrong('手机格式不正确');
			}
			if($mem['idcard']!='' && !preg_match('/\d{17}[\d|X]|\d{15}/',$mem['idcard'])){
				$this->wrong('身份证号格式不对');
			}
			$tm = $tab_member->where("userid = '$userid'")->data($mem)->save();
			$com['idcart_1'] = $_POST['idcart_1'];
			$com['idcart_2'] = $_POST['idcart_2'];				
					$cd = $tab_company_prove->where('userid = '.$userid)->data($com)->save();	
					if($tm || $cd){
						$this->right('资料保存成功','forward','/Member/onedata');
					}else{
						$this->wrong('没有改动哦','forward','/Member/onedata');
					}	
				
		}
			//获得用户数据
		$member = M('member')->field("m.username,m.truename,m.gender,m.email,m.mobile,m.idcard,m.areaid,r.idcart_1,r.idcart_2")->table('destoon_member m,destoon_company_prove r')->where('m.userid=r.userid and m.userid = '.$userid)->find();
		$this->assign('member',$member);
		$this->display();
	}	
	
	//账户资料
	public function userData(){
		$userData['userid']=$_SESSION['userdata']['userid'];
		$members=M('member');
		$userData=$members->where('userid='.$userData['userid'])->find();
		
		/* 判断是否是企业用户 */
		if($_SESSION['userdata']['usertype'] == 'company'){
			$comp 	=M('company');
			$company = $comp->where("userid='".$userData['userid']."'")->find();
			$this->assign('company',$company);
		}
		//数据模版
		$this->assign('userdata',$userData);
		/* 分部加载 */
		switch($this->_param('way')){
			case 'two':
				
				$this->display('userData_relat');
			break;
			case 'three':
				/* 公司介绍 */
				/* 读取缓存 *///$content_table = content_table(4, $userid, is_file(DT_CACHE.'/4.part'), $DT_PRE.'company_data');
				if(!empty($userData['userid'])){
					$cont = M('company_data');
					$contents = $cont->where("userid='".$userData['userid']."'")->getField('content');
				}
				$this->assign('Ccontent',$contents);
				$this->display('userData_cont');
				exit;
			break;
			case 'four':
				/* 资质 */
				if(!empty($userData['userid'])){
					$pro	=M('company_prove');					
					$com	=M('company');
					$prove = $pro->where("userid='".$userData['userid']."'")->find();
					$company = $com->field("status")->where("userid='".$userData['userid']."'")->find();
					$this->assign('company',$company);
					$this->assign('prove',$prove);
				}
				$this->display('userData_ver');
			break;
			default:
				/* 预定义 经营模式	*/
				$COM_MODE	=	Array(
										0 => '制造商',
										1 => '贸易商',
										2 => '服务商',
										3 => '其他机构'
								);
				$mode_check = dcheckbox($COM_MODE, 'mode[]', $company['mode'], 'onclick="check_mode(this,2);"', 0);
				$this->assign('mode_check',$mode_check);

				/* 预定义 公司规模 */
				$COM_SIZE	=	Array(
									0 => '1-49人',
									1 => '50-99人',
									2 => '100-499人',
									3 => '500-999人',
									4 => '1000-3000人',
									5 => '3000-5000人',
									6 => '5000-10000人',
									7 => '10000人以上'
								);
				$this->assign('COM_SIZE',$COM_SIZE);
				
				/* 主营行业  */
				$cates = $company['catid'] ? explode(',', trim($company['catid'],',')) : array();
				$this->assign('cates',$cates);
				
				/* 货币 */
				$MONEY_UNIT = Array
				(
					0 => '人民币',
					1 => '港元',
					2 => '台币',
					3 => '美元',
					4 => '欧元',
					5 => '英镑'
				);
				$this->assign('MONEY_UNIT',$MONEY_UNIT);
				$this->display();
			break;
		}

		
	}
	
	/* 修改密码 */
	public function changepw(){
		
		if($_POST['way']){
			
			$userid = $_SESSION['userdata']['userid'] ? $_SESSION['userdata']['userid'] : 0 ;
			if($userid==0){
				$this->wrong('请先登录！！','forward',"/Public/login");
			}
			if($_POST['way']=='passf'){
				$DBP = M('member');
				$oldpass = $DBP->where('userid='.$userid)->getField('password');
				//用户输入的旧密码
				$inoldpass = md5(md5($_POST['oldpasswd']));
				//新密码
				$newpssswdone = trim($_POST['newpasswdone']);
				$newpasswdtwo = trim($_POST['newpasswdtwo']);
				if(empty($newpasswdtwo) || empty($newpasswdtwo) ){
					echo "新密码不能为空！！";exit;
				}
				if(strlen($newpssswdone)<6){
					echo "密码长度不要小于 6 位！！";exit;
				}
				$newpass = md5(md5($newpasswdtwo));
				
				//验证
				if($oldpass==$inoldpass){
					if($_POST['oldpasswd']==$newpssswdone || $_POST['oldpasswd']==$newpasswdtwo){
						echo "新密码与原密码一致，未做修改！";exit;
					}elseif($newpssswdone!=$newpasswdtwo){
						echo "两次新密码输入不一致，请确认！";exit;
					}else{
						$result = $DBP->where('userid='.$userid)->setField('password',$newpass);
						if($result){
							echo '密码修改成功';exit;
						}else{
							echo '密码修改失败，请重试';exit;
						}
					}
				}else{
					echo '原密码输入错误！';exit;
				}
			}elseif($_POST['way']=='payf'){
				$DBP = M('member');
				$oldpay = $DBP->where('userid='.$userid)->getField('payword');
				//用户输入的旧密码
				$inoldpay = md5(md5($_POST['oldpaywd']));
				
				$newpay_one = trim($_POST['newpaywdone']);
				$newpay_two = trim($_POST['newpaywdtwo']);
				if(empty($newpay_one) || empty($newpay_two)){
					echo "新支付密码不能为空！！";exit;
				}elseif(strlen($newpay_one)<6 || strlen($newpay_two)<6){
					echo "新支付密码长度不能小于 6 位！！";exit;
				}
				//新密码
				$newpay_o = md5(md5($newpay_one));
				$newpay_t = md5(md5($newpay_two));
				
				//验证
				if($oldpay==$inoldpay){
					if($newpay_o!=$newpay_t){
						echo "两次新支付密码输入不一致，请确认！";exit;
					}elseif($oldpay==$newpay_o || $oldpay==$newpay_t){
						echo "新支付密码与原支付密码一致，请修改！";exit;
					}else{
						$result = $DBP->where('userid='.$userid)->setField('payword',$newpay_t);
						if($result){
							echo '密码修改成功';exit;
						}else{
							echo '密码修改失败，请重试';exit;
						}
					}
				}else{
					echo '原支付密码输入错误！';exit;
				}
			}else{
				echo '修改失败，请重试。';exit;
			}
			
		}else{
			if($_REQUEST['menus']==1){
				$this->display('changepayw');
			}else{
				$this->display();
			}
		}
		
	}
	
	/* 收货地址 */
	public function delivery(){
		$map = $this->_search();
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }
		
        $model = D('address');
        if (!empty($model)) {
            $this->_list($model, $map, $mk='itemid');
        }
		
        $this->display();
        return;
	}
	/* 添加收货地址 wuzhijie */
	public function add_addr(){
		if($_POST['way']=='sub'){
			/* 实例化类 */
			$model		 = D('address');
			
			if (false === $model->create()) {
				$this->wrong($model->getError());
				exit;
			}
			if($_POST['areaid']=='' || $_POST['areaid']==0){
				echo "请选择收货地区！";return false;exit;
			}
			if($_POST['address']==''){
				echo '请填写收货地址！';exit;
			}
			if($_POST['truename']==''){
				echo '请填写收货人姓名！';exit;
			}
			if($_POST['mobile']=='' && $_POST['telephone']==''){
				echo '请至少填写一个联系方式！';exit;
			}elseif($_POST['mobile']){
				if(!preg_match('/^((13)|(15)|(18))(\d{9})$/', $_POST['mobile']) && !preg_match('/^((\+86)|(86))?1[358]\d{9}$/', $_POST['mobile'])){
					echo '请填写正确规范的手机号码！';exit;
				}
			}elseif($_POST['telephone']){
				preg_match("/^(\d{3,4})([-]*)(\d{6,8})/i",$_POST['telephone'], $tel_matches);
				if( empty($tel_matches) ){
					echo '请填写正确规范的电话号码！';exit;
				} 
			}
			/* 当前操作用户 */
			$_POST['username'] = $_SESSION['userdata']['username'];
			$_POST['editor'] = $_POST['username'];
			/* 操作时间 */
			$_POST["addtime"] = time();
			$_POST["edittime"] = $_POST["addtime"];
			unset($_POST['way']);
			
			//保存当前数据对象
			$list = $model->add($_POST);
			
			/* 获取当前插入主表id */
			$insertId = mysql_insert_id();

			if ($list !== false) {
				//保存成功
				echo "新增地址成功";exit;
			} else {
				//失败提示
				//$this->wrong('新增数据失败!');
				echo "添加失败，请重试";exit;
			}

		}else{
			$model = D('address');
			$listorder=$model->where("username='".$_SESSION['userdata']['username']."'")->max('listorder');
			$neworder = $listorder +1;
			$this->assign('listorder',$neworder);
			$this->display();
			return;
			
		}
	}
	/* 编辑收货地址 */
	function edit() {
        $model = M('address');
		if($_POST['way']=='sub'){
			if($_POST['areaid']=='' || $_POST['areaid']==0){
				echo "请选择收货地区！";return false;exit;
			}
			if($_POST['address']==''){
				echo '请填写收货地址！';exit;
			}
			if($_POST['truename']==''){
				echo '请填写收货人姓名！';exit;
			}
			if($_POST['mobile']=='' && $_POST['telephone']==''){
				echo '请至少填写一个联系方式！';exit;
			}elseif($_POST['mobile']){
				if(!preg_match('/^((13)|(15)|(18))(\d{9})$/', $_POST['mobile']) && !preg_match('/^((\+86)|(86))?1[358]\d{9}$/', $_POST['mobile'])){
					echo '请填写正确规范的手机号码！';exit;
				}
			}elseif($_POST['telephone']){
				preg_match("/^(\d{3,4})([-]*)(\d{6,8})/i",$_POST['telephone'], $tel_matches);
				if( empty($tel_matches) ){
					echo '请填写正确规范的电话号码！';exit;
				}
			}
			$_POST["username"] = $_SESSION['userdata']['username'];
			$_POST["editor"] = $_POST["username"];
			$_POST["edittime"] = time();
			$resc = $model->where("itemid='".$_POST['itemid']."'")->save($_POST);
			if($resc==1){
				echo "保存成功";exit;
			}else{
				echo "保存失败，请重试";exit;
			}
			
		}else{
			if(!isset($_REQUEST['id'])){
				$this->wrong('请选择你要修改的地址！');
			}
			$results = $model->where("itemid='".$_REQUEST['id']."'")->find();
			
			$this->assign('results', $results);
			$this->display();
		}
    }
	
	
	
	/* 删除 */
	Public function foreverdelete(){
		//删除指定记录
        $model = D('address');
        if (!empty($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST ['id'];//获取参数id（此处id值不等于数据库字段，等于传参关键字的值）：micheal

			if (isset($id)) {
                $condition = array($pk => array('in', explode(',', $id)));
                if (false !== $model->where($condition)->delete()) {
                    $data['datas']='删除成功！';
					$data['status']=1;
					echo json_encode($data);
                } else {
					$data['datas']='删除失败！';
					$data['status']=0;
					echo json_encode($data);
                }
            } else {
				$data['datas']='非法操作';
				$data['status']=250;
				echo json_encode($data);
            }
        }
        //$this->forward();
		exit;
	}
	
	
	/* 保存资料 */
	public function saveData(){
		/* 经营模式 */
		if($_POST['mode']){
			$_POST['mode'] = implode(',',$_POST['mode']);
		}
		$Model_c = M('company');
		$com_status = $Model_c->where("userid ='".$_SESSION['userdata']['userid']."'")->getfield('status');
		//数据保存时间
		$edittimes = time();
		switch($_REQUEST['way']){
			case 'one':
				/* 保存公司资料 company.status =2*/
				if($_SESSION['userdata']['userid']){
					
					$data['company'] = $_POST['company'];
					$data['type'] = $_POST['type'];
					$data['areaid'] = $_POST['areaid'];
					$data['catid'] = $_POST['catid'];
					$data['business'] = $_POST['business'];
					$data['mode'] = $_POST['mode'];
					$data['size'] = $_POST['size'];
					$data['regunit'] = $_POST['regunit'];
					$data['capital'] = $_POST['capital'];
					$data['sell'] = $_POST['sell'];
					$data['buy'] = $_POST['buy'];
					if($com_status==5){
						if($data['type']==''|| $data['catid']==''||$data['regunit']==''){
							$this->wrong('必填项不能为空');
						}
					}else{
						
						if($data['company']=='' ||$data['type']=='' || $data['catid']==''||$data['regunit']==''){
							$this->wrong('必填项不能为空');
						}
						if($data['areaid']){
							$areamodel = M('area');
							$area = $areamodel->where("areaid='".$data['areaid']."' and areaid!='' ")->getField('child');
							if($area!==false){
								if($area==1){
									$this->wrong('请选择精确到区县级的地区位置！');return false;
								}
							}else{
								$this->wrong('地区选择有错误，请重试！');return false;
							}
						}else{
							$this->wrong('请选择公司所在地区！');return false;
						}
						
					}
					
					
					if($data['capital']!=0 && !preg_match('/[0-9]*[1-9][0-9]*$/',$data['capital'])){
						$this->wrong('资金为数字');
					}
					$res = $Model_c->where('userid='.$_SESSION[userdata][userid])->save($data);
					
					//更新会员表中的公司
					$membercompany['company'] =  $data['company'];
					if(isset($data['areaid']) && $data['areaid'] >0)
					{
						$membercompany['areaid'] = $data['areaid'];
					}
					$membercompany['edittime'] = $edittimes;
					M('member')->where('userid='.$_SESSION['userdata']['userid'])->save($membercompany);
					
			    	$_SESSION['userdata']['company']=$data['company'];

					if($res!==false){
						if($res!=0){
							if(!in_array($com_status,array(4,5))){
								$st['status'] = 2;
								$Model_c->where('userid='.$_SESSION['userdata']['userid'])->save($st);
								$_SESSION['userdata']['state'] = 2;
							}
							$this->right('保存成功！','forward','/Member/userData/way/'.$_REQUEST['way']);
						}else{
							$this->wrong('未作修改！');
						}
					}ELSE{
						$this->wrong('保存失败！');
					}
				}else{
					$this->wrong('帐号用户名不存在，请重新登录！！');
				}
			break;
			case 'two':
				/* 保存联系方式 企业 + 联系人 company.status =3*/
				$DBm = M('member');
				$data['truename'] = $_POST['truename'];
				$data['gender'] = $_POST['gender'];
				$data['department'] = $_POST['department'];
				$data['career'] = $_POST['career'];
				$data['mobile'] = $_POST['mobile'];
				$data['email'] = $_POST['email'];
				$data['qq'] = $_POST['qq'];
				if(isset($_POST['head_areaid'])){
					$datac['head_areaid'] = $_POST['head_areaid'];
				}
				$datac['address'] = $_POST['address'];
				$datac['postcode'] = $_POST['postcode'];
				$datac['telephone'] = $_POST['telephone'];
				$datac['fax'] = $_POST['fax'];
				$datac['mail'] = $_POST['mail'];
				$datac['homepage'] = $_POST['homepage'];
				if($data['truename']==''||$data['gender']==''||$data['mobile']==''||$data['email']==''){
					$this->wrong('必填项不能为空！');
				}
				if($data['mobile']!='' && !preg_match('/^13[0-9]{9}$|14[0-9]{9}|15[0-9]{9}$|18[0-9]{9}$/',$data['mobile'])){
					$this->wrong('请输入正确的手机号码');
				}
				if($data['email']!='' && !preg_match("/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$data['email'])){
					$this->wrong('请填入正确的邮箱');
				}
				if($data['qq']!=''&& !preg_match('/\d{5,11}/',$data['qq'])){
					$this->wrong('qq请输入数字');
				}
				if($com_status!=5){
					if($datac['head_areaid']=='' || $datac['head_areaid']=='0'){
						$this->wrong('请选择公司所在地区');
					}
				}
				if($datac['address']=='' || $datac['telephone']==''){
					$this->wrong('必填项不能为空');
				}
				if($datac['postcode']!='' && !preg_match('/[1-9]\d{5}(?!\d)/',$datac['postcode'])){
					$this->wrong('邮政编码格式不正确');
				}
				
				preg_match("/^(\d{3,4})([-]*)(\d{6,8})/i",$datac['telephone'], $tel_matches);
				if(empty($tel_matches)){
					$this->wrong('公司电话格式不正确');
				}

				if($datac['fax']!='' && !preg_match('/^[+]{0,1}(\d){1,3}[ ]?([-]?((\d)|[ ]){1,12})+$/',$datac['fax'])){
					$this->wrong('传真格式不正确');
				}
				if($datac['mail']!='' && !preg_match("/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/",$datac['mail'])){
					$this->wrong('请填写正确的邮箱');
				}
				if($datac['homepage']!='' && !preg_match('/(http(s)?:\/\/){0,1}([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/',$datac['homepage'])){
					$this->wrong('网址格式不对');
				}
				$data['edittime'] = $edittimes;
				$res_m = $DBm->where('userid='.$_SESSION['userdata']['userid'])->save($data);
				$res_c = $Model_c->where('userid='.$_SESSION['userdata']['userid'])->save($datac);
				
				if($res_m!==false && $res_c!==false){
					if($res_m!=0){
						if($res_c!=0){
							
							if(!in_array($com_status,array(0,4,5))){
								$st['status'] = 6;
								$Model_c->where('userid='.$_SESSION['userdata']['userid'])->save($st);
								$_SESSION['userdata']['state'] = 6;
							}
							$this->right('联系方式已修改','forward','/Member/userData/way/'.$_REQUEST['way']);
							
						}else{
							$this->right('联系人已修改，企业联系方式未修改！','forward','/Member/userData/way/'.$_REQUEST['way']);
						}
					}else{
						if($res_c!=0){
							$this->right('未修改联系人，企业联系方式已修改！','forward','/Member/userData/way/'.$_REQUEST['way']);
						}else{
							$this->wrong('联系方式未做修改！');
						}
					}
				}else{
					$this->wrong('保存失败！','forward','/Member/userData/way/'.$_REQUEST['way']);
				}
				$_SESSION['userdata']['truename']=$data['truename'];
				
			break;
			case 'three':
				
				$DBd = M('company_data');
				if($_POST['content']!=''){
					$res = $DBd->where('userid='.$_SESSION['userdata']['userid'])->save($_POST);
				}else{
					$this->wrong('内容不能为空！');
				}
				if($res!=false){
					if($res!=0){
						$times['edittime'] = $edittimes;
						M('member')->where("userid ='".$_SESSION['userdata']['userid']."'")->save($times);
						/* 保存公司介绍 company.status =6*/
						if(!in_array($com_status,array(0,4,5))){
							$st['status'] = 3;
							$Model_c->where('userid='.$_SESSION['userdata']['userid'])->save($st);
							$_SESSION['userdata']['state'] = 3;
						}
						$this->right('保存成功！','forward','/Member/userData/way/'.$_REQUEST['way']);
					}else{
						$this->right('未作修改！','forward','/Member/userData/way/'.$_REQUEST['way']);
					}
				}else{
					$this->wrong('保存失败！','forward','/Member/userData/way/'.$_REQUEST['way']);
				}
			break;
			case 'four':
				
				$DBp = M('company_prove');
				if($_POST['license']=='' || $_POST['zzjgdm']=='' ||$_POST['swdjz']==''){
					$this->wrong('资质图片不能为空');
				}
				$res = $DBp->where('userid='.$_SESSION['userdata']['userid'])->save($_POST);
				if($res!=false){
					if($res!=0){
						$times['edittime'] = $edittimes;
						M('member')->where("userid ='".$_SESSION['userdata']['userid']."'")->save($times);
						/* 保存公司资质 company.status =4*/
						if(!in_array($com_status,array(0,4,5))){
							$st['status'] = 4;
							$Model_c->where('userid='.$_SESSION['userdata']['userid'])->save($st);
							$_SESSION['userdata']['state'] = 4;
						}
						$this->right('保存成功！','forward','/Member/userData/way/'.$_REQUEST['way']);
					}else{
						$this->right('未作修改！','forward','/Member/userData/way/'.$_REQUEST['way']);
					}
				}ELSE{
					$this->wrong('保存失败！','forward','/Member/userData/way/'.$_REQUEST['way']);
				}
				
			break;
			default:
				$this->redirect('/Member/userData/way/'.$_REQUEST['way']);
			break;
		}
	}
	
	/* 商友功能，尚未做 */
	public function bfriends(){
		redirect('/Member/userData/',3,'暂不能进行此项操作，页面跳至资料页 ~');
	}
	public function companymessage(){
		$state=$this->_get('mestate');
		switch($state){
			case '1':   //添加信件;
				$_username=$this->_get('username');
				if(!empty($_username)){
					$this->assign('username',$_username);
				}
				$this->display('addmessage');
			break;
			case '0':   //收件箱;
			    $fields =$this->_get('fields');
				$kw		=$this->_get('kw');
				$dfields = array('','title');
				$add=M('message');
				$username=$_SESSION['userdata']['username'];
				$map="touser ='$username' and status=3";
				if(!empty($kw)){
					$map .=" and $dfields[$fields] like '%$kw%'";
				}
				if(!empty($add)){
					$this->_list($add, $map, $mk='itemid');
				}else{
					$this->wrong('暂无列表');
				}
				$this->display('repmessage');
			break;
			case '2':   //已发送;
				$fields =$this->_get('fields');
				$kw		=$this->_get('kw');
				$dfields = array('','title');
				$add=M('message');
				$username=$_SESSION['userdata']['username'];
				$map="fromuser ='$username' and status=2";
				if(!empty($kw)){
					$map .=" and $dfields[$fields] like '%$kw%'";
				}
				if(!empty($add)){
					$this->_list($add, $map, $mk='itemid');
				}else{
					$this->wrong('暂无列表');
				}
			
				$this->display('sendedmessage');
			break;
			case '3':   //草稿箱;
				$fields =$this->_get('fields');
				$kw		=$this->_get('kw');
				$dfields = array('','title');
				$add=M('message');
				$username=$_SESSION['userdata']['username'];
				$map="fromuser ='$username' and status=1";
				if(!empty($kw)){
					$map .=" and $dfields[$fields] like '%$kw%'";
				}
				if(!empty($add)){
					$this->_list($add, $map, $mk='itemid');
				}else{
					$this->wrong('暂无列表');
				}
				$this->display('craftmessage');
			break;
			case '5':   //信件清理;
				$this->display('clearmessage');
			break;
			default :
				$this->display('addmessage');
			break;
		}
	}
	public function addmessage(){ //发送站内信
		if(md5($_POST['verifycode'])!=$_SESSION['verify']){
			$this->wrong('验证码错误！');
		}
		$add =M('message');
		$member=M('member');
		$username =$_SESSION['userdata']['username'];
	    $recieve=$_POST['recieve'];
	    $map['touser']=$recieve;
		$title=htmlspecialchars($_POST['title']);
		$map['title']=$title;
		$content=$_POST['content'];
		$map['content']=$content;
		$typeid=$_POST['save'];
		$map['typeid']=$typeid;
		$map['addtime']=time();
		$map['ip']=get_client_ip();
		$map['fromuser']=$username;
		$memone=$member->where("username='$recieve'")->find();//是否有收件人用户名;
		if(!empty($recieve) && !empty($title) && !empty($content)){
			if($typeid!=1){ //已发送;
				$map['status']=3;
				if(is_array($memone)){
					$message=$add->where("fromuser='$recieve'")->data($map)->add();//成功发送
					if($message){
						$map['status']=2;
						$save=$add->where("fromuser='$recieve'")->data($map)->add();//保存已发送;
					}
					$condition['message']=$memone['message']+1;
					$memmessage= $member->where("username='$recieve'")->data($condition)->save();//更新member表;
					if($save  && $memmessage){
						$itmeid=$_GET['itemid'];
						if(isset($itmeid)){
							$mresult = M('message')->where("itemid ='$itmeid'")->delete();
						}
						/* 消息推送 ios*/
						$push = M('client');
						$pushdata = $push->where("userid='".$memone['userid']."' and devicetype='ios'")->field('id,devicetoken,devicetype')->select();
						if($pushdata){
							$devicetoken_ios = array();
							foreach($pushdata as $val){
								if($val['devicetoken']!=0){
									array_push($devicetoken_ios,$val['devicetoken']);
								}
								
							}
							pushtoapp($devicetoken_ios,'您有一封站内信,请注意查收');
						}
						$this->right('站内信发送成功！','forward','/Member/companymessage/mestate/2');
					}else{
						$this->wrong('站内信发送失败！');
					}
				}
			}else{ //草稿箱;
				$map['status']=1;
				$draft=$add->where("fromuser='$username'")->data($map)->add();
				if($draft){
					$this->right('存储草稿成功！','forward','/Member/companymessage/mestate/3');
				}else{
					$this->wrong('存储草稿失败！');
				}
			}
		}else{
			$this->wrong('所添项不能为空');
		}
	}
	public function del(){
		$id=trim($_POST['itemid'],',');
		$mark =$this->_get('mark');
		$arr=explode(',',$id);
		$map['itemid']  = array('in',$arr);
		$data['status']=4;
		$username=$_SESSION['userdata']['username'];
		if($mark==0){//收件箱中的删除;
			foreach($arr as $v){
				$message=M('message')->where("itemid='$v'")->find();
				if($message['isread']==0){//未读站内信					
					$mmessage=M('member')->where("username='$username'")->find();
					$success=M('message')->where("itemid=".$v)->data($data)->save();
					if($mmessage['message']>0){
						$condition['message']=$mmessage['message']-1;
						M('member')->where("username='".$username."'")->data($condition)->save();
					}		
					if($_SESSION['userdata']['message']>=1){
						$_SESSION['userdata']['message'] = $_SESSION['userdata']['message']-1;
					}	
					echo 0;					
				}else{ //已读
					$success=M('message')->where("itemid=".$v)->data($data)->save();
					if($success){
						echo 0;
					}else{
						$this->wrong("删除信件失败");
					}
				}		
			}
		}else if($mark==2){ //已发送列表删除;
			$result=M('message')->where($map)->data($data)->save();
			if($result){
				echo 2;exit;
			}else{
				$this->wrong('删除信件失败');
			}

		}else{  //草稿箱的删除;
			$result=M('message')->where($map)->data($data)->save();
			if($result){
				echo 3;exit;
			}else{
				$this->wrong('删除信件失败');
			}
		}
		
	}
	/* 
	public function clear(){ //清空;
		$add=M('message');
		$username=$_SESSION['userdata']['username'];
		
		$mark=$this->_get('mark');
		if($mark==0){ //清空收件箱
			$map="touser ='$username' and isread=1  and status =3";
			$result=$add->where($map)->select();
			if($result){
				$data['status']=4;  
				$clera=$add->where($map)->data($data)->save();
				if($clera){
					js_alert('清空信件成功！','','/Member/companymessage/mestate/0');
				}
			}else{
				js_alert('清空信件失败！','','/Member/companymessage/mestate/0');
			}
		}else if($mark==1){ //清空已发送列表;
			$map="fromuser ='$username' and isread=1  and status=3";
			$result=$add->where($map)->select();
			if($result){
				$data['status']=4;  
				$sended=$add->where($map)->data($data)->save();
				if($sended){
					js_alert('清空信件成功！','','/Member/companymessage/mestate/2');
				}
			}else{
				js_alert('清空信件失败！','','/Member/companymessage/mestate/2');
			}
		}else{ //清空草稿箱内容;
			$map="fromuser ='$username'  and isread=1  and status=1";
			$result=$add->where($map)->select();
			if($result){
				$data['status']=4;  
				$crafted=$add->where($map)->data($data)->save();
				if($crafted){
					js_alert('清空信件成功！','','/Member/companymessage/mestate/3');
				}
			}else{
				js_alert('清空信件失败！','','/Member/companymessage/mestate/3');
			}
		}
	} */
	public function look(){ //查看信件
	$itemid=$this->_get('itemid');
	$username=$_SESSION['userdata']['username'];
	$message=M('message')->where("itemid='$itemid'")->find();
	$data['isread']=1;
		if(!empty($message)){
				$read =M('message')->where("itemid='$itemid'")->data($data)->save();//站内信已读;
				if($read){
					$res=M('member')->where("username='$username'")->find();
					$map['message']=$res['message']-1;
					$success=M('member')->where("username = '$username'")->data($map)->save();//更新member中站内信个数;
					if($_SESSION['userdata']['message']>=1){
						$_SESSION['userdata']['message']=$_SESSION['userdata']['message']-1;
					}
				}
				$this->assign('message',$message);
		}else{
			js_alert('没有查到信件','','/Member/companymessage/mestate/0');
		}
		$this->display('look');
	}

	public function delete(){ //单条信息删除;
		$itemid=$_POST['itemid'];
		$mark =$this->_get('mark');
		$message=M('message')->where("itemid=$itemid")->find();
		if($message){
			$map['status']=4;
			$delete=M('message')->where("itemid=$itemid")->data($map)->save();
			if($delete){
				if($mark==0){
					echo 0;exit;
				}else if($mark==2){
					echo 2;exit;
				}else{
					echo 3;exit;
				}
			}else{
				if($mark==0){
					$this->wrong('删除失败');
				}else if($mark==2){
					$this->wrong('删除失败');
				}else{
					$this->wrong('删除失败');
				}
			}
		}
	}
	public function editmessage(){
		$itemid=$this->_get('itemid');
		$message=M('message')->where("itemid='$itemid'")->find();
		if($message){
			$this->assign('message',$message);
		}
		$this->display('editmessage');
	}
	public function clearall(){//信件清理;
		$username =$_SESSION['userdata']['username'];
		$craft=$this->_post('craft');  //0收件箱，2已发送，3草稿箱;
		$outdate =strtotime($this->_post('outdate'));
		$data =strtotime($this->_post('data'));
		$isread =$this->_post('isread');
		if(!empty($craft) && !empty($outdate) && !empty($data) && !empty($isread)){
			if($outdate<=$data){ //开始时间小于等于截至时间
				if($craft == 1){ //收件箱清理;
					if($isread ==1){ //若信件已读;
						$map="touser='$username' and status=3 and isread=1 and addtime >= '$outdate' and addtime <= '$data'";
						$result=M('message')->where($map)->select();
						if($result){
							$condition['status']=4;
							$res=M('message')->where($map)->data($condition)->save();
							if($res){
								$this->right('清理成功','forward','/Member/companymessage/mestate/0');
							}else{
								$this->wrong('清理失败');
							}
						}else{
							$this->wrong('暂无已读数据可清理');
						}
					}
				}else if($craft ==2){ //已发送列表清理;
					
						$map="fromuser='$username' and status=2 and addtime >= '$outdata' and addtime <= '$data'";
						$result=M('message')->where($map)->select();
						if($result){
							$condition['status']=4;
							$res=M('message')->where($map)->data($condition)->save();
							if($res){
								$this->right('清理成功','forward','/Member/companymessage/mestate/2');
							}else{
								$this->wrong('清理失败');
							}
						}else{
							$this->wrong('暂无已读数据可清理');
						}
					
				}else{ //草稿箱列表清理;
					
						$map="fromuser='$username' and status=1  and addtime >= '$outdata' and addtime <= '$data'";
						$result=M('message')->where($map)->select();
						if($result){
							$condition['status']=4;
							$res=M('message')->where($map)->data($condition)->save();
							if($res){
								$this->right('清理成功','forward','/Member/companymessage/mestate/3');
							}else{
								$this->wrong('清理失败');
							}
						}else{
							$this->wrong('暂无已读数据可清理');
						}
					
				}
			}else{
				$this->wrong('您的日期范围选择错误');
			}
		}else if(!empty($craft) && !empty($outdate) && !empty($data) && empty($isread)){ //删除所有信件;
			if($outdate<=$data){ //开始时间小于等于截至时间
				if($craft == 1){ //收件箱清理;
					//if($isread ==1){ //若信件已读;
						$map="touser='$username' and status=3  and addtime >= '$outdate' and addtime <= '$data'";
						$result=M('message')->where($map)->select();
						if($result){
							$condition['status']=4;
							$res=M('message')->where($map)->data($condition)->save();
							if($res){
								$this->right('清理成功','forward','/Member/companymessage/mestate/0');
							}else{
								$this->wrong('清理失败');
							}
						}else{
							$this->wrong('暂无已读数据可清理');
						}
					//}
				}else if($craft ==2){ //已发送列表清理;
					
						$map="fromuser='$username' and status=2 and addtime >= '$outdata' and addtime <= '$data'";
						$result=M('message')->where($map)->select();
						if($result){
							$condition['status']=4;
							$res=M('message')->where($map)->data($condition)->save();
							if($res){
								$this->right('清理成功','forward','/Member/companymessage/mestate/2');
							}else{
								$this->wrong('清理失败');
							}
						}else{
							$this->wrong('暂无已读数据可清理');
						}
					
				}else{ //草稿箱列表清理;
					
						$map="fromuser='$username' and status=1  and addtime >= '$outdata' and addtime <= '$data'";
						$result=M('message')->where($map)->select();
						if($result){
							$condition['status']=4;
							$res=M('message')->where($map)->data($condition)->save();
							if($res){
								$this->right('清理成功','forward','/Member/companymessage/mestate/3');
							}else{
								$this->wrong('清理失败');
							}
						}else{
							$this->wrong('暂无已读数据可清理');
						}
					
				}
			}else{
				$this->wrong('您的日期范围选择错误');
			}
		}else{
			$this->wrong('您的清理条件错误,请选择日期');
		}
	}
	function addnote()
	{
		if(isset($_POST['mynote'])){
			$mynote=$_POST['mynote'];
			
			if(strlen($mynote) >= 1 && strlen($mynote) <= 1000){
				$DBR = M('member');
				$DBR->where("userid='".$_SESSION['userdata']['userid']."'")->save($_POST);
				$_SESSION['userdata']['mynote'] = $_POST['mynote'];
				echo 'PASS';
			}else{
				$this->wrong('长度不对');
			}
		}
	}
	
	/*商品收藏 列表 2013年9月1日 20:10:50 wuzhijie */
	/*商品收藏 列表 2013年9月12日 b.thumb、delid for wangbin */
	public function favorite(){
		$userid = $_SESSION['userdata']['userid'];
		$db = M('favorite');
		import ( "ORG.Util.Page" );
		$record_sum =  $db->table("destoon_favorite a,destoon_mall b")->where("a.itemid=b.itemid and a.userid='".$userid."'")->count();
		$Page  = new Page($record_sum,5);
		$show  = $Page->show();
		$listdata= $db->field("b.itemid,b.title,b.thumb,a.addtime")->table("destoon_favorite a,destoon_mall b")->where("a.itemid=b.itemid and a.userid='".$userid."'")->limit($Page->firstRow.','.$Page->listRows)->select();
		//echo $db->getlastsql();dump($listdata);exit;
		$this -> assign( "page", $show);
		$this -> assign( "list", $listdata);
		if(!empty($_POST['way'])){
			$getid=$_REQUEST['id'];
			if (!$getid) $this->wrong('未选择选择任何收藏！') ;
			$getids=implode(',',$getid); //选择一个以上，就用,把值连接起来(1,2,3)这样
			$delid = is_array($getid)?$getids:$getid;//如果是数组，就把用,连接起来的值覆给$delid,否则就覆获取到的没有,号连接起来的值
			$map['itemid']  = array('exp',' IN ('.$delid.') ');
			$Result=$db->where($map)->delete();
			if($Result===false){
				$this->wrong('删除失败！');
			}else{
				$this->right('删除成功！','forward','/Member/favorite');
			}
		}
		$this->display();
	}

}