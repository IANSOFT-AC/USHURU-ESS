<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 2/21/2020
 * Time: 4:19 PM
 */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AdminlteAsset;
use common\widgets\Alert;

AdminlteAsset::register($this);
$this->title = Yii::$app->params['welcomeText'];
$absoluteUrl = \yii\helpers\Url::home(true);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="hold-transition login-page">
    <?php $this->beginBody() ?>



    <div class="container mt-5 pt-5 ">
        <div class="row">
            <div class="col-12 col-sm-8 col-md-6 m-auto">
                <div class="card shadow-sm ">

                    <div class="card-body">
                        <div class="my-5 row justify-content-around ">
                            <img src="<?= \yii\helpers\Url::to('/images/SaccoLogo.png') ?>" class="img-fluid w-auto h-auto" loading="lazy" alt="Ushuru Sacco Logo" />
                        </div>
                        <p class=" login-box-msg">Sign in to start your session</p>

                        <?= $content ?>

                    </div>

                </div>
            </div>
        </div>
    </div>




    <input class="baseUrl" type="hidden" value="<?= $absoluteUrl ?>">
</body>
<footer class="footer" style="color: #42B3E5">
    <strong>Copyright &copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block text-ushurusecondary">
        <b class=" text text-ushurusecondary"></b>
    </div>

</footer>

<?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage();





?>