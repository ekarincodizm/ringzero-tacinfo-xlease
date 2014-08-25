<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<style type="text/css">
#popupClose{
	font-size:12px;
	line-height:12px;
	rightright:6px;
	top:4px;
	position:absolute;
	font-weight:bold;
	display:block;
	cursor:pointer;
	font-family: Tahoma, Geneva, sans-serif;
	color: #999;
	text-decoration: none;
	right: 5px;
}
#bgPopup{  
    display:none;   
    position:fixed;    
    _position:absolute; /* hack for internet explorer 6*/    
    height:100%;    
    width:100%;    
    top:0;    
    left:0;    
    background:#000000;     
    z-index:1;    
}
#Popup{
	display:none;
	position:fixed;
	_position:absolute; /* hack for internet explorer 6 */
	background:#FFFFFF;
	border:1px solid #cecece;
	z-index:2;
	padding:15px;
	font-size:13px;
}
#customerName {
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 15px;
	line-height: 30px;
	font-weight: normal;
	color: #FFF;
	background-color: #000;
	height: 30px;
	width: 100%;
	position: absolute;
	left: 0px;
	bottom: 0px;
	opacity:0.7;
}
</style>
<script type="text/javascript">
	function loadPopup(){  
        //loads popup only if it is disabled  
        if($("#bgPopup").data("state")==0){  
            $("#bgPopup").css({  
                "opacity": "0.5"  
            });  
            $("#bgPopup").fadeIn("medium");  
            $("#Popup").fadeIn("medium");  
            $("#bgPopup").data("state",1);  
        }  
    }  
      
    function disablePopup(){  
        if ($("#bgPopup").data("state")==1){  
            $("#bgPopup").fadeOut("medium");  
            $("#Popup").fadeOut("medium");  
            $("#bgPopup").data("state",0);  
        }  
    }  
      
    function centerPopup(){  
        var winw = $(window).width();  
        var winh = $(window).height();  
        var popw = $('#Popup').width();  
        var poph = $('#Popup').height();  
        $("#Popup").css({  
            "position" : "absolute",  
            "top" : winh/2-poph/2,  
            "left" : winw/2-popw/2  
        });  
        //IE6  
        $("#bgPopup").css({  
            "height": winh    
        });  
    }  
</script>
<script type="text/javascript">
$(document).ready(function() {  
   $("#bgPopup").data("state",0);  
   $("#myButton").click(function(){  
        centerPopup();  
        loadPopup();     
   });  
   $("#popupClose").click(function(){  
        disablePopup();  
   }); 
   $("#bgPopup").click(function(){  
        disablePopup();  
   }); 
   $(document).keypress(function(e){  
        if(e.keyCode==27) {  
            disablePopup();   
        }  
    });  
});  
  
//Recenter the popup on resize - Thanks @Dan Harvey [http://www.danharvey.com.au/]  
$(window).resize(function() {  
centerPopup();  
});
</script>
</head>

<body>
<input id="myButton" value="Click to activate the Popup!" type="button">  
<div id="Popup">  
<a id="popupClose">x</a>  
<h1><img src="upload/1.png"</h1> 
<span id="customerName">นายติงต๊อง  บ๊องตื้น</span>   
</div>   
<div id="bgPopup"></div>
</body>
</html>