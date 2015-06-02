// go to new url
function gotoUrl(url)
{
	url?window.location.href=url:window.location.reload(true);
}
// reload captcha
function reloadcaptcha(o)
{
	var d = new Date();
	o.src=o.src.replace(/\d{10,}/,d.getTime());
}
function recordchange()
{
	var record = $('#record').val();
	var qs = window.location.search;
	if(record != undefined && !isNaN(record) && record > 0)
	{
		if(record > 50) record = 50;
		if(qs.indexOf('record') > 0)
		{
			qs = qs.replace(/[\&]record=[^\&]*/,'&record='+record);
		}else
		{
			qs += '&record='+record;	
		}
		if(qs.indexOf('page') > 0)
		{
			qs = qs.replace(/[\&]page=[^\&]*/,'&page=1');
		}else
		{
			qs += '&page=1';	
		}
		gotoUrl('/iokadmin.php'+qs);
	}
}
function searchkey()
{
	var item = $('#searchitem').val();
	var keyword = $('#keyword').val();
	var qs = window.location.search;
	if(keyword != undefined && keyword != '')
	{
		if(item != undefined && item != '')
		{
			if(qs.indexOf('item') > 0)
			{
				qs = qs.replace(/[\&]item=[^\&]*/,'&item='+item);
			}else
			{
				qs += '&item='+item;	
			}
		}
		if(qs.indexOf('keyword') > 0)
		{
			qs = qs.replace(/[\&]keyword=[^\&]*/,'&keyword='+encodeURIComponent(keyword));
		}else
		{
			qs += '&keyword='+encodeURIComponent(keyword);
		}
		if(qs.indexOf('page') > 0)
		{
			qs = qs.replace(/[\&]page=[^\&]*/,'&page=1');
		}else
		{
			qs += '&page=1';	
		}
		gotoUrl('/iokadmin.php'+qs);
	}
}
function checkall(o)
{
	var chkbox=document.getElementsByName('chkbox[]');
	for(var i=0;i<chkbox.length;i++)
	{
		if(chkbox[i].disabled==false)
		{
			chkbox[i].checked=o.checked;
		}
	}
}
function ischeckone()
{
	var chkcnt=0;
	var chkvalue='';
	var chkbox=document.getElementsByName('chkbox[]');
	for(var i=0;i<chkbox.length;i++)
	{
		if(chkbox[i].checked)
		{
			if(chkcnt==0)
			{
				chkvalue=chkbox[i].value;
				chkcnt++;
			}else
			{
				chkcnt++;
				break;
			}
		}
	}
	if(chkcnt==0)
	{
		alert("请选择一条记录");		
		return false;
	}
	if(chkcnt>1)
	{
		alert("对不起，一次只能操作一条记录！");
		return false;
	}
	return chkvalue;
}
function ischeckmore()
{
	var chkcnt=0;
	var chkvalue='';
	var chkbox=document.getElementsByName('chkbox[]');
	for(var i=0;i<chkbox.length;i++)
	{
		if(chkbox[i].checked)
		{
			chkvalue+=chkbox[i].value+',';
			chkcnt++;
		}
	}
	if(chkcnt==0)
	{
		alert("至少选择一条记录进行此项操作");
		return false;
	}
	return chkvalue.substring(0,chkvalue.length-1);
}
function addTr(id)
{
	var o = document.getElementById(id);
	$(o).clone().appendTo($(o.parentNode));
	$(o).removeAttr('id');
	$(o).css('display','');
}
function rmTr(o)
{
	if(o.parentNode.parentNode.style.backgroundColor=='')
	{
		if(confirm('确定要移除此行么？'))
		{
			o.parentNode.parentNode.parentNode.removeChild(o.parentNode.parentNode);
		}
	}
}

function loadarea(aid, pid, sid)
{	
	if(pid != undefined) 
	{
		if(sid == 1)
		{	
			$('#'+aid+'-0').val(pid);
		}else if(sid == 2)
		{
			if(pid != 0)
			{
				$('#'+aid+'-0').val(pid);
			}else
			{
				$('#'+aid+'-0').val($('#'+aid+'-1').val());
			}
		}else if(sid == 3)
		{
			if(pid != 0)
			{
				$('#'+aid+'-0').val(pid);
			}else
			{
				$('#'+aid+'-0').val($('#'+aid+'-2').val());
			}
		}	
	}
	if(sid != 3)
	{
		$.get('/index.php?m=Misc&a=area&aid='+aid+'&pid='+pid+'&sid='+sid,function(d)
		{
			if(sid == 1)
			{
				$('#'+aid+'-3').remove();
				$('#'+aid+'-2').remove();
			}else if(sid == 2)
			{
				$('#'+aid+'-3').remove();	
			}
			if(d)
			{
				$('#'+aid).append(d);
			}
											
		});
	}
}