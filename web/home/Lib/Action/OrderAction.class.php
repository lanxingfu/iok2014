<?php 
/********************
* @Action:OrderAction.class.php
* @Description:Order controller
* @Author:lanxingfu
* @Createtime:2014/01/02
*
* */
class OrderAction extends CommonAction {
	static  $orderObject;
	public function _initialize() {
		//初始化订单模型
		if(!is_object(self::$orderObject)) {
            self::$orderObject = new OrderModel();
        }  
        parent::_initialize();
	}
	//我是买家
	public function iokbuy() {
		$memberid = $_SESSION['member']['id'];
		//排序字段 默认订单更新时间
		$order = 'o.addtime';
		$sort =  'desc';
		$fWhere = 1;
		$auctionStatus = getvar('auctionStatus');
		if ( method_exists( self::$orderObject, '_filter' ) ) {
			$fWhere = self::$orderObject->_filter ( $fWhere,$auctionStatus,'buyer' );
		}
		$fWhere .= " AND buyerid = ".$memberid;
		
		//取得满足条件的记录数
		$countSql = " SELECT 
						count(o.id) 
					  FROM iok_order o  
								INNER JOIN iok_product pro ON o.productid = pro.id 
					  WHERE ".$fWhere."  
					  LIMIT 1 ";
	 	$count = $this->res($countSql);
		if ($count > 0) {
			import ( "@.ORG.Util.Page" );
			//创建分页对象
			$listRows = 5;
			$p = new Page ( $count, $listRows );
			//分页查询数据
			$listSql = " SELECT 
							o.*,
							pro.title,
							pro.thumb
						 FROM 
							iok_order o  
								INNER JOIN iok_product pro ON o.productid = pro.id
						 WHERE
							".$fWhere."
						 ORDER BY 
							$order $sort,
							o.id
						 LIMIT $p->firstRow , $p->listRows";
			$voList = $this->arr($listSql);
			foreach( $voList as $k=>$v ) {
				//订单总额
				$voList[$k]['amount'] = number_format($v['amount']+$v['shipfare']-$v['discountamount'],2);
				$voList[$k]['ordercomment']= intval($v[ordercomment])&17; 
				$staffSql =  "SELECT 
								i.prettyname,
								i.email,
								i.mobile,
								p.imageurl 
							 FROM 
								iok_memberinfo i 
							 INNER JOIN iok_memberproof p ON i.memberid = p.memberid 
							 WHERE 
								i.memberid=".$v['sellerstaffid']." AND p.prooftypeid=4
							 LIMIT 1";
				$staffInfo = $this->rec($staffSql);		 
				if($staffInfo) {
					$voList[$k]['staffname']=$staffInfo['prettyname']; //被指派的商务代表;
					$voList[$k]['email']=$staffInfo['email'];
					$voList[$k]['mobile']=$staffInfo['mobile'];
					$volist[$k]['headpic']=$staffInfo['imageurl'];
				} else {
					$voList[$k]['staffname']='我行网客服';
					$voList[$k]['email']='service@iokokok.com';
					$voList[$k]['mobile']=' 010-87162577';
					$voList[$k]['headpic']='__IMAGES__/public/iokkf2.png';
				}
				$voList[$k]['addtime']= date("Y-m-d H:i:s",$v['addtime']);
				$voList[$k]['nowstatus'] = self::$orderObject->getOrderState($v['stateid']);
				$voList[$k]['statusico']= self::$orderObject->statusIco($v['stateid'],'buyer',$v['id'],$v['extendreceipttime'],$v['extendinvoicetime'],$v['ordercomment']);
			} 
			
			//分页显示  调用交易管理中的分页
			$page = $p->show();
			//模板赋值显示
			$this->assign ( 'orderlist', $voList );
			$this->assign ( "page", $page );
		}

		$OrderTotal=self::$orderObject->OrderTotal('buyerid',$memberid);
		$this->assign('OrderTotal',$OrderTotal);
		$hover_menu = self::$orderObject->in_menu($auctionStatus);
		$this->assign($hover_menu,'navigation');
		$this->assign('title','我是买家_商务中心_我行网');
		$this->display();
	}
	//我是卖家
	public function ioksell() {
		$memberid = $_SESSION['member']['id'];
		//排序字段 默认订单更新时间
		$order = 'o.addtime';
		$sort =  'desc';
		$fWhere = 1;
		$auctionStatus = getvar('auctionStatus');
		if ( method_exists( self::$orderObject, '_filter' ) ) {
			$fWhere = self::$orderObject->_filter ( $fWhere,$auctionStatus,'seller' );
		}
		$fWhere .= " AND sellerid = ".$memberid;
		
		//取得满足条件的记录数
		$countSql = " SELECT
						count(o.id)
					  FROM iok_order o
								INNER JOIN iok_product pro ON o.productid = pro.id
					  WHERE ".$fWhere."
					  LIMIT 1 ";
		$count = $this->res($countSql);
		if ($count > 0) {
			import ( "@.ORG.Util.Page" );
			//创建分页对象
			$listRows = 5;
			$p = new Page ( $count, $listRows );
			//分页查询数据
			$listSql = " SELECT
							o.*,
							pro.title,
							pro.thumb
						 FROM
							iok_order o
								INNER JOIN iok_product pro ON o.productid = pro.id
						 WHERE
							".$fWhere."
						 ORDER BY
							$order $sort,o.id
						 LIMIT $p->firstRow , $p->listRows ";
			$voList = $this->arr($listSql);
			foreach( $voList as $k=>$v ) {
				//订单总额
				$voList[$k]['amount'] = number_format($v['amount']+$v['shipfare']-$v['discountamount'],2);
				$voList[$k]['ordercomment']= intval($v[ordercomment])&10;
				$staffSql =  "SELECT
								i.prettyname,
								i.email,
								i.mobile,
								p.imageurl
							 FROM
								iok_memberinfo i
							 INNER JOIN iok_memberproof p ON i.memberid = p.memberid
							 WHERE
								i.memberid=".$v['buyerstaffid']." AND p.prooftypeid=4
							 LIMIT 1";
				$staffInfo = $this->rec($staffSql);
				if($staffInfo) {
					$voList[$k]['staffname']=$staffInfo['prettyname']; //被指派的商务代表;
					$voList[$k]['email']=$staffInfo['email'];
					$voList[$k]['mobile']=$staffInfo['mobile'];
					$volist[$k]['headpic']=$staffInfo['imageurl'];
				} else {
					$voList[$k]['staffname']='我行网客服';
					$voList[$k]['email']='service@iokokok.com';
					$voList[$k]['mobile']=' 010-87162577';
					$voList[$k]['headpic']='__IMAGES__/public/iokkf2.png';
				}
				$voList[$k]['addtime']= date("Y-m-d H:i:s",$v['addtime']);
				$voList[$k]['nowstatus'] = self::$orderObject->getOrderState($v['stateid']);
				$voList[$k]['statusico']= self::$orderObject->statusIco($v['stateid'],'seller',$v['id'],$v['extendreceipttime'],$v['extendinvoicetime'],$v['ordercomment']);
			}
				
			//分页显示  调用交易管理中的分页
			$page = $p->show();
			//模板赋值显示
			$this->assign ( 'orderlist', $voList );
			$this->assign ( "page", $page );
		}
		$OrderTotal=self::$orderObject->OrderTotal('sellerid',$memberid);
		$hover_menu = self::$orderObject->in_menu($auctionStatus);
		$this->assign('OrderTotal',$OrderTotal);
		$this->assign($hover_menu,'navigation');
		$this->assign('title','我是卖家_商务中心_我行网');
		$this->display();
	}
	//修改价格
	public  function ModifyPrice() {
		$id = getvar('orderid',NULL, "integer");
		$memberid = $_SESSION['member']['id'];
		if( is_numeric($id) ) {
			$orderInfo =  self::$orderObject->getSingleOrderInfo($id);
			if( $orderInfo['stateid']==1 || $orderInfo['stateid']==2 ) {
				$total_money = $orderInfo['amount'] - $orderInfo['discountamount'] + $orderInfo['shipfare'];
				$orderInfo['amount'] = number_format($total_money,2);
					
				//对方商务代表信息
				$staffInfo=self::$orderObject->getStaffInfo($orderInfo['buyerstaffid']);
				$OrderTotal=self::$orderObject->OrderTotal('sellerid',$memberid);
				
				$orderInfo['nowstatus'] = self::$orderObject->getOrderState($orderInfo['stateid']);
				$orderInfo['buyerusertype'] = $this->getUserType($orderInfo['buyerid']);
				$hover_menu = self::$orderObject->in_menu($orderInfo['stateid']);
				$this->assign( $hover_menu,'navigation' );
				$this->assign( 'staffInfo',$staffInfo );
				$this->assign( 'OrderTotal',$OrderTotal );
				$this->assign( 'info',$orderInfo );
				$this->assign( 'title','修改价格_我是卖家_我行网' );
				$this->display('ModifyPrice');
			} else {
				js_alert('买家已付款或订单已关闭后不能修改价格，请您刷新页面后再操作！');
			}
		} else {
			header("location:$_SERVER[HTTP_REFERER]");
		}
	}
	//修改价格逻辑处理
	public function modifypriceexcute() {
		$id = getvar('id',NULL, "integer");
		$memberid = $_SESSION['member']['id'];
			if( !empty($id) ) {
				$orderInfo =  self::$orderObject->getSingleOrderInfo($id);
				if( $orderInfo['stateid']==1 || $orderInfo['stateid']==2) {
						//运费
						$shipfare = floatval(getvar('shipfare'));
						//优惠金额
						$discountamount = floatval(getvar('discountamount'));
						$receivables = getvar('receivables',1,'integer');
						if($receivables==2) {  //收款方式   1对公，2站内   默认对公
							$receivables=2;
						} else {
							$receivables=1;
						}
						
						if( $shipfare < 0 ) {
							$msg = '您输入的运费金额有误(运费不能于小0)，请重新输入！';
							$success = false;
							echo json_encode(array('success'=>$success,'msg'=>$msg,'url'=>false));die;
						}
						if( $discountamount < 0 || $discountamount > $orderInfo['amount']  ) {
							$msg = '您输入优惠金额有误(不能于小0或大于订单总额)，请重新输入！';
							$success = false;
							echo json_encode(array('success'=>$success,'msg'=>$msg,'url'=>false));die;
						}
						
						//如果是小数则判断只能保留两位小数
						if( strrpos($shipfare,'.')!=false ) {
							$fee_len = strlen(substr($shipfare,strrpos($shipfare,'.')))-1;
							if($fee_len>2) {
								$msg = '运费格式只能保留两位小数点！';
								$success = false;
								echo json_encode(array('success'=>$success,'msg'=>$msg,'url'=>false));die;
							}
						}
						
						if( strrpos($discountamount,'.')!=false ) {
							$privilege_len = strlen(substr($discountamount,strrpos($discountamount,'.')))-1;
							if($privilege_len>2) {
								$msg = '优惠价格格式只能保留两位小数点！';
								$success = false;
								echo json_encode(array('success'=>$success,'msg'=>$msg,'url'=>false));die;
							}
						}
						//佣金比率  	------   算得佣金后插入数据库
						$total_amount =($orderInfo['amount'] - $discountamount);
						$percent = $orderInfo['commissionrate']/100;
						//本次交易佣金总额
						$comisstionmoney = $total_amount*$percent;
						$money_one = $total_amount*0.9;
						$money_two = $total_amount - $money_one;
  						$editpricetime = time();
						$modifySql = "UPDATE
							iok_order
						  SET
							shipfare=".$shipfare.",
							discountamount=".$discountamount.",
							commissionrate=".$comisstionmoney.",
							money_one=".$money_one.",
							money_two=".$money_two.",
							receivables=".$receivables.",
							editpricetime=".$editpricetime."
						  WHERE
						  	id=".$id."
						  LIMIT 1";
						$bool = $this->exec($modifySql);
						
						//TODO  发送站内信
						
						if($bool) {
							$success = 200;
							$msg = '价格修改成功！';
							$url = '/Order/ioksell/auctionStatus/'.$orderInfo['status'];
						} else {
							$success = false;
							$msg = '网络出问题啦，请稍后再试！';
							$url = '/Order/ioksell/auctionStatus/'.$orderInfo['status'];
						}
						echo json_encode(array('success'=>$success,'msg'=>$msg,'url'=>$url));
				} else {
					$success = false;
					$msg = '买家已付款或订单已关闭后不能修改价格，请您刷新页面后再操作！';
					$url = '/Order/ioksell/auctionStatus/'.$orderInfo['status'];
					echo json_encode(array('success'=>$success,'msg'=>$msg,'url'=>$url));
				}  
			} else {
				$success = false;
				$msg = '网络出问题啦，请稍后再试！';
				echo json_encode(array('success'=>$success,'msg'=>$msg,'url'=>$url));
			}
	}
	//确认订单
	public function ConfirmOrder() {
		$memberid = $_SESSION['member']['id'];
		$orderid = getvar('orderid',NULL,'integer');
		if( is_numeric($orderid) ) {
			$orderInfo =  self::$orderObject->getSingleOrderInfo($orderid);
			//TODO
			if( $memberid == $orderInfo['sellerid'] && !empty($orderInfo['sellerstaffid']) ) {
				$staffInfo=self::$orderObject->getStaffInfo($memberid);
			}
			if( !empty($orderInfo) ) {
				//判断如果不是该用户的订单不能操作  （防止数据出错 ）
				if($orderInfo['stateid'] != 1 || $orderInfo['sellerid'] !== $memberid) {
					$msg = '非本人不能操作或该订单已经确认！';
					echo json_encode(array('success'=>false,'msg'=>$msg,'url'=>false));die;
				}
				//对公账号收款判断是否设置对公账号
				if( $orderInfo['receivables']==1 ) {
					$bool = $this->isAccounts($memberid);
					if( $bool==false ) {
						$msg = '您选择的是对公账号收款，请您设置对公帐号再继续交易！';
						$url = ''; //XXX
						echo json_encode(array('success'=>false,'msg'=>$msg,'url'=>$url));die;
					}
				}
				$total_money = $orderInfo['amount'] - $orderInfo['discountamount'];
				//佣金比率  	------   算得佣金后插入数据库
				$percent = $orderInfo['commissionrate']/100;
				//佣金
				$comisstionmoney = $total_money*$percent;
				//卖家实际应得的金额（还没有扣除佣金,不算邮费）
				$money_one = $total_money*0.9;
				$money_two = $total_money - $money_one;
				$confirmtime = time();
				$orderstatus = 1;
				$updateSql = "UPDATE
							iok_order
						  SET
							commissionrate=".$comisstionmoney.",
							money_one=".$money_one.",
							money_two=".$money_two.",
							confirmtime=".$confirmtime.",
							status=".$orderstatus."
						  WHERE
						  	id=".$orderid."
						  LIMIT 1";
				$this->exec($updateSql);
				if( $orderInfo['receivables'] == 1 ) {
					$msg = '订单已经确认，您当前选择的是对公账号收款，收款时需要支付手续费'.get_poundage($total_money+$orderInfo['shipfare']).'元，如果选择站内收款，则不需要手续费，请到修改价格中选择。';
					$url = '/Order/ioksell/auctionStatus/2';
				} else {
					$msg = '订单已经确认，等待买家付款。您选择的是站内收款，交易完成后货款将打到您的站内余额！';
					$url = '/Order/ioksell/auctionStatus/2';
				}
				echo json_encode(array('success'=>200,'msg'=>$msg,'url'=>$url));die;
			}
		} else {
			$msg = '网络出问题了，请尝试刷新页面后再重试！';
			$url = '/Order/ioksell/auctionStatus/1';
			echo json_encode(array('success'=>false,'msg'=>$msg,'url'=>$url));die;
		}
	}
	
