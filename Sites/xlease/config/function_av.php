<?php

function ThaiDate($daystr)
	{
		if (trim($daystr) == "")
		{
			return "";
		}
		else
		{
			$d = explode("/" , $daystr);
			$d[2] = $d[2] + 543;
		
			return $d[0] . "/" . $d[1] . "/" . $d[2];
		}
	}



function list_User($table,$condition)
{
$sql = "select * from  $table  $condition ";
$qy=pg_query($sql);
$result=pg_fetch_array($qy);
return $result;
pg_close();
}

function list_menu_user()
{
$sql = "select * from f_usermenu where status=1";
$qy=pg_query($sql);

}



?>