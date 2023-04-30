<!doctype html>
<html lang="en">
<?php include "template-header.php";?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" id="admin_dashboard">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <!-- <div class="btn-group me-2">
              <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
              <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
              <span data-feather="calendar"></span>
              This week
            </button> -->
        </div>
    </div>

    <div class="h2 card-header bg-white text-center text-dark">PENDING</div>
    <div class="row mb-3">
        <div class="col-sm-3">
            <div class="card">
                <div class="card-body bg-white text-dark">
                    <small>Booking</small>
                    <div style="font-size: 2vw">Rooms <span> <small> <a href="Admin-Dashboard-Booking-Rooms"
                                    class="btn btn-sm btn-outline-dark "> View </a> </small> </span></div>
                    <div class="h1" id="lbl_quantity_booking_rooms"> {{count_of_pending_bookings}} </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <div class="card-body bg-white text-dark ">
                    <small>Booking</small>
                    <div style="font-size: 2vw">Transportation <span> <small> <a
                                    href="Admin-Dashboard-Booking-Transportation" class="btn btn-sm btn-outline-dark ">
                                    View </a> </small> </span></div>
                    <div class="h1" id="lbl_quantity_booking_transpo"> 0 </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <div class="card-body bg-white text-dark">
                    <div style="font-size: 2vw">Ordering <span> <small> <a href="Admin-Dashboard-Ordering"
                                    class="btn btn-sm btn-outline-dark "> View </a> </small> </span></div>
                    <div class="h1" id="lbl_quantity_ordering"> 0 </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <div class="card-body bg-white text-dark">
                    <div style="font-size: 2vw">Customers <span> <small> <a href="Admin-Dashboard-Customers"
                                    class="btn btn-sm btn-outline-dark "> View </a> </small> </span></div>
                    <div class="h1" id="lbl_quantity_customers"> 0 </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <h2>Section title</h2>
        <div class="table-responsive">
          <table class="table table-striped table-sm">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Header</th>
                <th scope="col">Header</th>
                <th scope="col">Header</th>
                <th scope="col">Header</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1,001</td>
                <td>random</td>
                <td>data</td>
                <td>placeholder</td>
                <td>text</td>
              </tr>
              
            </tbody>
          </table>
        </div> -->
</main>
</div>
</div>

<?php include "template-footer.php"?>

<script>
var app = new Vue({
    el: "#admin_dashboard",
    data: {
        count_of_pending_bookings: '',
    },
    methods: {
        get_count_of_pending_bookings_rooms: () => {
            axios
                .post("controller/Booking_Rooms.php", {
                    request: "get_count_of_pending_bookings_rooms"
                })
                .then((response) => {
                  app.count_of_pending_bookings = response.data;
                })
        }
    },
    created: function(){
      var refresh_time = 1000 * 60  * 15; // 15 Minutes Refresh Time
      this.get_count_of_pending_bookings_rooms();
      console.log(refresh_time);
      setInterval(()=>{
        this.get_count_of_pending_bookings_rooms();
      },refresh_time)
    }
});
</script>


</body>

</html>