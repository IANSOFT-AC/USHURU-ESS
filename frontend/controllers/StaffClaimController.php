<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;


use frontend\models\Fundrequisition;
use frontend\models\Imprestcard;
use frontend\models\Imprestsurrendercard;
use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

use yii\web\Response;
use kartik\mpdf\Pdf;
use yii\helpers\FileHelper;

class StaffClaimController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'index', 'requestlist', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'requestlist', 'create', 'update', 'delete'],
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
                'only' => ['list', 'list-approved'],
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
            'dimension1', 'dimension2', 'transactiontypes',
            'grants', 'objectives', 'outputs', 'outcome',
            'activities', 'partners', 'donors', 'upload', 'accounts', 'rates',
            'employees', 'expense-codes'
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

    public function actionApproved()
    {
        return $this->render('approved');
    }

    public function actionSurrenderlist()
    {

        return $this->render('surrenderlist');
    }

    public function actionCreate()
    {

        $model = new Fundrequisition();
        $service = Yii::$app->params['ServiceName']['StaffClaimCard'];

        /*Do initial request */

        $model->Employee_No = Yii::$app->user->identity->{'Employee No_'};

        $request = Yii::$app->navhelper->postData($service, $model);

        if (is_object($request)) {
            Yii::$app->navhelper->loadmodel($request, $model);
            return $this->redirect(['update', 'Key' => $request->Key]);
        } else {
            Yii::$app->session->setFlash('error', $request);
            return $this->redirect(['index']);
        }
    }





    public function actionUpdate($No = '', $Key = '')
    {
        $service = Yii::$app->params['ServiceName']['StaffClaimCard'];
        $model = new Fundrequisition();

        if (!empty($No)) {
            $result = Yii::$app->navhelper->findOne($service, ['No' => $No]);
        } elseif (!empty($Key)) {

            $result = Yii::$app->navhelper->readByKey($service, urldecode($Key));
        }


        if (is_object($result)) {
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result, $model); //$this->loadtomodeEmployee_Nol($result[0],$Expmodel);
        } else {
            Yii::$app->session->setFlash('error', $result, true);
            return $this->redirect(['index']);
        }

        $recordID = $this->getRecordID($service, $model->Key);


        return $this->render('update', [
            'model' => $model,
            'document' => $result,
            'recordID' => $recordID
        ]);
    }

    public function actionDelete()
    {
        $service = Yii::$app->params['ServiceName']['CareerDevStrengths'];
        $result = Yii::$app->navhelper->deleteData($service, Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_string($result)) {

            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        } else {
            return ['note' => '<div class="alert alert-danger">Error Purging Record: ' . $result . '</div>'];
        }
    }

    public function actionView($No = '', $Key = '')
    {
        $service = Yii::$app->params['ServiceName']['StaffClaimCard'];
        $model = new Fundrequisition();

        if (!empty($No)) {
            $result = Yii::$app->navhelper->findOne($service, ['No' => $No]);
        } elseif (!empty($Key)) {
            $result = Yii::$app->navhelper->readByKey($service, $Key);
        }

        //load nav result to model
        $model = $this->loadtomodel($result, $model);
        $recordID = $this->getRecordID($service, $model->Key);

        //Yii::$app->recruitment->printrr($model);

        return $this->render('view', [
            'model' => $model,
            'document' => $result,
            'attachments' => [],
            'recordID' => $recordID
        ]);
    }

    public function getRecordID($service, $Key)
    {
        return Yii::$app->navhelper->getRecordID($service, $Key);
    }



    // Get Fund Request list

    public function actionList()
    {
        $service = Yii::$app->params['ServiceName']['StaffClaimList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
        ];
        //Yii::$app->recruitment->printrr( );
        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];


        if (is_array($result)) {
            foreach ($results as $item) {

                if (empty($item->No)) {
                    continue;
                }
                $recordID = $this->getRecordID($service, $item->Key);
                $link = $updateLink = $deleteLink =  '';
                $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view', 'Key' => $item->Key], ['class' => 'btn btn-outline-primary btn-xs mx-1', 'title' => 'View Card']);
                if ($item->Status == 'Open') {
                    $link = Html::a('<i class="fas fa-paper-plane"></i>', ['send-for-approval', 'recordID' => $recordID], ['title' => 'Send Approval Request', 'class' => 'mx-1 btn btn-primary btn-xs', 'title' => 'Make approval request.']);

                    $updateLink = Html::a('<i class="far fa-edit"></i>', ['update', 'Key' => $item->Key], ['class' => 'mx-1 btn btn-info btn-xs', 'title' => 'Update Document']);
                } else if ($item->Status == 'Pending_Approval') {
                    $link = Html::a('<i class="fas fa-times"></i>', ['cancel-request', 'recordID' => $recordID], ['title' => 'Cancel Approval Request', 'class' => 'mx-1 btn btn-warning btn-xs', 'title' => 'Cancel Approval Request.']);
                }



                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Employee_No' => !empty($item->Employee_No) ? $item->Employee_No : '',
                    'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                    'Job_Title' => !empty($item->Job_Title) ? $item->Job_Title : '',
                    'Global_Dimension_1_Code' => !empty($item->Global_Dimension_1_Code) ? $item->Global_Dimension_1_Code : '',
                    'Global_Dimension_2_Code' => !empty($item->Global_Dimension_2_Code) ? $item->Global_Dimension_2_Code : '',
                    'Claim_Type' => !empty($item->Claim_Type) ? $item->Claim_Type : '',
                    'Total_Surrender_Amount' => !empty($item->Total_Surrender_Amount) ? $item->Total_Surrender_Amount : '',
                    'Claim_Pay_Mode' => !empty($item->Claim_Pay_Mode) ? $item->Claim_Pay_Mode : '',
                    'Claim_Paying_Account' => !empty($item->Claim_Paying_Account) ? $item->Claim_Paying_Account : '',
                    'Claim_Payment_Tx_No' => !empty($item->Claim_Payment_Tx_No) ? $item->Claim_Payment_Tx_No : '',
                    'Status' => !empty($item->Status) ? $item->Status : '',
                    'Claim_Posted' => !empty($item->Claim_Posted) ? $item->Claim_Posted : '',
                    'Claim_Posted_By' => !empty($item->Claim_Posted_By) ? $item->Claim_Posted_By : '',
                    'Claim_Posted_Date' => !empty($item->Claim_Posted_Date) ? $item->Claim_Posted_Date : '',

                    'Action' => $link . $updateLink . $Viewlink,

                ];
            }
        }



        return $result;
    }


    public function actionListApproved()
    {
        $service = Yii::$app->params['ServiceName']['ApprovedStaffClaim'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
            'Status' => 'Released'
        ];
        //Yii::$app->recruitment->printrr( );
        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];


        if (is_array($result)) {
            foreach ($results as $item) {

                if (empty($item->No)) {
                    continue;
                }

                $link = $updateLink = $deleteLink =  '';
                $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view', 'Key' => $item->Key], ['class' => 'btn btn-outline-primary btn-xs mx-1', 'title' => 'View Card']);
                if ($item->Status == 'Open') {
                    $link = Html::a('<i class="fas fa-paper-plane"></i>', ['send-for-approval', 'No' => $item->No], ['title' => 'Send Approval Request', 'class' => 'mx-1 btn btn-primary btn-xs', 'title' => 'Make approval request.']);

                    $updateLink = Html::a('<i class="far fa-edit"></i>', ['update', 'Key' => $item->Key], ['class' => 'mx-1 btn btn-info btn-xs', 'title' => 'Update Document']);
                } else if ($item->Status == 'Pending_Approval') {
                    $link = Html::a('<i class="fas fa-times"></i>', ['cancel-request', 'Key' => $item->Key], ['title' => 'Cancel Approval Request', 'class' => 'mx-1 btn btn-warning btn-xs', 'title' => 'Cancel Approval Request.']);
                }

                $result['data'][] = [
                    'Key' => $item->Key,
                    'No' => $item->No,
                    'Employee_No' => !empty($item->Employee_No) ? $item->Employee_No : '',
                    'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                    'Job_Title' => !empty($item->Job_Title) ? $item->Job_Title : '',
                    'Global_Dimension_1_Code' => !empty($item->Global_Dimension_1_Code) ? $item->Global_Dimension_1_Code : '',
                    'Global_Dimension_2_Code' => !empty($item->Global_Dimension_2_Code) ? $item->Global_Dimension_2_Code : '',
                    'Claim_Type' => !empty($item->Claim_Type) ? $item->Claim_Type : '',
                    'Total_Surrender_Amount' => !empty($item->Total_Surrender_Amount) ? $item->Total_Surrender_Amount : '',
                    'Claim_Pay_Mode' => !empty($item->Claim_Pay_Mode) ? $item->Claim_Pay_Mode : '',
                    'Claim_Paying_Account' => !empty($item->Claim_Paying_Account) ? $item->Claim_Paying_Account : '',
                    'Claim_Payment_Tx_No' => !empty($item->Claim_Payment_Tx_No) ? $item->Claim_Payment_Tx_No : '',
                    'Status' => !empty($item->Status) ? $item->Status : '',
                    'Claim_Posted' => !empty($item->Claim_Posted) ? $item->Claim_Posted : '',
                    'Claim_Posted_By' => !empty($item->Claim_Posted_By) ? $item->Claim_Posted_By : '',
                    'Claim_Posted_Date' => !empty($item->Claim_Posted_Date) ? $item->Claim_Posted_Date : '',

                    'Action' => $link . $updateLink . $Viewlink,

                ];
            }
        }



        return $result;
    }


    public function getEmployees()
    {
        $service = Yii::$app->params['ServiceName']['Employees'];

        $employees = \Yii::$app->navhelper->getData($service);
        return ArrayHelper::map($employees, 'No', 'FullName');
    }

    /* My Imprests*/

    public function getmyimprests()
    {
        $service = Yii::$app->params['ServiceName']['PostedImprestRequest'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
            'Surrendered' => false,
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];
        $i = 0;
        if (is_array($results)) {
            foreach ($results as $res) {
                $result[$i] = [
                    'No' => $res->No,
                    'detail' => $res->No . ' - ' . $res->Imprest_Amount
                ];
                $i++;
            }
        }
        // Yii::$app->recruitment->printrr(ArrayHelper::map($result,'No','detail'));
        return ArrayHelper::map($result, 'No', 'detail');
    }

    /* Get My Posted Imprest Receipts */

    public function getimprestreceipts($imprestNo)
    {
        $service = Yii::$app->params['ServiceName']['PostedReceiptsList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
            'Imprest_No' => $imprestNo,
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);

        $result = [];
        $i = 0;
        if (is_array($results)) {
            foreach ($results as $res) {
                $result[$i] = [
                    'No' => $res->No,
                    'detail' => $res->No . ' - ' . $res->Imprest_No
                ];
                $i++;
            }
        }
        // Yii::$app->recruitment->printrr(ArrayHelper::map($result,'No','detail'));
        return ArrayHelper::map($result, 'No', 'detail');
    }

    /*Get Programs */

    public function getPrograms()
    {
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 1
        ];

        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result, 'Code', 'Name');
    }

    /* Get Department*/

    public function getDepartments()
    {
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];

        $filter = [
            'Global_Dimension_No' => 2
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        return ArrayHelper::map($result, 'Code', 'Name');
    }


    // Get Currencies

    public function getCurrencies()
    {
        $service = Yii::$app->params['ServiceName']['Currencies'];

        $result = \Yii::$app->navhelper->getData($service, []);
        return ArrayHelper::map($result, 'Code', 'Description');
    }

    public function actionSetpurpose()
    {
        $model = new Fundrequisition();
        $service = Yii::$app->params['ServiceName']['AllowanceRequestCard'];

        $request = Yii::$app->navhelper->postData($service, []);

        if (is_object($request)) {
            Yii::$app->navhelper->loadmodel($request, $model);
            $model->Key = $request->Key;
            $model->Purpose = Yii::$app->request->post('Purpose');
            $model->Employee_No = Yii::$app->user->identity->{'Employee_No'};
        }

        // Refresh record you are updating

        $refresh = Yii::$app->navhelper->getData($service, ['No' => $model->No]);
        Yii::$app->navhelper->loadmodel($refresh[0], $model);


        $result = Yii::$app->navhelper->updateData($service, $model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    public function actionSetdimension($dimension)
    {
        $model = new Imprestcard();
        $service = Yii::$app->params['ServiceName']['ImprestRequestCardPortal'];

        $filter = [
            'No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->{$dimension} = Yii::$app->request->post('dimension');
        }


        $result = Yii::$app->navhelper->updateData($service, $model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    /* Set Imprest Type */

    public function actionSetimpresttype()
    {
        $model = new Imprestcard();
        $service = Yii::$app->params['ServiceName']['ImprestRequestCardPortal'];

        $filter = [
            'No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->Imprest_Type = Yii::$app->request->post('Imprest_Type');
        }


        $result = Yii::$app->navhelper->updateData($service, $model, ['Amount_LCY']);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    /*Set Imprest to Surrend*/

    public function actionSetimpresttosurrender()
    {
        $model = new Imprestsurrendercard();
        $service = Yii::$app->params['ServiceName']['ImprestSurrenderCardPortal'];

        $filter = [
            'No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->Imprest_No = Yii::$app->request->post('Imprest_No');
        }


        $result = Yii::$app->navhelper->updateData($service, $model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
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

    /* Call Approval Workflow Methods */

    public function actionSendForApproval($recordID)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'recordID' => $recordID
        ];


        $result = Yii::$app->navhelper->codeunit($service, $data, 'SendDocumentApproval');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Document Sent for Approval Successfully.', true);
            //return $this->redirect(['view','No' => $No]);
            return $this->redirect(['index']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Sending Request for Approval  : ' . $result);
            // return $this->redirect(['view','No' => $No]);
            return $this->redirect(['index']);
        }
    }

    /*Cancel Approval Request */

    public function actionCancelRequest($recordID)
    {
        $service = Yii::$app->params['ServiceName']['PortalFactory'];

        $data = [
            'recordID' => $recordID
        ];

        $result = Yii::$app->navhelper->codeunit($service, $data, 'CancelDocumentApproval');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Approval Request Cancelled Successfully.', true);
            return $this->redirect(['index']);
        } else {

            Yii::$app->session->setFlash('error', 'Error Cancelling Approval Request.  : ' . $result);
            return $this->redirect(['index']);
        }
    }

    /*Print Surrender*/
    public function actionPrintRequisition($No)
    {
        $service = Yii::$app->params['ServiceName']['PortalReports'];
        $data = [
            'fundsReq' => $No
        ];
        $path = Yii::$app->navhelper->PortalReports($service, $data, 'IanGenerateFundsRequisition');
        if (!is_file($path['return_value'])) {
            Yii::$app->session->setFlash('error', 'File is not available: ' . $path['return_value']);
            return $this->render('printout', [
                'report' => false,
                'content' => null,
                'No' => $No
            ]);
        }

        $binary = file_get_contents($path['return_value']);
        $content = chunk_split(base64_encode($binary));
        //delete the file after getting it's contents --> This is some house keeping
        unlink($path['return_value']);
        return $this->render('printout', [
            'report' => true,
            'content' => $content,
            'No' => $No
        ]);
    }

    /** Updates a single field */
    public function actionSetfield($field)
    {
        $service = 'StaffClaimCard';
        $value = Yii::$app->request->post('fieldValue');
        $result = Yii::$app->navhelper->Commit($service, [$field => $value], Yii::$app->request->post('Key'));
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $result;
    }

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


    public function actionAddLine($Service, $Document_No)
    {
        $service = Yii::$app->params['ServiceName'][$Service];
        $data = [
            'No' => $Document_No,
            'Employee_No' => Yii::$app->user->identity->{'Employee No_'},
            'Line_No' => time()
        ];

        // Insert Record

        $result = Yii::$app->navhelper->postData($service, $data);

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (is_object($result)) {
            return [
                'note' => 'Record Created Successfully.',
                'result' => $result
            ];
        } else {
            return ['note' => $result];
        }
    }

    public function actionDeleteLine($Service, $Key)
    {
        $service = Yii::$app->params['ServiceName'][$Service];
        $result = Yii::$app->navhelper->deleteData($service, Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_string($result)) {

            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        } else {
            return ['note' => '<div class="alert alert-danger">Error Purging Record: ' . $result . '</div>'];
        }
    }


    public function actionExpenseCodes()
    {
        $data = Yii::$app->navhelper->dropdown('ExpenseCodes', 'Code', 'Description');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    public function getDimension($value)
    {
        $service = Yii::$app->params['ServiceName']['DimensionValueList'];
        $filter = ['Global_Dimension_No' => $value];
        $result = \Yii::$app->navhelper->getData($service, $filter);

        return Yii::$app->navhelper->refactorArray($result, 'Code', 'Name');
    }

    public function actionDimension1()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->getDimension(1);
    }

    public function actionDimension2()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->getDimension(2);
    }

    public function actionEmployees()
    {
        $data = Yii::$app->navhelper->dropdown('EmployeesUnfiltered', 'No', 'Full_Name');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    // Get Transaction Types

    public function actionTransactiontypes()
    {
        $data = Yii::$app->navhelper->dropdown('PaymentTypes', 'Code', 'Description');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionRates()
    {
        $data = Yii::$app->navhelper->dropdown('RequisitionRates', 'Code', 'Description');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionAccounts()
    {
        $data = Yii::$app->navhelper->dropdown('GLAccountList', 'No', 'Name');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    // Get Donors

    public function actionDonors()
    {

        $service = Yii::$app->params['ServiceName']['CustomerLookup'];
        $filter = [];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        $arr = [];

        foreach ($result as $res) {
            if (!empty($res->No) && !empty($res->Name)) {
                $arr[] = [
                    'Code' => $res->No,
                    'Description' => $res->No . ' - ' . $res->Name
                ];
            }
        }
        $data = ArrayHelper::map($arr, 'Code', 'Description');
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    // Get Grants

    public function actionGrants()
    {

        $service = Yii::$app->params['ServiceName']['GrantLookUp'];
        $filter = [];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        $arr = [];

        foreach ($result as $res) {
            if (!empty($res->No) && !empty($res->Title)) {
                $arr[] = [
                    'Code' => $res->No,
                    'Description' => $res->No . ' - ' . $res->Title
                ];
            }
        }
        $data = ArrayHelper::map($arr, 'Code', 'Description');
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    // Get Filtered Objectives

    public function actionObjectives()
    {
        $data = file_get_contents('php://input');
        $params = json_decode($data);
        $service = Yii::$app->params['ServiceName']['GrantLinesLookUp'];
        $filter = [
            'Grant_No' => $params->Grant_No,
            'Line_Type' => 'Objective'
        ];

        $result = \Yii::$app->navhelper->getData($service, $filter);
        //Yii::$app->recruitment->printrr($result);
        $arr = [];

        foreach ($result as $res) {
            if (!empty($res->Code)) {
                $arr[] = [
                    'Code' => $res->Code,
                    'Description' => $res->Code
                ];
            }
        }
        $data = ArrayHelper::map($arr, 'Code', 'Description');
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    // Get Filtered Outputs

    public function actionOutputs()
    {
        $data = file_get_contents('php://input');
        $params = json_decode($data);
        $service = Yii::$app->params['ServiceName']['GrantLinesLookUp'];
        $filter = [
            'Grant_No' => $params->Grant_No,
            'Line_Type' => 'Output'
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        //Yii::$app->recruitment->printrr($result);

        $data = Yii::$app->navhelper->refactorArray($result, 'Code', 'Code');
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    // Get Filtered OutCome

    public function actionOutcome()
    {
        $data = file_get_contents('php://input');
        $params = json_decode($data);
        $service = Yii::$app->params['ServiceName']['GrantLinesLookUp'];
        $filter = [
            'Grant_No' => $params->Grant_No,
            'Line_Type' => 'Outcome'
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        $data = Yii::$app->navhelper->refactorArray($result, 'Code', 'Code');
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    // Get Filterd Activities

    public function actionActivities()
    {

        $data = file_get_contents('php://input');
        $params = json_decode($data);
        $service = Yii::$app->params['ServiceName']['GrantLinesLookUp'];
        $filter = [
            'Grant_No' => $params->Grant_No,
            'Line_Type' => 'Activity'
        ];
        $result = \Yii::$app->navhelper->getData($service, $filter);
        $data = Yii::$app->navhelper->refactorArray($result, 'Code', 'Code');
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    public function actionPartners()
    {
        $data = file_get_contents('php://input');

        $jsonParams = json_decode($data);
        $service = Yii::$app->params['ServiceName']['GrantDetailLines'];
        $filter = [
            'Grant_Code' => $jsonParams->Grant_No
        ];

        $result = \Yii::$app->navhelper->getData($service, $filter);
        // Yii::$app->recruitment->printrr($result);
        $arr = [];

        foreach ($result as $res) {
            if (!empty($res->G_L_Account_No) && !empty($res->Activity_Description)) {
                $arr[] = [
                    'Code' => $res->G_L_Account_No,
                    'Description' => $res->G_L_Account_No . ' - ' . $res->Activity_Description
                ];
            }
        }
        $data = ArrayHelper::map($arr, 'Code', 'Description');
        ksort($data);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }

    // File reader action -- Model Neutral Function --Ooh shit, @francnjamb

    public function actionRead()
    {
        $path = Yii::$app->request->post('path');
        $No = Yii::$app->request->post('No');
        $binary = file_get_contents($path);
        $content = chunk_split(base64_encode($binary));
        return $this->render('read', [
            'path' => $path,
            'No' => $No,
            'content' => $content
        ]);
    }


    public function actionUpload()
    {

        $targetPath = '';
        if ($_FILES) {
            $uploadedFile = $_FILES['attachment']['name'];
            list($pref, $ext) = explode('.', $uploadedFile);
            $targetPath = './uploads/' . Yii::$app->security->generateRandomString(5) . '.' . $ext; // Create unique target upload path

            // Create upload directory if it dnt exist.
            if (!is_dir(dirname($targetPath))) {
                FileHelper::createDirectory(dirname($targetPath));
                chmod(dirname($targetPath), 0755);
            }
        }

        // Upload
        if (Yii::$app->request->isPost) {
            $DocumentService = Yii::$app->params['ServiceName'][Yii::$app->request->post('DocumentService')];
            $parentDocument = Yii::$app->navhelper->readByKey($DocumentService, Yii::$app->request->post('Key'));

            $metadata = [];
            if (is_object($parentDocument) && isset($parentDocument->Key)) {
                $metadata = [
                    'Application' => $parentDocument->No,
                    'Employee' => $parentDocument->Employee_No,
                    'Leavetype' => 'Imprest - ' . $parentDocument->Purpose,
                ];
            }
            Yii::$app->session->set('metadata', $metadata);


            $file = $_FILES['attachment']['tmp_name'];
            //Return JSON
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (move_uploaded_file($file, $targetPath)) {
                // Upload to sharepoint
                $spResult = Yii::$app->recruitment->sharepoint_attach($targetPath);
                return [
                    'status' => 'success',
                    'message' => 'File Uploaded Successfully' . $spResult,
                    'filePath' => $targetPath
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Could not upload file at the moment.'
                ];
            }
        }


        // Update Nav -  Get Request
        if (Yii::$app->request->isGet) {
            $fileName = basename(Yii::$app->request->get('filePath'));

            $DocumentService = Yii::$app->params['ServiceName'][Yii::$app->request->get('documentService')];
            $AttachmentService = Yii::$app->params['ServiceName'][Yii::$app->request->get('Service')];
            $Document = Yii::$app->navhelper->readByKey($DocumentService, Yii::$app->request->get('Key'));

            $data = [];
            if (is_object($Document) && isset($Document->No)) {
                $data = [
                    'Document_No' => $Document->No,
                    'Name' => $fileName,
                    'File_path' => \yii\helpers\Url::home(true) . 'uploads/' . $fileName,
                ];
            }

            // Update Nav
            $result = Yii::$app->navhelper->postData($AttachmentService, $data);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (is_object($result)) {
                return $result;
            } else {
                return $result;
            }
        }
    }
}
