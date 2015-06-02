/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function(config)
{
	// Define changes to default configuration here. For example:
	config.language = 'zh-cn';
	// config.uiColor = '#AADC6E';
	config.skin = 'office2003';
	config.scayt_autoStartup = false;
	config.filebrowserBrowseUrl = window.CKEDITOR_BASEPATH + 'ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = window.CKEDITOR_BASEPATH + 'ckfinder/ckfinder.html?Type=Images';
	config.filebrowserFlashBrowseUrl = window.CKEDITOR_BASEPATH + 'ckfinder/ckfinder.html?Type=Flash';
	config.filebrowserUploadUrl = window.CKEDITOR_BASEPATH + 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = window.CKEDITOR_BASEPATH + 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = window.CKEDITOR_BASEPATH + 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';
};
