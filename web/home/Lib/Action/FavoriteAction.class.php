<?php
/**
 * 
 * Enter description here ...
 * @author wuzhijie
 * time 2013年12月25日 11:57:34
 */
class FavoriteAction extends CommonAction
{
	/* company wuzhijie 未完成 */
	public function company(){
		$arr = array(
			0=>array(
				'id'=>'23119',
				'title'=>'ThinkPad E531-6885-D4C',
				'url'=>'http://www.iokokok.com/file/upload/201312/20/52b42bd1b6cd6.jpg.thumb.jpg',
				'addtime'=>'1387954775'
			)
		);
		
		$this->assign("list",$arr);
		$this->display('companyfavorite');
	}
	/* profile */
	public function profile(){
		$arr = array(
			0=>array(
				'id'=>'23119',
				'title'=>'ThinkPad E531-6885-D4C',
				'url'=>'http://www.iokokok.com/file/upload/201312/20/52b42bd1b6cd6.jpg.thumb.jpg',
				'addtime'=>'1387954775'
			)
		);
		
		$this->assign("list",$arr);
		$this->display('profilefavorite');
	}
	
	/* 自定义分类批量删除 wuzhijie */
	public function delete(){
		$id=trim(getvar('idlist'),',');
		
		if(!empty($id)){
			$result = $this->exec(" delete from iok_memberfavor where id in(".$id.") ");
			if($result){
				echo '删除成功！';
				return false;
			}else{
				echo '删除失败了，请刷新页面重试！';
				return false;
			}
		}else{
			echo '请选择要删除的分类！';
			return false;
		}
	}
	
	
	
}


?>