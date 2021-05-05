<?php
//require '..\classes\loader.php';

class Expenses extends Transfer
{
    private $productArray = array();

    private function getSuppliers(){
//        $sql = "SELECT [Supplier],
//                    [RepOrderNo],
//                    Expenses
//                FROM [RepMain]
//                WHERE [StockUpdateDate]
//                BETWEEN '".$this->data_array['startDate']."' AND '".$this->data_array['endDate']."'
//                    and StockAdded = 1
//                    and InvoiceRef not like '%>%'
//                ORDER By Supplier ASC";

        $sql = "SELECT [Supplier], [Expenses],
                       cast(SUBSTRING([Action],32,len([Action])) as Int) as 'RepOrderNo',
                       [DateTime]
                FROM [ActionLog]
                    inner join [RepMain] on [RepOrderNo] = cast(SUBSTRING([Action],32,len([Action])) as Int)
                WHERE
                   [DateTime]  BETWEEN '".$this->data_array['startDate']."' AND '".$this->data_array['endDate']."' and 
                   InvoiceRef not like '%>%' and
                   
                [Action] like 'Replenishment Order INCREASED #%' ORDER BY Supplier;";

        $query = $this->pdo->prepare($sql);
        $query->execute();

        while($row = $query->fetch()){
            $this->productArray[$row[0]][] = array(

                    'number' => $row[2],
                    'expenses' => round($row[1], 2),
                    'checked_cost' => $this->getOrderTotal($row[2])

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
        return 0;
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

}