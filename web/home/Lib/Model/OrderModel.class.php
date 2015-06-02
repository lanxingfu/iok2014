<?php 
/**************
 * @Model:OrderModel.class.php
 * @Description:Order processing
 * @Author:lanxingfu
 * @Createtime:2014/01/05
 * 
 * */
class OrderModel extends Action {
	
	public function getOrderState($stateid) {
		$orderstateSql = "
			SELECT
				*
			FROM
				iok_orderstate
			WHERE
				1
			";
		$orderState=$this->idx($orderstateSql,'id',true);
		return $orderState[$stateid]['prettyname'];
	}
	//******过滤条件
	public function _filter(&$fWhere,$auctionStatus,$mark) {
		switch ($auctionStatus) {
			case '1':  //待确认订单
				$fWhere = " o.stateid=".$auctionStatus;
				break;
			case '2': //待买家付款
				$fWhere = " o.stateid=".$auctionStatus;
				break;
			case '3': //待卖家发货
				$fWhere = " o.stateid=".$auctionStatus;
				break;
			case '4': //待买家收获
				$fWhere = " o.stateid=".$auctionStatus;
				break;
			case '5': //交易成功待评价
				$fWhere = " o.stateid=".$auctionStatus;
				if($mark == 'seller') {
					$number=10;
				}else{
					$number=17;
				}
				$fWhere .= " AND (o.ordercomment&$number) = 0 ";
				break;
			case '6': //申诉中
				$fWhere = " o.stateid=".$auctionStatus;
				break;
			case '11': //待买家收发票
				$fWhere = " o.stateid=".$auctionStatus;
				break;
			case 'history': //历史订单
				$lastThreeMonth = mktime(date('h'),date('i'),date('s'),date('m')-3,date('d'),date('y'));
				$fWhere = " o.addtime < ".$lastThreeMonth;
				break;
			default://近期订单  近三个月的订单
				$lastThreeMonth = mktime(date('h'),date('i'),date('s'),date('m')-3,date('d'),date('y'));
				$fWhere = " o.addtime >= ".$lastThreeMonth;
				break;
		}
		return $fWhere;
	}
	//ico status
	public function statusIco($orderstatus,$mark,$orderid,$extendreceipttime='',$extendinvoicetime='',$ordercomment='') {
		if( $mark == 'buyer') {
			switch($orderstatus) {
				case '1':
					$icostr =
					'<a href="/Order/tradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/buyer">订单详情</a>
		<a href="javascript:void(0);" onclick="close_order('.$orderid.');" id="close_order_'.$orderid.'">关闭交易</a>';
					break;
				case '2':
					$icostr =
					'<a href="/Order/tradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/buyer">订单详情</a>
					<a href="javascript:void(0);" onclick="close_order('.$orderid.');" id="close_order_'.$orderid.'">关闭交易</a>
					<!--<if condition="$_SESSION[userdata][groupid] neq 15 ">
					<a href="/Order/payment/itemid/{$vo[itemid]}">余额付款</a>
					</if>-->
					<a href="/Order/PayeaseOrder/orderid/'.$orderid.'">首信易付款</a>';
					break;
				case '3':
					$icostr =
					'<a href="/Order/tradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/buyer">订单详情</a>';
					break;
				case '4':
					$icostr =
					'<a href="/Order/tradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/buyer">订单详情</a>';
					if($extendreceipttime == 0) {
						$icostr .= '<a href="javascript:extenTime("extend_time_$id");" title="延长收货时间">延长时间</a>'; 			}
					$icostr .='<a href="/Order/confirmReceipt/itemid/{$vo[itemid]}">确认收货</a>';
					break;
				case '5':
					$icostr =
					'<a href="/Order/tradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/buyer">订单详情</a>';
					if($ordercomment == 0) {
						$icostr .= '<a href="/Order/valuation/itemid/{$vo[itemid]}/other_side/buyer" >评价</a>'; 					}
					break;
				case '6':
					$icostr =
					'<a href="javascript:;">撤销申述</a><a href="javascript:;">查看解释</a>';
					break;
				case '9':
					$icostr =
					'<a href="/Order/tradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/buyer">订单详情</a>';
					break;
				case '10':
					$icostr =
					'<a href="/Order/tradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/buyer">订单详情</a>';
					break;
				case '11':
					$icostr =
					'<a href="/Order/tradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/buyer">订单详情</a>';
					if($extendinvoicetime == 0) {
						$icostr .= '<a href="javascript:extendInvoice("extend_invoice_$id");" title="延长收发票时间" >延长时间</a>';
					}
					$icostr .='<a href="/Order/confirmInvoice/itemid/{$vo[itemid]}" >确认收发票</a>';
					break;
				default:
					$icostr = '';
			}
		} elseif( $mark == 'seller' ) {
			switch($orderstatus) {
				case '1':
					$icostr =
					'<a href="/Order/TradeDetail/orderid/'.$orderid.'/orderstatus/{$vo[status]}">订单详情</a>
					  <a href="javascript:void(0);" onclick="close_order('.$orderid.');" id="close_order_'.$orderid.'">关闭交易</a>
	                  <a href="/Order/ModifyPrice/orderid/'.$orderid.'">修改价格</a>
	                  <a href="javascript:void(0);" id="confirm_order_'.$orderid.'" onclick="confirm_order('.$orderid.')" >确认订单</a>';
					  break;
				case '2':   
					$icostr =
					'<a href="/Order/TradeDetail/itemid/'.$orderid.'/orderstatus/'.$orderstatus.'">订单详情</a>
					<a href="javascript:void(0);" onclick="close_order('.$orderid.');" id="close_order_'.$orderid.'">关闭交易</a>
					<a href="/Order/ModifyPrice/orderid/'.$orderid.'">修改价格</a>';
					break;
				case '3':
					$icostr =
					'<a href="/Order/TradeDetail/orderid/'.$orderid.'/orderstatus/'.$orderstatus.'">订单详情</a>
		 
		<a href="/Order/ConfirmDelivery/orderid/'.$orderid.'">确认发货</a>';
					break;
				case '4':
					$icostr =
					'<a href="/Order/TradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/seller">订单详情</a>';
					if( $extendreceipttime==0 ) {
						$icostr.= '<a href="javascript:extenTime("extend_time_'.$orderid.'");" title="延长收货时间">延长时间</a>';
					}
					break;
				case '5':
					$icostr =
					'<a href="/Order/tradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/seller">订单详情</a>';
					if($ordercomment == 0) {
						$icostr .= '<a href="/Order/valuation/itemid/{$vo[itemid]}/other_side/seller" >评价</a>'; 					}
					break;
				case '6':
					$icostr =
					'<a href="javascript:;" style="margin-right:55px; display:inline;">解释</a>';
					break;
				case '9':
					$icostr =
					'<a href="/Order/tradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/seller">订单详情</a>';
					break;
				case '10':
					$icostr =
					'<a href="/Order/tradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/seller">订单详情</a>';
					break;
				case '11':
					$icostr =
					'<a href="/Order/tradeDetail/itemid/{$vo[itemid]}/status/{$vo[status]}/other_side/seller">订单详情</a>';
						
					if ($extendinvoicetime == 0) {
						$icostr .= '<a href="javascript:extendInvoice("extend_invoice_'.$orderid.'");" title="延长收发票时间" >延长时间</a>';
					}
					break;
				default:
					$icostr = '';
			}
		} else {
			$icostr = '';
		}
		return $icostr;
	}
	
