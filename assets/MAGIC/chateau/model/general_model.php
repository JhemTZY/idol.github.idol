<?php

class General_model {

  public $konek;
  public function __construct($konek){
    $this->konek = $konek;
  }

  public function select_all_from($table){
    $cmd = "SELECT * FROM $table";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array())){
      return $datas = $stmt->fetchAll();
    }else {
      // echo "Error";
    }
  }

  public function select_all_from_where($table, $data1, $data2){
    $cmd = "SELECT * FROM $table WHERE $data1 = '$data2'";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array())){
      return $datas = $stmt->fetchAll();
    }else {
      // echo "Error";
    }
  }



}



?>
