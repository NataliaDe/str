
<div class="container">
    <div class="alert alert-danger">

        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Ошибка! </strong> Количество личного состава не соответствует введенным данным. Проверьте информацию и повторите попытку.<br>
<!--         <strong>Обратите внимание: поле 'Количество л/с (по списку)' должно равняться сумме полей 'Налицо', 'На больничном', 'В отпуске', 'В командировке', 'Др.причины'. </strong>-->
        <br>

        <?php
        $k=0;
        $spravka='<br>&nbsp;&nbsp;&nbsp;&nbsp;Проверьте информацию, сохраните изменения на главной вкладке.';
        foreach ($error_field as $key=>$row) {
$k++;
echo $k.'. ';
            if($key=='shtat'){
    /* 1 */
    ?>
        Количество л/с (по штату) не соответствует количеству л/с по штату в карточке учета сил и средств.<?= $spravka ?>
        <?php
}

            elseif($key=='on_list'){
    /* 2 */
    ?>
        Количество л/с (по списку) не соответствует количеству л/с по списку в карточке учета сил и средств. <?= $spravka ?>
        <?php
}

            elseif($key=='vacant'){
    /* 3 */
    ?>
        Количество вакантов не соответствует количеству вакантов в списке смен. <?= $spravka ?>
        <?php
}

            elseif($key=='face'){
    /* 4 */
    ?>
        Не соблюдена формула: <b><u>налицо = по списку - на больничном - в командировке - в отпуске - др.причины + ежедневники + из др.подразд.</u></b> <?= $spravka ?>
        <?php
}

            elseif($key=='calc'){
    /* 5 */
    ?>
        Указанное количество работников в боевом расчете не соответствует количеству работников, расставленных на технике. <?= $spravka ?>
        <?php
}

elseif($key=='vacant_form_main_table'){
    /* 6 */
    ?>
        Не соблюдена формула: <b><u>по штату - по списку = вакант</u></b>. <?= $spravka ?>
        <?php
}
elseif($key=='br_duty_face_from_main_table'){
    /* 7 */
    ?>
        Не соблюдена формула: <b><u>боевой расчет + наряд = налицо</u></b>. <?= $spravka ?>
        <?php
}

echo '<br><br>';
        }
        ?>

    </div>
</div>
<?php

//print_r($error_field);
?>
<div class="container noprint">

    <div class="col-lg-12 col-lg-offset-2">
        <div class="row">
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-10 col-md-offset-4 col-lg-offset-3">
                    <?php
                    if (isset($close_update) && ($close_update == 1)) {
                        ?>
                        <?php
                    } else {
                        ?>
                        <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/main" >  <button type="button" class="btn btn-warning">Назад</button></a>
                        <?php
                    }
                    ?>

                </div>
            </div>
        </div>
    </div>
</div>





