<?php
$dbh = new PDO("sqlite:example.sqlite");
if (isset($_POST["CRUD"]))
{
	$q = "update users set first_name = :fn, last_name = :ln, team = :tm where ID = :id";
	$stmt = $dbh->prepare($q);
	$params = array(
		":fn" => $_POST["rekord"]["first_name"],
		":ln" => $_POST["rekord"]["last_name"],
		":tm" => $_POST["rekord"]["team"],
		":id" => $_POST["rekord"]["ID"]
	);
	$stmt->execute($params);
	echo json_encode(array("status" => 1, "config"=>$_POST["config"]));
}
else
{
	if (isset($_POST["id"]))
	{
		$q = "select * from users where ID = :id";
		$stmt = $dbh->prepare($q);
		$stmt->execute(array(":id" => (int)pg_escape_string($_POST["id"])));
		echo json_encode(array("rekord" => $stmt->fetch(),"config" => $_POST["config"]));
	}
	else
	{
		$q = "select * from users order by ".$_POST["order_by"]." limit :limit offset :offset";
		$stmt = $dbh->prepare($q);
		$params = array(
			":limit" => (int)pg_escape_string($_POST["per_page"]), 
			":offset" => $_POST["per_page"]*$_POST["page"],
		);
		$count = 150;
		if ($_POST["filter"] != "" && $_POST["filter"] != "undefined")
		{
			$params[":search"] = "%".strtoupper($_POST["filter"])."%";
			$q = "select * from users where (upper(first_name) like :search or upper(last_name) like :search or upper(team) like :search) order by ".$_POST["order_by"]." limit :limit offset :offset";
			$stmt = $dbh->prepare($q);
		}
		$stmt->execute($params);
		$i = 0;
		while ($row = $stmt->fetch())
		{
			$out[] = $row;
			$i++;
		}
		if (isset($params[":search"])) { $count = $i; }
		echo json_encode(array("out" => $out, "count" => $count, "config" => $_POST["config"]));
	}
}
?>
