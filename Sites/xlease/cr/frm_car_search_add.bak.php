<?php
session_start();
include("../config/config.php");

$get_id_user = $_SESSION["av_iduser"];
if(!empty($_POST['mm'])){$mm = pg_escape_string($_POST['mm']);}
if(!empty($_POST['yy'])){$yy = pg_escape_string($_POST['yy']);}
$smeter = pg_escape_string($_POST['smeter']);
$gdate = $yy."-".$mm."-01";
$cid = pg_escape_string($_POST['cid']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>AV.LEASING</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
    <tr>
        <td>

<div class="header"><h1>ระบบทะเบียนรถ</h1></div>
<div class="wrapper" align="center">

<?php
pg_query("BEGIN WORK");

function month_lob($c_m,$c_n){
    $mmm = $c_m-$c_n;
    if($mmm < 1){
        if($mmm == 0){
            $mmm = 12;
        }else{
            $mmm = 12-abs($mmm);
        }
    }
    return $mmm;
}

function month_plus($c_m,$c_n){
    $mmm = $c_m+$c_n;
    if($mmm > 12){
        $mmm = $mmm-12;
    }
    return $mmm;
}   

$mm_4 = month_plus($mm,4);
$mm_6 = month_plus($mm,6);
$mm_8 = month_plus($mm,8);
$mm_12 = month_plus($mm,12);

$a_year = $yy;
$a_month = $mm;

$status = 0;
$inb = 0;
$qry_if=pg_query("SELECT * FROM \"VCarregistemp\" WHERE \"C_StartDate\" is not null AND \"C_YEAR\" is not null ORDER BY \"C_REGIS\" ASC");
$rows = pg_num_rows($qry_if);
while($res_if=pg_fetch_array($qry_if)){
    $C_REGIS = $res_if["C_REGIS"];
    $CarID = $res_if["CarID"];
    $C_YEAR = $res_if["C_YEAR"];
    $C_StartDate = $res_if["C_StartDate"];
    $IDNO = $res_if["IDNO"];
    $full_name = $res_if["full_name"];
	
    if (!in_array($C_REGIS, $cid)){
        continue;
    }
    
    list($a_styear,$a_stmonth,$a_stday) = split('-',$C_StartDate);

    $plusyear = $a_year - $C_YEAR;
    if($plusyear < 7){
        $numplus = 6;
        $numplus_2 = 12;
        $numplus_3 = 6;
    }elseif($plusyear == 7){
        if($a_stmonth >= $a_month){
            $numplus = 6;
            $numplus_2 = 12;
            $numplus_3 = 6;
        }else{
            $numplus = 4;
            $numplus_2 = 8;
            $numplus_3 = 12;
        }
    }else{
        $numplus = 4;
        $numplus_2 = 8;
        $numplus_3 = 12;
    }
    
    $date_check_1 = date("Y-m-d", strtotime("+$numplus month",strtotime($C_StartDate)));
    $date_check_2 = date("Y-m-d", strtotime("+$numplus_2 month",strtotime($C_StartDate)));
    $date_check_3 = date("Y-m-d", strtotime("+$numplus_3 month",strtotime($C_StartDate)));
    
    list($n_styear_1,$n_stmonth_1,$n_stday_1) = split('-',$date_check_1);
    list($n_styear_2,$n_stmonth_2,$n_stday_2) = split('-',$date_check_2);
    list($n_styear_3,$n_stmonth_3,$n_stday_3) = split('-',$date_check_3);
    
    if($n_stmonth_1 == $mm || $n_stmonth_2 == $mm || $n_stmonth_3 == $mm){
        if($n_stmonth_1 == $mm){
            $date_check = $date_check_1;
        }elseif($n_stmonth_2 == $mm){
            $date_check = $date_check_2;
        }elseif($n_stmonth_3 == $mm){
            $date_check = $date_check_3;
        }
        
        list($n_styear_c,$n_stmonth_c,$n_stday_c) = split('-',$date_check);
        $date_check = $a_year."-".$n_stmonth_c."-".$n_stday_c;
        
        $C_IDCarTax = 0;
        $qry_ccartax=pg_query("select COUNT(\"IDCarTax\") AS \"C_IDCarTax\" from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$IDNO' AND (\"TypeDep\" = '101' OR \"TypeDep\" = '105') AND \"TaxDueDate\" = '$date_check'");
        if($res_ccartax=pg_fetch_array($qry_ccartax)){
            $C_IDCarTax = $res_ccartax["C_IDCarTax"];
        }
        
        $TypeDep = 0;
        $qry_ccartax=pg_query("select \"TypeDep\" from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$IDNO' AND (\"TypeDep\" = '101' OR \"TypeDep\" = '105') AND \"TaxDueDate\" = '$date_check'");
        if($res_ccartax=pg_fetch_array($qry_ccartax)){
            $TypeDep = $res_ccartax["TypeDep"];
        }
        
        $TypeDepCHK = "";
        $qry_ccartax=pg_query("select \"TypeDep\" from carregis.\"CarTaxDue\" WHERE \"IDNO\"='$IDNO' AND (\"TypeDep\" = '101' OR \"TypeDep\" = '105') AND \"TaxDueDate\" != '$date_check' ORDER BY \"TaxDueDate\" DESC LIMIT 1");
        if($res_ccartax=pg_fetch_array($qry_ccartax)){
            $TypeDepCHK = $res_ccartax["TypeDep"];
        }
        
        if($C_IDCarTax == 0){
            
            if(empty($TypeDepCHK)){
                //$show_meter = "มิเตอร์";
                //$show_smeter = "300";
                $insert_type = "105";
            }elseif($TypeDepCHK == '101'){
                //$show_meter = "มิเตอร์";
                //$show_smeter = "300";
                $insert_type = "105";
            }elseif($TypeDepCHK == '105'){
                //$show_meter = "มิเตอร์/ภาษี";
                //$show_smeter = "1100";
                $insert_type = "101";
            }

            
            $g_id=pg_query("select carregis.gen_id('$gdate')");
            $res_g_id=pg_fetch_result($g_id,0);
            
            $in_sql="insert into carregis.\"CarTaxDue\" (\"IDCarTax\",\"IDNO\",\"TaxDueDate\",\"TypeDep\",\"CusAmt\") values  ('$res_g_id','$IDNO','$date_check','$insert_type','$smeter[$inb]')";
            if($result_in_sql=pg_query($in_sql)){

            }else{
                $status += 1;
            }
            
            $inb += 1;

        }
    }
}

if($status == 0){
    pg_query("COMMIT");
    echo "บันทึกข้อมูลเรียบร้อยแล้ว";
}else{
    pg_query("ROLLBACK");
    echo "ไม่สามารถบันทึกข้อมูลได้";
}

?>

<br><br>
<input type="button" value="  Back  " onclick="location.href='frm_car_search.php'">

</div>

        </td>
    </tr>
</table>

</body>
</html>