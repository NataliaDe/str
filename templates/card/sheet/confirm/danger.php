
<div class="container">
    <div class="alert alert-danger">

        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Ошибка!</strong>Количество личного состава не соответствует введенным данным. Проверьте информацию и повторите попытку.<br>
<!--         <strong>Обратите внимание: поле 'Количество л/с (по списку)' должно равняться сумме полей 'Налицо', 'На больничном', 'В отпуске', 'В командировке', 'Др.причины'. </strong>-->
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





