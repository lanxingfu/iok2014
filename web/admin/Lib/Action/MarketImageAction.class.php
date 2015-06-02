<?php

class MarketImageAction extends CommonAction
{
	function index()
	{
		// 排序
		$sortby = getvar('sortby', 'parentid');
		$sort = getvar('sort', 'desc');
		if(!in_array($sortby, array('id', 'addtime', 'updatetime'))) $sortby = 'id';
		
		if(!in_array($sort, array('asc', 'desc'))) $sort = 'desc';
		switch($sortby)
		{
			case 'addtime':
				$orderby = 'addtime';
				break;
			case 'updatetime':
				$orderby = 'updatetime';
				break;
			default:
				$orderby = 'id';
		}
		$this->assign('sortby', $sortby);
		$this->assign('sort', $sort);
		// 搜索
		$item = getvar('item');
		$keyword = getvar('keyword');
		$search = '';
		$items = array('id','title');
		
		if($keyword)
		{
			if(!in_array($item, $items)) $item = 'id';
			switch($item)
			{
				case 'id':
					$search = "id = '".$keyword."'";
					break;
				case 'title':
					$search = "title like '%" . $keyword . "%'";
					break;
				default:
					$search = "id = '".$keyword."'";
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
		
		$sql = "SELECT
				  COUNT(id)
				FROM
					iok_market
				WHERE
					categoryid = 10007 AND enabled = 1" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = "SELECT
				  *
				FROM
					iok_market
				WHERE
					categoryid = 10007 AND enabled = 1" . ($search ? " and $search" : '') . " 
				ORDER BY 
					".$orderby." ".$sort."
				LIMIT ".$recordstart." , ".$pagerecords."";
		$data = $this->arr($sql);
		foreach($data as $key=>$val)
		{
			$catname = $this->select_catname($val['categoryid']);//查询产品分类
			$data[$key]['categoryid'] = $catname['prettyname'];
			$data[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
			$data[$key]['adduserid']=$this->get_username($val['adduserid']);
			$data[$key]['updatetime'] = date('Y-m-d H:i:s', $val['updatetime']);
			$data[$key]['updateuserid']=$this->get_username($val['updateuserid']);
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
	
	function add()
	{	
		if($_FILES["imageurl"]){
			import("@.ORG.Net.UploadFile");
			/* 获取后缀名 小写 */
			$up_ext = strtolower(strstr($_FILES["imageurl"]["tmp_name"],'.'));
			//上传路径设置
			$upPaths = '/file/upload/pageshow/'.date("Ym/d",time())."/";
			$upPaths_up='.'.$upPaths;
			//文件保存路径设置
			$savePaths = 'http://'.$_SERVER['HTTP_HOST'].$upPaths;
			dmp($savePaths);exit;
			//导入上传类
			$upload = new UploadFile();
			//设置上传文件大小
			$upload->maxSize = 2048000;
			//设置上传文件类型
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');
			//目录上传检测及更改目录权限
			if(!is_dir($upPaths_up)){
				@mkdir($upPaths_up,0777,true);
			}
			//设置附件上传目录
			$upload->savePath = $upPaths_up;
			//设置上传文件规则
			$upload->saveRule = time().randcode(6,'hex');
			
			 if ($upload->upload()) {
				//取得成功上传的文件信息
				$uploadList = $upload->getUploadFileInfo();
			}
		}
		$data = $this->mget();
		$id = getvar("id");
		if($id && !$data){
			$id =  getvar("id");
			$sql = "select
						*
					from
						iok_market
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
	
	function submit()
	{
		$d['id'] = getvar("id");
		$d['title'] = getvar("title");
		$d['categoryid'] = getvar("categoryid");
		$d['content'] = getvar("content");
		$d['linkurl'] = getvar("linkurl");
		$d['imageurl'] = getvar("imageurl");
		$d['switchon'] = getvar("switchon");
		$d['addtime'] = time();
		$d['adduserid'] = $_SESSION['user']['id'];
		if(!itemcheck('require', $d['title']))
		{
			$this->mset('emptyaccount', 'title');
		}
		if(isset($_SESSION['_TIPS_']))
		{
			$this->mset(null, null, $d);
			$this->add();
		}
		if(empty($d['id']))
		{	
			dmp($_POST);
			echo 111111111;exit;
		
		$data = $this->ins($d);
		}else{
			$sql = "
			UPDATE 
				iok_market
			SET 
				`id` = 'id',
				`categoryid` = 'categoryid',
				`title` = 'title',
				`content` = 'content',
				`linkurl` = 'linkurl',
				`imageurl` = 'imageurl',
				`uploadid` = 'uploadid',
				`switchon` = 'switchon',
				`listorder` = 'listorder',
				`enabled` = 'enabled',
				`deleted` = 'deleted',
				`addtime` = 'addtime',
				`adduserid` = 'adduserid',
				`updatetime` = 'updatetime',
				`updateuserid` = 'updateuserid',
				`deletetime` = 'deletetime',
				`deleteuserid` = 'deleteuserid'
			WHERE 
				`id` = 'id'";
		
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