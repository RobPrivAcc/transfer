<?php
    class Product{

        private $productArray;
        private $pdo=null;
        private $date;

        /*
        function __construct($dbConnectionArray){
            $this -> pdo = new PDO($dbConnectionArray["server"], $dbConnectionArray["user"], $dbConnectionArray["password"]);
            $this -> date = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-90,   date("Y")));
        }*/

        function __construct(){
            $this -> date = date("Y-m-d",mktime(0, 0, 0, date("m"), date("d")-90,   date("Y")));
        }

        function openConnection($dbConnectionArray){
            $this -> pdo = new PDO($dbConnectionArray["server"], $dbConnectionArray["user"], $dbConnectionArray["password"]);
        }
        /*
        private function saleDetail($array){
            for ($i=0;$i < count($array);$i++){
                //print_r($array);
                $sql = "SELECT SUM([QuantityBought]) as total, [Selling Price], (SUM([QuantityBought]) * [Selling Price]) as [value]
                    FROM Stock
                        inner join [Orders] on [Name of Item] = [NameOfItem]
                        inner join [Days] on [Order Number] = OrderNo
                    WHERE [Date] < '$this->date'
                        AND [Name of Item] = '$array[$i]'
                        group by [Selling Price];";

                //echo $sql.'<br/>';
                $query = $this->pdo->prepare($sql);
                $query->execute();

                if($this->saleCount($array[$i]) >0){
                    for($j=0; $row = $query->fetch(); $j++){

                    $this->productArray[] = array($array[$i],round($row['total'],2),round($row['Selling Price'],2));
                      //$this->productArray[] = array($array[$i], array($row['ProdName']."  total",$row['Name of Item']."  selling price"));
                    }
                }else{
                    $this->productArray[] = array($array[$i],"0","0");
                }
            }
        }
        */
        public function saleDetail($name){

                //print_r($array);
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
                      //$this->productArray[] = array($array[$i], array($row['ProdName']."  total",$row['Name of Item']."  selling price"));
                    }
                }else{
                    //return "0";
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