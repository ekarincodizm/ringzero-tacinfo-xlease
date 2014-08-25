<?php




//$show_con = $_REQUEST["show_con"];

$Pfee = $_POST["Pfee"];

$otherfee = str_replace(',','',$_POST[of_type]);
$credit = $_POST["credit"];
if($credit==''){
	echo "";}
	else{
$credit = str_replace(',','',$credit);

$credit = round($credit,2);
		

$cnet = number_format(($credit-(($credit*$Pfee)/100))-$otherfee,0);
echo $cnet ;
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