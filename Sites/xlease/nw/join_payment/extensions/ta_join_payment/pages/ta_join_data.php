<?php
if($readonly=='t'){ //กรณีขอดูประวัติ จะไม่กำหนด deleted ='0' เนื่องจากถ้าเป็นรายการอนุมัติยกเลิก deleted ='1'
	$condition="";
}else{
	$condition="and deleted ='0'";
}

$query5 = "SELECT cpro_name,idno,car_license,car_license_seq,start_pay_date,carid,staff_check,cancel,id,note,approve_status FROM \"VJoinMain\" WHERE id='$id' $condition";					

$sql_query5 = pg_query($query5);	
while($sql_row5 = pg_fetch_array($sql_query5))
{				
	$cpro_name = $sql_row5['cpro_name'];
	$IDNO = $sql_row5['idno'];
	$car_license = $sql_row5['car_license'];
	$car_license_seq = $sql_row5['car_license_seq'];
	$start_pay_date2 = $sql_row5['start_pay_date'];
	$carid=$sql_row5["carid"];
	$app_st = $sql_row5['approve_status'];
	$staff_check  = $sql_row5['staff_check'];
	$cancel=$sql_row5['cancel'];
	$id = $sql_row5['id'];
	$id2 = $sql_row5['id'];
	$note = $sql_row5['note'];
}
					
$start_ta_join_date2 = date_ch_form_m($start_pay_date2);	
$start_ta_join_date = date_ch_form_c($start_pay_date2);	
	
$sql_query5=pg_query("select v.full_name,v.\"C_REGIS\",v.\"P_FDATE\",v.\"P_TOTAL\",v.\"P_ACCLOSE\" from \"VJoin\" v WHERE v.\"IDNO\" = '$IDNO' ");
if($sql_row5 = pg_fetch_array($sql_query5))
{	
	$start_contract_date  = date_ch_form_c($sql_row5['P_FDATE']);
	$car_month = $sql_row5['P_TOTAL'];
	$P_ACCLOSE = $sql_row5["P_ACCLOSE"];
	
	if($cancel==0 && $P_ACCLOSE=='f'){	 
		$car_license=$sql_row5["C_REGIS"];
		$cpro_name= $sql_row5['full_name'];	
	}
}				
?>

<?php 
if($cancel!='0'){
	echo "<center><h2><font color=red> เลขทะเบียนรถยนต์นี้ถูกยกเลิกไปแล้ว </font></h2></center>";
}?>	
<table border="0" align="center" cellpadding="1" cellspacing="1" >
<tr bgcolor="#66CCFF">
	<td align="center" width="150"><font><b>ข้อมูลเข้าร่วม  </b></font></td>
	<td width="300"><div style="text-align:right;">
	<?php 
	if($sp==1){?>
		<input value="แก้ไข" type="button" name="แก้ไข" onclick="javascript:editC()" id="แก้ไข" />
	<?php 
	}else{
	/*******ตรวจสอบว่ารายการนี้ได้ถูกยกเลิกหรือรออนุมัติยกเลิกอยู่หรือไม่ ถ้าใช่จะไม่สามารถขอยกเลิกได้อีก****/
		//ตรวจสอบว่าเลขที่สัญญานี้มีการรออนุมัติยกเลิกหรือไม่
		$qrychkapp=pg_query("select * from \"ta_join_main_delete_temp\" where \"id\"='$id' and \"appStatus\"='2'");
		if(pg_num_rows($qrychkapp)>0){ //แสดงว่ากำลังรออนุมัติยกเลิกอยู่
			echo "<div style=\"font-weight:bold;color:red;\">สัญญาเข้าร่วมนี้อยู่ระหว่างการขออนุมัติยกเลิก</div>";
		}else{
			if($readonly!='t'){ //กรณีขอดูข้อมูลอย่างเดียวจะไม่สามารถคลิกปุ่มนี้ได้
				//ตรวจสอบว่าเลขที่สัญญานี้มีการยกเลิกก่อนหน้านี้หรือไม่ ถ้า deleted=1 คือยกเลิกแล้ว
				$qrychk=pg_query("select * from \"ta_join_main\" where \"id\"='$id' and \"deleted\"='1'");
				if(pg_num_rows($qrychk)==0){  //แสดงว่าถูกยกเลิกไปแล้ว
					echo "<input type=\"button\" name=\"reqcancel\"  onclick=\"javascript:popU('frm_resultcancel.php?id=$id','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=400')\" value=\"ขอยกเลิกสัญญาเข้าร่วมนี้\" />";
				}
			}
		}
	} 
	?></div></td>
