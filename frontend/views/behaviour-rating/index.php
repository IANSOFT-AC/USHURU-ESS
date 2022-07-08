<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/22/2020
 * Time: 5:23 PM
 */



/* @var $this yii\web\View */

$this->title = 'HRMIS - Behavior Rating';

$url = \yii\helpers\Url::home(true);
?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Behavior Rating</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>                                                                                           
                                <td class="text text-bold text-center">Line No.</td>
                                <td class="text text-bold text-center">Review Period</td>
                                <td class="text text-bold text-center">Target Status</td>
                                <td class="text text-bold text-center">Non Achievement Reasons</td>
                                <td class="text text-bold text-center">Apraisee Self Rating</td>
                                <td class="text text-bold text-center">Appriasee Self Rating</td>
                                <td class="text text-bold text-center">Appraisee Comment</td>
                                <td class="text text-bold text-center">Appraiser Comment</td>
                                <td class="text text-bold text-center">Score</td>
                                <td class="text text-bold text-center">Agree</td>
                                <td class="text text-bold text-center">Disagreement Comment</td>
                                <td class="text text-bold text-center">Overview Manager Comment</td>

                            </tr>
                        </thead>
                        <tbody>
                            <?php                                                                                       
                                
                                foreach($ratings as $br):
                                    if(empty($br->Key))
                                    {
                                            continue;
                                    }
                                ?>
                                <tr>                                                                                              
                                    <td><?= $br->Line_No ?? '' ?></td>
                                    <td data-key="<?= $br->Key ?>" data-name="Review_Period" data-service="AppraisalBehaviourRating" ondblclick="addInput(this)" ><?= $br->Review_Period ?? '' ?></td>
                                    <td data-key="<?= $br->Key ?>" data-name="Target_Status" data-service="AppraisalBehaviourRating" ondblclick="addInput(this)" ><?= $br->Target_Status?? '' ?></td>
                                    <td data-key="<?= $br->Key ?>" data-name="Non_Achievement_Reasons" data-service="AppraisalBehaviourRating" ondblclick="addInput(this)" ><?= $br->Non_Achievement_Reasons ?></td>
                                    <td data-key="<?= $br->Key ?>" data-name="Appraisee_Self_Rating" data-service="AppraisalBehaviourRating" ondblclick="addInput(this)" ><?= $br->Appraisee_Self_Rating ?></td>
                                    <td data-key="<?= $br->Key ?>" data-name="Appraisee_Comments" data-service="AppraisalBehaviourRating" ondblclick="addInput(this)" ><?= $br->Appraisee_Comments ?? '' ?></td>
                                    <td data-key="<?= $br->Key ?>" data-name="Appraiser_Rating" data-service="AppraisalBehaviourRating" ondblclick="addInput(this)" ><?= $br->Appraiser_Rating ?? '' ?></td>
                                    <td data-key="<?= $br->Key ?>" data-name="Appraiser_Comments" data-service="AppraisalBehaviourRating" ondblclick="addInput(this)" ><?= $br->Appraiser_Comments ?? '' ?></td>
                                    <td data-key="<?= $br->Key ?>" data-name="Score" data-service="AppraisalBehaviourRating" ondblclick="addInput(this,'number')" ><?= $br->Score ?></td>
                                    <td data-key="<?= $br->Key ?>" data-name="Agree" data-service="AppraisalBehaviourRating" ondblclick="addInput(this,'checkbox')" ><?= $br->Agree ?></td>
                                    <td data-key="<?= $br->Key ?>" data-name="Disagreement_Comments" data-service="AppraisalBehaviourRating" ondblclick="addTextarea(this,'number')" ><?= $br->Disagreement_Comments ?? '' ?></td>
                                    <td data-key="<?= $br->Key ?>" data-name="Overview_Manager_Comments" data-service="AppraisalBehaviourRating" ondblclick="addTextarea(this,'number')" ><?= $br->Overview_Manager_Comments ?? '' ?></td>
                                </tr>
                                <?php endforeach;
                                
                                    ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




