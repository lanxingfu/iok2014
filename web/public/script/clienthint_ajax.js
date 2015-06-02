/********
*定义创建XMLHttpRequest对象的方法
*
**************************************/
var xmlHttp;//声明变量
var requestType="";//声明初始类型为空
function createXMLHttpRequest()//定义创建一个跨浏览器函数的开头
{
	if(window.ActiveXObject)//ActiveXObject对象到找到的时候返回的是真，否则是假
	{
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");//这个是使用IE的方法创建XmlHttp
	}
	else if(window.XMLHttpRequest)
	{
		xmlHttp=new XMLHttpRequest();//这个是使用非IE的方法创建XmlHttp
	}
}
/***************
*判断服务器响应的事件，如果返回是4则说明交互完成，判断标示头，
*url最后参数可以'&n='+Math.random() 防止缓存
strMessage出错的显示消息
statueMessage  未加载完显示的信息
*************************************************/

function queryCity(url,DivId,nowValue,strMessage,statueMessage){
	createXMLHttpRequest();
	xmlHttp.open("GET",url,true);
	xmlHttp.onreadystatechange=function(){
			if(xmlHttp.readyState==4){//4说明是执行交互完毕0 (未初始化)1 (正在装载)2 (装载完毕) 3 (交互中)4 (完成)
				if(xmlHttp.status==200){//http的一个报头说明成功找到
                  document.getElementById(DivId).innerHTML=xmlHttp.responseText;
				  document.getElementById('catid').value=nowValue;
				}else{
					alert(strMessage);
				}
			}
			else
			{
				document.getElementById(DivId).innerHTML=statueMessage;	
			}
		}
  xmlHttp.send(null)
}
