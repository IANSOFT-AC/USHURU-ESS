<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;

use frontend\models\Careerdevelopmentstrength;
use frontend\models\Employeeappraisalkra;
use frontend\models\Experience;
use frontend\models\Imprestcard;
use frontend\models\Imprestline;
use frontend\models\Imprestsurrendercard;
use frontend\models\Leaveattachment;
use frontend\models\Leaveplancard;
use frontend\models\Leave;
use frontend\models\Salaryadvance;
use frontend\models\Trainingplan;
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
use Mpdf\Gif\FileHeader;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\web\ForbiddenHttpException;

class LeaveController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'index', 'advance-list', 'create', 'update', 'delete', 'view', 'listactive', 'listbalances', 'listactivehod', 'activeleaves', 'activeleaveshod'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index', 'advance-list', 'create', 'update', 'delete', 'view', 'listactive', 'listbalances', 'listactivehod', 'activeleaves', 'activeleaveshod'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post']
                ],
            ],
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'only' => ['list', 'listactive', 'listbalances', 'listactivehod', 'listbalancesdivision'],
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
            'upload'
        ];

        if (in_array($action->id, $ExceptedActions)) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionUpload()
    {

        $targetPath = '';
        if ($_FILES) {
            $uploadedFile = $_FILES['attachment']['name'];
            list($pref, $ext) = explode('.', $uploadedFile);
            $targetPath = Yii::$app->security->generateRandomString(5) . '.' . $ext; // Create unique target upload path

            // Create upload directory if it dnt exist.
            if (!is_dir(dirname($targetPath))) {
                FileHelper::createDirectory(dirname($targetPath));
                chmod(dirname($targetPath), 0755);
            }
        }

        // Upload
        if (Yii::$app->request->isPost) {
            $DocumentService = Yii::$app->params['ServiceName'][Yii::$app->request->post('DocumentService')];
            $AttachmentService = Yii::$app->params['ServiceName'][Yii::$app->request->post('AttachmentService')];
            $parentDocument = Yii::$app->navhelper->readByKey($DocumentService, Yii::$app->request->post('Key'));
            $recordID = Yii::$app->navhelper->getRecordID($DocumentService, $parentDocument->Key);
            $metadata = [];
            if (is_object($parentDocument) && isset($parentDocument->Key)) {
                $metadata = [
                    'Application' => $parentDocument->Application_No,
                    'Employee' => $parentDocument->Employee_No,
                    //'Leavetype' => 'Imprest - ' . $parentDocument->Purpose,
                ];
            }
            Yii::$app->session->set('metadata', $metadata);


            $file = $_FILES['attachment']['tmp_name'];
            //Return JSON
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if (move_uploaded_file($file, $targetPath)) {
                // Upload to sharepoint
                // $spResult = Yii::$app->recruitment->sharepoint_attach($targetPath);
                $payload =  [
                    'status' => 'success',
                    'recordID' =>  $recordID,
                    'documentNo' => $parentDocument->Application_No,
                    'fileName' => basename($targetPath),
                    // 'fileType' => $_FILES['attachment']['type'],
                    'fileExtension' => $ext,
                    'createdBy' => $parentDocument->User_ID,
                    'filePath' => Url::home(true) . $targetPath
                ];
                // Update BC CodeUnit
                $result = Yii::$app->navhelper->codeunit($AttachmentService, $payload, 'UploadPortalFilePath');

                return [
                    'Status' => $result,
                    'payload' => $payload
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

    public function actionIndex()
    {

        return $this->render('index');
    }

    public function actionActiveleaves()
    {
        return $this->render('activeleaves');
    }

    public function actionActiveleaveshod()
    {

        return $this->render('activeleaveshod');
    }

    public function actionBalances()
    {
        // Yii::$app->recruitment->printrr(Yii::$app->user->identity->{'Head of Department'});
        if (!Yii::$app->user->identity->{'Head of Department'}) {
            throw new ForbiddenHttpException('You do not have permission to view this information, Pole!');
        }
        return $this->render('balances');
    }

    public function actionBalancesDivision()
    {
        if (!Yii::$app->user->identity->{'Head of Division'}) {
            throw new ForbiddenHttpException('You do not have permission to view this information, Pole!');
        }

        return $this->render('balancesdivision');
    }


    public function actionCreate()
    {

        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];

        /*Do initial request */
        if (!isset(Yii::$app->request->post()['Leave']) && empty($_FILES)) {

            $now = date('Y-m-d');
            //$model->Start_Date = date('Y-m-d', strtotime($now.' + 2 days'));
            $model->Employee_No = Yii::$app->user->identity->{'Employee No_'};
            $request = Yii::$app->navhelper->postData($service, $model);
            //Yii::$app->recruitment->printrr($request);
            if (is_object($request)) {
                Yii::$app->navhelper->loadmodel($request, $model);
                return $this->redirect(['update', 'No' => $model->Application_No]);
            } else {
                Yii::$app->session->setFlash('error', 'Error : ' . $request, true);
                return $this->redirect(['index']);
            }
        } /*End Application Initialization*/

        if (Yii::$app->request->post() && !empty(Yii::$app->request->post()['Leave']) && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Leave'], $model)) {

            $filter = [
                'Application_No' => $model->Application_No,
            ];
            /*Read the card again to refresh Key in case it changed*/
            $refresh = Yii::$app->navhelper->getData($service, $filter);
            $model->Key = $refresh[0]->Key;

            //Yii::$app->recruitment->printrr($refresh );
            Yii::$app->navhelper->loadmodel($refresh[0], $model);
            $result = Yii::$app->navhelper->updateData($service, $model);
            if (!is_string($result)) {

                Yii::$app->session->setFlash('success', 'Leave Request Created Successfully.');
                return $this->redirect(['view', 'No' =>  $refresh[0]->Application_No]);
            } else {
                Yii::$app->session->setFlash('error', 'Error Creating Leave Request : ' . $result);
                return $this->redirect(['view', 'No' => $refresh[0]->Application_No]);
            }
        }


        // Upload Attachment File
        if (!empty($_FILES)) {
            $Attachmentmodel = new Leaveattachment();
            $Attachmentmodel->Document_No =  Yii::$app->request->post()['Leaveattachment']['Document_No'];
            $Attachmentmodel->attachmentfile = UploadedFile::getInstanceByName('attachmentfile');

            $result = $Attachmentmodel->Upload($Attachmentmodel->Document_No);


            if (!is_string($result) || $result == true) {
                Yii::$app->session->setFlash('success', 'Leave Application and Attachement Saved Successfully. ', true);
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', 'Could not save attachment.' . $result, true);
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'leavetypes' => $this->getLeaveTypes(),
            'employees' => $this->getEmployees(),
        ]);
    }

    public function actionAttach()
    {
        // Upload Attachment File
        if (!empty($_FILES)) {
            $Attachmentmodel = new Leaveattachment();
            $Attachmentmodel->Document_No =  Yii::$app->request->post()['Leaveattachment']['Document_No'];
            $Attachmentmodel->attachmentfile = UploadedFile::getInstanceByName('attachmentfile');

            $result = $Attachmentmodel->Upload($Attachmentmodel->Document_No);


            return $result;
        }
    }


    /** Updates a single field */
    public function actionSetfield($field)
    {
        $service = 'LeaveCard';
        $value = Yii::$app->request->post('fieldValue');
        $result = Yii::$app->navhelper->Commit($service, [$field => $value], Yii::$app->request->post('Key'));
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $result;
    }

    public function actionUpdate()
    {
        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];
        $model->isNewRecord = false;

        $filter = [
            'Application_No' => Yii::$app->request->get('No'),
        ];
        $result = Yii::$app->navhelper->getData($service, $filter);

        // check Authoruty To view the document
        Yii::$app->navhelper->checkAuthority($result[0]);

        if (is_array($result)) {
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0], $model); //$this->loadtomodeEmployee_Nol($result[0],$Expmodel);
        } else {
            Yii::$app->recruitment->printrr($result);
        }



        if (Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Leave'], $model)) {
            $filter = [
                'Application_No' => $model->Application_No,
            ];
            /*Read the card again to refresh Key in case it changed*/
            $refresh = Yii::$app->navhelper->getData($service, $filter);
            $model->Key = $refresh[0]->Key;
            // Yii::$app->navhelper->loadmodel($refresh[0],$model);

            $result = Yii::$app->navhelper->updateData($service, $model);

            if (!is_string($result)) {

                Yii::$app->session->setFlash('success', 'Leave Updated Successfully.');

                return $this->redirect(['view', 'No' => $result->Application_No]);
            } else {
                Yii::$app->session->setFlash('error', 'Error Updating Leave Document ' . $result);
                return $this->redirect(['index']);
            }
        }



        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                'model' => $model,
                'leavetypes' => $this->getLeaveTypes(),
                'employees' => $this->getEmployees(),
            ]);
        }

        $recordID = $this->getRecordID($service, $model->Key);

        return $this->render('update', [
            'model' => $model,
            'leavetypes' => $this->getLeaveTypes(),
            'employees' => $this->getEmployees(),
            'recordID' => $recordID
        ]);
    }

    public function actionDelete()
    {
        $service = Yii::$app->params['ServiceName']['LeaveCard'];
        $result = Yii::$app->navhelper->deleteData($service, Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (!is_string($result)) {
            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        } else {
            return ['note' => '<div class="alert alert-danger">Error Purging Record: ' . $result . '</div>'];
        }
    }

    public function actionView($No, $Approval = false)
    {
        // exit($No);
        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];

        $filter = [
            'Application_No' => $No
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);

        // check Authority To view the document
        if (!$Approval) {
            Yii::$app->navhelper->checkAuthority($result[0]);
        }


        //load nav result to model
        $model = $this->loadtomodel($result[0], $model);
        $recordID = $this->getRecordID($service, $model->Key);

        //Yii::$app->recruitment->printrr($recordID);

        return $this->render('view', [
            'model' => $model,
            'recordID' => $recordID
        ]);
    }

    public function getRecordID($service, $Key)
    {
        return Yii::$app->navhelper->getRecordID($service, $Key);
    }



    // Get Leave list

    public function actionList()
    {
        $service = Yii::$app->params['ServiceName']['LeaveList'];
        $filter = [
            'Employee_No' => Yii::$app->user->identity->Employee[0]->No,
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];
        foreach ($results as $item) {
            if (empty($item->Application_No)) {
                continue;
            }
            $recordID = $this->getRecordID($service, $item->Key);
            $link = $updateLink = $deleteLink =  '';
            $Viewlink = Html::a('<i class="fas fa-eye"></i>', ['view'], [
                'class' => 'btn btn-outline-primary btn-xs',
                'data' => [
                    'params' => [
                        'No' => $item->Application_No
                    ],
                    'method' => 'GET'
                ]

            ]);
            if ($item->Status == 'New') {
                $link = Html::a('<i class="fas fa-paper-plane"></i>', ['send-for-approval', 'recordID' => $recordID], ['title' => 'Send Approval Request', 'class' => 'btn btn-primary btn-xs']);
                $updateLink = Html::a('<i class="far fa-edit"></i>', ['update'], [
                    'class' => 'btn btn-info btn-xs',
                    'data' => [
                        'params' => [
                            'No' => $item->Application_No
                        ],
                        'method' => 'GET'
                    ]
                ]);
            } else if ($item->Status == 'Pending_Approval') {
                $link = Html::a('<i class="fas fa-times"></i>', ['cancel-request', 'recordID' => $recordID], ['title' => 'Cancel Approval Request', 'class' => 'btn btn-warning btn-xs']);
            }

            $result['data'][] = [
                'Key' => $item->Key,
                'No' => $item->Application_No,
                'Employee_No' => !empty($item->Employee_No) ? $item->Employee_No : '',
                'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                'Application_Date' => !empty($item->Application_Date) ? $item->Application_Date : '',
                'Status' => $item->Status,
                'Action' => $link,
                'Update_Action' => $updateLink,
                'view' => $Viewlink
            ];
        }

        return $result;
    }

    /*Get Active Leaves*/

    public function actionListactive()
    {
        $service = Yii::$app->params['ServiceName']['StaffOnLeave'];
        $filter = [
            'Department' => Yii::$app->user->identity->Employee[0]->Department_Name,
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];
        foreach ($results as $item) {



            $result['data'][] = [

                'Key' => !empty($item->Key) ? $item->Key : '',
                'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                'Leave_Type' => !empty($item->Leave_Type) ? $item->Leave_Type : '',
                'Start_Date' => !empty($item->Start_Date) ? $item->Start_Date : '',
                'End_Date' => !empty($item->End_Date) ? $item->End_Date : '',
                'Reliever' => !empty($item->Reliever) ? $item->Reliever : '',
                'Department' => !empty($item->Department) ? $item->Department : '',

            ];
        }

        return $result;
    }

    /*Get HODs on Leave*/

    public function actionListactivehod()
    {
        // var_dump(Yii::$app->user->identity->Employee[0]->Global_Dimension_1_Code); exit;
        //Yii::$app->recruitment->printrr(Yii::$app->user->identity->Employee[0]);
        $service = Yii::$app->params['ServiceName']['StaffOnLeave'];
        $filter = [
            'Division' => Yii::$app->user->identity->Employee[0]->Global_Dimension_1_Code,
            'Is_HOD' => 1
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];
        foreach ($results as $item) {

            if (empty($item->Employee_Name) || empty($item->Leave_Type)) {
                continue;
            }

            $result['data'][] = [

                'Key' => !empty($item->Key) ? $item->Key : '',
                'Employee_Name' => !empty($item->Employee_Name) ? $item->Employee_Name : '',
                'Leave_Type' => !empty($item->Leave_Type) ? $item->Leave_Type : '',
                'Start_Date' => !empty($item->Start_Date) ? $item->Start_Date : '',
                'End_Date' => !empty($item->End_Date) ? $item->End_Date : '',
                'Reliever' => !empty($item->Reliever) ? $item->Reliever : '',
                'Department' => !empty($item->Department) ? $item->Department : '',

            ];
        }

        return $result;
    }

    /*Employee Leave Balances*/

    public function actionListbalances()
    {
        $service = Yii::$app->params['ServiceName']['EmployeeLeaveBalances'];
        $filter = [
            'Global_Dimension_2_Code' => Yii::$app->user->identity->Employee[0]->Global_Dimension_2_Code,
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];
        foreach ($results as $item) {

            if (empty($item->Full_Name)) {
                continue;
            }

            $result['data'][] = [

                'Key' => $item->Key,
                'No' => !empty($item->No) ? $item->No : '',
                'Full_Name' => !empty($item->Full_Name) ? $item->Full_Name : '',
                'Annual_Leave_Balance' => !empty($item->Annual_Leave_Balance) ? $item->Annual_Leave_Balance : '',
                'Global_Dimension_1_Code' => !empty($item->Global_Dimension_1_Code) ? $item->Global_Dimension_1_Code : '',

            ];
        }

        return $result;
    }

    // Head Of Departments Leave Balance List


    public function actionListbalancesdivision()
    {
        $service = Yii::$app->params['ServiceName']['HODLeaveBalances'];
        $filter = [
            'Global_Dimension_1_Code' => Yii::$app->user->identity->Employee[0]->Global_Dimension_1_Code,
            'Is_HOD' => 1
        ];

        $results = \Yii::$app->navhelper->getData($service, $filter);
        $result = [];
        foreach ($results as $item) {

            if (empty($item->Full_Name)) {
                continue;
            }

            $result['data'][] = [

                'Key' => $item->Key,
                'No' => !empty($item->No) ? $item->No : '',
                'Full_Name' => !empty($item->Full_Name) ? $item->Full_Name : '',
                'Annual_Leave_Balance' => !empty($item->Annual_Leave_Balance) ? $item->Annual_Leave_Balance : '',
                'Global_Dimension_1_Code' => !empty($item->Global_Dimension_1_Code) ? $item->Global_Dimension_1_Code : '',
                'Global_Dimension_2_Code' => !empty($item->Global_Dimension_2_Code) ? $item->Global_Dimension_2_Code : '',

            ];
        }

        return $result;
    }


    public function getCovertypes()
    {
        $service = Yii::$app->params['ServiceName']['MedicalCoverTypes'];

        $results = \Yii::$app->navhelper->getData($service);
        $result = [];
        $i = 0;
        if (is_array($results)) {
            foreach ($results as $res) {
                if (!empty($res->Code) && !empty($res->Description)) {
                    $result[$i] = [
                        'Code' => $res->Code,
                        'Description' => $res->Description
                    ];
                    $i++;
                }
            }
        }
        return ArrayHelper::map($result, 'Code', 'Description');
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

    /*Get Staff Loans */

    public function getLoans()
    {
        $service = Yii::$app->params['ServiceName']['StaffLoans'];

        $results = \Yii::$app->navhelper->getData($service);
        return ArrayHelper::map($results, 'Code', 'Loan_Name');
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

    public function getLeaveTypes($gender = '')
    {
        $service = Yii::$app->params['ServiceName']['LeaveTypesSetup']; //['leaveTypes'];
        $filter = [
            // 'Is_Sick_Leave' => '<>TRUE'
        ];

        $arr = [];
        $i = 0;
        $result = \Yii::$app->navhelper->getData($service, $filter);


        // Yii::$app->recruitment->printrr($result);


        foreach ($result as $res) {
            if ($res->Gender == 'Both' || $res->Gender == Yii::$app->user->identity->Employee[0]->Gender) {
                ++$i;
                $arr[$i] = [
                    'Code' => $res->Code,
                    'Description' => $res->Description
                ];
            }
        }
        return ArrayHelper::map($arr, 'Code', 'Description');
    }

    public function actionRequiresattachment($Code)
    {
        $service = Yii::$app->params['ServiceName']['LeaveTypesSetup'];
        $filter = [
            'Code' => $Code
        ];

        $result = \Yii::$app->navhelper->getData($service, $filter);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['Requires_Attachment' => $result[0]->Requires_Attachment];
    }

    public function getEmployees()
    {

        //Yii::$app->recruitment->printrr(Yii::$app->user->identity->Employee[0]->Global_Dimension_3_Code);
        $service = Yii::$app->params['ServiceName']['Employees'];
        $filter = [
            // 'Global_Dimension_3_Code' => Yii::$app->user->identity->Employee[0]->Global_Dimension_3_Code
        ];
        $employees = \Yii::$app->navhelper->getData($service, $filter);
        $data = [];
        $i = 0;
        if (is_array($employees)) {

            foreach ($employees as  $emp) {
                $i++;
                if (!empty($emp->FullName) && !empty($emp->No)) {
                    $data[$i] = [
                        'No' => $emp->No,
                        'Full_Name' => $emp->FullName
                    ];
                }
            }
        }
        return ArrayHelper::map($data, 'No', 'Full_Name');
    }




    public function actionSetleavetype()
    {
        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];

        $filter = [
            'Application_No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->Leave_Code = Yii::$app->request->post('Leave_Code');
        }


        $result = Yii::$app->navhelper->updateData($service, $model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    public function actionSetreliever()
    {
        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];

        $filter = [
            'Application_No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->Reliever = Yii::$app->request->post('Reliever');
        }


        $result = Yii::$app->navhelper->updateData($service, $model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    /*Set Receipt Amount */
    public function actionSetdays()
    {
        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];

        $filter = [
            'Application_No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->Days_To_Go_on_Leave = Yii::$app->request->post('Days_To_Go_on_Leave');
        }

        $result = Yii::$app->navhelper->updateData($service, $model);

        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;

        return $result;
    }

    /*Set Start Date */
    public function actionSetstartdate()
    {
        $model = new Leave();
        $service = Yii::$app->params['ServiceName']['LeaveCard'];

        $filter = [
            'Application_No' => Yii::$app->request->post('No')
        ];
        $request = Yii::$app->navhelper->getData($service, $filter);

        if (is_array($request)) {
            Yii::$app->navhelper->loadmodel($request[0], $model);
            $model->Key = $request[0]->Key;
            $model->Start_Date = Yii::$app->request->post('Start_Date');
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
}
