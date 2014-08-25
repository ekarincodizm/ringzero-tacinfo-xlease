<?php
	session_start();
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
	<?php
	$user_language = $_SESSION['language'];
	if($user_language=="TH"){
		$_SESSION['language']="LO";
	}
	else if($user_language=="LO"){
		$_SESSION['language']="TH";
	}
	echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php\">";
	echo "<script type='text/javascript'>alert('คุณทำการเปลี่ยนภาษาเรียบร้อยแล้วค่ะ')</script>";
	exit();
?>






