<?php
$ys=date("Y");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<form method="post" action="list_fp_year.php">
<select name="f_year">
<?php

for($i=2002;$i<=$ys;$i++)
 {
?>
  <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
<?php 
 }
?>

</select>
<input type="submit" value="NEXT" />
</form>
</body>
</html>
