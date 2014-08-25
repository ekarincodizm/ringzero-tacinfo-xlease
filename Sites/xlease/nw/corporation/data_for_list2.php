<?php
// ส่วนติดต่อกับฐานข้อมูล    
include("../../config/config.php"); 
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
  <option value="0"><เลือกประเภทอุตสาหกรรม></option>
  <?php
  $q="select * from public.\"th_corp_industype\" order by \"IndustypeName\" ";
  $qr=pg_query($q);  
  while($rs=pg_fetch_array($qr)){  
  ?>  
  <option value="<?php echo $rs['IndustypeID']; ?>"><?php echo $rs['IndustypeName']; ?></option>  
  <?php } ?>