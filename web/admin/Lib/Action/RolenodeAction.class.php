<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class RolenodeAction extends CommonAction
{
	// index 代表 list
	function index()
	{
		// 排序
		//		$sortby = getvar('sortby', 'parent');
		//		$sort = getvar('sort', 'desc');
		//		if(!in_array($sortby, array('parent','listorder'))) $sortby = 'parent';
		//		if(!in_array($sort, array('asc', 'desc'))) $sort = 'asc';
		//		switch($sortby)
		//		{
		//			default:
		//				$orderby = 'n.parentid';
		//		}
		//		$this->assign('sortby', $sortby);
		//		$this->assign('sort', $sort);
		// 搜索
		$item = getvar('item');
		$keyword = getvar('keyword');
		$search = '';
		$items = array('name', 'code');
		if($keyword)
		{
			if(!in_array($item, $items)) $item = 'name';
			switch($item)
			{
				case 'code':
					$search = " and n.code like '%" . $keyword . "%'";
					break;
				default:
					$search = " and n.prettyname like '%" . $keyword . "%'";
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
				count(id) as cnt 
			from 
				iok_node n
			where 
				1" . $search;
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		// 获取数据
		$sql = "
			select 
				n.id,
				n.prettyname,
				n.listorder,
				n.isloged,
				n.actiontype,
				p.prettyname as parent
			from 
				iok_node n
					left join iok_node p on n.parentid=p.id
			where 
				1" . $search . "
			order by 
				n.parentid, n.listorder, n.id
			limit $recordstart, $pagerecords";
		$rec = $this->arr($sql);
		// 数据处理
		$this->assign('rec', $rec);
		// 读取模板
		$this->display();
	}
	// detail 表示详细页面
	function detail()
	{
		// 查询数据
		$id = getvar('id', 0, 'integer');
		$rec = array();
		if($id)
		{
			$sql = "
				select 
					n.id,
					n.prettyname,
					n.listorder,
					n.isloged,
					n.actiontype,
					p.prettyname as parent
				from 
					iok_node n
						left join iok_node p on n.parentid=p.id
				where 
					n.id='" . $id . "'
				limit 1";
			$rec = $this->rec($sql);
		}
		if(!$rec)
		{
			js_alert('norecord', '', 'window.history.back(-1)');
		}
		$this->assign('rec', $rec);
		$this->display();
	}
	function edit()
	{
		// 判断权限
		$id = getvar('id', 0, 'integer');
		if($id)
		{
			//checkaccess('USER_EDIT');
		}else
		{
			//checkaccess('USER_ADD');
		}
		// 获取post数据, 并显示错误信息
		$rec = $this->mget();
		// 查询数据
		if($id && !$rec)
		{
			$sql = "
				select 
					n.id,
					n.prettyname,
					n.parentid,
					n.listorder,
					n.isloged,
					n.actiontype
				from 
					iok_node n
				where 
					n.id='" . $id . "'
				limit 1";
			$rec = $this->rec($sql);
			if(!$rec)
			{
				js_alert('norecord', '', 'window.history.back(-1)');
			}
		}
		$sql = "
			select
				n.id,
				n.prettyname
			from
				iok_node n
			where n.parentid=0
			order by n.listorder";
		$parent = $this->ass($sql);
		if(!$parent)
		{
			$parent['id'] = array();
			$parent['prettyname'] = array();
		}
		array_unshift($parent['id'], '0');
		array_unshift($parent['prettyname'], '无');
		$this->assign('parent', $parent);
		$this->assign('rec', $rec);
		$this->display('edit');
	}
	function submit()
	{
		// 权限判断
		$darr = array();
		$darr['id'] = getvar('id', 0, 'integer');
		if($darr['id'])
		{
			//checkaccess('USER_EDIT', true);
		}else
		{
			//checkaccess('USER_ADD', true);
		}
		// 获取数据
		$darr['parentid'] = getvar('parentid', 0, 'integer');
		$darr['prettyname'] = getvar('prettyname');
		$darr['code'] = getvar('code');
		$darr['isloged'] = getvar('isloged', 0, 'integer', array(0, 1));
		$darr['actiontype'] = getvar('actiontype', 2, 'integer', array(1, 2));
		$darr['listorder'] = getvar('listorder', 0, 'integer');
		// 数据合法性及逻辑性判断
		if(!itemcheck('require', $darr['prettyname']))
		{
			$this->mset('invalidname', 'prettyname');
		}else
		{
			$sql = "select id from iok_node where prettyname='" . $darr['prettyname'] . "' and id!='" . $darr['id'] . "' limit 1";
			if($this->res($sql))
			{
				$this->mset('existname', 'name');
			}
		}
		if(!itemcheck('require', $darr['code']))
		{
			$this->mset('invalidcode', 'code');
		}else
		{
			$sql = "select id from iok_node where code='" . $darr['code'] . "' and id!='" . $darr['id'] . "' limit 1";
			if($this->res($sql))
			{
				$this->mset('existcode', 'code');
			}
		}
		// 表示 如果使用了mset，表示有错误提示
		if(isset($_SESSION['_TIPS_']))
		{
			// 将post的数据传回去
			$this->mset(null, null, $darr);
			// 再调用edit方法，重新返回编辑或添加页面
			$this->edit();
		}else
		{
			if($darr['id'])
			{
				$sql = "
					update 
						iok_node
					set 
						parentid='" . $darr['parentid'] . "',
						prettyname='" . $darr['prettyname'] . "',
						listorder='" . $darr['listorder'] . "',
						actiontype='" . $darr['actiontype'] . "',
						isloged='" . $darr['isloged'] . "',
						code='" . $darr['code'] . "'
					where 
						id='" . $darr['id'] . "'
					limit 1";
				$this->exec($sql);
			}else
			{
				$insdata = array('parentid'=>$darr['parentid'], 'prettyname'=>$darr['prettyname'], 'code'=>$darr['code'], 'listorder'=>$darr['listorder'], 'isloged'=>$darr['isloged'], 'actiontype'=>$darr['actiontype']);
				$id = $this->ins('iok_node', $insdata);
				if(!$id)
				{
					$this->mset('submiterr', 'submit');
					$this->mget();
				}
			}
			$this->assign('id', $darr['id']);
			$this->display('submit');
		}
	}
}
?>