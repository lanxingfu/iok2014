<?php
/**
 * 广告排期
 * @author lee
 *
 */
class ScheduleAction extends Action{
	function _initialize(){
		if(!$_SESSION['user']['id']){
			header('Location: /iokadmin.php?m=Login');
		}
	}
	//列表
	function index(){
		$adplaceid=getvar("adplaceid");
		$adid=getvar("adid");
		
		$sql = "select
					id,
					adplaceid,
					adid,
					title,
					amount,
					FROM_UNIXTIME(fromtime,'%Y-%m-%d') fromtime,
					FROM_UNIXTIME(totime,'%Y-%m-%d') totime,
					enabled,
					addtime,
					adduserid
				from
					iok_adschedule
				where
					enabled=1 
				";
		$where='';
		if($adplaceid){
			$where.=" and adplaceid ='".$adplaceid."' ";
		}
		if($adid){
			$where.=" and adid='".$adid."' ";
		}
		$csql = "select
					count(id)
				from
					iok_adschedule
				where
					enabled=1 
				";
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 10, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		$totalrecords = $this->res($csql.$where);
		$listarr=$this->arr($sql.$where." order by id desc limit $recordstart,$pagerecords");
		// echo "<pre>";var_dump($listarr);echo "</pre>";
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
			// if($l['adplaceid']){
				// $addsql="
					// select
						// prettyname
					// from
						// iok_adplace
					// where
						// id=".$l['adplaceid'];
				// $listarr[$key]['adplaceid']=$this->res($addsql);
			// }
			if($l['addtime'])
				$listarr[$key]['addtime']=date('Y-m-d',$l['addtime']);
			if($l['enabled']){
				$listarr[$key]['enabled']="启用";
			}else{
				$listarr[$key]['enabled']="关闭";
			}
		}
		
