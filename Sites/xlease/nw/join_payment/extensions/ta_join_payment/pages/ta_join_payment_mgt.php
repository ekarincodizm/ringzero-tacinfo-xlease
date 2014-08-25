<?php
include("../../../../../config/config.php");
session_start();
ob_start();
$config =1;// config =1 คือ Update ทุกครั้ง ที่เปิดหน้านี้
$user_id = $_SESSION["av_iduser"];
$add_date=nowDateTime(); //ดึงข้อมูลวันเวลาจาก server

require_once("../../sys_setup.php");

//$rights_ta_join_payment_add=1; 
 $rights_ta_join_payment_view=1;
 $id_p_user=$_SESSION["av_iduser"];
   $res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$id_p_user'");
   $res_userprofile=pg_fetch_array($res_profile);
   $_SESSION["fullname_user"] = $res_userprofile["fullname"];


$id=trim($_REQUEST["idno_names"]);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รับชำระเข้าร่วมพิเศษ</title>

<!--
<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-1.7.1.min.js" type="text/javascript"></script>

<script src="../<?php echo $lo_ext_current_temp ?>scripts/js/jquery-ui-1.8.19.custom.min.js" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="../<?php echo $lo_ext_current_temp ?>scripts/css/ui-lightness/jquery-ui-1.8.1.custom.css" />-->

<link type="text/css" href="../../../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />    
    <script type="text/javascript" src="../../../../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
<script src="../../../../../jqueryui/js/number.js" type="text/javascript"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
<script type="text/javascript">  
function popup(url,name,windowWidth,windowHeight){       
    myleft=(screen.width)?(screen.width-windowWidth)/2:100;    
    mytop=(screen.height)?(screen.height-windowHeight)/2:100;      
    properties = "width="+windowWidth+",height="+windowHeight;   
    properties +=",scrollbars=yes, top="+mytop+",left="+myleft;      
    window.open(url,name,properties);   
}   
 function MM_openbrWindow(theURL,winName,features) { 
		window.open(theURL,winName,features);
}

var wnd = new Array();
function popU(U,N,T){
    wnd[N] = window.open(U, N, T);
}

function closeAll(){
    for (i in wnd){
        wnd[i].close();
    }
}
function ck_null(){

	if(document.getElementById('idno').value==""){alert("กรุณาใส่ข้อความที่จะค้นหา!!");return false;}

}

</script> 
<?php echo "

<link rel=\"stylesheet\" type=\"text/css\" href=\"../".$lo_ext_current_temp."css/view.css\" media=\"all\">
<script type=\"text/javascript\" src=\"../".$lo_ext_current_temp."scripts/view.js\"></script>
<script type=\"text/javascript\" src=\"../".$lo_ext_current_temp."scripts/calendar.js\"></script>
</head>
<body id=\"main_body\" >
	
	<img id=\"top\" src=\"../".$lo_ext_current_temp."pictures/top.png\" alt=\"\">
	
	<div id=\"form_container\">
		<div id=\"form_logon\">
			</br>
			<font color=white>
				ผู้ทำรายการ: [".$_SESSION["av_iduser"]."] - ".$_SESSION["fullname_user"]." 
			</font>
			</br><br>
		</div>

	
	"; ?>
    <style type="text/css">

table tr:hover td {
	background-color:pink;
}

</style>
   <script type="text/javascript">
function cal_minpay(){

 //alert("aaa");
 $.post("cal/cal_minpay.php", { car_credit: document.getElementById('car_credit').value,
 car_month: document.getElementById('car_month').value,
 car_rate: document.getElementById('car_rate').value
  

  },
  function(data){
    document.getElementById('installment').innerHTML=data;
  });


 }
  function nCredit(){

 
 $.post("cal/number_credit.php", { car_credit: document.getElementById('car_credit').value

 

  },
  function(data){
    document.getElementById('car_credit').value=data;
  });


 }
   function nValue(){

 
 $.post("cal/number_value.php", { car_value: document.getElementById('car_value').value

 

  },
  function(data){
    document.getElementById('car_value').value=data;
  });


 }
$(document).ready(
     function() {
   var d = new Date();
var s = d.getDate();
var m = d.getMonth()+1;
var y = d.getFullYear();
    

	  $("#datepicker").datepicker({ dateFormat: 'yy-mm-dd',

     defaultDate: y+'-'+m+'-'+s,
     dayNames: ['อาทิตย์','จันทร์','อังคาร',
                        'พุธ','พฤหัสบดี','ศุกร์','เสาร์'],
     dayNamesMin: ['อา.','จ.','อ.','พ.','พฤ.','ศ.','ส.'],
     monthNames: ['มกราคม','กุมภาพันธ์','มีนาคม',
                        'เมษายน','พฤษภาคม','มิถุนายน',
                        'กรกฎาคม','สิงหาคม','กันยายน',
                        'ตุลาคม','พฤศจิกายน','ธันวาคม'],
     monthNamesShort: ['ม.ค.','ก.พ.','มี.ค.','เม.ย.',
                         'พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.',
                         'พ.ย.','ธ.ค.']
    });
	
     }
);

