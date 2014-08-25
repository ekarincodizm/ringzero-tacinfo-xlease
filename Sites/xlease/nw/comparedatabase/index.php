<?php
set_time_limit(0);
session_start();
include("../../config/config.php");
$database1=$_POST["database1"];
$database2=$_POST["database2"];

$schema=$_POST["schema"];
$table=$_POST["table"];
$field=$_POST["field"];
$id=$_POST["id"];
$type=$_POST["type"];
$condition=$_POST["condition"];

if($schema != "public"){
    $public="$schema.";
}else{
	$public="";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>เปรียบเีทียบข้อมูล 2 Database</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<style>
	.textbox{
		text-align:center;
		color:#993300;
		background-color:#F9F2FF;
		font-weight:bold;
	}
</style>
<script language="javascript">
function checkdata() {
	var theMessage = "Please complete the following: \n-----------------------------------\n";
	var noErrors = theMessage

	if (document.form1.database1.value=="") {
		theMessage = theMessage + "\n -->  กรุณากรอก Database1";
	}
	if (document.form1.database2.value=="") {
		theMessage = theMessage + "\n -->  กรุณากรอก Database2";
	}
	if (document.form1.table.value=="") {
		theMessage = theMessage + "\n -->  กรุณากรอก Table";
	}
	if (document.form1.field.value=="") {
		theMessage = theMessage + "\n -->  กรุณากรอก field";
	}
	if (document.form1.id.value=="") {
		theMessage = theMessage + "\n -->  กรุณากรอก ID ของตาราง";
	}
	// If no errors, submit the form
	if (theMessage == noErrors) {
	return true;
	}else {
	// If errors were found, show alert message
		alert(theMessage);
		return false;
	}
}
</script>  
</head>
<body>
<form method="post" name="form1" action="index.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr><td align="center"><h2>เปรียบเทียบข้อมูล 2 Database</h2></td></tr>
	<tr>
		<td align="center" height="30">
			<input type="radio" name="condition" value="1" <?php if($condition=="" || $condition==1){ echo "checked"; }?>>แสดงรายการทั้งหมด
			<input type="radio" name="condition" value="2" <?php if($condition==2){ echo "checked"; }?>>แสดงเฉพาะรายการที่ต่างกัน
		</td>
	</tr>
	<tr align="center" height="100">
        <td style="font-weight:bold;">
		Database หลัก <input type="text" name="database1" value="<?php echo $database1;?>" class="textbox"> 
		Database รอง<input type="text" name="database2" value="<?php echo $database2;?>" class="textbox">
		Schemas 
		<select name="schema">
			<option value="account" <?php if($schema=="account"){ echo "selected"; }?>>account</option>
			<option value="carregis" <?php if($schema=="carregis"){ echo "selected"; }?>>carregis</option>
			<option value="corparate" <?php if($schema=="corparate"){ echo "selected"; }?>>corparate</option>
			<option value="gas" <?php if($schema=="gas"){ echo "selected"; }?>>gas</option>
			<option value="insure" <?php if($schema=="insure"){ echo "selected"; }?>>insure</option>
			<option value="letter" <?php if($schema=="letter"){ echo "selected"; }?>>letter</option>
			<option value="pmain" <?php if($schema=="pmain"){ echo "selected"; }?>>pmain</option>
			<option value="public" <?php if($schema=="public"){ echo "selected"; }?>>public</option>
			<option value="refinance" <?php if($schema=="refinance"){ echo "selected"; }?>>refinance</option>
		</select>
		Table <input type="text" name="table" value="<?php echo $table;?>" class="textbox">
		<br>Field <input type="text" name="field" value="<?php echo $field;?>" class="textbox">
		KEY ID <input type="text" name="id" value="<?php echo $id;?>" class="textbox">
		<input type="hidden" name="type" value="1">
		<input type="submit" value="ตกลง" onclick="return checkdata()"><br><br>
		
		<hr width="80%">
		<table width="30%" border=0>
		<tr>
			<td bgcolor="#F0E0FE" width="30"></td><td>รายการที่ต่างกัน</td><td bgcolor="#F9F2FF" width="30"></td><td>รายการที่ไม่ต่างกัน</td>
		</tr>
		</table>
        </td>
    </tr>
	<?php
	if($type==1){
	?>
	<tr>
		<td>
			<table width="600" border="0" cellspacing="1" cellpadding="1" style="margin-top:1px" align="center" bgcolor="#CCCCFF">
				<tr align="center" bgcolor="#D7A6FF">
					<th height="25" width="200">รหัส</th>
					<th width="200"><?php echo $database1;?></th>
					<th width="200"><?php echo $database2;?></th>
				</tr>
				<?php
				$conn_string = "host=". '172.16.2.251' ." port=5432 dbname=". $database1 ." user=". 'dev' ." password=". 'nextstep' ."";
				$dbconn1 = pg_connect($conn_string) or die("Can't Connect !");
				
				$query1=pg_query("select \"$field\",\"$id\" from $public\"$table\" order by \"$id\"");
				$nub=0;
				while($res1=pg_fetch_array($query1)){
					$s1_id=$res1["$id"];
					$s1_field=$res1["$field"];
					
					$conn_string = "host=". '172.16.2.251' ." port=5432 dbname=". $database2 ." user=". 'dev' ." password=". 'nextstep' ."";
					$dbconn2 = pg_connect($conn_string) or die("Can't Connect !");
					$query2=pg_query("select \"$field\",\"$id\" from $public\"$table\" where \"$id\"='$s1_id' order by \"$id\"");
					$num_rows=pg_num_rows($query2);
					if($num_rows==0){
						$s2_field="";
					}else{
						if($res2=pg_fetch_array($query2)){
							$s2_field=$res1["$field"];
						}
					}
					
					if($s1_field != $s2_field){
						echo "<tr align=center bgcolor=#F0E0FE><td>$s1_id</td><td>$s1_field</td><td>$s2_field</td></tr>";
						$nub++;
					}else{
						if($condition==1){
							echo "<tr align=center bgcolor=#F9F2FF><td width=50>$s1_id</td><td>$s1_field</td><td>$s2_field</td></tr>";
						}
					}
					
				}
				
				if($nub==0){
					echo "<tr bgcolor=#F9F2FF><td height=50 align=center colspan=3 ><b>ไม่พบรายการที่แตกต่างกัน</b></td></tr>";
				}
				?>
				<tr><td colspan="3"><b>รวมรายการที่ต่าง <U><?php echo $nub;?></U> รายการ</b></td></tr>
			</table>
		</td>
	</tr>
	<?php
	}
	?>
</table>          

</body>
</html>