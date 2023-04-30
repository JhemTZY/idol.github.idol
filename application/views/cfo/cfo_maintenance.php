<div class="content-wrapper" id="cfomaintenance">
    <!-- Content Header (Page header) -->
    <section class="content-header ">
        <h1 style="color: white;">
            <i class="fa fa-line-chart" aria-hidden="true"></i> CFO |</small>
            Maintenance</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-6">
                <div class="box">
                    <div class="box-header">
                        <div class="box-title"> Change Customer Color </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <thead>
                                <th>Customer</th>
                                <th>Color A</th>
                                <th>Color B</th>
                            </thead>
                            <tbody>
                                <template v-for="customer in CustomersColor">
                                    <tr>
                                        <td> {{ customer.customers_code }} </td>
                                        <td><input class="form-control" type="color" :value='customer.customer_color_A' @change="change_color(customer.customers_code)"></td>
                                        <td><input class="form-control" type="color" :value='customer.customer_color_B' @change="change_color(customer.customers_code)"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>

<!-- VUE JS SCRIPT FOR THIS PAGE ONLY -->
<script>
    var app = new Vue({
        el: '#cfomaintenance',
        data: {
            CustomersColor: [],

        },
        methods: {
            getCustomersColor: function() {
                axios.post("<?= base_url() ?>index.php/CFO/getCustomersColor", {

                }).then((response) => {
                    console.log(response.data)
                    app.CustomersColor = response.data.customers_color;
                })
            },
            change_color:function(customers_code,color){
                console.log(customers_code);
                console.log(color);
            }
        },
        created: function() {
            this.getCustomersColor();
        }
    });
</script>