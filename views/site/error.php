<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $exception;
?>
<div class="site-error">
    <div class="content">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
      <h1>
         <?= Html::encode($exception->statusCode) ?>Error Page
      </h1>
    </section> -->

    <!-- Main content -->
    <section class="content">
      <div class="error-page">
        <h2 class="headline text-yellow"> <?= Html::encode($exception->statusCode) ?></h2>

        <div class="error-content">
          <h3><i class="fa fa-warning text-yellow"></i> Oops! <?= $exception->getMessage()?>.</h3>

          <p>
            The above error occurred while the Web server was processing your request.
            Meanwhile, you may <a href="<?= Yii::$app->urlManager->createUrl(['dashboard'])?>">return to dashboard</a> or try using the search form.
          </p>

          <form class="search-form">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search">

              <div class="input-group-btn">
                <button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i>
                </button>
              </div>
            </div>
            <!-- /.input-group -->
          </form>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
    </section>
    <!-- /.content -->
  </div>

</div>



