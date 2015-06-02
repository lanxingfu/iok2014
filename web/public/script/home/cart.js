
//商品详情页购买数量增加减少   xxxx
function modifyNumber(state,moq,inventory){
	var minamount = moq;
	var amount = inventory;
	var num = $('#goods_num').val();
	if( isNaN($('#goods_num').val()) || $('#goods_num').val()<=0 ){
		$.jBox.tip('请您输入正确的订购数量','error');
		$("#goods_num").val(minamount);
		return false;
	}
	if(state){
		if(num >= amount) {
			num = amount;
			$.jBox.tip('对不起!您购买的数量超过了库存总量','error');
		} else {
			num++;
		}
	} else {
		if(num <= minamount) {
			num = minamount;
			$.jBox.tip('购买数量不能小于最小起订量','error');
		} else {
			num--;
		}
	}
	$('#goods_num').val(num);										
}


//加入购物车  XXXX
function addToCart( id,moq,inventory,status,userid ) {
    var islogin = userid;
	var goods_number = parseInt($('#goods_num').val());
	if(islogin==0) {
		 
		login_box()
		
	} else {
		if( isNaN($('#goods_num').val()) || $('#goods_num').val()<=0 ){
			$.jBox.tip('请您输入正确的订购数量','error');
			$("#goods_num").val(moq);
			return false;
		}
		if(status!='audit'){
			$.jBox.tip('对不起！该商品已下架或未通过审核','error');
			return false;
		} else {
			if( goods_number > inventory ) {
				$.jBox.tip('对不起!您购买的数量超过了库存总量','error');
			} else if( goods_number < moq ) {
				$.jBox.tip('购买数量不能小于最小起订量','error');
				$('#goods_num').val(moq)
			} else {
				$.ajax({
					type: "POST",
					url: "/Cart/putInCart",
					data: 'id='+id+'&quantity='+goods_number,
					dataType:"json",
					success: function(msg){
						//alert(msg.success)
						if( msg.success == 200 ) {
						   $.jBox.messager("商品成功加入购物车！", "购物车", 5000, {
								width: 350,
								icon: 'success',
								showType: 'show',
								buttons: { '查看购物车': true },
								submit: function (v, h, f) {
									window.location.href="/Cart/index";
									return true;
								}
							});
						} else {
							$.jBox.tip('对不起！系统发生错误！请您联系我们网站客服','error');
						}
						
					}
				});
				
			}		
		}
		
	}
}
 
//立即购买
function nowbuy(id) {
	var number = $('#goods_num').val();
	var productid = id;
	window.location.href="/Cart/settlement/productid/"+productid+"/number/"+number;
}

//从购物车单个结算
function singleSettlement(id) {
	var number = $('#quantity_'+id).val();
	var productid = id;
	window.location.href="/Cart/settlement/productid/"+productid+"/number/"+number;
}

