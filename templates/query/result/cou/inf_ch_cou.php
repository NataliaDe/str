<?php
//print_r($main['itogo_rb']);



if (isset($main) && !empty($main)) {
    ?>
    <p> <a name="result_page"></a></p>
    <br><br><br>
    <center><b>
    <?php
    if (isset($head_info) && !empty($head_info)) {
        foreach ($head_info as $h) {
            $dateduty_head = date('d.m.Y', strtotime($h['dateduty']));
            $name_head = $h['name'];
        }
        echo 'Результат запроса за ' . $dateduty_head . ', ' . $name_head;
    }
    ?>
        </b>
    <br>
    <!--    <div class="table-responsive" id="tbl-query-result">-->
    <table class="table table-condensed   table-bordered tbl_show_inf" style="width: 79% !important" >
        <!--   строка 1 -->
        <thead>
            <tr >
                <th colspan="12">Строевая записка по личному составу</th>
            </tr>
            <!-- строка 2 -->

        <th colspan="10">Дежурной смены</th>
        <th colspan="2">Подразделения</th>
        <tr>
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
    $itogo = 0;
    foreach ($main_cou as $key => $value) {
        if($key != 'Оперативная группа'){
            ?>


            <tr>

                <td class="success"><?= $key ?></td>
                <td class="success">

        <?php
        $itogo+=count($value);
        foreach ($value as $row) {
            if($row['slug'] == ''){
                 echo $row['fio'] . ' ' . $row['pasp'] . ' ' . $row['locorg_name'] . '<br>';
            }
            else{
                echo $row['fio'] . ' ' . $row['pasp'] . ' ' . $row['locorg_name'] . ' (' . $row['slug'] . ')<br>';
            }

            //mb_strtolower($row['slug'])
        }
        //print_r($value);
        ?>

                </td>

                <td class="success"><?= count($value) ?></td>

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
        }
    }

    /* ---------------  КОНЕЦ все должности и ФИО на этих должностях------------ */
    ?>


        <tr>

            <td colspan="2" class="success"><i>ИТОГО</i></td>
            <td class="success"><?= $itogo ?></td>


            <!--         л/с в смене-->
            <td><?= $shtat['shtat_ch'] ?></td>
            <td><?= $shtat['vacant_ch'] ?></td>
            <!--        END                 л/с в смене-->


            <td ><?= $count_fio_on_car ?></td>


            <!--                     отсутствующие-->
            <td class="danger"><?= $absent['trip'] ?></td>
            <td class="danger"><?= $absent['holiday'] ?></td>
            <td class="danger"><?= $absent['ill'] ?></td>
            <td class="danger"><?= $absent['other'] ?></td>
            <!--          END           отсутствующие-->


            <!--                         л/с подразделения-->
            <td><?= $shtat['shtat'] ?></td>
            <td><?= $shtat['vacant'] ?></td>
            <!--        END                 л/с подразделения-->

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

