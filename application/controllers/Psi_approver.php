<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Task (TaskController)
 * Task Class to control task related operations.
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 19 Jun 2022
 */
class Psi_approver extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('url', 'html', 'form'));
        $this->load->model('Psi_approvers_data', 'PAD');
         $this->load->model('PSI_MODEL', 'psim');
        $this->load->model('User_model', 'usm');
        $this->load->model('Task_model', 'tm');
        $this->load->model('PSI_MODEL', 'psim');
        $this->load->model('Task_model', 'tm');
        $this->module = 'PSI';

        // $this->isLoggedIn();
    }

/* MS.YAMADASAN*/
    public function Approvers()
    {
        $selectedDPRNO = $this->PAD->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data['revision'] = $this->PAD->get_revision();

        $dateFrom = $dateTo = "";
        $dashngayon = substr($selectedDPRNO, 7, 1);
        if ($dashngayon == 1 || $dashngayon == 4) {
            $dateFrom = date("Y-m-01", strtotime("-1 month"));
        } else {
            $dateFrom = date("Y-m-01");
        }

        $lastMonth_in_Current_Revision = $this->db->query("SELECT * FROM tbl_new_plan WHERE revision='$selectedDPRNO' ORDER BY month_year_numerical DESC LIMIT 1")->row();
        if (isset($lastMonth_in_Current_Revision)) {
            $dateTo = $lastMonth_in_Current_Revision->month_year_numerical;
        }
        $data['pageTitle'] = 'DWH : PSI Approval';
        $data['psiEdit_dateFrom'] = $dateFrom;
        $data['psiEdit_dateTo'] = $dateTo;
        $data['current_rev'] = $this->PAD->get_dates_AND_current_revision($selectedDPRNO, $dateFrom, $dateTo);
        $data['customers'] = $this->db->query("SELECT * FROM tbl_customer ORDER BY customers_id ASC")->result();
        $this->load->view("psi_approvers/approver", $data, NULL);
    }
    /* MR.YAMADASAN*/

    /* MS.V*/

    function viewing_reports()
    {

        $selectedMonth = date("F-Y");
        $selectedDPRNO = $this->PAD->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data['revision'] = $this->PAD->get_revision();



        $dateFrom = $dateTo = "";

        $dashngayon = substr($selectedDPRNO, 7, 1);
        if ($dashngayon == 1 || $dashngayon == 4) {
            $dateFrom = date("Y-m-01", strtotime("-1 month"));
        } else {
            $dateFrom = date("Y-m-01");
        }



        $lastMonth_in_Current_Revision = $this->db->query("SELECT * FROM tbl_new_plan WHERE revision='$selectedDPRNO' ORDER BY month_year_numerical DESC LIMIT 1")->row();
        if (isset($lastMonth_in_Current_Revision)) {
            $dateTo = $lastMonth_in_Current_Revision->month_year_numerical;
        }
        $data['pageTitle'] = 'DWH : PSI';
        $data['psiEdit_dateFrom'] = $dateFrom;
        $data['psiEdit_dateTo'] = $dateTo;
        $data['current_rev'] = $this->PAD->get_dates_AND_current_revision($selectedDPRNO, $dateFrom, $dateTo);
        $data['customers'] = $this->db->query("SELECT * FROM tbl_customer ORDER BY customers_id ASC")->result();
        $this->load->view("psi_approvers/noted_by", $data, $data, NULL);
    }
    /* MS.V*/


