<?php

class MarketNewsAction extends CommonAction
{
	function index()
	{
		
		$this->assign('data',$data);
		$this->display();
	}
	
}
?>