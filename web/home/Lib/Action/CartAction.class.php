<?php 
/********************
 * @Action:CartAction.class.php
 * @Description:Shopping cart controller
 * @Author:lanxingfu
 * @Createtime:2013/12/03
 * 
 * */
class CartAction extends CommonAction {
	
	public function _initialize() {
		//初始化购物车
		if(!is_object($_SESSION['mycart'])) {
            $_SESSION['mycart'] = new CartModel();
        }  
        parent::_initialize();
	}
	public function index() {
        $result =  $_SESSION['mycart']->foreachCart(); 
        $this->assign('mycart' , $result);
        $this->assign('title' , '我行网 - 我的购物车');
		$this->display();
	}
	//把商品放入购物车
	public function putInCart() {
		 $productid = getvar('id');
		 $quantity = getvar('quantity');
		 $userid = $_SESSION['member']['id'];
		 $sql = " SELECT
		 			id,title,price,inventory,moq,thumb
		 		  FROM 
		 			iok_product
		 		  WHERE
		 			id=".$productid."
		 		  LIMIT 1 ";
		$result = $this->rec($sql);
		$_SESSION['mycart']->addProducts($result['id'],$quantity,$userid,$result['title'],$result['price'],$result['inventory'],$result['moq'],$result['thumb']);
		echo json_encode(array('success'=>'200'));
	}
	//删除购物车
	public function deleteCart() {
		$id = getvar('id');
		if($id) {
		    if(strpos($id,',')!=false) {
		    	$newid= explode(',',$id);
		    }
			if(is_array($newid)){
				foreach($newid as $k=>$v){
					$_SESSION['mycart']->deleteProducts($v);
				}
			} else {
				$_SESSION['mycart']->deleteProducts($id);
			}
			echo  json_encode(array('success'=>'ok'));
		} else {
			echo  json_encode(array('success'=>'false'));
		}
		return;
	}
	
	//更新购物车
	public function updateCart() {
		$productid = intval(getvar('productid'));
		$quantity = intval(getvar('quantity'));
		$ordertype = getvar('ordertype');
		if($productid && $quantity) {
			if($ordertype == 1) {
				$productsql = "SELECT price FROM iok_product WHERE id=".$productid." LIMIT 1";
				$result = $this->rec($productsql);
				$price_count = $result['price']*$quantity;
				$price_total = number_format($price_count, 2, '.', '');
			} else {
				$_SESSION['mycart']->updateCart($productid,$quantity);
				$result=$_SESSION['mycart']->foreachCart();
				$price_total = $result['product_price_total'];
			}
			echo json_encode(array('success'=>'ok','price_total'=>$price_total));
		} else {
			echo json_encode(array('success'=>'false'));
		} 
		return;    
	}
	//订单计算
	public function ajaxOrder() {
		$productid = $_POST['productid'];
		$quantity = $_POST['quantity'];
		$arr_note = $_POST['note'];
		
		foreach($productid as $key=>$val) {
			$_SESSION['mycart']->note[array_search($val , $_SESSION['mycart']->product_id)] = $arr_note[$key];
		}
		header("location:/index.php/Cart/settlement");
	}
	
	public function settlement() {
		$memberid = $_SESSION['member']['id'];
		if( !$memberid ) {
			$this->redirect('/Login');
		}
		//设置表单token
		if(!isset($_SESSION['form_tokenid']) || $_SESSION['form_tokenid']=='') {
			set_form_token();
		}
		//判断是否是立即购买还是通过购物车
		$productid = $_REQUEST['productid']; 
		$number = $_REQUEST['number'];
		$ordertype = 0;
		if( $productid ) {
			$cartSql = "SELECT id,memberid,title,price,unit,inventory,moq,thumb,stateid FROM iok_product WHERE id=".$productid." LIMIT 1 ";
			$productInfo =  $this->rec($cartSql);
			$result[$productid]['id'] = $productInfo['id'];
			$result[$productid]['title'] = $productInfo['title'];
			$result[$productid]['price'] = $productInfo['price'];
			$result[$productid]['inventory'] = $productInfo['inventory'];
			$result[$productid]['quantity'] = $number;
			$result[$productid]['moq'] = $productInfo['moq'];
			$result[$productid]['thumb'] = $productInfo['thumb'];
			
			$price_count = $productInfo['price']*$number;
			$price_total = number_format($price_count, 2, '.', '');
			
			$ordertype = 1;
		} else {
			$mycart =  $_SESSION['mycart']->foreachCart();
			$result = $mycart['cart_product_data'];
			$price_total = $mycart['product_price_total'];
		}
		$this->assign('price_total',$price_total);
		$this->assign('mycart' , $result);
		$addressSql = "SELECT 
							* 
						FROM 
							iok_memberaddr 
						WHERE 
							memberid=".$memberid."
						ORDER BY 
							isdefault DESC,addtime DESC
						LIMIT
							5";	
		$addressList=$this->arr($addressSql);
		$this->assign('addressInfo',$addressList);
		
		//发票信息
		$invoice_sql = "SELECT 
							*
						FROM
							iok_memberinvoice
						WHERE
							memberid=".$memberid."
						ORDER BY 
							id DESC
						LIMIT 1  ";
		$memberinvoice = $this->rec($invoice_sql);
		$this->assign('ordertype',$ordertype);
		$this->assign('invoice',$memberinvoice);
		$this->assign('step' , '2');
		$this->assign('title',"我行网 - 订单预览");
		$this->display();
	}
	
