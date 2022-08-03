<?php
$db_host = "127.0.0.1";
$db_name = "movies";
$db_user = "root";
$db_pass = "";

$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

$sql = "SELECT * FROM movies";
$result = $mysqli->query($sql);

while($row = $result->fetch_array(MYSQLI_ASSOC)){
  $data[] = $row;
}

$results = ["sEcho" => 1,
        	"iTotalRecords" => count($data),
        	"iTotalDisplayRecords" => count($data),
        	"aaData" => $data ];

echo json_encode($results);
?>