//页面加载完毕时
$(document).ready(function(){
$("input[name='quantity[]']").bind('change', function() {
	var id = $(this).attr("rel");    //商品ID
	var minamount = $('#moq_'+id).val();  //最小起定量
	var inventory = $('#inventory_'+id).val(); //库存总量
	var quantity = $('#quantity_'+id).val();  //购买数量
	var price = $('#price_'+id).val();   //单价
	var ordertype = $('#ordertype').val()==1?1:0;
	if( !( ( parseInt(quantity) <= parseInt(inventory) ) && ( parseInt(quantity) >= parseInt(minamount) ) ) ) {
		$.jBox.tip('购买数量不能小于起订量和不能大于库存量', 'warning');
		quantity = parseInt(minamount);
		$('#quantity_'+id).val(quantity);
		//更新小计
		var subtotal = '&yen;'+toDecimal2(toDecimal2(price)*quantity);
		$("#small_note_"+id).html(subtotal);
		//Ajax更新购物车
		$.ajax({
			type: "POST",
			url: "/Cart/updateCart/?rand="+Math.random(),
			data: 'productid='+id+'&quantity='+quantity+'&ordertype='+ordertype,
			dataType:"json",
			cache:false,
			success: function(msg){
				if(msg.success == 'ok') {
					var total_price = '&yen;'+msg.price_total;
					$("#total_price").html(total_price);
				} else {
					$.jBox.tip('您提供的参数有误！', 'warning');
				}
			}
		});
		return false;
	}
	//更新小计
	var subtotal = '&yen;'+toDecimal2(toDecimal2(price)*quantity);
	$("#small_note_"+id).html(subtotal);
	//Ajax更新购物车
	$.ajax({
		type: "POST",
		url: "/Cart/updateCart/?rand="+Math.random(),
		data: 'productid='+id+'&quantity='+quantity+'&ordertype='+ordertype,
		dataType:"json",
		cache:false,
		success: function(msg){
			if(msg.success == 'ok') {
				var total_price = '&yen;'+msg.price_total;
				$("#total_price").html(total_price);
			} else {
				$.jBox.tip('您提供的参数有误！', 'warning');
			}
		}
	});

});
//全选复选框
$("#chk_all").click(function(){
	 if($("#chk_all").attr("checked") == true ){
		$("input[name='itemids[]']").attr("checked",$(this).attr("checked"));
	 }else{
		$("input[name='itemids[]']").attr("checked",false);
	 }
});

//删除所选商品
$("#delSeletectd").click(function(){
	var deleteAll = function (v, h, f) {
	if (v == 'ok') {
	 
		var chk_value =[]; 
		$("[name='itemids[]']:checked").each(function(){ 
			 chk_value.push($(this).val()); 
		})
		//alert(chk_value.length==0 ?'你还没有选择任何内容！':chk_value);
		if (chk_value.length==0)
		{
			$.jBox.tip('您还没有选择任何商品!', 'warning');
		} else {
			$.ajax({
			type: "POST",
			url: "/Cart/deleteCart/?rand="+Math.random(),
			data: "id="+chk_value,
			dataType:"json",
			cache:false,
			success: function(msg){
				if(msg.success == 'ok') {
					$.jBox.tip("正在删除数据...", 'loading');
					window.location.reload();
					// 模拟2秒后完成操作
					//window.setTimeout(function () { $.jBox.tip('删除成功。', 'success'); }, 2000);	
				} else {
					$.jBox.tip('删除失败。', 'error');
				}
			}
			});
		}
	} else if (v == 'cancel') {
		// 取消
	}
		return true; //close
	};
	$.jBox.confirm("确认要删除所选商品吗？", "提示", deleteAll);
})
});
//删除单个商品
function deleteSingle(productid){	
	var submit = function (v, h, f) {
		if (v == 'ok') {
			$.ajax({
				type: "POST",
				url: "/Cart/deleteCart/?rand="+Math.random(),
				data: "id="+productid,
				dataType:"json",
				cache:false,
				success: function(msg){
					if(msg.success == 'ok') {
						$.jBox.tip("正在删除数据...", 'loading');
						window.location.reload();
						// 模拟2秒后完成操作
						//window.setTimeout(function () { $.jBox.tip('删除成功。', 'success'); }, 2000);	
					} else {
					  $.jBox.tip('删除失败。', 'warning');
					}
				}
			});
		}
		else if (v == 'cancel') {
		// 取消
		}
	return true; //close
	};
	$.jBox.confirm("确任要删除该商品吗？", "提示", submit);
}

//购买数量增加减少   xxxx
function modifyNum(state,id){
	var minamount = $('#moq_'+id).val();
	var inventory = $('#inventory_'+id).val();
	var num = $('#quantity_'+id).val();
	if( isNaN(parseInt($('#quantity_'+id).val())) || parseInt($('#quantity_'+id).val())<=0 ){
		$.jBox.tip('请您输入正确的订购数量','warning');
		$("#quantity_"+id).val(minamount);
		return false;
	} 
	if(state==1){
		if( parseInt(num) >= parseInt(inventory) ) {
			num = inventory;
			$.jBox.tip('对不起!您购买的数量超过了库存总量','warning');
		} else {
			num++;
		}
	} else {
		if( parseInt(num) <= parseInt(minamount) ) {
			num = minamount;
			$.jBox.tip('购买数量不能小于最小起订量','error');
		} else {
			num--;
		}
	}
	$('#quantity_'+id).val(num);
	$('#quantity_'+id).trigger("change");	
}

function ajaxOrder() {
	$('#cart_form').attr('action','/Cart/ajaxOrder');
	 
}

