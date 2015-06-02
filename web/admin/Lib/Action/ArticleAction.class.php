<?php
/**
 * 文章管理
 * @author lee
 *
 */
class ArticleAction extends Action{
	function _initialize(){
		if(!$_SESSION['user']['id']){
			header('Location: /iokadmin.php?m=Login');
		}
	}
	//列表 
	function index(){
		//查询
		$search_type=getvar("search_type");
		$search_title=getvar("search_title");
		$search_s=getvar("search_s");
		$search_e=getvar("search_e");
		$where="";
		if($search_type)
			$where.=" and categoryid=".$search_type;
		if($search_title)
			$where.=" and title like '%".likefilter($search_title)."%'";
		if($search_s && $search_e)
			$where.=" and ".strtotime($search_e).">updatetime>".strtotime($search_s);
		// 分类
		$sql="
			select
				id,
				prettyname
			from
				iok_category
			where
				categorytype=1
			and
				deleted=0
		";
		$type=$this->arr($sql);
		$typeids="(";
		foreach($type as $tt){
			$typeids.=$tt['id'].",";
		}
		$typeids=trim($typeids,",").")";
		$sql="
			select 
				id,
				categoryid,
				title,
				istop,
				addtime,
				adduserid,
				enabled 
			from 
				iok_article 
			where 
				deleted=0 and categoryid in".$typeids;
		$csql = "select
					count(id)
				from
					iok_article
				where
					deleted=0 and
					categoryid in ".$typeids;
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 10, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		$totalrecords = $this->res($csql.$where);
		$list=$this->arr($sql.$where." order by addtime desc limit $recordstart,$pagerecords");
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
				$list[$k]['addtime']=date("Y-m-d H:i:s",$l['addtime']);
				$catesql="
					select
						prettyname
					from 
						iok_category
					where
						id=".$l['categoryid'];
				$list[$k]['categoryid']=$this->res($catesql);
			}
		}
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign("search_type",$search_type);
		$this->assign("search_title",$search_title);
		$this->assign("search_s",$search_s);
		$this->assign("search_e",$search_e);
		$this->assign('page', $page);
		$this->assign('type',$type);
		$this->assign("list",$list);
		// dump($list);
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
				categorytype=1
		";
		$type=$this->arr($sql);
		$this->assign('type',$type);
		$this->display();
	}
	//执行增加动作
	function submit(){
		if($_FILES["imageurl"]){
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
		}
		$categoryid=getvar("categoryid");
		$tag=getvar("tag");
		$enabled=getvar("enabled");
		$istop=getvar("istop");
		$isred=getvar("isred");
		$title=getvar("title");
		$subtitle=getvar("subtitle");
		$author=getvar("author");
		$copyfrom=getvar("copyfrom");
		$fromurl=getvar("fromurl");
		$introduce=getvar("introduce");
		$content=getvar("content");
		$addtime=time();
		$adduserid=$_SESSION['user']['id'];
		$user_IP = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
		$user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
		$sql="
			insert into
				iok_article(
					categoryid,
					enabled,
					istop,
					isred,
					title,
					tag,
					subtitle,
					author,
					copyfrom,
					fromurl,
					introduce,
					content,
					addtime,
					thumb,
					ip,
					adduserid)
			values(
				'".$categoryid."',
				'".$enabled."',
				'".$istop."',
				'".$isred."',
				'".$title."',
				'".$tag."',
				'".$subtitle."',
				'".$author."',
				'".$copyfrom."',
				'".$fromurl."',
				'".$introduce."',
				'".$content."',
				'".$addtime."',
				'".$savePaths.$imageurl."',
				'".$user_IP."',
				'".$adduserid."'
			)
		";
		$result=$this->exec($sql);
		if($result){
			echo "<script>alert('操作成功');</script>";
			echo "<script>window.location.href='/iokadmin.php/Article/add';</script>";
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
				categorytype=1
		";
		$type=$this->arr($sql);
		$sql="select
				id,
				categoryid,
				enabled,
				istop,
				isred,
				title,
				tag,
				subtitle,
				author,
				copyfrom,
				fromurl,
				introduce,
				content,
				thumb
			from 
				iok_article 
			where 
				id=".$id;
		$list=$this->rec($sql);
		// dump($list);
		$this->assign("type",$type);
		$this->assign("l",$list);
		$this->display();
	}
	//执行修改
	function doedit(){
		$articleid=getvar("articleid");
		if($_FILES["imageurl"]){
			$sql="
				select 
					thumb
				from
					iok_article
				where
					id=".$articleid;
			$oldfile=$this->res($sql);
			if($oldfile){
				$oldfile=explode("file",$oldfile);
				$imageurl= THINK_PATH."../file".$oldfile[1];
				$imageurl=str_replace("\\","/",$imageurl);
				unlink($imageurl);
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
			$sql="
				update
					iok_article
				set
					thumb='".$savePaths.$imageurl."'
				where
					id=".$articleid;
			$this->exec($sql);
		}
		
		$categoryid=getvar("categoryid");
		$enabled=getvar("enabled");
		$istop=getvar("istop");
		$isred=getvar("isred");
		$title=getvar("title");
		$tag=getvar("tag");
		$subtitle=getvar("subtitle");
		$author=getvar("author");
		$copyfrom=getvar("copyfrom");
		$fromurl=getvar("fromurl");
		$introduce=getvar("introduce");
		$content=getvar("content");
		$updatetime=time();
		$upuserid=$_SESSION['user']['id'];
		$sql="
			update
				iok_article
			set
				categoryid='".$categoryid."',
				enabled='".$enabled."',
				istop='".$istop."',
				isred='".$isred."',
				title='".$title."',
				tag='".$tag."',
				subtitle='".$subtitle."',
				author='".$author."',
				copyfrom='".$copyfrom."',
				fromurl='".$fromurl."',
				introduce='".$introduce."',
				content='".$content."',
				updatetime='".$updatetime."',
				updateuserid='".$upuserid."'
			where
				id=".$articleid;
		$result=$this->exec($sql);
		if($result){
			echo "<script>alert('操作成功');</script>";
			echo "<script>window.location.href='/iokadmin.php/Article/index';</script>";
		}  
	}
	
	//删除
	function delete(){
		$id=getvar("id");
		$id=trim($id,',');
		//删除图片
		$sql="
			select 
				thumb
			from
				iok_article
			where
				id in(".$id.")";
		$oldfile=$this->arr($sql);
		foreach($oldfile as $of){
			if($of){
				$nof=explode("file",$of['thumb']);
				$imageurl= THINK_PATH."../file".$nof[1];
				$imageurl=str_replace("\\","/",$imageurl);
				unlink($imageurl);
			}
		}
		
		//数据库删除
		$deleteuserid=$_SESSION['user']['id'];
		$deletetime=time();
		$sql="
			update
				iok_article
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
			echo "<script>window.location.href='/iokadmin.php/Article/index';</script>";
		}
	}
}
?>