	//******统计订单各状态下的数量
	public function OrderTotal($markfield,$memberid) {
		 
		//待确认订单
		$orderStatus['queren'] = $this->res( "SELECT COUNT(id) FROM iok_order WHERE stateid=1 AND ".$markfield." =".$memberid );
		//待买家付款
		$orderStatus['daifukuan'] = $this->res( "SELECT COUNT(id) FROM iok_order WHERE stateid=2 AND ".$markfield."=".$memberid );
		//待卖家发货
		$orderStatus['daifahuo'] = $this->res( " SELECT COUNT(id) FROM iok_order WHERE stateid=3 AND ".$markfield."=".$memberid );
			
		//待买家收货
		$orderStatus['daishouhuo'] = $this->res( " SELECT COUNT(id) FROM iok_order WHERE stateid=4 AND ".$markfield."=".$memberid );
			
		//待买家收发票
		$orderStatus['daishoupiao'] = $this->res( "SELECT COUNT(id) FROM iok_order WHERE stateid=11 AND ".$markfield."=".$memberid );
		//交易成功待评价
		$eWhere = 'stateid=5';
		if( $markfield == 'sellerid') {
			$number=10;
		} elseif( $markfield == 'buyerid' ) {
			$number=17;
		} else {
			$number=0;
		}
		$eWhere .= " AND (ordercomment&".$number.") = 0 AND ".$markfield."=".$memberid;
		$orderStatus['daipingjia'] = $this->res( "SELECT COUNT(id) FROM iok_order WHERE ".$eWhere );
		
		//申诉中
		$orderStatus['shenshu'] = $this->res( "SELECT COUNT(id) FROM iok_order WHERE stateid=6 AND ".$markfield."=".$memberid );
		
		return $orderStatus;
	}
	//当前所在页背景图加色
	public function in_menu($status,$ordecomment=false) {
		switch ($status) {
			case '1':  //待确认订单
				$_hover = 'hover0';
				break;
			case '2': //待买家付款
				$_hover = 'hover1';
				break;
			case '3': //待卖家发货
				$_hover = 'hover2';
				break;
			case '4': //待买家收获
				$_hover = 'hover3';
				break;
			case '5': //交易成功待评价
				if( $ordecomment&10 >0 || $ordecomment&17 >0 ) {
					$_hover = 'jq';
				} else {
					$_hover = 'hover4';
				}
				break;
			case '6': //申诉中
				$_hover = 'hover5';
				break;
			case '11': //待买家收发票
				$_hover = 'hover10';
				break;
			case 'history': //历史订单
				$_hover = 'history';
				break;
			default:
				$_hover = 'jq';//近期订单  近一个月的订单
				break;
		}
		return $_hover;
	}
	
