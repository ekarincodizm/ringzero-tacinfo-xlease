<?php


function search(){
global $allowsearch ;

echo "<form action=";
if ($allowsearch==1) {
  echo "cal_search.php method=post>\n";
  }
else echo ">\n";
echo "<input type=text name=search size=20>\n";
echo "<input type=submit value=\"".("search")."\">";
echo "</form>" ;
}

?>
