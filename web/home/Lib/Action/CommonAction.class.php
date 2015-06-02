<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class CommonAction extends Action
{
	public function _initialize() {
		
		//头部购物车商品数量
		if($_SESSION['mycart']) {
		
			$result =  $_SESSION['mycart']->foreachCart();
			$this->assign('productTotal' , $result['product_type_count']);
		} 
	}
	public function index()
	{
		
	}
	//检测用户名是否合法 排除非法符号 允许 " _ " 和 " - " ;
	function check_name($username) {
		if(strpos($username, '__') !== false || strpos($username, '--') !== false) return false; 
		return preg_match('/^[a-z0-9]{1}[a-z0-9_\-]{0,}[a-z0-9]{1}$/', $username);
	}
	//正则手机号
	function check_mobile($mobile){
		if(preg_match('/^((13)|(15)|(18))(\d{9})$/', $mobile)){
			return true;
		}elseif(preg_match('/^((\+86)|(86))?1[358]\d{9}$/', $mobile)){
			return true;
		}else{
			return false;
		}
	}
	
	//正则座机
	function check_telephone($telephone){
		if(preg_match('/^((0\d{2,3})-)(\d{7,8})(-(\d{3,}))?$/', $telephone)){
			return true;
		}else{
			return false;
		}
	}
/*
 +----------------------------------------------------------
 *增加积分;
 * 
 +----------------------------------------------------------
 * @param string $tablename  数据库表名称
 * @param string $memberid   会员用户id (如有推荐人,即可换成推荐人id)
 * @param string $amount     积分数
 * @param string $reason     积分获得原因
 * @param string $note       备注
	 +----------------------------------------------------------
 * @return boolean 	如果成功返回true，失败返回false
	 +----------------------------------------------------------
*/
function  scoreadd($tablename,$memberid,$amount,$reason,$note){
	$nowtime = time();
	$sql = "select
			id
		from  
			iok_logscore
		where 
			memberid = $memberid
		";
	$res=$this->arr($sql);
	if($res){ //如有积分记录
		$sql2 = "select 
				sum(amount)
			from  
				iok_logscore
			where 
				memberid = $memberid
		";
		
		$num=$this->res($sql2);
		$sum = $num + $amount;
	}else{//如无积分记录
		$sum = $amount;
	}
	$arr = array('memberid'=>$memberid,'amount'=>$amount,'balance'=>$sum,'reason'=>$reason,'note'=>$note,'addtime'=>$nowtime);
	$result=$this->ins($tablename, $arr);
	if($result){
		return true;
	}else{
		return false;
	}
}
/*
 +----------------------------------------------------------
 *站内信发送;
 * 
 +----------------------------------------------------------
 * @param string $tablename  数据库表名称
 * @param string $memberid   发件人id
 * @param string $title      站内信标题
 * @param string $content    站内信内容
 * @param string $orderid    订单id
 * @param string $orderurl    订单链接
 * @param string $tomemberid 收件人id
 * @param string $messagetype 信件类型（member会员间,order订单,system系统）
 * @param string $isread     站内信状态（1为已读,0为未读）
  * @param string $deleted   删除状态(是否删除取值为0，都未作删除1发件人删除，2收件人删除，3都删除)
 +----------------------------------------------------------
 * @return boolean 	如果成功返回true，失败返回false
 +----------------------------------------------------------
*/
function  messageadd($tablename='iok_message',$memberid,$messagetype='',$title,$content,$orderid,$orderurl,$tomemberid,$isread='0',$deleted){
	$nowtime = time();
	//$memberid = $_SESSION['member']['id'];
	$arr = array('memberid'=>$memberid,'title'=>$title,'content'=>$content,'orderid'=>$orderid,
		     'orderurl'=>$orderurl,'tomemberid'=>$tomemberid,'isread'=>$isread,'messagetype'=>$messagetype,
		     'addtime'=>$nowtime);
	$result=$this->ins($tablename, $arr);
	if($result){
		return true;
	}else{
		return false;
	}
}

//获取用户类型
function getUserType($memberid) {
	$memsql = " SELECT gradeid FROM iok_member WHERE id=".$memberid." LIMIT 1 ";
	$memberInfo = $this->rec($memsql);
	return  $memberInfo['gradeid'];
}

//获取商务代表的id
function getServiceStaffid($memberid) {
	$staffidSql = "SELECT servicestaffid FROM iok_member WHERE id=".$memberid." LIMIT 1 ";
	$staffid = $this->rec($staffidSql);
	return  $staffid['servicestaffid'];
}

//******判断是否设置对公账号
function isAccounts($memberid) {
	//获得收款方的银行帐号
	$accountSql = " SELECT 
						bankaccount,bankname,banktruename,bankareaid 
			 		FROM 
						iok_memberaccount 
					WHERE 
						memberid=".$memberid." 
					LIMIT 1 ";
	$accountInfo = $this->rec($accountSql);
	$bool = true;
	if($accountInfo) {
		foreach($accountInfo as $k=>$v) {
			if($v=='') {
				$bool = false;
				break;
			}
		}
	} else {
		$bool = false;
		break;
	}
	return $bool;
}




}
?>