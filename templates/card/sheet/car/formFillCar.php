
<div class="container">
    <div class="col-lg-12">
        
        <?php
        if(isset($tech) && !empty($tech)){

            ?>
        
        <form  role="form" id="formFillCar" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/car">
            <?php
            //  if ((($_SESSION['ulevel'] == 1) && ($_SESSION['can_edit'] == 1) && ($rcu == 0)) || ($_SESSION['can_edit'] == 0) || ($duty == 1)) {//рцу чужие строевые может только просматривать, редактировать можно только свою
             if ((($duty == 1) && ($is_open_update == 0) && ($is_duty_today == 1)) || (($data['is_btn_confirm'] != 1) && ($is_open_update == 0))) {//смена деж и доступ на редактирование закрыт
                ?>
                <fieldset disabled>
                    <?php
                }
                ?>
                <b>Заполните поля формы:</b>
                <br><br><br>
                <?php
//echo $countill;
                $i = 0;

                foreach ($tech as $t) {
                    $i++;
                    ?>

                    <div class="row">

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="techclass">Вид техники</label>
                                 <input type="text" class="form-control" id="tehclass<?= $i ?>"  name="tehclass<?= $i ?>" value="<?= $t['name_teh'] ?>" disabled="disabled">
                                             </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="tname">Наименование</label>
                                <input type="text" class="form-control" id="tname" placeholder="№" name="tname" value="<?= $t['name_teh'] ?>" disabled="disabled">
                            </div>
                        </div>


                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="mark">Марка</label>
                                <textarea class="form-control" id="mark"  name="mark" disabled="disabled"><?= $t['mark'] ?></textarea>
                             <!--  <input type="text" class="form-control" id="mark"  name="mark" value=" <? /*$t['mark']*/ ?>" disabled="disabled">-->
                            </div>
                        </div>


                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="numbsign<?= $i ?>">Номерной знак</label>
                                <input type="text" class="form-control"  placeholder="Номерной знак"  id="numbsign<?= $i ?>" name="numbsign<?= $i ?>">

                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="petrol<?= $i ?>">Бензин, л</label>
                                <input type="text" class="form-control" placeholder="Бензин, т" id="petrol<?= $i ?>"   name="petrol<?= $i ?>" >

                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label  for="diesel<?= $i ?>">ДТ, л</label>
                                <input type="text" class="form-control" id="diesel<?= $i ?>" placeholder="ДТ, т" name="diesel<?= $i ?>">

                            </div>
                        </div>

                        <!--
                                                <div class="col-lg-2">
                                                    <div class="form-group">
                                                        <label for="typename">Боевая/резерв</label>
                                                        <input type="text" class="form-control" id="typename"  id="v" name="typename" value="<?= $t['type_name'] ?>" disabled="disabled">

                                                    </div>
                                                </div>-->

                    </div>

                    <div class="row">

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="powder<?= $i ?>">ОП, л</label>
                                <input type="text" class="form-control" id="powder<?= $i ?>" placeholder="Порошок, т" name="powder<?= $i ?>">


                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label  for="foam<?= $i ?>">ПО, л</label>
                                <input type="text" class="form-control" id="foam<?= $i ?>" placeholder="Пенообразователь, т" name="foam<?= $i ?>">

                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="v">Объем цистерны, л</label>
                                <input type="text" class="form-control" id="v"   name="v" value="<?= $t['v'] ?>" disabled="disabled">


                            </div>
                        </div>

                        <!--<label class="control-label col-sm-1 col-lg-1 col-md-1 col-xs-1" for="calculation">Мин. боевой<br>расчет</label>
                        <div class="col-sm-2 col-lg-1 col-md-3 col-xs-2">
                            <div class="form-group">
                                <input type="text" class="form-control" id="divizionNum"  id="calculation" name="calculation" value="<?// $t['calculation'] ?>">


                            </div>
                        </div>-->
                                                            <div class="col-lg-2">
                                        <div class="form-group">
                                            <label for="type">Состояние техники</label>
                                            <select class="form-control" name="type<?= $i ?>" id="type<?= $i ?>"   >

                                                <?php
                                                foreach ($type_teh as $key=>$ty) {
                                                        printf("<p><option value='%s' ><label>%s</label></option></p>", $key, $ty);
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
<!--                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="type">Боевая/резерв</label>
                                <select class="form-control" name="type<? $i ?>" id="type<? $i ?>" onchange="getTehType(<? $i ?>);"  >

                                    <?php
//                                    foreach ($type as $row) {
//                                        if ($row['id'] == 1) {
//                                            printf("<p><option value='%s' selected><label>%s</label></option></p>", $row['id'], $row['name']);
//                                        } else {
//                                            printf("<p><option value='%s' ><label>%s</label></option></p>", $row['id'], $row['name']);
//                                        }
//                                    }
                                    ?>

                                </select>

                            </div>
                        </div>-->
<!--                        <div class="col-lg-2">
                            <div class="form-group">
                                <label  for="to<? $i ?>">ТО</label>
                                <select class="form-control" name="to<? $i ?>" id="to<? $i ?>" onchange="getTehTo(<? $i ?>);">

                                    <?php
//                                    foreach ($to as $row) {
//                                        printf("<p><option value='%s' ><label>%s</label></option></p>", $row['id'], $row['name']);
//                                    }
                                    ?>

                                </select>


                            </div>
                        </div>-->

<!--                        <div class="col-lg-2">
                            <div class="form-group">
                                <label  for="repair<? $i ?>">Ремонт</label>
                                <select class="form-control" name="repair<? $i ?>" id="repaire<? $i ?>" onchange="getTehRepaire(<? $i ?>);">
                                    <option value='0' selected="">нет</option>
                                    <option value='1'>да</option>

                                </select>


                            </div>
                        </div>-->

                        <input type="hidden" class="form-control"   id="idcar<?= $i ?>" name="idcar<?= $i ?>" value="<?= $t['tid'] ?>">



                    </div>



                    <div class="row">
                        
                        
                            <div class="col-lg-2">
                            <div class="form-group">
                                <label  for="fio<?= $i ?>[]">Ф.И.О.л/с отделения </label>
                                <select class="form-control chosen-select-deselect" name="fio<?= $i ?>[]" id="fio<?= $i ?>" multiple tabindex="4" data-placeholder="Добавить" >

                                    <option></option>
                                    <?php
                                    if ($fio_car && !empty($fio_car)) {
                                        foreach ($listfio as $l) {
                                            if (in_array($l['id'], $fio_car)) {
                                                printf("<p><option value='%s' selected ><label>%s%s</label></option></p>", $l['id'], $l['fio'], $l['cardch']);
                                            } else {
                                                printf("<p><option value='%s'  ><label>%s%s</label></option></p>", $l['id'], $l['fio'], $l['cardch']);
                                            }
                                        }
                                    } else {
                                        foreach ($listfio as $l) {
                                            printf("<p><option value='%s'  ><label>%s%s</label></option></p>", $l['id'], $l['fio'], $l['cardch']);
                                        }
                                    }
                                    ?>



                                </select>


                            </div>
                        </div>
                            
                        
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="comments">Примечание</label>
                                <textarea class="form-control" id="comments<?= $i ?>"  name="comments<?= $i ?>" ></textarea>
                             <!--  <input type="text" class="form-control" id="mark"  name="mark" value=" <? /*$t['mark']*/ ?>" disabled="disabled">-->
                            </div>
                        </div>

                    </div>


                    <hr>
                    <?php
                }
                ?>


                <input type="hidden" class="form-control"   id="countcar" name="countcar" value="<?= $i ?>">
                <div class="col-lg-12  col-md-offset-3 col-sm-offset-3">
                    <div class="row">

                        <div class="form-group">

                            <div class="col-sm-offset-5 col-lg-offset-1">
                                <button type="submit" class="btn btn-success">Сохранить изменения</button>
                                <br>    <br>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                // if ((($_SESSION['ulevel'] == 1) && ($_SESSION['can_edit'] == 1) && ($rcu == 0)) || ($_SESSION['can_edit'] == 0) || ($duty == 1)) {//рцу чужие строевые может только просматривать, редактировать можно только свою//смена дежурная-редактирование недоступно
                if ((($duty == 1) && ($is_open_update == 0) && ($is_duty_today == 1)) || (($data['is_btn_confirm'] != 1) && ($is_open_update == 0))) {//смена деж-ред недоступно
                    ?>
                </fieldset>
                <?php
            }
            ?>
        </form>
        
        <?php
        }
        else{//для ЦОУ, если нет техники для заступления - поставить отметку и сохранить
  include 'cou/is_car_form.php';
        }
        ?>

        

    </div>
</div>