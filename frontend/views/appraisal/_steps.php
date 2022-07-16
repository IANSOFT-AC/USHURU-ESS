<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 3/11/2020
 * Time: 12:17 PM
 */

$profileAction = (Yii::$app->session->has('ProfileID') && Yii::$app->recruitment->hasProfile(Yii::$app->session->get('ProfileID'))) ? 'update?No=' . Yii::$app->session->get('ProfileID') : 'create';

//var_dump(Yii::$app->recruitment->hasProfile(Yii::$app->session->get('ProfileID')));
?>

<!-- another version - flat style with animated hover effect -->
<div class="breadcrumbb flat">

        <a href="<?= Yii::$app->recruitment->absoluteUrl() . 'appraisal/view?Employee_No=' . $model->Employee_No . '&Appraisal_No=' . $model->Appraisal_No ?>" <?= ($model->Review_Period == 'OBJ SETTING') ? 'class="active"' : '' ?>>Goal Setting</a>
        <a href="<?= Yii::$app->recruitment->absoluteUrl() . 'appraisal/view?Employee_No=' . $model->Employee_No . '&Appraisal_No=' . $model->Appraisal_No ?>" <?= ($model->Review_Period == 'Closed' && $model->Review_Period == 'Appraisee_Level') ? 'class="active"' : '' ?>>Q1 Appraisal</a>

        <a href="<?= Yii::$app->recruitment->absoluteUrl() . 'appraisal/view?Employee_No=' . $model->Employee_No . '&Appraisal_No=' . $model->Appraisal_No ?>" <?= ($model->Review_Period == 'Closed' && $model->Review_Period == 'Appraisee_Level') ? 'class="active"' : '' ?>>Q2 Appraisal</a>
        <a href="<?= Yii::$app->recruitment->absoluteUrl() . 'appraisal/view?Employee_No=' . $model->Employee_No . '&Appraisal_No=' . $model->Appraisal_No ?>" <?= ($model->Review_Period == 'Closed' && $model->Review_Period == 'Agreement_Level') ? 'class="active"' : '' ?>>Q3 Appraisal</a>

        <a href="<?= Yii::$app->recruitment->absoluteUrl() . 'appraisal/eyappraiseeclosedlist' ?>" <?= ($model->Review_Period == 'Closed') ? 'class="active"' : '' ?>>Q4 Appraisal</a>
        <a href="<?= Yii::$app->recruitment->absoluteUrl() . 'appraisal/eyappraiseeclosedlist' ?>" <?= ($model->Review_Period == 'Closed') ? 'class="active"' : '' ?>>Closed Appraisal</a>

</div>