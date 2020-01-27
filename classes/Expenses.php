<?php


class Expenses extends Transfer
{
    private $productArray = array();

    private function getSuppliers(){
        $sql = "SELECT [Supplier],
                    [RepOrderNo],
                    Expenses
                FROM [RepMain] 
                WHERE [StockUpdateDate] 
                BETWEEN '".$this->data_array['startDate']."' AND '".$this->data_array['endDate']."' 
                    and StockAdded = 1 
                    and InvoiceRef not like '%>%' 
                ORDER By Supplier ASC";

        $query = $this->pdo->prepare($sql);
        $query->execute();

        while($row = $query->fetch()){
            $this->productArray[$row['Supplier']][] = array(

                    'number' => $row['RepOrderNo'],
                    'expenses' => round($row['Expenses'], 2),
                    'checked_cost' => $this->getOrderTotal($row['RepOrderNo'])

              );
          }
        return $this->productArray;
    }

    private function getOrderTotal($order_number){
        $sql = "SELECT sum([Price]*[TotalCheckedQuantity]) as total_checked
                FROM [RepSub] WHERE OrderNo = '$order_number'";

        $query = $this->pdo->prepare($sql);
        $query->execute();

        while($row = $query->fetch()){
            return round($row['total_checked'], 2);
        }

    }

    public function getExpenses()
    {
        $stats = array();

        $this->getSuppliers();

        foreach ($this->productArray as $supplier => $array_data){
                $expenses = 0;
                $costs = 0;

                    foreach ($array_data as $index => $val){
                        $expenses = $expenses + $val['expenses'];
                        $costs = $costs + $val['checked_cost'];
                    }
//                echo $supplier.' '.$expenses.' '.$costs.'<br/>';
                $stats[] = array($supplier, $expenses, $costs);
            }

        return $stats;
        }

//
//        $array["Canners"] = array(
//            array(
//                "number" => "3936",
//                "expenses" => 0,
//                "checked_cost" => 640.17
//            ),
//            array(
//                "number" => "4130",
//                "expenses" => 0,
//                "checked_cost" => 682
//            ),
//            array(
//                "number" => "4180",
//                "expenses" => 0,
//                "checked_cost" => 876.39
//            ),
//            array(
//                "number" => "4189",
//                "expenses" => 0,
//                "checked_cost" => 450.86
//            ),
//            array(
//                "number" => "4220",
//                "expenses" => 0,
//                "checked_cost" => 650.07
//            )
//
//        );
//        echo "<pre>";
//        var_dump($array);
//        echo "</pre>";
//        foreach ($array as $supplier => $array){
//
//            $expenses = 0;
//            $costs = 0;
//                foreach ($array as $index => $val){
//                    $expenses = $expenses + $val['expenses'];
//                    $costs = $costs + $val['checked_cost'];
//                }
//            echo $supplier.' '.$expenses.' '.$costs.'<br/>';
//        }
//        echo count($array["Canners"]);
//    }
}