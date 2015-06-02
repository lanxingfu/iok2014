<?php

class PersonAction extends CommonAction
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
					left join iok_memberinfo mi on m.id = mi.memberid
					left join iok_memberaccount ma on m.id = ma.memberid
			where 
				m.gradeid= 6 and m.deleted=0" . ($search ? " and $search" : '');
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = "
			select
				m.*,
				mi.*,
				ma.*
			from
				iok_member m
					left join iok_memberinfo mi on m.id = mi.memberid
					left join iok_memberaccount ma on m.id = ma.memberid
			where
				m.gradeid = 6 and m.deleted=0" . ($search ? " and $search" : '') . " 
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
						left join iok_memberinfo mi on m.id = mi.memberid
						left join iok_memberaccount ma on m.id = ma.memberid
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
}
?>