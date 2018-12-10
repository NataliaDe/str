<?php
//print_r($main);

?>
<br>
<span class="glyphicon glyphicon-hand-up" style="color: red;" ></span>&nbsp;&nbsp;
 <span style="color: red;">  Информация доступна за последние <b>3 дня</b></span>. Данные актуальны, если на указанную дату все строевые заполнены.
<br><br><br>
<form  role="form"  method="POST" action="<?= $_SERVER['REQUEST_URI'] ?>">
    
     <div class="row">
         
         <div class="col-lg-2">
            <!--                         Инициализация виджета "Bootstrap datetimepicker" -->
            <div class="form-group">

                <label for="date11">    Дата деж.смены:</label>
                <div class="input-group date" id="date_start">
                    <?php
                    if (isset($_POST['date_start']) && !empty($_POST['date_start'])) {
                        ?>
                        <input type="text" class="form-control"  name="date_start" value="<?= $_POST['date_start'] ?>" />
                        <?php
                    } else {
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

                        } else {
                         
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
         
         <div class="col-lg-3">
            <div class="form-group">
                <label for="locorg">Подразделение</label>
                <select class="form-control" name="locorg" id="locorg" >

                    <?php
                    if ($select_grochs != 1) {
                        ?>
                            <option value="" selected="">все</option>
                        <?php
                    }

                             if($_SESSION['note'] == NULL || $_SESSION['note'] == AVIA){
                                                    foreach ($locorg as $lo) {
                        printf("<p><option value='%s' class='%s'  ><label>%s</label></option></p>", $lo['locorg_id'], $lo['region'], $lo['locor']);
                    } 
                             }
                             else{// CP
                                     foreach ($locorg as $lo) {
                        printf("<p><option value='%s' class='%s'  ><label>%s</label></option></p>", $lo['locorg_id'], $lo['org_id'], $lo['locname']);
                    } 
                             }

                    ?>


                </select>
            </div>
        </div>
         
         <?php
               //     print_r($locorg);
         if($_SESSION['note'] == NULL){
             ?>
         <div class="col-lg-2">
            <div class="form-group">
                <label for="diviz">Часть</label>
                <select class="form-control" name="diviz" id="diviz" >

                    <?php
                    if ($select_pasp != 1) {
                        if (isset($_POST['diviz']) && !empty($_POST['diviz'])) {
                            foreach ($diviz as $di) {
                                if ($_POST['diviz'] == $di['recid']) {
                                    printf("<p><option value='%s' class='%s'><label>%s</label></option></p>", $di['recid'], $di['idlocorg'], $di['name']);
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

                    foreach ($diviz as $di) {
                        printf("<p><option value='%s' class='%s'><label>%s</label></option></p>", $di['recid'], $di['idlocorg'], $di['name']);
                    }
                    ?>

                </select>
            </div>
        </div>
         <?php
         }
         ?>
         
          
     </div>
    <br><br>
    <center>
        <div class="row">

            <div class="form-group">
                <button type="submit" class="btn btn-info" name="export_to_excel">Экспорт в Excel</button>
            </div>  
        </div>
        
    </center>

</form>