function add_address() {

	var html = '<div class="add_address_box">' +
		'<form id="address_form"  action="#">' +
		'<ul><li><h3><span style="color:red">*</span>选择所在地：</h3>'+
		'<span><div id="areaid" ></div></span><script>loadarea("areaid")</script></li>' +
		'<li><h3><span style="color:red">*</span>详细地址：</h3>' +
		'<input type="text" name="address" id="address" size=39 /></li>' +
		'<li><h3>邮政编码：</h3>' +
		'<input type="text" name="postcode" id="postcode" size="10" /></li></ul></div>' +
		'<div class="add_address_box"><hr />' +
		'<ul><li><h3><span style="color:red">*</span>收货人姓名：</h3>' +
		'<input type="text" name="prettyname" size="10"  id="prettyname"/></li>' +
		'<li><h3><span style="color:red">*</span>手机号码：</h3>' +
		'<input type="text" name="mobile" id="mobile"/></li>' +
		'<li><h3>电话号码：</h3>' +
		'<input type="text" name="telephone" id="telephone"/></li>' +
		'<li><h3>显示顺序：</h3>' +
		'<input type="text" name="listorder" id="listorder" size=5 /></li>' +
		'<li><h3>备注：</h3><input type="text" name="note" id="note" size=30 /></li>' +
		'<li><h3>设为默认地址：</h3>' +
		'<label><input type="checkbox" name="defaultaddr" id="defaultaddr" value="1"><span style="color:#000;">设置后系统将在购买时自动选中该收货地址</span></label></li></ul></form>' +
	'</div>';
	var data = {};
	var post_data = true;
	var states = {};
	states.state1 = {
		content: html,
		buttons: { '确认': 1, '取消': 0 },
		submit: function (v, h, f) {
			if (v == 0) {
				return true; // close the window
			} else {
				h.find('.errorBlock').hide('fast', function () { $(this).remove(); });
				
				//地区
				data.areaid = f.areaid; //或 h.find('#areaid').val();
				if (data.areaid == '' || data.areaid == 0) {
					$('<div class="errorBlock" style="display: none;">请选择所在地！</div>').prependTo(h).show('fast');
					return false;
				}
				post_data = "areaid="+parseInt(data.areaid);
				
				//详细地址
				data.address = f.address;
				if (data.address == '') {
					$('<div class="errorBlock" style="display: none;">请输入详细收货地址！</div>').prependTo(h).show('fast');
					return false;
				}
				post_data = post_data+"&address="+data.address;
				
				//邮政编码
				if(f.postcode) {
					var regexp_postcode = /^[1-9][0-9]{5}$/;
					if( !regexp_postcode.test(f.postcode) ) {
						$('<div class="errorBlock" style="display: none;">请输入正确的邮政编码！</div>').prependTo(h).show('fast');
						return false;
					}
					post_data = post_data+"&postcode="+f.postcode;
				}
				
				
				//真实姓名
				data.prettyname = f.prettyname;
				var regexp_prettyname = /^[\w\u4e00-\u9fa5]{2,18}$/;
				if (data.prettyname == '' || !regexp_prettyname.test(data.prettyname)) {
					$('<div class="errorBlock" style="display: none;">请正确输入姓名，最少不能低于2个字，最多不能超过18个字</div>').prependTo(h).show('fast');
					return false;
				} 
				post_data = post_data+"&prettyname="+data.prettyname;
				
				//手机号码
				data.mobile = f.mobile;
				var regexp_mobile = /^[\d-]{11,16}$/;
				if (data.mobile == '' || !regexp_mobile.test(data.mobile)) {
					$('<div class="errorBlock" style="display: none;">请输入正确的号码，手机号码必须全为数字，且不能超过16位</div>').prependTo(h).show('fast');
					return false;
				}
				post_data = post_data+"&mobile="+data.mobile;
				
				//电话号码
				if(f.telephone) {
					var regexp_tel = /^0\d{2,3}(\-)?\d{7,8}$/;
					if( !regexp_tel.test(f.telephone) ) {
						$('<div class="errorBlock" style="display: none;">请输入正确的电话号码！</div>').prependTo(h).show('fast');
						return false;
						
					}
					post_data = post_data+"&telephone="+f.telephone;
				}
				
				//显示顺序
				if( f.listorder ) {
					var regexp_order = /[\d]/;
					if( !regexp_order.test(f.listorder) ) {
						$('<div class="errorBlock" style="display: none;">请输入正确的显示顺序！</div>').prependTo(h).show('fast');
						return false;
					}
					post_data = post_data+"&listorder="+f.listorder;
				}
				
				//备注 
				if( f.note ) {
					post_data = post_data+"&note="+f.note;
				} 
				//是否设置为默认收获地址
				if( parseInt(f.defaultaddr) == 1 ) {
					post_data = post_data+"&defaultaddr="+f.defaultaddr;
				}
				
				$.ajax({
					type: "POST",
					url: "/Cart/ajax_add_address/?rand="+Math.random(),
					data:post_data,
					dataType: "json",
					success: function(msg){
						//alert( "Data Saved: " + msg );
						if( msg.success == 'false' ) {
							if( msg.info == 'nologin' ) {
								login_box();
							} else if(msg.info=='systemerror') {
								$.jBox.tip('系统出错,添加失败', 'error');
							}
							
						} else if( msg.success == 'empty' ) {
							$('<div class="errorBlock" style="display: none;">必填项不能为空！</div>').prependTo(h).show('fast');
							return false;
						} else {
							window.location.reload();
							return true;
						}
					}
				});	
			}
			return false;
		}
	};
	$.jBox.open(states, '添加新地址', 700, 'auto');
}

function checkform() {
	$('#orderform').attr('action','/Cart/orderok/?to_time='+Math.random());
    return true;
}



function login_box() {
	//XXXX
	var submit = function (v, h, f) {
		if (v == 'yes') {
			window.location.href="/Login"
		}
		if (v == 'reg') {
			window.location.href="/Register"
		}
		if (v == 'cancel') {
			return true; 
		}	
	};
	// 可根据需求仿上例子定义按钮
	$.jBox.warning("亲，您还没有登录哦！赶快去登录吧！", "登录提示", submit, { buttons: { '登录': 'yes', '注册': 'reg','取消':'cancel'} });
	return false;
	
}