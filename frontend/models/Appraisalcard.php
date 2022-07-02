<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/9/2020
 * Time: 4:09 PM
 */

namespace frontend\models;

use common\models\User;
use Yii;
use yii\base\Model;


class Appraisalcard extends Model
{

    public $Key;
    
public $Appraisal_No;
public $Employee_No;
public $Employee_Name;
public $Employee_User_Id;
public $Global_Dimension_1_Code;
public $Global_Dimension_2_Code;
public $Level_Grade;
public $Job_Title;
public $Appraisal_Calendar;
public $Appraisal_Start_Date;
public $Supervisor_No;
public $Supervisor_Name;
public $Supervisor_User_Id;
public $Supervisor_Overall_Comments;
public $Supervisor_Rejection_Comments;
public $Overview_Manager;
public $Overview_Manager_Name;
public $Overview_Manager_UserID;
public $Over_View_Manager_Comments;
public $Overview_Rejection_Comments;
public $Review_Period;
public $Overall_Score;
public $Quarter;
public $Approval_Status;
public $Recomended_Action;



    public function rules()
    {
        return [];
    }

    public function attributeLabels()
    {
        return [
            'MY_End_Date' => 'Mid Year Appraisal End Date',
            'MY_Start_Date' => 'Mid Year Appraisal Start Date',
            'EY_End_Date' => 'End Year Appraisal End Date',
            'EY_Start_Date' =>  'End Year Start Date',
            'EY_Appraisal_Status' => 'End Year Appraisal Status',
            'MY_Appraisal_Status' => 'Mid Year Appraisal Status'


        ];
    }

    public function getKPI($KRA_Line_No)
    {
        $service = Yii::$app->params['ServiceName']['EmployeeAppraisalKPI'];
        $filter = [
            'Appraisal_No' => $this->Appraisal_No,
            'KRA_Line_No' => $KRA_Line_No
        ];

        $kpas = Yii::$app->navhelper->getData($service, $filter);
        return $kpas;
    }

    public function getAppraisalbehaviours($Category_Line_No)
    {
        $service = Yii::$app->params['ServiceName']['EmployeeAppraisalBehaviours'];
        $filter = [
            'Appraisal_Code' => $this->Appraisal_No,
            'Competence_Line_No' => $Category_Line_No
        ];

        $behaviours = Yii::$app->navhelper->getData($service, $filter);
        return $behaviours;
    }

    public function getCareerdevelopmentstrengths($Goal_Line_No)
    {
        $service = Yii::$app->params['ServiceName']['CareerDevStrengths'];
        $filter = [
            'Appraisal_Code' => $this->Appraisal_No,
            'Goal_Line_No' => $Goal_Line_No
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);
        return $result;
    }

    public function getWeaknessdevelopmentplan($Wekaness_Line_No)
    {
        $service = Yii::$app->params['ServiceName']['WeeknessDevPlan'];
        $filter = [
            'Appraisal_No' => $this->Appraisal_No,
            'Wekaness_Line_No' => $Wekaness_Line_No
        ];

        $result = Yii::$app->navhelper->getData($service, $filter);
        return $result;
    }


    //get supervisor status

    public function isSupervisor()
    {

        return (Yii::$app->user->identity->{'Employee No_'} == $this->Supervisor_No);
    }


    public function isOverView()
    {

        return (Yii::$app->user->identity->{'Employee No_'} == $this->Overview_Manager);
    }

    public function isAppraisee()
    {

        return (Yii::$app->user->identity->{'Employee No_'} == $this->Employee_No);
    }
}
