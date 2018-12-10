<?php
include 'templates/query/pzform.php';
?>
<!--<div class="col-lg-12">
    <center> <b>Информация по сменам</b> </center>
</div>-->

<!--форма-->
<span class="glyphicon glyphicon-hand-up" style="color: red;" ></span>&nbsp;&nbsp;
 <span style="color: red;">  Информация доступна за последние <b>3 дня</b></span>
<br><br><br>
<form  role="form" id="formFillCar" method="POST" action="/str/builder/basic/inf_car_big/3#result_page">

  
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
                    } else {
                        ?>
                        <input type="text" class="form-control"  name="date_start"  />
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
        
<div class="col-lg-2">
            <div class="form-group">
                <label for="vid_teh">Вид техники</label>
                <select class="form-control" name="vid_teh" id="vid_teh" >

                    <?php
                    if (isset($_POST['vid_teh']) && !empty($_POST['vid_teh'])) {
                        foreach ($vid_teh as $v) {
                            if ($_POST['vid_teh'] == $v['id']) {
                                printf("<p><option value='%s' ><label>%s</label></option></p>", $v['id'], $v['name']);
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

                    foreach ($vid_teh as $v) {
                        printf("<p><option value='%s'><label>%s</label></option></p>", $v['id'], $v['name']);
                    }
                    ?>

                </select>
            </div>
        </div>
        
        <div class="col-lg-2">
            <div class="form-group">
                <label for="type">Состояние техники</label>
                <select class="form-control" name="state_teh"   >

                    <?php
                    $state_teh=array(1=>'б/р',2=>'резерв',3=>'ТО',4=>'ремонт');
                 
                       if (isset($_POST['state_teh']) && !empty($_POST['state_teh'])) {
                          foreach ($state_teh as $key => $name) {
                            if ($_POST['state_teh'] == $key) {
                                    printf("<p><option value='%s' selected ><label>%s</label></option></p>", $key, $name);
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

                          foreach ($state_teh as $key => $name) {
                                printf("<p><option value='%s' ><label>%s</label></option></p>", $key, $name);
                            }
                    
                    ?>
                </select>
            </div>
        </div>

    </div>
    
        
    <div class="row">
                <label class="control-label col-sm-12 col-lg-8 col-xs-4" for="technic_name">Наименование</label>
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