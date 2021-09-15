//<!--
	   function handleHttpResponse() { 
	   
       document.getElementById("divActivities").innerHTML = "<table style='width:80%;margin-left:22px;'><tr><td height='180'><div align='center'><img style='vertical-align: middle;' src='img/loader.gif'> Loading...</div></td></tr></table>";	

		if (http.readyState == 4) {
			  if(http.status==200) {
			  	var results=http.responseText;
			  document.getElementById('divActivities').innerHTML = results;
			  }
  			}
		}
		
	    function requestActivities() {
        //document.getElementById("divActivities").innerHTML = "Loading...";		
		    var url = document.getElementById("buildUrl2").value;
            var t1 = document.getElementById("text1").value;
			var t2 = document.getElementById("text2").value;
			var t3 = document.getElementById("text3").value;
			var t4 = document.getElementById("text4").value;
			http.open("GET", "" + url + escape(t1)+"&keyword2="+escape(t2)+"&keyword3="+escape(t3)+"&keyword4="+escape(t4) , true);
			http.onreadystatechange = handleHttpResponse;
			http.send(null);
        }
function getHTTPObject() {
  var xmlhttp;
 
  if(window.XMLHttpRequest){
    xmlhttp = new XMLHttpRequest();
  }
  else if (window.ActiveXObject){
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    if (!xmlhttp){
        xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
    
}
  return xmlhttp;

  
}
var http = getHTTPObject(); // We create the HTTP Object


  function handleHttpResponse() {  
       document.getElementById("divActivities2").innerHTML = "<br /><div align='center'><table style='width:80%;margin-left:22px;'><tr><td height='180'><br /><div align='center'><img style='vertical-align: middle;' src='img/loader.gif'> Loading...</div><br /></td></tr></table></div>";	

		if (http.readyState == 4) {
			  if(http.status==200) {
			  	var results=http.responseText;
			  document.getElementById('divActivities2').innerHTML = results;
			  }
  			}
		}
		
	    function requestActivities2(url2) {
        document.getElementById("divActivities2").innerHTML = "Loading...";		
			http.open("GET", "" + url2 , true);
			http.onreadystatechange = handleHttpResponse;
			http.send(null);
        }
function getHTTPObject() {
  var xmlhttp;
 
  if(window.XMLHttpRequest){
    xmlhttp = new XMLHttpRequest();
  }
  else if (window.ActiveXObject){
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    if (!xmlhttp){
        xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
    }
    
}
  return xmlhttp;

  
}
var http = getHTTPObject(); // We create the HTTP Object

/////////////////////////////////////////////////////////////////////////////////////
  function showSpoiler(obj)    
	{    
    var inner = obj.parentNode.getElementsByTagName('div')[0];    
        if (inner.style.display == 'none')        
        inner.style.display = '';    
    else        
    inner.style.display = 'none';    
	}
	
	
	function toggle() {
	var ele = document.getElementById("toggleText");
	var text = document.getElementById("displayText");
	if(ele.style.display == "block") {
    		ele.style.display = "none";
		text.innerHTML = "show";
  	}
	else {
		ele.style.display = "block";
		text.innerHTML = "hide";
	}
} 
////////////////////////////////////////////////


document.onclick=check;

var Ary=[];

function check(e) {
 var target = (e && e.target) || (event && event.srcElement);
 while (target.parentNode){
  if (target.className.match('pop')||target.className.match('poplink')) return;
  target=target.parentNode;
 }
 var ary=zxcByClassName('pop')
 for (var z0=0;z0<ary.length;z0++){
  ary[z0].style.display='none';
 }
}
function zxcByClassName(nme,el,tag){
 if (typeof(el)=='string') el=document.getElementById(el);
 el=el||document;
 for (var tag=tag||'*',reg=new RegExp('\\b'+nme+'\\b'),els=el.getElementsByTagName(tag),ary=[],z0=0; z0<els.length;z0++){
  if(reg.test(els[z0].className)) ary.push(els[z0]);
 }
 return ary;
}

function toggle2(layer_ref) {
 var hza = document.getElementById(layer_ref);
 if (hza && hza.style){
  if (!hza.set){ hza.set=true;  Ary.push(hza); }
  hza.style.display = (hza.style.display == '')? 'show':'';
 }
}



    function showhide(id){
if (document.getElementById){
obj = document.getElementById(id);
if (obj.style.display == "none"){
obj.style.display = "";
} else {
obj.style.display = "none";
}
}
} 
//--> 


///////////////////// TOOLTiP //////////////////

var offsetfromcursorX=12 //Customize x offset of tooltip
var offsetfromcursorY=10 //Customize y offset of tooltip

var offsetdivfrompointerX=10 //Customize x offset of tooltip DIV relative to pointer image
var offsetdivfrompointerY=14 //Customize y offset of tooltip DIV relative to pointer image. Tip: Set it to (height_of_pointer_image-1).

document.write('<div id="dhtmltooltip"></div>') //write out tooltip DIV
document.write('<img id="dhtmlpointer" src="img/arrow.gif">') //write out pointer image

var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

var pointerobj=document.all? document.all["dhtmlpointer"] : document.getElementById? document.getElementById("dhtmlpointer") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function tooltip(thetext, thewidth, thecolor){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var nondefaultpos=false
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var winwidth=ie&&!window.opera? ietruebody().clientWidth : window.innerWidth-20
var winheight=ie&&!window.opera? ietruebody().clientHeight : window.innerHeight-20

var rightedge=ie&&!window.opera? winwidth-event.clientX-offsetfromcursorX : winwidth-e.clientX-offsetfromcursorX
var bottomedge=ie&&!window.opera? winheight-event.clientY-offsetfromcursorY : winheight-e.clientY-offsetfromcursorY

var leftedge=(offsetfromcursorX<0)? offsetfromcursorX*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth){
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=curX-tipobj.offsetWidth+"px"
nondefaultpos=true
}
else if (curX<leftedge)
tipobj.style.left="5px"
else{
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetfromcursorX-offsetdivfrompointerX+"px"
pointerobj.style.left=curX+offsetfromcursorX+"px"
}

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight){
tipobj.style.top=curY-tipobj.offsetHeight-offsetfromcursorY+"px"
nondefaultpos=true
}
else{
tipobj.style.top=curY+offsetfromcursorY+offsetdivfrompointerY+"px"
pointerobj.style.top=curY+offsetfromcursorY+"px"
}
tipobj.style.visibility="visible"
if (!nondefaultpos)
pointerobj.style.visibility="visible"
else
pointerobj.style.visibility="hidden"
}
}

function hidetooltip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
pointerobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

document.onmousemove=positiontip