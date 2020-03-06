<?php
//Запросы-  информация по СЗ

//print_r($main);
//echo $type;
    unset($main['itogo']);
    unset($main['itogo_obl']);
    unset($main['itogo_rb']);
/* * * ИТОГО ** */
$shtat = 0;
$face = 0;
$calc = 0;
$duty = 0;
$trip = 0;
$hol = 0;
$ill = 0;
$other = 0;

$osn_res = 0;
$spec_res = 0;
$to1 = 0;
$to2 = 0;
$repair = 0;
$asv = 0;
$powder = 0;
$foam = 0;

$c_osn_rb=0;
$c_spec_rb=0;

/* * * ИТОГО  по ГРОЧС** */
$shtat_g = 0;
$face_g = 0;
$calc_g = 0;
$duty_g = 0;
$trip_g = 0;
$hol_g = 0;
$ill_g = 0;
$other_g = 0;

$osn_res_g = 0;
$spec_res_g = 0;
$to1_g = 0;
$to2_g = 0;
$repair_g = 0;
$asv_g = 0;
$powder_g = 0;
$foam_g = 0;

$c_osn_grochs=0;
$c_spec_grochs=0;

/* * * ИТОГО  по области** */
$shtat_r = 0;
$face_r = 0;
$calc_r = 0;
$duty_r = 0;
$trip_r = 0;
$hol_r = 0;
$ill_r = 0;
$other_r = 0;

$osn_res_r = 0;
$spec_res_r = 0;
$to1_r = 0;
$to2_r = 0;
$repair_r = 0;
$asv_r = 0;
$powder_r = 0;
$foam_r = 0;

$c_osn_obl=0;
$c_spec_obl=0;

if (isset($main) && !empty($main)) {
 foreach ($main as $key => $value) {
     $dateduty=$value['dateduty'];//дата, на которую сформирован отчет

      $date_d = new DateTime($dateduty);
        $date_result = $date_d->Format('d-m-Y');

     continue;
 }
}


