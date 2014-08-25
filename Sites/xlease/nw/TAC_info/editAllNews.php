<?php
	session_start();
	include("config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	
	pg_query("BEGIN");
	$status=0;
	
	$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host     = $_SERVER['HTTP_HOST'];
    $script   = $_SERVER['SCRIPT_NAME'];
    $params   = $_SERVER['QUERY_STRING'];
	if($params=="")
	{
		$currentUrl = $protocol . '://' . $host . $script;
	}
	else
	{
    	$currentUrl = $protocol . '://' . $host . $script . '?' . $params;
	}
	
	$server=$_SERVER["REMOTE_ADDR"];
	$visitDate=date("Y-m-d H:i:s");
	$visitor="";
	
	if(isset($_SESSION['username']))
	{
		$sql="select * from \"TrMember\" where \"Username\"='".$_SESSION['username']."'";
		$dbquery=pg_query($sql);
		$result=pg_fetch_assoc($dbquery);
		if($result['isAdmin']==0)
		{
			$visitor="user";
		}
		else
		{
			$visitor="admin";
		}
	}
	else
	{
		$visitor="general";
	}
	
	$sql="insert into \"TrStatistic\"(\"Remote_IP\", \"Remote_Time\", \"Visit_Path\", \"visitor_type\") values('$server','$visitDate','$currentUrl','$visitor')";
	if($result=pg_query($sql))
	{}
	else
	{
		$status++;
	}
	if($status==0)
	{
		pg_query("COMMIT");
	}
	else
	{
		pg_query("ROLLBACK");
		echo "บันทึกข้อมูลล้มเหลว";
	}
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TR Member</title>
<link href="info.css" rel="stylesheet" type="text/css">
<link href="css/page.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="jquery-1.3.2.js"></script>
</head>
<body>
	<div align="center">
    	<div id="main">
        	<table width="800" border="0" cellpadding="0" cellspacing="0">
            	<tr>
                	<td height="190">
                    <iframe width="800" height="220" src="header.php" frameborder="0" name="iframe_header"></iframe>
                    </td>
           	  	</tr>
                <tr>
                	<td align="center" valign="top" id="content">
                    	<table border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="center" valign="top" id="content">
                                    <div id="content_box1">
                                        <table width="780" border="0" cellpadding="0" cellspacing="0" id="tb_ShowAllNews">
                                            <tr>
                                                <td id="allNews_Title_3" align="center" valign="middle">หัวข้อข่าว</td>
                                                <td id="allNews_PostBy" align="center" valign="middle">โพสต์โดย</td>
                                                <td id="allNews_PostTime2" align="center" valign="middle">โพสต์เมื่อ</td>
                                                <td id="allNews_Edit_3" align="center" valign="middle">แก้ไข</td>
                                                <td id="allNews_showhide" align="center" valign="middle">S/H</td>
                                            </tr>
                                             <?php
												$type=$_GET['newsType'];  
												// สร้างฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
												function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page){   
													global $e_page;
													global $querystr;
													$urlfile="editAllNews.php"; // ส่วนของไฟล์เรียกใช้งาน ด้วย ajax (ajax_dat.php)
													$per_page=10;
													$num_per_page=floor($chk_page/$per_page);
													$total_end_p=($num_per_page+1)*$per_page;
													$total_start_p=$total_end_p-$per_page;
													$pPrev=$chk_page-1;
													$pPrev=($pPrev>=0)?$pPrev:0;
													$pNext=$chk_page+1;
													$pNext=($pNext>=$total_p)?$total_p-1:$pNext;		
													$lt_page=$total_p-4;
													if($chk_page>0){  
														echo "<a  href='$urlfile?newsType=".$type."&s_page=$pPrev&querystr=".$querystr."' class='naviPN'>Prev</a>";
													}
													for($i=$total_start_p;$i<$total_end_p;$i++){  
														$nClass=($chk_page==$i)?"class='selectPage'":"";
														if($e_page*$i<$total){
														echo "<a href='$urlfile?newsType=".$type."&s_page=$i&querystr=".$querystr."' $nClass  >".intval($i+1)."</a> ";   
														}
													}		
													if($chk_page<$total_p-1){
														echo "<a href='$urlfile?newsType=".$type."&s_page=$pNext&querystr=".$querystr."'  class='naviPN'>Next</a>";
													}
												}   
												?>
												<?php
												//////////////////////////////////////// เริ่มต้น ส่วนเนื้อหาที่จะนำไปใช้ในไฟล์ ที่เรียกใช้ด้วย ajax
												?>
												
												<?php
												$q="select * from \"Main_News\"";
												$q.=" order by \"NewsID\" desc";
												$qr=pg_query($q);
												$total=pg_num_rows($qr);
												$rows=pg_num_rows($qr);
												$e_page=25; // กำหนด จำนวนรายการที่แสดงในแต่ละหน้า   
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
												
												if($rows==0)
												{
													echo"<tr>";
                                                	echo"<td id=\"allNews_NoData\" align=\"center\" valign=\"middle\" colspan=\"3\">**ยังไม่มีข้อมูล**</td>";
                                            		echo"</tr>";
												}
												else
												{
													while($result=pg_fetch_assoc($qr))
													{
														$title=$result['Subject'];
														$poster=$result['doerID'];
														$postTime=substr($result['doerStamp'],0,10);
														$contentID=$result['NewsID'];
														$newsstatus=$result['disabled'];
														echo"<tr>";
														echo"<td id=\"allNews_Title1_3\" align=\"left\" valign=\"middle\"><img src=\"images/link_bullet.gif\"> <a href=\"content.php?NewsID=$contentID\">$title</a></td>";
														echo"<td id=\"allNews_PostBy1\" align=\"center\" valign=\"middle\">$poster</td>";
														echo"<td id=\"allNews_PostTime1\" align=\"center\" valign=\"middle\">$postTime</td>";
														echo"<td id=\"allNews_Edit1\" align=\"center\" valign=\"middle\"><a href=\"editNews.php?id=$contentID\"><img src=\"images/gtk-edit.png\"</a></td>";
														if($newsstatus=="y")
														{
															echo"<td id=\"allNews_Edit1\" align=\"center\" valign=\"middle\"><a href=\"disablenews.php?id=$contentID&operation=enable\"><img src=\"images/enable.png\"</a></td>";
														}
														else
														{
															echo"<td id=\"allNews_Edit1\" align=\"center\" valign=\"middle\"><a href=\"disablenews.php?id=$contentID&operation=disable\"><img src=\"images/disable.png\"</a></td>";
														}
														echo"</tr>";
													}
												}
											?>
                                        </table>
                                    </div>
                                                                        <?php if($total>0){ ?>
                                        <table border="0" cellpadding="0" cellspacing="0" width="780" id="tbNavigate1">
                                        <tr>
                                        <td align="right" valign="middle" id="navigate">
                                        <div class="browse_page">
                                         <?php   
                                         // เรียกใช้งานฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
                                          page_navigator($before_p,$plus_p,$total,$total_p,$chk_page);    
                                          ?>
                                        </div>
                                        </td>
                                        </tr>
                                        </table>
                                	<?php } ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                	<td height="30">
                    	<iframe width="800" height="30" frameborder="0" src="footer.php" scrolling="no"></iframe>
                    </td>
                </tr>
            </table>
        </div>
	</div>
    <ul id="navigation">
        <li class="home"><a href="index.php" title="หน้าหลัก"></a></li>
        <li class="admin"><a href="adminMenu.php" title="เมนูผู้ดูแลระบบ"></a></li>
        <li class="back"><a href="javascript:history.back(-1);" title="กลับก่อนหน้า"></a></li>
    </ul>
    <script type="text/javascript">
        $(function() {
            $('#navigation a').stop().animate({'marginLeft':'-95px'},1000);

            $('#navigation > li').hover(
                function () {
                    $('a',$(this)).stop().animate({'marginLeft':'-2px'},200);
                },
                function () {
                    $('a',$(this)).stop().animate({'marginLeft':'-95px'},200);
                }
            );
        });
    </script>
</body>
</html>