<br>
<center><b><u>Результат запроса</u><br>
        <?php
//        foreach ($head as $value) {
//            echo $value.', ';
//        }

        echo implode(',', $head);
        echo '<br>';
        if(!empty($head_pos))
        echo 'Должности: '.implode(',', $head_pos);
//                foreach ($head_pos as $value) {
//            echo $value.', ';
//        }
        ?>
    </b></center>
<br>
<?php
$all = 0;
$k = 0;


//print_r($main);

if((isset($main)&& !empty($main)) || !empty($main_cou)){

    if(isset($main)&& !empty($main)){
        foreach ($main as $key => $value) {
         $date=$value['duty_date1'];
         break;
     }
}
    elseif(isset($main_cou)&& !empty($main_cou)){
        foreach ($main_cou as $key => $value) {
         $date=$value['duty_date1'];
         break;
     }
}
else{
    $date='';
}



    ?>
<center><b>Информация за <?= $date ?></b></center>
<div  id="tbl-query-result">
    <table class="table table-condensed   table-bordered tbl_show_inf" >
        <!--   строка 1 -->
        <thead>
            <tr >
                <th rowspan="3">Наименование подразделения</th>
                <th colspan="12">Строевая записка по личному составу</th>

            </tr>
            <!-- строка 2 -->
        <th colspan="2">Подразделения</th>
        <th colspan="10">Дежурной смены</th>
        <tr>
            <th>По<br>штату</th>
            <th>Вакансия</th>
            <th>По<br>штату<br>в деж.<br>смене</th>
            <th>Вакансия<br>в деж.<br>смене</th>
            <th>Налицо</th>
            <th>В<br>боевом<br>расчете</th>
            <th>Ком-ка</th>
            <th>Отпуск</th>
            <th>Больные</th>
            <th>Наряд</th>
            <th>Др.<br>причины</th>
            <th>ГДЗС, чел</th>
        </tr>

        </thead>

        <tbody>
            <?php
             if(isset($main)&& !empty($main)){
            foreach ($main as $key => $value) {
                ?>
                <tr>
                    <td><?php echo (isset($value['name'])) ? $value['name'] : 'строевая не заполнена' ?>, смена <?= $value['ch'] ?></td>
                    <td><?= ($value['shtat'] == 0) ? '-' : $value['shtat'] ?></td>
                    <td><?= ($value['vacant'] == 0) ? '-' : $value['vacant'] ?></td>
                    <td><?= ($value['shtat_ch'] == 0) ? '-' : $value['shtat_ch'] ?></td>
                    <td><?= ($value['vacant_ch'] == 0) ? '-' : $value['vacant_ch'] ?></td>

                    <td><?= ($value['face'] == 0) ? '-' : $value['face'] ?></td>
                    <td><?= ($value['calc'] == 0) ? '-' : $value['calc'] ?></td>

                    <td><?= ($value['trip'] == 0) ? '-' : $value['trip'] ?></td>
                    <td><?= ($value['holiday'] == 0) ? '-' : $value['holiday'] ?></td>
                    <td><?= ($value['ill'] == 0) ? '-' : $value['ill'] ?></td>
                    <td><?= ($value['duty'] == 0) ? '-' : $value['duty'] ?></td>

                    <td><?= ($value['other'] == 0) ? '-' : $value['other'] ?></td>
                    <td><?= ($value['gas'] == 0) ? '-' : $value['gas'] ?></td>



                </tr>
                <?php
            }
             }

            /* cou */
if(isset($main_cou)&& !empty($main_cou)){
  foreach ($main_cou as $key => $value) {
                ?>
                <tr>
                    <td><?php echo (isset($value['name'])) ? $value['name'] : 'строевая не заполнена' ?>, смена <?= $value['ch'] ?></td>
                    <td><?= ($value['shtat'] == 0) ? '-' : $value['shtat'] ?></td>
                    <td><?= ($value['vacant'] == 0) ? '-' : $value['vacant'] ?></td>
                    <td><?= ($value['shtat_ch'] == 0) ? '-' : $value['shtat_ch'] ?></td>
                    <td><?= ($value['vacant_ch'] == 0) ? '-' : $value['vacant_ch'] ?></td>

                    <td><?= ($value['face'] == 0) ? '-' : $value['face'] ?></td>
                    <td><?= ($value['calc'] == 0) ? '-' : $value['calc'] ?></td>

                    <td><?= ($value['trip'] == 0) ? '-' : $value['trip'] ?></td>
                    <td><?= ($value['holiday'] == 0) ? '-' : $value['holiday'] ?></td>
                    <td><?= ($value['ill'] == 0) ? '-' : $value['ill'] ?></td>
                    <td><?= ($value['duty'] == 0) ? '-' : $value['duty'] ?></td>

                    <td><?= ($value['other'] == 0) ? '-' : $value['other'] ?></td>
                    <td><?= ($value['gas'] == 0) ? '-' : $value['gas'] ?></td>



                </tr>
                <?php
            }
}

 /* cou */
            ?>

        </tbody>
    </table>
    <br>
    <?php
 if(isset($main)&& !empty($main)){
         foreach ($main as $key => $value) {
               if (!empty($main[$key]['vacant_inf']) || !empty($main[$key]['trip_inf']) || !empty($main[$key]['holiday_inf'])
                  || !empty($main[$key]['ill_inf']) ||  !empty($main[$key]['duty_inf']) || !empty($main[$key]['other_inf'])) {
                   ?>
                        <br>
            <u><b><?= $value['name'] ?></b></u>
            <br>
    <?php
               }


                  //vacant
                    if (!empty($main[$key]['vacant_inf'])) {
                        //  print_r($d2_duty)
                        foreach ($main[$key]['vacant_inf'] as $vacant_inf) {

                            ?>
<!--            <i>1 чел. (<? mb_strtolower($vacant_inf['position'])  . ' ' ?>) - <b><? $vacant_inf['fio'] ?></b> </i>-->
             <i>1 чел. (<?= mb_strtolower($vacant_inf['position'])  . ' ' ?>)  <b>вакансия</b> </i>
                            <br>
                            <?php
                        }
                    }


                    //вывод работников в командировке
        if (!empty($main[$key]['trip_inf'])) {
            foreach ($main[$key]['trip_inf'] as $trip_inf) {
                //$date2=(($trip_inf['date2']) != NULL) ? $trip_inf['date2']:'-';
                ?>
            <i>1 чел. (<?= mb_strtolower($trip_inf['position']) . ' ' ?><?= $trip_inf['fio'] ?>) - <b>командировка</b> c  <?= $trip_inf['date1'] ?> по
                        <?php echo (($trip_inf['date2']) != NULL) ? $trip_inf['date2'] : '-'; ?> <?= ', '.$trip_inf['place'] . ' ' ?>

                <?= (!empty($trip_inf['prikaz'])) ? ( '('.mb_strtolower(mb_substr($trip_inf['prikaz'], 0, 1)) . mb_substr($trip_inf['prikaz'], 1).') ' ) : '' ?>
                        <?= ($trip_inf['is_cosmr'] == 1) ? ', согласовано с ЦОСМР' : ''; ?>.
                    </i>
            <br>
                <?php
            }
        }

        //отпуск
               if (!empty($main[$key]['holiday_inf'])) {
            foreach ($main[$key]['holiday_inf'] as $holiday_inf) {
                ?>
            <i>1 чел. (<?= mb_strtolower($holiday_inf['position']) . ' ' ?><?= $holiday_inf['fio'] ?>) - <b>отпуск</b> c  <?= $holiday_inf['date1'] ?> по
                        <?php echo (($holiday_inf['date2']) != NULL) ? $holiday_inf['date2'] : '-'; ?>  <?= '('.mb_strtolower(mb_substr($holiday_inf['prikaz'], 0, 1)) . mb_substr($holiday_inf['prikaz'], 1).')'  ?>.

                    </i>
            <br>
                <?php
            }
        }

        //больные
           if (!empty($main[$key]['ill_inf'])) {
            foreach ($main[$key]['ill_inf'] as $ill_inf) {
                ?>
            <i>1 чел. (<?= mb_strtolower($ill_inf['position']) . ' ' ?><?= $ill_inf['fio'] ?>) - <b>больничный</b> c  <?= $ill_inf['date1'] ?> по
                        <?php echo (($ill_inf['date2']) != NULL) ? $ill_inf['date2'] : '-'; ?> <?= ', '.$ill_inf['maim'] . ' ' ?>
                        <?= ', '. $ill_inf['diagnosis'] . ' ' ?>.

                    </i>
            <br>
                <?php
            }
        }

        //вывод работников в наряде
        if (!empty($main[$key]['duty_inf']) && $value['duty']>0) {
               $date1_duty = new DateTime($value['duty_date1']);
                                    $d1_duty = $date1_duty->Format('d.m.Y');
                                    $date2_duty = new DateTime($value['duty_date2']);
                                    $d2_duty = $date2_duty->Format('d.m.Y');
            echo '<i>'. $value['duty'].' чел. '.mb_strtolower($main[$key]['duty_inf']).' - '.'<b>наряд.</b> с '.$d1_duty.' по '. $d2_duty.'</i>';
            echo '<br>';
        }

               //др причины
               if (!empty($main[$key]['other_inf'])) {
            foreach ($main[$key]['other_inf'] as $other_inf) {
                ?>
            <i>1 чел. (<?= mb_strtolower($other_inf['position']) . ' ' ?><?= $other_inf['fio'] ?>) - <b>другие причины</b> c  <?= $other_inf['date1'] ?> по
                        <?php echo (($other_inf['date2']) != NULL) ? $other_inf['date2'] : '-'; ?> <?= ' '.$other_inf['reason']  ?>
<!--                          < echo (($other_inf['note']) != NULL) ? ', '.$other_inf['note'] : ''; ?>.-->
  <?= (!empty($other_inf['note'])) ? ( '('.mb_strtolower(mb_substr($other_inf['note'], 0, 1)) . mb_substr($other_inf['note'], 1).') ' ) : '' ?>.
                    </i>
            <br>
                <?php
            }
        }



    }
 }


 /* cou */
  if(isset($main_cou)&& !empty($main_cou)){

         foreach ($main_cou as $key => $value) {
              if (!empty($main_cou[$key]['vacant_inf']) || !empty($main_cou[$key]['trip_inf']) || !empty($main_cou[$key]['holiday_inf'])
                  || !empty($main_cou[$key]['ill_inf']) ||  !empty($main_cou[$key]['duty_inf']) || !empty($main_cou[$key]['other_inf'])) {
                  ?>
                 <br>
            <u><b><?= $value['name'] ?></b></u>
            <br>
            <?php
              }

                  //vacant
                    if (!empty($main_cou[$key]['vacant_inf'])) {
                        //  print_r($d2_duty)
                        foreach ($main_cou[$key]['vacant_inf'] as $vacant_inf) {

                            ?>
<!--            <i>1 чел. (<? mb_strtolower($vacant_inf['position'])  . ' ' ?>) - <b><? $vacant_inf['fio'] ?></b> </i>-->
             <i>1 чел. (<?= mb_strtolower($vacant_inf['position'])  . ' ' ?>)  <b>вакансия</b> </i>
                            <br>
                            <?php
                        }
                    }


                    //вывод работников в командировке
        if (!empty($main_cou[$key]['trip_inf'])) {
            foreach ($main_cou[$key]['trip_inf'] as $trip_inf) {
                //$date2=(($trip_inf['date2']) != NULL) ? $trip_inf['date2']:'-';
                ?>
            <i>1 чел. (<?= mb_strtolower($trip_inf['position']) . ' ' ?><?= $trip_inf['fio'] ?>) - <b>командировка</b> c  <?= $trip_inf['date1'] ?> по
                        <?php echo (($trip_inf['date2']) != NULL) ? $trip_inf['date2'] : '-'; ?> <?= ', '.$trip_inf['place'] . ' ' ?>
 <?= (!empty($trip_inf['prikaz'])) ? ( '('.mb_strtolower(mb_substr($trip_inf['prikaz'], 0, 1)) . mb_substr($trip_inf['prikaz'], 1).') ' ) : '' ?>

                        <?= ($trip_inf['is_cosmr'] == 1) ? ', согласовано с ЦОСМР' : ''; ?>.
                    </i>
            <br>
                <?php
            }
        }

        //отпуск
               if (!empty($main_cou[$key]['holiday_inf'])) {
            foreach ($main_cou[$key]['holiday_inf'] as $holiday_inf) {
                ?>
            <i>1 чел. (<?= mb_strtolower($holiday_inf['position']) . ' ' ?><?= $holiday_inf['fio'] ?>) - <b>отпуск</b> c  <?= $holiday_inf['date1'] ?> по
                        <?php echo (($holiday_inf['date2']) != NULL) ? $holiday_inf['date2'] : '-'; ?>  <?= '('.mb_strtolower(mb_substr($holiday_inf['prikaz'], 0, 1)) . mb_substr($holiday_inf['prikaz'], 1).')'  ?>.

                    </i>
            <br>
                <?php
            }
        }

        //больные
           if (!empty($main_cou[$key]['ill_inf'])) {
            foreach ($main_cou[$key]['ill_inf'] as $ill_inf) {
                ?>
            <i>1 чел. (<?= mb_strtolower($ill_inf['position']) . ' ' ?><?= $ill_inf['fio'] ?>) - <b>больничный</b> c  <?= $ill_inf['date1'] ?> по
                        <?php echo (($ill_inf['date2']) != NULL) ? $ill_inf['date2'] : '-'; ?> <?= ', '.$ill_inf['maim'] . ' ' ?>
                        <?= ', '. $ill_inf['diagnosis'] . ' ' ?>.

                    </i>
            <br>
                <?php
            }
        }

        //вывод работников в наряде
        if (!empty($main_cou[$key]['duty_inf'])  && $value['duty']>0) {
               $date1_duty = new DateTime($value['duty_date1']);
                                    $d1_duty = $date1_duty->Format('d.m.Y');
                                    $date2_duty = new DateTime($value['duty_date2']);
                                    $d2_duty = $date2_duty->Format('d.m.Y');
            echo '<i>'. $value['duty'].' чел. '.mb_strtolower($main_cou[$key]['duty_inf']).' - '.'<b>наряд.</b> с '.$d1_duty.' по '. $d2_duty.'</i>';
            echo '<br>';
        }

               //др причины
               if (!empty($main_cou[$key]['other_inf'])) {
            foreach ($main_cou[$key]['other_inf'] as $other_inf) {
                ?>
            <i>1 чел. (<?= mb_strtolower($other_inf['position']) . ' ' ?><?= $other_inf['fio'] ?>) - <b>другие причины</b> c  <?= $other_inf['date1'] ?> по
                        <?php echo (($other_inf['date2']) != NULL) ? $other_inf['date2'] : '-'; ?> <?= ', '.$other_inf['reason']  ?>
<!--                          < echo (($other_inf['note']) != NULL) ? ', '.$other_inf['note'] : ''; ?>.-->
<?= (!empty($other_inf['note'])) ? ( '('.mb_strtolower(mb_substr($other_inf['note'], 0, 1)) . mb_substr($other_inf['note'], 1).') ' ) : '' ?>.
                    </i>
            <br>
                <?php
            }
        }



    }
 }

    ?>
<!-- <center> <a onclick="javascript:history.back();">  <button class="btn btn-warning" type="button" data-dismiss="modal">Назад</button></a></center>-->
</div>
<?php
}
 else {
    ?>
<div class="container">
    <div class="alert alert-danger">

        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Внимание!</strong> Нет данных для отображения
    </div>
</div>
<?php
}
//print_r($main_cou);
?>

