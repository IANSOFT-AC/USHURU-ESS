<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/22/2020
 * Time: 2:53 PM
 */

namespace frontend\controllers;

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
use kartik\mpdf\Pdf;

class PayslipController extends Controller
{

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = ($action->id !== "index"); // <-- here
        return parent::beforeAction($action);
    }

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
                'only' => ['getleaves'],
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                    //'application/xml' => Response::FORMAT_XML,
                ],
            ]
        ];
    }

    public function actionIndex()
    {
       // Yii::$app->recruitment->printrr($this->getPayrollperiods());
        $service = Yii::$app->params['ServiceName']['PortalReports'];

        //Yii::$app->recruitment->printrr(ArrayHelper::map($payrollperiods,'Date_Opened','desc'));
        if (Yii::$app->request->post() && Yii::$app->request->post('payperiods')) {
            //Yii::$app->recruitment->printrr(Yii::$app->request->post('payperiods'));
            $data = [
                'selectedPeriod' => Yii::$app->request->post('payperiods'),
                'empNo' => Yii::$app->user->identity->{'Employee No_'}
            ];
            $path = Yii::$app->navhelper->PortalReports($service, $data, 'GeneratePayslip');
            //Yii::$app->recruitment->printrr($path);
            if (is_array($path) && !empty($path['return_value'])) {
                                
                return $this->render('index', [
                    'report' => true,
                    'content' => $path['return_value'],
                    'pperiods' => $this->getPayrollperiods()
                ]);
            }else{
                Yii::$app->session->setFlash('error',$path);
                return $this->render('index', [
                    'report' => false,
                    'content' => $path,
                    'pperiods' => $this->getPayrollperiods()
                ]);
            }
        }

        return $this->render('index', [
            'report' => false,
            'pperiods' => $this->getPayrollperiods()
        ]);
    }






    public function getPayrollperiods()
    {
        $service = Yii::$app->params['ServiceName']['Payrollperiods'];

        $filter = [
            'Status' => 'Approved',
        ];

        $periods = \Yii::$app->navhelper->getData($service, $filter);
      // return $periods->ReadMultiple_Result->PayrollPeriods;
        if (is_array($periods->ReadMultiple_Result->PayrollPeriods)) {
            krsort($periods->ReadMultiple_Result->PayrollPeriods); //sort  keys in descending order
            $res = [];
            foreach ($periods->ReadMultiple_Result->PayrollPeriods as $p) {
               // var_dump($p); exit;
                $res[] = [
                    'Date_Opened' => $p->Start_Date,
                    'desc' => $p->Start_Date . ' - ' . $p->Period_Name
                ];
            }
            return ArrayHelper::map($res, 'Date_Opened', 'desc');
        } else {
            return [];
        }
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
}
