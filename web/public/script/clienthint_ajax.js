/********
*���崴��XMLHttpRequest����ķ���
*
**************************************/
var xmlHttp;//��������
var requestType="";//������ʼ����Ϊ��
function createXMLHttpRequest()//���崴��һ��������������Ŀ�ͷ
{
	if(window.ActiveXObject)//ActiveXObject�����ҵ���ʱ�򷵻ص����棬�����Ǽ�
	{
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");//�����ʹ��IE�ķ�������XmlHttp
	}
	else if(window.XMLHttpRequest)
	{
		xmlHttp=new XMLHttpRequest();//�����ʹ�÷�IE�ķ�������XmlHttp
	}
}
/***************
*�жϷ�������Ӧ���¼������������4��˵��������ɣ��жϱ�ʾͷ��
*url����������'&n='+Math.random() ��ֹ����
strMessage�������ʾ��Ϣ
statueMessage  δ��������ʾ����Ϣ
*************************************************/

function queryCity(url,DivId,nowValue,strMessage,statueMessage){
	createXMLHttpRequest();
	xmlHttp.open("GET",url,true);
	xmlHttp.onreadystatechange=function(){
			if(xmlHttp.readyState==4){//4˵����ִ�н������0 (δ��ʼ��)1 (����װ��)2 (װ�����) 3 (������)4 (���)
				if(xmlHttp.status==200){//http��һ����ͷ˵���ɹ��ҵ�
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
