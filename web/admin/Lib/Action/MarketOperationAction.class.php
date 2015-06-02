<?php

class MarketOperationAction extends CommonAction
{
	function index()
	{
		
		$this->assign('data',$data);
		$this->display();
	}
	
}
?>