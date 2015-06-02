<?php

class OrderAction extends CommonAction
{
	function index()
	{
		// 排序
		$sortby = getvar('sortby', 'parentid');
		$sort = getvar('sort', 'desc');
		if(!in_array($sortby, array('id', 'addtime', 'confirmtime'))) $sortby = 'id';
		
		if(!in_array($sort, array('asc', 'desc'))) $sort = 'desc';
		switch($sortby)
		{
			case 'id':
				$orderby = 'o.id';
				break;
			case 'addtime':
				$orderby = 'o.addtime';
				break;
			default:
				$orderby = 'o.confirmtime';
		}
		$this->assign('sortby', $sortby);
		$this->assign('sort', $sort);
		// 搜索
		$item = getvar('item');
		$keyword = getvar('keyword');
		$search = '';
		$items = array('oid','pid','title','sellerid','buyerid','status');
		
		if($keyword)
		{
			if(!in_array($item, $items)) $item = 'id';
			switch($item)
			{
				case 'oid':
					$search = "o.ordernumber like '%" . $keyword . "%'";
					break;
				case 'bid':
					$search = "b.id like '%" . $keyword . "%'";
					break;
				case 'title':
					$search = "p.title like '%" . $keyword . "%'";
					break;
				case 'sellerid':
					$sellerid = $this->res("SELECT id FROM iok_member WHERE account = '".$keyword."' ");
					$search = "o.sellerid = '".$sellerid."' ";
					break;
				case 'buyerid':
					$buyerid = $this->res("SELECT id FROM iok_member WHERE account = '".$keyword."' ");
					$search = "o.buyerid = '".$buyerid."'";
					break;
				case 'status':
					$search = "o.stateid = '" . $keyword. "'";
					break;
				default:
					$search = "p.id like '%" . $keyword . "%'";
			}
		}
		$this->assign('item', $item);
		$this->assign('items', getitems($items));
		$this->assign('keyword', $keyword);
		// 额外搜索（例如时间，类别等）
		
		
		// 分页处理
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 5, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		
		$sql = "
			SELECT 
				count(o.id)
			FROM 
				iok_order o
				left join iok_product p on o.productid = p.id
			WHERE 
				o.stateid != ''" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = "
			select
				o.id as oid,o.ordernumber,o.price,o.number,o.sellerid,o.buyerid,o.addtime,o.confirmtime,o.stateid,
				p.id as pid,p.title,p.unit,p.categoryid,p.thumb,p.thumb1,p.thumb2
			from
				iok_order o
				left join iok_product p on o.productid = p.id
			where
				o.stateid != ''" . ($search ? " and $search" : '') . " 
			order by 
				".$orderby." ".$sort."
			limit ".$recordstart." , ".$pagerecords."";
		$data = $this->arr($sql);
		$state = $this->arr("SELECT * FROM iok_orderstate");
		foreach($data as $key=>$val)
		{
			$data[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
			$data[$key]['confirmtime'] = date('Y-m-d H:i:s', $val['confirmtime']);
			$catname = $this->select_catname($val['categoryid']);//查询产品分类
			$data[$key]['categoryid'] = $catname['prettyname'];
			$sellerid=$this->get_username($val['sellerid']);
			$data[$key]['sellerid'] =  $sellerid['account'];
			$buyerid=$this->get_username($val['buyerid']);
			$data[$key]['buyerid'] =  $buyerid['account'];
			$data[$key]['state'] = in_array($val['stateid'], array_keys($state)) ? $state[$val['stateid']]['prettyname']: '-';
			//获取商品大图
			$str0 = "jpg";
			$str1 = "png";
			$str2 = "gif";
			if(stristr($val['thumb'],$str0)){$data[$key]['thumbd']= str_replace(".jpg.thumb","",$val['thumb']);}
			if(stristr($val['thumb'],$str1)){$data[$key]['thumbd']= str_replace(".png.thumb","",$val['thumb']);}
			if(stristr($val['thumb'],$str2)){$data[$key]['thumbd']= str_replace(".gif.thumb","",$val['thumb']);}
		}
		$this->assign('data',$data);
		$this->display();
	}
	
	function select_catname($id){
		$sql = "select
					prettyname 
				from 
					iok_category 
				where 
					id = '".$id."'";
		$data = $this->rec($sql);
		return $data;
	}
	
	function del(){
		$id = getvar("id");
		js_alert('订单不可删除','', 'window.history.back(-1)');
		$sql = "
			update
				iok_order
			set
				deleted = 1
			where
				id = '".$id."'";
		$data = $this->exec($sql);
		if($data)
		{	
			js_alert('删除成功','?m=Product');
		}else{
		
			js_alert('删除失败','', 'window.history.back(-1)');
		}
	
	}
	function detail(){
		$id = getvar("id");
		$sql = "
			select
				o.id as oid,o.ordernumber,o.price,o.number,o.sellerid,o.buyerid,o.addtime,o.confirmtime,o.stateid,
				o.delivery_postcode,o.delivery_address,o.delivery_truename,o.delivery_phone,o.delivery_mobile,
				o.note,o.shipfare,o.discountamount,o.amount,o.delivery_logistics,o.delivery_number,o.deliverytime,
				p.id as pid,p.title,p.unit,p.categoryid,p.thumb,p.thumb1,p.thumb2
			from
				iok_order o
				left join iok_product p on o.productid = p.id
			where
				o.id = '".$id ."' ";
		$data = $this->rec($sql);
		$state = $this->arr("SELECT * FROM iok_orderstate");
		$data['addtime'] = date('Y-m-d H:i:s', $data['addtime']);
		$data['confirmtime'] = date('Y-m-d H:i:s', $data['confirmtime']);
		$catname = $this->select_catname($data['categoryid']);//查询产品分类
		$data['categoryid'] = $catname['prettyname'];
		$seller=$this->get_username($data['sellerid']);
		$data['seller'] =  $seller['account'];
		$buyer=$this->get_username($data['buyerid']);
		$data['buyer'] =  $buyer['account'];
		$data['state'] = in_array($data['stateid'], array_keys($state)) ? $state[$data['stateid']]['prettyname']: '-';
		//获取商品大图
		$str0 = "jpg";
		$str1 = "png";
		$str2 = "gif";
		if(stristr($data['thumb'],$str0)){$data['thumbd']= str_replace(".jpg.thumb","",$data['thumb']);}
		if(stristr($data['thumb'],$str1)){$data['thumbd']= str_replace(".png.thumb","",$data['thumb']);}
		if(stristr($data['thumb'],$str2)){$data['thumbd']= str_replace(".gif.thumb","",$data['thumb']);}
		//获取买卖双方评论
		$seller_c = $this->res("SELECT 
									content 
								FROM 
									iok_ordercomment 
								WHERE orderid = '".$data['oid']."' AND productid = '".$data['pid']."' AND 
									memberid = '".$data['sellerid']."' AND tomemberid = '".$data['buyerid']."'");
		$buyer_c = $this->res("SELECT 
									content 
								FROM 
									iok_ordercomment 
								WHERE orderid = '".$data['oid']."' AND productid = '".$data['pid']."' AND 
									memberid = '".$data['buyerid']."' AND tomemberid = '".$data['sellerid']."'");
		$this->assign('seller_c',$seller_c);
		$this->assign('buyer_c',$buyer_c);
		$this->assign('data',$data);
		$this->display();
	}
	function add()
	{
		$data = $this->mget();
		$id = getvar("id");
		if($id && !$data){
			$id =  getvar("id");
			$sql = "select
						*
					from
						iok_product
					where
						id = ".$id." and enabled = 1" ;
		$data = $this->rec($sql);

		$sql = "select prettyname from iok_category where id = '".$data['categoryid']."'";
		$categoryid = $this->rec($sql);
		$data['categoryid'] = $categoryid;
		}
		$this->assign('data',$data);
		$this->display('add');
	
	}

	function check()
	{
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 5, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;

		$sql = "
			select 
				count(id)
			from 
				iok_product
			where 
				status = 'request' and enabled != 0 and deleted=0" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		/* $sql = "
			select
				p.*,pp.* 
			from 
				iok_product p
				left join iok_productproperty pp on pp.productid=p.id
			where
				p.status = 'request' and p.enabled != 0
			order by 
				$orderby $sort
				p.id,p.addtime
			limit $recordstart, $pagerecords"; */
		$sql = "
			select
				* 
			from 
				iok_product
			where
				status = 'request'
			order by 
				$orderby $sort
				id,addtime
			limit $recordstart, $pagerecords";	
		$data = $this->arr($sql);
		$status = $this->getdictdata('iok_product','status');
		foreach($data as $key=>$val)
		{
			$data[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
			$catname = $this->select_catname($val['categoryid']);//查询产品分类
			$data[$key]['categoryid'] = $catname['prettyname'];
			$memberid=$this->get_username($val['memberid']);//查询供应商姓名
			$data[$key]['memberid'] =  $memberid['account'];
			$servicestaff = $this->select_represent($val['memberid']);//查询供应商的代理商姓名
			$data[$key]['status'] = in_array($val['status'], array_keys($status)) ? $status[$val['status']]['prettyname']: '-';
			$servicestaff = $this->get_username($servicestaff['servicestaffid']);
			$data[$key]['servicestaff'] = $servicestaff['account'];
			//获取商品大图
			$str0 = "jpg";
			$str1 = "png";
			$str2 = "gif";
			if(stristr($val['thumb'],$str0)){$data[$key]['thumbd']= str_replace(".jpg.thumb","",$val['thumb']);}
			if(stristr($val['thumb'],$str1)){$data[$key]['thumbd']= str_replace(".png.thumb","",$val['thumb']);}
			if(stristr($val['thumb'],$str2)){$data[$key]['thumbd']= str_replace(".gif.thumb","",$val['thumb']);}
		}
		$this ->assign('data',$data);
		$this ->display('check');
	}
	
	function pass(){
		$id = getvar("id");
		$updatetime = time();
		$ip = get_client_ip();
		$addtime = time();
		$sql = "update
					iok_product
				set
					status = 'audit',
					updatetime = '".$updatetime."'
				where
					id = '".$id."'";
		$data = $this->ass($sql);
		$d = array();
			$d['audittype'] = 'product';
			$d['itemid'] = $id;
			$d['auditstafftype'] = 'user';
			$d['auditstaffid'] = $_SESSION['user']['id'];
			$d['result'] = 1;
			$d['note'] = '已通过';
			$d['ip'] = $ip;
			$d['addtime'] = $addtime;
		$this->ins('iok_logaudit', $d);
		$this->check();
	
	}
	
	function nopass(){
		$id = getvar("id");
		$note = getvar("note");
		$updatetime = time();
		$ip = get_client_ip();
		$addtime = time();
		$sql = "update
					iok_product
				set
				status = 'refuse'
				where
					id = '".$id."'";
		$data = $this->exec($sql);
		$d = array();
			$d['audittype'] = 'product';
			$d['itemid'] = $id;
			$d['auditstafftype'] = 'user';
			$d['auditstaffid'] = $_SESSION['user']['id'];
			$d['result'] = 0;
			$d['note'] = $note;
			$d['ip'] = $ip;
			$d['addtime'] = $addtime;
		$this->ins('iok_logaudit', $d);
		$this->check();
	
	}
}
?>