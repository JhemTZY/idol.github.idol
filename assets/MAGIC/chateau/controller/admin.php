<?php
include "include.php";

/**
  NO ONE MUST CHANGE THE CODES WRITTEN HERE UNLESS AUTHORIZED BY THE WEBMASTER
 This Controller Class will be used for ADMIN of CHATEAU DE GALERA 
 */

 
class Admin {

  function __construct(){
    $this->connection = new Connection();
    $this->konek = $this->connection->connect();
    $this->admin_model = new Admin_model($this->konek);
    $this->form_validation = new Form_Validation();
    $this->timestamp = time() + (1 * 24 * 30 * 40);
    $this->dateTime = gmdate("F j, Y | h:i:s A",$this->timestamp);
    $this->date_html = gmdate("Y-m-j",$this->timestamp);
  }

  function xss_filter($data){
    if($this->form_validation->submitted()){
      $data = $this->form_validation->xss_clean($this->form_validation->test_input($data));
      return $data;
    }
  }

  function add_room(){
    if($this->form_validation->submitted()){
      $room_name = $this->form_validation->xss_clean($_POST['room_name']);
      $room_details = $this->form_validation->xss_clean($_POST['room_details']);
      $room_price = $this->form_validation->xss_clean($_POST['room_price']);
      $room_limit = $this->form_validation->xss_clean($_POST['room_limit']);
      $room_photo_link = $this->form_validation->xss_clean($_POST['room_photo_link']);
      if($this->form_validation->run()){
        print_r($_POST);
        $this->admin_model->add_room(NULL,$room_name,$room_details,$room_price,$room_limit,$room_photo_link,"Available");
      }
    }

    
}

function add_menu(){
  $dt = json_decode(file_get_contents("php://input"));
  $menu_item_name = $this->form_validation->xss_clean($dt->menu_item_name);
  $menu_item_category = $this->form_validation->xss_clean($dt->menu_item_category);
  $menu_item_description = $this->form_validation->xss_clean($dt->menu_item_description);
  $menu_item_min_pax = $this->form_validation->xss_clean($dt->menu_item_min_pax);
  $menu_item_max_pax = $this->form_validation->xss_clean($dt->menu_item_max_pax);
  $menu_item_price = $this->form_validation->xss_clean($dt->menu_item_price);
  $menu_item_measurement = $this->form_validation->xss_clean($dt->menu_item_measurement);
  $menu_item_unit_of_measurement = $this->form_validation->xss_clean($dt->menu_item_unit_of_measurement);
  $menu_item_preparation_time = $this->form_validation->xss_clean($dt->menu_item_preparation_time);
  $menu_item_photo_link = $this->form_validation->xss_clean($dt->menu_item_photo_link);

  $this->form_validation->empty($dt->menu_item_name,"Empty");
  $this->form_validation->empty($dt->menu_item_category,"Empty");
  $this->form_validation->empty($dt->menu_item_description,"Empty");
  $this->form_validation->empty($dt->menu_item_min_pax,"Empty");
  $this->form_validation->empty($dt->menu_item_max_pax,"Empty");
  $this->form_validation->empty($dt->menu_item_price,"Empty");
  $this->form_validation->empty($dt->menu_item_measurement,"Empty");
  $this->form_validation->empty($dt->menu_item_unit_of_measurement,"Empty");
  $this->form_validation->empty($dt->menu_item_preparation_time,"Empty");
  $this->form_validation->empty($dt->menu_item_photo_link,"Empty");

  if($this->form_validation->run()){
    $menu_item_id = "CM-".mt_rand(1000,999999);
    $this->admin_model->add_menu(
      $menu_item_id,
      $menu_item_name,
      $menu_item_category,
      $menu_item_description,
      $menu_item_min_pax,
      $menu_item_max_pax,
      $menu_item_price,
      $menu_item_measurement,
      $menu_item_unit_of_measurement,
      $menu_item_preparation_time,
      $menu_item_photo_link,
      "Available"
    );
  }else{
      $errs =  json_decode($this->form_validation->validation_errors());
      print_r($errs);
  }
}

function get_all_pending_orders(){
  $dt = json_decode(file_get_contents("php://input"));
  $date_from = $this->form_validation->xss_clean($dt->filter_date_from);
  $date_to = $this->form_validation->xss_clean($dt->filter_date_to);
  
  $pending_orders = $this->admin_model->get_all_pending_orders($date_from, $date_to);
  echo json_encode($pending_orders);
}

function get_placed_orders_by_guest_id(){
  $dt = json_decode(file_get_contents("php://input"));
  $guest_id = $this->form_validation->xss_clean($dt->order_guest_id);
  $placed_orders_by_id = $this->admin_model->get_placed_orders_by_guest_id($guest_id);
  echo json_encode($placed_orders_by_id);
  // echo $guest_id;
  
}

}


// AXIOM
$cntrlr = new Admin();
if(empty($_POST)){
  $dt = json_decode(file_get_contents("php://input"));
  $request = $dt->request;
  if($request=="add_menu"){
    $cntrlr->add_menu();
  }
  else if ($request=="get_all_pending_orders"){
    $cntrlr->get_all_pending_orders();
  }
  else if ($request=="get_placed_orders_by_guest_id"){
    $cntrlr->get_placed_orders_by_guest_id();
  }
  else {
    echo "INTRUDER ALERT - OBJECT REQUEST";
  }
}
else{
// AJAX
  $request = $_POST['request'];
  if($request=="add_room"){
    $cntrlr->add_room();
  }
}