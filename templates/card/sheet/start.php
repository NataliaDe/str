<?php
/* foreach ($record as $k => $r) {
  if (($r['idCard'] == 160) || ($r['idCard'] == 162) || ($r['idCard'] == 163)) {//ROSN
  $p = $r['localName'] . ' ' . $r['divizionName'];
  } else {
  $p = $r['divizionName'] . ' № ' . $r['divizionNum'];
  }
  } */
$change = $change;
?>


<div >
    <!-- Навигация -->
    <ul class="nav nav-tabs" role="tablist" id="nav-tabs-user">
        <?php
        if ($change == 1) {
            ?>
            <li class="active"><a href="/str/v1/card/<?= $record_id ?>/ch/1/main" aria-controls="change1" role="tab" >1 смена</a></li>
            <li><a href="/str/v1/card/<?= $record_id ?>/ch/2/main" aria-controls="change2" role="tab" >2 смена</a></li>
            <li><a href="/str/v1/card/<?= $record_id ?>/ch/3/main" aria-controls="change3" role="tab" >3 смена</a></li>

            <?php
        } elseif ($change == 2) {
            ?>
            <li><a href="/str/v1/card/<?= $record_id ?>/ch/1/main" aria-controls="change1" role="tab" >1 смена</a></li>
            <li class="active"><a href="/str/v1/card/<?= $record_id ?>/ch/2/main" aria-controls="change2" role="tab" >2 смена</a></li>
            <li ><a href="/str/v1/card/<?= $record_id ?>/ch/3/main" aria-controls="change3" role="tab" >3 смена</a></li>

            <?php
        } else {
            ?>
            <li ><a href="/str/v1/card/<?= $record_id ?>/ch/1/main" aria-controls="change1" role="tab" >1 смена</a></li>
            <li><a href="/str/v1/card/<?= $record_id ?>/ch/2/main" aria-controls="change2" role="tab" >2 смена</a></li>
            <li class="active"><a href="/str/v1/card/<?= $record_id ?>/ch/3/main" aria-controls="change3" role="tab" >3 смена</a></li>

            <?php
        }
        ?>



    </ul>
    <!-- Содержимое вкладок -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" >

            <ul class="nav nav-tabs noprint" id="tabs">



                <?php
                // echo $sign;

                if ($sign == 5) {//main active
                    ?>
                    <li class="active">
                        <?php
                    } else {
                        ?>
                    <li>
                        <?php
                    }
                    ?>
                    <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/main" ><i class="fa fa-calendar-check-o fa-lg tilt" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Главная"></i></a></li>


                <?php
                if ($sign == 1) {//ill active
                    ?>
                    <li class="active">
                        <?php
                    } else {
                        ?>
                    <li>
                        <?php
                    }
                    ?>
                    <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/ill" ><i class="fa fa-bed fa-lg tilt" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Больничные"></i></a></li>

                <?php
                if ($sign == 2) {//holiday active
                    ?>
                    <li class="active">
                        <?php
                    } else {
                        ?>
                    <li>
                        <?php
                    }
                    ?>
                    <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/holiday" ><i class="fa fa-bicycle fa-lg tilt" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Отпуска"></i></a></li>

                <?php
                if ($sign == 3) {//trip active
                    ?>
                    <li class="active">
                        <?php
                    } else {
                        ?>
                    <li>
                        <?php
                    }
                    ?>
                    <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/trip" ><i class="fa fa-suitcase fa-lg tilt" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Командировки"></i></a></li>

                <?php
                if ($sign == 4) {//other reasons active
                    ?>
                    <li class="active">
                        <?php
                    } else {
                        ?>
                    <li>
                        <?php
                    }
                    ?>
                    <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/other" ><i class="fa fa-exclamation-triangle fa-lg tilt" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Другие причины"></i></a></li>
                <?php
                if ($sign == 6) {//technics active
                    ?>
                    <li class="active">
                        <?php
                    } else {
                        ?>
                    <li>
                        <?php
                    }
                    ?>
                    <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/car" ><i class="fa fa-car fa-lg tilt" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Техника"></i></a></li>
                <?php
                if ($sign == 7) {//storage active
                    ?>
                    <li class="active">
                        <?php
                    } else {
                        ?>
                    <li>
                        <?php
                    }
                    ?>
                    <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/storage" ><i class="fa fa-archive fa-lg tilt" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Склад"></i></a></li>




            </ul>







