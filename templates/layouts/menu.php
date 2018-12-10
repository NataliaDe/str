<?php
include 'topmenu.php';
?>

<div class="container-fluid"><!-- content -->

    <div class="row" ><!--  content row -->

        <?php
        include 'leftmenu.php';
        ?>


        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main " id="content-center"><!-- content data -->

            <div class="col-lg-12" id="container-content">
                <div class="container-fluid" id="chief-container">

                    <?php
                    if ($_SESSION['note'] == 8) {
                        $gr = 'РОСН';
                    } else {
                        if ($_SESSION['ulevel'] == 1) {
                            $gr = 'РЦУРЧС';
                        } else {
                            $obl = array(
                                1 => 'Брестская область',
                                2 => 'Витебская область',
                                3 => 'г.Минск',
                                4 => 'Гомельская область',
                                5 => 'Гродненская область',
                                6 => 'Минская область',
                                7 => 'Могилевская область'
                            );
                            $gr = $obl[$_SESSION['uregions']];
                        }
                    }
                    ?>
                   
                    <div class="row">

                       
 

                        <div id="auth">
                            <span ><?= $_SESSION['uname'] ?> | <?= $gr ?> <a href="/str/logout"><span class="glyphicon glyphicon-share" data-toggle="tooltip" data-placement="left" title="Выход"></span></a></span>
                        </div>

                    </div>




                    <!--  <div class="col-lg-2" id="container-calendar">
                  
                  
                    <?php
                    /* if (isset($inf) && !empty($inf)) {
                      foreach ($inf as $in) {
                      $c = $in['ch'];
                      $name = $in['name'];
                      $d = $in['dateduty'];
                      $last_update = $in['last_update'];
                      }
                      ?>
                      <center> <p id="cal-date-today"><?= date("d.m.Y") ?><br>смена <?= $c ?>
                      <i class="fa fa-hand-o-up" id="inf-duty-ch" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Заполнил <?= $name ?> <?= $last_update ?>"></i> </p>

                      </center>
                      <?php
                      } else {
                      ?>
                      <center> <p id="cal-date-today"><?= date("d.m.Y") ?></p></center>
                      <?php
                      } */
                    ?>
                      </div>-->











