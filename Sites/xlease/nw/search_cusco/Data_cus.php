<?php 
include("../../config/config.php");
set_time_limit(0);
$cur_date = date("Y-m-d H:i:s");
$id_user = $_SESSION["av_iduser"];

$qr_usr = pg_query("select \"fullname\" from \"Vfuser\" where \"id_user\"='$id_user'");
$rs_usr = pg_fetch_array($qr_usr);
$user_name = $rs_usr["fullname"];
?>
<script>
$(document).ready(function(){
	$("#waitapprove").show();
	$("#text1").hide();
	$("#text2").show();
	
	$("#waitclick").click(function(){	
		$("#waitapprove").show();
		$("#text1").hide();
		$("#text2").show();	
	});
	$("#waitclick1").click(function(){	
		$("#waitapprove").hide();
		$("#text1").show();
		$("#text2").hide();	
	});

	$("#Image1").click(function(){	
		$("#waitapprove").show();
		$("#text1").hide();
		$("#text2").show();
	});
	
	$("#Image2").click(function(){	
		$("#waitapprove").hide();
		$("#text1").show();
		$("#text2").hide();	
	});
	
	
<?php if($cussearch != ""){ ?>	
	$("#waitapprove").load("../manageCustomer/frm_ShowDetail.php?CusID=<?php echo $cussearch; ?>&notshowapp=not");
<?php }else{ ?>
	var aaaa = $("#sname").val();
    var brokenstring=aaaa.split("#");
	$("#waitapprove").load("../manageCustomer/frm_ShowDetail.php?CusID="+ brokenstring[0] +"&notshowapp=not");
<?php } ?>


});	

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

</script>
<body onLoad="MM_preloadImages('images/folderopen.gif','images/folderadd.gif')">
<table width="850px" align="center">
<tr >
	<td align="left">
    	<div style="position:relative;">
        	<div style="position:absolute; top:0px; right:10px; text-align:right; font-size:14px; font-weight:bold; color:#ff0000;">
            	<?php
					echo "ผู้ทำรายการ : ".$user_name."  วันเวลาที่ทำรายการ : ".$cur_date." <input type=\"button\" name=\"btn_print\" id=\"btn_print\" value=\"พิมพ์หน้านี้\" onClick=\"window.print();\" />";
				?>
            </div>	
            <div id="text1" >
                <img src="images/folderadd.gif" width="19" height="16" id="Image1" style="cursor:pointer;" onMouseOver="MM_swapImage('Image1','','images/folderopen.gif',1)" onMouseOut="MM_swapImgRestore()">
                <span id="waitclick" style="cursor:pointer;"><b>แสดงข้อมูลลูกค้า</b></span>				
            </div>
            <div id="text2">
                <img src="images/folderopen.gif" width="19" height="16" id="Image2" style="cursor:pointer;">
                <span id="waitclick1" style="cursor:pointer;"><b>ซ่อนการแสดงข้อมูลลูกค้า</b></span>				
            </div>
            <div id="waitapprove">	
                    
            </div>	
        </div>			
	</td>
</tr>	
</table>
</body>	