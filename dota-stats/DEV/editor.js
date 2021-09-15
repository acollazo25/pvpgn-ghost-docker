function formatText(el, tagstart, tagend) {
    if (el.setSelectionRange) {
        var start = el.value.substring(0, el.selectionStart);
        var selected = el.value.substring(el.selectionStart, el.selectionEnd);
        var end = el.value.substring(el.selectionEnd, el.value.length);

        var scroll = el.scrollTop;
        var caret = el.selectionStart;

        el.value = start + tagstart + selected + tagend + end;
        el.focus();
        el.scrollTop = scroll;

        if (selected.length == 0) {
            el.setSelectionRange(caret + tagstart.length, caret + tagstart.length);
        } else {
            el.setSelectionRange(caret, caret + tagstart.length + selected.length + tagend.length);
        }
    } else if (el.createTextRange) {
        // IE code here...
		var start = el.value.substring(0, el.selectionStart);
		var selected = el.value.substring(el.selectionStart, el.selectionEnd);
		var end = el.value.substring(el.selectionEnd, el.value.length);
		var caret = el.selectionStart;
		
		sel = document.selection.createRange();
		el.value = start + tagstart + selected + tagend + end;
		el.focus();
		
		if (selected.length == 0) {
            el.createTextRange(caret + tagstart.length, caret + tagstart.length);
        } else {
            el.createTextRange(caret, caret + tagstart.length + selected.length + tagend.length);
        }
		
    }
}
  
  
//LINK
function doLink() {
        var mylink = prompt("Enter a URL:", "http://");
		var mytext = prompt("Enter text link (optional):", mylink);
		if ((mylink == null) && (mylink == "")) {mytext=mylink};
        if ((mylink != null) && (mylink != "")) {
        formatText(document.getElementById('reply'),'[url='+mylink+']'+mytext,'[/url]');}
} 
  
//YOUTUBE
function InsertYoutube() {
	    var mylink = prompt("Enter a URL:", "http://");
        if ((mylink != null) && (mylink != "")) {
        formatText(document.getElementById('reply'),'[youtube]'+mylink,'[/youtube]');}
}
//IMAGE
function doImage() {
	    myimg = prompt('Enter Image URL:', 'http://');
        if ((myimg != null) && (myimg != "")) {
        formatText(document.getElementById('reply'),'[img]'+myimg,'[/img]');}
}
//QUOTE SELECTED
function getQuote()
{
    var txt = '';
if (window.getSelection)
{txt = window.getSelection();}

else if (document.getSelection)
{txt = document.getSelection();}

    else if (document.selection)
    {txt += document.selection.createRange().text;}	
	
      else return;
	   if(txt==''){
	   alert('Please highlight some text from the message first, then click quote'
	   ); 
return;
}
document.myForm.reply.value +=  '[quote]'+txt+'[/quote]\n ';
window.location.hash="reply";
}

function AddTag(tag) {
formatText(document.getElementById('reply'),'['+tag+']','[/'+tag+']');
}

function doSize(tag,tag2) {
formatText(document.getElementById('reply'),'['+tag+']','[/'+tag2+']');
}

function doSmile(tag) {
formatText(document.getElementById('reply'),''+tag+'','');
}

//DIV MORE SMILIES
var doMoreSmiley;
doMoreSmiley=false;
function More(){
if(!doMoreSmiley) { disp='block'; doMoreSmiley=true; } else { disp='none'; doMoreSmiley=false; }
document.getElementById('moresmilies').style.display=disp;
}

///////////SMILIES/////////

