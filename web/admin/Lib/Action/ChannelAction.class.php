<?php

class ChannelAction extends CommonAction
{
	function index()
	{
		// 排序
		$sortby = getvar('sortby', 'parentid');
		$sort = getvar('sort', 'desc');
		if(!in_array($sortby, array('id', 'addtime', 'registertime'))) $sortby = 'id';
		
		if(!in_array($sort, array('asc', 'desc'))) $sort = 'desc';
		switch($sortby)
		{
			case 'id':
				$orderby = 'm.id';
				break;
			case 'addtime':
				$orderby = 'm.addtime';
				break;
			default:
				$orderby = 'm.registertime';
		}
		$this->assign('sortby', $sortby);
		$this->assign('sort', $sort);
		// 搜索
		$item = getvar('item');
		$keyword = getvar('keyword');
		$search = '';
		$items = array('id','account','gradeid','areaid','agentareaid');
		if($keyword)
		{
			if(!in_array($item, $items)) $item = 'account';
			switch($item)
			{
				case 'id':
					$search = "m.id like '%" . $keyword . "%'";
					break;
				case 'gradeid':
					$search = "m.gradeid like '%" . $keyword . "%'";
					break;
				case 'areaid':
					$search = "a.prettyname like '%" . $keyword . "%'";
					break;
				case 'agentareaid':
					$search = "m.agentareaid = a.id and a.prettyname like '%" . $keyword . "%'";
					break;
				default:
					$search = "m.account like '%" . $keyword . "%'";
			}
		}
		$this->assign('item', $item);
		$this->assign('items', getitems($items));
		$this->assign('keyword', $keyword);
		// 额外搜索（例如时间，类别等）
		
		
		// 分页处理
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 10, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		
		// 
		$sql = "
			select 
				count(m.id)
			from 
				iok_member m
					left join iok_area a on m.areaid = a.id
			where 
				(m.gradeid = 1 or
				m.gradeid = 2 or
				m.gradeid = 3) and m.deleted=0" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = "
				select
					m.*,
					a.prettyname
				from
					iok_member m
						left join iok_area a on m.areaid = a.id
				where
					(m.gradeid = 1 or
					m.gradeid = 2 or
					m.gradeid = 3) and
					m.deleted=0" . ($search ? " and $search" : '') . " 
				order by 
					$orderby $sort,
					m.id,m.registertime
				limit $recordstart, $pagerecords";
		$data = $this->arr($sql);
		foreach($data as $key=>$val)
		{
			$data[$key]['registertime'] = date('Y-m-d H:i:s', $val['registertime']);
			$data[$key]['areaid'] = $this->get_areanames($val['areaid']);
			if(!empty($data[$key]['inviterid']))
			{
			    $invest=$this->get_username($val['inviterid']);
				$data[$key]['inviterid'] =  $invest['account'];
			}else{
				$data[$key]['inviterid'] =	"系统推荐";
			}
		}
		$this->assign('data',$data);
		$this->display();
	}
	
	function detail()
	{
		// 查询数据
		$id = getvar('id', 0, 'integer');
		$rec = array();
		if($id)
		{
			$sql = "
				select
					m.*,
					mi.*,
					ma.*,
					mc.*
					
				from 
					iok_member m 
						left join iok_memberinfo mi on m.id=mi.memberid
						left join iok_memberaccount ma on m.id=ma.memberid
						left join iok_membercompany mc on m.id=mc.memberid
				where
					m.id='" . $id . "'
				limit 1";
			$rec = $this->rec($sql);
		}
		if(!$rec)
		{
			js_alert('norecord', '', 'window.history.back(-1)');
		}
		// 如果存在，查询更新人prettyname
		if($rec['updateuserid'])
		{
			$sql = "select prettyname from iok_memberinfo where userid='".$rec['updateuserid']."' limit 1";
			$rec['updateuser'] = $this->res($sql);
		}
		$this->assign('rec', $rec);
		$this->display();
	}
	
	function cadd()
	{	
		$id = getvar('id', 0, 'integer');
		$rec = $this->mget();
		// 查询数据
		if($id && !$rec)
		{
			$sql = "
				select 
					m.*,mi.*
				from 
					iok_member m 
						left join iok_memberinfo mi on m.id=mi.memberid
				where 
					m.id='" . $id . "' and
					m.deleted=0
				limit 1";
			$rec = $this->rec($sql);
			$rec['inviterid'] = $this->get_username($rec['inviterid']);
			$rec['areaid'] = $this->get_areanames($rec['areaid']);
			$rec['agentareaid'] = $this->get_areanames($rec['agentareaid']);
			//$rec['addrareaid'] = $this->get_areanames($rec['addrareaid']);暂时决定前台录入详细地址
			if(!$rec)
			{
				js_alert('norecord', '', 'window.history.back(-1)');
			}
		}
		$this->assign('data', $rec);
		$this->display('cadd');
	}
	