/* MS.HELEN*/


    function approver_reports()
    {
        $selectedDPRNO = $this->PAD->get_dpr_no();
        $page = "ps_plan_form";
        $data["page"] = $page;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data['revision'] = $this->PAD->get_revision();

        $dateFrom = $dateTo = "";
        $dashngayon = substr($selectedDPRNO, 7, 1);
        if ($dashngayon == 1 || $dashngayon == 4) {
            $dateFrom = date("Y-m-01", strtotime("-1 month"));
        } else {
            $dateFrom = date("Y-m-01");
        }

        $lastMonth_in_Current_Revision = $this->db->query("SELECT * FROM tbl_new_plan WHERE revision='$selectedDPRNO' ORDER BY month_year_numerical DESC LIMIT 1")->row();
        if (isset($lastMonth_in_Current_Revision)) {
            $dateTo = $lastMonth_in_Current_Revision->month_year_numerical;
        }
        $data['pageTitle'] = 'DWH : PSI Approval';
        $data['psiEdit_dateFrom'] = $dateFrom;
        $data['psiEdit_dateTo'] = $dateTo;
        $data['current_rev'] = $this->PAD->get_dates_AND_current_revision($selectedDPRNO, $dateFrom, $dateTo);
        $data['customers'] = $this->db->query("SELECT * FROM tbl_customer ORDER BY customers_id ASC")->result();
        $this->load->view("psi_approvers/prepared_by", $data, NULL);
    }

