<?php
/**
 * 
 * 求购
 * @author lee
 *
 */
class BuyAction extends CommonAction
{
	public function _initialize(){
		$this->assign("inMall","link_in_header");
	}
	//首页
	public function index(){
		//最新供应
		$sql="
			select 
				id,
				title,
				price 
			from 
				iok_buy 
			order by 
				updatetime desc 
			limit 0,8";
		$buylist=$this->arr($sql);
		foreach($buylist as $bl){
			$buylist['title']=msubstr($bl['title'],0,6,'utf-8',true);
		}
		//随机供应
		$sql="
			select id,title,amount,expiredtime from iok_buy limit 0,8
				";
		$randlist=$this->arr($sql);
		//供应列表
		$sql="
			select 
				id,
				arrchildid,
				prettyname 
			from 
				iok_category 
			where 
				parentid=0  and categorytype=8";
		$catelist=$this->arr($sql);
		$list=array();
		foreach($catelist as $ck=>$cl){
			$list[$ck]['id']=$cl['id'];
			$list[$ck]['title']=$cl['prettyname'];
			$sql="select id,title,amount,expiredtime from iok_buy where categoryid in(".$cl['arrchildid'].") limit 0,8";
			$list[$ck]['list']=$this->arr($sql);
			if($cl['expiredtime']==0){
				$list[$ck]['expiredtime']=="长期";
			}elseif($cl['expiredtime']<time()){
				$list[$ck]['expiredtime']=="已过期";
			}else{
				$list[$ck]['expiredtime']=ceil(($cl['expiredtime']-time())/(24*3600))."天";
			}
		}
		
		//商务服务站
		$sql="
			select 
				f.star,
				i.prettyname,
				i.address,
				p.imageurl
			from
				iok_member m
				left join iok_memberinfo i
			on 
				m.id=i.memberid
				left join iok_memberproof p
			on 
				m.id=p.memberid
				left join iok_memberstaff f
			on 
				m.id=f.memberid 
			where
				m.gradeid=5 and p.prooftypeid=4 and m.id not in (21484,22200,21440,20703)
			order by
				m.registertime desc
			limit
				16
		";
		$buslist=$this->arr($sql);
		foreach($buslist as $busk=>$bus){
			if($busk%4==0){
				$buslist[$busk]['s']=true;
			}
			if(($busk+1)%4==0){
				$buslist[$busk]['e']=true;
			}
		}
		//广告
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='C1')";
		$adc1=$this->arr($sql);
		$sql="select linkurl,imageurl,title from iok_adschedule where adplaceid=(select id from iok_adplace where name='C2')";
		$adc2=$this->arr($sql);
		// dump($buslist);
		$this->assign("randlist",$randlist);
		$this->assign("buylist",$buylist);
		$this->assign("list",$list);
		$this->assign("buslist",$buslist);
		$this->assign("adc1",$adc1);
		$this->assign("adc2",$adc2);
		
