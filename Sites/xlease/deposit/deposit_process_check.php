<?php
include("../config/config.php");

$cmd=pg_escape_string($_POST['cmd']);
$old_cusid=pg_escape_string($_POST['old_cusid']);
$old_idno=pg_escape_string($_POST['old_idno']);
$idno=pg_escape_string($_POST['idno']);
$old_asid=pg_escape_string($_POST['asid']);

if($cmd == "check_cusid")
{
    if($old_idno != $idno)
	{
        $qry_name=pg_query("select \"CusID\",\"asset_id\" from \"VContact\" WHERE \"IDNO\"='$idno'");
		$row_name = pg_num_rows($qry_name);
        if($res_name=pg_fetch_array($qry_name))
		{
            $CusID=$res_name["CusID"];
            $asset_id=$res_name["asset_id"];
        }
        
		if($row_name > 0)
		{
			if(($old_cusid == $CusID) || ($old_asid == $asset_id))
			{
				$data['success'] = true;
				$data['message'] = "เลขที่สัญญาถูกต้อง";
				$data['status'] = "1";
			}
			else
			{
				$data['success'] = false;
				$data['message'] = "เลขที่สัญญาไม่ถูกต้อง!";
				$data['status'] = "3";
			}
		}
		else
		{
			$data['success'] = false;
			$data['message'] = "ไม่พบเลขที่สัญญาในระบบ!";
			$data['status'] = "0";
		}
    }
	else
	{
        $data['success'] = false;
        $data['message'] = "เลขที่สัญญาซ้ำรายการหลัก!";
		$data['status'] = "2";
    }
}

echo json_encode($data);
?>