	//******商务代表基本信息
	public function getStaffInfo($memberid) {
		$staffInfoSql = " 
				SELECT  
					mi.*,m.account 
				FROM 
					iok_memberinfo mi LEFT JOIN iok_member m ON m.id=mi.memberid 
				WHERE 
					mi.memberid=".$memberid." 
				LIMIT 1 ";
		
		$result = $this->rec($staffInfoSql);
		if( count($result)>0 ) {
			$staffInfo = $result;
		} else {
			$staffInfo['prettyname'] = '我行网客服';
			$staffInfo['email'] = 'service@iokokok.com';
			$staffInfo['mobile'] = '010-87162577';
			$staffInfo['telephone'] = '010-87162577';
		}
		return  $staffInfo;
	}
	
	//获得当前订单的发票信息
	public function getInvoice($invoiceid) {
		$invoiceSql = " SELECT
							 *
					    FROM
							iok_memberinvoice
					   	WHERE
							id=".$invoiceid."
						LIMIT 1";
		$invoiceInfo = $this->rec($invoiceSql);
		if($invoiceInfo) {
			if($invoiceInfo['invoicetype']==1) {
				$invoiceInfo['invoicetype'] = '普通发票';
				if($invoiceInfo['headtitle']==1) {
					$invoiceInfo['headtitle'] = '个人';
				} elseif($invoiceInfo['headtitle']==2) {
					$r_com = $invoiceInfo['company']?$invoiceInfo['company']:'暂无';
					$invoiceInfo['headtitle'] = '单位 (' . $r_com . ')';
				}
			} elseif($invoiceInfo['invoicetype']==2) {
				$invoiceInfo['invoicetype'] = '增值税发票';
				$invoiceInfo['invoicetype'] = $invoiceInfo['company']?$invoiceInfo['company']:'暂无';
			}
		}
		return $invoiceInfo;
	}
	
	public function getSingleOrderInfo($orderid) {
		$orderInfoSql = "SELECT
							o.*,
							p.title,
							p.thumb,
							p.deliverydays
						 FROM
							iok_order o INNER JOIN iok_product p ON o.productid=p.id
						 WHERE
							o.id=".$orderid."
						 LIMIT 1";
		$orderInfo = $this->rec($orderInfoSql);
		return $orderInfo;
	}
	
	//******展示各状态下的付款信息
	public function showBill($orderInfo,$memberid) {
	
		$amount = number_format($orderInfo['amount'] + $orderInfo['fee'] - $orderInfo['privilege'],2);//订单总货款
		if( $orderInfo['seller']==$memberid ) {
			//如果是对公账号收款则收手续费
			if( $orderInfo['disposable']==1 ) {
				$orderInfo['shouhuoyingshou'] = ($orderInfo['money_one'] + $orderInfo['fee'] - $orderInfo['c_money']);
				//如果是对公账号收款则有手续费
				if( $orderInfo['receivables']==0 ) {
					$orderInfo['poundage'] = get_poundage($orderInfo['shouhuoyingshou']) + get_poundage($orderInfo['money_two']);		}
			}else{
				//全款
				$orderInfo['shouhuoyingshou'] = ($orderInfo['money_one']+$orderInfo['money_two']+ $orderInfo['fee']);
				//如果是对公账号收款则有手续费
				if( $orderInfo['receivables']==0 ) {
					$orderInfo['poundage'] = get_poundage($orderInfo['shouhuoyingshou']);
				}
			}
				
		}elseif( $orderInfo['buyerid']==$memberid ) {
			if( $orderInfo['disposable']==1 ) {
				$orderInfo['shouhuoyingfu'] = ($orderInfo['money_one'] + $orderInfo['fee']);
			}else{
				//全款
				$orderInfo['shouhuoyingfu'] = ($orderInfo['money_one']+$orderInfo['money_two']+ $orderInfo['fee']);
			}
		}
		$orderInfo['jiaoyiyingshou'] = ($amount - $orderInfo['c_money']);
		$orderInfo['amount'] = number_format($orderInfo['amount'] + $orderInfo['fee'] - $orderInfo['privilege'],2);   //订单总货款
		$orderInfo['ninety'] = $orderInfo['money_one'] + $orderInfo['fee']; //百分之九十货款加运费
		$orderInfo['jiaoyishishou'] =  $orderInfo['jiaoyiyingshou']-$orderInfo['poundage'];
		return $orderInfo;
	}
	
}

?> 