<?php
//print_r($error);

$time_now = date("H:i:s");

if (isset($duty_ch) && !empty($duty_ch)) {
    $duty_ch = $duty_ch;
} else
    $duty_ch = 1;
?>
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="general_table">
        <!-- Содержимое вкладки  general_table -->
        <div class="col-lg-12">
            <br>
            <center> <u><b>Подразделения, заполнившие строевую записку с недочетами </b></u></center>
            <br>
            <!--                <div class="table-responsive" id="div_tbl_general">-->
            <table class="table table-condensed   table-bordered" id="tbl_general">
                <thead>
                    <tr>
                        <th>Область</th>
                        <th>Г(Р)ОЧС</th>
                        <th>ПАСЧ</th>
                        <th>Есть недочеты</th>
                        <th>Заступившая<br>смена</th>
                        <th>Дата<br>заступления</th>
<!--                        <th>Информация</th>-->
                        <?php
                        if ((($_SESSION['ulevel'] == 1) || ($_SESSION['ulevel'] == 2) || ($_SESSION['ulevel'] == 3)) && ($_SESSION['is_admin'])) {//РЦУ может открыть доступ на ред.заступившей смене либо область
                            ?>
                            <th>Открыть/закрыть<br>доступ</th>
                            <th>Доступ открыл</th>
                            <?php
                        }

                        if ((($_SESSION['ulevel'] == 1) ) && ($_SESSION['is_admin'])) {//РЦУ имеет право просматривать, сколько раз был открыт доступ
                            ?>
    <!--                            <th>Доступ открыт<br>(кол-во раз)</th>-->
                            <?php
                        }
                        ?>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Область</th>
                        <th>Г(Р)ОЧС</th>
                        <th>ПАСЧ</th>
                        <th>Есть недочеты</th>
                        <th>Заступившая<br>смена</th>
                        <th>Дата<br>заступления</th>
<!--                        <th>Информация</th>-->
                        <?php
                        if ((($_SESSION['ulevel'] == 1) || ($_SESSION['ulevel'] == 2) || ($_SESSION['ulevel'] == 3)) && ($_SESSION['is_admin'])) {//РЦУ может открыть доступ на ред.заступившей смене либо область
                            ?>
                            <th></th>
                            <th>Доступ открыл</th>
                            <?php
                        }

                        if ((($_SESSION['ulevel'] == 1) ) && ($_SESSION['is_admin'])) {//РЦУ имеет право просматривать, сколько раз был открыт доступ
                            ?>
    <!--                            <th>Доступ открыт<br>(кол-во раз)</th>-->
                            <?php
                        }
                        ?>

                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    foreach ($general as $row) {
                        if ($row['is_fill'] == 1) {
                            if ($row['open_update'] == 0) {//доступ закрыт
                                ?>
                                <tr class="success">
                                    <?php
                                } else {
                                    ?>
                                <tr class="info">                        
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
                            <td class="warning"><?php
                                foreach ($error as $e) {
                                    if ($e['id_card'] == $row['id_record']) {
                                        echo $e['msg'];
                                        break;
                                    }
                                }
                                ?></td>
                            <td><?= $row['ch'] ?></td>
                            <td><?= $row['dateduty'] ?></td>

                            <?php
                            //РЦУ может открыть доступ на ред.заступившей смене
                            if ((($_SESSION['ulevel'] == 1) || ($_SESSION['ulevel'] == 2) || ($_SESSION['ulevel'] == 3) ) && ($_SESSION['is_admin'])) {//РЦУ может открыть доступ на ред.заступившей смене либо область
                                ?>
                                <td>
                                    <?php
                                    if ($row['open_update'] == 0) {//доступ закрыт,можно открыть
                                        //можно открыть доступ только той смене, которая сегодня заступила до 11:00:00 (время храним в БД)
                                        if ($row['dateduty'] == date("d-m-Y") && ($time_now < $time_allow_open)) {
                                            ?>
                                            <a href="/str/open_update/<?= $row['id_main'] ?>" target="_blank"><button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="bottom" title="Доступ закрыт" ><span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span></button></a>
                                            <?php
                                        }
                                        ?>

                                        <?php
                                    } else {//доступ открыт-можно закрыть
                                        if ($row['dateduty'] == date("d-m-Y")) {
                                            if ($_SESSION['uid'] == $row['id_user']) {//закрыть доступ может тот, кто открывал
                                                ?>
                                                <a href="/str/close_update/<?= $row['id_main'] ?>" target="_blank"><button type="button" class="btn btn-xs btn-success"  data-toggle="tooltip" data-placement="bottom" title="Доступ открыт"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button></a>     
                                                <?php
                                            } else {
                                                ?>
                                                <a href="/str/close_update/<?= $row['id_main'] ?>"><button type="button" disabled="" class="btn btn-xs btn-success"  data-toggle="tooltip" data-placement="bottom" title="Доступ открыт"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button></a>     
                                                        <?php
                                                    }
                                                }
                                                ?>

                                        <?php
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if ($row['open_update'] == 0) {//доступ закрыт,можно открыть
                                        ?>

                                        <?php
                                    } else {
                                        echo $row['who_open'];
                                    }
                                    ?>
                                </td>

                                <?php
                            }

                            if ((($_SESSION['ulevel'] == 1) ) && ($_SESSION['is_admin'])) {//РЦУ имеет право просматривать, сколько раз был открыт доступ
                                ?>
                <!--                                <td>-->
                                <?php
//                                if (!empty($count_open)) {
//                                    foreach ($count_open as $value) {
//                                        if (($value['id_main'] == $row['id_main']) && ($row['dateduty'] == date("d-m-Y")) && (date("Y-m-d") == $value['dateduty'])) {
//                                            echo $value['count_open'];
//                                        }
//                                    }
//                                }
                                ?>
                                <!--                                </td>-->
                                <?php
                            }
                            ?>

                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
            <!--                </div>-->
        </div>