<?php
/*
*@author jia
*2014/1/6
*/
class ApiAction extends CommonAction{
	public function index(){
		$sql = "select * from it_project where islocked =0";
		$rec = $this->arr($sql);
		$this->assign('leftmenu',$rec);
		$this->display();
	}
	public function find(){//找模块
		//模块查询
		$sql = "select id,prettyname from it_project where islocked =0";
		$rec = $this->arr($sql);
		$this->assign('leftmenu',$rec);
		$id = getvar('projectid');
		$sql2 = "select id,prettyname from it_module where projectid=".$id;
		$result=$this->arr($sql2);
		if($result){
			$this->assign('list',$result);
		}
		$this->display();
	}
	public function interfacefind(){//找接口
		$sql = "select id,prettyname from it_project where islocked =0";
		$rec = $this->arr($sql);
		$this->assign('leftmenu',$rec);
		$id = getvar('moduleid');
		$sql2 = "select id,requesturl from it_interface where moduleid=".$id;
		$result=$this->arr($sql2);
		if($result){
			$this->assign('list',$result);
		}
		$this->display();
	}
	public function detail(){
		$sql = "select id,prettyname from it_project where islocked =0";
		$rec = $this->arr($sql);
		$this->assign('leftmenu',$rec);
		$id = getvar('interface');
		$sql = "
			select 
				a.prettyname as name ,b.prettyname as mname,c.returnformat,c.needlogin,c.requestmethod,c.requesturl,c.sample
			from 
				it_project as a,it_module as b ,it_interface as c 
			where 
				c.id='" . $id . "' and  a.id = b.projectid 
			limit 1";
		$result=$this->rec($sql);
		
		$this->assign('paramlist',$result);
		//dump($result);
		$sql2 = "
			select 
				*
			from
				it_parameter 
			where 
				paramtype=1 and interfaceid =".$id //请求列表
			;
		$list = $this->arr($sql2);
		$this->assign('list',$list);
		//dump($list);
		$sql2 = "
			select 
				*
			from
				it_parameter 
			where 
				paramtype=0 and interfaceid =".$id //返回列表
			;
		$list = $this->arr($sql2);
		if($list){
			$this->assign('back',$list);
		}
		
		$this->display('detail');
	}
	
}