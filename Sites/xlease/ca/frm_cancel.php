<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title><?php echo $_SESSION["session_company_name"]; ?></title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
</head>
<body>
 
<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
    <tr>
        <td>

<fieldset><legend><B>ยกเลิกใบเสร็จ</B></legend>

<div style="text-align:center">
<input name="button" type="button" onclick="window.location='frm_cc_rec.php'" value="ยกเลิกทั่วไป" />
<input name="button" type="button" onclick="window.location='frm_cc_rec_fail.php'" value="เงินโอนที่ออกผิดเลขที่สัญญา" />
</div> 

</fieldset> 


        </td>
    </tr>
</table>         

</body>
</html>
