<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 6:09 PM
 */

use yii\bootstrap4\Html as Bootstrap4Html;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Induction - ' . $model->Application_No;
$this->params['breadcrumbs'][] = ['label' => 'Training List', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Training Card', 'url' => ['view', 'No' => $model->Application_No]];
/** Status Sessions */

?>


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


<div class="row actions">

    <?= (!empty($model->Status) && $model->Status == 'Inductee') ? Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req', ['send-for-approval'], [
        'class' => 'btn btn-app submitforapproval',
        'data' => [
            'confirm' => 'Are you sure you want to send imprest request for approval?',
            'params' => [
                'No' => $model->Application_No
            ],
            'method' => 'get',
        ],
        'title' => 'Submit for Approval'

    ]) : '' ?>


</div>

<?= (!empty($model->Status) && $model->Status == 'Inductor' && $model->Action_ID == Yii::$app->user->identity->{'Employee No_'}) ? Html::a('<i class="fas fa-check"></i> Approve.', ['approve-induction'], [
    'class' => 'btn btn-app bg-success mx-1',
    'data' => [
        'confirm' => 'Are you sure you want to approve this document?',
        'params' => [
            'No' => $model->No,
        ],
        'method' => 'get',
    ],
    'title' => 'Approve Document.'

]) : '' ?>


<?= Bootstrap4Html::a('<i class="fas fa-edit"></i> update', ['update'], [
    'class' => 'btn btn-app bg-ushuruprimary text-white ',
    'data' => [
        'params' => [
            'Key' => $model->Key
        ],
        'method' => 'get',
    ],
    'title' => 'Update Document'

])  ?>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-ushurusecondary">
            <div class="card-header">
                <h3>Training Card </h3>
            </div>



        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">




                <h3 class="card-title">Training No : <?= $model->Application_No ?></h3>



            </div>
            <div class="card-body">


                <?php $form = ActiveForm::begin(); ?>


                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">

                            <?= $form->field($model, 'Application_No')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>
                            <?= $form->field($model, 'Training_Need')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Date_of_Application')->textInput(['readonly' =>  true]) ?>
                            <?= $form->field($model, 'Training_Calender')->textInput(['readonly' =>  true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Training_Need_Description')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Employee_No')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Employee_Name')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Job_Group')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Job_Title')->textInput(['readonly' => true, 'disabled' => true]) ?>


                            <p class="parent"><span>+</span>




                            </p>


                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'Status')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Start_Date')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'End_Date')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Period')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Expected_Cost')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Trainer')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Exceeds_Expected_Trainees')->checkbox([$model->Training_Start_Date, 'Training_Start_Date'], ['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Training_Start_Date')->textInput(['readonly' => true, 'disabled' => true]) ?>

                            <p class="parent"><span>+</span>



                            </p>



                        </div>
                    </div>
                </div>








            </div>
        </div>
        <!--end header card-->



        <!-- Card Lines -->

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h3>Training Feedback</h3>
                </div>

            </div>

            <div class="card-body">
                <?= $form->field($model, 'Training_Feedback')->textarea(['rows' => 2]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
        <!-- End Lines Card -->













        </>
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


    <?php

    $script = <<<JS

    $(function(){  
    
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
    
    
        
    });//end jquery

    

        
JS;

    $this->registerJs($script);

    $style = <<<CSS
   
    
    
CSS;

    $this->registerCss($style);
