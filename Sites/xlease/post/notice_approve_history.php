<?php
include("../config/config.php");

//ไม่สามารถใช้ร่วมกันได้  เนื่องจากแต่ละหน้าจะมีการส่งค่าไม่เท่ากัน
function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page){   
	global $e_page;
	//global $querystr;
	$urlfile="notice_approve_history.php"; // ส่วนของไฟล์เรียกใช้งาน ด้วย ajax (ajax_dat.php)
	$per_page=10;	//จำนวนหน้าที่แสดง
	$num_per_page=floor($chk_page/$per_page);
	$total_end_p=($num_per_page+1)*$per_page;
	$total_start_p=$total_end_p-$per_page;
	$pPrev=$chk_page-1;
	$pPrev=($pPrev>=0)?$pPrev:0;
	$pNext=$chk_page+1;
	$pNext=($pNext>=$total_p)?$total_p-1:$pNext;		
	$lt_page=$total_p-4;
	if($chk_page>0){  
		echo "<a  href=\"$urlfile?s_page=$pPrev\" class=\"naviPN\">ก่อนหน้า</a>";
	}
	for($i=$total_start_p;$i<$total_end_p;$i++){  
		$nClass=($chk_page==$i)?"class=\"selectPage\"":"";
		if($e_page*$i<$total){
		echo "<a href=\"$urlfile?s_page=$i\" $nClass  >".intval($i+1)."</a> ";   
		}
	}		
	if($chk_page<$total_p-1){
		echo "<a href=\"$urlfile?s_page=$pNext\"  class='naviPN'>ถัดไป</a>";
	}
}

$cur_page = $_GET['s_page'];
if($cur_page=="")
{
	$cur_page = 0;
}
$no = ($cur_page*30)+1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Approve Cancel NT Histories</title>

<link type="text/css" rel="stylesheet" href="act.css"></link>

<link type="text/css" href="../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    $(window).bind("beforeunload",function(event){
        window.opener.$('div#div_admin_menu').load('list_admin_menu.php');
    });    
});
</script>

