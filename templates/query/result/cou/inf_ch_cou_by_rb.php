<style>
    .main-cou-request tr th {
        font-size: 11px;
    }
</style>

<?php
//print_r($main['itogo_rb']);
//print_r($cnt_by_rb);
if (isset($main_cou) && !empty($main_cou)) {

    ?>
    <p> <a name="result_page"></a></p>
    <br><br>
        <center><b>
                <?php
                if (isset($head_info) && !empty($head_info)) {
                    // foreach ($head_info as $h) {
                    $dateduty_head = date('d.m.Y', strtotime($head_info['dateduty']));
                    $name_head = $head_info['name'];
                    //}
                    echo 'Результат запроса за ' . $dateduty_head . ', ' . $name_head.'. '.'ЦОУ.';
                }

                ?>
                <br>
            </b>
    <!--    <div class="table-responsive" id="tbl-query-result">-->
    <table class="table table-condensed   table-bordered tbl_show_inf main-cou-request" style="width: 79% !important" >
        <!--   строка 1 -->
        <thead>
            <tr >
                <?php
                //$c=14+count($posduty_list);
                $c=14+14;
                ?>
                <th colspan="<?= $c ?>">Строевая записка по личному составу</th>
            </tr>
            <!-- строка 2 -->
        <th colspan="2"></th>
        <th colspan="<?= count($posduty_list) ?>">Должность</th>
        <th colspan="7">Дежурной смены</th>
        <th colspan="2">Подразделения</th>
        <tr>
            <th >Область</th>
            <th >Г(Р)ОЧС</th>

            <th>Нач-к.<br>см.</th>
            <th>ОД</th>
            <th>Зам.<br>ОД</th>
            <th>Ст.пом.<br>ОД</th>
            <th>Пом.<br>ОД</th>
            <th>Диспетчер</th>
            <th>Инж.<br>ТКС</th>
            <th>Инж.<br>связи</th>
            <th>Мастер.<br>связи</th>
            <th>Водитель</th>
            <th>Стажер</th>
            <th>Другие</th>
            <th>Инспектор.<br>ОНиП</th>
            <th>Отв. по <br>гарнизону</th>

                <?php
               // foreach ($posduty_list as $value) {

                    ?>
<!--                    <th>-->
<!--                    $value['name'] -->
<!--                    </th>-->
                    <?php
              //  }

                ?>


            <th>по штату<br>в деж.<br>смене</th>
            <th>вакансия<br>в деж.<br>смене</th>
            <th>в<br>боевом<br>расчете</th>
            <th>ком-ка</th>
            <th>отп.</th>
            <th>больн.</th>
            <th>др.<br>прич.</th>

            <th>по штату</th>
            <th>вакансия</th>
        </tr>

    </thead>

    <tbody>

            <?php
            $prev_region_id=0;

            $itogo_region_shtat_ch = 0;
            $itogo_region_vacant_ch = 0;
            $itogo_region_br = 0;
            $itogo_region_trip = 0;
            $itogo_region_holiday = 0;
            $itogo_region_ill = 0;
            $itogo_region_other = 0;
            $itogo_region_shtat = 0;
            $itogo_region_vacant = 0;

            $itogo_rb_shtat_ch = 0;
            $itogo_rb_vacant_ch = 0;
            $itogo_rb_br = 0;
            $itogo_rb_trip = 0;
            $itogo_rb_holiday = 0;
            $itogo_rb_ill = 0;
            $itogo_rb_other = 0;
            $itogo_rb_shtat = 0;
            $itogo_rb_vacant = 0;




            foreach ($main_cou as $key=>$row) {

                $region_id=$row['general_inf']['region_id'];
                ?>



                        <?php
                        if ($region_id != $prev_region_id) {


                            if (isset($cnt_by_region[$prev_region_id])) {

                                ?>
        <tr class="warning">
                                <td colspan="2">Итого по области</td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][1]) && $cnt_by_region[$prev_region_id][1] != 0) ? $cnt_by_region[$prev_region_id][1] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][2]) && $cnt_by_region[$prev_region_id][2] != 0) ? $cnt_by_region[$prev_region_id][2] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][3]) && $cnt_by_region[$prev_region_id][3] != 0) ? $cnt_by_region[$prev_region_id][3] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][4]) && $cnt_by_region[$prev_region_id][4] != 0) ? $cnt_by_region[$prev_region_id][4] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][5]) && $cnt_by_region[$prev_region_id][5] != 0) ? $cnt_by_region[$prev_region_id][5] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][6]) && $cnt_by_region[$prev_region_id][6] != 0) ? $cnt_by_region[$prev_region_id][6] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][7]) && $cnt_by_region[$prev_region_id][7] != 0) ? $cnt_by_region[$prev_region_id][7] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][8]) && $cnt_by_region[$prev_region_id][8] != 0) ? $cnt_by_region[$prev_region_id][8] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][9]) && $cnt_by_region[$prev_region_id][9] != 0) ? $cnt_by_region[$prev_region_id][9] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][10]) && $cnt_by_region[$prev_region_id][10] != 0) ? $cnt_by_region[$prev_region_id][10] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][11]) && $cnt_by_region[$prev_region_id][11] != 0) ? $cnt_by_region[$prev_region_id][11] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][12]) && $cnt_by_region[$prev_region_id][12] != 0) ? $cnt_by_region[$prev_region_id][12] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][13]) && $cnt_by_region[$prev_region_id][13] != 0) ? $cnt_by_region[$prev_region_id][13] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][14]) && $cnt_by_region[$prev_region_id][14] != 0) ? $cnt_by_region[$prev_region_id][14] : '' ?></td>
                                <td><?= (isset($itogo_region_shtat_ch) && $itogo_region_shtat_ch != 0) ? $itogo_region_shtat_ch : '' ?></td>
                                <td><?= (isset($itogo_region_vacant_ch) && $itogo_region_vacant_ch != 0) ? $itogo_region_vacant_ch : '' ?></td>
                                <td><?= (isset($itogo_region_br) && $itogo_region_br != 0) ? $itogo_region_br : '' ?></td>
                                <td><?= (isset($itogo_region_trip) && $itogo_region_trip != 0) ? $itogo_region_trip : '' ?></td>
                                <td><?= (isset($itogo_region_holiday) && $itogo_region_holiday != 0) ? $itogo_region_holiday : '' ?></td>
                                <td><?= (isset($itogo_region_ill) && $itogo_region_ill != 0) ? $itogo_region_ill : '' ?></td>
                                <td><?= (isset($itogo_region_other) && $itogo_region_other != 0) ? $itogo_region_other : '' ?></td>
                                <td><?= (isset($itogo_region_shtat) && $itogo_region_shtat != 0) ? $itogo_region_shtat : '' ?></td>
                                <td><?= (isset($itogo_region_vacant) && $itogo_region_vacant != 0) ? $itogo_region_vacant : '' ?></td>
                            </tr>
                                <?php
                            }




            $itogo_rb_shtat_ch +=$itogo_region_shtat_ch;
            $itogo_rb_vacant_ch +=$itogo_region_vacant_ch;
            $itogo_rb_br +=$itogo_region_br;
            $itogo_rb_trip +=$itogo_region_trip;
            $itogo_rb_holiday +=$itogo_region_holiday;
            $itogo_rb_ill +=$itogo_region_ill;
            $itogo_rb_other +=$itogo_region_other;
            $itogo_rb_shtat +=$itogo_region_shtat;
            $itogo_rb_vacant +=$itogo_region_vacant;




            $itogo_region_shtat_ch = 0;
            $itogo_region_vacant_ch = 0;
            $itogo_region_br = 0;
            $itogo_region_trip = 0;
            $itogo_region_holiday = 0;
            $itogo_region_ill = 0;
            $itogo_region_other = 0;
            $itogo_region_shtat = 0;
            $itogo_region_vacant = 0;
                        }

                        ?>


                <tr>
                    <td><?= $row['general_inf']['region'] ?></td>
                    <td><?= $row['general_inf']['locorg'] ?><br>
                        <?= $row['general_inf']['divizion_name'] ?></td>

                    <td><?= (isset($row['position'][1])) ? $row['position'][1]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][2])) ? $row['position'][2]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][3])) ? $row['position'][3]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][4])) ? $row['position'][4]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][5])) ? $row['position'][5]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][6])) ? $row['position'][6]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][7])) ? $row['position'][7]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][8])) ? $row['position'][8]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][9])) ? $row['position'][9]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][10])) ? $row['position'][10]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][11])) ? $row['position'][11]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][12])) ? $row['position'][12]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][13])) ? $row['position'][13]['pos_cnt'] : '' ?></td>
                    <td><?= (isset($row['position'][14])) ? $row['position'][14]['pos_cnt'] : '' ?></td>

                     <td><?= (isset($shtat[$key]['shtat_ch']) && $shtat[$key]['shtat_ch'] != 0) ? $shtat[$key]['shtat_ch'] : '' ?></td>
                     <?php
                     $itogo_region_shtat_ch+=$shtat[$key]['shtat_ch'];
                     ?>

                     <td><?= (isset($shtat[$key]['vacant_ch']) && $shtat[$key]['vacant_ch'] != 0) ? $shtat[$key]['vacant_ch'] : '' ?></td>
                     <?php
                     $itogo_region_vacant_ch+=$shtat[$key]['vacant_ch'];
                     ?>
                      <td><?= (isset($count_fio_on_car[$key]) && $count_fio_on_car[$key] != 0) ? $count_fio_on_car[$key] : '' ?></td>
                     <?php
                     $itogo_region_br+=$count_fio_on_car[$key];
                     ?>
                     <td><?= (isset($absent[$key]['trip']) && $absent[$key]['trip'] != 0) ? $absent[$key]['trip'] : '' ?></td>
                     <?php
                     $itogo_region_trip+=$absent[$key]['trip'];
                     ?>
                     <td><?= (isset($absent[$key]['holiday']) && $absent[$key]['holiday'] != 0) ? $absent[$key]['holiday'] : '' ?></td>
                     <?php
                     $itogo_region_holiday+=$absent[$key]['holiday'];
                     ?>
                     <td><?= (isset($absent[$key]['ill']) && $absent[$key]['ill'] != 0) ? $absent[$key]['ill'] : '' ?></td>
                     <?php
                     $itogo_region_ill+=$absent[$key]['ill'];
                     ?>
                     <td><?= (isset($absent[$key]['other']) && $absent[$key]['other'] != 0) ? $absent[$key]['other'] : '' ?></td>
                                          <?php
                     $itogo_region_other+=$absent[$key]['other'];
                     ?>
                     <td><?= (isset($shtat[$key]['shtat']) && $shtat[$key]['shtat'] != 0) ? $shtat[$key]['shtat'] : '' ?></td>
                                          <?php
                     $itogo_region_shtat+=$shtat[$key]['shtat'];
                     ?>
                     <td><?= (isset($shtat[$key]['vacant']) && $shtat[$key]['vacant'] != 0) ? $shtat[$key]['vacant'] : '' ?></td>
                                                               <?php
                     $itogo_region_vacant+=$shtat[$key]['vacant'];
                     ?>
                </tr>



            <?php
            $prev_region_id=$region_id;
        }



            $itogo_rb_shtat_ch +=$itogo_region_shtat_ch;
            $itogo_rb_vacant_ch +=$itogo_region_vacant_ch;
            $itogo_rb_br +=$itogo_region_br;
            $itogo_rb_trip +=$itogo_region_trip;
            $itogo_rb_holiday +=$itogo_region_holiday;
            $itogo_rb_ill +=$itogo_region_ill;
            $itogo_rb_other +=$itogo_region_other;
            $itogo_rb_shtat +=$itogo_region_shtat;
            $itogo_rb_vacant +=$itogo_region_vacant;
        ?>


                    <?php
                    if ( $prev_region_id != 0) {


                        if (isset($cnt_by_region[$prev_region_id])) {

                            ?>
                <tr class="warning">
                                <td colspan="2">Итого по области</td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][1]) && $cnt_by_region[$prev_region_id][1] != 0) ? $cnt_by_region[$prev_region_id][1] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][2]) && $cnt_by_region[$prev_region_id][2] != 0) ? $cnt_by_region[$prev_region_id][2] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][3]) && $cnt_by_region[$prev_region_id][3] != 0) ? $cnt_by_region[$prev_region_id][3] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][4]) && $cnt_by_region[$prev_region_id][4] != 0) ? $cnt_by_region[$prev_region_id][4] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][5]) && $cnt_by_region[$prev_region_id][5] != 0) ? $cnt_by_region[$prev_region_id][5] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][6]) && $cnt_by_region[$prev_region_id][6] != 0) ? $cnt_by_region[$prev_region_id][6] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][7]) && $cnt_by_region[$prev_region_id][7] != 0) ? $cnt_by_region[$prev_region_id][7] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][8]) && $cnt_by_region[$prev_region_id][8] != 0) ? $cnt_by_region[$prev_region_id][8] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][9]) && $cnt_by_region[$prev_region_id][9] != 0) ? $cnt_by_region[$prev_region_id][9] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][10]) && $cnt_by_region[$prev_region_id][10] != 0) ? $cnt_by_region[$prev_region_id][10] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][11]) && $cnt_by_region[$prev_region_id][11] != 0) ? $cnt_by_region[$prev_region_id][11] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][12]) && $cnt_by_region[$prev_region_id][12] != 0) ? $cnt_by_region[$prev_region_id][12] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][13]) && $cnt_by_region[$prev_region_id][13] != 0) ? $cnt_by_region[$prev_region_id][13] : '' ?></td>
                                <td><?= (isset($cnt_by_region[$prev_region_id][14]) && $cnt_by_region[$prev_region_id][14] != 0) ? $cnt_by_region[$prev_region_id][14] : '' ?></td>
                                <td><?= (isset($itogo_region_shtat_ch) && $itogo_region_shtat_ch != 0) ? $itogo_region_shtat_ch : '' ?></td>
                                <td><?= (isset($itogo_region_vacant_ch) && $itogo_region_vacant_ch != 0) ? $itogo_region_vacant_ch : '' ?></td>
                                <td><?= (isset($itogo_region_br) && $itogo_region_br != 0) ? $itogo_region_br : '' ?></td>
                                <td><?= (isset($itogo_region_trip) && $itogo_region_trip != 0) ? $itogo_region_trip : '' ?></td>
                                <td><?= (isset($itogo_region_holiday) && $itogo_region_holiday != 0) ? $itogo_region_holiday : '' ?></td>
                                <td><?= (isset($itogo_region_ill) && $itogo_region_ill != 0) ? $itogo_region_ill : '' ?></td>
                                <td><?= (isset($itogo_region_other) && $itogo_region_other != 0) ? $itogo_region_other : '' ?></td>
                                <td><?= (isset($itogo_region_shtat) && $itogo_region_shtat != 0) ? $itogo_region_shtat : '' ?></td>
                                <td><?= (isset($itogo_region_vacant) && $itogo_region_vacant != 0) ? $itogo_region_vacant : '' ?></td>
                            </tr>
                            <?php
                        }
                    }

                    ?>


                            <tr class="success">
                                 <td colspan="2">Итого по республике</td>
                                <td><?= (isset($cnt_by_rb[1]) && $cnt_by_rb[1] != 0) ? $cnt_by_rb[1] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[2]) && $cnt_by_rb[2] != 0) ? $cnt_by_rb[2] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[3]) && $cnt_by_rb[3] != 0) ? $cnt_by_rb[3] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[4]) && $cnt_by_rb[4] != 0) ? $cnt_by_rb[4] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[5]) && $cnt_by_rb[5] != 0) ? $cnt_by_rb[5] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[6]) && $cnt_by_rb[6] != 0) ? $cnt_by_rb[6] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[7]) && $cnt_by_rb[7] != 0) ? $cnt_by_rb[7] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[8]) && $cnt_by_rb[8] != 0) ? $cnt_by_rb[8] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[9]) && $cnt_by_rb[9] != 0) ? $cnt_by_rb[9] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[10]) && $cnt_by_rb[10] != 0) ? $cnt_by_rb[10] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[11]) && $cnt_by_rb[11] != 0) ? $cnt_by_rb[11] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[12]) && $cnt_by_rb[12] != 0) ? $cnt_by_rb[12] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[13]) && $cnt_by_rb[13] != 0) ? $cnt_by_rb[13] : '' ?></td>
                                <td><?= (isset($cnt_by_rb[14]) && $cnt_by_rb[14] != 0) ? $cnt_by_rb[14] : '' ?></td>

                                <td><?= (isset($itogo_rb_shtat_ch) && $itogo_rb_shtat_ch != 0) ? $itogo_rb_shtat_ch : '' ?></td>
                                    <td><?= (isset($itogo_rb_vacant_ch) && $itogo_rb_vacant_ch != 0) ? $itogo_rb_vacant_ch : '' ?></td>
                                    <td><?= (isset($itogo_rb_br) && $itogo_rb_br != 0) ? $itogo_rb_br : '' ?></td>
                                    <td><?= (isset($itogo_rb_trip) && $itogo_rb_trip != 0) ? $itogo_rb_trip : '' ?></td>
                                    <td><?= (isset($itogo_rb_holiday) && $itogo_rb_holiday != 0) ? $itogo_rb_holiday : '' ?></td>
                                    <td><?= (isset($itogo_rb_ill) && $itogo_rb_ill != 0) ? $itogo_rb_ill : '' ?></td>
                                    <td><?= (isset($itogo_rb_other) && $itogo_rb_other != 0) ? $itogo_rb_other : '' ?></td>
                                    <td><?= (isset($itogo_rb_shtat) && $itogo_rb_shtat != 0) ? $itogo_rb_shtat : '' ?></td>
                                    <td><?= (isset($itogo_rb_vacant) && $itogo_rb_vacant != 0) ? $itogo_rb_vacant : '' ?></td>
                            </tr>

    </tbody>
    </table>
    </center>
    <!--
        </div>-->


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

