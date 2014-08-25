<?php
session_start();
include("../config/config.php");
$_SESSION["ses_idno"] = "";
$add_user=$_SESSION["av_iduser"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION['session_company_name']; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
	
    <link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />  
    <script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
     
   

<script language=javascript>
var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}

function closeAll(){
    for (i in wnd){
        wnd[i].close();
    }
}

$(document).ready(function(){
	document.search.submit1.focus();
    $("#idno2").autocomplete({
        source: "gdata.php",
        minLength:3
    });
	
	    $("#idno").autocomplete({
        source: "gdata2.php",
        minLength:3
    });
	
input_findtxt();
input_findtxt('t');	
});

function input_findtxt(num){
	if(num == 't'){
		if(document.search.idno.value == ""){
			document.search.idno.style.color = "gray";
			document.search.idno.value = "กรอกข้อมูลอย่างน้อย 3 ตัวอักษรขึ้นไป...";		
		}
	}else{
		if(document.search.idno2.value == ""){
			document.search.idno2.style.color = "gray";
			document.search.idno2.value = "กรอกข้อมูลอย่างน้อย 3 ตัวอักษรขึ้นไป...";		
		}
	}	
}
function remove_findtxt(num){
	if(num == 't'){
		document.search.idno.style.color = "";
		document.search.idno.value = "";
	}else{
		document.search.idno2.style.color = "";
		document.search.idno2.value = "";
	}
}
</script>
    
</head>
<body>
 
<fieldset><legend><B>แสดงตารางผ่อนชำระ - ค้นหาข้อมูล</B></legend>

 <form name="search" method="post" action="frm_viewcuspayment.php">
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
     <td><b>IDNO,ชื่อ/สกุล,ทะเบียน,Ref1,Ref2</b>
        <input name="idno_names" type="hidden" id="idno_names" value="<?php echo $_POST['h_arti_id']; ?>" />
        <input type="text" id="idno" name="idno" size="100" value="<?php echo $_POST['h_arti_id']; ?>" onblur="javascript:input_findtxt('t');" onclick="javascript:remove_findtxt('t');" tabindex="0">
        <input type="submit" name="submit1" value="   ค้นหา   " tabindex="1">
      </td>
      </tr>
      <tr align="center">
      <td><b>ค้นหาแบบละเอียดพร้อมแสดงแถบสี </b>
        <input name="idno_names2" type="hidden" id="idno_names2" value="<?php echo $_POST['h_arti_id']; ?>" />
        <input type="text" id="idno2" name="idno2" size="100" value="<?php echo $_POST['h_arti_id']; ?>" onblur="javascript:input_findtxt();" onclick="javascript:remove_findtxt();" tabindex="2">
        <input type="submit" name="submit" value="   ค้นหา   " tabindex="3">
      </td>
   </tr>
</table>
</form>

</fieldset>
 <div style="margin-top:25px;"></div>
