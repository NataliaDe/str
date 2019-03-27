<?php
//print_r($main);

?>
<span class="glyphicon glyphicon-hand-up" style="color: red;" ></span>&nbsp;&nbsp;
Информация доступна за последние <b>3 дня</b>

 <br><br>
<form  role="form"  method="POST" action="/str/v1/report/spr_info/grochs/<?=$id_grochs  ?>/<?= $pasp_active ?>">

     <div class="row">
        <div class="col-lg-2">
            <!--                         Инициализация виджета "Bootstrap datetimepicker" -->
            <div class="form-group">

                <label for="date11">    Дата:</label>
                <div class="input-group date" id="date_start">
                                      <?php

                    if(isset($_POST['date_start'])){
                        ?>
                    <input type="text" class="form-control"  name="date_start" value="<?= $_POST['date_start']?>" />
                    <?php
                    }
                    else{
                        ?>
                      <input type="text" class="form-control"  name="date_start" />
                     <?php
                    }
                    ?>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>

     </div>
    <br><br>
    <center>
        <div class="row">

            <div class="form-group">
                <button type="submit" class="btn btn-success">Результат</button>
            </div>
        </div>
    </center>

</form>


