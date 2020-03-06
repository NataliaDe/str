<?php
//print_r($main);

?>
<br>
<span class="glyphicon glyphicon-hand-up" style="color: red;" ></span>&nbsp;&nbsp;
Информация доступна за последние <b>3 дня</b>. Выбирается техника, которая на выбранную дату имеет состояние "ремонт".

 <br><br>
<form  role="form"  method="POST" action="/str/v1/report/teh_repaire">

     <div class="row">
        <div class="col-lg-2">
            <!--                         Инициализация виджета "Bootstrap datetimepicker" -->
            <div class="form-group">

                <label for="date11">    Дата:</label>
                <div class="input-group date" id="date_single_report">
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

                <label class="control-label col-sm-12 col-lg-8 col-xs-4" for="technic_name">Наименование техники</label>
        <div class="col-sm-6 col-lg-5 col-md-7 col-xs-7">
            <div class="form-group">

                <select class="form-control chosen-select-deselect " name="technic_name" id="technic_name" tabindex="2" data-placeholder="вся техника">
                    <option></option>
                    <?php
                    foreach ($name_teh as $row) {
                        if (isset($_POST['technic_name']) && !empty($_POST['technic_name']) && ($_POST['technic_name'] == $row['id'])) {//не очищать форму
                            printf("<p><option value='%s' selected ><label>%s(%s)</label></option></p>", $row['id'], $row['name'], $row['description']);
                        } else
                            printf("<p><option value='%s' ><label>%s(%s)</label></option></p>", $row['id'], $row['name'], $row['description']);
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


