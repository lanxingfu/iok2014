<?php

class MarketCaseAction extends CommonAction
{
	function index()
	{
		
		$this->assign('data',$data);
		$this->display();
	}
	
}
?>