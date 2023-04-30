<?php
include "include.php";

/**
  NO ONE MUST CHANGE THE CODES WRITTEN HERE UNLESS AUTHORIZED BY THE WEBMASTER
 This Controller Class will be used for SYSTEM of CHATEAU DE GALERA 
 */

 
class Ordering_Controller {

  function __construct(){
    $this->connection = new Connection();
    $this->konek = $this->connection->connect();
    $this->form_validation = new Form_Validation();
    $this->ordering_model = new Ordering_Model($this->konek);

    $this->timestamp = time() + (1 * 24 * 30 * 40);
    $this->dateTime = gmdate("F j, Y | h:i:s A",$this->timestamp);
    $this->date_html = gmdate("Y-m-d",$this->timestamp);

  }

  function xss_filter($data){
    if($this->form_validation->submitted()){
      $data = $this->form_validation->xss_clean($this->form_validation->test_input($data));
      return $data;
    }
  }

  function get_menu_based_on_category(){
    $dt = json_decode(file_get_contents("php://input"));
    $menu_category = $this->form_validation->xss_clean($dt->menu_category);
    $menu = $this->ordering_model->get_menu_based_on_category($menu_category);
    echo json_encode($menu);
  }

  function add_to_cart(){
    $dt = json_decode(file_get_contents("php://input"));
    $food_id = $this->form_validation->xss_clean($dt->food_id);
    $guest_id = $this->form_validation->xss_clean($dt->guest_id);
    $isItemInGuestCart_existing = $this->ordering_model->isItemInGuestCart_existing($guest_id,$food_id);

    if($isItemInGuestCart_existing){
      $this->ordering_model->update_cartQuantity_increment($guest_id,$food_id);
    }
    else{
      $this->ordering_model->add_to_cart($guest_id,$food_id,+1,$this->date_html,"ACTIVE");
    }
  }

  // function cart_abandoned(){
  //   $dt = json_decode(file_get_contents("php://input"));
  //   $guest_id = $this->form_validation->xss_clean($dt->guest_id);
  //   $this->ordering_model->cart_is_Abandoned($guest_id);
  // }

  function get_active_cart_items(){
    $dt = json_decode(file_get_contents("php://input"));
    $guest_id = $this->form_validation->xss_clean($dt->guest_id);
    $cart_items =  $this->ordering_model->get_active_cart_items($guest_id);
    echo json_encode($cart_items);
    // print_r($cart_items);
  }

  function get_placed_cart_items(){
    $dt = json_decode(file_get_contents("php://input"));
    $guest_id = $this->form_validation->xss_clean($dt->guest_id);
    $cart_items =  $this->ordering_model->get_placed_cart_items($guest_id);
    echo json_encode($cart_items);
    // print_r($cart_items);
  }

  function remove_from_cart(){
    $dt = json_decode(file_get_contents("php://input"));
    $guest_id = $this->form_validation->xss_clean($dt->guest_id);
    $food_id = $this->form_validation->xss_clean($dt->food_id);
    $this->ordering_model->remove_from_cart($guest_id,$food_id);
  }

  function place_order(){
    $dt = json_decode(file_get_contents("php://input"));
    $guest_id = $this->form_validation->xss_clean($dt->guest_id);
    $this->ordering_model->place_order($guest_id);
  }

  function get_total_amount_of_active_orders(){
    $dt = json_decode(file_get_contents("php://input"));
    $guest_id = $this->form_validation->xss_clean($dt->guest_id);
    $total_amount_of_orders = $this->ordering_model->get_total_amount_of_active_orders($guest_id);
    // echo json_encode(json_encode($total_amount_of_orders)) ;
    echo $total_amount_of_orders['total_amount_of_orders'];
  }

  function get_total_amount_of_placed_orders(){
    $dt = json_decode(file_get_contents("php://input"));
    $guest_id = $this->form_validation->xss_clean($dt->guest_id);
    $total_amount_of_orders = $this->ordering_model->get_total_amount_of_placed_orders($guest_id);
    // echo json_encode(json_encode($total_amount_of_orders)) ;
    echo $total_amount_of_orders['total_amount_of_orders'];
  }

