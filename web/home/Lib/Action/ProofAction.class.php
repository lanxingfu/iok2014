<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class ProofAction extends CommonAction
{
	//资质
	public function edit(){
		/*  */
		$prooftypesql = "
			select * from iok_memberprooftype where membertype=2 
		";
		$proof_type = $this->arr($prooftypesql);
		$this->assign('proof_type',$proof_type);
		
		$proofsql = "
			select * from iok_memberproof 
			where memberid = '".$_SESSION['member']['id']."' 
		";
		$proof_data = $this->arr($proofsql);
		$this->assign('proof_data',$proof_data);
		
		/* 菜单按钮显示 */
		$this->assign('way','four');
		$this->display("Company:userData_ver");
		
	}
	public function submit(){
		/* 
			企业经营执照		businesslicense
			组织结构代码		organizationcode
			税务登记证			taxation
			特许经营许可证		franchise
		*/
		$memberid = $_SESSION['member']['id'];
		
		$businesslicense	= getvar('franchise');
		$businesslicensetype= 11;
		
		$organizationcode	= getvar('organizationcode');
		$organizationcodetype	= 5;
		
		$taxation			= getvar('taxation');
		$taxationtype			= 4;
		
		$franchise			= getvar('franchise');
		$franchisetype			= 6;
		

		/* $uploadid;	upload表id */
		$enabled = 1;
		$deleted = 0;
		$addtime = time();
		dump($_POST);exit;
		
		/* 入库 */
		$result = $this->exec($insertsql);
		redirect('/Proof/edit');
		
		
	}
	
	
}
?>