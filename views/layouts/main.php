<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Adminsidemenu;
use diecoding\toastr\ToastrFlash;
use aryelds\sweetalert\SweetAlert;
use yii\widgets\Pjax;

$controller = Yii::$app->controller->id;
$action = Yii::$app->controller->action->id;
//echo "<pre>";print_r(Yii::$app->request->url);exit;
//AppAsset::register($this);
$asset      = app\assets\AppAsset::register($this);
//echo $asset; exit;
$baseUrl    = $asset->baseUrl;
//phpinfo();
//exit;
$color = '#321af8';


$adminsidemenu = Adminsidemenu::find()->Where(['status' => 'Active'])->orderBy(['display_order' => SORT_ASC])->all();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" type="image/webp" href="<?= Yii::getAlias('@web') . '/uploads/default/home-0.webp' ?>">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <meta name="msapplication-TileColor" content="#ff86d6">
  <meta name="theme-color" content="#ffffff">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/0.8.0/cropper.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/0.8.0/cropper.min.js"></script>

  <title><?= Html::encode(Yii::$app->name) ?></title>
  <style type="text/css">
    .has-error {
      color: #DC2626;
    }

    .pagination {
      display: -ms-flexbox;
      display: flex;
      padding-left: 0;
      list-style: none;
      border-radius: 0.25rem;
    }

    ul.pagination li {
      position: relative;
      display: block;
      padding: 0.5rem 0.75rem;
      margin-left: -1px;
      line-height: 1.25;
      color: #007bff;
      background-color: #fff;
      border: 1px solid #dee2e6;
    }

    ul.pagination li.active {
      background-color: #007bff;
    }

    ul.pagination li.active a {
      color: #ffffff;
    }

    .summary {
      float: left;
    }

    .note-editable {
      background-color: black !important;
    }

    .note-editable {
      background-color: #333333 !important;
      border-radius: 6px !important;
      color: #ffffff !important;
    }

    .btn-success:focus,
    .btn-success.focus {
      color: #000000 !important;
      background-color: #FCD100 !important;
      border-color: #FCD100 !important;
      box-shadow: none !important;
    }

    /* .btn-link-info:hover {
      background: #000000;
      color: #FCD100;
      border-color: #FCD100;
    } */
  </style>
  <?php $this->head() ?>


</head>

