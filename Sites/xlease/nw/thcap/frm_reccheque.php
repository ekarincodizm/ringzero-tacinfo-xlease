<?php
include("../../config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใส่รายการรับเช็ค</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    
    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
$(document).ready(function(){

    $("#sname").autocomplete({
        source: "s_idall.php",
        minLength:2
    });

    $('#btn1').click(function(){
		var aaaa = $("#sname").val();
        var brokenstring=aaaa.split("#");
		if(brokenstring[0]==""){alert("กรุณาป้อนข้อมูลด้วยค่ะ");}
		else{
			if((brokenstring[0].length==15)||(brokenstring[0].length==20)){
			//ตรวจสอบว่ามีเลขที่สัญญานี้อยู่จริงหรือไม่ ถ้าไม่มีให้แจ้ง alert			
			$.post("api.php",{
				cmd : "checkcontract" , 
				contractID : brokenstring[0], 
				
			},
			function(data){
				if(data == "1"){	
					document.location="cheque1.php?ConID="+ brokenstring[0];
				}
				else if(data == "3"){
					if(confirm('เนื่องจาก เลขที่สัญญานี้ไม่มีในระบบ  และ ประเภทสินเขื่อนี้ก็ไม่มีในระบบ คุณต้องการดำเนินการต่อหรือไม่')==true){
						document.location="cheque1.php?ConID="+ brokenstring[0]+"&type=1";
					}
				}
				else if(data == "4"){
					alert("เนื่องจาก เลขที่สัญญาไม่ถูกต้องตาม FORMAT กรุณาป้อนข้อมูลให้ ให้ถูกต้อง");
				}
				else{
					if(confirm('เนื่องจาก เลขที่สัญญานี้ไม่มีในระบบ คุณต้องการดำเนินการต่อหรือไม่')==true){
						document.location="cheque1.php?ConID="+ brokenstring[0]+"&type=1";
					}
				}
			});
			}
			else{
				alert("กรุณาป้อนข้อมูลให้ ให้ถูกต้อง");
			}
		}
        
    });

});
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
			<div style="clear:both; padding: 10px;text-align:center;"><h2>(THCAP) ใส่รายการรับเช็ค</h2></div>
			<div style="text-align:right;"><input type="button" value="  Close  " onclick="javascript:window.close();"></div>
			<fieldset><legend><B>ค้นหา</B></legend>
				<div class="ui-widget" align="center">
					<div style="margin:0;padding-bottom:10px;">
						<b>ชื่อ-นามสกุล, เลขบัตรประชาชน, เลขที่สัญญา</b>&nbsp;
						<input id="sname" name="sname" size="60" />&nbsp;
						<input type="button" id="btn1" value="NEXT>>"/>
					</div>
				</div>
			</fieldset>
			
        </td>
    </tr>
</table>
<fieldset><legend><B>รายการระหว่างรออนุมัติรับเช็ค</B></legend>
	<table align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#66CC99">
		<tr align="center" bgcolor="#66CC99">
			<th>รายการที่</th>
			<th>ประเภท</th>	
			<th>เลขที่สัญญา</th>
			<th>เลขที่เช็ค</th>
			<th>วันที่สั่งเช็ค</th>
			<th>วันที่รับเช็ค</th>
			<th>ธนาคาร</th>	
			<th>สาขา</th>
			<th>จำนวนเงิน</th>
			
		</tr>
		
		
		<?php
		$query = pg_query("select * from finance.\"thcap_receive_cheque\" where \"revChqStatus\" = '9' order by \"revChqID\"");
		$numrows = pg_num_rows($query);
		$no=0;
		while($pending= pg_fetch_array($query))
		{   
			$no+=1;
			$PostChq=$pending ["isPostChq"];
			$InsurChq= $pending["isInsurChq"];
			$resuType="";
			if($PostChq=='1'){$resuType="เช็คชำระล่วงหน้า"; }
			else if($InsurChq=='1'){$resuType="เช็คค้ำประกัน"; }
			else{$resuType="--";}
			$revChqToCCID=$pending["revChqToCCID" ];
			$bankChqNo=$pending["bankChqNo"];
			$bankChqDate=$pending["bankChqDate"];
			
			$revChqDate=$pending["revChqDate" ];//รับเช็ค
			$bankOutID=$pending["bankOutID"];//id bank
			$qry_bank=pg_query("select \"bankName\" from \"BankProfile\" where \"bankID\"='$bankOutID'");
			$resu_bankname= pg_fetch_array($qry_bank);
			$bankname=$resu_bankname["bankName"];
			$bankOutBranch=$pending["bankOutBranch"];
			$bankChqAmt=$pending["bankChqAmt"];
			//จำนวนเงินเป็น !=null ต้องใส่ number_format 
			if($bankChqAmt!=""){ $bankChqAmt=number_format($bankChqAmt,2);}
		if($no%2==0){
			echo "<tr bgcolor=\"#90EE90\">";
		}else{
			echo "<tr bgcolor=\"#F2FFF2\">";
		}
		
		
		echo "<td align=\"center\">$no</td>";
		echo "<td align=\"center\">$resuType</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$revChqToCCID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$revChqToCCID</u></font></a></td>";	
		echo "<td align=\"center\">$bankChqNo</td>";
		echo "<td align=\"center\">$bankChqDate</td>";
		echo "<td align=\"center\">$revChqDate</td>";
		echo "<td align=\"center\">$bankname</td>";	
		echo "<td align=\"center\">$bankOutBranch</td>";
		echo "<td align=\"right\">$bankChqAmt</td>";
		echo "</tr>";
		}
		if($numrows==0){
		echo "<tr bgcolor=#FFFFFF height=50><td colspan=11 align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#66CC99\" height=30><td colspan=11><b>ข้อมูลทั้งหมด $no รายการ</b></td><tr>";
	}
		?>
		</table>
</fieldset>
<br>
<fieldset><legend><B>ประวัติการรับเช็ค 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;"onclick="javascript:popU('frm_pending_cheque.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')" ><u>ทั้งหมด</u></a>) </font></B></legend>
	<table id="tb_approved" align="center" width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#BBBBEE">
	<tr align="center" bgcolor="#CDC9C9">
			<th>รายการที่</th>
			<th>ประเภท</th>	
			<th>เลขที่สัญญา</th>
			<th>เลขที่เช็ค</th>
			<th>วันที่สั่งเช็ค</th>
			<th>วันที่รับเช็ค</th>
			<th>ธนาคาร</th>	
			<th>สาขา</th>
			<th>จำนวนเงิน</th>
			<th>สถานะ</th>
			
			
		</tr>
		
		
		<?php
		//$query = pg_query("select * from finance.\"thcap_receive_cheque\" where \"revChqStatus\" in ('4','8') order by \"revChqID\" DESC limit 30");
		$query = pg_query("select a.* from  finance.\"thcap_receive_cheque\" a
			left join finance.\"thcap_receive_cheque_keeper\" b on a.\"revChqID\"=b.\"revChqID\"	
			where a.\"revChqStatus\" in ('4','8')  order by b.\"keeperStamp\" DESC limit 30");
		
		$numrows = pg_num_rows($query);
		$no=0;
		while($pending= pg_fetch_array($query))
		{   
			$no+=1;
			$PostChq=$pending ["isPostChq"];
			$InsurChq= $pending["isInsurChq"];
			$resuType="";
			if($PostChq=='1'){$resuType="เช็คชำระล่วงหน้า"; }
			else if($InsurChq=='1'){$resuType="เช็คค้ำประกัน"; }
			else{$resuType="--";}
			$revChqToCCID=$pending["revChqToCCID" ];
			$bankChqNo=$pending["bankChqNo"];
			$bankChqDate=$pending["bankChqDate"];
			$revChqStatus=$pending["revChqStatus"];
			if($revChqStatus=='8'){$revChqStatus="<font color=\"green\">ได้รับเช็ค";}
			else if($revChqStatus=='4'){$revChqStatus="<font color=\"red\">ไม่ได้รับเช็ค";}
			
			$revChqDate=$pending["revChqDate" ];//รับเช็ค
			$bankOutID=$pending["bankOutID"];//id bank
			$qry_bank=pg_query("select \"bankName\" from \"BankProfile\" where \"bankID\"='$bankOutID'");
			$resu_bankname= pg_fetch_array($qry_bank);
			$bankname=$resu_bankname["bankName"];
			$bankOutBranch=$pending["bankOutBranch"];
			$bankChqAmt=$pending["bankChqAmt"];//
		if($no%2==0){
			echo "<tr bgcolor=\"#EEE9E9\">";
		}else{
			echo "<tr bgcolor=\"#FFFAFA\">";
		}
		//จำนวนเงินเป็น !=null ต้องใส่ number_format 
		if($bankChqAmt!=""){ $bankChqAmt=number_format($bankChqAmt,2);}
		echo "<td align=\"center\">$no</td>";
		echo "<td align=\"center\">$resuType</td>";
		echo "<td align=\"center\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$revChqToCCID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1048,height=700')\" style=\"cursor:pointer;\"><font color=\"#0000FF\"><u>$revChqToCCID</u></font></a></td>";	
		echo "<td align=\"center\">$bankChqNo</td>";
		echo "<td align=\"center\">$bankChqDate</td>";
		echo "<td align=\"center\">$revChqDate</td>";
		echo "<td align=\"center\">$bankname</td>";	
		echo "<td align=\"center\">$bankOutBranch</td>";
		echo "<td align=\"right\">$bankChqAmt</td>";
		echo "<td align=\"center\">$revChqStatus</td>";
		echo "</tr>";
		}
		if($numrows==0){
		echo "<tr bgcolor=\"#CDC5BF\" height=50><td colspan=\"14\" align=center><b>ไม่พบรายการ</b></td><tr>";
	}else{
		echo "<tr bgcolor=\"#CDC5BF\" height=30><td colspan=\"14\"><b>ข้อมูลทั้งหมด $no รายการ</b></td><tr>";
	}
	?>
		
		</table>
</fieldset>
<div id="panel" style="padding-top: 10px;"></div>
</body>
</html>
