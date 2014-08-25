<?php
session_start();

return array(
	'connection' => array
  (
		'dsn'      => 'pgsql:host='. $_SESSION["session_company_server"] .';dbname='. $_SESSION["session_company_dbname"] .'',
    'username' => ''. $_SESSION["session_company_dbuser"] .'',
    'password' => ''. $_SESSION["session_company_dbpass"] .'',
		'dialect'  => 'pgsql'
	),
	
  'classes' => array
  (
    'bankcheque' => array 
    (
      'table' => '"BankCheque"' , 
      'props' => array
      (
        'bankcode'      => array('"BankCode"'        , 'varchar'  , array('pk'=>true) ),
        'bankname'      => array('"BankName"'        , 'varchar') 
      )
    ) ,
    
    'detailcheque'  => array
    (
      'table' => '"DetailCheque"' ,
      'props' => array 
      (
        //'auto_id'       => array('"auto_id"'         , 'int'      , array('pk'=>true , 'autoIncrement'=>true)) ,   
        'postid'        => array('"PostID"'          , 'varchar'  , array('pk'=>true)) ,    
        'chequeno'      => array('"ChequeNo"'        , 'varchar' , array('pk'=>true)) , 
        'cusid'         => array('"CusID"'           , 'varchar' , array('pk'=>true)) , 
        'idno'          => array('"IDNO"'            , 'varchar' , array('pk'=>true)) , 
        'typepay'       => array('"TypePay"'         , 'int'     , array('pk'=>true)) ,  
        'cusamount'     => array('"CusAmount"'       , 'float') , 
        'receiptno'     => array('"ReceiptNo"'       , 'varchar') , 
        'prndate'       => array('"PrnDate"'         , 'varchar')
      )
    ) ,
  
    'fa1'     => array 
    (
      'table' => '"Fa1"' , 
      'props' => array  
      (
        'cusid'         => array('"CusID"'        , 'varchar'  , array('pk'=>true) ),
        'a_firname'     => array('"A_FIRNAME"'    , 'varchar') ,
        'a_name'        => array('"A_NAME"'       , 'varchar') ,
        'a_sirname'     => array('"A_SIRNAME"'    , 'varchar') ,
        'a_pair'        => array('"A_PAIR"'       , 'varchar') ,
        'a_no'          => array('"A_NO"'         , 'varchar') ,
        'a_subno'       => array('"A_SUBNO"'      , 'varchar') ,
        'a_soi'         => array('"A_SOI"'        , 'varchar') ,
        'a_rd'          => array('"A_RD"'         , 'varchar') ,
        'a_tum'         => array('"A_TUM"'        , 'varchar') ,
        'a_aum'         => array('"A_AUM"'        , 'varchar') ,
        'a_pro'         => array('"A_PRO"'        , 'varchar') ,
        'a_post'        => array('"A_POST"'       , 'varchar') 
      )
    ) ,  
    
    'fc'      => array  
    (
      'table' => '"VCarregistemp"' ,
      'props' => array 
      (
        'carid'         => array('"CarID"'        , 'varchar' , array('pk'=>true) ), 
        'c_carname'     => array('"C_CARNAME"'    , 'varchar') , 
        'c_year'        => array('"C_YEAR"'       , 'varchar') , 
        'c_regis'       => array('"C_REGIS"'      , 'varchar') , 
        'c_regisby'     => array('"C_REGIS_BY"'   , 'varchar') , 
        'c_color'       => array('"C_COLOR"'      , 'varchar') , 
        'c_carnum'      => array('"C_CARNUM"'     , 'varchar') , 
        'c_marnum'      => array('"C_MARNUM"'     , 'varchar') , 
        'c_milage'      => array('"C_Milage"'     , 'varchar') , 
        'c_tax_expdate' => array('"C_TAX_ExpDate"', 'datetime') , 
        'c_tax_mon'     => array('"C_TAX_MON"'    , 'float') , 
      )    
    ) ,  
  
    'fcash'   => array 
    (
      'table' => '"FCash"' , 
      'props' => array 
      (
        //'auto_id'       => array('"auto_id"'      , 'int'      , array('pk'=>true , 'autoIncrement'=>true)) ,
        'postid'        => array('"PostID"'       , 'varchar'  , array('pk'=>true)) ,
        'cusid'         => array('"CusID"'        , 'varchar'  , array('pk'=>true)) ,   
        'idno'          => array('"IDNO"'         , 'varchar'  , array('pk'=>true)) ,   
        'typepay'       => array('"TypePay"'      , 'int'      , array('pk'=>true)) ,   
        'amtpay'        => array('"AmtPay"'       , 'float') ,
        'refreceipt'    => array('"refreceipt"'   , 'varchar') 
      )
    ) , 
    
    'fcheque' => array 
    (
      'table' => '"FCheque"' ,
      'props' => array
      (
        'postid'        => array('"PostID"'      , 'varchar'  , array('pk'=>true)) ,   
        'cheqeqno'      => array('"ChequeNo"'    , 'varchar') ,
        'bankname'      => array('"BankName"'    , 'varchar') ,
        'bankbranch'    => array('"BankBranch"'  , 'varchar') , 
        'amtoncheque'   => array('"AmtOnCheque"' , 'float') , 
        'receiptdate'   => array('"ReceiptDate"' , 'varchar') ,
        'dateoncheque'  => array('"DateOnCheque"', 'varchar') , 
        'outbangkok'    => array('"OutBangkok"'  , 'varchar') ,
        'reenterdate'   => array('"ReEnterDate"' , 'varchar') ,
        'numofreenter'  => array('"NumOfReEnter"', 'int'),
        'ispass'        => array('"IsPass"'      , 'varchar'),
        'accept'        => array('"Accept"'      , 'varchar'),
        'isreturn'      => array('"IsReturn"'    , 'varchar')
      ) 
    ) ,
    
    'fgas'    => array  
    (
      'table' => '"FGas"' , 
      'props' => array  
      (
        'gasid'         => array('"GasID"'        , 'varchar'  , array('pk'=>true) ) ,  
        'gas_name'      => array('"gas_name"'     , 'varchar') , 
        'gas_number'    => array('"gas_number"'   , 'varchar') ,
        'gas_type'      => array('"gas_type"'     , 'varchar') ,
        'car_regis'     => array('"car_regis"'    , 'varchar') ,
        'car_regis_by'  => array('"car_regis_by"' , 'varchar') ,
        'car_year'      => array('"car_year"'     , 'varchar') ,
        'carnum'        => array('"carnum"'       , 'varchar') ,
        'marnum'        => array('"marnum"'       , 'varchar') 
      )
    ) ,
    
    'fp'      => array 
    (
      'table' => '"Fp"' ,
      'props' => array 
      (
        'idno'          => array('"IDNO"'         , 'varchar'  , array('pk'=>true)) ,  
        'cusid'         => array('"CusID"'        , 'varchar') ,
        'p_month'       => array('"P_MONTH"'      , 'float') , 
        'p_vat'         => array('"P_VAT"'        , 'float') , 
        'asset_type'    => array('"asset_type"'   , 'int') ,
        'asset_id'      => array('"asset_id"'     , 'varchar') ,
        'p_sl'          => array('"P_SLBAK"'        , 'float')
      )      
    ) ,
  
		'postlog' => array
    (
			'table' => '"PostLog"',
			'props' => array
      (
				'postid'        => array('"PostID"'       , 'varchar'  , array('pk'=>true)) ,
				'useridpost'    => array('"UserIDPost"'   , 'varchar') ,
        'useridaccept'  => array('"UserIDAccept"' , 'varchar') ,
        'postdate'      => array('"PostDate"'     , 'varchar') ,
        'paytype'       => array('"paytype"'      , 'varchar') ,
        'acceptpost'    => array('"AcceptPost"'   , 'varchar')
			)
		) , 
    
    'typepay' => array
    (
      'table' => '"TypePay"' ,
      'props' => array 
      (
        'typeid'        => array('"TypeID"'       , 'int'      , array('pk'=>true)) ,
        'tname'         => array('"TName"') , 
        'usevat'        => array('"UseVat"')
      )
    ) ,
    
    'vcontact'=> array 
    (
      'table' => '"VContact"' , 
      'props' => array 
      (
        'idno'          => array('"IDNO"'         , 'varchar' , array('pk'=>true)) ,
        'full_name'     => array('"full_name"'    , 'varchar') ,
        'p_stdate'      => array('"P_STDATE"'     , 'varchar') ,
        'p_beginx'      => array('"P_BEGINX"'     , 'float') ,
        'p_begin'       => array('"P_BEGIN"'      , 'float') ,
        'p_month'       => array('"P_MONTH"'      , 'float') ,
        'p_vat'         => array('"P_VAT"'        , 'float') ,
        'p_total'       => array('"P_TOTAL"'      , 'int') ,
        'p_down'        => array('"P_DOWN"'       , 'float') ,
        'p_vatofdown'   => array('"P_VatOfDown"'  , 'float') , 
        'cusid'         => array('"CusID"'        , 'varchar') ,
        'c_carname'     => array('"C_CARNAME"'    , 'varchar') ,
        'c_regis'       => array('"C_REGIS"'      , 'varchar') ,
        'c_carnum'      => array('"C_CARNUM"'     , 'varchar') ,
        'gas_name'      => array('"gas_name"'     , 'varchar') , 
        'gas_number'    => array('"gas_number"'   , 'varchar') ,
        'car_regis'     => array('"car_regis"'    , 'varchar') ,
        'dp_balance'    => array('"dp_balance"'   , 'float')
      )
    ) , 
    
    'vcuspayment' => array 
    (
      'table' => '"VCusPayment"',
      'props' => array 
      (
        'idno'          => array('"IDNO"'         , 'varchar' , array('pk'=>true)) , 
        'dueno'         => array('"DueNo"'        , 'int'     , array('pk'=>true)) , 
        'duedate'       => array('"DueDate"'      , 'varchar') ,
        'r_date'        => array('"R_Date"'       , 'varchar') ,
        'daydelay'      => array('"daydelay"'     , 'int') ,
        'calamtdelay'   => array('"CalAmtDelay"'  , 'float') ,
        'r_receipt'     => array('"R_Receipt"'    , 'varchar') ,
        'v_receipt'     => array('"V_Receipt"'    , 'varchar') ,
        'v_date'        => array('"V_Date"'       , 'varchar') ,
        'r_money'       => array('"R_Money"'      , 'float') ,
        'vatvalue'      => array('"VatValue"'     , 'float')      
      )
    )
       
	)                           
);
?>