<body data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="light">
  <?php $this->beginBody() ?>
  <?php $site_logo = Yii::getAlias('@web') . '/uploads/default/home-0.webp'; ?>

  <div class="loader-bg">
    <div class="loader-track">
      <div class="loader-fill"></div>
    </div>
  </div>
  <nav class="pc-sidebar">
    <div class="navbar-wrapper">
      <div class="m-header">
        <a href="<?= Yii::$app->params['domain'] ?>" class="b-brand text-primary">
          <!-- ========   Change your logo from here   ============ -->
          <span style="text-align: center;">
            <img src="<?= $site_logo ?>" class="img-fluid logo-lg" alt="logo" style="width: 50% !important">
          </span>
          <!-- <span>
            <img src="<?= Yii::$app->params['ImagePath'] . "uploads/default/noir.png"; ?>" class="img-fluid logo-lg" alt="logo" style="height: 50%;width: 50%;">
          </span> -->
        </a>
      </div>
      <div class="navbar-content">
        <div class="card pc-user-card">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <div class="flex-shrink-0">
                  <?php $image = (!empty(Yii::$app->user->identity->image)) ? Yii::$app->params['ImagePath'] . Yii::$app->user->identity->image : Yii::getAlias('@web') . '/uploads/default/home-0.webp'; ?>
                <img src="<?= $image ?>" alt="user-image" class="user-avtar wid-45 rounded-circle" />
              </div>
              <div class="flex-grow-1 ms-3 me-2">
                <h6 class="mb-0"><?= Yii::$app->user->identity->full_name ?></h6>
                <small><?= (Yii::$app->user->identity->user_type == 'MasterAdmin') ? 'Master Admin' : Yii::$app->user->identity->user_type; ?></small>
              </div>
            </div>
            <div class="collapse pc-user-links" id="pc_sidebar_userlink">
              <div class="pt-3">
                <a href="javascript:void(0)">
                  <i class="fab fa-chrome"></i>
                  <span>WEBSITE</span>
                </a>
                <a href="javascript:void(0)">
                  <i class="fas fa-mobile-alt"></i>
                  <span>APP</span>
                </a>
                <a href="javascript:void(0)">
                  <i class="fas fa-users"></i>
                  <span>PORTAL</span>
                </a>
              </div>
            </div>
          </div>
        </div>
        <ul class="pc-navbar custom-scrollbar">
          <?php
          if (!empty($adminsidemenu)) {
            foreach ($adminsidemenu as $key => $value) {
              if (empty($value->adminSidemenuDetails)) {

                //echo "<pre>"; print_r($value->controller_name); exit;
          ?>
                <li class="<?= ($controller == $value->controller_name && $value->action_name != 'logout') ? "pc-item active" : "pc-item" ?>">
                  <a href="<?= Yii::$app->urlManager->createUrl(["" . $value->controller_name . '/' . $value->action_name . ""]) ?>" class="pc-link">
                    <span class="pc-micon">
                      <i class="<?= $value->icon ?>"></i>
                    </span>
                    <span class="pc-mtext"><?= $value->title ?></span>
                  </a>
                </li>
              <?php } else {
                $controller_name = explode(",", $value->controller_name);
                $controller_action = (!empty($value->sub_menu_action_name)) ? explode(",", $value->sub_menu_action_name) : [];
                $type_action = (isset(Yii::$app->request->queryParams['type']) && !empty(Yii::$app->request->queryParams['type'])) ? 'index?type=' . Yii::$app->request->queryParams['type'] : '';
              ?>
                <li class="<?= ((in_array($controller, $controller_name) && in_array($type_action, $controller_action)) || (in_array($controller, $controller_name) && $controller != 'category')) ? "pc-item pc-hasmenu menu-open pc-trigger active" : "pc-item pc-hasmenu" ?>">
                  <a href="#!" class="pc-link">
                    <span class="pc-micon">
                      <i class="<?= $value->icon ?>"></i>
                    </span>
                    <span class="pc-mtext"><?= $value->title ?></span>
                    <span class="pc-arrow"><i data-feather="chevron-right"></i></span>
                  </a>
                  <ul class="pc-submenu" style="<?= ((in_array($controller, $controller_name) && in_array($type_action, $controller_action)) || (in_array($controller, $controller_name) && $controller != 'category')) ? "display: block;" : "display: none;" ?>">
                    <?php foreach ($value->adminSidemenuDetails as $k => $v) {
                      if (empty($v->action_name) && !empty($v->adminsidemenusubdetail)) {
                        $sub_controller_name = explode(",", $v->controller_name); ?>
                        <li class="<?= ((in_array($controller, $sub_controller_name) && in_array($type_action, $controller_action)) || (in_array($controller, $sub_controller_name) && $controller != 'category')) ? "pc-item pc-hasmenu menu-open pc-trigger active" : "pc-item pc-hasmenu" ?>">
                          <a href="#!" class="pc-link"><?= $v->title ?><span class="pc-arrow"><i data-feather="chevron-right"></i></span></a>
                          <ul class="pc-submenu" style="<?= ((in_array($controller, $sub_controller_name) && in_array($type_action, $controller_action)) || (in_array($controller, $sub_controller_name) && $controller != 'category')) ? "display: block;" : "display: none;" ?>">
                            <?php foreach ($v->adminsidemenusubdetail as $ks => $vs) { ?>
                              <li class="<?= ($controller == $vs->controller_name  && $v->action_name == $type_action) ? "pc-item active" : "pc-item" ?>"><a class="pc-link" href="<?= Yii::$app->urlManager->createUrl(["" . $vs->controller_name . '/' . $vs->action_name . ""]) ?>"><?= $vs->title ?></a></li>
                            <?php } ?>
                          </ul>
                        </li>
                      <?php } else { ?>
                        <?php if ($controller == 'category') {
                          $type_action = (isset(Yii::$app->request->queryParams['type']) && !empty(Yii::$app->request->queryParams['type'])) ? 'index?type=' . Yii::$app->request->queryParams['type'] : '';
                          //echo "<pre>";print_r($type_action);exit;
                        ?>
                          <li class="<?= ($controller == $v->controller_name && $v->action_name != 'logout' && $v->action_name == $type_action) ? "pc-item active" : "pc-item" ?>"><a class="pc-link" href="<?= Yii::$app->urlManager->createUrl(["" . $v->controller_name . '/' . $v->action_name . ""]) ?>"><?= $v->title ?></a></li>
                        <?php } else { ?>
                          <li class="<?= ($controller == $v->controller_name && $v->action_name != 'logout') ? "pc-item active" : "pc-item" ?>"><a class="pc-link" href="<?= Yii::$app->urlManager->createUrl(["" . $v->controller_name . '/' . $v->action_name . ""]) ?>"><?= $v->title ?></a></li>
                        <?php } ?>
                      <?php } ?>
                    <?php } ?>
                  </ul>
                </li>
          <?php }
            }
          }
          ?>
        </ul>
        <!-- <div class="product-icon">
          <img src="<?= Yii::$app->params['ImagePath'] . "uploads/default/data_dashboard.svg"; ?>" class="img-fluid logo-lg product-icon-single" alt="logo">
          <img src="<?= Yii::$app->params['ImagePath'] . "uploads/default/dam.svg"; ?>" class="img-fluid logo-lg product-icon-single" alt="logo">
          <a href="<?= Yii::$app->urlManager->createUrl(["site/pushpull", "id" => Yii::$app->MyFunctions->encode(Yii::$app->user->identity->appuser_id)]) ?>" target="_blank">
            <img src="<?= Yii::$app->params['ImagePath'] . "uploads/default/push_pull.svg"; ?>" class="img-fluid logo-lg product-icon-single" alt="logo">
          </a>
        </div> -->
      </div>
    </div>
  </nav>
  <header class="pc-header">
    <div class="header-wrapper"> <!-- [Mobile Media Block] start -->
      <div class="me-auto pc-mob-drp">
        <ul class="list-unstyled">
          <!-- ======= Menu collapse Icon ===== -->
          <li class="pc-h-item pc-sidebar-collapse">
            <a href="#" class="pc-head-link ms-0 " id="sidebar-hide">
              <i class="ti ti-arrow-bar-left"></i>
            </a>
          </li>
          <li class="pc-h-item pc-sidebar-popup">
            <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
        </ul>
      </div>
      <!-- [Mobile Media Block end] -->
      <div class="ms-auto">
        <ul class="list-unstyled">
          <li class="dropdown pc-h-item">
            <div class="flex-grow-1 ms-3 me-2">
              <h6 class="mb-0">Welcome <?= Yii::$app->user->identity->full_name ?></h6>
            </div>
          </li>
          <li class="dropdown pc-h-item header-user-profile">
            <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
              <img src="<?= $image ?>" alt="user-image" class="user-avtar" />
            </a>
            <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
              <div class="dropdown-header d-flex align-items-center justify-content-between">
                <h5 class="m-0">Profile</h5>
              </div>
              <div class="dropdown-body">
                <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 225px)">
                  <div class="d-flex mb-1">
                    <div class="flex-shrink-0">
                      <img src="<?= $image ?>" alt="user-image" class="user-avtar" />
                    </div>
                    <div class="flex-grow-1 ms-3">
                      <h6 class="mb-1"><?= Yii::$app->user->identity->full_name ?></h6>
                      <span><?= Yii::$app->user->identity->email ?></span>
                    </div>
                  </div>
                  <hr class="border-secondary border-opacity-50" />
                  <a href="<?= Yii::$app->urlManager->createUrl(["appuser/updateadmin"]) ?>" class="dropdown-item">
                    <span>
                      <svg class="pc-icon text-muted me-2">
                        <use xlink:href="#custom-user"></use>
                      </svg>
                      <span>My Account</span>
                    </span>
                  </a>
                  <a href="<?= Yii::$app->urlManager->createUrl(["appuser/resetpassword"]) ?>" class="dropdown-item">
                    <span>
                      <svg class="pc-icon text-muted me-2">
                        <use xlink:href="#custom-lock-outline"></use>
                      </svg>
                      <span>Change Password</span>
                    </span>
                  </a>
                  <hr class="border-secondary border-opacity-50" />
                  <div class="d-grid mb-3">
                    <a href="<?= Yii::$app->urlManager->createUrl(["site/logout"]) ?>" class="btn btn-primary">
                      <svg class="pc-icon me-2">
                        <use xlink:href="#custom-logout-1-outline"></use>
                      </svg>Logout
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </header>
  <section class="pc-container">
    <div class="pc-content contentpage">
      <div class="page-header">
        <div class="page-block">
          <div class="row align-items-center">
            <div class="col-md-12">
              <!-- <ul class="breadcrumb"> -->
              <?= Breadcrumbs::widget([
                'itemTemplate' => "<li class='breadcrumb-item' style='margin: 0 5px 0 0;'>{link}</li><li style='margin: 0 10px 0 0;'>/</li>",
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
              ]) ?>
              <!-- </ul> -->
            </div>
          </div>
        </div>
      </div>
      <?= ToastrFlash::widget(); ?>
      <?= $content ?>
    </div>
  </section>
  <footer class="pc-footer">
    <div class="footer-wrapper container-fluid">
      <div class="row">
        <div class="col my-1">
          <p class="m-0">Copyright &copy; <?= date('Y') ?> <a href="<?= Yii::$app->params['web_url'] ?>"><?= Yii::$app->params['project_display_name'] ?></a>. All rights reserved</p>
        </div>
      </div>
    </div>
  </footer>

  <?php $this->endBody() ?>
  <script>
    layout_change('light');
  </script>
  <script>
    layout_theme_contrast_change('false');
  </script>
  <script>
    change_box_container('false');
  </script>
  <script>
    layout_caption_change('true');
  </script>
  <script>
    layout_rtl_change('false');
  </script>
  <script>
    preset_change("preset-1");
  </script>
  <script type="text/javascript">
    $(function() {
      $("a.single_image").fancybox();
    });
    $(function() {
      //Initialize Select2 Elements
      $('.select2').select2({
        closeOnSelect: false
      })
      //Initialize Select2 Elements
      // $('.select2bs4').select2({
      //   theme: 'bootstrap4'
      // })
    })
  </script>
  <script type="text/javascript">
    $(document).ready(function() {
      $("#sidebar-hide").click(function() {
        $(this).toggleClass("sidebar-hide");
      });
    });
  </script>
  <!-- <script>
  $(function() {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-right',
      showConfirmButton: false,
      timer: 3000,
      // customClass: {
      //   popup: 'colored-toast'
      // },
    });

    //$('.swalDefaultSuccess').click(function() {
    <?php if (Yii::$app->session->getFlash('success')) { ?>
      Toast.fire({
        icon: 'success',
        title: "<?= Yii::$app->session->getFlash('success') ?>"
      })
    <?php } ?>
    //});
  });
