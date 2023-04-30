<!doctype html>
<html lang="en">
<?php include "template-header.php";?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4" id="app">
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Tools</h1>
    </div>


    <div class="h2">BOOKING MANAGEMENT | ROOMS</div>
    <div class="row mb-5">
        <div class="col-md-3">
            <a class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#add_room_modal">
                <div class="card btn btn-dark shadow bg-primary">
                    <div class="card-body">
                        <div class="h3">Add Room</div>
                    </div>
                </div>
            </a>
        </div>

    </div>

    <div class="h2">ORDERING MANAGEMENT | MENU</div>
    <div class="row mb-5">
        <div class="col-md-3">
            <a class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#add_menu_modal">
                <div class="card btn btn-dark shadow bg-primary">
                    <div class="card-body">
                        <div class="h3">Add Menu</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Add Room Modal -->
    <div class="modal fade" id="add_room_modal" tabindex="-1" aria-labelledby="add_room_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_room_modal">Add a Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class='row px-3 py-3'>
                        <div class='col-lg-12'>
                            <div class='card'>
                                <div class='card-body'>
                                    <div class='forms'>
                                        <div class="mb-3">
                                            <label for="room_name" class="form-label">Room Name</label>
                                            <input type="email" class="form-control" id="txt_room_name"
                                                aria-describedby="emailHelp">
                                        </div>
                                        <div class="mb-3">
                                            <label for="room_details" class="form-label">Room Details</label>
                                            <textarea class="form-control" id="txt_room_details" rows='3'> </textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="room_price" class="form-label">Room Price</label>
                                            <input type="text" class="form-control" id="txt_room_price">
                                        </div>
                                        <div class="mb-3">
                                            <label for="room_limit" class="form-label">Room Limit</label>
                                            <input type="text" class="form-control" id="txt_room_limit">
                                        </div>
                                        <div class="mb-3">
                                            <label for="room_photo_link" class="form-label">Room Photo Link</label>
                                            <select class='form-control' id="txt_room_photo_link">
                                                <option value="NULL">Select Photo</option>
                                                <?php
                                                    $dir = "views/user/img/Rooms";
                                                    $room_photos = scandir($dir,1);
                                                    foreach($room_photos as $photos):                                      
                                                ?>
                                                <option value="<?=$photos?>"><?=$photos?></option>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button class="btn btn-dark" type="button" id="btn_addRoom">Create
                                                Room</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Add Room Modal End -->

    <!-- Add Menu Modal -->
    <div class="modal fade" id="add_menu_modal" tabindex="-1" aria-labelledby="add_menu_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add_menu_modal">Add a Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="h3 text-center">{{server_message}} </div>
                    <div class='row px-3 py-3' id="form_add_menu">
                        <div class='col-lg-6'>
                            <div class='card'>
                                <div class='card-body'>

                                    <div class="mb-3">
                                        <label for="txt_item_name" class="form-label">Item Name</label>
                                        <input type="text" class="form-control" v-model="menu_item_name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="txt_item_category" class="form-label">Item Category</label>
                                        <input type="text" class="form-control" v-model="menu_item_category">
                                    </div>
                                    <div class="mb-3">
                                        <label for="txt_item_description" class="form-label">Item
                                            Description</label>
                                        <input type="text" class="form-control" v-model="menu_item_description">
                                    </div>
                                    <div class="mb-3">
                                        <label for="txt_item_min_pax" class="form-label">Min Pax</label>
                                        <input type="text" class="form-control" v-model="menu_item_min_pax">
                                    </div>
                                    <div class="mb-3">
                                        <label for="txt_item_max_pax" class="form-label">Max Pax</label>
                                        <input type="text" class="form-control" v-model="menu_item_max_pax">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='col-lg-6'>
                            <div class='card'>
                                <div class='card-body'>
                                    <div class="mb-3">
                                        <label for="txt_item_price" class="form-label">Item Price</label>
                                        <input type="text" class="form-control" v-model="menu_item_price">
                                    </div>
                                    <div class="mb-3">
                                        <label for="txt_item_measurement" class="form-label">Item
                                            Measurement</label>
                                        <input type="text" class="form-control" v-model="menu_item_measurement">
                                    </div>
                                    <div class="mb-3">
                                        <label for="txt_item_uom" class="form-label">Item Unit of
                                            Measurement</label>
                                        <input type="text" class="form-control" v-model="menu_item_unit_of_measurement">
                                    </div>
                                    <div class="mb-3">
                                        <label for="txt_item_prep_time" class="form-label">Item Preparation
                                            Time</label>
                                        <input type="text" class="form-control" v-model="menu_item_preparation_time">
                                    </div>
                                    <div class="mb-3">
                                        <label for="txt_item_photo_link" class="form-label">Item Photo Link</label>
                                        <input type="text" class="form-control" v-model="menu_item_photo_link">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mt-3">
                            <button class="btn btn-dark" type="button" @click="add_menu">Create
                                Menu</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Add Menu Modal End -->

</main>
</div>
</div>


<?php include "template-footer.php"?>

<script>
$(document).ready(() => {


    $("#btn_addRoom").on('click', () => {
        var request = "add_room";
        var room_name = $("#txt_room_name").val();
        var room_details = $("#txt_room_details").val();
        var room_price = $("#txt_room_price").val();
        var room_limit = $("#txt_room_limit").val();
        var room_photo_link = $("#txt_room_photo_link").val();

        $.ajax({
            url: "controller/admin.php",
            type: "POST",
            data: {
                request: request,
                room_name: room_name,
                room_details: room_details,
                room_price: room_price,
                room_limit: room_limit,
                room_photo_link: room_photo_link
            },
            success: (result) => {
                // console.log(result);
            }

        })
    })
})
</script>

<script>
var app = new Vue({
    el: "#app",
    data: {
        menu_item_name: '',
        menu_item_category: '',
        menu_item_description: '',
        menu_item_min_pax: '',
        menu_item_max_pax: '',
        menu_item_price: '',
        menu_item_measurement: '',
        menu_item_unit_of_measurement: '',
        menu_item_preparation_time: '',
        menu_item_photo_link: '',
        server_message: ''
    },
    methods: {
        add_menu: () => {
            axios
                .post("controller/admin.php", {
                    request: "add_menu",
                    menu_item_name: app.menu_item_name,
                    menu_item_category: app.menu_item_category,
                    menu_item_description: app.menu_item_description,
                    menu_item_min_pax: app.menu_item_min_pax,
                    menu_item_max_pax: app.menu_item_max_pax,
                    menu_item_price: app.menu_item_price,
                    menu_item_measurement: app.menu_item_measurement,
                    menu_item_unit_of_measurement: app.menu_item_unit_of_measurement,
                    menu_item_preparation_time: app.menu_item_preparation_time,
                    menu_item_photo_link: app.menu_item_photo_link,
                })
                .then((response) => {
                    if (response.data.length > 0) {
                        app.server_message = response.data + '-' + app.menu_item_name;
                        app.menu_item_name = '';
                        app.menu_item_category = '';
                        app.menu_item_description = '';
                        app.menu_item_min_pax = '';
                        app.menu_item_max_pax = '';
                        app.menu_item_price = '';
                        app.menu_item_measurement = '';
                        app.menu_item_unit_of_measurement = '';
                        app.menu_item_preparation_time = '';
                        app.menu_item_photo_link = '';
                    } else {
                        // app.server_message = '';
                    }
                })
        }
    }
})
</script>

</body>

</html>