/* MS.HELEN*/





    function psi_noted_by_status()
    {
        $pd = $_POST;
        if (isset($pd['approved'], $pd['userId'], $pd['comment'],$pd['revisionID'])) {
            $query = $this->db->query("UPDATE tbl_approved_psi SET status = '1' , remarks = '" . $pd['comment'] . "' WHERE approver_id = " . $pd['userId'] . " AND rev_id=".$pd['revisionID']." ");
        }
         // SEND EMAIL TO PSI APPROVERS
            file_get_contents('http://10.216.2.202/Datawarehouse_approval/approved_by.php');
        redirect('index.php/Psi_approver/Psi_viewing', NULL);
    }

      function psi_noted_by_rejected_status()
    {
        $pd = $_POST;
        if (isset($pd['approved'], $pd['userId'], $pd['comment'],$pd['revisionID'])) {
            $query = $this->db->query("UPDATE tbl_approved_psi SET status = 'Rejected' , remarks = '" . $pd['comment'] . "' WHERE approver_id = " . $pd['userId'] . " AND rev_id=".$pd['revisionID']." ");
        }
        redirect('index.php/Psi_approver/Psi_viewing', NULL);
    }


    
  function psi_prepared_by_status()
    {
        $pd = $_POST;
        if (isset($pd['approved'], $pd['userId'], $pd['comment'],$pd['revisionID'])) {
            $query = $this->db->query("UPDATE tbl_approved_psi SET status = '1' , remarks = '" . $pd['comment'] . "' WHERE approver_id = " . $pd['userId'] . " AND rev_id=".$pd['revisionID']." ");
        }

         // SEND EMAIL TO PSI APPROVERS
            file_get_contents('http://10.216.2.202/Datawarehouse_approval/noted_by.php');
        redirect('index.php/Psi_approver/Psi_viewing', NULL);
    }

      function psi_prepared_by_rejected_status()
    {
        $pd = $_POST;
        if (isset($pd['approved'], $pd['userId'], $pd['comment'],$pd['revisionID'])) {
            $query = $this->db->query("UPDATE tbl_approved_psi SET status = 'Rejected' , remarks = '" . $pd['comment'] . "' WHERE approver_id = " . $pd['userId'] . " AND rev_id=".$pd['revisionID']." ");
        }
        redirect('index.php/Psi_approver/Psi_viewing', NULL);
    }


      function psi_approved_by_status()
    {
        $pd = $_POST;
        $revision_date = $this->db->query("SELECT revision_date  FROM cias.tbl_revision where  isActive = 1 ")->row();

        if (isset($pd['approved'], $pd['userId'], $pd['comment'],$pd['revisionID'])) {
            $query = $this->db->query("UPDATE tbl_approved_psi SET status = '1' , remarks = '" . $pd['comment'] . "' WHERE approver_id = " . $pd['userId'] . " AND rev_id=".$pd['revisionID']." ");

   if (isset($revision_date) && !empty($revision_date)) {

            // *** 1. Get the current revision ***
            $query = $this->db->query('SELECT * FROM tbl_revision WHERE isActive="1" ');
            $current_revision = $query->row()->revision_date;

            // *** 2. Get all data from current revision ***

            $data_from_current_rev = array();
            if (isset($current_revision)) {
                $q = $this->db->query("SELECT * FROM tbl_new_plan WHERE revision='$current_revision' ");
                $data_from_current_rev = $q->result();
            }

            // Check tbl_new_plan if current revision already exist.
            $rev_exist = $this->db->query("SELECT * FROM tbl_new_plan WHERE revision='$revision_date' LIMIT 1 ")->result();
            if (count($rev_exist) >= 1) {
                // print_r($rev_exist);
                // echo "Revision already made! ";
                $this->session->set_flashdata('error', 'REVISION ALREADY MADE !');
            } else {
                // *** 3. Insert all the data from current revision and change revision to new revision ***
                if (count($data_from_current_rev) > 0) {
                    foreach ($data_from_current_rev as $row) {
                        $data = array(
                            "customer" => $row->customer,
                            "model" => $row->model,
                            "price" => $row->price,
                            "month_year" => $row->month_year,
                            "revision" => $revision_date->revision_date,
                            "merge_sales_qty" => $row->sales_qty,
                            "merge_prod_qty" => $row->prod_qty,
                            "inventory" => 0,
                            "beginning_invty" => 0,
                            "month_year_numerical" => $row->month_year_numerical
                            
                        );
                        $this->db->insert('tbl_sales_amount', $data);

                    }
                }
            }
}
            

        }

        redirect('index.php/Psi_approver/Psi_viewing', NULL);
    }

      function psi_approved_by_rejected_status()
    {
        $pd = $_POST;
        if (isset($pd['approved'], $pd['userId'], $pd['comment'],$pd['revisionID'])) {
            $query = $this->db->query("UPDATE tbl_approved_psi SET status = 'Rejected' , remarks = '" . $pd['comment'] . "' WHERE approver_id = " . $pd['userId'] . " AND rev_id=".$pd['revisionID']." ");
        }
        redirect('index.php/Psi_approver/Psi_viewing', NULL);
    }

   

    // GENERATE PSI EDIT
      function GeneratePSIedit()
    {
        $current_revision = $this->tm->get_dpr_no();
        $data = array();
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            $customers = $this->psim->GetCustomers();
            $months = $this->psim->GETMONTHS($current_revision);
            /*  $begin_invty = $this->psim->GET_BEGIN();*/
            $allModels = [];
            $psi = [];
            foreach ($customers as $customer):
                $models = $this->psim->GETMODELS($customer->customers_id);
                $allModels[$customer->customers_desc] = $models; foreach ($models as $modelKey => $model):
                $psi[$customer->customers_desc][$modelKey]['modelDT'] = $model;
                $inventory = 0; foreach ($months as $month):
                $psiDT = $this->psim->GETPSIDATA($model->models_code, $current_revision, $month->month_year);
                if ($psiDT !== NULL) {

                    if ($model->cat_code == "EOL") {
                        $inventory = $psiDT->inventory;
                    }
                    $inventory += $psiDT->mergedInventory;
                    $psiDT->mergedInventory = $inventory;
                    $psi[$customer->customers_desc][$modelKey][$month->month_year] = $psiDT;


                    if ($model->cat_code == "MP" || $model->cat_code == "SMPL" || $model->cat_code == "EOL") {
                                // MP AND SAMPLE Total
                        if (isset($psi[$customer->customers_desc]['Total'][$month->month_year]['mergedProdQty'])) {
                            $psi[$customer->customers_desc]['Total'][$month->month_year]['mergedProdQty'] += $psiDT->mergedProdQty;
                            $psi[$customer->customers_desc]['Total'][$month->month_year]['mergedSalesQty'] += $psiDT->mergedSalesQty;
                            $psi[$customer->customers_desc]['Total'][$month->month_year]['mergedInventory'] += $psiDT->mergedInventory;
                            $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                            $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                        } else {
                            $psi[$customer->customers_desc]['Total'][$month->month_year]['mergedProdQty'] = $psiDT->mergedProdQty;
                            $psi[$customer->customers_desc]['Total'][$month->month_year]['mergedSalesQty'] = $psiDT->mergedSalesQty;
                            $psi[$customer->customers_desc]['Total'][$month->month_year]['mergedInventory'] = $psiDT->mergedInventory;
                            $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                            $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                        }
                    }

                    if ($model->cat_code == "EOL") {
                                // MP AND SAMPLE Total
                        if (isset($psi[$customer->customers_desc]['Total'][$month->month_year]['mergedProdQty'])) {

                            $psi[$customer->customers_desc]['Total'][$month->month_year]['mergedInventory'] += $psiDT->mergedInventory;
                            $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                            $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                        } else {

                            $psi[$customer->customers_desc]['Total'][$month->month_year]['mergedInventory'] = $psiDT->mergedInventory;
                            $psi[$customer->customers_desc]['Total']['modelDT']['cat_code'] = "TOTAL";
                            $psi[$customer->customers_desc]['Total']['modelDT']['models_desc'] = $customer->customers_code . " Total";
                        }
                    }




                    if ($model->cat_code == "EOL") {
                                // MP AND SAMPLE Total
                        if (isset($psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'])) {
                            $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['mergedProdQty'] += $psiDT->mergedProdQty;
                            $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['mergedSalesQty'] += $psiDT->mergedSalesQty;
                            $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'] += $psiDT->inventory;
                            $psi[$customer->customers_desc]['EOLTotal']['modelDT']['cat_code'] = "EOLTOTAL";
                            $psi[$customer->customers_desc]['EOLTotal']['modelDT']['models_desc'] = $customer->customers_code . " EOL Total";
                        } else {
                            $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['mergedProdQty'] = $psiDT->mergedProdQty;
                            $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['mergedSalesQty'] = $psiDT->mergedSalesQty;
                            $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'] = $psiDT->inventory;
                            $psi[$customer->customers_desc]['EOLTotal']['modelDT']['cat_code'] = "EOLTOTAL";
                            $psi[$customer->customers_desc]['EOLTotal']['modelDT']['models_desc'] = $customer->customers_code . " EOL Total";
                        }
                    }


                            // if ($model->cat_code == "EOL") {

                            //     // EOL
                            //     if (isset($psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'])) {
                            //         $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'] += $psiDT->inventory;
                            //         $psi[$customer->customers_desc]['EOLTotal']['modelDT']['cat_code'] = "EOLTOTAL";
                            //         $psi[$customer->customers_desc]['EOLTotal']['modelDT']['models_desc'] = $customer->customers_code . " EOL Total";
                            //     } else {
                            //         $psi[$customer->customers_desc]['EOLTotal'][$month->month_year]['inventory'] = $psiDT->inventory;
                            //         $psi[$customer->customers_desc]['EOLTotal']['modelDT']['cat_code'] = "EOLTOTAL";
                            //         $psi[$customer->customers_desc]['EOLTotal']['modelDT']['models_desc'] = $customer->customers_code . " EOL Total";
                            //     }
                            // }

                    if ($model->cat_code == "MP" || $model->cat_code == "SMPL") {
                        if (isset($psi['GrandTotal']['cat_code'][$month->month_year]['mergedProdQty'])) {
                            $psi['GrandTotal']['cat_code'][$month->month_year]['mergedProdQty'] += $psiDT->mergedProdQty;
                            $psi['GrandTotal']['cat_code'][$month->month_year]['mergedSalesQty'] += $psiDT->mergedSalesQty;
                            $psi['GrandTotal']['cat_code'][$month->month_year]['mergedInventory'] += $psiDT->mergedInventory;
                            $psi['GrandTotal']['cat_code']['modelDT']['cat_code'] = "GRANDTOTAL";
                            $psi['GrandTotal']['cat_code']['modelDT']['models_desc'] = " GRAND TOTAL";

                        } else {
                            $psi['GrandTotal']['cat_code'][$month->month_year]['mergedProdQty'] = $psiDT->mergedProdQty;
                            $psi['GrandTotal']['cat_code'][$month->month_year]['mergedSalesQty'] = $psiDT->mergedSalesQty;
                            $psi['GrandTotal']['cat_code'][$month->month_year]['mergedInventory'] = $psiDT->mergedInventory;
                            $psi['GrandTotal']['cat_code']['modelDT']['cat_code'] = "GRANDTOTAL";
                            $psi['GrandTotal']['cat_code']['modelDT']['models_desc'] = " GRAND TOTAL";
                        }
                        $this->movetobottom($psi, "GrandTotal");
                    }


                            // if ($model->cat_code == "EOL") {
                            //      if (isset($psi['GrandTotal'][$model->cat_code][$month->month_year]['mergedProdQty'])) {
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['mergedProdQty'] += $psiDT->mergedProdQty;
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['sales_qty'] += $psiDT->sales_qty;
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['mergedInventory'] += $psiDT->mergedInventory;
                            //         $psi['GrandTotal'][$model->cat_code]['modelDT']['cat_code'] = "GRANDTOTAL";
                            //         $psi['GrandTotal'][$model->cat_code]['modelDT']['models_desc'] = " GRAND TOTAL";

                            //     } else {
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['mergedProdQty'] = $psiDT->mergedProdQty;
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['sales_qty'] = $psiDT->sales_qty;
                            //         $psi['GrandTotal'][$model->cat_code][$month->month_year]['mergedInventory'] = $psiDT->mergedInventory;
                            //         $psi['GrandTotal'][$model->cat_code]['modelDT']['cat_code'] = "GRANDTOTAL";
                            //         $psi['GrandTotal'][$model->cat_code]['modelDT']['models_desc'] =  " GRAND TOTAL";
                            //     }
                            //     $this->movetobottom($psi, "GrandTotal");
                            // }

                }
            endforeach;
        endforeach;
    endforeach;

    $data = array(
        "customers" => $customers,
        "months" => $months,
        /*"begin_invty" => $begin_invty,*/
        "AllModels" => $allModels,
        "psiData" => $psi
    );
    echo json_encode($data);
}
}

    // function update_new_plan()
    // {
    //     $dt = json_decode(file_get_contents("php://input"));
    //     if (isset($dt)) {
    //         // print_r($dt);
    //         $revision = $dt->SelectedRevision;
    //         $id = $dt->id;
    //         $value = $dt->value;
    //         $psi = $dt->psi;
    //         $modelID = $dt->modelID;
    //         $month_year = $dt->month_year;

    //         if ($psi == 'price') {
    //             echo "Price Updated";
    //             $this->db->query("UPDATE tbl_new_plan SET price='$value' WHERE auto_id='$id' AND revision='$revision' ");
    //         }

    //         if ($psi == 'prod_qty') {
    //             echo "Production Updated";
    //             print_r($dt);
    //             $this->db->query("UPDATE tbl_new_plan, tbl_models SET tbl_new_plan.prod_qty=0 WHERE tbl_models.models_code='$modelID' AND tbl_new_plan.revision='$revision' AND tbl_new_plan.month_year='$month_year' AND tbl_new_plan.model=tbl_models.models_id");

    //             $this->db->query("UPDATE tbl_new_plan SET prod_qty='$value' *1000, inventory=prod_qty-sales_qty , isProdEdit = 1 WHERE auto_id='$id' AND revision='$revision' ");

    //         }

    //         if ($psi == 'sales_qty') {
    //             echo "Sales Updated";
                
    //            $this->db->query("UPDATE tbl_new_plan, tbl_models SET tbl_new_plan.sales_qty=0 WHERE tbl_models.models_code='$modelID' AND tbl_new_plan.revision='$revision' AND tbl_new_plan.month_year='$month_year' AND tbl_new_plan.model=tbl_models.models_id");

    //             $this->db->query("UPDATE tbl_new_plan SET sales_qty='$value' *1000,  inventory=prod_qty-sales_qty , isSalesEdit = 1 WHERE auto_id='$id' AND revision='$revision' ");
    //         }
    //         if ($psi == 'inventory') {
    //             echo "Inventory Updated";
    //             $this->db->query("UPDATE tbl_new_plan SET inventory='$value' *1000 WHERE auto_id='$id' AND revision='$revision' ");
    //         }

    //          if ($psi == 'inventory') {
    //             echo "Inventory Updated";
    //             $this->db->query("UPDATE tbl_new_plan SET beginning_inv='$value' *1000 WHERE auto_id='$id' AND revision='$revision' ");
    //         }


    //     }
    // }


    //   function update_new_plan()
    // {
    //     $dt = json_decode(file_get_contents("php://input"));
    //     if (isset($dt)) {
    //         // print_r($dt);
    //         $revision = $dt->SelectedRevision;
    //         $id = $dt->id;
    //         $value = $dt->value;
    //         $psi = $dt->psi;
    //         $month = $dt->month;
    //         $model = $dt->model;
    //         $cellnum = $dt->cellID;
    //         $user_id =  $this->session->userdata('userId');
    //         $name =  $this->session->userdata('name');
    //         $role_text =  $this->session->userdata('roleText');

            

    //         if ($psi == 'price') {
    //             echo "Price Updated";
    //             $this->db->query("UPDATE tbl_new_plan SET price='$value' WHERE auto_id='$id' AND revision='$revision' ");
    //             $action_made = "updated the Price of {$cellnum} in PSI EDIT MODE";
    //         }


    //      if ($psi == 'prod_qty') {
            
            
    //              $this->db->query("UPDATE tbl_new_plan SET prod_qty='$value' *1000, inventory=prod_qty-sales_qty , isProdEdit = 1  WHERE auto_id='$id' AND revision='$revision' ");

    //              $action_made = "updated the Prod Qty of {$cellnum} in PSI EDIT MODE";

    //             $previous_revision =  $this->db->query("SELECT revision_date AS prevrev FROM cias.tbl_revision LIMIT 2")->row()->prevrev;
    //               $previous_rev = $this->db->query("SELECT prod_qty FROM tbl_new_plan WHERE revision='$previous_revision' AND month_year='$month'  AND model='$model'")->row();
    //               $current_rev = $this->db->query("SELECT prod_qty FROM tbl_new_plan WHERE revision='$revision' AND month_year='$month'  AND model='$model'")->row();

    //                 if ($previous_rev->prod_qty == $current_rev->prod_qty) {

    //                $this->db->query("UPDATE tbl_new_plan SET prod_qty='$value' *1000, inventory=prod_qty-sales_qty , isProdEdit = 0  WHERE auto_id='$id' AND revision='$revision' ");

    //              $action_made = "Same data in previous Prod Qty of {$cellnum} in PSI EDIT MODE";
    //         }

    //         } 

          


    //         if ($psi == 'sales_qty') {
    //             echo "Sales Updated";
               

    //             $this->db->query("UPDATE tbl_new_plan SET sales_qty='$value' *1000,  inventory=prod_qty-sales_qty , isSalesEdit = 1 WHERE auto_id='$id' AND revision='$revision' ");

    //              $action_made = "updated the Sales Qty of {$cellnum} in PSI EDIT MODE";
                 
    //               $previous_revision =  $this->db->query("SELECT revision_date AS prevrev FROM cias.tbl_revision LIMIT 2")->row()->prevrev;
    //               $previous_rev = $this->db->query("SELECT sales_qty FROM tbl_new_plan WHERE revision='$previous_revision' AND month_year='$month'  AND model='$model'")->row();
    //               $current_rev = $this->db->query("SELECT sales_qty FROM tbl_new_plan WHERE revision='$revision' AND month_year='$month'  AND model='$model'")->row();

    //                 if ($previous_rev->sales_qty == $current_rev->sales_qty) {

    //                $this->db->query("UPDATE tbl_new_plan SET sales_qty='$value' *1000,  inventory=prod_qty-sales_qty , isSalesEdit = 0 WHERE auto_id='$id' AND revision='$revision' ");

    //              $action_made = "Same data in previous Sales Qty of {$cellnum} in PSI EDIT MODE";
    //          }
    //         }

    //         if ($psi == 'inventory') {
    //             echo "Inventory Updated";
    //             $this->db->query("UPDATE tbl_new_plan SET inventory='$value' *1000 WHERE auto_id='$id' AND revision='$revision' ");
    //              $action_made = "updated the Inventory of {$cellnum} in PSI EDIT MODE";
    //         }

    //          if ($psi == 'inventory') {
    //             echo "Inventory Updated";
    //             $this->db->query("UPDATE tbl_new_plan SET beginning_inv='$value' *1000 WHERE auto_id='$id' AND revision='$revision' ");
    //              $action_made = "updated the Inventory of {$cellnum} in PSI EDIT MODE";
    //         }

    //             $this->psim->UserLog($user_id,  $role_text, $name, $action_made);
    //     }

    // }




