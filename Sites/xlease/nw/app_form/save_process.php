<?php
include("config/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>บันทึกข้อมูล::Application Form</title>
<link href="css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div align="center">
	<div class="main_container">
    	<ul>
        	<li class="login_title bold">การแจ้งเตือน :: Application Form</li>
<?php
$company_name = chk_null(pg_escape_string($_POST['company_name']));
$date = chk_null(pg_escape_string($_POST['date']));
$director_name = chk_null(pg_escape_string($_POST['director_name']));
$company_address = chk_null(pg_escape_string($_POST['company_address']));
$vat_place = chk_null(pg_escape_string($_POST['vat_place']));
$equipment_place = chk_null(pg_escape_string($_POST['equipment_place']));
$customer_name = chk_null(pg_escape_string($_POST['customer_name']));
$telephone = chk_null(pg_escape_string($_POST['telephone']));
$fax = chk_null(pg_escape_string($_POST['fax']));
$dealer_customer = chk_null(pg_escape_string($_POST['dealer_customer']));
$pay_history = chk_null(pg_escape_string($_POST['pay_history']));
$customer_time = chk_null(pg_escape_string($_POST['customer_time']));
$all_cpm = chk_null(pg_escape_string($_POST['all_cpm']));
$bill_condition = chk_null(pg_escape_string($_POST['bill_condition']));
$cheque_condition = chk_null(pg_escape_string($_POST['cheque_condition']));
$down_payment = chk_null(pg_escape_string($_POST['down_payment']));
$financial_amount = chk_null(pg_escape_string($_POST['financial_amount']));
$term_years = chk_null(pg_escape_string($_POST['term_years']));
$model_name = $_POST['model_name'];	//array
if(sizeof($model_name)!=0)
{
	$model_name = chk_null(implode(",",array_filter($model_name)));
}
$ppu = $_POST['ppu'];	//array
if(sizeof($ppu)!=0)
{
	$ppu = chk_null(implode(",",array_filter($ppu)));
}
$quantity = $_POST['quantity'];	//array
if(sizeof($quantity)!=0)
{
	$quantity = chk_null(implode(",",array_filter($quantity)));
}
$pputotal = chk_null(pg_escape_string($_POST['pputotal']));
$quantitytotal = chk_null(pg_escape_string($_POST['quantitytotal']));
$owner = chk_null(pg_escape_string($_POST['owner']));
$payment_step = chk_null(pg_escape_string($_POST['payment_step']));
$remainder_price = chk_null(pg_escape_string($_POST['remainder_price']));
$machine_type = chk_null(pg_escape_string($_POST['machine_type']));
$ipm = chk_null(pg_escape_string($_POST['ipm']));
$commencement_date = chk_null(pg_escape_string($_POST['commencement_date']));
$ied = chk_null(pg_escape_string($_POST['ied']));
$dofi = chk_null(pg_escape_string($_POST['dofi']));
$bondsman1_name = chk_null(pg_escape_string($_POST['bondsman1_name']));
$bondsman1_address = chk_null(pg_escape_string($_POST['bondsman1_address']));
$bondsman2_name = chk_null(pg_escape_string($_POST['bondsman2_name']));
$bondsman2_address = chk_null(pg_escape_string($_POST['bondsman2_address']));
$personal_attach = $_POST['personal_attach'];	//array
if(sizeof($personal_attach)!=0)
{
	$personal_attach = implode(",",array_filter($personal_attach));
}
$personal_attach = chk_null($personal_attach);
$corperate_attach = $_POST['corperate_attach'];	//array
if(sizeof($corperate_attach)!=0)
{
	$corperate_attach = implode(",",array_filter($corperate_attach));
}
$corperate_attach = chk_null($corperate_attach);
$personal_doc = array();	//array
for($i=0;$i<count($_FILES["personal_doc"]["name"]);$i++)
{
	if($_FILES["personal_doc"]["name"][$i] != "")
	{
		$personal_doc[] = $_FILES["personal_doc"]["name"][$i];
	}
}
$per_doc = "";
if(sizeof($personal_doc)!=0)
{
	$per_doc = implode(",",array_filter($personal_doc));
}
$corperate_doc = array();	//array
for($i=0;$i<count($_FILES["corperate_doc"]["name"]);$i++)
{
	if($_FILES["corperate_doc"]["name"][$i] != "")
	{
		$corperate_doc[] = $_FILES["corperate_doc"]["name"][$i];
	}
}
$cor_doc = "";
if(sizeof($corperate_doc)!=0)
{
	$cor_doc = implode(",",array_filter($corperate_doc));
}
$upload_file = "";
if($per_doc!=""&&$cor_doc!="")
{
	$upload_file = $per_doc.",".$cor_doc;
}
else
{
	if($cor_doc=="")
	{
		$upload_file = $per_doc;
	}
	else if($per_doc=="")
	{
		$upload_file = $cor_doc;
	}
}
$upload_file = chk_null($upload_file);
$time = date("YmdHis");

$folder = md5($_SESSION['app_username'].$time);
$folder1 = "upload/".$folder;
$doer = $_SESSION['app_userid'];
$doer_time = date("Y-m-d H:i:s");
pg_query("BEGIN");
$q = "insert into $schema.\"app_frm\"(\"company_name\",\"form_date\",\"director_name\",\"company_address\",\"vat_place\",\"equipment_place\",\"customer_name\",\"telephone\",\"fax\",\"dealer_customer\",\"pay_history\",\"customer_time\",\"all_cpm\",\"bill_condition\",\"cheque_condition\",\"down_payment\",\"financial_amount\",\"term_years\",\"model_name\",\"ppu\",\"quantity\",\"pputotal\",\"quantitytotal\",\"owner\",\"payment_step\",\"remainder_price\",\"machine_type\",\"ipm\",\"commencement_date\",\"ied\",\"dofi\",\"bondsman1_name\",\"bondsman1_address\",\"bondsman2_name\",\"bondsman2_address\",\"personal_attach\",\"corperate_attach\",\"upload_path\",\"upload_file\",\"doer_time\",\"doer\") values($company_name,$date,$director_name,$company_address,$vat_place,$equipment_place,$customer_name,$telephone,$fax,$dealer_customer,$pay_history,$customer_time,$all_cpm,$bill_condition,$cheque_condition,$down_payment,$financial_amount,$term_years,$model_name,$ppu,$quantity,$pputotal,$quantitytotal,$owner,$payment_step,$remainder_price,$machine_type,$ipm,$commencement_date,$ied,$dofi,$bondsman1_name,$bondsman1_address,$bondsman2_name,$bondsman2_address,$personal_attach,$corperate_attach,'$folder1',$upload_file,'$doer_time','$doer')";
$qr = pg_query($q);
$status = 0;
if($qr)
{
	if(!file_exists("upload")&&!is_dir("upload"))
	{
		mkdir("upload", 0777);
	}
	mkdir("upload/$folder",0777);
	for($i=0;$i<count($_FILES["personal_doc"]["name"]);$i++)
	{
		if($_FILES["personal_doc"]["name"][$i] != "")
		{
			if(!move_uploaded_file($_FILES["personal_doc"]["tmp_name"][$i],$folder1."/".iconv("UTF-8","TIS-620",$_FILES["personal_doc"]["name"][$i])))
			{
				$status = 1;
			}
			else
			{
				$status = 0;
			}
		}
	}
	for($i=0;$i<count($_FILES["corperate_doc"]["name"]);$i++)
	{
		if($_FILES["corperate_doc"]["name"][$i] != "")
		{
			if(!move_uploaded_file($_FILES["corperate_doc"]["tmp_name"][$i],$folder1."/".iconv("UTF-8","TIS-620",$_FILES["corperate_doc"]["name"][$i])))
			{
				$status = 1;
			}
			else
			{
				$status = 0;
			}
		}
	}
	if($status==1)
	{
		pg_query("ROLLBACK");
		echo "<li class=\"alert\">ไม่สามารถอัพโหลดได้</li>";
		echo "<li class=\"alert\"><span class=\"btn primary\" onclick=\"window.location.href = 'home.php';\">ตกลง</span></li>";
	}
	else
	{
		pg_query("COMMIT");
		echo "<li class=\"alert\">บันทึกข้อมูลเรียบร้อยแล้ว</li>";
		echo "<li class=\"alert\"><span class=\"btn primary\" onclick=\"window.location.href = 'home.php';\">ตกลง</span></li>";
	}
}
else
{
	echo $q;
}
?>
		</ul>
	</div>
</div>
</body>
</html>