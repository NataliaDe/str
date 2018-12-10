<p style="text-align:  -webkit-right;"><a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/car/1" data-toggle="tooltip" data-placement="left" title="Отсортировать по состоянию 'боевая' " style="text-transform: uppercase"> <button type="button" class="btn btn-default" style="background-color:#ccc"><i class="fa fa-level-up" aria-hidden="true"></i></button></a></p>     
<?php
// print_r($car);
if ((isset($own_car) && !empty($own_car)) || (isset($car_in_reserve) && !empty($car_in_reserve)) || (isset($own_car_in_trip) && !empty($own_car_in_trip))) {
    ?>

    <form  role="form" id="formFillCar" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/car">
        <?php
        if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm == 1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
            ?>
            <fieldset disabled>
                <?php
            }
            // print_r($type_teh);
            ?>
            <b>Заполните поля формы:</b>
          
            <br><br><br>
            <?php
//echo $countill;
            // echo $dateduty;
            $today = date("Y-m-d");
            $i = 0;

            /*----------------------------------------------------------------------------------------  своя техника, доступная для заполнения ----------------------------------------------------------------------------------*/
//$own_car
            $car = $own_car;
            $last_fio_on_car = $fio_on_own_car;


            if (isset($car) && !empty($car)) {
                foreach ($car as $row) {
                    $i++;
                    /* -------- цвет техники определяется в зависим от ее типа: боевая, резерв, ремонт,ТО -------- */
                    if ($row['id_type'] == 1) {//боевая 
                        $color = '#77ca3830';
                    } elseif ($row['id_type'] == 2) {//reserve
                        $color = '#ffff0030';
                    } elseif ($row['id_to'] == 1 || $row['id_to'] == 2) {//to-1,2 
                        $color = '#3fb1f12b';
                    } elseif ($row['is_repair'] == 1) {//repair
                        $color = '#f700001f';
                    } else {
                        $color = '#e9eee6';
                    }
                    ?>


                    <!---------------------------------------- отображаем постоянно ----------------------------------------->
                    <div class="row" style="background-color: <?= $color ?>;" >
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="numbsign<?= $i ?>"></label>
                                <p style="background-color: <?= $color ?>;  cursor: pointer"  data-toggle="collapse" data-target="#collapse<?= $i ?>"><i class="fa fa-plus" aria-hidden="true"></i> <?= $row['mark'] ?></p>
            <!--                                    <button type="button"  id="collapseButtonCar<?= $i ?>" class="btn" style="background-color: <?= $color ?>;"  name="send" data-toggle="collapse" data-target="#collapse<?= $i ?>"><i class="fa fa-plus" aria-hidden="true"></i> <?= $row['mark'] ?></span></button>-->
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="tname">Наименование</label>
                                <input type="text" class="form-control" id="tname" placeholder="№" name="tname" value="<?= $row['name_view'] ?>" disabled="disabled">
                            </div>
                        </div>


                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="numbsign<?= $i ?>">Номерной знак</label>
                                <input type="text" class="form-control" id="numbsign<?= $i ?> "disabled="disabled"  id="numbsign<?= $i ?>" name="numbsign<?= $i ?>"  value="<?= $row['numbsign'] ?>" >
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="techclass">Вид техники</label>
                                <input type="text" class="form-control" id="tehclass<?= $i ?>"  name="tehclass<?= $i ?>" value="<?= $row['teh_cls'] ?>" disabled="disabled">

                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="type">Состояние техники</label>
                                <select class="form-control" name="type<?= $i ?>" id="type<?= $i ?>"   >

                                    <?php
                                    if ($row['id_type'] == 1) {//боевая 
                                        $active_type = 1;
                                    } elseif ($row['id_type'] == 2) {//reserve
                                        $active_type = 2;
                                    } elseif ($row['id_to'] == 1) {//to-1 
                                        $active_type = 3;
                                    } elseif ($row['id_to'] == 2) {//to-2
                                        $active_type = 4;
                                    } elseif ($row['is_repair'] == 1) {//repair
                                        $active_type = 5;
                                    }
                                    foreach ($type_teh as $key => $ty) {
                                        if ($active_type == $key) {
                                            printf("<p><option value='%s' selected ><label>%s</label></option></p>", $key, $ty);
                                        } else {
                                            printf("<p><option value='%s' ><label>%s</label></option></p>", $key, $ty);
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!---------------------------------------- КОНЕЦ отображаем постоянно ----------------------------------------->


                    <div  id="collapse<?= $i ?>" class="panel-collapse collapse">

                        <div class="row"  style="background-color: <?= $color ?>;" >

                            <!--                            <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="techclass">Вид техники</label>
                                                                <input type="text" class="form-control" id="tehclass<?= $i ?>"  name="tehclass<?= $i ?>" value="<?= $row['teh_cls'] ?>" disabled="disabled">
                            
                                                            </div>
                                                        </div>-->

                            <!--                            <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="tname">Наименование</label>
                                                                <input type="text" class="form-control" id="tname" placeholder="№" name="tname" value="<?= $row['name_view'] ?>" disabled="disabled">
                                                            </div>
                                                        </div>-->


                            <!--                            <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="mark">Марка</label>
                                                                <textarea class="form-control" id="mark"  name="mark" disabled="disabled"><?= $row['mark'] ?></textarea>
                                                              <input type="text" class="form-control" id="mark"  name="mark" value=" <? /* $t['mark']*/ ?>" disabled="disabled">
                                                            </div>
                                                        </div>-->

                            <!--                            <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="numbsign<?= $i ?>">Номерной знак</label>
                                                                <input type="text" class="form-control" id="numbsign<?= $i ?> "disabled="disabled"  id="numbsign<?= $i ?>" name="numbsign<?= $i ?>"  value="<?= $row['numbsign'] ?>" >
                                                            </div>
                                                        </div>-->

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="petrol<?= $i ?>">Бензин,л</label>
                                    <input type="text" class="form-control" placeholder="Бензин, т" id="petrol<?= $i ?>"  id="petrol<?= $i ?>" name="petrol<?= $i ?>"  value="<?= $row['petrol'] ?>" >
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label  for="diesel<?= $i ?>">ДТ, л</label>
                                    <input type="text" class="form-control" id="diesel<?= $i ?>" placeholder="ДТ, т" name="diesel<?= $i ?>"  value="<?= $row['diesel'] ?>" >
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="powder<?= $i ?>">ОП, л</label>
                                    <input type="text" class="form-control" id="powder<?= $i ?>" placeholder="Порошок, т" name="powder<?= $i ?>"  value="<?= $row['powder'] ?>" >
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label  for="foam<?= $i ?>">ПО, л</label>
                                    <input type="text" class="form-control" id="foam<?= $i ?>" placeholder="Пенообразователь, т" name="foam<?= $i ?>"  value="<?= $row['foam'] ?>" >
                                </div>
                            </div>


                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="v">Объем цистерны, л</label>
                                    <input type="text" class="form-control" id="v"  id="v" name="v" value="<?= $row['v'] ?>" disabled="disabled">
                                </div>
                            </div>


                        </div>

                        <div class="row"  style="background-color: <?= $color ?>;" >



                            <!--                            <div class="col-lg-2">
                                                            <div class="form-group">
                                                                <label for="type">Состояние техники</label>
                                                                <select class="form-control" name="type<?= $i ?>" id="type<?= $i ?>"   >
                            
                            <?php
                            if ($row['id_type'] == 1) {//боевая 
                                $active_type = 1;
                            } elseif ($row['id_type'] == 2) {//reserve
                                $active_type = 2;
                            } elseif ($row['id_to'] == 1) {//to-1 
                                $active_type = 3;
                            } elseif ($row['id_to'] == 2) {//to-2
                                $active_type = 4;
                            } elseif ($row['is_repair'] == 1) {//repair
                                $active_type = 5;
                            }
                            foreach ($type_teh as $key => $ty) {
                                if ($active_type == $key) {
                                    printf("<p><option value='%s' selected ><label>%s</label></option></p>", $key, $ty);
                                } else {
                                    printf("<p><option value='%s' ><label>%s</label></option></p>", $key, $ty);
                                }
                            }
                            ?>
                                                                </select>
                                                            </div>
                                                        </div>-->

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="reason_repaire">Причина неиспр.</label>
                                    <select class="form-control" name="reason_repaire<?= $i ?>" id="reason_repaire<?= $i ?>"   >

                                        <?php
                                        foreach ($reason_repaire as $re) {

                                            if (isset($row['id_reason_repaire']) && !empty($row['id_reason_repaire'])) {

                                                if ($re['id'] == $row['id_reason_repaire']) {
                                                    printf("<p><option value='%s' selected ><label>%s</label></option></p>", $re['id'], $re['name']);
                                                } else {
                                                    printf("<p><option value='%s' ><label>%s</label></option></p>", $re['id'], $re['name']);
                                                }
                                            } else {

                                                if ($re['id'] == 1) {
                                                    printf("<p><option value='%s' selected ><label>%s</label></option></p>", $re['id'], $re['name']);
                                                } else {
                                                    printf("<p><option value='%s' ><label>%s</label></option></p>", $re['id'], $re['name']);
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-2">

                                <label for="comments">Примечание</label>


                                <div class="form-group">
                                    <?php
                                    if ($row['comments'] != NULL) {
                                        ?>
                                        <textarea class="form-control" id="comments<?= $i ?>"  name="comments<?= $i ?>" ><?= $row['comments'] ?></textarea>
                                        <?php
                                    } else {
                                        ?>
                                        <textarea class="form-control" id="comments<?= $i ?>"  name="comments<?= $i ?>" ></textarea>
                                        <?php
                                    }
                                    ?>

                                                                                                                                                <!--  <input type="text" class="form-control" id="mark"  name="mark" value=" <? /*$t['mark']*/ ?>" disabled="disabled">-->
                                </div>
                            </div>



                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="type">Дата неисправности</label>
                                    <div class="input-group date" id="date1<?= $i ?>" >

                                        <?php
                                        if ($row['start_repaire'] == NULL) {
                                            ?>
                                            <input type="text" class="form-control"  name="date1<?= $i ?>"style="width: 125px;"  />
                                            <?php
                                        } else {
                                            $dat1 = new DateTime($row['start_repaire']);
                                            $d1 = $dat1->Format('d-m-Y');
                                            ?>
                                            <input type="text" class="form-control"  name="date1<?= $i ?>"style="width: 125px;"  value="<?= $d1 ?>" />

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
                                    <label for="type">Дата устранения</label>
                                    <div class="input-group date" id="date2<?= $i ?>">
                                        <?php
                                        if ($row['end_repaire'] == NULL) {
                                            ?>
                                            <input type="text" class="form-control" name="date2<?= $i ?>" style="width: 125px;" />
                                            <?php
                                        } else {
                                            $dat2 = new DateTime($row['end_repaire']);
                                            $d2 = $dat2->Format('d-m-Y');
                                            ?>
                                            <input type="text" class="form-control" name="date2<?= $i ?>" style="width: 125px;"  value="<?= $d2 ?>" />


                                            <?php
                                        }
                                        ?>

                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>



                            <input type="hidden" class="form-control"   id="idcar<?= $i ?>" name="idcar<?= $i ?>" value="<?= $row['tehstr_id'] ?>">
                        </div>

                        <div class="row"  style="background-color: <?= $color ?>;" >

                            <!--Классификатор ФИО-->
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label  for="fio<?= $i ?>[]">Ф.И.О.л/с отделения


                                        <?php
                                        /**  Кто заступал прошлый раз  * */
                                        if ($dateduty != $today) {
                                            if (isset($last_fio_on_car) && !empty($last_fio_on_car)) {
                                                $x_array=array();
                                                foreach ($last_fio_on_car as $x) {
                                                   $x_array[]=$x['tehstr_id']; 
                                                }
                                                if(in_array($row['tehstr_id'], $x_array)){//отображаем колокольчик, если прошлый раз кто-то заступал на эту машину
                                                ?>


                                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right" 

                                                            title="Заступали прошлый раз: <?php
                                                            foreach ($last_fio_on_car as $value) {
                                                                if ($row['tehstr_id'] == $value['tehstr_id']) {
                                                                    echo $value['fio'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . '), ';
                                                                }
                                                            }
                                                            ?> ">

                                                </i>



                                                <div class="col-lg-4">
                                                    <div class="form-group">
                                                        <label  for="fio<?= $i ?>[]"><u>Заступали: </u> </label>
                                                        <?php
//                                            foreach ($last_fio_on_car as $value) {
//                                                if ($row['tehstr_id'] == $value['tehstr_id']) {
//                                                    echo $value['fio'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . ')<br>';
//                                                }
//                                            }
                                                        ?>


                                                    </div>
                                                </div>
                                                <?php
                                                }
                                            }
                                        }
                                        ?>

                                    </label>
            <!--                                    <select class="form-control chosen-select-deselect" name="fio<?= $i ?>[]" id="fio<?= $i ?>" multiple tabindex="4" data-placeholder="Добавить" >-->
                                    <select class="form-control js-example-basic-multiple" name="fio<?= $i ?>[]" id="fio<?= $i ?>" multiple tabindex="4" data-placeholder="Добавить" >
                                        <option></option>
                                        <?php
                                        if (isset($present_car_fio) && !empty($present_car_fio)) {
                                            foreach ($present_car_fio as $present) {
                                                if (isset($last_fio_on_car) && !empty($last_fio_on_car)) {
                                                    $k = 0;
                                                    foreach ($last_fio_on_car as $value) {
                                                        if ($row['tehstr_id'] == $value['tehstr_id'] && $present['id'] == $value['id']) {
                                                            $k++;
                                                        }
                                                    }
                                                    if ($k != 0)
                                                        printf("<p ><option value='%s' selected ><label >%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                                    else {
                                                        printf("<p><option value='%s'><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                                    }
                                                } else {
                                                    printf("<p><option value='%s'  ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>



<div class="col-lg-2">
                            <div class="form-group">
                                <label for="tname">Мин.б.р.</label>
                                <input type="text" class="form-control" id="tcalc" name="tcalc" value="<?= $row['calc'] ?>" disabled="disabled">
                            </div>
                        </div>





                        </div>
                    </div>

                    <!--                Техника доступна для редактирования и сохранения в БД в отличие от той, которая просто отображается на экран(командировка, резерв)-->
                    <input type="hidden" value="1" name="is_save">
                    <hr>
                    <?php
                }// end foreach car
            }

            // echo count($last_fio_on_car);
//                Техника, которая в командировке/в др ПАСЧ - только отображение
            if (isset($own_car_in_trip) && !empty($own_car_in_trip)) {
                foreach ($own_car_in_trip as $value) {
                    ?>
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="mark">Марка</label>
                                <textarea class="form-control" disabled="disabled"><?= $value['mark'] ?></textarea>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="numbsign<?= $i ?>">Номерной знак</label>
                                <input type="text" class="form-control" id="numbsign<?= $i ?> "disabled="disabled" name="numbsign<?= $i ?>"  value="<?= $value['numbsign'] ?>" >
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <br>
                            <div class="form-group">
                                <div class="checkbox checkbox-danger">
                                    <input id="checkbox15" type="checkbox" checked="checked" disabled readonly="">             
                                    <label for="checkbox15">
                                        <b>  В КОМАНДИРОВКЕ</b>
                                    </label>
                                </div>
            <!--                                <label for="numbsign<?= $i ?>"></label>
                                         <input type="text" class="form-control" disabled="disabled"   value="   В КОМАНДИРОВКЕ" >
                                -->
                            </div>
                        </div>
                    </div>

                    <hr>
                    <?php
                }
            }
            ?> 

            <!-----------------------------------------------------------------      техника из др ПАСЧ, доступная для заполнения ------------------------------------------------------------------------>
            <?php
// car_in_reserve
            $car = $car_in_reserve;
            $last_fio_on_car = $fio_on_reserve_car;

            if (isset($car) && !empty($car)) {
                foreach ($car as $row) {
                    $i++;
                    
                           /* -------- цвет техники определяется в зависим от ее типа: боевая, резерв, ремонт,ТО -------- */
                    if ($row['id_type'] == 1) {//боевая 
                        $color = '#77ca3830';
                    } elseif ($row['id_type'] == 2) {//reserve
                        $color = '#ffff0030';
                    } elseif ($row['id_to'] == 1 || $row['id_to'] == 2) {//to-1,2 
                        $color = '#3fb1f12b';
                    } elseif ($row['is_repair'] == 1) {//repair
                        $color = '#f700001f';
                    } else {
                        $color = '#e9eee6';
                    }
                    
                    ?>
<!--      вариант 1              <div class="row car_in_reserve">


                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="techclass">Вид техники</label>
                                <input type="text" class="form-control" id="tehclass<?= $i ?>"  name="tehclass<?= $i ?>" value="<?= $row['teh_cls'] ?>" disabled="disabled">

                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="tname">Наименование</label>
                                <input type="text" class="form-control" id="tname" placeholder="№" name="tname" value="<?= $row['name_view'] ?>" disabled="disabled">
                            </div>
                        </div>


                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="mark">Марка</label>
                                <textarea class="form-control" id="mark"  name="mark" disabled="disabled"><?= $row['mark'] ?></textarea>
                              <input type="text" class="form-control" id="mark"  name="mark" value=" <? /* $t['mark']*/ ?>" disabled="disabled">
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="numbsign<?= $i ?>">Номерной знак</label>
                                <input type="text" class="form-control" id="numbsign<?= $i ?> "disabled="disabled"  id="numbsign<?= $i ?>" name="numbsign<?= $i ?>"  value="<?= $row['numbsign'] ?>" >
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="petrol<?= $i ?>">Бензин,л</label>
                                <input type="text" class="form-control" placeholder="Бензин, т" id="petrol<?= $i ?>"  id="petrol<?= $i ?>" name="petrol<?= $i ?>"  value="<?= $row['petrol'] ?>" >
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label  for="diesel<?= $i ?>">ДТ, л</label>
                                <input type="text" class="form-control" id="diesel<?= $i ?>" placeholder="ДТ, т" name="diesel<?= $i ?>"  value="<?= $row['diesel'] ?>" >
                            </div>
                        </div>
                    </div>

                    <div class="row car_in_reserve">

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="powder<?= $i ?>">ОП, л</label>
                                <input type="text" class="form-control" id="powder<?= $i ?>" placeholder="Порошок, т" name="powder<?= $i ?>"  value="<?= $row['powder'] ?>" >
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label  for="foam<?= $i ?>">ПО, л</label>
                                <input type="text" class="form-control" id="foam<?= $i ?>" placeholder="Пенообразователь, т" name="foam<?= $i ?>"  value="<?= $row['foam'] ?>" >
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="v">Объем цистерны, л</label>
                                <input type="text" class="form-control" id="v"  id="v" name="v" value="<?= $row['v'] ?>" disabled="disabled">
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="type">Боевая/резерв</label>
                                <select class="form-control" name="type<?= $i ?>" id="type<?= $i ?>" onchange="getTehType(<?= $i ?>);"  >

                                    <?php
                                    foreach ($type as $ty) {
                                        if ($ty['id'] == $row['id_type']) {
                                            printf("<p><option value='%s' selected><label>%s</label></option></p>", $ty['id'], $ty['name']);
                                        } else {
                                            printf("<p><option value='%s' ><label>%s</label></option></p>", $ty['id'], $ty['name']);
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label  for="to<?= $i ?>">ТО</label>
                                <select class="form-control" name="to<?= $i ?>" id="to<?= $i ?>" onchange="getTehTo(<?= $i ?>);">

                                    <?php
                                    foreach ($to as $too) {

                                        if ($too['id'] == $row['id_to']) {
                                            printf("<p><option value='%s' selected><label>%s</label></option></p>", $too['id'], $too['name']);
                                        } else {
                                            printf("<p><option value='%s' ><label>%s</label></option></p>", $too['id'], $too['name']);
                                        }
                                    }
                                    ?>

                                </select>


                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label  for="repair<?= $i ?>">Ремонт</label>
                                <select class="form-control" name="repair<?= $i ?>"  id="repaire<?= $i ?>" onchange="getTehRepaire(<?= $i ?>);">
                                    <?php
                                    if ($row['is_repair'] == 0) {
                                        ?>
                                        <option value='0' selected="">нет</option>
                                        <option value='1'>да</option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value='0' >нет</option>
                                        <option value='1' selected="">да</option>
                                        <?php
                                    }
                                    ?>
                                </select>


                            </div>
                        </div>
                            id car
                        <input type="hidden" class="form-control"   id="idcar<?= $i ?>" name="idcar<?= $i ?>" value="<?= $row['tehstr_id'] ?>">
                    </div>

                    <div class="row car_in_reserve">
                        <?php
                        /**  Кто заступал прошлый раз  * */
                        if ($dateduty != $today) {
                            if (isset($last_fio_on_car) && !empty($last_fio_on_car)) {
                                ?>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label  for="fio<?= $i ?>[]"><u>Заступали: </u> </label>
                                        <?php
                                        foreach ($last_fio_on_car as $value) {
                                            if ($row['tehstr_id'] == $value['tehstr_id']) {
                                                echo $value['fio'] . '<br>';
                                            }
                                        }
                                        ?>


                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>

                        Классификатор ФИО
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label  for="fio<?= $i ?>[]">Ф.И.О.л/с отделения </label>
                                <select class="form-control chosen-select-deselect" name="fio<?= $i ?>[]" id="fio<?= $i ?>" multiple tabindex="4" data-placeholder="Добавить" >

                                    <option></option>
                                    <?php
                                    if (isset($present_car_fio) && !empty($present_car_fio)) {
                                        foreach ($present_car_fio as $present) {
                                            if (isset($last_fio_on_car) && !empty($last_fio_on_car)) {
                                                $k = 0;
                                                foreach ($last_fio_on_car as $value) {
                                                    if ($row['tehstr_id'] == $value['tehstr_id'] && $present['id'] == $value['id']) {
                                                        $k++;
                                                    }
                                                }
                                                if ($k != 0)
                                                    printf("<p><option value='%s' selected  ><label>%s</label></option></p>", $present['id'], $present['fio']);
                                                else {
                                                    printf("<p><option value='%s'><label>%s</label></option></p>", $present['id'], $present['fio']);
                                                }
                                            } else {
                                                printf("<p><option value='%s'  ><label>%s</label></option></p>", $present['id'], $present['fio']);
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-2">

                            <label for="comments">Примечание</label>


                            <div class="form-group">
                                <?php
                                if ($row['comments'] != NULL) {
                                    ?>
                                    <textarea class="form-control" id="comments<?= $i ?>"  name="comments<?= $i ?>" ><?= $row['comments'] ?></textarea>
                                    <?php
                                } else {
                                    ?>
                                    <textarea class="form-control" id="comments<?= $i ?>"  name="comments<?= $i ?>" ></textarea>
                                    <?php
                                }
                                ?>

                                                                                                                                                  <input type="text" class="form-control" id="mark"  name="mark" value=" <? /*$t['mark']*/ ?>" disabled="disabled">
                            </div>
                        </div>

                    </div> вариант 1 -->




   <!---------------------------------------- отображаем постоянно ----------------------------------------->
                    <div class="row" style="background-color: <?= $color ?>;" >
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="numbsign<?= $i ?>"></label>
                                <p style="background-color: <?= $color ?>; cursor: pointer"  data-toggle="collapse" data-target="#collapse<?= $i ?>" data-toggle="tooltip" data-placement="left" title="Из др.подразделения" style="text-transform: uppercase"><i class="fa fa-plus" aria-hidden="true"></i> <b ><i><?= $row['mark'] ?></i></b></p>
            <!--                                    <button type="button"  id="collapseButtonCar<?= $i ?>" class="btn" style="background-color: <?= $color ?>;"  name="send" data-toggle="collapse" data-target="#collapse<?= $i ?>"><i class="fa fa-plus" aria-hidden="true"></i> <?= $row['mark'] ?></span></button>-->
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="tname">Наименование</label>
                                <input type="text" class="form-control" id="tname" placeholder="№" name="tname" value="<?= $row['name_view'] ?>" disabled="disabled">
                            </div>
                        </div>


                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="numbsign<?= $i ?>">Номерной знак</label>
                                <input type="text" class="form-control" id="numbsign<?= $i ?> "disabled="disabled"  id="numbsign<?= $i ?>" name="numbsign<?= $i ?>"  value="<?= $row['numbsign'] ?>" >
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="techclass">Вид техники</label>
                                <input type="text" class="form-control" id="tehclass<?= $i ?>"  name="tehclass<?= $i ?>" value="<?= $row['teh_cls'] ?>" disabled="disabled">

                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group">
                                <label for="type">Состояние техники</label>
                                <select class="form-control" name="type<?= $i ?>" id="type<?= $i ?>"   >

                                    <?php
                                    if ($row['id_type'] == 1) {//боевая 
                                        $active_type = 1;
                                    } elseif ($row['id_type'] == 2) {//reserve
                                        $active_type = 2;
                                    } elseif ($row['id_to'] == 1) {//to-1 
                                        $active_type = 3;
                                    } elseif ($row['id_to'] == 2) {//to-2
                                        $active_type = 4;
                                    } elseif ($row['is_repair'] == 1) {//repair
                                        $active_type = 5;
                                    }
                                    foreach ($type_teh as $key => $ty) {
                                        if ($active_type == $key) {
                                            printf("<p><option value='%s' selected ><label>%s</label></option></p>", $key, $ty);
                                        } else {
                                            printf("<p><option value='%s' ><label>%s</label></option></p>", $key, $ty);
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!---------------------------------------- КОНЕЦ отображаем постоянно ----------------------------------------->


                    <div style="background-color: <?= $color ?>;"   id="collapse<?= $i ?>" class="panel-collapse collapse">

                        <div class="row">


                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="petrol<?= $i ?>">Бензин,л</label>
                                    <input type="text" class="form-control" placeholder="Бензин, т" id="petrol<?= $i ?>"  id="petrol<?= $i ?>" name="petrol<?= $i ?>"  value="<?= $row['petrol'] ?>" >
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label  for="diesel<?= $i ?>">ДТ, л</label>
                                    <input type="text" class="form-control" id="diesel<?= $i ?>" placeholder="ДТ, т" name="diesel<?= $i ?>"  value="<?= $row['diesel'] ?>" >
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="powder<?= $i ?>">ОП, л</label>
                                    <input type="text" class="form-control" id="powder<?= $i ?>" placeholder="Порошок, т" name="powder<?= $i ?>"  value="<?= $row['powder'] ?>" >
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label  for="foam<?= $i ?>">ПО, л</label>
                                    <input type="text" class="form-control" id="foam<?= $i ?>" placeholder="Пенообразователь, т" name="foam<?= $i ?>"  value="<?= $row['foam'] ?>" >
                                </div>
                            </div>


                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="v">Объем цистерны, л</label>
                                    <input type="text" class="form-control" id="v"  id="v" name="v" value="<?= $row['v'] ?>" disabled="disabled">
                                </div>
                            </div>


                        </div>

                        <div class="row">

                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="reason_repaire">Причина неиспр.</label>
                                    <select class="form-control" name="reason_repaire<?= $i ?>" id="reason_repaire<?= $i ?>"   >

                                        <?php
                                        foreach ($reason_repaire as $re) {

                                            if (isset($row['id_reason_repaire']) && !empty($row['id_reason_repaire'])) {

                                                if ($re['id'] == $row['id_reason_repaire']) {
                                                    printf("<p><option value='%s' selected ><label>%s</label></option></p>", $re['id'], $re['name']);
                                                } else {
                                                    printf("<p><option value='%s' ><label>%s</label></option></p>", $re['id'], $re['name']);
                                                }
                                            } else {

                                                if ($re['id'] == 1) {
                                                    printf("<p><option value='%s' selected ><label>%s</label></option></p>", $re['id'], $re['name']);
                                                } else {
                                                    printf("<p><option value='%s' ><label>%s</label></option></p>", $re['id'], $re['name']);
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-2">

                                <label for="comments">Примечание</label>


                                <div class="form-group">
                                    <?php
                                    if ($row['comments'] != NULL) {
                                        ?>
                                        <textarea class="form-control" id="comments<?= $i ?>"  name="comments<?= $i ?>" ><?= $row['comments'] ?></textarea>
                                        <?php
                                    } else {
                                        ?>
                                        <textarea class="form-control" id="comments<?= $i ?>"  name="comments<?= $i ?>" ></textarea>
                                        <?php
                                    }
                                    ?>

                                                                                                                                                <!--  <input type="text" class="form-control" id="mark"  name="mark" value=" <? /*$t['mark']*/ ?>" disabled="disabled">-->
                                </div>
                            </div>



                            <div class="col-lg-2">
                                <div class="form-group">
                                    <label for="type">Дата неисправности</label>
                                    <div class="input-group date" id="date1<?= $i ?>" >

                                        <?php
                                        if ($row['start_repaire'] == NULL) {
                                            ?>
                                            <input type="text" class="form-control"  name="date1<?= $i ?>"style="width: 125px;"  />
                                            <?php
                                        } else {
                                            $dat1 = new DateTime($row['start_repaire']);
                                            $d1 = $dat1->Format('d-m-Y');
                                            ?>
                                            <input type="text" class="form-control"  name="date1<?= $i ?>"style="width: 125px;"  value="<?= $d1 ?>" />

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
                                    <label for="type">Дата устранения</label>
                                    <div class="input-group date" id="date2<?= $i ?>">
                                        <?php
                                        if ($row['end_repaire'] == NULL) {
                                            ?>
                                            <input type="text" class="form-control" name="date2<?= $i ?>" style="width: 125px;" />
                                            <?php
                                        } else {
                                            $dat2 = new DateTime($row['end_repaire']);
                                            $d2 = $dat2->Format('d-m-Y');
                                            ?>
                                            <input type="text" class="form-control" name="date2<?= $i ?>" style="width: 125px;"  value="<?= $d2 ?>" />


                                            <?php
                                        }
                                        ?>

                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>



                            <input type="hidden" class="form-control"   id="idcar<?= $i ?>" name="idcar<?= $i ?>" value="<?= $row['tehstr_id'] ?>">
                        </div>

                        <div class="row">

                            <!--Классификатор ФИО-->
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label  for="fio<?= $i ?>[]">Ф.И.О.л/с отделения


                                        <?php
                                        /**  Кто заступал прошлый раз  * */
                                        if ($dateduty != $today) {
                                            if (isset($last_fio_on_car) && !empty($last_fio_on_car)) {
                                                 $x_array=array();
                                                foreach ($last_fio_on_car as $x) {
                                                   $x_array[]=$x['tehstr_id']; 
                                                }
                                                if(in_array($row['tehstr_id'], $x_array)){//отображаем колокольчик, если прошлый раз кто-то заступал на эту машину
                                                ?>


                                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right" 

                                                            title="Заступали прошлый раз: <?php
                                                            foreach ($last_fio_on_car as $value) {
                                                                if ($row['tehstr_id'] == $value['tehstr_id']) {
                                                                    echo $value['fio'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . '), ';
                                                                }
                                                            }
                                                            ?> ">

                                                </i>

                                                <?php
                                                }
                                            }
                                        }
                                        ?>

                                    </label>
            <!--                                    <select class="form-control chosen-select-deselect" name="fio<?= $i ?>[]" id="fio<?= $i ?>" multiple tabindex="4" data-placeholder="Добавить" >-->
                                    <select class="form-control js-example-basic-multiple" name="fio<?= $i ?>[]" id="fio<?= $i ?>" multiple tabindex="4" data-placeholder="Добавить" >
                                        <option></option>
                                        <?php
                                        if (isset($present_car_fio) && !empty($present_car_fio)) {
                                            foreach ($present_car_fio as $present) {
                                                if (isset($last_fio_on_car) && !empty($last_fio_on_car)) {
                                                    $k = 0;
                                                    foreach ($last_fio_on_car as $value) {
                                                        if ($row['tehstr_id'] == $value['tehstr_id'] && $present['id'] == $value['id']) {
                                                            $k++;
                                                        }
                                                    }
                                                    if ($k != 0)
                                                        printf("<p ><option value='%s' selected ><label >%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                                    else {
                                                        printf("<p><option value='%s'><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                                    }
                                                } else {
                                                    printf("<p><option value='%s'  ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>




<div class="col-lg-2">
                            <div class="form-group">
                                <label for="tname">Мин.б.р.</label>
                                <input type="text" class="form-control" id="tcalc" name="tcalc" value="<?= $row['calc'] ?>" disabled="disabled">
                            </div>
                        </div>




                        </div>
                    </div>


                    <!--                Техника доступна для редактирования и сохранения в БД в отличие от той, которая просто отображается на экран(командировка, резерв)-->
                    <input type="hidden" value="1" name="is_save">
                    <hr>
                    <?php
                }// end foreach car
            }
            // echo count($last_fio_on_car);
            ?>
            <!----------------------------------------------------------------------------- Конец техника из др ПАСЧ------------------------------------------------------------------------------->
            
            <input type="hidden" class="form-control"   id="countcar" name="countcar" value="<?= $i ?>">

            <?php
            if (isset($post) && ($post == 0)) {//put
                ?>
                <input type="hidden" name="_METHOD" value="PUT"/>

                <?php
            }
            ?>
            <center>
                <div class="row">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </div>
                </div>
            </center>


            <?php
            if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm == 1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
                ?>
            </fieldset>
            <?php
        }
        ?>
    </form>

    <?php
}


if (empty($own_car) && empty($car_in_reserve)) {
    include 'cou/is_car_form.php'; //для ЦОУ, если нет техники для заступления - поставить отметку и сохранить
}
?>