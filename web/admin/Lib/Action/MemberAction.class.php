<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class MemberAction extends Action
{
	public function index()
	{
		$this->mget();
		$searchitems = array('name','title');
		$arr = $this->arr("select * from iok_user");
		$this->assign('rec', $arr);
		$this->assign('items',getitems($searchitems));
		$this->assign('page',setpage(300));
		$this->display();
	}
	public function detail()
	{
		
	}
	public function edit()
	{
		
	}
	public function submit()
	{
		
	}
}
?>