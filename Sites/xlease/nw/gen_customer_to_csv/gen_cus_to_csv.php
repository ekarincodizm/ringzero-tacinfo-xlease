<?php
include("../../config/config.php");

$date = date("Y_m_d_H_i_s");

function chk_null($str){
	if($str=="")
	{
		$str="NULL";
	}
	return $str;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Export Customer to CSV</title>
<link href="css/act.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="scripts/jquery-1.8.2.js"></script>
</head>
<body>
<div align="center">
    <div style="width:800px">
        <h2>Export Customer To CSV</h2>
        <hr />
        <div style="display:block; text-align:left; margin-top:20px;" id="result_msg">
        <?php
		$q = "select distinct a.\"A_FIRNAME\",a.\"A_NAME\",a.\"A_SIRNAME\",a.\"A_NO\",a.\"A_SUBNO\",a.\"A_SOI\",a.\"A_RD\",a.\"A_TUM\",a.\"A_AUM\",a.\"A_PRO\",a.\"A_POST\",a.\"A_MOBILE\",a.\"A_TELEPHONE\",b.\"N_IDCARD\" from \"Fa1\" a left join \"Fn\" b on a.\"CusID\"=b.\"CusID\" where a.\"Approved\"=TRUE";
		$qr = pg_query($q);
		if($qr)
		{
			$row = pg_num_rows($qr);
			if($row!=0)
			{
				$str = "";
				while($res = pg_fetch_array($qr))
				{
					$A_FIRNAME = chk_null($res['A_FIRNAME']);
					$A_NAME = chk_null($res['A_NAME']);
					$A_SIRNAME = chk_null($res['A_SIRNAME']);
					$A_NO = $res['A_NO'];
					$A_SUBNO = $res['A_SUBNO'];
					$A_SOI = $res['A_SOI'];
					$A_RD = $res['A_RD'];
					$A_TUM = chk_null($res['A_TUM']);
					$A_AUM = chk_null($res['A_AUM']);
					$A_PRO = chk_null($res['A_PRO']);
					$A_POST = chk_null($res['A_POST']);
					$A_MOBILE = chk_null(str_replace(",","",$res['A_MOBILE']));
					$N_IDCARD = chk_null($res['N_IDCARD']);
					$phone = "";
					$addr = "";
					if($A_MOBILE!="")
					{
						$phone = $A_MOBILE;
					}
					else
					{
						$phone = "NULL";
					}
					$phone = str_replace("-","",$phone);
					$phone = str_replace(" ","",$phone);
					if(strlen($phone)!=10)
					{
						$phone = "NULL";
					}
					if($A_NO!="")
					{
						$addr.=str_replace(" ","",$A_NO);
					}
					if($A_SUBNO!="")
					{
						$addr.=" หมู่ ".str_replace(" ","",$A_SUBNO);
					}
					if($A_SOI!="")
					{
						$addr.=" ซอย ".str_replace(" ","",$A_SOI);
					}
					if($A_RD!="")
					{
						$addr.=" ถนน ".str_replace(" ","",$A_RD);
					}
					$addr = chk_null($addr);
					
					$str .= str_replace(" ","",$A_FIRNAME).",".str_replace(" ","",$A_NAME).",".str_replace(" ","",$A_SIRNAME).",".$addr.",".str_replace(" ","",$A_TUM).",".str_replace(" ","",$A_AUM).",".str_replace(" ","",$A_PRO).",".str_replace(" ","",$A_POST).",".str_replace(" ","",$N_IDCARD).",".str_replace(" ","",$phone)."\n";
				}
				$text=iconv("UTF-8","",$str);
				$dir = "csv";
				if(!file_exists($dir)&&!is_dir($dir))
				{
					mkdir($dir, 0777);
				}
				$strFileName = $dir."/tel_text_".$date.".csv";
				$objFopen = fopen($strFileName, 'a');
				fwrite($objFopen, $text);
				if(fopen($strFileName,'a'))
				{
					echo "<h3>ทำรายการเรียบร้อยแล้ว  ท่านสามารถดาวน์โหลดไฟล์ได้จากลิงค์ด้านล่างครับ</h3><br /><br />";
					echo "<li style=\"text-decoration:underline;\"><a style=\"color:#444;\" href=\"".$strFileName."\">tel_text_".$date.".csv</a></li>";
				}
			}
			else
			{
				echo "<b>ไม่มีข้อมูลลูกค้าครับ</b>";
			}
		}
		else
		{
			echo "<b>เกิดข้อผิดพลาด  กรุณาติดต่อผู้ดูและระบบ</b>";
		}
		?>
        </div>
    </div>
</div>
</body>
</html>