<?php

class MarketBeeplanAction extends CommonAction
{
	function index()
	{
		
		$this->assign('data',$data);
		$this->display();
	}
	
}
?>