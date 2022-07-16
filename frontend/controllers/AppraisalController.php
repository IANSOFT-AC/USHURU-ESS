<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/28/2020
 * Time: 12:27 AM
 */


namespace frontend\controllers;

use common\models\HrloginForm;
use common\models\SignupForm;
use frontend\models\Appraisalcard;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use frontend\models\Applicantprofile;
use frontend\models\Employeerequisition;
use frontend\models\Employeerequsition;
use frontend\models\Job;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use frontend\models\Employee;
use yii\web\Controller;
use yii\web\Response;

class AppraisalController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'vacancies', 'view', 'create', 'update', 'delete', 'myappraiseelist', 'eyagreementlist', 'eyappraiseelist', 'viewsubmitted'],
                'rules' => [
                    [
                        'actions' => ['vacancies'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['index', 'vacancies', 'view', 'create', 'update', 'delete', 'myappraiseelist', 'eyagreementlist', 'eyappraiseelist', 'viewsubmitted'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
                    'reject' => ['POST']
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'only' => [
                    'getappraisals',
                    'getsubmittedappraisals',
                    'getapprovedappraisals',
                    'getsuperapprovedappraisals',
                    'getmyappraiseelist',
                    'getmysupervisorlist',
                    'getmyapprovedappraiseelist',
                    'getmyapprovedsupervisorlist',
                    'getmyoverviewlist',
                    'geteyappraiseelist',
                    'geteysupervisorlist',
                    'geteypeer1list',
                    'geteypeer2list',
                    'geteyagreementlist',
                    'geteyappraiseeclosedlist',
                    'geteysupervisorclosedlist',
                    'probation-status-list',
                    'short-term-status',
                    'long-term-status',
                    'setfield',
                    'getmyagreementlist',
                    'getmyagreementlistsuper',
                    'probation-status-list-super',
                    'short-term-status-super',
                    'long-term-status-super',
                    'add-line',
                    'perspective',
                    'kpi-status',
                    'training-categories',
                    'rating',
                    'add-rating-line'
                ],
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    //'application/xml' => Response::FORMAT_XML,
                ],
            ]
        ];
    }

    public function beforeAction($action)
    {

        $ExcemptedActions = [
            'add-line', 'perspective', 'kpi-status', 'training-categories', 'rating', 'add-rating-line'
        ];

        if (in_array($action->id, $ExcemptedActions)) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionMyagreement()
    {

        return $this->render('myagreement');
    }
    public function actionMyagreementsuper()
    {

        return $this->render('myagreementsuper');
    }

    public function actionSubmitted()
    {

        return $this->render('submitted');
    }

    public function actionOverviewgoalslist()
    {

        return $this->render('approvedappraisals');
    }

    public function actionSuperapprovedappraisals()
    {

        return $this->render('superapprovedappraisals');
    }

    public function actionMyappraiseelist()
    {

        return $this->render('myappraiseelist');
    }

    public function actionMysupervisorlist()
    {

        return $this->render('mysupervisorlist');
    }

    public function actionMyapprovedappraiseelist()
    {

        return $this->render('myapprovedappraiseelist');
    }

    public function actionMyapprovedsupervisorlist()
    {

        return $this->render('myapprovedsupervisorlist');
    }

    public function actionMyoverviewlist()
    {

        return $this->render('myoverviewlist');
    }

    public function actionEyappraiseelist()
    {

        return $this->render('eyappraiseelist');
    }

    public function actionEysupervisorlist()
    {

        return $this->render('eysupervisorlist');
    }

    public function actionEyoverviewlist()
    {

        return $this->render('eypeer1list');
    }

    public function actionEypeer2list()
    {

        return $this->render('eypeer2list');
    }

    public function actionEyagreementlist()
    {

        return $this->render('eyagreementlist');
    }

    public function actionEyappraiseeclosedlist()
    {

        return $this->render('eyappraiseeclosedlist');
    }

    public function actionEysupervisorclosedlist()
    {

        return $this->render('eysupervisorclosedlist');
    }

    public function actionSuperapprovedappraisals12()
    {

        return $this->render('superapprovedappraisals');
    }

    /*Show Probation Status List*/

    public function actionProbStatusList()
    {

        return $this->render('probation-status');
    }

    public function actionProbStatusListSuper()
    {

        return $this->render('probation-status-super');
    }

    /*Show shorterm Appraisal Status List*/

    public function actionStStatus()
    {

        return $this->render('shortterm-status');
    }

    public function actionStStatusSuper()
    {

        return $this->render('shortterm-status-super');
    }

    /*Show Long Term Status List*/


    public function actionLtStatus()
    {

        return $this->render('longterm-status');
    }

    public function actionLtStatusSuper()
    {

        return $this->render('longterm-status-super');
    }






    public function actionGetappraisals()
    {

        $service = Yii::$app->params['ServiceName']['AppraisalList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);
        //ksort($appraisals);
        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('View', ['view', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);

                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Calendar) ? $req->Appraisal_Calendar : '',
                    'Appraisal_Start_Date' =>  !empty($req->Appraisal_Start_Date) ? $req->Appraisal_Start_Date : '',
                    'Appraisal_End_Date' =>  !empty($req->Appraisal_End_Date) ? $req->Appraisal_End_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];
            }
        }

        return $result;
    }

    /*Get Submitted Appraisals Pending Approval*/
    public function actionGetsubmittedappraisals()
    {
        $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['AppraisalList'];
        $filter = [
            // 'Supervisor_User_Id' => Yii::$app->user->identity->employee[0]->User_ID,
            'Supervisor_No' => Yii::$app->user->identity->{'Employee No_'}
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];

        //Yii::$app->recruitment->printrr($appraisals);
        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                if ($model->isSupervisor()) {
                    Yii::$app->session->set('isSupervisor', true);
                } else {
                    Yii::$app->session->set('isSupervisor', false);
                }
                $Viewlink = Html::a('<i class="fa fa-eye"></i>', ['viewsubmitted', 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : '', 'Employee_No' => $req->Employee_No], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);
                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }

    /**Get Approved Appraisals (Supervisor view) */

    public function actionGetsuperapprovedappraisals()
    {
        $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['ApprovedAppraisals'];
        $filter = [
            'Supervisor_User_Id' => Yii::$app->user->identity->employee[0]->User_ID,
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];


        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                if ($model->isSupervisor($req->Employee_User_Id, $req->Supervisor_User_Id)) {
                    Yii::$app->session->set('isSupervisor', true);
                } else {
                    Yii::$app->session->set('isSupervisor', false);
                }

                $Viewlink = Html::a('<i class="fa fa-eye"></i>', ['viewsubmitted', 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : '', 'Employee_No' => $req->Employee_No], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);
                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }

    /** Get Approved Appraisal Goals/Objectives -- Appraisee */

    public function actionGetapprovedappraisals()
    {
        $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['AppraisalList'];
        $filter = [
            'Overview_Manager' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);


        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['view', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);

                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];
            }
        }

        return $result;
    }

    /*Get Mid Year Appraisals - Appraisee List*/

    public function actionGetmyappraiseelist()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['MYAppraiseeList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'}
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['view', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);

                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];
            }
        }

        return $result;
    }

    /*Get Mid Year Approved Appraisals - Appraisee List*/

    public function actionGetmyapprovedappraiseelist()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['MYApprovedList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee_No'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['view', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);

                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];
            }
        }

        return $result;
    }





    /*Get MY Overview List*/

    public function actionGetmyoverviewlist()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['EYPeer2List'];
        $filter = [
            'Overview_Manager' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['view', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);
                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }

    /*Get Mid Year Appraisals - Supervisor List*/

    public function actionGetmysupervisorlist()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['MYSupervisorList'];
        $filter = [
            'Supervisor_No' => Yii::$app->user->identity->{'Employee No_'},

        ];

        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('views', ['viewsubmitted', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);
                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }


    /*Get Mid Year Approved Appraisals - Supervisor List*/

    public function actionGetmyapprovedsupervisorlist()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['MYApprovedList'];
        $filter = [
            'Supervisor_User_Id' => Yii::$app->user->identity->employee[0]->User_ID,
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['viewsubmitted', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);
                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }



    /*Get End Year Appraisals - Appraisee List*/

    public function actionGeteyappraiseelist()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['EYAppraiseeList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {


                /* if($model->isSupervisor($req->Employee_User_Id,$req->Supervisor_User_Id)){
                     Yii::$app->session->set('isSupervisor',true);
                 }else{
                     Yii::$app->session->set('isSupervisor',false);
                 }*/


                $Viewlink = Html::a('view', ['view', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                /* if($model->isSupervisor($req->Employee_User_Id,$req->Supervisor_User_Id)){
                     $Viewlink = Html::a('viewsubmitted', ['view','Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No)?$req->Appraisal_No: ''], ['class' => 'btn btn-outline-primary btn-xs']);
                 }*/


                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];
            }
        }

        return $result;
    }




    /*Get Mid Year Appraisals - Supervisor List*/

    public function actionGeteysupervisorlist()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['EYSupervisorList'];
        $filter = [
            'Supervisor_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('views', ['viewsubmitted', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);
                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }



    /*Get End Year Appraisals - Peer1 List*/

    public function actionGeteypeer1list()
    {

        $service = Yii::$app->params['ServiceName']['EYPeer1List'];
        $filter = [
            'Overview_Manager' => Yii::$app->user->identity->{'Employee No_'},

        ];
        //return $filter;
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['viewsubmitted', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);

                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];
            }
        }

        return $result;
    }



    /*Get End Year Appraisals - Peer2 List*/

    public function actionGeteypeer2list()
    {

        $service = Yii::$app->params['ServiceName']['EYPeer2List'];
        $filter = [
            'Overview_Manager' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['viewsubmitted', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);

                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink : '',

                ];
            }
        }

        return $result;
    }

    // Get MY Agreement List

    public function actionGetmyagreementlist()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['MYAgreementList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},

        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['viewsubmitted', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);
                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }

    public function actionGetmyagreementlistsuper()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['MYAgreementList'];
        $filter = [
            'Supervisor_No' => Yii::$app->user->identity->{'Employee No_'},

        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['viewsubmitted', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);


                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }

    /*Get Mid Year Appraisals - Supervisor List*/

    public function actionGeteyagreementlist()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['EYAgreementList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},

        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['viewsubmitted', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);
                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }



    /*Get EY Year Closed Appraisals - Appraisee List*/

    public function actionGeteyappraiseeclosedlist()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['ClosedAppraisalsList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},

        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('views', ['view', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);



                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }

    /*Get EY Year Closed Appraisals -  Supervisor List*/

    public function actionGeteysupervisorclosedlist()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['ClosedAppraisalsList'];
        $filter = [
            //'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('views', ['viewsubmitted', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);
                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Level_Grade' => !empty($req->Level_Grade) ? $req->Level_Grade : 'Not Set',
                    'Job_Title' => !empty($req->Job_Title) ? $req->Job_Title : '',
                    'Function_Team' =>  !empty($req->Function_Team) ? $req->Function_Team : '',
                    'Appraisal_Period' =>  !empty($req->Appraisal_Period) ? $req->Appraisal_Period : '',
                    'Goal_Setting_Start_Date' =>  !empty($req->Goal_Setting_Start_Date) ? $req->Goal_Setting_Start_Date : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }

    /*Get Probation Status List*/

    public function actionProbationStatusList()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['ProbationStatusList'];
        $filter = [
            //'Supervisor_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['../probation/dashview', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);


                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Appraisal_Period' => !empty($req->Appraisal_Period) ? $req->Appraisal_Period : 'Not Set',
                    'Goal_Setting_Status' => !empty($req->Goal_Setting_Status) ? $req->Goal_Setting_Status : '',
                    'Appraisal_Status' =>  !empty($req->Appraisal_Status) ? $req->Appraisal_Status : '',
                    'Supervisor_Name' =>  !empty($req->Supervisor_Name) ? $req->Supervisor_Name : '',
                    'Probation_Recomended_Action' =>  !empty($req->Probation_Recomended_Action) ? $req->Probation_Recomended_Action : '',
                    'Overview_Manager_Name' =>  !empty($req->Overview_Manager_Name) ? $req->Overview_Manager_Name : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }

    // Supervisor List

    public function actionProbationStatusListSuper()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['ProbationStatusList'];
        $filter = [
            'Supervisor_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['../probation/dashview', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]

                ]);

                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Appraisal_Period' => !empty($req->Appraisal_Period) ? $req->Appraisal_Period : 'Not Set',
                    'Goal_Setting_Status' => !empty($req->Goal_Setting_Status) ? $req->Goal_Setting_Status : '',
                    'Appraisal_Status' =>  !empty($req->Appraisal_Status) ? $req->Appraisal_Status : '',
                    'Supervisor_Name' =>  !empty($req->Supervisor_Name) ? $req->Supervisor_Name : '',
                    'Probation_Recomended_Action' =>  !empty($req->Probation_Recomended_Action) ? $req->Probation_Recomended_Action : '',
                    'Overview_Manager_Name' =>  !empty($req->Overview_Manager_Name) ? $req->Overview_Manager_Name : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }


    /*Short Term Status List*/

    public function actionShortTermStatus()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['ShortTermStatusList'];
        $filter = [
            //'Supervisor_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['../shortterm/dashview', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);


                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Appraisal_Period' => !empty($req->Appraisal_Period) ? $req->Appraisal_Period : 'Not Set',
                    'Goal_Setting_Status' => !empty($req->Goal_Setting_Status) ? $req->Goal_Setting_Status : '',
                    'Appraisal_Status' =>  !empty($req->Appraisal_Status) ? $req->Appraisal_Status : '',
                    'Supervisor_Name' =>  !empty($req->Supervisor_Name) ? $req->Supervisor_Name : '',
                    'Overview_Manager_Name' =>  !empty($req->Overview_Manager_Name) ? $req->Overview_Manager_Name : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }

    // Supervisor List

    public function actionShortTermStatusSuper()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['ShortTermStatusList'];
        $filter = [
            'Supervisor_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['../shortterm/dashview', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);

                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]

                ]);


                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Appraisal_Period' => !empty($req->Appraisal_Period) ? $req->Appraisal_Period : 'Not Set',
                    'Goal_Setting_Status' => !empty($req->Goal_Setting_Status) ? $req->Goal_Setting_Status : '',
                    'Appraisal_Status' =>  !empty($req->Appraisal_Status) ? $req->Appraisal_Status : '',
                    'Supervisor_Name' =>  !empty($req->Supervisor_Name) ? $req->Supervisor_Name : '',
                    'Overview_Manager_Name' =>  !empty($req->Overview_Manager_Name) ? $req->Overview_Manager_Name : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }

    /*Long Term Appraisal Status List*/

    public function actionLongTermStatus()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['LongTermAppraisal_Status'];
        $filter = [
            //'Supervisor_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['appraisal/dashview', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);
                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Appraisal_Period' => !empty($req->Appraisal_Period) ? $req->Appraisal_Period : 'Not Set',
                    'Goal_Setting_Status' => !empty($req->Goal_Setting_Status) ? $req->Goal_Setting_Status : '',
                    'MY_Appraisal_Status' => !empty($req->MY_Appraisal_Status) ? $req->MY_Appraisal_Status : '',
                    'Appraisal_Status' =>  !empty($req->Appraisal_Status) ? $req->Appraisal_Status : '',
                    'Supervisor_Name' =>  !empty($req->Supervisor_Name) ? $req->Supervisor_Name : '',
                    'Overview_Manager_Name' =>  !empty($req->Overview_Manager_Name) ? $req->Overview_Manager_Name : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }

        return $result;
    }

    // List for Supervisor

    public function actionLongTermStatusSuper()
    {
        // $model = new Appraisalcard();
        $service = Yii::$app->params['ServiceName']['LongTermAppraisal_Status'];
        $filter = [
            'Supervisor_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        $appraisals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (is_array($appraisals)) {
            foreach ($appraisals as $req) {

                $Viewlink = Html::a('view', ['appraisal/dashview', 'Employee_No' => $req->Employee_No, 'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : ''], ['class' => 'btn btn-outline-primary btn-xs']);
                $Reportlink = Html::a('<i class="fa fa-file-pdf"></i>', ['../appraisal/report'], [
                    'title' => 'View Appraisal Report', 'class' => 'btn btn-outline-primary btn-xs mx-1', 'target' => '_blank',
                    'data' => [
                        'params' => [
                            'appraisalNo' => $req->Appraisal_No,
                            'employeeNo' => $req->Employee_No
                        ],
                        'method' => 'post'
                    ]
                ]);



                $result['data'][] = [
                    'Appraisal_No' => !empty($req->Appraisal_No) ? $req->Appraisal_No : 'Not Set',
                    'Employee_No' => !empty($req->Employee_No) ? $req->Employee_No : '',
                    'Employee_Name' => !empty($req->Employee_Name) ? $req->Employee_Name : 'Not Set',
                    'Appraisal_Period' => !empty($req->Appraisal_Period) ? $req->Appraisal_Period : 'Not Set',
                    'Goal_Setting_Status' => !empty($req->Goal_Setting_Status) ? $req->Goal_Setting_Status : '',
                    'MY_Appraisal_Status' => !empty($req->MY_Appraisal_Status) ? $req->MY_Appraisal_Status : '',
                    'Appraisal_Status' =>  !empty($req->Appraisal_Status) ? $req->Appraisal_Status : '',
                    'Supervisor_Name' =>  !empty($req->Supervisor_Name) ? $req->Supervisor_Name : '',
                    'Overview_Manager_Name' =>  !empty($req->Overview_Manager_Name) ? $req->Overview_Manager_Name : '',
                    'Action' => !empty($Viewlink) ? $Viewlink . $Reportlink : '',

                ];
            }
        }
        return $result;
    }


    public function actionView()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalCard'];
        $model = new Appraisalcard();

        $Appraisal_No = Yii::$app->request->get('Appraisal_No');
        $Employee_No = Yii::$app->request->get('Employee_No');

        $filter = [
            'Appraisal_No' => Yii::$app->request->get('Appraisal_No'),
            'Employee_No' => Yii::$app->request->get('Employee_No')
        ];

        $appraisal = Yii::$app->navhelper->getData($service, $filter);
        // Yii::$app->recruitment->printrr($appraisal);
        if (is_array($appraisal)) {
            $model = Yii::$app->navhelper->loadmodel($appraisal[0], $model);
        }

        //echo property_exists($appraisal[0]->Employee_Appraisal_KRAs,'Employee_Appraisal_KRAs')?'Exists':'Haina any';

        // Yii::$app->recruitment->printrr($appraisal[0]);



        return $this->render('view', [
            'model' => $model,
            'card' => $appraisal[0]
        ]);
    }


    public function actionDashview()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalCard'];
        $model = new Appraisalcard();

        $filter = [
            'Appraisal_No' => Yii::$app->request->get('Appraisal_No'),
            'Employee_No' => Yii::$app->request->get('Employee_No')
        ];

        $appraisal = Yii::$app->navhelper->getData($service, $filter);
        // Yii::$app->recruitment->printrr($appraisal);
        if (is_array($appraisal)) {
            $model = Yii::$app->navhelper->loadmodel($appraisal[0], $model);
        }

        //echo property_exists($appraisal[0]->Employee_Appraisal_KRAs,'Employee_Appraisal_KRAs')?'Exists':'Haina any';

        // Yii::$app->recruitment->printrr($appraisal[0]);


        return $this->render('dashview', [
            'model' => $model,
            'card' => $appraisal[0]
        ]);
    }



    public function actionSetfield($field)
    {
        $model = new  Appraisalcard();
        $service = Yii::$app->params['ServiceName']['AppraisalCard'];

        $filter = [
            'Appraisal_No' => Yii::$app->request->post('Appraisal_No'),
        ];
        $result = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($result)) {
            Yii::$app->navhelper->loadmodel($result[0], $model);
            $model->Key = $result[0]->Key;
            $model->$field = Yii::$app->request->post($field);
        }


        $result = Yii::$app->navhelper->updateData($service, $model);
        // Yii::$app->recruitment->printrr( $result);
        return $result;
    }

    public function actionViewsubmitted($Appraisal_No, $Employee_No)
    {
        // Yii::$app->recruitment->printrr(Yii::$app->user->identity);
        $service = Yii::$app->params['ServiceName']['AppraisalCard'];
        $model = new Appraisalcard();

        $filter = [
            'Appraisal_No' => $Appraisal_No,
            'Employee_No' => $Employee_No
        ];

        $appraisal = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($appraisal)) {
            $model = Yii::$app->navhelper->loadmodel($appraisal[0], $model);
        }

        if ($model->isAppraisee()) {
            return $this->redirect(
                [
                    'view',
                    'Appraisal_No' => $Appraisal_No,
                    'Employee_No' => $Employee_No
                ]
            );
        }


        return $this->render('viewsubmitted', [
            'model' => $model,
            'card' => $appraisal[0],
            'peers' =>  ArrayHelper::map($this->getEmployees(), 'No', 'Full_Name'),
        ]);
    }

    //set peer1

    public function actionSetpeer1()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalCard'];
        $model = new Appraisalcard();

        $filter = [
            'Appraisal_No' => Yii::$app->request->post('Appraisal_No'),
            //'Employee_No' => Yii::$app->request->get('Employee_No')
        ];

        $appraisal = Yii::$app->navhelper->getData($service, $filter);
        $model = Yii::$app->navhelper->loadmodel($appraisal[0], $model);
        $model->Peer_1_Employee_No = Yii::$app->request->post('Employee_No');
        //Update
        $result = Yii::$app->navhelper->updateData($service, $model);

        //Yii::$app->recruitment->printrr($result);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!is_string($result)) {
            //Yii::$app->session->setFlash('success', 'Perfomance Appraisal Goals Rejected and Sent Back to Appraisee Successfully.', true);
            return ['note' => '<div class="alert alert-success alert-dismissable">Peer Set Successfully.</div>'];
        } else {

            // Yii::$app->session->setFlash('error', 'Error Rejecting Performance Appraisal Goals : '. $result);
            return ['note' => '<div class="alert alert-danger alert-dismissable">Error Setting Peer. </div>'];
        }
    }

    //Set Peer 2
    public function actionSetpeer2()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalCard'];
        $model = new Appraisalcard();

        $filter = [
            'Appraisal_No' => Yii::$app->request->post('Appraisal_No'),
            //'Employee_No' => Yii::$app->request->get('Employee_No')
        ];

        $appraisal = Yii::$app->navhelper->getData($service, $filter);
        $model = Yii::$app->navhelper->loadmodel($appraisal[0], $model);
        $model->Peer_2_Employee_No = Yii::$app->request->post('Employee_No');
        //Update
        $result = Yii::$app->navhelper->updateData($service, $model);

        //Yii::$app->recruitment->printrr($result);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!is_string($result)) {
            //Yii::$app->session->setFlash('success', 'Perfomance Appraisal Goals Rejected and Sent Back to Appraisee Successfully.', true);
            return ['note' => '<div class="alert alert-success alert-dismissable">Peer 2 Set Successfully.</div>'];
        } else {

            // Yii::$app->session->setFlash('error', 'Error Rejecting Performance Appraisal Goals : '. $result);
            return ['note' => '<div class="alert alert-danger alert-dismissable">Error Setting Peer 2 </div>'];
        }
    }

    //Submit Appraisal to supervisor

    public function actionSubmit($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/view', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo])
        ];

        $result = Yii::$app->navhelper->codeunit($service, $data, 'SendGoalSettingForApproval');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Goal Setting Submitted Successfully.', true);
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Error  : ' . $result);
            return $this->redirect(['view', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        }
    }

    /*Supervisor Actions :Approve Reject*/



    //SendGoalSettingToOverview

    public function actionSendgoalsettingtooverview($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/view', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo])
        ];

        $result = Yii::$app->navhelper->Codeunit($service, $data, 'SendGoalSettingToOverview');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Successfully sent to overview manager.', true);
            return $this->redirect(['submitted']);
        } else {
            Yii::$app->session->setFlash('error', 'Error : ' . $result);
            return $this->redirect(['view', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        }
    }




    // send back to line manager

    public function actionSendbacktolinemanager()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $appraisalNo = Yii::$app->request->post('Appraisal_No');
        $employeeNo = Yii::$app->request->post('Employee_No');
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]),
            'rejectionComments' => Yii::$app->request->post('comment'),
        ];

        $result = Yii::$app->navhelper->Codeunit($service, $data, 'IanSendGoalSettingBackToLineManager');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Successfully sent to overview manager.', true);
            return $this->redirect(['viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        } else {
            Yii::$app->session->setFlash('error', 'Error sending to overview manager : ' . $result);
            return $this->redirect(['viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        }
    }



    public function actionApprove($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo])
        ];

        $result = Yii::$app->navhelper->IanApproveGoalSetting($service, $data);

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Perfomance Appraisal Goals Approved Successfully.', true);
            return $this->redirect(['viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        } else {
            Yii::$app->session->setFlash('error', 'Error Approving Performance Appraisal Goals : ' . $result);
            return $this->redirect(['viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        }
    }


    /*Over Mid Year Approval*/



    public function actionOvapprovemy($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo])
        ];

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'IanApproveMYAppraisal');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Mid Year Appraisal Approved Successfully.', true);
            return $this->redirect(['myoverviewlist']);
        } else {
            Yii::$app->session->setFlash('error', 'Error : ' . $result);
            return $this->redirect(['myoverviewlist']);
        }
    }





    public function actionReject()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $appraisalNo = Yii::$app->request->post('Appraisal_No');
        $employeeNo = Yii::$app->request->post('Employee_No');
        $data = [
            'appraisalNo' => Yii::$app->request->post('Appraisal_No'),
            'employeeNo' => Yii::$app->request->post('Employee_No'),
            'sendEmail' => 0,
            'approvalURL' => 1,
            'rejectionComments' => Yii::$app->request->post('comment')
        ];

        $result = Yii::$app->navhelper->IanSendGoalSettingBackToAppraisee($service, $data);
        //Response of this action is json only
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (!is_string($result)) {
            //Yii::$app->session->setFlash('success', 'Perfomance Appraisal Goals Rejected and Sent Back to Appraisee Successfully.', true);
            return ['note' => '<div class="alert alert-success alert-dismissable">Perfomance Appraisal Goals Rejected and Sent Back to Appraisee Successfully.</div>'];
        } else {

            // Yii::$app->session->setFlash('error', 'Error Rejecting Performance Appraisal Goals : '. $result);
            return ['note' => '<div class="alert alert-danger alert-dismissable">Error Rejecting Performance Appraisal Goals </div>'];
        }
    }

    //Submit MY Appraisal for Approval

    public function actionSubmitmy($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo])
        ];

        $result = Yii::$app->navhelper->IanSendMYAppraisalForApproval($service, $data);

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Mid Year Perfomance Appraisal Submitted Successfully.', true);
            return $this->redirect(['myappraiseelist']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Submitting Mid Year Performance Appraisal : ' . $result);
            return $this->redirect(['myappraiseelist']);
        }
    }


    // Send Mid Year  To Agreement



    public function actionSendMyToAgreement($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]),
            'rejectionComments' => '',
        ];

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'IanSendMYAppraisalToAgreement');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Mid Year Perfomance Appraisal Pushed to Agreement Stage Successfully.', true);
            return $this->redirect(['myagreementsuper']);
        } else {

            Yii::$app->session->setFlash('error', 'Error : ' . $result);
            return $this->redirect(['myagreementsuper']);
        }
    }

    // Send MY Agreement Back to Appraisee


    public function actionMyToAppraisee()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];

        $appraisalNo = Yii::$app->request->post('Appraisal_No');
        $employeeNo = Yii::$app->request->post('Employee_No');

        $data = [
            'appraisalNo' => Yii::$app->request->post('Appraisal_No'),
            'employeeNo' => Yii::$app->request->post('Employee_No'),
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/view', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]),
            'rejectionComments' => '',
        ];

        // IanSendMYAppraisaBackLineManagerFromAgreement

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'IanSendMYAppraisaBackLineManagerFromAgreement');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Mid Year Appraisal Sent Back to Appraisee Successfully.', true);
            return $this->redirect(['myagreementsuper']);
            // return ['note' => '<div class="alert alert-success alert-dismissable">Mid Year Appraisal Rejected and Sent Back to Appraisee Successfully.</div>'];
        } else {

            Yii::$app->session->setFlash('error', 'Error Sending Mid Year Appraisal Back to Appraisee : ' . $result);
            return $this->redirect(['myagreementsuper']);
            // return ['note' => '<div class="alert alert-danger alert-dismissable">Error Rejecting Mid Year Appraisal : '. $result.'</div>'];

        }
    }


    public function actionAgreementToSupervisor($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]),
            'rejectionComments' => '',
        ];

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'IanSendMYAppraisaBackLineManagerFromAgreement');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Mid Year Agreement Appraisal Sent Back to Line Manager Successfully.', true);
            return $this->redirect(['myagreement']);
        } else {

            Yii::$app->session->setFlash('error', 'Error : ' . $result);
            return $this->redirect(['myagreement']);
        }
    }


    // On agreement level , senf EY Back to ln Manager


    public function actionAgreementtolinemgr($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo])
        ];

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'IanSendEYAppraisalForApproval');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'End Year Perfomance Appraisal Agreement Submitted to Line Manager Successfully.', true);
            return $this->redirect(['eyagreementlist']);
        } else {

            Yii::$app->session->setFlash('error', 'Error : ' . $result);
            return $this->redirect(['eyagreementlist']);
        }
    }


    //Approve MY appraisal
    public function actionApprovemy($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 0,
            'approvalURL' => 1
        ];

        $result = Yii::$app->navhelper->IanApproveMYAppraisal($service, $data);

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Mid Year Appraisal Approved Successfully.', true);
            return $this->redirect(['viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        } else {

            Yii::$app->session->setFlash('error', 'Error Approving Mid Year Appraisal : ' . $result);
            return $this->redirect(['viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        }
    }

    //Reject Mid-Year Appraisal

    public function actionRejectmy()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => Yii::$app->request->post('Appraisal_No'),
            'employeeNo' => Yii::$app->request->post('Employee_No'),
            'sendEmail' => 0,
            'approvalURL' => 1, //Ask korir to change this to text currently set to int
            'rejectionComments' => Yii::$app->request->post('comment')
        ];

        $result = Yii::$app->navhelper->IanSendMYAppraisaBackToAppraisee($service, $data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_string($result)) {
            //Yii::$app->session->setFlash('success', 'Mid Year Appraisal Rejected and Sent Back to Appraisee Successfully.', true);
            //return $this->redirect(['viewsubmitted','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
            return ['note' => '<div class="alert alert-success alert-dismissable">Mid Year Appraisal Rejected and Sent Back to Appraisee Successfully.</div>'];
        } else {

            //Yii::$app->session->setFlash('error', 'Error Rejecting Mid Year Appraisal : '. $result);
            //return $this->redirect(['viewsubmitted','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
            return ['note' => '<div class="alert alert-danger alert-dismissable">Error Rejecting Mid Year Appraisal : ' . $result . '</div>'];
        }
    }


    // Send MY Appraisal to Overview

    public function actionMyToOverview($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo])
        ];

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'IanSendMYAppraisalToOverViewManager');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Mid Year Perfomance Appraisal Submitted Successfully to Overview.', true);
            return $this->redirect(['mysupervisorlist']);
        } else {

            Yii::$app->session->setFlash('error', 'Error  : ' . $result);
            return $this->redirect(['mysupervisorlist']);
        }
    }



    //Submit End Year Appraisal for Approval

    public function actionSubmitey($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo])
        ];

        $result = Yii::$app->navhelper->IanSendEYAppraisalForApproval($service, $data);

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'End Year Perfomance Appraisal Submitted Successfully.', true);
            return $this->redirect(['eyappraiseelist']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Submitting End Year Performance Appraisal : ' . $result);
            return $this->redirect(['eyappraiseelist']);
        }
    }


    //Overview Appraisal Approval
    public function actionApprovegoalsetting($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 0,
            'approvalURL' => 1
        ];

        $result = Yii::$app->navhelper->codeunit($service, $data, 'ApproveGoalSetting');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Goal Setting Approved Successfully.', true);
            return $this->redirect(['overviewgoalslist']);
        } else {

            Yii::$app->session->setFlash('error', 'Error : ' . $result);
            return $this->redirect(['overviewgoalslist']);
        }
    }

    // Overview Appraisal  Rejection 

    public function actionRejectgoalsetting()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => Yii::$app->request->post('Appraisal_No'),
            'employeeNo' => Yii::$app->request->post('Employee_No'),
            'sendEmail' => 1,
            'approvalURL' => 1, //Ask korir to change this to text currently set to int
            'rejectionComments' => Yii::$app->request->post('comment')
        ];

        $result = Yii::$app->navhelper->codeunit($service, $data, 'SendGoalSettingBackToLineManager');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_string($result)) {
            //Yii::$app->session->setFlash('success', 'End Year Appraisal Rejected and Sent Back to Appraisee Successfully.', true);
            //return $this->redirect(['viewsubmitted','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
            return ['note' => '<div class="alert alert-success alert-dismissable"> Appraisal Goal Setting Rejected and Sent Back to Line Successfully.</div>'];
        } else {

            //Yii::$app->session->setFlash('error', 'Error Rejecting End Year Appraisal : '. $result);
            //return $this->redirect(['viewsubmitted','Appraisal_No' => $appraisalNo,'Employee_No' => $employeeNo]);
            return ['note' => '<div class="alert  alert-danger alert-dismissable">Error  : ' . $result . '</div>'];
        }
    }

    //Overview reject ey



    public function actionOvrejectey()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => Yii::$app->request->post('Appraisal_No'),
            'employeeNo' => Yii::$app->request->post('Employee_No'),
            'sendEmail' => 1,
            'approvalURL' => 1, //Ask korir to change this to text currently set to int
            'rejectionComments' => Yii::$app->request->post('comment')
        ];

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'IanSendEYAppraisaBackToLineManager');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_string($result)) {

            return ['note' => '<div class="alert alert-success alert-dismissable">End Year Appraisal Rejected and Sent Back to Appraisee Successfully.</div>'];
        } else {


            return ['note' => '<div class="alert  alert-danger alert-dismissable">Error  : ' . $result . '</div>'];
        }
    }

    //send appraisal to peer 1

    public function actionSendeytooverview($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]), //Ask korir to change this to text currently set to int

        ];

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'IanSendEYAppraisalToOverview');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'End Year Appraisal Sent to Overview Mgr. Successfully.', true);
            return $this->redirect(['eysupervisorlist']);
        } else {

            Yii::$app->session->setFlash('error', 'Error  : ' . $result);
            return $this->redirect(['eysupervisorlist']);
        }
    }

    //send appraisal to peer 2

    public function actionSendpeer2($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 0,
            'approvalURL' => 1, //Ask korir to change this to text currently set to int

        ];

        $result = Yii::$app->navhelper->IanSendEYAppraisalToPeer2($service, $data);

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'End Year Appraisal Sent to Peer 2 Successfully.', true);
            return $this->redirect(['viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        } else {

            Yii::$app->session->setFlash('error', 'Error Sending Appraisal to Peer 2 for evaluation : ' . $result);
            return $this->redirect(['viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        }
    }

    //send End Year Appraisal Back to Supervisor from peer

    public function actionSendbacktosupervisor($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 0,
            'approvalURL' => 1, //Ask korir to change this to text currently set to int

        ];

        $result = Yii::$app->navhelper->IanSendEYAppraisaBackToSupervisorFromPeer($service, $data);

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'End Year Appraisal Sent back to supervisor from peer  Successfully.', true);
            return $this->redirect(['viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        } else {

            Yii::$app->session->setFlash('error', 'Error Sending End Year Appraisal to Supervisor from Peer : ' . $result);
            return $this->redirect(['viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        }
    }

    //Send End-Year Appraisal to Agreement Level

    public function actionSendtoagreementlevel($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]),

        ];

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'IanSendEYAppraisalToAgreementLevel');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'End Year Appraisal Sent Agreement Level  Successfully.', true);
            return $this->redirect(['eysupervisorlist']);
        } else {

            Yii::$app->session->setFlash('error', 'Error  : ' . $result);
            return $this->redirect(['eysupervisorlist']);
        }
    }

    //Get Employees this is just for selecting peer1 and Peer 2

    public function getEmployees()
    {
        $service = Yii::$app->params['ServiceName']['Employees'];

        $employees = \Yii::$app->navhelper->getData($service);
        $res = [];
        foreach ($employees as $e) {
            if (!empty($e->User_ID)) {
                $res[] = [
                    'No' => $e->No,
                    'Full_Name' => $e->Full_Name
                ];
            }
        }
        return $res;
    }

    //Generate Appraisal Report

    public function actionReport()
    {

        $service = Yii::$app->params['ServiceName']['PortalReports'];

        if (Yii::$app->request->post()) {

            $data = [
                'appraisalNo' => Yii::$app->request->post('appraisalNo'),
                'employeeNo' => Yii::$app->request->post('employeeNo')
            ];
            //$path = Yii::$app->navhelper->IanGenerateAppraisalReport($service,$data);
            $path = Yii::$app->navhelper->CodeUnit($service, $data, 'IanGenerateNewEmployeeAppraisalReport');
            //Yii::$app->recruitment->printrr($path);
            if (!isset($path['return_value']) || !is_file($path['return_value'])) {

                return $this->render('report', [
                    'report' => false,
                    'message' => isset($path['return_value']) ? $path['return_value'] : 'Report is not available',
                ]);
            }
            $binary = file_get_contents($path['return_value']); //fopen($path['return_value'],'rb');
            $content = chunk_split(base64_encode($binary));
            //delete the file after getting it's contents --> This is some house keeping
            // unlink($path['return_value']);

            // Yii::$app->recruitment->printrr($path);
            return $this->render('report', [
                'report' => true,
                'content' => $content,
            ]);
        }

        return $this->render('report', [
            'report' => false,
            'content' => '',
        ]);
    }

    public function actionBacktoemp()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $appraisalNo = Yii::$app->request->post('Appraisal_No');
        $employeeNo = Yii::$app->request->post('Employee_No');
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/view', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]),
            'rejectionComments' => Yii::$app->request->post('comment'),
        ];

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'SendGoalSettingBackToAppraisee');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Appraisal Sent Back to Appraisee Successfully.', true);
            return $this->redirect(['submitted']);
        } else {

            Yii::$app->session->setFlash('error', 'Error  : ' . $result);
            return $this->redirect(['view', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]);
        }
    }


    public function actionBacktolinemgr()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $appraisalNo = Yii::$app->request->post('Appraisal_No');
        $employeeNo = Yii::$app->request->post('Employee_No');
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]),
            'rejectionComments' => Yii::$app->request->post('comment'),
        ];

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'IanSendGoalSettingBackToLineManager');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Goals Sent Back Line Manager with comments Successfully.', true);
            return $this->redirect(['myoverviewlist']);
        } else {

            Yii::$app->session->setFlash('error', 'Error : ' . $result);
            return $this->redirect(['myoverviewlist']);
        }
    }

    public function actionMybacktolinemgr()
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $appraisalNo = Yii::$app->request->post('Appraisal_No');
        $employeeNo = Yii::$app->request->post('Employee_No');
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,
            'approvalURL' => Yii::$app->urlManager->createAbsoluteUrl(['appraisal/viewsubmitted', 'Appraisal_No' => $appraisalNo, 'Employee_No' => $employeeNo]),
            'rejectionComments' => Yii::$app->request->post('comment'),
        ];

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'IanSendMYAppraisaBackLineManager');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Mid Year Appraisal Sent Back to Line Mgr Successfully.', true);
            return $this->redirect(['myoverviewlist']);
        } else {

            Yii::$app->session->setFlash('error', 'Error : ' . $result);
            return $this->redirect(['myoverviewlist']);
        }
    }

    /*Overview Mgr Goals Approval*/

    public function actionApprovegoals($appraisalNo, $employeeNo)
    {
        $service = Yii::$app->params['ServiceName']['AppraisalManagement'];
        $data = [
            'appraisalNo' => $appraisalNo,
            'employeeNo' => $employeeNo,
            'sendEmail' => 1,

        ];

        $result = Yii::$app->navhelper->CodeUnit($service, $data, 'IanApproveGoalSetting');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Probation Goals Approved Successfully.', true);
            return $this->redirect(['overviewgoalslist']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Approving Probation Goals  : ' . $result);
            return $this->redirect(['overviewgoalslist']);
        }
    }

    // A universal data commital function

    public function actionCommit()
    {
        $commitService = Yii::$app->request->post('service');
        $key = Yii::$app->request->post('key');
        $name = Yii::$app->request->post('name');
        $value = Yii::$app->request->post('value');

        $service = Yii::$app->params['ServiceName'][$commitService];
        $request = Yii::$app->navhelper->readByKey($service, $key);
        $data = [];
        if (is_object($request)) {
            $data = [
                'Key' => $request->Key,
                $name => $value
            ];
        } else {
            Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
            return ['error' => $request];
        }

        $result = Yii::$app->navhelper->updateData($service, $data);
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $result;
    }

    // A universal Line Addtion Method

    public function actionAddLine()
    {
        // get arguments as a json payload and cort it into a php array -- @todo
        $json = file_get_contents('php://input');

        // Convert it into a PHP object
        $data = json_decode($json);

        $service = Yii::$app->params['ServiceName'][$data->Service];

        // Remove unwanted attributes to payload attribute
        unset($data->Service);
        unset($data->Line_No);

        // Insert Record

        $result = Yii::$app->navhelper->postData($service, $data);

        if (is_object($result)) {
            return [
                'note' => 'Record Created Successfully.',
                'result' => $result
            ];
        } else {
            return ['note' => $result];
        }
    }

    public function actionAddRatingLine()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $service = Yii::$app->params['ServiceName'][$data->Service];

        // Remove unwanted attributes to payload attribute
        unset($data->Service);
        // Insert Record
        $result = Yii::$app->navhelper->postData($service, $data);
        if (is_object($result)) {
            return [
                'note' => 'Record Created Successfully.',
                'result' => $result
            ];
        } else {
            return ['note' => $result];
        }
    }

    // A universal line delete functionality

    public function actionDeleteLine($Service, $Key)
    {
        $service = Yii::$app->params['ServiceName'][$Service];
        $result = Yii::$app->navhelper->deleteData($service, Yii::$app->request->get('Key'));
        Yii::$app->session->setFlash('success', 'Record Deleted Successfully.', true);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_string($result)) {
            return [
                'note' => 'Record Deleted Successfully.',
                'result' => $result
            ];
        } else {
            return ['note' => $result];
        }
    }

    public function actionPerspective()
    {
       $data = Yii::$app->navhelper->dropdown('AppraisalPerspectives', 'Code', 'Description', [], ['Code']);
       return $data;
    }

    public function actionKpiStatus()
    {
       $data = [
        '_blank_' => '_blank_',
        'Achieved' => 'Achieved',
        'Not_Achieved' => 'Not_Achieved'
       ];
       return $data;
    }

    public function actionTrainingCategories()
    {
       $data = Yii::$app->navhelper->dropdown('TrainingCategories', 'Code', 'Description', [], ['Code']);
       return $data;
    }
    public function actionRating()
    {
        $data = Yii::$app->navhelper->dropdown('AppraisalRating', 'Code', 'Description');
        return $data;
    }
}
