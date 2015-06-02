<?php

class MarketArticleAction extends CommonAction
{
	function index()
	{
		
		$this->assign('data',$data);
		$this->display();
	}
	
}
?>