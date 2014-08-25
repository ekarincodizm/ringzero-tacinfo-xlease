<?php
	session_start();
	include("../../config/config.php");
	$brand=$_POST['lbxBrand'];
	$subBrand=$_POST['lbxSubBrand'];
	$model=$_POST['lbxModel'];
	$carType=$_POST['lbxTypeProduct'];
	$carYear=$_POST['tbxYearCar'];
	if($carYear=="ระบุเป็นปี ค.ศ.")
	{
		$carYear="";
	}
	$gasType=$_POST['lbxTypeGas'];
	$gasSystem=$_POST['lbxGasSystem'];
	$gasTankSize=$_POST['tbxTankSize'];
	$province=$_POST['lbxThaiProvince'];
	$detail=$_POST['tarDetailCarSell'];
	$installmentPrice=$_POST['tbxInstallmentPrice'];
	$installmentMonth=$_POST['tbxInstallmentMonth'];
	$sellPrice=$_POST['tbxAllPrice'];
	$color=$_POST['lbxColor'];
	$sql="select \"memberID\" from carsystem.\"members\" where \"username\"='".$_SESSION['username']."'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$poster=$result['memberID'];
	$date=date("Y-m-d H:i:s");
/*	$sql="select \"brandName\" from \"TrCarBrand\" where \"brandID\"='$brand'";
	$dbquery=pg_query($sql);
	$result=pg_fetch_assoc($dbquery);
	$brand=$result['brandName'];*/
	
	$sql="insert into carsystem.\"TrPostSell\"(\"carBrand\",\"carSubBrand\",\"carModel\",\"carType\",\"carYear\",\"gasType\",\"gasSystem\",\"gasTankSize\",\"liveProvince\",\"postDetail\",\"carColor\",\"carInstallment\",\"carprice\",\"installmentMonth\",\"poster\",\"postTime\") values('$brand','$subBrand','$model','$carType','$carYear','$gasType','$gasSystem','$gasTankSize','$province','$detail','$color','$installmentPrice','$sellPrice','$installmentMonth','$poster','$date')";
	//pg_query($sql);
	if(pg_query($sql))
	{
		$sql1="select \"carSellID\" from carsystem.\"TrPostSell\" where \"carBrand\"='$brand' and \"carSubBrand\"='$subBrand' and \"carModel\"='$model' and \"carType\"='$carType' and \"carYear\"='$carYear' and \"gasType\"='$gasType' and \"gasSystem\"='$gasSystem' and \"gasTankSize\"='$gasTankSize' and \"liveProvince\"='$province' and \"postDetail\"='$detail' and \"carColor\"='$color' and \"carInstallment\"='$installmentPrice' and \"carprice\"='$sellPrice' and \"installmentMonth\"='$installmentMonth' and \"poster\"='$poster'";
		$dbquery1=pg_query($sql1);
		$result1=pg_fetch_assoc($dbquery1);
		$postID=$result1['carSellID'];
		mkdir("uploads/full/".$postID, 0777);
		mkdir("uploads/croped/".$postID, 0777);
		mkdir("uploads/thumnails/".$postID, 0777);
		$fullpath="uploads/full/".$postID."/";
		$croppath="uploads/croped/".$postID."/";
		$thumnailspath="uploads/thumnails/".$postID."/";
		session_register("fullpath");
		session_register("croppath");
		session_register("thumnailspath");
		session_register("postID");
		$_SESSION['fullpath']=$fullpath;
		$_SESSION['croppath']=$croppath;
		$_SESSION['thumnailspath']=$thumnailspath;
		$_SESSION['postID']=$postID;
		$sql2="update carsystem.\"TrPostSell\" set \"fullImagePath\"='$fullpath', \"cropImagePath\"='$croppath', \"thumnailsImagePath\"='$thumnailspath' where \"carSellID\"='$postID'";
		if(pg_query($sql2))
		{
			echo "<script type=\"text/javascript\">";
			echo "window.location.href = \"upload.php?id=".$postID."\";";
			echo "</script>";
		}
		else
		{
			echo "เกิดข้อผิดพลาด  โปรดแจ้งผู้ดูแลระบบเพิ่มดำเนินการแก้ไขครับ";
		}
	}
	else
	{
		$saveStatus="บันทึกล้มเหลว";
		echo $sql;
		echo "<br>";
		echo $sql1;
	}
?>