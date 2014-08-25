<?php
include('../../config/config.php');
//Include The Database Connection File
if(isset($_POST['chkpoint']))
{
	if($_POST['chkpoint']=="user")
	{
		if(isset($_POST['username']))//If a username has been submitted
		{
			$username = $_POST['username'];//Some clean up :)
			
			$check_for_username = pg_query("SELECT \"username\" FROM carsystem.\"members\" WHERE \"username\"='$username'");
			//Query to check if username is available or not
		
			if(pg_num_rows($check_for_username))
			{
				echo '1';//If there is a&nbsp; record match in the Database - Not Available
			}
			else
			{
				echo '0';//No Record Found - Username is available
			}
		}
	}
	if($_POST['chkpoint']=="showname")
	{
		if(isset($_POST['showname']))//If a username has been submitted
		{
			$showname = $_POST['showname'];//Some clean up :)
			
			$check_for_showname = pg_query("SELECT \"showname\" FROM carsystem.\"members\" WHERE \"showname\"='$showname'");
			//Query to check if username is available or not
		
			if(pg_num_rows($check_for_showname))
			{
				echo '1';//If there is a&nbsp; record match in the Database - Not Available
			}
			else
			{
				echo '0';//No Record Found - Username is available
			}
		}
	}
	if($_POST['chkpoint']=="email")
	{
		if(isset($_POST['email']))//If a username has been submitted
		{
			$email = $_POST['email'];//Some clean up :)
			
			$check_for_email = pg_query("SELECT \"email_address\" FROM carsystem.\"members\" WHERE \"email_address\"='$email'");
			//Query to check if username is available or not
		
			if(pg_num_rows($check_for_email))
			{
				echo '1';//If there is a&nbsp; record match in the Database - Not Available
			}
			else
			{
				echo '0';//No Record Found - Username is available
			}
		}
	}
	if($_POST['chkpoint']=="mobile")
	{
		if(isset($_POST['mobile']))//If a username has been submitted
		{
			$mobile = $_POST['mobile'];//Some clean up :)
			
			$check_for_mobile = pg_query("SELECT \"mobilephone\" FROM carsystem.\"members\" WHERE \"mobilephone\"='$mobile'");
			//Query to check if username is available or not
		
			if(pg_num_rows($check_for_mobile))
			{
				echo '1';//If there is a&nbsp; record match in the Database - Not Available
			}
			else
			{
				echo '0';//No Record Found - Username is available
			}
		}
	}
}
?>