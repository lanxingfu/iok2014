<?php
/**
 * 
 * Enter description here ...
 * @author sunwei
 *
 */
class MiscAction extends Action
{
	public function index()
	{
		
	}
	public function setskin()
	{
		$skin = $_GET['skin'];
		$skins = array('blue','green','brown','greyblue','purple','red');
		if(!in_array($skin, $skins))
		{
			$skin = 'blue';
		}
		cookie('skin', $skin);
	}
	public function captcha()
	{
		header("Content-type: image/png");
		srand((double)microtime() * 1000000);
		$_SESSION['captcha'] = "";
		$im = imagecreate(60, 20);
		$black = ImageColorAllocate($im, 0, 0, 0);
		$gray = ImageColorAllocate($im, 200, 200, 200);
		imagefill($im, 0, 0, $gray);
		while(($authnum = rand() % 10000) < 1000);
		$_SESSION['captcha'] = $authnum;
		imagestring($im, 5, 10, 3, $authnum, $black);
		for($i = 0; $i < 200; $i++)
		{
			$randcolor = ImageColorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
			imagesetpixel($im, rand() % 70, rand() % 30, $randcolor);
		}
		ImagePNG($im);
		ImageDestroy($im);
	}
	public function logout()
	{
		unset($_SESSION['user'], $_SESSION['syscfg']);
		$_SESSION = array();
		if(isset($_COOKIE[session_name()])) setcookie(session_name(), '', time() - 42000, '/');
		session_destroy();
		gotourl('/iokadmin.php?m=Login');
	}
	public function getusergroup()
	{
		$id = getvar('id',0,'integer');
		$did = getvar('did',0,'integer');
		$html = "<select name=\"usergroupid\">";
		if($id)
		{
			$sql = "select id, prettyname from iok_usergroup where departmentid='".$id."' and enabled=1 order by listorder,id";
			$rec = $this->arr($sql);
			if($rec)
			{
				foreach($rec as $val)
				{
					$html .= "<option value=\"".$val['id']."\"".($did == $val['id'] ? " selected": "").">".$val['prettyname']."</option>";
				}
			}
		}
		$html .= "</select>";
		echo $html;
	}

	public function t()
	{
		$sql = "select id,prettyname,parentid,pparentid from iok_area order by id,parentid,pparentid";
		$arr = $this->arr($sql);
		$area = array();
		foreach($arr as $key=>$val)
		{
			if($val['parentid'] == 0)
			{
				$area[] = array('id'=>$val['id'],'prettyname'=>$val['prettyname'],'child'=>array(array('id'=>0,'prettyname'=>'无','child'=>array(array('id'=>0,'prettyname'=>'无','child'=>array())))));
			}elseif($val['pparentid'] == 0)
			{
				foreach($area as $k=>$v)
				{
					if($v['id'] == $val['parentid'])
					{
						$area[$k]['child'][] = array('id'=>$val['id'],'prettyname'=>$val['prettyname'],'child'=>array(array('id'=>0,'prettyname'=>'无','child'=>array())));
					}
				}
			}else
			{
				foreach($area as $k=>$v)
				{
					if($v['id'] == $val['pparentid'])
					{
						foreach($v['child'] as $kk=>$vv)
						{
							if($vv['id'] == $val['parentid'])
							{
								$area[$k]['child'][$kk]['child'][] = array('id'=>$val['id'],'prettyname'=>$val['prettyname'],'child'=>array());
							}
						}
					}
				}
			}
		}
		echo json_encode($area);
	}
}
?>