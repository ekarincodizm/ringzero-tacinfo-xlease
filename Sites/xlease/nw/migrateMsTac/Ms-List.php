﻿<?php

1.Ms-TacCusDtl

นำไปลง pg 4 ตารางคือ Cars, Customers, LetterAddress, Contracts

$ins="insert into \"Cars\" (\"car_id\", \"car_name\", \"car_num\", \"car_year\",\"license_plate\", \"regis_date\") values  ('$car_id','$CarName','$CarNum','$CarYear','$CarRegis','$CarDate')";

$ins="INSERT INTO \"Customers\" (\"cus_id\",\"pre_name\",\"cus_name\",\"surname\",\"address\",\"card_type\",\"card_id\",\"card_do_date\",\"contact_add\",\"telephone\") values ('$cus_id','$PreName','$Name','$SurName','$address','$CardType','$CardID','$CardDate','$contact_add','$tel')";

$ins3="INSERT INTO \"LetterAddress\"(\"cus_id\", \"address\")VALUES ('$cus_id', '$let_addr')";

$in_contr="INSERT INTO \"Contracts\"(\"idno\", \"cus_id\", \"radio_id\", \"car_id\", \"sign_date\", \"start_date\", \"rent_price\",\"branch_office\",\"remark\",\"cus_year\",\"lock_con\")
    VALUES ('$CusID', '$cus_id', '$Rad_ID', '$car_id', '$SignDate', '$SignDate', '$SalePrice',1,'$CustRemark','$CusYear','t')";


2.Ms-TacInvoice

นำไปลง pg 4 ตารางคือ Invoices, InvoiceDetails , ReceiptDtl ,VatDtl

	$ins="INSERT INTO \"Invoices\" (\"inv_no\",\"idno\",\"cus_id\",\"inv_date\",\"cancel\",\"user_id\") values 
    ('$InvNo','$CusID','$cus_id','$InvDate','$InvCancel','$InvIDUser')";

	$ins2="INSERT INTO \"InvoiceDetails\" (\"inv_no\",\"service_id\",\"due_date\",\"amount\",\"vat\",\"cancel\") values 
        ('$InvNo','$service_id','$InvFixDate','$InvAmountExVAT','$VAT','$InvCancel')";
		
	$ins="INSERT INTO \"ReceiptDtl\" (\"inv_no\",\"r_receipt\") values 
    ('$InvNo','$RecNO')";
	
	$ins="INSERT INTO \"VatDtl\" (\"inv_no\",\"v_receipt\") values 
    ('$InvNo','$VatNO')";
		
3.Ms-TacRadio  (แบบเก่า)

นำไปลง pg 1 ตารางคือ Radios

$ins="insert into \"Radios\" (\"radio_id\",\"radio_no\",\"band\", \"model\", \"serial_no\", \"pt_no\") values 
		('$Rad_ID','$RadioID','$RadioBand','$RadioModel','$RadioONID','$RadioPT')";
		
4.Ms-TacRec

นำไปลง pg 1 ตารางคือ Receipts

$ins="insert into \"Receipts\" (\"r_receipt\",\"r_date\",\"money_way\",\"money_type\",\"prndate\",\"cancel\",\"memo\",\"user_id\",\"type_rec\") values 
		('$RecNO','$RecDate','OC','CA','$PrintDate','$RecCancel','$CancelRemark','$RIDUser','A')";
		
5.Ms-TacVat

นำไปลง pg 1 ตารางคือ v_receipt

$ins="insert into \"Vats\" (\"v_receipt\",\"v_date\",\"prndate\",\"cancel\",\"user_id\",\"type_rec\") values 
		('$VatNO','$VatDate','$VPrintDate','$VCancel','$VIDUser','A')";
		
		
		

?>

