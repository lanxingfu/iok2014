<?php
/**
 * 
 * 商学院
 * @author lee
 *
 */
class SchoolAction extends CommonAction
{
	//首页
	public function index(){
		$this->display();
	}
	
	//商学院分类列表页
	public function listy(){
		$this->display();
	}
	
	//商学院关于我们 简介
	public function about(){
		//商学院关于我们
		$this->display();
		//商学院简介 map.HTML
		// $this->display();
	}
	
	//商学院讲师团 lecturers
	public function lecturers(){
		$this->display();
	}
	
	//商学院讲师细览 lecturer
	function lecturer(){
		$this->display();
	}
	
	//商学院公开课程 openlecture
	function openlecture(){
		$this->display();
	}
	
	//商学院认证培训 authlecture
	function authlecture(){
		$this->display();
	}
	
	//商学院VIP课程	viplecture
	function viplecture(){
		$this->display();
	}
	
	//商学院文章列表 articles
	function articles(){
		$this->display();
	}
	
	//商学院文章细览 article
	function article(){
		$this->display();
	}
	
	//视频详情 lecture
	function lecture(){
		$this->display();
	}
	
}
?>