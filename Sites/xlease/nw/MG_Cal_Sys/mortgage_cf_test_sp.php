<?php
ob_start();
session_start();




// address.php - ไฟล์สำหรับ แสดงผลเกี่ยวกับข้อมูลที่อยู่


require_once("setup/sys_setup.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ยืนยันข้อมูล</title>
<script src="<?php echo $lo_ext_current_temp ?>scripts/jquery-1.3.2.min.js" type="text/javascript"></script>
<script type="text/javascript">  
function popup(url,name,windowWidth,windowHeight){       
    myleft=(screen.width)?(screen.width-windowWidth)/2:100;    
    mytop=(screen.height)?(screen.height-windowHeight)/2:100;      
    properties = "width="+windowWidth+",height="+windowHeight;   
    properties +=",scrollbars=yes, top="+mytop+",left="+myleft;      
    window.open(url,name,properties);   
}   


</script> 
<?php echo "

<link rel=\"stylesheet\" type=\"text/css\" href=\"".$lo_ext_current_temp."css/view.css\" media=\"all\">
<script type=\"text/javascript\" src=\"".$lo_ext_current_temp."scripts/view.js\"></script>
<script type=\"text/javascript\" src=\"".$lo_ext_current_temp."scripts/calendar.js\"></script>
</head>
<body id=\"main_body\" >
	
	<img id=\"top\" src=\"".$lo_ext_current_temp."pictures/top.png\" alt=\"\">
	
	<div id=\"form_container\">
		<div id=\"form_logon\">
			</br>
			<font color=white>
				ผู้ทำรายการ: [".$_SESSION['user_id']." - ".$_SESSION['user_div']."] ".$_SESSION['user_firstname']." ".$_SESSION['user_lastname']." (".$_SESSION['user_nick'].") <a href=\"../../../index.php?err=logout&add=".$_SESSION['user_id']."\">ออกจากระบบ</a>
			</font>
			</br><br>
		</div>

"; ?>
	<script type="text/javascript">



</script>
<?Php
	if($_POST[credit]=='' || $_POST[MinimumInsDate]==''
	|| $_POST[pdate]=='' || $_POST[cmort_length]=='' || $_POST[cmort_minpay]=='' || $_POST[cnet]==''
	)	 {// แสดงข้อผิดพลาด
	
	echo "<center><br><b>ท่านกรอกข้อมูลไม่ครบ</b><br><br>
	  <input id=\"saveForm\" class=\"button_text\" type=\"button\" name=\"submit\" value=\"กลับ\" onClick=\"history.go(-1);return true;\" style='width:100px; height:50px'/></center>
	";
	
	}else {


	
	echo "<center>";
	
	
	echo "<form action=\"../processor_mortgage.php\" method=\"post\">
	<div class=\"form_description\">
				<h2>สรุปข้อมูลคำขอสินเชื่อเงินกู้ Refinance</h2>
				
						</div>	";
	
	
		
	
	 ?>

     

	
		<table width="500" border="1" align="center" cellspacing="0">

		  <tr>
		    <td colspan="2" bgcolor="#66CCFF" ><strong><font color="blue">ข้อมูลสรุปเบื้องต้น</font></strong></td>
	      </tr>
		  <tr>
		    <td width="250" bgcolor="#F4F4F4" ><label class="description" for="element_3">
	        <div align="right">อัตราดอกเบี้ยปกติ : </div></td>
		    <td width="250" ><div align="left">
		      <?Php 
			  echo "<b><font color='red'> ".$_POST[cmort_int_normal]."</font>" ;
			  ?> %</div></td>
	      </tr>
        
          <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_3">
	        <div align="right">อัตราดอกเบี้ยผิดนัด : </div></td>
		    <td><div align="left">
		      <?Php 
			
	echo "<b><font color='red'> ".$_POST[cmort_int_penalty]."</font>" ;
	 ?>
		      
	        %</div></td>
	      </tr>
       
          
		  <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_4">
		      <div align="right">% ของค่าใช้จ่าย : 
	      
            </div></td>
		    <td><div align="left"><?Php echo "<b><font color='red'> ".$_POST[cmort_pfee]."</font>" ; ?> %</div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_5">
		      <div align="right">ค่าใช้จ่ายอื่นๆของยอดจดจำนองใหม่  : </div></td>
		    <td><div align="left"><?Php echo "<b><font color='red'> ".$_POST[cmort_otherfee_new]."</font>" ; ?> บาท</div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_6">
		      <div align="right"><span class="description">ยอดจดจำนองใหม่  : </span></div></td>
		    <td><div align="left"><?Php echo "<b><font color='red'> ".$_POST[cmort_credit_new]."</font>" ; ?> บาท</div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_6">
		      <div align="right"><span class="description">ลูกค้ารับเงินสุทธิจากยอดจดจำนองใหม่ิ  : </span></div></td>
		    <td><div align="left"><?Php echo "<b><font color='red'> ".$_POST[cnet_new]."</font>" ; ?> บาท</div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_6">
		      <div align="right"><span class="description">ยอดจดจำนองเดิม  : </span></div></td>
		    <td><div align="left"><?Php echo "<b><font color='red'> ".$_POST[cmort_credit_old]."</font>" ; ?> บาท</div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_3">
	        <div align="right">วงเงินจดจำนองรวม  : </div></td>
		    <td><div align="left"><?Php echo "<b><font color='red'> ".$_SESSION['credit']."</font>" ; ?> บาท</div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_6">
		      <div align="right"><span class="description">ลูกค้ารับเงินสุทธิ  : </span>
		        
            </div></td>
		    <td><div align="left"><?Php echo "<b><font color='red'> ".$_POST[cnet]."</font>" ; ?>
	        บาท</div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_7">
		      <div align="right">วันเริ่มสัญญา : 
	       
            </div></td>
		    <td><div align="left"><?Php echo "<b><font color='red'> ".$_POST[MinimumInsDate]."</font>" ; ?>
	        </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_3">
	        <div align="right">จ่ายทุกวันที่ :</div></td>
		    <td><div align="left"><?Php echo "<b><font color='red'> ".$_POST[pdate]."</font>" ; ?> ของทุกเดือน </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_3">
		      <div align="right">เริ่มจ่ายวันที่ :</div></td>
		    <td><div align="left"><?Php echo "<b><font color='red'> ".$_POST[pdate]."/$_POST[mm01]/".($_POST[yy01]+543)."</font>" ; ?> </div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4"><label class="description" for="element_8">
		      <div align="right">ระยะเวลาในการจ่ายคืนสินเชื่อ  :
            
            </div></td>
		    <td><div align="left"><?Php echo "<b><font color='red'> ".$_SESSION['length']."</font>" ; ?>
	        เดือน</div></td>
	      </tr>
		  <tr>
		    <td bgcolor="#F4F4F4" ><label class="description" for="element_3">
		      <div align="right">จำนวนเงินขั้นต่ำที่ต้องจ่ายต่อเดือน :
		     
            </div></td>
		    <td><div align="left"><?Php echo "<b><font color='red'> ".$_SESSION['min_pay']."</font>" ; ?> บาท</div></td>
	      </tr>
		  </table>
<p><br>		
<?php if($_POST[mortgage_edit]=='')	 {
	
	?>
<input type="hidden" name="form_name" value="cmort_add_db" />
<?php }else { ?>
	<input type="hidden" name="form_name" value="cmort_update_db" />
    <input type="hidden" name="cmort_id" value="<?Php print $_POST['cmort_id'] ?>" />
   
	<?php } ?>

			
            <input type="hidden" name="cmort_credit" value="<?Php print $_POST['credit'] ?>" />
            <input type="hidden" name="cmort_pfee" value="<?Php print $_POST['cmort_pfee'] ?>" />
            <input type="hidden" name="cmort_otherfee" value="<?Php print $_POST['cmort_otherfee'] ?>" />
            <input type="hidden" name="cmort_cnet" value="<?Php print $_POST['cnet'] ?>" />
            <input type="hidden" name="MinimumInsDate" value="<?Php print $_POST['MinimumInsDate'] ?>" />
			<input type="hidden" name="cmort_pdate" value="<?Php print $_POST['pdate'] ?>" />
            <input type="hidden" name="cmort_length" value="<?Php print $_POST['cmort_length'] ?>" />
            <input type="hidden" name="cmort_int_normal" value="<?Php print $_POST['cmort_int_normal'] ?>" />
            <input type="hidden" name="cmort_int_penalty" value="<?Php print $_POST['cmort_int_penalty'] ?>" />
			<input type="hidden" name="cmort_minpay" value="<?Php print $_POST['cmort_minpay'] ?>" />
            <input type="hidden" name="cmort_app_id" value="<?Php print $_POST['cmort_app_id'] ?>" />
        <input id="saveForm" class="button_text" type="reset" name="Reset" value="กลับ" onClick="history.go(-1);return true;" style='width:100px; height:50px'/>
	</p>
	</form>
		
        </center>

<?Php
	}
echo "
			<div class=form_description></div>
			

	  
	
</div>
	<img id=\"bottom\" src=\"".$lo_ext_current_temp."pictures/bottom.png\" alt=\"\">
</body>
</html>

";
ob_end_flush();
?>