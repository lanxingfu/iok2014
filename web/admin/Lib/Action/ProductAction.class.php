<?php

class ProductAction extends CommonAction
{
	function index()
	{

		// 搜索
		$item = getvar('item');
		$keyword = getvar('keyword');
		$search = '';
		$items = array('id','title','custombrandid','company','memberid','account','telephone',);
		if($keyword)
		{
			if(!in_array($item, $items)) $item = 'account';
			switch($item)
			{
				case 'id':
					$search = "p.id like '%" . $keyword . "%'";
					break;
				case 'title':
					$search = "p.title like '%" . $keyword . "%'";
					break;
				case 'custombrandid':
					$search = "p.custombrandid like '%" . $keyword . "%'";
					break;
				case 'company':
					$search = "company like '%" . $keyword . "%'";
					break;
				case 'memberid':
					$search = "p.memberid = '" . $keyword. "'";
					break;
				case 'account':
					$keyword = $this->rec("select id from iok_member where account = '".$account."'");
					$search = "p.memberid = '".$keyword['id']."'";
				case 'telephone':
					$search = "mi.telephone like '%" . $keyword . "%'";
					break;
				default:
					$search = "p.title like '%" . $keyword . "%'";
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
		
		// 
		$sql = "
			select 
				count(p.id)
			from 
				iok_product p
				left join iok_member m on p.memberid = m.id
				left join iok_memberinfo mi on p.memberid = mi.memberid
			where 
				p.deleted=0" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = "
			select
				p.*,
				m.account,
				mi.prettyname,
				mi.mobile,
				mi.email,
				mi.telephone
			from
				iok_product p
				left join iok_member m on p.memberid = m.id
				left join iok_memberinfo mi on p.memberid = mi.memberid
			where
				p.deleted =0 and p.enabled = 1" . ($search ? " and $search" : '') . " 
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
			$data[$key]['memberid'] =  $memberid['account'];//dmp($data[$key]['status']);
			$data[$key]['status'] = in_array($val['status'], array_keys($status)) ? $status[$val['status']]['prettyname']: '-';
			$data[$key]['status'] =  $status['prettyname'];
			$servicestaff = $this->select_represent($val['memberid']);//查询供应商的代理商姓名
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
		$this->assign('data',$data);
		$this->display();
	}
	
	function select_represent($memberid)
	{
		$sql = "
				select
					servicestaffid
				from
					iok_member
				where
					id = '".$memberid."'";
		$data = $this->rec($sql);
		return $data;
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
		$sql = "
			update
				iok_product
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
	function submit(){
	
		$d = array();
		$d['id'] = getvar("id");
		$d['title'] = getvar("title");
		$d['categoryid'] = getvar("categoryid");
		$d['customcategoryid'] = getvar("customcategoryid");//自定义分类
		$d['custombrandid'] = getvar("custombrandid");//品牌
		$d['model'] = getvar("model");//货号型号
		$d['material'] = getvar("material");//成分材质
		$d['placeareaid'] = getvar("placeareaid");//原产地
		$d['imgurl'] = getvar("imgurl");
		$d['imgurl1'] = getvar("imgurl1");
		$d['imgurl2'] = getvar("imgurl2");
		$d['content'] = getvar("content");
		$d['shipareaid'] = getvar("shipareaid");//发货地
		$d['unit'] = getvar("unit");//计量单位
		$d['price'] = getvar("price");//产品单价
		$d['referenceprice'] = getvar("referenceprice");//参考价格
		$d['moq'] = getvar("moq");//最小起订量
		$d['inventory'] = getvar("inventory");//库存总量
		$d['deliverydays']  = getvar("deliverydays");//发货期限
		$d['commission'] = getvar("commission");//佣金比例
		if(!$d['id'])
			{
			if(!itemcheck('require', $darr['title']))
			{
				$this->mset('emptyaccount', 'title');
			}
			if(!itemcheck('require', $darr['categoryid']))
			{
				$this->mset('emptyaccount', 'categoryid');
			}
			if(!itemcheck('require', $darr['custombrandid']))
			{
				$this->mset('emptyaccount', 'custombrandid');
			}
			if(!itemcheck('require', $darr['model']))
			{
				$this->mset('emptyaccount', 'model');
			}
			if(isset($_SESSION['_TIPS_']))
			{
				// 将post的数据传回去
				$this->mset(null, null, $darr);
				// 再调用edit方法，重新返回编辑或添加页面
				$this->add();
			}else
			{
				if($darr['id'])
				{
					$sql = "UPDATE 'iok_product'
								SET
								categoryid = '".$d['categoryid']."',
								customcategoryid = '".$d['customcategoryid']."',
								custombrandid = '".$d['custombrandid']."',
								memberid = '" . $_SESSION['user']['id'] . "',
								title = '".$d['title']."',
								price = '".$d['price']."',
								referenceprice = '".$d['referenceprice']."',
								unit = '".$d['unit']."',
								commission = '".$d['commission']."',
								inventory = '".$d['inventory']."',
								moq = '".$d['moq']."',
								isfreedelivery = '".$d['isfreedelivery']."',
								deliverydays = '".$d['deliverydays']."',
								model = '".$d['model']."',
								material = '".$d['material']."',
								placeareaid = '".$d['placeareaid']."',
								shipareaid = '".$d['shipareaid']."',
								content = '".$d['content']."',
								listorder = '".$d['listorder']."',
								hits = '".$d['hits']."',
								thumb = '".$d['thumb']."',
								thumb1 = '".$d['thumb1']."',
								thumb2 = '".$d['thumb2']."',
								uploadid1 = '".$d['uploadid1']."',
								uploadid2 = '".$d['uploadid2']."',
								uploadid3 = '".$d['uploadid3']."',
								ip = '".$d['ip']."',
								status = '".$d['status']."',
								updatetime = '".$d['updatetime']."',
							WHERE 'id' = '".$id."';";
				}else{
					$this->ins(iok_product,$d);
				}
			}
		}
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