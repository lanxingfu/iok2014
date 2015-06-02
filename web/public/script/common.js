/* 公共 js 文件 */

//协议选中 
function Tong_Xieyi(val){
	var ap = $("#regxieyi").attr("checked");
	if(ap != 'checked'){
		alert(val);
		$("#regxieyi").attr("checked", "checked");
	}
}


//只保留2位小数，如：2，会在2后面补上00.即2.00 
function toDecimal2(x) { 
	var f = parseFloat(x); 
	if (isNaN(f)) { 
		return false; 
	} 
	var f = Math.round(x*100)/100; 
	var s = f.toString(); 
	var rs = s.indexOf('.'); 
	if (rs < 0) { 
		rs = s.length; 
		s += '.'; 
	} 
	while (s.length <= rs + 2) { 
		s += '0'; 
	} 
	return s; 
}