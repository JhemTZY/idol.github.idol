<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : Roles (RolesController)
 * Roles Class to control role related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 22 Jan 2021
 */
class Material_cost extends BaseController
{
    /**
     * This is default constructor of the class
     */
     public function __construct()
    {
        parent::__construct();

        $this->load->helper(array('url', 'html', 'form'));
        $this->load->model('material_cost_model', 'mc');
        $this->load->library('functions', 'ci');
        $this->isLoggedIn();
        $this->module = 'Task';
    }

      function materialCost_Report()
    {
        $selectedMonth = date("F-Y");
        $selectedDPRNO = $this->mc->get_dpr_no();
        $page = "ps_plan_form";
        $data["selectedMonth"] = $selectedMonth;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data["page"] = $page;
          $p = $_POST;
        $selected_revision = "";
        if (isset($p['revi'])) {
            $selected_revision = $p['revi'];
        }
        $selectedDPRNO = $this->mc->get_dpr_no();
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data["selected_revision"] = $selected_revision;
        $data['revision'] = $this->mc->get_revision();

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : Reports';

           /* $this->loadViews("material_cost/mc_cal", $this->global, $data, NULL);*/
            $this->loadViews("reports/404", $this->global, $data, NULL);
        }
    }



    function Material_cost_per_quarter()
    {

        $data = array();
        $dt = json_decode(file_get_contents("php://input"));

        if (isset($dt)) {
            $selected_revision = $this->mc->get_dpr_no();
            $FirstMonthOfCurrentRevision = $this->mc->getFirstMonthOfCurrentRevision($selected_revision)->month_year_numerical;
            $LastMonthOfCurrentRevision = $this->mc->getLastMonthOfCurrentRevision($selected_revision)->month_year_numerical;

            $distinctCustomers = $this->mc->get_table_customer();
            $months = $this->mc->get_dates_from_current_revision($selected_revision, $FirstMonthOfCurrentRevision, $LastMonthOfCurrentRevision);
            $distinctModels = $this->mc->get_distinct_models();
            $psiMergedData = [];
            foreach ($distinctModels as $model) :
                $models_code = $model->models_code;
                $models_desc = $model->models_desc;
                $customers_id = $model->customers_id;
                $totalMergedInventory = 0;
                foreach ($months as $month) :
                    $mergedModelsData = $this->mc->get_models_merged_data($customers_id, $month->month_year, $models_code, $selected_revision);
                    $psiMergedData[$model->customers_desc][$models_desc][$month->month_year]['mergedSalesQty'] = $mergedModelsData->mergedSalesQty;
                    $psiMergedData[$model->customers_desc][$models_desc][$month->month_year]['mergedProdQty'] = $mergedModelsData->mergedProdQty;
                    // Total Inventory
                    $totalMergedInventory += $mergedModelsData->mergedInventory; // Inventory = EndInventory + NewInventory - Sales
                    $psiMergedData[$model->customers_desc][$models_desc][$month->month_year]['mergedInventory'] =  $totalMergedInventory;
                    // Total Production Quantity
                    if (isset($psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedProdQty'])) {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedProdQty'] += $mergedModelsData->mergedProdQty;
                    } else {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedProdQty'] = $mergedModelsData->mergedProdQty;
                    }
                    // Total Sales Quantity
                    if (isset($psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedSalesQty'])) {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedSalesQty'] += $mergedModelsData->mergedSalesQty;
                    } else {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedSalesQty'] = $mergedModelsData->mergedSalesQty;
                    }
                    // Total Inventory
                    if (isset($psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedInventory'])) {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedInventory'] += $totalMergedInventory;
                    } else {
                        $psiMergedData[$model->customers_desc]['Total'][$month->month_year]['mergedInventory'] = $totalMergedInventory;
                    }
                    $this->movetobottom($psiMergedData[$model->customers_desc], "Total");
                endforeach;
            endforeach;

            $data = array(
                "distinctCustomers" => $distinctCustomers,
                "distinctModels" => $distinctModels,
                "months" => $months,
                "psiMergedData" => $psiMergedData,
            );
        }
        echo json_encode($data);
    }

    function movetobottom(&$array, $key)
    {
        $value = $array[$key];
        unset($array[$key]);
        $array[$key] = $value;
    }


      function mc_block_view()
    {
        $selectedMonth = date("F-Y");
        $selectedDPRNO = $this->mc->get_dpr_no();
        $page = "ps_plan_form";
        $data["selectedMonth"] = $selectedMonth;
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data["page"] = $page;
          $p = $_POST;
        $selected_revision = "";
        if (isset($p['revi'])) {
            $selected_revision = $p['revi'];
        }
        $selectedDPRNO = $this->mc->get_dpr_no();
        $data["selectedDPRNO"] = $selectedDPRNO;
        $data["selected_revision"] = $selected_revision;
        $data['revision'] = $this->mc->get_revision();

        if (!$this->hasUserListAccess()) {
            $this->loadThis();
        } else {
            $this->global['pageTitle'] = 'DWH : Reports';

           /* $this->loadViews("material_cost/mc_block", $this->global, $data, NULL);*/
             $this->loadViews("reports/404", $this->global, $data, NULL);
        }
    }

public function mc_block_data(){

 $data = array();
        $dt = json_decode(file_get_contents("php://input"));

        if (isset($dt)) {
            $selected_revision = $this->mc->get_dpr_no();
            $FirstMonthOfCurrentRevision = $this->mc->getFirstMonthOfCurrentRevision($selected_revision)->month_year_numerical;
            $LastMonthOfCurrentRevision = $this->mc->getLastMonthOfCurrentRevision($selected_revision)->month_year_numerical;
            $months = $this->mc->get_dates_from_current_revision($selected_revision, $FirstMonthOfCurrentRevision, $LastMonthOfCurrentRevision);
            $distinctCustomers = $this->mc->get_table_customer();
         

            $data = array(
                "distinctCustomers" => $distinctCustomers,
                "months" => $months,
                
            );
        }
        echo json_encode($data);  
    }
    
  
}


?>