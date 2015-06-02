 
$(function($) {	
	//保存发票
	$("#fapiao_but,#fapiao_but2").bind("click", function(){
		var leixing = parseInt($("input[name='leixing']:checked").val());
		var taitou = parseInt($("input[name='taitou']:checked").val());
		var danwei_mingcheng1 = $("#danwei_mingcheng1").val();
		if( (leixing == 1)  && (taitou==2) && (danwei_mingcheng1=='') ) {
			$.jBox.tip("单位名称不能为空", 'warning');
			$("#danwei_mingcheng1").focus().css('border','1px solid red');
			return false;
		}
		
		if( leixing==2 ) {
	
			if($("#danwei_mingcheng").val()==""){
				$.jBox.tip("单位名称不能为空", 'warning');
				$("#danwei_mingcheng").focus().css('border','1px solid red');
				return false;
			}
			if($("#nashuiren").val()==""){
				$.jBox.tip("纳税人识别号不能为空！", 'warning');
				$("#nashuiren").focus().css('border','1px solid red');
				return false;
			}
			if($("#zhuce_dizhi").val()==""){
				$.jBox.tip("注册地址不能为空！", 'warning');
				$("#zhuce_dizhi").focus().css('border','1px solid red');
				return false;
			}
			if($("#zhuce_dianhua").val()==""){
				$.jBox.tip("注册电话不能为空！", 'warning');
				$("#zhuce_dianhua").focus().css('border','1px solid red');
				return false;
			}
			
			if( $("#kaihu_yinhang").val() == "" ){
				$.jBox.tip("开户银行不能为空！", 'warning');
				$("#kaihu_yinhang").focus().css('border','1px solid red');
				return false;
			}
			
			if( $("#yinhang_zhanghu").val()==''){
				$.jBox.tip("银行账户不能为空！", 'warning');
				$("#yinhang_zhanghu").focus().css('border','1px solid red');
				return false;
			}			
			
			if($("input[name='om']:checked").val()==""){
				$.jBox.tip("发票类型请选择！", 'warning');
				return false;
			}
		
		}
	
		var neirong = $("input[name='neirong']:checked").val();
		var danwei_mingcheng = $("#danwei_mingcheng").val();
		var nashuiren = $("#nashuiren").val();
		var zhuce_dizhi = $("#zhuce_dizhi").val();
		var zhuce_dianhua = $("#zhuce_dianhua").val();
		var kaihu_yinhang = $("#kaihu_yinhang").val();
		var yinhang_zhanghu = $("#yinhang_zhanghu").val();
		var om = $("input[name='om']:checked").val();
		var fid = $("#fid").val();
		var posrData = true;
		postData = 'leixing='+leixing;
		postData = postData+'&taitou='+taitou;
		if( leixing == 1 ) {
			if( (danwei_mingcheng1!='') && (taitou==2) ) {
				postData = postData+'&danwei_mingcheng1='+danwei_mingcheng1;
			}
			postData = postData+'&neirong='+neirong;
			
		} else if( leixing == 2 ) {
			postData = postData+'&danwei_mingcheng='+danwei_mingcheng+'&nashuiren='+nashuiren+'&zhuce_dizhi='+zhuce_dizhi+'&zhuce_dianhua='+zhuce_dianhua+'&kaihu_yinhang='+kaihu_yinhang+'&yinhang_zhanghu='+yinhang_zhanghu+'&om='+om;
		
		}
		
		if( fid!='' ) {
			postData = postData+'&fid='+fid;
		}
		$.ajax({
			type: "POST",
			url: "/Cart/excute_invoice/?rand="+Math.random(),
			data: postData,
			dataType:"json",
			success: function(msg){
				
				if( msg.success == 'ok' ){
					//记录编号值
					$('#fid').val(msg.id);
					//加入值
					$('#fpnr').hide();
					$('#fpnr_0').hide();
					$('#fpnr_1').hide();
					$('#fpnr_2').show();
					$('#fpnr_3').hide();
					$("#fp_p").html('<a href="javascript:void(0);" onclick="fapiao_edit()">[修改]</a> <a href="javascript:void(0);" onclick="fapiao_del()">[删除]</a>');
					$('#fpnr_2_1').html("<label>"+$("input[name='leixing']:checked").attr("rel")+"</label>");
					
					if( $("input[name='leixing']:checked").val()==2 ) {
						$('#fpnr_2_2').html("<label>单位</label>");
						$('#fpnr_2_3').html("<label>"+$("#danwei_mingcheng").val()+"</label>");
					} else {
						$('#fpnr_2_2').html("<label>"+$("input[name='taitou']:checked").attr("rel")+"</label>");
						$('#fpnr_2_3').html("<label>"+$("input[name='neirong']:checked").attr("rel")+"</label>");
					}
				} else {   
					$.jBox.tip("保存异常！", 'error');
					return false;
				}
				
			}
		});
	
	});
}); 

