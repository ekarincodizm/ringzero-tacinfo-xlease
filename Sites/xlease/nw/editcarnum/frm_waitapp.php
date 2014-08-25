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
				<td colspan="5" align="left" style="font-weight:bold;">รายการที่รออนุมัติ</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF" align="center">
				<td>เลขที่สัญญา</td>
				<td>เลขตัวถังเก่า</td>
				<td>เลขตัวถังใหม่</td>
				<td>ผู้แก้ไขรายการ</td>
				<td>วันเวลาที่แก้ไข</td>
			</tr>
			<?php
			$qry_app=pg_query("select a.*,b.\"fullname\" as \"addUser\" from \"Carnum_Temp\" a
			left join \"Vfuser\" b on a.\"addUser\"=b.\"id_user\"
			where \"appStatus\" ='2' order by \"addStamp\" ");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$auto_id=$res_app["auto_id"];
				$IDNO=$res_app["IDNO"];
				$CARNUM_OLD=$res_app["CARNUM_OLD"]; //เลขตัวถังเก่า
				$CARNUM_NEW=$res_app["CARNUM_NEW"]; //เลขตัวถังที่แก้ไข
				$addUser=$res_app["addUser"]; //ชื่อผู้ทำการแก้ไข
				$addStamp=$res_app["addStamp"]; //วันเวลาที่แก้ไข
				$appUser=$res_app["appUser"]; //ชื่อผู้อนุมัติ
				
				$i+=1;
				if($i%2==0){
					echo "<tr class=\"odd\" align=center>";
				}else{
					echo "<tr class=\"even\" align=center>";
				}
				
			?>
				<td><span onclick="javascript:popU('../../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $IDNO;?></u></font></span></td>
				<td><?php echo $CARNUM_OLD; ?></td>
				<td><?php echo $CARNUM_NEW; ?></td>
				<td align="left"><?php echo $addUser; ?></td>
				<td><?php echo $addStamp; ?></td>			
			</tr>
			<?php
			} //end while
			if($nub == 0){
				echo "<tr><td colspan=5 align=center height=50><b>- ไม่พบรายการที่รออนุมัติ -</b></td></tr>";
			}
			?>
			</table>
		</div>
	</td>
</tr>
</table>

</body>
</html>