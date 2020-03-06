<?php
/* ------- запрос по технике в цифрах ------- */
//foreach ($res as $value) {
//    echo $value['id_pasp'];
//    echo '<br>';
//}


//print_r($teh_mark_from_other_card_array);
//print_r($teh_from_other_card_array);
$id_native_teh = array();
foreach ($res as $value) {//родная техника записать id техники в массив
    $id_native_teh[] = $value['id_pasp'];
}
//print_r($teh_mark_from_other_card_array);
//print_r($res);
foreach ($teh_from_other_card_array as $key => $value) {
    if (!in_array($key, $id_native_teh)) {//добавить в массив информацию о подразделении, где есть только чужая техника, а родной нет
      //  $res[] = $value;
       // print_r($value);
        $id_gochs=$value['id_grochs'];
        $k_pos=0;
        $k_pos_region=0;
         foreach ($res as $k=>$m) {

             if($m['id_grochs'] == $id_gochs){

                 $k_pos=$k+1;
             }
             if($value['region_id'] == $m['region_id'])
                 $k_pos_region=$k+1;

         }

         if($k_pos != 0){
            array_splice($res, $k_pos, 0, array($value));
         }
         elseif($k_pos_region != 0){
            array_splice($res, $k_pos_region, 0, array($value));
         }
         else{
             $res[] = $value;
         }

        unset($teh_from_other_card_array[$key]);
    }
}
//$price = array_column($res, 'region_id');
//
//array_multisort($price, SORT_DESC, $res);
//
//$price = array_column($res, 'organ');
//
//array_multisort($price, SORT_ASC, $res);


 $today = new DateTime(date("Y-m-d"));
$date_start = $today->Format('d.m.Y');
//print_r($res);
//exit();
//print_r($teh_from_other_card_array);
// print_r($res);
?>
<style>
    .tbl-no-border{
        border: 1px solid #e9eee6 !important;
    }
