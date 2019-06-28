<br>
<?php
 $trip = array();
                                    $id_of_trip=array();
/* ----------------------------------- право только у РЦУ админ, область админ, Авиация админ(ур 3) ------------------------------------- */
//print_r($trip);


                            if (isset($is_trip) && !empty($is_trip)) {
                                foreach ($is_trip as $i) {
                                    $trip[] = $i['id_fio'];
                                    $id_of_trip[$i['id_fio']] = $i['id']; //массив фио=>ill.id
                                }
                            } else {
                                $trip = array();
                            }

?>
<div class="container" id="container-query-result">
    <div class="col-lg-12">

        <!--        <div class="table-responsive"  id="tbl-query-result">-->
        <br>
        <center> <b>Работники, находящиеся в командировке</b></center>
        <table class="table table-condensed   table-bordered" id="tbl_list_fio">
            <!-- строка 1 -->
            <thead>
                <tr>
                    <th>Ф.И.О.</th>
                    <th>Звание</th>
                    <th>Должность</th>
                    <th>Подразделение</th>
                    <th>Смена</th>
                    <th>ГРОЧС</th>
                    <th>Область</th>
                   <th>Закрыть командировку</th>

                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th>Ф.И.О.</th>
                    <th>Звание</th>
                    <th>Должность</th>
                    <th>Подразделение</th>
                    <th>Смена</th>
                    <th>ГРОЧС</th>
                    <th>Область</th>
                    <th></th>

                </tr>
            </tfoot>

            <tbody>
                <?php
                foreach ($list_fio as $row) {

                    if ($row['ch'] == 1) {
                        ?>
                        <tr class="success">
                            <?php
                        } elseif ($row['ch'] == 2) {
                            ?>
                        <tr class="warning">
                            <?php
                        } elseif ($row['ch'] == 3) {
                            ?>
                        <tr class="info">
                            <?php
                        } else {
                            ?>
                        <tr class="danger">
                            <?php
                        }
                        ?>

                        <td><?= $row['fio'] ?></td>
                        <td><?= $row['rank'] ?></td>
                        <td><?= $row['position'] ?></td>
                        <td><?= $row['divizion'] ?></td>
                        <?php
                        if ($row['ch'] == 0) {
                            ?>
                            <td>ежедневник</td>
                            <?php
                        } else {
                            ?>
                            <td><?= $row['ch'] ?></td>
                            <?php
                        }
                        ?>


                        <td>
                            <?= $row['locorg_name'] ?>
                        </td>
                        <td>
                            <?= $row['region_name'] ?>
                        </td>


                        <!-------------- Закрыть больничный ------------------- -->

                        <td>
                            <?php


                            if (in_array($row['id_fio'], $trip)) {

                                ?>
                               <a href="/str/listfio/close_trip/<?= $id_of_trip[$row['id_fio']] ?>">отозв.</a>
                                    <a href="/str/listfio/close_trip/<?= $id_of_trip[$row['id_fio']] ?>"> <button class="btn btn-xs btn-danger" type="button"><i class="fa fa-calendar-times-o" aria-hidden="true"></i></button></a>

                                        <?php

                                    }
                                    ?>


                        </td>
                        <!--------------- END Закрыть больничный ------------------- -->


                    </tr>
                    <?php
                    // }
                }
                ?>

            </tbody>
        </table>

        <!--        </div>-->
    </div>
</div>