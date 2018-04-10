<?php
    namespace MMS;
    require 'Database.php';
    use MMS\Database as DB;
    
    class Visit{
        public function __construct(){
            $mysqli = DB::init();
        }
    }
?>
