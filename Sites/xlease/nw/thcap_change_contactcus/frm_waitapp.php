<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>

</head>
<body>

<table width="800" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
			<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
			<tr bgcolor="#FFFFFF">
				<td colspan="4" align="left" style="font-weight:bold;">รายการที่รออนุมัติ</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>เลขที่สัญญา</td>
				<td>ผู้แก้ไขรายการ</td>
				<td>วันเวลาที่แก้ไข</td>
				<td>รายละเอียด</td>
			</tr>
			<?php
			$qry_app=pg_query("select a.\"contractID\",b.\"fullname\" as \"addUser\",a.\"addStamp\" from \"thcap_ContactCus_Temp\" a
			left join \"Vfuser\" b on a.\"addUser\"=b.\"id_user\"
			where \"appStatus\" ='2' group by a.\"contractID\",b.\"fullname\",a.\"addStamp\" order by \"addStamp\" ");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$contractID=$res_app["contractID"]; //เลขที่สัญญา				
				$addUser=$res_app["addUser"]; //ชื่อผู้ทำการแก้ไข
				$addStamp=$res_app["addStamp"]; //วันเวลาที่แก้ไข
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
				
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<td align="left"><?php echo $addUser; ?></td>
				<td><?php echo $addStamp; ?></td>
				<td>	
					<img src="images/detail.gif" onclick="javascript:popU('show_approve.php?contractID=<?php echo $contractID?>&waitapp=yes','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor:pointer;">
				</td>
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=4 align=center height=50><b>- ไม่พบรายการที่รออนุมัติ -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>