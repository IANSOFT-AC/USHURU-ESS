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


class Fundrequisition extends Model
{

    public $Key;
    public $No;
    public $Employee_No;
    public $Employee_Name;
    public $Job_Title;
    public $Global_Dimension_1_Code;
    public $Global_Dimension_2_Code;
    public $Claim_Type;
    public $Description;
    public $Repayment_Period;
    public $Created_By;
    public $Total_Surrender_Amount;
    public $Date;
    public $Pending_Approvals_Ext;
    public $Approvers;
    public $Status;
    public $Claim_Posted;
    public $Claim_Posted_By;
    public $Claim_Posted_Date;
    public $isNewRecord;

    /*public function __construct(array $config = [])
    {
        return $this->getLines($this->No);
    }*/

    public function rules()
    {
        return [
            [['Description', 'Global_Dimension_1_Code', 'Global_Dimension_2_Code'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Code' => 'Department Code',
            'Global_Dimension_2_Code' => 'Branch Code'
        ];
    }

    public function getLines($No)
    {
        $service = Yii::$app->params['ServiceName']['AllowanceRequestLine'];
        $filter = [
            'Request_No' => $No,
        ];

        $lines = Yii::$app->navhelper->getData($service, $filter);
        $this->Allowance_Request_Line = $lines;
        return $lines;
    }
}
