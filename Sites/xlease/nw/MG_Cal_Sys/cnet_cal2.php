<?php


//$show_con = $_REQUEST["show_con"];

$Pfee = $_POST["Pfee"];
$otherfee_type = str_replace(',','',$_POST[of_type]);
		
$cnet1 = $_POST["cnet"];
if($cnet1==''){
	echo "";}
	else{
$cnet1 = str_replace(',','',$cnet1);
$cnet1 = round($cnet1,2);				

	$credit1  = 100*($cnet1+$otherfee_type)/(100-$Pfee);
	
		

$credit1 = number_format($credit1,0);
echo $credit1 ;
	}

	  /*
		if($show_con=="contacts_responder"){
			//update $_GET[cus_id])  column cpro_main
			
			echo " <select name=\"show_key\" id=\"show_key\" >
      <option selected=\"selected\" value=\"show_key\">[แสดงเฉพาะผู้ที่คีย์ข้อมูล]</option>
      <option 
                          value=\"show_0\">[แสดงเฉพาะผู้ที่ไม่คีย์ข้อมูล]</option>
  
    </select>";
		
		
		}
	     */
?>