function cal_ar1(){

	 
 $.post("arrears.php", { pay_date: document.getElementById('datepicker').value,
 id: '<?php echo $id ?>',
 change_pay_type: $("#type1").val(),
 start_pay_date: document.getElementById('start_pay_date').value

  },
  function(data){
	  x = data.split('#');
     document.getElementById('arrears').value=x[0];
	 
	 document.getElementById('exp_d').innerHTML=x[1];
	 document.getElementById('expire_date').value=x[2];
	 document.getElementById('pay_type').value=x[3];
  });

  

 }
 
function cal_ar(){

	//alert(document.getElementById('pay_type').value)

   var amount = parseFloat(document.getElementById('amount1').value.replace(/,/g,'')); 

 $.post("../../../../join_cal/cal_arrears.php", { amount: amount,
 change_pay_type: $("#type1").val(),
 arrears: document.getElementById('arrears').value,
 pay_type: document.getElementById('pay_type').value,
 start_pay_date: document.getElementById('start_pay_date').value,
 expire_date: document.getElementById('expire_date').value
 


  },
  function(data){

		document.getElementById('cal_arrears').innerHTML=data;  

	  
  });


 }

  function updateOpener(){

	    var amount = parseFloat(document.getElementById('amount1').value.replace(/,/g,'')); 
 $.post("insert_join_payment_mgt.php", { amount: amount,
 amount: amount,
 change_pay_type: $("#type1").val(),
 pay_date: document.getElementById('datepicker').value,
 pay_type: document.getElementById('pay_type').value,
 id: '<?php echo $id ?>',
 reason: document.getElementById('reason').value



  },
  function(data){
//alert(data);
		if(data==0){alert('บันทึกรายการเรียบร้อยแล้ว!!');
		window.location.href='ta_join_payment_mgt.php?idno_names=<?php print $id ?>';
		
		
		}
		if(data!=0)alert('ไม่สามารถบันทึกรายการได้!!');

  });

	 
 }
 

	 </script>


        
     
        <style type="text/css">
        body center form table tr td div {
	color: black;
}
        </style>



     <fieldset><legend><B>ค้นหาข้อมูลค่าเข้าร่วม</B></legend>

 <form name="search" method="post" action="ta_join_payment_mgt.php" onSubmit="JavaScript:return ck_null();" >
<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr >
      <td ><center><b>IDNO,ทะเบียนรถ,ชื่อ/สกุล</b>
        <input name="idno_names" type="hidden" id="idno_names" value="<?php echo $_POST['h_arti_id']; ?>" />
        <input type="text" id="idno2" name="idno2" size="100" value="<?php echo $_POST['h_arti_id']; ?>">
        <input type="submit" name="submit" value="   ค้นหา   " ></center>
      </td>
   </tr>
</table>
</form>

</fieldset>
 
<script type="text/javascript">

function make_autocom(autoObj,showObj){
    var mkAutoObj=autoObj; 
    var mkSerValObj=showObj; 
    new Autocomplete(mkAutoObj, function() {
        this.setValue = function(id) {        
            document.getElementById(mkSerValObj).value = id;
        }
        if ( this.isModified )
            this.setValue("");
        if ( this.value.length < 1 && this.isNotClick ) 
            return ;    
        return "gdata.php?q=" + this.value;
    });    
}    
 
