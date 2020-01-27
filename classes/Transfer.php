<?php
    class Transfer extends DbConnection{
        public $shopArray = array();
        public $shopTaArray = array();
        protected $data_array = array();

        function __construct($dbConnectionArray, $array){
            parent::__construct($dbConnectionArray);
            $this->data_array = $array;
        }

        public function getTransferReference(){
            //'shop'=>$_POST['shopName'],'startDate'=>$_POST['dateFrom'],'endDate'=>$_POST['dateTo'
                $productArray = array();

                $sql = "SELECT Distinct([Nameofitem]) AS ProductName
                            ,SUM([RepSub].[Quantity]) as Transfered
                            ,round([Supplier Cost],2) AS Cost
                            ,[InvoiceRef]
                        FROM [RepMain]
                            inner join [RepSub] on [RepSub].OrderNo = [RepMain].[RepOrderNo]
                            inner join Stock on RepSub.Nameofitem = Stock.[Name of Item]
                        WHERE DateOrdered BETWEEN '".$this->data_array['startDate']."' AND '".$this->data_array['endDate']."' AND InvoiceRef like '".$this->data_array['shop']." > %' 
                        GROUP BY [Nameofitem],[InvoiceRef],[Supplier Cost]
                        ORDER BY [InvoiceRef], NameofItem";

                $query = $this->pdo->prepare($sql);
                $query->execute();

                while($row = $query->fetch()){
                    $name = str_replace("''",'"',$row['ProductName']);
                    $productArray[] =   array(
                                           'productName' => $name,
                                           'transfered' => round($row['Transfered'],2),
                                           'invRef' => $row['InvoiceRef'],
                                           'cost' => round($row['Cost'],2),
                                           'value' => round($row['Transfered'] * $row['Cost'] ,2)
                                        );
                    $value = $row['InvoiceRef'];

                    if (!in_array($value, $this->shopArray)){
                        $this->shopArray[] = $value;
                    }
                }
                return $productArray;
        }

        public function getShops(){
            if(is_array($this->shopArray) && count($this->shopArray)>0){
                return $this->shopArray;
            }
        }
    }