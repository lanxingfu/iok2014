<?php
/**
 * 
 * 渠道招商
 * @author lee
 *
 */
class InvestAction extends CommonAction
{
	//首页
	public function index(){
		//广告 f1/2/3/4/6/7/9/10/11
		$sql="
			select 
				linkurl,imageurl,title 
			from 
				iok_adschedule 
			where 
				adplaceid=(select id from iok_adplace where name='F1')";
		$adf1=$this->arr($sql);
		$sql="
			select 
				linkurl,imageurl,title 
			from 
				iok_adschedule 
			where 
				adplaceid=(select id from iok_adplace where name='F2')";
		$adf2=$this->arr($sql);
		$sql="
			select 
				linkurl,imageurl,title	
			from 
				iok_adschedule 
			where 
				adplaceid=(select id from iok_adplace where name='F3')";
		$adf3=$this->arr($sql);
		$sql="
			select 
				linkurl,imageurl,title 
			from 
				iok_adschedule 
			where 
				adplaceid=(select id from iok_adplace where name='F4')";
		$adf4=$this->arr($sql);
		$sql="
			select 
				linkurl,imageurl,title 
			from 
				iok_adschedule 
			where 
				adplaceid=(select id from iok_adplace where name='F6')";
		$adf6=$this->arr($sql);
		$sql="
			select 
				linkurl,imageurl,title 
			from 
				iok_adschedule 
			where 
				adplaceid=(select id from iok_adplace where name='F7')";
		$adf7=$this->arr($sql);
		$sql="
			select 
				linkurl,imageurl,title
			from 
				iok_adschedule 
			where 
				adplaceid=(select id from iok_adplace where name='F9')";
		$adf9=$this->arr($sql);
		$sql="
			select 
				linkurl,imageurl,title 
			from 
				iok_adschedule 
			where 
				adplaceid=(select id from iok_adplace where name='F10')";
		$adf10=$this->arr($sql);
		$sql="
			select 
				linkurl,imageurl,title 
			from 
				iok_adschedule 
			where 
				adplaceid=(select id from iok_adplace where name='F11')";
		$adf11=$this->arr($sql);
		$sql="
			select 
				id,prettyname 
			from 
				iok_area 
			where 
				parentid=0";
		$area=$this->arr($sql);
		$this->assign("area",$area);
		$this->assign("adf1",$adf1);
		$this->assign("adf2",$adf2);
		$this->assign("adf3",$adf3);
		$this->assign("adf4",$adf4);
		$this->assign("adf6",$adf6);
		$this->assign("adf7",$adf7);
		$this->assign("adf9",$adf9);
		$this->assign("adf10",$adf10);
		$this->assign("adf11",$adf11);
		$this->display();
	}
	
	//渠道招商分类列表页
	public function listy(){
		$this->display();
	}
	
