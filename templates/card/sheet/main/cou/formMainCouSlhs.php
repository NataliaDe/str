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
    $p_e_fio_count = array();
    foreach ($past_everyday_fio as $key => $value) {
        $p_e_fio[] = $value['id'];
        if ($value['is_nobody'] == 0) {
            $p_e_fio_count[] = $value['id'];
        }
    }
    $count_everyday = count($p_e_fio_count); //кол-во ежедневников
}


//заступАЛ начальник смены
if (empty($past_head_fio)) {
    $p_h_fio = array();
} else {
    foreach ($past_head_fio as $key => $value) {
        $p_h_fio[] = $value['id'];
    }
}


//ОД
if (empty($past_od_fio)) {
    $p_od_fio = array();
} else {
    foreach ($past_od_fio as $key => $value) {
        $p_od_fio[] = $value['id'];
    }
}


// зам ОД
if (empty($past_z_od_fio)) {
    $p_z_od_fio = array();
} else {
    foreach ($past_z_od_fio as $key => $value) {
        $p_z_od_fio[] = $value['id'];
    }
}

// ст помощник ОД
if (empty($past_st_pom_od_fio)) {
    $p_st_pom_od_fio = array();
} else {
    foreach ($past_st_pom_od_fio as $key => $value) {
        $p_st_pom_od_fio[] = $value['id'];
    }
}

// помощник ОД
if (empty($past_pom_od_fio)) {
    $p_pom_od_fio = array();
} else {
    foreach ($past_pom_od_fio as $key => $value) {
        $p_pom_od_fio[] = $value['id'];
    }
}

// диспетчера
if (empty($past_disp_fio)) {
    $p_disp_fio = array();
} else {
    foreach ($past_disp_fio as $key => $value) {
        $p_disp_fio[] = $value['id'];
    }
}


// инженер ТКС
if (empty($past_eng_tks_fio)) {
    $p_eng_tks_fio = array();
} else {
    foreach ($past_eng_tks_fio as $key => $value) {
        $p_eng_tks_fio[] = $value['id'];
    }
}

// инженер связи
if (empty($past_eng_connect_fio)) {
    $p_eng_connect_fio = array();
} else {
    foreach ($past_eng_connect_fio as $key => $value) {
        $p_eng_connect_fio[] = $value['id'];
    }
}

