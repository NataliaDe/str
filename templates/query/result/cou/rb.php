<style>
    .main-cou-request tr th {
        font-size: 11px;
    }

    .obl-name{
        background-color: beige;
    }

    .itogo-row{
        background-color: #e8f9dc !important;
    }



/*

    *					{ margin: 0; padding: 0; }
    .main-cou-request				{ font: 13px Georgia, serif; }

    #page-wrap		    {  margin: 0 auto; }

    table               { border-collapse: collapse; width: 100%; margin-top: 109px; }
    td                  { border: 1px solid #ccc; padding: 10px; }

    thead               { width: 79%; position: fixed; height: 109px; top: 67%;
    }

    .slim               { width: 88px; }
    .hover              { background-color: #eee; }


    @media (max-width: 1400px) {
    thead               {
        top: calc(100% - 11em);
    }
}*/
</style>


<div id="page-wrap">



    <?php
//echo '2';
//print_r($region_itogo);
//print_r($countBrRb);

    $cnt_position = count($posduty_list);
    $all_td = $cnt_position + 2;
    $vsego_obl = 0;
    $vsego_pos_obl = [];
    $prev_obl = 'Брестская';
    $i = 0;
    $itogo_rb = [];
    $vsego_rb = 0;

    if (isset($result) && !empty($result)) {

        ?>
<!--        <p> <a name="result_page"></a></p>-->
        <br>
        <center><b>
                <?php
                if (isset($head_info) && !empty($head_info)) {
                    // foreach ($head_info as $h) {
                    $dateduty_head = date('d.m.Y', strtotime($head_info['dateduty']));
                    $name_head = $head_info['name'];
                    //}
                    echo 'Результат запроса за ' . $dateduty_head . ', ' . $name_head . '. ' . 'ЦОУ.';
                }

                ?>

            </b>
            <!--    <div class="table-responsive" id="tbl-query-result">-->
            <table class="table table-condensed   table-bordered tbl_show_inf main-cou-request" style="width: 79% !important" >
                <!--   строка 1 -->
                <thead>

                    <tr>
                        <th>Подразделение&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <?php
                        foreach ($posduty_list as $pos) {

                            ?>
                            <th><?= $pos['name'] ?></th>


                            <?php
                        }

                        ?>
                        <th>Всего</th>
                    </tr>
                </thead>

                <tbody>


                    <?php
                    foreach ($result as $obl => $row) {


                        $i++;


                        if ($prev_obl != $obl) {

                            ?>
                            <tr style="background-color: #e8f9dc !important; font-weight: 800;">
                                <td>
                                    Итого по области:
                                </td>

                                <?php
                                $all_obl_cnt = 0;
                                if (!empty($vsego_pos_obl) && isset($vsego_pos_obl[$prev_obl])) {
                                    foreach ($vsego_pos_obl[$prev_obl] as $value) {
                                        $all_obl_cnt += $value;

                                        ?>
                                        <td><?= $value ?></td>
                                        <?php
                                    }
                                }

                                ?>
                                <td><?= $all_obl_cnt ?></td>
                            </tr>
                            <?php
                        }

                        ?>




                        <tr>
                            <td class="obl-name" colspan="<?= $all_td ?>">
                                <?= ($obl == 'г.Минск' || $obl == 'РОСН') ? $obl : $obl . ' область' ?>
                            </td>
                        </tr>



                        <?php
                        if (isset($row['list_cou']) && !empty($row['list_cou'])) {

                            foreach ($row['list_cou'] as $cou) {

                                ?>
                                <tr style="background-color: <?= ($cou['id_organ'] == 4) ? '#cbe0ef !important' : '' ?>">
                                    <td>
                                        <?= $cou['pasp_name_spec'] . ' ' . $cou['locorg_name_spec'] ?>
                                    </td>


                                    <?php
                                    $vsego = 0;
                                    foreach ($posduty_list as $pos) {

                                        ?>
                                        <td><?= $cou[$pos['name']] ?></td>



                                        <?php
                                        $vsego += $cou[$pos['name']];
                                        $vsego_obl += $cou[$pos['name']];
                                        if (isset($vsego_pos_obl[$obl][$pos['name']]))
                                            $vsego_pos_obl[$obl][$pos['name']] += $cou[$pos['name']];
                                        else {
                                            $vsego_pos_obl[$obl][$pos['name']] = $cou[$pos['name']];
                                        }
                                    }

                                    ?>
                                    <td style="background-color: #e8f9dc !important; font-weight: 800;"><?= $vsego ?></td>

                                </tr>
                                <?php
                            }
                        }
                        $prev_obl = $obl;



                        if ($i == count($result)) {

                            ?>
                            <tr style="background-color: #e8f9dc !important; font-weight: 800;">
                                <td>
                                    Итого по РОСН:
                                </td>

                                <?php
                                $all_obl_cnt = 0;
                                if (!empty($vsego_pos_obl) && isset($vsego_pos_obl[$prev_obl])) {
                                    foreach ($vsego_pos_obl[$prev_obl] as $value) {
                                        $all_obl_cnt += $value;

                                        ?>
                                        <td><?= $value ?></td>
                                        <?php
                                    }
                                }

                                ?>
                                <td><?= $all_obl_cnt ?></td>
                            </tr>
                            <?php
                        }
                    }

                    ?>

                    <tr style="background-color: #e8f9dc !important; font-weight: 800;">
                        <td>
                            ИТОГО по РБ:
                        </td>

                        <?php
                        if (!empty($vsego_pos_obl) && isset($vsego_pos_obl[$prev_obl])) {
                            foreach ($vsego_pos_obl as $value) {

                                foreach ($value as $name_p => $cnt) {

                                    if (isset($itogo_rb[$name_p]))
                                        $itogo_rb[$name_p] += $cnt;
                                    else
                                        $itogo_rb[$name_p] = $cnt;
                                }
                            }
                        }

                        if (isset($itogo_rb) && !empty($itogo_rb)) {
                            foreach ($itogo_rb as $cnt) {

                                $vsego_rb += $cnt;

                                ?>
                                <td><?= $cnt ?></td>
                                <?php
                            }
                        }

                        ?>
                        <td><?= $vsego_rb ?></td>
                    </tr>
                </tbody>
                <tbody>

                    <tr style="background-color: #e9eee6 !important; color:#e9eee6 !important;">
                        <th style="border: 1px solid #e9eee6 !important;">Область</th>
                        <?php
                        foreach ($posduty_list as $pos) {

                            ?>
                            <th style="border: 1px solid #e9eee6 !important;"><?= $pos['name'] ?></th>


                            <?php
                        }

                        ?>
                        <th style="border: 1px solid #e9eee6 !important;">Всего</th>
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
</div>

<!--<script type="text/javascript" src="/str/app/js/jquery-1.11.1.js"></script>-->
<script>
//    $(function () {
//
//        var i = 0;
//
//        $("colgroup").each(function () {
//
//            i++;
//
//            $(this).attr("id", "col" + i);
//
//        });
//
//        var totalCols = i;
//
//        i = 1;
//
//        $("td").each(function () {
//
//            $(this).attr("rel", "col" + i);
//
//            i++;
//
//            if (i > totalCols) {
//                i = 1;
//            }
//
//        });
//
//        $("td").hover(function () {
//
//            $(this).parent().addClass("hover");
//            var curCol = $(this).attr("rel");
//            $("#" + curCol).addClass("hover");
//
//        }, function () {
//
//            $(this).parent().removeClass("hover");
//            var curCol = $(this).attr("rel");
//            $("#" + curCol).removeClass("hover");
//
//        });
//
//    });

</script>