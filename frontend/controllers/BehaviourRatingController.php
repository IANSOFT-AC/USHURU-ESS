<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/28/2020
 * Time: 12:27 AM
 */


namespace frontend\controllers;


use Yii;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\Response;

class BehaviourRatingController extends Controller
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
                     'add-line',
                    'perspective',
                    'kpi-status'
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
            'add-line','perspective','kpi-status'
        ];

        if (in_array($action->id, $ExcemptedActions)) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex($Appraisal_No,$Behaviour_Line_No,$Competence_Line_No,$Employee_No)
    {

        $service = Yii::$app->params['ServiceName']['AppraisalBehaviourRating'];
        $filter = [
            'Behaviour_Line_No' => $Behaviour_Line_No,
            'Competence_Line_No' => $Competence_Line_No,
            'Appraisal_No' => $Appraisal_No,
            'Employee_No' => $Employee_No
        ];

        $ratings = Yii::$app->navhelper->getData($service, $filter);
       
        return $this->renderAjax('index',[
            'ratings' => $ratings
        ]);
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
}
