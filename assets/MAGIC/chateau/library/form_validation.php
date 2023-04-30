<?php

class Form_Validation{
  public $errors = array();

  // function __construct(){
  //   $this->timestamp = time() + (1 * 24 * 30 * 40);
  //   $this->dateTime = gmdate("F j, Y | h:i:s A",$this->timestamp);
  //   $this->date_html = gmdate("Y-m-j",$this->timestamp);
  // }

  public function submitted() {
      return !empty($_POST) ? TRUE : FALSE;
  }

  public function test_input($data)
  {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
  }

  public function date_expired($date,$current_date,$error_message){
    if($date < $current_date){
      array_push($this->errors,$error_message);
    }
  }

  public function matches($field1,$field2,$error_message){
    if($field1 !== $field2)
      array_push($this->errors,$error_message);
  }

  public function empty($field,$error_message){
    if(empty($field))
      array_push($this->errors,$error_message);
  }

  public function min_length($field1,$length, $error_message) {
      if ( ! is_numeric($length))
          return FALSE;
      if(mb_strlen($field1) < $length){
        array_push($this->errors,$error_message);
      }
      return $this;
  }
  public function max_length($field1,$length, $error_message) {
    if ( ! is_numeric($length))
        return FALSE;
    if(mb_strlen($field1) > $length){
      array_push($this->errors,$error_message);
    }
    return $this;
}

  public function alpha_numeric_space($field,$error_message){
      if(!preg_match('/^[A-Z0-9 ]+$/i', $field))
        array_push($this->errors,$error_message);
      return $this;
  }

  public function valid_email($email, $error_message ){
      if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        array_push($this->errors,$error_message);
      return $this;
  }

  public function numeric($field,$error_message) {
      if(!preg_match('/^[\-+]?[0-9]*\.?[0-9]+$/', $field))
        array_push($this->errors,$error_message);
      return $this;
  }

  public function is_unique($konek,$table,$column,$condition,$error_message){
    $cmd = "SELECT * FROM $table WHERE $column=? LIMIT 1";
    $stmt = $konek->prepare($cmd);
    $stmt->execute(array($condition));
    if(!$stmt->rowCount() == 0){
      array_push($this->errors,$error_message);
    }

  }

  public function does_exist($konek,$table,$column,$condition,$error_message){
    $cmd = "SELECT * FROM $table WHERE $column=? LIMIT 1";
    $stmt = $konek->prepare($cmd);
    $stmt->execute(array($condition));
    if(!$stmt->rowCount() == 0){
      array_push($this->errors,$error_message);
    }
  }
  
  public function doesnt_exist($konek,$table,$column,$condition,$error_message){
    $cmd = "SELECT * FROM $table WHERE $column=? LIMIT 1";
    $stmt = $konek->prepare($cmd);
    $stmt->execute(array($condition));
    if(!$stmt->rowCount() != 0){
      array_push($this->errors,$error_message);
    }

  }


  public function run(){
    if(count($this->errors) >0){
      return FALSE;
    }else {
      return TRUE;
    }
  }

  public function validation_errors(){
    // var_export($this->errors);
    $error_json = json_encode($this->errors);
    return $error_json;
  }

  public function xss_clean($data){
  // Fix &entity\n;
  $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
  $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
  $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
  $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

  // Remove any attribute starting with "on" or xmlns
  $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

  // Remove javascript: and vbscript: protocols
  $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
  $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
  $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

  // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
  $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
  $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
  $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

  // Remove namespaced elements (we do not need them)
  $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

  do
  {
      // Remove really unwanted tags
      $old_data = $data;
      $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
  }
  while ($old_data !== $data);

  // we are done...
  return $data;
  }


}

?>