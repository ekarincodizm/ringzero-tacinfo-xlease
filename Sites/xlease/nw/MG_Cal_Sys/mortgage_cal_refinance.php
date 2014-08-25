<?php
ob_start();
session_start();

require_once("setup/sys_setup.php");


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>คำนวณสัญญาจำนอง Refinance</title>
<script src="<?php echo $lo_ext_current_temp ?>scripts/js/jquery-1.4.2.min.js" type="text/javascript"></script>
<script src="<?php echo $lo_ext_current_temp ?>scripts/js/jquery-ui-1.8.1.offset.datepicker.min.js" type="text/javascript"></script>
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
			
			
		</div>

"; ?>
<link rel="stylesheet" type="text/css" href="<?php echo $lo_ext_current_temp ?>scripts/css/ui-lightness/jquery-ui-1.8.1.custom.css" />
<script type="text/javascript">


$(document).ready(
     function() {
var d = new Date();
var s = d.getDate();
var m = d.getMonth()+1;
var y = d.getFullYear()+543;
  $("#MinimumInsDate").datepicker({ dateFormat: 'dd/mm/yy',
     yearOffset: 543, 
     defaultDate: s+'/'+m+'/'+y,
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



function credit_cal(){
$of_type = document.getElementById('cmort_otherfee').value;

 $.post("credit_cal2.php", { credit: document.getElementById('credit').value,
 Pfee: document.getElementById('Pfee').value,
 of_type: $of_type


 

  },
  function(data){
    document.getElementById('cnet').value=data;
  });


 }
 function credit_cal_new(){
$of_type = document.getElementById('cmort_otherfee_new').value;

 $.post("credit_cal2.php", { credit: document.getElementById('cmort_credit_new').value,
 Pfee: document.getElementById('Pfee').value,
 of_type: $of_type


 

  },
  function(data){
    document.getElementById('cnet_new').value=data;
  });


 }
  function otherfee_cal_new(){
$of_type = document.getElementById('cmort_otherfee_new').value;
 
 $.post("otherfee_cal.php", { credit: document.getElementById('cmort_credit_new').value,
 of_type: $of_type


 //Pfee: document.getElementById('Pfee').value
//of: document.getElementsByName('o_f_type').item(2);

 

  },
  function(data){
    document.getElementById('cmort_otherfee_new').value=data;
  });


 }
  function nCredit_new(){

 
 $.post("cal/number_credit.php", { credit: document.getElementById('cmort_credit_new').value

 

  },
  function(data){
    document.getElementById('cmort_credit_new').value=data;
  });


 }
  function nCredit_old(){

 
 $.post("cal/number_credit.php", { credit: document.getElementById('cmort_credit_old').value

 

  },
  function(data){
    document.getElementById('cmort_credit_old').value=data;
  });


 }
   function credit_new_cal(){

 $.post("cal/cal_credit_new_old.php", { cmort_credit_new: document.getElementById('cmort_credit_new').value ,
 cmort_credit_old: document.getElementById('cmort_credit_old').value
 
  },
  function(data){
    document.getElementById('credit').value=data;
  });


 }
 function cnet_cal(){
$of_type = document.getElementById('cmort_otherfee').value;
 
 $.post("cnet_cal2.php", { cnet: document.getElementById('cnet').value,
 Pfee: document.getElementById('Pfee').value,
 of_type: $of_type

 

  },
  function(data){
    document.getElementById('credit').value=data;
  });


 }
 function nCredit(){

 
 $.post("cal/number_credit.php", { credit: document.getElementById('credit').value

 

  },
  function(data){
    document.getElementById('credit').value=data;
  });


 }
  function nCnet(){

 
 $.post("cal/number_cnet.php", { cnet: document.getElementById('cnet').value

 

  },
  function(data){
    document.getElementById('cnet').value=data;
  });


 }
   function nMinpay(){

 
 $.post("cal/number_minpay.php", { cmort_minpay: document.getElementById('cmort_minpay1').value

 

  },
  function(data){
    document.getElementById('cmort_minpay1').value=data;
  });


 }
 function otherfee_cal(){
$of_type = document.getElementById('cmort_otherfee').value;
 
 $.post("otherfee_cal.php", { credit: document.getElementById('credit').value,
 of_type: $of_type


 //Pfee: document.getElementById('Pfee').value
//of: document.getElementsByName('o_f_type').item(2);

 

  },
  function(data){
    document.getElementById('cmort_otherfee').value=data;
  });


 }
 function cal_minpay(){
	 
	 var start_d = document.getElementById('MinimumInsDate').value.split("/"); 
	 var date1=new Date(start_d[2]-543,start_d[1]-1,start_d[0]);
	  var date2=new Date(document.getElementById('yy01').value,document.getElementById('mm01').value-1,document.getElementById('pdate').value);
	   
	 
if(date2 <= date1 ){
	alert("วันเดือนปีที่เริ่มจ่าย ต้องมากกว่าวันที่เริ่มทำสัญญา!!");
}else {

//alert(document.getElementById('mm01').value);
//alert(document.getElementById('yy01').value);
 $.post("cal/cal_minpay_test2.php", { credit: document.getElementById('credit').value,
 cmort_length: document.getElementById('cmort_length').value,
 intNormal: document.getElementById('intNormal').value,
  MinimumInsDate: document.getElementById('MinimumInsDate').value,
   pdate: document.getElementById('pdate').value,
   first_pay_date_m: document.getElementById('mm01').value,
    first_pay_date_y: document.getElementById('yy01').value
 

  },
  function(data){
    document.getElementById('cmort_minpay1').value=data;
  });

}
 }
 function calLength(){
var start_d = document.getElementById('MinimumInsDate').value.split("/"); 
	 var date1=new Date(start_d[2]-543,start_d[1]-1,start_d[0]);
	  var date2=new Date(document.getElementById('yy01').value,document.getElementById('mm01').value-1,document.getElementById('pdate').value);
	   
	 
if(date2 <= date1 ){
	alert("วันเดือนปีที่เริ่มจ่าย ต้องมากกว่าวันที่เริ่มทำสัญญา!!");
}else {
 
 $.post("cal/cal_length_test2.php", { credit: document.getElementById('credit').value,
 cmort_minpay: document.getElementById('cmort_minpay1').value,
 intNormal: document.getElementById('intNormal').value,
   MinimumInsDate: document.getElementById('MinimumInsDate').value,
   pdate: document.getElementById('pdate').value,
   first_pay_date_m: document.getElementById('mm01').value,
    first_pay_date_y: document.getElementById('yy01').value

  },
  function(data){
    document.getElementById('cmort_length').value=data;
  });

}
 }
  function calC(){

 
 $.post("cal/cal_credit.php", { cmort_length: document.getElementById('cmort_length').value,
 cmort_minpay: document.getElementById('cmort_minpay1').value,
 intNormal: document.getElementById('intNormal').value
 
   

  },
  function(data){
    document.getElementById('credit').value=data;
	 document.getElementById('receive').innerHTML='';
	  document.getElementById('r_length').innerHTML='';
  });


 }
   function calCcal(){

 
 $.post("cal/cal_credit_cal.php", { cmort_length: document.getElementById('cmort_length').value,
 cmort_minpay: document.getElementById('cmort_minpay1').value,
 credit: document.getElementById('credit').value,
 intNormal: document.getElementById('intNormal').value,
  MinimumInsDate: document.getElementById('MinimumInsDate').value,
   pdate: document.getElementById('pdate').value
 
   

  },
  function(data){
    document.getElementById('credit').value=data;
  });


 }
function cal_credit_b(){
	document.getElementById('cal_l_b').style.display='none';
	document.getElementById('cal_m_b').style.display='none';
	document.getElementById('cal_c_b').style.display='';
}
function cal_b(){
	document.getElementById('cal_l_b').style.display='';
	document.getElementById('cal_m_b').style.display='';
	document.getElementById('cal_c_b').style.display='none';
}

 function cal_receive(){

 var start_d = document.getElementById('MinimumInsDate').value.split("/"); 
	 var date1=new Date(start_d[2]-543,start_d[1]-1,start_d[0]);
	  var date2=new Date(document.getElementById('yy01').value,document.getElementById('mm01').value-1,document.getElementById('pdate').value);
	   
	 
if(date2 <= date1 ){
	//alert("วันเดือนปีที่เริ่มจ่าย ต้องมากกว่าวันที่เริ่มทำสัญญา!!");
}else {
 $.post("cal/cal_receive_test2.php", { credit: document.getElementById('credit').value,
 cmort_length: document.getElementById('cmort_length').value,
 intNormal: document.getElementById('intNormal').value,
  MinimumInsDate: document.getElementById('MinimumInsDate').value,
   pdate: document.getElementById('pdate').value,
   first_pay_date_m: document.getElementById('mm01').value,
    first_pay_date_y: document.getElementById('yy01').value
 

  },
  function(data){
    document.getElementById('receive').innerHTML=data;
  });
}
 }
  function cal_r_length(){
var start_d = document.getElementById('MinimumInsDate').value.split("/"); 
	 var date1=new Date(start_d[2]-543,start_d[1]-1,start_d[0]);
	  var date2=new Date(document.getElementById('yy01').value,document.getElementById('mm01').value-1,document.getElementById('pdate').value);
	   
	 
if(date2 <= date1 ){
	//alert("วันเดือนปีที่เริ่มจ่าย ต้องมากกว่าวันที่เริ่มทำสัญญา!!");
}else {
 
 $.post("cal/cal_receive_length_test2.php", { credit: document.getElementById('credit').value,
 cmort_minpay: document.getElementById('cmort_minpay1').value,
 intNormal: document.getElementById('intNormal').value,
  MinimumInsDate: document.getElementById('MinimumInsDate').value,
   pdate: document.getElementById('pdate').value,
   first_pay_date_m: document.getElementById('mm01').value,
    first_pay_date_y: document.getElementById('yy01').value
 

  },
  function(data){
    document.getElementById('r_length').innerHTML=data;
  });
}
 }
function num_deeds(){

 
 $.post("show_deeds_tf.php", { num: document.getElementById('num').value
 
 
  },
  function(data){
    document.getElementById('result').innerHTML=data;
  });


 }
 function num_cpro(){

 
 $.post("show_cpro_tf.php", { num: document.getElementById('num2').value
 
 
  },
  function(data){
    document.getElementById('result2').innerHTML=data;
  });


 }
 function num_cpro_g(){

 
 $.post("show_cpro_gtf.php", { num: document.getElementById('num_g').value
 
 
  },
  function(data){
    document.getElementById('result3').innerHTML=data;
  });


 }
 	function dokeyup( obj ,e)
{
	    var key;
   
    if(window.event){
        key = window.event.keyCode; // IE

    }else{
        key = e.which; // Firefox       

  }
if( key != 37 & key != 39 & key != 190 & key != 110)
{
var value = obj.value;
var svals = value.split( "." ); //แยกทศนิยมออก
var sval = svals[0]; //ตัวเลขจำนวนเต็ม

var n = 0;
var result = "";
var c = "";
for ( a = sval.length - 1; a >= 0 ; a-- )
{
c = sval.charAt(a);
if ( c != ',' )
{
n++;
if ( n == 4 )
{
result = "," + result;
n = 1;
};
result = c + result;
};
};

if ( svals[1] )
{
result = result + '.' + svals[1];
};

obj.value = result;
};
};

//ให้ text รับค่าเป็นตัวเลขอย่างเดียว
function checknumber(e)
{
	    if(window.event){
        key = window.event.keyCode; // IE

    }else{
        key = e.which; // Firefox       

  }
   

if ( key != 46 & ( key < 48 || key > 57 ) & key != 8 )
{
	    if(window.event){
      event.returnValue = false; // IE

    }else{
       e.preventDefault(); // Firefox       

  }

};
};
</script>

<?php  //$credit_min =  setting('min_loan_amt',$dbtb_loan_default,'loan_default_value','loan_default_name','gen_loan_default_id');

 $credit_min =  100000;//setting('min_loan_amt',$dbtb_loan_default,'loan_default_value','loan_default_name','gen_loan_default_id');
//$max_d =  0;//setting('max_date',$dbtb_loan_default,'loan_default_value','loan_default_name','gen_loan_default_id');
?>
        
     
        <center>
     
<form action="mortgage_cf_test_sp.php" method="post" name="form1" >
<div class="form_description">
				<h2>คำนวณสัญญาจำนอง Refinance</h2>
			
					</div>		
		<table width="800" border="1" align="center" cellspacing="0">

		  <tr>
		    <td colspan="4" bgcolor="#66CCFF">&nbsp;</td>
	      </tr>
		
	    <tr>
		    <td  > <div align="right"><label class="description" for="element_8">
		     อัตราดอกเบี้ยปกติ :
                <input name="cmort_int_normal" type="text" id="intNormal" style="color:<?php echo $settcol;?>;background:<?php echo $setbgcol;?>;font-weight:<?php echo $setfow;?>;text-align:<?php echo $align;?>" value="15" size="2" />
               
                </label>
	        </div></td>
		    <td colspan="3"><label class="description" for="element_8">
		     อัตราดอกเบี้ยผิดนัด :
		        <input name="cmort_int_penalty" type="text" id="penalty" style="color:<?php echo $settcol;?>;background:<?php echo $setbgcol;?>;font-weight:<?php echo $setfow;?>;text-align:<?php echo $align;?>" value="15" size="2" /></label>
		      </td>
	      </tr>
		 
		  <tr>
		    <td ><div align="right"><label class="description" for="element_5">
		      % ของค่าใช้จ่าย :
		        <input name="cmort_pfee" type="text" id="Pfee" style="color:<?php echo $settcol;?>;background:<?php echo $setbgcol;?>;font-weight:<?php echo $setfow;?>;text-align:<?php echo $align;?>" value="10" size="2" />
		       </label>
    
		      </div></td>
		    <td colspan="3"><label class="description" for="element_6">
		     ค่าใช้จ่ายอื่นๆ (บาท) :
		        <input name="cmort_otherfee" type="text" id="cmort_otherfee" onkeyup="dokeyup(this,event);" style="color:<?php echo $settcol;?>;background:<?php echo $setbgcol;?>;font-weight:<?php echo $setfow;?>;text-align:<?php echo $align;?>" value="5,000" size="15" />
		        </label>

		     </td>
	      </tr>
		  <tr>
		    <td><div align="right"><font color="green"><strong>ยอดจดจำนองใหม่ (บาท) : </strong></font>
		      <input name="cmort_credit_new" type="text" id="cmort_credit_new" style="text-align:<?php echo $align;?>" onkeypress="checknumber(event)" onkeyup="dokeyup(this,event);credit_cal_new();otherfee_cal_new();" onchange="credit_cal_new();otherfee_cal_new();nCredit_new();" value="" size="15"/>
		      <input value="คำนวน" id="credit_new" type="button" name="credit_new" 
				  onclick="javascript:if(document.form1.cmort_credit_new.value==''){alert('กรุณากรอกวงเงินจดจำนองใหม่');}else{credit_cal_new();otherfee_cal_new();nCredit_new();}" />
	        </div></td>
		    <td colspan="3"><font color="green">ค่าใช้จ่ายอื่นๆ ของยอดจดจำนองใหม่(บาท) : </font>
              <input name="cmort_otherfee_new" type="text" id="cmort_otherfee_new" onchange="credit_cal_new();"  onkeyup="dokeyup(this,event);" style="text-align:<?php echo $align;?>" value="5,000" size="15" />
           
             </td>
		           
	      </tr>
		  <tr>
		    <td>
	          <div align="right"><font color="green"><strong>ยอดจดจำนองเดิม(บาท) : </strong></font>
	            <input name="cmort_credit_old" type="text" id="cmort_credit_old" style="text-align:<?php echo $align;?>" onkeypress="checknumber(event)" onkeyup="dokeyup(this,event);credit_new_cal();otherfee_cal();" onchange="nCredit_old();credit_new_cal();otherfee_cal();" value="" size="15"/>
	            <input value="คำนวน" id="credit_new2" type="button" name="credit_new2" 
				  onclick="javascript:if(document.form1.cmort_credit_old.value==''){alert('กรุณากรอกวงเงินจดจำนองใหม่');}else{nCredit_old();credit_new_cal();otherfee_cal();}" />
            </div></td>
		    <td colspan="3">
		    
		     <div align="left"><font color="green">ลูกค้ารับเงินสุทธิจากยอดจดจำนองใหม่(บาท) </font>: 
		      <input name="cnet_new" type="text" id="cnet_new" style="text-align:<?php echo $align;?>" size="15" readonly="readonly" />
		
	        <font color="red">*</font></div></td>
	      </tr>
		  <tr>
		    <td><label class="description" for="element_7">
		      
		      <div align="right"><font color="red">*</font>วงเงินจดจำนอง (บาท) : 
                <input name="credit" type="text" id="credit" style="text-align:<?php echo $align;?>" autocomplete="off" onchange="dokeyup(this,event);javascript:if(document.form1.credit.value.replace(/,/g,'')&lt;<?php echo $credit_min?>){alert('วงเงินจดจำนองต้องมากกว่า <?php echo number_format($credit_min)?> บาท');}else{credit_cal();otherfee_cal();nCredit();}" onkeypress="checknumber(event)" onkeyup="dokeyup(this,event);credit_cal();otherfee_cal();" size="15" />
		      <input value="คำนวน" id="credit_b" type="button" name="ตกลง" 
				  onclick="javascript:if(document.form1.credit.value==''){alert('กรุณากรอกวงเงินจดจำนอง');}else{if(document.form1.credit.value==''){alert('กรุณาระบุจำนวนเงินกู้');}else if(document.form1.credit.value.replace(/,/g,'')&lt;<?php echo $credit_min?>){alert('จำนวนเงินกู้ต้องมากกว่า <?php echo number_format($credit_min)?> บาท');}else{credit_cal();otherfee_cal();nCredit();}}" />
		      </label></div></td>
		    <td colspan="3" rowspan="2"><p align="left">
		      <input name="cal_credit" type="radio" id="calCredit" value="" checked="checked" 
                onClick="javaScript:if(this.checked){document.form1.cnet.readOnly=true;document.form1.credit.readOnly=false;document.form1.credit.value='';document.form1.cnet.value='';
                document.getElementById('cnet_b').disabled=true;
	document.getElementById('credit_b').disabled=false;}">
		      คำนวนจากยอดจัดจำนอง
		      <br />
		      <input type="radio" name="cal_credit" id="calCnet" value="" 
  onClick="javaScript:if(this.checked){document.form1.credit.readOnly=true;document.form1.cnet.readOnly=false;document.form1.credit.value='';document.form1.cnet.value='';
  document.getElementById('cnet_b').disabled=false;
	document.getElementById('credit_b').disabled=true;}">
		      คำนวนจากเงินรับสุทธิ
	        </td>
	      </tr>
		  <tr>
		    <td> <div align="right"><label class="description" for="111">
		      ลูกค้ารับเงินสุทธิ (บาท) : 
		    <input name="cnet" type="text" id="cnet" style="text-align:<?php echo $align;?>" onkeyup="dokeyup(this,event);"  onchange="nCnet();cnet_cal();" size="15" readonly="readonly"/>
            <input value="คำนวน" type="button" name="ตกลง007" id="cnet_b" disabled="disabled" 
				  onclick="javascript:if(document.form1.cnet.value==''){alert('กรุณากรอกยอดรับเงินสุทธิ');}else{cnet_cal();otherfee_cal();}
                  " /></label></div></td>
	      </tr>
		  <tr>
		    <td ><label class="description" for="element_8">
		      <div align="right">วันเริ่มสัญญา :
		        <input name="MinimumInsDate" type="text" id="MinimumInsDate" value="" size="8"/>
		        <font color="red">*</font> 
		        </label>
		      </div></td>
		    <td ><div align="left"><font color="#000000" size="1" 
                        face="MS Sans Serif">
		    </font>
		        จ่ายทุกวันที่ <font color="#000000" size="1" 
                        face="MS Sans Serif">
		          <select name="pdate" id="pdate" >
		            <option selected="selected" 
                          value="0">[วันที่]</option>
		          <?Php for($i=1;$i<32;$i++){ ?>
                  
		            <option value="<?Php if( $i<10)echo '0'.$i; else echo $i ;?>"><?Php echo $i ?></option>
<?Php		           
}?>
	 
	            </select>
		        </font><font color="red">*</font>
		    </div></td>
		    <td ><div align="right"><span class="description">เริ่มจ่าย :</span></div></td>
		    <td ><div align="left">
		      <select name="mm01" id="mm01">
		        <?php 
		  $current_month = date("n");
	$current_month_num = strlen($current_month) ;
	if($current_month_num==1)	
		$current_month = '0'.$current_month ;
		
		print $current_month ;
		  ?>
		        <option value="01"><?php print core_translate_month(01)?></option>
		        <option value="02"><?php print core_translate_month(02)?></option>
		        <option value="03"><?php print core_translate_month(03)?></option>
		        <option value="04"><?php print core_translate_month(04)?></option>
		        <option value="05"><?php print core_translate_month(05)?></option>
		        <option value="06"><?php print core_translate_month(06)?></option>
		        <option value="07"><?php print core_translate_month(07)?></option>
		        <option value="08"><?php print core_translate_month(08)?></option>
		        <option value="09"><?php print core_translate_month('09')?></option>
		        <option value="10"><?php print core_translate_month(10)?></option>
		        <option value="11"><?php print core_translate_month(11)?></option>
		        <option value="12"><?php print core_translate_month(12)?></option>
	          </select>
		      <select name="yy01" id="yy01" >
		        <?php 
			  $current_y = date("Y");
			
		$k = $current_y+543 ;
		$j = $k-1;
		  for($i = $current_y;$i<($current_y+2);$i++){
			 if($j < ($k+2)){
			 $j++ ;
				 
		  ?>
		        <option 
                          value="<?php print $i ?>"><?php print $j ?></option>
		        <?php } 
		  }
		  ?>
	          </select>
	        </div></td>
	      </tr>
		  <tr>
		    <td >  <div align="right"><label class="description" for="element_9">
		    คำนวนหาค่า :
		        </label>
	          </div></td>
		    <td colspan="3"><div align="left">
		    
	            <input name="cal" type="radio" id="cal_length" value="" checked="checked" 
                onClick="javaScript:if(this.checked){document.form1.cmort_minpay.readOnly=false;document.form1.cmort_length.readOnly=true;document.form1.credit.readOnly=false;
                document.form1.cal_l_b.disabled=true;document.form1.cal_m_b.disabled=false;document.form1.cmort_minpay.value='';document.form1.cmort_length.value='';cal_b();
                  document.getElementById('receive').innerHTML='';}">
	         ระยะเวลาในการจ่ายคืนสินเชื่อ (เดือน)
              <br />
              <input type="radio" name="cal" id="cal_minpay1" value="" 
  onClick="javaScript:if(this.checked){document.form1.cmort_length.readOnly=false;document.form1.cmort_minpay.readOnly=true;document.form1.credit.readOnly=false;
  document.form1.cal_m_b.disabled=true;document.form1.cal_l_b.disabled=false;document.form1.cmort_minpay.value='';document.form1.cmort_length.value='';cal_b();
  document.getElementById('r_length').innerHTML='';}">
             จำนวนเงินขั้นต่ำที่ต้องจ่ายต่อเดือน             
              <br />
              <input type="radio" name="cal" id="cal_c" onclick="javaScript:if(this.checked){document.form1.cmort_length.readOnly=false;document.form1.cmort_minpay.readOnly=false;
  document.form1.credit.readOnly=true;document.form1.cal_m_b.disabled=false;document.form1.cal_l_b.disabled=false;cal_credit_b();}" />
