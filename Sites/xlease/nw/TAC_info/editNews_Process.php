<?php
	session_start();
	include("config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	pg_query("BEGIN");
	$status=0;
	$id=$_GET[id];;
	$title=$_POST['tbxSubject'];
	$message=$_POST['tbxMessage'];
	$type=$_POST['lbNewsType'];
	$forMember=$_POST['forMember'];
	$forMemberStatus=0;
	$poster=$_SESSION['username'];
	$postDate=$_POST['tbxTime'];
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
	$sql="update \"Main_News\" set \"Subject\"='$title', \"Message\"='$message', \"Type\"='$type', \"doerID\"='$poster', \"doerStamp\"='$postDate', \"isMember\"='$forMemberStatus' where \"NewsID\"=$id";
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