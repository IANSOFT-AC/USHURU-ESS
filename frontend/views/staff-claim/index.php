<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/22/2020
 * Time: 5:23 PM
 */



/* @var $this yii\web\View */

$this->title = Yii::$app->params['generalTitle'];
$this->params['breadcrumbs'][] = ['label' => 'New Staff Claim List', 'url' => ['index']];
$this->params['breadcrumbs'][] = '';
$url = \yii\helpers\Url::home(true);
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?= \yii\helpers\Html::a('New', ['create'], ['class' => 'btn btn-info push-right', 'data' => [
                    'confirm' => 'Are you sure you want to create a New Fund Request?',
                    'method' => 'post',
                ],]) ?>
            </div>
        </div>
    </div>
</div>


<?php
if (Yii::$app->session->hasFlash('success')) {
    print ' <div class="alert alert-success alert-dismissable">
                             <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Success!</h5>
 ';
    echo Yii::$app->session->getFlash('success');
    print '</div>';
} else if (Yii::$app->session->hasFlash('error')) {
    print ' <div class="alert alert-danger alert-dismissable">
                                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <h5><i class="icon fas fa-check"></i> Error!</h5>
                                ';
    echo Yii::$app->session->getFlash('error');
    print '</div>';
}
?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Staff Claims List</h3>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered dt-responsive table-hover" id="table">
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" value="<?= $url ?>" id="url" />
<?php

$script = <<<JS

    $(function(){
         /*Data Tables*/
         
         // $.fn.dataTable.ext.errMode = 'throw';
        const url = $('#url').val();
    
          $('#table').DataTable({
           
            //serverSide: true,  
            ajax: url+'staff-claim/list',
            paging: true,
            columns: [
                { title: 'No' ,data: 'No'},
                { title: 'Employee No' ,data: 'Employee_No'},
                { title: 'Employee Name' ,data: 'Employee_Name'},
                { title: 'Job Title' ,data: 'Job_Title'},
                { title: 'Department' ,data: 'Global_Dimension_1_Code'},
                { title: 'Branch Code' ,data: 'Global_Dimension_2_Code'},
                { title: 'Claim Type', data: 'Claim_Type' },
                { title: 'Total Surrender Amount', data: 'Total_Surrender_Amount' },
                { title: 'Claim Pay Mode', data: 'Claim_Pay_Mode' },
                { title: 'Claim Paying Account', data: 'Claim_Paying_Account' },
                { title: 'Claim Payment Tx No', data: 'Claim_Payment_Tx_No' },
                { title: 'Status', data: 'Status' },
                { title: 'Claim Posted', data: 'Claim_Posted' },
                { title: 'Claim Posted By', data: 'Claim_Posted_By' },
                { title: 'Claim Posted Date', data: 'Claim_Posted_Date' },
                { title: 'Action', data: 'Action' },
               
            ] ,                              
           language: {
                "loadingRecords": "Please wait - loading...",
                "zeroRecords": "No Records to Display",
            },
            
            order : [[ 0, "desc" ]]
            
           
       });
        
       //Hidding some 
       var table = $('#table').DataTable();
       table.columns([0,5,8,9,10,12,13]).visible(false);
    
    /*End Data tables*/
        $('#table').on('click','tr', function(){
            /*Do any event delegation related tasks in dtable here*/
        });
    });
        
JS;

$this->registerJs($script);

$style = <<<CSS
   
CSS;

$this->registerCss($style);
