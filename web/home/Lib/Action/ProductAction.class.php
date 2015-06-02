<?php 
/**
 * 
 * 供应
 * @author lee
 *
 */
class ProductAction extends CommonAction {
	public function _initialize(){
		$this->assign("inProduct","link_in_header");
	}
	
	//首页
	public function index(){
		//供应
		$sql="
			SELECT 
				m.id,
				m.title,
				m.price
			from 
				`iok_product` m,
				(select max(t.id) as tid from iok_product t group by t.memberid) as tb
			where
				m.id=tb.tid
			order by
				m.updatetime desc
			limit 0,14";
		$gylist=$this->arr($sql);
		foreach($gylist as $gyk=>$gy){
			$gylist[$gyk]['title'] = $gy['title']?msubstr($gy['title'],0,6,'utf-8',false):"暂无";
		}
		//商品
		$sql = "
			select
				id,
				prettyname,
				arrchildid 
			from
				iok_category 
			where 
				categorytype=8 
				and parentid=0";
		$imp_nav = $this->arr($sql);
		foreach($imp_nav as $k=>$t){
			$malldata[$k]['catname']= $t['prettyname'];//一级
			$malldata[$k]['catid']= $t['id'];
			$sql = "
				select 
					id,
					prettyname 
				from 
					iok_category 
				where 
					categorytype=8 
					and parentid=".$t['id']."
				limit 0,7";
			$imp_child = $this->arr($sql);
			$malldata[$k]['child'] = $imp_child;//二级
			$sql='
				SELECT 
					m.id,
					m.title,
					m.thumb,
					m.price,
					m.unit
				FROM iok_product m,
					(SELECT MAX(t.id) AS tid FROM iok_product t GROUP BY t.memberid) AS tb 
				WHERE 
					m.id=tb.tid 
					AND m.categoryid IN ('.$t["arrchildid"].')
				ORDER BY 
					m.updatetime DESC 
				LIMIT 5';
			$malldata[$k]['list'] = $this->arr($sql);		
		}
		//优质供应产品
		$sql="
			select 
				id,
				title,
				price,
				unit,
				thumb
			from
				iok_product
			order by
				updatetime desc
			limit
				5
		";
		$goodpro=$this->arr($sql);
		
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='B1')";
		$adb1=$this->arr($sql);
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='B2')";
		$adb2=$this->arr($sql);
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='B3')";
		$adb3=$this->arr($sql);
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='B4')";
		$adb4=$this->arr($sql);
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='B5')";
		$adb5=$this->arr($sql);
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='B6')";
		$adb6=$this->arr($sql);
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='B7')";
		$adb7=$this->arr($sql);

		$this->assign("adb1",$adb1);
		$this->assign("adb2",$adb2);
		$this->assign("adb3",$adb3);
		$this->assign("adb4",$adb4);
		$this->assign("adb5",$adb5);
		$this->assign("adb6",$adb6);
		$this->assign("adb7",$adb7);
		$this->assign("gylist",$gylist);
		$this->assign("malldata",$malldata);
		$this->assign("goodpro",$goodpro);
		// dump($malldata);exit;
		$this->assign("inProduct","link_in_header");
		$this->display();
	}
	
	//供应分类列表页
	public function listy(){
		$id=getvar("catid");
		//产品导航
		$sql="
			select
				id,
				prettyname,
				arrparentid 
			from 
				iok_category 
			where id=".$id;
		$cate=$this->rec($sql);
		$sql="
			select 
				id,
				prettyname 
			from 
				iok_category 
			where id in(".$cate['arrparentid'].")";
		$place=$this->arr($sql);
		$returnp="";
		foreach($place as $p){
			$returnp.="<span>&gt;&nbsp;<a href='/Product/listy/catid/".$p['id']."'>".$p['prettyname']."</a></span>";
		}
		$returnp.="<span>&gt;&nbsp;<a href='/Product/listy/catid/".$cate['id']."'>".$cate['prettyname']."</a></span>";
		//分类列表
		$sql="select id,prettyname from iok_category where parentid=".$id;
		$sonlist=$this->arr($sql);
		if(!$sonlist){
			$sql="select id,prettyname from iok_category where parentid=(select parentid from iok_category where id=".$id.")";
			$sonlist=$this->arr($sql);
		}
		//主数据
		$sql="
			select 
				prettyname,
				arrchildid 
			from 
				iok_category 
			where 
				id=".$id;
		$arr=$this->rec($sql);
		$sortby=getvar("sortby");
		$orderby=getvar("orderby");
		if(!in_array($sortby, array('price', 'sales', 'updatetime','goodcomment'))){
			$sortby = 'price';
		}
		if($orderby == 'asc'){
			$orderby = 'asc';
			$newby = 'desc';
		}else{
			$orderby = 'desc';
			$newby = 'asc';
		}
		if($sortby){
			$order=" order by ".$sortby." ".$orderby;
		}
		//分页----		
		import("@.ORG.Util.Page");
		$sql="
			select 
				count(id) 
			from 
				iok_product 
			where 
				categoryid in(".$arr['arrchildid'].")";
		$count = $this->res($sql);
		$Page = new Page($count,10);
		//自定义分页样式
		$Page->setConfig('theme',' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage%  %first% %linkPage% %downPage% %end%');		
		$showpage = $Page->show();
		
		$sql="
			select 
				id,
				title,
				price,
				unit,
				goodcomment,
				inventory,
				shipareaid,
				moq,
				placeareaid,
				thumb,
				hits,
				sales,
				updatetime
			from 
				iok_product 
			where 
				categoryid in(".$arr['arrchildid'].")".$order." 
			limit ".$Page->firstRow.",".$Page->listRows;
		$list=$this->arr($sql);
		foreach($list as $k=>$l){
			$list[$k]['shipareaid']=get_areaname($l['shipareaid']);
			$list[$k]['placeareaid']=get_areaname($l['placeareaid']);
		}
		//同类推荐
		$sql="
			select 
				id,
				title,
				price,
				unit,
				thumb
			from 
				iok_product 
			where 
				categoryid in(".$arr['arrchildid'].")
			order by
				sales desc
			limit
				6";
		$hotlist=$this->arr($sql);
		// dump($hotlist);
		$this->assign("nav",$returnp);
		$this->assign("hotlist",$hotlist);
		$this->assign("sonlist",$sonlist);
		$this->assign("list",$list);
		$this->assign("cateid",$id);
		$this->assign("orderby",$newby);
		$this->assign("sortby",$sortby);
		$this->assign("showpage",$showpage);//分页
		$this->assign("inProduct","link_in_header");
	
		$this->display();
	}
	
	//供应产品详情页
	public function show(){
		$id=getvar("itemid");
		//类别导航
		$sql="
			select 
				categoryid 
			from 
				iok_product 
			where 
				id=".$id;
		$cate=$this->rec($sql);
		$sql="
			select
				id,
				prettyname,
				arrparentid 
			from 
				iok_category 
			where id=".$cate['categoryid'];
		$cate=$this->rec($sql);
		$sql="
			select 
				id,
				prettyname 
			from 
				iok_category 
			where id in(".$cate['arrparentid'].")";
		$place=$this->arr($sql);
		$returnp="";
		foreach($place as $p){
			$returnp.="<span>&gt;&nbsp;<a href='/Product/listy/catid/".$p['id']."'>".$p['prettyname']."</a></span>";
		}
		$returnp.="<span>&gt;&nbsp;<a href='/Product/listy/catid/".$cate['id']."'>".$cate['prettyname']."</a></span>";
		//产品详情
		$sql="
			select
				p.id,
				p.memberid,
				p.title,
				b.prettyname,
				p.price,
				p.unit,
				p.sales,
				p.inventory,
				p.moq,
				p.isfreedelivery,
				p.shipareaid,
				p.content,
				p.hits,
				p.thumb,
				p.thumb1,
				p.thumb2,
				p.enabled,
				p.deleted,
				p.updatetime,
				p.addtime
			from 
				iok_product p 
				left join iok_memberbrand b on p.custombrandid=b.id 
			where 
				p.id=".$id;
		$list=$this->rec($sql);
		if($list['updatetime']){
			$list['updatetime']=date("Y-m-d H:i:s",$list['updatetime']);
		}
		$list['shipareaid']=get_areaname($list['shipareaid']);
		$sql="
			select 
				count(id) cnt 
			from 
				iok_order 
			where 
				productid=".$id;
		$list['xl']=$this->res($sql);//销售量
		$sql="
			select 
				count(id) cnt 
			from 
				iok_ordercomment 
			where 
				productid=".$id;
		$list['pj']=$this->res($sql);//评价数量
		//检测商品缩略图是否  wuzhijie 2013年11月26日 20:22:19
		//dump(file_get_contents("http://www.iokokok.com/file/upload/2013/30/52709f7cea5f0.png.thumb.png"));exit;
		$large_img = str_replace('thumb','large',$list['thumb']);
		if(file_get_contents($large_img)){
			$list['thumb'] = $large_img;
		}else{
			$list['thumb'] = get_bigimg($list['thumb']);
		}
		$large_img1 = str_replace('thumb','large',$list['thumb1']);
		if(file_get_contents($large_img1)){
			$list['thumb1'] = $large_img1;
		}else{
			$list['thumb1'] = get_bigimg($list['thumb1']);
		}
		$large_img2 = str_replace('thumb','large',$list['thumb2']);
		if(file_get_contents($large_img2)){
			$list['thumb2'] = $large_img2;
		}else{
			$list['thumb2'] = get_bigimg($list['thumb2']);
		}
		//获取商务代表信息
		if($list['memberid']){
			$sql="
				select 
					servicestaffid 
				from 
					iok_member 
				where 
					id=".$list['memberid'];
			$servicestaffid=$this->res($sql);
			if($servicestaffid){
				$sql="
					select 
						i.memberid,
						i.prettyname,
						i.mobile,
						i.email,
						i.star,
						i.addrareaid,
						p.imageurl
					from 
						iok_memberstaff i 
						left join iok_memberproof p on i.memberid=p.memberid 
					where 
						i.memberid=".$servicestaffid;
				$represent=$this->rec($sql);
				$represent['addrareaid']=get_areaname($represent['addrareaid']);
			}
		}
		//获取渠道商表信息
		//manufacture 制造商  merchant 贸易商 service 服务商 other 其他机构
		if($list['memberid']){
			$sql="
				select 
					c.company,
					c.mode,
					m.agentareaid
				from 
					iok_member m 
					right join iok_membercompany c on m.id=c.memberid 
				where 
					id=".$list['memberid'];
			$member=$this->arr($sql);
			foreach($member as $k=>$m){
				switch($m['mode']){
					case "manufacture":
						$member[$k]['mode']="制造商";
						break;
					case "merchant":
						$member[$k]['mode']="贸易商";
						break;
					case "service":
						$member[$k]['mode']="服务商";
						break;
					case "other":
						$member[$k]['mode']="其他机构";
						break;
					default:
						$member[$k]['mode']="其他机构";
				}
				$member[$k]['agentareaid']=get_areaname($m['agentareaid']);
			}
		}
		//用户自定义分类
		if($list['memberid']){
			$sql="
				select 
					id,
					prettyname 
				from 
					iok_membercategory 
				where 
					memberid=".$list['memberid'];
			$custom=$this->arr($sql);
			foreach($custom as $cusk=>$cus){
				$custom[$cusk]['prettyname']=msubstr($cus['prettyname'],0,12,'utf-8',false);
			}
		}
		//销量排行
		if($list['memberid']){
			$sql="
				select 
					count(p.id) cnt,
					p.id,
					p.thumb,
					p.title,
					p.price,
					p.unit 
				from 
					iok_product p 
					inner join iok_order o on p.id=o.id 
				where 
					p.memberid='".$list['memberid']."' 
				group by p.memberid 
				order by cnt desc";	
			$selllist=$this->arr($sql);
			foreach($selllist as $k=>$t){
				$t['title'] = $t['title']?msubstr($t['title'],0,8,'utf-8',false):"暂无";
				$selllist[$k] = $t;
			}	
		}
		//商家推荐  取出该商家的其他商品4个 2013-09-04
		if($list['memberid']){
			$sql="
				select 
					id,
					thumb,
					title,
					price,
					unit 
				from 
					iok_product 
				where 
					memberid=".$list['memberid']." and id<>".$id." 
				limit 4";
			$tongcate=$this->arr($sql);
		}
		foreach($tongcate as $tonk=>$ton){
			$tongcate[$tonk]['title']=msubstr($ton['title'],0,8,'utf-8',flase);
		}
		//猜你喜欢（随机敢数据）
		$sql="
			select 
				id,
				thumb,
				title,
				price,
				unit 
			from 
				iok_product
			ORDER BY RAND() limit 6";
		$gass=$this->arr($sql);
		foreach($gass as $gak=>$ga){
			$gass[$gak]['title']=msubstr($ga['title'],0,8,'utf-8',flase);
		}
		//评价
		$sql="
			select 
				m.account,
				o.content,
				o.grade,
				o.reply,
				o.replytime,
				o.addtime,
				s.attitude,
				s.speed,
				s.tally,
				d.price,
				d.number
			from 
				iok_member m inner join iok_ordercomment o on m.id=o.productid
				inner join iok_orderstar s on o.id=s.ordercommentid
				inner join iok_order d on d.productid=o.productid
			where 
				o.productid =".$id." 
			order by d.id desc";
		$pingjia=$this->arr($sql);
		foreach($pingjia as $pjkey=>$pj){
			$pingjia[$pjkey]['account']=substr($pj['account'], 0, 1)."*****".substr($pj['account'], -1);
			if($pj['addtime']){
				$pingjia[$pjkey]['addtime']=date("Y-m-d H:i:s",$pj['addtime']);
			}
			if($pj['replytime']){
				$pingjia[$pjkey]['replytime']=date("Y-m-d H:i:s",$pj['replytime']);
			}
			if($pj['grade']=="bad"){
				$bad[]=$pingjia[$pjkey];
			}else if($pj['grade']=="medium"){
				$medium[]=$pingjia[$pjkey];
			}else if($pj['grade']=="good"){
				$good[]=$pingjia[$pjkey];
			}
		}
		
		//订单记录
		import("@.ORG.Util.Page");
		$sql="
			select 
				count(id) 
			from 
				iok_order 
			where 
				productid=".$id;
		$dingcount = $this->res($sql);
		$dingPage = new Page($dingcount,10,'#order');
		$dingShowpage = $dingPage->show();
		$sql="
			select 
				m.account,
				o.price,
				o.number,
				o.addtime
				
			from 
				iok_order o 
				left join iok_member m on o.buyerid=m.id 
			where 
				o.productid=".$id." 
			order by o.addtime desc 
			limit ".$dingPage->firstRow.",".$dingPage->listRows;
		$olist=$this->arr($sql);
		foreach($olist as $olk=>$ol){
			if($ol['addtime']){
				$olist[$olk]['addtime']=date("Y-m-d H:i:s",$ol['addtime']);
			}
			$olist[$olk]['account']=substr($ol['account'], 0, 1)."*****".substr($ol['account'], -1);
		}
		$this->assign("list",$list);//产品详情
		$this->assign("returnp",$returnp);//导航
		$this->assign("member",$member);//获取渠道商表信息
		$this->assign("selllist",$selllist);//销量排行
		$this->assign("tongcate",$tongcate);//商家推荐
		$this->assign("gass",$gass);//猜你喜欢
		$this->assign("pingjia",$pingjia);//评价
		$this->assign("medium",$medium);//中评
		$this->assign("bad",$bad);//差评
		$this->assign("good",$good);//好价
		$this->assign("olist",$olist);//订单记录
		$this->assign("dingShowpage",$dingShowpage);//分页
		$this->assign("represent",$represent);//获取商务代表信息
		// dump($list);
		$this->display();
	}
	
	

	/* 企业自定义分类 */
	public function customcatelist(){
		$retrundata = $this->mget();
		$this->assign('returndata',$retrundata);
		$this->assign('menus',7);
		$sql = "
			select id,prettyname,listorder,note,addtime 
			from iok_membercategory 
			where memberid='".$_SESSION['member']['id']."' and deleted!=1 
			order by listorder asc,addtime desc limit 0,10
		";
		$list = $this->arr($sql);
		$this->assign('typelist',$list);
		
		$this->display('customcatelist');
	}
	public function customsubmit(){
		$data['listorder'] = getvar('listorder');
		$data['prettyname'] = getvar('prettyname');
		$data['memberid'] = $_SESSION['member']['id'];
		$data['note'] = getvar('note');
		$data['addtime'] = time();
		if(empty($data['prettyname'])){
			$this->mset('请填写分类名称！','vprettyname',$data);
			$this->customcatelist();
			return false;
		}
		$inspectname = $this->res("
			select prettyname 
			from iok_membercategory 
			where prettyname='".$data['prettyname']."' 
		");
		if($inspectname){
			$this->mset('该分类已存在！','vprettyname',$data);
			$this->customcatelist();
			return false;
		}
		$result = $this->ins("iok_membercategory",$data);
		$this->customcatelist();

	}
	/* 保存修改自定义分类 */
	public function catesave(){
		$data['prettyname'] = getvar('prettyname');
		$data['listorder'] = getvar('listorder');
		$data['note'] = getvar('note');
		$id = getVar('id');
		if(empty($data['prettyname'])){
			echo '保存失败！';
			return false;
		}
		$inspectname = $this->res("
			select prettyname 
			from iok_membercategory 
			where prettyname='".$data['prettyname']."' and id!='".$id."' 
		");
		if($inspectname){
			echo '分类已存在！';
			return false;
		}
		$sql = "
			update iok_membercategory 
			set
				prettyname = '".$data['prettyname']."' ,
				listorder = '".$data['listorder']."' ,
				note = '".$data['note']."',
				updatetime = ".time()." 
			where 
				id = '".$id."' and memberid = '".$_SESSION['member']['id']."' 
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
	/* 自定义分类批量删除 wuzhijie */
	public function catedel(){
		$id=trim(getvar('idlist'),',');
		$deletetime = time();
		if(!empty($id)){
			$result = $this->exec(" 
				update iok_membercategory 
				set deleted=1,deletetime='".$deletetime."' 
				where id in(".$id.") and memberid = '".$_SESSION['member']['id']."' 
			");
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
	
	/* 供应列表 已上架 */
	public function productlist(){
		$this->assign('menus',2);
		$sql_list = "
			select * from iok_product 
			where 
				memberid = '".$_SESSION['member']['id']."' 
				and enabled = 1 
				and onsale = 1 
				and stateid in(2,4,6,8,10,12,34) 
			order by id desc 
			limit 0,10 
		";
		$list = $this->arr($sql_list);
		$this->assign('list',$list);
		$this->display("productlist");
	}
	/* 供应列表 审核中 */
	public function check(){
		$this->assign('menus',3);
		$sql_list = "
			select * from iok_product 
			where 
				memberid = '".$_SESSION['member']['id']."' 
				and enabled = 1 
				and stateid = 1 
			order by id desc 
			limit 0,10 
		";
		$list = $this->arr($sql_list);
		$this->assign('list',$list);
		
		$this->display("productlist");
	}
	/* 供应列表 审核未通过 */
	public function nopass(){
		$this->assign('menus',4);
		$sql_list = "
			select * from iok_product 
			where 
				memberid = '".$_SESSION['member']['id']."' 
				and enabled = 1 
				and stateid in(3,5,7,9,11,13,35) 
			order by id desc 
			limit 0,10 
		";
		$list = $this->arr($sql_list);
		$this->assign('list',$list);
		
		$this->display("productlist");
	}
	/* 供应列表 已下架 */
	public function nosell(){
		$this->assign('menus',5);
		$sql_list = "
			select * from iok_product 
			where 
				memberid = '".$_SESSION['member']['id']."' 
				and enabled = 1 
				and onsale = 0 
				and stateid in(2,4,6,8,10,12,34) 
			order by id desc 
			limit 0,10 
		";
		$list = $this->arr($sql_list);
		$this->assign('list',$list);
		
		$this->display("productlist");
	}
	
	
	
	
	/* 发布供应 wuzhijie 2014年1月3日 10:51:37 */
	public function addproduct(){
		$this->assign('menus',1);
		
		/* 添加商品，无需验证id 清空 */
		unset($_SESSION['inspect_product_id']);
		
		$postdata = $this->mget();
		$this->assign('postdata',$postdata);
		
		/* 品牌 */
		$sql_brand = "
			select id,prettyname,listorder 
			from iok_memberbrand 
			where memberid='".$_SESSION['member']['id']."' and deleted!=1 
			order by listorder asc 
		";
		$brandlist = $this->arr($sql_brand);
		$this->assign("brandlist",$brandlist);
		/* 自定义分类 */
		$sql_cate = "
		select id,prettyname,listorder,addtime 
			from iok_membercategory 
			where memberid='".$_SESSION['member']['id']."' and deleted!=1 
			order by listorder asc,addtime desc 
		";
		$catelist = $this->arr($sql_cate);
		$this->assign('catelist',$catelist);
		$this->display('edit');
		
	}
	/* 提交供应 */
	public function editsubmit(){

		$datas['categoryid'] = getvar('categoryid');
		$datas['title'] = getvar('title');
		$datas['custombrandid'] = getvar('custombrandid');
		$datas['model'] = getvar('model');
		$datas['material'] = getvar('material');
		$datas['placeareaid'] = getvar('placeareaid');
		$datas['shipareaid'] = getvar('shipareaid');
		$datas['unit'] = getvar('unit');
		$datas['price'] = getvar('price');
		$datas['referenceprice'] = getvar('referenceprice');
		$datas['moq'] = getvar('moq');
		$datas['inventory'] = getvar('inventory');
		$datas['deliverydays'] = getvar('deliverydays');
		$datas['commission'] = getvar('commission');
		$datas['thumb'] = getvar('thumb');
		$datas['thumb1'] = getvar('thumb1');
		$datas['thumb2'] = getvar('thumb2');
		$datas['content'] = getvar('content');
		$datas['customcategoryid'] = getvar('mycate');
		/* 验证过滤数据 */
		if(empty($datas['categoryid'])){
			$this->mset('请选择商品分类！','tcategory',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['title'])){
			$this->mset('请填写商品名称！','ttitle',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['custombrandid'])){
			$this->mset('请选择品牌！','tbrand',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['model'])){
			$this->mset('请添加货号/型号！','tmodel',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['material'])){
			$this->mset('请添加成分/材料！','tmaterial',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['placeareaid'])){
			$this->mset('请选择原产地！','tplaceareaid',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['shipareaid'])){
			$this->mset('请选择发货地！','tshipareaid',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['unit'])){
			$this->mset('请填写计量单位！','tunit',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['price'])){
			$this->mset('请填写产品价格！','tprice',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['moq'])){
			$this->mset('请填写最小起订量！','tmoq',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['inventory'])){
			$this->mset('请填写供应总量！','tinventory',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['deliverydays'])){
			$this->mset('请填写发货日期！','tdeliverydays',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['commission'])){
			$this->mset('请填写促单佣金！','tcommission',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['thumb'])){
			$this->mset('请上传第一张图片！','tthumb',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['thumb1'])){
			$this->mset('请上传第二张图片！','tthumb',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['thumb2'])){
			$this->mset('请上传第三张图片！','tthumb',$datas);
			$this->addproduct();
			return false;
		}
		if(empty($datas['content'])){
			$this->mset('请填写商品详情！','tcontent',$datas);
			$this->addproduct();
			return false;
		}
		
		/* 验证码 */
		$verifycode = getvar('verifycode');
		if($_SESSION['verify']!=md5($verifycode) ){
			$this->mset('验证码错误！','tverify',$datas);
			$this->addproduct();
			return false;
		}
		
		$datas['memberid'] = $_SESSION['member']['id'];
		$datas['ip'] = get_client_ip();
		$datas['addtime'] = time();
		
		/* 入库 */
		$results = $this->ins('iok_product',$datas);
		if($results){echo '添加成功！嘿嘿~没恭喜页面呢~';return false;
			$this->productlist();
			return false;
		}else{
			$this->mset('添加失败！请重试！','toperror',$datas);
			$this->addproduct();
			return false;
		}
	}
	
	/* 编辑供应 wuzhijie 2014年1月8日 15:01:29 */
	public function edit(){
		$this->assign('menus',1);
		
		$postdata = $this->mget();
		$this->assign('postdata',$postdata);
		
		$id = getvar('id');
		if($id){
			/* 商品id 存入session */
			$_SESSION['inspect_product_id'] = $id;
		}
		if(empty($_SESSION['inspect_product_id'])){
			redirect('/Product/productlist',3,'请选择商品，页面还没有，，，唉，，，');
			return false;
		}
		$sql_product = "
			select 
				id,
				categoryid,
				customcategoryid,
				custombrandid,
				title,
				price,
				referenceprice,
				unit,
				commission,
				inventory,
				moq,
				isfreedelivery,
				deliverydays,
				model,
				material,
				placeareaid,
				shipareaid,
				content,
				thumb,
				thumb1,
				thumb2,
				uploadid1,
				uploadid2,
				uploadid3
			from iok_product  
			where id='".$_SESSION['inspect_product_id']."' and memberid = '".$_SESSION['member']['id']."' 
		";
		if(!$postdata){
			$productdata = $this->rec($sql_product);
			$this->assign('postdata',$productdata);
		}
		/* 品牌 */
		$sql_brand = "
			select id,prettyname,listorder 
			from iok_memberbrand 
			where memberid='".$_SESSION['member']['id']."' and deleted!=1 
			order by listorder asc 
		";
		$brandlist = $this->arr($sql_brand);
		$this->assign("brandlist",$brandlist);
		/* 自定义分类 */
		$sql_cate = "
		select id,prettyname,listorder,addtime 
			from iok_membercategory 
			where memberid='".$_SESSION['member']['id']."' and deleted != 1 
			order by listorder asc,addtime desc 
		";
		$catelist = $this->arr($sql_cate);
		$this->assign('catelist',$catelist);
		
		$this->display('edit');
	}
	
	/* 保存修改 wuzhijie 2014年1月8日 15:01:29 */
	public function editsave(){
		
		$datas['categoryid'] = getvar('categoryid');
		$datas['title'] = getvar('title');
		$datas['custombrandid'] = getvar('custombrandid');
		$datas['model'] = getvar('model');
		$datas['material'] = getvar('material');
		$datas['placeareaid'] = getvar('placeareaid');
		$datas['shipareaid'] = getvar('shipareaid');
		$datas['unit'] = getvar('unit');
		$datas['price'] = getvar('price');
		$datas['referenceprice'] = getvar('referenceprice');
		$datas['moq'] = getvar('moq');
		$datas['inventory'] = getvar('inventory');
		$datas['deliverydays'] = getvar('deliverydays');
		$datas['commission'] = getvar('commission');
		$datas['thumb'] = getvar('thumb');
		$datas['thumb1'] = getvar('thumb1');
		$datas['thumb2'] = getvar('thumb2');
		$datas['content'] = getvar('content');
		$datas['customcategoryid'] = getvar('mycate');
		/* 验证过滤数据 */
		if(empty($datas['categoryid'])){
			$this->mset('请选择商品分类！','tcategory',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['title'])){
			$this->mset('请填写商品名称！','ttitle',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['custombrandid'])){
			$this->mset('请选择品牌！','tbrand',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['model'])){
			$this->mset('请添加货号/型号！','tmodel',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['material'])){
			$this->mset('请添加成分/材料！','tmaterial',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['placeareaid'])){
			$this->mset('请选择原产地！','tplaceareaid',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['shipareaid'])){
			$this->mset('请选择发货地！','tshipareaid',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['unit'])){
			$this->mset('请填写计量单位！','tunit',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['price'])){
			$this->mset('请填写产品价格！','tprice',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['moq'])){
			$this->mset('请填写最小起订量！','tmoq',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['inventory'])){
			$this->mset('请填写供应总量！','tinventory',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['deliverydays'])){
			$this->mset('请填写发货日期！','tdeliverydays',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['commission'])){
			$this->mset('请填写促单佣金！','tcommission',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['thumb'])){
			$this->mset('请上传第一张图片！','tthumb',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['thumb1'])){
			$this->mset('请上传第二张图片！','tthumb',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['thumb2'])){
			$this->mset('请上传第三张图片！','tthumb',$datas);
			$this->edit();
			return false;
		}
		if(empty($datas['content'])){
			$this->mset('请填写商品详情！','tcontent',$datas);
			$this->edit();
			return false;
		}
		
		/* 验证码 */
		$verifycode = getvar('verifycode');
		if($_SESSION['verify']!=md5($verifycode) ){
			$this->mset('验证码错误！','tverify',$datas);
			$this->edit();
			return false;
		}
		
		$datas['memberid'] = $_SESSION['member']['id'];
		$datas['ip'] = get_client_ip();
		$datas['updatetime'] = time();
		
		/* 入库 */
		$sql_updates = "
			update iok_product 
				set 
				categoryid = '".$datas['categoryid']."',
				title = '".$datas['title']."',
				custombrandid = '".$datas['custombrandid']."',
				model = '".$datas['model']."',
				material = '".$datas['material']."',
				placeareaid = '".$datas['placeareaid']."',
				shipareaid = '".$datas['shipareaid']."',
				unit = '".$datas['unit']."',
				price = '".$datas['price']."',
				referenceprice = '".$datas['referenceprice']."',
				moq = '".$datas['moq']."',
				inventory = '".$datas['inventory']."',
				deliverydays = '".$datas['deliverydays']."',
				commission = '".$datas['commission']."',
				thumb = '".$datas['thumb']."',
				thumb1 = '".$datas['thumb1']."',
				thumb2 = '".$datas['thumb2']."',
				content = '".$datas['content']."',
				customcategoryid = '".$datas['customcategoryid']."',
				memberid = '".$datas['memberid']."',
				ip = '".$datas['ip']."',
				stateid = 1,
				onsale = 0,
				updatetime = '".$datas['updatetime']."' 
			where 
				id='".$_SESSION['inspect_product_id']."' and memberid = '".$_SESSION['member']['id']."' 
			
		";
		$results = $this->exec($sql_updates);
		if($results){
			unset($_SESSION['inspect_product_id']);
			echo '修改成功！嘿嘿~没恭喜页面呢~';return false;
			$this->productlist();
			return false;
		}else{
			$this->mset('修改失败！请重试！','toperror',$datas);
			$this->edit();
			return false;
		}
	}
	
	/* 批量下架 wuzhijie 2014年1月9日 13:30:54 */
	public function soldout(){
		$ids = trim(getvar('id'),',');
		$ip = get_client_ip();
		$sql_del = "
			update iok_product 
			set 
				onsale = 0,
				ip = '".$ip."' 
			where 
				id in('".$ids."') 
				and memberid = '".$_SESSION['member']['id']."' 
				and onsale = 1 
		";
		$result = $this->exec($sql_del);
		if($result){
			$returndata = array('state'=>1,'returndata'=>'删除成功！');
			return $returndata;
			return false;
		}else{
			$returndata = array('state'=>2,'returndata'=>'删除失败！');
			return $returndata;
			return false;
		}
		
	}
	/* 商品上架 wuzhijie 2014年1月9日 12:00:49 */
	public function putaway(){
		$ids = trim(getvar('id'),',');
		$ip = get_client_ip();
		$sql_del = "
			update iok_product 
			set 
				onsale = 1,
				ip = '".$ip."' 
			where 
				id in('".$ids."') 
				and memberid = '".$_SESSION['member']['id']."' 
				and stateid in(2,4,6,8,10,12,34) 
				and onsale = 0 
		";
		$result = $this->exec($sql_del);
		if($result){
			$returndata = array('state'=>1,'returndata'=>'已上架！');
			return $returndata;
			return false;
		}else{
			$returndata = array('state'=>2,'returndata'=>'上架失败！');
			return $returndata;
			return false;
		}
		
	}
	
	
}



?>