	//区域中心查询详情页
	public function areasearch(){
		$id=getvar("id");
		$areanme="";
		$sql="
			select 
				id,prettyname,parentid,pparentid
		 	from 
				iok_area 
			where 
				id=".$id;
		$areaname1=$this->rec($sql);
		$nowname=$areaname1;
		$areanme="<p> > <a href='/Invest/areasearch/id/".$areaname1['id']."'>".$areaname1['prettyname']."</a></p>";
		$sql="
			select 
				id,prettyname,parentid 
			from 
				iok_area 
			where 
				id=".$areaname1['parentid'];
		$areaname2=$this->rec($sql);
		if($areaname2['prettyname']){
			$nowname=$areaname2;
			$areanme="<p> > <a href='/Invest/areasearch/id/".$areaname2['id']."'>".$areaname2['prettyname']."</a></p>".$areanme;
			$sql="
				select 
					id,prettyname,parentid 
				from 
					iok_area 
				where 
					id=".$areaname2['parentid'];
			$areaname3=$this->rec($sql);
			if($areaname3['prettyname']){
				$nowname=$areaname3;
				$areanme="<p> > <a href='/Invest/areasearch/id/".$areaname3['id']."'>".$areaname3['prettyname']."</a></p>".$areanme;
			}
		}
		if($areaname1['parentid']==0){
			$nowname["type"]="省级管理中心";
		}else if($areaname1['pparentid']==0){
			$nowname["type"]="市级管理中心";
		}else{
			$nowname['type']="县/区级业务中心";
		}
		$sql="
			select 
				i.prettyname name,i.gender,i.telephone,i.email,i.qq,i.address,a.prettyname,m.gradeid 
			from 
				iok_member m 
				left join iok_memberinfo i 
			on 
				m.id=i.memberid 
				left join iok_area a 
			on 
				m.agentareaid=a.id 
			where 
				m.gradeid in(1,2,3) and m.agentareaid=".$id;
		$mainlist=$this->rec($sql);
		$is=$this->res("
			select 
				count(id) 
			from 
				iok_area 
			where 
				parentid=".$id);
		if($is!=0){
			$sql="
				select 
					id,prettyname 
				from 
					iok_area 
				where 
					parentid=".$id." or pparentid=".$id;
			$area=$this->arr($sql);
			if($area){
				$ids="";
				foreach($area as $a){
					$ids.=$a['id'].",";
				}
				$sql="
					select 
						i.prettyname name,i.gender,i.telephone,i.email,i.qq,i.address,a.prettyname,m.gradeid 
					from 
						iok_member m 
						left join iok_memberinfo i 
					on 
						m.id=i.memberid 
						left join iok_area a 
					on 
						m.agentareaid=a.id 
					where 
						m.gradeid in(1,2,3) and m.agentareaid in(".trim($ids,",").")";
				$list=$this->arr($sql);
			}
			$sql="
				select 
					id,prettyname 
				from 
					iok_area 
				where 
					parentid=".$id;
			$arealist=$this->arr($sql);
		}else{
			$sql="
				select 
					id,prettyname 
				from 
					iok_area 
				where 
					parentid=(select parentid from iok_area where id=".$id.")";
			$arealist=$this->arr($sql);
			$sql="
				select 
					i.prettyname name,i.gender,i.telephone,i.email,i.qq,i.address,a.prettyname,m.gradeid 
				from 
					iok_member m 
					left join iok_memberinfo i 
				on 
					m.id=i.memberid 
					left join iok_area a 
				on 
					m.agentareaid=a.id 
				where 
					m.gradeid in(1,2,3) and m.agentareaid=".$id;
			$list=$this->arr($sql);
		}
		foreach($list as $l){
			if($l['gradeid']==1){
				// $l['prettyname']=$l['prettyname']."省级管理中心";
				$nlist["sheng"][]=$l;
			}
			if($l['gradeid']==2){
				// $l['prettyname']=$l['prettyname']."市级管理中心";
				$nlist["shi"][]=$l;
			}
			if($l['gradeid']==3){
				// $l['prettyname']=$l['prettyname']."县/区级业务中心";
				$nlist["xian"][]=$l;
			}
		}
		$sql="
			select 
				id,prettyname 
			from 
				iok_area 
			where 
				parentid=0";
		$area=$this->arr($sql);
		$this->assign("area",$area);
		$this->assign("nowname",$nowname);
		$this->assign("mainlist",$mainlist);
		$this->assign("areanme",$areanme);
		$this->assign("list",$nlist);
		$this->assign("arealist",$arealist);
		$this->display();
		// dump($nlist);
	}
	
	//商务代表查询详情页
	public function represearch(){
		$id=getvar("id");
		$areanme="";
		$sql="
			select 
				id,prettyname,parentid,pparentid 
			from 
				iok_area 
			where 
				id=".$id;
		$areaname1=$this->rec($sql);
		$nowname=$areaname1;
		$areanme="<p> > <a href='/Invest/represearch/id/".$areaname1['id']."'>".$areaname1['prettyname']."</a></p>";
		$sql="
			select 
				id,prettyname,parentid 
			from 
				iok_area 
			where 
				id=".$areaname1['parentid'];
		$areaname2=$this->rec($sql);
		if($areaname2['prettyname']){
			$nowname=$areaname2;
			$areanme="<p> > <a href='/Invest/represearch/id/".$areaname2['id']."'>".$areaname2['prettyname']."</a></p>".$areanme;
			$sql="
				select 
					id,prettyname,parentid 
				from 
					iok_area 
				where 
					id=".$areaname2['parentid'];
			$areaname3=$this->rec($sql);
			if($areaname3['prettyname']){
				$nowname=$areaname3;
				$areanme="<p> > <a href='/Invest/represearch/id/".$areaname3['id']."'>".$areaname3['prettyname']."</a></p>".$areanme;
			}
		}
		
		$is=$this->res("
			select 
				count(id) 
			from 
				iok_area 
			where 
				parentid=".$id);
		if($is!=0){
			$sql="
				select 
					id,prettyname 
				from 
					iok_area 
				where 
					parentid=".$id." or pparentid=".$id;
			$area=$this->arr($sql);
			if($area){
				$ids="";
				foreach($area as $a){
					$ids.=$a['id'].",";
				}
				$sql="
					select 
						i.prettyname name,i.gender,i.telephone,i.email,i.qq,i.address,a.prettyname,m.gradeid 
					from 
						iok_member m 
						left join iok_memberinfo i 
					on 
						m.id=i.memberid 
						left join iok_area a 
					on 
						m.agentareaid=a.id 
					where 
						m.gradeid in(4,5) and m.agentareaid in(".$ids.$id.")";
				$list=$this->arr($sql);
			}
			$sql="
				select 
					id,prettyname 
				from 
					iok_area 
				where 
					parentid=".$id;
			$arealist=$this->arr($sql);
		}else{
			$sql="
				select 
					id,prettyname 
				from 
					iok_area 
				where 
					parentid=(select parentid from iok_area where id=".$id.")";
			$arealist=$this->arr($sql);
			$sql="
				select 
					i.prettyname name,i.gender,i.telephone,i.email,i.qq,i.address,a.prettyname,m.gradeid 
				from 
					iok_member m 
					left join iok_memberinfo i 
				on 
					m.id=i.memberid 
					left join iok_area a 
				on 
					m.agentareaid=a.id 
				where 
					m.gradeid in(4,5) and m.agentareaid=".$id;
			$list=$this->arr($sql);
		}
		foreach($list as $l){
			if($l['gradeid']==4){
				// $l['prettyname']=$l['prettyname']."商务代表";
				$nlist["sheng"][]=$l;
			}
			if($l['gradeid']==5){
				// $l['prettyname']=$l['prettyname']."商务服务站";
				$nlist["shi"][]=$l;
			}
		}
		$sql="
			select 
				id,prettyname 
			from 
				iok_area 
			where 
				parentid=0";
		$area=$this->arr($sql);
		$this->assign("area",$area);
		$this->assign("nowname",$nowname);
		$this->assign("areanme",$areanme);
		$this->assign("list",$nlist);
		$this->assign("arealist",$arealist);
		// dump($nlist);
		$this->display();
	}
	
	//渠道招商信息详情页
	public function show(){
		$this->display();
	}
}
?>