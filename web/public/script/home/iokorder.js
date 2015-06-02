function showStaff(id){
	$('#boox_'+id).show();
}
function hideStaff(id){
	$('#boox_'+id).hide();
}
 //检测价格输入是否正确
function checkPrice(price,id) {
	var val = price;
	var inputId = id;
	var regexp = /^[0-9.]{1,15}?$/g;
	if(!regexp.test(val)) {
		$("#"+inputId).val('');
	}
}
//修改价格订单价格 
$("#confirmsubmit").click( function () {
	 
	 var id = parseInt($('#id').val());
	 var shipfare = parseFloat($('#fee').val());
	 var discountamount = parseFloat($('#privilege').val());
	 var receivables = parseInt($("input[name='receivables']:checked").val());
	 $.ajax({
		type: "POST",
		url: "/Order/modifypriceexcute",
		data: "id="+id+"&shipfare="+shipfare+"&discountamount="+discountamount+"&receivables="+receivables,
		dataType:'json',
		success: function(msg){
			if(msg.success == 200) {
				$.jBox.prompt(msg.msg, '修改价格', 'success', { closed: function () { 
						window.location.href=msg.url;
				 } });
			} else {
				$.jBox.error(msg.msg, '修改价格');
			}
			 
		}
	});
} );

//确认订单
function confirm_order(orderid) {
	var confirmorder = function (v, h, f) {
		if (v == 'ok') {
			$.ajax({
				type: "POST",
				url: "/Order/ConfirmOrder",
				data: "orderid="+orderid,
				dataType:"json",
				success: function(msg){
					if(msg.success == 200) {
						$.jBox.prompt(msg.msg, '确认订单', 'success', { closed: function () { 
							window.location.href=msg.url;
						} });
					} else {
						$.jBox.error(msg.msg, '确认订单');
					}
				}
			});
			
		} else if (v == 'cancel') {
			// 取消
		}
		return true; //close
	};
	$.jBox.confirm("确实要确认此订单吗？此操作将不可撤销", "确认订单", confirmorder);
}

/**************首信易支付  开始***************/
function checkPayeaseForm() {
	$.jBox.confirm("您确认此订单，并立即支付吗？", "确认订单", function(v,h,f){
		if (v == 'ok') {
			TanShow();
			$("#dform").attr("onsubmit","return true");
			$("#gobuy").click();
			$('#gobuy').attr("disabled","true");
			
		} else if (v == 'cancel') {
			// 取消
		}
		//return true; //close
	});
	return false;
}
	
function TanShow(){
	var scrollTop = $(document).scrollTop(); 
	var top = ($(window).height() - $("#tan_hide_show").height())/2 + scrollTop -50;   
	var right = ($(window).width() - $("#tan_hide_show").width())/2;  
	$("#tan_hide_show").css({'top' : top, 'right' : right });
	$('#iok_zhezhao').css({"width":$(window).width(),"height":$(window).height()});
	$('#iok_zhezhao').show();
	$('#tan_hide_show').fadeIn(300);
}

function closeTipsBox() {
	$('#iok_zhezhao').hide();
	$('#tan_hide_show').fadeOut(300);	
}
/**************首信易支付  结束***************/




 
function closeOrder(itemid) {
	var id = itemid
	$.iokPlugin.confirm('您确认要关闭此交易吗？此操作将不可撤销!',function(){
	window.location.href="/Order/closeTrade/itemid/"+id;	
	},function(){});
}

 
function extenTime(id) {
	var div=document.getElementById(id);
	if( div.style.display=='none' ) {
		$("#"+id).show("slow");
	} else{
		$("#"+id).hide("slow");
	}
 	
}

function extendInvoice(id){ 
	var div=document.getElementById(id);
	if( div.style.display=='none' ) {
		$("#"+id).show("slow");
	} else{
		$("#"+id).hide("slow");
	}
}

function submitExtendTime(id) {
	$.iokPlugin.confirm('您确认要延长收货时间吗？',function(){
	var extend_time = $("input[name='extend_time']:checked").val();
	var order_id = id;
	$.ajax({
		type: "POST",
		url: "/Order/extendTime",
		data: "orderid="+order_id+"&extend_time="+extend_time,
		success: function(msg){
			if(msg.indexOf('ok')>=0) {
				$.iokPlugin.alert( "成功延长收货时间" )
				$("#extend_time_"+id).hide("slow")
				window.location.reload()
			} else if (msg.indexOf('no')>=0) {
				$.iokPlugin.alert( "延长收货时间失败" )
				$("#extend_time_"+id).hide("slow")
				window.location.reload();
			} else if(msg.indexOf('repeat')>=0) {
				$.iokPlugin.alert( "已经延长过收货时间，不能重复延长" )
				$("#extend_time_"+id).hide("slow")
				window.location.reload();
			} else if(msg.indexOf('empty')>=0) {
				$.iokPlugin.alert( "延长时间数据不全" )
				$("#extend_time_"+id).hide("slow")
				window.location.reload();
			} else {
				$.iokPlugin.alert( "操作错误" )
				$("#extend_time_"+id).hide("slow")
				window.location.reload()
			}
		}
	});	
	},function(){})
}

function submitExtendInvoice(id) {
	$.iokPlugin.confirm('您确认要延长收发票时间吗？',function(){
		var extend_time = $("input[name='extend_invoice_time']:checked").val();
		var order_id = id;
		$.ajax({
			type: "POST",
			url: "/Order/extendInvoiceTime",
			data: "orderid="+order_id+"&extend_time="+extend_time,
			success: function(msg){
				if(msg.indexOf('ok')>=0) {
					$.iokPlugin.alert( "成功延长收发票时间" );
					$("#extend_invoice_"+id).hide("slow");
					window.location.reload();
				} else if (msg.indexOf('no')>=0) {
					$.iokPlugin.alert( "延长收发票时间失败" );
				} else if(msg.indexOf('repeat')>=0) {
					$.iokPlugin.alert( "已经延长过收发票时间，不能重复延长" );
					$("#extend_invoice_"+id).hide("slow");
					window.location.reload();
				} else if(msg.indexOf('empty')>=0) {
					$.iokPlugin.alert( "延长时间数据不全" );
					$("#extend_invoice_"+id).hide("slow");
					window.location.reload();
				} else{
					$.iokPlugin.alert( "操作错误" );
					$("#extend_invoice_"+id).hide("slow");
					window.location.reload();
				}
			}
		});	
	},function(){})
}