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

                <!-- OBJ SETTING APPRAISEE -->
                <?php if ($model->Review_Period == 'OBJ SETTING' && $model->Approval_Status == 'Appraisee_Level') : ?>
                        <?= Html::a('<i class="fas fa-forward"></i> submit', ['submit', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No], [
                                'class' => 'btn btn-app submitforapproval mx-1', 'data' => [
                                        'confirm' => 'Are you sure you want to submit this Appraisal Goals for Appraisal?',
                                        'method' => 'post',
                                ],
                                'title' => 'Submit Objectives for Approval'

                        ]) ?>


                <?php endif; ?>







                <?php if ($model->Approval_Status == 'Closed' && $model->Approval_Status == 'Appraisee_Level') : ?>

                        <div class="col-md-4">
                                <?= Html::a('<i class="fas fa-forward"></i> submit EY', ['submitey', 'appraisalNo' => $_GET['Appraisal_No'], 'employeeNo' => $_GET['Employee_No']], [
                                        'class' => 'btn btn-app bg-primary',
                                        'title' => 'Submit End Year Appraisal for Approval',
                                        'data' => [
                                                'confirm' => 'Are you sure you want to submit End Year Appraisal?',
                                                'method' => 'post',
                                        ]
                                ]) ?>
                        </div>

                <?php endif; ?>


                <?php if ($model->Approval_Status == 'Closed' && $model->Approval_Status == 'Agreement_Level') : ?>

                        <div class="col-md-4">
                                <?= Html::a('<i class="fas fa-check"></i> To Ln Manager', ['agreementtolinemgr', 'appraisalNo' => $_GET['Appraisal_No'], 'employeeNo' => $_GET['Employee_No']], [
                                        'class' => 'btn btn-app bg-success py-2 mx-2',
                                        'title' => 'To Line Manager',
                                        'data' => [
                                                'confirm' => 'Are you sure you want to submit End Year Appraisal?',
                                                'method' => 'post',
                                        ]
                                ]) ?>
                        </div>

                <?php endif; ?>




                <?php if ($model->Review_Period == 'Overview_Manager' && $model->isOverview()) : ?>
                        <div class="col-md-4">

                                <?= Html::a(
                                        '<i class="fas fa-backward"></i> To Line Mgr.',
                                        ['backtolinemgr', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No],
                                        [
                                                'class' => 'btn btn-app bg-danger rejectgoals',
                                                'rel' => $_GET['Appraisal_No'],
                                                'rev' => $_GET['Employee_No'],
                                                'title' => 'Submit Appraisal  Back to Line Manager'

                                        ]
                                ) ?>
                        </div>
                        <div class="col-md-4">&nbsp;</div>
                        <div class="col-md-4">

                                <?= Html::a(
                                        '<i class="fas fa-forward"></i> Approve',
                                        ['approvegoals', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No],
                                        [

                                                'class' => 'btn btn-app submitforapproval', 'data' => [
                                                        'confirm' => 'Are you sure you want to approve goals ?',
                                                        'method' => 'post',
                                                ],
                                                'title' => 'Approve Set Appraisal Goals .'
                                        ]
                                ) ?>

                        </div>

                <?php endif; ?>


                <!-- Overview Manager Actions at MY -->
                <?php if ($model->Approval_Status == 'Overview_Manager' && $model->isOverview()) : ?>
                        <?= Html::a(
                                '<i class="fas fa-check"></i> Approve',
                                ['ovapprovemy', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No],
                                [

                                        'class' => 'mx-1 btn btn-app bg-success submitforapproval', 'data' => [
                                                'confirm' => 'Are you sure you want to approve this Mid-Year Appraisal ?',
                                                'method' => 'post',
                                        ],
                                        'title' => 'Approve Mid Year Appraisal .'
                                ]
                        ) ?>



                        <?= Html::a(
                                '<i class="fas fa-backward"></i> To Line Mgr.',
                                ['mybacktolinemgr', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No],
                                [
                                        'class' => 'btn btn-app bg-danger rejectmyappraisal',
                                        'rel' => $_GET['Appraisal_No'],
                                        'rev' => $_GET['Employee_No'],
                                        'title' => 'Send Mid Year Appraisal Back to Line Manager'

                                ]
                        ) ?>

                <?php endif; ?>

                <!-- End MY Overview Actions -->


                <!--Mid Year Actions By Appraisee -->

                <?php if ($model->Review_Period == 'Closed' && $model->Approval_Status == 'Appraisee_Level' && $model->isAppraisee()) : ?>

                        <div class="col-md-4 mx-1">
                                <?= Html::a('<i class="fas fa-forward"></i> Submit', ['submitmy', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No], [
                                        'class' => 'btn btn-app bg-info submitforapproval ',
                                        'title' => 'Submit Your Mid Year Appraisal for Approval',
                                        'data' => [
                                                'confirm' => 'Are you sure you want to submit Your Mid Year Appraisal?',
                                                'method' => 'post',
                                        ]
                                ]) ?>

                        </div>


                <?php endif; ?>

                <?php if ($model->Review_Period == 'Closed' && $model->Approval_Status == 'Agreement_Level' && $model->isAppraisee()) : ?>
                        <?= Html::a('<i class="fas fa-play"></i>Agreement To Ln Mgr ', ['agreement-to-supervisor', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No], [
                                'class' => 'btn btn-app bg-warning  mx-1',
                                'title' => 'Mid-Year to Agreement Stage',
                                'data' => [
                                        'confirm' => 'Are you sure you want to send MY Appraisal to Agreement Level ?',
                                        'method' => 'post',
                                ]
                        ]);
                        ?>
                <?php endif; ?>

                <!--Enf Mid Year Actions By Appraisee -->

                <!-- Line Mgr Actions on complete goals -->

                <?php if ($model->Review_Period == 'OBJ SETTING'  && $model->Approval_Status == 'Appraiser_Level') : ?>


                        <?= Bootstrap4Html::a(
                                '<i class="fas fa-backward"></i> To Appraisee.',
                                ['backtoemp', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No],
                                [
                                        'class' => 'btn btn-app bg-danger rejectappraiseesubmition',
                                        'rel' => $model->Appraisal_No,
                                        'rev' => $model->Employee_No,
                                        'title' => 'Submit Probation  Back to Appraisee'

                                ]
                        ) ?>


                        <!-- Send Probation to Overview -->

                        <?= BootstrapHtml::a(
                                '<i class="fas fa-forward"></i> Overview ',
                                ['sendgoalsettingtooverview', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No],
                                [

                                        'class' => 'mx-1 btn btn-app submitforapproval', 'data' => [
                                                'confirm' => 'Are you sure you want to Submit Goals to Overview Manager ?',
                                                'method' => 'post',
                                        ],
                                        'title' => 'Submit Goals to Overview Manager.'
                                ]
                        ) ?>






                <?php endif; ?>

                <!-- Mid YEar Supervisor Action -->

                <?php if ($model->Approval_Status == 'Supervisor_Level') : ?>

                        <?= Html::a('<i class="fas fa-times"></i> Reject MY', ['rejectmy'], [
                                'class' => 'btn btn-app bg-warning rejectmy mx-1',
                                'title' => 'Reject Mid-Year Appraisal',
                                'rel' => $_GET['Appraisal_No'],
                                'rev' => $_GET['Employee_No'],
                                /*'data' => [
                                            'confirm' => 'Are you sure you want to Reject this Mid-Year appraisal?',
                                            'method' => 'post',]*/
                        ])
                        ?>

                        <?= Html::a('<i class="fas fa-play"></i>MY To Agreement ', ['send-my-to-agreement', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No], [
                                'class' => 'btn btn-app bg-warning  mx-1',
                                'title' => 'Mid-Year to Agreement Stage',
                                'data' => [
                                        'confirm' => 'Are you sure you want to send MY Appraisal to Agreement Level ?',
                                        'method' => 'post',
                                ]
                        ]);
                        ?>




                        <?= Html::a('<i class="fas fa-play"></i> To Overview ', ['my-to-overview', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No], [
                                'class' => 'btn btn-app bg-warning mx-1',
                                'title' => 'Send Appraisal To Overview Manager.',
                                'data' => [
                                        'confirm' => 'Are you sure you want to send MY Appraisal to Overview Manager ?',
                                        'method' => 'post',
                                ]
                        ]);
                        ?>

                <?php endif; ?>

                <!--/ Mid YEar Supervisor Action -->



                <!-- Agreement actions -->


                <?php if ($model->Approval_Status == 'Agreement_Level') : ?>

                        <?= Html::a('<i class="fas fa-play"></i>MY To Appraisee ', ['my-to-appraisee', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No], [
                                'class' => 'btn btn-app bg-warning  mx-1',
                                'title' => 'Mid-Year Agreement Back to Appraisee.',
                                'data' => [
                                        'confirm' => 'Are you sure you want to send MY Appraisal Back to Appraisee ?',
                                        'method' => 'post',
                                ]
                        ]);
                        ?>

                <?php elseif ($model->Approval_Status == 'Agreement_Level') : ?>


                        <?= Html::a('<i class="fas fa-times"></i> Reject EY', ['rejectey', 'appraisalNo' => $_GET['Appraisal_No'], 'employeeNo' => $_GET['Employee_No']], [
                                'class' => 'btn btn-app bg-warning rejectey',
                                'title' => 'Reject End-Year Appraisal',
                                'rel' =>  $_GET['Appraisal_No'],
                                'rev' => $_GET['Employee_No'],
                                /*'data' => [
                            'confirm' => 'Are you sure you want to Reject this End-Year Appraisal?',
                            'method' => 'post',]*/
                        ])
                        ?>

                <?php endif; ?>

                <!-- End Agreement actions -->

                <?php if ($model->Approval_Status == 'Closed' && $model->Approval_Status == 'Agreement_Level') : ?>

                        <div class="col-md-4">
                                <?= Html::a('<i class="fas fa-check"></i> To Ln Mgr.', ['agreementtolinemgr', 'appraisalNo' => $_GET['Appraisal_No'], 'employeeNo' => $_GET['Employee_No']], [
                                        'class' => 'btn btn-app bg-success',
                                        'title' => 'Submit End Year Appraisal for Approval',
                                        'data' => [
                                                'confirm' => 'Are you sure you want to submit End Year Appraisal?',
                                                'method' => 'post',
                                        ]
                                ]) ?>
                        </div>

                <?php endif; ?>


                <?= ($model->Approval_Status == 'Peer_1_Level' || $model->Approval_Status == 'Peer_2_Level') ? Html::a('<i class="fas fa-play"></i> Send Back to Supervisor', ['sendbacktosupervisor', 'appraisalNo' => $_GET['Appraisal_No'], 'employeeNo' => $_GET['Employee_No']], [
                        'class' => 'btn btn-success ',
                        'title' => 'Send Peer Appraisal to Supervisor',
                        'data' => [
                                'confirm' => 'Are you sure you want to send Appraisal to Supervisor?',
                                'method' => 'post',
                        ]
                ]) : '';
                ?>


                <!-- Overview Manager Actions -->

                <?php if ($model->Review_Period == 'Q1'  && $model->Approval_Status == 'Overview_Manager_Level') : ?>

                        <?= Html::a('<i class="fas fa-check"></i> Approve Goals', ['approveey', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No], [
                                'class' => 'mx-1 btn btn-app bg-success submitforapproval',
                                'title' => 'Approve Appraisal Goals',
                                'data' => [
                                        'confirm' => 'Are you sure you want to Approve this Goals ?',
                                        'method' => 'post',
                                ]
                        ])
                        ?>

                        <?= Html::a('<i class="fas fa-times"></i> To Ln Manager', ['rejectey', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No], [
                                'class' => 'btn btn-app bg-danger ovrejectey',
                                'title' => 'Reject Goals and Send Back to Line Manager',
                                'rel' => $model->Appraisal_No,
                                'rev' => $model->Employee_No,
                                /*'data' => [
                            'confirm' => 'Are you sure you want to Reject this Mid Year Appraisal?',
                            'method' => 'post',]*/
                        ])
                        ?>

                <?php endif; ?>


                <?php if ($model->Review_Period == 'OBJ SETTING'  && $model->Approval_Status == 'Overview_Manager_Level') : ?>

                        <?= Bootstrap4Html::a('<i class="fas fa-check"></i> Approve Goals', ['approvegoalsetting', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No], [
                                'class' => 'mx-1 btn btn-app bg-success submitforapproval',
                                'title' => 'Approve Appraisal Goals',
                                'data' => [
                                        'confirm' => 'Are you sure you want to Approve this Goals ?',
                                        'method' => 'post',
                                ]
                        ])
                        ?>

                        <?= Bootstrap4Html::a('<i class="fas fa-times"></i> To Ln Manager', ['rejectgoalsetting', 'appraisalNo' => $model->Appraisal_No, 'employeeNo' => $model->Employee_No], [
                                'class' => 'btn btn-app bg-danger ovrejectey',
                                'title' => 'Reject Goals and Send Back to Line Manager',
                                'rel' => $model->Appraisal_No,
                                'rev' => $model->Employee_No,
                                /*'data' => [
                            'confirm' => 'Are you sure you want to Reject this Mid Year Appraisal?',
                            'method' => 'post',]*/
                        ])
                        ?>

                <?php endif; ?>

                <!-- End Overview Actions -->

                <?php if ($model->Approval_Status == 'Supervisor_Level') : ?>

                        <?= Html::a('<i class="fas fa-check"></i> Agreement..', ['sendtoagreementlevel', 'appraisalNo' => $_GET['Appraisal_No'], 'employeeNo' => $_GET['Employee_No']], [
                                'class' => 'btn btn-app bg-success submitforapproval',
                                'title' => 'Move Appraisal to  Agreement Level',
                                'data' => [
                                        'confirm' => 'Are you sure you want to send this End-Year Appraisal to Agreement Level ?',
                                        'method' => 'post',
                                ]
                        ])
                        ?>

                        <!-- Back to Appraisee -->

                        <?= Html::a('<i class="fas fa-times"></i> To Appraisee', ['rejectey', 'appraisalNo' => $_GET['Appraisal_No'], 'employeeNo' => $_GET['Employee_No']], [
                                'class' => 'btn btn-app bg-danger rejectey',
                                'title' => 'Reject Goals Set by Appraisee',
                                'rel' => $_GET['Appraisal_No'],
                                'rev' => $_GET['Employee_No'],

                        ])
                        ?>


                        <?= Html::a('<i class="fas fa-forward"></i> Overview', ['sendeytooverview', 'appraisalNo' => $_GET['Appraisal_No'], 'employeeNo' => $_GET['Employee_No']], [
                                'class' => 'mx-1 btn btn-app bg-success submitforapproval',
                                'title' => 'Move Appraisal to  Agreement Level',
                                'data' => [
                                        'confirm' => 'Are you sure you want to send this End-Year Appraisal to Agreement Level ?',
                                        'method' => 'post',
                                ]
                        ])
                        ?>

                <?php endif; ?>


        </div>
        <!--End Actions row-->

</div>