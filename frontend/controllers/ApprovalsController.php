<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/25/2020
 * Time: 3:55 PM
 */


namespace frontend\controllers;

use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

use frontend\models\Leave;
use yii\web\Response;

class ApprovalsController extends Controller
{

    public $leaveWorkflows = ['Leave_Application', 'Leave_Reinstatement', 'Leave_Reimbursement'];

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'index'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'only' => ['getapprovals', 'open', 'rejected', 'approved', 'super-approved', 'super-rejected'],
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

        $ExceptedActions = [
            'reject-request'
        ];

        if (in_array($action->id, $ExceptedActions)) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {

        return $this->render('index');
    }


    /* start Rendering of dashboard approval action pages*/

    public function actionOpenApprovals()
    {

        return $this->render('open');
    }

    public function actionRejectedApprovals()
    {

        return $this->render('rejected');
    }


    public function actionApprovedApprovals()
    {

        return $this->render('approved');
    }

    public function actionSapproved()
    {
        return $this->render('sapproved');
    }

    public function actionSrejected()
    {
        return $this->render('srejected');
    }

    /* End Rendering of dashboard approval action pages*/


    public function actionCreate()
    {


        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['leaveApplicationCard'];

        if (\Yii::$app->request->get('create')) {
            //make an initial empty request to nav
            $req = Yii::$app->navhelper->postData($service, []);
            $modeldata = (get_object_vars($req));
            foreach ($modeldata as $key => $val) {
                if (is_object($val)) continue;
                $model->$key = $val;
            }

            $model->Start_Date = date('Y-m-d');
            $model->End_Date = date('Y-m-d');
        }

        $leaveTypes = $this->getLeaveTypes();
        $employees = $this->getEmployees();
        $message = "";
        $success = false;

        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->post()) {

            $result = Yii::$app->navhelper->updateData($service, Yii::$app->request->post()['Leave']);

            if (is_object($result)) {

                Yii::$app->session->setFlash('success', 'Leave request Created Successfully', true);
                return $this->redirect(['view', 'ApplicationNo' => $result->Application_No]);
            } else {

                Yii::$app->session->setFlash('error', 'Error Creating Leave request: ' . $result, true);
                return $this->redirect(['index']);
            }
        }