/*
var myUrl = location.href.match(/^.*\//);
//Set your folder here
var url= location.href.match(/^.*\//);

//SMILIES
function doSmiley() {
        myimg = url +'img/smilies/grin.gif';
		formatText(document.getElementById('reply'),':)','');
    }

function doWink() {
        myimg = url +'img/smilies/wink.gif';
	formatText(document.getElementById('reply'),';-)','');
    }

function doTongue() {
        myimg = url +'img/smilies/tongue.gif';
		formatText(document.getElementById('reply'),':-p','');
    }

function doSad() {
        myimg = url +'img/smilies/sad.gif';
	formatText(document.getElementById('reply'),':-(','');
    }

function doLol() {
    myimg = url +'img/smilies/laugh.gif';
	formatText(document.getElementById('reply'),':-D','');
    }
function doUp() {
        myimg = url +'img/smilies/up.gif';
	formatText(document.getElementById('reply'),':up:','');
    }
function doGiggle() {
        myimg = url +'img/smilies/giggle.gif';
	formatText(document.getElementById('reply'),':giggle:','');
    }
function doDown() {
        myimg = url +'img/smilies/down.gif';
	formatText(document.getElementById('reply'),':down:','');
    }
function doUp2() {
        myimg = url +'img/smilies/up2.gif';
	    formatText(document.getElementById('reply'),':up2:','');
    }
function doHi() {
        myimg = url +'img/smilies/hi.gif';
		 formatText(document.getElementById('reply'),':hi:','');
    }
function doNerdy() {
        myimg = url +'img/smilies/nerdy.gif';
		formatText(document.getElementById('reply'),'8-)','');
    }
function doCool() {
        myimg = url +'img/smilies/cool.gif';
	formatText(document.getElementById('reply'),':cool:','');
    }
function doConfused() {
        myimg = url +'img/smilies/confused.gif';
	formatText(document.getElementById('reply'),':confused:','');
    }
function doNono() {
        myimg = url +'img/smilies/nono.gif';
	formatText(document.getElementById('reply'),':nono:','');
    }
function doShy() {
        myimg = url +'img/smilies/shy.gif';
	formatText(document.getElementById('reply'),':shy:','');
    }
function doWinking() {
        myimg = url +'img/smilies/winking.gif';
	formatText(document.getElementById('reply'),';-D','');
    }
function doSealed() {
        myimg = url +'img/smilies/sealed.gif';
	formatText(document.getElementById('reply'),':sealed:','');
    }
function doLove() {
        myimg = url +'img/smilies/heart.gif';
	formatText(document.getElementById('reply'),':love:','');
    }
function doEvil() {
        myimg = url +'img/smilies/evil.gif';
	formatText(document.getElementById('reply'),':-@','');
    }
*/	
//=======color picker
function getScrollY() { var scrOfX = 0, scrOfY = 0; if (typeof (window.pageYOffset) == 'number') { scrOfY = window.pageYOffset; scrOfX = window.pageXOffset; } else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) { scrOfY = document.body.scrollTop; scrOfX = document.body.scrollLeft; } else if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) { scrOfY = document.documentElement.scrollTop; scrOfX = document.documentElement.scrollLeft; } return scrOfY; }

document.write("<style type='text/css'>.colorpicker201{visibility:hidden;display:none;position:absolute;background:#FFF;z-index:999;filter:progid:DXImageTransform.Microsoft.Shadow(color=#D0D0D0,direction=135);}.o5582brd{padding:0;width:12px;height:14px;border-bottom:solid 3px #DFDFDF;border-right:solid 3px #DFDFDF;}a.o5582n66,.o5582n66,.o5582n66a{font-family:arial,tahoma,sans-serif;text-decoration:underline;font-size:12px;color:#666;border:none;}.o5582n66,.o5582n66a{text-align:center;text-decoration:none;}a:hover.o5582n66{text-decoration:none;color:#FFA500;cursor:pointer;}.a01p3{padding:1px 4px 1px 2px;background:whitesmoke;border:solid 3px #DFDFDF;}</style>");

