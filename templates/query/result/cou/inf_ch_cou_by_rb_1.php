<style>
    .main-cou-request tr th {
        font-size: 11px;
    }
</style>

<?php
//echo '2';
//print_r($region_itogo);
//print_r($countBrRb);


if (isset($region_itogo) && !empty($region_itogo)) {

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
        <th ></th>
        <th colspan="<?= count($posduty_list) ?>">Должность</th>
        <th colspan="7">Дежурной смены</th>
        <th colspan="2">Подразделения</th>
        <tr>
            <th >Область</th>

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
                           foreach ($region_itogo as $id_region => $value) {
                               ?>
        <tr>
            <td><?= $value['info']['region_name'] ?></td>

            <td><?= (isset($value['position'][1]['cnt_pos']) && $value['position'][1]['cnt_pos'] != 0) ? $value['position'][1]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][2]['cnt_pos']) && $value['position'][2]['cnt_pos'] != 0) ? $value['position'][2]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][3]['cnt_pos']) && $value['position'][3]['cnt_pos'] != 0) ? $value['position'][3]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][4]['cnt_pos']) && $value['position'][4]['cnt_pos'] != 0) ? $value['position'][4]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][5]['cnt_pos']) && $value['position'][5]['cnt_pos'] != 0) ? $value['position'][5]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][6]['cnt_pos']) && $value['position'][6]['cnt_pos'] != 0) ? $value['position'][6]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][7]['cnt_pos']) && $value['position'][7]['cnt_pos'] != 0) ? $value['position'][7]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][8]['cnt_pos']) && $value['position'][8]['cnt_pos'] != 0) ? $value['position'][8]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][9]['cnt_pos']) && $value['position'][9]['cnt_pos'] != 0) ? $value['position'][9]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][10]['cnt_pos']) && $value['position'][10]['cnt_pos'] != 0) ? $value['position'][10]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][11]['cnt_pos']) && $value['position'][11]['cnt_pos'] != 0) ? $value['position'][11]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][12]['cnt_pos']) && $value['position'][12]['cnt_pos'] != 0) ? $value['position'][12]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][13]['cnt_pos']) && $value['position'][13]['cnt_pos'] != 0) ? $value['position'][13]['cnt_pos'] : '' ?></td>
            <td><?= (isset($value['position'][14]['cnt_pos']) && $value['position'][14]['cnt_pos'] != 0) ? $value['position'][14]['cnt_pos'] : '' ?></td>
            <td><?= (isset($shtat[$id_region]['shtat_ch']) && $shtat[$id_region]['shtat_ch'] != 0) ? $shtat[$id_region]['shtat_ch'] : '' ?></td>
            <td><?= (isset($shtat[$id_region]['vacant_ch']) && $shtat[$id_region]['vacant_ch'] != 0) ? $shtat[$id_region]['vacant_ch'] : '' ?></td>
            <td><?= (isset($count_fio_on_car[$id_region]['cnt_on_car']) && $count_fio_on_car[$id_region]['cnt_on_car'] != 0) ? $count_fio_on_car[$id_region]['cnt_on_car'] : '' ?></td>
            <td class="danger"><?= (isset($absent[$id_region]['trip']) && $absent[$id_region]['trip'] != 0) ? $absent[$id_region]['trip'] : '' ?></td>
            <td class="danger"><?= (isset($absent[$id_region]['holiday']) && $absent[$id_region]['holiday'] != 0) ? $absent[$id_region]['holiday'] : '' ?></td>
            <td class="danger"><?= (isset($absent[$id_region]['ill']) && $absent[$id_region]['ill'] != 0) ? $absent[$id_region]['ill'] : '' ?></td>
            <td class="danger"><?= (isset($absent[$id_region]['other']) && $absent[$id_region]['other'] != 0) ? $absent[$id_region]['other'] : '' ?></td>
            <td><?= (isset($shtat[$id_region]['shtat']) && $shtat[$id_region]['shtat'] != 0) ? $shtat[$id_region]['shtat'] : '' ?></td>
            <td><?= (isset($shtat[$id_region]['vacant']) && $shtat[$id_region]['vacant'] != 0) ? $shtat[$id_region]['vacant'] : '' ?></td>
        </tr>
        <?php
                           }

            $cp = array(8, 9, 12);
            foreach ($cp as $id_organ) {
                $id_region = $id_organ;

                ?>
        <tr class="warning">
            <td><?= ($id_organ == 8) ? 'РОСН' : (($id_organ == 9) ? 'УГЗ' : (($id_organ == 12) ? 'Авиация' : '')) ?></td>


            <td><?= (isset($region_rosn_itogo[$id_region]['position'][1]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][1]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][1]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][2]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][2]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][2]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][3]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][3]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][3]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][4]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][4]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][4]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][5]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][5]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][5]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][6]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][6]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][6]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][7]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][7]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][7]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][8]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][8]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][8]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][9]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][9]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][9]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][10]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][10]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][10]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][11]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][11]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][11]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][12]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][12]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][12]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][13]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][13]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][13]['cnt_pos'] : '' ?></td>
            <td><?= (isset($region_rosn_itogo[$id_region]['position'][14]['cnt_pos']) && $region_rosn_itogo[$id_region]['position'][14]['cnt_pos'] != 0) ? $region_rosn_itogo[$id_region]['position'][14]['cnt_pos'] : '' ?></td>


                    <td><?= (isset($shtat[$id_region]['shtat_ch']) && $shtat[$id_region]['shtat_ch'] != 0) ? $shtat[$id_region]['shtat_ch'] : '' ?></td>
                    <td><?= (isset($shtat[$id_region]['vacant_ch']) && $shtat[$id_region]['vacant_ch'] != 0) ? $shtat[$id_region]['vacant_ch'] : '' ?></td>
                    <td><?= (isset($count_fio_on_car[$id_region]['cnt_on_car']) && $count_fio_on_car[$id_region]['cnt_on_car'] != 0) ? $count_fio_on_car[$id_region]['cnt_on_car'] : '' ?></td>
                    <td class="danger"><?= (isset($absent[$id_region]['trip']) && $absent[$id_region]['trip'] != 0) ? $absent[$id_region]['trip'] : '' ?></td>
                    <td class="danger"><?= (isset($absent[$id_region]['holiday']) && $absent[$id_region]['holiday'] != 0) ? $absent[$id_region]['holiday'] : '' ?></td>
                    <td class="danger"><?= (isset($absent[$id_region]['ill']) && $absent[$id_region]['ill'] != 0) ? $absent[$id_region]['ill'] : '' ?></td>
                    <td class="danger"><?= (isset($absent[$id_region]['other']) && $absent[$id_region]['other'] != 0) ? $absent[$id_region]['other'] : '' ?></td>
                    <td><?= (isset($shtat[$id_region]['shtat']) && $shtat[$id_region]['shtat'] != 0) ? $shtat[$id_region]['shtat'] : '' ?></td>
                    <td><?= (isset($shtat[$id_region]['vacant']) && $shtat[$id_region]['vacant'] != 0) ? $shtat[$id_region]['vacant'] : '' ?></td>
                <tr>
        <?php
    }

    ?>
        <tr class="success">
            <td>Итого по республике</td>

            <td><?= (isset($rb_itogo[1]['cnt_pos']) && $rb_itogo[1]['cnt_pos'] != 0) ? $rb_itogo[1]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[2]['cnt_pos']) && $rb_itogo[2]['cnt_pos'] != 0) ? $rb_itogo[2]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[3]['cnt_pos']) && $rb_itogo[3]['cnt_pos'] != 0) ? $rb_itogo[3]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[4]['cnt_pos']) && $rb_itogo[4]['cnt_pos'] != 0) ? $rb_itogo[4]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[5]['cnt_pos']) && $rb_itogo[5]['cnt_pos'] != 0) ? $rb_itogo[5]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[6]['cnt_pos']) && $rb_itogo[6]['cnt_pos'] != 0) ? $rb_itogo[6]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[7]['cnt_pos']) && $rb_itogo[7]['cnt_pos'] != 0) ? $rb_itogo[7]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[8]['cnt_pos']) && $rb_itogo[8]['cnt_pos'] != 0) ? $rb_itogo[8]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[9]['cnt_pos']) && $rb_itogo[9]['cnt_pos'] != 0) ? $rb_itogo[9]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[10]['cnt_pos']) && $rb_itogo[10]['cnt_pos'] != 0) ? $rb_itogo[10]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[11]['cnt_pos']) && $rb_itogo[11]['cnt_pos'] != 0) ? $rb_itogo[11]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[12]['cnt_pos']) && $rb_itogo[12]['cnt_pos'] != 0) ? $rb_itogo[12]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[13]['cnt_pos']) && $rb_itogo[13]['cnt_pos'] != 0) ? $rb_itogo[13]['cnt_pos'] : '' ?></td>
            <td><?= (isset($rb_itogo[14]['cnt_pos']) && $rb_itogo[14]['cnt_pos'] != 0) ? $rb_itogo[14]['cnt_pos'] : '' ?></td>

            <td><?= (isset($shtatRb['shtat_ch']) && $shtatRb['shtat_ch'] != 0) ? $shtatRb['shtat_ch'] : '' ?></td>
            <td><?= (isset($shtatRb['vacant_ch']) && $shtatRb['vacant_ch'] != 0) ? $shtatRb['vacant_ch'] : '' ?></td>
            <td><?= (isset($countBrRb['cnt_on_car']) && $countBrRb['cnt_on_car'] != 0) ? $countBrRb['cnt_on_car'] : '' ?></td>
            <td class="danger"><?= (isset($absentRb['trip']) && $absentRb['trip'] != 0) ? $absentRb['trip'] : '' ?></td>
            <td class="danger"><?= (isset($absentRb['holiday']) && $absentRb['holiday'] != 0) ? $absentRb['holiday'] : '' ?></td>
            <td class="danger"><?= (isset($absentRb['ill']) && $absentRb['ill'] != 0) ? $absentRb['ill'] : '' ?></td>
            <td class="danger"><?= (isset($absentRb['other']) && $absentRb['other'] != 0) ? $absentRb['other'] : '' ?></td>
            <td><?= (isset($shtatRb['shtat']) && $shtatRb['shtat'] != 0) ? $shtatRb['shtat'] : '' ?></td>
            <td><?= (isset($shtatRb['vacant']) && $shtatRb['vacant'] != 0) ? $shtatRb['vacant'] : '' ?></td>


        </tr>
    </tbody>
    </table>
    </center>


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

