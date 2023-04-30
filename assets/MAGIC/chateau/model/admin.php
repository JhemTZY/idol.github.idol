<?php

class Admin_model{
    
  public $konek;
  public function __construct($konek){
    $this->konek = $konek;
  }

  public function add_room($room_id,$room_name,$room_details,$room_price,$room_limit,$room_photo_link,$room_status){
    $cmd = "INSERT INTO tbl_rooms VALUES (?,?,?,?,?,?,?)";
    $stmt = $this->konek->prepare($cmd);
    $stmt->execute(array($room_id,$room_name,$room_details,$room_price,$room_limit,$room_photo_link,$room_status));
    if($stmt->rowCount()>0){
      echo "Successfully Added New Room ";
    }else {
      echo "Error! Something Happened";
    }
  }

  public function add_menu(
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
      $menu_status ){
    $cmd = "INSERT INTO tbl_menu VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?)";
    $stmt = $this->konek->prepare($cmd);
    $stmt->execute(array(
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
      $menu_status));
    if($stmt->rowCount()>0){
      echo "Successfully Added ";
    }else {
      echo "Error! Something Happened";
    }
  }

  public function get_all_pending_orders($date_from, $date_to){
    $cmd = "SELECT * FROM tbl_orders 
    WHERE order_status='Pending' 
    AND order_pickup_or_deliver_date BETWEEN ? AND ?
    ORDER BY order_pickup_or_deliver_date ASC";
    // $cmd = "SELECT * FROM tbl_booking_rooms WHERE br_booking_status='PENDING' AND br_checkin_date BETWEEN ? AND ? ORDER BY br_checkin_date ASC";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($date_from, $date_to))){
      return $datas = $stmt->fetchAll();
    }else {
      echo "Error";
    }
  }

  public function get_placed_orders_by_guest_id($cart_guest_id){
    $cmd = "SELECT cart_guest_id, cart_menu_item_id,cart_quantity,cart_status,menu_item_id,menu_item_name,menu_item_price, 
            (cart_quantity * menu_item_price) AS subtotal
            FROM `tbl_cart` 
            INNER JOIN tbl_menu 
            ON cart_menu_item_id = tbl_menu.menu_item_id 
            WHERE cart_guest_id=? 
            AND cart_status='PLACED' ";
    // $cmd = "SELECT * FROM tbl_booking_rooms WHERE br_booking_status='PENDING' AND br_checkin_date BETWEEN ? AND ? ORDER BY br_checkin_date ASC";
    $stmt = $this->konek->prepare($cmd);
    if($stmt->execute(array($cart_guest_id))){
      return $datas = $stmt->fetchAll();
    }else {
      echo "Error";
    }
  }
  


}

?>