<?php
session_start();
include("../../company.php");
?>
<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
<?php 
foreach($company as $v){
   $comp = $v['code'];
}
if(!empty($comp)){
    foreach($company as $v){
        if($v['code'] == $comp){
            $_SESSION["session_company_code"] = $v['code'];
            $_SESSION["session_company_name"] = $v['name'];
            $_SESSION["session_company_server"] = $v['server'];
            $_SESSION["session_company_dbname"] = $v['dbname'];
            $_SESSION["session_company_dbuser"] = $v['dbuser'];
            $_SESSION["session_company_dbpass"] = $v['dbpass'];
			$_SESSION["session_company_seed"]=$v['seed'];
						
            break;
        }
    }
    
    if(empty($_SESSION["session_company_code"]) || empty($_SESSION["session_company_name"]) || empty($_SESSION["session_company_server"]) || empty($_SESSION["session_company_dbname"]) || empty($_SESSION["session_company_dbuser"]) || empty($_SESSION["session_company_dbpass"])){
        echo "ข้อมูลสำหรับการเชื่อมต่อไม่ถูกต้อง";
        exit;
    }
}

include("../../config/config.php");

$username=$_POST['username'];
$iden=$_POST['iden'];
$datebirth=$_POST['BDate'];
list($day,$month,$year)=explode("-",$datebirth);
$year1 = $year - 543;
$Bdate = $year1."-".$month."-".$day; 
$datenow=Date('Y-m-d H:i:s');

if(empty($username) ||empty($iden) ||empty($Bdate)){

	echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm1.php\">";
	echo "<script type='text/javascript'>alert('คุณกรอกข้อมูลไม่ครบ')</script>";
	exit();
}else{
		//ตรวจสอบว่ามี user นี้หรือไม่
		$sql = pg_query("select * from \"Vrpfuser\" where \"username\" = '$username'");
		$rowsql= pg_num_rows($sql);
		
		if($rowsql==0){
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm1.php\">";
			echo "<script type='text/javascript'>alert('ไม่พบข้อมูลของ $username  ในระบบ')</script>";
			exit();
		}else{
			$sqlchk = pg_query("select * from \"Vrpfuser\" where \"username\" = '$username' and \"u_idnum\" = '$iden' and \"u_birthday\"='$Bdate' ");
			$result = pg_fetch_array($sqlchk);
			$row= pg_num_rows($sqlchk);

			if($row == 0){

				echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm1.php\">";
				echo "<script type='text/javascript'>alert('กรอกข้อมูลไม่ถูกต้อง กรุณาตรวจสอบ')</script>";
				exit();

			}else{
					$iduser=$result['id_user'];
					$fname=$result['fname'];
					$seed = $_SESSION["session_company_seed"];
					$genpass = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz123456789'),0,8);
					$pass = MD5(MD5($genpass).$seed);
					$pin = substr(str_shuffle('123456789'),0,4);
					$status = 0;
					$status1 = 0;

					pg_query("BEGIN");

						$sql2 = "Insert into \"repass_admin\"(\"repass_username\",\"repass_iden\",\"repass_bdate\",\"repass_genpass\"
						,\"repass_pin\",\"repass_status\",\"repass_date\",\"id_user\") values('$username','$iden','$Bdate','$pass','$pin','0','$datenow','$iduser') ";
						$results2=pg_query($sql2);
	
				if($results2)
				{}
				else{
				$status++;
				}
	
					if($status == 0){
						
						$sql3 = "Update \"fuser\" set \"password\"='$pass',\"status_user\" = 'FALSE' where \"id_user\"='$iduser' and \"username\"='$username' ";
						$results3=pg_query($sql3);
							
							if($results3)
							{}
							else{
							$status1++;
							}
								if($status1 == 0){
								
									pg_query("COMMIT");
?>
							<body>

							<table width="900" border="0" cellspacing="0" cellpadding="0" align="center">
								<tr>
									<td>
        
									<div style="float:left">&nbsp;</div>
									<div style="clear:both;"></div>

										<center><legend><h2>ขอรหัสผ่านใหม่</h2></legend></center>
										<p>
											<div class="style5" style="width:auto; height:40px; padding-left:10px;">

											<table name="tb1" bgcolor="#70BCFFF" align="center" >

												<tr>
														<td align="right">Username : </td>  
														<td align="left"><?php echo $username; ?> </td>
														<td> </td>
												</tr>
<tr>
												<td> </td>
												<td> </td>
												<td> </td>
												</tr>												
												<tr>
														<td align="right">คุณ : </td>
														<td align="left"><?php echo $fname; ?></td>
														<td> </td>
												</tr>
												<tr>
												<td> </td>
												<td> </td>
												<td> </td>
												</tr>
												<tr>
														<td align="right">รหัสผ่านใหม่ของคุณคือ : </td>
														<td align="left"><font size="5" ><?php echo $genpass; ?></font></td>
														<td> </td>
												</tr>
												<tr>
												<td> </td>
												<td> </td>
												<td> </td>
												</tr>
												<tr>
														<td align="right">PIN : </td>
														<td align="left"><font size="5" ><?php echo $pin; ?></font></td>
														<td> </td>
												</tr>
												<tr>
												<td> </td>
												<td> </td>
												<td> </td>
												</tr>
												<tr>
												
														<td colspan="3" align="center" ><h1><textarea rows="5" cols="50" readonly="readonly" style="color:red;font-size:12pt"; >*โปรดจำรหัสผ่านนี้ไว้และรหัสผ่านของท่านจะได้รับการอนุมัติก็ต่อเมื่อท่านโทรกลับหาผู้ดูแลระบบและยืนยันเลข PIN แก่ผู้ดูแลระบบ </textarea><h1></td>
														
												</tr>
												<tr>
														<td colspan="2"  align="center"><input type="button" value=" รับทราบ " style="width:100px; height:35px;" onclick="parent.location.href='../../index.php'"></td>
														
												</tr>
											</div>
									</td>
								</tr>
							</table>
<?PHP
						
							}else{
	
								pg_query("ROLLBACK");
								echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm1.php\">";
								echo "<script type='text/javascript'>alert('ไม่สามารถขอ Password ได้ กรุณาลองใหม่ในภายหลัง')</script>";
								echo "Error Save $sql2";
								exit();
							}
						}else{
	
							pg_query("ROLLBACK");
							echo "<meta http-equiv=\"refresh\" content=\"0; URL=frm1.php\">";
							echo "<script type='text/javascript'>alert('ไม่สามารถขอ Password ได้ กรุณาลองใหม่ในภายหลัง')</script>";
							echo "Error Save $sql2";
							exit();
 
						}
			}
		}
}

 ?>