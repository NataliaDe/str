
<div class="container">
    <div class="col-lg-12">

        <form  role="form" id="formFillStorage" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/storage">
            <?php
            if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
                ?>
                <fieldset disabled>
                    <?php
                }
                ?>
                <b>Заполните поля формы:</b>
                <br><br><br>

                <?php
                foreach ($storage as $st) {

                }
                ?>
                <div class="row">


                    <!--<div class="col-lg-2">
                        <div class="form-group">
                            <label for="kip">КИП</label>
                            <input type="text" class="form-control"  placeholder="КИП"  id="kip" name="kip"  value="<? /* $st['kip'] */ ?>" >

                        </div>
                    </div>-->

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="asv">АСВ</label>
                            <input type="text" class="form-control"  placeholder="АСВ"  id="asv" name="asv" value="<?= $st['asv'] ?>">

                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label  for="foam" data-toggle="tooltip" data-placement="left" title="Пенообразователь">ПО, л</label>
                            <input type="text" class="form-control" id="foam" placeholder="Пенообразователь, л" name="foam" value="<?= $st['foam'] ?>">

                        </div>
                    </div>


                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="powder" data-toggle="tooltip" data-placement="left" title="Огнетушащий порошок">ОП, л</label>
                            <input type="text" class="form-control" id="powder" placeholder="Порошок, л" name="powder" value="<?= $st['powder'] ?>">


                        </div>
                    </div>




                </div>
                <input type="hidden" class="form-control"   id="idstorage" name="idstorage" value="<?= $st['id'] ?>">
                <input type="hidden" name="_METHOD" value="PUT"/>

               <center>
        <div class="row">
            <div class="form-group">
                 <button type="submit" class="btn btn-primary">Сохранить изменения</button>
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
</div>