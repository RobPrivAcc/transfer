<?php
    class Product{

        private $productArray;
        private $pdo=null;
        private $date;

        function __construct(){
            $this -> date = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-90,   date("Y")));
        }

        function openConnection($dbConnectionArray){
            $this -> pdo = new PDO($dbConnectionArray["server"], $dbConnectionArray["user"], $dbConnectionArray["password"]);
        }

        public function saleDetail($name){

                $sql = "SELECT SUM([QuantityBought]) as total, [Selling Price], (SUM([QuantityBought]) * [Selling Price]) as [value], Quantity
                    FROM Stock
                        inner join [Orders] on [Name of Item] = [NameOfItem]
                        inner join [Days] on [Order Number] = OrderNo
                    WHERE [Date] > '$this->date'
                        AND [Name of Item] = '$name'
                        group by [Selling Price],Quantity;";

                $query = $this->pdo->prepare($sql);
                $query->execute();

                $quantity = $this->stockQuantity($name);
                if($this->saleCount($name) >0){
                    for($j=0; $row = $query->fetch(); $j++){
                        return array(round($row['total'],2),$quantity);
                    }
                }else{
                    return array("0",$quantity);
                }

        }

        private function stockQuantity($name){
            $sql = "SELECT Quantity FROM Stock WHERE [Name of Item] = '$name'";

                $quantity = 0;

                $query = $this->pdo->prepare($sql);
                $query->execute();

                for($i=0; $row = $query->fetch(); $i++){
                    $quantity = round($row['Quantity'],2);
                }
                return $quantity;
        }

        private function saleCount($product){
                $sqlCount = "SELECT count([NameOfItem])
                            FROM [Orders]
                                inner join [Days] on [Order Number] = OrderNo  
                                inner join Stock on [Name of Item] = [NameOfItem] 
                        WHERE [Date] > '$this->date'
                        AND [Name of Item] = '$product';";

                $query = $this->pdo->prepare($sqlCount);
                $query->execute();

            if ($query->fetchColumn() > 0) {
                return 1;
            }else{
                return 0;
            }
        }

        public function getSales($array){
            $this -> saleDetail($array);
            //echo count($this->productArray);
            return $this->productArray;
        }

        public function getDate(){
            return $this->date;
        }
    }