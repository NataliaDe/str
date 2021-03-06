<?php
//print_r($general);

$time_now=date("H:i:s");

if (isset($duty_ch) && !empty($duty_ch)) {
    $duty_ch = $duty_ch;
} else
    $duty_ch = 1;

//print_r($general);exit();
?>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="general_table">
        <!-- Содержимое вкладки  general_table -->
        <div class="col-lg-12">
            <br>
            <?php

            if(date(" H:i:s")< '00:00:00'){
                ?>
             <center> <u><b>Строевая записка ЦОУ, ШЛЧС c 08:00 <?= date("d.m.Y") ?> до 08:00  <?= date("d.m.Y", time() + (60 * 60 * 24)) ?>  </b></u></center>
            <?php
            }
            else{
                ?>
               <center> <u><b>Строевая записка  ЦОУ, ШЛЧС c 08:00 <?= date("d.m.Y", time() - (60 * 60 * 24)) ?> до 08:00  <?= date("d.m.Y") ?>  </b></u></center>
             <?php
            }
            ?>

            <br>
            <!--                <div class="table-responsive" id="div_tbl_general">-->
            <table class="table table-condensed   table-bordered" id="tbl_general">
                <thead>
                    <tr>
                        <th>Область</th>
                        <th>Г(Р)ОЧС</th>
                        <th>ЦОУ, ШЛЧС</th>
                        <th>Статус</th>
<!--                        <th>Есть недочеты</th>-->
                        <th>Заступившая<br>смена</th>
                        <th>Дата<br>заступления</th>
<!--                        <th>Информация</th>-->
                        <?php
                        if ((($_SESSION['ulevel'] == 1) || ($_SESSION['ulevel'] == 2) || ($_SESSION['ulevel'] == 3)) && ($_SESSION['is_admin'])) {//РЦУ может открыть доступ на ред.заступившей смене либо область
                            ?>
                            <th>Открыть/закрыть<br>доступ<br>
                                (доступно до <?= date('H:i', strtotime($time_allow_open))?>)

                            </th>
<!--                            <th>Доступ открыл</th>-->
                            <?php
                        }
?>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Область</th>
                        <th>Г(Р)ОЧС</th>
                         <th>ЦОУ, ШЛЧС</th>
                        <th>Статус</th>
<!--                        <th>Есть недочеты</th>-->
                        <th>Заступившая<br>смена</th>
                        <th>Дата<br>заступления</th>
<!--                        <th>Информация</th>-->
                        <?php
                        if ((($_SESSION['ulevel'] == 1) || ($_SESSION['ulevel'] == 2) || ($_SESSION['ulevel'] == 3)) && ($_SESSION['is_admin'])) {//РЦУ может открыть доступ на ред.заступившей смене либо область
                            ?>
                            <th></th>
<!--                            <th>Доступ открыл</th>-->
                            <?php
                        }

                        ?>

                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    foreach ($general as $row) {
                       //  foreach ($v as $row) {


                        if ($row['is_fill'] == 1) {
                            if ($row['open_update'] == 0) {//доступ закрыт
                                ?>
                                <tr class="success">
                                    <?php
                                } elseif ($row['open_update'] == 1)  {
                                    ?>
                                <tr class="info">
                                    <?php
                                }
                                 else{
                                    ?>
 <tr>
                                    <?php
                                }
                                ?>

                                <?php
                            } else {
                                ?>
                            <tr class="danger">
                                <?php
                            }
                            ?>

                            <td><?= $row['region'] ?></td>
                            <td><?= $row['locorg_name'] ?></td>
                            <td><a href="/str/v1/card/<?= $row['id_record'] ?>/ch/<?= $duty_ch ?>/main" data-toggle="tooltip" data-placement="left" title="Просмотр" target="_blank"><?= $row['divizion'] ?></a></td>
                            <td><?= $row['stat'] ?></td>
                            <td><?= (!empty($row['ch'])) ? $row['ch'] : '-' ?></td>
                            <td><?= (!empty($row['dateduty'])) ? date('d.m.Y', strtotime($row['dateduty'])) : '-' ?></td>


                            <?php
                            //РЦУ может открыть доступ на ред.заступившей смене
                            if ((($_SESSION['ulevel'] == 1) || ($_SESSION['ulevel'] == 2) || ($_SESSION['ulevel'] == 3) ) && ($_SESSION['is_admin'])) {//РЦУ может открыть доступ на ред.заступившей смене либо область
                                ?>
                                <td>
                                    <?php
                                    if ($row['open_update'] == 0) {//доступ закрыт,можно открыть

                                        //можно открыть доступ только той смене, которая сегодня заступила до 11:00:00 (время храним в БД)
                                       // if ($row['dateduty'] == date("Y-m-d") && ($time_now<$time_allow_open)) {
                                       //echo $row['is_duty'];
                                        if (date('Y-m-d', strtotime($row['dateduty'])) == date("Y-m-d") && ($time_now < $time_allow_open) && $row['is_duty']==1) {
                                            ?>
                                            <a href="/str/v2/card/open_update/<?= $row['id_record'] ?>" target="_blank"><button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="bottom" title="Доступ закрыт" ><span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span></button></a>
                                            <?php
                                        }
                                        ?>

                                        <?php
                                    } elseif ($row['open_update'] == 1){//доступ открыт-можно закрыть
                                        if (date('Y-m-d', strtotime($row['dateduty'])) == date("Y-m-d") && $time_now <$time_allow_open ) {

                                                ?>
                                                <a href="/str/v2/card/close_update/<?= $row['id_record']?>" target="_blank"><button type="button" class="btn btn-xs btn-success"  data-toggle="tooltip" data-placement="bottom" title="Доступ открыт"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button></a>
                                                <?php

                                        }
                                        ?>

                                                <?php
                                            }
                                            ?>
                                </td>

<!--                                <td>
                                    <?php
//                                    if ($row['open_update'] == 0) {//доступ закрыт,можно открыть
//                                        ?>

                                        //<?php
//                                    } else {
//                                        echo $row['who_open'];
//                                    }
                                    ?>
                                </td>-->

                                    <?php
                                }

                                ?>

                        </tr>
                            <?php
                       // }
                        }
                        ?>
                </tbody>
            </table>
            <!--                </div>-->
        </div>