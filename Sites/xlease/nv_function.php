<?php
// Header Section ------------------- START ------------------------
session_start();
//include("../config/config.php");
// Header Section ------------------- END ------------------------


// Coding Section ------------------- START ------------------------

/*

Function: nv_correct_TranIDRef2()
Last modified: 2011-08-16

คำอธิบาย
-------------------------------------------------------
ใช้ในการ convert TranIDRef2 ที่มีตัวอักษร หรือ - ปนอยู่เนื่องจากตัวถังรถที่นำมาใช้ในการ gen code ทำมาจาก
กรณีเป็นเลขตัวถังรถ TAXI ที่เป็น TOYOTA ถ้าเป็นรถอื่นอ่านจะมี ตัวอักษรหรือ - เข้ามาปน ซึ่งผิดหลักของ bank ที่
Ref ID จะต้องเป็นตัวเลขเท่านั้น

หลักการทำงาน
-------------------------------------------------------
function จะทำการ convert a-z & A-Z & - ให้่เป็นตัวเลขอื่นๆ โดยแปลง a-z, A-Z => 0 และ - => 9

*/
function nv_correct_TranIDRef2($IDRef2){
	$idref = strtr( $IDRef2, "-", "9" );
	$change = str_split($idref);

	for($j=0;$j<sizeof($change);$j++){
		$num = $change[$j];
		if(!ereg("[0-9]",$change[$j])){
			$num = 0;	
		}
		$result = $result.$num;
	}
return $result;
}
// Coding Section ------------------- END ------------------------


// Tailer Section ------------------- START ------------------------

// Tailer Section ------------------- END ------------------------ 
?>
