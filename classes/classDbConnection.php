 <?php
 class dbConnection{
    
    /*
        0 - Petzone
        1 - Donaghmede
        4 - Swords
        2 - Petco
        3 - Charlestown
    */
    
    
    private $user = "";
    private $password = "";
    private $index;
    
    private $dbConnectionArray;

  
     function __construct($xmlArray){
    
      $this->user = $xmlArray['login']['user'];
      $this->password = $xmlArray['login']['password'];
      $array = $xmlArray['shop'];
      for($i=0; $i < count($array); $i++){
         $this->dbConnectionArray[$i] = array(
                'shopName' => $array[$i]['shopName'],
                'server' => 'sqlsrv:Server='.$array[$i]['external_IP'].',1317;Database='.$array[$i]['dbName'],
                'localServer' => 'sqlsrv:Server='.$array[$i]['internal_IP'].';Database='.$array[$i]['dbName'],// SANGOTILL3
                'user' => $this -> user,
                'password' => $this -> password,
                'shopNo' => $array[$i]['shop_No']
            );
      }
    }
     
    public function getMaxIndex(){
        return count($this -> dbConnectionArray);
    }
    
    public function getShopName(){
        return $this -> dbConnectionArray[$this -> index]['shopName'];
    }
    
    public function getShopNo(){
         return $this -> dbConnectionArray[$this -> index]['shopNo'];
    }

    public function getDbConnection($index){
     // echo $index;
        $this -> index = $index;
        if($this -> index > $this -> getMaxIndex()){
            return "Out of range. Max index =".count($this -> dbConnectionArray);
        }else{
            return $this -> dbConnectionArray[$this -> index];
        }
     }

     public function getDbConnectionByName($shopName){
          foreach (array_values($this->dbConnectionArray) as $i => $value) {
                  if(strtolower($shopName) == $value['shopName']){
                      return $i;
                  }
          }
     }
     
     public function getShopsName(){
      $shopNameArray = array();
      
      for ($i = 0; $i < $this -> getMaxIndex(); $i++){
         $shopNameArray[] = array($this -> dbConnectionArray[$i]['shopName'],$this -> dbConnectionArray[$i]['shopNo']);
      }
      return $shopNameArray;
     }
}
?>