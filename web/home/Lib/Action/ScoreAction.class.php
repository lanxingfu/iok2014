<?php
/**
 * description: 企业 / 个人积分管理 
 * @author  jia
 * last modified by: jia
 * last modified date: 2013/12/10
 * last modified content:推广记录inventindex()
 */
class ScoreAction extends CommonAction{
	//积分明细;
	public function index(){
		//$userid = $_SESSION['member']['id'];
		$userid = 12;
		import("@.ORG.Util.Page");
		$sq =" select 
				count(id) as cnt
			from 	
				iok_logscore 
			where 	
				memberid= $userid
			";
		$totalrecords = $this->res($sq);	
		$Page = new Page($totalrecords, 8);
		$showpage = $Page->show();
		$sql2 ="
			select 
				id,amount,balance,reason,note,addtime
			from 	
				iok_logscore 
			where 	
				memberid= $userid
			order by
				addtime desc
			limit 	$Page->firstRow, $Page->listRows ";
		$rec = $this->arr($sql2);
		$this->assign('showpage',$showpage);
		$this->assign('list',$rec);
		/*$a=$this->scoreadd('iok_logscore','12',-2,'减积分','减去两个积分');
		if($a){
			echo '增加积分成功';
		}else{
			echo '增加积分失败';
		}*///增加积分  减积分;
	}
	public function  inventindex(){//推广记录列表;
	//$userid = $_SESSION['member']['id'];
	$userid =12;
		import("@.ORG.Util.Page");
		$sq =" select 
				inviterid
			from 	
				iok_member 
			where 	
				id= $userid
			";
		$inventid = $this->res($sq);//是否存在推荐人;
		if(empty($inventid)){
			$none= "暂无数据";
			$this->display('none',$none);
			return false;
		}
		
		$sql =" select 
				count(id)
			from 	
				iok_logscore 
			where 	
				memberid= $inventid
			";
		$totalrecords = $this->res($sql);
		$Page = new Page($totalrecords, 8);
		$showpage = $Page->show();
		$sql2 ="
			select 
				id,amount,balance,reason,note,addtime
			from 	
				iok_logscore 
			where 	
				memberid= $inventid and reason ='推广链接'
			order by
				addtime desc
			limit 	$Page->firstRow, $Page->listRows ";
		$rec = $this->arr($sql2);
		//dump($rec);
		$this->assign('showpage',$showpage);
		$this->assign('list',$rec);
	}
	
}