// master связи
if (empty($past_master_connect_fio)) {
    $p_master_connect_fio = array();
} else {
    foreach ($past_master_connect_fio as $key => $value) {
        $p_master_connect_fio[] = $value['id'];
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


//стажер
if (empty($past_trainee_fio)) {
    $p_trainee_fio = array();
} else {
    foreach ($past_trainee_fio as $key => $value) {
        $p_trainee_fio[] = $value['id'];
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

// inspector_inip_fio
if (empty($past_inspector_inip_fio)) {
    $p_inspector_inip_fio = array();
} else {
    foreach ($past_inspector_inip_fio as $key => $value) {
        $p_inspector_inip_fio[] = $value;
    }
}
//print_r($p_inspector_inip_fio);
// head on garnison
if (empty($past_garnison_fio)) {
    $p_garnison_fio = array();
} else {
    foreach ($past_garnison_fio as $key => $value) {
        $p_garnison_fio[] = $value;
    }
}


/* ------------  END заступАЛИ прошлый раз ------------- */


/* ------------ должности связь 1 к 1 ----------------- */
$head_ch = 0;
$od = 0;
$eng_tks = 0;
$eng_connect = 0;
$master_connect = 0;
$trainee = 0;
$inspector_inip = 0;
$garnison = 0;
/* ------------ КОНЕЦ должности связь 1 к 1 ----------------- */

if (isset($main) && !empty($main)) {

    $count_all = array(); //all select on position - id of fio
    //print_r($main);
    foreach ($main as $row) {

        $dateduty = $row['dateduty'];

        if (!empty($row['id_fio'])) {
            $count_all[] = $row['id_fio'];
        }


        if ($row['id_pos_duty'] == 1)
            $head_ch = $row['id_fio'];

        elseif ($row['id_pos_duty'] == 2)
            $od = $row['id_fio'];

        elseif ($row['id_pos_duty'] == 7)
            $eng_tks = $row['id_fio'];

        elseif ($row['id_pos_duty'] == 8)
            $eng_connect = $row['id_fio'];

        elseif ($row['id_pos_duty'] == 9)
            $master_connect = $row['id_fio'];
        elseif ($row['id_pos_duty'] == 11)
            $trainee = $row['id_fio'];

        elseif ($row['id_pos_duty'] == 13)
            $inspector_inip = $row['id_fio'];

        elseif ($row['id_pos_duty'] == 14)
            $garnison = $row['id_fio'];


        elseif ($row['id_pos_duty'] == 3)
            $z_od[] = $row['id_fio'];

        elseif ($row['id_pos_duty'] == 4)
            $st_pom_od[] = $row['id_fio'];
        elseif ($row['id_pos_duty'] == 5)
            $pom_od[] = $row['id_fio'];
        elseif ($row['id_pos_duty'] == 6)
            $disp[] = $row['id_fio'];
        elseif ($row['id_pos_duty'] == 10)
            $driver[] = $row['id_fio'];
        elseif ($row['id_pos_duty'] == 12)
            $others[] = $row['id_fio'];


        //$is_open_update = $row['open_update']; //доступ на ред.дежурной смены
    }

    $count_all_unique = array_unique($count_all);
    $count_all = count($count_all_unique);

    $date_d = new DateTime($dateduty);
    $dateduty_for_calendar = $date_d->Format('d-m-Y');
} else {
	$count_all=0;
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

<form  role="form" id="formFillMain" method="POST" action="/str/v2/card/<?= $record_id ?>/ch/<?= $change ?>/main">

    <?php
//смена деж и доступ на редактирование закрыт
    if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($_SESSION['can_edit'] == 0)) {

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
                    <label class="control-label col-lg-12" for="face">штат ЦОУ</label>
                    <input type="text" class="form-control"  placeholder="0" value="<?= $count_shtat ?>" disabled="" >
                </div>
            </div>
            </div>



        <div class="row">
             <div class="col-lg-3">

                    <div class="form-group">
                        <label class="control-label  col-lg-12" for="head_ch">Начальник смены (ПАСО, РОСН)

                            <?php
                            /* -----------------  Заступали прошлый раз ----------------- */
                            if (isset($dateduty) && ($dateduty != $today)) {
                                //кто заступал начальником смены прошлый раз
                                // на сегодня начальник смены доступен в списке или нет.если нет - вывод

                                if (isset($past_head_fio) && !empty($past_head_fio)) {

                                    ?>

                                    &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                                title="Заступал прошлый раз: <?php
                                                foreach ($past_head_fio as $value) {
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
                        <select class=" chosen-select-deselect form-control " name="head_ch"  tabindex="2" data-placeholder="Выбрать"  >
                            <option ></option>
                            <?php
                            foreach ($present_head_fio as $present) {
                                if (in_array($present['id'], $p_h_fio) && ($dateduty != $today)) {

                                    printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                } elseif (isset($head_ch) && ($head_ch == $present['id'])) {
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
                        <label class="control-label  col-lg-12" for="od">Оперативный дежурный ЦОУ

                            <?php
                            /* -----------------  Заступали прошлый раз ----------------- */
                            if (isset($dateduty) && ($dateduty != $today)) {
                                //кто заступал начальником смены прошлый раз
                                // на сегодня начальник смены доступен в списке или нет.если нет - вывод

                                if (isset($past_od_fio) && !empty($past_od_fio)) {

                                    ?>

                                    &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                                title="Заступал прошлый раз: <?php
                                                foreach ($past_od_fio as $value) {
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
                        <select class=" chosen-select-deselect form-control " name="od"  tabindex="2" data-placeholder="Выбрать"  >
                            <option value=""></option>
                            <?php
                            foreach ($present_head_fio as $present) {
                                if (in_array($present['id'], $p_od_fio) && ($dateduty != $today)) {

                                    printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                } elseif (isset($od) && ($od == $present['id'])) {
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
                    <label class="control-label col-lg-12" for="face">всего</label>
                    <input type="text" class="form-control"  placeholder="0" value="<?= $count_all ?>" disabled="" id="on_holiday">
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

                                                title="Заступали прошлый раз: <?php
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
                        <select class=" chosen-select-deselect form-control " name="z_od[]" multiple tabindex="4" data-placeholder="Выбрать"  >
                            <option ></option>
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
                    <label class="control-label  col-lg-12" for="master_connect">Мастер связи

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {
                            //кто заступал начальником смены прошлый раз
                            // на сегодня начальник смены доступен в списке или нет.если нет - вывод

                            if (isset($past_master_connect_fio) && !empty($past_master_connect_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступал прошлый раз: <?php
                                            foreach ($past_master_connect_fio as $value) {
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
                    <select class=" chosen-select-deselect form-control " name="master_connect"  tabindex="4" data-placeholder="Выбрать"  >
                        <option ></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_master_connect_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($master_connect) && ($master_connect == $present['id'])) {
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
                        <label class="control-label  col-lg-12" for="st_pom_od">Старший помощник ОД


                            <?php
                            /* -----------------  Заступали прошлый раз ----------------- */
                            if (isset($dateduty) && ($dateduty != $today)) {

                                if (isset($past_st_pom_od_fio) && !empty($past_st_pom_od_fio)) {

                                    ?>

                                    &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                                title="Заступали прошлый раз: <?php
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
                        <select class=" chosen-select-deselect form-control " name="st_pom_od[]" multiple tabindex="4" data-placeholder="Выбрать"  >
                            <option ></option>
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





            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="disp">Диспетчера

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {

                            if (isset($past_disp_fio) && !empty($past_disp_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз: <?php
                                            foreach ($past_disp_fio as $value) {
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
                    <select class=" chosen-select-deselect form-control " name="disp[]" multiple tabindex="4" data-placeholder="Выбрать"  >
                        <option ></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_disp_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($disp) && !empty($disp) && in_array($present['id'], $disp)) {
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

                <div class="col-lg-3">
                    <div class="form-group">
                        <label class="control-label  col-lg-12" for="pom_od">Помощник ОД

                            <?php
                            /* -----------------  Заступали прошлый раз ----------------- */
                            if (isset($dateduty) && ($dateduty != $today)) {

                                if (isset($past_pom_od_fio) && !empty($past_pom_od_fio)) {

                                    ?>

                                    &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                                title="Заступали прошлый раз: <?php
                                                foreach ($past_pom_od_fio as $value) {
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
                        <select class=" chosen-select-deselect form-control " name="pom_od[]" multiple tabindex="4" data-placeholder="Выбрать"  >
                            <option ></option>
                            <?php
                            foreach ($present_head_fio as $present) {
                                if (in_array($present['id'], $p_pom_od_fio) && ($dateduty != $today)) {

                                    printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                } elseif (isset($pom_od) && !empty($pom_od) && in_array($present['id'], $pom_od)) {
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
                        <label class="control-label  col-lg-12" for="driver">Водитель

                            <?php
                            /* -----------------  Заступали прошлый раз ----------------- */
                            if (isset($dateduty) && ($dateduty != $today)) {

                                if (isset($past_driver_fio) && !empty($past_driver_fio)) {

                                    ?>

                                    &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                                title="Заступали прошлый раз: <?php
                                                foreach ($past_driver_fio as $value) {
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
                        <select class=" chosen-select-deselect form-control " name="driver[]" multiple tabindex="4" data-placeholder="Выбрать"  >
                            <option ></option>
                            <?php
                            foreach ($present_head_fio as $present) {
                                if (in_array($present['id'], $p_driver_fio) && ($dateduty != $today)) {

                                    printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                } elseif (isset($driver) && !empty($driver) && in_array($present['id'], $driver)) {
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
                    <label class="control-label col-lg-12" for="face">вакант</label>
                    <input type="text" class="form-control"  placeholder="0" value="<?= $count_vacant_from_list ?>" disabled="" >
                </div>
            </div>

        </div>


        <div class="row">



            <div class="col-lg-3">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="trainee">Стажер

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
                    <select class=" chosen-select-deselect form-control " name="trainee"  tabindex="4" data-placeholder="Выбрать"  >
                        <option ></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_trainee_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($trainee) && ($trainee == $present['id'])) {
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
                    <label class="control-label  col-lg-12" for="others">Другие

                        <?php
                        /* -----------------  Заступали прошлый раз ----------------- */
                        if (isset($dateduty) && ($dateduty != $today)) {

                            if (isset($past_others_fio) && !empty($past_others_fio)) {

                                ?>

                                &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                            title="Заступали прошлый раз: <?php
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
                    <select class=" chosen-select-deselect form-control " name="others[]" multiple tabindex="4" data-placeholder="Выбрать"  >
                        <option ></option>
                        <?php
                        foreach ($present_head_fio as $present) {
                            if (in_array($present['id'], $p_others_fio) && ($dateduty != $today)) {

                                printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                            } elseif (isset($others) && !empty($others) && in_array($present['id'], $others)) {
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
                    <label class="control-label col-lg-12" for="face">еж.</label>
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
            <hr>
            <div class="col-lg-6">
                <div class="form-group">
                    <label class="control-label  col-lg-12" for="inspector_inip">Дежурный инспектор ОНиП (ФИО, должность, звание)


                        <?php
                        if (!empty($past_inspector_inip_fio)) {

                            ?>
                            <input type="text" class="form-control"  value="<?= $p_inspector_inip_fio[0] ?>"  name="inspector_inip" >
                            <?php
                        } else {

                            ?>
                            <input type="text" class="form-control"   name="inspector_inip" >
                            <?php
                        }

                        ?>




                        </div>
                        </div>


                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="control-label  col-lg-12" for="garnison">Ответственный по гарнизону (ФИО, должность, звание)

                                    <?php
                                    if (!empty($past_garnison_fio)) {

                                        ?>
                                        <input type="text" class="form-control"  value="<?= $p_garnison_fio[0] ?>"  name="garnison" >
                                        <?php
                                    } else {

                                        ?>
                                        <input type="text" class="form-control"   name="garnison" >
                                        <?php
                                    }

                                    ?>

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

                                                            ?>

                                                            &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                                                        title="Заступали прошлый раз: <?php
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

                                                            ?>

                                                            &nbsp;   <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                                                        title="Заступали прошлый раз: <?php
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


