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
$id = $_GET['id'];

$q_log = "insert into application_form.\"app_frm_log\"(\"formid\",\"company_name\",\"form_date\",\"director_name\",\"company_address\",\"vat_place\",\"equipment_place\",\"customer_name\",\"telephone\",\"fax\",\"dealer_customer\",\"pay_history\",\"customer_time\",\"all_cpm\",\"bill_condition\",\"cheque_condition\",\"down_payment\",\"financial_amount\",\"term_years\",\"model_name\",\"ppu\",\"quantity\",\"pputotal\",\"quantitytotal\",\"owner\",\"payment_step\",\"remainder_price\",\"machine_type\",\"ipm\",\"commencement_date\",\"ied\",\"dofi\",\"bondsman1_name\",\"bondsman1_address\",\"bondsman2_name\",\"bondsman2_address\",\"personal_attach\",\"corperate_attach\",\"upload_path\",\"upload_file\",\"doer_time\",\"doer\",\"form_state\") 
select \"formID\",\"company_name\",\"form_date\",\"director_name\",\"company_address\",\"vat_place\",\"equipment_place\",\"customer_name\",\"telephone\",\"fax\",\"dealer_customer\",\"pay_history\",\"customer_time\",\"all_cpm\",\"bill_condition\",\"cheque_condition\",\"down_payment\",\"financial_amount\",\"term_years\",\"model_name\",\"ppu\",\"quantity\",\"pputotal\",\"quantitytotal\",\"owner\",\"payment_step\",\"remainder_price\",\"machine_type\",\"ipm\",\"commencement_date\",\"ied\",\"dofi\",\"bondsman1_name\",\"bondsman1_address\",\"bondsman2_name\",\"bondsman2_address\",\"personal_attach\",\"corperate_attach\",\"upload_path\",\"upload_file\",\"doer_time\",\"doer\",\"form_state\" from application_form.\"app_frm\" where \"formID\"='$id'";
pg_query($q_log) or die("ไม่สามารถบันทึกประวัติได้");

$qs = "select \"upload_path\",\"upload_file\" from $schema.\"app_frm\" where \"formID\"='$id'";
$qrs = pg_query($qs);
$rss = pg_fetch_array($qrs);

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
	$model_name = "'".implode(",",array_filter($model_name))."'";
}
else
{
	$model_name = "null";
}
$ppu = $_POST['ppu'];	//array
if(sizeof($ppu)!=0)
{
	$ppu = "'".implode(",",array_filter($ppu))."'";
}
else
{
	$ppu = "null";
}
$quantity = $_POST['quantity'];	//array
if(sizeof($quantity)!=0)
{
	$quantity = "'".implode(",",array_filter($quantity))."'";
}
else
{
	$quantity = "null";
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
	$personal_attach = "'".implode(",",array_filter($personal_attach))."'";
}
else
{
	$personal_attach = "null";
}
$corperate_attach = $_POST['corperate_attach'];	//array
if(sizeof($corperate_attach)!=0)
{
	$corperate_attach = "'".implode(",",array_filter($corperate_attach))."'";
}
else
{
	$corperate_attach = "null";
}
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
$upload_file = $rss['upload_file'];
if($per_doc!=""&&$cor_doc!="")
{
	if($upload_file=="")
	{
		$upload_file = $per_doc.",".$cor_doc;
	}
	else
	{
		$upload_file.=",".$per_doc.",".$cor_doc;
	}
}
else
{
	if($cor_doc=="")
	{
		if($upload_file=="")
		{
			$upload_file = $per_doc;
		}
		else
		{
			$upload_file.=",".$per_doc;
		}
	}
	else if($per_doc=="")
	{
		if($upload_file=="")
		{
			$upload_file = $cor_doc;
		}
		else
		{
			$upload_file.=",".$cor_doc;
		}
	}
}

$upload_file = "'".$upload_file."'";
$time = date("YmdHis");
$folder1 = $rss['upload_path'];
$doer = $_SESSION['app_userid'];
$doer_time = date("Y-m-d H:i:s");
$form_state = 2;

$answer = $_POST['txtarea_edit_details'];
if($answer!="")
{
	$q_quest = "select max(\"questionID\") as questionID from $schema.\"questions\" where \"formID\"='$id'";
	$qr_quest = pg_query($q_quest);
	$rs_quest = pg_fetch_array($qr_quest);
	$questionID = $rs_quest['questionID'];
	$q_ans = "insert into $schema.\"answers\"(\"questionID\",\"answer\",\"ans_date\",\"answer_by\") values('$questionID','$answer','$doer_time','$doer')";
	pg_query($q_ans);
}

if(!file_exists($folder1)&&!is_dir($folder1))
{
	mkdir($folder1,0777);
}

pg_query("BEGIN");
$q = "update $schema.\"app_frm\" set \"company_name\"=$company_name, \"form_date\"=$date, \"director_name\"=$director_name, \"company_address\"=$company_address, \"vat_place\"=$vat_place, \"equipment_place\"=$equipment_place, \"customer_name\"=$customer_name, \"telephone\"=$telephone, \"fax\"=$fax, \"dealer_customer\"=$dealer_customer, \"pay_history\"=$pay_history, \"customer_time\"=$customer_time, \"all_cpm\"=$all_cpm, \"bill_condition\"=$bill_condition, \"cheque_condition\"=$cheque_condition, \"down_payment\"=$down_payment, \"financial_amount\"=$financial_amount, \"term_years\"=$term_years, \"model_name\"=$model_name, \"ppu\"=$ppu, \"quantity\"=$quantity, \"pputotal\"=$pputotal, \"quantitytotal\"=$quantitytotal, \"owner\"=$owner, \"payment_step\"=$payment_step, \"remainder_price\"=$remainder_price, \"machine_type\"=$machine_type, \"ipm\"=$ipm, \"commencement_date\"=$commencement_date, \"ied\"=$ied, \"dofi\"=$dofi, \"bondsman1_name\"=$bondsman1_name, \"bondsman1_address\"=$bondsman1_address, \"bondsman2_name\"=$bondsman2_name, \"bondsman2_address\"=$bondsman2_address, \"personal_attach\"=$personal_attach, \"corperate_attach\"=$corperate_attach,\"upload_path\"='$folder1', \"upload_file\"=$upload_file, \"doer_time\"='$doer_time', \"doer\"='$doer', \"form_state\"='$form_state' where \"formID\"='$id'";
$qr = pg_query($q);
$status = 0;
if($qr)
{
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
?>
		</ul>
	</div>
</div>
</body>
</html>