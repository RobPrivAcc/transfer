<?php
require 'loader.php';
require 'vendor\autoload.php';
//    include('loader.php');
//   //include('connection.php');
//   include('classes/DbConnection.php');
//   //include('classes/classSupplier.php');
//   include("classes/XML.php");

set_time_limit(0);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="js/jQuery/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <!--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>-->
    <script src="vendor/twbs/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">-->
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/myCSS.css">

    <!-- Include Required Prerequisites -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />

    <!-- Include Date Range Picker -->
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

</head>
<body>
<?php
$xml = new XML($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
$array = $xml->getConnectionArray();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
    <div class='col-xs-4 col-4'>
        <select id="storeName">

            <option>Choose store</option>
            <?php
            foreach($array['shop'] as $key=>$value){

                echo "<option>".ucfirst($value['shopName']).'</option>';
            }
            ?>
        </select>
    </div>
    <div class='col-xs-6 col-6'>
        Date from: <input type="text" name="dateFrom" id="dateFrom" value="" />
        Date to: <input type="text" name="dateTo" id="dateTo" value="" />
    </div>

    <div class='col-xs-1 col-1'>
        <button class = "btn btn-secondary" id = "search"><i class="fa fa-toggle-right fa-lg" aria-hidden="true"></i></button>
    </div>
    <div class='col-xs-1 col-1'>
        <button class = "btn btn-success" id = "exportToExcelBtn"><i class="fa fa-file-excel-o fa-lg" aria-hidden="true"></i></button>
    </div>
</nav>

<div class="row">
    <div class='col-xs-12 col-12'>


    </div>
</div>

<div class="row">
    <div class='col-xs-12 col-12'>
        <div id="result" style="width: 100%;"></div>
    </div>
</div>

<div class="row">
    <div class='col-xs-12 col-12'>
        <div class="alert alert-secondary" role="alert">
            <div id="foot" style="width: 100%;">ver: <?php include('version.php');?></div>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->

<script>
    $( document ).ready(function() {
        console.log( "ready!" );
        $('#exportToExcelBtn').hide();
        $.get( "https://www.robertkocjan.com/petRepublic/ip/ipGetArray.php", function(i) {
            var configArray = i;
            $.get( "getIpFromServer.php", { ipArray: configArray }, function(data) {
            });
        });
    });


    $( "#search" )
        .click(function () {

            var shopName = $("#storeName option:selected").text();
            var dateFrom = $("#dateFrom").val()+' 00:00:01';
            var dateTo = $("#dateTo").val()+' 23:59:59';
            if (shopName != 'Choose store'){
                var spinner = '<Div class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></DIV>';
                $('#result').html(spinner);

                $.post( "sql/raport.php", { shopName: shopName, dateFrom: dateFrom, dateTo:dateTo })
                    .done(function( data ) {
                        $('#result').html(data);

                        $('#result').html(data);
                    });
            }else{
                alert("Choose store first!");
            }
        });

    $( "#exportToExcelBtn" )
        .click(function () {
            var shop = $('#shop').val();
            var shops = $('#arrayShops').val();
            var products = $('#array').val();
            var expenses_array = $('#expenses_array').val();
            var primeline_array = $('#primeline_array').val();
            //$.post( "pages/exportToExcel.php", { shop: shop, shopName: shops, product: products })
            $.post( "pages/exportToExcel.php", { shop: shop, shops: shops, products: products, expanses: expenses_array, primeline: primeline_array })
                .done(function( data ) {
                    $('#result').html(data);
                });
        });
</script>


<script type="text/javascript">
    $(function() {
        $('input[name="dateFrom"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $('input[name="dateTo"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
    });
</script>




</body>
</html>