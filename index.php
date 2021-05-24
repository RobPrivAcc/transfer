<?php
session_start();
require 'loader.php';
require 'vendor\autoload.php';

set_time_limit(0);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="css/myCSS.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/twbs/bootstrap/dist/css/bootstrap.min.css">

</head>

<?php
$xml = new XML($_SERVER["DOCUMENT_ROOT"].'/dbXML.xml');
$array = $xml->getConnectionArray();
?>
<body>

<header class="p-3 bg-light justify-content-center">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 text-body me-md-auto text-decoration-none">
            <span class="fs-5 fw-bold">Transfers raport</span>
        </a>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                <select id="storeName" class="form-select">
                    <option selected>Chose Store</option>
                    <?php
                        foreach($array['shop'] as $key=>$value){
                            echo "<option>".ucfirst($value['shopName']).'</option>';
                        }
                    ?>
                </select>
            </form>
            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
<!--                uu <input type="search" class="form-control" placeholder="Search..." aria-label="Search">-->
                <input type="text" name="dateFrom" class="form-control" id="dateFrom" value="" />
            </form>

            <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
<!--                uu <input type="search" class="form-control" placeholder="Search..." aria-label="Search">-->
                <input type="text" name="dateTo" class="form-control" id="dateTo" value="" />
            </form>


            <div class="text-end">
                <button class = "btn btn-secondary" id = "search">
                        Search
                </button>

                <button class = "btn btn-success" id = "exportToExcelBtn">
                                    <i class="fa fa-file-excel-o fa-lg" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<div class="container">
    <div class="row">
        <div class='col-xs-12 col-12'>
            <div id="result" style="width: 100%;"></div>
        </div>
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


    $( "#search" ).click(function () {

            var shopName = $("#storeName option:selected").text();
            var dateFrom = $("#dateFrom").val()+' 00:00:01';
            var dateTo = $("#dateTo").val()+' 23:59:59';
            if (shopName != 'Choose store'){
                var spinner = '<Div class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></DIV>';
                $('#result').html(spinner);

                $.post( "sql/raport.php", { shopName: shopName, dateFrom: dateFrom, dateTo:dateTo })
                    .done(function( data ) {
                        $('#result').html(data);
                    });
            }else{
                alert("Choose store first!");
            }
        });

    $( "#exportToExcelBtn" ).click(function () {
            var shop = $('#shop').val();
            var shops = $('#arrayShops').val();
            var products = $('#array').val();
            var expenses_array = $('#expenses_array').val();
            var primeline_array = $('#primeline_array').val();
            console.log(expenses_array);
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
            autoApply: true,
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD'
            }
        });
        $('input[name="dateTo"]').daterangepicker({
            autoApply: true,
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