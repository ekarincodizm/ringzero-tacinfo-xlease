<?php
include("../../config/config.php");
include("../function/nameMonth.php");
$year=$_GET['year'];
?>
<script language=javascript>
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>

<fieldset>
<table width="750" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
	<tr align="center" bgcolor="#79BCFF">
		<th>รหัสผู้ใช้งาน</th>
		<th>ชื่อผู้ใช้งาน</th>
		<th>คำนำหน้า-ชื่อ-นามสกุล (ชื่อเล่น)</th>
		<th>ข้อมูลประจำเดือน</th>
		<th>จำนวนรายได้พิเศษ</th>
		<th>รายละเอียด</th>
	</tr>
	<?php
		$query=pg_query("select inc_userid,username,fullname,nickname,EXTRACT(MONTH FROM \"inc_date\") as month,sum(inc_money) as money from ta_user_incentive a
		left join \"Vfuser\" b on a.inc_userid=b.id_user
		where EXTRACT(YEAR FROM \"inc_date\")='$year'
		group by inc_userid,fullname,username,nickname,month order by inc_userid");	
		$numrows=pg_num_rows($query);
		
		while($result=pg_fetch_array($query)){
			$inc_userid=$result["inc_userid"]; //รหัสผู้ใช้งาน
			$username=$result["username"];
			$fullname=$result["fullname"];	
			$nickname =$result["nickname"];
			if($nickname!=""){
				$fullname="$fullname ($nickname)";
			}
			$month =$result["month"];
			if($month<10){
				$month='0'.$month;
			}
			$monthtxt=nameMonthTH($month);
			$money =$result["money"];
			$money=number_format($money,2);
		
			if($i%2==0){
				echo "<tr class=\"odd\" align=center >";
			}else{
				echo "<tr class=\"even\" align=center >";
			}
			echo "<td height=25>$inc_userid</td>";
			echo "<td align=left>$username</td>";
			echo "<td align=left>$fullname</td>";
			echo "<td>$monthtxt</td>";
			echo "<td align=right>$money</td>";
			echo "<td><img src=\"images/detail.gif\" onclick=\"javascript:popU('frm_Show_detail.php?inc_userid=$inc_userid&month=$month&year=$year','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=500')\" style=\"cursor:pointer\"></td>";
			echo "</tr>";
			
		}
		if($numrows==0){
			echo "<tr bgcolor=#FFFFFF height=50><td colspan=6 align=center>ไม่พบข้อมูล</td></tr>";
		}
	?>
</table>
</fieldset> 
			
						