จำนวนเงินยอดจัดจำนอง<br />
		     
</div></td>
	      </tr>
		  <tr>
		    <td ><label class="description" for="element_8">
		      <div align="right">ระยะเวลาในการจ่ายคืนสินเชื่อ (เดือน) : 
		      </label>
            </div></td>
		    <td colspan="3"><div align="left">
		      <input name="cmort_length" type="text" id="cmort_length" size="15" maxlength="3" readonly="readonly"  />
	          <input value="คำนวน" type="button" name="cal_l_b" id="cal_l_b" onclick="javascript:if(document.form1.credit.value==''){alert('กรุณากรอกวงเงินจดจำนอง');}else{
              if(document.form1.MinimumInsDate.value.length!=10){alert('กรุณากรอกวันเริ่มสัญญา');}else{
              if(document.form1.pdate.value=='0'){alert('กรุณากรอกวันที่จ่ายประจำทุกเดือน');}else{if(document.form1.cmort_length.value==''){alert('กรุณากรอกจำนวนเดือน');}else {cal_minpay();cal_receive();}}}}" disabled />
		      <font color="red">*</font><br />
		    </div><div id="r_length" align="left" ></div></td>
	      </tr>
		  <tr>
		    <td ><label class="description" for="element_9">
		      <div align="right">จำนวนเงินขั้นต่ำที่ต้องจ่ายต่อเดือน(บาท) :
		      </label>
		      </div></td>
		    <td colspan="3"><div align="left">
		      <input name="cmort_minpay" type="text" id="cmort_minpay1" style="text-align:<?php echo $align;?>" onkeyup="dokeyup(this,event);"  onchange="nMinpay()" size="15" />
		      <input value="คำนวน" type="button" name="cal_m_b" id="cal_m_b" onclick="javascript:if(document.form1.credit.value==''){alert('กรุณากรอกวงเงินจดจำนอง');}else{
              if(document.form1.MinimumInsDate.value.length!=10){alert('กรุณากรอกวันเริ่มสัญญา');}else{
              if(document.form1.pdate.value=='0'){alert('กรุณากรอกวันที่จ่ายประจำทุกเดือน');}else{if(document.form1.cmort_minpay1.value==''){alert('กรุณากรอกจำนวนเงินขั้นต่ำ');}else{calLength();cal_r_length();}}}}" />
		      <font color="red">*</font><br />
