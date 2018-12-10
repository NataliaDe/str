<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
    <button type="button" id="collapseButtonAddCar" class="btn col-lg-12 col-md-12 col-sm-12 col-xs-12" name="send" data-toggle="collapse" data-target="#collapseF"><i class="fa fa-plus" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Добавить технику"></i></button><br>
</div>

<div id="collapseF" class="panel-collapse  collapse col-lg-12 col-md-12 col-sm-12 col-xs-12" >
    <form class="form" role="form" id="formAddReserveCar" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/car/reserve">
          <?php
         if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
                ?>
                <fieldset disabled>
                    <?php
                }
                ?>
        <!--
            <div class="table-responsive" id="conttabl" >-->
        <br>  <br><br>
        <u><b>Выберите технику из другого подразделения и сохраните:</b></u>
        <br><br><br>
        <div class="row">

            <div class="col-lg-2">
                <div class="form-group">
                    <label for="id_region_for_car">Область</label>
                    <select class=" form-control" name="id_region"  id="id_region_for_car" >

                        <?php
                        foreach ($regions as $r) {

                            printf("<p><option value='%s'><label>%s</label></option></p>", $r['id'], $r['name']);
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    <label for="id_grochs_for_car">Г(Р)ОЧС</label>
                    <select class=" form-control" name="id_grochs"  id="id_grochs_for_car" >

                        <?php
                        foreach ($grochs as $gr) {

                            printf("<p><option value='%s' class='%s' ><label>%s</label></option></p>", $gr['id_grochs'], $gr['id_region'], $gr['name']);
                        }
                        ?>

                    </select>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    <label for="id_diviz_for_car">Подразделение</label>
                    <select class=" form-control" name="id_pasp"  id="id_diviz_for_car" >

                        <?php
                        foreach ($pasp as $p) {

                            printf("<p><option value='%s' class='%s'><label>%s</label></option></p>", $p['id_record'], $p['id_grochs'], $p['name']);
                        }
                        ?>

                    </select>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <label for="id_teh_for_car">Техника</label>
                    <select class=" form-control" name="id_teh" id="id_teh_for_reserve"  >

                        <?php
                        foreach ($car_for_reserve as $c) {

                            printf("<p><option value='%s'  class='%s'><label>%s</label></option></p>", $c['id_teh'], $c['id_record'], $c['car']);
                        }
                        ?>

                    </select>
                </div>
            </div>


        </div>

        <div class="row">
            <div class="col-lg-3">
                <!--               Инициализация виджета "Bootstrap datetimepicker" -->
                <div class="form-group">
                    <label for="date1">С (дата)</label>
                    <div class="input-group date" id="date1">
                        <input type="text" class="form-control"  name="date1" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>                       
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <!--               Инициализация виджета "Bootstrap datetimepicker" -->
                <div class="form-group">
                    <label for="date2">По (дата)</label>
                    <div class="input-group date" id="date2">
                        <input type="text" class="form-control"  name="date2" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>                       
                    </div>
                </div>
            </div>


      

            <div class="col-lg-3">
                <label for="prikaz">Основание командирования, дата</label>
                <div class="form-group">
                    <textarea cols="20" rows="4" class="form-control" placeholder="№ приказа, дата"  id="prikaz" name="prikaz">Приказ нач. РОЧС от <?= date("d.m.Y") ?> № </textarea>
                </div>
            </div>

            <div class="col-lg-3">
                <label for="note">Примечание</label>
                <div class="form-group">
                    <textarea cols="20" rows="4" class="form-control" placeholder="Примечание"  id="note" name="note"></textarea>
                </div>
            </div>

        </div>


        <center>
            <div class="row">
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Сохранить в БД</button>
                </div>
            </div>
        </center>
        <?php
if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
    ?>
                </fieldset>
                    <?php
                }
                ?>
    </form>
</div>
