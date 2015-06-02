<?php
/**
 * 关于我们管理
 * @author lee
 *
 */
class AboutusAction extends Action{
	function _initialize(){
		if(!$_SESSION['user']['id']){
			header('Location: /iokadmin.php?m=Login');
		}
	}
	//列表 
	function index(){
		$sql="
			select
				id,
				prettyname
			from
				iok_category
			where
				categorytype=5
		";
		$type=$this->arr($sql);
		$this->assign('type',$type);
		
		$sql="
			select
				a.id,
				a.categoryid,
				a.title,
				a.hits,
				a.enabled,
				FROM_UNIXTIME(a.addtime,'%Y-%m-%d %H:%i:%s') addtime,
				a.adduserid,
				c.prettyname
			from
				iok_aboutus a
			left join
				iok_category c
			on
				a.categoryid=c.id
			where
				a.deleted=0 
		";
		$search_type=getvar("search_type");
		$search_title=getvar("search_title");
		$search_s=strtotime(getvar("search_s"));
		$search_e=strtotime(getvar("search_e"));
		$where="";
		if($search_type)
			$where.=" and a.categoryid=".$search_type;
		if($search_title)
			$where.=" and a.title like'%".likefilter($search_title)."%'";
		if($search_s && $search_e)
			$where.=" and ".$search_e.">a.updatetime>".$search_s;
		$csql = "select
					count(id)
				from
					iok_aboutus a
				where
					deleted=0 
				";
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 10, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		$totalrecords = $this->res($csql.$where);
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('page', $page);
		$list=$this->arr($sql.$where." order by id desc limit $recordstart,$pagerecords");
		foreach($list as $k=>$l){
			if($l['id']){
				$addsql="
					select
						prettyname
					from
						iok_userinfo
					where
						userid=".$l['adduserid'];
				$list[$k]['adduserid']=$this->res($addsql);
			}
		}
		$this->assign('list',$list);
		$this->display();
		
	}
	
	//增加
	function add(){
		$sql="
			select
				id,
				prettyname
			from
				iok_category
			where
				categorytype=5
		";
		$type=$this->arr($sql);
		$this->assign('type',$type);
		$this->display();
	}
	//执行增加动作
	function submit(){
		$categoryid=getvar("categoryid");
		$enabled=getvar("enabled");
		$title=getvar("title");
		$content=getvar("content");
		$addtime=time();
		$adduserid=$_SESSION['user']['id'];
		$sql="
			insert into
				iok_aboutus(
					categoryid,
					title,
					content,
					enabled,
					addtime,
					adduserid)
			values(
				'".$categoryid."',
				'".$title."',
				'".$content."',
				'".$enabled."',
				'".$addtime."',
				'".$adduserid."'
			)
		";
		$result=$this->exec($sql);
		if($result){
			echo "<script>alert('操作成功');</script>";
			echo "<script>window.location.href='/iokadmin.php/Aboutus/add';</script>";
		}  
	}
	
	//修改
	function edit(){
		$id=getvar("id");
		$sql="
			select
				id,
				prettyname
			from
				iok_category
			where
				categorytype=5
		";
		$type=$this->arr($sql);
		$sqlinfo="
			select
				id,
				categoryid,
				title,
				content,
				enabled
			from
				iok_aboutus
			where
				id=".$id;
		$info=$this->rec($sqlinfo);
		$this->assign("type",$type);
		$this->assign("info",$info);
		$this->display();
	}
	//执行修改
	function doedit(){
		$categoryid=getvar("categoryid");
		$abid=getvar("abid");
		$enabled=getvar("enabled");
		$title=getvar("title");
		$content=getvar("content");
		$uptime=time();
		$upuserid=$_SESSION['user']['id'];
		$sql="
			update
				iok_aboutus
			set
				categoryid='".$categoryid."',
				enabled='".$enabled."',
				title='".$title."',
				content='".$content."',
				updatetime='".$uptime."',
				updateuserid='".$upuserid."'
			where
				id=".$abid;
		$result=$this->exec($sql);
		if($result){
			echo "<script>alert('操作成功');</script>";
			echo "<script>window.location.href='/iokadmin.php/Aboutus/index';</script>";
		}  
	}
	
	//删除
	function delete(){
		$id=getvar("id");
		$id=trim($id,',');
		$deleteuserid=$_SESSION['user']['id'];
		$deletetime=time();
		$sql="
			update
				iok_aboutus
			set	
				enabled=0,
				deleted=1,
				deleteuserid='".$deleteuserid."',
				deletetime='".$deletetime."'			
			where
				id in(".$id.")
			";
		$result=$this->exec($sql);
		if($result){
			echo "<script>alert('删除成功');</script>";
			echo "<script>window.location.href='/iokadmin.php/Aboutus/index';</script>";
		}
	}
}
?>