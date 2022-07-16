<?php
/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/26/2020
 * Time: 5:23 AM
 */

namespace frontend\models;


use yii\base\Model;
use Yii;

class Employee extends Model
{
    public $Key;
    public $No;
    public $_x002B_;
    public $First_Name;
    public $Middle_Name;
    public $Last_Name;
    public $Full_Name;
    public $Gender;
    public $Country_Region_Code;
    public $County_of_Origin;
    public $Sub_County;
    public $Location;
    public $Sub_Location;
    public $Village;
    public $Ethnic_Origin;
    public $National_ID;
    public $Passport_Number;
    public $Marital_Status;
    public $Religion;
    public $Driving_License;
    public $User_ID;
    public $Health_Conditions;
    public $Phone_No;
    public $Alternative_Phone_No;
    public $E_Mail;
    public $Company_E_Mail;
    public $Address;
    public $Post_Code;
    public $City;
    public $Address_2;
    public $ShowMap;
    public $Alt_Address_Code;
    public $Birth_Date;
    public $Age;
    public $Employment_Date;
    public $Probation_Period;
    public $Service_Period;
    public $Period_To_Retirement;
    public $Contract_Start_Date;
    public $Contract_End_Date;
    public $Date_of_joining_Medical_Scheme;
    public $Type_of_Employee;
    public $Job_Grade;
    public $Pointer;
    public $Payroll_Grade;
    public $Job_Code;
    public $Job_Title;
    public $Nature_Of_Employment;
    public $Global_Dimension_6_Code;
    public $Global_Dimension_1_Code;
    public $Global_Dimension_2_Code;
    public $Global_Dimension_3_Code;
    public $Global_Dimension_4_Code;
    public $Global_Dimension_5_Code;
    public $Probation_Status;
    public $End_of_Probation_Period;
    public $Probation_Period_Extended;
    public $Probabtion_Extended_By;
    public $Reasons_For_Extension;
    public $New_Probation_Period_End_Date;
    public $Notice_Period;
    public $Manager_No;
    public $Currency;
    public $Grant_Approver;
    public $Long_Term;
    public $Suspend_Leave_Application;
    public $ProfileID;
    public $Disabled;
    public $Line_Manager_Name;
    public $Overview_Manager_Name;
    public $Grant_Approver_Name;
    public $Disability_Id;
    public $Covered_Medically;
    public $Status;
    public $Payment_Methods;
    public $KRA_Number;
    public $NHIF_Number;
    public $NSSF_Number;
    public $Employee_Posting_Group;
    public $Bank_Code;
    public $Bank_Name;
    public $Bank_Branch_No;
    public $Allocated_Leave_Days;
    public $Bank_Account_No;
    public $Cause_of_Inactivity_Code;
    public $Termination_Date;
    public $Grounds_for_Term_Code;


    public $Initials;
    
    
    public function rules()
    {
        return [

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'Global_Dimension_1_Code' => 'Program Code',
            'Global_Dimension_2_Code' => 'Department Code',
            'Global_Dimension_3_Code' => 'Section Code',
            'Global_Dimension_4_Code' => 'Unit',
            'Global_Dimension_5_Code' => 'Location',
            'Job_Description' => 'Job Title',
            'Job_Title' => 'Job Code'
        ];
    }

    

}