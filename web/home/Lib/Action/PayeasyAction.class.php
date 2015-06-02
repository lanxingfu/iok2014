<?php 
/********************
 *@Action:PayEaseAction.class.php
* @Description: PayEase may Controller
* @Author:lanxingfu
* @Createtime:2014/01/08
*
* */
class PayEaseAction extends CommonAction {
	
	public function _initialize() {
		parent::_initialize();
	}
	
	/***
	 * 提交订单数据
	* @transactiontype	:	提交方式  B2B and B2C
	* @author 	:	lanxingfu	2014-01-08 
	*/
	public function SendType() {
	 if( valid_form_token() == true ) {  //tokenid验证防止重复提交表单
		$transactiontype = getvar('transactiontype');
		if($transactiontype=='b2b') {
			$this->send_order_b2b();
		} elseif($transactiontype=='b2c') {
			$this->send_order_b2c();
		} else {
			js_alert('由于网络出现问题暂时不能支付，请您刷新页面后再重新支付！');
		}
	 } else {
	 	js_alert('请不要重复操作！！',C("BASEURL"));
	 }
	}	
	
	/***
	 * 提交订单数据   B2B
	* transactiontype	:	B2B
	* @author 	:	lanxingfu	2014-01-08
	*/
	public function send_order_b2b() {
		$orderid=getvar('orderid',NULL,'integer');
		
		
	}
	
	/***
	 * 提交订单数据   B2C
	* transactiontype	:	B2C
	* @author 	:	lanxingfu	2014-01-08
	*/
	public function send_order_b2c() {
		$memberid = $_SESSION['member']['id'];
		$orderid = getvar('orderid',NULL,'integer');   //POST过来的订单号
		$orderInfo = array();
		if( is_numeric($orderid) && $memberid ) {
			$orderSql = "
				SELECT
					amount,shipfare,discountamount,stateid
				FROM
					iok_order
				WHERE
					buyerid=".$memberid." AND id=".$orderid."
				LIMIT 1
				";
			$orderInfo = $this->rec($orderSql);
			if( count($orderInfo) > 0  ) {
				if($orderInfo['stateid'] != 2) {
					js_alert('卖家没有确认该订单不能进行付款!','/Order/iokbuy/auctionStatus/1');	die();
				}
				if( $orderInfo['stateid'] == 3  ) {
					js_alert('此订单已经支付完成!','/Order/iokbuy/auctionStatus/3');	die();
				}
				
				$v_ymd = date("Ymd");  //订单生成日期YYYYmmdd
				$nowtime = date("His");  //订单生成时间
				$v_mid = '6432';
				$v_oid = $v_ymd.'-'.$v_mid.'-'.$orderid.'-'.$nowtime.randcode('8');
				//收货人姓名(v_rcvname)统一用商户编号的值代替。
				$v_rcvname = '6432';
				//收货人地址，可用商户编号代替
				$v_rcvaddr = iconv("UTF-8","GB2312//IGNORE",getvar('delivery_address') ); 
				//收货人电话
				$v_rcvtel = iconv("UTF-8","GB2312//IGNORE",getvar('delivery_mobile') );  
				//邮编
				$v_rcvpost = getvar('delivery_postcode'); 
				//商户配货状态，0为未配齐，1为已配齐
				$v_orderstatus = '1';
				
				//订货人姓名(v_ordername) 
				$v_ordername  = iconv("UTF-8","GB2312//IGNORE",getvar('delivery_truename') );
				//支付金额 = 订单总额+运费-优惠金额
				$v_amount = $orderInfo['amount']+$orderInfo['shipfare']-$orderInfo['discountamount']; 
				
				$v_moneytype = 0; //支付货币类型  //0为人民币，1为美元，2为欧元，3为英镑，4为日元，5为韩元，6为澳大利亚元，7为卢布(内卡商户币种只能为人民币)
				$v_url = C('BASEURL')."PayEase/b2creceive";
				
				$key = 'test';//商户的密钥
				//拼接七个参数
				//v_moneytype v_ymd v_amount v_rcvname v_oid v_mid v_url七个参数的value值拼成
				$sendStrData = $v_moneytype.$v_ymd.$v_amount.$v_rcvname.$v_oid.$v_mid.$v_url;
				$v_md5info = $this->hmac($key,$sendStrData);
				
				//更新订单表中payeasenumber,和支付方式
				$updateOrderSql = "
						UPDATE
							iok_order
						SET 
							payeasenumber='".$v_oid."',
							paymethod=1
						WHERE
							id=".$orderid."
						LIMIT 1
						";
				$this->exec($updateOrderSql);				
				
				//为避免重复记录，判断是否插过此条记录
				$judgeSql = "
						SELECT 
							count(id)
						FROM 
							iok_payeaseincome
						WHERE
							payeasenumber='".$v_oid."'
						LIMIT 1						
						";
				$oidcount = $this->res($judgeSql);
				if( $oidcount==0 ) {
					//插入首信易表
					$insertTime = time();
					$insertSql  = "
						INSERT INTO
							iok_payeaseincome
							( 
								`payeasenumber`,
								`amount`,
								`moneytype`,
								`orderid`,
								`memberid`,
								`addtime`
							)
						VALUES
							(
								'".$v_oid."',
								'".$v_amount."',
								'".$v_moneytype."',
								'".$orderid."',
								'".$memberid."',
								'".$insertTime."'
							)";
					$this->exec($insertSql);
				}
				$this->assign("v_mid",$v_mid);
				$this->assign("v_oid",$v_oid);
				$this->assign("v_rcvname",$v_rcvname);
				$this->assign("v_rcvaddr",$v_rcvaddr);
				$this->assign("v_rcvtel",$v_rcvtel);
				$this->assign("v_rcvpost",$v_rcvpost);
				$this->assign("v_amount",$v_amount);
				$this->assign("v_ymd",$v_ymd);
				$this->assign("v_orderstatus",$v_orderstatus);
				$this->assign("v_ordername",$v_ordername);
				$this->assign("v_moneytype",$v_moneytype);
				$this->assign("v_url",$v_url);
				$this->assign("v_md5info",$v_md5info);
				$this->display("PayEase:send_order_b2c");
			} else {
				js_alert('该订单不存在！',C('BASEURL'));
				die();
			}
		} else {
			js_alert('当前页面已失效，请您重新支付！',C('BASEURL'));
			die();
		}
	}
	
