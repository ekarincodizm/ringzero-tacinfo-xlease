<?php
require 'class/outlet/Outlet.php';

require 'class/orm/bankcheque.php';
require 'class/orm/detailcheque.php';
require 'class/orm/fa1.php';
require 'class/orm/fc.php';
require 'class/orm/fcash.php';
require 'class/orm/fcheque.php';
require 'class/orm/fgas.php';
require 'class/orm/fp.php';
require 'class/orm/postlog.php'; 
require 'class/orm/typepay.php';
require 'class/orm/vcuspayment.php';
require 'class/orm/vcontact.php';

Outlet::init(include 'config/outlet-config-av.php');

$outlet = Outlet::getInstance();
$outlet->createProxies();

?>
