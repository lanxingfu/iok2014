/* 

__APP__/Uploadfile/upload/
__PUBLIC__/js/swfupload/flash/swfupload.swf
'phones'
'<a class="butstyle">【浏览】</a>'
".butstyle {font-size:12px;}"
"thumbnails"
"divFileProgressContainer"
--------------------------------
loadurl：上传控制器路径
furl：swf文件路径
filenames：上传图片字段名
uplimit：上传文件最多数量限制
qlimit：一次上传文件的数量限制
in_id：上传按钮 id
width：按钮宽度
height：按钮高度
btext：按钮文字
bstyles：按钮样式
isonly：打开浏览窗口是否可以同事选中多文件
imgid：图片预览 img id
divproconid：上传提示（是否成功）文字位置 id
inputsrc：页面图片显示位置 id
--------------------------------

 */

function swfloadin(loadurl,furl,filenames,uplimit,qlimit,in_id,width,height,btext,bstyles,isonly,imgid,divproconid,inputsrc){
	if(isonly){
		var selfiles = "SWFUpload.BUTTON_ACTION.SELECT_FILE";
	}else{
		var selfiles = "SWFUpload.BUTTON_ACTION.SELECT_FILES";
	}
	var swfu = new SWFUpload({
		upload_url : loadurl,
		flash_url : furl,
		file_size_limit : "2048",
		file_post_name:filenames,
		file_types:"*.jpg;*.gif;*.png;*.jpeg",
		file_upload_limit:uplimit,
		file_queue_limit:qlimit,
		
		button_placeholder_id : in_id,
		button_width: width,
		button_height: height,
		button_cursor : SWFUpload.CURSOR.HAND,
		button_text: btext,
		button_text_style:bstyles,
		button_action:selfiles,
		
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : fileDialogComplete,
		upload_error_handler : uploadError,
		upload_success_handler : uploadSuccess,
		upload_complete_handler : uploadComplete,
		custom_settings : {
			upload_target : divproconid,
			upload_img_div: imgid,
			upload_input_src:inputsrc
		},
		
		
		debug: false
	});
}