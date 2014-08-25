<?php
	session_start();
	include("config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	pg_query("BEGIN");
	$status=0;
	$title=$_POST['tbxSubject'];
	$message=$_POST['tbxMessage'];
	$type=$_POST['lbNewsType'];
	$forMember=$_POST['forMember'];
	$forMemberStatus=0;
	$poster=$_SESSION['username'];
	$postDate=date("Y-m-d H:i:s");
	if($forMember==1)
	{
		$forMemberStatus=1;
	}
	else
	{
		$forMemberStatus=0;
	}
	?>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <?php
	$sql="INSERT INTO \"Main_News\"(\"Subject\", \"Message\", \"Type\", \"doerID\", \"doerStamp\",\"isMember\",\"disabled\") VALUES('$title','$message','$type','$poster','$postDate','$forMemberStatus','n')";
	if($result=pg_query($sql))
	{}
	else
	{
		$status++;
	}
	if($status==0)
	{
		pg_query("COMMIT");
		header("Location: index.php");
	}
	else
	{
		pg_query("ROLLBACK");
		echo"บันทึกข้อมูลล้มเหลว";
	}
	
?>