<?php
//print_r($main);

/* ---всего по ГРОЧС  --- */
$all_g = 0;
/* ---всего по области --- */
$all_r = 0;

$last_id_grochs = 0;
$last_id_region = 0;
$k = 0; //кол-во больных

if (isset($main) && !empty($main)) {
    ?>
<!--    <div class="table-responsive" id="tbl-query-result">-->
        <?php
        if (isset($date_start) && isset($date_end)) {
            echo '<b><center>С ' . date('d.m.Y', strtotime($date_start)) . ' по ' . date('d.m.Y', strtotime($date_end)) . '</center></b><br>';
        }
                          if (isset($ch) ) {
            echo '<b><center>смена ' . $ch.'</center></b>';
        }
        ?>
<table class="table table-condensed   table-bordered tbl_show_inf" id="tbl_basic_inf_other" >
            <!--   строка 1 -->
            <thead>
                <tr >
                    <th >Область</th>
                    <th >Г(Р)ОЧС</th>
                    <th>Наименование<br>подразделения</th>
                    <th>Ф.И.О.</th>
                       <th>Смена</th>
                    <th>Должность</th>
                    <th>Дата<br>начала</th>
                    <th>Дата<br>окончания</th>
                    <th>Причина</th>

                </tr>

            </thead>

            <tfoot>
                <tr >
                    <th >Область</th>
                    <th >Г(Р)ОЧС</th>
                    <th>Наименование<br>подразделения</th>
                    <th>Ф.И.О.</th>
                       <th>Смена</th>
                    <th>Должность</th>
                    <th>Дата<br>начала</th>
                    <th>Дата<br>окончания</th>
                    <th>Причина</th>

                </tr>

            </tfoot>

            <tbody>
                <?php
                // print_r($main);
                foreach ($main as $key => $value) {


                    foreach ($value as $key2 => $row) {
                        if (!empty($value[$key2])) {
                          /*  if ($type == 1) {//кроме РОСН/UGZ
                                /* ++++++++++ итого по ГРОЧС ++++++++++++++ */
                               /* if ($last_id_grochs != $row['id_grochs'] && $last_id_grochs != 0) {
                                    ?>
                                    <tr class="info">
                                        <td><b>ИТОГО по Г(Р)ОЧС:</b></td>
                                        <td><b><?= $all_g ?></b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    $all_g = 0; //обнулсть
                                }

                                /* ++++ Итого по области ++++ */
                              /*  if ($last_id_region != $row['region_id'] && $last_id_region != 0) {
                                    ?>
                                    <tr class="warning">
                                        <td><b>ИТОГО по области:</b></td>
                                        <td><b><?= $all_r ?></b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    <?php
                                    $all_r = 0; //обнулсть
                                }

                                                            $all_g+=1;
                            $all_r+=1;


                            $last_id_grochs = $row['id_grochs'];
                            $last_id_region = $row['region_id'];

                            }*/
                            ?>
                            <tr>
                                <td><?= $row['region_name'] ?></td>
                                <td><?= $row['name'] ?></td>
                                <td><?= $row['name_div'] ?></td>
                                <td><?= $row['fio'] ?></td>
                                                                 <td> <?php

                                if($row['ch'] == 0)
                                    echo 'еж.';
                                else
                                    echo  $row['ch'];

                                ?></td>
                                <td><?= $row['position'] ?></td>
                                <td><?= date('d.m.Y', strtotime($row['date1'])) ?></td>
                                <td><?= ($row['date2'] !== null)? date('d.m.Y', strtotime($row['date2'])): '' ?></td>
                                <td><?= $row['reason'] ?></td>

                            </tr>
                            <?php
  $k++; //itogo
                        }
                    }
                }

               /* if ($type == 1) {//кроме РОСН/UGZ
                    /* ++++ Итого по ГРОЧС ++++ */
                   /* if ($last_id_grochs && $last_id_grochs != 0) {
                        ?>
                        <tr class="info">
                            <td><b>ИТОГО по Г(Р)ОЧС:</b></td>
                            <td><b><?= $all_g ?></b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                        $all_g = 0;
                    }

                    /* ++++ Итого по области ++++ */
                 /*   if ($last_id_region && $last_id_region != 0) {
                        ?>
                        <tr class="warning">
                            <td><b>ИТОГО по области:</b></td>
                            <td><b><?= $all_r ?></b></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        <?php
                        $all_r = 0; //обнулсть
                    }
                }*/

              /*  if ($k != 0) {
                    ?>
                    <tr class="success">
                        <td><b>ИТОГО:</b></td>
                        <td><b><?= $k ?></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <?php
                }*/
                ?>

            </tbody>
        </table>
        <br>
        <?php
        ?>

<!--    </div>-->
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