make_autocom("idno2","idno_names");
</script>
        <?php
		$config=$_REQUEST["config"];
	
		if($id!=""){
			$sp=1;
		include("ta_join_data.php");
		
			if($config=="")$config=1;
		
		
		


		if($_REQUEST["rf"]==1){?>
        
     
		<script type="text/javascript">
		window.location.href='ta_join_payment_mgt.php?idno_names=<?php print $id2 ?>&config=0&rf=0';
		</script>
		   <?php }?>
         <script type="text/javascript">  
		
		
           function editC(){

        location.href="frm_main.php?car_id_r=<?php print $carid ?>&idno=<?php print $IDNO ?>&action=edit&id=<?php print $id2 ?>&sp=1";
}
	</script>

<p><strong>ประวัติการชำระเงินค่าเข้าร่วม</strong> <input value="คำนวณทั้งหมดใหม่" type="button" name="คำนวณทั้งหมดใหม่" 
				   onclick="javascript:location.href='ta_join_payment_mgt.php?idno_names=<?php print $id2 ?>&config=0&rf=1';" > <input value="เรียงลำดับใหม่" type="button" name="เรียงลำดับใหม่" 
				   onclick="javascript:location.href='ta_join_payment_mgt.php?idno_names=<?php print $id2 ?>&config=0&rf=1&seq=1';" ></p>
<?php

if($_REQUEST["seq"]!=1)$query15 = "SELECT ta_join_payment_id,period_date,expire_date,change_pay_type FROM ta_join_payment WHERE id_main='$id2' and deleted ='0' and car_license_seq='$car_license_seq' order by period_date,pay_date,expire_date,id asc ";
else $query15 = "SELECT ta_join_payment_id,period_date,expire_date,change_pay_type FROM ta_join_payment WHERE id_main='$id2' and deleted ='0' and car_license_seq='$car_license_seq' order by pay_date,period_date,expire_date,id asc ";
	
		$sql_query15 = pg_query($query15);
		$num_rows15 = pg_num_rows($sql_query15);
		$er01=0;
		$n_ck = 0;
		$n_ch = 0;
		if($num_rows15>0){
			while($sql_row15 = pg_fetch_array($sql_query15))
				{
			
			$change_pay_type =	$sql_row15[change_pay_type];
			$expire_date =	$sql_row15[expire_date];
			if($expire_date==''  && $n_ck==0){
				//เมื่อ ไม่มี วันหมดอายุ ให้ไปดึง เดือนที่เริ่มชำระ - 1
				$exp_new3=pg_query("select join_date_diff_month('$start_pay_date2',1)");
            $expire_date=@pg_fetch_result($exp_new3,0);
			}
			$period_date =	$sql_row15[period_date];
			if($exp_old==""){
				
			$exp_old = $expire_date ;
			}
			
			//if($change_pay_type!=1){
				
			
			$exp_new2=pg_query("select join_date_add_month('$exp_old',1)");
            $exp_new=@pg_fetch_result($exp_new2,0);
	
				
				
		/*	}else{
				$n_ch=1;
				$exp_new = $exp_old;
				
			}
*/
if($change_pay_type==1){
	$n_ch=1;
	
}
	
			if($period_date!=$exp_new && $n_ck!=0){//ตั้งแต่ ปัจุบัน != ถึงของอันก่อน + 1 และไม่ทำครั้งแรก
			//echo $n_ck." ".$period_date." ".$exp_new."<br>";
				$er01 ++;
				}
				
			$exp_old = $expire_date ;
			$n_ck++;
			
			
			
				}
				$er01 -= $n_ch; //ถ้า จ่ายแรกเข้า 5000 วันที่จะเท่ากัน ให้ - err ไป 1
				if($er01>0){
		?>

<h2><font color=red>ระวัง! การจ่ายมีการกระโดดข้ามเดือน โปรดแจ้งฝ่ายไอที</font></h2>
<?php } }

				if($config=='0'){ //เครียร์ค่า ตั้งแต่ ถึง เมื่อมีการกดคำนวณใหม่
	
	$query =	"UPDATE \"ta_join_payment\"  SET
					period_date  =NULL,
					expire_date  = NULL
					where \"id_main\" ='$id2' and deleted='0' ";		
				
$res_inss=pg_query($query);
	}
		$query2 = "SELECT amount FROM ta_join_payment WHERE id_main='$id2' and deleted ='0' and car_license_seq='$car_license_seq' ORDER BY pay_date ";
		$sql_query2 = pg_query($query2);
		while($sql_row2 = pg_fetch_array($sql_query2))
				{			//$version = $sql_row2['version'];
				$version = 0;
			if($version=='0'){
	
			$amount_all = $amount_all + $sql_row2[amount];

			$last =  MKTIME(0,0,0,$mm2, '01', $yy2) ;			
			$now =  MKTIME(0,0,0,date("m"), '01', date("Y")) ;
		//$now =  MKTIME(0,0,0,'07', '01', '2011') ;
			$month = $now-$last;
	
		}else {
			if($_REQUEST["seq"]!=1)$query = "SELECT expire_date,pay_type,pay_ar FROM ta_join_payment WHERE id_main='$id2' and deleted ='0' and car_license_seq='$car_license_seq' ORDER BY period_date ";
else $query = "SELECT expire_date,pay_type,pay_ar FROM ta_join_payment WHERE id_main='$id2' and deleted ='0' and car_license_seq='$car_license_seq' ORDER BY pay_date ";
 		 
			//echo "1";
				$sql_query = pg_query($query);

	
				if($sql_row = pg_fetch_array($sql_query))
				{			
					
					$expire_date =	$sql_row[expire_date];
					
					$expire_date = date_ch_form_m($expire_date);
					$pay_type = $sql_row['pay_type'];
					$pay_ar = 	$sql_row['pay_ar'];
					
					
					
				}
				
				list($mm2,$yy2)=split("/",$expire_date);	
					// $yy2=$yy2-543;		

					 $last =  MKTIME(0,0,0,$mm2, '01', $yy2) ;			
					 $now =  MKTIME(0,0,0,date("m"), '01', date("Y")) ;
				//$now =  MKTIME(0,0,0,'07', '01', '2011') ;
					 $month = $now-$last;
				if($pay_type==0)
					$type=300;
				else
					$type=100;
	
					$month = round($month/60/60/24/30);	


					$arrears = ($month*$type)-$pay_ar;
				if($arrears<0){
					$arrears =0;
				}
				//if($arrears<0)
				//$arrears=0;
				}	

				}		$month_all = $amount_all/300;
		
		list($dd2,$mm2,$yy2)=split("/",$start_ta_join_date);	
		//	 $yy2=$yy2-543;		

			 $expire_date =  MKTIME(0,0,0,$mm2+$month_all,'01',$yy2) ;
			 $expire_date = date("m/Y", $expire_date); 
		list($mm3,$yy3)=split("/",$expire_date);
			// $expire_date = $mm3."/".($yy3+543) ;
			 $expire_date = $mm3."/".($yy3) ;
			 $period =  MKTIME(0,0,0,$mm3, '01', $yy3) ;			
			 $now =  MKTIME(0,0,0,date("m"), '01', date("Y")) ;
		//$now =  MKTIME(0,0,0,'07', '01', '2011') ;
			 $month = $now-$period;

			 $type = 300 ;
			 $month = round($month/60/60/24/30);	
			 $arrears = ($month*$type)-$pay_ar;
		  if($arrears<0){
			 $arrears =0;
			}
				?>
<table border="0" align="center" cellpadding="1" cellspacing="1" >

  <tr bgcolor="#CCCCFF">
    <td bgcolor="#66CCFF" height="30px" ><div align="center">&nbsp;ลำดับ&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;เลขที่ใบเสร็จ&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ทะเบียนรถ&nbsp;</div></td>
   <td bgcolor="#66CCFF"><div align="center">&nbsp;สัญญาเลขที่&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ชื่อลูกค้า&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;วันที่ชำระเงิน&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ประเภทชำระ&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;จำนวน&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ตั้งแต่&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ใช้ได้ถึง&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;จำนวนเดือน&nbsp;</div></td>

    <td bgcolor="#66CCFF" ><div align="center">&nbsp;หมายเหตุ &nbsp;</div></td>
  </tr>
  <?php
  if($_REQUEST["seq"]!=1)$query = "SELECT * FROM ta_join_payment WHERE id_main='$id2' and deleted='0' and car_license_seq='$car_license_seq' ORDER BY period_date,pay_date,expire_date,id asc ";
else $query = "SELECT * FROM ta_join_payment WHERE id_main='$id2' and deleted='0' and car_license_seq='$car_license_seq' ORDER BY pay_date,period_date,expire_date,id asc ";

  
				//echo $query ;
				$sql_query = pg_query($query);
	$i=1;
	$type = 300 ;
	$next_m = 1;
	$ck_amount=0;
	$ck_amount2=0;
				while($sql_row = pg_fetch_array($sql_query))
				{		
				
				$change_pay_type = $sql_row[change_pay_type];
				
				if($config==1){
				$period_date55 =	$sql_row[period_date];
				$expire_date55  =	$sql_row[expire_date];
				$amount_month55 =	$sql_row[amount_month];
				$pay_type55=	$sql_row[pay_type];
				}
				$ta_join_payment_id = $sql_row[ta_join_payment_id];
				if($change_pay_type==1 && $ck_amount!=1){
						$type = 100 ;	
						
						}	
				
					$pay_type = $sql_row[pay_type];	
				if($pay_type=='0'){
					$pay_type = "300/เดือน";
					$pay_type = $type."/เดือน";
					
						if($change_pay_type==1 && $ck_amount!=1){
							$pay_type  = "<font color=red>(ชำระค่าเข้าร่วม)</font>" ;
					
						}
				}
				else if($pay_type=='1'){
				if($change_pay_type==1 && $ck_amount!=1){
							$pay_type  = "<font color=red>(ชำระค่าเข้าร่วม)</font>" ;
					
						}else{
				$pay_type = "100/เดือน";
				$pay_type = $type."/เดือน";
						}
				}
				else if($pay_type=='2'){
				
					
						if($change_pay_type==1 && $ck_amount!=1){
							$pay_type  = "<font color=red>SP(ค่าเข้าร่วม)</font>" ;
					
						}else {
							$pay_type = "SP(".$type.")";
						}
				}$id =	$sql_row[id];
						$pay_date =	$sql_row[pay_date];
					$pay_date = date_ch_form_c($pay_date);
					
					//$expire_date =	$sql_row[expire_date];
					//$expire_date = date_ch_form_m($expire_date);
					if($change_pay_type==1 && $ck_amount!=1){
						$next_m = 0 ;}
						else {
							$next_m = 1;
						}
					 if($ck_period_date_first==''){
					 	list($dd,$mm,$yy)=split("/",$start_ta_join_date);
		 					 $period_date  = $mm."/".$yy ;
					//echo $period_date;
					 }
					else {
						list($mm4,$yy4)=split("/",$expire_date_pay);	
							// $yy4=$yy4-543;	

						$expire_date_pay =  MKTIME(0,0,0,$mm4+$next_m+$next_m4,'01',$yy4) ;
						$expire_date_pay = date("m/Y", $expire_date_pay);
						
						list($mm,$yy)=split("/",$expire_date_pay);
							// $period_date   =$mm."/".($yy+543) ;
							 $period_date   =$mm."/".($yy) ;
						}
							 $ck_period_date_first=1;
					//$period_date = date_ch_form_m($period_date);
						if(($i==1) && ($change_pay_type==1 && $ck_amount!=1))	{
							$next_m4=-1;
					}else{
						$next_m4=0;
						}
						$update_datetime =$sql_row[update_datetime];
					
					list($aa,$bb)=split(" ",$update_datetime);
					list($yy,$mm,$dd)=split("-",$aa);
						// $aa  = $dd."/".$mm."/".($yy+543) ;
						$aa  = $dd."/".$mm."/".($yy) ;
						 $amount =  $sql_row[amount];
						 $month_pay = ($amount/$type);
						 $month_pay1 = ($amount/$type)-1;
					if($change_pay_type==1 && $ck_amount!=1){
					   $month_pay=0;
					   $month_pay1=0;
					   $ck_amount=1;
					}
	
			if($type==300){
			//$amount = 1400;
				$error = ($amount%$type);
				if($error!=0){
				$error1 = $error; }
			
			} else if($type==100){
			//$amount = 250;
					  $error = ($amount%$type);
				if($error!=0){
					$error1 = $error; }
			}
			
		
			
		
		list($mm2,$yy2)=split("/",$period_date);	
			// $yy2=$yy2-543;		

			 $expire_date_pay =  MKTIME(0,0,0,$mm2+$month_pay1,'01',$yy2) ;
			 $expire_date_pay = date("m/Y", $expire_date_pay);
				list($mm,$yy)=split("/",$expire_date_pay);
			// $expire_date_pay   =$mm."/".($yy+543) ;
			 $expire_date_pay   =$mm."/".($yy) ;
			 
		 // if($sql_row[start_pay_date]=="0000-00-00"){
	  
		if($type==100){
						$pt=1;
							
						}	
						else if ($type==300) {
								$pt=0;
						}
						
							list($mm,$yy)=split("/",$period_date);
	//$yy=$yy-543;
		$period_date3="$yy-$mm-01";
		list($mm,$yy)=split("/",$expire_date_pay);
	//$yy=$yy-543;
	//echo $_SESSION['expire_date'];
		$expire_date3="$yy-$mm-01";	
	if($config==0){	
		$cmp = strstr($pay_type,"SP") ;
					
							 if($cmp==''){ //เปรียบเทียบไม่เจอ Sp
		
		//echo 1;
		
  $query4 =	"UPDATE ta_join_payment SET \"period_date\" = '$period_date3' ,
	amount_month  = '$month_pay' ,
	pay_type='$pt',
	expire_date = '$expire_date3',
	start_pay_date='$start_pay_date2',
	amount='".$sql_row[amount]."',
	pay='".$sql_row[pay]."'
	WHERE id='".$sql_row[id]."' ";
	
		$sql_query4 = pg_query($query4);
							 }else {
								
				  $query4 =	"UPDATE ta_join_payment SET \"period_date\" = '$period_date3' ,
	amount_month  = '$month_pay' ,
	
	expire_date = '$expire_date3',
	start_pay_date='$start_pay_date2',
	amount='".$sql_row[amount]."',
	pay='".$sql_row[pay]."'
	WHERE id='".$sql_row[id]."' ";
		$sql_query4 = pg_query($query4);				 
							 }
		//echo $query4;
  
  //}	 
//ACTIONLOG
		$sqlaction = pg_query("INSERT INTO action_log(id_user, action_desc, action_time) VALUES ('$user_id', '(TAL) รับชำระเข้าร่วมพิเศษ', '$add_date')");
//ACTIONLOG---
		 } // End Config =1
			 $discount = $sql_row[amount_discount];
			 if($discount!=0 && $change_pay_type ==1){
				 
			 $amount_show =  number_format($sql_row[amount])."<font color=red>*</font>";
			 }else {
				 $amount_show =  number_format($sql_row[amount]); 
			 }
			 
		if($config==1){
			$period_date = date_ch_form_m($period_date55);
			 $expire_date_pay = date_ch_form_m($expire_date55);
				$month_pay =	$amount_month55;

		

						
				
if($pay_type55=='1'){
	$pay_type = "100/เดือน";
}else if($pay_type55=='0'){
	$pay_type = "300/เดือน";
}else{
		$pay_type = "SP(".$type.")";
}
					
				
						if($change_pay_type==1 && $ck_amount55!=1){ //5000 ครั้งแรก
						if($pay_type55=='2'){
							$pay_type  = "<font color=red>SP(ค่าเข้าร่วม)</font>" ;
							
						}else{
							$pay_type  = "<font color=red>(ชำระค่าเข้าร่วม)</font>" ;
						}
					
						}
						if($change_pay_type==1){
							$ck_amount55 = 1;
						}
						
}
$qry_vcus=pg_query("select \"PayType\",\"O_BANK\" from \"FOtherpay\" WHERE  \"O_RECEIPT\"='".$sql_row[ta_join_payment_id]."' ");

$rows = pg_num_rows($qry_vcus);
if($rows > 0){
if($resvc=pg_fetch_array($qry_vcus)) {
	$note2 = " / ".$resvc['PayType'];
	$O_BANK = $resvc['O_BANK'];
}
}else{
	
	$qry_vcus=pg_query("select \"PayType\",\"O_memo\" from \"FOtherpayDiscount\" WHERE  \"O_RECEIPT\"='".$sql_row[ta_join_payment_id]."' ");

$rows = pg_num_rows($qry_vcus);
if($rows > 0){
if($resvc=pg_fetch_array($qry_vcus)) {
	$note2 = "ส่วนลด ".$resvc['O_memo'];
	
}
}
	
	
}
				?>
  <tr>
    <td bgcolor="#EEFBFA" height="30px" ><div align="center"><u><a title="หมายเหตุ" href="javascript:MM_openbrWindow('showDetailCall.php?id=<?php print $sql_row[id] ?>','','scrollbars=no,width=500,height=260, left = 0, top = 0')"><?Php print $i ?></a></u></div></td>
    <td bgcolor="#EEFBFA"><div align=left><u><a href="javascript:popU('ta_join_payment_detail.php?id=<?php print $id ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=768')"><?Php print $sql_row[ta_join_payment_id] ?></a></u></div></td>
    <td bgcolor="#EEFBFA"><div align="center"><?Php print $sql_row[car_license]; if($sql_row[car_license_seq]!=0) print "/".$sql_row[car_license_seq]; ?></div></td>
    <td bgcolor="#EEFBFA"><div align="center"><u><a href="javascript:popU('../../../../../post/frm_viewcuspayment.php?idno=<?Php print $IDNO ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=768')"><?Php print $sql_row[idno] ?></a></u></div></td>
    <td bgcolor="#EEFBFA"><div align="center"><?Php print $sql_row[cpro_name] ?></div></td>
    <td bgcolor="#EEFBFA"><div align="center"><?Php print $pay_date ?></div></td>
    <td bgcolor="#EEFBFA"><div align=left><?Php print $pay_type ?></div></td>
    <td bgcolor="#EEFBFA"><div align=right><?Php print $amount_show ?></div></td>
    <td bgcolor="#EEFBFA"><div align=left><?Php print $period_date ?></div></td>
    <td bgcolor="#EEFBFA"><div align=left><?Php print $expire_date_pay ?></div></td>
    <td bgcolor="#EEFBFA"><div align=center><?Php print $month_pay ?></div></td>
   
   
    <td bgcolor="#EEFBFA"><div align=left><textarea name="note" style="width:100px;height:20px;" id="note" readonly="readonly" ><?php 
if($sql_row[pay]!=""){
	print $sql_row[pay].$note2." ".$sql_row[note] ;
}else{
	print $O_BANK.$note2." ".$sql_row[note] ;
}

	/*
	$query7 = "SELECT * FROM ta_join_payment WHERE ta_join_payment_id ='".$sql_row[ta_join_payment_id]."' and  ta_join_payment_id  like 'TAJ-%' ";
	//echo $query7;
		$sql_query7 = pg_query($query7);
		$Num_Rows7 = pg_num_rows($sql_query7);*/
	
		 ?></textarea><a href="<?php if($rows==0){?>join_receipt_pdf.php?id=<?Php echo $sql_row[id]; }else { ?>
         ../../../../../ca/frm_recprint_THA.php?id=<?php echo $sql_row[ta_join_payment_id]; ?>&idno=<?php echo $sql_row[idno]; } ?>" target="_blank"><img title="พิมพ์ใบเสร็จ" src="../images/print-icon.png" width="20" height="20" /></a>
		 
		 <?php if($rights_ta_join_payment_del && $cancel=='0') { ?><a href="javascript:popup('../processor_ta_join_payment.php?id=<?Php echo $sql_row[id]; ?>&form_name=confirm_del','',900,400)"><img src="../images/del.png" width="20" height="20" /></a><?php } ?></div></td>
  </tr>
  <?php 
  
  	if($change_pay_type==1 && $ck_amount==1){
					  
					   $ck_amount2=1;
					}
  

  
  
  
  $i++;
  } ?>
    <tr>
    <td colspan="14"  bgcolor="#EEFBFA"><div align="right">
      <?Php 
	 /* 
	   $query9 = "SELECT * FROM $dbtb_ta_images WHERE img_id='".$ta_join_payment_id."' AND img_from_type LIKE 'payment_image%' ";
	//   echo $query9;
		$sql_query9 =  pg_query($query9);
		$num_rows9 = pg_num_rows($sql_query9);
		//echo $num_rows ;
		  
		*/				
									
	
      if($rights_ta_join_payment_add && $cancel=='0'){ ?>
      
        <input value="เพิ่มประวัติการชำระ" type="button" name="เพิ่ม" 
				   onclick="javascript:window.open('ta_join_payment_add.php?id=<?php print $id2 ?>&expire_date=<?php 
				   if($expire_date_pay==''){
					   	list($dd5,$mm5,$yy5)=split("/",$start_ta_join_date);
						//$yy5=$yy5-543;	

						$expire_date_pay =  MKTIME(0,0,0,$mm5-1,'01',$yy5) ;
$expire_date_pay = date("m/Y", $expire_date_pay);
  	list($mm6,$yy6)=split("/",$expire_date_pay);
		//$expire_date_pay  = $mm6."/".($yy6+543) ;
		$expire_date_pay  = $mm6."/".($yy6) ;
				   }
				 
				   
				   print $expire_date_pay ?>&pay_type=<?php print $type ?>','_blank')" id="เพิ่ม" /><?php } ?>
    </div></td>
  </tr>
</table>
<p><?php if($error1!=0){
	echo "<h3><font color=red>มีการคำนวนผิดพลาด โปรดระวังเป็นพิเศษ</font></h3>";
}
?>
เริ่มคิดค่าบริการเข้าร่วม วันที่ <?Php print $start_ta_join_date ;if($expire_date_pay!=""){?> ใช้ได้ถึง <?Php print $expire_date_pay ?> <br /><br />
<font color="#9900FF">ยอดค้างชำระ ณ วันที่ <?Php 

list($mm3,$yy3)=split("/",$expire_date_pay);
//$yy3=$yy3-543;
$period =  MKTIME(0,0,0,$mm3, '01', $yy3) ;			
$now =  MKTIME(0,0,0,date("m"), '01', date("Y")) ;
//$now =  MKTIME(0,0,0,'07', '01', '2011') ;
$month = $now-$period;


$month = round($month/60/60/24/30);	
$arrears = ($month*$type);
if($arrears<0){
	$arrears =0;
}

//$yy = date("Y")+543;
$yy = date("Y");
print date("d")."/".date("m")."/".$yy ?> ทั้งหมด <font color="red"> <?Php print number_format($arrears) ?> </font>บาท</font></p>

   <?php }
  $query = "SELECT * FROM ta_join_payment WHERE id_main='$id2' and deleted='1' ORDER BY update_datetime asc ";
				
				$sql_query = pg_query($query);
				$num_row_del = pg_num_rows($sql_query);
				if($num_row_del!=0){
	?>

<strong><font color=red>รายการที่เคยถูกยกเลิก</font></strong><br /><br>
<table border="0" align="center" cellpadding="1" cellspacing="1" >

  <tr bgcolor="#CCCCFF">
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ลำดับ&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;เลขที่ใบเสร็จ&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ทะเบียนรถ&nbsp;</div></td>
   <td bgcolor="#66CCFF"><div align="center">&nbsp;สัญญาเลขที่&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ชื่อลูกค้า&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;วันที่ชำระเงิน&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ประเภทการชำระ&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;จำนวน&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ตั้งแต่&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;ใช้ได้ถึง&nbsp;</div></td>
    <td bgcolor="#66CCFF"><div align="center">&nbsp;จำนวนเดือน&nbsp;</div></td>
  
    <td colspan="2" bgcolor="#66CCFF"><div align="center">ผู้ยกเลิก</div></td>
  </tr>
   <?php

	$ui = 1;
				while($sql_row = pg_fetch_array($sql_query))
				{	
				$change_pay_type = $sql_row[change_pay_type];
				
				
				$users_del = $sql_row[update_by]; //ใบเสร็จแบบเก่า ใช้ update_by
				if($sql_row[create_by]!=""){ //ใบเสร็จแบบใหม่  แบบเก่า create_by จะไม่มี
					
				$res_profile=pg_query("select postuser from \"CancelReceipt\" where admin_approve='true' and ref_receipt = '".$sql_row[ta_join_payment_id]."' ");
   $res_userprofile=pg_fetch_array($res_profile);
   $users_del=  $res_userprofile["postuser"];

				}
   				$res_profile=pg_query("select fullname,id_user from \"Vfuser\" where id_user='$users_del'");
   $res_userprofile=pg_fetch_array($res_profile);
   $users_del=  $users_del."-".$res_userprofile["fullname"];
				//list($users_del,$bb,$cc) = split(" ",$users_del);
				
				$pay_date =	$sql_row[pay_date];
					$pay_date = date_ch_form_c($pay_date);
				
					$pay_type = $sql_row[pay_type];	
					
				if($pay_type=='0'){
					$pay_type = "300/เดือน";
					//$pay_type = $type."/เดือน";
					
						if($change_pay_type=='1' ){
							$pay_type  = "<font color=red>(ชำระค่าเข้าร่วม)</font>" ;
					
						}
				}
				else if($pay_type=='1'){
				
				if($change_pay_type=='1' ){
							$pay_type  = "<font color=red>(ชำระค่าเข้าร่วม)</font>" ;
					
						}else{
				$pay_type = "100/เดือน";
				//$pay_type = $type."/เดือน";
				
				
						}
				}
				else if($pay_type=='2'){
				
					
						if($change_pay_type=='1'){
							$pay_type  = "<font color=red>SP(ค่าเข้าร่วม)</font>" ;
					
						}else {
							$pay_type = "SP(".$type.")";
						}
				}
				
				$period_date =	$sql_row[period_date];
				
				if($period_date!=""){
					$period_date = date_ch_form_c($period_date);
					list($dd5,$mm5,$yy5)=split("/",$period_date);	
							 $period_date = $mm5."/".$yy5;
				}
					else $period_date= '-';
					
					$expire_date  =	$sql_row[expire_date];
				if($expire_date !=""){
					
					$expire_date  = date_ch_form_c($expire_date);
					list($dd6,$mm6,$yy6)=split("/",$expire_date);	
							$expire_date = $mm6."/".$yy6;
				}
					else $expire_date= '-';
				
				?>
 
   <tr>
    <td bgcolor="#F4F4F4"><div align="center"><u><a title="หมายเหตุ" href="javascript:MM_openbrWindow('showDetailCall.php?id=<?php print $sql_row[id] ?>','','scrollbars=no,width=500,height=260, left = 0, top = 0')"><?Php print $ui ?></a></u></div></td>
     <td bgcolor="#F4F4F4"><div align=left><u><a href="javascript:popU('ta_join_payment_detail.php?id=<?php print $sql_row[id] ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=768')"><?Php print $sql_row[ta_join_payment_id] ?></a></u></div></td>
    <td bgcolor="#F4F4F4"><div align="center"><?Php print $sql_row[car_license]; if($sql_row[car_license_seq]!=0) print "/".$sql_row[car_license_seq]; ?></div></td>
    <td bgcolor="#F4F4F4"><div align="center"><u><a href="javascript:popU('../../../../../post/frm_viewcuspayment.php?idno=<?Php print $IDNO ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1024,height=768')"><?Php print $sql_row[idno] ?></a></u></div></td>
    <td bgcolor="#F4F4F4"><div align="center"><?Php print $sql_row[cpro_name] ?></div></td>
    <td bgcolor="#F4F4F4"><div align="center"><?Php print $pay_date ?></div></td>
    <td bgcolor="#F4F4F4"><div align=left><?Php print $pay_type ?></div></td>
    <td bgcolor="#F4F4F4"><div align=right><?Php print number_format($sql_row[amount])?></div></td>
    <td bgcolor="#F4F4F4"><div align="center"><?Php print $period_date ?></div></td>
    <td bgcolor="#F4F4F4"><div align="center"><?Php print $expire_date ?></div></td>
    <td bgcolor="#F4F4F4"><div align=center><?Php print $sql_row[amount_month] ?></div></td>
   
    <td bgcolor="#F4F4F4"><div align="left"><?Php print $users_del ?></div></td>
  </tr>
  <?php $ui++;} ?>
  
  </table><br />
<?php  } ?>


<input type="hidden" name="form_name" value="ta_join_payment_add" />
<?php


                ?>


 <input name="expire_date" type="hidden" id="expire_date" value="" size="20"/>
 <input name="pay_type" type="hidden" id="pay_type"  value="" size="20"/>
 <input name="change_pay_type" type="hidden" id="change_pay_type" value="" size="20"/>
  <input name="start_pay_date" type="hidden" id="start_pay_date"  value="<?Php print $start_pay_date2 ?>" size="20"/>
<table border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td colspan="2" bgcolor="#66CCFF" height="25px" ><b>รายละเอียดการชำระเงินแบบพิเศษ : </a></b></td>
  </tr>

  <tr>
    <td bgcolor="#EEFBFA"><div align="right"><strong>ประเภทการชำระ : </strong></div></td>
    <td bgcolor="#EEFBFA"> <select name="type1" id="type1" onchange="cal_ar1()">
    <option value="0">เฉพาะค่าเข้าร่วม</option>
	<option value="1">รวมค่าแรกเข้า</option>
	</select>
	</td>
  </tr>
  <tr>
    <td bgcolor="#EEFBFA"><div align="right"><strong>วันที่ชำระ : </strong></div></td>
    <td bgcolor="#EEFBFA"><input type="text" id="datepicker" name="datepicker" value="" onchange="cal_ar1()"  size="15"  /></td>
  </tr>
    <tr>
    <td width="200" bgcolor="#EEFBFA"><div align="right"><strong>เดือนที่หมดอายุ : </strong></div></td>
    <td width="200" bgcolor="#EEFBFA"><span id="exp_d"></span></td>
  </tr>
  
  <tr>
    <td bgcolor="#EEFBFA"><div align="right"><strong>ค่าค้างชำระ : </strong></div></td>
    <td bgcolor="#EEFBFA"><input name="arrears" type="text" id="arrears" value="" readonly="readonly" size="15"/> บาท</td>
  </tr>
  
  
  <tr>
    <td bgcolor="#EEFBFA"><div align="right"><strong>หมายเหตุ : </strong></div></td>
    <td bgcolor="#EEFBFA"><textarea name="reason" style="width:195px;height:40px;" id="reason" ></textarea></td>
  </tr>
  <tr>
    <td bgcolor="#EEFBFA"><div align="right"><b>จำนวนเงิน : </b></div></td>
    <td bgcolor="#EEFBFA"><input name="amount" type="text" id="amount1" style="text-align:right"  onchange="dokeyup(this,event);cal_ar();" size="15" onkeyup="dokeyup(this,event);" onkeypress="checknumber2(event)"  />
      บาท</font>
      <input value="คำนวน" id="credit_b2" type="button" name="credit_b" 
				  onclick="javaScript:cal_ar()" /></td>
  </tr>
</table>
<div id="cal_arrears" align="center"></div>
</center>
<?php  } ?>
<?Php  
echo "
			
		
		
			</ul>
	  </form>
	<div id=\"footer\"></div>
</div>
	<img id=\"bottom\" src=\"../".$lo_ext_current_temp."pictures/bottom.png\" alt=\"\">
</body>
</html>

";
ob_end_flush();
?>