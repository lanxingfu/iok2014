<?php
/**
 * description:  站内信模块
 * @author  jia
 * last modified by: jia
 * last modified date: 2013/12/11
 * last modified content:
 */
class MessageAction extends CommonAction{
	public function index(){	//站内信列表
	$userid = $_SESSION['member']['id'];
	//收件箱
		$type =getvar('type');
		$membertype = array('tomemberid','memberid');
		if($type=='receive'){
			$id = $membertype['0'];
			$aid = $membertype['1'];
			$deleted = 2;
		}
	//发件箱
		if($type=='send'){
			$id = $membertype['1'];
			$aid =$membertype['0'];
			$deleted = 1;
		}
		//$userid = 13;
		import("@.ORG.Util.Page");
		$sq =" select 
				count(id) as cnt
			from 	
				iok_message 
			where 	
				$id= $userid  and deleted !=$deleted and deleted!=3
			";
		$totalrecords = $this->res($sq);	
		$Page = new Page($totalrecords, 8);
		$showpage = $Page->show();
		$sql2 ="
			select 
				a.id,b.account,a.messagetype,a.title,a.orderid,a.orderurl,a.content,a.memberid,a.tomemberid,a.addtime,a.isread,a.deleted
			from 	
				iok_message as a,iok_member as b 
			where 	
				$id= $userid  and a.$aid = b.id and a.deleted != $deleted and a.deleted!=3
			order by
				addtime desc
			limit 	$Page->firstRow, $Page->listRows ";
		$rec = $this->arr($sql2);
		$this->assign('showpage',$showpage);
		$this->assign('list',$rec);
		$this->display('messagelisty');
	}
	public function mesadd(){
		$message=$this->mget();
		$this->assign('data',$message);
		$this->display('messageadd');
	}
	public function submit(){
		$memberid = $_SESSION['member']['id'];
		$datas['reciever'] = getvar('recieve'); 
		$datas['title'] = getvar('title');
		$datas['content'] = getvar('content');
		$datas['code'] = md5(getvar('verifycode'));
		if($datas['title']==''){
			$errcode = '收件标题不能为空!';
			$this->mset($errcode,'ttitle',$datas);
			$this->mesadd();
			return false;
		}
		if($datas['content']==''){
			$errcode = '收件箱内容不能为空!';
			$this->mset($errcode,'tcontent',$datas);
			$this->mesadd();
			return false;
		}
		if($_SESSION['verify']!=$datas['code']){
			$errcode = '验证码错误！';
			$this->mset($errcode,'tverify',$datas);
			$this->mesadd();
			return false;
		}
		$sql_a = "select id from iok_member where account = '".$datas['reciever']."'";
		$rec_a = $this->rec($sql_a);
			if(!empty($rec_a))
			{
				if($rec_a['id']!=$memberid){
					$tomemberid = $rec_a['id'];
					$success=$this->messageadd('iok_message',$memberid,'member',$datas['title'],$datas['content'],'','',$tomemberid,0,0);
					if($success){
						echo '站内信发送成功';
					}else{
						echo '站内信发送失败';
					}
				}else{
					echo '不能给自己发信件';
				}
			}else{
				echo '暂无收件人';
			}
	}
	public function mesdelete(){//删除站内信;
		$messageid = getvar('idlist','string');//单条站内信的id;
		$userid = $_SESSION['member']['id'];
		$idlist=trim($messageid,',');
		
		//10,9,8,7,
		//$userid = 12;
		//收件箱
		$type =getvar('type');
		$membertype = array('memberid','tomemberid');
		if($type=='receive'){
			$id = $membertype['1']; //tomemberid;
			$delstatus = 2;
		}
		//已发送
		if($type=='send'){
			$id = $membertype['0'];//memberid;
			$delstatus = 1;
		}
		$sql  ="select 
				deleted
			from 	
				iok_message
			where 	
				$id = '$userid' and id in(".$idlist.")
			";
		$result=$this->rec($sql);
		if($result['deleted']==0){
			$update = "update iok_message  set deleted='$delstatus' where $id = '$userid' and id in (".$idlist.")";
		}
		$status = $delstatus + $result['deleted'];
		$update = "update iok_message  set deleted='$status' where $id = '$userid' and id in (".$idlist.")";
		
		//echo $update;
		$success=$this->exec($update);
		if($success){
			echo '站内信删除成功';
		}
	
	}
	public function meslook(){ //查看信件
		$userid = $_SESSION['member']['id'];
		$itemid = getvar('itemid');
		$type = getvar('type');
		$membertype = array('memberid','tomemberid');
		if($type =='recieve'){
			$id = $membertype['0'];
			$sql = "select 
					*
				from 
					iok_message   
				where  
					tomemberid = '$userid' and id= '$itemid' 
				";
			$sq ="update iok_message set isread = 1 where tomemberid = '$userid' and id= '$itemid' ";
			$result = $this->exec($sq);
			$success=$this->rec($sql);
			$sql2  = " SELECT   account FROM iok_member    WHERE     id = (  SELECT 
					memberid
				FROM 
					iok_message 
				WHERE  
					tomemberid ='$userid' AND id = '$itemid' )";
			$ok=$this->rec($sql2);
			if($ok && $success){
				$this->assign('account',$ok['account']);
				$this->assign('list',$success);
			}
		}
		if($type=='send'){
			$id = $membertype['1'];
			$sql1 = "select * from iok_message where  memberid = '$userid' and id= '$itemid'";
			$success=$this->rec($sql1);
			$sql2  = " SELECT   account FROM iok_member    WHERE     id = (  SELECT 
					tomemberid
				FROM 
					iok_message 
				WHERE  
					memberid ='$userid' AND id = '$itemid' )";
			$ok=$this->rec($sql2);
			
