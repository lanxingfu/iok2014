<?php
/**
 * 
 * 首页
 * @author lee
 *
 */
class IndexAction extends CommonAction
{
	public function index()
	{
		//供应
		$sql="
			SELECT 
				m.id,
				m.title,
				m.price 
			from 
				`iok_product` m,
				(select 
					max(t.id) as tid 
				from 
					iok_product t 
				group by 
					t.memberid) as tb 
			where 
				m.id=tb.tid and m.enabled=1 
			order by 
				m.updatetime desc 
			limit 0,9";
		$gylist=$this->arr($sql);
		foreach($gylist as $gyk=>$gy){
			$gylist[$gyk]['title'] = $gy['title']?msubstr($gy['title'],0,6,'utf-8',false):"暂无";
		}
		//需求
		
		//最新成交
		$sql="
			select 
				p.id,
				p.title,
				p.price
			from 
				iok_product p,
				iok_order o 
			group by 
				o.productid,
				o.sellerid 
			order by 
				o.addtime desc";
		$newlist=$this->arr($sql);
		//分类
		$list=array();
		$sql="
			select 
				prettyname,
				id 
			from 
				iok_category 
			where 
				categorytype=8 and parentid=0";
		$arr=$this->arr($sql);//一级
		$list=$arr;
		foreach($arr as $k=>$a){
			$msql="
				select 
					prettyname,
					id 
				from 
					iok_category 
				where 
					parentid=".$a['id'];
			$marr['arr']=$this->arr($msql);//二级
			foreach($marr['arr'] as $mk=>$m){
				$mmsql="
					select 
						prettyname,
						id 
					from 
						iok_category 
					where 
						parentid=".$m['id']." limit 2";
				$mmrr=$this->arr($mmsql);
				$marr['arr'][$mk]['list']=$mmrr;//三级
			}
			$list[$k][]=$marr;
		}
		//广告
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='A1')";
		$ada1=$this->arr($sql);
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='A2')";
		$ada2=$this->arr($sql);
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='A3')";
		$ada3=$this->arr($sql);
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='A4')";
		$ada4=$this->arr($sql);
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='A5')";
		$ada5=$this->arr($sql);
		$this->assign("adlist",$ada1);
		$this->assign("ada2",$ada2);
		$this->assign("ada3",$ada3);
		$this->assign("ada4",$ada4);
		$this->assign("ada5",$ada5);
		$this->assign("inIndex","link_in_header");
		$this->assign("gylist",$gylist);
		$this->assign("newlist",$newlist);
		$this->assign("list",$list);
		// dump($list);
		$this->display();
	}
}
?>