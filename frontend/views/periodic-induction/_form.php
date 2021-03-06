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
//Yii::$app->recruitment->printrr(Yii::$app->user->identity->{'Employee No_'});

?>


<?php if (Yii::$app->session->hasFlash('success')) : ?>
    <div class="alert alert-success"><?= Yii::$app->session->getFlash('success') ?></div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('error')) : ?>
    <div class="alert alert-danger"><?= Yii::$app->session->getFlash('error') ?></div>
<?php endif; ?>


<div class="card">
    <div class="actions card-body">
        <?= ($model->Status == 'Inductee' && $model->Employee_No == Yii::$app->user->identity->{'Employee No_'}) ? Html::a('<i class="fas fa-forward"></i>Next Section', ['next-section', 'employeeNo' => Yii::$app->user->identity->employee[0]->No], [
            'class' => 'btn btn-app bg-success btn-success submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to send induction to next section?',
                'params' => [
                    'No' => $model->No,
                    'Key' => $model->Key,
                ],
                'method' => 'post',
            ],
            'title' => 'Send Induction To Next Section.'

        ]) : '' ?>


        <?= ($model->Status == 'Inductor' && $model->Action_ID == Yii::$app->user->identity->{'Employee No_'}) ? Html::a('<i class="fas fa-check"></i> Approve.', ['approve-induction'], [
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

        <?= ($model->Status == 'Inductee' && $model->Employee_No == Yii::$app->user->identity->{'Employee No_'}) ? Html::a('<i class="fas fa-paper-plane"></i> Send Approval Req', ['send-for-approval'], [
            'class' => 'btn btn-app submitforapproval',
            'data' => [
                'confirm' => 'Are you sure you want to send imprest request for approval?',
                'params' => [
                    'No' => $model->No
                ],
                'method' => 'get',
            ],
            'title' => 'Submit for Approval'

        ]) : '' ?>



    </div>

</div>



<div class="row">

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= Html::encode($this->title) ?></h3>
                <div class="card-tools">
                    <p class="text"><span class="text-bold">Next Section >></span><b class="mx-1  text-primary"><?= $model->nextSection ?></b></p>
                </div>
            </div>

            <div class="card-body">



                <?php

                $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="row col-md-12">



                        <div class="col-md-6">

                            <?= $form->field($model, 'No')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Key')->hiddenInput()->label(false) ?>
                            <?= $form->field($model, 'Employee_No')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Employee_Name')->textInput(['readonly' =>  true]) ?>
                            <?= $form->field($model, 'Global_Dimension_1_Code')->textInput(['readonly' =>  true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Global_Dimension_2_Code')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Global_Dimension_3_Code')->textInput(['readonly' => true, 'disabled' => true]) ?>


                        </div>

                        <div class="col-md-6">
                            <?= $form->field($model, 'Status')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'CEO_Comments')->textarea(['rows' => 2]) ?>
                            <?= $form->field($model, 'HOO_Comments')->textarea(['rows' => 2]) ?>
                            <?= $form->field($model, 'HOF_Comments')->textarea(['rows' => 2]) ?>
                            <?= $form->field($model, 'Action_Section')->textInput(['readonly' => true]) ?>
                            <?= $form->field($model, 'Action_ID')->textInput(['readonly' => true, 'disabled' => true]) ?>


                        </div>




                    </div>
                </div>



                <?php ActiveForm::end(); ?>
            </div>
        </div>


        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h3>Employee Induction Lines</h3>
                </div>
                <div class="card-tools">
                    <?php Html::a('<i class="fa fa-plus-square"></i> New Induction Line', ['add-line'], [
                        'class' => 'add btn btn-outline-info',
                        'data-no' => $model->No,
                        'data-service' => 'Employee_Induction_Overall_In'
                    ]) ?>
                </div>
            </div>

            <div class="card-body">
                <?php if (property_exists($document->Employee_Induction_Overall_Pe, 'Employee_Induction_Overall_Pe')) { //show Lines 
                ?>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td></td>
                                    <!-- <td class="text-bold">Induction_No</td> -->
                                    <td class="text-bold">Section</td>
                                    <td class="text-bold">Expected Start Date</td>
                                    <td class="text-bold">Expected End Date</td>
                                    <td class="text-bold">Expected Start Time</td>
                                    <td class="text-bold">Expected End Time</td>
                                    <td class="text-bold">Attended</td>

                                    <td class="text-bold">Reason for Failure</td>

                                    <td class="text-bold">Employee comments</td>
                                    <td class="text-bold">Inductor Comments</td>

                                    <!-- <td class="text-bold">Action</td> -->

                                </tr>
                            </thead>
                            <tbody>
                                <?php


                                foreach ($document->Employee_Induction_Overall_Pe->Employee_Induction_Overall_Pe as $obj) :

                                    $deleteLink = Html::a('<i class="fa fa-trash"></i>', ['delete-line'], [
                                        'class' => 'del btn btn-outline-danger btn-xs',
                                        'data-key' => $obj->Key,
                                        'data-service' => 'InductionOverallPE'
                                    ]);
                                ?>
                                    <tr class="parent">
                                        <td><span>+</span></td>

                                        <td data-key="<?= $obj->Key ?>" data-name="Section" data-service="InductionOverallPE"><?= !empty($obj->Section) ? $obj->Section : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Expected_Start_Date" data-service="InductionOverallPE" ondblclick="addInput(this,'date')"><?= !empty($obj->Expected_Start_Date) ? $obj->Expected_Start_Date : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Expected_End_Date" data-service="InductionOverallPE" ondblclick="addInput(this,'date')"><?= !empty($obj->Expected_End_Date) ? $obj->Expected_End_Date : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Expected_Start_Time" data-service="InductionOverallPE" ondblclick="addInput(this,'time')"><?= !empty($obj->Expected_Start_Time) ? Yii::$app->formatter->asTime($obj->Expected_Start_Time) : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Expected_End_Time" data-service="InductionOverallPE" ondblclick="addInput(this,'time')"><?= !empty($obj->Expected_End_Time) ? Yii::$app->formatter->asTime($obj->Expected_End_Time) : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Attended" data-service="InductionOverallPE" ondblclick="addDropDown(this,'attended')"><?= !empty($obj->Attended) ? $obj->Attended : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Reason_for_Failure" data-service="InductionOverallPE" ondblclick="addInput(this)"><?= !empty($obj->Reason_for_Failure) ? $obj->Reason_for_Failure : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Employee_comments" data-service="InductionOverallPE" ondblclick="addInput(this)"><?= !empty($obj->Employee_comments) ? $obj->Employee_comments : '' ?></td>
                                        <td data-key="<?= $obj->Key ?>" data-name="Inductor_Comments" data-service="InductionOverallPE" ondblclick="addInput(this)"><?= !empty($obj->Inductor_Comments) ? $obj->Inductor_Comments : '' ?></td>


                                        <!-- <td><?= $deleteLink ?></td> -->
                                    </tr>
                                    <tr class="child">
                                        <td colspan="11">
                                            <div class="table-responsive">
                                                <table class="table table-hover ">
                                                    <thead>
                                                        <tr>
                                                            <td>#</td>
                                                            <td class="text-bold">Induction Item</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php

                                                        //print_r($model->getLines($obj->Line_No)); 
                                                        $counter = 0;
                                                        if (is_array($model->getLines($obj->Line_No))) :
                                                            foreach ($model->getLines($obj->Line_No) as $ln) :
                                                                $counter++;
                                                        ?>

                                                                <tr>
                                                                    <td><?= $counter ?></td>
                                                                    <td><?= $ln->Induction_Item ?></td>
                                                                </tr>

                                                        <?php endforeach;
                                                        endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                <?php } ?>
            </div>
        </div>






    </div>
</div>



<input type="hidden" name="url" value="<?= $absoluteUrl ?>">
<input type="hidden" name="absolute" value="<?= $absoluteUrl ?>">
<?php
$script = <<<JS
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
    
     
JS;

$this->registerJs($script);
