<?php

/**
  NO ONE MUST CHANGE THE CODES WRITTEN HERE UNLESS AUTHORIZED BY THE WEBMASTER
 This Model Class will be used for the SYSTEM OF CHATEAU DE GALERA.
 */

class BookingRoom_Model {

  public $konek;
  public function __construct($konek){
    $this->konek = $konek;
  }

  public function create_new_booking($br_auto_increment_id ,
                                     $booking_id ,
                                     $br_room_id ,
                                     $br_guest_id ,
                                     $br_guest_fname ,
                                     $br_guest_lname ,
                                     $br_guest_contact_num ,
                                     $br_guest_email,
                                     $br_checkin_date ,
                                     $br_checkout_date ,
                                     $br_number_of_days_to_stay ,
                                     $br_downpayment ,
                                     $br_payment_method ,
                                     $br_payment_reference_number ,
                                     $br_payment_status ,
                                     $br_booking_status ,
                                     $br_transaction_date){
    $cmd = "INSERT INTO tbl_booking_rooms VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $this->konek->prepare($cmd);
    $stmt->execute(array( $br_auto_increment_id ,
                          $booking_id ,
                          $br_room_id ,
                          $br_guest_id ,
                          $br_guest_fname ,
                          $br_guest_lname ,
                          $br_guest_contact_num ,
                          $br_guest_email,
                          $br_checkin_date ,
                          $br_checkout_date ,
                          $br_number_of_days_to_stay ,
                          $br_downpayment ,
                          $br_payment_method ,
                          $br_payment_reference_number ,
                          $br_payment_status ,
                          $br_booking_status ,
                          $br_transaction_date));
    if($stmt->rowCount()>0){
      echo "
        <h3 class='txt-info text-center shadow px-3 py-3'> Booking is Successful, You will be notified shortly via SMS. </h3>
      ";
    }else {
      echo "Error! Something Happened";
    }
  }

  public function get_all_pending_room_bookings($date_from, $date_to){
    $cmd = "SELECT * FROM tbl_booking_rooms 
                    INNER JOIN tbl_rooms 
                    ON tbl_booking_rooms.br_room_id = md5(tbl_rooms.room_id) 
                    WHERE br_booking_status='PENDING' 
                    AND br_checkin_date BETWEEN ? AND ? 
                    ORDER BY br_checkin_date ASC";
    // $cmd = "SELECT * FROM tbl_booking_rooms WHERE br_booking_status='PENDING' AND br_checkin_date BETWEEN ? AND ? ORDER BY br_checkin_date ASC";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($date_from, $date_to))){
      return $datas = $stmt->fetchAll();
    }else {
      echo "Error";
    }
  }

  public function get_all_booked_rooms($date_from, $date_to){
    $cmd = "SELECT * FROM tbl_booking_rooms WHERE br_booking_status='Accepted' AND br_checkin_date BETWEEN ? AND ? ORDER BY br_checkin_date ASC";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($date_from, $date_to))){
      return $datas = $stmt->fetchAll();
    }else {
      echo "Error";
    }

  }

  public function accept_pending_booking($booking_id){
    $cmd = "UPDATE tbl_booking_rooms SET br_booking_status='Accepted', br_payment_status='Verified' WHERE booking_id=? ";   
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($booking_id))){
      echo "Booking Accepted";
    }else {
      echo "Error";
    }
  }

  public function decline_pending_booking($booking_id){
    $cmd = "UPDATE tbl_booking_rooms SET br_booking_status='Declined', br_payment_status='Voided' WHERE booking_id=? ";   
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($booking_id))){
      echo "Booking Declined";
    }else {
      echo "Error";
    }
  }

  public function archive_booked_rooms($booking_id){
    $cmd = "UPDATE tbl_booking_rooms SET br_booking_status='Archived', br_payment_status='Paid' WHERE booking_id=? ";   
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($booking_id))){
      echo "Booking Archived";
    }else {
      echo "Error";
    }
  }

  public function check_existing_booking($room_id,$checkin_date,$booking_status){
    $cmd = "SELECT * FROM tbl_booking_rooms WHERE br_room_id=? AND br_checkin_date=? AND br_booking_status=? ";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($room_id,$checkin_date,$booking_status))){
      return $datas = $stmt->fetchAll();
    }else {
      echo "Error";
    }

  }

  public function get_count_of_pending_bookings_rooms(){
    $cmd = "SELECT COUNT(booking_id) AS count_of_pending_bookings FROM tbl_booking_rooms WHERE br_payment_status='Pending'";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array())){
      return $datas = $stmt->fetchAll();
    }else {
      echo "Error";
    }

  }



  



}



?>