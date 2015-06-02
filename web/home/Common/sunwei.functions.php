<?php
/**
 * @author wayne
 * 
 */
function artn($data = array(), $status = 1, $msg = '')
{
	$rtn = array('status'=>$status, 'msg'=>$msg, 'data'=>$data);
	return json_encode($rtn);
}
function sendsms($phone, $content, $num)
{
	$curtime = time();
	$password = md5('beijing2012_' . $curtime . '_topsky');
	$contents = iconv('utf-8', 'gbk', $content);
	$ispurl = 'http://admin.sms9.net/houtai/sms.php?cpid=2550&password=' . $password . '&channelid=1669&tele=' . $phone . '&msg=' . $contents . '&timestamp=' . $curtime;
	$result = file_get_contents($ispurl);
	$data = array('content'=>$content, 'phone'=>$phone, 'sendtime'=>$curtime, 'randnum'=>$num, 'state'=>$result);
	if(strpos($result, 'success') === false)
	{
		$data['status'] = -1;
		//M('Smslog')->data($data)->add();
		return $result;
	}else
	{
		$data['status'] = 0;
		//M('Smslog')->data($data)->add();
		return 0;
	}
		//return strpos($result,'success') === false ? $result : 0;
}
?>