			if($success && $ok){
				$this->assign('account',$ok['account']);
				$this->assign('list',$success);
			}
		}
		$this->display('messagelook');
	}
	public function mesclear(){//清理站内信;
		$this->display('messageclear');
	}
	public function clearsubmit(){
		//dump($_POST);
		$userid = $_SESSION['member']['id'];
		$type = getvar('type');
		$fromdate = strtotime(getvar('outdate'));
		$todate = strtotime(getvar('data'));
		$isread = getvar('isread');
		if($fromdate >= $todate){
			echo '对不起开始时间不能大于截至时间';
			return false;
		}
		if($type ==1){//收件箱;
			if($isread==1){
				$sql =" select 
						*
					from 
						iok_message 
					where 
						isread = 1 and addtime >= ".$fromdate." and addtime <=".$todate." and tomemberid = ".$userid."
					";
				$result=$this->arr($sql);
				if($result){
					foreach($result as $v){
						$sql2 = "
							update
								iok_message 
							set 
								deleted = 3
							where 
								tomemberid = $userid and id = ".$v['id']." and isread = 1 and addtime >= $fromdate and addtime <=$todate
						";
						$res=$this->exec($sql2);
					}
					if($res){
						echo '清理成功';
					}else{
						echo '清理失败';
					}
				}else{
					echo '暂无信件可以清理';
				}
			}else{
				$sql =" select 
						*
					from 
						iok_message 
					where 
						addtime >= ".$fromdate." and addtime <=".$todate." and tomemberid = ".$userid."
					";
				$result=$this->arr($sql);
				if($result){
					foreach($result as $v){
						$sql2 = "
							update
								iok_message 
							set 
								deleted = 3
							where 
								tomemberid = $userid and id = ".$v['id']."  and addtime >= $fromdate and addtime <=$todate
						";
						$res=$this->exec($sql2);
					}
					if($res){
						echo '所有信件清理成功';
					}else{
						echo '清理失败';
					}
				}else{
					echo '暂无数据可清理';
				}
			}
		}
		if($type ==2){//已发送;
			$sql =" select 
					*
				from 
					iok_message 
				where 
					  addtime >= ".$fromdate." and addtime <=".$todate." and memberid = ".$userid."
				";
			$result=$this->arr($sql);
			if($result){
				foreach($result as $v){
					$sql2 = "
						update
							iok_message 
						set 
							deleted = 3
						where 
							memberid = $userid and id = ".$v['id']."  and addtime >= $fromdate and addtime <=$todate
					";
					$res=$this->exec($sql2);
				}
				if($res){
					echo '清理成功';
				}else{
					echo '清理失败';
				}
			}else{
				echo '暂无信件可以清理';
			}	
		}	
	}

}