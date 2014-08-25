<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) พิมพ์ใบกำกับภาษี</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	$("#id").autocomplete({
        source: "s_thcap_v_inv.php?condition="+ $("#idcon").val(),
        minLength:2
    });

    $('#btn1').click(function(){
        $("#panel").load("frm_Receipt_Detail_v_inv.php?id="+ $("#id").val()+"&condition="+$("#idcon").val());
    });

});
function check_search(){
	if(document.getElementById("search1").checked){
		document.getElementById("idcon").value ='1';
	}else if(document.getElementById("search2").checked){
		document.getElementById("idcon").value ='2';
		
	}
	document.form1.submit();
}
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
  
<style type="text/css">
.ui-datepicker{
    width:200px;
    font-family:tahoma;
    font-size:13px;
    text-align:center;
}

.odd{
    background-color:#EDF8FE;
    font-size:12px
}
.even{
    background-color:#D5EFFD;
    font-size:12px
}
</style>
    
</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td>

<div style="float:right"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
<div style="clear:both; padding-bottom: 10px;"></div>
<?php
	$con= pg_escape_string($_POST["idcon"]);
	if($con==""){
		$con=1;
	}
?>
<fieldset>
<legend><B>(THCAP) พิมพ์ใบกำกับภาษี</B></legend>
<div class="ui-widget" align="center">
<form method="post" name="form1" action="#">
<div style="margin:0;padding-bottom:10px;">
<b>ค้นจาก : </b>
<input type="radio" name="typesearch" id="search1" value="1" onclick="check_search()" checked <?php if($con=="1"){ echo "checked";}?>>เลขที่สัญญา
<input type="radio" name="typesearch" id="search2" value="2" onclick="check_search()" <?php if($con=="2"){ echo "checked";}?>>เลขที่ใบกำกับภาษี
<br>
<b>คำค้น : </b><input type="text" id="id" name="id" size="40" />
<input type="hidden" id="idcon" name="idcon" value="<?php echo $con;?>">&nbsp;
<input type="button" id="btn1" value="ค้นหา"/>
</div>
</form>
 </fieldset>

        </td>
    </tr>
</table>

<div id="panel" style="padding-top: 10px;"></div>

<!-- ประวัติการพิมพ์ 30 รายการล่าสุด -->

<div style="padding-top:70px;"></div>
<center>
<fieldset style="padding:15px;width:85%;" >
	<legend><font color="black"><b>ประวัติการพิมพ์ใบกำกับภาษี 30 รายการล่าสุด ( <font color="blue"><a onclick="popU('frm_history_print_vreceipt.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1250,height=700')" style="cursor:pointer;"><u> ทั้งหมด </u></a></font>)</b></font></legend>
    <table width="100%" border="0" cellpadding="1" cellspacing="1" align="center">
    	<tr style="background-color:#CCCCCC;">
			<th align="center">รายการที่</th>
        	<th align="center">เลขที่สัญญา</th>
            <th align="center">ผู้กู้หลัก</th>
            <th align="center">เลขที่ใบกำกับภาษี</th>
            <th align="center">วันที่จ่าย</th>
            <th align="center">จำนวนเงินที่จ่าย</th>
            <th align="center">ผู้พิมพ์</th>
			<th align="center">วันที่/เวลาที่พิมพ์</th>
			<th align="center">เหตุผลที่ขอพิมพ์</th>
        </tr>
        <?php
				$i = 0;
					//ค้นหาเลขที่สัญญา
					$qry_con=pg_query("	SELECT a.\"id\",b.\"contractID\" as contract,b.\"taxinvoiceID\",b.\"taxpointDate\",a.\"reprint_datetime\",a.\"reprint_reason\",c.\"fullname\" as \"fullnameuserprint\"
										FROM \"thcap_reprint_log\" a 
										LEFT JOIN \"thcap_v_taxinvoice_details\" b ON a.\"receipt_id\" = b.\"taxinvoiceID\"
										LEFT JOIN \"Vfuser\" c ON a.\"reprint_user\" = c.\"id_user\"
										WHERE a.\"receipt_id\" IN (select \"taxinvoiceID\" from \"thcap_v_taxinvoice_details\")
										GROUP BY a.\"id\",b.\"contractID\",b.\"taxinvoiceID\",b.\"taxpointDate\",a.\"reprint_datetime\",a.\"reprint_reason\",\"fullnameuserprint\"
										ORDER BY a.\"reprint_datetime\" DESC LIMIT 30");
					
					
					$numcon=pg_num_rows($qry_con);
					
					if($numcon>0){ //แสดงว่ามีข้อมูล
						$status=1;
					}else{
						$status=0;
					}
				
				
				if($status==1){
					while($result=pg_fetch_array($qry_con)){
						$logid=trim($result["id"]); //รหัสการเก็บประวัติการพิมพ์		
						$contractID=trim($result["contract"]); //เลขที่สัญญษ
						$taxinvoiceID=trim($result["taxinvoiceID"]); //รหัสใบกำกับ
						$taxpointDate=trim($result["taxpointDate"]); //วันที่ออกใบกำกับ
						$fullnameuserprint=trim($result["fullnameuserprint"]); //ชื่อผู้ขอพิมพ์
						$reprint_datetime=trim($result["reprint_datetime"]); //วันเวลาที่ขอพิมพ์
						$reprint_reason=trim($result["reprint_reason"]); //เหตุผลที่ขอพิมพ์
						$i++;
						$qry_cusname = pg_query("SELECT thcap_fullname FROM \"vthcap_ContactCus_detail\" where \"contractID\" = '$contractID' and \"CusState\" = 0");
						list($full_namecus) = pg_fetch_array($qry_cusname);
				
						
							$id = $result['taxinvoiceID'];
						

						
						$qry_con1=pg_query("select sum(\"debtAmt\") as \"debtAmt1\" from thcap_v_taxinvoice_otherpay where \"taxinvoiceID\"='$id' ");

						$result2=pg_fetch_array($qry_con1);	

						$sum_debtAmt = $result2["debtAmt1"]; // netAmt+vatAmt
						
						
						if($i%2==0){
							echo "<tr bgcolor=\"#EEEEEE\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=\"center\">";
						}else{
							echo "<tr bgcolor=\"#DDDDDD\" onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#DDDDDD';\" align=\"center\">";
						}
				
						
						echo "
						<td>$i</td>
						<td><span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\">
						<u>$contractID</u></font></span></td>
						<td align=\"left\">$full_namecus</td>
						<td>$taxinvoiceID</td>
						<td>$taxpointDate</td>
						<td align=right>".number_format($sum_debtAmt,2)."</td>
						<td align=\"left\">$fullnameuserprint</td>
						<td>$reprint_datetime</td>
						<td><img src=\"images/detail.gif\" onclick=\"popU('frm_note_reprint.php?logid=".$logid."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=350')\" style=\"cursor:pointer;\"></td>
						";		
						echo "</tr>";
					}	
					
				}else{
					echo "<tr align=center height=30 bgcolor=\"#EAF9FF\"><td colspan=9><h2>-ไม่พบข้อมูลการพิมพ์กำกับภาษี-</h2></td></tr>";
				}
		?>
    </table>
</fieldset>
</center>




</div>

</body>
</html>