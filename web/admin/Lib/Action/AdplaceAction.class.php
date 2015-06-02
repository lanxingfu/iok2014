<?php
/**
 * 广告位管理
 * @author lee
 *
 */
class AdplaceAction extends Action{
	function _initialize(){
		if(!$_SESSION['user']['id']){
			header('Location: /iokadmin.php?m=Login');
		}
	}
	//列表
	function index(){
		$prettyname=getvar("prettyname");
		$adtype=getvar("adtype");
		$width=getvar("width");
		$height=getvar("height");
		
		$sql = "select
					id,
					prettyname,
					name,
					adtype,
					height,
					width,
					introduce,
					listorder,
					enabled,
					addtime,
					adduserid
				from
					iok_adplace
				where
					deleted=0 
				";
		$where='';
		if($prettyname){
			$where.=" and prettyname like '%".likefilter($prettyname)."%' ";
		}
		if($adtype){
			$where.=" and adtype='".$adtype."' ";
		}
		if($width){
			$where.=" and width= '".$width."' ";
		}
		if($height){
			$where.=" and height= '".$height."' ";
		}
		$csql = "select
					count(id)
				from
					iok_adplace
				where
					deleted=0 
				";
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 10, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		$totalrecords = $this->res($csql.$where);
		$listarr=$this->arr($sql.$where." order by id desc limit $recordstart,$pagerecords");
		// echo "<pre>";var_dump($listarr);echo "</pre>";
		foreach($listarr as $key=>$l){
			$listarr[$key]['addtime']=date('Y-m-d',$l['addtime']);
			if($l['enabled']){
				$listarr[$key]['enabled']="启用";
			}else{
				$listarr[$key]['enabled']="关闭";
			}
			switch($l['adtype']){
				case "image":
					$listarr[$key]['adtype']="图片";
				break;
				case "slideshow":
					$listarr[$key]['adtype']="幻灯片";
				break;
				case "text":
					$listarr[$key]['adtype']="文字、文本";
				break;
				case "mobile":
					$listarr[$key]['adtype']="手机类";
				break;
			}
		}
		
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('prettyname', $prettyname);
		$this->assign('adtype', $adtype);
		$this->assign('width', $width);
		$this->assign('height', $height);
		$this->assign('page', $page);
		$this->assign('listarr',$listarr);
		$this->display();
	}
	
	//增加
	function add(){
		$this->display();
	}
	//执行增加动作
	function submit(){
		$prettyname=getvar("prettyname");
		$name=getvar("name");
		$adtype=getvar("adtype");
		$height=getvar("height");
		$width=getvar("width");
		$introduce=getvar("introduce");
		$listorder=getvar("listorder");
		$enabled=getvar("enabled");
		$addtime=time();
		$adduserid=$_SESSION['user']['id'];
		$sql="insert into
					iok_adplace(
						prettyname,
						name,
						adtype,
						height,
						width,
						introduce,
						listorder,
						enabled,
						addtime,
						adduserid
					)
				values(
						'".$prettyname."',
						'".$name."',
						'".$adtype."',
						'".$height."',
						'".$width."',
						'".$introduce."',
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
					name,
					adtype,
					height,
					width,
					introduce,
					listorder,
					enabled,
					addtime,
					adduserid
				from
					iok_adplace
				where
					id=$id
			";
		$arr=$this->rec($sql);
		// var_dump($arr);
		$this->assign('arr', $arr);
		$this->display();
	}
	//执行修改
	function doedit(){
		$id=getvar("id");
		$prettyname=getvar("prettyname");
		$name=getvar("name");
		$adtype=getvar("adtype");
		$height=getvar("height");
		$width=getvar("width");
		$introduce=getvar("introduce");
		$listorder=getvar("listorder");
		$enabled=getvar("enabled");
		$addtime=getvar("addtime");
		$adduserid=getvar("adduserid");
		$uptime=time();
		$upuser=$_SESSION['user']['id'];
		$sql="
			update
				iok_adplace
			set	
				prettyname='".$prettyname."',
				name='".$name."',
				adtype='".$adtype."',
				height='".$height."',
				width='".$width."',
				introduce='".$introduce."',
				listorder='".$listorder."',
				enabled='".$enabled."',
				addtime='".$addtime."',
				adduserid='".$adduserid."',
				addtime='".$addtime."'
			where
				id=".$id."
			";
		
		$result=$this->exec($sql);
		if($result){
			echo "<script>alert('修改成功');</script>";
			echo "<script>window.location.href='/iokadmin.php/Adplace/index';</script>";
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
				iok_adplace
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
			echo "<script>window.location.href='/iokadmin.php/Adplace/index';</script>";
		}
	}
	
	//向广告位添加广告
	function addad(){
		$id=getvar("id");
		echo "<script>window.location.href='/iokadmin.php/Ad/add/id/".$id."';</script>";
	}
	
	//查看广告位下所有广告
	function detail(){
		$search_type=getvar("search_type");
		$search_key=getvar("search_key");
		
		$adplaceid=getvar("adplaceid");
		
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
					deleted=0 and
					adplaceid=".$adplaceid." 
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
}
?>