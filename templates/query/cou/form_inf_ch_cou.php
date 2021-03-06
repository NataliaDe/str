<?php
include 'templates/query/pzform.php';
?>

<!--<div class="col-lg-12">
    <br><br><br>
    <center> <b>Информация по сменам</b> </center>
</div>-->

<!--форма-->
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <span style="color: green;">  Информация доступна за последние <b>3 дня</b>. Для просмотра информации по ЦОУ РОСН - выберите область "г. Минск".</span><br>

  <span class="glyphicon glyphicon-hand-up" style="color: red;" ></span>&nbsp;&nbsp;
 <span style="color: red;"> <b>Внимание!</b> Формирование сведений по республике может занимать достаточное количество времени в связи с большими объемами данных (от 2 до 5 мин.).</span>
<br><br><br>
<form  role="form" id="formFillCar" method="POST" action="/str/v2/card/builder/basic/inf_ch_cou/<?= $type ?>#result_page">


    <div class="row">

               <div class="col-lg-2">
            <!--                         Инициализация виджета "Bootstrap datetimepicker" -->
            <div class="form-group">

                <label for="date11">    Дата:</label>
                <div class="input-group date" id="date_start">
                   <?php
				 if (isset($_POST['date_start']) && !empty($_POST['date_start'])) {
					 ?>
					 <input type="text" class="form-control"  name="date_start" value="<?= $_POST['date_start'] ?>" />
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

        <div class="col-lg-2">
            <div class="form-group">
                <label for="region">Область</label>
                <select class="form-control" name="region" id="region"  >
                    <?php
                    if ($select != 1) {

                        if (isset($_POST['region']) && !empty($_POST['region'])) {
                            foreach ($region as $re) {
                                if ($_POST['region'] == $re['id']) {
                                    printf("<p><option value='%s' selected ><label>%s</label></option></p>", $re['id'], $re['name']);
                                }
                            }
                            ?>
                            <option value="">все</option>
                            <?php
                        } else {
                            ?>
                            <option value="" selected="">все</option>
                            <?php
                        }
                        ?>

                        <?php
                    }


                    foreach ($region as $re) {
                        printf("<p><option value='%s' ><label>%s</label></option></p>", $re['id'], $re['name']);
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group">
                <label for="locorg">Подразделение</label>
                <select class="form-control" name="locorg" id="locorg" >

                    <?php
                    if ($select_grochs != 1) {
                        if (isset($_POST['locorg']) && !empty($_POST['locorg'])) {
                            foreach ($locorg as $lo) {
                                if ($_POST['locorg'] == $lo['locorg_id']) {
                                    printf("<p><option value='%s' class='%s' selected ><label>%s</label></option></p>", $lo['locorg_id'], $lo['region'], $lo['locor']);
                                }
                            }
                            ?>
                            <option value="">все</option>
                            <?php
                        } else {
                            ?>
                            <option value="" selected="">все</option>
                            <?php
                        }
                        ?>

                        <?php
                    }

                    foreach ($locorg as $lo) {
                        ?>

                         <?php
                        printf("<p><option value='%s' class='%s'  ><label>%s</label></option></p>", $lo['locorg_id'], $lo['region'], $lo['locor']);
                    }
                    ?>


                </select>
            </div>
        </div>

        <div class="col-lg-2">
            <div class="form-group">
                <label for="diviz">Часть</label>
                <select class="form-control" name="diviz" id="diviz" >
                <option value="">все</option>
                    <?php
                            foreach ($diviz as $di) {

                                if (isset($_POST['diviz']) && !empty($_POST['diviz']) && $_POST['diviz'] == $di['recid']) {
                                    printf("<p><option value='%s' class='%s'><label>%s</label></option></p>", $di['recid'], $di['idlocorg'], $di['name']);
                                }
                                else{
                                     printf("<p><option value='%s' class='%s'><label>%s</label></option></p>", $di['recid'], $di['idlocorg'], $di['name']);
                                }
                            }
                            ?>


                </select>
            </div>
        </div>

    </div>
    <br><br>
    <center>
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-success">Результат</button>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-warning" name="export_to_excel">Экспорт в Excel</button>
            </div>
        </div>

    </center>
</form>
