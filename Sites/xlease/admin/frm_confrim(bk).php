<?php
session_start();
include("../config/config.php");
$_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/tempAV_admin.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>

<link rel="stylesheet" href="menu/lavalamp_test.css" type="text/css" media="screen">
    <script type="text/javascript" src="menu/jquery-1.2.3.min.js"></script>
    <script type="text/javascript" src="menu/jquery.easing.min.js"></script>
    <script type="text/javascript" src="menu/jquery.lavalamp.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $("#menu").lavaLamp({
                fx: "backout",
                speed: 700,
                click: function(event, menuItem) {
                    return true;
                }
            });
        });
    </script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>AV. leasing co.,ltd</title>
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<style type="text/css">
    .mouseOut {
    background: #708090;
    color:#00CCFF;
    }

    .mouseOver {
    background:#00CCCC;
    color: #000000;
    }
<!--
.style1 {
	font-family: Tahoma;
	font-size: medium;
}
.style3 {
    font-family: Tahoma;
	color: #ffffff;
	font-weight: bold;
	font-size: medium;
}
.style4 {
    font-family: Tahoma;
	color: #000000;
  }
  .style5 {
    font-family: Tahoma;
	color: #000000;
	font-size: medium;
  }
.style7 {color: #0000FF}

   
    

-->
</style>
<!-- InstanceBeginEditable name="doctitle" -->
<title>AV. leasing co.,ltd</title>
   <script type="text/javascript">
	    var xmlHttp;

	function createXMLHttpRequest() {
	     if (window.ActiveXObject) {
		    xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
		 } 
			else if (window.XMLHttpRequest) {
			 xmlHttp = new XMLHttpRequest();
			 }
		}
	
		function startRequest_Lock() {
		    createXMLHttpRequest();
            var sText = document.getElementById("var_lockidno").value;
			var sNumber = document.getElementById("f_carnum").value;
            xmlHttp.open("get", "lock_idno.php?stalock=0&idnoget=" + sText + "&fcarnum="+sNumber, true);
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
                        displayInfo_lock(xmlHttp.responseText);
                    } else {
                        displayInfo_lock("พบข้อผิดพลาด: " + xmlHttp.statusText); 
                    }
                }
				    
            };
            xmlHttp.send(null);
        }
		
		function startRequest_nuLock() {
		    createXMLHttpRequest();
            var sText = document.getElementById("var_lockidno").value;
			var sNumber = document.getElementById("f_carnum").value;
            xmlHttp.open("get", "lock_idno.php?stalock=1&idnoget=" + sText+ "&fcarnum="+sNumber, true);
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
                        displayInfo_lock(xmlHttp.responseText);
                    } else {
                        displayInfo_lock("พบข้อผิดพลาด: " + xmlHttp.statusText); 
                    }
                }
				    
            };
            xmlHttp.send(null);
        }
		
        
        function displayInfo_lock() {
            document.getElementById("divInfo_lock").innerHTML = xmlHttp.responseText;
			 
        }
		
		function startRequest_acc() {
		    createXMLHttpRequest();
            var acc_Text = document.getElementById("var_lockidno").value;
            xmlHttp.open("get", "create_accpayment.php?idno_acc=" + acc_Text, true);
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
                        displayInfo_acc(xmlHttp.responseText);
                    } else {
                        displayInfo_acc("พบข้อผิดพลาด: " + xmlHttp.statusText); 
                    }
                }
				    
            };
            xmlHttp.send(null);
        }
        
        function displayInfo_acc() {
            document.getElementById("divInfo_acc").innerHTML = xmlHttp.responseText;
			 
        }
		
		
			function startRequest_ccc() {
		    createXMLHttpRequest();
            var ccc_Text = document.getElementById("var_lockidno").value;
            xmlHttp.open("get", "create_cuspayment.php?idno_ccc=" + ccc_Text, true);
            xmlHttp.onreadystatechange = function () {
                if (xmlHttp.readyState == 4) {
                    if (xmlHttp.status == 200) {
                        displayInfo_ccc(xmlHttp.responseText);
                    } else {
                        displayInfo_ccc("พบข้อผิดพลาด: " + xmlHttp.statusText); 
                    }
                }
				    
            };
            xmlHttp.send(null);
        }
        
        function displayInfo_ccc() {
            document.getElementById("divInfo_ccc").innerHTML = xmlHttp.responseText;
			 
        }
		
    </script>

