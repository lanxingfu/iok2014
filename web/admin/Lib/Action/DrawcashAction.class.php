<?php
/**
 * 提现管理
 * @author lee
 *
 */
class DrawcashAction extends Action{
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
				bankareaid,
				bankname,
				bankaccount,
				banktruename,
				amount,
				fee,
				note,
				ip,
				status,
				memberid,
				addtime
			from
				iok_drawcash
		";
		$this->display();
		
	}
	
	//增加
	function add(){
		
	}
	//执行增加动作
	function submit(){
		
	}
	
	//修改
	function edit(){
		
	}
	//执行修改
	function doedit(){
		
	}
	
	//删除
	function delete(){
	
	}
}
?>