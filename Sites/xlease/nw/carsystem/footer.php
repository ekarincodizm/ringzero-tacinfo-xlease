<?php
	include("../../config/config.php");
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link href="css/footer.css" rel="stylesheet" type="text/css">
</head>

<body>
<div id="footercontrainer">
	<div align="center">
        <div id="divgroupbothmenu">
          <div id="divbothmenu">
                <div id="diveachbothmenu1" class="diveachbothmenu">
                    <ul id="uleachbothmenu">
                        <li style="font-size:12px; margin-bottom:5px;"><b>ค้นหาตามยี่ห้อรถ</b></li>
                        <?php
							$sql="select * from carsystem.\"productBrand\"";
							$dbquery=pg_query($sql);
							while($rs=pg_fetch_assoc($dbquery))
							{
								echo "<li class=\"subbothmenueng\"><a href=\"showproduct.php?brand=".$rs['productBrandName']."\">| ".$rs['productBrandName']."</a></li>";
							}
						?>
                    </ul>
                </div>
                <div id="diveachbothmenu2" class="diveachbothmenu">
                    <ul id="uleachbothmenu">
                        <li style="font-size:12px; margin-bottom:5px;"><b>ค้นหาตามประเภทรถ</b></li>
                        <li class="subbothmenuthai"><a href="showproduct.php?cartype=ป้ายแดง">| รถป้ายแดง</a></li>
                        <li class="subbothmenuthai"><a href="showproduct.php?cartype=มือสอง">| รถมือสอง</a></li>
                  </ul>
              </div>
              <div id="diveachbothmenu3" class="diveachbothmenu">
                    <ul id="uleachbothmenu">
                        <li style="font-size:12px; margin-bottom:5px;"><b>ค้นหาตามสีรถ</b></li>
                        <li class="subbothmenuthai"><a href="showproduct.php?carcolor=สีฟ้า">| สีฟ้า</a></li>
                        <li class="subbothmenuthai"><a href="showproduct.php?carcolor=สีชมพู">| สีชมพู</a></li>
                        <li class="subbothmenuthai"><a href="showproduct.php?carcolor=สีขาว">| สีขาว</a></li>
                        <li class="subbothmenuthai"><a href="showproduct.php?carcolor=สีเขียวเหลือง">| สีเขียวเหลือง</a></li>
                  </ul>
             </div>
             <div id="diveachbothmenu4" class="diveachbothmenu">
                <ul id="uleachbothmenu">
                    <li style="font-size:12px; margin-bottom:5px;"><b>ค้นหาตามค่าผ่อนชำระ</b></li>
                    <li class="subbothmenuthai"><a href="showproduct.php?carprice=0_10000">| น้อยกว่า 10,000 บาท</a></li>
                    <li class="subbothmenuthai"><a href="showproduct.php?carprice=10000_25000">| 10,000 - 25,000 บาท</a></li>
                    <li class="subbothmenuthai"><a href="showproduct.php?carprice=25000_40000">| 25,000 - 40,000 บาท</a></li>
                    <li class="subbothmenuthai"><a href="showproduct.php?carprice=40000_55000">| 40,000 - 55,000 บาท</a></li>
                    <li class="subbothmenuthai"><a href="showproduct.php?carprice=55000_1000000">| 55,000  บาท ขึ้นไป</a></li>
                </ul>
            </div>
            </div>
            <div id="divfooter"><span id="spanauthor">บริษัทไทยเอซ ลิสซิ่ง จำกัด - Thai Ace Leasing Co.,LTD</span><span id="copyright">&copy; 2012</span></div>
        </div>
    </div>
</div>
</body>
</html>