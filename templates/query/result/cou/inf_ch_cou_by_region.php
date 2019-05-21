<?php
//print_r($main['itogo_rb']);
//print_r($head_info);
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
    <table class="table table-condensed   table-bordered tbl_show_inf" style="width: 79% !important" >
        <!--   строка 1 -->
        <thead>
            <tr >
                <th colspan="15">Строевая записка по личному составу</th>
            </tr>
            <!-- строка 2 -->
        <th colspan="3"></th>
        <th colspan="10">Дежурной смены</th>
        <th colspan="2">Подразделения</th>
        <tr>
            <th >Область</th>
            <th >Г(Р)ОЧС</th>
            <th >Подразделение</th>
            <th>Должность</th>
            <th>Ф.И.О.</th>
            <th>кол-во</th>
            <th>по штату<br>в деж.<br>смене</th>
            <th>вакансия<br>в деж.<br>смене</th>
            <th>в<br>боевом<br>расчете</th>
            <th>ком-ка</th>
            <th>отпуск</th>
            <th>больные</th>
            <th>др.<br>причины</th>

            <th>по штату</th>
            <th>вакансия</th>
        </tr>

    </thead>

    <tbody>
    <?php
    /* ---------------все должности и ФИО на этих должностях------------ */

    $itogo_region = 0;
    $itogo_region_shtat_ch = 0;
    $itogo_region_vacant_ch = 0;
    $itogo_region_br = 0;
    $itogo_region_trip = 0;
    $itogo_region_holiday = 0;
    $itogo_region_ill = 0;
    $itogo_region_other = 0;
    $itogo_region_shtat = 0;
    $itogo_region_vacant = 0;