	//首信易支付订单
	public function PayeaseOrder() {
		$id = getvar('orderid',NULL,'integer');
		$memberid = $_SESSION['member']['id'];
		if( is_numeric($id) ) {
			//设置表单token
			if(!isset($_SESSION['form_tokenid']) || $_SESSION['form_tokenid']=='') {
				set_form_token();
			}
			//获取单条订单的信息
			$orderInfo =  self::$orderObject->getSingleOrderInfo($id);
			//当前订单状态文字
			$orderInfo['nowstatus'] = self::$orderObject->getOrderState($orderInfo['stateid']);
			//最终付款金额
			$orderInfo['FinalAmount'] = ($orderInfo['amount']+$orderInfo['shipfare']-$orderInfo['discountamount']);
			//获取买家用户类型 
			$orderInfo['buyerusertype'] = $this->getUserType($orderInfo['buyerid']);
			//各状态下的订单总量
			$OrderTotal=self::$orderObject->OrderTotal('buyerid',$memberid);
			$this->assign( 'OrderTotal',$OrderTotal );
			//对方商务代表信息
			$StaffInfo = self::$orderObject->getStaffInfo($orderInfo['sellerstaffid']);
			$this->assign( 'staffInfo',$StaffInfo );
			//获取订单分票信息
			$invoiceInfo = self::$orderObject->getInvoice( $orderInfo['invoiceid'] );
			$this->assign('invoice',$invoiceInfo);
			$this->assign('memberinfo',$_SESSION['member']);
			$this->assign('info',$orderInfo);
			$this->assign( 'hover1','navigation');
			$this->assign('title','首信易支付_我是买家_商务中心_我行网');
			$this->display();
		} else {
			header("location:$_SERVER[HTTP_REFERER]");
		}
	}
	
}
?>