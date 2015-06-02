<?php
/**
 * 
 * Enter description here :Brand 品牌管理
 * @author wuzhijie
 *
 */
class BrandAction extends CommonAction
{
	/* list 2014年1月7日 09:30:12 wuzhijie 品牌列表 */
	public function index(){
		$returndate = $this->mget();
		$this->assign('returndate',$returndata);
		$sql = "
			select id,prettyname,note,listorder,addtime 
			from iok_memberbrand 
			where memberid = ".$_SESSION['member']['id']." and deleted!=1 
			order by listorder asc,addtime desc 
			limit 10 
		";
		$list = $this->arr($sql);
		$this->assign('brandlist',$list);
		$this->display('index');
	}
	/* 添加自定义品牌 */
	public function submit(){
		
		$data['listorder'] = getvar('listorder');
		$data['prettyname'] = getvar('prettyname');
		$data['memberid'] = $_SESSION['member']['id'];
		$data['note'] = getvar('note');
		$data['addtime'] = time();
		if(empty($data['prettyname'])){
			$this->mset('请填写品牌名称！','vprettyname',$data);
			$this->index();
			return false;
		}
		$inspectname = $this->res("
			select prettyname 
			from iok_memberbrand 
			where prettyname='".$data['prettyname']."' 
		");
		if($inspectname){
			$this->mset('该品牌已存在，不要重复添加！','vprettyname',$data);
			$this->index();
			return false;
		}
		$result = $this->ins("iok_memberbrand",$data);
		$this->index();
	}
	/* 保存修改品牌 */
	public function brandsave(){
		$data['prettyname'] = getvar('prettyname');
		$data['listorder'] = getvar('listorder');
		$data['note'] = getvar('note');
		$id = getVar('id');
		if(empty($data['prettyname'])){
			echo '保存失败！';
			return false;
		}
		$sql = "
			update iok_memberbrand 
			set
				prettyname = '".$data['prettyname']."' ,
				listorder = '".$data['listorder']."' ,
				note = '".$data['note']."' ,
				updatetime = ".time()." 
			where 
				id = '".$id."' and memberid ='".$_SESSION['member']['id']."' 
		";
		$result = $this->exec($sql);
		if($result==1){
			echo '保存成功！';
			return false;
		}else{
			echo '保存失败！';
		}
		return false;
	}
	/* 禁用，非物理删除 wuzhijie */
	public function branddel(){
		$id=trim(getvar('idlist'),',');
		$deletetime = time();
		if(!empty($id)){
			$result = $this->exec(" update iok_memberbrand set deleted=1,deletetime='".$deletetime."'  where id in(".$id.") ");
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