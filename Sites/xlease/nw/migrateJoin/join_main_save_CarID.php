<?php
$excel = $_REQUEST[excel];

set_time_limit (0); 
ini_set("memory_limit","2048M"); 
include("config/config.php");

pg_query("BEGIN WORK");
$status = 0;
$num_add = 0;
?>
<?php if($excel==1)header("Content-Type: application/vnd.ms-excel");
if($excel==1)header('Content-Disposition: attachment; filename="join_main_ck.xls"'); ?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"

xmlns:x="urn:schemas-microsoft-com:office:excel"

xmlns="http://www.w3.org/TR/REC-html40">

<HTML>

<HEAD>

<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
<link href="js/jquery-ui-1.8.19.custom.css" rel="stylesheet" type="text/css"/>
<script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.19.custom.min.js" type="text/javascript"></script>

</HEAD><BODY>
<style type="text/css">

table.t2 tr:hover td {
	background-color:pink;
}

</style>
  <script>


 function car_save(i){
	
     document.getElementById('tr'+i).style.backgroundColor = "#97FFB9";
        $.post('CarSaveDB.php',{
            cmd: 'save',
            id_main: document.getElementById('id_main'+i).value,
            car_id: document.getElementById('CarID'+i).value,   
            car_license: document.getElementById('car_license'+i).value,
			contract_id: document.getElementById('contract_id'+i).value,
			id_body: document.getElementById('id_body'+i).value
        },
        function(data){
           alert(data);
        });
    }

	</script>

<br><button onClick="window.open('mg_join_main.php?excel=1')" style="width:160px">Excel</button>


<table border="0" cellpadding="0" cellspacing="0">

  <tr>
   
    <td style="vertical-align:top">
    
    <fieldset><legend>
    <h3> ค้นหาทะเบียนรถใน Join Main ระบบเก่า ไม่เจอใน Fc  </h3>
    </legend>   
    <TABLE BORDER="0" class="t2" cellpadding="1" cellspacing="1" style="vertical-align:top;" x:str>
<Tr bgcolor="#33CCFF">
<Th style="height:30px"><b>ลำดับ</b></Th>
<Th><b>เลขที่สัญญา</b></Th>
<Th><b>ทะเบียนรถยนต์</b></Th>
<Th><b>เลขตัวถัง</b></Th>
<Th><b>ชื่อ</b></Th>
<Th><b>นามสกุล</b></Th>
<Th><b>รหัสรถยนต์ CarID</b></Th>
<Th><b> บันทึก </b></Th>
</Tr>
<?php			

$test_sql=pg_query("select a.id,a.car_license, a.idno,a.cpro_name,b.id_body, a.deleted from public.\"ta_join_main\" a inner join ta_tal_1r4_mg.\"ta_join_main\" b on a.id= b.id  where a.CarID is null ");
$rowtest=pg_num_rows($test_sql);
$seq2=1;
while($result=pg_fetch_array($test_sql))
{
	$id_main=trim($result["id"]);
	$car_license=trim($result["car_license"]); //ตัดช่องว่าง ข้างหน้าและข้างหลังออก
	$contract_id=trim($result["idno"]);
	$cpro_name=trim($result["cpro_name"]); //ชื่อ-นามสกุล
	list($A_NAME,$A_SIRNAME)=explode(" ",$cpro_name,2); //แยกช่องว่าง เพื่อตัดชื่อ กับนามสกุล  แยกเป็น 2 คำ เท่านั้น ถึงแม้จะมีช่องว่างมากกว่า 1 
	$A_SIRNAME = trim($A_SIRNAME);
	
	$A_NAME = str_replace("นาย",'',$A_NAME) ;
	$A_NAME = str_replace("นางสาว",'',$A_NAME) ;
	$A_NAME = str_replace("นาง",'',$A_NAME) ;
	$id_body=trim($result["id_body"]);
	
										$deleted =$result["deleted"];

/*
if($id_card=="" || $id_card=="-" || $id_card=="- " || $id_card=="--" || strlen ($id_card)!=13){
$test_sql3=pg_query("select \"CusID\" from public.\"Fa1\" where \"A_NAME\" = '$A_NAME' and  \"A_SIRNAME\" = '$A_SIRNAME' ");	

}
else{
	
	$id_card2 = $id_card[0]." ".$id_card[1].$id_card[2].$id_card[3].$id_card[4]." ".$id_card[5].$id_card[6].$id_card[7].$id_card[8].$id_card[9]." ".$id_card[10].$id_card[11]." ".$id_card[12];
$test_sql3=pg_query("select a.\"CusID\" from public.\"Fa1\" a inner join  public.\"Fn\" b on a.\"CusID\" = b.\"CusID\"  where a.\"A_NAME\" = '$A_NAME' and  a.\"A_SIRNAME\" = '$A_SIRNAME' and b.\"N_IDCARD\"='$id_card2' ");
//echo "select a.\"CusID\" from public.\"Fa1\" a inner join  public.\"Fn\" b on a.\"CusID\" = b.\"CusID\"  where a.\"A_NAME\" = '$A_NAME' and  a.\"A_SIRNAME\" = '$A_SIRNAME' and b.\"N_IDCARD\"='$id_card2' ";
$rowtest3=pg_num_rows($test_sql3);
	if($rowtest3==0) //ถ้ายังไม่เจออีกให้ค้นหาเฉพาะ เลขบัตร
	$test_sql3=pg_query("select a.\"CusID\" from public.\"Fa1\" a inner join  public.\"Fn\" b on a.\"CusID\" = b.\"CusID\"  where b.\"N_IDCARD\"='$id_card2' ");

}
//echo "select a.\"CusID\" from public.\"Fa1\" a inner join  public.\"Fn\" b on a.\"CusID\" = b.\"CusID\"  where b.\"N_IDCARD\"='$id_card2'<br><br>";
		//if($A_NAME=="บุญสุข")
		//echo "select \"CusID\" from public.\"Fa1\" where \"A_NAME\" = '$A_NAME' and  \"A_SIRNAME\" = '$A_SIRNAME' <br>";
	$rowtest3=pg_num_rows($test_sql3);
	if($rowtest3==0){
*/
        if($seq2%2==0){
            echo "<TR id=\"tr$seq2\" bgcolor=\"#EDF8FE\">";
        }else{
            echo "<TR id=\"tr$seq2\" bgcolor=\"#D5EFFD\">";
        }
?>

  <TD><?php echo $seq2; ?></TD>
<TD><?php echo $contract_id; ?></TD>
<TD><?php echo $car_license; ?></TD>
<TD ><?php echo $id_body; ?></TD>
<TD><?php echo $A_NAME ?></TD>
<TD><?php echo $A_SIRNAME; ?></TD>

<TD><input type="text" id="CarID<?php echo $seq2; ?>"><input type="hidden" id="id_main<?php echo $seq2; ?>" value="<?php echo $id_main; ?>">
<input type="hidden" id="car_license<?php echo $seq2; ?>" value="<?php echo $car_license; ?>">
<input type="hidden" id="contract_id<?php echo $seq2; ?>" value="<?php echo $contract_id; ?>">
<input type="hidden" id="id_body<?php echo $seq2; ?>" value="<?php echo $id_body; ?>">
</TD>
<TD><button onClick="car_save(<?php echo $seq2; ?>)" style="width:100px;height:30px" > บันทึก </button></TD>
</TR>
		<?php		
		//$id_card2=null;	
		$seq2++;		
	
		
	} ?>

 </table></fieldset>
</td>
      </tr>
    </table>
</BODY>

</HTML>
