<?php
	session_start();
	include("config.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	
	$id=$_GET['userID'];
	
	$q=$_POST['txtSearch'];
	$word=$q;
	
	//นับสถิติ
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
	//--------------------------------------------------------------//
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>TR Member</title>
<link href="info.css" rel="stylesheet" type="text/css">
<link href="css/page.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="autocomplete.js"></script>
<link rel="stylesheet" href="autocomplete.css"  type="text/css"/>
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
                                    	<form name="frmMain" action="viewUser.php" method="post">
                                        <fieldset id="fsSearchBox">
                                        <legend>ค้นหาสมาชิก</legend>
											<table border="0">
                                            	<tr>
                                                	<td align="center" valign="middle">
                                            			<span id="spanSearchLabel">Search : </span>
                                            			  <input type="text" name="txtSearch" id="txtSearch2"><input type="hidden" id="inputHidden" value="">
                                                    </td>
                                                    <td>
                                                    	<input type="image" src="images/button-search.png" OnClick="JavaScript:doCallAjax(document.getElementById('txtSearch').value);">
                                                    </td>
                                                </tr>
                                            </table>
                                        </fieldset>
								    	</form>
                                        <script type="text/javascript">
											function make_autocom(autoObj,showObj){
											var mkAutoObj=autoObj;
											var mkSerValObj=showObj;
											new Autocomplete(mkAutoObj, function() {
											this.setValue = function(id) {     
											document.getElementById(mkSerValObj).value = id;
											}
											if ( this.isModified )
											this.setValue("");
											if ( this.value.length < 1 && this.isNotClick )
											return ;   
											return "memberSearchAutoCompleteUser.php?q=" +encodeURIComponent(this.value);
											});
											}  
											// การใช้งาน
											// make_autocom(" id ของ input ตัวที่ต้องการกำหนด "," id ของ input ตัวที่ต้องการรับค่า");
											make_autocom("txtSearch","inputHidden");
										</script>
                                        <table width="780" border="0" cellpadding="0" cellspacing="0" id="tb_ShowAllNews">
                                            <tr>
                                                <td id="td_Username" align="center" valign="middle">ชื่อผู้ใช้</td>
                                                <td id="td_UserType" align="center" valign="middle">ประเภท</td>
                                                <td id="td_MemberName" align="center" valign="middle">ชื่อสมาชิก</td>
                                                <td id="td_CarCode" align="center" valign="middle">ทะเบียนรถ</td>
                                                <td id="td_Telephone5" align="center" valign="middle">หมายเลขโทรศัพท์</td>
                                            </tr>
                                             <?php
												$type=$_GET['newsType'];  
												// สร้างฟังก์ชั่น สำหรับแสดงการแบ่งหน้า   
												function page_navigator($before_p,$plus_p,$total,$total_p,$chk_page,$word){   
													global $e_page;
													global $querystr;
													$urlfile="viewUser.php"; // ส่วนของไฟล์เรียกใช้งาน ด้วย ajax (ajax_dat.php)
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
														echo "<a  href='$urlfile?txtSearch=".$word."&s_page=$pPrev&querystr=".$querystr."' class='naviPN'>Prev</a>";
													}
													for($i=$total_start_p;$i<$total_end_p;$i++){  
														$nClass=($chk_page==$i)?"class='selectPage'":"";
														if($e_page*$i<$total){
														echo "<a href='$urlfile?txtSearch=".$word."&s_page=$i&querystr=".$querystr."' $nClass  >".intval($i+1)."</a> ";   
														}
													}		
													if($chk_page<$total_p-1){
														echo "<a href='$urlfile?txtSearch=".$word."&s_page=$pNext&querystr=".$querystr."'  class='naviPN'>Next</a>";
													}
												}   
												?>
												<?php
												//////////////////////////////////////// เริ่มต้น ส่วนเนื้อหาที่จะนำไปใช้ในไฟล์ ที่เรียกใช้ด้วย ajax
												?>
												
												<?php
												if($q=="")
												{
													$q="select * from \"TrMember\"";
												}
												else
												{
													$q="select * from \"TrMember\" where \"UserID\" = '$q'";
												}
												$q.=" order by \"UserID\" asc";
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
                                                	echo"<td id=\"allNews_NoData\" align=\"center\" valign=\"middle\" colspan=\"7\">**ยังไม่มีข้อมูล**</td>";
                                            		echo"</tr>";
												}
												else
												{
													while($result=pg_fetch_assoc($qr))
													{
														$id=$result['UserID'];
														$username=$result['Username'];
														if($username=="")
														{
															$username="-";
														}
														$isAdmin="";
														if($result['isAdmin']==0)
														{
															$isAdmin="user";
														}
														else
														{
															$isAdmin="admin";
														}
														
														$memberBeforName=$result['beforName'];
														$memberFirstName=$result['firstName'];
														$memberLastName=$result['lastName'];
														
														$memberFullName=$memberBeforName." ".$memberFirstName." ".$memberLastName;
														//$memberFullName="\"".$memberFullName."\"";
														
														if($memberFullName=="  ")
														{
															$memberFullName="-";
														}
														
														$carCode=$result['carregistrationnumber'];
														if($carCode=="")
														{
															$carCode="-";
														}
														$telephone=$result['telephonenumber'];
														if($telephone=="")
														{
															$telephone="-";
														}
														if($result['disabled']=="y")
														{
															echo "<tr>";
															echo "<td id=\"td_Username2\" align=\"left\" valign=\"middle\">$username</td>";
															echo "<td id=\"td_UserType2\" align=\"center\" valign=\"middle\">$isAdmin</td>";
															echo "<td id=\"td_MemberName2\" align=\"center\" valign=\"middle\">$memberFullName</td>";
															echo "<td id=\"td_CarCode2\" align=\"center\" valign=\"middle\">$carCode</td>";
															echo "<td id=\"td_Telephone2_5\" align=\"center\" valign=\"middle\">$telephone</td>";
															echo "</tr>";
														}
														else
														{
															echo "<tr>";
															echo "<td id=\"td_Username1\" align=\"left\" valign=\"middle\">$username</td>";
															echo "<td id=\"td_UserType1\" align=\"center\" valign=\"middle\">$isAdmin</td>";
															echo "<td id=\"td_MemberName1\" align=\"center\" valign=\"middle\">$memberFullName</td>";
															echo "<td id=\"td_CarCode1\" align=\"center\" valign=\"middle\">$carCode</td>";
															echo "<td id=\"td_Telephone1_5\" align=\"center\" valign=\"middle\">$telephone</td>";
															echo "</tr>";
														}
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
                                          page_navigator($before_p,$plus_p,$total,$total_p,$chk_page,$word);    
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