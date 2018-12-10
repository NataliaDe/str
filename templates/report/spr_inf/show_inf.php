<?php
//print_r($main);

if(isset($main)&& !empty($main)){
     foreach ($main as $key => $value) {
         $date=$value['duty_date1'];
         break;
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
            foreach ($main as $key => $value) {
                ?>
                <tr>
                    <td><?= $value['name'] ?>, смена <?= $value['ch'] ?></td>
                    <td><?= $value['shtat'] ?></td>
                    <td><?= $value['vacant'] ?></td>
                    <td><?= $value['shtat_ch'] ?></td>
                    <td><?= $value['vacant_ch'] ?></td>

                    <td><?= $value['face'] ?></td>
                    <td><?= $value['calc'] ?></td>

                    <td><?= $value['trip'] ?></td>
                    <td><?= $value['holiday'] ?></td>
                    <td><?= $value['ill'] ?></td>
                    <td><?= $value['duty'] ?></td>

                    <td><?= $value['other'] ?></td>
                    <td><?= $value['gas'] ?></td>



                </tr>
                <?php
            }
            ?>

        </tbody>
    </table>
    <br>
    <?php
    
            foreach ($main as $key => $value) {
        ?>
     <br>
            <u><b><?= $value['name'] ?></b></u>
            <br>
        <?php
        //вывод работников в командировке
        if (!empty($main[$key]['trip_inf'])) {
            foreach ($main[$key]['trip_inf'] as $trip_inf) {
                //$date2=(($trip_inf['date2']) != NULL) ? $trip_inf['date2']:'-';
                ?>
            <i><?= $trip_inf['position'] . ' ' ?><?= $trip_inf['fio'] ?> - <b>командировка</b> c  <?= $trip_inf['date1'] ?> по
                        <?php echo (($trip_inf['date2']) != NULL) ? $trip_inf['date2'] : '-'; ?> <?= ', '.$trip_inf['place'] . ' ' ?>
                        <?= ', '.$trip_inf['prikaz'] . ' ' ?>
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
            <i><?= $holiday_inf['position'] . ' ' ?><?= $holiday_inf['fio'] ?> - <b>отпуск</b> c  <?= $holiday_inf['date1'] ?> по
                        <?php echo (($holiday_inf['date2']) != NULL) ? $holiday_inf['date2'] : '-'; ?> <?= ', '.$holiday_inf['prikaz']  ?>.
                       
                    </i>
            <br>
                <?php
            }
        }
        
        //больные
           if (!empty($main[$key]['ill_inf'])) {
            foreach ($main[$key]['ill_inf'] as $ill_inf) {
                ?>
            <i><?= $ill_inf['position'] . ' ' ?><?= $ill_inf['fio'] ?> - <b>болен</b> c  <?= $ill_inf['date1'] ?> по
                        <?php echo (($ill_inf['date2']) != NULL) ? $ill_inf['date2'] : '-'; ?> <?= ', '.$ill_inf['maim'] . ' ' ?>.
                        <?= ', '. $ill_inf['diagnosis'] . ' ' ?>
                       
                    </i>
            <br>
                <?php
            }
        }

        //вывод работников в наряде
        if (!empty($main[$key]['duty_inf'])) {
               $date1_duty = new DateTime($value['duty_date1']);
                                    $d1_duty = $date1_duty->Format('d-m-Y');
                                    $date2_duty = new DateTime($value['duty_date2']);
                                    $d2_duty = $date2_duty->Format('d-m-Y');
            echo '<i>'.$main[$key]['duty_inf'].' - '.'<b>наряд.</b> с '.$d1_duty.' по '. $d2_duty.'</i>';
            echo '<br>';
        }
        
               //др причины
               if (!empty($main[$key]['other_inf'])) {
            foreach ($main[$key]['other_inf'] as $other_inf) {
                ?>
            <i><?= $other_inf['position'] . ' ' ?><?= $other_inf['fio'] ?> - <b>другие причины</b> c  <?= $other_inf['date1'] ?> по
                        <?php echo (($other_inf['date2']) != NULL) ? $other_inf['date2'] : '-'; ?> <?= ', '.$other_inf['reason']  ?>
                          <?php echo (($other_inf['note']) != NULL) ? ', '.$other_inf['note'] : ''; ?>.
                       
                    </i>
            <br>
                <?php
            }
        }
        
                  //ваканты
               if (!empty($main[$key]['vacant_inf'])) {
                 //  print_r($d2_duty)
            foreach ($main[$key]['vacant_inf'] as $vacant_inf) {
                ?>
            <i><?= $vacant_inf['position'] . ' ' ?> - <b><?= $vacant_inf['fio'] ?></b> </i>
            <br>
                <?php
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
?>

