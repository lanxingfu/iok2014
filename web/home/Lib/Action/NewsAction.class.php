<?php
/**
 * 
 * 资讯
 * @author lee
 *
 */
class NewsAction extends CommonAction
{
	//首页
	function index(){
		//热点资讯
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=1 and categoryid=10001
			order by
				addtime desc
			limit
				1
			";
		$hottop=$this->rec($sql);
		$sql="
			select
				id,
				title,
				addtime
			from
				iok_article
			where 
				istop=0 and categoryid=10001 
			order by
				addtime desc
			limit
				7
			";
		$hotlist=$this->arr($sql);
		foreach($hotlist as $hk=>$hl){
			$hotlist[$hk]['addtime']=date("Y-m",$hl['addtime']);
		}
			
		//我行视点 8189
		$sql="
			select
				id,
				title,
				thumb,
				introduce
			from
				iok_article
			where 
				istop=1 and categoryid=8189
			order by
				addtime desc
			limit
				1
			";
		$wxsdtop=$this->rec($sql);
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=0 and categoryid=8189 
			order by
				addtime desc
			limit
				5
			";
		$wxsdlist=$this->arr($sql);
		
		//微周刊
		
		//研究报告 10002
		$sql="
			select
				id,
				title,
				thumb,
				introduce
			from
				iok_article
			where 
				istop=1 and categoryid=10002
			order by
				addtime desc
			limit
				1
			";
		$yjbgtop=$this->rec($sql);
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=0 and categoryid=10002 
			order by
				addtime desc
			limit
				5
			";
		$yjbglist=$this->arr($sql);
		
		
		//数据中心 
		
		//轮播广告 
		
		//专题报道 
		
		//访谈视频 
		
		//推荐展会 10003
		$sql="
			select
				id,
				title,
				thumb,
				introduce
			from
				iok_article
			where 
				istop=1 and categoryid=10003
			order by
				addtime desc
			limit
				2
			";
		$tjzhtop=$this->arr($sql);
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=0 and categoryid=10003 
			order by
				addtime desc
			limit
				3
			";
		$tjzhlist=$this->arr($sql);
		
				
		//市场走势 10004
		$sql="
			select
				id,
				title,
				thumb
			from
				iok_article
			where 
				istop=1 and categoryid=10004
			order by
				addtime desc
			limit
				1
			";
		$sczstop=$this->rec($sql);
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=0 and categoryid=10004 
			order by
				addtime desc
			limit
				5
			";
		$sczslist=$this->arr($sql);
		
		
		//政策法规 10005
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=1 and categoryid=10005
			order by
				addtime desc
			limit
				1
			";
		$zcfgtop=$this->rec($sql);
		$sql="
			select
				id,
				title,
				addtime
			from
				iok_article
			where 
				istop=0 and categoryid=10005 
			order by
				addtime desc
			limit
				7
			";
		$zcfglist=$this->arr($sql);
		foreach($zcfglist as $zcfgk=>$zcfgl){
			$zcfglist[$zcfgk]['addtime']=date("Y-m",$zcfgl['addtime']);
		}
		
		//商务指南 10006
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=1 and categoryid=10006
			order by
				addtime desc
			limit
				1
			";
		$swzntop=$this->rec($sql);
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=0 and categoryid=10006 
			order by
				addtime desc
			limit
				14
			";
		$swznlist=$this->arr($sql);
		
		//媒体合作
		$sql="
			select 
				r.id,
				r.title,
				r.linkurl,
				d.imageurl,
				d.content
			from
				iok_report r left join
				iok_reportdata d 
			on
				r.id=d.reportid
			where
				r.istop=1 and enabled=1
			order by
				r.addtime desc
			limit
				1
		";
		$mthztop=$this->rec($sql);
		$sql="
			select
				id,
				title,
				linkurl
			from
				iok_report
			where
				istop=0 and enabled=1
			order by
				addtime desc
			limit
				6
			";
		$mthzlist=$this->arr($sql);
		
		$this->assign("hottop",$hottop);
		$this->assign("hotlist",$hotlist);
		$this->assign("wxsdtop",$wxsdtop);
		$this->assign("wxsdlist",$wxsdlist);
		$this->assign("sczstop",$sczstop);
		$this->assign("sczslist",$sczslist);
		$this->assign("tjzhtop",$tjzhtop);
		$this->assign("tjzhlist",$tjzhlist);
		$this->assign("yjbgtop",$yjbgtop);
		$this->assign("yjbglist",$yjbglist);
		$this->assign("zcfgtop",$zcfgtop);
		$this->assign("zcfglist",$zcfglist);
		$this->assign("swzntop",$swzntop);
		$this->assign("swznlist",$swznlist);
		$this->assign("mthztop",$mthztop);
		$this->assign("mthzlist",$mthzlist);
		$this->display();
	}
	