<table width="100%"  cellSpacing="0"  cellPadding="1" > 
	<tr>
		<td width="49%" align="center">
			<fieldset style="width:95%;"><legend>15 รายการล่าสุดที่เปิดโดยฉัน</legend><br>
				<table width="100%" frame="box" cellSpacing="0" cellPadding="1">
					<tr bgcolor="#CDB5CD">		
						<th width="20%">สัญญาที่ค้นหา</th>
						<th width="25%">ทะเบียนรถ</th>
						<th width="30%">ผู้เช่าซื้อ</th>
						<th width="25%">เวลาเริ่มใช้งาน</th>
					</tr>	
			
				<?php $sqlfindlist = pg_query("	SELECT a.time_open, a.ref_id,b.\"C_REGIS\", b.full_name ,b.asset_id 
												FROM \"LogsAnyFunction\" a
												LEFT JOIN (	
															SELECT z.\"IDNO\",y.\"C_REGIS\",x.\"full_name\",z.\"asset_type\",z.asset_id
															FROM \"Fp\" z 
															LEFT JOIN \"Fc\" y ON z.\"asset_id\" = y.\"CarID\"
															LEFT JOIN \"Fa1_FAST\" x ON z.\"CusID\" = x.\"CusID\"
															WHERE z.\"asset_type\" = '1'
														  ) b 
												on b.\"IDNO\" = a.ref_id
												where a.id_menu='P05' and a.user_id='$add_user' and b.\"asset_type\" = '1'
												
												UNION ALL
												
												SELECT a.time_open, a.ref_id,b.\"car_regis\" as \"C_REGIS\", b.full_name ,b.asset_id 
												FROM \"LogsAnyFunction\" a
												LEFT JOIN (	
															SELECT z.\"IDNO\",y.\"car_regis\",x.\"full_name\",z.\"asset_type\",z.asset_id
															FROM \"Fp\" z 
															LEFT JOIN \"FGas\" y ON z.\"asset_id\" = y.\"GasID\"
															LEFT JOIN \"Fa1_FAST\" x ON z.\"CusID\" = x.\"CusID\"
															WHERE z.\"asset_type\" = '2'
														  ) b 
												on b.\"IDNO\" = a.ref_id
												where a.id_menu='P05' and a.user_id='$add_user' and b.\"asset_type\" = '2'
												
												ORDER BY \"time_open\" DESC limit 15"); 
						$i=0;						
						while($refindlist = pg_fetch_array($sqlfindlist)){	
							$inno = $refindlist['ref_id'];
							$careregis = $refindlist['C_REGIS'];
							$fullname = $refindlist['full_name'];						
							$carid = $refindlist['asset_id'];
							$i++;
							if($i%2==0){
								echo "<tr bgcolor=#EED2EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EED2EE';\" align=center>";
							}else{
								echo "<tr bgcolor=#FFE1FF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFE1FF';\" align=center>";
							}
							echo "
									<td align=\"center\" onclick=\"javascript:popU('frm_viewcuspayment.php?idno_names=$inno&carid=$carid','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer\"><u>".$inno."</u></td>
									<td align=\"center\">".$careregis."</td>	
									<td align=\"left\">".$fullname."</td>
									<td align=\"center\">".$refindlist['time_open']."</td>										
								 </tr>";
						}
				?>				
				</table> 
			</fieldset>
		 </td>
		 <td align="center">
			<fieldset style="width:95%;"><legend>15 รายการสัญญาล่าสุดที่เปิดโดยทั่วไป (ในบุคคลทั่วไปจะรวมของฉันด้วย)</legend><br>
				<table width="100%" frame="box" cellSpacing="0" cellPadding="1">
					<tr bgcolor="#CDBA96">		
						<th width="20%">สัญญาที่ค้นหา</th>
						<th width="25%">ทะเบียนรถ</th>
						<th width="30%">ผู้เช่าซื้อ</th>
						<th width="25%">เวลาเริ่มใช้งาน</th>
					</tr>	
			
				<?php $sqlfindlist = pg_query("
												SELECT a.time_open, a.ref_id,b.\"C_REGIS\", b.full_name ,b.asset_id
												FROM \"LogsAnyFunction\" a
												LEFT JOIN (	
															SELECT z.\"IDNO\",y.\"C_REGIS\",x.\"full_name\",z.\"asset_type\",z.asset_id
															FROM \"Fp\" z 
															LEFT JOIN \"Fc\" y ON z.\"asset_id\" = y.\"CarID\"
															LEFT JOIN \"Fa1_FAST\" x ON z.\"CusID\" = x.\"CusID\"
															WHERE z.\"asset_type\" = '1'
														  ) b 
												on b.\"IDNO\" = a.ref_id
												where a.id_menu='P05' and b.\"asset_type\" = '1'
												
												UNION ALL
												
												SELECT a.time_open, a.ref_id,b.\"car_regis\" as \"C_REGIS\", b.full_name ,b.asset_id
												FROM \"LogsAnyFunction\" a
												LEFT JOIN (	
															SELECT z.\"IDNO\",y.\"car_regis\",x.\"full_name\",z.\"asset_type\",z.asset_id
															FROM \"Fp\" z 
															LEFT JOIN \"FGas\" y ON z.\"asset_id\" = y.\"GasID\"
															LEFT JOIN \"Fa1_FAST\" x ON z.\"CusID\" = x.\"CusID\"
															WHERE z.\"asset_type\" = '2'
														  ) b 
												on b.\"IDNO\" = a.ref_id
												where a.id_menu='P05' and b.\"asset_type\" = '2'
												
												ORDER BY \"time_open\" DESC limit 15
											"); 
						$i=0;						
						while($refindlist = pg_fetch_array($sqlfindlist)){	
							$idno = $refindlist['ref_id'];
							$careregis = $refindlist['C_REGIS'];
							$fullname = $refindlist['full_name'];
							$carid = $refindlist['asset_id'];
							$i++;
							if($i%2==0){
								echo "<tr bgcolor=#EED8AE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EED8AE';\" align=center>";
							}else{
								echo "<tr bgcolor=#FFE7BA onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFE7BA';\" align=center>";
							}
							echo "
									
									
									<td align=\"center\" onclick=\"javascript:popU('frm_viewcuspayment.php?idno_names=$idno&carid=$carid','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')\" style=\"cursor:pointer\"><u>".$idno."</u></td>
									<td align=\"center\">".$careregis."</td>	
									<td align=\"left\">".$fullname."</td>
									<td align=\"center\">".$refindlist['time_open']."</td>							
								 </tr>";
						}
				?>				
				</table> 
			</fieldset>
		 </td>
	 
	</tr>
</table>	 
<script type="text/javascript">
/*
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
        return "gdata_old.php?q=" + this.value;
    });    
}    
 
make_autocom("idno","idno_names");
*/
</script>

</body>
</html>