<input value="คำนวน" type="button" name="cal_c_b" id="cal_c_b"  style="display:none" onclick="javascript:if(document.form1.cmort_minpay1.value==''){alert('กรุณากรอกจำนวนเงินขั้นต่ำ');}else{if(document.form1.cmort_length.value==''){alert('กรุณากรอกจำนวนเดือน');}else {
              if(document.form1.MinimumInsDate.value==''){alert('กรุณากรอกวันเริ่มสัญญา');}else{
              if(document.form1.pdate.value=='0'){alert('กรุณากรอกวันที่จ่ายประจำทุกเดือน');}else{calC();}}}}" />
		    </div> <div id="receive" align="left" ></div>
	        </td>
	      </tr>
		  </table>
<p><br><input type="hidden" name="form_name" value="mortgage_cf" />
		    <input id="saveForm" class="button_text" type="submit" name="submit" value="ตกลง" style='width:100px; height:50px'/>
        <input id="saveForm" class="button_text" type="reset" name="Reset" value="ยกเลิก" style='width:100px; height:50px'/></form>
<form action="../barcode/gen_code_128b.php?cmort_id=$_GET[cmort_id]&mode=cmort" method="post">
</p>
	
      

<?Php
echo " </center>
			<div class=form_description></div>
			<label class=\"description\" for=\"element_1\"><a href=\"mortgage_cal_menu.php\"> กลับเมนูคำนวนสัญญาจำนอง</a></label>
			
			
	  </form>
	
</div>
	<img id=\"bottom\" src=\"".$lo_ext_current_temp."pictures/bottom.png\" alt=\"\">
</body>
</html>

";
ob_end_flush();
?>