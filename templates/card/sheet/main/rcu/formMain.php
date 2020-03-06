<!--form cou umchs without g. Minsk-->
<style>
    .close-open-a{
        float: right;
    }
</style>
<?php
//print_r($present_head_fio);
//какую дату писать в дате заступления. если смена сег должна заступить - сегодня
$is_btn_confirm = isset($is_btn_confirm) ? $is_btn_confirm : 0;
// получаем текущее время в сек. и вычитаем кол-во секунд в 1 сутках
$my_time = time() - 86400;
// переменная для вывода даты в поток
$yesterday = date("d-m-Y", $my_time);
$today = date("Y-m-d");
$today_for_calendar = date("d-m-Y");
$time_now = date("H:i:s");


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
    $p_e_fio_count = array();
    foreach ($past_everyday_fio as $key => $value) {
        $p_e_fio[] = $value['id'];
        if ($value['is_nobody'] == 0) {
            $p_e_fio_count[] = $value['id'];
        }
    }
    $count_everyday = count($p_e_fio_count); //кол-во ежедневников
}





//god
if (empty($past_god_fio)) {
    $p_god_fio = array();
} else {
    foreach ($past_god_fio as $key => $value) {
        $p_god_fio[] = $value['id'];
    }
}

//od
if (empty($past_od_fio)) {
    $p_od_fio = array();
} else {
    foreach ($past_od_fio as $key => $value) {
        $p_od_fio[] = $value['id'];
    }
}


// zam od
if (empty($past_z_od_fio)) {
    $p_z_od_fio = array();
} else {
    foreach ($past_z_od_fio as $key => $value) {
        $p_z_od_fio[] = $value['id'];
    }
}



// st pom od
if (empty($past_st_pom_od_fio)) {
    $p_st_pom_od_fio = array();
} else {
    foreach ($past_st_pom_od_fio as $key => $value) {
        $p_st_pom_od_fio[] = $value['id'];
    }
}


// inspector
if (empty($past_insp_fio)) {
    $p_insp_fio = array();
} else {
    foreach ($past_insp_fio as $key => $value) {
        $p_insp_fio[] = $value['id'];
    }
}


// other
if (empty($past_other_fio)) {
    $p_other_fio = array();
} else {
    foreach ($past_other_fio as $key => $value) {
        $p_other_fio[] = $value['id'];
    }
}

//otsio
if (empty($past_st_ing_otsio_fio)) {
    $p_st_ing_otsio_fio = array();
} else {
    foreach ($past_st_ing_otsio_fio as $key => $value) {
        $p_st_ing_otsio_fio[] = $value['id'];
    }
}

// connect ing
if (empty($past_ing_connect_fio)) {
    $p_ing_connect_fio = array();
} else {
    foreach ($past_ing_connect_fio as $key => $value) {
        $p_ing_connect_fio[] = $value['id'];
    }
}


// psych
if (empty($past_psych_fio)) {
    $p_psych_fio = array();
} else {
    foreach ($past_psych_fio as $key => $value) {
        $p_psych_fio[] = $value['id'];
    }
}

// monitoring
if (empty($past_monitoring_fio)) {
    $p_monitoring_fio = array();
} else {
    foreach ($past_monitoring_fio as $key => $value) {
        $p_monitoring_fio[] = $value['id'];
    }
}


/* ------------  END заступАЛИ прошлый раз ------------- */


/* ------------ должности связь 1 к 1 ----------------- */
$head_ch = 0;

$zam_head_sch = 0;
$st_pom_sch = 0;
$head_sch_fio = 0;
$trainee = 0;
$inspector_inip = 0;
$garnison = 0;
/* ------------ КОНЕЦ должности связь 1 к 1 ----------------- */

