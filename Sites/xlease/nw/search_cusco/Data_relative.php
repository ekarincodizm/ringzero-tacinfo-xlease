<?php 
include("../../config/config.php");
set_time_limit(0); ?>
<script>
$(document).ready(function(){
	$("#reladetail").hide();
	$("#textrela1").show();
	$("#textrela2").hide();
	
	$("#waitrelaclick").click(function(){	
		$("#reladetail").show();
		$("#textrela1").hide();
		$("#textrela2").show();	
	});
	$("#waitrelaclick1").click(function(){	
		$("#reladetail").hide();
		$("#textrela1").show();
		$("#textrela2").hide();	
	});

	$("#Imagerela1").click(function(){	
		$("#reladetail").show();
		$("#textrela1").hide();
		$("#textrela2").show();
	});
	
	$("#Imagerela2").click(function(){	
		$("#reladetail").hide();
		$("#textrela1").show();
		$("#textrela2").hide();	
	});
});	

function MM_swapImgRestorerela() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_swapImagerela() { //v3.0
  var i,j=0,x,a=MM_swapImagerela.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObjrela(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_preloadImagesrela() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImagesrela.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObjrela(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObjrela(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}
</script>
<body onload="MM_preloadImagesrela('images/folderopen.gif','images/folderadd.gif')">
<table width="850px" align="center">
<tr>
	<td> 	
		<div id="textrela1" align="left" >
			<img src="images/folderadd.gif" width="19" height="16" id="Imagerela1" style="cursor:pointer;" onmouseover="MM_swapImagerela('Imagerela1','','images/folderopen.gif',1)" onmouseout="MM_swapImgRestorerela()">
			<span id="waitrelaclick" style="cursor:pointer;"><b>แสดงข้อมูลบุคคลที่น่าจะเกี่ยวข้องกัน</b></span>				
		</div>
		<div id="textrela2" align="left">
			<img src="images/folderopen.gif" width="19" height="16" id="Imagerela2" style="cursor:pointer;">
			<span id="waitrelaclick1" style="cursor:pointer;"><b>ซ่อนการแสดงข้อมูลบุคคลที่น่าจะเกี่ยวข้องกัน</b></span>				
		</div>
		
			<div id="reladetail" width="850px" align="center">
				
				<fieldset><legend><div align="left" style="padding:5px 0px 0px 5px"><font size="2px"><b>บุคคลที่น่าจะเกี่ยวข้องโดยเป็น ญาติกัน</b></font></div></legend>
				
				
					<?php 
						
						$cussrhfam = pg_escape_string($_GET['CusID']);
						
						$sqlsrhcusfam = pg_query("SELECT \"A_NAME\",\"A_SIRNAME\" FROM \"Fa1\" where \"CusID\" = '$cussrhfam' "); 
						list($namecus,$sirnamecus) = pg_fetch_array($sqlsrhcusfam);
						$sirnamecus = trim($sirnamecus);
						$namecus = trim($namecus);
				
						$sqlsrhcusfam = pg_query("SELECT \"CusID\" FROM \"Fa1\" where TRIM(\"A_SIRNAME\") = '$sirnamecus' and \"A_NAME\" != '$namecus' and \"CusID\" != '$cussrhfam'");
						$rowsrhcusfam = pg_num_rows($sqlsrhcusfam);	
					if($rowsrhcusfam == 0){ echo "<div style=\"background-color:#FFFF99\"> ไม่พบข้อมูล </div>"; }else{
							while($resrhcusfam = pg_fetch_array($sqlsrhcusfam)){
							$CusID = trim($resrhcusfam['CusID']);							
							$dontshowapp = 'not';
							$showdatacus = 'true';
					?>		
							<div><?php include("../manageCustomer/frm_ShowDetail.php"); ?></div>
								
							
						<?php } 
					}
						
						?>
				</fieldset>
				<fieldset><legend><div align="left" style="padding:5px"><font size="2px"><b>บุคคลที่น่าจะเกี่ยวข้องโดยเป็นเพื่อนหรือผู้มีส่วนได้เสียกัน</b></font></div></legend>
					<?php 						
						
					$sqlsrhcusfam = pg_query("select count(\"CusID\") as cc,\"CusID\" from \"ContactCus\" where \"IDNO\" IN (SELECT b.\"IDNO\" FROM \"ContactCus\" b where b.\"CusID\" = '$cussrhfam') and \"CusID\" != '$cussrhfam' group by \"CusID\" having  count(\"CusID\") > 2");
					$rowsrhcusfam = pg_num_rows($sqlsrhcusfam);	
				if($rowsrhcusfam == 0){ echo "<div style=\"background-color:#FFFF99\"> ไม่พบข้อมูล </div>"; }else{	
					while($resrhcusfam = pg_fetch_array($sqlsrhcusfam)){
							$CusID = trim($resrhcusfam['CusID']);							
							$dontshowapp = 'not';
							$showdatacus = 'true';
					?>		
							<div><?php include("../manageCustomer/frm_ShowDetail.php"); ?></div>
								
							
						<?php } 
						
				}		
						?>
				</fieldset>	
			</div>			
	</td>
</tr>	
</table>
</body>	