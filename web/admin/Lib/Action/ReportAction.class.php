<?php
/**
 * 新闻报道管理
 * @author lee
 *
 */
class ReportAction extends Action{
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
				subjectid,
				title,
				linkurl,
				istop,
				enabled,
				FROM_UNIXTIME(addtime,'%Y-%m-%d %H:%i:%s') addtime,
				adduserid
			from
				iok_report
			where
				enabled=1
		";
		$search_title=getvar("search_title");
		$where="";
		if($search_title)
			$where.=" and title like '%".likefilter($search_title)."%'";
		$csql = "select
					count(id)
				from
					iok_report
				where
					1=1
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
		$this->display();
	}
	//执行增加动作
	function submit(){
		$istop=getvar("istop");
		$subjectid=getvar("subjectid");
		$enabled=getvar("enabled");
		$title=getvar("title");
		$linkurl=getvar("linkurl");
		$content=getvar("content");
		$addtime=time();
		$adduserid=$_SESSION['user']['id'];
		$insert_array=array('subjectid'=>$subjectid,'title'=>$title,'linkurl'=>$linkurl,'istop'=>$istop,'enabled'=>$enabled,'addtime'=>$addtime,'adduserid'=>$adduserid);
		$returnid=$this->ins("iok_report", $insert_array);
		if($_FILES["imageurl"] && $istop==1){
			import("@.ORG.Net.UploadFile");
			/* 获取后缀名 小写 */
			$up_ext = strtolower(strstr($_FILES["imageurl"]["tmp_name"],'.'));
			//上传路径设置
			$upPaths = '/file/upload/pageshow/'.date("Ym/d",time())."/";
			$upPaths_up='.'.$upPaths;
			//文件保存路径设置
			$savePaths = 'http://'.$_SERVER['HTTP_HOST'].$upPaths;
			
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
			$imageurl=$uploadList[0]['savename'];
			$adduserid=$_SESSION['user']['id'];
			$insert_array1=array('reportid'=>$returnid,'imageurl'=>$savePaths.$imageurl,'uploadid'=>$adduserid,'content'=>$content);
			$this->ins("iok_reportdata", $insert_array1);
		}
		if($returnid){
			echo "<script>alert('操作成功');</script>";
			echo "<script>window.location.href='/iokadmin.php/Report/add';</script>";
		}  
	}
	
	//修改
	function edit(){
		$id=getvar("id");
		$sqlinfo="
			select
				id,
				subjectid,
				title,
				linkurl,
				istop,
				enabled
			from
				iok_report
			where
				id=".$id;
		$info=$this->rec($sqlinfo);
		if($info['istop']==1){
			$istopdiv=1;
			$sql="
				select 
					imageurl,
					content
				from
					iok_reportdata
				where
					reportid=".$id;
			$topdata=$this->rec($sql);
		}
		$this->assign("istopdiv",$istopdiv);
		$this->assign("topdata",$topdata);
		$this->assign("info",$info);
		$this->display();
	}
	//执行修改
	function doedit(){
		$reid=getvar("reid");
		$subjectid=getvar("subjectid");
		$enabled=getvar("enabled");
		$title=getvar("title");
		$linkurl=getvar("linkurl");
		$content=getvar("content");
		$istop=getvar("istop");
		$uptime=time();
		$upuserid=$_SESSION['user']['id'];
		$sql="
			update
				iok_report
			set
				subjectid='".$subjectid."',
				enabled='".$enabled."',
				title='".$title."',
				linkurl='".$linkurl."',
				updatetime='".$uptime."',
				istop='".$istop."',
				updateuserid='".$upuserid."'
			where
				id=".$reid;
		$result=$this->exec($sql);
		if($_FILES["imageurl"] && $istop==1){
			$sql="
				select 
					imageurl
				from
					iok_reportdata
				where
					reportid =".$reid;
			$oldfile=$this->arr($sql);
			foreach($oldfile as $of){
				if($of){
					$nof=explode("file",$of['imageurl']);
					$imageurl= THINK_PATH."../file".$nof[1];
					$imageurl=str_replace("\\","/",$imageurl);
					unlink($imageurl);
				}
			}
			import("@.ORG.Net.UploadFile");
			/* 获取后缀名 小写 */
			$up_ext = strtolower(strstr($_FILES["imageurl"]["tmp_name"],'.'));
			//上传路径设置
			$upPaths = '/file/upload/pageshow/'.date("Ym/d",time())."/";
			$upPaths_up='.'.$upPaths;
			//文件保存路径设置
			$savePaths = 'http://'.$_SERVER['HTTP_HOST'].$upPaths;
			
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
			$imageurl=$uploadList[0]['savename'];
			$adduserid=$_SESSION['user']['id'];
			$insert_array1=array('reportid'=>$returnid,'imageurl'=>$savePaths.$imageurl,'uploadid'=>$adduserid,'content'=>$content);
			$sql="
				update
					iok_reportdata
				set
					imageurl='".$savePaths.$imageurl."',
					uploadid='".$adduserid."',
					content='".$content."'
				where
					reportid=".$reid;
			$result=$this->exec($sql);
		}
		if($result){
			echo "<script>alert('操作成功');</script>";
			echo "<script>window.location.href='/iokadmin.php/Report/index';</script>";
		}  
	}
	
	//删除
	function delete(){
		$id=getvar("id");
		$id=trim($id,',');
		//删除图片
		$sql="
			select 
				imageurl
			from
				iok_reportdata
			where
				reportid in(".$id.")";
		$oldfile=$this->arr($sql);
		foreach($oldfile as $of){
			if($of){
				$nof=explode("file",$of['imageurl']);
				$imageurl= THINK_PATH."../file".$nof[1];
				$imageurl=str_replace("\\","/",$imageurl);
				unlink($imageurl);
			}
		}
		//删除数据
		$sql="
			update
				iok_report
			set
				enabled=0,
				deleted=1
			where
				id in(".$id.")
			";
		$result=$this->exec($sql);
		if($result){
			echo "<script>alert('删除成功');</script>";
			echo "<script>window.location.href='/iokadmin.php/Report/index';</script>";
		}
	}
}
?>