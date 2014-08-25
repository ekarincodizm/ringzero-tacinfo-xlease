<?php
//*****ใช้สำหรับดึงเดือนแบบ combo box ให้เลือกตั้งแต่ ม.ค.-ธ.ค. value จะเท่ากับ 01-12 ******//
include "../function/nameMonth.php";

echo "<select name=month id=month>";
for($i=1;$i<=12;$i++){
	if($i<10){
		$j='0'.$i;
	}else{
		$j=$i;
	}
	$chk="";
	
	//กรณีมีการเลือกเดือนแล้ว ต้องการให้แสดงเดือนที่เลือก ให้กำหนดตัวแปร $month เท่ากับเดือนที่ต้องการให้แสดง
	if($j==$month){
		$chk="selected";
	}
	echo "<option value=$j $chk>".nameMonthTH($j)."</option>";
}
echo "</select>";
?>