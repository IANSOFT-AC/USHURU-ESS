<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 12:13 PM
 */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$absoluteUrl = \yii\helpers\Url::home(true);
?>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">

                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>

            </div>
            <div class="card-body">

                <?php

                $form = ActiveForm::begin([
                    //'id' => $model->formName(),
                    //'enableAjaxValidation' => true,
                ]);






                ?>
                <div class="row">
                    <div class="row col-md-12">



                        <div class="col-md-6">

                            <?= $form->field($model, 'Key')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'No')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Employee_No')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Employee_Name')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Job_Title')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Global_Dimension_2_Code')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Claim_Type')->dropDownList([
                                '_blank_' => '_blank_',
                                'Training' => 'Training',
                                'Overtime' => 'Overtime',
                                'Laptop' => 'Laptop'
                            ], ['prompt' => 'Select ...']) ?>
                            <?= $form->field($model, 'Description')->textarea(['maxlength' => 250, 'rows' => 2]) ?>



                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'Created_By')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Total_Surrender_Amount')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Date')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Pending_Approvals_Ext')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Approvers')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Status')->textInput(['readonly' => true]) ?>


                            <!--                            <p class="parent"><span>+</span>-->

                            </p>

                        </div>

                    </div>

                </div>


                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <!-- Lines card -->

        <div class="card">
            <div class="card-header">
                <div class="card-title"> Staff Claim Detail</div>
                <div class="card-tools">
                    <?= ($model->Claim_Type == 'Overtime') ? Html::a(
                        '<i class="fa fa-plus-square"></i> New Line',
                        ['add-line'],
                        [
                            'class' => 'add btn btn-outline-info',
                            'data-no' => $model->No,
                            'data-service' => 'StaffClaimLines'
                        ]
                    ) : '' ?>
                </div>
            </div>
            <div class="card-body">
                <?php
                if (property_exists($document->SSStaffClaimDetails, 'SS_Staff_Claim_Details')) { //show Lines 
                ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td class="text text-bold text-bold text-info">Expense Code</td>
                                <td class="text text-bold text-bold">Expense</td>
                                <td class="text text-bold text-bold">Account_Name</td>
                                <td class="text text-bold text-bold text-info">Date</td>
                                <td class="text text-bold text-bold text-info">Claim_Quantity</td>
                                <td class="text text-bold text-bold text-info">Claim_Unit_Cost</td>
                                <td class="text text-bold text-bold text-info">Actual_Spent</td>
                                <td class="text text-bold text-bold">Branch</td>
                                <td class="text text-bold text-bold">Action</td>


                            </tr>
                        </thead>
                        <tbody>
                            <?php


                            foreach ($document->SSStaffClaimDetails->SS_Staff_Claim_Details as $obj) :

                                $deleteLink = Html::a('<i class="fa fa-trash"></i>', ['delete-line'], [
                                    'class' => 'del btn btn-outline-danger btn-xs',
                                    'data-key' => $obj->Key,
                                    'data-service' => 'StaffClaimLines'
                                ]);

                            ?>
                                <tr>

                                    <td data-key="<?= $obj->Key ?>" data-name="Expense_Code" data-service="StaffClaimLines" ondblclick="addDropDown(this,'expense-codes')"><?= !empty($obj->Expense_Code) ? $obj->Expense_Code : 'Not Set' ?></td>
                                    <td><?= !empty($obj->Expense) ? $obj->Expense : 'Not Set' ?></td>
                                    <td><?= !empty($obj->Account_Name) ? $obj->Account_Name : 'Not Set' ?></td>
                                    <td data-key="<?= $obj->Key ?>" data-name="Date" data-service="StaffClaimLines" ondblclick="addInput(this,'date')"><?= !empty($obj->Date) ? $obj->Date : 'Not Set' ?></td>
                                    <td data-key="<?= $obj->Key ?>" data-name="Claim_Quantity" data-service="StaffClaimLines" ondblclick="addInput(this,'number')"><?= !empty($obj->Claim_Quantity) ? $obj->Claim_Quantity : 'Not Set' ?></td>
                                    <td data-key="<?= $obj->Key ?>" data-name="Claim_Unit_Cost" data-service="StaffClaimLines" ondblclick="addInput(this,'number')"><?= !empty($obj->Claim_Unit_Cost) ? $obj->Claim_Unit_Cost : 'Not Set' ?></td>
                                    <td data-key="<?= $obj->Key ?>" data-name="Actual_Spent" data-service="StaffClaimLines" ondblclick="addInput(this,'number')"><?= !empty($obj->Actual_Spent) ? $obj->Actual_Spent : 'Not Set' ?></td>
                                    <td><?= !empty($obj->Global_Dimension_2_Code) ? $obj->Global_Dimension_2_Code : 'Not Set' ?></td>
                                    <td><?= $deleteLink ?></td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>

    </div>
</div>



<!--My Bs Modal template  --->

<div class="modal fade bs-example-modal-lg bs-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel" style="position: absolute">Imprest Management</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>

        </div>
    </div>
</div>
<input type="hidden" name="absolute" value="<?= $absoluteUrl ?>">
<?php
$script = <<<JS

    $('#fundrequisition-claim_type').change((e) => {
        globalFieldUpdate('fundrequisition','staff-claim','Claim_Type', e);
        setTimeout(() => {
            location.reload(true);
        },200);
    });
 
    $('#fundrequisition-description').change((e) => {
        globalFieldUpdate('fundrequisition','staff-claim','Description', e);
    });

    // Trigger Creation of a line
$('.add').on('click',function(e){
            e.preventDefault();
            let url = $(this).attr('href');
           
            let data = $(this).data();
            const payload = {
                'Document_No': data.no,
                'Service': data.service
            };
            //console.log(payload);
            //return;
            $('a.add').text('Inserting...');
            $('a.add').attr('disabled', true);
            $.get(url, payload).done((msg) => {
                console.log(msg);
                setTimeout(() => {
                    location.reload(true);
                },1500);
            });
        });


        $('.del').on('click',function(e){
            e.preventDefault();
            if(confirm('Are you sure about deleting this record?'))
            {
                let data = $(this).data();
                let url = $(this).attr('href');
                let Key = data.key;
                let Service = data.service;
                const payload = {
                    'Key': Key,
                    'Service': Service
                };
                $(this).text('Deleting...');
                $(this).attr('disabled', true);
                $.get(url, payload).done((msg) => {
                    console.log(msg);
                    setTimeout(() => {
                        location.reload(true);
                    },3000);
                });
            }
            
    });
     
     
     
JS;

$this->registerJs($script);
