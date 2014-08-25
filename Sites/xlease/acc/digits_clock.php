<html>
<head>
<title>Server &amp; Local Time</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-874">
<style type="text/css">
<!--
.style1 {font-family: "Microsoft Sans Serif", Tahoma}
-->
</style>
</head>
 
<body>




<?php
  //  
	$current_server_time = date("Y")."/".date("m")."/".date("d")." ".date("H:i:s");
?>
 
<script  language="javascript" type="text/javascript">
function server_date(now_time) {
    current_time1 = new Date(now_time);
    current_time2 = current_time1.getTime() +1000;
    current_time = new Date(current_time2);
 
    server_time.innerHTML = "Time from server  [ "+current_time.getFullYear() + "/" + (current_time.getMonth()+1) + "/" + current_time.getDate() + " " + current_time.getHours() + ":" + current_time.getMinutes() + ":" +current_time.getSeconds()+"]";
 
 setTimeout("server_date(current_time.getTime())",1000);
}
 
setTimeout("server_date('<?php echo $current_server_time; ?>')",1000);

</script>
<div id="server_time" style="background-color:#FFFFFF; width:300px; padding:2px;">&nbsp;</div>
 

</body>
</html>