<!-- InstanceEndEditable -->

<!-- InstanceBeginEditable name="head" --><!-- InstanceEndEditable -->
</head>

<body style="background-color:#ffffff; margin-top:0px;">
<div id="wmax" style="width:100%; height:100%; border:#666666 solid 0px; margin-top:0px;">
<div class="style1" id="header" style="height:50px; width:auto; text-align:center; opacity:20;">
<h1 class="style4"> AV.LEASING</h1>
</div>
<div id="login"  style="height:50px; width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-image:url(../css/bg_bar.jpg); background-repeat:no-repeat; width:800px; height:27px; ">
       <div style="float:left; width:520px;">
	   <ul class="lavaLampNoImage" id="menu">
	   <li class="current"><a href="../list_menu.php">รายการหลัก </a></li>
	   <?php
	   include("../config/config.php");
	   $qrymenu=pg_query("select * from f_menu where id_menu like 'A0%' ");
	   while($res_menu=pg_fetch_array($qrymenu))
	   {
	    #$xp_path=substr($res_menu["path_menu"],0,10); 
		$xp_path=$res_menu["path_menu"]; 
	   ?>
        <li><a href="../<?php echo $xp_path; ?>"><?php echo $res_menu["name_menu"]; ?></a></li>
	<?php
	 }
	?>
</ul>
</div>

      <div align="right" style="padding-right:27px; width:180px; float:left;">
	  <div style="padding-top:4px;  height:27px; background-image:url(../css/bg_search.jpg); background-repeat:no-repeat;" align="center">	
	  <form method="post" action="../Templates/frm_edit.php" name="frmsent"> 
	    <input type="text" id="idno_names" name="idno_names" style="border:0px;" onchange="document.frmsent.submit();"/>
	    <input name="h_id" type="hidden" id="h_id" value="0"  />
		 <script type="text/javascript">
function make_autocom(autoObj,showObj){
	var mkAutoObj=autoObj; 
	var mkSerValObj=showObj; 
	new Autocomplete(mkAutoObj, function() {
		this.setValue = function(id) {		
			document.getElementById(mkSerValObj).value = id;
		}
		if ( this.isModified )
			this.setValue("");
		if ( this.value.length < 1 && this.isNotClick ) 
			return ;	
		return "../admin/listdata.php?q=" + this.value;
    });	
}	
make_autocom("idno_names","h_id");
</script>
</form>
	  </div>
     </div>
  </div>
  <div class="style3 style7" style="background-color:#ffffff; width:auto; height:20px; padding-left:10px;">
  </div>
  <!-- InstanceBeginEditable name="EditRegion3" -->
<div id="login"  style="width:800px; text-align:left; margin-left:auto; margin-right:auto;">
  <div class="style3" style="background-color:#FFCC02;; width:auto; height:20px; padding-left:10px;">AV. Leasing </div>
  <div class="style3" style="background-color:#996600; width:auto; height:20px; padding-left:10px;"></div>
  <div class="style5" style="width:auto; height:100px; padding-left:0px;">
  <?php
  
