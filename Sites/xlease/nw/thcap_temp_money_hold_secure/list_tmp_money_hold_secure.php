<?php
include("../../config/config.php");
  
$money_type = pg_escape_string($_REQUEST['p_money_type']);
$date_sel = pg_escape_string($_REQUEST['p_date_sel']);
?>
<HTML> 
   <link type="text/css" rel="stylesheet" href="act.css"></link>
<?php 
if($money_type == "deposits") // ถ้าเป็นเงินมัดจำ
{
	$qry = "SELECT	
				\"contractID\", 
				SUM(\"debtAmt\") AS \"debtAmt\",
				(select sum(\"dcNoteAmtALL\") from account.\"thcap_dncn\" where \"contractID\" = \"thcap_v_receipt_otherpay\".\"contractID\" and \"subjectStatus\" = '3' and \"dcNoteStatus\" = '1' and \"dcNoteDate\"::date <= '$date_sel') AS \"amtrefund\"
			FROM 
				\"thcap_v_receipt_otherpay\" 
			WHERE  
				\"typePayID\" LIKE '%997' AND
				\"receiveDate\"::date <= '$date_sel'
			GROUP BY
				\"contractID\"
			ORDER BY 
				\"contractID\" ";
	$query_list = pg_query($qry);
	$num_row = pg_num_rows($query_list);
?>
	<table ALIGN="CENTER" border="0" cellpadding="1" cellspacing="0" width="75%">
		<tr>
			<td align="left" colspan="7">
				<font color="#6C7B8B"><b>- (THCAP) รายงานเงินพักรอตัดรายการ เงินค้ำประกันการชำระหนี้ เงินมัดจำ</b></font>
			</td>
		</tr>
		<tr bgcolor="#9FB6CD">
			<th align="center">เลขที่สัญญา</th>
			<th align="center">ประเภทเงิน</th>
			<th align="center">จำนวนเงินมัดจำ</th>
			<th align="center">จำนวนเงินที่คืน</th>
			<th align="center">จำนวนเงินคงเหลือ</th>
			<th align="center">วันที่ข้อมูล</th>
		</tr>
		<?php
		$i = 0;
		$sum_debtAmt = 0;
		$sum_amtrefund = 0;
		$sum_balance = 0;
		while($res_list = pg_fetch_array($query_list))
		{
			$i++;
			$contractID = $res_list["contractID"]; // เลขที่สัญญา
			$debtAmt = $res_list["debtAmt"];
			$amtrefund = $res_list["amtrefund"];
			$balance = $debtAmt - $amtrefund;
			
			$sum_debtAmt += $debtAmt;
			$sum_amtrefund += $amtrefund;
			$sum_balance += $balance;
			
			if($i%2==0){
				echo "<tr bgcolor=#B9D3EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B9D3EE';\" >";
			}else{
				echo "<tr bgcolor=#C6E2FF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#C6E2FF';\" >";
			}
			
			echo "<td align=\"center\"><font color=\"#0000FF\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><u>$contractID</u></a></font></td>";
			echo "<td align=\"center\">เงินมัดจำ</td>";
			echo "<td align=\"right\">".number_format($debtAmt,2)."</td>";
			echo "<td align=\"right\">".number_format($amtrefund,2)."</td>";
			echo "<td align=\"right\">".number_format($balance,2)."</td>";
			echo "<td align=\"center\">$date_sel</td>";
			echo "</tr>";
		}
		echo '<tr bgcolor=$FFFFCC>';
		echo "<td align=\"right\" colspan=\"2\"><b>รวมเป็นเงิน</b></td>";
		echo "<td align=\"right\"><b>".number_format($sum_debtAmt,2)."</b></td>";
		echo "<td align=\"right\"><b>".number_format($sum_amtrefund,2)."</b></td>";
		echo "<td align=\"right\"><b>".number_format($sum_balance,2)."</b></td>";
		echo "<td></td>";
		echo '</tr>';
		?>
	</table>
<?php
}
else
{ 
  $qry = "SELECT * FROM thcap_temp_money_hold_secure WHERE (moneytype = $money_type ) And (\"dataDate\" = '$date_sel') And (money > 0) ORDER By \"contractID\" ASC  ";
  $query_list = pg_query($qry);
  $num_row = pg_num_rows($query_list);
?>
<table ALIGN = CENTER border="0" cellpadding="1" cellspacing="0" width="75%">
  <tr>
	<td align="left" colspan="4">
			<font color="#6C7B8B"><b>- (THCAP) รายงานเงินพักรอตัดรายการ เงินค้ำประกันการชำระหนี้ เงินมัดจำ</b></font>
	</td>
	<td align = "right">
	   <input type="button" id="btnprint" value="พิมพ์ PDF" onclick="javascript:popU('rpt_tmp_money_hold_secure_pdf.php?money_type=<?php echo $money_type;?>&date_sel=<?php echo $date_sel; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=700')">
	
	</td>
   </tr>
  <tr bgcolor="#9FB6CD">
     
							<td  align="center">เลขที่สัญญา</td>
							<td  align="center">จำนวนเงิน</td>
							<td  align="center">ประเภทเงิน</td>
							<td  align="center">วันที่ข้อมูล</td>
							<td  align="center">วันที่สร้างข้อมูล</td>
							
   </tr>
   <!-- เริ่มต้นการแสดงข้อมูลในตาราง  -->
   <?php
     $Sum_Money = 0;
	 for($i=1;$i<=$num_row; $i++){ 
	    $data = pg_fetch_array($query_list); 
		$conidd = $data['contractID']; $Sum_Money+=$data['money'];
	   if($i%2==0){
		  echo "<tr bgcolor=#B9D3EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#B9D3EE';\" align=center>";
	   }else{
		  echo "<tr bgcolor=#C6E2FF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#C6E2FF';\" align=center>";
	   } // end if
	   // echo "<td>col l </td>"; 
	   echo "<td width = \"30\"><font color=\"#0000FF\"><a onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=$conidd','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><u>".$data['contractID']."</u></a></font></td>";
	   echo "<td ALIGN = RIGHT>".number_format($data['money'],2,'.',',')."</td>";
	   // echo "<td>".$data['moneytype']."</td>";
       if($data['moneytype'] == 998){
	      echo "<td>เงินพัก</td>";
	   }elseif($data['moneytype'] == 997){
	      echo "<td>เงินค้ำประกัน</td>";
	   }   
	   echo "<td  >".$data['dataDate']."</td>";
	   echo "<td>".$data['genDate']."</td>";
    } 
	echo '<tr bgcolor=$FFFFCC>';
	echo "<td><B>รวมเป็นเงิน </B></td>";
	echo "<td ALIGN = RIGHT>".number_format($Sum_Money,2,'.',',')."</td>";
	echo '</tr>';
	
   ?> 

   
   
   <!-- สิ้นสุดการแสดงข้อมูลในตาราง  -->
   
  </table>
</HTML>
<?php
}
?>