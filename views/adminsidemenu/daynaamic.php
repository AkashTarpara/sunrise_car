<?php 
          if(!empty($adminsidemenu)){
            foreach ($adminsidemenu as $key => $value) { 
              if($value->is_multiple=='No'){ 
                //echo "<pre>"; print_r($value->controller_name); exit;?>
              <li class="<?= ($controller==$value->controller_name)? "current" :"" ?>">
                <a class="waves-effect" href="<?=Yii::$app->urlManager->createUrl(["".$value->controller_name.'/'.$value->action_name.""])?>"><i class="<?=$value->icon ?>"></i><span><?= $value->title ?></span></a>
              </li>
            <?php }elseif($value->is_multiple=='Yes'){ 
              $c=[];
              foreach ($value->adminSidemenuDetails as $kc => $vc) {
                //$c[]="$controller==$vc->controller_name"; 

                ?>
                
              <?php }
              //$c_new=implode(" OR ",$c);
              // /echo "<pre>"; print_r($c_new); exit; ?>
              <li class="<?= ($controller==$vc->controller_name)? "current" :"" ?>">
                

                <a class="waves-effect parent-item js__control" href="#"><i class="<?=$value->icon ?>"></i><span><?= $value->title ?></span><span class="menu-arrow fa fa-angle-down"></span></a>
                <ul class="sub-menu js__content">
                  <?php foreach ($value->adminSidemenuDetails as $k => $v) { ?>
                    <li class="<?= ($controller==$v->controller_name)? "current" :"" ?>"><a href="<?=Yii::$app->urlManager->createUrl(["".$v->controller_name.'/'.$v->action_name.""])?>"><?= $v->title ?></a></li>
                  <?php } ?>
                  
                </ul>
              </li>
            <?php } }
          }
        ?>