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

</HEAD><BODY>
<style type="text/css">

table.t2 tr:hover td {
	background-color:pink;
}

</style>


<br><button onClick="window.open('split_name_join_main.php?excel=1')" style="width:160px">Excel</button>


    <fieldset><legend>
    <h3> แยกคำนำหน้า ชื่อ-นามสกุล ble ta_join_main</h3>
    </legend>   
    <TABLE BORDER="0" class="t2" cellpadding="1" cellspacing="1" style="vertical-align:top;" x:str>
<Tr bgcolor="#33CCFF">
<Th><b>ลำดับ</b></Th>
<Th><b>คำนำหน้า</b></Th>
<Th><b>ชื่อ</b></Th>
<Th><b>นามสกุล</b></Th>

</Tr>
<?php			

$test_sql=pg_query("select id,cpro_name from \"ta_join_main\" order by id ");
$rowtest=pg_num_rows($test_sql);
$seq2=0;
while($result=pg_fetch_array($test_sql))
{
	$id=trim($result["id"]);

	$cpro_name=trim($result["cpro_name"]); //ชื่อ-นามสกุล



$j = 0;
$i = 0;

$cpro_name = str_replace("นาย ",'นาย',$cpro_name) ;
	$cpro_name = str_replace("นางสาว ",'นางสาว',$cpro_name) ;
	$cpro_name = str_replace("นาง ",'นาง',$cpro_name) ;
	
list($name1,$surname)=explode(" ",$cpro_name,2);
$p1 = substr($name1,0,9);
$p2 = substr($name1,0,18);
//echo $p1."<br>";
//แยกคำนำหน้าออกจากชื่อ
if ($p1=="นาย")
{
$j = 9;

$per =" prefix='นาย', ";
$prefix = "นาย";
}
else if ($p2 == "นางสาว")
{
$j = 18;
$per =" prefix='นางสาว', ";
$prefix = "นางสาว";
}
else if ($p1 == "นาง")
{
$j = 9;
$per =" prefix='นาง', ";
$prefix = "นาง";
}
//แยกชื่อออกจากคำนำหน้า
if ($p1=="นาย")
{
$i = 9;
}
else if ($p2 == "นางสาว")
{
$i = 18;
}
else if ($p1 == "นาง")
{
$i = 9;
}
//$per = substr($name1,0,$j);
$name = substr($name1,$i);
$surname = trim($surname);
		$query =	"UPDATE ta_join_main SET $per
										f_name='$name',
										l_name='$surname' where id  ='$id' ";
		

		if($sql_query=pg_query($query))
			{
				$seq2++;		
				}
			else
			{
				$status++;
			}

        if($seq2%2==0){
            echo "<TR bgcolor=\"#EDF8FE\">";
        }else{
            echo "<TR bgcolor=\"#D5EFFD\">";
        }
?>
<TD><?php echo $seq2; ?></TD>
<TD><?php echo $prefix; ?>&nbsp;</TD>
<TD><?php echo $name; ?>&nbsp;</TD>
<TD><?php echo $surname; ?>&nbsp;</TD>

</TR>
		<?php		
		$per = "";	
	$prefix= "";	
	
	} ?>

 </table></fieldset>
<br><br>
    <fieldset><legend>
    <h3> แยกคำนำหน้า ชื่อ-นามสกุล Table ta_join_main_bin</h3>
    </legend>   
    <TABLE BORDER="0" class="t2" cellpadding="1" cellspacing="1" style="vertical-align:top;" x:str>
<Tr bgcolor="#33CCFF">
<Th><b>ลำดับ</b></Th>
<Th><b>คำนำหน้า</b></Th>
<Th><b>ชื่อ</b></Th>
<Th><b>นามสกุล</b></Th>

</Tr>
<?php			

$test_sql=pg_query("select id,cpro_name from \"ta_join_main_bin\" order by id ");
$rowtest=pg_num_rows($test_sql);
$seq2=0;
while($result=pg_fetch_array($test_sql))
{
	$id=trim($result["id"]);

	$cpro_name=trim($result["cpro_name"]); //ชื่อ-นามสกุล



$j = 0;
$i = 0;

$cpro_name = str_replace("นาย ",'นาย',$cpro_name) ;
	$cpro_name = str_replace("นางสาว ",'นางสาว',$cpro_name) ;
	$cpro_name = str_replace("นาง ",'นาง',$cpro_name) ;
	
list($name1,$surname)=explode(" ",$cpro_name,2);
$p1 = substr($name1,0,9);
$p2 = substr($name1,0,18);
//echo $p1."<br>";
//แยกคำนำหน้าออกจากชื่อ
if ($p1=="นาย")
{
$j = 9;

$per =" prefix='นาย', ";
$prefix = "นาย";
}
else if ($p2 == "นางสาว")
{
$j = 18;
$per =" prefix='นางสาว', ";
$prefix = "นางสาว";
}
else if ($p1 == "นาง")
{
$j = 9;
$per =" prefix='นาง', ";
$prefix = "นาง";
}
//แยกชื่อออกจากคำนำหน้า
if ($p1=="นาย")
{
$i = 9;
}
else if ($p2 == "นางสาว")
{
$i = 18;
}
else if ($p1 == "นาง")
{
$i = 9;
}
//$per = substr($name1,0,$j);
$name = substr($name1,$i);
$surname = trim($surname);
		$query =	"UPDATE ta_join_main_bin SET $per
										f_name='$name',
										l_name='$surname' where id  ='$id' ";
		

		if($sql_query=pg_query($query))
			{
				$seq2++;		
				}
			else
			{
				$status++;
			}

        if($seq2%2==0){
            echo "<TR bgcolor=\"#EDF8FE\">";
        }else{
            echo "<TR bgcolor=\"#D5EFFD\">";
        }
?>
<TD><?php echo $seq2; ?></TD>
<TD><?php echo $prefix; ?>&nbsp;</TD>
<TD><?php echo $name; ?>&nbsp;</TD>
<TD><?php echo $surname; ?>&nbsp;</TD>

</TR>
		<?php		
		$per = "";	
	$prefix= "";	
	
	} ?>

 </table></fieldset>
</BODY>

</HTML>
<?php
//}

if($status == 0){
	
   pg_query("COMMIT");
   echo "<br>แยกข้อมูลเรียบร้อยแล้ว";
   
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถแยกข้อมูลได้";
}

?>
