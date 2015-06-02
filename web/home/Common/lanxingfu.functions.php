<?php 

//设置表单token
function set_form_token() {
	$_SESSION['form_tokenid'] = md5(randcode(32)).'_'.md5(time());
}

//验证token
function valid_form_token() {
	$return = $_REQUEST['form_tokenid'] === $_SESSION['form_tokenid'] ? true : false;
	set_form_token();
	return $return;
}

/**
 +-------------------------------------------------------------------
 * 	（获取手续费）
 +-------------------------------------------------------------------
 * @param  float	  $money  	 		金额
 +-------------------------------------------------------------------
 */
function get_poundage($money) {
	$poundage = 0;
	if($money <= 50000) {
		$poundage = 2;
	} elseif($money > 50000) {
		$poundage = $money * 0.0009;
		if($poundage > 230) $poundage = 230;
	}
	return $poundage;
}


function tracefile($data, $status = false, $filename = "console.txt")
{
	@$fp = fopen($filename, "a+");
	if(!$fp)
	{
		echo "system error";
		exit();
	}else
	{
		$fileData = date("Y-m-d h:i:s") . "\t" . ":" . "\n";
		if($status)
			$fileData = $fileData . var_export($data, TRUE) . "\n";
		else
			$fileData = $fileData . print_r($data, TRUE) . "\n";
		$fileData = $fileData . "\n";
		fwrite($fp, $fileData);
		fclose($fp);
	}
}

?>