//        if (isset($cnt_main_cou) && !empty($cnt_main_cou)) {
//            foreach ($cnt_main_cou as $k => $cnt) {
//                if ($cnt_main_cou > 0) {
    foreach ($cou_ids as $key => $value) {
        $itogo = 0;
        $id_card = $value['record_id'];
        $name_region = $value['region'];
        $name_locorg = $value['locorg'];
        $name_div = $value['divizion_name'];
        // if ($k == $id_card) {


        if (isset($cnt_main_cou[$id_card]) && $cnt_main_cou[$id_card] > 0) {

            if (isset($main_cou[$id_card]) && !empty($main_cou[$id_card])) {

                foreach ($main_cou[$id_card] as $key1 => $row) {

                    ?>
                        <tr>
                            <td><?= $name_region ?></td>
                            <td><?= $name_locorg ?></td>
                            <td><?= $name_div ?></td>
                            <td class="success"><?= $key1 ?></td>
                            <td class="success">
                    <?php
                    foreach ($row as $r) {
                        if ($r['slug'] == '') {
                            echo $r['fio'] . ' ' . $r['pasp'] . ' ' . $r['locorg_name'] . '<br>';
                        } else {
                            echo $r['fio'] . ' ' . $r['pasp'] . ' ' . $r['locorg_name'] . ' (' . $r['slug'] . ')<br>';
                        }
                    }

                    ?>
                            </td>
                            <td><?= count($row) ?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php
                    $itogo += count($row);

                    ?>
                        <?php
                        //mb_strtolower($row['slug'])
                    }
                }

                ?>
                <tr class="success">
                    <td colspan="5" ><i>ИТОГО</i></td>
                    <td><?= $itogo ?></td>
                    <?php $itogo_region += $itogo; ?>

                    <!--         л/с в смене-->
                    <td>
                        <?php
                        if (isset($shtat[$id_card]['shtat_ch']) && !empty($shtat[$id_card]['shtat_ch'])) {
                            echo $shtat[$id_card]['shtat_ch'];
                            $itogo_region_shtat_ch += $shtat[$id_card]['shtat_ch'];
                        }

                        ?>
                    </td>
                    <td>
                        <?php
                        if (isset($shtat[$id_card]['vacant_ch']) && !empty($shtat[$id_card]['vacant_ch'])) {
                            echo $shtat[$id_card]['vacant_ch'];
                            $itogo_region_vacant_ch += $shtat[$id_card]['vacant_ch'];
                        }

                        ?>
                    </td>

                    <!--        END                 л/с в смене-->


                    <td>
                        <?php
                        if (isset($count_fio_on_car[$id_card]) && !empty($count_fio_on_car[$id_card])) {
                            echo $count_fio_on_car[$id_card];
                            $itogo_region_br += $count_fio_on_car[$id_card];
                        }

                        ?>
                    </td>


                    <!--                     отсутствующие-->
                    <td class="danger">
                        <?php
                        if (isset($absent[$id_card]['trip']) && !empty($absent[$id_card]['trip'])) {
                            echo $absent[$id_card]['trip'];
                            $itogo_region_trip += $absent[$id_card]['trip'];
                        }

                        ?>
                    </td>
                    <td class="danger">
                        <?php
                        if (isset($absent[$id_card]['holiday']) && !empty($absent[$id_card]['holiday'])) {
                            echo $absent[$id_card]['holiday'];
                            $itogo_region_holiday += $absent[$id_card]['holiday'];
                        }

                        ?>
                    </td>
                    <td class="danger">
                        <?php
                        if (isset($absent[$id_card]['ill']) && !empty($absent[$id_card]['ill'])) {
                            echo $absent[$id_card]['ill'];
                            $itogo_region_ill += $absent[$id_card]['ill'];
                        }

                        ?>
                    </td>
                    <td class="danger">
                        <?php
                        if (isset($absent[$id_card]['other']) && !empty($absent[$id_card]['other'])) {
                            echo $absent[$id_card]['other'];
                            $itogo_region_other += $absent[$id_card]['other'];
                        }

                        ?>
                    </td>
                    <!--          END           отсутствующие-->


                    <!--                         л/с подразделения-->
                    <td>
                        <?php
                        if (isset($shtat[$id_card]['shtat']) && !empty($shtat[$id_card]['shtat'])) {
                            echo $shtat[$id_card]['shtat'];
                            $itogo_region_shtat += $shtat[$id_card]['shtat'];
                        }

                        ?>
                    </td>
                    <td>
                        <?php
                        if (isset($shtat[$id_card]['vacant']) && !empty($shtat[$id_card]['vacant'])) {
                            echo $shtat[$id_card]['vacant'];
                            $itogo_region_vacant += $shtat[$id_card]['vacant'];
                        }

                        ?>
                    </td>
                </tr>

                <!--        END                 л/с подразделения-->




                <?php
            }

            ?>


            <?php
        }

?>

                <tr class="warning">
                    <td colspan="5" ><i>ИТОГО по области</i></td>
                    <td><?= (($itogo_region != 0) ? $itogo_region : '') ?></td>
                    <td><?= ($itogo_region_shtat_ch != 0) ? $itogo_region_shtat_ch : '' ?></td>
                    <td><?= ($itogo_region_vacant_ch != 0) ? $itogo_region_vacant_ch : '' ?></td>
                    <td><?= ($itogo_region_br != 0) ? $itogo_region_br : '' ?></td>
                    <td class="danger"><?= ($itogo_region_trip != 0) ? $itogo_region_trip : '' ?></td>
                    <td class="danger"><?= ($itogo_region_holiday != 0) ? $itogo_region_holiday : '' ?></td>
                    <td class="danger"><?= ($itogo_region_ill != 0) ? $itogo_region_ill : '' ?></td>
                    <td class="danger"><?= ($itogo_region_other != 0) ? $itogo_region_other : '' ?></td>
                    <td><?= ($itogo_region_shtat != 0) ? $itogo_region_shtat : '' ?></td>
                    <td><?= ($itogo_region_vacant != 0) ? $itogo_region_vacant : '' ?></td>
                </tr>
<?php



        /* ---------------  КОНЕЦ все должности и ФИО на этих должностях------------ */

        ?>



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