</tr>
<tr>
	<td bgcolor="#EEFBFA">
		<div align="right"><font color="green">รายละเอียดชุดลูกค้า : </font></div>
	</td>
    <td bgcolor="#EEFBFA"><div align="left">
        <?php print $cpro_name ?> <?php if($readonly!='t'){ ?><input type=button value=" พิมพ์ " onClick="javascript:window.print()" name="print"><?php } ?>
        <!--  <font color="red">*</font> <a href="../../ta_book_cus/pages/book_cus_view.php?cpro_id=<?php // print $cpro_id ?>" target=\"_blank\">ดูข้อมูล</a> </font> -->
        <?php 
		if($rights_ta_join_main_view){ ?>
			<input value="ดูข้อมูล" type="button" name="ดูข้อมูล" onclick="javascript:window.open('ta_join_payment_main.php?id=<?php print $id ?>&action=view','_blank')" id="ดูข้อมูล" />
        <?php 
		}  
		if($rights_ta_join_main_edit){ ?>
			<input value="แก้ไข" type="button" name="แก้ไข" onclick="javascript:window.open('ta_join_payment_main.php?id=<?php print $id ?>&action=edit','_blank')" id="แก้ไข" />
        <?php 
		}  
		if($rights_ta_join_main_del){?>
			<input value="ลบ" type="button" name="ลบ" onclick="JavaScript:if(confirm('ยืนยันการลบข้อมูล')==true){window.open('../processor_ta_join_main.php?id=<?php print $id ?>&form_name=del','_self')}" id="ลบ" />
        <?php 
		}  
		?>
        <!--<input value="พิมพ์สัญญา" type="button" name="พิมพ์" onclick="javascript:window.open('../../../../webreceive/APP_UI/Form/frmreportProfile.aspx2','_blank')" id="พิมพ์" /> -->
        <?php 
		if($staff_check=='1' and $readonly!='t'){ ?>
            <img title="ตรวจสอบแล้ว" src="../images/staff_check.png" width="20" height="20" /> <?php 
		}
		if($app_st=='4' and $readonly!='t'){ ?>
			<img src="../images/non_app.png" width="25" height="25" title="ยังไม่ได้รับการตรวจสอบ" />
		<?php 
		} 
		?>		
		</div>
	</td>  
</tr>
<tr>
	<td bgcolor="#EEFBFA"><div align="right">ทะเบียนรถยนต์ : </div></td>
	<td bgcolor="#EEFBFA"><div align="left">
		<?php print $car_license ; if($cancel!='0'){ if($cancel==1){$cancleType="ยกเลิกแล้ว-ถอดป้าย/เปลี่ยนสี";} else if($cancel==2){$cancleType="ยกเลิกแล้ว-รถยึด";} else if($cancel==3){$cancleType="ยกเลิกแล้ว-ขายคืน";}else if($cancel==4){$cancleType="ยกเลิกแล้ว-โอนสิทธิ์";} ?>
        <img title="ยกเลิกแล้ว" src="../images/cancel.jpg" width="20" height="20" /><?php echo $cancleType;?><?php 
		}?>	</div>
	</td>
</tr>
<tr>
	<td bgcolor="#EEFBFA"><div align="right">เลขสัญญา : </div></td>
	<td bgcolor="#EEFBFA"><div align="left">
		<u><a href="javascript:popU('../../../../../post/frm_viewcuspayment.php?idno=<?php print $IDNO ?>&carid=<?php echo $carid ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=768')"><?php print $IDNO; ?></a></u>
		</div>
	</td>
</tr>
<tr>
	<td bgcolor="#EEFBFA"><div align="right">วันที่เริ่มชำระเงิน(เช่าซื้อรถ) : </div></td>
	<td bgcolor="#EEFBFA"><div align="left"><?php print $start_contract_date ?></div></td>
</tr>
<tr>
	<td bgcolor="#EEFBFA"><div align="right">จำนวนงวด : </div></td>
	<td bgcolor="#EEFBFA"><div align="left"><?php print $car_month ?> งวด</div></td>
</tr>
<tr>
	<td bgcolor="#EEFBFA"><div align="right"><font color="red">เดือนที่เริ่มเก็บค่าเข้าร่วม : </font></div></td>
	<td bgcolor="#EEFBFA"><div align="left">
		<font color="red"> <?php print $start_ta_join_date2 ?></font>
		</div>
	</td>
</tr> 
<?php 
if($cancel!='0'){  ?>
<tr>
	<td bgcolor="#EEFBFA"><div align="right">สถานะ : </label></div></td>
	<td bgcolor="#EEFBFA"><div align="left">
		<input name="cancel" type="radio" id="cancel0" <?php if($action=='add'){ echo "checked" ;} ?> value="0" <?php if($cancel=='0'){ echo "checked" ;} ?>  disabled="disabled" />
		ยังเป็นลูกค้า
		<input type="radio" name="cancel" id="cancel1" value="1" <?php if($cancel=='1'){ echo "checked" ;} ?> disabled="disabled"/>
		ถอดป้าย/เปลี่ยนสี
		<input type="radio" name="cancel" id="cancel2" value="2" <?php if($cancel=='2'){ echo "checked" ;} ?> disabled="disabled"/>
		รถยึด
		<input type="radio" name="cancel" id="cancel3" value="3" <?php if($cancel=='3'){ echo "checked" ;} ?> disabled="disabled"/>
		ขายคืน 
		<input type="radio" name="cancel" id="cancel4" value="4" <?php if($cancel=='4'){ echo "checked" ;} ?> disabled="disabled"/>
		โอนสิทธิ์ </div>
	</td>
</tr>
<?php 
}
if($note!=''){ ?>
<tr>
	<td bgcolor="#EEFBFA"><div align="right">หมายเหตุ : </div></td>
	<td bgcolor="#EEFBFA"><div align="left"><?php print $note ?></div></td>
</tr>
<?php } ?>
</table>