	//视频列表页
	function video(){
		//热点资讯
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=1 and categoryid=10001
			order by
				addtime desc
			limit
				1
			";
		$hottop=$this->rec($sql);
		$sql="
			select
				id,
				title,
				addtime
			from
				iok_article
			where 
				istop=0 and categoryid=10001 
			order by
				addtime desc
			limit
				7
			";
		$hotlist=$this->arr($sql);
		foreach($hotlist as $hk=>$hl){
			$hotlist[$hk]['addtime']=date("Y-m",$hl['addtime']);
		}

		
		//我行视点 8189
		$sql="
			select
				id,
				title,
				thumb,
				introduce
			from
				iok_article
			where 
				istop=1 and categoryid=8189
			order by
				addtime desc
			limit
				1
			";
		$wxsdtop=$this->rec($sql);
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=0 and categoryid=8189 
			order by
				addtime desc
			limit
				5
			";
		$wxsdlist=$this->arr($sql);
		
		//专题报道 
		
		//研究报告 10002
		$sql="
			select
				id,
				title,
				thumb,
				introduce
			from
				iok_article
			where 
				istop=1 and categoryid=10002
			order by
				addtime desc
			limit
				1
			";
		$yjbgtop=$this->rec($sql);
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=0 and categoryid=10002 
			order by
				addtime desc
			limit
				5
			";
		$yjbglist=$this->arr($sql);
		
		//市场走势 10004
		$sql="
			select
				id,
				title,
				thumb
			from
				iok_article
			where 
				istop=1 and categoryid=10004
			order by
				addtime desc
			limit
				1
			";
		$sczstop=$this->rec($sql);
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=0 and categoryid=10004 
			order by
				addtime desc
			limit
				5
			";
		$sczslist=$this->arr($sql);
		
		$this->assign("hottop",$hottop);
		$this->assign("hotlist",$hotlist);
		$this->assign("wxsdtop",$wxsdtop);
		$this->assign("wxsdlist",$wxsdlist);
		$this->assign("yjbgtop",$yjbgtop);
		$this->assign("yjbglist",$yjbglist);
		$this->assign("sczstop",$sczstop);
		$this->assign("sczslist",$sczslist);
		$this->display();
	}
	
	// 文章详情
	function show(){
		//主数据 ps 真不知道为什么需要查询这么多次。。
		$id=getvar("itemid");
		$sql="
			select
				id,
				title,
				categoryid,
				subtitle,
				content,
				introduce,
				tag,
				author,
				copyfrom,
				addtime
			from
				iok_article
			where
				id=".$id;
		$main=$this->rec($sql);
		$main['addtime']=date("Y-m-d",$main['addtime']);
		//上一篇文章
		$sql="
			select
				id,
				title
			from
				iok_article
			where
				categoryid=".$main['categoryid']."
				and id=".($id-1);
		$pre=$this->rec($sql);
		if(!$pre){
			$pre="没有了";
		}else{
			$pre="<a href='/News/show/itemid/".$pre['id']."'>".$pre['title']."</a>";
		}
		//下一篇文章
		$sql="
			select
				id,
				title
			from
				iok_article
			where
				categoryid=".$main['categoryid']."
				and id=".($id+1);
		$next=$this->rec($sql);
		if(!$next){
			$next="没有了";
		}else{
			$next="<a href='/News/show/itemid/".$next['id']."'>".$next['title']."</a>";
		}
		
		//热点资讯
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=1 and categoryid=10001
			order by
				addtime desc
			limit
				1
			";
		$hottop=$this->rec($sql);
		$sql="
			select
				id,
				title,
				addtime
			from
				iok_article
			where 
				istop=0 and categoryid=10001 
			order by
				addtime desc
			limit
				7
			";
		$hotlist=$this->arr($sql);
		foreach($hotlist as $hk=>$hl){
			$hotlist[$hk]['addtime']=date("Y-m",$hl['addtime']);
		}

		
		//我行视点 8189
		$sql="
			select
				id,
				title,
				thumb,
				introduce
			from
				iok_article
			where 
				istop=1 and categoryid=8189
			order by
				addtime desc
			limit
				1
			";
		$wxsdtop=$this->rec($sql);
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=0 and categoryid=8189 
			order by
				addtime desc
			limit
				5
			";
		$wxsdlist=$this->arr($sql);
		
		//专题报道 
		
		//研究报告 10002
		$sql="
			select
				id,
				title,
				thumb,
				introduce
			from
				iok_article
			where 
				istop=1 and categoryid=10002
			order by
				addtime desc
			limit
				1
			";
		$yjbgtop=$this->rec($sql);
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=0 and categoryid=10002 
			order by
				addtime desc
			limit
				5
			";
		$yjbglist=$this->arr($sql);
		
		//市场走势 10004
		$sql="
			select
				id,
				title,
				thumb
			from
				iok_article
			where 
				istop=1 and categoryid=10004
			order by
				addtime desc
			limit
				1
			";
		$sczstop=$this->rec($sql);
		$sql="
			select
				id,
				title
			from
				iok_article
			where 
				istop=0 and categoryid=10004 
			order by
				addtime desc
			limit
				5
			";
		$sczslist=$this->arr($sql);
		
		// dump($main);exit;
		$this->assign("pre",$pre);
		$this->assign("next",$next);
		$this->assign("main",$main);
		$this->assign("hottop",$hottop);
		$this->assign("hotlist",$hotlist);
		$this->assign("wxsdtop",$wxsdtop);
		$this->assign("wxsdlist",$wxsdlist);
		$this->assign("yjbgtop",$yjbgtop);
		$this->assign("yjbglist",$yjbglist);
		$this->assign("sczstop",$sczstop);
		$this->assign("sczslist",$sczslist);
		
		$url_this =  "http://".$_SERVER ['HTTP_HOST'].$_SERVER['PHP_SELF'];
		$this->assign("fenxiang",$url_this);
		$this->display();
	}
}
?>