<?php
include('../classes/classDbConnection.php');
include('../classes/classTable.php');
include('../classes/classXML.php');
include('../classes/classTransfer.php');


$shopName = $_POST['shopName'];
$dateFrom = $_POST['dateFrom'];
$dateTo = $_POST['dateTo'];

$paramArray = array('shop'=>$_POST['shopName'],'startDate'=>$_POST['dateFrom'],'endDate'=>$_POST['dateTo']);

$shop = "";
if($shopName == 'Petzone'){
    $shop = "Coolock";
}else{
    $shop = $shopName;
}

          $xml = new xmlFile($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
		  #print_r($xml);
          $db = new dbConnection($xml->getConnectionArray());

	
	$transfer = new Transfer();
	$transfer->openConnection($db -> getDbConnection($db -> getDbConnectionByName($shopName)));
	$transferArray = $transfer->getTransferReference($paramArray);
	
	$tab = new table("");
	$tab -> addHeader("centerTable",array("Product","Transfered","Destination","Cost","Value"));
	$tab -> addRowT("",$transferArray);
	echo $tab -> showTable();
	
	echo "<input type = 'hidden' id='shop' value='".$shopName."'/>";
	echo "<input type = 'hidden' id='array' value='".json_encode($transferArray ,true)."'/>";
	echo "<input type = 'hidden' id='arrayShops' value='".json_encode($transfer->getShops(),true)."'/>";
	echo "<script> $('#exportToExcelBtn').show();</script>";
?>