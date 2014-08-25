<?php
include("../config/config.php");

$qr_contest = pg_query("select * from \"TAC_contest_types\"");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ระบบลงทะเบียน :: TAC INFO e-Commerce Web Design Competition 2013</title>
<link href="../libralies/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="../libralies/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />
<link href="../css/flick/jquery-ui-1.9.0.custom.css" rel="stylesheet" type="text/css" />

<link href="css/main.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../scripts/jquery-1.9.0.js"></script>
<script type="text/javascript" src="../scripts/jquery-ui-1.10.0.custom.js"></script>
<script type="text/javascript" src="../libralies/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.show_logo').fadeIn(1000,function(){
		setTimeout(function(){
			window.location.href = 'rules.php';
		},1500);
	});
	
});
</script>
</head>
<body>
	<div class="show_logo"></div>		
</body>

</html>