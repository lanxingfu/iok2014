<?php
 
class LogAction extends CommonAction
{
	/* 登陆日志 loglogin */
    function index()
	{	
		// 分页
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 8, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		
		$account = getvar("search_account");
		$data_start = getvar("search_ds");
		$data_end = getvar("search_de");
		$search = '';
		if($data_start > $data_end)
		{
			js_alert('起始时间不能小于结束时间', '', 'window.history.back(-1)');
		}
		if($account)
		{
			$search .= " and account like '%" . $account . "%'";
		}
		if($data_start && $data_end)
		{
			$search .= " and logintime >= ".totimestamp($data_start)." and logintime <= ".totimestamp($data_end);
		}
		
		$sql = "
			select 
				count(id)
			from 
				iok_loglogin
			where
				id != 0 ". ($search ? $search : '') . " ";

		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('search_key', $account);
		$this->assign('search_ds', $data_start);
		$this->assign('search_de', $data_end);
		$this->assign('page', $page);

		$sql = "
			select
				*
			from
				iok_loglogin
			where
				id != 0 ". ($search ? $search: '') . " 
			order by 
				logintime desc
			limit $recordstart,$pagerecords";
		$data = $this->arr($sql);
		// get dictonary for logintype
		$logintype_dict = $this->getdictdata('iok_loglogin','logintype');
		$loginstate_dict = $this->getdictdata('iok_loglogin','loginstate');
		foreach($data as $key=>$val)
		{
			$data[$key]['logintime'] = date('Y-m-d H:i:s', $val['logintime']);
			$data[$key]['logintype'] = in_array($val['logintype'], array_keys($logintype_dict)) ? $logintype_dict[$val['logintype']]['prettyname']: '-';
			$data[$key]['loginstate'] = in_array($val['loginstate'], array_keys($loginstate_dict)) ? $loginstate_dict[$val['loginstate']]['prettyname']: '-';
		}
		$this->assign('data', $data);
		$this->display();
	}
	
	// 审核日志 logaudit
	function logaudit() 
	{
		// 分页
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 8, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		$sql = "
			select 
				count(id)
			from 
				iok_logaudit";
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = "
			select
				*
			from
				iok_logaudit
			order by 
				addtime desc
			limit ".$recordstart.",".$pagerecords."";
		$data = $this->arr($sql);
		$a_id = $this->getdictdata('iok_logaudit','audittype');
		$a_type = $this->getdictdata('iok_logaudit','auditstafftype');
		foreach($data as $key=>$val)
		{
			$data[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
			/* $sql="SELECT 
				a.id, 
				IF(a.auditstafftype = 2,
					(SELECT 
						m.prettyname 
					FROM 
						iok_memberinfo m 
					WHERE 
						a.auditstaffid=m.memberid),
					(SELECT 
						u.prettyname 
					FROM 
						iok_userinfo u 
					WHERE 
						a.auditstaffid=u.userid)
				) AS username
			FROM 
				iok_logaudit a 
			WHERE 
				a.id = '".$val['auditstaffid']."'"; */
			if($val['auditstafftype'] == 1)
			{
				$sql = "SELECT 
							m.prettyname,
							a.id
						FROM 
							iok_logaudit a
							left join iok_memberinfo m on a.auditstaffid=m.memberid
						WHERE 
							a.auditstaffid='".$val['auditstaffid']."'";
			}else{
				$sql = "SELECT 
							u.prettyname,
							a.id
						FROM 
							iok_logaudit a
							left join iok_userinfo u on a.auditstaffid=u.userid
						WHERE 
							a.auditstaffid='".$val['auditstaffid']."'";
			}
			$data[$key]['auditstaffid'] = $this->res($sql);
			$data[$key]['audittype'] = in_array($val['audittype'], array_keys($a_id)) ? $a_id[$val['audittype']]['prettyname']: '-';
			$data[$key]['auditstafftype'] = in_array($val['auditstafftype'], array_keys($a_type)) ? $a_type[$val['auditstafftype']]['prettyname']: '-';
		}
		$this->assign('data', $data);
		$this->display('');
	}
	
	// 积分流水  iok_logscore
	function logscore()
	{	
	
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 8, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		$sql = "
			select 
				count(id)
			from 
				iok_logscore";
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = "
			select
				*
			from
				iok_logscore
			order by 
				addtime desc
			limit $recordstart,$pagerecords";
		$data = $this->arr($sql);
		foreach($data as $key=>$val)
		{
			$data[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
			$data[$key]['memberid'] = $this->get_username($val['memberid']);
		}
		$this->assign('data', $data);
		$this->display();
	}
	
	// 财务流水 iok_logfinance
	function logfinance()
	{
		// 搜索
		$item = getvar('item');
		$keyword = getvar('keyword');
		$search = '';
		$items = array('memberid', 'areaid');
		if($keyword)
		{	$this->assign('keyword', $keyword);
			if(!in_array($item, $items)) $item = 'memberid';
			switch($item)
			{	
				case 'memberid':
					$sql = "select id from iok_member where account = '".$keyword."'";
					$keyword = $this->res($sql);	
					$search = "memberid = '".$keyword."' ";
					break;
				case 'areaid':
					$sql = "select id from iok_area where prettyname = '".$keyword."'";
					$keyword = $this->res($sql);
					$search = "areaid = '".$keyword."' ";
					break;
				default:
					$sql = "select id from iok_member where account = '".$keyword."'";
					$keyword = $this->res($sql);	
					$search = "memberid = '".$keyword."' ";
			}
		}
		$this->assign('item', $item);
		$this->assign('items', getitems($items));
		//分页
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 8, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		$sql = "
			select 
				count(id)
			from 
				iok_logfinance
				where
			memberid != ''  " . ($search ? " and $search" : '') . " ";
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = "
			select
				*
			from
				iok_logfinance
			where
				memberid != '' " . ($search ? " and $search" : '') . " 
			order by 
				addtime desc
			limit $recordstart,$pagerecords";
		$data = $this->arr($sql);
		$f_type = $this->getdictdata('iok_logfinance','type');
		foreach($data as $key=>$val)
		{
			$data[$key]['type'] = in_array($val['type'], array_keys($f_type)) ? $f_type[$val['type']]['prettyname']: '-';
			$data[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
			$data[$key]['areaid'] = $this->get_areaname($val['areaid']);
			$data[$key]['memberid'] = $this->get_username($val['memberid']);
		}
		$this->assign('data', $data);
		$this->display();
	}
	
	// 操作记录 iok_logaction
	function logactions()
	{
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 8, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		$sql = "
			select 
				count(id)
			from 
				iok_logaction";
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = "
			select
				*
			from
				iok_logaction
			order by 
				addtime desc
			limit $recordstart,$pagerecords";
		$data = $this->arr($sql);
		foreach($data as $key=>$val)
		{
			$data[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
			$operater = $this->get_username($val['operaterid']);
			$data[$key]['operaterid'] = $operater['account'];
			$sql = "select prettyname from iok_node where id = '".$val['nodeid']."'";
			$data[$key]['nodeid'] = $this->res($sql);
		}
		$this->assign('data', $data);
		$this->display();
	}
}
?>