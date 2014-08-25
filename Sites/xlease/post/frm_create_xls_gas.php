<?php
 $xs_m=$_GET["m"];
 $xs_y=$_GET["y"];
  //
  // establish database connection
  //
  include("../config/config.php");
  //
  // execute sql query
  //
  $query ="SELECT A.\"IDNO\",A.full_name,A.\"P_STDATE\",A.car_regis,B.\"TranIDRef1\",B.\"TranIDRef2\",A.\"P_BEGINX\" FROM \"VContact\" A
                    INNER JOIN \"Fp\" B ON B.\"IDNO\"=A.\"IDNO\" 
					where (A.asset_type='2') AND  (EXTRACT(MONTH FROM B.\"P_STDATE\")='$xs_m' AND EXTRACT(YEAR FROM B.\"P_STDATE\")='$xs_y')  order by A.\"IDNO\" ";
  $result = pg_query($query);
  //
  // send response headers to the browser
  // following headers instruct the browser to treat the data as a csv file called export.csv
  //
  header( 'Content-Type: text/csv' );
  header( 'Content-Disposition: attachment;filename=export.csv' );
  //
  // output header row (if atleast one row exists)
  //
  $row =pg_fetch_assoc( $result );
  if ( $row )
  {
    echocsv( array_keys( $row ) );
  }
  //
  // output data rows (if atleast one row exists)
  //
  while ( $row )
  {
    echocsv( $row );
    $row = pg_fetch_assoc( $result );
  }
  //
  // echocsv function
  //
  // echo the input array as csv data maintaining consistency with most CSV implementations
  // * uses double-quotes as enclosure when necessary
  // * uses double double-quotes to escape double-quotes 
  // * uses CRLF as a line separator
  //
  function echocsv( $fields )
  {
    $separator = '';
    foreach ( $fields as $field )
    {
      if ( preg_match( '/\\r|\\n|,|"/', $field ) )
      {
        $field = '"' . str_replace( '"', '""', $field ) . '"';
      }
      echo $separator .iconv('UTF-8','WINDOWS-874',$field);
      $separator = ',';
    }
    echo "\r\n";
  }
?>
