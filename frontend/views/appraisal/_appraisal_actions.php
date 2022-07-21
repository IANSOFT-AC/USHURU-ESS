<?php

use yii\bootstrap4\Html as Bootstrap4Html;
use yii\bootstrap\Html as BootstrapHtml;
use yii\helpers\Html;
?>
<div class="card-body info-box">

        <div class="d-flex justify-content-center">


                <!-- APPRAISAL REPORT -->
                <?= Html::a('<i class="fas fa-book-open"></i> P.A Report', ['report', 'appraisalNo' => $_GET['Appraisal_No'], 'employeeNo' => $_GET['Employee_No']], [
                        'class' => 'btn btn-app bg-success  pull-right mx-1',
                        'title' => 'Generate Performance Appraisal Report',
                        'target' => '_blank',
                        'data' => [
                                // 'confirm' => 'Are you sure you want to send appraisal to peer 2?',
                                'params' => [
                                        'appraisalNo' => $model->Appraisal_No,
                                        'employeeNo' => $model->Employee_No,
                                ],
                                'method' => 'post',
                        ]
                ]);
                ?>

                <!--  APPRAISEE Actions -->
                <?php if ($model->Approval_Status == 'Appraisee_Level') : ?>

                        <?= Html::a('<i class="fas fa-forward"></i> To Line Mgr.', ['appraisal-submit', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No], [
                                'class' => 'btn btn-app submitforapproval mx-1', 'data' => [
                                        'confirm' => 'Are you sure you want to submit this Appraisal for Appraisal?',
                                        'method' => 'post',
                                ],
                                'title' => 'Submit Appraisal for Approval'

                        ]) ?>


                <?php endif; ?>



                <!-- APPRAISEER ACTIONS -->
                <?php if ($model->Approval_Status == 'Appraiser_Level') : ?>


                        <?= Bootstrap4Html::a(
                                '<i class="fas fa-backward"></i> To Appraisee.',
                                ['appraisal-to-emp', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No],
                                [
                                        'class' => 'btn btn-app bg-danger rejectappraiseesubmition',
                                        'rel' => $model->Appraisal_No,
                                        'rev' => $model->Employee_No,
                                        'title' => 'Submit Probation  Back to Appraisee'

                                ]
                        ) ?>



                        <?= BootstrapHtml::a(
                                '<i class="fas fa-play"></i> Overview ',
                                ['appraisal-to-agreement', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No],
                                [

                                        'class' => 'mx-1 btn btn-app', 'data' => [
                                                'confirm' => 'Are you sure you want to Submit Appraisal for Agreement ?',
                                                'method' => 'post',
                                        ],
                                        'title' => 'Submit Appraisal for Agreement.'
                                ]
                        ) ?>



                        <!-- Send to Overview -->

                        <?= BootstrapHtml::a(
                                '<i class="fas fa-forward"></i> Overview ',
                                ['appraisal-to-overview', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No],
                                [

                                        'class' => 'mx-1 btn btn-app submitforapproval', 'data' => [
                                                'confirm' => 'Are you sure you want to Submit Goals to Overview Manager ?',
                                                'method' => 'post',
                                        ],
                                        'title' => 'Submit Goals to Overview Manager.'
                                ]
                        ) ?>


                <?php endif; ?>

                <!-- Overview Actions -->
                <?php if ($model->Approval_Status == 'Overview_Manager_Level') : ?>

                        <?= Bootstrap4Html::a(
                                '<i class="fas fa-backward"></i> To Line Mgr.',
                                ['appraisal-to-linemgr', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No],
                                [
                                        'class' => 'btn btn-app bg-danger rejectappraiseesubmition',
                                        'rel' => $model->Appraisal_No,
                                        'rev' => $model->Employee_No,
                                        'title' => 'Submit Probation  Back to Line Manager'

                                ]
                        ) ?>



                        <?= BootstrapHtml::a(
                                '<i class="fas fa-check"></i> Approve',
                                ['appraisal-approve', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No],
                                [

                                        'class' => 'mx-1 btn btn-app ', 'data' => [
                                                'confirm' => 'Are you sure you want to Approve this Appraisal ?',
                                                'method' => 'post',
                                        ],
                                        'title' => 'Approve Appraisal for the Current  Review Period.'
                                ]
                        ) ?>
                <?php endif; ?>

        </div>