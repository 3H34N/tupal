/***
*评论*
****/
var limitTime=null;

function quotecomment(oo){
	document.getElementById("comment_content").value=oo;
	document.getElementById("comment_content").focus();
}

function limitComment(){
	limitTime=limitTime-1;
	if(limitTime>0){
		document.getElementById("comment_content").value='还剩'+limitTime+'秒,你才可以再发表评论';
		document.getElementById("comment_content").disabled=true;
		document.getElementById("comment_submit").disabled=true;
		setTimeout("limitComment()",1000);
	}else if(limitTime==0){
		document.getElementById("comment_content").value='';
		document.getElementById("comment_content").disabled=false;
		document.getElementById("comment_submit").disabled=false;
	}
	
}
//旧版的需要用到
function postcomment(url,yzimgnum){
	var yzimgstr='';
	//if(yzimgnum=='1'){
	if(document.getElementById("yzImgNum")!=null){
		yzimgstr+="&yzimg="+ document.getElementById("yzImgNum").value;
	}
	if(document.getElementById("commentface")!=null){
		yzimgstr+="&commentface="+ document.getElementById("commentface").value;
	}
	username4 = document.getElementById("comment_username").value;
	content4 = document.getElementById("comment_content").value;
	if(content4==''){
		showerr("内容不能为空");
		return false;
	}
	content4=content4.replace(/(\n)/g,"@@br@@");
	//document.getElementById("comment_content").value='';
	//document.getElementById("comment_content").disabled=true;
	limitTime=10;
	limitComment();
	
	AJAX.get("comment",url + "&username=" + username4 + "&content=" + content4 + yzimgstr ,0);
	//if(yzimgnum=='1'){
	if(document.getElementById("yzImgNum")!=null){
		//document.getElementById("yz_Img").src;
		document.getElementById("yzImgNum").value='';
	}
}
function showerr(msg){
	alert(msg);
}
function getcomment(url){
	AJAX.get("comment",url,1);
}

function ShowMenu_mmc(){
}
function HideMenu_mmc(){
}

function get_position(o){//取得坐标
	var to=new Object();
	to.left=to.right=to.top=to.bottom=0;
	var twidth=o.offsetWidth;
	var theight=o.offsetHeight;
	while(o!=document.body){
		to.left+=o.offsetLeft;
		to.top+=o.offsetTop;
		o=o.offsetParent;
	}
	to.right=to.left+twidth;
	to.bottom=to.top+theight;
	return to;
}

/***
*在线操作*
****/
document.write('<div style="display:none;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="AjaxEditTable"><tr><td class="head"><h3 class="L"></h3><h3 class="R"></h3><span class="eidtmodule" onclick="this.offsetParent.offsetParent.offsetParent.style.display=\'none\'" onMouseOver="this.style.cursor=\'hand\'">关闭</span></td></tr><tr> <td class="middle"></td></tr></table></div>');
var clickEdit={
	tableid:null,
	init:function(){
		oo=document.body.getElementsByTagName("A");
		for(var i=0;i<oo.length;i++){
			if(oo[i].getAttribute("editurl")!=null){
				if(oo[i].getAttribute("href")!=null)oo[i].href='javascript:';
				oo[i].title='点击可以修改这里的设置';
				if (document.all) { //For IE
					oo[i].attachEvent("onmousedown",clickEdit.showdiv);
					oo[i].attachEvent("onmouseover",clickEdit.showstyle);
					oo[i].attachEvent("onmouseout",clickEdit.hidestyle);
				}else{ //For Mozilla
					oo[i].addEventListener("onmousedown".substr(2,"onmousedown".length-2),clickEdit.showdiv,true);
				}
			}
		}
	},
	showstyle:function(evt){
		var evt = (evt) ? evt : ((window.event) ? window.event : "");
		if (evt) {
			 ao = (evt.target) ? evt.target : evt.srcElement;
		}
		ao.style.border='1px dotted red';
		ao.style.cursor='hand';
	},
	hidestyle:function(evt){
		var evt = (evt) ? evt : ((window.event) ? window.event : "");
		if (evt) {
			 ao = (evt.target) ? evt.target : evt.srcElement;
		}
		ao.style.border='0px dotted red';
	},
	showdiv:function(evt){
		var w=150;
		var h=100;
		var evt = (evt) ? evt : ((window.event) ? window.event : "");
		if (evt) {
			 ao = (evt.target) ? evt.target : evt.srcElement;
		}
		//oid=ao.offsetParent.offsetParent.id;
		//获取坐标的函数头部有定义
		position=get_position(ao);
		
		//alert(oid);
		url=ao.getAttribute("editurl");
		oid=url.replace(/(\.|=|\?|&|\\|\/|:)/g,"_");
		ao.id=oid;
		DivId="clickEdit_"+oid;
		url=url + "&TagId=" + oid;
		obj=document.getElementById(DivId);
		if(obj==null){
			obj=document.createElement("div");

			obj.innerHTML=document.getElementById('AjaxEditTable').outerHTML;
			objs=obj.getElementsByTagName("TD");
			objs[1].id=DivId;
			//obj.id=DivId;
			//obj.className="Editdiv";
			obj.style.Zindex='999';
			//obj.style.display='';
			obj.style.position='absolute';
			obj.style.top=position.bottom;
			obj.style.left=position.left;
			obj.style.height=h;
			obj.style.width=w;
			document.body.appendChild(obj);
			//obj.innerHTML='以下是显示内容...';
			AJAX.get(DivId,url,1);
		}else{
			fobj=obj.offsetParent.offsetParent;
			if(fobj.style.display=='none'){
				fobj.style.display='';
			}else{
				fobj.style.display='none';
			}
		}
	},
	save:function(oid,job,va){
		divid="clickEdit_"+oid;
		//alert(oid)
		//GET方式提交内容,如果有空格的话.会有BUG
		//即时显示,不过没判断是否保存成功也显示了
		document.getElementById(oid).innerHTML=va;
		va=va.replace(/(\n)/g,"@BR@");
		AJAX.get(divid,"ajax.php?inc="+job+"&step=2&TagId="+oid+"&va="+va,0);
	},
	cancel:function(divid){
		document.getElementById(divid).offsetParent.offsetParent.style.display='none';
	}
}

//显示子栏目
function showSonName(fid)
{
	oo=document.body.getElementsByTagName('DIV');
	for(var i=0;i<oo.length;i++){
		if(oo[i].className=='SonName'+fid){
			if(oo[i].style.display=='none'){
				oo[i].style.display='';
			}
			else
			{
				oo[i].style.display='none';
			}
		}
	}
}

function avoidgather(myname){
	fs=document.body.getElementsByTagName('P');
	for(var i=0;i<fs.length;i++){
		if(myname!=''&&fs[i].className.indexOf(myname)!=-1){
			fs[i].style.display='none';
		}
		
	}
	fs=document.body.getElementsByTagName('DIV');
	for(var i=0;i<fs.length;i++){
		if(myname!=''&&fs[i].className.indexOf(myname)!=-1){
			fs[i].style.display='none';
		}
	}
}