		$this->display();
	}
	
	//求购分类列表页
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
			$returnp.="<span>&gt;&nbsp;<a href='/Buy/listy/catid/".$p['id']."'>".$p['prettyname']."</a></span>";
		}
		$returnp.="<span>&gt;&nbsp;<a href='/Buy/listy/catid/".$cate['id']."'>".$cate['prettyname']."</a></span>";
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
		if(!in_array($sortby, array('price', 'hits', 'updatetime','goodcomment'))){
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
				lastdays,
				amount,
				price,
				unit,
				thumb,
				expiredtime,
				areaid,
				hits,
				updatetime
			from 
				iok_buy 
			where 
				enabled=1 and
				categoryid in(".$arr['arrchildid'].")".$order." 
			limit ".$Page->firstRow.",".$Page->listRows;
		$list=$this->arr($sql);
		foreach($list as $k=>$l){
			$list[$k]['areaid']=get_areaname($l['areaid']);
			if($l['expiredtime']==0){
				$list[$k]['expiredtime']=="长期";
			}elseif($l['expiredtime']<time()){
				$list[$k]['expiredtime']=="已过期";
			}else{
				$list[$k]['expiredtime']=ceil(($l['expiredtime']-time())/(24*3600))."天";
			}
		}
		//热销产品
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
				sales desc
			limit
				6";
		$hotlist=$this->arr($sql);
		// dump($sonlist);
		$this->assign("nav",$returnp);
		$this->assign("hotlist",$hotlist);
		$this->assign("sonlist",$sonlist);
		$this->assign("list",$list);
		$this->assign("cateid",$id);
		$this->assign("orderby",$newby);
		$this->assign("sortby",$sortby);
		$this->assign("showpage",$showpage);//分页
		$this->assign("inBuy","link_in_header");
	
		$this->display();
	}
	
	//求购产品详情页
	public function show(){
		$id=getvar("itemid");
		//类别导航
		$sql="
			select 
				categoryid 
			from 
				iok_buy 
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
			$returnp.="<span>&gt;&nbsp;<a href='/Buy/listy/catid/".$p['id']."'>".$p['prettyname']."</a></span>";
		}
		$returnp.="<span>&gt;&nbsp;<a href='/Buy/listy/catid/".$cate['id']."'>".$cate['prettyname']."</a></span>";
		//产品详情
		$sql="
			select
				id,
				memberid,
				title,
				expiredtime,
				areaid,
				amount,
				price,
				unit,
				thumb,
				thumb1,
				thumb2,
				updatetime,
				content,
				hits
			from 
				iok_buy 
			where 
				id=".$id;
		$list=$this->rec($sql);
		if($list['updatetime']){
			$list['updatetime']=date("Y-m-d H:i:s",$list['updatetime']);
		}
		$list['areaid']=get_areaname($list['areaid']);
		if($list['expiredtime']==0){
			$list['expiredtime']=="长期";
		}elseif($list['expiredtime']<time()){
			$list['expiredtime']=="已过期";
		}else{
			$list['expiredtime']=ceil(($list['expiredtime']-time())/(24*3600))."天";
		}
		
		
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
						iok_memberinfo i 
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
		
		//猜你喜欢（随机敢数据）
		$sql="
			select 
				id,
				thumb,
				title,
				price
			from 
				iok_buy
			order by
				hits desc
			limit 6";
		$gass=$this->arr($sql);
		foreach($gass as $gak=>$ga){
			$gass[$gak]['title']=msubstr($ga['title'],0,8,'utf-8',flase);
		}
		//最新求购
		if($list['memberid']){
			$sql="
			select 
				id,
				thumb,
				title,
				price
			from 
				iok_buy
			where
				memberid=".$list['memberid']."
			order by
				addtime desc
			limit 4";
			$newlist=$this->arr($sql);
		}
		
		$this->assign("list",$list);//产品详情
		$this->assign("returnp",$returnp);//导航
		$this->assign("member",$member);//获取渠道商表信息
		$this->assign("gass",$gass);//猜你喜欢
		$this->assign("newlist",$newlist);//最新求购
		$this->assign("represent",$represent);//获取商务代表信息
		// dump($list);
		$this->display();
	}

	
	// 求购中 wuzhijie 2014年1月13日 14:20:45
	public function published(){
		$this->assign('menus',2);
		$sql_list = "
			select * from iok_buy 
			where 
				memberid = '".$_SESSION['member']['id']."' 
				and enabled = 1 
				and stateid not in(2,4,6,8,10,12,34) 
			order by id desc 
			limit 0,10 
		";
		$list = $this->arr($sql_list);
		$this->assign('list',$list);
		
		$this->display("buylist");
	}
	
	// 审核中
	public function check(){
		$this->assign('menus',3);
		$sql_list = "
			select * from iok_buy 
			where 
				memberid = '".$_SESSION['member']['id']."' 
				and enabled = 1 
				and stateid not in(2,4,6,8,10,12,34) 
			order by id desc 
			limit 0,10 
		";
		$list = $this->arr($sql_list);
		$this->assign('list',$list);
		
		$this->display("buylist");
	}
	
	// 审核未通过中
	public function notpass(){
		$this->assign('menus',4);
		$sql_list = "
			select * from iok_buy 
			where 
				memberid = '".$_SESSION['member']['id']."' 
				and enabled = 1 
				and stateid not in(2,4,6,8,10,12,34) 
			order by id desc 
			limit 0,10 
		";
		$list = $this->arr($sql_list);
		$this->assign('list',$list);
		
		$this->display("buylist");
	}
	
	// 过期求购
	public function pastdue(){
		$this->assign('menus',5);
		$sql_list = "
			select * from iok_buy 
			where 
				memberid = '".$_SESSION['member']['id']."' 
				and enabled = 1 
				and stateid not in(2,4,6,8,10,12,34) 
			order by id desc 
			limit 0,10 
		";
		$list = $this->arr($sql_list);
		$this->assign('list',$list);
		
		$this->display("buylist");
	}
	
	//发布求购
	public function addbuy(){
		/* menu */
		$this->assign('menus',1);
		/* 添加求购，无需验证id 清空 */
		unset($_SESSION['inspect_buy_id']);
		
		/* 错误返回值 */
		$returndatas = $this->mget();
		$this->assign('returndatas',$returndatas);
		
		$this->display('edit');
	}
	
	//提交求购
	public function addsubmit(){
		
		$data['categoryid']= getvar('categoryid');
		$data['memberid'] = $_SESSION['member']['id'];
		$data['title'] = getvar('title');			//信息标题
		$data['amount'] = getvar('amount');			//需求数量
		$data['unit'] = getvar('unit');				//单位
		$data['price'] = getvar('price');			//价格要求
		$data['areaid'] = getvar('areaid');			//收货地
		$data['lastdays'] = getvar('lastdays');     //有效期，X天
		$data['expiredtime'] = time()+$data['lastdays']*24*60*60; //过期日期Unix时间戳
		$data['thumb'] = getvar('imgurl');
		$data['thumb1'] = getvar('imgurl1');
		$data['thumb2'] = getvar('imgurl2');
		$data['content'] = getvar('content');		
		
		$data['ip'] = get_client_ip();
		$data['addtime']=time();
		$data['updatetime']=time();
		
		$verifycode = getvar('verifycode');
		
		if($data['categoryid'] =='' ){
			$this->mset('请选择产品分类！','tcategory',$data);
			$this->addbuy();
			return false;
		}
		if($data['title']=='' ){
			$this->mset('请填写信息标题！','ttitle',$data);
			$this->addbuy();
			return false;
		}
		if($data['amount']=='' ){
			$this->mset('请填写需求数量！','tamount',$data);
			$this->addbuy();
			return false;
		}
		if($data['unit']=='' ){
			$this->mset('请填写单位！','tunit',$data);
			$this->addbuy();
			return false;
		}
		if($data['price']=='' ){
			$this->mset('请填价格要求！','tprice',$data);
			$this->addbuy();
			return false;
		}
		if($data['areaid']=='' ){
			$this->mset('请选择收货地！','tareaid',$data);
			$this->addbuy();
			return false;
		}
		if($data['thumb']=='' ){
			$this->mset('请上传第一张商品图片！','tthumb',$data);
			$this->addbuy();
			return false;
		}
		
		if($data['content']=='' ){
			$this->mset('请填写商品详情！','tcontent',$data);
			$this->addbuy();
			return false;
		}
		if(md5($verifycode)!=$_SESSION['verify'] ){
			$this->mset('验证码错误！','tverify',$data);
			$this->addbuy();
			return false;
		}
		
		/* if(!preg_match('/[0-9]*[1-9][0-9]*$/',$data['amount'])){
			$this->wrong('需求数量为数字');
		}
		if(!preg_match('/^[0-9]{1,10}([.]{1}[0-9]{1,4})?$/',$data['price'])){
			$this->wrong('价格为小数(小数点后最多四位)及正整数(小数点前最多10位)');
		} */
		
		/* 入库 */
		$result = $this->ins('iok_buy',$data);
		
		$this->published();
	    
	}
	
	//编辑求购商品
	public function edit(){
		$this->assign('menus',1);
		
		$postdata = $this->mget();
		$this->assign('returndatas',$postdata);
		
		$id = getvar('id');
		if($id){
			/* 商品id 存入session */
			$_SESSION['inspect_buy_id'] = $id;
		}
		if(empty($_SESSION['inspect_buy_id'])){
			redirect('/Buy/published',3,'请选择商品，页面还没有，，，唉，，，');
			return false;
		}
		$sql_buy = "
			select 
				id,
				categoryid,
				title,
				amount,
				unit,
				price,
				areaid,
				lastdays,
				thumb,
				thumb1,
				thumb2,
				content
			from iok_buy 
			where id='".$_SESSION['inspect_buy_id']."' and memberid = '".$_SESSION['member']['id']."' 
		";
		if(!$postdata){
			$buydata = $this->rec($sql_buy);
			$this->assign('returndatas',$buydata);
		}
		
		$this->display('edit');
		
	}
	
	//保存修改
	public function editsave(){
		
		$data['categoryid']= getvar('categoryid');
		$data['memberid'] = $_SESSION['member']['id'];
		$data['title'] = getvar('title');			//信息标题
		$data['amount'] = getvar('amount');			//需求数量
		$data['unit'] = getvar('unit');				//单位
		$data['price'] = getvar('price');			//价格要求
		$data['areaid'] = getvar('areaid');			//收货地
		$data['lastdays'] = getvar('lastdays');     //有效期，X天
		$data['expiredtime'] = time()+$data['lastdays']*24*60*60; //过期日期Unix时间戳
		$data['thumb'] = getvar('imgurl');
		$data['thumb1'] = getvar('imgurl1');
		$data['thumb2'] = getvar('imgurl2');
		$data['content'] = getvar('content');		
		
		$data['ip'] = get_client_ip();
		$data['updatetime']=time();
		$data['published'] = 0;
		
		$verifycode = getvar('verifycode');
		
		if($data['categoryid'] =='' ){
			$this->mset('请选择产品分类！','tcategory',$data);
			$this->edit();
			return false;
		}
		if($data['title']=='' ){
			$this->mset('请填写信息标题！','ttitle',$data);
			$this->edit();
			return false;
		}
		if($data['amount']=='' ){
			$this->mset('请填写需求数量！','tamount',$data);
			$this->edit();
			return false;
		}
		if($data['unit']=='' ){
			$this->mset('请填写单位！','tunit',$data);
			$this->edit();
			return false;
		}
		if($data['price']=='' ){
			$this->mset('请填价格要求！','tprice',$data);
			$this->edit();
			return false;
		}
		if($data['areaid']=='' ){
			$this->mset('请选择收货地！','tareaid',$data);
			$this->edit();
			return false;
		}
		if($data['thumb']=='' ){
			$this->mset('请上传第一张商品图片！','tthumb',$data);
			$this->edit();
			return false;
		}
		
		if($data['content']=='' ){
			$this->mset('请填写商品详情！','tcontent',$data);
			$this->edit();
			return false;
		}
		if(md5($verifycode)!=$_SESSION['verify'] ){
			$this->mset('验证码错误！','tverify',$data);
			$this->edit();
			return false;
		}
		
		/* 入库 */
		$sql_updates = "
			update iok_buy 
				set 
				categoryid = '".$data['categoryid']."',
				memberid = '".$data['memberid']."',
				title = '".$data['title']."',
				amount = '".$data['amount']."',
				unit = '".$data['unit']."',
				price = '".$data['price']."',
				areaid = '".$data['areaid']."',
				lastdays = '".$data['lastdays']."',
				expiredtime = '".$data['categoryid']."',
				thumb = '".$data['thumb']."',
				thumb1 = '".$data['thumb1']."',
				thumb2 = '".$data['thumb2']."',
				content = '".$data['content']."',
				ip = '".$data['ip']."',
				updatetime = '".time()."',
				published = 0 
			where 
				id='".$_SESSION['inspect_buy_id']."' and memberid = '".$_SESSION['member']['id']."' 
		";
		$results = $this->exec($sql_updates);
		if($results){
			unset($_SESSION['inspect_buy_id']);
			echo '修改成功！嘿嘿~没恭喜页面呢~';return false;
			$this->published();
			return false;
		}else{
			$this->mset('修改失败！请重试！','toperror',$data);
			$this->edit();
			return false;
		}
	}
	
	
	
	
	
	
	
	
	
	
}
?>