<script language=javascript>
function popU(U,N,T){
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body>
<div align="center">
	<div style="width:1248px;">
    	<h2>Approve Cancel NT Histories</h2>
        <hr />
    	<table width="96%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#F0F0F0">
        <tr style="font-weight:bold;" valign="middle" bgcolor="#B5B5B5">
            <td align="center">ลำดับ</td>
            <td align="center">เลข NT</td>
            <td align="center">เลขที่สัญญา</td>
            <td align="center">ชื่อ</td>
            <td align="center">ทะเบียน</td>
            <td align="center">สีรถ</td>
            <td align="center">เหตุผล</td>
            <td align="center">ผู้ออกหนังสือ</td>
            <td align="center">ผู้ที่ยกเลิกหนังสือ</td>
            <td align="center">วันยกเลิกหนังสือ</td>
            <td align="center">ผู้อนุมัติ</td>
            <td align="center">วันเวลาที่อนุมัติ</td>
            <td align="center">สถานะ</td>
            <!--<td align="center">เหตุผล</td>-->
        </tr>
    
    <?php
            $q="		
			--อนุมัติ
			
			(
			SELECT e.\"runrow\",a.\"IDNO\",a.\"NTID\",a.\"cancel\",c.\"fullname\" as \"markfullname\",d.\"fullname\" as \"canfullname\",a.\"cancel_date\",f.\"fullname\" as \"appfullname\",e.\"app_date\",e.\"noteapp\"
			FROM \"NTHead\" a 
			LEFT JOIN \"nw_statusNT\" b ON a.\"NTID\" = b.\"NTID\"
			LEFT JOIN \"NTHead_log_notappvcancel\" e ON a.\"NTID\" = e.\"NTID\"
			LEFT JOIN \"Vfuser\" c ON a.\"makerid\" = c.\"id_user\"
			LEFT JOIN \"Vfuser\" d ON a.\"cancelid\" = d.\"id_user\"
			LEFT JOIN \"Vfuser\" f ON e.\"app_user\" = f.\"id_user\"	
			WHERE a.\"CusState\"='0' AND a.\"cancel\"='TRUE' AND a.\"cancelid\" IS NOT NULL 
			)
			
			
			UNION ALL
			
			--ไม่อนุมัติ
			
			(
			SELECT e.\"runrow\",a.\"IDNO\",a.\"NTID\",a.\"cancel\",c.\"fullname\" as \"markfullname\",d.\"fullname\" as \"canfullname\",e.\"cancel_date\",f.\"fullname\" as \"appfullname\",e.\"app_date\",e.\"noteapp\"
			FROM \"NTHead\" a 
			LEFT JOIN \"nw_statusNT\" b ON a.\"NTID\" = b.\"NTID\"
			LEFT JOIN \"NTHead_log_notappvcancel\" e ON a.\"NTID\" = e.\"NTID\"
			LEFT JOIN \"Vfuser\" c ON a.\"makerid\" = c.\"id_user\"
			LEFT JOIN \"Vfuser\" d ON e.\"cancelid_old\" = d.\"id_user\"
			LEFT JOIN \"Vfuser\" f ON e.\"app_user\" = f.\"id_user\"
			WHERE a.\"CusState\"='0' AND a.\"cancel\"='FALSE' AND  a.\"NTID\" IN ( 
			SELECT y.\"NTID\" FROM \"NTHead_log_notappvcancel\" y 
			LEFT JOIN \"NTHead\" z ON y.\"NTID\" = z.\"NTID\"
			WHERE z.\"CusState\"='0' AND y.\"noteapp\" is not null
			)
			)	
			ORDER BY \"app_date\" DESC NULLS LAST,\"cancel_date\" DESC";
			
			$qr=pg_query($q);
			$total=pg_num_rows($qr);
			$resultRows=pg_num_rows($qr);
			$e_page=30; // กำหนด จำนวนรายการที่แสดงในแต่ละหน้า   
			if(!isset($_GET['s_page'])){   
				$_GET['s_page']=0;   
			}else{   
				$chk_page=$_GET['s_page'];     
				$_GET['s_page']=$_GET['s_page']*$e_page;   
			}   
			$q.=" LIMIT $e_page offset ".$_GET['s_page'];
			$qr=pg_query($q);
			if(pg_num_rows($qr)>=1){   
				$plus_p=($chk_page*$e_page)+pg_num_rows($qr);   
			}else{   
				$plus_p=($chk_page*$e_page);       
			}   
			$total_p=ceil($total/$e_page);   
			$before_p=($chk_page*$e_page)+1;
    
            while($res=pg_fetch_array($qr)){
                $IDNO = $res["IDNO"];
                $NTID = $res["NTID"];
                $markfullname =  $res["markfullname"];
                $canfullname =  $res["canfullname"];
                $cancel_date =  $res["cancel_date"];
                $appfullname =  $res["appfullname"];
                $app_date =  $res["app_date"];
                $noteapp =  $res["noteapp"];
                $cancelstatus = $res["cancel"];
                $runrow = $res["runrow"];
                IF($noteapp != ""){
                    $statustxt = 'ไม่อนุมัติ';
                }ELSE{
                    $statustxt = 'อนุมัติ';								
                }
                
                
                $qry_vc=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
                if($res_vc=pg_fetch_array($qry_vc)){
                    $full_name = $res_vc["full_name"];
                    $C_COLOR = $res_vc["C_COLOR"];
                    $asset_type = $res_vc["asset_type"];
                    $C_REGIS = $res_vc["C_REGIS"];
                    $car_regis = $res_vc["car_regis"];
                    
                    if($asset_type == 1) $show_regis = $C_REGIS; else $show_regis = $car_regis;
                }
    
                $i+=1;
                if($i%2==0){
                    echo "<tr bgcolor=#CFCFCF onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#CFCFCF';\" align=center>";
                }else{
                    echo "<tr bgcolor=#E8E8E8 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#E8E8E8';\" align=center>";
                }
            ?>
            <td align="center"><?php echo $no; ?></td>
            <td align="center">
                <font color="blue">
                    <a onclick="javascript:popU('../nw/showNT/notice_reprint_pdf.php?idno=<?php echo $IDNO; ?>&ntid=<?php echo $NTID?>','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="รายละเอียด NT">
                        <u>
                            <?php echo $NTID; ?>
                        </u>
                    </a>
                </font>	
            </td>
            <td align="center">
                <font color="blue">
                    <a onclick="javascript:popU('../post/frm_viewcuspayment.php?idno_names=<?php echo $IDNO; ?>&type=outstanding','<?php echo "$IDNO_outstanding"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1000,height=800')" style="cursor: pointer;" title="ดูตารางการชำระ">
                        <u>
                            <?php echo $IDNO; ?>
                        </u>
                    </a>
                </font>	
            </td>
            <td align="left"><?php echo $full_name; ?></td>
            <td align="left"><?php echo $show_regis; ?></td>
            <td align="left"><?php echo $C_COLOR; ?></td>
            <td align="center">
                <font color="blue">
                    <a onclick="javascript:popU('notice_approve_remark.php?ntid=<?php echo "$NTID"; ?>','<?php echo "$NTID_approve_remark"; ?>','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=250')" style="cursor: pointer;" title="Re Print">
                        <u>
                            รายละเอียด
                        </u>
                    </a>
                </font>	
            </td>
            <td align="left"><?php echo $markfullname; ?></td>
            <td align="left"><?php echo $canfullname; ?></td>
            <td align="center"><?php echo $cancel_date; ?></td>
            <td align="left"><?php echo $appfullname; ?></td>
            <td align="center"><?php echo $app_date; ?></td>
            <td align="center"><?php echo $statustxt; ?></td>
           <!-- <?php if($noteapp == ""){ ?>
            <td align="center"></td>								
            <?php }else{ ?>		
            <td align="center"><a onclick="javascript:popU('frm_notepop.php?runrow=<?php echo $runrow; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=250')" style="cursor:pointer;" title="note"><u>แสดงเหตุผล</u></a></td>
            <?php } ?> -->
            
        </tr>
    <?php
        unset($statustxt);
        //บวกจำนวนอันดับ
            $no++;
        
        }//ปิด While
    
        if($total == 0){
            echo "<tr><td colspan=14 align=center bgcolor=#B5B5B5>- ไม่พบข้อมูล -</td></tr>";
        }
		else
		{
			echo "
				<tr>
					<td colspan=\"14\" align=\"right\">
						<div class=\"browse_page\">
				 ";
						 // เรียกใช้งานฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
						page_navigator($before_p,$plus_p,$total,$total_p,$chk_page);
			echo "
						</div>
					</td>
				</tr>
			";
		}
    ?>
    </table>
    </div>
</div>
</body>
</html>