  function confirm_order(){
    $dt = json_decode(file_get_contents("php://input"));
    $order_id = $this->form_validation->xss_clean($dt->order_id);
    $order_guest_id = $this->form_validation->xss_clean($dt->order_guest_id);
    $order_guest_fname = $this->form_validation->xss_clean($dt->order_guest_fname);
    $order_guest_lname = $this->form_validation->xss_clean($dt->order_guest_lname);
    $order_guest_contact = $this->form_validation->xss_clean($dt->order_guest_contact);
    $order_guest_email = $this->form_validation->xss_clean($dt->order_guest_email);
    $order_mode_of_order = $this->form_validation->xss_clean($dt->order_mode_of_order);
    $order_pickup_deliver_date = $this->form_validation->xss_clean($dt->order_pickup_deliver_date);
    $order_pickup_deliver_time = $this->form_validation->xss_clean($dt->order_pickup_deliver_time);
    $order_payment_method = $this->form_validation->xss_clean($dt->order_payment_method);
    $order_total_amount_to_pay = $this->form_validation->xss_clean($dt->order_total_amount_to_pay);
    $order_reference_number = $this->form_validation->xss_clean($dt->order_reference_number);
    // $order_id = "ORDR-".gmdate('Y',$this->timestamp).gmdate('m',$this->timestamp).gmdate('d',$this->timestamp)."-".substr(mt_rand(999,99999999),3);
    
    $this->form_validation->empty($order_payment_method,"Please Select Payment Method");
    $this->form_validation->empty($order_guest_contact,"Contact Number shouldn't be empty");
    $this->form_validation->numeric($order_guest_contact,"Invalid Contact Number");
    $this->form_validation->empty($order_guest_email,"Email shouldn't be empty");
    $this->form_validation->valid_email($order_guest_email,"Email is invalid");
    $this->form_validation->empty($order_pickup_deliver_date,"Select Date of Order");
    $this->form_validation->date_expired($order_pickup_deliver_date,"$this->date_html","Date of Order is Expired");
    $this->form_validation->empty($order_pickup_deliver_time,"Select Time of Order");
    $this->form_validation->empty($order_reference_number,"Enter your Payment Reference Number");
    $this->form_validation->does_exist($this->konek,"tbl_orders","order_id",$order_id,"Order Already Exist.");
   
    if($this->form_validation->run()){
      $this->ordering_model->confirm_order( $order_id, $order_guest_id, $order_guest_fname , $order_guest_lname, $order_guest_contact, $order_guest_email, $order_mode_of_order, $order_pickup_deliver_date, $order_pickup_deliver_time, $order_payment_method, $order_total_amount_to_pay, "Pending", $order_reference_number, $this->dateTime, "Pending");
    }
    else{
      echo $this->form_validation->validation_errors(); 
      // echo json_encode(array($order_id,$order_pickup_deliver_date,$this->date_html));
    }
  }

}

// AXIOM
$cntrlr = new Ordering_Controller();
if(empty($_POST)){
  $pd = json_decode(file_get_contents("php://input"));
  $request = $pd->request;
  if($request=="get_menu_based_on_category"){
    $cntrlr->get_menu_based_on_category();
  }
  else if ($request=="add_to_cart"){
    $cntrlr->add_to_cart();
  }
  else if($request=="get_active_cart_items"){
    $cntrlr->get_active_cart_items();
  }
  else if($request=="get_placed_cart_items"){
    $cntrlr->get_placed_cart_items();
  }
  // else if($request=="cart_abandoned"){
  //   $cntrlr->cart_abandoned();
  // }
  else if($request=="remove_from_cart"){
    $cntrlr->remove_from_cart();
  }
  else if($request=="place_order"){
    $cntrlr->place_order();
  }
  else if($request=="get_total_amount_of_active_orders"){
    $cntrlr->get_total_amount_of_active_orders();
  }
  else if($request=="get_total_amount_of_placed_orders"){
    $cntrlr->get_total_amount_of_placed_orders();
  }
  else if($request=="confirm_order"){
    $cntrlr->confirm_order();
  }
  else {
    echo "INTRUDER ALERT - OBJECT REQUEST";
  }
}
else{
// AJAX
  $request = $_POST['request'];
  if($request==""){
  }
  else{
    echo "INTRUDER ALERT - AJAX";
  }
}