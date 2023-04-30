<?php
include "include.php";

/**
  NO ONE MUST CHANGE THE CODES WRITTEN HERE UNLESS AUTHORIZED BY THE WEBMASTER
 This Controller Class will be used for SYSTEM of CHATEAU DE GALERA 
 */

 
class BookingRoom_Controller {

  function __construct(){
    $this->connection = new Connection();
    $this->konek = $this->connection->connect();
    $this->form_validation = new Form_Validation();
    $this->br_model = new BookingRoom_Model($this->konek);

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

  function get_all_pending_room_bookings($filter_date_from,$filter_date_to){
    $date_from = $filter_date_from;
    $date_to = $filter_date_to;
    $pending_br_rooms = $this->br_model->get_all_pending_room_bookings($date_from, $date_to);
    echo json_encode($pending_br_rooms);
  }

  function get_all_booked_rooms($filter_date_from,$filter_date_to){
    $date_from = $filter_date_from;
    $date_to = $filter_date_to;
    $booked_rooms = $this->br_model->get_all_booked_rooms($date_from, $date_to);
    echo json_encode($booked_rooms);
  }

  function accept_pending_booking($booking_id){
    $this->br_model->accept_pending_booking($booking_id);
  }

  function decline_pending_booking($booking_id){
    $this->br_model->decline_pending_booking($booking_id);
  }

  function archive_booked_rooms($booking_id){
    $this->br_model->archive_booked_rooms($booking_id);
  }
  

  function check_existing_booking(){
    $room_id = $this->xss_filter($_POST['room_id']);
    $checkin_date = $this->xss_filter($_POST['checkin_date']);
    $booking_data = $this->br_model->check_existing_booking($room_id,$checkin_date,"Accepted");
    $b_data_count =  count($booking_data);
    if($b_data_count>=1){
      // There is an approved booking for this date.
      echo "This Room is Fully Booked on this date.";
    }
  }

  function get_count_of_pending_bookings_rooms(){
    $count_of_pending_bookings =  $this->br_model->get_count_of_pending_bookings_rooms();
    // print_r($count_of_pending_bookings);
    echo $count_of_pending_bookings[0][0];
  }


}

// AXIOM
$cntrlr = new BookingRoom_Controller();
if(empty($_POST)){
  $vue_post_data = json_decode(file_get_contents("php://input"));
  $request = $vue_post_data->request;
  if($request=="get_all_pending_room_bookings"){
    $cntrlr->get_all_pending_room_bookings($vue_post_data->filter_date_from,$vue_post_data->filter_date_to);
  }
  else if($request=="get_all_booked_rooms"){
    $cntrlr->get_all_booked_rooms($vue_post_data->filter_date_from,$vue_post_data->filter_date_to);
  }
  else if($request=="accept_pending_booking"){
    $cntrlr->accept_pending_booking($vue_post_data->booking_id);
  }
  else if($request=="decline_pending_booking"){
    $cntrlr->decline_pending_booking($vue_post_data->booking_id);
  }
  else if($request=="archive_booked_rooms"){
    $cntrlr->archive_booked_rooms($vue_post_data->booking_id);
  }
  else if($request=="get_count_of_pending_bookings_rooms"){
    $cntrlr->get_count_of_pending_bookings_rooms();
  }
  else {
    echo "INTRUDER ALERT - OBJECT REQUEST";
  }
}
else{
// AJAX
  $request = $_POST['request'];
  if($request=="check_existing_booking"){
    $cntrlr->check_existing_booking();
  }
  else{
    echo "INTRUDER ALERT - AJAX";
  }
}