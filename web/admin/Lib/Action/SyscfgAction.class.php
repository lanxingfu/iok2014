<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class SyscfgAction extends CommonAction
{
	public function index()
	{
		$this->mget();
		$this->assign('authed', true);
		$searchitems = array('name','title');
		$sql = "select value from iok_syscfg where name='sitename'";
		$sitename = $this->res($sql);
		$sql1 = "select value from iok_syscfg where name='logo'";
		$logo = $this->res($sql1);
		$app_name = $_SERVER['HTTP_HOST'];
		$this->assign('sitename',$sitename);
		$this->assign('logo',$logo);
		$this->assign('app_name',$app_name);
		$this->assign('items',getitems($searchitems));
		$this->assign('page',setpage(300));
		$this->display('index');
	}
	
	public function seoset()
	{

		$this->display('seoset');
	}
	public function banip()
	{

		$this->display('banip');
	}
	public function node()
	{

		$this->display('node');
	}
}
?>