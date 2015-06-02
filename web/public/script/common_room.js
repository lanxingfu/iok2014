/* 账户中心公共 js 文件 */
//搜索分类下拉
jQuery(document).ready(function (){
	$('#search-val').hover(
	function(){
	$('#search-list').attr("style", "display: ");
	},function(){
	$('#search-list').attr("style", "display:none");
	});
});
/* 主营行业 添加，删除 wuzhijie 2014年1月10日 15:00:41 */
function Dd(i) {return document.getElementById(i);}
function addop(id, v, t) {var op = document.createElement("option"); op.value = v; op.text = t; Dd(id).options.add(op);}
function delop(id) {
	var s = -1;
	for(var i = 0; i < Dd(id).options.length; i++) {if(Dd(id).options[i].selected) {s = i; break;}}
	if(s == -1) {alert(L['choose_category']); Dd(id).focus();} else {Dd(id).remove(s);}
}
function addcate(m) {
	var v = Dd('catid_1').value; var l = Dd('cates').options.length;
	if(l >= m) {alert('最多可添加6个分类'); return;}
	for(var i = 0; i < l; i++) {if(Dd('cates').options[i].value == v) {alert('已添加过此分类'); return;}}
	var e = Dd('cate').getElementsByTagName('select'); var c = s = '';
	for(var i = 0; i < e.length; i++) {if(e[i].value) {s = e[i].options[e[i].selectedIndex].innerHTML; c += s + '/'; s = '';}}
	if(c) {c = c.replace('&amp;', '&'); c = c.substring(0, c.length-1); addop('cates', v, c); Dd('catid').value = Dd('catid').value ? Dd('catid').value+v+',' : ','+v+',';} else {alert('请选择分类');}
}
function delcate(cates) {
	var s = -1;
	for(var i = 0; i < Dd(cates).options.length; i++) {if(Dd(cates).options[i].selected) {s = i; break;}}
	if(s == -1) {alert('请选择分类'); Dd(cates).focus();} else {Dd('catid').value = Dd('catid').value.replace(','+Dd(cates).options[s].value+',', ','); Dd(cates).remove(s);}
}


/* 经模式限制 企业商务室完善资料 wuzhijie 2013年12月30日 15:32:50 */
function check_mode(c, m) {
	var mode_num = 0; var e = document.getElementById('com_mode').getElementsByTagName('input');	
	for(var i=0; i<e.length; i++){if(e[i].checked){mode_num++;}}
	if(mode_num > m) {confirm('最多可选2种经营模式'); c.checked = false;}
}
/* 全选 itemids:选项 、 decidename：按钮name wuzhijie 2013年12月30日 15:32:57 */
function checkalls(itemids,decidename){
	var obj = document.getElementsByName(itemids);
	var objd = document.getElementsByName(decidename);
	var num = obj.length;
	
	
		if(objd[0].checked==false){
			for (var i=0; i<num; i++){
				obj[i].checked=false;
			}
		}else{
			for (var i=0; i<num; i++){
				obj[i].checked=true;
			}
		}
}
/*
	地区选择 wuzhijie 2013年12月30日 15:33:43 
*/
function load_areas(areaid, id) {
	var area_id;
	area_id = id; area_areaid[id] = areaid;
	$.ajax({
		type: "POST",
		url: "/Ajax/AJAXrequirearea",
		data: {area_title: area_title[id], area_extend: area_extend[id],area_id:area_id,areaid:areaid},
		success: function(datas){
			into_areas(datas,area_id);
		}
	});
	
}
function into_areas(datas,area_id) {
		$('#areaid_'+area_id).val(area_areaid[area_id]);
		$('#load_area_'+area_id).html(datas);
}

/* 分类 */
function load_categorys(catid, id) {
	var cat_id;
	cat_id = id; category_catid[id] = catid;
	$.ajax({
		type: "POST",
		url: "/Ajax/AJAXrequirecate",
		data: {category_title: category_title[id], category_categorytypeid: category_categorytypeid[id],category_extend:category_extend[id],category_deep:category_deep[id],cat_id:cat_id,catid:catid},
		success: function(datac){
			into_categorys(datac,cat_id);
		}
	});
	
}


function into_categorys(datac,cat_id) {
		$('#catid_'+cat_id).val(category_catid[cat_id]);
		$('#load_category_'+cat_id).html(datac);
}


/* 
	批量操作 多选列表 执行ajax操作 wuzhijie 2014年1月8日 17:59:45 
	urls：操作路径
	doom：被选节点name array
	tourl：操作结束后跳转的页面
	msg : 返回值 array(state,returndata) ; 1-success,2-false,else-error 
*/
function Bulkaction(urls,doom,tourl){
	var dellist = document.getElementsByName(doom);
	var delid = "";
	for (var i = 0; i < dellist.length; i++){
		if (dellist[i].checked){
			delid += dellist[i].value + ",";
		}
	}
	if(delid==''){
		alert('请先选中操作对象！');
		return false;
	}
	if(confirm('确定要执行该操作吗？')){alert(1);
		$.ajax({
			type: "POST",
			url: urls,
			data: "id="+delid,
			success: function(msg){
				if(msg.state==1){
				//1表示删除成功，
					if(tourl){
						window.location =tourl;
					}else{
						window.location.reload(true);
					}
				}else if(msg.state==2){
					//如果操作不成功，则输出返回值
					alert(msg.returndata);
					if(tourl){
						setTimeout("window.location = '"+tourl+"'",2500);
					}
				}else{
					alert('操作失败了！请重试！');
				}
			}
		});
	}
}