	// 创建 md5的HMAC
	public function hmac($key, $data){
		$b = 64; // md5加密字节长度
		if (strlen($key) > $b) {
			$key = pack("H*",md5($key));
		}
		$key  = str_pad($key, $b, chr(0x00));
		$ipad = str_pad('', $b, chr(0x36));
		$opad = str_pad('', $b, chr(0x5c));
		$k_ipad = $key ^ $ipad;
		$k_opad = $key ^ $opad;
		return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
	}
	
	//首信易交易订单B2C支付
	public function b2creceive() {
		//接收返回的参数
		$v_oid=$_GET['v_oid'];//订单编号组
		$v_pmode= urldecode($_GET['v_pmode']);//支付方式组
		$v_pstatus=$_GET['v_pstatus'];//支付状态组
		$v_pstring= urldecode($_GET['v_pstring']);//支付结果说明
		$v_amount=$_GET['v_amount'];//订单支付金额
		$v_count=$_GET['v_count'];//订单个数
		$v_moneytype=$_GET['v_moneytype'];//订单支付币种
		$v_mac=$_GET['v_mac'];//数字指纹（v_mac）
		$v_md5money=$_GET['v_md5money'];//数字指纹（v_md5money）
		$v_sign=$_GET['v_sign'];//验证商城数据签名（v_sign）
		//拆分参数
		$sp = '|_|';
		$a_oid = explode($sp, $v_oid);
		$a_pmode = explode($sp, $v_pmode);
		$a_pstatus = explode($sp, $v_pstatus);
		$a_pstring = explode($sp, $v_pstring);
		$a_amount = explode($sp, $v_amount);
		$a_moneytype = explode($sp, $v_moneytype);
		
		$key = 'test';//商户的密钥
		$data1=$v_oid.$v_pmode.$v_pstatus.$v_pstring.$v_count;
		$mac= $this->hmac($key, $data1);
		
		$data2=$v_amount.$v_moneytype;
		$md5money= $this->hmac($key, $data2);
		$v_count = count($a_pstatus);
		if($mac == $v_mac or $md5money == $v_md5money)
		{
			//通过for循环查看该笔通知有几笔订单,并对于更改数据库状态
			for($i=0;$i<$v_count;$i++) {
				//$v_pstatus    1（支付成功）；2（未支付，支付结果不确认）；3（支付失败）；9（已提交，尚未执行付款指令）；
				//验证payeaseinfo中是否提交过信息，如果提交过则提示错误 	 
			    $pstatusSql = "
			    		SELECT 
			    			status
			    		FROM
			    			iok_payeaseincome
			    		WHERE
			    			payeasenumber='".$v_oid."'
			    		LIMIT 1		    		
			    		";
				$pstatus = $this->res($pstatusSql);
				if($pstatus=='20' or $pstatus=='1'){
					js_alert('已支付操作完毕');die;
				}
				//更新首信易支付记录表iok_payeaseincome
				$paytime = time();
				$updatePaseSql = "
						UPDATE
							iok_payeaseincome
						SET
							`paytime`=".$paytime.",
							`status`=".$v_pstatus."
						WHERE
							`payeasenumber`='".$v_oid."'
						LIMIT 1
						";
				$this->exec($updatePaseSql);
				$orderarr = array();
				$orderarr=explode("-",$a_oid[$i]);
				$orderid=$orderarr[2];
				if($a_pstatus[$i]=='20' or $a_pstatus[$i]=='1') {
					//查看订单状态值不对做提示  
					$orderStatusSql = "
							SELECT
								statusid,ordernumber
							FROM
								iok_order
							WHERE
								id=".$orderid."
							LIMIT 1
							";
					$orderinfo = array();
					$orderinfo = $this->rec($orderStatusSql);
					if($orderinfo['statusid'] !=2 ) {
						js_alert('该订单状态有误，不能更新订单状态！');die;
					}
					 //更新订单表状态
			        $updateOrderSql = "
			        		UPDATE
			        			iok_order
			        		SET 
			        			`status`=3,
			        		    `paymethod`=1,
			        			`transactiontype`=2
			        		WHERE
			        			id=".$orderid."
			        		LIMIT 1
			        		";
					$this->exec($updateOrderSql); 
					//支付成功后的返回页面；
					js_alert("订单".$orderinfo[ordernumber]."已经支付成功！",C('BASEURL')."Order/iokbuy/auctionStatus/3");
				} else if($a_pstatus[$i]=='3') {
					$orderinfoSql = "
							SELECT 
								id,ordernumber
							FROM
								iok_order
							WHERE
								id=".$orderid."
							LIMIT 1			
							";
					$orderinfo = array();
					$orderinfo = $this->rec($orderinfoSql);
					
					js_alert("由于".$v_pstring."原因，你的".$orderinfo[ordernumber]."订单没有支付成功！请重新再试一下！",C('BASEURL')."/Order/PayEaseOrder/orderid/".$orderid);
					
					//给当前购买的用户发送一封站内信，内容说明（订单号，………………）
					
					
				} else {
					$orderinfoSql = "
							SELECT 
								id,ordernumber
							FROM
								iok_order
							WHERE
								id=".$orderid."
							LIMIT 1			
							";
					$orderinfo = array();
					$orderinfo = $this->rec($orderinfoSql);
					
					js_alert("由于".$v_pstring."原因，你的".$orderinfo[ordernumber]."订单没有支付成功！请重新再试一下！",C('BASEURL')."/Order/PayEaseOrder/orderid/".$orderid);
					//给当前购买的用户发送一封站内信，内容说明（订单号，………………）
					
				}
			}
	  } else {
		echo("error");
	  }
	}
	
	

	
}




?>