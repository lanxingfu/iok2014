<?php

class UploadfileAction extends CommonAction {
	
	public function upload() {
        if (!empty($_FILES)) {
            //如果有文件上传 上传附件
            $this->_upload();
        }else{
			$this->display("Public:upload");
		}
    }
	
	// 文件上传
    protected function _upload() {
		/* 获取后缀名 小写 */
		$up_ext = strtolower(strrchr($_REQUEST['Filename'],'.'));
		//上传路径设置
		$upPaths = '/file/upload/'.date("Ym")."/".date("d")."/";
		$upPaths_up='.'.$upPaths;
		//文件保存路径设置
		$savePaths = 'http://'.$_SERVER['HTTP_HOST'].$upPaths;
		import("@.ORG.Util.UploadFile");
        //导入上传类
        $upload = new UploadFile();
		
        //设置上传文件大小
        //$upload->maxSize = 3292200;
		$upload->maxSize = 2097155;
        //设置上传文件类型
        $upload->allowExts = explode(',', 'jpg,gif,png,jpeg');
		
		//目录上传检测及更改目录权限
		if(!is_dir($upPaths_up)){
			@mkdir($upPaths_up,0777,true);
		}
		//设置附件上传目录
        $upload->savePath = $upPaths_up;
        //设置上传文件规则
        $upload->saveRule = uniqid;
		
		//设置需要生成缩略图，仅对图像文件有效
        $upload->thumb = true;
        // 设置引用图片类库包路径
        $upload->imageClassPath = '@.ORG.Util.Image';
        //设置需要生成缩略图的文件前后缀
        $upload->thumbPrefix = ',,';  //生产3张缩略图
		$upload->thumbSuffix = "$up_ext.thumb,$up_ext.middle,$up_ext.large";  //生产3张缩略图
        //设置缩略图最大宽度
        $upload->thumbMaxWidth = '200,400,800';
        //设置缩略图最大高度
        $upload->thumbMaxHeight = '150,300,600';
		//生成缩略图后是否删除原图
        $upload->thumbRemoveOrigin = false;
		
		
        if (!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        } else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $uploadList = $uploadList[0];
			
			//备份原图
			//copy($fileimgname,$uploadList['savepath'].'backup_'.$uploadList['savename']);
			//$fileimgname = $uploadList['savepath'].$uploadList['savename'];
			/* 图片处理 */
			//import("@.ORG.Util.Image");
            //给m_缩略图添加水印, Image::water('原文件名','水印图片地址')   
			//Image::water($uploadList['savepath'].$uploadList['savename'],'./Public/images/shuiyin.png',null,100,true);
            //Image::water($fileimgname.'.large'.$up_ext, './Public/images/shuiyin.png',null,100,true);
			//Image::water($fileimgname.'.middile'.$up_ext, './Public/images/shuiyin2.png',null,100,true);
			
			//返回最小缩略图地址（入库地址）
			echo $savePaths.$uploadList['savename'].'.thumb'.$up_ext;
			
        }
		
    }
	/* 
		删除上传图片 单图片
		2013年8月30日 13:00:03
		wuzhijie
		数值定义：	0-删除失败；
					1-成功，删除空目录；
					2-成功，未删除目录；
					3-文件不存在
	*/
	public function imgdel(){
		/* 图片地址 */
		$imgurl = $_POST['imgurl'];
		/* 获取图片相对路径 */
		$imgurl_t = explode('file/upload',$imgurl);
		/* 拼接图片目录路径 */
		$delurl = THINK_PATH.'../file/upload'.$imgurl_t['1'];
		/* 获取图片信息 */
		$imginfo = pathinfo($delurl);
		/* 获取图片所在目录 */
		$imgdir = $imginfo['dirname'];
		
		if(file_exists($delurl)){
			
		    if(unlink( $delurl )){
				
				if (rmdir($imgdir)) {
					/* 删除成功 目录空，删除目录*/
					echo 1;
					exit;
				}else{
					/* 删除文件成功 目录不为空，未删除目录 */
					echo 2;
					exit;
				}
			}else{
				/* 删除失败 */
				echo 0;
			}
		}else{
			/* 文件不存在 */
			echo 3;exit;
		}
	}

}

?>