<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:21 PM
 */

namespace frontend\controllers;
use frontend\models\Employeeappraisalkra;
use frontend\models\Experience;
use frontend\models\Workhistory;
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

class WorkHistoryController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup','index'],
                'rules' => [
                    [
                        'actions' => ['signup','index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index','create','update','delete'],
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
            'contentNegotiator' =>[
                'class' => ContentNegotiator::class,
                'only' => ['commit'],
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    //'application/xml' => Response::FORMAT_XML,
                ],
            ]
        ];
    }

    public function actionIndex(){

        return $this->render('index');

    }

    public function actionCreate($Change_No){

        $model = new  Workhistory();
        $service = Yii::$app->params['ServiceName']['EmployeeWorkHistoryChange'];
        $model->Action = 'New_Addition';
        $model->Change_No = $Change_No;
        $model->Employee_No = Yii::$app->user->identity->{'Employee No_'};
       
        $model->isNewRecord = true;

        if(Yii::$app->request->get('Change_No') && !Yii::$app->request->post()){
            $result = Yii::$app->navhelper->postData($service, $model);
            Yii::$app->navhelper->loadmodel($result,$model);
        }

        if(Yii::$app->request->post() && $model->load(Yii::$app->request->post()['Workhistory'],'')  && $model->validate() ){

         Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Workhistory'], $model); // my fall back in case yii model loader fails
         if(!empty(Yii::$app->request->post()['Workhistory']['Key'])){
            
             $result = Yii::$app->navhelper->updateData($service,$model);
         }else{
             $result = Yii::$app->navhelper->postData($service,$model);
         }

           
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(is_object($result)){

                return ['note' => '<div class="alert alert-success">Record Added Successfully. </div>'];

            }else{

                return ['note' => '<div class="alert alert-danger">Error Adding Record : '.$result.'</div>' ];

            }

        }//End Saving experience

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('create', [
                'model' => $model,
                //'qualifications' => $this->getQualifications()               
                
            ]);
        }

        return $this->render('create',[
            'model' => $model,
           // 'qualifications' => $this->getQualifications()
           
        ]);
    }

    public function actionCommit()
    {
         $model = new Professionalchange();
         $service = Yii::$app->params['ServiceName']['EmployeeProffesionalBodies'];

         $model->Employee_No = Yii::$app->user->identity->{'Employee No_'};
         $model->Body_Code = Yii::$app->request->get('Body_Code');
         $model->Change_No = Yii::$app->request->get('Change_No');

         $result = Yii::$app->navhelper->postData($service,$model);

         return $result;

    }


    public function actionUpdate(){
        $model = new Employeeappraisalkpi() ;
        $model->isNewRecord = false;
        $service = Yii::$app->params['ServiceName']['EmployeeAppraisalKPI'];
        $filter = [
            'KRA_Line_No' => Yii::$app->request->get('KRA_Line_No'),
            'Employee_No' => Yii::$app->request->get('Employee_No'),
            'Appraisal_No' => Yii::$app->request->get('Appraisal_No'),
            'Line_No' => Yii::$app->request->get('Line_No'),
        ];
        $result = Yii::$app->navhelper->getData($service,$filter);

        if(is_array($result)){
            //load nav result to model
            $model = Yii::$app->navhelper->loadmodel($result[0],$model) ;
        }else{
            Yii::$app->recruitment->printrr($result);
        }


        if(Yii::$app->request->post() && Yii::$app->navhelper->loadpost(Yii::$app->request->post()['Employeeappraisalkpi'],$model) && $model->validate() ){
            $result = Yii::$app->navhelper->updateData($service,$model);

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if(!is_string($result)){

                return ['note' => '<div class="alert alert-success">Employee Objective/ KPI Updated Successfully. </div>' ];
            }else{

                return ['note' => '<div class="alert alert-danger">Error Updating Employee Objective/ KPI: '.$result.'</div>'];
            }

        }

        if(Yii::$app->request->isAjax){
            return $this->renderAjax('update', [
                'model' => $model,
                'ratings' => $this->getRatings(),
                'assessments' => $this->getPerformancelevels(),
            ]);
        }

        return $this->render('update',[
            'model' => $model,
            'ratings' => $this->getRatings(),
            'assessments' => $this->getPerformancelevels() ,
        ]);
    }



    public function getQualifications()
    {
        $service = Yii::$app->params['ServiceName']['Professional'];

        $result = Yii::$app->navhelper->getData($service, []);

        return Yii::$app->navhelper->refactorArray($result,'Code','Name');
    }

    public function actionDelete(){
        $service = Yii::$app->params['ServiceName']['EmployeeAppraisalKPI'];
        $result = Yii::$app->navhelper->deleteData($service,Yii::$app->request->get('Key'));
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(!is_string($result)){
            return ['note' => '<div class="alert alert-success">Record Purged Successfully</div>'];
        }else{
            return ['note' => '<div class="alert alert-danger">Error Purging Record: '.$result.'</div>' ];
        }
    }

    public function actionView($ApplicationNo){
        $service = Yii::$app->params['ServiceName']['leaveApplicationCard'];
        $leaveTypes = $this->getLeaveTypes();
        $employees = $this->getEmployees();

        $filter = [
            'Application_No' => $ApplicationNo
        ];

        $leave = Yii::$app->navhelper->getData($service, $filter);

        //load nav result to model
        $leaveModel = new Leave();
        $model = $this->loadtomodel($leave[0],$leaveModel);


        return $this->render('view',[
            'model' => $model,
            'leaveTypes' => ArrayHelper::map($leaveTypes,'Code','Description'),
            'relievers' => ArrayHelper::map($employees,'No','Full_Name'),
        ]);
    }




    public function getRatings()
    {
          $service = Yii::$app->params['ServiceName']['AppraisalRating'];
          $data = Yii::$app->navhelper->getData($service, []);
          $result = Yii::$app->navhelper->refactorArray($data,'Rating','Rating_Description');
          return $result;
    }

    /** Updates a single field */
    public function actionSetfield($field){
        $service = 'EmployeeWorkHistoryChange';
        $value = Yii::$app->request->post('fieldValue');
        $result = Yii::$app->navhelper->Commit($service,[$field => $value],Yii::$app->request->post('Key'));
        Yii::$app->response->format = \yii\web\response::FORMAT_JSON;
        return $result;
        
    }
}