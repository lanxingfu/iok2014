<?php
/**
 * 广告管理
 * @author lee
 *
 */
class AdAction extends Action{
	function _initialize(){
		if(!$_SESSION['user']['id']){
			header('Location: /iokadmin.php?m=Login');
		}
	}
	//列表
	function index(){
		$search_type=getvar("search_type");
		$search_key=getvar("search_key");
		$search_adplaceid=getvar("search_adplaceid");
		
		$sql = "select
					id,
					prettyname,
					adplaceid,
					price,
					introduce,
					note,
					listorder,
					enabled,
					addtime,
					adduserid
				from
					iok_ad
				where
					deleted=0 
				";
		$where='';
		if($search_key){
			if($search_type=="prettyname"){
				$where=" and prettyname like '%".likefilter($search_key)."%' ";
			}else if($search_type=="introduce"){
				$where=" and introduce like '%".likefilter($search_key)."%' ";
			}else if($search_type=="user"){
				$where=" and adduserid like '%".likefilter($search_key)."%' ";
			}
		}
		if($search_adplaceid){
			$where.=" and adplaceid=$search_adplaceid ";
		}
		$csql = "select
					count(id)
				from
					iok_ad
				where
					deleted=0 
				";
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 10, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		$totalrecords = $this->res($csql.$where);
		$listarr=$this->arr($sql.$where." order by id desc limit $recordstart,$pagerecords");
		foreach($listarr as $key=>$l){
			if($l['id']){
				$addsql="
					select
						prettyname
					from
						iok_userinfo
					where
						userid=".$l['adduserid'];
				$listarr[$key]['adduserid']=$this->res($addsql);
			}
			$listarr[$key]['addtime']=date('Y-m-d',$l['addtime']);
			if($l['enabled']){
				$listarr[$key]['enabled']="启用";
			}else{
				$listarr[$key]['enabled']="关闭";
			}
		}
		
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('search_type', $search_type);
		$this->assign('search_key', $search_key);
		$this->assign('search_adplaceid', $search_adplaceid);
		$this->assign('page', $page);
		$this->assign('listarr',$listarr);
		$this->display();
	}
	
	//增加
	function add(){
		$id=getvar("id");
		$adplaceid=$id ? $id : "";
		$this->assign("id",$id);
		$this->display();
	}
	//执行增加动作
	function submit(){
		$adplaceid=getvar("adplaceid");
		$price=getvar("price");
		$introduce=getvar("introduce");
		$note=getvar("note");
		$listorder=getvar("listorder");
		$enabled=getvar("enabled");
		$prettyname=getvar("prettyname");
		$addtime=time();
		$adduserid=$_SESSION['user']['id'];
		$sql="insert into
					iok_ad(
						prettyname,
						adplaceid,
						price,
						introduce,
						note,
						listorder,
						enabled,
						addtime,
						adduserid
					)
				values(
						'".$prettyname."',
						'".$adplaceid."',
						'".$price."',
						'".$introduce."',
						'".$note."',
						'".$listorder."',
						'".$enabled."',
						'".$addtime."',
						'".$adduserid."'
				)
			";
		$result=$this->exec($sql);
		if($result){
			echo "<script>alert('添加成功');</script>";
			echo "<script>window.location.href='add';</script>";
		}
	}
	
	//修改
	function edit(){
		$id=getvar("id");
		$sql = "select
					id,
					prettyname,
					adplaceid,
					price,
					introduce,
					note,
					listorder,
					enabled,
					addtime,
					adduserid
				from
					iok_ad
				where
					id=$id
			";
		$arr=$this->rec($sql);
		
		$this->assign('arr', $arr);
		$this->display();
	}
	//执行修改
	function doedit(){

		$id=getvar("id");
		$adplaceid=getvar("adplaceid");
		$price=getvar("price");
		$introduce=getvar("introduce");
		$note=getvar("note");
		$listorder=getvar("listorder");
		$enabled=getvar("enabled");
		$prettyname=getvar("prettyname");
		$uptime=time();
		$upuser=$_SESSION['user']['id'];
		$sql="
			update
				iok_ad
			set	
				prettyname='".$prettyname."',
				adplaceid='".$adplaceid."',
				price='".$price."',
				introduce='".$introduce."',
				note='".$note."',
				listorder='".$listorder."',
				enabled='".$enabled."',
				updatetime='".$uptime."',
				updateuserid='".$upuser."'
			where
				id=".$id."
			";
		
		$result=$this->exec($sql);
		if($result){
			echo "<script>alert('修改成功');</script>";
			echo "<script>window.location.href='/iokadmin.php/Ad/index';</script>";
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
				iok_ad
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
			echo "<script>window.location.href='/iokadmin.php/Ad/index';</script>";
		}
	}
}
?>