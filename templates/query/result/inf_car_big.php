<?php
//print_r($main);
?>
<center><b>
        РЕЗУЛЬТАТ запроса <?= ($date_start == 0) ? 'за последнюю заполненную смену' : (' на '.date('d.m.Y', strtotime($_POST['date_start']))) ?><br>
        наименование техники: <?= (!empty($query_name_teh)) ? $query_name_teh : 'все' ?>
        , вид техники: <?= (!empty($query_vid_teh)) ? $query_vid_teh : 'все' ?></b>
</center>
<!--<div class="table-responsive" id="tbl-query-result">-->
<table class="table table-condensed   table-bordered tbl_show_inf" >
    <!--               строка 1 -->
    <thead>
        <tr >
            <th rowspan="2">№</th>
            <th rowspan="2">Область</th>
            <th rowspan="2">Наименование подразделения</th>
            <th colspan="2">Б/р</th>
            <th colspan="2">Резерв</th>
            <th colspan="2">ТО-1</th>

            <th colspan="2">ТО-2</th>
            <th colspan="2">Ремонт</th>
            <th colspan="2">Ком-ка</th>
            <th rowspan="2">Всего</th>

        </tr>
        <!--                 строка 2 -->
        <tr>
            <th>наим</th>
            <th>кол-во</th>
            <th>наим</th>
            <th>кол-во</th>
            <th>наим</th>
            <th>кол-во</th>
            <th>наим</th>
            <th>кол-во</th>

            <th>наим</th>
            <th>кол-во</th>

            <th>наим</th>
            <th>кол-во</th>
        </tr>

    </thead>
    <?php
    $i = 0;
    $last_id_grochs = 0;
    $last_id_region = 0;
    $grochs_br = 0; //итого по ГРОЧС
    $region_br = 0; //итого по области
    $rb_br = 0; //итого по РБ
    $grochs_res = 0; //итого по ГРОЧС
    $region_res = 0; //итого по области
    $rb_res = 0; //итого по РБ
    $grochs_to1 = 0; //итого по ГРОЧС
    $region_to1 = 0; //итого по области
    $rb_to1 = 0; //итого по РБ
    $grochs_to2 = 0; //итого по ГРОЧС
    $region_to2 = 0; //итого по области
    $rb_to2 = 0; //итого по РБ
    $grochs_repair = 0; //итого по ГРОЧС
    $region_repair = 0; //итого по области
    $rb_repair = 0; //итого по РБ

    $grochs_absent = 0; //итого по ГРОЧС
    $region_absent = 0; //итого по области
    $rb_absent = 0; //итого по РБ

    $grochs_vsego = 0; //всего по ГРОЧС
    $region_vsego = 0; //всего по области
    $rb_vsego = 0; //всего по РБ
    ?>
    <tbody>
        <?php
        foreach ($main as $value) {
            $i++;
            $vsego = 0; //всего техники в ПАСЧ
            /* -------------------------------------------------  ИТОГО------------------------------------------------------------ */

            if ($type == 1) {//кроме РОСН/UGZ
                if ($value['id_grochs'] != $last_id_grochs && $last_id_grochs != 0) {//итого по ГРОЧС
                    ?>
                    <tr class="info">
                        <td></td>

                        <td>ИТОГО по Г(Р)ОЧС</td>
                        <td></td>
                        <td></td>
                        <td><?= $grochs_br ?></td>
                        <td></td>
                        <td><?= $grochs_res ?></td>
                        <td></td>
                        <td><?= $grochs_to1 ?></td>
                        <td></td>
                        <td><?= $grochs_to2 ?></td>
                        <td></td>
                        <td><?= $grochs_repair ?></td>
                        <td></td>
                        <td><?= $grochs_absent ?></td>
                        <td><?= $grochs_vsego-$grochs_absent ?></td>
                    </tr>
                    <?php
                    $grochs_br = 0; //обнулить
                    $grochs_res = 0; //обнулить
                    $grochs_to1 = 0; //обнулить
                    $grochs_to2 = 0; //обнулить
                    $grochs_repair = 0; //обнулить
                    $grochs_absent = 0; //обнулить
                    $grochs_vsego = 0;
                }
                if ($value['region_id'] != $last_id_region && $last_id_region != 0) {//итого по region
                    ?>
<!--                    <tr class="warning">
                        <td></td>

                        <td>ИТОГО по области</td>
                        <td></td>
                        <td></td>
                        <td><?= $region_br ?></td>
                        <td></td>
                        <td><?= $region_res ?></td>
                        <td></td>
                        <td><?= $region_to1 ?></td>
                        <td></td>
                        <td><?= $region_to2 ?></td>
                        <td></td>
                        <td><?= $region_repair ?></td>
                        <td></td>
                        <td><?= $region_absent ?></td>
                        <td><?= $region_vsego-$region_absent ?></td>
                    </tr>   -->
                    <?php
                    $region_br = 0; //обнулить
                    $region_res = 0;
                    $region_to1 = 0;
                    $region_to2 = 0;
                    $region_repair = 0;
                    $region_absent = 0;
                    $region_vsego = 0;
                }
            }
            ?>
            <!--  ------------------------------------------- END ИТОГО ------------------------------------------------------------------------------>
            <?php

            if (!empty($value['region_name'])) {
                ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><?= $value['region_name'] ?> </td>
                    <td><?= $value['name'] ?>, смена <?= $value['ch'] ?></td>

                    <!-----------------   боевой расчет ---------------------->
                    <td>
                        <?php
                        $j = 0;
                        foreach ($value['br_name'] as $br_n) {
//                            $j++;
//                            if ($j % 2 != 0)
//                                echo $br_n . ', ';
//                            else {
//
//                                echo $br_n . ', ' . "<br>";
//                            }
                            echo $br_n  . "<br>";
                        }
                        //из др.ПАСЧ
                        foreach ($value['additional_car']['br_name'] as $add_br_n) {
                            echo "<i><b>" . $add_br_n . "</b></i><br>";
                        }
                        ?>
                    </td>
                    <td><?= $value['br_count'] + $value['additional_car']['br_count'] ?></td>

                    <!-------------------------  резерв ---------------------->
                    <td>
                        <?php
                        $j = 0;
                        foreach ($value['res_name'] as $res_n) {
//                            $j++;
//                            if ($j % 2 != 0)
//                                echo $res_n . ', ';
//                            else {
//
//                                echo $res_n . ', ' . "<br>";
//                            }
                             echo $res_n ."<br>";
                        }
                        //из др.ПАСЧ
                        foreach ($value['additional_car']['res_name'] as $add_res_n) {
                            echo "<i><b>" . $add_res_n . "</b></i><br>";
                        }
                        ?>
                    </td>
                    <td><?= $value['res_count'] + $value['additional_car']['res_count'] ?></td>

                    <!------------------   ТО-1  --------------------------->
                    <td>
                        <?php
                        foreach ($value['to1_name'] as $to1_n) {
                            echo $to1_n . "<br>";
                        }
                        //из др.ПАСЧ
                        foreach ($value['additional_car']['to1_name'] as $add_to1_n) {
                            echo "<i><b>" . $add_to1_n . "</b></i><br>";
                        }
                        ?>
                    </td>
                    <td><?= $value['to1_count'] + $value['additional_car']['to1_count'] ?></td>

                    <!-------------------   ТО-2   ------------------------->
                    <td>
                        <?php
                        foreach ($value['to2_name'] as $to2_n) {
                            echo $to2_n . "<br>";
                        }
                        //из др.ПАСЧ
                        foreach ($value['additional_car']['to2_name'] as $add_to2_n) {
                            echo "<i><b>" . $add_to2_n . "</b></i><br>";
                        }
                        ?>
                    </td>
                    <td><?= $value['to2_count'] + $value['additional_car']['to2_count'] ?></td>

                    <!-----------------   ремонт   ------------------------->
                    <td>
                        <?php
                        foreach ($value['repair_name'] as $repair_n) {
                            echo $repair_n . "<br>";
                        }
                        //из др.ПАСЧ
                        foreach ($value['additional_car']['repair_name'] as $add_repair_n) {
                            echo "<i><b>" . $add_repair_n . "</b></i><br>";
                        }
                        ?>
                    </td>
                    <td><?= $value['repair_count'] + $value['additional_car']['repair_count'] ?></td>

                    <!------------------    командировка    ---------------------->
                    <td>
                        <?php
                        foreach ($value['absent_car']['absent_name'] as $abs_n) {
                            echo $abs_n . "<br>";
                        }
                        ?>
                    </td>
                    <td><?= $value['absent_car']['absent_count'] ?></td>
                    <?php
                    //б.р+рез+то+ремонт+команд
                    $vsego = $value['br_count'] + $value['additional_car']['br_count'] + $value['res_count'] + $value['additional_car']['res_count'] + $value['to1_count'] + $value['additional_car']['to1_count'] +
                            $value['to2_count'] + $value['additional_car']['to2_count'] + $value['repair_count'] + $value['additional_car']['repair_count'];
                    ?>
                    <td class="success"><?= $vsego ?></td>
                </tr>
                <?php
            }
            ?>


            <?php
            $last_id_grochs = $value['id_grochs'];
//подсчет по ГРОЧС
            $grochs_br+=$value['br_count'] + $value['additional_car']['br_count'];
            $grochs_res+=$value['res_count'] + $value['additional_car']['res_count'];
            $grochs_to1+=$value['to1_count'] + $value['additional_car']['to1_count'];
            $grochs_to2+=$value['to2_count'] + $value['additional_car']['to2_count'];
            $grochs_repair+=$value['repair_count'] + $value['additional_car']['repair_count'];
            $grochs_absent+=$value['absent_car']['absent_count'];

            //б.р+рез+то+ремонт+команд
            $grochs_vsego+=$value['br_count'] + $value['additional_car']['br_count'] + $value['res_count'] + $value['additional_car']['res_count'] + $value['to1_count'] + $value['additional_car']['to1_count'] +
                    $value['to2_count'] + $value['additional_car']['to2_count'] + $value['repair_count'] + $value['additional_car']['repair_count'] +
                    $value['absent_car']['absent_count'];

            $last_id_region = $value['region_id'];
            //подсчет по области
            $region_br+=$value['br_count'] + $value['additional_car']['br_count'];
            $region_res+=$value['res_count'] + $value['additional_car']['res_count'];
            $region_to1+=$value['to1_count'] + $value['additional_car']['to1_count'];
            $region_to2+=$value['to2_count'] + $value['additional_car']['to2_count'];
            $region_repair+=$value['repair_count'] + $value['additional_car']['repair_count'];
            $region_absent+=$value['absent_car']['absent_count'];
            $region_vsego+=$value['br_count'] + $value['additional_car']['br_count'] + $value['res_count'] + $value['additional_car']['res_count'] + $value['to1_count'] + $value['additional_car']['to1_count'] +
                    $value['to2_count'] + $value['additional_car']['to2_count'] + $value['repair_count'] + $value['additional_car']['repair_count'] +
                    $value['absent_car']['absent_count'];
//подсчет по рБ
            $rb_br+=$value['br_count'] + $value['additional_car']['br_count'];
            $rb_res+=$value['res_count'] + $value['additional_car']['res_count'];
            $rb_to1+=$value['to1_count'] + $value['additional_car']['to1_count'];
            $rb_to2+=$value['to2_count'] + $value['additional_car']['to2_count'];
            $rb_repair+=$value['repair_count'] + $value['additional_car']['repair_count'];
            $rb_absent+=$value['absent_car']['absent_count'];
            $rb_vsego+=$value['br_count'] + $value['additional_car']['br_count'] + $value['res_count'] + $value['additional_car']['res_count'] + $value['to1_count'] + $value['additional_car']['to1_count'] +
                    $value['to2_count'] + $value['additional_car']['to2_count'] + $value['repair_count'] + $value['additional_car']['repair_count'] +
                    $value['absent_car']['absent_count'];
        }

        /* -------------------------------------------------  ИТОГО------------------------------------------------------------ */
        if ($type == 1) {//кроме РОСН/UGZ
            /* ++++ Итого по ГРОЧС ++++ */
            if ($last_id_grochs && $last_id_grochs != 0) {
                ?>
                <tr class="info">
                    <td></td>

                    <td>ИТОГО по Г(Р)ОЧС</td>
                    <td></td>
                    <td></td>
                    <td><?= $grochs_br ?></td>
                    <td></td>
                    <td><?= $grochs_res ?></td>
                    <td></td>
                    <td><?= $grochs_to1 ?></td>
                    <td></td>
                    <td><?= $grochs_to2 ?></td>
                    <td></td>
                    <td><?= $grochs_repair ?></td>
                    <td></td>
                    <td><?= $grochs_absent ?></td>
                    <td><?= $grochs_vsego-$grochs_absent ?></td>
                </tr>
        <?php
        $grochs_br = 0; //обнулить
        $grochs_res = 0; //обнулить
        $grochs_to1 = 0; //обнулить
        $grochs_to2 = 0; //обнулить
        $grochs_repair = 0; //обнулить
        $grochs_absent = 0; //обнулить
        $grochs_vsego = 0; //обнулить
    }
    /* ++++ Итого по области ++++ */
    if ($last_id_region && $last_id_region != 0) {
        ?>
<!--
                <tr class="warning">
                    <td></td>
                    <td>ИТОГО по области</td>
                    <td></td>
                    <td></td>
                    <td><?= $region_br ?></td>
                    <td></td>
                    <td><?= $region_res ?></td>
                    <td></td>
                    <td><?= $region_to1 ?></td>
                    <td></td>
                    <td><?= $region_to2 ?></td>
                    <td></td>
                    <td><?= $region_repair ?></td>
                    <td></td>
                    <td><?= $region_absent ?></td>
                    <td><?= $region_vsego-$region_absent  ?></td>
                </tr> -->
        <?php
        $region_br = 0; //обнулить
        $region_res = 0;
        $region_to1 = 0;
        $region_to2 = 0;
        $region_repair = 0;
        $region_absent = 0;
        $region_vsego = 0;
    }
}

if($type != 1){//кроме УМЧСы
    ?>
    <tr class="success">
            <td></td>
            <td>ИТОГО</td>
            <td></td>
            <td></td>
            <td><?= $rb_br ?></td>
            <td></td>
            <td><?= $rb_res ?></td>
            <td></td>
            <td><?= $rb_to1 ?></td>
            <td></td>
            <td><?= $rb_to2 ?></td>
            <td></td>
            <td><?= $rb_repair ?></td>
            <td></td>
            <td><?= $rb_absent ?></td>
            <td><?= $rb_vsego-$rb_absent ?></td>
        </tr>
<?php
}
?>
        <!---------------------------------------- END ИТОГО--------------------------------------------------------------->


    </tbody>
</table>


<!--</div>-->