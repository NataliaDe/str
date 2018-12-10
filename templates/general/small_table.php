<?php
if (isset($duty_ch) && !empty($duty_ch)) {
    $duty_ch = $duty_ch;
} else
    $duty_ch = 1;

$itogo_rb=0;
$itogo_yes_rb=0;
$itogo_yes_after_rb=0;
$itogo_no_rb=0;
?>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="general_table">

        <div class="row">
        <!-- Содержимое вкладки  general_table -->
        <div class="col-lg-5">
            <br>
            <br>
<!--            <center>-->
            <div class="table-responsive" id="div_tbl_general">

                <table class="table table-condensed   table-bordered" id="small_table">
                      <caption style="color: #0d420c">  <u><b>Строевая записка ОПЧС на <?= date("d-m-Y") ?></b></u></caption>
                    <thead>
                        <tr>
                            <th>Область</th>
                            <th>Заполнено</th>
                             <th>Заполнено<br>после 10:00</th>
                            <th>Не заполнено</th>
                             <th>Всего</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
//УМЧС
                       $last=  array_pop($general);
                      // print_r($last);
                        foreach ($general as $value) {

                            ?>
                            <tr>
                                <td><?= $value['region_name'] ?></td>
                                <td class="success"><?= $value['yes_fill'] ?></td>
                                <td class="warning"><?= $value['yes_fill_after'] ?></td>
                                <td class="danger"><?= $value['no_fill'] ?></td>
                                 <td><?= $value['itogo'] ?></td>
                            </tr>
                            <?php

                        }

                            ?>
                            <tr>
                                <td><b>ИТОГО:</b></td>
                                <td class="success"><b><?= $last['yes_fill'] ?></b></td>
                                <td class="warning"><b><?= $last['yes_fill_after'] ?></b></td>
                                <td class="danger"><b><?= $last['no_fill'] ?></b></td>
                                 <td><b><?= $last['itogo'] ?></b></td>
                            </tr>
                            <?php
                             $itogo_yes_rb+=$last['yes_fill'];
                             $itogo_yes_after_rb+=$last['yes_fill_after'];
                             $itogo_no_rb+=$last['no_fill'];
                        $itogo_rb+=$last['itogo'];//итого по рб

                        unset($last);
//РОСН,УГЗ, ГИИ, ИППК,Авиация
                        if (isset($general_2) && !empty($general_2)) {
                             $last=  array_pop($general_2);
                            foreach ($general_2 as $value) {
                                ?>
                                <tr>
                                    <td><?= $value['region_name'] ?></td>
                                    <td class="success"><?= $value['yes_fill'] ?></td>
                                     <td class="warning"><?= $value['yes_fill_after'] ?></td>
                                    <td class="danger"><?= $value['no_fill'] ?></td>
                                     <td><?= $value['itogo'] ?></td>
                                </tr>
                                <?php
                            }

                                ?>
                                <tr>
                                     <td><b>ИТОГО:</b></td>
                                <td class="success"><b><?= $last['yes_fill'] ?></b></td>
                                <td class="warning"><b><?= $last['yes_fill_after'] ?></b></td>
                                <td class="danger"><b><?= $last['no_fill'] ?></b></td>
                                 <td><b><?= $last['itogo'] ?></b></td>
                                </tr>
                                <?php
                                         $itogo_yes_rb+=$last['yes_fill'];
                             $itogo_yes_after_rb+=$last['yes_fill_after'];
                             $itogo_no_rb+=$last['no_fill'];
                        $itogo_rb+=$last['itogo'];//итого по рб

                        }
                        ?>
                                <tr>
                                    <td><b>ИТОГО:</b></td>
                                    <td><b><?= $itogo_yes_rb ?></b></td>
                                    <td><b><?= $itogo_yes_after_rb ?></b></td>
                                    <td><b><?= $itogo_no_rb ?></b></td>
                                    <td><b><?= $itogo_rb ?></b></td>
                                </tr>
                    </tbody>
                </table>
            </div>
<!--            </center>-->
        </div>


        <div class="col-lg-5">


            <br>

            <br>
<!--            <center>-->
                <div class="table-responsive" id="div_tbl_general">
                    <table class="table table-condensed   table-bordered" id="small_table">
                        <caption style="color: #0d420c">  <u><b>Строевая записка ЦОУ на <?= date("d-m-Y") ?></b></u></caption>
                        <thead>
                            <tr>
                                <th>Область</th>
                                <th>Заполнено</th>
                                <th>Не заполнено</th>
                                <th>Всего</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sum_fill=0;
                            $sum_no_fill=0;
                            $sum_vsego=0;
                            foreach ($general as $value) {

                                ?>
                                <tr>
                                    <td><?= $value['region_name'] ?></td>
                                    <?php
                                    $f = (isset($fill_cou[$value['region_id']])) ? $fill_cou[$value['region_id']] : 0;
$sum_fill+=$f;
$sum_no_fill+=(isset($f) && isset($cnt_cou[$value['region_id']])) ? ($cnt_cou[$value['region_id']] - $f) : 0 ;
                                    ?>
                                    <td class="success"><?= $f ?> </td>
                                    <td class="danger"><?= (isset($f) && isset($cnt_cou[$value['region_id']])) ? ($cnt_cou[$value['region_id']] - $f) : 0 ?></td>
                                    <td><?= (isset($cnt_cou[$value['region_id']])) ? $cnt_cou[$value['region_id']] : 0 ?></td>
                                </tr>
                                <?php
                            }

                            ?>
                        <td><b>ИТОГО</b></td>
                        <td><b><?= $sum_fill ?></b></td>
                        <td><b><?= $sum_no_fill ?></b></td>
                        <td><b><?= array_sum($cnt_cou) ?></b></td>
                        </tbody>
                    </table>
                </div>
<!--            </center>-->

        </div>

        </div>
        <i>Всего <?= $itogo_rb+ array_sum($cnt_cou)  ?> подразделений по республике.</i>
