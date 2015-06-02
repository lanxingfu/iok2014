<?php

class Banwordaction extends CommonAction
{	
	function index()
	{	
		// 分页处理
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 5, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;

		$sql = "
			select 
				count(id)
			from 
				iok_banword";
		$totalrecords = $this->res($sql);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		
		$sql = 	"select
					*
				from
					iok_banword
				order by 
					id,addtime
				limit $recordstart,$pagerecords";
		$re = 	$this->arr($sql);
		foreach($re as $key=>$val)
		{
			$re[$key]['addtime'] = date('Y-m-d H:i:s', $val['addtime']);
			$sql = "select account from iok_user where id = '".$val['adduserid']."'";
			$re[$key]['adduserid'] = $this->rec($sql);
		}
		$this->assign('banword', $re);
		$this->display();
	}
	
	function add()
	{	
		$bid = getvar("id");
		$rec = $this->mget();
		if($bid && !$rec)
		{
			$sql = 	"select
						*
					from
						iok_banword
					where
						id = '" . $bid . "'";
			$rec = 	$this->rec($sql);
		}
		$this->assign('rec', $rec);
		$this->display('add');
	}
	function submit()
	{	
		$id = getvar("id");
		if(!empty($id))
		{
			$d['replacefrom'] = getvar("replacefrom");
			$d['replaceto'] = getvar("replaceto");
			$d['enabled'] = getvar("enabled");
			$d['updatetime'] = time();
			$d['updateuserid'] = $_SESSION['user']['id'];
			
			$sql = "update
						iok_banword
					set
						replacefrom = '".$d['replacefrom']."',
						replaceto = '".$d['replaceto']."',
						enabled = '".$d['enabled']."',
						updatetime = '".$d['updatetime']."',
						updateuserid = '".$d['updateuserid']."'
					where
						id = '".$id."'
					limit 1";
			if(empty($d['replacefrom']))
			{
				$this->mset('emptycontent', 'top0');
			}
			if(empty($d['replaceto']))
			{
				$this->mset('emptycontent', 'top1');
			}
			if(isset($_SESSION['_TIPS_']))
			{
				$this->mset(null, null, $d);
				$this->add();
			}else{
				$data = $this->exec($sql);
				if($data)
				{
					js_alert('编辑成功', '?m=Banword&a=index');
				}else{
					js_alert('编辑失败', '', 'window.history.back(-1)');
				}
			}
		}else{
			$d['replacefrom'] = getvar("replacefrom");
			$d['replaceto'] = getvar("replaceto");
			$d['enabled'] = getvar("enabled");
			$d['addtime'] = time();
			$d['adduserid'] = $_SESSION['user']['id'];
			
			if(empty($d['replacefrom']))
			{
				$this->mset('emptycontent', 'top0');
			}
			if(empty($d['replaceto']))
			{
				$this->mset('emptycontent', 'top1');
			}
			if(isset($_SESSION['_TIPS_']))
			{
				$this->mset(null, null, $d);
				$this->add();
			}else{
				$data = $this->ins('iok_banword', $d);
				if($data)
				{
					js_alert('添加成功', '?m=Banword&a=index');
				}else{
					js_alert('添加失败', '', 'window.history.back(-1)');
				}
			}
		}
	}
	
	function del()
	{	
		$id = getvar("id");
		$id=trim($id,',');
		$sql = "
			DELETE FROM
				iok_banword
			where
				id in(".$id.");";
		$data = $this->exec($sql);
		if($data)
		{
			js_alert('删除成功', '?m=Banword&a=index');
		}else{
			js_alert('删除失败', '', 'window.history.back(-1)');
		}
	}
}
?>