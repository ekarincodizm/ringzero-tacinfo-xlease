<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tranfer Data mssql to postgresql</title>
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>
</head>

<body>
<p>&nbsp;</p>
<table width="700" border="0" cellpadding="0" cellspacing="1" style="background-color:#CCCCCC;">
  <tr>
    <td colspan="3"><div align="center"><strong>Mssql to Postgres</strong></div></td>
  </tr>
  <tr style="background-color:#EDF1DA" >
    <td>1.</td>
    <td><button onclick="MM_openBrWindow('start_number.php','','width=600,height=400')">Start number_id</button></td>
    <td><button onclick="MM_openBrWindow('del_mstable.php?id=<?php echo "number_id"; ?> ','','width=600,height=400')">Empty number_id</button></td>
  </tr>
  <tr style="background-color:#EDF1DA">
    <td width="27">2..</td>
    <td width="433" style="padding:3px;"><button onclick="MM_openBrWindow('list_fa1.php','','width=600,height=400')">ms.fa1 to fill_CusID</button> </td>
    <td width="226" rowspan="2"><button onclick="MM_openBrWindow('del_mstable.php?id=<?php echo "fill_CusID"; ?> ','','width=600,height=400')">Empty  fill_CusID</button></td>
  </tr>
  
  <tr style="background-color:#EDF1DA">
    <td>3.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('list_fa2.php','','width=600,height=400')">ms.fa2 to fill_CusID</button></td>
  </tr>
  <tr style="background-color:#EDF1DA;">
    <td>4.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('list_fc.php','','width=600,height=400')" >ms.Fc to fill_CarID</button></td>
    <td><button onclick="MM_openBrWindow('del_mstable.php?id=<?php echo "fill_CarID"; ?> ','','width=600,height=400')">Empty  fill_CarID</button></td>
  </tr>
  <tr style="background-color:#EDF1DA;">
    <td>5.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('list_fcfgas.php','','width=600,height=400')">gen asset to fill_assetID</button></td>
    <td><button onclick="MM_openBrWindow('del_mstable.php?id=<?php echo "fill_assetID"; ?> ','','width=600,height=400')">Empty  fill_assetID</button></td>
  </tr>
  <tr style="background-color:#EDF1DA;">
    <td>6.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('ins_fp.php','','width=600,height=400')">insert to pg.Fp</button> </td>
    <td><button  onclick="MM_openBrWindow('del_pgtable.php?id=<?php echo "Fp"; ?> ','','width=600,height=400')">Empty  pg.Fp</button></td>
  </tr>
   <tr style="background-color:#EDF1DA;">
    <td>7.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('ins_fa1.php','','width=600,height=400')">insert to pg.Fa1</button></td>
    <td><button onclick="MM_openBrWindow('del_pgtable.php?id=<?php echo "Fa1"; ?> ','','width=600,height=400')">Empty  pg.Fa1-Fn</button></td>
  </tr>
   <tr style="background-color:#EDF1DA;">
    <td>8.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('ins_cc.php','','width=600,height=400')">insert to pg.ContacCus</button></td>
    <td><button onclick="MM_openBrWindow('del_pgtable.php?id=<?php echo "ContactCus"; ?> ','','width=600,height=400')">Empty  pg.ContactCus</button></td>
  </tr>
   <tr style="background-color:#EDF1DA;">
    <td>9.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('ins_car.php','','width=600,height=400')">insert to pg.Fc-FGas</button></td>
    <td><button onclick="MM_openBrWindow('del_pgtable.php?id=<?php echo "Fc"; ?> ','','width=600,height=400')">Empty  pg.Fc-FGas</button></td>
  </tr>
  <tr style="background-color:#EDF1DA;">
    <td>10.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('move_fr.php','','width=600,height=400')">insert to pg.Fr</button></td>
    <td><button onclick="MM_openBrWindow('del_pgtable.php?id=<?php echo "Fr"; ?> ','','width=600,height=400')">Empty  pg.Fr</button></td>
  </tr>
  <tr style="background-color:#EDF1DA;">
    <td>11.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('move_fvat.php','','width=600,height=400')">insert to pg.FVat</button></td>
    <td><button onclick="MM_openBrWindow('del_pgtable.php?id=<?php echo "FVat"; ?> ','','width=600,height=400')">Empty  pg.FVat</button></td>
  </tr>
  <tr style="background-color:#EDF1DA;">
    <td>12.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('move_fr2.php','','width=600,height=400')">FOtherPay+Vat</button></td>
    <td><button onclick="MM_openBrWindow('del_pgtable.php?id=<?php echo "Fr"; ?> ','','width=600,height=400')">Empty  pg.Fr</button></td>
  </tr>
  <tr style="background-color:#EDF1DA;">
    <td>13.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('move_fvat2.php','','width=600,height=400')">VatOtherPay</button></td>
    <td><button onclick="MM_openBrWindow('del_pgtable.php?id=<?php echo "FVat"; ?> ','','width=600,height=400')">Empty  pg.FVat</button></td>
  </tr>
   <tr style="background-color:#EDF1DA;">
    <td>14.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('ins_old_idnofp.php','','width=600,height=400')">Update OLD_IDNO</button></td>
    <td></td>
  </tr>
  <tr style="background-color:#EDF1DA;">
    <td>15.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('move_fcontrol.php','','width=600,height=400')">FControl</button></td>
    <td><button onclick="MM_openBrWindow('del_pgtable.php?id=<?php echo "FollowUpCus"; ?> ','','width=600,height=400')">Empty  pg.FollowUpCus</button></td>
  </tr>
  <tr style="background-color:#EDF1DA;">
    <td>16.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('move_fotherpay.php','','width=600,height=400')">FOtherpay</button></td>
    <td><button onclick="MM_openBrWindow('del_pgtable.php?id=<?php echo "FOtherpay"; ?> ','','width=600,height=400')">Empty  pg.FOtherpay</button></td>
  </tr>
  <tr style="background-color:#EDF1DA;">
    <td>17.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('move_freceiptno.php','','width=600,height=400')">FReceiptNO</button></td>
    <td><button onclick="MM_openBrWindow('del_pgtable.php?id=<?php echo "FReceiptNO"; ?> ','','width=600,height=400')">Empty  pg.FReceiptNO</button></td>
  </tr>
  <tr style="background-color:#EDF1DA;">
    <td>18.</td>
    <td style="padding:3px;"><button onclick="MM_openBrWindow('move_fotherpay_new.php','','width=600,height=400')">FOtherpay + Fr</button></td>
    <td></td>
  </tr>
  
   <tr style="background-color:#EDF1DA;">
     <td></td>
     <td>&nbsp;</td>
     <td>
     </td>
   </tr>
</table>
<p>&nbsp;</p>
</body>
</html>