function update_new_plan()
{
    $dt = json_decode(file_get_contents("php://input"));
    if (isset($dt)) {
            // print_r($dt);
        $revision = $dt->SelectedRevision;
        $id = $dt->id;
        $value = $dt->value;
        $psi = $dt->psi;
        $month = $dt->month;
        $model = $dt->model;
        $models_code = $dt->models_code;
        $cellnum = $dt->cellID;
        $user_id = $this->session->userdata('userId');
        $name = $this->session->userdata('name');
        $role_text = $this->session->userdata('roleText');



        if ($psi == 'price') {
            echo "Price Updated";
            $this->db->query("UPDATE tbl_new_plan SET price='$value' WHERE auto_id='$id' AND revision='$revision' ");
            $action_made = "updated the Price of {$cellnum} in PSI EDIT MODE";
        }


        if ($psi == 'prod_qty') {

            $this->db->query("UPDATE tbl_new_plan SET prod_qty='$value' *1000, inventory=prod_qty-sales_qty , isProdEdit = 1  WHERE auto_id='$id' AND revision='$revision' ");

            $action_made = "updated the Prod Qty of {$cellnum} in PSI EDIT MODE";

            }





        if ($psi == 'sales_qty') {
            echo "Sales Updated";



            $this->db->query("UPDATE tbl_new_plan SET sales_qty='$value' *1000,  inventory=prod_qty-sales_qty , isSalesEdit = 1 WHERE auto_id='$id' AND revision='$revision' ");

            $action_made = "updated the Sales Qty of {$cellnum} in PSI EDIT MODE";

            
        }

        if ($psi == 'inventory') {
            echo "Inventory Updated";
            $this->db->query("UPDATE tbl_new_plan SET inventory='$value' *1000 WHERE auto_id='$id' AND revision='$revision' ");
            $action_made = "updated the Inventory of {$cellnum} in PSI EDIT MODE";
        }

        if ($psi == 'inventory') {
            echo "Inventory Updated";
            $this->db->query("UPDATE tbl_new_plan SET beginning_inv='$value' *1000 WHERE auto_id='$id' AND revision='$revision' ");
            $action_made = "updated the Inventory of {$cellnum} in PSI EDIT MODE";
        }

        $this->psim->UserLog($user_id, $role_text, $name, $action_made);
    }

}



