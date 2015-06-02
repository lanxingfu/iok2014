<?php
/**
 * description: 个人/企业资金明细
 * @author  jia
 * last modified by: jia
 * last modified date: 2013/12/9
 * last modified content:
 */
class FundAction extends CommonAction
{
	public function index(){
		$username = $_SESSION['member']['account'] ? $_SESSION['member']['account'] : 0 ;
		$userid  = $_SESSION['member']['id'];
		//$userid =20636;
		$action = getvar('action');
		$type = array('0','1','2','3','4','5','6');
		switch($action){
			case "$action" : //全部资金明细;
			 $action==0?$map="":$map=" and type='$type[$action]'";
			// echo $map;
				import("@.ORG.Util.Page");
				$sql="	select  
						count(memberid) as cnt
					from  
						iok_logfinance
					where 	
						memberid = '$userid' " .$map
				;
				$countnum=$this->res($sql);
				$Page = new Page($countnum, 8);
				$showpage = $Page->show();
				$sql2 ="
					select 
						id,bank,amount,balance,reason,note,addtime
					from 	
						iok_logfinance 
					where 	
						memberid = '$userid' $map	
					order by
						addtime desc
					limit 	$Page->firstRow, $Page->listRows ";
				$rec = $this->arr($sql2);
				//dump($rec);
				$this->assign('list',$rec);
				$this->assign('action',$action);
				$this->assign('showpage',$showpage);
				$this->display();
			break;
			default:
				$this->display();
			break;
		}
	}	
}
?>