function Isfa_piao(){
	$("#_ptfp_").attr("checked","checked");
	$("#_geren_").attr("checked","checked");
	$("#_mingxi_").attr("checked","checked");
	$("#fp_p").html('<a onclick="closefa_piao()" id="_isfao_piao_" href="javascript:void(0);">[ + 关闭]</a>');
	$('#fpnr').show();
	$('#fpnr_0').show();
	$('#fpnr_1').hide();
}

function closefa_piao(){
	$("#fp_p").html('<a onclick="Isfa_piao()" id="_isfao_piao_" href="javascript:void(0);">[ + 发票信息]</a>');
	$('#fpnr').hide();
	$('#fpnr_0').hide();
	$('#fpnr_1').hide();
}
function Ptongfp(){
	$('#fpnr_0').show();
	$('#fpnr_1').hide();
}
function Zzsfp(){
	$('#fpnr_1').show();
	$('#fpnr_0').hide();
}
function Ge_ren(){
	$('#company_name').hide();
	$('#company_detail').hide();
}
function Dan_wei(){
	$('#company_name').show();
	$('#company_detail').show();
}
function fapiao_edit(){
	$('#fpnr').show();
	if($("input[name='leixing']:checked").val()==2) {
		$('#fpnr_0').hide();
		$('#fpnr_1').show();
	} else {
		$('#fpnr_0').show();
		$('#fpnr_1').hide();
	}
	$('#fpnr_2').hide();
	$('#fpnr_3').hide();
	$("#fp_p").html('');
}
	
//Ajax删除
function fapiao_del(){
	$.ajax({
		type: "POST",
		url: "/Cart/del_invoice/?rand="+Math.random(),
		data: "fid="+$("#fid").val(),
		dataType:"json",
		success: function(msg){
		if( msg.success == 'ok' ){
			$.jBox.tip('删除成功', 'success');
			var leixing = $("input[name='leixing']:first").attr("checked","checked");
			var taitou = $("input[name='taitou']:first").attr("checked","checked");
			var danwei_mingcheng1 = $("#danwei_mingcheng1").val("");
			var neirong = $("input[name='neirong']:first").attr("checked","checked");
			var danwei_mingcheng = $("#danwei_mingcheng").val("");
			var nashuiren = $("#nashuiren").val("");
			var zhuce_dizhi = $("#zhuce_dizhi").val("");
			var zhuce_dianhua = $("#zhuce_dianhua").val("");
			var kaihu_yinhang = $("#kaihu_yinhang").val("");
			var yinhang_zhanghu = $("#yinhang_zhanghu").val("");
			var yinhang_zhanghu = $("#fid").val("");
			var om = $("input[name='om']:first").attr("checked","checked");
			
			$('#fpnr').show();
			$('#fpnr_0').show();
			$('#fpnr_1').hide();
			$('#fpnr_2').hide();
			$('#fpnr_3').hide();
			$("#fp_p").html('');
		} else {
			$.jBox.tip('删除失败', 'error');
			return false;
		}
		}
	});
}