	function submit()
	{	
		$id = getvar("id");
		$d['account'] = getvar("account");
		$d['saltcode'] = randcode(8);	//干扰码
		$d['passhash'] = getvar("passhash");
		$passhash2 = getvar("passhash2");
		$d['agentareaid'] = getvar("agentareaid");//渠道代理地区ID  
		$d['areaid'] = getvar("areaid");//注册所在地区ID
		if(empty($d['areaid']))
		{
			$d['areaid'] = getvar("agentareaid");
		}
		$d['registerip'] = get_client_ip();
		$d['registertime'] = time();
		$d['addtime'] = time();
		$d['adduserid'] = $_SESSION['user']['id'];
		$di['gender'] = getvar("gender");
		$di['prettyname'] = getvar("prettyname");
		$di['mobile'] = getvar("mobile");
		$di['email'] = getvar("email");
		$di['qq'] = getvar("qq");
		$di['telephone'] = getvar("telephone");
		$di['fax'] = getvar("fax");
		$di['postcode'] = getvar("postcode");
		$di['addrareaid'] = getvar("areaid0");//地址ID
		$di['address'] = getvar("address");//详细地址
		
		if(!itemcheck('require', $d['account']))
		{
			$this->mset('emptyaccount', 'account');
		}elseif(!itemcheck('account', $d['account']))
		{
			$this->mset('invalidaccount', 'account');
		}else
		{	if(empty($id)){
				$sql = "select id from iok_member where account='" . $d['account'] . "' limit 1";
				if($this->res($sql))
				{
					$this->mset('existaccount', 'account');
				}
			}
		}	
		if(!itemcheck('passwd', $d['passhash']))
		{
			$this->mset('invalidpasswd', 'passhash');
		}elseif($d['passhash'] != $passhash2)
		{
			$this->mset('notmatch', 'passhash2');
		}
		if(!itemcheck('require', $d['agentareaid']))
		{
			$this->mset('emptycontent', 'agentareaid');
		}
		if($di['mobile'] && !itemcheck('mobile', $di['mobile']))
		{
			$this->mset('invalidmobile', 'mobile');
		}
		if($di['email'])
		{
			if(!itemcheck('email', $di['email']))
			{
				$this->mset('invalidemail', 'email');
			}else
			{	if(empty($id)){
					$sql = "select memberid from iok_memberinfo where email='" . $di['email'] . "'  limit 1";
					if($this->res($sql))
					{
						$this->mset('existemail', 'email');
					}
				}
			}
		}
		
		$d['inviterid'] = getvar("inviterid");//根据获取的推荐人username查出其ID并入库
		if(!empty($d['inviterid'])){
			$sql0 = "select id from iok_member where account = '".$d['inviterid']."'"; 
			$inviterid = $this->rec($sql0);
			$d['inviterid'] = $inviterid['id'];
			if(empty($d['inviterid']))
			{
				$this->mset('推荐人不存在！', 'inviterid');
			}
		}else{
			$d['inviterid'] == 0;
		}
		if(isset($_SESSION['_TIPS_']))
		{
			$this->mset(null, null, $d);
			$this->cadd();
		}
		$d['passhash'] = md5(getvar("passhash").$d['saltcode']);//新密码MD5加密并拼接扰乱码
		$sql = "select parentid,pparentid from iok_area where id = '".$d['areaid']."'";
		$d['gradeid'] = $this->rec($sql);//根据所选择的代理地区查出其代理级别并入库
		if($d['gradeid']['parentid'] == 0)
		{
			$d['gradeid'] = 1;
		}elseif($d['gradeid']['parentid'] != 0 && $d['gradeid']['pparentid'] == 0){
			$d['gradeid'] = 2;
		}else{
			$d['gradeid'] = 3;
		}
		$d['membertype'] = 2; //代理商必须为企业类型
		if(!empty($id)){
			$sql = "
				update 
					iok_member 
				set 
					account='" . $d['account'] . "',
					saltcode='" . $d['saltcode'] . "',
					passhash='" . $d['passhash'] . "',
					agentareaid='" . $d['agentareaid'] . "',
					areaid='" . $d['areaid'] . "',
					updatetime='" . $d['addtime'] . "',
					updateuserid='" . $d['adduserid'] . "',
					inviterid='" . $d['inviterid'] . "',
					gradeid='" . $d['gradeid'] . "'
				where 
					id='" .$id. "'
				limit 1";
			$data1 = $this->exec($sql);
			$sql = "
				update
					iok_memberinfo
				set
					gender='".$di['gender']."',
					prettyname='".$di['prettyname']."',
					mobile='".$di['mobile']."',
					email='".$di['email']."',
					qq='".$di['qq']."',
					telephone='".$di['telephone']."',
					fax='".$di['fax']."',
					postcode='".$di['postcode']."',
					addrareaid='".$di['addrareaid']."',
					address='".$di['address']."'
				where
					memberid='" . $id . "'
				limit 1";
			$data2 = $this->exec($sql);
			if($data1 && $data2)
			{
				js_alert('修改成功', '?m=Channel');
			}else{
				js_alert('修改失败', '', 'window.history.back(-1)');
			}
		}else{

			$mid= $this->ins('iok_member', $d);
			if($mid){
				$di['memberid'] = $mid;
				$iok_m = M('memberinfo');
				$data0 = $this->ins('iok_memberinfo', $di);
				$sql1 = "INSERT INTO iok_memberaccount(memberid) VALUES ('".$di['memberid']."')";
				$sql2 = "INSERT INTO iok_membercompany(memberid) VALUES ('".$di['memberid']."')";
				$data1 = $this->exec($sql1);
				$data2 = $this->exec($sql2);
			}
			if($data1 && $data2)
			{	
				js_alert('添加成功', '?m=Channel');
			}else{
			
				js_alert('添加失败', '', 'window.history.back(-1)');
			}
		}
	}

	function del(){
		$id = getvar("id");
		$sql = "
			update
				iok_member
			set
				deleted = 1
			where
				id = '".$id."'";
		$data = $this->exec($sql);
		
		if($data)
		{	
			js_alert('删除成功','?m=Channel');
		}else{
		
			js_alert('删除失败','', 'window.history.back(-1)');
		}
	
	
	
	}
}
?>