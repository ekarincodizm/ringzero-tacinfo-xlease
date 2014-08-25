<?php
session_start();
$idno = pg_escape_string($_POST['idno_names']);
$form_radio=pg_escape_string($_POST['frm_radio_fr']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
    <script type="text/javascript" src="autocomplete.js"></script>  
    <link rel="stylesheet" href="autocomplete.css"  type="text/css"/>  
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<div class="wrapper"> 

<div style="float:left">
<?php
	if($form_radio=="frm_radio_fr"){
	?>
	<input name="button" type="button" onclick="window.location='frm_radio_fr.php'" value="   กลับ   " />
	<?php
	}else{
?>
	<input name="button" type="button" onclick="window.location='frm_recid_fr.php'" value="   กลับ   " />
<?php }?>
</div>
<div style="float:right">
<input type="button" value="  Close  " onclick="javascript:window.close()">
</div> 
<div style="clear:both"></div>

<fieldset><legend><B>พิมพ์ใบเสร็จ</B></legend>

<table width="100%" border="0" cellSpacing="0" cellPadding="5" align="center">
    <tr align="center">
      <td>
<?php echo pg_escape_string($_POST['idno']); ?>
<br><br>
<input name="button" type="button" onclick="window.location='frm_recid_fr_fr.php?idno=<?php echo $idno; ?>'" value="     ค่างวด     " />
<input name="button" type="button" onclick="window.location='frm_recid_fr_other.php?idno=<?php echo $idno; ?>'" value="     ค่าือื่นๆ     " />
<input name="button" type="button" onclick="window.location='frm_recid_fr_vat.php?idno=<?php echo $idno; ?>'" value=" ใบกำกับภาษี " />
      </td>
   </tr>
</table>

</fieldset> 

</div>
        </td>
    </tr>
</table>         
 


</body>
</html>