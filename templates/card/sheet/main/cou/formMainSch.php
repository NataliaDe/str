<?php
//какую дату писать в дате заступления. если смена сег должна заступить - сегодня
$is_btn_confirm = isset($is_btn_confirm) ? $is_btn_confirm : 0;
// получаем текущее время в сек. и вычитаем кол-во секунд в 1 сутках
$my_time = time() - 86400;
// переменная для вывода даты в поток
$yesterday = date("d-m-Y", $my_time);
$today = date("Y-m-d");
$today_for_calendar = date("d-m-Y");

/* ------------  END заступАЛИ прошлый раз ------------- */

//заступАЛИ из др подразделения
if (empty($past_reserve_fio)) {
    $p_r_fio = array();
    $count_past_reserve_fio = 0; //кол-во из др пасч
} else {
    foreach ($past_reserve_fio as $key => $value) {
        $p_r_fio[] = $value['id'];
    }
    $count_past_reserve_fio = count($p_r_fio); //кол-во из др пасч
}


//заступАЛИ ежедневники
if (empty($past_everyday_fio)) {
    $p_e_fio = array();
    $count_everyday = 0; //кол-во ежедневников
} else {
     $p_e_fio_count=array();
    foreach ($past_everyday_fio as $key => $value) {
        $p_e_fio[] = $value['id'];
          if($value['is_nobody']==0){
            $p_e_fio_count[]=$value['id'];
        }
    }
    $count_everyday = count($p_e_fio_count); //кол-во ежедневников
}


//заступАЛ начальник смены
if (empty($past_z_head_fio)) {
    $p_z_h_fio = array();
} else {
    foreach ($past_z_head_fio as $key => $value) {
        $p_z_h_fio[] = $value['id'];
    }
}


//ст помощник нач-ка ШЛЧС
if (empty($past_st_pom_fio)) {
    $p_st_pom_fio = array();
} else {
    foreach ($past_st_pom_fio as $key => $value) {
        $p_st_pom_fio[] = $value['id'];
    }
}


// помощник нач-ка ШЛЧС
if (empty($past_pom_fio)) {
    $p_pom_fio = array();
} else {
    foreach ($past_pom_fio as $key => $value) {
        $p_pom_fio[] = $value['id'];
    }
}


//стажер
if (empty($past_trainee_fio)) {
    $p_trainee_fio = array();
} else {
    foreach ($past_trainee_fio as $key => $value) {
        $p_trainee_fio[] = $value['id'];
    }
}

// driver
if (empty($past_driver_fio)) {
    $p_driver_fio = array();
} else {
    foreach ($past_driver_fio as $key => $value) {
        $p_driver_fio[] = $value['id'];
    }
}




//другие
if (empty($past_others_fio)) {
    $p_others_fio = array();
} else {
    foreach ($past_others_fio as $key => $value) {
        $p_others_fio[] = $value['id'];
    }
}


/* ------------  END заступАЛИ прошлый раз ------------- */


/* ------------ должности связь 1 к 1 ----------------- */
$z_head_sch = 0;
$st_pom_sch = 0;
$pom_sch = 0;
$trainee_sch = 0;
$driver_sch  = 0;
$trainee = 0;

/* ------------ КОНЕЦ должности связь 1 к 1 ----------------- */

if (isset($main) && !empty($main)) {

    $count_all = array(); //всего выбрано на должностях - id of fio


    foreach ($main as $row) {

        $dateduty = $row['dateduty'];

        $count_all[] = $row['id_fio'];

        if ($row['id_pos_duty'] == 15)
            $z_head_sch = $row['id_fio'];

        elseif ($row['id_pos_duty'] == 16)
            $st_pom_sch = $row['id_fio'];

        elseif ($row['id_pos_duty'] == 17)
            $pom_sch = $row['id_fio'];

        elseif ($row['id_pos_duty'] == 18)
            $trainee_sch = $row['id_fio'];

        elseif ($row['id_pos_duty'] == 20)
            $driver_sch = $row['id_fio'];


        elseif ($row['id_pos_duty'] == 19)
            $others_sch[] = $row['id_fio'];

        //$is_open_update = $row['open_update']; //доступ на ред.дежурной смены
    }

    $count_all_unique = array_unique($count_all);
    $count_all = count($count_all_unique);

    $date_d = new DateTime($dateduty);
    $dateduty_for_calendar = $date_d->Format('d-m-Y');
} else {
    $dateduty = 0;
    $dateduty_for_calendar = 0;
    $vacant = 0;
}

//print_r($everyday_duty_fio);
//print_r($main);
//echo $idmain;
//echo $_SESSION['can_edit'];
//echo $is_btn_confirm;
//echo $duty;
//echo $is_open_update;
//echo $dateduty;
?>

