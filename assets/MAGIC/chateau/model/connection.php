<?php

class Connection{
   private $host = "localhost";
   private $username = "root";
   private $password = "";
   private $db_name = "db_chateau";
   private $port = "3308";
   public $konek;

   public function connect(){
     $this->konek = null;
     try{
       $this->konek = new PDO("mysql:host=" . $this->host . "; port=".$this->port. ";dbname=" . $this->db_name, $this->username, $this->password);
     }catch(PDOException $exception){
     echo "Connection error: " . $exception->getMessage();
     }
     return $this->konek;
   }
}

?>