	//Ajax添加地址框
	public function ajax_add_address() {
		 $memberid = $_SESSION['member']['id']; 
		 if( !$memberid ) {
		 	echo json_encode(array('success'=>'false','info'=>'nologin'));die;
		 }
		 $insert_data = array();
		 
		 $areaid = getvar('areaid');
		 $address = getvar('address');
		 $postcode = getvar('postcode');
		 $prettyname = getvar('prettyname');
		 $mobile = getvar('mobile');
		 $telephone = getvar('telephone');
		 $listorder = getvar('listorder');
		 $note = getvar('note');
		 $isdefault = getvar('defaultaddr');
		 
		 //非空验证
		 if( intval($areaid)!='' ) {
		 	$insert_data['areaid'] = $areaid;
		 } else {
		 	echo json_encode(array('success'=>'empty','info'=>'areaid'));die;
		 }  
		 
		 if( $address !='' ) {
		 	$insert_data['address'] = $address;
		 } else {
		 	echo json_encode(array('success'=>'empty','info'=>'address'));die;
		 }
		 
		 if( $postcode != '' ) {
		 	$insert_data['postcode'] = $postcode;
		 }
		 
		 if( $prettyname!='' ) {
		 	$insert_data['prettyname'] = $prettyname;
		 } else {
		 	echo json_encode(array('success'=>'empty','info'=>'prettyname'));die;
		 }
		 
		 if( $mobile!='' ) {
		 	$insert_data['mobile'] = $mobile;
		 } else {
		 	echo json_encode(array('success'=>'empty','info'=>'mobile'));die;
		 }
		 
		 if( $telephone!='' ) {
		 	$insert_data['telephone'] = $telephone;
		 }
		 
		 if( $listorder!='' ) {
		 	$insert_data['listorder'] = $listorder;
		 }
		 
		 if( $note!='' ) {
		 	$insert_data['note'] = $note;
		 }
		 
		 if( $isdefault!='' ) {
		 	$insert_data['isdefault'] = $isdefault;
		 }
		 $insert_data['memberid'] = $memberid;
		 $insert_data['addtime'] = time();
		 $lastid = $this->ins('iok_memberaddr',$insert_data);
		 if( $lastid ) {
		 	echo json_encode(array('success'=>'ok','info'=>'insertok'));die;
		 } else {
		 	echo json_encode(array('success'=>'false','info'=>'systemerror'));die;
		 } 
		return;		
	}
	
	//发票添加编辑
	public function excute_invoice() {   
		$memberid = $_SESSION['member']['id'];
		$id = getvar('fid');
		
		$insertData['invoicetype'] = getvar('leixing');
		$insertData['headtitle'] = getvar('taitou');
 
		if( $insertData['invoicetype'] == 1 ) {
			$insertData['company'] =  getvar('danwei_mingcheng1');
			$insertData['content'] = getvar('neirong');
			
			$updateSetSql  = "invoicetype=".$insertData['invoicetype'].",headtitle=".$insertData['headtitle'].",content=".$insertData['content'];
			if( $insertData['company']!='' ) {
				$updateSetSql  = "invoicetype=".$insertData['invoicetype'].",headtitle=".$insertData['headtitle'].",content="."$insertData[content]".",company="."'$insertData[company]'";
			}
		
		} else {
			$insertData['company'] =  getvar('danwei_mingcheng');
			$insertData['taxpayer'] =  getvar('nashuiren');
			$insertData['address'] = getvar('zhuce_dizhi');
			$insertData['telephone'] = getvar('zhuce_dianhua');
			$insertData['bank'] = getvar('kaihu_yinhang');
			$insertData['account'] = getvar('yinhang_zhanghu');
			$insertData['content'] = getvar('om');
			
			$updateSetSql  = "invoicetype=".$insertData['invoicetype'].",headtitle=".'2'.",company="."'$insertData[company]'".",taxpayer="."'$insertData[taxpayer]'".",address="."'$insertData[address]'".",telephone="."'$insertData[telephone]'".",bank="."'$insertData[bank]'".",account="."'$insertData[account]'".",content=".$insertData['content'];
			
		} 
		
		if( empty($id) ) {
			$insertData['addtime'] = time();
			$insertData['memberid'] = $memberid;
			$id = $this->ins('iok_memberinvoice',$insertData);
		} else {
	       $updateSql="UPDATE iok_memberinvoice SET ".$updateSetSql.",updatetime=".time()." WHERE id=".$id." AND memberid=".$memberid;
	       $this->exec($updateSql);
		}
		echo json_encode(array('success'=>'ok','id'=>$id));die;
	}
	
