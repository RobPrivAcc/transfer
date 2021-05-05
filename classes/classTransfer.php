<?php
class Transfer{
    protected $pdo = null;
    public $shopArray = array();
    public $shopTaArray = array();
	
    function openConnection($dbConnectionArray){
            try{
                
                $this->pdo = new PDO($dbConnectionArray["server"],$dbConnectionArray["user"],$dbConnectionArray["password"]); 
            }
            catch (PDOException $e){
               // var_dump($e);
                $this->pdo = new PDO($dbConnectionArray["localServer"],$dbConnectionArray["user"],$dbConnectionArray["password"]);
            }
    }
    
    public function getTransferReference($array){
            $productArray = array();
            
        	$sql = "SELECT Distinct([Nameofitem]) AS ProductName
                        ,SUM([RepSub].[Quantity]) as Transfered
                        ,round([Supplier Cost],2) AS Cost
                        ,[InvoiceRef]
                    FROM [RepMain]
                        inner join [RepSub] on [RepSub].OrderNo = [RepMain].[RepOrderNo]
                        inner join Stock on RepSub.Nameofitem = Stock.[Name of Item]
                    WHERE DateOrdered BETWEEN '".$array['startDate']."' AND '".$array['endDate']."' AND InvoiceRef like '".$array['shop']." > %' 
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
                                       'value' => round($row['Transfered'],2) * round($row['Cost'],2)
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