if (isset($main) && !empty($main) && isset($dateduty) && !empty($dateduty)) {

    ?>
    <p> <a name="result_page"></a></p>
    <br><br><br>
    <center><b> Результат на <?= date('d.m.Y', strtotime($date_result)) ?></b></center>
<!--    <div class="table-responsive" id="tbl-query-result">-->
        <table class="table table-condensed   table-bordered tbl_show_inf" >
            <!--               строка 1 -->
            <thead>
                <tr >
                    <th rowspan="2">№</th>
                    <th rowspan="2">Область</th>
                    <th rowspan="2">Наименование подразделения</th>
                    <th colspan="8">По личному составу</th>
                    <th colspan="2">Основная<br>техника</th>
                    <th colspan="2">Специальная<br>техника</th>

                    <th colspan="2">Резерв</th>
                    <th colspan="2">На<br>ТО</th>
                    <th rowspan="2">Ре-<br>монт</th>



                    <th colspan="3">Склад</th>

                    <th rowspan="2">Начальник<br>деж.смены</th>

                </tr>
                <!--                 строка 2 -->
                <tr>
                    <th>по<br>штату</th>
                    <th>налицо</th>
                    <th>боевой<br>расчет</th>
                    <th>наряд</th>
                    <th>ком-ка</th>
                    <th>отпуск</th>
                    <th>болен</th>
                    <th>др.<br>причины</th>

                     <th>марка</th>
                    <th>кол-во</th>

                     <th>марка</th>
                    <th>кол-во</th>

                    <th>основн.</th>
                    <th>спец.</th>


                    <th>ТО-1</th>
                    <th>ТО-2</th>

                    <th>АСВ</th>
                    <th>П<br>(л)</th>
                    <th>ПО<br>(л)</th>
                </tr>

            </thead>

            <tbody>
                <?php
                $i = 0;
                $last_id_grochs = 0;
                $last_id_region = 0;
                foreach ($main as $key => $value) {
                    $i++;

                    if ($type == 1) {//кроме РОСН/UGZ
                        if ($last_id_grochs != $value['id_grochs'] && $last_id_grochs != 0) {

                        /* ++++ Итого по ГРОЧС ++++ */
                        ?>
                        <tr class="info">
                            <td></td>
                            <td><b>ИТОГО по Г(Р)ОЧС:</b></td>
                            <td></td>
                            <td><b><?= $shtat_g ?></b></td>
                            <td><b><?= $face_g ?></b></td>
                            <td><b><?= $calc_g ?></b></td>
                            <td><b><?= $duty_g ?></b></td>
                            <td><b><?= $trip_g ?></b></td>
                            <td><b><?= $hol_g ?></b></td>
                            <td><b><?= $ill_g ?></b></td>
                            <td><b><?= $other_g ?></b></td>
                            <td></td>
                            <td><?= $c_osn_grochs ?></td>
                              <td></td>
                            <td><?= $c_spec_grochs ?></td>
                            <td><b><?= $osn_res_g ?></b></td>
                            <td><b><?= $spec_res_g ?></b></td>
                            <td><b><?= $to1_g ?></b></td>
                            <td><b><?= $to2_g ?></b></td>
                            <td><b><?= $repair_g ?></b></td>
                            <td><b><?= $asv_g ?></b></td>
                            <td><b><?= $powder_g ?></b></td>
                            <td><b><?= $foam_g ?></b></td>
                            <td></td>

                        </tr>
                        <?php
                        /*                         * * ИТОГО  по ГРОЧС обнулить** */
                        $shtat_g = 0;
                        $face_g = 0;
                        $calc_g = 0;
                        $duty_g = 0;
                        $trip_g = 0;
                        $hol_g = 0;
                        $ill_g = 0;
                        $other_g = 0;

                        $osn_res_g = 0;
                        $spec_res_g = 0;
                        $to1_g = 0;
                        $to2_g = 0;
                        $repair_g = 0;
                        $asv_g = 0;
                        $powder_g = 0;
                        $foam_g = 0;

                        $c_osn_grochs = 0;
                        $c_spec_grochs = 0;


                                    }

                     if ($last_id_region != $value['region_id'] && $last_id_region != 0) {

                        /* ++++ Итого по области ++++ */
                        ?>
                        <tr class="warning">
                            <td></td>
                            <td><b>ИТОГО по области:</b></td>
                            <td></td>
                            <td><b><?= $shtat_r ?></b></td>
                            <td><b><?= $face_r ?></b></td>
                            <td><b><?= $calc_r ?></b></td>
                            <td><b><?= $duty_r ?></b></td>
                            <td><b><?= $trip_r ?></b></td>
                            <td><b><?= $hol_r ?></b></td>
                            <td><b><?= $ill_r ?></b></td>
                            <td><b><?= $other_r ?></b></td>
                            <td></td>
                            <td><?= $c_osn_obl ?></td>
                              <td></td>
                            <td><?= $c_spec_obl ?></td>
                            <td><b><?= $osn_res_r ?></b></td>
                            <td><b><?= $spec_res_r ?></b></td>
                            <td><b><?= $to1_r ?></b></td>
                            <td><b><?= $to2_r ?></b></td>
                            <td><b><?= $repair_r ?></b></td>
                            <td><b><?= $asv_r ?></b></td>
                            <td><b><?= $powder_r ?></b></td>
                            <td><b><?= $foam_r ?></b></td>
                            <td></td>

                        </tr>
                        <?php
                        /*                         * * ИТОГО  по области обнулить** */
                        $shtat_r = 0;
                        $face_r = 0;
                        $calc_r = 0;
                        $duty_r = 0;
                        $trip_r = 0;
                        $hol_r = 0;
                        $ill_r = 0;
                        $other_r = 0;

                        $osn_res_r = 0;
                        $spec_res_r = 0;
                        $to1_r = 0;
                        $to2_r = 0;
                        $repair_r = 0;
                        $asv_r = 0;
                        $powder_r = 0;
                        $foam_r = 0;
                        $c_osn_obl=0;
                        $c_spec_obl=0;

                    }
                    }

                    ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $value['region_name'] ?></td>
                        <td><?= $value['name'] ?>, смена <?= $value['ch'] ?></td>
                        <td><?= $value['shtat'] ?></td>
                        <td><?= $value['face'] ?></td>
                        <td><?= $value['calc'] ?></td>
                        <td><?= $value['duty'] ?></td>
                        <td><?= $value['trip'] ?></td>
                        <td><?= $value['holiday'] ?></td>
                        <td><?= $value['ill'] ?></td>
                        <td><?= $value['other'] ?></td>


                        <td>
                            <?php
                                    if (!empty($value['osn_car'])) {
                                        $c_osn = count($value['osn_car']); //кол-во осн техн
                                        foreach ($value['osn_car'] as $osn_car) {
                                            echo $osn_car . '<br>';
                                        }
                                    } else {
                                        $c_osn = 0; //кол-во осн техн
                                    }
                                   // echo $c_osn;
                                    ?>
                        </td>
                        <td><?= $c_osn ?></td>
                        <td>
                            <?php
                            if (!empty($value['spec_car'])) {
                                 $c_spec = count($value['spec_car']); //кол-во спец техн
                                foreach ($value['spec_car'] as $spec_car) {
                                    echo $spec_car . '<br>';
                                }
                            }
                            else{
                                $c_spec=0; //кол-во спец техн
                            }
                            //echo $c_spec;
                            ?>
                        </td>
                        <td><?= $c_spec ?></td>
                        <td><?= $value['osn_reserve'] ?></td>
                        <td><?= $value['spec_reserve'] ?></td>

                        <td><?= $value['to1'] ?></td>
                        <td><?= $value['to2'] ?></td>

                        <td><?= $value['repair'] ?></td>
                        <td><?= $value['asv'] ?></td>

                        <td><?= $value['powder'] ?></td>
                        <td><?= $value['foam'] ?></td>

                        <td><?= $value['fio_head'] ?></td>


                    </tr>
                    <?php
                    /*                     * ******* ИТОГО ************ */
                    $shtat+=$value['shtat'];
                    $face+=$value['face'];
                    $calc+=$value['calc'];
                    $duty+=$value['duty'];
                    $trip+=$value['trip'];
                    $hol+=$value['holiday'];
                    $ill+=$value['ill'];
                    $other+=$value['other'];

                    $osn_res+=$value['osn_reserve'];
                    $spec_res+=$value['spec_reserve'];
                    $to1+=$value['to1'];
                    $to2+=$value['to2'];
                    $repair+=$value['repair'];
                    $asv+=$value['asv'];
                    $powder+=str_replace(",", ".", $value['powder']);
                    $foam+=str_replace(",", ".", $value['foam']);
                    $c_osn_rb+=$c_osn;
                    $c_spec_rb+=$c_spec;

                    /*                     * ******* ИТОГО по ГРОЧС подсчет ************ */
                    $shtat_g+=$value['shtat'];
                    $face_g+=$value['face'];
                    $calc_g+=$value['calc'];
                    $duty_g+=$value['duty'];
                    $trip_g+=$value['trip'];
                    $hol_g+=$value['holiday'];
                    $ill_g+=$value['ill'];
                    $other_g+=$value['other'];

                    $osn_res_g+=$value['osn_reserve'];
                    $spec_res_g+=$value['spec_reserve'];
                    $to1_g+=$value['to1'];
                    $to2_g+=$value['to2'];
                    $repair_g+=$value['repair'];
                    $asv_g+=$value['asv'];
                    $powder_g+=str_replace(",", ".", $value['powder']);
                    $foam_g+=str_replace(",", ".", $value['foam']);
                    $c_osn_grochs+=$c_osn;
                    $c_spec_grochs+=$c_spec;

                    /*                     * ******* ИТОГО по области подсчет ************ */
                    $shtat_r+=$value['shtat'];
                    $face_r+=$value['face'];
                    $calc_r+=$value['calc'];
                    $duty_r+=$value['duty'];
                    $trip_r+=$value['trip'];
                    $hol_r+=$value['holiday'];
                    $ill_r+=$value['ill'];
                    $other_r+=$value['other'];

                    $osn_res_r+=$value['osn_reserve'];
                    $spec_res_r+=$value['spec_reserve'];
                    $to1_r+=$value['to1'];
                    $to2_r+=$value['to2'];
                    $repair_r+=$value['repair'];
                    $asv_r+=$value['asv'];
                    $powder_r+=str_replace(",", ".", $value['powder']);
                    $foam_r+=str_replace(",", ".", $value['foam']);
                    $c_osn_obl+=$c_osn;
                    $c_spec_obl+=$c_spec;


                    $last_id_grochs = $value['id_grochs'];
                    $last_id_region = $value['region_id'];
                }

                       if ($type == 1) {//кроме РОСН/UGZ
                                    /* ++++ Итого по ГРОЧС ++++ */
                if ($last_id_grochs && $last_id_grochs != 0) {
                    ?>
                    <tr class="info">
                        <td></td>
                        <td><b>ИТОГО по Г(Р)ОЧС:</b></td>
                        <td></td>
                        <td><b><?= $shtat_g ?></b></td>
                        <td><b><?= $face_g ?></b></td>
                        <td><b><?= $calc_g ?></b></td>
                        <td><b><?= $duty_g ?></b></td>
                        <td><b><?= $trip_g ?></b></td>
                        <td><b><?= $hol_g ?></b></td>
                        <td><b><?= $ill_g ?></b></td>
                        <td><b><?= $other_g ?></b></td>
                        <td></td>
                        <td><?= $c_osn_grochs ?></td>
                          <td></td>
                            <td><?= $c_spec_grochs ?></td>
                        <td><b><?= $osn_res_g ?></b></td>
                        <td><b><?= $spec_res_g ?></b></td>
                        <td><b><?= $to1_g ?></b></td>
                        <td><b><?= $to2_g ?></b></td>
                        <td><b><?= $repair_g ?></b></td>
                        <td><b><?= $asv_g ?></b></td>
                        <td><b><?= $powder_g ?></b></td>
                        <td><b><?= $foam_g ?></b></td>
                        <td></td>

                    </tr>
                    <?php
                    /*                     * * ИТОГО  по ГРОЧС обнулить** */
                    $shtat_g = 0;
                    $face_g = 0;
                    $calc_g = 0;
                    $duty_g = 0;
                    $trip_g = 0;
                    $hol_g = 0;
                    $ill_g = 0;
                    $other_g = 0;

                    $osn_res_g = 0;
                    $spec_res_g = 0;
                    $to1_g = 0;
                    $to2_g = 0;
                    $repair_g = 0;
                    $asv_g = 0;
                    $powder_g = 0;
                    $foam_g = 0;

                    $c_osn_grochs=0;
                    $c_spec_grochs=0;
                }

                   /* ++++ Итого по области ++++ */
                  if ($last_id_region  && $last_id_region != 0) {

                        ?>
                        <tr class="warning">
                            <td></td>
                            <td><b>ИТОГО по области:</b></td>
                            <td></td>
                            <td><b><?= $shtat_r ?></b></td>
                            <td><b><?= $face_r ?></b></td>
                            <td><b><?= $calc_r ?></b></td>
                            <td><b><?= $duty_r ?></b></td>
                            <td><b><?= $trip_r ?></b></td>
                            <td><b><?= $hol_r ?></b></td>
                            <td><b><?= $ill_r ?></b></td>
                            <td><b><?= $other_r ?></b></td>
                            <td></td>
                            <td><?= $c_osn_obl ?></td>
                              <td></td>
                            <td><?= $c_spec_obl ?></td>
                            <td><b><?= $osn_res_r ?></b></td>
                            <td><b><?= $spec_res_r ?></b></td>
                            <td><b><?= $to1_r ?></b></td>
                            <td><b><?= $to2_r ?></b></td>
                            <td><b><?= $repair_r ?></b></td>
                            <td><b><?= $asv_r ?></b></td>
                            <td><b><?= $powder_r ?></b></td>
                            <td><b><?= $foam_r ?></b></td>
                            <td></td>

                        </tr>
                        <?php
                        /*                         * * ИТОГО  по области** */
                        $shtat_r = 0;
                        $face_r = 0;
                        $calc_r = 0;
                        $duty_r = 0;
                        $trip_r = 0;
                        $hol_r = 0;
                        $ill_r = 0;
                        $other_r = 0;

                        $osn_res_r = 0;
                        $spec_res_r = 0;
                        $to1_r = 0;
                        $to2_r = 0;
                        $repair_r = 0;
                        $asv_r = 0;
                        $powder_r = 0;
                        $foam_r = 0;

                        $c_osn_obl=0;
                        $c_spec_obl=0;

                    }
                       }

                ?>
                <tr class="success">
                    <td></td>
                    <td><b>ИТОГО:</b></td>
                    <td></td>
                    <td><b><?= $shtat ?></b></td>
                    <td><b><?= $face ?></b></td>
                    <td><b><?= $calc ?></b></td>
                    <td><b><?= $duty ?></b></td>
                    <td><b><?= $trip ?></b></td>
                    <td><b><?= $hol ?></b></td>
                    <td><b><?= $ill ?></b></td>
                    <td><b><?= $other ?></b></td>
                    <td></td>
                    <td><?= $c_osn_rb ?></td>
                      <td></td>
                            <td><?= $c_spec_rb ?></td>
                    <td><b><?= $osn_res ?></b></td>
                    <td><b><?= $spec_res ?></b></td>
                    <td><b><?= $to1 ?></b></td>
                    <td><b><?= $to2 ?></b></td>
                    <td><b><?= $repair ?></b></td>
                    <td><b><?= $asv ?></b></td>
                    <td><b><?= $powder ?></b></td>
                    <td><b><?= $foam ?></b></td>
                    <td></td>

                </tr>
            </tbody>
        </table>


<!--    </div>-->
    <!--информация по отсутствующим в виде таблицы-->



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