if (isset($main) && !empty($main)) {

    $count_all = array(); //all select on position - id of fio
    //print_r($main);
    foreach ($main as $row) {
$id_card=$row['id_card'];
        $dateduty = $row['dateduty'];

        if (!empty($row['id_fio']) && $row['id_fio'] != 8) {
        $count_all[] = $row['id_fio'];
         }


        if ($row['id_pos_duty'] == 1)
            $god[] = $row['id_fio'];

        elseif ($row['id_pos_duty'] == 2)
            $od[] = $row['id_fio'];
        elseif ($row['id_pos_duty'] == 3)
            $z_od[] = $row['id_fio'];
        elseif ($row['id_pos_duty'] == 4)
            $st_pom_od[] = $row['id_fio'];
        elseif ($row['id_pos_duty'] == 6)
            $insp[] = $row['id_fio'];
        elseif ($row['id_pos_duty'] == 8)
            $other[] = $row['id_fio'];
        elseif ($row['id_pos_duty'] == 10)
            $st_ing_otsio[] = $row['id_fio'];
        elseif ($row['id_pos_duty'] == 11)
            $ing_connect[] = $row['id_fio'];
                elseif ($row['id_pos_duty'] == 12)
            $psych[] = $row['id_fio'];
                                elseif ($row['id_pos_duty'] == 13)
            $monitoring[] = $row['id_fio'];




        //$is_open_update = $row['open_update']; //доступ на ред.дежурной смены
    }

    $count_all_unique = array_unique($count_all);
    $count_all = count($count_all_unique);

    $date_d = new DateTime($dateduty);
    $dateduty_for_calendar = $date_d->Format('d-m-Y');
} else {
    $count_all = 0;
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

if((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 1) && ($dateduty == $today) ) && (date('Y-m-d', strtotime($row['dateduty'])) == $today && $time_now <$time_allow_open)){
?>

<a href="/str/v3/card/close_update/<?=$id_card?>" class="close-open-a"><button type="button" class="btn btn-xs btn-success"  data-toggle="tooltip" data-placement="bottom" title="Закрыть доступ на редактирование смены"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button></a>
<?php
}
elseif((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) && ($dateduty == $today) )){
    ?>

<a href="/str/v3/card/open_update/<?=$id_card?>" class="close-open-a"><button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="bottom" title="Открыть доступ на редактирование смены" ><span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span></button></a>
<?php
}
?>

<form  role="form" id="formFillMain" method="POST" action="/str/v3/card/<?= $record_id ?>/ch/<?= $change ?>/main">

    <?php