<form  role="form" id="formFillMain" method="POST" action="/str/v2/card/<?= $record_id ?>/ch/<?= $change ?>/main_sch">

    <?php
//смена деж и доступ на редактирование закрыт
    if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) && ($dateduty==$today)) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($_SESSION['can_edit'] == 0)) {
        ?>
        <fieldset disabled>
            <?php
        }
        ?>




        <div class="row">

            <div class="col-lg-3">
                <div class="form-group">
                    <label for="date_start" >Дата заступления</label>
                    <div class="input-group input-append date" id="dateduty" >

                        <?php
                        if (isset($dateduty) && $dateduty != 0) {
                            if ($is_btn_confirm == 1) {
                                ?>
                                <input type="text" class="form-control" id="dd" name="dateduty" value="<?= $today_for_calendar ?>"/>
                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                <?php
                            } else {
                                if ($is_open_update == 1) {//доступ открыт на ред
                                    ?>
                                    <input type="hidden" class="form-control" id="dd" name="dateduty" value="<?= $dateduty_for_calendar ?>" />
                                    <input type="text" class="form-control" id="dd" name="dateduty_view" value="<?= $dateduty_for_calendar ?>" disabled=""/>
                                    <?php
                                } else {
                                    ?>
                                    <input type="text" class="form-control" id="dd" name="dateduty" value="<?= $dateduty_for_calendar ?>"/>
                                    <?php
                                }
                                ?>

                                <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                                <?php
                            }
                            ?>

                            <?php
                        } else {
                            ?>
                            <input type="text" class="form-control" id="dd" name="dateduty" value="<?= $today_for_calendar ?>" />
                            <span class="input-group-addon add-on"><span class="glyphicon glyphicon-calendar"></span></span>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </div>

        </div>




        <div class="row">




            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="z_head_sch">Зам.начальника ШЛЧС

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {
                            //кто заступал начальником смены прошлый раз
                            // на сегодня начальник смены доступен в списке или нет.если нет - вывод

                            if (isset($past_z_head_fio) && !empty($past_z_head_fio)) {
                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступал прошлый раз: <?php
                                            foreach ($past_z_head_fio as $value) {
                                                echo $value['fio'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . '), ';
                                            }
                                            ?> ">

                                </i>

                                <?php
                            }
                        }
                        /* -----------------  КОНЕЦ Заступали прошлый раз ----------------- */
                        ?>

                    </label>
                    <select class=" chosen-select-deselect form-control " name="z_head_sch"  tabindex="2" data-placeholder="Выбрать"  >
                        <option ></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_z_h_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($z_head_sch) && ($z_head_sch == $present['id'])) {
                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } else {
                                printf("<p><option value='%s' ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            }
                        }
                        ?>

                    </select>
                </div>
            </div>



            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="st_pom_sch">Ст.помощник начальника ШЛЧС

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {
                            //кто заступал начальником смены прошлый раз
                            // на сегодня начальник смены доступен в списке или нет.если нет - вывод

                            if (isset($past_st_pom_fio) && !empty($past_st_pom_fio)) {
                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступал прошлый раз: <?php
                                            foreach ($past_st_pom_fio as $value) {
                                                echo $value['fio'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . '), ';
                                            }
                                            ?> ">

                                </i>

                                <?php
                            }
                        }
                        /* -----------------  КОНЕЦ Заступали прошлый раз ----------------- */
                        ?>

                    </label>
                    <select class=" chosen-select-deselect form-control " name="st_pom_sch"  tabindex="2" data-placeholder="Выбрать"  >
                        <option ></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_st_pom_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($st_pom_sch) && ($st_pom_sch == $present['id'])) {
                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } else {
                                printf("<p><option value='%s' ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            }
                        }
                        ?>

                    </select>
                </div>
            </div>






            <div class="col-lg-1">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="">больн.</label>
                    <input type="text" class="form-control"  placeholder="0" value="<?= $count_ill ?>" disabled="" id="on_ill">
                </div>
            </div>


            <div class="col-lg-1">
                <div class="form-group">
                    <label class="control-label col-lg-12" for="face">отп.</label>
                    <input type="text" class="form-control"  placeholder="0" value="<?= $count_holiday ?>" disabled="" id="on_holiday">
                </div>
            </div>



            <div class="col-lg-1">
                <div class="form-group">
                    <label class="control-label col-lg-12" for="face">еж.</label>
                    <input type="text" class="form-control" style="background-color:  #d4e062 !important;" placeholder="0" value="<?= $count_everyday ?>" disabled="" id="on_every">
                </div>
            </div>




        </div>


        <div class="row">

            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="pom_sch">Помощник начальника ШЛЧС

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {
                            //кто заступал начальником смены прошлый раз
                            // на сегодня начальник смены доступен в списке или нет.если нет - вывод

                            if (isset($past_pom_fio) && !empty($past_pom_fio)) {
                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступал прошлый раз: <?php
                                            foreach ($past_pom_fio as $value) {
                                                echo $value['fio'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . '), ';
                                            }
                                            ?> ">

                                </i>

                                <?php
                            }
                        }
                        /* -----------------  КОНЕЦ Заступали прошлый раз ----------------- */
                        ?>

                    </label>
                    <select class=" chosen-select-deselect form-control " name="pom_sch"  tabindex="2" data-placeholder="Выбрать"  >
                        <option ></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_pom_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($pom_sch) && ($pom_sch == $present['id'])) {
                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } else {
                                printf("<p><option value='%s' ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            }
                        }
                        ?>

                    </select>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="trainee_sch">Стажер

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {
                            //кто заступал начальником смены прошлый раз
                            // на сегодня начальник смены доступен в списке или нет.если нет - вывод

                            if (isset($past_trainee_fio) && !empty($past_trainee_fio)) {
                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступал прошлый раз: <?php
                                            foreach ($past_trainee_fio as $value) {
                                                echo $value['fio'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . '), ';
                                            }
                                            ?> ">

                                </i>

                                <?php
                            }
                        }
                        /* -----------------  КОНЕЦ Заступали прошлый раз ----------------- */
                        ?>

                    </label>
                    <select class=" chosen-select-deselect form-control " name="trainee_sch"  tabindex="2" data-placeholder="Выбрать"  >
                        <option ></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_trainee_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($trainee_sch) && ($trainee_sch == $present['id'])) {
                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } else {
                                printf("<p><option value='%s' ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            }
                        }
                        ?>

                    </select>
                </div>
            </div>


            <div class="col-lg-1">
                <div class="form-group">
                    <label class="control-label col-lg-12" for="face">ком.</label>
                    <input type="text" class="form-control"  placeholder="0" value="<?= $count_trip ?>" disabled="" id="on_holiday">
                </div>
            </div>


            <div class="col-lg-1">
                <div class="form-group">
                    <label class="control-label col-lg-12" for="face">др.прич.</label>
                    <input type="text" class="form-control"  placeholder="0" value="<?= $count_other ?>" disabled="" id="on_holiday">
                </div>
            </div>

            <div class="col-lg-1">
                <div class="form-group">
                    <label class="control-label col-lg-12" for="">др.подр.</label>
                    <input type="text" class="form-control" style="background-color:  #d4e062 !important;" placeholder="0" value="<?= $count_past_reserve_fio ?>" disabled="" id="on_reserve">
                </div>
            </div>


        </div>


        <div class="row">

            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="driver_sch">Водитель ШЛЧС

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {
                            //кто заступал начальником смены прошлый раз
                            // на сегодня начальник смены доступен в списке или нет.если нет - вывод

                            if (isset($past_driver_fio) && !empty($past_driver_fio)) {
                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступал прошлый раз <?= count($past_driver_fio)?> чел: <?php
                                            foreach ($past_driver_fio as $value) {
                                                echo $value['fio'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . '), ';
                                            }
                                            ?> ">

                                </i>

                                <?php
                            }
                        }
                        /* -----------------  КОНЕЦ Заступали прошлый раз ----------------- */
                        ?>

                    </label>
                    <select class=" chosen-select-deselect form-control " name="driver_sch"  tabindex="2" data-placeholder="Выбрать"  >
                        <option ></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_driver_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($driver_sch) && ($driver_sch == $present['id'])) {
                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } else {
                                printf("<p><option value='%s' ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            }
                        }
                        ?>

                    </select>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="others_sch">Другие


                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {

                            if (isset($past_others_fio) && !empty($past_others_fio)) {
                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз <?= count($past_others_fio)?> чел: <?php
                                            foreach ($past_others_fio as $value) {
                                                echo $value['fio'] . ' (' . mb_strtolower($value['slug']) . '), ';
                                            }
                                            ?> ">

                                </i>

                                <?php
                            }
                        }
                        /* -----------------  КОНЕЦ Заступали прошлый раз ----------------- */
                        ?>

                    </label>
                    <select class=" chosen-select-deselect form-control " name="others_sch[]" multiple tabindex="4" data-placeholder="Выбрать"  >
                        <option ></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_others_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($others_sch) && !empty($others_sch) && in_array($present['id'], $others_sch)) {
                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } else {
                                printf("<p><option value='%s' ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            }
                        }
                        ?>

                    </select>
                </div>
            </div>

            <div class="col-lg-2">
                <div class="form-group">
                    <label class="control-label col-lg-12" for="face">всего
                    <span class="glyphicon glyphicon-check" style="color: green;" data-toggle="tooltip" data-placement="top" title="Соответствует количеству работников, заступивших на должности (расставлены по полям). Кроме инспектора ОНиП и ответств.по гарнизону"></span>
                    </label>
                    <input type="text" class="form-control"  placeholder="0" value="<?= $count_all ?>" disabled="" id="on_holiday">
                </div>
            </div>

            <div class="col-lg-1">
                <div class="form-group">
                    <label class="control-label col-lg-12" for="face">б/р</label>
                    <input type="text" class="form-control"  placeholder="0" value="<?= $count_fio_on_car ?>" disabled="" >
                </div>
            </div>


        </div>




        <div class="row">
            <hr>

            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="everydayfio[]">Заступают ежедневники

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {
                            //вывод ежедневников этого ПАСЧ, кто заступал  прошлый раз
                            if (isset($past_everyday_fio) && !empty($past_everyday_fio)) {

                                $cnt_every=0;
                                 foreach ($past_everyday_fio as $value) {
                                              $cnt_every++;
                                            }
                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз <?= $cnt_every?> чел: <?php
                                            foreach ($past_everyday_fio as $value) {
                                              $cnt_every++;
                                            }
                                            ?> ">

                                </i>

                                <?php
                            }
                        }
                        /* -----------------  КОНЕЦ Заступали прошлый раз ----------------- */


                        ?>

                    </label>
                    <select class=" chosen-select-deselect form-control " name="everydayfio[]"  multiple tabindex="4" data-placeholder="Добавить" >
                        <option ></option>
                        <?php
                        foreach ($present_everyday_fio as $present) {
                            if (in_array($present['id'], $p_e_fio) && ($dateduty == $today)) {
                                    printf("<p><option value='%s' selected ><label>%s (%s)</label></option></p>", $present['id'], $present['fio'], mb_strtolower($present['slug']));
                            } else {
                                printf("<p><option value='%s'  ><label>%s (%s)</label></option></p>", $present['id'], $present['fio'], mb_strtolower($present['slug']));
                            }
                        }
                        ?>

                    </select>
                </div>
            </div>



            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="reserve[]">Заступают из др.подр.(смены)


                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {
                            //вывод тех, кто заступал из др пасч прошлый раз
                            if (isset($past_reserve_fio) && !empty($past_reserve_fio)) {

                                $cnt_other=0;
                                 foreach ($past_reserve_fio as $value) {
                                               $cnt_other++;
                                            }
                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз <?= $cnt_other?> чел: <?php
                                            foreach ($past_reserve_fio as $value) {
                                                echo $value['fio'] . ' ' . $value['is_every'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . '), ';
                                            }
                                            ?> ">

                                </i>

                                <?php
                            }
                        }
                        /* -----------------  КОНЕЦ Заступали прошлый раз ----------------- */
                        ?>

                    </label>

                    <select class=" chosen-select-deselect form-control " name="reserve[]" multiple tabindex="4" data-placeholder="Добавить">
                        <option ></option>
                        <?php

                        foreach ($present_reserve_fio as $present) {
                            if (in_array($present['id'], $p_r_fio) && ($dateduty == $today)) {
                                //если работник др ПАСЧ выбран как начальник смены-его нельзя удалить, пока он не будет снят
                                if (isset($id_fio) && $id_fio == $present['id']) {
                                    printf("<p><option value='%s' selected disabled><label>%s %s %s %s  (%s)</label></option></p>", $present['id'], $present['fio'], $present['is_every'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                    //ежедневник, который выбран как начальник смены
                                    $reserve_add = $present['id'];
                                } else
                                    printf("<p><option value='%s' selected ><label>%s %s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['is_every'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } else {
                                printf("<p><option value='%s'  ><label>%s %s %s %s  (%s)</label></option></p>", $present['id'], $present['fio'], $present['is_every'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            }
                        }
                        ?>

                    </select>
                </div>
            </div>

        </div>


        <div class="col-lg-12 col-lg-offset-1">
            <div class="row">

                <div class="form-group">

                    <div class="col-sm-offset-4 col-sm-10 col-md-offset-4 col-lg-offset-2">
                        <button type="submit" class="btn btn-primary" id="save_main">Сохранить изменения</button>
                        <br>    <br>
                    </div>
                </div>
            </div>
        </div>

        <?php
//смена деж и доступ на редактирование закрыт
        if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0)) || ($_SESSION['can_edit'] == 0)) {
            ?>
        </fieldset>
        <?php
    }
    ?>

</form>