function getTop2() { csBrHt = 0; if (typeof (window.innerWidth) == 'number') { csBrHt = window.innerHeight; } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) { csBrHt = document.documentElement.clientHeight; } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) { csBrHt = document.body.clientHeight; } ctop = ((csBrHt / 2) - 115) + getScrollY(); return ctop; }
var nocol1 = "&#78;&#79;&#32;&#67;&#79;&#76;&#79;&#82;",
clos1 = "[X] Close";

function getLeft2() { var csBrWt = 0; if (typeof (window.innerWidth) == 'number') { csBrWt = window.innerWidth; } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) { csBrWt = document.documentElement.clientWidth; } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) { csBrWt = document.body.clientWidth; } cleft = (csBrWt / 2) - 125; return cleft; }

//function setCCbldID2(val, textBoxID) { document.getElementById(textBoxID).value = val; }
function setCCbldID2(val) { formatText(document.getElementById('reply'),'[color=#' + val + ']','[/color]'); }

function setCCbldSty2(objID, prop, val) {
    switch (prop) {
        case "bc": if (objID != 'none') { document.getElementById(objID).style.backgroundColor = val; }; break;
        case "vs": document.getElementById(objID).style.visibility = val; break;
        case "ds": document.getElementById(objID).style.display = val; break;
        case "tp": document.getElementById(objID).style.top = val; break;
        case "lf": document.getElementById(objID).style.left = val; break;
    }
}

function putOBJxColor2(Samp, pigMent, textBoxId) { if (pigMent != 'x') { setCCbldID2(pigMent, textBoxId); setCCbldSty2(Samp, 'bc', pigMent); } setCCbldSty2('colorpicker201', 'vs', 'hidden'); setCCbldSty2('colorpicker201', 'ds', 'none'); }

function showColorGrid2(Sam, textBoxId) {
    var objX = new Array('00', '33', '66', '99', 'CC', 'FF');
    var c = 0;
    var xl = '"' + Sam + '","x", "' + textBoxId + '"'; var mid = '';
    mid += '<table bgcolor="#FFFFFF" border="0" cellpadding="0" cellspacing="0" style="width:219px;height:187;border:solid 3px #DFDFDF;padding:2px;"><tr>';
    mid += "<td colspan='9' align='left' style='margin:0;padding:2px;height:12px;' ><input class='o5582n66' type='text' size='12' id='o5582n66' value='#FFFFFF'><input class='o5582n66a' type='text' size='2' style='width:14px;' id='o5582n66a' onclick='javascript:alert(\"click on selected swatch below...\");' value='' style='border:solid 1px #666;'></td><td colspan='9' align='right'><a class='o5582n66' href='javascript:onclick=putOBJxColor2(" + xl + ")'><span class='a01p3'>" + clos1 + "</span></a></td></tr><tr>";
    var br = 1;
    for (o = 0; o < 6; o++) {
        mid += '</tr><tr>';
        for (y = 0; y < 6; y++) {
            if (y == 3) { mid += '</tr><tr>'; }
            for (x = 0; x < 6; x++) {
                var grid = '';
                grid = objX[o] + objX[y] + objX[x];
                var b = "'" + Sam + "','" + grid + "', '" + textBoxId + "'";
                mid += '<td class="o5582brd" style="background-color:#' + grid + '"><a class="o5582n66"  href="javascript:onclick=putOBJxColor2(' + b + ');" onmouseover=javascript:document.getElementById("o5582n66").value="#' + grid + '";javascript:document.getElementById("o5582n66a").style.backgroundColor="#' + grid + '";  title="#' + grid + '"><div style="width:12px;height:14px;"></div></a></td>';
                c++;
            }
        }
    }
    mid += "</tr></table>";
    //var ttop=getTop2();
    //setCCbldSty2('colorpicker201','tp',ttop);
    //document.getElementById('colorpicker201').style.left=getLeft2();
    document.getElementById('colorpicker201').innerHTML = mid;
    setCCbldSty2('colorpicker201', 'vs', 'visible');
    setCCbldSty2('colorpicker201', 'ds', 'inline');
}	