//смена деж и доступ на редактирование закрыт
    if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) && ($dateduty == $today) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($_SESSION['can_edit'] == 0)) {

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


            <div class="col-lg-3"></div>




            <div class="col-lg-2">
                <div class="form-group">
                    <label class="control-label col-lg-12" for="face">штат
                        <span class="glyphicon glyphicon-check" style="color: green;" data-toggle="tooltip" data-placement="top" title="Соответствует количеству всего л/с вместе с ежедневниками и с вакантами (все смены), введенными в списке смен"></span>
                    </label>
                    <input type="text" class="form-control"  placeholder="0" value="<?= $count_shtat ?>" disabled="" >
                </div>
            </div>




        </div>

            <input type="hidden" id="all_selected_fio1">
            <input type="hidden" id="all_selected_fio2">
            <input type="hidden" id="all_selected_fio3">
            <input type="hidden" id="all_selected_fio4">
            <input type="hidden" id="all_selected_fio5">
            <input type="hidden" id="all_selected_fio6">
            <input type="hidden" id="all_selected_fio7">
            <input type="hidden" id="all_selected_fio8">
            <input type="hidden" id="all_selected_fio9">

        <div class="row">

            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="od">ГОД

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {
                            //кто заступал начальником смены прошлый раз
                            // на сегодня начальник смены доступен в списке или нет.если нет - вывод

                            if (isset($past_god_fio) && !empty($past_god_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступал прошлый раз  <?= count($past_god_fio) ?> чел: <?php
                                            foreach ($past_god_fio as $value) {
                                                echo $value['fio'] . ' '.  ' (' . mb_strtolower($value['slug']) . '), ';
                                            }

                                            ?> ">

                                </i>

                                <?php
                            }
                        }
                        /* -----------------  КОНЕЦ Заступали прошлый раз ----------------- */

                        ?>

                    </label>
                    <select class=" chosen-select-deselect form-control b2 " name="god[]" tabindex="4" multiple data-placeholder="Выбрать" id="fio1" onchange="changeFio(1);">
<!--                        <option></option>-->
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_god_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($god) && !empty($god) && in_array($present['id'], $god) ) {
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
                    <label class="control-label  col-lg-12" for="od">ОД

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {

                            if (isset($past_od_fio) && !empty($past_od_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз <?= count($past_od_fio) ?> чел: <?php
                                            foreach ($past_od_fio as $value) {
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
                    <select class=" chosen-select-deselect form-control b2" name="od[]" id="fio2" multiple tabindex="4" data-placeholder="Выбрать"  onchange="changeFio(2);">
                        <option></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_od_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($od) && !empty($od) && in_array($present['id'], $od)) {
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
                        <span class="glyphicon glyphicon-check" style="color: green;" data-toggle="tooltip" data-placement="top" title="Соответствует количеству работников, заступивших на должности (расставлены по полям). Кроме другие"></span>
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





            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="z_od">Заместитель ОД

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {

                            if (isset($past_z_od_fio) && !empty($past_z_od_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз <?= count($past_z_od_fio) ?> чел: <?php
                                            foreach ($past_z_od_fio as $value) {
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
                    <select class=" chosen-select-deselect form-control b2" name="z_od[]" multiple  tabindex="4" data-placeholder="Выбрать"  id="fio3" onchange="changeFio(3);">
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_z_od_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($z_od) && !empty($z_od) && in_array($present['id'], $z_od)) {
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
                    <label class="control-label  col-lg-12" for="st_pom_od">Старший помощник ОД


                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {

                            if (isset($past_st_pom_od_fio) && !empty($past_st_pom_od_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз <?= count($past_st_pom_od_fio) ?> чел: <?php
                                            foreach ($past_st_pom_od_fio as $value) {
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
                    <select class=" chosen-select-deselect form-control b2" name="st_pom_od[]" multiple="" tabindex="4" data-placeholder="Выбрать" id="fio4" onchange="changeFio(4);" >

                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_st_pom_od_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($st_pom_od) && !empty($st_pom_od) && in_array($present['id'], $st_pom_od)) {
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




        </div>



        <div class="row">


            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="otsio">Инженеры ОТСиО

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {
                            //кто заступал начальником смены прошлый раз
                            // на сегодня начальник смены доступен в списке или нет.если нет - вывод

                            if (isset($past_st_ing_otsio_fio) && !empty($past_st_ing_otsio_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступал прошлый раз: <?php
                                            foreach ($past_st_ing_otsio_fio as $value) {
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
                    <select class=" chosen-select-deselect form-control b2" name="st_ing_otsio[]" multiple=""  tabindex="4" data-placeholder="Выбрать" id="fio5" onchange="changeFio(5);">

                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_st_ing_otsio_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($st_ing_otsio) && !empty ($st_ing_otsio) &&  in_array($present['id'], $st_ing_otsio)) {
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
                    <label class="control-label  col-lg-12" for="ing_connect">Инженеры связи

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {

                            if (isset($past_ing_connect_fio) && !empty($past_ing_connect_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз <?= count($past_ing_connect_fio) ?> чел: <?php
                                            foreach ($past_ing_connect_fio as $value) {
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
                    <select class=" chosen-select-deselect form-control b2" name="ing_connect[]" multiple tabindex="4" data-placeholder="Выбрать" id="fio6" onchange="changeFio(6);" >
                        <option ></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_ing_connect_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($ing_connect) && !empty($ing_connect) && in_array($present['id'], $ing_connect)) {
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


        </div>

        <div class="row">
<!--            <div class="col-lg-3">

                <div class="form-group">
                    <label class="control-label  col-lg-12" for="other">Другие

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                       // if (isset($dateduty) && ($dateduty != $today)) {

                           // if (isset($past_other_fio) && !empty($past_other_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз  чел: <?php
//                                            foreach ($past_other_fio as $value) {
//                                                echo $value['fio'] . ' (' . mb_strtolower($value['slug']) . '), ';
//                                            }

                                            ?> ">

                                </i>

                                <?php
                            //}
                        //}
                        /* -----------------  КОНЕЦ Заступали прошлый раз ----------------- */

                        ?>

                    </label>
                    <select class=" chosen-select-deselect form-control b2" name="other[]" multiple tabindex="4" data-placeholder="Выбрать" id="fio7" onchange="changeFio(7);" >
                        <option ></option>
                        <?php
//                        foreach ($present_head_fio as $present) {
//                            if (in_array($present['id'], $p_other_fio) && ($dateduty != $today)) {
//
//                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
//                            } elseif (isset($other) && !empty($other) && in_array($present['id'], $other)) {
//                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
//                            } else {
//                                printf("<p><option value='%s' ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
//                            }
//                        }

                        ?>

                    </select>
                </div>
            </div>-->


            <div class="col-lg-3">

                <div class="form-group">
                    <label class="control-label  col-lg-12" for="other">Психологи

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {

                            if (isset($past_psych_fio) && !empty($past_psych_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз <?= count($past_psych_fio) ?> чел: <?php
                                            foreach ($past_psych_fio as $value) {
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
                    <select class=" chosen-select-deselect form-control b2" name="psych[]" multiple tabindex="4" data-placeholder="Выбрать" id="fio7" onchange="changeFio(7);" >

                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_psych_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($psych) && !empty($psych) && in_array($present['id'], $psych)) {
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
                    <label class="control-label  col-lg-12" for="monitoring">Инженеры мониторинга

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {

                            if (isset($past_monitoring_fio) && !empty($past_monitoring_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз <?= count($past_monitoring_fio) ?> чел: <?php
                                            foreach ($past_monitoring_fio as $value) {
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
                    <select class=" chosen-select-deselect form-control b2" name="monitoring[]" multiple tabindex="4" data-placeholder="Выбрать" id="fio9" onchange="changeFio(9);" >

                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_monitoring_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($monitoring) && !empty($monitoring) && in_array($present['id'], $monitoring)) {
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
                    <label class="control-label col-lg-12" for="face">вакант
                        <span class="glyphicon glyphicon-check" style="color: green;" data-toggle="tooltip" data-placement="top" title="Соответствует количеству вакантов в текущей смене, берется из списка смен "></span>
                    </label>
                    <input type="text" class="form-control"  placeholder="0" value="<?= $count_vacant_from_list ?>" disabled="" >
                </div>
            </div>

        </div>


            <div class="row">

                            <div class="col-lg-6">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="other_text">Другие

                    </label>
                    <textarea class="form-control" rows="4" cols="5" name="other_text"><?= (isset($past_other_text[0]) && !empty($past_other_text[0])) ? $past_other_text[0] : '' ?></textarea>

                </div>
            </div>


                            <div class="col-lg-1">
                <div class="form-group">
                    <label class="control-label col-lg-12" for="face">подмен.</label>
                    <input type="text" class="form-control" style="background-color:  #d4e062 !important;" placeholder="0" value="<?= $count_everyday ?>" disabled="" id="on_every">
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
            <div class="col-lg-6"><p class="line"><span>Дежурная часть</span></p></div>
        </div>


        <div class="row">


            <div class="col-lg-6">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="head_odh">Начальник ОДЧ

                    </label>
                    <input type="text" class="form-control" disabled="" value="<?= (isset($head_odh) && !empty($head_odh)) ? (mb_strtolower($head_odh['rank']) . ' ' . $head_odh['fio']) : '' ?>">
                </div>
            </div>






        </div>


        <div class="row">
            <div class="col-lg-3">

                <div class="form-group">
                    <label class="control-label  col-lg-12" for="od">Инспекторы

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {

                            if (isset($past_insp_fio) && !empty($past_insp_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз <?= count($past_insp_fio) ?> чел: <?php
                                            foreach ($past_insp_fio as $value) {
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
                    <select class=" chosen-select-deselect form-control b2 " name="insp[]" multiple tabindex="4" data-placeholder="Выбрать" id="fio8" onchange="changeFio(8);" >
                        <option ></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_insp_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($insp) && !empty($insp) && in_array($present['id'], $insp)) {
                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } else {
                                printf("<p><option value='%s' ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            }
                        }

                        ?>

                    </select>
                </div>
            </div>



            <div class="col-lg-3"></div>









        </div>


        <div class="row">


            <div class="col-lg-6"></div>



        </div>


        <div class="row">




        </div>



        <div class="row">
            <hr>

            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="everydayfio[]">Заступают подменные/еж.

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {
                            //вывод ежедневников этого ПАСЧ, кто заступал  прошлый раз
                            if (isset($past_everyday_fio) && !empty($past_everyday_fio)) {

                                $cnt_every = 0;
                                foreach ($past_everyday_fio as $value) {
                                    $cnt_every++;
                                }

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз <?= $cnt_every ?> чел: <?php
                                            foreach ($past_everyday_fio as $value) {
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
                    <select class=" chosen-select-deselect form-control " name="everydayfio[]"  multiple tabindex="4" data-placeholder="Добавить" >
                        <option ></option>
                        <?php
                        foreach ($present_everyday_fio as $present) {
                            if (in_array($present['id'], $p_e_fio) && ($dateduty == $today)) {
                                //если ежедневник выбран как начальник смены-его нельзя удалить, пока он не будет снят
                                if (isset($id_fio) && $id_fio == $present['id']) {
                                    printf("<p><option value='%s' selected disabled ><label>%s (%s)</label></option></p>", $present['id'], $present['fio'], mb_strtolower($present['slug']));
                                    $everyday_add = $present['id'];
                                } else
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

                                $cnt_other = 0;
                                foreach ($past_reserve_fio as $value) {
                                    $cnt_other++;
                                }

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз <?= $cnt_other ?> чел: <?php
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

        <div class="row">
            <hr>



            <div class="col-lg-6">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="head_ousis">Начальник ОУСиС

                    </label>
                    <input type="text" class="form-control" disabled="" value="<?= (isset($head_ousis) && !empty($head_ousis)) ? (mb_strtolower($head_ousis['rank']) . ' ' . $head_ousis['fio']) : '' ?>">
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

<script>

    function changeFio(i){

        var select_id_fio=$("#fio"+i+" option:selected:last").val();
        $('#fio'+i).removeClass('b2');

        var prev_fio_ids = $('#all_selected_fio'+i).val();
        var new_fio_ids=$("#fio"+i).val();


        if(prev_fio_ids === ''){

           if(new_fio_ids !== ''){
                if( select_id_fio !==undefined){

                     // remove from post-read
                    $(".b2 option[value='"+select_id_fio+"']").remove();
                    $(".b2").trigger("chosen:updated");
                }

            }

        }
        else{

                var prev_arr=[];
                var new_arr=[];

                if(prev_fio_ids.indexOf(",") !== -1){//, isset
                  //  alert('yes');
                  var prev_arr = prev_fio_ids.split(",");
                }
                else{
                     prev_arr.push(prev_fio_ids);
                }

                if(new_fio_ids !== null ){

                   if($.isArray(new_fio_ids)){
                       new_arr = new_fio_ids;
                   }
                   else{
                    if(new_fio_ids.indexOf(",") !== -1){//, isset
                        new_arr = new_fio_ids.split(",");
                    }
                    else{

                         new_arr.push(new_fio_ids);
                    }
                   }

    //alert(prev_arr.length+'  '+new_arr.length);
                    if(prev_arr.length>new_arr.length ){


                                            /** SUBTRACT ARRAYS **/
                      //function subtractarrays(array1, array2){
                          var diff = [];
                          for (var j=prev_arr.length; j--;) {
                                if (new_arr.indexOf(prev_arr[j]) === -1)
                                diff.push(prev_arr[j]);
                            }

                           var diff_fio_text=$("#fio"+i+" option[value='"+diff+"']").text();

                            // back to select
                           $(".b2").append('<option value="' + diff + '">' + diff_fio_text + ' </option>');
                           $(".b2").trigger("chosen:updated");

                    }
                    else if(prev_arr.length === new_arr.length && new_arr.length === 1){


                    //return prev
    //alert(prev_arr+'=>'+new_arr);
    //                var diff_fio_text=$("#fio"+i+" option[value='"+diff+"']").text();
    //
    //                // back to select
    //               $(".b2").append('<option value="' + diff + '">' + diff_fio_text + ' </option>');
    //               $(".b2").trigger("chosen:updated");

                    //delete now
                    $(".b2 option[value='"+select_id_fio+"']").remove();

                        var diff = [];
                        for (var j=prev_arr.length; j--;) {
                            if (new_arr.indexOf(prev_arr[j]) === -1)
                                diff.push(prev_arr[j]);
                        }

                        var diff_fio_text=$("#fio"+i+" option[value='"+diff+"']").text();

                        // back to select
                       $(".b2").append('<option value="' + diff + '">' + diff_fio_text + ' </option>');
                       $(".b2").trigger("chosen:updated");

                }
                else{

                    //remove from select
                    if( select_id_fio !==undefined){
                            // remove from post-read
                           $(".b2 option[value='"+select_id_fio+"']").remove();
                           $(".b2").trigger("chosen:updated");
                     }
                }

                  }
              else{

                    var diff = prev_fio_ids;
                    // var diff_fio_text=$("#hidden-fio option[value='144']").text();
                    var diff_fio_text=$("#fio"+i+" option[value='"+diff+"']").text();

                    // back to select
                    $(".b2").append('<option value="' + diff + '">' + diff_fio_text + ' </option>');
                    $(".b2").trigger("chosen:updated");
              }
      }

        $('#fio'+i).addClass('b2');
        $('#all_selected_fio'+i).val(new_fio_ids);

    }


    </script>