/* NO COLOR
    function update_new_plan_prepared_noted()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            // print_r($dt);
            $revision = $dt->SelectedRevision;
            $id = $dt->id;
            $value = $dt->value;
            $psi = $dt->psi;
            $modelID = $dt->modelID;
            $month_year = $dt->month_year;

          if ($psi == 'price') {
                echo "Price Updated";
                $this->db->query("UPDATE tbl_new_plan SET price='$value' WHERE auto_id='$id' AND revision='$revision' ");
            }
            if ($psi == 'prod_qty') {
                echo "Production Updated";
                $this->db->query("UPDATE tbl_new_plan SET prod_qty='$value' *1000, inventory=prod_qty-sales_qty  WHERE auto_id='$id' AND revision='$revision' ");
            }
            if ($psi == 'sales_qty') {
                echo "Sales Updated";
                $this->db->query("UPDATE tbl_new_plan SET sales_qty='$value' *1000, inventory=prod_qty-sales_qty   WHERE auto_id='$id' AND revision='$revision' ");
            }
            if ($psi == 'inventory') {
                echo "Inventory Updated";
                $this->db->query("UPDATE tbl_new_plan SET inventory='$value' *1000  WHERE auto_id='$id' AND revision='$revision' ");
            }

            if ($psi == 'inventory') {
                echo "Inventory Updated";
                $this->db->query("UPDATE tbl_new_plan SET beginning_inv='$value' *1000  WHERE auto_id='$id' AND revision='$revision' ");
            }
        }
    }         NO COLOR*/

    
 function update_new_plan_prepared_noted()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            // print_r($dt);
            $revision = $dt->SelectedRevision;
            $id = $dt->id;
            $value = $dt->value;
            $psi = $dt->psi;
            $modelID = $dt->modelID;
            $month_year = $dt->month_year;

            if ($psi == 'price') {
                echo "Price Updated";
                $this->db->query("UPDATE tbl_new_plan SET price='$value' WHERE auto_id='$id' AND revision='$revision' ");
            }

            if ($psi == 'prod_qty') {
                echo "Production Updated";
                print_r($dt);
                $this->db->query("UPDATE tbl_new_plan, tbl_models SET tbl_new_plan.prod_qty=0 WHERE tbl_models.models_code='$modelID' AND tbl_new_plan.revision='$revision' AND tbl_new_plan.month_year='$month_year' AND tbl_new_plan.model=tbl_models.models_id");

                $this->db->query("UPDATE tbl_new_plan SET prod_qty='$value' *1000, inventory=prod_qty-sales_qty , isProdEdit = 1 WHERE auto_id='$id' AND revision='$revision' ");

                  /*$this->db->query("UPDATE tbl_new_plan SET prod_qty='$value', inventory=prod_qty-sales_qty WHERE auto_id='$id' AND revision='$revision'");*/
            }

            if ($psi == 'sales_qty') {
                echo "Sales Updated";
                
               $this->db->query("UPDATE tbl_new_plan, tbl_models SET tbl_new_plan.sales_qty=0 WHERE tbl_models.models_code='$modelID' AND tbl_new_plan.revision='$revision' AND tbl_new_plan.month_year='$month_year' AND tbl_new_plan.model=tbl_models.models_id");

                $this->db->query("UPDATE tbl_new_plan SET sales_qty='$value' *1000,  inventory=prod_qty-sales_qty , isSalesEdit = 1 WHERE auto_id='$id' AND revision='$revision' ");
            }
            if ($psi == 'inventory') {
                echo "Inventory Updated";
                $this->db->query("UPDATE tbl_new_plan SET inventory='$value' *1000 WHERE auto_id='$id' AND revision='$revision' ");
            }

             if ($psi == 'inventory') {
                echo "Inventory Updated";
                $this->db->query("UPDATE tbl_new_plan SET beginning_inv='$value' *1000 WHERE auto_id='$id' AND revision='$revision' ");
            }


        }
    }




     public function Psi_viewing()
    {
        $p = $_POST;
        $selected_revision = "";
        if (isset($p['revi'])) {
            $selected_revision = $p['revi'];
        }
        $selectedDPRNO = $this->tm->get_dpr_no();
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data["selected_revision"] = $selected_revision;
        $data['revision'] = $this->tm->get_revision();
        $data['month_years'] = $this->tm->get_month();
        $this->load->view("psi_approvers/psi_viewing", $data, NULL);
    }




    function GetCurrentRevisionData()
    {
        $data = array();
        $CurrentRevisionData = $this->PAD->GetCurrentRevisionData();
        $data = array(
            "CurrentRevisionData" => $CurrentRevisionData
        );
        echo json_encode($data);
    }

      function getApprovers()
    {
        $data = array();
        $MgaApprover = $this->db->query("SELECT * FROM cias.tbl_approved_psi")->result();
        $data = array(
               "MgaApprover" => $MgaApprover
            );
            echo json_encode($data);
    }

     function approved_and_disapproved()
    {
        $dt = json_decode(file_get_contents("php://input"));
        if (isset($dt)) {
            // print_r($dt);
            $approved_disapproved = $dt->approved_disapproved;
            $approver_id = $dt->approver_id;
          
            if ($approved_disapproved == 'Approved') {
         
                $this->db->query("UPDATE cias.tbl_approved_psi SET status=1  WHERE approver_id = '$approver_id' and rev_id =3");
            }
        }
}


}