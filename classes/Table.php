<?php
    class Table{

        private $table = "";
        private $tableHeader = "";
        private $tableRow = "";

        function __construct($class){
            if(strlen($class)!=0){
                $class = ' class="'.$class.'"';
            }
            $this -> table = "<table".$class.">";
        }

        function addHeader($class,$array){
            $header = "";

            if(strlen($class)!=0){
                $class = ' class="'.$class.'"';
            }

            if(count($array) > 0){
                $header .= "<TR>";
                for($i = 0; $i < count($array); $i++){
                    $header .= "<TH".$class.">".$array[$i]."</TH>";
                }
                $header .= "</TR>";
            }
            $this -> tableHeader = $header;
        }

        function addRow($class,$array){
            $header = "";

            if(strlen($class)!=0){
                $class = ' class="'.$class.'"';
            }

            if(count($array) > 0){
                $header .= "<TR>";
                for($i = 0; $i < count($array); $i++){
                    if ($i!=0){
                        $header .= "<TD".$class.">".$array[$i]."</TD>";
                    }else{
                        $header .= "<TD>".$array[$i]."</TD>";
                    }

                }
                $header .= "</TR>";
            }
            $this -> table .= $header;
        }

        function addRowT($class,$array){
            $header = "";

            if(count($array) > 0) {

                if (strlen($class) != 0) {
                    $class = ' class="' . $class . '"';
                }
                $isArray = is_array($array[0]) ? 1 : 0;
                if ($isArray == 1) {

                    for ($i = 0; $i < count($array); $i++) {
                        $header .= "<TR>";
                        foreach ($array[$i] as $key => $value) {
                            if ($i != 0) {
                                $header .= "<TD" . $class . ">" . $value . "</TD>";
                            } else {
                                $header .= "<TD>" . $value . "</TD>";
                            }
                        }
                        $header .= "</TR>";
                    }

                } else {
                    if (count($array) > 0) {
                        $header .= "<TR>";
                        for ($i = 0; $i < count($array); $i++) {
                            if ($i != 0) {
                                $header .= "<TD" . $class . ">" . $array[$i] . "</TD>";
                            } else {
                                $header .= "<TD>" . $array[$i] . "</TD>";
                            }
                        }
                        $header .= "</TR>";
                    }
                }

                $this -> tableRow = $header;
            }
        }
    
    function showTable(){
        if($this->tableRow != ""){
            $this->table .= $this->tableHeader;
            $this->table .= $this->tableRow;
            $this -> table .= "</table>";
            return $this -> table;
        }
        else{
            return "No transfers sent.";
        }
    }
    
}