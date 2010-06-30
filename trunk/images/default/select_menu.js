function showfid_S(showThisId,obj,fuid,inputid,type)
{
	//主要是处理赋值后要切换.就要隐藏一些存在的选项
	oo=document.body.getElementsByTagName("span");
	ppck=0;
	for(var i=0;i<oo.length;i++){
		if(oo[i].id==showThisId){
			ppck=1;
		}
		if(ppck==1&&oo[i].getAttribute("divname")==fuid){
			oo[i].style.display='none';
		}
	}
	
	/*
	if (document.getElementById(showThisId)!=null)
	{
		if(document.getElementById(showThisId).innerHTML!='')
		{
			document.getElementById(showThisId).style.display='';
		}
	}
	*/

	for(i=1;i<obj.options.length;i++){
		obj.options[i].style.color='';
		if(i==obj.selectedIndex){
			obj.options[i].style.color='red';
		}
	}
	fid=parseInt(obj.options[obj.selectedIndex].value);
	if(fid>0)
	{
		document.getElementById(inputid).value=fid;
	}
	else
	{
		document.getElementById(inputid).value='';
	}
	if(fid<0){
		fid=-fid;
		get_div_S(showThisId,fuid,inputid,type,fid,'');
	}
	if (document.getElementById(showThisId)!=null)
	{
		if(document.getElementById(showThisId).innerHTML!='')
		{
			document.getElementById(showThisId).style.display='';
		}
	}
}

function get_div_S(showThisId,fuid,inputid,type,fid,ckfid){
	AJAX.get(showThisId,file+"?fuid="+fuid+"&inputid="+inputid+"&type="+type+"&fid="+fid+"&ckfid="+ckfid);
}
