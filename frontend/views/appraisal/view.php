<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/24/2020
 * Time: 6:09 PM
 */

use Mpdf\Writer\BookmarkWriter;
use yii\bootstrap4\Html as Bootstrap4Html;
use yii\bootstrap\Html as BootstrapHtml;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Performance Appraisal - ' . $model->Appraisal_No;
$this->params['breadcrumbs'][] = ['label' => 'Performance Management', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'Appraisal View', 'url' => ['view', 'Employee_No' => $model->Employee_No, 'Appraisal_No' => $model->Appraisal_No]];
/** Status Sessions */

Yii::$app->session->set('Review_Period', $model->Review_Period);
Yii::$app->session->set('Approval_Status', $model->Approval_Status);
Yii::$app->session->set('isSupervisor', $model->isSupervisor());
Yii::$app->session->set('isOverview', $model->isOverView());
Yii::$app->session->set('isAppraisee', $model->isAppraisee());

$absoluteUrl = \yii\helpers\Url::home(true);

//Yii::$app->recruitment->printrr($card);
?>

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
                                    <h5><i class="icon fas fa-times"></i> Error!</h5>
                                ';
    echo Yii::$app->session->getFlash('error');
    print '</div>';
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="card-ushurusecondary">
            <div class="card-header">
                <h3>Performance Appraisal Card </h3>
            </div>

            <!-- Action body -->
            <?php
            if ($model->Review_Period == 'OBJ SETTING') {
                echo $this->render('_goalsetting_actions', ['model' => $model]);
            } else {
                echo $this->render('_appraisal_actions', ['model' => $model]);
            }
            ?>
            <!--End card body-->


        </div>
    </div>
</div>

<!--Appraisal Indicator Steps-->

<div class="row">
    <div class="col-md-12">
        <?= $this->render('_steps', ['model' => $model]); ?>
    </div>
</div>