</style>
<center>

    <b>
        РЕЗУЛЬТАТ запроса за <?= (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? date('d.m.Y', strtotime($_POST['date_start'])) : $date_start ?><br>
        наименование техники: <?= (!empty($query_name_teh)) ? $query_name_teh : 'все' ?>
        , вид техники: <?= (!empty($query_vid_teh)) ? $query_vid_teh : 'все' ?>, состояние техники: <?= (!empty($query_name_state_teh)) ? $query_name_state_teh : 'все' ?></b>
</center>
<!--<div class="table-responsive" id="tbl-query-result">-->

<!--
<table>
    <tr >
        <td style="background-color: green;" class="tbl-no-border">&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td class="tbl-no-border"> - боевая</td>
        <td style="background-color: #cc9704;" class="tbl-no-border">&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td class="tbl-no-border"> - резерв</td>
        <td style="background-color: blue;" class="tbl-no-border">&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td class="tbl-no-border"> - ТО</td>
        <td style="background-color: red;" class="tbl-no-border">&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td class="tbl-no-border"> - ремонт</td>
    </tr>
    <tr>
        <td style="background-color: #cc9704;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td> - резерв</td>

    </tr>
    <tr>
        <td style="background-color: blue;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td> - ТО</td>
    </tr>
        <tr>
        <td style="background-color: red;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td> - ремонт</td>
    </tr>
</table>-->
<?php
//print_r($res_mark_array);
?>
<center>

    <table class="table table-condensed   table-bordered tbl_show_inf" style="width: 64% !important;" >
        <!--               строка 1 -->
        <thead>
            <tr >
                <th >№</th>
                <th >Область</th>
                <th >Наименование подразделения</th>
                <th >Марка</th>
                <th>Кол-во</th>

            </tr>


        </thead>
        <?php
        $i = 0;
        $last_id_grochs = 0;
        $last_id_region = 0;
        $grochs_all = 0; //итого по ГРОЧС
        $region_all = 0; //итого по области
        $rb_all = 0; //итого по РБ
        ?>
        <tbody>
            <?php
            foreach ($res as $value) {
                $i++;


                /* -------------------------------------------------  ИТОГО------------------------------------------------------------ */

                if ($type == 1) {//кроме РОСН/UGZ
                    if ($value['id_grochs'] != $last_id_grochs && $last_id_grochs != 0) {//итого по ГРОЧС
                        ?>
                        <tr class="info">
                            <td></td>
                            <td>ИТОГО по Г(Р)ОЧС</td>
                            <td></td>
                            <td></td>
                            <td><?= $grochs_all ?></td>
                        </tr>
                        <?php
                        $grochs_all = 0; //обнулить
                    }
                    if ($value['region_id'] != $last_id_region && $last_id_region != 0) {//итого по region
                        ?>
                        <tr class="warning">
                            <td></td>
                            <td>ИТОГО по области</td>
                            <td></td>
                            <td></td>
                            <td><?= $region_all ?></td>

                        </tr>
            <?php
            $region_all = 0; //обнулить
        }
    }
    ?>
                <!--  ------------------------------------------- END ИТОГО ------------------------------------------------------------------------------>





    <?php

        $co_from_other_pasp = (isset ($teh_from_other_card_array[$value['id_pasp']]['co'])) ? $teh_from_other_card_array[$value['id_pasp']]['co'] : 0; //кол-во техники, которая пришла  из др подразд

    if (( isset($value['co']) && $value['co'] != 0 ) || $co_from_other_pasp != 0) {
        ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= $value['region_name'] ?> </td>
                        <td> <?= $value['divizion'] ?>, <?= $value['organ'] ?> </td>
                        <td><?php
            if (isset($res_mark_array[$value['id_pasp']])) {//марка родной техники
               // print_r($res_mark_array[$value['id_pasp']] );
                foreach ($res_mark_array[$value['id_pasp']] as $mark) {

                    echo $mark . '<br>';
                }
            }
            if (isset($teh_mark_from_other_card_array[$value['id_pasp']])) {//марки техники из др пасч
                foreach ($teh_mark_from_other_card_array[$value['id_pasp']] as $mark) {
                    echo '<b><i>' . $mark .' из др.подр.'. '</i></b><br>';
                }
            }
        ?></td>
                        <td><?= $value['co'] + $co_from_other_pasp ?></td>
                    </tr>
                            <?php
                            $grochs_all+=$value['co'] + $co_from_other_pasp;
                            $region_all+=$value['co'] + $co_from_other_pasp;
                            $rb_all+=$value['co'] + $co_from_other_pasp;
                        }
//            elseif(!isset ($value['co']) &&  $co_from_other_pasp != 0 ){
//
                        ?>
    <!--                <tr>
                    <td><? $i ?></td>
                    <td><? $value['region_name'] ?> </td>
                    <td><? $value['organ'] ?>,  <? $value['divizion'] ?></td>
                    <td><?   $co_from_other_pasp ?></td>
                </tr>     -->
    <?php
//                $grochs_all+= $co_from_other_pasp;
//                $region_all+= $co_from_other_pasp;
//                $rb_all+= $co_from_other_pasp;
//            }
    $last_id_grochs = $value['id_grochs'];
    $last_id_region = $value['region_id'];
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
                        <td><?= $grochs_all ?></td>
                    </tr>
        <?php
        $grochs_all = 0; //обнулить
    }
    /* ++++ Итого по области ++++ */
    if ($last_id_region && $last_id_region != 0) {
        ?>

                    <tr class="warning">
                        <td></td>
                        <td>ИТОГО по области</td>
                        <td></td>
                        <td></td>
                        <td><?= $region_all ?></td>
                    </tr>
        <?php
        $region_all = 0; //обнулить
    }
}
?>
            <tr class="success">
                <td></td>
                <td>ИТОГО</td>
                <td></td>
                <td></td>
                <td><?= $rb_all ?></td>

            </tr>

            <!---------------------------------------- END ИТОГО--------------------------------------------------------------->
        </tbody>
    </table>

</center>


<!--</div>-->