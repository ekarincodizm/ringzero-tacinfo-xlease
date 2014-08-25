<?php
include("../../config/config.php");


$id = $_GET['id'];

?>
<fieldset> 
				<div align="center">
					<div class="ui-widget">
			
<?php
	
							$qry_debt_due=pg_query("select c.\"tpID\", sum(b.\"typePayLeft\") as total, c.\"tpDesc\", b.\"contractID\" from thcap_temp_otherpay_debt b ,account.\"thcap_typePay\" c
where b.\"typePayID\" = c.\"tpID\" and b.\"debtStatus\"='1' and b.\"typePayID\" ='$id' group by c.\"tpID\",b.\"contractID\", c.\"tpDesc\" order by total desc  "); 
							$row_debt_due = pg_num_rows($qry_debt_due);
							//c.\"tpFullDesc\"
?>
							<div>
							
								<table class="ab" width="100%" border="0" cellSpacing="1" cellPadding="3" bgcolor="#999999">
									<thead>
									<tr style="font-weight:bold;" valign="top" bgcolor="#79BCFF" align="center">
                                    <th>ลำดับ</th>
                                    <th>เลขที่สัญญา</th>
                                    <th>ผู้กู้หลัก</th>
                                    <th>เบอร์มือถือ</th>
										<th>ประเภทรับชำระ</th>
	
										<th>ยอดรวมค้างชำระ</th>
										
									</tr>
									</thead>
<?php
							
									if($row_debt_due == 0)
									{
										echo "<tr><td colspan=6 bgcolor=\"#E9F8FE\" align=center height=50><b>-ไม่พบข้อมูล-</b></td></tr>";
									}
									else
									{	
										$i = 0;
										while($res_fc = pg_fetch_array($qry_debt_due))
										{
											$i++;
											$contractID =trim($res_fc["contractID"]);
											$typePayID =trim($res_fc["tpID"]);
											$total =trim($res_fc["total"]);
											$tpDesc =trim($res_fc["tpDesc"]);
											//$tpFullDesc =trim($res_fc["tpFullDesc"]);
											$qry_namemain=pg_query("select thcap_fullname,\"A_MOBILE\" from \"vthcap_ContactCus_detail\"
	where \"contractID\" = '$contractID' and \"CusState\" ='0'");
	if($resnamemain=pg_fetch_array($qry_namemain)){
		$name3=trim($resnamemain["thcap_fullname"]);
		$A_MOBILE=trim($resnamemain["A_MOBILE"]);
	}
											if($i%2==0){
												echo "<tr class=\"odd2\" >";
											}else{
												echo "<tr class=\"even2\" >";
											}
							
											echo "<td align=\"center\">$i</th>";
											echo "<td align=\"center\"><a href=\"javascript:popU('../thcap_installments/frm_Index.php?idno=$contractID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=740')\"><FONT COLOR=#0000FF><u>$contractID</u></FONT></a></th>";
											echo "<td align=\"left\">$name3</th>";
											echo "<td align=\"left\">$A_MOBILE</th>";
											echo "<td align=\"left\">$typePayID - $tpDesc</th>";
						
											echo "<td align=\"right\">".number_format($total,2)."</th>";
											echo "</tr>";
											
											$sum_total += $total; 
										}
										
										echo "<tr bgcolor=\"#ffb0e3\">";
										echo "<td align=\"right\" COLSPAN=\"5\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>รวมทั้งสิ้น</b></th>";
										echo "<td align=\"right\"> <b>".number_format($sum_total,2)." </b></th>";
										echo "</tr>";
									}
?>
								</table>

							</div>
					</div>
				</div>
			</fieldset>