<!--/End Steps-->

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Appraisal : <?= $model->Appraisal_No ?></h3>
            </div>
            <div class="card-body">


                <?php $form = ActiveForm::begin(); ?>


                <div class="row">
                    <div class=" row col-md-12">
                        <div class="col-md-6">

                            <?= $form->field($model, 'Appraisal_No')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Employee_No')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Employee_Name')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Global_Dimension_1_Code')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Global_Dimension_2_Code')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Level_Grade')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Job_Title')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Appraisal_Calendar')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Appraisal_Start_Date')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Supervisor_No')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Supervisor_Name')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Supervisor_User_Id')->textInput(['readonly' => true, 'disabled' => true]) ?>

                            <p class="parent"><span>+</span>





                            </p>


                        </div>
                        <div class="col-md-6">


                            <?= $form->field($model, 'Supervisor_Overall_Comments')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Supervisor_Rejection_Comments')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Overview_Manager')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Overview_Manager_Name')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Overview_Manager_UserID')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Over_View_Manager_Comments')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Overview_Rejection_Comments')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Review_Period')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Quarter')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Approval_Status')->textInput(['readonly' => true, 'disabled' => true]) ?>
                            <?= $form->field($model, 'Recomended_Action')->textInput(['readonly' => true, 'disabled' => true]) ?>


                            <p class="parent"><span>+</span>




                            </p>



                        </div>
                    </div>
                </div>

                <!-- Mid Year Overview comment shit -->




                <div class="row">

                    <div class="col-md-6">



                        <div class="card">

                            <div class="card-header">
                                <div class="card-title">
                                    Line Manager Comments
                                </div>
                            </div>
                            <div class="card-body">
                                <?= ($model->Approval_Status == 'Supervisor_Level') ? $form->field($model, 'Supervisor_Overall_Comments')->textArea(['rows' => 2, 'maxlength' => '140']) : '' ?>
                                <span class="text-success" id="confirmation-super">Comment Saved Successfully.</span>

                                <?= ($model->Approval_Status !== 'Supervisor_Level') ? $form->field($model, 'Supervisor_Overall_Comments')->textArea(['rows' => 2, 'readonly' => true, 'disabled' =>  true]) : '' ?>
                            </div>
                        </div>



                    </div>
                    <div class="col-md-6">



                        <div class="card">

                            <div class="card-header">
                                <div class="card-title">
                                    Overview Manager Comments
                                </div>
                            </div>
                            <div class="card-body">
                                <?= ($model->Approval_Status == 'Overview_Manager') ? $form->field($model, 'Over_View_Manager_Comments')->textArea(['rows' => 2, 'maxlength' => '140']) : '' ?>
                                <span class="text-success" id="confirmation">Comment Saved Successfully.</span>

                                <?= ($model->Approval_Status !== 'Overview_Manager') ? $form->field($model, 'Over_View_Manager_Comments')->textArea(['rows' => 2, 'readonly' => true, 'disabled' =>  true]) : '' ?>
                            </div>
                        </div>

                    </div>

                </div>



                <?php ActiveForm::end(); ?>



            </div>
        </div>
        <!--end details card-->


        <?php if (1 == 1) { //$model->Approval_Status !== 'Agreement_Level' 
        ?>
            <!--KRA CARD -->
            <div class="card-ushurusecondary">
                <div class="card-header">
                    <h4 class="card-title">Employee Appraisal Key Result Areas (KRAs)</h4>
                    <div class="card-tools">
                        <?= ($model->Review_Period == 'New' || $model->Approval_Status == 'Appraisee_Level') ? Html::a('<i class="fa fa-plus mr-2"></i> Add', ['appraisal/add-line'], [
                            'class' => 'add btn btn-sm btn-outline-light',
                            'data-Appraisal_No' => $model->Appraisal_No,
                            'data-service' => 'EmployeeAppraisalKRA',
                            'data-Line_No' => time(),
                            'data-Employee_No' => $model->Employee_No
                        ]) : '' ?>
                    </div>
                </div>

                <div class="card-body">

                    <?php if (property_exists($card->Appraisal_Objectives_KRAs, 'Appraisal_Objectives_KRAs')) { ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td class="text text-bold text-center text-info">Perspective Pillar</td>
                                    <td class="text text-bold text-center text-info">KRA Objective</td>
                                    <td class="text text-bold text-center text-info">Maximum Weight</td>
                                    <td class="text text-bold text-center">Total Weight</td>
                                    <td class="text text-bold text-center">Agreed Rating</td>
                                    <td class="text text-bold text-center">Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($card->Appraisal_Objectives_KRAs->Appraisal_Objectives_KRAs as $k) {

                                ?>
                                    <tr class="parent">
                                        <td><span>+</span></td>
                                        <td data-key="<?= $k->Key ?>" data-name="Perspective_Pillar" data-service="EmployeeAppraisalKRA" ondblclick="addDropDown(this,'perspective')"><?= !empty($k->Perspective_Pillar) ? $k->Perspective_Pillar : '' ?></td>
                                        <td data-key="<?= $k->Key ?>" data-name="KRA_Objective" data-service="EmployeeAppraisalKRA" ondblclick="addInput(this)"><?= !empty($k->KRA_Objective) ? $k->KRA_Objective : '' ?></td>
                                        <td data-key="<?= $k->Key ?>" data-name="Maximum_Weight" data-service="EmployeeAppraisalKRA" ondblclick="addInput(this,'number')"><?= $k->Maximum_Weight ?></td>
                                        <td><?= $k->Agreed_Rating ?? '' ?></td>
                                        <td><?= $k->Overall_Rating ?? '' ?></td>
                                        <td>

                                            <?= ($model->Review_Period == 'New' || $model->Approval_Status == 'Appraisee_Level') ?  Html::a(
                                                '<i class="fa fa-trash"></i> ',
                                                ['delete-line'],
                                                [
                                                    'class' => 'delete btn btn-outline-danger',
                                                    'title' => 'Delete this record.',
                                                    'data-key' => $k->Key,
                                                    'data-service' => 'EmployeeAppraisalKRA',

                                                ]
                                            )
                                                : ''
                                            ?>


                                        </td>
                                    </tr>
                                    <tr class="child">
                                        <td colspan="11">
                                            <table class="table table-hover table-borderless table-info">
                                                <thead>
                                                    <tr>

                                                        <td class="text text-bold text-center">KRA_Objective</td>
                                                        <td class="text text-bold text-center text-info">Activity</td>
                                                        <td class="text text-bold text-center text-info">Due_Date</td>
                                                        <td class="text text-bold text-center text-info">Target_KPI</td>
                                                        <td class="text text-bold text-center text-info">Weight</td>
                                                        <td class="text text-bold text-center text-info">Target_KPI_Status</td>
                                                        <td class="text text-bold text-center text-info">Target_Justification</td>


                                                        <th> <?=
                                                                Html::a(
                                                                    '<i class="fas fa-plus"></i>',
                                                                    ['appraisal/add-line'],
                                                                    [
                                                                        'class' => 'add btn btn-xs btn-success',
                                                                        'title' => 'Add Objective / KPI',
                                                                        'data-Appraisal_No' => $model->Appraisal_No,
                                                                        'data-service' => 'EmployeeAppraisalKPI',
                                                                        'data-Line_No' => time(),
                                                                        'data-Employee_No' => $model->Employee_No,
                                                                        'data-Kra_Line_No' =>  $k->Line_No
                                                                    ]
                                                                ) ?>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (is_array($model->getKPI($k->Line_No))) :
                                                        foreach ($model->getKPI($k->Line_No) as $kpi) :

                                                    ?>
                                                            <tr>
                                                                <td class="Kra_Objective" data-key="<?= $kpi->Key ?>" data-name="Kra_Objective" data-service="EmployeeAppraisalKPI"><?= $kpi->Kra_Objective ?></td>
                                                                <td data-key="<?= $kpi->Key ?>" data-name="Activity" data-service="EmployeeAppraisalKPI" ondblclick="addInput(this)" data-validate="Kra_Objective"><?= $kpi->Activity ?? '' ?></td>
                                                                <td data-key="<?= $kpi->Key ?>" data-name="Due_Date" data-service="EmployeeAppraisalKPI" ondblclick="addInput(this,'date')"><?= $kpi->Due_Date ?></td>
                                                                <td data-key="<?= $kpi->Key ?>" data-name="Target_KPI" data-service="EmployeeAppraisalKPI" ondblclick="addInput(this)"><?= $kpi->Target_KPI ?? '' ?></td>
                                                                <td data-key="<?= $kpi->Key ?>" data-name="Weight" data-service="EmployeeAppraisalKPI" ondblclick="addInput(this,'number')"><?= $kpi->Weight ?></td>
                                                                <td data-key="<?= $kpi->Key ?>" data-name="Target_KPI_Status" data-service="EmployeeAppraisalKPI" ondblclick="addDropDown(this,'kpi-status')"><?= $kpi->Target_KPI_Status ?></td>
                                                                <td data-key="<?= $kpi->Key ?>" data-name="Target_Justification" data-service="EmployeeAppraisalKPI" ondblclick="addInput(this)"><?= $kpi->Target_Justification ?? '' ?></td>
                                                                <td><?= ($model->Review_Period == 'New' || $model->Approval_Status == 'Appraisee_Level') ?  Html::a(
                                                                        '<i class="fa fa-trash"></i> ',
                                                                        ['delete-line'],
                                                                        [
                                                                            'class' => 'delete  btn-outline-danger',
                                                                            'title' => 'Delete this record.',
                                                                            'data-key' => $kpi->Key,
                                                                            'data-service' => 'EmployeeAppraisalKPI',

                                                                        ]
                                                                    )
                                                                        : ''; ?>

                                                                    <?=
                                                                    Html::a(
                                                                        '<i class="fas fa-check"></i>',
                                                                        [
                                                                            './kpi-rating',
                                                                            'Kpi_Line_No' => $kpi->Line_No,
                                                                            'Kra_Line_No' => $k->Line_No,
                                                                            'Appraisal_No' => $model->Appraisal_No,
                                                                            'Employee_No' => $model->Employee_No
                                                                        ],
                                                                        [
                                                                            'class' => 'rating btn btn-xs btn-success',
                                                                            'title' => 'Behaviour Rating',
                                                                            'data-Kpi_Line_No' => $kpi->Line_No,
                                                                            'data-Kra_Line_No' => $k->Line_No,
                                                                            'data-Appraisal_No' => $model->Appraisal_No,
                                                                            'data-Employee_No' => $model->Employee_No,
                                                                            'data-service' => 'AppraisalKPIRating'

                                                                        ]
                                                                    ) ?>


                                                                </td>
                                                            </tr>
                                                    <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                        </table>


                    <?php } ?>
                </div>
            </div>

            <!--END KRA CARD -->

            <!--Employee Appraisal  Competence --->

            <div class="card-ushurusecondary">
                <div class="card-header">
                    <h4 class="card-title">Employee Appraisal Competences</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>#</td>
                                <td class="text text-bold text-center text-info">Category</td>
                                <td>Maximum Weight</td>
                                <td>Overall Rating</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <?php if (property_exists($card->Appraisal_Competence, 'Appraisal_Competence')) { ?>

                            <tbody>
                                <?php foreach ($card->Appraisal_Competence->Appraisal_Competence as $comp) { ?>

                                    <tr class="parent">
                                        <td><span>+</span></td>
                                        <td><?= isset($comp->Category) ? $comp->Category : 'Not Set' ?></td>
                                        <td><?= isset($comp->Maximum_Weigth) ? $comp->Maximum_Weigth : 'Not Set' ?></td>
                                        <td><?= isset($comp->Overal_Rating) ? $comp->Overal_Rating : 'Not Set' ?></td>
                                        <td>

                                            <?= ($model->Review_Period == 'New' || $model->Approval_Status == 'Appraisee_Level') ?  Html::a(
                                                '<i class="fa fa-trash"></i> ',
                                                ['delete-line'],
                                                [
                                                    'class' => 'delete  btn-outline-danger mx-1',
                                                    'title' => 'Delete this record.',
                                                    'data-key' => $comp->Key,
                                                    'data-service' => 'StEmployeeAppraisalCompetence',

                                                ]
                                            )
                                                : ''; ?>

                                            <?=
                                            Html::a(
                                                '<i class="fas fa-plus"></i>',
                                                ['appraisal/add-line'],
                                                [
                                                    'class' => 'add btn btn-xs btn-success',
                                                    'title' => 'Add Competence Behaviours',
                                                    'data-Appraisal_Code' => $model->Appraisal_No,
                                                    'data-service' => 'EmployeeAppraisalBehaviours',
                                                    'data-Line_No' => time(),
                                                    'data-Employee_No' => $model->Employee_No,
                                                    'data-Competence_Line_No' =>  $comp->Line_No
                                                ]
                                            ) ?>
                                        </td>

                                    </tr>
                                    <tr class="child">
                                        <td colspan="11">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-borderless table-info">
                                                    <thead>
                                                        <tr>
                                                            <th colspan="15" style="text-align: center;">Employee Appraisal Behaviours</th>
                                                        </tr>
                                                        <tr>
                                                            <td class="text text-bold text-center">Appraisal Code</td>
                                                            <td class="text text-bold text-center">Competence Category</td>
                                                            <td class="text text-bold text-center">Behaviour Name</td>
                                                            <td class="text text-bold text-center">Applicable</td>
                                                            <!-- <td class="text text-bold text-center">Current_Proficiency_Level</td>
                                                     <td class="text text-bold text-center">Expected_Proficiency_Level</td> -->
                                                            <td class="text text-bold text-center">Behaviour Description</td>
                                                            <td class="text text-bold text-center">Weight</td>
                                                            <td class="text text-bold text-center">Action</td>

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (is_array($model->getAppraisalbehaviours($comp->Line_No))) {

                                                            foreach ($model->getAppraisalbehaviours($comp->Line_No) as $be) :  ?>
                                                                <tr>

                                                                    <td data-key="<?= $be->Key ?>" data-name="Appraisal_Code" data-service="EmployeeAppraisalBehaviours"><?= $be->Appraisal_Code ?></td>
                                                                    <td class="Competence_Category" data-key="<?= $be->Key ?>" data-name="Competence_Category" data-service="EmployeeAppraisalBehaviours" ondblclick="addInput(this)" data-validate="Appraisal_Code"><?= $be->Competence_Category ?? '' ?></td>
                                                                    <td data-key="<?= $be->Key ?>" data-name="Behaviour_Name" data-service="EmployeeAppraisalBehaviours" ondblclick="addInput(this)" data-validate="Competence_Category"><?= $be->Behaviour_Name ?? '' ?></td>
                                                                    <td data-key="<?= $be->Key ?>" data-name="Applicable" data-service="EmployeeAppraisalBehaviours" ondblclick="addInput(this,'checkbox')" data-validate="Competence_Category"><?= $be->Applicable ? 'Yes' : 'No' ?></td>
                                                                    <!-- <td data-key="<?= $be->Key ?>" data-name="Current_Proficiency_Level" data-service="EmployeeAppraisalBehaviours" ondblclick="addInput(this)"><?= $be->Current_Proficiency_Level ?? '' ?></td>
                                                                        <td data-key="<?= $be->Key ?>" data-name="Expected_Proficiency_Level" data-service="EmployeeAppraisalBehaviours" ondblclick="addInput(this)" data-validate="Competence_Category"><?= $be->Expected_Proficiency_Level ?? '' ?></td> -->
                                                                    <td data-key="<?= $be->Key ?>" data-name="Behaviour_Description" data-service="EmployeeAppraisalBehaviours" ondblclick="addTextarea(this)" data-validate="Competence_Category"><?= $be->Behaviour_Description ?? '' ?></td>
                                                                    <td data-key="<?= $be->Key ?>" data-name="Weight" data-service="EmployeeAppraisalBehaviours" ondblclick="addInput(this,'number')" data-validate="Competence_Category"><?= $be->Weight ?></td>
                                                                    <td>
                                                                        <?= ($model->Review_Period == 'New' || $model->Approval_Status == 'Appraisee_Level') ?  Html::a(
                                                                            '<i class="fa fa-trash"></i> ',
                                                                            ['delete-line'],
                                                                            [
                                                                                'class' => 'delete  btn-outline-danger mx-1',
                                                                                'title' => 'Delete this record.',
                                                                                'data-key' => $be->Key,
                                                                                'data-service' => 'EmployeeAppraisalBehaviours',

                                                                            ]
                                                                        )
                                                                            : ''; ?>

                                                                        <?=
                                                                        Html::a(
                                                                            '<i class="fas fa-check"></i>',
                                                                            [
                                                                                './behaviour-rating',
                                                                                'Appraisal_No' => $model->Appraisal_No,
                                                                                'Behaviour_Line_No' => $be->Line_No,
                                                                                'Competence_Line_No' => $comp->Line_No,
                                                                                'Employee_No' => $model->Employee_No
                                                                            ],
                                                                            [
                                                                                'class' => 'rating btn btn-xs btn-success',
                                                                                'title' => 'Behaviour Rating',
                                                                                'data-Appraisal_No' => $model->Appraisal_No,
                                                                                'data-Behaviour_Line_No' => $be->Line_No,
                                                                                'data-Competence_Line_No' => $comp->Line_No,
                                                                                'data-Employee_No' => $model->Employee_No,
                                                                                'data-service' => 'AppraisalBehaviourRating'

                                                                            ]
                                                                        ) ?>

                                                                    </td>

                                                                </tr>

                                                        <?php
                                                            endforeach;
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>

                                <?php } ?>
                            </tbody>
                    </table>


                <?php } ?>
                </div>
            </div>



        <?php } ?>





        <!---Areas_of_Further_Development-->

        <div class="card-ushurusecondary">
            <div class="card-header">
                <h4 class="card-title">Areas of Further Development</h4>

                <div class="card-tools">
                    <?= ($model->isAppraisee()) ?
                        Html::a(
                            '<i class="fas fa-plus"></i> Add Area of Further Development',
                            ['appraisal/add-line'],
                            [
                                'class' => 'add btn btn-xs btn-success',
                                'title' => 'Add Training Need',
                                'data-Appraisal_No' => $model->Appraisal_No,
                                'data-service' => 'FurtherDevAreas',
                                'data-Line_No' => time(),
                                'data-Employee_No' => $model->Employee_No,

                            ]
                        )
                        : '' ?>
                </div>

            </div>
            <div class="card-body">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <!-- <td>#</td> -->

                            <td class="text text-bold text-center">Employee No</td>
                            <td class="text text-bold text-center">Appraisal No</td>
                            <td class="text text-bold text-center text-info">Weakness</td>
                            <td class="text text-bold text-center text-info">Training Needed</td>
                            <td class="text text-bold text-center text-info">Support Needed</td>
                            <td class="text text-bold text-center text-info">Status Comment</td>
                            <td class="text text-bold text-center">Action</td>


                        </tr>
                    </thead>
                    <tbody>

                        <?php if (property_exists($card->Areas_of_Further_Development, 'Areas_of_Further_Development')) { ?>
                            <?php foreach ($card->Areas_of_Further_Development->Areas_of_Further_Development as $fda) { ?>
                                <tr>
                                    <!-- <td><span>+</span></td> -->
                                    <!--  <td><?php $fda->Line_No ?></td> -->
                                    <td><?= $fda->Employee_No ?></td>
                                    <td><?= $fda->Appraisal_No ?></td>
                                    <td data-key="<?= $fda->Key ?>" data-name="Weakness" data-service="FurtherDevAreas" ondblclick="addInput(this)"><?= !empty($fda->Weakness) ? $fda->Weakness : '' ?></td>
                                    <td data-key="<?= $fda->Key ?>" data-name="Training_Needed" data-service="FurtherDevAreas" ondblclick="addInput(this,'checkbox')"><?= $fda->Training_Needed ? 'Yes' : 'No' ?></td>
                                    <td data-key="<?= $fda->Key ?>" data-name="Support_Needed" data-service="FurtherDevAreas" ondblclick="addInput(this)"><?= !empty($fda->Support_Needed) ? $fda->Support_Needed : '' ?></td>
                                    <td data-key="<?= $fda->Key ?>" data-name="Status_Comment" data-service="FurtherDevAreas" ondblclick="addInput(this)"><?= !empty($fda->Status_Comment) ? $fda->Status_Comment : '' ?></td>

                                    <td>

                                        <?= ($model->Review_Period == 'New' || $model->Approval_Status == 'Appraisee_Level') ?  Html::a(
                                            '<i class="fa fa-trash"></i> ',
                                            ['delete-line'],
                                            [
                                                'class' => 'delete  btn-outline-danger',
                                                'title' => 'Delete this record.',
                                                'data-key' => $fda->Key,
                                                'data-service' => 'FurtherDevAreas',

                                            ]
                                        )
                                            : ''; ?>

                                    </td>
                                </tr>

                            <?php } ?>
                        <?php }  ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!--/-Areas_of_Further_Development-->


        <!----Training Needs-->
        <div class="card-ushurusecondary">


            <div class="card-header">
                <h4 class="card-title">Training Needs</h4> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="card-tools">
                    <?= ($model->isAppraisee() && $model->Approval_Status == 'Appraisee_Level') ?
                        Html::a(
                            '<i class="fas fa-plus"></i> Add Training Need',
                            ['appraisal/add-line'],
                            [
                                'class' => 'add btn btn-xs btn-success',
                                'title' => 'Add Training Need',
                                'data-Appraisal_No' => $model->Appraisal_No,
                                'data-service' => 'AppraisalTrainingNeeds',
                                'data-Line_No' => time(),
                                'data-Employee_No' => $model->Employee_No,

                            ]
                        )
                        : ''
                    ?>
                </div>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>

                                <td class="text text-bold text-center text-info">Training Category</td>
                                <th>Training Category Name</th>
                                <td class="text text-bold text-center text-info">Training Need Description</th>
                                <th>Recommended</th>
                                <th>Recommendation Justification</th>
                                <th>Proposed Trainer</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (property_exists($card->Training_Needs, 'Appraisal_Training_Needs')) { ?>

                                <?php foreach ($card->Training_Needs->Appraisal_Training_Needs as $wdp) :  ?>
                                    <tr>

                                        <td data-key="<?= $wdp->Key ?>" data-name="Category" data-service="AppraisalTrainingNeeds" ondblclick="addDropDown(this,'training-categories')" data-validate="Category_Name"><?= $wdp->Category ?? '' ?></td>
                                        <td class="Category_Name" data-key="<?= $wdp->Key ?>" data-name="Category_Name" data-service="AppraisalTrainingNeeds"><?= $wdp->Category_Name ?? '' ?></td>
                                        <td data-key="<?= $wdp->Key ?>" data-name="Training_Need_Description" data-service="AppraisalTrainingNeeds" ondblclick="addInput(this)" data-validate="Category_Name"><?= $wdp->Training_Need_Description ?? '' ?></td>

                                        <td data-key="<?= $wdp->Key ?>" data-name="Recommended" data-service="AppraisalTrainingNeeds" ondblclick="addInput(this,'checkbox')"><?= $wdp->Recommended ? 'Yes' : 'No' ?></td>
                                        <td data-key="<?= $wdp->Key ?>" data-name="Recommendation_Justification" data-service="AppraisalTrainingNeeds" ondblclick="addInput(this)"><?= $wdp->Recommendation_Justification ?? '' ?></td>
                                        <td data-key="<?= $wdp->Key ?>" data-name="Proposed_Trainer" data-service="AppraisalTrainingNeeds" ondblclick="addInput(this)"><?= $wdp->Proposed_Trainer ?? '' ?></td>
                                        <td><?= ($model->Review_Period == 'New' || $model->Approval_Status == 'Appraisee_Level') ?  Html::a(
                                                '<i class="fa fa-trash"></i> ',
                                                ['delete-line'],
                                                [
                                                    'class' => 'delete  btn-outline-danger',
                                                    'title' => 'Delete this record.',
                                                    'data-key' => $wdp->Key,
                                                    'data-service' => 'AppraisalTrainingNeeds',

                                                ]
                                            )
                                                : ''; ?>
                                        </td>
                                    </tr>
                                <?php
                                endforeach; ?>


                            <?php } ?>


                        </tbody>
                    </table>
                </div>
            </div>



        </div>


        <!--Training Needs-->






    </div>
</div>

<!--My Bs Modal template  --->

<div class="modal fade bs-example-modal-xl bs-modal-xl" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel" style="position: absolute">Performance Appraisal</h4>
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

<!-- Goal setting rejection by overview -->


<div id="backtolinemgr" style="display: none">

    <?= Html::beginForm(['appraisal/backtolinemgr'], 'post', ['id' => 'backtolinemgr-form']) ?>

    <?= Html::textarea('comment', '', ['placeholder' => 'Rejection Comment', 'row' => 4, 'class' => 'form-control', 'required' => true]) ?>

    <?= Html::input('hidden', 'Appraisal_No', '', ['class' => 'form-control']); ?>
    <?= Html::input('hidden', 'Employee_No', '', ['class' => 'form-control']); ?>


    <?= Html::submitButton('submit', ['class' => 'btn btn-warning', 'style' => 'margin-top: 10px']) ?>

    <?= Html::endForm() ?>
</div>




<div id="rejectmyappraisal" style="display: none">

    <?= Html::beginForm(['appraisal/mybacktolinemgr'], 'post', ['id' => 'mybacktolinemgr-form']) ?>

    <?= Html::textarea('comment', '', ['placeholder' => 'Rejection Comment', 'row' => 4, 'class' => 'form-control', 'required' => true]) ?>

    <?= Html::input('hidden', 'Appraisal_No', '', ['class' => 'form-control']); ?>
    <?= Html::input('hidden', 'Employee_No', '', ['class' => 'form-control']); ?>


    <?= Html::submitButton('submit', ['class' => 'btn btn-warning', 'style' => 'margin-top: 10px']) ?>

    <?= Html::endForm() ?>
</div>


<input type="hidden" name="url" value="<?= $absoluteUrl ?>">
<?php

$script = <<<JS

    $(function(){
      
    $('.rating').on('click', function(e){
        e.preventDefault();

        // Ping concerned rating url and create de ranting line
        let data = $(this).data();
        payloadContent = Object.entries(data);
         // convert object of arrays into a pure object
        payload = Object.assign(...payloadContent.map(([key, val]) => ({ [key.replace(/(^\w{1})|(\_+\w{1})/g, letter => letter.toUpperCase())]: val })));
            const res = fetch('./add-rating-line', {
            method: 'POST',
            headers: new Headers({
            Origin: 'http://localhost:8061/',
            "Content-Type": 'application/json',
            //'Content-Type': 'application/x-www-form-urlencoded'
            }),
            body: JSON.stringify({ ...payload })
        })
        .then(data => data.json())
        .then(result => {
            console.log(result);
        });

        // Process modal opening...
        const url = $(this).attr('href');
        console.log(url);
        $('.modal-body').html('<div class="text text-info">Loading ...</div>');
        //Display the rejection comment form
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
    
     


/*Send Goals Back to Line Mgr*/

         $('.rejectgoals').on('click', function(e){
        e.preventDefault();
        const form = $('#backtolinemgr').html(); 
        const Appraisal_No = $(this).attr('rel');
        const Employee_No = $(this).attr('rev');
        
        console.log('Appraisal No: '+Appraisal_No);
        console.log('Employee No: '+Employee_No);
        
        //Display the rejection comment form
        $('.modal').modal('show')
                        .find('.modal-body')
                        .append(form);
        
        //populate relevant input field with code unit required params
                
        $('input[name=Appraisal_No]').val(Appraisal_No);
        $('input[name=Employee_No]').val(Employee_No);
        
        //Submit Rejection form and get results in json    
        $('form#backtolinemgr').on('submit', function(e){
            e.preventDefault()
            const data = $(this).serialize();
            const url = $(this).attr('action');
            $.post(url,data).done(function(msg){
                    $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
        
                },'json');
        });
        
        
    });//End click event on  GOals rejection-button click







/*Send My Back to Line Mgr*/

        $('.rejectmyappraisal').on('click', function(e){
        e.preventDefault();
        const form = $('#rejectmyappraisal').html(); 
        const Appraisal_No = $(this).attr('rel');
        const Employee_No = $(this).attr('rev');
        
        console.log('Appraisal No: '+Appraisal_No);
        console.log('Employee No: '+Employee_No);
        
        //Display the rejection comment form
        $('.modal').modal('show')
                        .find('.modal-body')
                        .append(form);
        
        //populate relevant input field with code unit required params
                
        $('input[name=Appraisal_No]').val(Appraisal_No);
        $('input[name=Employee_No]').val(Employee_No);
        
        //Submit Rejection form and get results in json    
        $('form#rejectmyappraisal-form').on('submit', function(e){
            e.preventDefault()
            const data = $(this).serialize();
            const url = $(this).attr('action');
            $.post(url,data).done(function(msg){
                    $('.modal').modal('show')
                    .find('.modal-body')
                    .html(msg.note);
        
                },'json');
        });
        
        
    });//End click event on my appraisal overview back to line mgr





/*Commit Overview Manager Comment*/
     
     $('#confirmation').hide();
     $('#appraisalcard-over_view_manager_comments').change(function(e){
        const Comments = e.target.value;
        const Appraisal_No = $('#appraisalcard-appraisal_no').val();
        if(Appraisal_No.length){
            
            const url = $('input[name=url]').val()+'appraisal/setfield?field=Over_View_Manager_Comments';
            $.post(url,{'Over_View_Manager_Comments': Comments,'Appraisal_No': Appraisal_No}).done(function(msg){
                   //populate empty form fields with new data
                   
                  
                   $('#appraisalcard-key').val(msg.Key);
                  

                    console.log(typeof msg);
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-appraisalcard-over_view_manager_comments');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                      
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-appraisalcard-over_view_manager_comments');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        $('#confirmation').show();
                        
                        
                    }
                    
                },'json');
            
        }     
     });





       /*Commit Line Manager Comment*/
     
     $('#confirmation-super').hide();
     $('#appraisalcard-supervisor_overall_comments').change(function(e){

        const Comments = e.target.value;
        const Appraisal_No = $('#appraisalcard-appraisal_no').val();

       
        if(Appraisal_No.length){

      
            const url = $('input[name=url]').val()+'appraisal/setfield?field=Supervisor_Overall_Comments';
            $.post(url,{'Supervisor_Overall_Comments': Comments,'Appraisal_No': Appraisal_No}).done(function(msg){
                   //populate empty form fields with new data
                   
                  
                   $('#appraisalcard-key').val(msg.Key);
                  
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-appraisalcard-supervisor_overall_comments');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                      
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-appraisalcard-supervisor_overall_comments');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        $('#confirmation-super').show();
                        
                        
                    }
                    
                },'json');
            
        }     
     });


    /*Commit Mid Year Overview Manager Comment*/
     
     $('#confirmation-my').hide();
     $('#appraisalcard-overview_mid_year_comments').change(function(e){

        const Comments = e.target.value;
        const Appraisal_No = $('#appraisalcard-appraisal_no').val();

       
        if(Appraisal_No.length){

      
            const url = $('input[name=url]').val()+'appraisal/setfield?field=Overview_Mid_Year_Comments';
            $.post(url,{'Overview_Mid_Year_Comments': Comments,'Appraisal_No': Appraisal_No}).done(function(msg){
                   //populate empty form fields with new data
                   
                  
                   $('#appraisalcard-key').val(msg.Key);
                  
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-appraisalcard-overview_mid_year_comments');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                      
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-appraisalcard-overview_mid_year_comments');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        $('#confirmation-my').show();
                        
                        
                    }
                    
                },'json');
            
        }     
     });


     // Commit Line Manager Mid Year Comments 

     $('#ln-confirmation-my').hide();
     $('#appraisalcard-line_manager_mid_year_comments').change(function(e){

        const Comments = e.target.value;
        const Appraisal_No = $('#appraisalcard-appraisal_no').val();

       
        if(Appraisal_No.length){

      
            const url = $('input[name=url]').val()+'appraisal/setfield?field=Line_Manager_Mid_Year_Comments';
            $.post(url,{'Line_Manager_Mid_Year_Comments': Comments,'Appraisal_No': Appraisal_No}).done(function(msg){
                   //populate empty form fields with new data
                   
                  
                   $('#appraisalcard-key').val(msg.Key);
                  
                    console.table(msg);
                    if((typeof msg) === 'string') { // A string is an error
                        const parent = document.querySelector('.field-appraisalcard-line_manager_mid_year_comments');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = msg;
                      
                        
                    }else{ // An object represents correct details
                        const parent = document.querySelector('.field-appraisalcard-line_manager_mid_year_comments');
                        const helpbBlock = parent.children[2];
                        helpbBlock.innerText = ''; 
                        $('#ln-confirmation-my').show();
                        
                        
                    }
                    
                },'json');
            
        }     
     });


    
        
    });//end jquery

    

        
JS;

$this->registerJs($script);