</script> -->
  <!-- <script type="text/javascript">
  $(document).on('ready pjax:success', function() {
    $('.pjax-chnagestatus-link').on('click', function(e) {
      //alert('hello');
      e.preventDefault();
      var chnagestatusUrl = $(this).attr('chnagestatus-url');
      var pjaxContainer = $(this).attr('pjax-container');
      //var result = confirm('Delete this item, are you sure?');                                
      //if(result) {
      $.ajax({
        url: chnagestatusUrl,
        type: 'post',
        error: function(xhr, status, error) {
          alert('There was an error with your request.' + xhr.responseText);
        }
      }).done(function(data) {
        $.pjax.reload('#' + $.trim(pjaxContainer), {timeout: 3000});
      });
    });
  });
  $(document).on('ready pjax:success', function() {
    $('.pjax-delete-link').on('click', function(e) {
      e.preventDefault();
      var deleteUrl = $(this).attr('delete-url');
      var pjaxContainer = $(this).attr('pjax-container');
      var result = confirm($(this).attr('message'));                                
      if(result) {
        $.ajax({
          url: deleteUrl,
          type: 'post',
          error: function(xhr, status, error) {
            alert('There was an error with your request.' + xhr.responseText);
          }
        }).done(function(data) {
          $.pjax.reload('#' + $.trim(pjaxContainer), {timeout: 3000});
        });
      }
    });
  });
</script> -->
</body>

</html>

<?php $this->endPage() ?>