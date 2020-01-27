 <?php
     class DbConnection
     {
        public $pdo = null;

        function __construct($dbConnectionArray){
         try{

             $this->pdo = new PDO($dbConnectionArray["server"],$dbConnectionArray["user"],$dbConnectionArray["password"]);
         }
         catch (PDOException $e){
             // var_dump($e);
             $this->pdo = new PDO($dbConnectionArray["localServer"],$dbConnectionArray["user"],$dbConnectionArray["password"]);
         }
        }
     }