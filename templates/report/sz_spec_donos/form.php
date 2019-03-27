<?php
//print_r($main);

?>
<span class="glyphicon glyphicon-hand-up" style="color: red;" ></span>&nbsp;&nbsp;
Информация доступна за последние <b>3 дня</b>
<?php
//print_r($main);

?>

<br>
&nbsp;&nbsp;
<span style="color: red;">
&nbsp;&nbsp;    Для просмотра информации за весь РОСН/УГЗ необходимо выбрать соответствующее подразделение в списке "Область".<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Для просмотра информации по подчиненным РОСН/УГЗ - выбрать в списке "Подразделение" соответствующей области.</span>
<br><br><br>
<form  role="form"  method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">

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


        <div class="col-lg-2">
            <div class="form-group">
                <label for="region">Область</label>
                <select class="form-control" name="region" id="region"  >
                    <option value="">Все</option>
                    <?php
                    if (isset($_POST['region']) && !empty($_POST['region'])) {
                        foreach ($region as $re) {
                            if ($_POST['region'] == $re['id']) {
                                printf("<p><option value='%s' selected ><label>%s</label></option></p>", $re['id'], $re['name']);
                            } else {
                                printf("<p><option value='%s' ><label>%s</label></option></p>", $re['id'], $re['name']);
                            }
                        }
                    } else {
                        foreach ($region as $re) {
                            printf("<p><option value='%s' ><label>%s</label></option></p>", $re['id'], $re['name']);
                        }
                    }

                    ?>
                </select>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="form-group">
                <label for="locorg">Подразделение</label>
                <select class="form-control" name="locorg" id="locorg" >

                    <option value="" >все</option>
                    <?php
                    if (isset($_POST['locorg']) && !empty($_POST['locorg'])) {
                        foreach ($locorg as $lo) {
                            if ($_POST['locorg'] == $lo['locorg_id']) {
                                printf("<p><option value='%s' class='%s' selected ><label>%s</label></option></p>", $lo['locorg_id'], $lo['region'], $lo['locor']);
                            } else {
                                printf("<p><option value='%s' class='%s' ><label>%s</label></option></p>", $lo['locorg_id'], $lo['region'], $lo['locor']);
                            }
                        }
                    } else {
                        foreach ($locorg as $lo) {
                            printf("<p><option value='%s' class='%s'  ><label>%s</label></option></p>", $lo['locorg_id'], $lo['region'], $lo['locor']);
                        }
                    }

                    ?>
                </select>
            </div>
        </div>


        <div class="col-lg-2">
            <div class="form-group">
                <label for="diviz">Часть</label>
                <select class="form-control" name="diviz" id="diviz" >
                    <option value="" >все</option>
                    <?php
                    if (isset($_POST['diviz']) && !empty($_POST['diviz'])) {
                        foreach ($diviz as $di) {
                            if ($_POST['diviz'] == $di['recid']) {
                                printf("<p><option value='%s' class='%s' selected><label>%s</label></option></p>", $di['recid'], $di['idlocorg'], $di['name']);
                            } else {
                                printf("<p><option value='%s' class='%s'><label>%s</label></option></p>", $di['recid'], $di['idlocorg'], $di['name']);
                            }
                        }

                        ?>

                        <?php
                    } else {

                        foreach ($diviz as $di) {
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
                <button type="submit" class="btn btn-success" >Вывод на экран</button>
            </div>
        </div>

        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-info" name="export_to_word">Экспорт в Word</button>
            </div>
        </div>



    </center>

</form>