$sidno=trim(pg_escape_string($_POST["idno_names"]));
$idno=substr($sidno,0,11);	
  
   /* $qry_fp=pg_query("select A.*,
                     B.\"C_REGIS\",B.\"CarID\",    
                     C.\"CusID\",C.\"A_FIRNAME\",C.\"A_NAME\",C.\"A_SIRNAME\"
                     from \"Fp\" A
                     LEFT OUTER JOIN \"Fc\" B on A.asset_id=B.\"CarID\" 
					 LEFT OUTER JOIN \"Fa1\" C on A.\"CusID\"=C.\"CusID\" 
					 where  A.\"IDNO\"='$idno'");
   */
   $qry_fp=pg_query("select A.*,B.\"LockContact\",B.\"IDNO\",B.\"P_FDATE\",B.\"P_STDATE\" from \"VContact\" A LEFT OUTER JOIN \"Fp\" B on B.\"IDNO\"=A.\"IDNO\" where  A.\"IDNO\"='$idno' ");
   $res_fp=pg_fetch_array($qry_fp);
   
     //C_CARNUM 
   if($res_fp["C_REGIS"]=="")
	{

	$rec_regis=trim($res_fp["car_regis"]);
	$rec_cnumber=trim($res_fp["carnum"]);
	$res_band="ยี่ห้อแก๊ส ".$res_fp["gas_name"];
	
	
	}
	else
	{
	
	$rec_regis=trim($res_fp["C_REGIS"]);
	$rec_cnumber=trim($res_fp["C_CARNUM"]);
	$res_band="ยี่ห้อรถ ".$res_fp["C_CARNUM"];
	}
   
   
   $reslock=$res_fp["LockContact"];
   if($reslock=='t')
   {
     $strlock="Lock แล้ว";
	 $bt_lock="<input type=\"button\" value=\"ปลด Lock Contact\" name=\"lockidno\" id=\"lockidno\" onClick=\"startRequest_nuLock()\"/>";
   }
   else
   {
     $strlock="ยังไม่ได้ Lock";
	 $bt_lock="<input type=\"button\" value=\"Lock Contact\" name=\"lockidno\" id=\"lockidno\" onClick=\"startRequest_Lock()\"/>";
   }
   
   if($res_fp["P_BEGIN"]==0)
   {
    $bt_ccc="";
   }
   else
   {
     $bt_ccc="<input name=\"button2\" type=\"button\" value=\"Create Customer Payment\" onclick=\"startRequest_ccc()\" />";
   }
   
   if($res_fp["P_BEGINX"]==0)
   {
    $bt_acc="";
   }
   else
   {
    $bt_acc="<input type=\"button\" value=\" Create Account Payment \" onclick=\"startRequest_acc()\"/>";
   
   }
 
      
  ?>
  
  <table width="100%" border="0">
  <tr style="background-color:#FF9900;">
    <td colspan="4">Lock สัญญา </td>
    </tr>
  <tr style="background-color:#EBF0C6;">
    <td width="122">เลขที่สัญญา</td>
    <td width="307"><input type="text" value="<?php echo $res_fp["IDNO"]; ?>"  /></td>
    <td width="93">วันทำสัญญา</td>
    <td><input type="text" value="<?php echo $res_fp["P_STDATE"]; ?>"  /></td>
  </tr>
  <tr style="background-color:#D0DCA0">
    <td>ทะเบียน</td>
    <td><input type="text" value="<?php echo $rec_regis; ?>"  /></td>
    <td>วันชำระงวดแรก</td>
    <td><input type="text" value="<?php echo $res_fp["P_FDATE"]; ?>"  /></td>
  </tr>
  <tr style="background-color:#EBF0C6;">
    <td>ชื่อ - นามสกุล </td>
    <td colspan="3"><input type="text" value="<?php echo $res_fp["full_name"]; ?>" size="50"  /></td>
    </tr>
  <tr style="background-color:#D0DCA0">
    <td>เงินดาวน์</td>
    <td colspan="3"><input type="text" value="<?php echo number_format($res_fp["P_DOWN"],2); ?>" style="text-align:right;"  /></td>
    </tr>
  <tr style="background-color:#EBF0C6;">
    <td>ผ่อนชำระเดือนละ</td>
    <td colspan="3"><input type="text" value="<?php echo number_format($res_fp["P_MONTH"],2); ?>" style="text-align:right;"  /></td>
    </tr>
  <tr style="background-color:#D0DCA0">
    <td>จำนวนงวด</td>
    <td colspan="3"><input type="text" value="<?php echo $res_fp["P_TOTAL"]; ?>" style="text-align:right;"  /></td>
    </tr>
  <tr style="background-color:#EBF0C6;">
    <td>เงินต้นลูกค้า</td>
    <td colspan="3"><input type="text" value="<?php echo number_format($res_fp["P_BEGIN"],2); ?>" style="text-align:right;" /></td>
    </tr>
  <tr style="background-color:#D0DCA0;">
    <td>เงินต้นทางบัญชี</td>
    <td colspan="3"><input type="text" value="<?php echo number_format($res_fp["P_BEGINX"],2); ?>" style="text-align:right;"  /> </td>
    </tr>
   <tr>
    <td><?php echo $bt_lock; ?>
	    <?php  
		$resstnumber=strlen($rec_cnumber);         
		$var_cnumber=substr($rec_cnumber,$resstnumber-9,9)
	   ?>
	    <input type="hidden" value="<?php echo $var_cnumber; ?>" name="f_carnum" id="f_carnum"  />
		<input type="hidden" value="<?php echo $idno; ?>" name="var_lockidno" id="var_lockidno"  />	</td>
    <td colspan="2"><div id="divInfo_lock"><?php echo $strlock; ?></div></td>
    <td width="242">&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $bt_acc; ?></td>
    <td colspan="2"><div id="divInfo_acc"></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><?php echo $bt_ccc; ?></td>
    <td colspan="2"><div id="divInfo_ccc"></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2"><input type="button" value="กลับไปรายการหลัก" onclick="window.location='../list_menu.php' "/></td>
    <td>&nbsp;</td>
  </tr>
