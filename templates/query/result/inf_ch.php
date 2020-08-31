<?php
//print_r($main['itogo_rb']);

if (isset($main) && !empty($main)) {

    foreach ($main as $key => $value) {
        if (isset($value['duty_date1'])) {
            $date = $value['duty_date1']; //дата, на которую выбраны даты
            break;
        }
    }
}

if (isset($main) && !empty($main)  && isset($date) && !empty($date)) {

         foreach ($main as $key => $value) {
        $date = $value['duty_date1']; //дата, на которую выбраны даты
        break;
    }


    if (isset($main['itogo']))
        $itogo_grochs = $main['itogo'];
    if (isset($main['itogo_obl']))
        $itogo_obl = $main['itogo_obl'];
    $itogo_rb = $main['itogo_rb'];
    //print_r($itogo_grochs);
    unset($main['itogo']);
    unset($main['itogo_obl']);
    unset($main['itogo_rb']);

    $last_id_grochs = 0;
$last_id_region = 0;
    ?>
<p> <a name="result_page"></a></p>
<br><br><br>
<center><b>Информация за <?= date('d.m.Y', strtotime($date)) ?></b></center>
<!--    <div class="table-responsive" id="tbl-query-result">-->
        <table class="table table-condensed   table-bordered tbl_show_inf" >
            <!--   строка 1 -->
            <thead>
                <tr >
                    <th rowspan="3">Область</th>
                    <th rowspan="3">Наименование подразделения</th>
                    <th colspan="12">Строевая записка по личному составу</th>

                </tr>
                <!-- строка 2 -->
            <th colspan="2">Подразделения</th>
            <th colspan="10">Дежурной смены</th>
            <tr>
                <th>По<br>штату</th>
                <th>Вакансия</th>
                <th>По<br>штату<br>в деж.<br>смене</th>
                <th>Вакансия<br>в деж.<br>смене</th>
                <th>Налицо</th>
                <th>В<br>боевом<br>расчете</th>
                <th>Ком-ка</th>
                <th>Отпуск</th>
                <th>Больные</th>
                <th>Наряд</th>
                <th>Др.<br>причины</th>
                <th>ГДЗС, чел</th>
            </tr>

            </thead>

            <tbody>
                <?php
                foreach ($main as $key => $value) {
                    if(is_int($key) && !empty($main[$key])){
                         if ($type == 1) {//кроме РОСН/UGZ
                              /* ++++++++++ итого по ГРОЧС ++++++++++++++ */
                             if ($last_id_grochs != $value['id_grochs'] && $last_id_grochs != 0) {
                                ?>
                <tr class="info">
                    <td></td>
                    <td><b>ИТОГО по Г(Р)ОЧС</b></td>
                        <td><?= $itogo_grochs[$last_id_grochs]['shtat'] ?></td>
                        <td><?=  $itogo_grochs[$last_id_grochs]['vacant'] ?></td>
                        <td><?=  $itogo_grochs[$last_id_grochs]['shtat_ch'] ?></td>
                        <td><?=  $itogo_grochs[$last_id_grochs]['vacant_ch']?></td>

                        <td><?=  $itogo_grochs[$last_id_grochs]['face'] ?></td>
                        <td><?=  $itogo_grochs[$last_id_grochs]['calc'] ?></td>

                        <td><?=  $itogo_grochs[$last_id_grochs]['trip'] ?></td>
                        <td><?=  $itogo_grochs[$last_id_grochs]['holiday'] ?></td>
                        <td><?=  $itogo_grochs[$last_id_grochs]['ill'] ?></td>
                        <td><?=  $itogo_grochs[$last_id_grochs]['duty'] ?></td>

                        <td><?= $itogo_grochs[$last_id_grochs]['other']?></td>
                        <td><?=  $itogo_grochs[$last_id_grochs]['gas'] ?></td>
                </tr>

                <?php
                             }
                               /* ++++ Итого по области ++++ */
                                if ($last_id_region != $value['region_id'] && $last_id_region != 0) {
                                    ?>
                <tr class="warning">
                                        <td></td>
                                        <td><b>ИТОГО по области</b></td>
                        <td><?= $itogo_obl[$last_id_region]['shtat'] ?></td>
                        <td><?=  $itogo_obl[$last_id_region]['vacant'] ?></td>
                        <td><?=  $itogo_obl[$last_id_region]['shtat_ch'] ?></td>
                        <td><?=  $itogo_obl[$last_id_region]['vacant_ch']?></td>

                        <td><?=  $itogo_obl[$last_id_region]['face'] ?></td>
                        <td><?=  $itogo_obl[$last_id_region]['calc'] ?></td>

                        <td><?=  $itogo_obl[$last_id_region]['trip'] ?></td>
                        <td><?=  $itogo_obl[$last_id_region]['holiday'] ?></td>
                        <td><?=  $itogo_obl[$last_id_region]['ill'] ?></td>
                        <td><?=  $itogo_obl[$last_id_region]['duty'] ?></td>

                        <td><?= $itogo_obl[$last_id_region]['other']?></td>
                        <td><?=  $itogo_obl[$last_id_region]['gas'] ?></td>
                </tr>
                        <?php
                                }
                        }
                    ?>
                <tr>
                        <td><?= $value['region_name'] ?></td>
                        <td><?= $value['name'] ?>, смена <?= $value['ch'] ?></td>
                        <td><?= $value['shtat'] ?></td>
                        <td><?= $value['vacant'] ?></td>
                        <td><?= $value['shtat_ch'] ?></td>
                        <td><?= $value['vacant_ch'] ?></td>

                        <td><?= $value['face'] ?></td>
                        <td><?= $value['calc'] ?></td>

                        <td><?= $value['trip'] ?></td>
                        <td><?= $value['holiday'] ?></td>
                        <td><?= $value['ill'] ?></td>
                        <td><?= $value['duty'] ?></td>

                        <td><?= $value['other'] ?></td>
                        <td><?= $value['gas'] ?></td>



                    </tr>
                    <?php
                      $last_id_grochs = $value['id_grochs'];
                            $last_id_region = $value['region_id'];
                    }

                }

                  if ($type == 1) {//кроме РОСН/UGZ
                    /* ++++ Итого по ГРОЧС ++++ */
                    if ($last_id_grochs && $last_id_grochs != 0) {
                       ?>
                        <tr class="info">
                    <td></td>
                    <td><b>ИТОГО по Г(Р)ОЧС</b></td>
                        <td><?= $itogo_grochs[$value['id_grochs']]['shtat'] ?></td>
                        <td><?=  $itogo_grochs[$value['id_grochs']]['vacant'] ?></td>
                        <td><?=  $itogo_grochs[$value['id_grochs']]['shtat_ch'] ?></td>
                        <td><?=  $itogo_grochs[$value['id_grochs']]['vacant_ch']?></td>

                        <td><?=  $itogo_grochs[$value['id_grochs']]['face'] ?></td>
                        <td><?=  $itogo_grochs[$value['id_grochs']]['calc'] ?></td>

                        <td><?=  $itogo_grochs[$value['id_grochs']]['trip'] ?></td>
                        <td><?=  $itogo_grochs[$value['id_grochs']]['holiday'] ?></td>
                        <td><?=  $itogo_grochs[$value['id_grochs']]['ill'] ?></td>
                        <td><?=  $itogo_grochs[$value['id_grochs']]['duty'] ?></td>

                        <td><?= $itogo_grochs[$value['id_grochs']]['other']?></td>
                        <td><?=  $itogo_grochs[$value['id_grochs']]['gas'] ?></td>
                </tr>
                    <?php

                    }
                        /* ++++ Итого по области ++++ */
                    if ($last_id_region && $last_id_region != 0) {
                        ?>
                 <tr class="warning">
                                        <td></td>
                                        <td><b>ИТОГО по области</b></td>
                        <td><?= $itogo_obl[$value['region_id']]['shtat'] ?></td>
                        <td><?=  $itogo_obl[$value['region_id']]['vacant'] ?></td>
                        <td><?=  $itogo_obl[$value['region_id']]['shtat_ch'] ?></td>
                        <td><?=  $itogo_obl[$value['region_id']]['vacant_ch']?></td>

                        <td><?=  $itogo_obl[$value['region_id']]['face'] ?></td>
                        <td><?=  $itogo_obl[$value['region_id']]['calc'] ?></td>

                        <td><?=  $itogo_obl[$value['region_id']]['trip'] ?></td>
                        <td><?=  $itogo_obl[$value['region_id']]['holiday'] ?></td>
                        <td><?=  $itogo_obl[$value['region_id']]['ill'] ?></td>
                        <td><?=  $itogo_obl[$value['region_id']]['duty'] ?></td>

                        <td><?= $itogo_obl[$value['region_id']]['other']?></td>
                        <td><?=  $itogo_obl[$value['region_id']]['gas'] ?></td>
                </tr>
                <?php
                    }
                    }
                ?>
                 <tr class="success">
                                        <td></td>
                                        <td><b>ИТОГО</b></td>
                        <td><?= $itogo_rb['shtat'] ?></td>
                        <td><?=  $itogo_rb['vacant'] ?></td>
                        <td><?=  $itogo_rb['shtat_ch'] ?></td>
                        <td><?=  $itogo_rb['vacant_ch']?></td>

                        <td><?=  $itogo_rb['face'] ?></td>
                        <td><?=  $itogo_rb['calc'] ?></td>

                        <td><?=  $itogo_rb['trip'] ?></td>
                        <td><?=  $itogo_rb['holiday'] ?></td>
                        <td><?=  $itogo_rb['ill'] ?></td>
                        <td><?=  $itogo_rb['duty'] ?></td>

                        <td><?= $itogo_rb['other']?></td>
                        <td><?=  $itogo_rb['gas'] ?></td>
                </tr>


            </tbody>
        </table>
<!--
    </div>-->
<!--информация по отсутствующим в виде таблицы-->

<div class="noprint" id="conttabl">
    <b> Выберите столбец, чтобы скрыть/отобразить:</b>

    <a class="toggle-vis" style="color: #b5031a;" data-column="8">Вид травмы</a> -
    <a class="toggle-vis" style="color: #b5031a;" data-column="9">Диагноз</a>  -
    <a class="toggle-vis" data-column="10" style="color: green;">Приказ</a> -
    <a class="toggle-vis" data-column= "11"  style="color: #8a7508;">Место и цель командирования</a> -
    <a class="toggle-vis" data-column= "12"  style="color: #037ab5;">Примечание</a>

</div>
<br>
<table class="table table-condensed   table-bordered tbl_show_inf" id="tbl_basic_inf_ch">
        <thead>
            <tr >
                <th >Область</th>
                <th>Наим.<br>подразд.</th>
                <th >Г(Р)ОЧС</th>
                <th>Ф.И.О.</th>
                <th>Должность</th>
                <th>Причина<br>отсутствия</th>
                <th>Дата<br>начала</th>
                <th>Дата<br>окончания</th>
                <th style="color: #b5031a;">Вид<br>травмы</th>
                <th style="color: #b5031a;">Диагноз</th>
                <th style="color: green;">Приказ</th>
                <th style="color: #8a7508;">Место и цель<br>команди-<br>рования</th>
                <th style="color: #037ab5;">Прим.</th>
            </tr>
        </thead>
        <tfoot>
                <tr >
                <th ></th>
                <th></th>
                <th ></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>

        <tbody>
 <?php
        foreach ($main as $key => $value) {
            ?>


            <?php
            /*------------- вывод работников в командировке -----------------*/
            if (!empty($main[$key]['trip_inf'])) {
                foreach ($main[$key]['trip_inf'] as $trip_inf) {
                    //$date2=(($trip_inf['date2']) != NULL) ? $trip_inf['date2']:'-';
                    ?>
        <tr class="warning">
                        <td><?= $value['region_name'] ?></td>
                        <td><?= $value['divizion_name'] ?></td>
                        <td><?= $value['grochs_name'] ?></td>
                        <td><?= $trip_inf['fio'] ?></td>
                        <td><?= $trip_inf['position'] . ' ' ?></td>
                        <td>командировка</td>
                        <td><?= $trip_inf['date1'] ?></td>
                        <td>  <?php echo (($trip_inf['date2']) != NULL) ? $trip_inf['date2'] : '-'; ?></td>
                        <td>-</td>
                        <td>-</td>
                        <td> <?php echo (($trip_inf['prikaz']) != NULL) ? $trip_inf['prikaz'] : 'не указано'; ?> </td>
                        <td> <?php echo (($trip_inf['place']) != NULL) ? $trip_inf['place'] : 'не указано'; ?> <br>
                <?php ($trip_inf['is_cosmr'] == 1) ?  ', согласовано с ЦОСМР' : ''; ?> </td>
                        <td>-</td>
                    </tr>

                <?php
            }
        }


            /*---------------------- отпуск --------------*/
            if (!empty($main[$key]['holiday_inf'])) {

                      foreach ($main[$key]['holiday_inf'] as $holiday_inf) {
                    ?>
                    <tr class="success">
                        <td><?= $value['region_name'] ?></td>
                        <td><?= $value['divizion_name'] ?></td>
                        <td><?= $value['grochs_name'] ?></td>
                        <td><?= $holiday_inf['fio'] ?></td>
                        <td><?= $holiday_inf['position'] . ' ' ?></td>
                        <td>отпуск</td>
                        <td><?= $holiday_inf['date1'] ?></td>
                        <td>  <?php echo (($holiday_inf['date2']) != NULL) ? $holiday_inf['date2'] : 'не указано'; ?></td>
                        <td>-</td>
                        <td>-</td>
                        <td> <?php echo (($holiday_inf['prikaz']) != NULL) ? $holiday_inf['prikaz'] : 'не указано'; ?> </td>
                        <td>-</td>
                        <td>-</td>
                    </tr>

                <?php
            }

            }

               /*--------------------- больные -------------*/
            if (!empty($main[$key]['ill_inf'])) {

                foreach ($main[$key]['ill_inf'] as $ill_inf) {
                    ?>

                    <tr class="danger">
                        <td><?= $value['region_name'] ?></td>
                        <td><?= $value['divizion_name'] ?></td>
                        <td><?= $value['grochs_name'] ?></td>
                        <td><?= $ill_inf['fio'] ?></td>
                        <td><?= $ill_inf['position'] . ' ' ?></td>
                        <td>больничный</td>
                        <td><?= $ill_inf['date1'] ?></td>
                        <td>  <?php echo (($ill_inf['date2']) != NULL) ? $ill_inf['date2'] : 'не указано'; ?></td>
                        <td><?= $ill_inf['maim'] ?></td>
                        <td><?php echo (($ill_inf['diagnosis']) != NULL) ? $ill_inf['diagnosis'] : 'не указано'; ?></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>

                    <?php
                }
            }


            /*------------------- вывод работников в наряде ---------- */
            if (!empty($main[$key]['duty_inf'])) {
                ?>
                    <tr >
                        <td><?= $value['region_name'] ?></td>
                        <td><?= $value['divizion_name'] ?></td>
                        <td><?= $value['grochs_name'] ?></td>
                        <td><?= $main[$key]['duty_inf'] ?></td>
                        <td>-</td>
                        <td>наряд</td>
                        <?php
                                    $date1_duty = new DateTime($value['duty_date1']);
                                    $d1_duty = $date1_duty->Format('d-m-Y');
                                    $date2_duty = new DateTime($value['duty_date2']);
                                    $d2_duty = $date2_duty->Format('d-m-Y');
                                    ?>
                        <td><?= $d1_duty ?></td>
                        <td><?= $d2_duty ?></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <?php
            }


            /*-------------- др причины ------------*/
            if (!empty($main[$key]['other_inf'])) {
                foreach ($main[$key]['other_inf'] as $other_inf) {
                    ?>
                    <tr class="info">
                        <td><?= $value['region_name'] ?></td>
                        <td><?= $value['divizion_name'] ?></td>
                        <td><?= $value['grochs_name'] ?></td>
                        <td><?= $other_inf['fio'] ?></td>
                        <td><?= $other_inf['position'] . ' ' ?></td>
                        <td>Др.причины</td>
                        <td><?= $other_inf['date1'] ?></td>
                        <td> <?php echo (($other_inf['date2']) != NULL) ? $other_inf['date2'] : 'не указано'; ?></td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td> <?php echo (($other_inf['reason']) != NULL) ?  $other_inf['reason'] : ''; ?>
                            <br> <?php echo (($other_inf['note']) != NULL) ?  $other_inf['note'] : ''; ?>
                        </td>
                    </tr>
                              <?php
                }
            }

                   /*-------------- ваканты ------------*/
            if (!empty($main[$key]['vacant_inf'])) {

                foreach ($main[$key]['vacant_inf'] as $vacant_inf) {

                    ?>
                    <tr style="background-color: khaki  !important;">
                        <td><?= $value['region_name'] ?></td>
                        <td><?= $value['divizion_name'] ?></td>
                        <td><?= $value['grochs_name'] ?></td>
                        <td><?= $vacant_inf['fio'] ?></td>
                        <td><?= $vacant_inf['position'] . ' ' ?></td>
                        <td>вакансия</td>
                        <td>-</td>
                        <td> -</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                              <?php
                }
            }
        ?>


        <?php
    }
    ?>

        </tbody>
    </table>

    <?php
} else {
    ?>
    <div class="container">
        <div class="alert alert-danger">

            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Внимание!</strong> Нет данных для отображения
        </div>
    </div>
    <?php
}
?>