        return $this->render('create', [
            'model' => $model,
            'leaveTypes' => ArrayHelper::map($leaveTypes, 'Code', 'Description'),
            'relievers' => ArrayHelper::map($employees, 'No', 'Full_Name'),

        ]);
    }


    public function actionUpdate($ApplicationNo)
    {
        $service = Yii::$app->params['ServiceName']['leaveApplicationCard'];
        $leaveTypes = $this->getLeaveTypes();
        $employees = $this->getEmployees();


        $filter = [
            'Application_No' => $ApplicationNo
        ];
        $result = Yii::$app->navhelper->getData($service, $filter);



        //load nav result to model
        $leaveModel = new Leave();

        $model = $this->loadtomodel($result[0], $leaveModel);



        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->post()) {
            $result = Yii::$app->navhelper->updateData($model);


            if (!empty($result)) {
                Yii::$app->session->setFlash('success', 'Leave request Updated Successfully', true);
                return $this->redirect(['view', 'ApplicationNo' => $result->Application_No]);
            } else {
                Yii::$app->session->setFlash('error', 'Error Updating Leave Request : ' . $result, true);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'leaveTypes' => ArrayHelper::map($leaveTypes, 'Code', 'Description'),
            'relievers' => ArrayHelper::map($employees, 'No', 'Full_Name')
        ]);
    }

    public function actionView($ApplicationNo)
    {
        $service = Yii::$app->params['ServiceName']['leaveApplicationCard'];

        $filter = [
            'Application_No' => $ApplicationNo
        ];

        $leave = Yii::$app->navhelper->getData($service, $filter);


        return $this->render('view', [
            'leave' => $leave[0],
        ]);
    }


    public function actionApprovalRequest($app)
    {
        $service = Yii::$app->params['ServiceName']['Portal_Workflows'];
        $data = ['applicationNo' => $app];

        $request = Yii::$app->navhelper->SendLeaveApprovalRequest($service, $data);

        print '<pre>';
        print_r($request);
        return;
    }

    /*Data access functions */

    public function actionLeavebalances()
    {

        $balances = $this->Getleavebalance();

        return $this->render('leavebalances', ['balances' => $balances]);
    }

    public function actionGetapprovals()
    {
        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];

        $filter = [
            'Approver_ID' => Yii::$app->user->identity->{'User ID'},
            'Status' => 'Open'
        ];


        $approvals = \Yii::$app->navhelper->getData($service, $filter);
        //Yii::$app->recruitment->printrr($filter);

        $result = [];

        $leaveWorkflows = ['Leave_Application', 'Leave_Reinstatement', 'Leave_Reimbursement'];
        $Rejectlink = "";

        if (!is_object($approvals)) {
            foreach ($approvals as $app) {

                $Approvelink = Html::a('<i class="fas fa-paper-plane"></i>', ['approve-request', 'recordID' => $app->Record_ID_to_Approve], ['title' => 'Approve Request', 'class' => 'btn btn-success btn-xs']);
                $Delegatelink = Html::a('<i class="fas fa-paper-plane"></i> Delegate', ['delegate-request', 'recordID' => $app->Record_ID_to_Approve], ['title' => 'Delegate Request', 'class' => 'btn btn-info btn-xs']);

                $Rejectlink = ($app->Status == 'Open') ? Html::a('Reject Request', ['reject-request', 'recordID' => $app->Record_ID_to_Approve], [
                    'class' => 'btn btn-warning reject btn-xs',
                    'rel' => $app->Document_No,
                    'rev' => $app->Record_ID_to_Approve,
                    'name' => $app->Table_ID
                ]) : "";
                /*Card Details */
                if(!empty($app->Record_ID_to_Approve)){
                    $app->Document_Type = $this->getDocumentType($app->Record_ID_to_Approve);
                }else{
                    $app->Document_Type = '';
                }
                


                if ($app->Document_Type == 'StaffClaim') {
                    $detailsLink = Html::a('View Details', ['staff-claim/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                }  elseif ($app->Document_Type == 'ImprestRequest') {
                    $detailsLink = Html::a('Request Details', ['imprest/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } 
                elseif ($app->Document_Type == 'LeaveApplication') {
                    $detailsLink = Html::a('View Details', ['leave/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'LeaveReimbursement') {
                    $detailsLink = Html::a('View Details', ['leave-reimburse/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Application') {
                    $detailsLink = Html::a('View Details', ['leave/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Contract_Renewal') {
                    $detailsLink = Html::a('View Details', ['contractrenewal/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Employee_Exit') {
                    $detailsLink = Html::a('View Details', ['exit/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'LeavePlan') {
                    $detailsLink = Html::a('View Details', ['leaveplan/view', 'Plan_No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Recall') {
                    $detailsLink = Html::a('View Details', ['leaverecall/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Change_Request') {
                    $detailsLink = Html::a('View Details', ['change-request/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Asset_Assignment') {
                    $detailsLink = Html::a('View Details', ['asset-assignment/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'SalaryAdvance') {
                    $detailsLink = Html::a('View Details', ['salaryadvance/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Overtime') {
                    $detailsLink = Html::a('View Details', ['overtime/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Training_Application') {
                    $detailsLink = Html::a('View Details', ['training-applications/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'PurchaseRequisition') {
                    $detailsLink = Html::a('View Details', ['purchase-requisition/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'StoreRequisition') {
                    $detailsLink = Html::a('View Details', ['storerequisition/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } 
                
                else { //Employee_Exit
                    $detailsLink = '';
                }



                $result['data'][] = [
                    'Key' => $app->Key,
                    'Entry_No' => $app->Entry_No,
                    'Details' => !empty($app->Details) ? $app->Details : 'NOT SET',
                    'Comment' => !empty($app->Comment) ? $app->Comment : '',
                    'Sender_ID' => !empty($app->Sender_Name) ? $app->Sender_Name : '',
                    'Document_Type' => !empty($app->Document_Type) ? $app->Document_Type : '',
                    'Status' => !empty($app->Status) ? $app->Status : $app->Status,
                    'Document_No' => !empty($app->Document_No) ? $app->Document_No : '',
                    'Approvelink' => !empty($Approvelink) ? $Approvelink : '',
                    'Delegatelink' => !empty($Delegatelink) ? $Delegatelink : '',
                    'Rejectlink' => $Rejectlink,
                    'details' => $detailsLink
                ];
            }
        }


        return $result;
    }

    Public function getDocumentType($recordID)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];
        $data = [
            'recordID' => $recordID
        ];
        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'GetDocumentType');
        //Yii::$app->recruitment->printrr($result);
       return $result['return_value'];
    }

    public function actionApproveRequest($recordID)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'recordID' => $recordID
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'ApproveDocument');


        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Approval Request Approved Successfully.', true);
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Error Approving Approval Approval Request.  : ' . $result);
            return $this->redirect(['index']);
        }
    }

    // Deletegate

    public function actionDelegateRequest($recordID)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'recordID' => $recordID
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'DelegateDocument');


        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Approval Request Delegated Successfully.', true);
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Error Approving Approval Approval Request.  : ' . $result);
            return $this->redirect(['index']);
        }
    }

    public function actionRejectRequest()
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];
        $Commentservice = Yii::$app->params['ServiceName']['ApprovalCommentsWeb'];

        if (Yii::$app->request->post()) {
            $comment = Yii::$app->request->post('comment');
            $documentno = Yii::$app->request->post('documentNo');
            $Record_ID_to_Approve = Yii::$app->request->post('Record_ID_to_Approve');
            $Table_ID = Yii::$app->request->post('Table_ID');


            $commentData = [
                'comment' => $comment,
                'recordID' => $Record_ID_to_Approve,
                'userID' => Yii::$app->user->identity->{'User ID'},
                'docNo' => $documentno
            ];

            // Yii::$app->recruitment->printrr($commentData);
            $data = [
                'recordID' => $Record_ID_to_Approve
            ];
            //save comment
            $Commentrequest = Yii::$app->navhelper->PortalWorkFlows($service, $commentData, 'ApprovalComment');
            // Call rejection cu function

            if (is_string($Commentrequest)) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['note' => '<div class="alert alert-danger">Error Rej Rejecting Request: ' . $Commentrequest . '</div>'];
            }

            $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'RejectDocument');



            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (!is_string($result)) {
                return ['note' => '<div class="alert alert-success">Request Rejected Successfully. </div>'];
            } else {
                return ['note' => '<div class="alert alert-danger">Error Rejecting Request: ' . $result . '</div>'];
            }
        }
    }





    public function actionApproveLeave($app)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $app,
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'IanApproveLeave');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Request Approved Successfully.', true);
            return $this->redirect(['index']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Approving Request.  : ' . $result);
            return $this->redirect(['index']);
        }
    }

    public function actionApproveRecall($app)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $app,
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'IanApproveLeaveRecall');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Request Approved Successfully.', true);
            return $this->redirect(['index']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Approving Request.  : ' . $result);
            return $this->redirect(['index']);
        }
    }

    /* Approve Leave Plan */

    public function actionApproveLeavePlan($app)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'applicationNo' => $app,
        ];


        $result = Yii::$app->navhelper->PortalWorkFlows($service, $data, 'IanApproveLeavePlan');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Request Approved Successfully.', true);
            return $this->redirect(['index']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Approving Request.  : ' . $result);
            return $this->redirect(['index']);
        }
    }

    public function getName($userID)
    {

        //get Employee No
        $user = \common\models\User::find()->where(['User ID' => $userID])->one();
        $No = $user->{'Employee_No'};
        //Get Employees full name
        $service = Yii::$app->params['ServiceName']['Employees'];
        $filter = [
            'No' => $No
        ];

        $results = Yii::$app->navhelper->getData($service, $filter);
        return $results[0]->FullName;
    }




    public function loadtomodel($obj, $model)
    {

        if (!is_object($obj)) {
            return false;
        }
        $modeldata = (get_object_vars($obj));
        foreach ($modeldata as $key => $val) {
            if (is_object($val)) continue;
            $model->$key = $val;
        }

        return $model;
    }

    /*Open Approvals*/

    public function actionOpen()
    {

        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];

        $filter = [
            'Sender_No' => Yii::$app->user->identity->{'Employee No_'},
            'Status' => 'Open'
        ];
        $approvals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (!is_object($approvals)) {
            foreach ($approvals as $app) {


                /*Card Details */


                if ($app->Document_Type == 'Staff_Board_Allowance') {
                    $detailsLink = Html::a('View Details', ['fund-requisition/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Imprest') {
                    $detailsLink = Html::a('Request Details', ['imprest/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Reimbursement') {
                    $detailsLink = Html::a('View Details', ['leave-reimburse/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Application') {
                    $detailsLink = Html::a('View Details', ['leave/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Contract_Renewal') {
                    $detailsLink = Html::a('View Details', ['contractrenewal/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Employee_Exit') {
                    $detailsLink = Html::a('View Details', ['exit/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Plan') {
                    $detailsLink = Html::a('View Details', ['leaveplan/view', 'Plan_No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Recall') {
                    $detailsLink = Html::a('View Details', ['leaverecall/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Change_Request') {
                    $detailsLink = Html::a('View Details', ['change-request/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Asset_Assignment') {
                    $detailsLink = Html::a('View Details', ['asset-assignment/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Salary_Advance') {
                    $detailsLink = Html::a('View Details', ['salaryadvance/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Overtime_Application') {
                    $detailsLink = Html::a('View Details', ['overtime/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } else { //Employee_Exit
                    $detailsLink = '';
                }





                $result['data'][] = [
                    'Key' => $app->Key,
                    'Entry_No' => $app->Entry_No,
                    'Details' => !empty($app->Details) ? $app->Details : 'NOT SET',
                    'Comment' => $app->Comment,
                    'Sender_ID' => $app->Sender_Name,
                    'Document_Type' => $app->Document_Type,
                    'Status' => $app->Status,
                    'Document_No' => $app->Document_No,
                    'details' => $detailsLink

                ];
            }
        }


        return $result;
    }

    public function actionRejected()
    {

        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];

        $filter = [
            'Sender_No' => Yii::$app->user->identity->{'Employee No_'},
            'Status' => 'Rejected'
        ];
        $approvals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (!is_object($approvals)) {
            foreach ($approvals as $app) {

                /*Card Details */


                if ($app->Document_Type == 'Staff_Board_Allowance') {
                    $detailsLink = Html::a('View Details', ['fund-requisition/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Imprest') {
                    $detailsLink = Html::a('Request Details', ['imprest/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Reimbursement') {
                    $detailsLink = Html::a('View Details', ['leave-reimburse/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Application') {
                    $detailsLink = Html::a('View Details', ['leave/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Contract_Renewal') {
                    $detailsLink = Html::a('View Details', ['contractrenewal/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Employee_Exit') {
                    $detailsLink = Html::a('View Details', ['exit/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Plan') {
                    $detailsLink = Html::a('View Details', ['leaveplan/view', 'Plan_No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Recall') {
                    $detailsLink = Html::a('View Details', ['leaverecall/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Change_Request') {
                    $detailsLink = Html::a('View Details', ['change-request/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Asset_Assignment') {
                    $detailsLink = Html::a('View Details', ['asset-assignment/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Salary_Advance') {
                    $detailsLink = Html::a('View Details', ['salaryadvance/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Overtime_Application') {
                    $detailsLink = Html::a('View Details', ['overtime/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } else { //Employee_Exit
                    $detailsLink = '';
                }





                $result['data'][] = [
                    'Key' => $app->Key,
                    'Entry_No' => $app->Entry_No,
                    'Details' => !empty($app->Details) ? $app->Details : 'NOT SET',
                    'Comment' => $app->Comment,
                    'Sender_ID' => $app->Sender_Name,
                    'Document_Type' => $app->Document_Type,
                    'Status' => $app->Status,
                    'Document_No' => $app->Document_No,
                    'details' => $detailsLink

                ];
            }
        }


        return $result;
    }

    public function actionApproved()
    {


        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];

        $filter = [
            'Sender_No' => Yii::$app->user->identity->{'Employee No_'},
            'Status' => 'Approved'
        ];
        $approvals = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (!is_object($approvals)) {
            foreach ($approvals as $app) {

                /*Card Details */


                if ($app->Document_Type == 'Staff_Board_Allowance') {
                    $detailsLink = Html::a('View Details', ['fund-requisition/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Imprest') {
                    $detailsLink = Html::a('Request Details', ['imprest/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Reimbursement') {
                    $detailsLink = Html::a('View Details', ['leave-reimburse/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Application') {
                    $detailsLink = Html::a('View Details', ['leave/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Contract_Renewal') {
                    $detailsLink = Html::a('View Details', ['contractrenewal/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Employee_Exit') {
                    $detailsLink = Html::a('View Details', ['exit/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Plan') {
                    $detailsLink = Html::a('View Details', ['leaveplan/view', 'Plan_No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Recall') {
                    $detailsLink = Html::a('View Details', ['leaverecall/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Change_Request') {
                    $detailsLink = Html::a('View Details', ['change-request/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Asset_Assignment') {
                    $detailsLink = Html::a('View Details', ['asset-assignment/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Salary_Advance') {
                    $detailsLink = Html::a('View Details', ['salaryadvance/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Overtime_Application') {
                    $detailsLink = Html::a('View Details', ['overtime/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } else { //Employee_Exit
                    $detailsLink = '';
                }





                $result['data'][] = [
                    'Key' => $app->Key,
                    'Entry_No' => $app->Entry_No,
                    'Details' => !empty($app->Details) ? $app->Details : 'NOT SET',
                    'Comment' => $app->Comment,
                    'Sender_ID' => $app->Sender_Name,
                    'Document_Type' => $app->Document_Type,
                    'Status' => $app->Status,
                    'Document_No' => $app->Document_No,
                    'details' => $detailsLink

                ];
            }
        }


        return $result;
    }

    /*Get Approvals based on supervisor actions -Approved or Rejected -*/

    /*Request I have approved*/

    public function actionSuperApproved()
    {

        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];
        $filter = [
            'Approver_No' => Yii::$app->user->identity->{'Employee No_'},
            'Status' => 'Approved'
        ];
        $approvals = Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (!is_object($approvals)) {
            foreach ($approvals as $app) {

                /*Card Details */


                if ($app->Document_Type == 'Staff_Board_Allowance') {
                    $detailsLink = Html::a('View Details', ['fund-requisition/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Imprest') {
                    $detailsLink = Html::a('Request Details', ['imprest/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Reimbursement') {
                    $detailsLink = Html::a('View Details', ['leave-reimburse/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Application') {
                    $detailsLink = Html::a('View Details', ['leave/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Contract_Renewal') {
                    $detailsLink = Html::a('View Details', ['contractrenewal/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Employee_Exit') {
                    $detailsLink = Html::a('View Details', ['exit/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Plan') {
                    $detailsLink = Html::a('View Details', ['leaveplan/view', 'Plan_No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Recall') {
                    $detailsLink = Html::a('View Details', ['leaverecall/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Change_Request') {
                    $detailsLink = Html::a('View Details', ['change-request/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Asset_Assignment') {
                    $detailsLink = Html::a('View Details', ['asset-assignment/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Salary_Advance') {
                    $detailsLink = Html::a('View Details', ['salaryadvance/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Overtime_Application') {
                    $detailsLink = Html::a('View Details', ['overtime/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } else { //Employee_Exit
                    $detailsLink = '';
                }





                $result['data'][] = [
                    'Key' => $app->Key,
                    'Entry_No' => $app->Entry_No,
                    'Details' => !empty($app->Details) ? $app->Details : 'NOT SET',
                    'Comment' => $app->Comment,
                    'Sender_ID' => $app->Sender_Name,
                    'Document_Type' => $app->Document_Type,
                    'Status' => $app->Status,
                    'Document_No' => $app->Document_No,
                    'details' => $detailsLink

                ];
            }
        }


        return $result;
    }


    /* Requests I have Rejected */

    public function actionSuperRejected()
    {

        $service = Yii::$app->params['ServiceName']['RequestsTo_ApprovePortal'];
        $filter = [
            'Approver_No' => Yii::$app->user->identity->{'Employee No_'},
            'Status' => 'Rejected'
        ];
        $approvals = Yii::$app->navhelper->getData($service, $filter);

        $result = [];

        if (!is_object($approvals)) {
            foreach ($approvals as $app) {

                /*Card Details */


                if ($app->Document_Type == 'Staff_Board_Allowance') {
                    $detailsLink = Html::a('View Details', ['fund-requisition/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Imprest') {
                    $detailsLink = Html::a('Request Details', ['imprest/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Reimbursement') {
                    $detailsLink = Html::a('View Details', ['leave-reimburse/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Application') {
                    $detailsLink = Html::a('View Details', ['leave/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Contract_Renewal') {
                    $detailsLink = Html::a('View Details', ['contractrenewal/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Employee_Exit') {
                    $detailsLink = Html::a('View Details', ['exit/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Plan') {
                    $detailsLink = Html::a('View Details', ['leaveplan/view', 'Plan_No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Leave_Recall') {
                    $detailsLink = Html::a('View Details', ['leaverecall/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Change_Request') {
                    $detailsLink = Html::a('View Details', ['change-request/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Asset_Assignment') {
                    $detailsLink = Html::a('View Details', ['asset-assignment/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Salary_Advance') {
                    $detailsLink = Html::a('View Details', ['salaryadvance/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } elseif ($app->Document_Type == 'Overtime_Application') {
                    $detailsLink = Html::a('View Details', ['overtime/view', 'No' => $app->Document_No, 'Approval' => true], ['class' => 'btn btn-outline-info btn-xs', 'target' => '_blank']);
                } else { //Employee_Exit
                    $detailsLink = '';
                }





                $result['data'][] = [
                    'Key' => $app->Key,
                    'Entry_No' => $app->Entry_No,
                    'Details' => !empty($app->Details) ? $app->Details : 'NOT SET',
                    'Comment' => $app->Comment,
                    'Sender_ID' => $app->Sender_Name,
                    'Document_Type' => $app->Document_Type,
                    'Status' => $app->Status,
                    'Document_No' => $app->Document_No,
                    'details' => $detailsLink

                ];
            }
        }


        return $result;
    }
}