</table>  
<table width="95%" border="0" style="background-color:#999999;" cellpadding="1" cellspacing="1">
<?php 
$qry_cpm=pg_query("select * from \"CusPayment\" where \"IDNO\"='$idno' ");
$numr=pg_num_rows($qry_cpm);
if($numr==0)
{
?>
 <tr style="background-color:#FDE2AC">
    <td colspan="6">ยังไม่ได้สร้างข้อมูล Cuspayment</td>
  </tr>
<?php
}
else
{
?>  
  <tr style="background-color:#EEF2DB;">
    <td colspan="6">ตารางแสดง Cuspayment </td>
  </tr>
  
	
 
  <tr style="background-color:#D0DCA0">
    <td width="106">DueNo</td>
    <td width="110">DueDate</td>
    <td width="110">Remine</td>
    <td width="152">Priciple</td>
    <td width="125">Interest</td>
    <td width="143">AccuInt</td>
  </tr>
  
   <?php
  while($rescus=pg_fetch_array($qry_cpm))
  {
  ?>	
  <tr style="background-color:#EEF2DB">
    <td width="106"><?php echo $rescus["DueNo"]; ?></td>
    <td width="110"><?php echo $rescus["DueDate"]; ?></td>
    <td width="110" style="text-align:right;"><?php echo number_format($rescus["Remine"],2); ?></td>
    <td width="152" style="text-align:right;"><?php echo number_format($rescus["Priciple"],2); ?></td>
    <td width="125" style="text-align:right;"><?php echo number_format($rescus["Interest"],2); ?></td>
    <td width="143" style="text-align:right;"><?php echo number_format($rescus["AccuInt"],2); ?></td>
  </tr>
  <?php
  }
  ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
 <?php
 }
 ?> 
</table>
  </div>

</div>
  <div style="height:300px; overflow:auto;"> 


</div>
<!-- InstanceEndEditable -->
</div>
</body>
<!-- InstanceEnd --></html>