		$page = setpage($totalrecords, $currentpage, $pagerecords);
		$this->assign('adplaceid', $adplaceid);
		$this->assign('adid', $adid);
		$this->assign('page', $page);
		$this->assign('listarr',$listarr);
		$this->display();
		// dump($listarr);
	}
	
	//详情(排期详情页) *废弃
	function detail(){
		$search_type=getvar("search_type");
		$search_key=getvar("search_key");
		
		$adplaceid=getvar("adplaceid");
		
		$sql = "select
					a.id,
					a.prettyname,
					a.adplaceid,
					a.price,
					a.introduce,
					a.note,
					a.listorder,
					s.enabled,
					a.addtime,
					a.adduserid,
					s.fromtime,
					s.totime
				from
					iok_ad a
				left join
					iok_adschedule s
				on 
					a.id=s.adid
				where
					a.enabled=1 and
					a.adplaceid=".$adplaceid." 
				";
		$where='';
		if($search_key){
			if($search_type=="prettyname"){
				$where=" and a.prettyname like '%".likefilter($search_key)."%' ";
			}else if($search_type=="introduce"){
				$where=" and a.introduce like '%".likefilter($search_key)."%' ";
			}else if($search_type=="user"){
				$where=" and a.adduserid like '%".likefilter($search_key)."%' ";
			}
		}
		$csql = "select
					count(id)
				from
					iok_ad a
				where
					a.enabled=1 
				";
		$currentpage = getvar(C('VAR_PAGE'), 1, 'integer');
		$pagerecords = getvar(C('VAR_RECORD'), 10, 'integer');
		$recordstart = ($currentpage - 1) * $pagerecords;
		$totalrecords = $this->res($csql.$where);
		
		$listarr=$this->arr($sql.$where." order by addtime desc limit $recordstart,$pagerecords");
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
			if($l['fromtime'])
				$listarr[$key]['fromtime']=date('Y-m-d',$l['fromtime']);
			else
				$listarr[$key]['fromtime']="未排期";
			if($l['totime'])
				$listarr[$key]['totime']=date('Y-m-d',$l['totime']);
			else
				$listarr[$key]['totime']="未排期";
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
		// echo "<pre>";
		// var_dump($listarr);
		// echo "</pre>";
		$this->display();
	}
	
	//状态(添加排期通过广告位id获取广告ajax)
	function ajaxstate(){
		$sql="
			select
				id,
				prettyname
			from
				iok_ad
			where
				adplaceid=".getvar("place")
			;
		echo json_encode($this->arr($sql));
	}
	
	//增加
	function add(){
		$sql="
			select
				id,
				prettyname	
			from
				iok_adplace
		";
		$place=$this->arr($sql);
		$this->assign('place',$place);
		$this->display();
	}
	//执行增加操作
	function submit(){
		$id=getvar("adid");
		if($_FILES["imageurl"]){
			$sql="
				select 
					thumb
				from
					iok_article
				where
					id=".$id;
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
			$upPaths = '/file/upload/ad/'.date("Ym/d",time())."/";
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
		
		$prettynamesql="
			select
				prettyname
			from
				iok_ad
			where
				id=".$id
		;	
		$prettyname=$this->res($prettynamesql);
		$amount=getvar("amount");
		$finalamount=getvar("finalamount");
		$linkurl=getvar("linkurl");
		$isstat=getvar("isstat");
		$fromtime=strtotime(getvar("fromtime"));
		$totime=strtotime(getvar("totime"));
		$listorder=getvar("listorder");
		$enabled=getvar("enabled");
		$adplaceid=getvar("adplaceid");
		$mobiletype=getvar("mobiletype");
		$edit=getvar("edit");
		$addtime=time();
		$adduserid=$_SESSION['user']['id'];
		if(!$edit){
			$sql="insert into
						iok_adschedule(
							adplaceid,
							adid,
							title,
							amount,
							finalamount,
							uploadid,
							linkurl,
							mobiletype,
							isstat,
							fromtime,
							totime,
							listorder,
							enabled,
							addtime,
							adduserid,
							imageurl
						)
					values(
							'".$adplaceid."',
							'".$id."',
							'".$prettyname."',
							'".$amount."',
							'".$finalamount."',
							'".$uploadid."',
							'".$linkurl."',
							'".$mobiletype."',
							'".$isstat."',
							'".$fromtime."',
							'".$totime."',
							'".$listorder."',
							'".$enabled."',
							'".$addtime."',
							'".$adduserid."',
							'ad/".date('Ym/d',time())."/".$imageurl."'
					)
				";
			$result=$this->exec($sql);
			// dump($result);
			// exit;
			// if($imageurl){
				// $sqlimg="
					// update
						// iok_adschedule
					// set
						// imageurl='".$imageurl."'
					// where
						// id=".$result;
				// $this->exec($sqlimg);
			// }
		}else{
			$id=getvar("wyid");
			$sql="update
					iok_adschedule
				  set
					adplaceid='".$adplaceid."',
					adid='".$id."',
					title='".$prettyname."',
					amount='".$amount."',
					finalamount='".$finalamount."',
					uploadid='".$uploadid."',
					linkurl='".$linkurl."',
					mobiletype='".$mobiletype."',
					isstat='".$isstat."',
					fromtime='".$fromtime."',
					totime='".$totime."',
					listorder='".$listorder."',
					enabled='".$enabled."',
					updatetime='".$addtime."',
					updateuserid='".$adduserid."'
				where
					id=".$id;
			if($imageurl){
				$sqldel="
					select
						imageurl
					from
						iok_adschedule
					where
						id=".$id;
				$delimg=$this->res($sqldel);
				// unlink('http://'.$_SERVER['HTTP_HOST']."/file/upload/".$delimg);
				$sqlimg="
					update
						iok_adschedule
					set
						imageurl='".$imageurl."'
					where
						id=".$id;
				$this->exec($sqlimg);
			}
			$result=$this->exec($sql);
		}
		if($result){
			echo "<script>alert('操作成功');</script>";
			echo "<script>window.location.href='/iokadmin.php/Schedule/index';</script>";
		}else{
			echo "<script>alert('操作失败');</script>";
			echo "<script>window.location.href='/iokadmin.php/Schedule/index';</script>";
		}   
	}
	
	//修改
	function edit(){
		$id=getvar("id");
		$listsql="
			select
				id,
				adplaceid,
				adid,
				title,
				amount,
				finalamount,
				uploadid,
				linkurl,
				mobiletype,
				isstat,
				FROM_UNIXTIME(fromtime,'%Y-%m-%d') fromtime,
				FROM_UNIXTIME(totime,'%Y-%m-%d') totime,
				listorder,
				enabled,
				addtime,
				adduserid,
				imageurl
			from
				iok_adschedule
			where
				id=".$id;
		$list=$this->rec($listsql);
		$adsql="
			select
				id,
				prettyname
			from
				iok_ad
			where
				adplaceid=".$list['adplaceid'];
		$place=$this->arr($sql);
		$adlist=$this->arr($adsql);
		$sql="
			select
				id,
				prettyname	
			from
				iok_adplace
		";
		$place=$this->arr($sql);
		// dump($list);
		// dump($place);
		$this->assign('adlist',$adlist);
		$this->assign('list',$list);
		$this->assign('place',$place);
		$this->display();
	}
	
	//删除
	function delete(){
	
	}
}
?>