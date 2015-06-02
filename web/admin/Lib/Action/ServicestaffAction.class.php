<?php

class ServicestaffAction extends CommonAction
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
				$orderby = 'id';
				break;
			case 'addtime':
				$orderby = 'addtime';
				break;
			default:
				$orderby = 'registertime';
		}
		$this->assign('sortby', $sortby);
		$this->assign('sort', $sort);
		// 搜索
		$item = getvar('item');
		$keyword = getvar('keyword');
		$search = '';
		$items = array('id','account','gradeid','areaid','agentid');
		if($keyword)
		{
			if(!in_array($item, $items)) $item = 'account';
			switch($item)
			{
				case 'id':
					$search = "id like '%" . $keyword . "%'";
					break;
				case 'gradeid':
					$search = "gradeid like '%" . $keyword . "%'";
					break;
				case 'areaid':
					$search = "areaid like '%" . $keyword . "%'";
					break;
				case 'agentid':
					$search = "agentid like '%" . $keyword . "%'";
					break;
				default:
					$search = "account like '%" . $keyword . "%'";
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
				count(id)
			from 
				iok_member m
					left join iok_membercompany mc on m.id = mc.memberid
					left join iok_memberinfo mi on m.id = mi.memberid 
			where 
				(m.gradeid = 4 or m.gradeid = 5 ) and 
				m.deleted=0" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = "
				select
					*
				from
					iok_member m
					left join iok_membercompany mc on m.id = mc.memberid
					left join iok_memberinfo mi on m.id = mi.memberid
				where
					(m.gradeid = 4 or m.gradeid = 5 ) and
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
					ma.*
				from 
					iok_member m 
						left join iok_memberinfo mi on m.id=mi.memberid
						left join iok_memberaccount ma on m.id=ma.memberid
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
	
	function cadd(){

		$data = $this->mget();
		$id = getvar("id");
		if(!$data && $id)
		{
			$sql = "select
						m.*,
						mc.*,
						mi.*
					from
						iok_member m
						left join iok_memberaccount mc on m.id = mc.memberid
						left join iok_memberinfo mi on m.id = mi.memberid
					where
						m.id = '".$id."'
					";
			$data = $this->rec($sql);
			$agentarea = $this->get_areaname($data['agentareaid']);
			$data['agentareaid'] = $agentarea['prettyname'];
			$inviter = $this->get_username($data['inviterid']);
			$data['inviterid'] = $inviter['account'];
			$sql = "SELECT 
						id,prooftypeid,imageurl,uploadid,addtime 
					FROM 
						iok_memberproof 
					WHERE 
						memberid = '".$data['id']."'";
			$data_img = $this->arr($sql);
			foreach($data_img as $key=>$val)
			{
				if($val['prooftypeid'] == 1){
					$data['img_1'] = $val['imageurl'];
				}elseif($val['prooftypeid'] == 2){
					$data['img_2'] = $val['imageurl'];
				}elseif($val['prooftypeid'] == 3){
					$data['img_3'] = $val['imageurl'];
				}elseif($val['prooftypeid'] == 4){
					$data['img_4'] = $val['imageurl'];
				}else{
					die;
				}
			}
		}
		$this->assign('data',$data);
		$this->display('cadd');
	}
	
	function submit (){
		$id = getvar("id");
		$d['account'] = getvar("account");
		$d['saltcode'] = randcode(8);	//干扰码
		$d['passhash'] = getvar("passhash");
		$passhash2 = getvar("passhash2");
		$d['agentareaid'] = getvar("agentareaid");//服务地区ID  
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
		$di['captcha'] = md5(getvar("captcha"));

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
		if($_SESSION['captcha'] != $di['captcha'])
			{
				$errcode = '验证码错误！';
				$this->mset($errcode,'tverify',$data);
			}
		if(isset($_SESSION['_TIPS_']))
		{
			$this->mset(null, null, $d);
			$this->cadd();
			return false;
		}else{
		
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
			$d['membertype'] = 1;
			if(empty($id)){
				$this->ins($d);			
			}else{
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

			
			
			
			}
		}
	
	}
}
?>