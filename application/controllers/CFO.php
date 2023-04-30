<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Task (TaskController)
 * Task Class to control task related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 19 Jun 2022
 */
class CFO extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->helper(array('url', 'html', 'form'));
        $this->load->model('Task_model', 'tm');
        $this->load->library('functions', 'ci');
        $this->isLoggedIn();
        $this->module = 'Task';
    }

    public function cfo_maintenance()
    {
        $this->global['pageTitle'] = 'DWH : CFO Maintenance';
        $data['dat'] = "";
        $this->loadViews("cfo/cfo_maintenance", $this->global, $data, NULL);
    }

    public function cfo_sales_comparison()
    {
        $pd = $_POST;
        $revFrom = $revTo = "";
        if (count($pd) > 0) {
            $revFrom = $pd['txt_revision_from'];
            $revTo = $pd['txt_revision_to'];
        }
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data['revision'] = $this->tm->get_revision();

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {

            $this->global['pageTitle'] = 'DWH : CFO';
            $dateFrom = date("Y-01-01");
            $dateTo = date("Y-m-31", strtotime("+7 month"));

            $data['revFrom'] = $revFrom;
            $data['revTo'] = $revTo;

            $data['current_rev'] = $this->tm->get_dates_from_current_revision($selectedDPRNO, $dateFrom, $dateTo);
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer ORDER BY customers_id ASC")->result();
            $this->loadViews("cfo/cfo_sales_comparison_excel", $this->global, $data, NULL);
        }
    }

    public function cfo_sales_comparison_models_graph()
    {
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {

            $data["page"] = $page;
            $data["selectedDPRNO"] = $selectedDPRNO;
            $data['revisions'] = $this->tm->get_revision();
            $this->global['pageTitle'] = 'DWH : CFO - Sales Comparison Graph';
            $this->loadViews("cfo/cfo_sales_comparison_model_graph", $this->global, $data, NULL);
            /* $this->loadViews("reports/404.php", $this->global, $data, NULL);*/
        }
    }

    public function cfo_sales_comparison_customer_graph()
    {
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {

            $data["page"] = $page;
            $data["selectedDPRNO"] = $selectedDPRNO;
            $data['revisions'] = $this->tm->get_revision();
            $this->global['pageTitle'] = 'DWH : CFO - Sales Comparison Graph';
            $this->loadViews("cfo/cfo_sales_comparison_customer_graph", $this->global, $data, NULL);
        }
    }



    /*---------------------------------------------------------------------------------------------------------------
        VueJS
    ---------------------------------------------------------------------------------------------------------------*/

    function get_customers()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $customers = $this->db->query("SELECT * FROM tbl_customer ORDER BY sorting_code ASC")->result();
            $data = array(
                "customers" => $customers,
            );
            echo json_encode($data);
        }
    }

    /* -----------------------------------------------------------------------------------------------------
						*** CFO | SALES COMPARISON | GRAPH MODE
	-----------------------------------------------------------------------------------------------------*/
    function fetch_salesCompa_Customer_Graph()
    {
        /* CURRENT REVISION */
        $selectedDPRNO = $this->tm->get_dpr_no();
        /* AXIOS POST DATA */
        $dt = json_decode(file_get_contents("php://input"));
        /* CHECK IF SUBMITTED */
        if (isset($dt)) {
            if (!empty($dt->selectedQuarter)) {
                $dateFrom = []; // is an array that contains the starting date based on the selected quarter
                $dateTo = []; // is an array that contains the ending date based on the selected quarter
                $monthYearList = [];
                $customers = [];
                $selected_customer = [];

                if (count($dt->selectedQuarter) > 0) {
                    foreach ($dt->selectedQuarter as $quarters) :
                        if ($quarters == 1) {
                            $dateFrom[1] = date($dt->inputYear . "-04-01");
                            $dateTo[1] = date($dt->inputYear . "-06-31");
                        }
                        if ($quarters == 2) {
                            $dateFrom[2] = date($dt->inputYear . "-07-01");
                            $dateTo[2] = date($dt->inputYear . "-09-31");
                        }
                        if ($quarters == 3) {
                            $dateFrom[3] = date($dt->inputYear . "-10-01");
                            $dateTo[3] = date($dt->inputYear . "-12-31");
                        }
                        if ($quarters == 4) {
                            $dateFrom[4] = date($dt->inputYear . "-01-01");
                            $dateTo[4] = date($dt->inputYear . "-03-31");
                        }
                    endforeach;
                }

                // GET MONTH_YEARS FROM SELECTED QUARTER
                if (count($dateFrom) > 0) {
                    for ($x = 1; $x <= 4; $x++) {
                        if (isset($dateFrom[$x]) || isset($dateTo[$x])) {
                            $month_year = $this->db->query("SELECT DISTINCT month_year FROM tbl_new_plan WHERE month_year_numerical BETWEEN '$dateFrom[$x]' AND '$dateTo[$x]' ORDER BY month_year_numerical ASC ")->result();
                            foreach ($month_year as $month) {
                                array_push($monthYearList, $month->month_year);
                            }
                            array_push($monthYearList, "Q$x");
                        }
                    }
                }


                // GRAPH | GET SELECTED CUSTOMER
                if (count($dt->SelectedCustomers) > 0) {
                    foreach ($dt->SelectedCustomers as $selectedCustomer) {
                        $customer = $this->db->query("SELECT * FROM tbl_customer WHERE customers_id= '$selectedCustomer' ORDER BY sorting_code ASC ")->result();
                        foreach ($customer as $selected) {
                            array_push($selected_customer, $selected);
                        }
                    }
                }

                $total_sales_customer = [];
                $grandTotal_sales_customer = [];
                // GET TOTAL PER CUSTOMER | TBL ACTUAL DATA
                if (count($selected_customer) > 0) {
                    foreach ($selected_customer as $selectedCustomer) {
                        foreach ($monthYearList as $month_year) {
                            $customerSalesTotal = $this->db->query("SELECT customer,customers_desc,month_year,
                            SUM(actual_sales_qty) AS total_sales_qty,
                            SUM(actual_sales_qty*actual_price) AS total_sales_amount  
                            FROM tbl_actual_data 
                            INNER JOIN tbl_customer ON tbl_actual_data.customer=tbl_customer.customers_id
                            WHERE month_year='$month_year' 
                            AND customer='$selectedCustomer->customers_id' ")->row();

                            foreach ($customerSalesTotal as $totalSales) {
                                // Total of Sales per Customer and MonthYear
                                $total_sales_customer[$selectedCustomer->customers_desc][$month_year]['total_sales_qty'] = $customerSalesTotal->total_sales_qty;
                                $total_sales_customer[$selectedCustomer->customers_desc][$month_year]['total_sales_amount'] = $customerSalesTotal->total_sales_amount;
                            }
                        }
                    }
                }

                $revisions = [$dt->selectedRevision, $selectedDPRNO];

                // TBL NEW PLAN | SALES DATA | SELECTED REVISION VS CURRENT REVISION
                if (count($selected_customer) > 0 && count($total_sales_customer) > 0 && count($month_year) > 0) {
                    foreach ($selected_customer as $selectedCustomer) {
                        foreach ($monthYearList as $month_year) {
                            $totalSalesCustomer_salesQty = $total_sales_customer[$selectedCustomer->customers_desc][$month_year]['total_sales_qty'];
                            $totalSalesCustomer_salesAmount = $total_sales_customer[$selectedCustomer->customers_desc][$month_year]['total_sales_amount'];
                            if ($totalSalesCustomer_salesQty == null && $totalSalesCustomer_salesAmount == null) {
                                foreach ($revisions as $revision) {
                                    $customerSalesData = $this->db->query(
                                        "SELECT customer,customers_desc,month_year,
                                        SUM(sales_qty) AS total_sales_qty,
                                        SUM(sales_qty*price) AS total_sales_amount  
                                        FROM tbl_new_plan
                                        INNER JOIN tbl_customer ON tbl_new_plan.customer=tbl_customer.customers_id
                                        WHERE month_year='$month_year' 
                                        AND customer='$selectedCustomer->customers_id'
                                        AND revision='$revision';
                                    "
                                    )->row();
                                    $total_sales_customer[$selectedCustomer->customers_desc][$month_year][$revision]['total_sales_qty'] = $customerSalesData->total_sales_qty;
                                    $total_sales_customer[$selectedCustomer->customers_desc][$month_year][$revision]['total_sales_amount'] = $customerSalesData->total_sales_amount;
                                }
                            }
                        }
                    }
                }

                // TOTAL OF CUSTOMER PER QUARTER
                if (count($selected_customer) > 0 && count($total_sales_customer) > 0) {
                    foreach ($selected_customer as $selectedCustomer) {
                        if (count($dateFrom) > 0) {
                            for ($x = 1; $x <= 4; $x++) {
                                if (isset($dateFrom[$x]) || isset($dateTo[$x])) {
                                    $SalesActualData = $this->db->query(
                                        "SELECT customer,customers_desc,month_year,
                                SUM(actual_sales_qty) AS total_sales_qty,
                                SUM(actual_sales_qty*actual_price) AS total_sales_amount  
                                FROM tbl_actual_data 
                                INNER JOIN tbl_customer ON tbl_actual_data.customer=tbl_customer.customers_id
                                WHERE customer='$selectedCustomer->customers_id'
                                AND month_year_numerical BETWEEN '$dateFrom[$x]' AND '$dateTo[$x]';"
                                    )->result();
                                    foreach ($SalesActualData as $sales) {
                                        $total_sales_customer[$selectedCustomer->customers_desc]["Q" . $x]['total_sales_qty'] = $sales->total_sales_qty;
                                        $total_sales_customer[$selectedCustomer->customers_desc]["Q" . $x]['total_sales_amount'] = $sales->total_sales_amount;
                                    }

                                    //QUARTERLY TOTAL OF CUSTOMER PER QUARTER AND PER REVISION
                                    $Total_MontlhySalesQty_Selected_Revision = array();
                                    $Total_MontlhySalesQty_Current_Revision = array();
                                    $Total_MontlhySalesAmount_Selected_Revision = array();
                                    $Total_MontlhySalesAmount_Current_Revision = array();

                                    $QuarterTotal_ActualSalesQty =  $total_sales_customer[$selectedCustomer->customers_desc]["Q" . $x]['total_sales_qty'];
                                    $QuarterTotal_ActualSalesAmount =  $total_sales_customer[$selectedCustomer->customers_desc]["Q" . $x]['total_sales_amount'];

                                    $month_years[$x] = $this->db->query("SELECT DISTINCT month_year FROM tbl_new_plan WHERE month_year_numerical BETWEEN '$dateFrom[$x]' AND '$dateTo[$x]' ORDER BY month_year_numerical ASC ")->result();

                                    foreach ($month_years[$x] as $month_year) :
                                        foreach ($month_year as $month) :
                                            $totalSalesCustomer_salesQty = $total_sales_customer[$selectedCustomer->customers_desc][$month]['total_sales_qty'];
                                            $totalSalesCustomer_salesAmount = $total_sales_customer[$selectedCustomer->customers_desc][$month]['total_sales_amount'];
                                            if ($totalSalesCustomer_salesQty == null && $totalSalesCustomer_salesAmount == null) {

                                                $MontlhySalesQty_Selected_Revision = intval($total_sales_customer[$selectedCustomer->customers_desc][$month][$revisions[0]]['total_sales_qty']);
                                                $MontlhySalesQty_Current_Revision = intval($total_sales_customer[$selectedCustomer->customers_desc][$month][$revisions[1]]['total_sales_qty']);
                                                $MontlhySalesAmount_Selected_Revision = intval($total_sales_customer[$selectedCustomer->customers_desc][$month][$revisions[0]]['total_sales_amount']);
                                                $MontlhySalesAmount_Current_Revision = intval($total_sales_customer[$selectedCustomer->customers_desc][$month][$revisions[1]]['total_sales_amount']);
                                                array_push($Total_MontlhySalesQty_Selected_Revision, $MontlhySalesQty_Selected_Revision);
                                                array_push($Total_MontlhySalesQty_Current_Revision, $MontlhySalesQty_Current_Revision);
                                                array_push($Total_MontlhySalesAmount_Selected_Revision, $MontlhySalesAmount_Selected_Revision);
                                                array_push($Total_MontlhySalesAmount_Current_Revision, $MontlhySalesAmount_Current_Revision);

                                                $total_sales_customer[$selectedCustomer->customers_desc]["Q" . $x]['total_sales_qty'] = null;
                                                $total_sales_customer[$selectedCustomer->customers_desc]["Q" . $x]['total_sales_amount'] = null;
                                            }
                                        endforeach;
                                    endforeach;

                                    $Sum_Total_MontlhySalesQty_Selected_Revision = array_sum($Total_MontlhySalesQty_Selected_Revision);
                                    $Sum_Total_MontlhySalesQty_Current_Revision = array_sum($Total_MontlhySalesQty_Current_Revision);
                                    $Sum_Total_MontlhySalesAmount_Selected_Revision = array_sum($Total_MontlhySalesAmount_Selected_Revision);
                                    $Sum_Total_MontlhySalesAmount_Current_Revision = array_sum($Total_MontlhySalesAmount_Current_Revision);
                                    $total_sales_customer[$selectedCustomer->customers_desc]["Q" . $x][$revisions[0]]['total_sales_qty'] = $Sum_Total_MontlhySalesQty_Selected_Revision + $QuarterTotal_ActualSalesQty;
                                    $total_sales_customer[$selectedCustomer->customers_desc]["Q" . $x][$revisions[1]]['total_sales_qty'] = $Sum_Total_MontlhySalesQty_Current_Revision + $QuarterTotal_ActualSalesQty;
                                    $total_sales_customer[$selectedCustomer->customers_desc]["Q" . $x][$revisions[0]]['total_sales_amount'] = $Sum_Total_MontlhySalesAmount_Selected_Revision + $QuarterTotal_ActualSalesAmount;
                                    $total_sales_customer[$selectedCustomer->customers_desc]["Q" . $x][$revisions[1]]['total_sales_amount'] = $Sum_Total_MontlhySalesAmount_Current_Revision + $QuarterTotal_ActualSalesAmount;
                                }
                            }
                        }
                    }
                }

                // GET GRAND TOTAL PER SELECTED CUSTOMER
                if (count($total_sales_customer) > 0) {

                    foreach ($total_sales_customer as $customer) {
                        foreach ($customer as $months => $sales) {

                            if (isset($grandTotal_sales_customer[$months]['Sales_Quantity'])) {
                                $grandTotal_sales_customer[$months]['Sales_Quantity'] += $sales['total_sales_qty'];
                            } else {
                                $grandTotal_sales_customer[$months]['Sales_Quantity'] = $sales['total_sales_qty'];
                            }

                            if (isset($grandTotal_sales_customer[$months]['Sales_Amount'])) {
                                $grandTotal_sales_customer[$months]['Sales_Amount'] += $sales['total_sales_amount'];
                            } else {
                                $grandTotal_sales_customer[$months]['Sales_Amount'] = $sales['total_sales_amount'];
                            }

                            // To include the revision data in the computation of the Total;
                            foreach ($revisions as $rev) {
                                if ($sales['total_sales_qty'] == null) {

                                    if (isset($grandTotal_sales_customer[$months][$rev]['Sales_Quantity'])) {
                                        $grandTotal_sales_customer[$months][$rev]['Sales_Quantity'] += $sales[$rev]['total_sales_qty'];
                                    } else {
                                        $grandTotal_sales_customer[$months][$rev]['Sales_Quantity'] = $sales[$rev]['total_sales_qty'];
                                    }

                                    if (isset($grandTotal_sales_customer[$months][$rev]['Sales_Amount'])) {
                                        $grandTotal_sales_customer[$months][$rev]['Sales_Amount'] += $sales[$rev]['total_sales_amount'];
                                    } else {
                                        $grandTotal_sales_customer[$months][$rev]['Sales_Amount'] = $sales[$rev]['total_sales_amount'];
                                    }
                                }
                            }
                        }
                    }
                }

                $data = array(
                    "customers" => $customers,
                    'monthYear' => $monthYearList,
                    'selected_customer' => $selected_customer,
                    'total_sales_customer' => $total_sales_customer,
                    'grandTotal_sales_customer' => $grandTotal_sales_customer,
                );
                echo json_encode($data);
            }
        }
    }
    /* -----------------------------------------------------------------------------------------------------
						CFO | SALES COMPARISON | GRAPH MODE ***
	-----------------------------------------------------------------------------------------------------*/



    /* -----------------------------------------------------------------------------------------------------
                        CFO | PROD COMPARISON | GRAPH MODE ***
    -----------------------------------------------------------------------------------------------------*/




    public function cfo_prod_comparison()
    {
        $pd = $_POST;
        $revFrom = $revTo = "";
        if (count($pd) > 0) {
            $revFrom = $pd['txt_revision_from'];
            $revTo = $pd['txt_revision_to'];
        }
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data['revision'] = $this->tm->get_revision();

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {

            $this->global['pageTitle'] = 'DWH : CFO';
            $dateFrom = date("Y-01-01");
            $dateTo = date("Y-m-31", strtotime("+7 month"));

            $data['revFrom'] = $revFrom;
            $data['revTo'] = $revTo;

            $data['current_rev'] = $this->tm->get_dates_from_current_revision($selectedDPRNO, $dateFrom, $dateTo);
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer ORDER BY customers_id ASC")->result();
            $this->loadViews("cfo/cfo_prod_comparison_excel", $this->global, $data, NULL);
        }
    }

    public function cfo_prod_comparison_customer_graph()
    {
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {

            $data["page"] = $page;
            $data["selectedDPRNO"] = $selectedDPRNO;
            $data['revisions'] = $this->tm->get_revision();
            $this->global['pageTitle'] = 'DWH : CFO - Production Comparison Graph';
            $this->loadViews("cfo/cfo_prod_comparison_customer_graph", $this->global, $data, NULL);
        }
    }



    function fetch_prodCompa_Customer_Graph()
    {
        /* CURRENT REVISION */
        $selectedDPRNO = $this->tm->get_dpr_no();
        /* AXIOS POST DATA */
        $dt = json_decode(file_get_contents("php://input"));
        /* CHECK IF SUBMITTED */
        if (isset($dt)) {
            if (!empty($dt->selectedQuarter)) {
                $dateFrom = []; // is an array that contains the starting date based on the selected quarter
                $dateTo = []; // is an array that contains the ending date based on the selected quarter
                $monthYearList = [];
                $customers = [];
                $selected_customer = [];

                if (count($dt->selectedQuarter) > 0) {
                    foreach ($dt->selectedQuarter as $quarters) :
                        if ($quarters == 1) {
                            $dateFrom[1] = date($dt->inputYear . "-04-01");
                            $dateTo[1] = date($dt->inputYear . "-06-31");
                        }
                        if ($quarters == 2) {
                            $dateFrom[2] = date($dt->inputYear . "-07-01");
                            $dateTo[2] = date($dt->inputYear . "-09-31");
                        }
                        if ($quarters == 3) {
                            $dateFrom[3] = date($dt->inputYear . "-10-01");
                            $dateTo[3] = date($dt->inputYear . "-12-31");
                        }
                        if ($quarters == 4) {
                            $dateFrom[4] = date($dt->inputYear . "-01-01");
                            $dateTo[4] = date($dt->inputYear . "-03-31");
                        }
                    endforeach;
                }

                // GET MONTH_YEARS FROM SELECTED QUARTER
                if (count($dateFrom) > 0) {
                    for ($x = 1; $x <= 4; $x++) {
                        if (isset($dateFrom[$x]) || isset($dateTo[$x])) {
                            $month_year = $this->db->query("SELECT DISTINCT month_year FROM tbl_new_plan WHERE month_year_numerical BETWEEN '$dateFrom[$x]' AND '$dateTo[$x]' ORDER BY month_year_numerical ASC ")->result();
                            foreach ($month_year as $month) {
                                array_push($monthYearList, $month->month_year);
                            }
                            array_push($monthYearList, "Q$x");
                        }
                    }
                }



                // GRAPH | GET SELECTED CUSTOMER
                if (count($dt->SelectedCustomers) > 0) {
                    foreach ($dt->SelectedCustomers as $selectedCustomer) {
                        $customer = $this->db->query("SELECT * FROM tbl_customer WHERE customers_id= '$selectedCustomer' ORDER BY sorting_code ASC ")->result();
                        foreach ($customer as $selected) {
                            array_push($selected_customer, $selected);
                        }
                    }
                }

                $total_prod_customer = [];
                $grandTotal_prod_customer = [];
                // GET TOTAL PER CUSTOMER | TBL ACTUAL DATA
                if (count($selected_customer) > 0) {
                    foreach ($selected_customer as $selectedCustomer) {
                        foreach ($monthYearList as $month_year) {
                            $customerprodTotal = $this->db->query("SELECT customer,customers_desc,month_year,
                            SUM(actual_prod_qty) AS total_prod_qty,
                            SUM(actual_prod_qty*actual_price) AS total_prod_amount  
                            FROM tbl_actual_data 
                            INNER JOIN tbl_customer ON tbl_actual_data.customer=tbl_customer.customers_id
                            WHERE month_year='$month_year' 
                            AND customer='$selectedCustomer->customers_id' ")->row();

                            foreach ($customerprodTotal as $totalprod) {
                                // Total of prod per Customer and MonthYear
                                $total_prod_customer[$selectedCustomer->customers_desc][$month_year]['total_prod_qty'] = $customerprodTotal->total_prod_qty;
                                $total_prod_customer[$selectedCustomer->customers_desc][$month_year]['total_prod_amount'] = $customerprodTotal->total_prod_amount;
                            }
                        }
                    }
                }

                $revisions = [$dt->selectedRevision, $selectedDPRNO];

                // TBL NEW PLAN | prod DATA | SELECTED REVISION VS CURRENT REVISION
                if (count($selected_customer) > 0 && count($total_prod_customer) > 0 && count($month_year) > 0) {
                    foreach ($selected_customer as $selectedCustomer) {
                        foreach ($monthYearList as $month_year) {
                            $totalprodCustomer_prodQty = $total_prod_customer[$selectedCustomer->customers_desc][$month_year]['total_prod_qty'];
                            $totalprodCustomer_prodAmount = $total_prod_customer[$selectedCustomer->customers_desc][$month_year]['total_prod_amount'];
                            if ($totalprodCustomer_prodQty == null && $totalprodCustomer_prodAmount == null) {
                                foreach ($revisions as $revision) {
                                    $customerprodData = $this->db->query(
                                        "SELECT customer,customers_desc,month_year,
                                        SUM(prod_qty) AS total_prod_qty,
                                        SUM(prod_qty*price) AS total_prod_amount  
                                        FROM tbl_new_plan
                                        INNER JOIN tbl_customer ON tbl_new_plan.customer=tbl_customer.customers_id
                                        WHERE month_year='$month_year' 
                                        AND customer='$selectedCustomer->customers_id'
                                        AND revision='$revision';
                                    "
                                    )->row();
                                    $total_prod_customer[$selectedCustomer->customers_desc][$month_year][$revision]['total_prod_qty'] = $customerprodData->total_prod_qty;
                                    $total_prod_customer[$selectedCustomer->customers_desc][$month_year][$revision]['total_prod_amount'] = $customerprodData->total_prod_amount;
                                }
                            }
                        }
                    }
                }

                // TOTAL OF CUSTOMER PER QUARTER
                if (count($selected_customer) > 0 && count($total_prod_customer) > 0) {
                    foreach ($selected_customer as $selectedCustomer) {
                        if (count($dateFrom) > 0) {
                            for ($x = 1; $x <= 4; $x++) {
                                if (isset($dateFrom[$x]) || isset($dateTo[$x])) {
                                    $prodActualData = $this->db->query(
                                        "SELECT customer,customers_desc,month_year,
                                SUM(actual_prod_qty) AS total_prod_qty,
                                SUM(actual_prod_qty*actual_price) AS total_prod_amount  
                                FROM tbl_actual_data 
                                INNER JOIN tbl_customer ON tbl_actual_data.customer=tbl_customer.customers_id
                                WHERE customer='$selectedCustomer->customers_id'
                                AND month_year_numerical BETWEEN '$dateFrom[$x]' AND '$dateTo[$x]';"
                                    )->result();
                                    foreach ($prodActualData as $prod) {
                                        $total_prod_customer[$selectedCustomer->customers_desc]["Q" . $x]['total_prod_qty'] = $prod->total_prod_qty;
                                        $total_prod_customer[$selectedCustomer->customers_desc]["Q" . $x]['total_prod_amount'] = $prod->total_prod_amount;
                                    }

                                    //QUARTERLY TOTAL OF CUSTOMER PER QUARTER AND PER REVISION
                                    $Total_MontlhyprodQty_Selected_Revision = array();
                                    $Total_MontlhyprodQty_Current_Revision = array();
                                    $Total_MontlhyprodAmount_Selected_Revision = array();
                                    $Total_MontlhyprodAmount_Current_Revision = array();

                                    $QuarterTotal_ActualprodQty =  $total_prod_customer[$selectedCustomer->customers_desc]["Q" . $x]['total_prod_qty'];
                                    $QuarterTotal_ActualprodAmount =  $total_prod_customer[$selectedCustomer->customers_desc]["Q" . $x]['total_prod_amount'];

                                    $month_years[$x] = $this->db->query("SELECT DISTINCT month_year FROM tbl_new_plan WHERE month_year_numerical BETWEEN '$dateFrom[$x]' AND '$dateTo[$x]' ORDER BY month_year_numerical ASC ")->result();

                                    foreach ($month_years[$x] as $month_year) :
                                        foreach ($month_year as $month) :
                                            $totalprodCustomer_prodQty = $total_prod_customer[$selectedCustomer->customers_desc][$month]['total_prod_qty'];
                                            $totalprodCustomer_prodAmount = $total_prod_customer[$selectedCustomer->customers_desc][$month]['total_prod_amount'];
                                            if ($totalprodCustomer_prodQty == null && $totalprodCustomer_prodAmount == null) {

                                                $MontlhyprodQty_Selected_Revision = intval($total_prod_customer[$selectedCustomer->customers_desc][$month][$revisions[0]]['total_prod_qty']);
                                                $MontlhyprodQty_Current_Revision = intval($total_prod_customer[$selectedCustomer->customers_desc][$month][$revisions[1]]['total_prod_qty']);
                                                $MontlhyprodAmount_Selected_Revision = intval($total_prod_customer[$selectedCustomer->customers_desc][$month][$revisions[0]]['total_prod_amount']);
                                                $MontlhyprodAmount_Current_Revision = intval($total_prod_customer[$selectedCustomer->customers_desc][$month][$revisions[1]]['total_prod_amount']);
                                                array_push($Total_MontlhyprodQty_Selected_Revision, $MontlhyprodQty_Selected_Revision);
                                                array_push($Total_MontlhyprodQty_Current_Revision, $MontlhyprodQty_Current_Revision);
                                                array_push($Total_MontlhyprodAmount_Selected_Revision, $MontlhyprodAmount_Selected_Revision);
                                                array_push($Total_MontlhyprodAmount_Current_Revision, $MontlhyprodAmount_Current_Revision);

                                                $total_prod_customer[$selectedCustomer->customers_desc]["Q" . $x]['total_prod_qty'] = null;
                                                $total_prod_customer[$selectedCustomer->customers_desc]["Q" . $x]['total_prod_amount'] = null;
                                            }
                                        endforeach;
                                    endforeach;

                                    $Sum_Total_MontlhyprodQty_Selected_Revision = array_sum($Total_MontlhyprodQty_Selected_Revision);
                                    $Sum_Total_MontlhyprodQty_Current_Revision = array_sum($Total_MontlhyprodQty_Current_Revision);
                                    $Sum_Total_MontlhyprodAmount_Selected_Revision = array_sum($Total_MontlhyprodAmount_Selected_Revision);
                                    $Sum_Total_MontlhyprodAmount_Current_Revision = array_sum($Total_MontlhyprodAmount_Current_Revision);
                                    $total_prod_customer[$selectedCustomer->customers_desc]["Q" . $x][$revisions[0]]['total_prod_qty'] = $Sum_Total_MontlhyprodQty_Selected_Revision + $QuarterTotal_ActualprodQty;
                                    $total_prod_customer[$selectedCustomer->customers_desc]["Q" . $x][$revisions[1]]['total_prod_qty'] = $Sum_Total_MontlhyprodQty_Current_Revision + $QuarterTotal_ActualprodQty;
                                    $total_prod_customer[$selectedCustomer->customers_desc]["Q" . $x][$revisions[0]]['total_prod_amount'] = $Sum_Total_MontlhyprodAmount_Selected_Revision + $QuarterTotal_ActualprodAmount;
                                    $total_prod_customer[$selectedCustomer->customers_desc]["Q" . $x][$revisions[1]]['total_prod_amount'] = $Sum_Total_MontlhyprodAmount_Current_Revision + $QuarterTotal_ActualprodAmount;
                                }
                            }
                        }
                    }
                }

                // GET GRAND TOTAL PER SELECTED CUSTOMER
                if (count($total_prod_customer) > 0) {
                    foreach ($total_prod_customer as $customer) {
                        foreach ($customer as $months => $prod) {
                            /* print_r($months);*/
                            if (isset($grandTotal_prod_customer[$months]['prod_Quantity'])) {
                                $grandTotal_prod_customer[$months]['prod_Quantity'] += $prod['total_prod_qty'];
                            } else {
                                $grandTotal_prod_customer[$months]['prod_Quantity'] = $prod['total_prod_qty'];
                            }

                            if (isset($grandTotal_prod_customer[$months]['prod_Amount'])) {
                                $grandTotal_prod_customer[$months]['prod_Amount'] += $prod['total_prod_amount'];
                            } else {
                                $grandTotal_prod_customer[$months]['prod_Amount'] = $prod['total_prod_amount'];
                            }

                            // To include the revision data in the computation of the Total;
                            foreach ($revisions as $rev) {
                                if ($prod['total_prod_qty'] == null) {

                                    if (isset($grandTotal_prod_customer[$months][$rev]['prod_Quantity'])) {
                                        $grandTotal_prod_customer[$months][$rev]['prod_Quantity'] += $prod[$rev]['total_prod_qty'];
                                    } else {
                                        $grandTotal_prod_customer[$months][$rev]['prod_Quantity'] = $prod[$rev]['total_prod_qty'];
                                    }

                                    if (isset($grandTotal_prod_customer[$months][$rev]['prod_Amount'])) {
                                        $grandTotal_prod_customer[$months][$rev]['prod_Amount'] += $prod[$rev]['total_prod_amount'];
                                    } else {
                                        $grandTotal_prod_customer[$months][$rev]['prod_Amount'] = $prod[$rev]['total_prod_amount'];
                                    }
                                }
                            }
                        }
                    }
                }

                $data = array(
                    "customers" => $customers,
                    'monthYear' => $monthYearList,
                    'selected_customer' => $selected_customer,
                    'total_prod_customer' => $total_prod_customer,
                    'grandTotal_prod_customer' => $grandTotal_prod_customer,
                );
                echo json_encode($data);
            }
        }
    }
    /* -----------------------------------------------------------------------------------------------------
                       *** CFO | PROD COMPARISON | GRAPH MODE 
    -----------------------------------------------------------------------------------------------------*/








    /* -----------------------------------------------------------------------------------------------------
                      CFO | PROD MODEL COMPARISON | GRAPH MODE   ***
    -----------------------------------------------------------------------------------------------------*/
    function get_model_customers()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $customers = $this->db->query("SELECT * FROM tbl_customer ORDER BY sorting_code ASC")->result();
            $data = array(
                "customers" => $customers,
            );
            echo json_encode($data);
        }
    }

    public function cfo_prod_comparison_models_graph()
    {
        $selectedDPRNO = $this->tm->get_dpr_no();
        $page = "ps_plan_form";

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {

            $data["page"] = $page;
            $data["selectedDPRNO"] = $selectedDPRNO;
            $data['revisions'] = $this->tm->get_revision();
            $data['customers'] = $this->db->query("SELECT * FROM tbl_customer ORDER BY sorting_code ASC")->result();
            $this->global['pageTitle'] = 'DWH : CFO - Production Comparison Graph';
            $this->loadViews("cfo/cfo_prod_comparison_model_graph", $this->global, $data, NULL);
            /*$this->loadViews("reports/404.php", $this->global, $data, NULL);*/
        }
    }


    function fetch_prod_model_Compa_Customer_Graph()
    {


        /* CURRENT REVISION */

        $selectedDPRNO = $this->tm->get_dpr_no();
        /* AXIOS DATA */

        $dt = json_decode(file_get_contents("php://input"));
        /* CHECK IF SUBMITTED */
        if (isset($dt)) {
            if (!empty($dt->selectedQuarter)) {
                $dateFrom = []; // is an array that contains the starting date based on the selected quarter
                $dateTo = []; // is an array that contains the ending date based on the selected quarter
                $monthYearList = [];
                $customers = [];
                $selected_customer = [];
                $selected_model = [];


                if (count($dt->selectedQuarter) > 0) {
                    foreach ($dt->selectedQuarter as $quarters) :
                        if ($quarters == 1) {
                            $dateFrom[1] = date($dt->inputYear . "-04-01");
                            $dateTo[1] = date($dt->inputYear . "-06-31");
                        }
                        if ($quarters == 2) {
                            $dateFrom[2] = date($dt->inputYear . "-07-01");
                            $dateTo[2] = date($dt->inputYear . "-09-31");
                        }
                        if ($quarters == 3) {
                            $dateFrom[3] = date($dt->inputYear . "-10-01");
                            $dateTo[3] = date($dt->inputYear . "-12-31");
                        }
                        if ($quarters == 4) {
                            $dateFrom[4] = date($dt->inputYear . "-01-01");
                            $dateTo[4] = date($dt->inputYear . "-03-31");
                        }
                    endforeach;
                }
                /* GET MONTH_YEARS FROM SELECTED QUARTER */
                if (count($dateFrom) > 0) {
                    for ($x = 1; $x <= 4; $x++) {
                        if (isset($dateFrom[$x]) || isset($dateTo[$x])) {
                            $month_year = $this->db->query("SELECT DISTINCT month_year FROM tbl_new_plan WHERE month_year_numerical BETWEEN '$dateFrom[$x]' AND '$dateTo[$x]' ORDER BY month_year_numerical ASC ")->result();
                            foreach ($month_year as $month) {
                                array_push($monthYearList, $month->month_year);
                            }
                            array_push($monthYearList, "Q$x");
                        }
                    }
                }



                // GRAPH | GET SELECTED CUSTOMER
                if (count($dt->SelectedCustomers) > 0) {
                    foreach ($dt->SelectedCustomers as $selectedCustomer) {
                        $customer = $this->db->query("SELECT *
                        FROM cias.tbl_customer AS A
                        INNER JOIN cias.tbl_models AS B
                        ON A.customers_id = B.customer_models_id where A.customers_id = '$selectedCustomer' AND B.customer_models_id = '$selectedCustomer' ORDER BY A.sorting_code ASC;")->result();
                        foreach ($customer as $selected) {
                            array_push($selected_customer, $selected);
                        }
                    }
                }

                $total_prod_customer = [];
                $grandTotal_prod_customer = [];
                // GET TOTAL PER CUSTOMER | TBL ACTUAL DATA
                if (count($selected_customer) > 0) {
                    foreach ($selected_customer as $selectedCustomer) {
                        // print_r($selectedCustomer);
                        /*   $models = [];
                        if (count($dt->SelectedCustomers) > 0) {
                                $models = $this->db->query("SELECT * FROM tbl_models WHERE customer_models_id= ".$selectedCustomer->customers_id." ORDER BY sorting_code ASC ")->result();
                        }
        
                        foreach ($models as $mod) {*/
                        /*foreach ($monthYearList as $month_year) {
                                $customerprodTotal = $this->db->query(
                                "SELECT DISTINCT customer,customers_desc,month_year,
                                SUM(actual_prod_qty) AS total_prod_qty,
                                SUM(actual_prod_qty*actual_price) AS total_prod_amount  
                                FROM tbl_actual_data 
                                INNER JOIN tbl_customer ON tbl_actual_data.customer=tbl_customer.customers_id
                                WHERE month_year='$month_year' 
                                AND customer='$selectedCustomer->customers_id'
                                AND model='$selectedCustomer->models_id' ")->row();*/


                        foreach ($monthYearList as $month_year) {
                            $customerprodTotal = $this->db->query(
                                "SELECT DISTINCT customers_id, customers_code, customers_desc, models_code, models_desc , month_year,
                                SUM(actual_prod_qty) AS total_prod_qty,
                                SUM(actual_prod_qty*actual_price) AS total_prod_amount  
                                FROM cias.tbl_actual_data 
                                INNER JOIN cias.tbl_customer ON cias.tbl_actual_data.customer=cias.tbl_customer.customers_id 
                                INNER JOIN cias.tbl_models ON cias.tbl_actual_data.model=tbl_models.models_id
                                WHERE month_year='$month_year' 
                                AND customer='$selectedCustomer->customers_id'
                                AND models_code='$selectedCustomer->models_code' "
                            )->row();


                            foreach ($customerprodTotal as $totalprod) {
                                // Total of prod per Customer and MonthYear
                                $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month_year]['total_prod_qty'] = $customerprodTotal->total_prod_qty;
                                $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month_year]['total_prod_amount'] = $customerprodTotal->total_prod_amount;
                            }
                        }
                    }
                }



                $revisions = [$dt->selectedRevision, $selectedDPRNO];

                /*  TBL NEW PLAN | prod DATA | SELECTED REVISION VS CURRENT REVISION*/
                if (count($selected_customer) > 0 && count($total_prod_customer) > 0 && count($month_year) > 0) {
                    foreach ($selected_customer as $selectedCustomer) {
                        /* $model = [];
                        if (count($dt->SelectedCustomers) > 0) {
                                $model = $this->db->query("SELECT * FROM tbl_models WHERE customer_models_id= ".$selectedCustomer->customers_id." ORDER BY sorting_code ASC ")->result();
                        }
                        foreach ($model as $mod) {*/
                        foreach ($monthYearList as $month_year) {
                            $totalprodCustomer_prodQty = $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month_year]['total_prod_qty'];
                            $totalprodCustomer_prodAmount = $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month_year]['total_prod_amount'];
                            if ($totalprodCustomer_prodQty == null && $totalprodCustomer_prodAmount == null) {
                                foreach ($revisions as $revision) {
                                    $customerprodData = $this->db->query(
                                        /* "SELECT DISTINCT customer,customers_desc,month_year,
                                        SUM(prod_qty) AS total_prod_qty,
                                        SUM(prod_qty*price) AS total_prod_amount  
                                        FROM tbl_new_plan
                                        INNER JOIN tbl_customer ON tbl_new_plan.customer=tbl_customer.customers_id
                                        WHERE month_year='$month_year' 
                                        AND customer='$selectedCustomer->customers_id'
                                        AND model='$selectedCustomer->models_id'
                                        AND revision='$revision';"*/




                                        "SELECT DISTINCT customers_id, customers_code, customers_desc, models_code, models_desc , month_year,
                                        SUM(prod_qty) AS total_prod_qty,
                                        SUM(prod_qty*price) AS total_prod_amount  
                                        FROM tbl_new_plan
                                        INNER JOIN cias.tbl_customer ON cias.tbl_new_plan.customer=cias.tbl_customer.customers_id 
                                        INNER JOIN cias.tbl_models ON cias.tbl_new_plan.model=tbl_models.models_id
                                        WHERE month_year='$month_year' 
                                        AND customer='$selectedCustomer->customers_id'
                                        AND models_code='$selectedCustomer->models_code'
                                        AND revision='$revision';"


                                    )->row();
                                    $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month_year][$revision]['total_prod_qty'] = $customerprodData->total_prod_qty;
                                    $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month_year][$revision]['total_prod_amount'] = $customerprodData->total_prod_amount;
                                }
                            }
                        }
                    }
                }

                // TOTAL OF CUSTOMER PER QUARTER
                if (count($selected_customer) > 0 && count($total_prod_customer) > 0) {
                    foreach ($selected_customer as $selectedCustomer) {
                        if (count($dateFrom) > 0) {
                            for ($x = 1; $x <= 4; $x++) {
                                if (isset($dateFrom[$x]) || isset($dateTo[$x])) {
                                    $prodActualData = $this->db->query(
                                        "SELECT DISTINCT customers_id, customers_code, customers_desc, models_code, models_desc , month_year,
                                SUM(actual_prod_qty) AS total_prod_qty,
                                SUM(actual_prod_qty*actual_price) AS total_prod_amount  
                                FROM cias.tbl_actual_data 
                                INNER JOIN cias.tbl_customer ON cias.tbl_actual_data.customer=cias.tbl_customer.customers_id 
                                INNER JOIN cias.tbl_models ON cias.tbl_actual_data.model=tbl_models.models_id
                                AND customer='$selectedCustomer->customers_id'
                                AND models_code='$selectedCustomer->models_code'
                                AND month_year_numerical BETWEEN '$dateFrom[$x]' AND '$dateTo[$x]';"
                                    )->result();
                                    foreach ($prodActualData as $prod) {
                                        $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x]['total_prod_qty'] = $prod->total_prod_qty;
                                        $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x]['total_prod_amount'] = $prod->total_prod_amount;
                                    }

                                    //QUARTERLY TOTAL OF CUSTOMER PER QUARTER AND PER REVISION
                                    $Total_MontlhyprodQty_Selected_Revision = array();
                                    $Total_MontlhyprodQty_Current_Revision = array();
                                    $Total_MontlhyprodAmount_Selected_Revision = array();
                                    $Total_MontlhyprodAmount_Current_Revision = array();

                                    $QuarterTotal_ActualprodQty =  $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x]['total_prod_qty'];
                                    $QuarterTotal_ActualprodAmount =  $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x]['total_prod_amount'];

                                    $month_years[$x] = $this->db->query("SELECT DISTINCT month_year FROM tbl_new_plan WHERE month_year_numerical BETWEEN '$dateFrom[$x]' AND '$dateTo[$x]' ORDER BY month_year_numerical ASC ")->result();

                                    foreach ($month_years[$x] as $month_year) :
                                        foreach ($month_year as $month) :
                                            $totalprodCustomer_prodQty = $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month]['total_prod_qty'];
                                            $totalprodCustomer_prodAmount = $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month]['total_prod_amount'];
                                            if ($totalprodCustomer_prodQty == null && $totalprodCustomer_prodAmount == null) {

                                                $MontlhyprodQty_Selected_Revision = intval($total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month][$revisions[0]]['total_prod_qty']);
                                                $MontlhyprodQty_Current_Revision = intval($total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month][$revisions[1]]['total_prod_qty']);
                                                $MontlhyprodAmount_Selected_Revision = intval($total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month][$revisions[0]]['total_prod_amount']);
                                                $MontlhyprodAmount_Current_Revision = intval($total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month][$revisions[1]]['total_prod_amount']);
                                                array_push($Total_MontlhyprodQty_Selected_Revision, $MontlhyprodQty_Selected_Revision);
                                                array_push($Total_MontlhyprodQty_Current_Revision, $MontlhyprodQty_Current_Revision);
                                                array_push($Total_MontlhyprodAmount_Selected_Revision, $MontlhyprodAmount_Selected_Revision);
                                                array_push($Total_MontlhyprodAmount_Current_Revision, $MontlhyprodAmount_Current_Revision);

                                                $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x]['total_prod_qty'] = null;
                                                $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x]['total_prod_amount'] = null;
                                            }
                                        endforeach;
                                    endforeach;

                                    $Sum_Total_MontlhyprodQty_Selected_Revision = array_sum($Total_MontlhyprodQty_Selected_Revision);
                                    $Sum_Total_MontlhyprodQty_Current_Revision = array_sum($Total_MontlhyprodQty_Current_Revision);
                                    $Sum_Total_MontlhyprodAmount_Selected_Revision = array_sum($Total_MontlhyprodAmount_Selected_Revision);
                                    $Sum_Total_MontlhyprodAmount_Current_Revision = array_sum($Total_MontlhyprodAmount_Current_Revision);
                                    $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x][$revisions[0]]['total_prod_qty'] = $Sum_Total_MontlhyprodQty_Selected_Revision + $QuarterTotal_ActualprodQty;
                                    $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x][$revisions[1]]['total_prod_qty'] = $Sum_Total_MontlhyprodQty_Current_Revision + $QuarterTotal_ActualprodQty;
                                    $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x][$revisions[0]]['total_prod_amount'] = $Sum_Total_MontlhyprodAmount_Selected_Revision + $QuarterTotal_ActualprodAmount;
                                    $total_prod_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x][$revisions[1]]['total_prod_amount'] = $Sum_Total_MontlhyprodAmount_Current_Revision + $QuarterTotal_ActualprodAmount;
                                }
                            }
                        }
                    }
                }

                // GET GRAND TOTAL PER SELECTED CUSTOMER
                if (count($total_prod_customer) > 0) {

                    foreach ($total_prod_customer as $customer) {
                        foreach ($customer as $mon) {
                            foreach ($mon as $month_year => $prod) {




                                if (isset($grandTotal_prod_customer[$month_year]['prod_Quantity'])) {
                                    $grandTotal_prod_customer[$month_year]['prod_Quantity'] += $prod['total_prod_qty'];
                                } else {
                                    $grandTotal_prod_customer[$month_year]['prod_Quantity'] = $prod['total_prod_qty'];
                                }

                                if (isset($grandTotal_prod_customer[$month_year]['prod_Amount'])) {
                                    $grandTotal_prod_customer[$month_year]['prod_Amount'] += $prod['total_prod_amount'];
                                } else {
                                    $grandTotal_prod_customer[$month_year]['prod_Amount'] = $prod['total_prod_amount'];
                                }

                                // To include the revision data in the computation of the Total;
                                foreach ($revisions as $rev) {
                                    if ($prod['total_prod_qty'] == null) {

                                        if (isset($grandTotal_prod_customer[$month_year][$rev]['prod_Quantity'])) {
                                            $grandTotal_prod_customer[$month_year][$rev]['prod_Quantity'] += $prod[$rev]['total_prod_qty'];
                                        } else {
                                            $grandTotal_prod_customer[$month_year][$rev]['prod_Quantity'] = $prod[$rev]['total_prod_qty'];
                                        }

                                        if (isset($grandTotal_prod_customer[$month_year][$rev]['prod_Amount'])) {
                                            $grandTotal_prod_customer[$month_year][$rev]['prod_Amount'] += $prod[$rev]['total_prod_amount'];
                                        } else {
                                            $grandTotal_prod_customer[$month_year][$rev]['prod_Amount'] = $prod[$rev]['total_prod_amount'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }



                $data = array(
                    "customers" => $customers,
                    'monthYear' => $monthYearList,
                    'selected_customer' => $selected_customer,
                    'total_prod_customer' => $total_prod_customer,
                    'grandTotal_prod_customer' => $grandTotal_prod_customer,
                );
                echo json_encode($data);
            }
        }
    }











    /* -----------------------------------------------------------------------------------------------------
                      CFO | SALES MODEL COMPARISON | GRAPH MODE   ***
    -----------------------------------------------------------------------------------------------------*/

    function get_sales_model_customers()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $customers = $this->db->query("SELECT * FROM tbl_customer ORDER BY sorting_code ASC")->result();
            $data = array(
                "customers" => $customers,
            );
            echo json_encode($data);
        }
    }
    function fetch_sales_model_Compa_Customer_Graph()
    {


        /* CURRENT REVISION */

        $selectedDPRNO = $this->tm->get_dpr_no();
        /* AXIOS DATA */

        $dt = json_decode(file_get_contents("php://input"));
        /* CHECK IF SUBMITTED */
        if (isset($dt)) {
            if (!empty($dt->selectedQuarter)) {
                $dateFrom = []; // is an array that contains the starting date based on the selected quarter
                $dateTo = []; // is an array that contains the ending date based on the selected quarter
                $monthYearList = [];
                $customers = [];
                $selected_customer = [];
                $selected_model = [];


                if (count($dt->selectedQuarter) > 0) {
                    foreach ($dt->selectedQuarter as $quarters) :
                        if ($quarters == 1) {
                            $dateFrom[1] = date($dt->inputYear . "-04-01");
                            $dateTo[1] = date($dt->inputYear . "-06-31");
                        }
                        if ($quarters == 2) {
                            $dateFrom[2] = date($dt->inputYear . "-07-01");
                            $dateTo[2] = date($dt->inputYear . "-09-31");
                        }
                        if ($quarters == 3) {
                            $dateFrom[3] = date($dt->inputYear . "-10-01");
                            $dateTo[3] = date($dt->inputYear . "-12-31");
                        }
                        if ($quarters == 4) {
                            $dateFrom[4] = date($dt->inputYear . "-01-01");
                            $dateTo[4] = date($dt->inputYear . "-03-31");
                        }
                    endforeach;
                }
                /* GET MONTH_YEARS FROM SELECTED QUARTER */
                if (count($dateFrom) > 0) {
                    for ($x = 1; $x <= 4; $x++) {
                        if (isset($dateFrom[$x]) || isset($dateTo[$x])) {
                            $month_year = $this->db->query("SELECT DISTINCT month_year FROM tbl_new_plan WHERE month_year_numerical BETWEEN '$dateFrom[$x]' AND '$dateTo[$x]' ORDER BY month_year_numerical ASC ")->result();
                            foreach ($month_year as $month) {
                                array_push($monthYearList, $month->month_year);
                            }
                            array_push($monthYearList, "Q$x");
                        }
                    }
                }



                // GRAPH | GET SELECTED CUSTOMER
                if (count($dt->SelectedCustomers) > 0) {
                    foreach ($dt->SelectedCustomers as $selectedCustomer) {
                        $customer = $this->db->query("SELECT *
                        FROM cias.tbl_customer AS A
                        INNER JOIN cias.tbl_models AS B
                        ON A.customers_id = B.customer_models_id where A.customers_id = '$selectedCustomer' AND B.customer_models_id = '$selectedCustomer' ORDER BY A.sorting_code ASC;")->result();
                        foreach ($customer as $selected) {
                            array_push($selected_customer, $selected);
                        }
                    }
                }

                $total_sales_customer = [];
                $grandTotal_sales_customer = [];
                // GET TOTAL PER CUSTOMER | TBL ACTUAL DATA
                if (count($selected_customer) > 0) {
                    foreach ($selected_customer as $selectedCustomer) {
                        // print_r($selectedCustomer);
                        /*   $models = [];
                        if (count($dt->SelectedCustomers) > 0) {
                                $models = $this->db->query("SELECT * FROM tbl_models WHERE customer_models_id= ".$selectedCustomer->customers_id." ORDER BY sorting_code ASC ")->result();
                        }
        
                        foreach ($models as $mod) {*/
                        /*foreach ($monthYearList as $month_year) {
                                $customersalesTotal = $this->db->query(
                                "SELECT DISTINCT customer,customers_desc,month_year,
                                SUM(actual_sales_qty) AS total_sales_qty,
                                SUM(actual_sales_qty*actual_price) AS total_sales_amount  
                                FROM tbl_actual_data 
                                INNER JOIN tbl_customer ON tbl_actual_data.customer=tbl_customer.customers_id
                                WHERE month_year='$month_year' 
                                AND customer='$selectedCustomer->customers_id'
                                AND model='$selectedCustomer->models_id' ")->row();*/


                        foreach ($monthYearList as $month_year) {
                            $customersalesTotal = $this->db->query(
                                "SELECT DISTINCT customers_id, customers_code, customers_desc, models_code, models_desc , month_year,
                                SUM(actual_sales_qty) AS total_sales_qty,
                                SUM(actual_sales_qty*actual_price) AS total_sales_amount  
                                FROM cias.tbl_actual_data 
                                INNER JOIN cias.tbl_customer ON cias.tbl_actual_data.customer=cias.tbl_customer.customers_id 
                                INNER JOIN cias.tbl_models ON cias.tbl_actual_data.model=tbl_models.models_id
                                WHERE month_year='$month_year' 
                                AND customer='$selectedCustomer->customers_id'
                                AND models_code='$selectedCustomer->models_code' "
                            )->row();


                            foreach ($customersalesTotal as $totalsales) {
                                // Total of sales per Customer and MonthYear
                                $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month_year]['total_sales_qty'] = $customersalesTotal->total_sales_qty;
                                $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month_year]['total_sales_amount'] = $customersalesTotal->total_sales_amount;
                            }
                        }
                    }
                }



                $revisions = [$dt->selectedRevision, $selectedDPRNO];

                /*  TBL NEW PLAN | sales DATA | SELECTED REVISION VS CURRENT REVISION*/
                if (count($selected_customer) > 0 && count($total_sales_customer) > 0 && count($month_year) > 0) {
                    foreach ($selected_customer as $selectedCustomer) {
                        /* $model = [];
                        if (count($dt->SelectedCustomers) > 0) {
                                $model = $this->db->query("SELECT * FROM tbl_models WHERE customer_models_id= ".$selectedCustomer->customers_id." ORDER BY sorting_code ASC ")->result();
                        }
                        foreach ($model as $mod) {*/
                        foreach ($monthYearList as $month_year) {
                            $totalsalesCustomer_salesQty = $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month_year]['total_sales_qty'];
                            $totalsalesCustomer_salesAmount = $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month_year]['total_sales_amount'];
                            if ($totalsalesCustomer_salesQty == null && $totalsalesCustomer_salesAmount == null) {
                                foreach ($revisions as $revision) {
                                    $customersalesData = $this->db->query(
                                        /* "SELECT DISTINCT customer,customers_desc,month_year,
                                        SUM(sales_qty) AS total_sales_qty,
                                        SUM(sales_qty*price) AS total_sales_amount  
                                        FROM tbl_new_plan
                                        INNER JOIN tbl_customer ON tbl_new_plan.customer=tbl_customer.customers_id
                                        WHERE month_year='$month_year' 
                                        AND customer='$selectedCustomer->customers_id'
                                        AND model='$selectedCustomer->models_id'
                                        AND revision='$revision';"*/




                                        "SELECT DISTINCT customers_id, customers_code, customers_desc, models_code, models_desc , month_year,
                                        SUM(sales_qty) AS total_sales_qty,
                                        SUM(sales_qty*price) AS total_sales_amount  
                                        FROM tbl_new_plan
                                        INNER JOIN cias.tbl_customer ON cias.tbl_new_plan.customer=cias.tbl_customer.customers_id 
                                        INNER JOIN cias.tbl_models ON cias.tbl_new_plan.model=tbl_models.models_id
                                        WHERE month_year='$month_year' 
                                        AND customer='$selectedCustomer->customers_id'
                                        AND models_code='$selectedCustomer->models_code'
                                        AND revision='$revision';"


                                    )->row();
                                    $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month_year][$revision]['total_sales_qty'] = $customersalesData->total_sales_qty;
                                    $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month_year][$revision]['total_sales_amount'] = $customersalesData->total_sales_amount;
                                }
                            }
                        }
                    }
                }

                // TOTAL OF CUSTOMER PER QUARTER
                if (count($selected_customer) > 0 && count($total_sales_customer) > 0) {
                    foreach ($selected_customer as $selectedCustomer) {
                        if (count($dateFrom) > 0) {
                            for ($x = 1; $x <= 4; $x++) {
                                if (isset($dateFrom[$x]) || isset($dateTo[$x])) {
                                    $salesActualData = $this->db->query(
                                        "SELECT DISTINCT customers_id, customers_code, customers_desc, models_code, models_desc , month_year,
                                SUM(actual_sales_qty) AS total_sales_qty,
                                SUM(actual_sales_qty*actual_price) AS total_sales_amount  
                                FROM cias.tbl_actual_data 
                                INNER JOIN cias.tbl_customer ON cias.tbl_actual_data.customer=cias.tbl_customer.customers_id 
                                INNER JOIN cias.tbl_models ON cias.tbl_actual_data.model=tbl_models.models_id
                                AND customer='$selectedCustomer->customers_id'
                                AND models_code='$selectedCustomer->models_code'
                                AND month_year_numerical BETWEEN '$dateFrom[$x]' AND '$dateTo[$x]';"
                                    )->result();
                                    foreach ($salesActualData as $sales) {
                                        $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x]['total_sales_qty'] = $sales->total_sales_qty;
                                        $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x]['total_sales_amount'] = $sales->total_sales_amount;
                                    }

                                    //QUARTERLY TOTAL OF CUSTOMER PER QUARTER AND PER REVISION
                                    $Total_MontlhysalesQty_Selected_Revision = array();
                                    $Total_MontlhysalesQty_Current_Revision = array();
                                    $Total_MontlhysalesAmount_Selected_Revision = array();
                                    $Total_MontlhysalesAmount_Current_Revision = array();

                                    $QuarterTotal_ActualsalesQty =  $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x]['total_sales_qty'];
                                    $QuarterTotal_ActualsalesAmount =  $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x]['total_sales_amount'];

                                    $month_years[$x] = $this->db->query("SELECT DISTINCT month_year FROM tbl_new_plan WHERE month_year_numerical BETWEEN '$dateFrom[$x]' AND '$dateTo[$x]' ORDER BY month_year_numerical ASC ")->result();

                                    foreach ($month_years[$x] as $month_year) :
                                        foreach ($month_year as $month) :
                                            $totalsalesCustomer_salesQty = $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month]['total_sales_qty'];
                                            $totalsalesCustomer_salesAmount = $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month]['total_sales_amount'];
                                            if ($totalsalesCustomer_salesQty == null && $totalsalesCustomer_salesAmount == null) {

                                                $MontlhysalesQty_Selected_Revision = intval($total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month][$revisions[0]]['total_sales_qty']);
                                                $MontlhysalesQty_Current_Revision = intval($total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month][$revisions[1]]['total_sales_qty']);
                                                $MontlhysalesAmount_Selected_Revision = intval($total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month][$revisions[0]]['total_sales_amount']);
                                                $MontlhysalesAmount_Current_Revision = intval($total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc][$month][$revisions[1]]['total_sales_amount']);
                                                array_push($Total_MontlhysalesQty_Selected_Revision, $MontlhysalesQty_Selected_Revision);
                                                array_push($Total_MontlhysalesQty_Current_Revision, $MontlhysalesQty_Current_Revision);
                                                array_push($Total_MontlhysalesAmount_Selected_Revision, $MontlhysalesAmount_Selected_Revision);
                                                array_push($Total_MontlhysalesAmount_Current_Revision, $MontlhysalesAmount_Current_Revision);

                                                $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x]['total_sales_qty'] = null;
                                                $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x]['total_sales_amount'] = null;
                                            }
                                        endforeach;
                                    endforeach;

                                    $Sum_Total_MontlhysalesQty_Selected_Revision = array_sum($Total_MontlhysalesQty_Selected_Revision);
                                    $Sum_Total_MontlhysalesQty_Current_Revision = array_sum($Total_MontlhysalesQty_Current_Revision);
                                    $Sum_Total_MontlhysalesAmount_Selected_Revision = array_sum($Total_MontlhysalesAmount_Selected_Revision);
                                    $Sum_Total_MontlhysalesAmount_Current_Revision = array_sum($Total_MontlhysalesAmount_Current_Revision);
                                    $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x][$revisions[0]]['total_sales_qty'] = $Sum_Total_MontlhysalesQty_Selected_Revision + $QuarterTotal_ActualsalesQty;
                                    $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x][$revisions[1]]['total_sales_qty'] = $Sum_Total_MontlhysalesQty_Current_Revision + $QuarterTotal_ActualsalesQty;
                                    $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x][$revisions[0]]['total_sales_amount'] = $Sum_Total_MontlhysalesAmount_Selected_Revision + $QuarterTotal_ActualsalesAmount;
                                    $total_sales_customer[$selectedCustomer->customers_desc][$selectedCustomer->models_desc]["Q" . $x][$revisions[1]]['total_sales_amount'] = $Sum_Total_MontlhysalesAmount_Current_Revision + $QuarterTotal_ActualsalesAmount;
                                }
                            }
                        }
                    }
                }

                // GET GRAND TOTAL PER SELECTED CUSTOMER
                if (count($total_sales_customer) > 0) {

                    foreach ($total_sales_customer as $customer) {
                        foreach ($customer as $mon) {
                            foreach ($mon as $month_year => $sales) {




                                if (isset($grandTotal_sales_customer[$month_year]['sales_Quantity'])) {
                                    $grandTotal_sales_customer[$month_year]['sales_Quantity'] += $sales['total_sales_qty'];
                                } else {
                                    $grandTotal_sales_customer[$month_year]['sales_Quantity'] = $sales['total_sales_qty'];
                                }

                                if (isset($grandTotal_sales_customer[$month_year]['sales_Amount'])) {
                                    $grandTotal_sales_customer[$month_year]['sales_Amount'] += $sales['total_sales_amount'];
                                } else {
                                    $grandTotal_sales_customer[$month_year]['sales_Amount'] = $sales['total_sales_amount'];
                                }

                                // To include the revision data in the computation of the Total;
                                foreach ($revisions as $rev) {
                                    if ($sales['total_sales_qty'] == null) {

                                        if (isset($grandTotal_sales_customer[$month_year][$rev]['sales_Quantity'])) {
                                            $grandTotal_sales_customer[$month_year][$rev]['sales_Quantity'] += $sales[$rev]['total_sales_qty'];
                                        } else {
                                            $grandTotal_sales_customer[$month_year][$rev]['sales_Quantity'] = $sales[$rev]['total_sales_qty'];
                                        }

                                        if (isset($grandTotal_sales_customer[$month_year][$rev]['sales_Amount'])) {
                                            $grandTotal_sales_customer[$month_year][$rev]['sales_Amount'] += $sales[$rev]['total_sales_amount'];
                                        } else {
                                            $grandTotal_sales_customer[$month_year][$rev]['sales_Amount'] = $sales[$rev]['total_sales_amount'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $data = array(
                    "customers" => $customers,
                    'monthYear' => $monthYearList,
                    'selected_customer' => $selected_customer,
                    'total_sales_customer' => $total_sales_customer,
                    'grandTotal_sales_customer' => $grandTotal_sales_customer,
                );
                echo json_encode($data);
            }
        }
    }

    function getCustomersColor()
    {
        $data['customers_color'] = $this->db->query("SELECT * FROM tbl_customer GROUP BY customers_code ORDER BY sorting_code ASC ")->result();

        echo json_encode($data);
    }
}
