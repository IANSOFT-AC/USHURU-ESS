<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 6:09 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Staff Claim - ' . $model->No;
$this->params['breadcrumbs'][] = ['label' => 'Staff Claim', 'url' => ['/fund-requisition']];
$this->params['breadcrumbs'][] = ['label' => ' Staff Claim Card', 'url' => ['view', 'No' => $model->No]];
//Yii::$app->recruitment->printrr($document);


?>


<div class="row">
    <div class="col-md-4">

        <?= ($model->Status == 'Open') ? Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req', ['send-for-approval'], [
            'class' => 'btn btn-app submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to send this document for approval?',
                'params' => [
                    'recordID' => $recordID,
                ],
                'method' => 'get',
            ],
            'title' => 'Submit Leave Approval'

        ]) : '' ?>


        <?php ($model->Status == 'Pending_Approval') ? Html::a('<i class="fas fa-times"></i> Cancel Approval Req.', ['cancel-request'], [
            'class' => 'btn btn-app submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to cancel this document approval request?',
                'params' => [
                    'recordID' => $recordID,
                ],
                'method' => 'get',
            ],
            'title' => 'Cancel Leave Approval Request'

        ]) : '' ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4">

        <?= ($model->Status == 'New') ? Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req', ['send-for-approval', 'employeeNo' => Yii::$app->user->identity->{'Employee_No'}], [
            'class' => 'btn btn-app submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to send Fund Requisition request for approval?',
                'params' => [
                    'No' => $model->No,
                    'employeeNo' => Yii::$app->user->identity->{'Employee_No'},
                ],
                'method' => 'get',
            ],
            'title' => 'Make an  Approval Request'

        ]) : '' ?>


        <?= ($model->Status == 'Pending_Approval') ? Html::a('<i class="fas fa-times"></i> Cancel Approval Req.', ['cancel-request'], [
            'class' => 'btn btn-app submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to cancel Fund Requisition approval request?',
                'params' => [
                    'No' => $model->No,
                ],
                'method' => 'get',
            ],
            'title' => 'Cancel Approval Request'

        ]) : '' ?>



        <?php Html::a('<i class="fas fa-file-pdf"></i> Print Requisition', ['print-requisition'], [
            'class' => 'btn btn-app ',
            'data' => [
                'confirm' => 'Print Requisition?',
                'params' => [
                    'No' => $model->No,
                ],
                'method' => 'get',
            ],
            'title' => 'Print Claim.'

        ]) ?>


    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card-info">
            <div class="card-header">
                <h3>Staff Claim Card </h3>
            </div>



        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">




                <h3 class="card-title">Claim No : <?= $model->No ?></h3>



                <?php
                if (Yii::$app->session->hasFlash('success')) {
                    print ' <div class="alert alert-success alert-dismissable">
                                 ';
                    echo Yii::$app->session->getFlash('success');
                    print '</div>';
                } else if (Yii::$app->session->hasFlash('error')) {
                    print ' <div class="alert alert-danger alert-dismissable">
                                 ';
                    echo Yii::$app->session->getFlash('error');
                    print '</div>';
                }
                ?>
            </div>
            <div class="card-body">


                <?php $form = ActiveForm::begin(); ?>


                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">

                            <?= $form->field($model, 'No')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Employee_No')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Employee_Name')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Job_Title')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Global_Dimension_2_Code')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Claim_Type')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Description')->textarea(['readonly' => true, 'disabled' => true]) ?>


                        </div>
                        <div class="col-md-6">

                            <?= $form->field($model, 'Created_By')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Total_Surrender_Amount')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Date')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Pending_Approvals_Ext')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Approvers')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Status')->textInput(['readonly' => true]) ?>





                        </div>
                    </div>
                </div>




                <?php ActiveForm::end(); ?>



            </div>
        </div>
        <!--end details card-->


        <!--Objectives card -->








        <div class="card">
            <div class="card-header">
                <div class="card-title"> Staff Claim Detail</div>
            </div>
            <div class="card-body">
                <?php
                if (property_exists($document->SSStaffClaimDetails, 'SS_Staff_Claim_Details')) { //show Lines 
                ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td class="text text-bold text-bold">Expense Code</td>
                                <td class="text text-bold text-bold">Expense</td>
                                <td class="text text-bold text-bold">Account_Name</td>
                                <td class="text text-bold text-bold">Date</td>
                                <td class="text text-bold text-bold">Claim_Quantity</td>
                                <td class="text text-bold text-bold">Claim_Unit_Cost</td>
                                <td class="text text-bold text-bold">Actual_Spent</td>
                                <td class="text text-bold text-bold">Branch</td>


                            </tr>
                        </thead>
                        <tbody>
                            <?php


                            foreach ($document->SSStaffClaimDetails->SS_Staff_Claim_Details as $obj) :
                            ?>
                                <tr>

                                    <td><?= !empty($obj->Expense_Code) ? $obj->Expense_Code : 'Not Set' ?></td>
                                    <td><?= !empty($obj->Expense) ? $obj->Expense : 'Not Set' ?></td>
                                    <td><?= !empty($obj->Account_Name) ? $obj->Account_Name : 'Not Set' ?></td>
                                    <td><?= !empty($obj->Date) ? $obj->Date : 'Not Set' ?></td>
                                    <td><?= !empty($obj->Claim_Quantity) ? $obj->Claim_Quantity : 'Not Set' ?></td>
                                    <td><?= !empty($obj->Claim_Unit_Cost) ? $obj->Claim_Unit_Cost : 'Not Set' ?></td>
                                    <td><?= !empty($obj->Actual_Spent) ? $obj->Actual_Spent : 'Not Set' ?></td>
                                    <td><?= !empty($obj->Global_Dimension_2_Code) ? $obj->Global_Dimension_2_Code : 'Not Set' ?></td>

                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>

        <!--objectives card -->








        </>
    </div>

    <!--My Bs Modal template  --->

    <div class="modal fade bs-example-modal-lg bs-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">??</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel" style="position: absolute">Fund Requisition Management</h4>
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


    <?php

    $script = <<<JS

    $(function(){
      
        
     /*Deleting Records*/
     
     $('.delete, .delete-objective').on('click',function(e){
         e.preventDefault();
           var secondThought = confirm("Are you sure you want to delete this record ?");
           if(!secondThought){//if user says no, kill code execution
                return;
           }
           
         var url = $(this).attr('href');
         $.get(url).done(function(msg){
             $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
         },'json');
     });
      
    
    /*Evaluate KRA*/
        $('.evalkra').on('click', function(e){
             e.preventDefault();
            var url = $(this).attr('href');
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 

        });
        
        
      //Add a training plan
    
     $('.add-objective, .update-objective').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
     
     
     //Update a training plan
    
     $('.update-trainingplan').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
     
     
     //Update/ Evalute Employeeappraisal behaviour -- evalbehaviour
     
      $('.evalbehaviour').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
      
      /*Add learning assessment competence-----> add-learning-assessment */
      
      
      $('.add-learning-assessment').on('click',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        console.log('clicking...');
        $('.modal').modal('show')
                        .find('.modal-body')
                        .load(url); 

     });
      
      
     
      
      
      
    
    /*Handle modal dismissal event  */
    $('.modal').on('hidden.bs.modal',function(){
        var reld = location.reload(true);
        setTimeout(reld,1000);
    }); 
        
    /*Parent-Children accordion*/ 
    
    $('tr.parent').find('span').text('+');
    $('tr.parent').find('span').css({"color":"red", "font-weight":"bolder"});    
    $('tr.parent').nextUntil('tr.parent').slideUp(1, function(){});    
    $('tr.parent').click(function(){
            $(this).find('span').text(function(_, value){return value=='-'?'+':'-'}); //to disregard an argument -event- on a function use an underscore in the parameter               
            $(this).nextUntil('tr.parent').slideToggle(100, function(){});
     });
    
    /*Divs parenting*/
    
     $('p.parent').find('span').text('+');
    $('p.parent').find('span').css({"color":"red", "font-weight":"bolder"});    
    $('p.parent').nextUntil('p.parent').slideUp(1, function(){});    
    $('p.parent').click(function(){
            $(this).find('span').text(function(_, value){return value=='-'?'+':'-'}); //to disregard an argument -event- on a function use an underscore in the parameter               
            $(this).nextUntil('p.parent').slideToggle(100, function(){});
     });
    
        //Add Career Development Plan
        
        $('.add-cdp').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
           
            
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });//End Adding career development plan
         
         /*Add Career development Strength*/
         
         
        $('.add-cds').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
            
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });
         
         /*End Add Career development Strength*/
         
         
         /* Add further development Areas */
         
            $('.add-fda').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
                       
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });
         
         /* End Add further development Areas */
         
         /*Add Weakness Development Plan*/
             $('.add-wdp').on('click',function(e){
            e.preventDefault();
            var url = $(this).attr('href');
                       
            console.log('clicking...');
            $('.modal').modal('show')
                            .find('.modal-body')
                            .load(url); 
            
         });
         /*End Add Weakness Development Plan*/

         //Change Action taken

         $('select#probation-action_taken').on('change',(e) => {

            const key = $('input[id=Key]').val();
            const Employee_No = $('input[id=Employee_No]').val();
            const Appraisal_No = $('input[id=Appraisal_No]').val();
            const Action_Taken = $('#probation-action_taken option:selected').val();
           
              

            /* var data = {
                "Action_Taken": Action_Taken,
                "Appraisal_No": Appraisal_No,
                "Employee_No": Employee_No,
                "Key": key

             } 
            */
            $.get('./takeaction', {"Key":key,"Appraisal_No":Appraisal_No, "Action_Taken": Action_Taken,"Employee_No": Employee_No}).done(function(msg){
                 $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
                });


            });
    
        
    });//end jquery

    

        
JS;

    $this->registerJs($script);

    $style = <<<CSS
    p span {
        margin-right: 50%;
        font-weight: bold;
    }

    table td:nth-child(11), td:nth-child(12) {
                text-align: center;
    }
    
    /* Table Media Queries */
    
     @media (max-width: 500px) {
          table td:nth-child(2),td:nth-child(3),td:nth-child(7),td:nth-child(8),td:nth-child(9),td:nth-child(10), td:nth-child(11) {
                display: none;
        }
    }
    
     @media (max-width: 550px) {
          table td:nth-child(2),td:nth-child(7),td:nth-child(8),td:nth-child(9),td:nth-child(10), td:nth-child(11) {
                display: none;
        }
    }
    
    @media (max-width: 650px) {
          table td:nth-child(2),td:nth-child(7),td:nth-child(8),td:nth-child(9),td:nth-child(10), td:nth-child(11) {
                display: none;
        }
    }


    @media (max-width: 1100px) {
          table td:nth-child(2),td:nth-child(7),td:nth-child(8),td:nth-child(9),td:nth-child(10), td:nth-child(11), td:nth-child(12),td:nth-child(13) {
                display: none;
        }
    }
CSS;

    $this->registerCss($style);
