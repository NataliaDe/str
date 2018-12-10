<?php
//print_r($main);

?>
<br>
<span class="glyphicon glyphicon-hand-up" style="color: red;" ></span>&nbsp;&nbsp;
 <span style="color: red;">  Информация доступна за последние <b>3 дня</b></span>. Данные актуальны, если на указанную дату все строевые заполнены. Без учета ЦОУ, ШЛЧС.
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


