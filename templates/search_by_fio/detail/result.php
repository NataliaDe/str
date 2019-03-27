<style>
    .features-table
    {
        width: 100%;
        margin: 0 auto;
        border-collapse: separate;
        border-spacing: 0;
        border: 0;
        text-shadow: 0 1px 0 #fff;
        color: #2a2a2a;
        background: #fafafa;
        background-image: -moz-linear-gradient(top, #fff, #eaeaea, #fff); /* Firefox 3.6 */
        background-image: -webkit-gradient(linear,center bottom,center top,from(#fff),color-stop(0.5, #eaeaea),to(#fff));
        margin-top:20px;
        margin-bottom:20px;
    }

    .features-table td
    {
        height: 50px;
        padding: 0 20px;
        border-bottom: 1px solid #cdcdcd;
        box-shadow: 0 1px 0 white;
        -moz-box-shadow: 0 1px 0 white;
        -webkit-box-shadow: 0 1px 0 white;
        text-align: center;
        vertical-align: middle;
        display: table-cell;
    }

    .features-table tbody td
    {
        text-align: center;
        width: 150px;
    }


    .features-table td.grey
    {
        background: #efefef;
        background: rgba(144,144,144,0.15);
        border-right: 1px solid white;
    }

    .features-table td.green
    {
        background: #e7f3d4;
        background: rgba(184,243,85,0.3);
    }

    .features-table td:nowrap
    {
        white-space: nowrap;
    }

    .features-table thead td
    {
        font-size: 120%;
        font-weight: bold;
        -moz-border-radius-topright: 10px;
        -moz-border-radius-topleft: 10px;
        border-top-right-radius: 10px;
        border-top-left-radius: 10px;
        border-top: 1px solid #eaeaea;
    }

    .features-table tfoot td
    {
        font-size: 120%;
        font-weight: bold;
        -moz-border-radius-bottomright: 10px;
        -moz-border-radius-bottomleft: 10px;
        border-bottom-right-radius: 10px;
        border-bottom-left-radius: 10px;
        border-bottom: 1px solid #dadada;
    }
</style>
<?php
/* -------------------------------  fio ------------------------------- */
if (isset($result) && !empty($result)) {

    foreach ($result as $row) {

        ?>
        <center>
            <span style="    font-size: 15px;  font-weight: 600;">  <?= $row['fio'] ?> (<?= mb_strtolower($row['rank']) ?>), <?= mb_strtolower($row['position']) ?></span>
            <br>
            <?php
            switch ($row['id_region']) {
                case 1: $name_oblast = 'Брестская область';
                    break;
                case 2: $name_oblast = 'Витебская область';
                    break;
                case 3: $name_oblast = 'г. Минск';
                    break;
                case 4: $name_oblast = 'Гомельская область';
                    break;
                case 5: $name_oblast = 'Гродненская область';
                    break;
                case 6: $name_oblast = 'Минская область';
                    break;
                case 7: $name_oblast = 'Могилевская область';
                    break;
                default : $name_oblast = 'область';
            }

            ?>

            <i class="fa fa-home fa-lg" aria-hidden="true"></i>&nbsp;
            <span style="font-size: 14px;">
                <?php
                echo (($row['ch'] == 0) ? 'ежедневник, ' : ('  работник ' . $row['ch'] . ' смены, ' )) . $row['locorg_name'] . ', ' . $row['divizion'] . ', ' . $name_oblast;

                ?>
            </span>
            <hr style="width: 15%;">
        </center>
        <?php
    }
}


/* fio */
//if (isset($result) && !empty($result)) {

if (isset($date) && !empty($date)) {

    ?>
    <center>
        <span style="  font-size: 14px;  color: darkblue;font-weight: 600;"><i class="fa fa-calendar-check-o" aria-hidden="true"></i>&nbsp;&nbsp;Информация за <?= $date ?></span>
    </center>
    <?php
}

?>


<br>
<?php
/* ------------------------------- ill ------------------------------- */
if (isset($ill) && !empty($ill)) {
    //print_r($ill);

    ?>
    <table class="features-table">
        <thead>
            <tr>
                <td><i class="fa fa-bed fa-lg" aria-hidden="true" style="color:red"></i></td>
                <td class="green">Дата открытия</td>
                <td class="green" nowrap>Дата закрытия</td>
                <td class="grey">Вид травмы</td>
                <td class="grey">Предварительный диагноз</td>
                <td class="grey">Создатель</td>
            </tr>
        </thead>

    <!--<tfoot>
            <tr>
                    <td nowrap>Итого по размерам</td>
                    <td class="grey">Мелкие</td>
                    <td class="grey">Средние</td>
                    <td class="green">Крупные</td>
            </tr>
    </tfoot>-->

        <tbody>
            <tr>
    <?php
    foreach ($ill as $ill_row) {

        ?>
                    <td nowrap><a href="/str/v1/card/<?= $ill_row['id_card'] ?>/ch/<?= $ill_row['ch'] ?>/ill" data-toggle="tooltip" data-placement="left" title="Перейти в строевую записку" target="_blank">Больничный</a></td>
                    <td class="green"><img alt="check" src="/str/templates/search_by_fio/detail/images/check.png" height="16" width="16">&nbsp;
                        <?= $ill_row['date1'] ?></td>
                    <td class="green">
        <?php
        echo (empty($ill_row['date2'])) ? '<img alt="check" src="/str/templates/search_by_fio/detail/images/cross.png" height="16" width="16">' : $ill_row['date2'];

        ?>
                    </td>
                    <td class="grey"><?= $ill_row['maim_name'] ?></td>
                    <td class="grey"><?= $ill_row['diagnosis'] ?></td>
                    <td class="grey"><?= $ill_row['u_name'] ?><br><?= $ill_row['date_insert'] ?></td>
        <?php
    }

    ?>

            </tr>


        </tbody>
    </table>
    <?php
}

/* ------------------------------- holiday ------------------------------- */
if (isset($holiday) && !empty($holiday)) {
    // print_r($holiday);

    ?>
    <table class="features-table">
        <thead>
            <tr>
                <td><i class="fa fa-bicycle fa-lg" aria-hidden="true" style="color:red"></i></td>
                <td class="green">Дата начала</td>
                <td class="green" nowrap>Дата окончания</td>
                <td class="grey">№ Приказа, дата</td>
                <td class="grey">Создатель</td>
            </tr>
        </thead>

        <tbody>
            <tr>
                    <?php
                    foreach ($holiday as $ill_row) {

                        ?>
                    <td nowrap><a href="/str/v1/card/<?= $ill_row['id_card'] ?>/ch/<?= $ill_row['ch'] ?>/holiday" data-toggle="tooltip" data-placement="left" title="Перейти в строевую записку" target="_blank">Отпуск</a></td>
                    <td class="green"><img alt="check" src="/str/templates/search_by_fio/detail/images/check.png" height="16" width="16">&nbsp;
        <?= $ill_row['date1'] ?></td>
                    <td class="green">
                    <?php
                    echo (empty($ill_row['date2'])) ? '<img alt="check" src="/str/templates/search_by_fio/detail/images/cross.png" height="16" width="16">' : ('<img alt="check" src="/str/templates/search_by_fio/detail/images/check.png" height="16" width="16">&nbsp' . $ill_row['date2']);

                    ?>
                    </td>
                    <td class="grey"><?= $ill_row['prikaz'] ?></td>
                    <td class="grey"><?= $ill_row['u_name'] ?><br><?= $ill_row['date_insert'] ?></td>
        <?php
    }

    ?>

            </tr>


        </tbody>
    </table>
    <?php
}


/* ------------------------------- trip ------------------------------- */
if (isset($trip) && !empty($trip)) {
    // print_r($holiday);

    ?>
    <table class="features-table">
        <thead>
            <tr>
                <td><i class="fa fa-suitcase fa-lg" aria-hidden="true" style="color:red"></i></td>
                <td class="green">Дата начала</td>
                <td class="green" nowrap>Дата окончания</td>
                <td class="grey">Место и цель<br>командирования</td>
                <td class="grey">Основание командирования,<br>дата</td>
                <td class="grey">Примечание,<br>вид</td>
                <td class="grey">Создатель</td>
            </tr>
        </thead>

        <tbody>
            <tr>
                    <?php
                    foreach ($trip as $trip_row) {

                        ?>
                    <td nowrap><a href="/str/v1/card/<?= $trip_row['id_card'] ?>/ch/<?= $trip_row['ch'] ?>/trip" data-toggle="tooltip" data-placement="left" title="Перейти в строевую записку" target="_blank">Командировка</a><br>
        <?php echo ($trip_row['is_cosmr'] == 1) ? ('<br><img alt="check" src="/str/templates/search_by_fio/detail/images/check.png" height="16" width="16">&nbsp' . 'согласовано с ЦОСМР') : ''; ?>
                    </td>
                    <td class="green"><img alt="check" src="/str/templates/search_by_fio/detail/images/check.png" height="16" width="16">&nbsp;
                        <?= $trip_row['date1'] ?></td>
                    <td class="green">
                    <?php
                    echo (empty($trip_row['date2'])) ? '<img alt="check" src="/str/templates/search_by_fio/detail/images/cross.png" height="16" width="16">' : ('<img alt="check" src="/str/templates/search_by_fio/detail/images/check.png" height="16" width="16">&nbsp' . $trip_row['date2']);

                    ?>
                    </td>
                    <td class="grey"><?= $trip_row['place'] ?></td>
                    <td class="grey"><?= $trip_row['prikaz'] ?></td>
                    <td class="grey"><?= $trip_row['note'] ?><br>
        <?= $trip_row['type_trip'] ?>
                    </td>
                    <td class="grey"><?= $trip_row['u_name'] ?><br><?= $trip_row['date_insert'] ?></td>
        <?php
    }

    ?>

            </tr>


        </tbody>
    </table>
    <?php
}


/* ------------------------------- other ------------------------------- */
if (isset($other) && !empty($other)) {
    // print_r($other);

    ?>
    <table class="features-table">
        <thead>
            <tr>
                <td rowspan="2"><i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true" style="color:red"></i></td>
                <td colspan="2" class="green">Дата отсутствия</td>
                <td rowspan="2" class="grey">Причина</td>
                <td rowspan="2" class="grey">Примечание</td>
                <td rowspan="2"  class="grey">Создатель</td>
            </tr>

            <tr>

                <td class="green">с</td>
                <td class="green" nowrap>по</td>

            </tr>
        </thead>

        <tbody>

            <tr>
    <?php
    foreach ($other as $other_row) {

        ?>
                    <td nowrap><a href="/str/v1/card/<?= $other_row['id_card'] ?>/ch/<?= $other_row['ch'] ?>/other" data-toggle="tooltip" data-placement="left" title="Перейти в строевую записку" target="_blank">Другие причины</a></td>
                    <td class="green"><img alt="check" src="/str/templates/search_by_fio/detail/images/check.png" height="16" width="16">&nbsp;
        <?= $other_row['date1'] ?></td>
                    <td class="green">
        <?php
        echo (empty($other_row['date2'])) ? '<img alt="check" src="/str/templates/search_by_fio/detail/images/cross.png" height="16" width="16">' : ('<img alt="check" src="/str/templates/search_by_fio/detail/images/check.png" height="16" width="16">&nbsp' . $other_row['date2']);

        ?>
                    </td>
                    <td class="grey"><?= $other_row['reason'] ?></td>
                    <td class="grey"><?= $other_row['note'] ?></td>
                    <td class="grey"><?= $other_row['u_name'] ?><br><?= $other_row['date_insert'] ?></td>
        <?php
    }

    ?>

            </tr>

        </tbody>
    </table>
    <br>
    <?php
}

/* ------------------------------- everyday ------------------------------- */
if (isset($everyday) && !empty($everyday)) {
    //print_r($everyday);

    foreach ($everyday as $value) {
        switch ($value['id_region']) {
            case 1: $name_oblast = 'Брестская область';
                break;
            case 2: $name_oblast = 'Витебская область';
                break;
            case 3: $name_oblast = 'г. Минск';
                break;
            case 4: $name_oblast = 'Гомельская область';
                break;
            case 5: $name_oblast = 'Гродненская область';
                break;
            case 6: $name_oblast = 'Минская область';
                break;
            case 7: $name_oblast = 'Могилевская область';
                break;
            default : $name_oblast = 'область';
        }

        $pasp = $value['ch'] . ' смену, ' . $value['locorg_name'] . ', ' . $value['divizion'] . ', ' . $name_oblast;
    }

    ?>

    <span style="    font-size: 15px;  ">
        <i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"  style="color:blue"></i>&nbsp;&nbsp;
        Заступил <span style="color: blue;  ">ежедневником</span>
                    <?php
            if ($value['ch'] == 2)
                echo 'во';
            else {
                echo 'в';
            }
            ?>
        <a href="/str/v1/card/<?= $value['id_card'] ?>/ch/<?= $value['ch'] ?>/main" data-toggle="tooltip" data-placement="left" title="Перейти в строевую записку" target="_blank"><?= $pasp ?></a>
    </span>

    <?php
}


/* ------------------------------- reserve fio - from other pasp ------------------------------- */
if (isset($reserve_fio) && !empty($reserve_fio)) {
    //print_r($reserve_fio);
    echo '<br>';
    foreach ($reserve_fio as $value) {
        switch ($value['id_region']) {
            case 1: $name_oblast = 'Брестская область';
                break;
            case 2: $name_oblast = 'Витебская область';
                break;
            case 3: $name_oblast = 'г. Минск';
                break;
            case 4: $name_oblast = 'Гомельская область';
                break;
            case 5: $name_oblast = 'Гродненская область';
                break;
            case 6: $name_oblast = 'Минская область';
                break;
            case 7: $name_oblast = 'Могилевская область';
                break;
            default : $name_oblast = 'область';
        }

        $pasp = $value['ch'] . ' смену, ' . $value['locorg_name'] . ', ' . $value['divizion'] . ', ' . $name_oblast;
    }

    ?>

    <span style="    font-size: 15px;  ">
        <i class="fa fa-calendar-check-o fa-lg" aria-hidden="true"  style="color:blue"></i>&nbsp;&nbsp;
        Заступил <span style="color: blue;  ">из другого подразделения </span>
            <?php
            if ($value['ch'] == 2)
                echo 'во';
            else {
                echo 'в';
            }
            ?>
        <a href="/str/v1/card/<?= $value['id_card'] ?>/ch/<?= $value['ch'] ?>/main" data-toggle="tooltip" data-placement="left" title="Перейти в строевую записку" target="_blank"><?= $pasp ?></a>
    </span>

    <?php
}


/* -------------------------------   worker is head of ch in main table  ------------------------------- */
if (isset($main) && !empty($main)) {
    //print_r($br);
    echo '<br><br>';

     foreach ($main as $value) {
        switch ($value['id_region']) {
            case 1: $name_oblast = 'Брестская область';
                break;
            case 2: $name_oblast = 'Витебская область';
                break;
            case 3: $name_oblast = 'г. Минск';
                break;
            case 4: $name_oblast = 'Гомельская область';
                break;
            case 5: $name_oblast = 'Гродненская область';
                break;
            case 6: $name_oblast = 'Минская область';
                break;
            case 7: $name_oblast = 'Могилевская область';
                break;
            default : $name_oblast = 'область';
        }

        $pasp = $value['ch'] . ', ' . $value['locorg_name'] . ', ' . $value['divizion'] . ', ' . $name_oblast;
    }
    ?>

      <span style="    font-size: 15px;  ">
          <i class="fa fa-calendar-check-o fa-lg" aria-hidden="true" style="color:blue"></i>&nbsp;&nbsp;
        Заступил <span style="color: blue;  ">начальником смены </span>

        <a href="/str/v1/card/<?= $value['id_card'] ?>/ch/<?= $value['ch'] ?>/main" data-toggle="tooltip" data-placement="left" title="Перейти в строевую записку" target="_blank"><?= $pasp ?></a>
    </span>

    <?php

}


/* -------------------------------   worker is in maincou table  ------------------------------- */
if (isset($maincou) && !empty($maincou)) {
    //print_r($br);
    echo '<br><br>';

     foreach ($maincou as $value) {
        switch ($value['id_region']) {
            case 1: $name_oblast = 'Брестская область';
                break;
            case 2: $name_oblast = 'Витебская область';
                break;
            case 3: $name_oblast = 'г. Минск';
                break;
            case 4: $name_oblast = 'Гомельская область';
                break;
            case 5: $name_oblast = 'Гродненская область';
                break;
            case 6: $name_oblast = 'Минская область';
                break;
            case 7: $name_oblast = 'Могилевская область';
                break;
            default : $name_oblast = 'область';
        }

        $pasp = $value['ch'] . ' смену, ' . $value['locorg_name'] . ', ' . $value['divizion'] . ', ' . $name_oblast;
        $pos=$value['posduty'];
    }
    ?>

      <span style="    font-size: 15px;  ">
          <i class="fa fa-calendar-check-o fa-lg" aria-hidden="true" style="color:blue"></i>&nbsp;&nbsp;
        Заступил как <span style="color: blue;  "> <?=  $pos ?></span>
            <?php
            if ($value['ch'] == 2)
                echo 'во';
            else {
                echo 'в';
            }
            ?>
        <a href="/str/v1/card/<?= $value['id_card'] ?>/ch/<?= $value['ch'] ?>/main" data-toggle="tooltip" data-placement="left" title="Перейти в строевую записку" target="_blank"><?= $pasp ?></a>
    </span>

    <?php

}


/* ------------------------------- br - fio on car ------------------------------- */
if (isset($br) && !empty($br)) {
    //print_r($br);
    echo '<br><br>';

     foreach ($br as $value) {
        switch ($value['id_region']) {
            case 1: $name_oblast = 'Брестская область';
                break;
            case 2: $name_oblast = 'Витебская область';
                break;
            case 3: $name_oblast = 'г. Минск';
                break;
            case 4: $name_oblast = 'Гомельская область';
                break;
            case 5: $name_oblast = 'Гродненская область';
                break;
            case 6: $name_oblast = 'Минская область';
                break;
            case 7: $name_oblast = 'Могилевская область';
                break;
            default : $name_oblast = 'область';
        }

        $pasp = $value['ch'] . ' смену, ' . $value['locorg_name'] . ', ' . $value['divizion'] . ', ' . $name_oblast;
    }
    ?>

      <span style="    font-size: 15px;  ">
          <i class="fa fa-car fa-lg" aria-hidden="true" style="color:blue"></i>&nbsp;&nbsp;
        Заступил <span style="color: blue;  ">в боевой расчет </span>
            <?php
            if ($value['ch'] == 2)
                echo 'во';
            else {
                echo 'в';
            }
            ?>
        <a href="/str/v1/card/<?= $value['id_card'] ?>/ch/<?= $value['ch'] ?>/car" data-toggle="tooltip" data-placement="left" title="Перейти в строевую записку" target="_blank"><?= $pasp ?></a>
        на
        <?= $value['mark'] ?>
    </span>

    <?php

}





/* on duty */
        if (isset($duty) && !empty($duty)) {
            //print_r($duty);
            echo '<br><br>';

            ?>

            <span style="    font-size: 15px;  ">
                <span style="    color: red  ">  <i class="fa fa-question-circle fa-lg" aria-hidden="true"></i></span>&nbsp;&nbsp;
                Возможно, данный работник заступил в наряд. <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Работники в наряде: <br></span>

            <?php
            foreach ($duty as $value) {

                ?>
                 &nbsp;  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <textarea disabled=""  rows="2" cols="40" name="fio_duty" id="fio_duty" style="background-color: aliceblue;"><?= $value['fio_duty'] ?></textarea>



        <?php
    }
}



/* maincou fio_text */
        if (isset($fio_text_cou) && !empty($fio_text_cou)) {
            //print_r($duty);
            echo '<br><br>';
            ?>
            <span style="    font-size: 15px;  ">
                <span style="    color: red  ">  <i class="fa fa-question-circle fa-lg" aria-hidden="true"></i></span>&nbsp;&nbsp;
                Возможно, данный работник заступил как <span style="color: blue;  ">дежурный инспектор ОНиП</span> или <span style="color: blue;  ">ответственный по гарнизону</span>. <br><br>

            <?php
            foreach ($fio_text_cou as $value) {
                ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?= $value['posduty'] ?>: <i><?=  $value['fio_text'] ?></i><br>
        <?php
    }
    ?>
                 </span>
                 <?php
}

/* worker in his list of change */
if (isset($list_ch) && !empty($list_ch)) {
                     echo '<br><br>';

                     ?>

               <span style="    font-size: 15px;  ">
         <span style="    color: green  ">  <i class="fa fa-check-circle fa-lg" aria-hidden="true"></i></span>&nbsp;&nbsp;
               <?= $list_ch ?> </span>
    <?php
}

/* ch is not duty! */
if (isset($empty_result) && !empty($empty_result)) {
                     echo '<br><br>';

                     ?>

               <span style="    font-size: 15px;  ">
         <span style="    color: red  ">  <i class="fa fa-exclamation-circle fa-lg" aria-hidden="true"></i></span>&nbsp;&nbsp;
               <?= $empty_result ?> </span>
    <?php
}

//}

?>