<?php
    require '../loader.php';
//include('../classes/DbConnection.php');
//include('../classes/Table.php');
//include('../classes/XML.php');
//include('../classes/Transfer.php');
//include('../classes/Expenses.php');
//include('../classes/ConnectionString.php');


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

    $xml = new XML($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
    #print_r($xml);
    $db = new ConnectionString($xml->getConnectionArray());

	
	$transfer = new Transfer($db -> getDbConnection($db -> getDbConnectionByName($shopName)), $paramArray);
//	$transfer->openConnection($db -> getDbConnection($db -> getDbConnectionByName($shopName)));
	$transferArray = $transfer->getTransferReference();


	$tab = new Table("");
	$tab -> addHeader("centerTable",array("Product","Transfered","Destination","Cost","Value"));
	$tab -> addRowT("",$transferArray);
//	echo $tab -> showTable();
//
//
//    echo "<h3>Expenses</h3>";

    $expenses = new Expenses($db -> getDbConnection($db -> getDbConnectionByName($shopName)), $paramArray);
    $expenses_array = $expenses->getExpenses();

    $exp_tab = new Table("");
    $exp_tab -> addHeader("centerTable",array("Supplier","Expenses","Checked cost"));
    $exp_tab -> addRowT("",$expenses_array);

    $primeline = new Expenses($db -> getDbConnection($db -> getDbConnectionByName($shopName)), $paramArray);
    $primeline_array = $primeline->getExpenses(true);

    $prim_tab = new Table("");
    $prim_tab -> addHeader("centerTable",array("Supplier","Expenses","Checked cost"));
    $prim_tab -> addRowT("",$primeline_array);
//    echo $exp_tab -> showTable();
?>

    <div class="row">
        <div class='col-xs-12 col-lg-6'>
            <h3>Transfers</h3>
            <?php echo $tab -> showTable();?>
        </div>
        <div class='col-xs-12 col-lg-6'>
            <h3>Expenses</h3>
            <?php echo $exp_tab -> showTable();?>
        </div>
        <div class='col-xs-12 col-lg-6'>
            <h3>Primeline</h3>
            <?php echo $prim_tab -> showTable();?>
        </div>
    </div>


<?php
    echo "<br/>";

	echo "<input type = 'hidden' id='shop' value='".$shopName."'/>";
	echo "<input type = 'hidden' id='array' value='".json_encode($transferArray ,true)."'/>";
	echo "<input type = 'hidden' id='expenses_array' value='".json_encode($expenses_array ,true)."'/>";
	echo "<input type = 'hidden' id='primeline_array' value='".json_encode($primeline_array ,true)."'/>";

	echo "<input type = 'hidden' id='arrayShops' value='".json_encode($transfer->getShops(),true)."'/>";
	echo "<script> $('#exportToExcelBtn').show();</script>";

