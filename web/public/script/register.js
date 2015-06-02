
function Dd(i) {return document.getElementById(i);}
function Ds(i) {Dd(i).style.display = '';}
function Dh(i) {Dd(i).style.display = 'none';}
function DQ(i) {return $("#"+i);}

//获得邮箱验证码
function Formcode(){
		
		var mailaddress = DQ('email').val();			
		$.ajax({
			type: "POST",
			url: "/Ajax/mail",
			data: "mailaddress="+mailaddress+"&title=我行网用户注册邮件验证码",
			success: function(msg){
				if(msg.status){
					DQ('textemailcode').html("已发送 (30)");
					alert('邮件发送成功！');
					secs = 30;
					wait = secs * 1000;
					for(i=1;i<=(wait/1000);i++) {
						window.setTimeout("doUpdate(" + i + ")", i * 1000);
					}
				}else{
					alert("提示信息: " + msg.data );
				}
			}
		});
}
//倒计时
function doUpdate(num) {
	if(num == (wait/1000)) {
		DQ('textemailcode').html('<a onclick="Formcode();" href="javascript:void(0);" class="fa_show">重新发送</a>');
	}else{
		wut = (wait/1000)-num;
		DQ('textemailcode').html("已发送 (" + wut + ")");
	}
}