	//删除发票
	public function del_invoice() {
		$id=getvar('fid');
		$memberid = $_SESSION['member']['id'];
		if($id) { 
			$deleteSql = " DELETE FROM iok_memberinvoice WHERE id=$id AND memberid=$memberid";
			$this->exec($deleteSql);
			echo json_encode(array('success'=>'ok'));
		} else {
			echo json_encode(array('success'=>'false'));
		}
	}
	
	//订单入库
	public function orderok() {
		if( $_POST ) {
			if( valid_form_token() == true ) {  //tokenid验证防止重复提交表单
				$ordertype = getvar('ordertype');
				$memberid = $_SESSION['member']['id'];
				$result =  $_SESSION['mycart']->foreachCart();
				$cartmsg = $result['cart_product_data'];
				$note = $_REQUEST['note']; //备注
				if( $ordertype == 1) {
					$cartmsg = array();
					$productid = $_REQUEST['productid'];
					$quantity = $_REQUEST['quantity'];
					foreach( $productid as $k=>$v ) {
						$itemSql = "SELECT id,memberid,title,moq,inventory,price,referenceprice FROM iok_product WHERE id=".$v." ";
						$itemInfo = $this->rec($itemSql);
						$cartmsg[$k] = $itemInfo;
						$cartmsg[$k]['quantity'] = $quantity[$k];
						$cartmsg[$k]['note'] = $note[$k];
					}
				} elseif( count($cartmsg)>0 ) {
					foreach( $cartmsg as $k=>$v ) {
						//获取商品信息
						$itemSql = "SELECT * FROM iok_product WHERE id=".$v['id']." LIMIT 1 ";
						$itemInfo = $this->rec($itemSql);
						$cartmsg[$k]['note'] = $note[$k];
					}
				} else {
					$this->redirect('/Product');
				}
				$addrSql = "SELECT * FROM iok_memberaddr WHERE id=".getvar('addrid'). " LIMIT 1";
				$addressInfo = $this->rec($addrSql);
				//启动事务
				$this->startTrans();
				if( count($cartmsg)>0 ) {
					foreach( $cartmsg as $key=>$val ) {
						//减少库存
						$updateSql = "UPDATE iok_product SET sales=sales+".$val['quantity'].",inventory=inventory-".$val['quantity']." WHERE id=".$val['id'];
						$bool =  $this->exec($updateSql);
							
						$itemSql = "SELECT * FROM iok_product WHERE id=".$val['id']." LIMIT 1 ";
						$itemInfo = $this->rec($itemSql);
						$insertOrderData['buyerstaffid'] = $this->getServiceStaffid($memberid);
						$insertOrderData['sellerstaffid'] = $this->getServiceStaffid($memberid);
						$insertOrderData['buyerid'] = $memberid;
						$insertOrderData['sellerid'] = $itemInfo['memberid'];
						$insertOrderData['productid'] = $val['id'];
						$insertOrderData['propertyname'] = $val['propertyname'];
						$insertOrderData['propertyvalue'] = $val['propertyvalue'];
						$insertOrderData['price'] = $val['price'];
						$insertOrderData['number'] = $val['quantity'];
						$insertOrderData['commissionrate'] = $itemInfo['commission'];
						$insertOrderData['invoiceid'] = getvar('fid');
						$insertOrderData['delivery_truename'] = $addressInfo['prettyname'];
						$insertOrderData['delivery_areaid'] = $addressInfo['areaid'];
						$insertOrderData['delivery_address'] = $addressInfo['address'];
						$insertOrderData['delivery_postcode'] = $addressInfo['postcode'];
						$insertOrderData['delivery_phone'] = $addressInfo['telephone'];
						$insertOrderData['delivery_mobile'] = $addressInfo['mobile'];
						$ordernumber = date('YmdHis').mt_rand(1000,9999);
						$insertOrderData['ordernumber'] =  $ordernumber;  //交易订单号
						$insertOrderData['note'] =  $val['note'];
						$insertOrderData['amount'] = $val['price']*$val['quantity'];
						$insertOrderData['addtime'] = time();
						$insertOrderData['stateid'] = '1';
						$bool2 = $this->ins('iok_order',$insertOrderData);

						//要么全对都执行，要么一个都不执行
						if( !$bool &&  !$bool2) {
							break;
						}
						
						//发送站内信  TODO
						
					}
				}
				
				if( !$bool && !$bool ) {
					$this->rollback(); //失败回滚
				} else {
					$this->commit(); //成功执行
					$this->redirect("/Cart/orderover/ordertype/".$ordertype);
				}
			} else {
				die('不能重复提交表单！');
			}
		} else { //如果非法访问则返回供应列表
			$this->redirect('/Product');
		} 
	}
	
	//订单结束
	public function orderover() {
		$ordertype = getvar('ordertype');
		if($ordertype == 1) {
			$this->redirect('/Order/iokbuy');
		} else {
			unset($_SESSION['mycart']);
			$this->redirect('/Order/iokbuy');
		}
	}
}




?>