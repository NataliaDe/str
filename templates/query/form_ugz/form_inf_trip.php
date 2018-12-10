<?php
include 'templates/query/pzform.php';
?>
<!--<div class="col-lg-12">
    <center> <b>Информация по командировкам</b> </center>
</div>-->

<!--форма-->

<form  role="form" id="formFillCar" method="POST" action="/str/builder/basic/inf_trip/3#result_page">

    <div class="row">

        <div class="col-lg-2">
            Дата:
            <!--                         Инициализация виджета "Bootstrap datetimepicker" -->
            <div class="form-group">

                <label for="date11"> c</label>
                <div class="input-group date" id="date11">
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
            &nbsp;
            <div class="form-group">
                <label for="date21">по</label>
                <div class="input-group date" id="date21">
                  	<?php
				 if (isset($_POST['date_end']) && !empty($_POST['date_end'])) {
					 ?>
					 <input type="text" class="form-control"  name="date_end" value="<?= $_POST['date_end'] ?>" />
					 <?php
				 }
				 else{
					 ?>
					  <input type="text" class="form-control" name="date_end" />
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
                    <br>
            <div class="form-group">
                <label for="ch">Смена</label>
                <select class="form-control" name="ch" >
                       <option value="">все</option>    
                    <?php

                    for($i=1;$i<4;$i++){
                          if (isset($_POST['ch']) && !empty($_POST['ch']) && $i==$_POST['ch']) {
                               printf("<p><option selected value='%s' ><label>%s</label></option></p>", $i, $i);
                          }
                          else
                        printf("<p><option value='%s' ><label>%s</label></option></p>", $i, $i);
                    }
                    ?>
                </select>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-lg-2">
            <div class="form-group">
                <label for="region">Подразделение</label>
                <select class="form-control" name="region" id="region" >
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
                <label for="locorg">Подчиненные</label>
                <select class="form-control" name="locorg" id="locorg" >

                    <?php
                    if ($select_grochs != 1) {
                          if (isset($_POST['locorg']) && !empty($_POST['locorg'])) {
                           foreach ($locorg as $lo) {
                                if ($_POST['locorg'] == $lo['locorg_id']) {
                                   printf("<p><option value='%s' class='%s' selected ><label>%s</label></option></p>", $lo['locorg_id'], $lo['org_id'], $lo['locname']);
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
                        printf("<p><option value='%s' class='%s'  ><label>%s</label></option></p>", $lo['locorg_id'], $lo['org_id'], $lo['locname']);
                    }
                    ?>


                </select>
            </div>
        </div>

       </div>

 
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

