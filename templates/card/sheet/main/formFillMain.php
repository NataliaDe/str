<?php

//какую дату писать в дате заступления. если смена сег должна заступить - сегодня
$is_btn_confirm = isset($is_btn_confirm) ? $is_btn_confirm : 0;
// получаем текущее время в сек. и вычитаем кол-во секунд в 1 сутках
$my_time = time() - 86400;
// переменная для вывода даты в поток
$yesterday = date("d-m-Y", $my_time);
$today = date("Y-m-d");
$today_for_calendar = date("d-m-Y");

/* * **************** Списки ФИО ***************** */
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
if (empty($past_head_fio)) {
    $p_h_fio = array();
} else {
    foreach ($past_head_fio as $key => $value) {
        $p_h_fio[] = $value['id'];
    }
}

/* foreach ($present_head_fio as $key => $value) {
  echo $value['id'] . $value['fio'] . '<br>';
  } */
//print_r($present_head_fio);
// print_r($past_reserve_fio);

if (isset($main) && !empty($main)) {
    foreach ($main as $row) {
        //$fio = $row['fio'];
        $id_fio = $row['id_fio'];
        $countls = $row['countls'];
        $face = $row['face'];
        $calc = $row['calc'];
        $gas = $row['gas'];
        $dutyls = $row['duty'];
        $fiodisp = $row['fio_duty'];
        $listls = $row['listls'];
        $vacant = $row['vacant'];
        $dateduty = $row['dateduty'];
        $fio_duty = $row['fio_duty'];
        $is_open_update = $row['open_update']; //доступ на ред.дежурной смены
        $idmain = $row['id'];
    }
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
//признак наличия несоответствия введенных цифр
$error_calc = 0;
$error_listls = 0;
$error_face = 0;
$error_on_shtat = 0;
$error_vacant = 0;

//реальная цифра 'налицо', которая д б в идеале
if (!isset($countls))
    $countls = 0;

if (!isset($count_ls_shtat))
    $count_ls_shtat = 0;

$on_face_rule = $on_list - $count_ill - $count_holiday - $count_trip - $count_other + $count_everyday + $count_past_reserve_fio;
//echo $on_face_rule;

if ((isset($listls) && ($listls != $on_list) ) || (!isset($listls) && (0 != $on_list) )) {
    $error_listls = 1;
}

if ((isset($face) && ($face != $on_face_rule) ) || (!isset($face) && (0 != $on_face_rule) )) {
    $error_face = 1;
}

if ((isset($calc) && ($calc != $count_fio_on_car) ) || (!isset($calc) && (0 != $count_fio_on_car) )) {
    $error_calc = 1;
}

if ($count_ls_shtat != $countls) {
    $error_on_shtat = 1;
}

if (isset($vacant) && ($vacant != $count_vacant_from_list)) {
    $error_vacant = 1;
}

if ($error_listls != 0 || $error_face != 0 || $error_calc != 0 || $error_on_shtat != 0 || $error_vacant != 0) {
    ?>
    <div class="container">
        <div class="alert alert-danger">
            <strong>Внимание!</strong>На данной вкладке требуется сохранить изменения.
        </div>
    </div>
    <?php
    $error_calc = 0;
    $error_listls = 0;
    $error_face = 0;
}

?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <form class="form-horizontal" role="form" id="formFillMain" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/main">
                <?php
//смена деж и доступ на редактирование закрыт
                if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($_SESSION['can_edit'] == 0)) {
                    ?>
                    <fieldset disabled>
                        <?php
                    }
                    ?>
                    <!-- Инициализация виджета "Bootstrap datetimepicker" -->
                    <!-- в календаре доступна только сегодн дата -->
                    <div class="row">

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="dateduty">Дата заступления <?= $change ?> смены</label>
                        <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                            <div class="form-group">
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

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="face">На больничном</label>
                        <div class="col-sm-6 col-lg-1 col-md-4 col-xs-9">
                            <div class="form-group">
                                <input type="text" class="form-control"  placeholder="0" value="<?= $count_ill ?>" disabled="" id="on_ill">

                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="id_fio">Ф.И.О начальника деж. смены


                            <?php
                            if (isset($dateduty) && ($dateduty != $today)) {
                                //кто заступал начальником смены прошлый раз
                                // на сегодня начальник смены доступен в списке или нет.если нет - вывод

                                if (isset($past_head_fio) && !empty($past_head_fio)) {
                                    ?>

                                    <!--                            <div class="row">
                                                                    <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="id_fio">Начальником смены <u>заступал</u></label>
                                                                    <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                                                                        <div class="form-group">
                                    <?php
//                                    foreach ($past_head_fio as $value) {
//                                        echo $value['fio'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . '), ';
//                                    }
                                    ?>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>-->

                                      <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                                title="Заступал прошлый раз: <?php
                                                foreach ($past_head_fio as $value) {
                                                    echo $value['fio'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . '), ';
                                                }
                                                ?> ">

                                    </i>

                                    <?php
                                }
                            }
                            ?>

                        </label>
                        <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                            <div class="form-group">
                                <select class=" chosen-select-deselect form-control " name="id_fio"  tabindex="2" data-placeholder="Выбрать"  >

                                    <?php
                                    foreach ($present_head_fio as $present) {
                                        if (in_array($present['id'], $p_h_fio) && ($dateduty == $today)) {

                                            printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                        } elseif (isset($id_fio) && ($id_fio == $present['id'])) {
                                            printf("<p><option value='%s' selected ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                        } else {
                                            printf("<p><option value='%s' ><label>%s %s %s (%s)</label></option></p>", $present['id'], $present['fio'], $present['pasp'], $present['locorg_name'], mb_strtolower($present['slug']));
                                        }
                                    }
                                    ?>

                                </select>
                            </div>
                        </div>

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="face">В отпуске</label>
                        <div class="col-sm-5 col-lg-1 col-md-4 col-xs-9">
                            <div class="form-group">
                                <input type="text" class="form-control"  placeholder="0" value="<?= $count_holiday ?>" disabled="" id="on_holiday">

                            </div>

                        </div>

                    </div>


                    <div class="row">
                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="everydayfio[]">Заступают ежедневники


                             <?php
                    if (isset($dateduty) && ($dateduty != $today)) {
                        //вывод ежедневников этого ПАСЧ, кто заступал  прошлый раз
                        if (isset($past_everyday_fio) && !empty($past_everyday_fio)) {
                                                                            $cnt=0;
                                                 foreach ($past_everyday_fio as $value) {
                                                     $cnt++;
                                                }
                            ?>


                                      <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                                title="Заступали прошлый раз <?=$cnt ?> чел: <?php

                                                 foreach ($past_everyday_fio as $value) {

                                                   echo $value['fio'] . ' (' . mb_strtolower($value['slug']) . '), ';
                                                }
                                                ?> ">

                                    </i>
                            <?php
                        }
                    }
                    ?>


                        </label>
                        <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                            <div class="form-group">
                                <select class="form-control chosen-select-deselect " name="everydayfio[]"  multiple tabindex="4" data-placeholder="Добавить" >
                                    <option></option>
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

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="face">В командировке</label>
                        <div class="col-sm-6 col-lg-1 col-md-4 col-xs-9">
                            <div class="form-group">
                                <input type="text" class="form-control"  placeholder="0" value="<?= $count_trip ?>" disabled="" id="on_trip">
                            </div>
                        </div>

                    </div>



                    <div class="row">
                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="reserve[]">Заступают из другого подразделения (смены)



                          <?php

                           if (isset($dateduty) && ($dateduty != $today)) {
                        //вывод тех, кто заступал из др пасч прошлый раз
                        if (isset($past_reserve_fio) && !empty($past_reserve_fio)) {

                            $cnt_other=0;
                                                                            foreach ($past_reserve_fio as $value) {
            $cnt_other++;
        }

        ?>

<!--                            <div class="row">
                                <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="id_fio"><u>Заступали из других подразделений (смен)</u></label>
                                <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                                    <div class="form-group">-->
                                        <?php
//                                        foreach ($past_reserve_fio as $value) {
//                                            echo $value['fio'] . ' ' . $present['is_every'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . ')<br>';
//                                        }
                                        ?>
<!--                                    </div>
                                </div>
                            </div>-->


                                      <i style="color:#ce5050;" class="fa fa-bell"  data-toggle="tooltip" data-placement="right"

                                                title="Заступали прошлый раз <?=$cnt_other ?> чел: <?php
                                                 foreach ($past_reserve_fio as $value) {
                                                   echo $value['fio'] . ' ' . $value['is_every'] . ' ' . $value['pasp'] . ' ' . $value['locorg_name'] . ' (' . mb_strtolower($value['slug']) . '), ';
                                                }
                                                ?> ">

                                    </i>

                            <?php
                        }
                    }
                            ?>


                        </label>
                        <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                            <div class="form-group">
                                <select class="form-control chosen-select-deselect " name="reserve[]" multiple tabindex="4" data-placeholder="Добавить">
                                    <option></option>
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
                            <!--                                    reserve, который выбран как начальник смены-->
                            <?php
                            if (isset($reserve_add)) {
                                ?>
                                <input type="hidden" value="<?= $reserve_add ?>" name="reserve_add">
                                <?php
                            }
                            ?>
                            <!--   ежедневник, который выбран как начальник смены -->
                            <?php
                            if (isset($everyday_add)) {
                                ?>
                                <input type="hidden" value="<?= $everyday_add ?>" name="everyday_add">
                                <?php
                            }
                            ?>
                        </div>

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="face">Др. причины</label>
                        <div class="col-sm-6 col-lg-1 col-md-4 col-xs-9">
                            <div class="form-group">
                                <input type="text" class="form-control"  placeholder="0" value="<?= $count_other ?>" disabled="" id="on_other">

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <!--                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="fiodisp">Ф.И.О радиотелефониста</label>
                                                <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                                                    <div class="form-group">
                        <?php
//  if (isset($fiodisp)) {
                        ?>
                                                            <textarea class="form-control" rows="2" cols="22" name="fiodisp" id="fiodisp"><? $fiodisp ?></textarea>
                        <?php
                        //  } else {
                        ?>
                                                            <textarea class="form-control" rows="2" cols="22" name="fiodisp" id="fiodisp"></textarea>
                        <?php
                        // }
                        ?>

                                                    </div>
                                                </div>-->
                    </div>



                    <div class="row">

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="countls">Количество л/с (по штату)
                            <span class="glyphicon glyphicon-check" style="color: green;" data-toggle="tooltip" data-placement="left" title="Соответствует количеству, введенному в карточке учета сил и средств"></span>
                        </label>
                        <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                            <div class="form-group">
                                <?php
                                if (isset($count_ls_shtat)) {
                                    ?>
                                    <input type="text" class="form-control" placeholder="0" value="<?= $count_ls_shtat ?>" disabled="">
                                    <input type="hidden" class="form-control" id="countls" placeholder="0" name="countls" value="<?= $count_ls_shtat ?>" >
                                    <?php
                                } elseif (isset($countls)) {
                                    ?>
                                    <input type="text" class="form-control"  placeholder="0" value="<?= $countls ?>" disabled="">
                                    <input type="hidden" class="form-control" id="countls" placeholder="0" name="countls" value="<?= $countls ?>" >
                                    <?php
                                } else {
                                    ?>
                                    <input type="text" class="form-control"  placeholder="0"   disabled="">
                                    <input type="hidden" class="form-control" id="countls" placeholder="0" name="countls"  disabled="" value="0">
                                    <?php
                                }
                                ?>
                            </div>

                        </div>

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="count_everyday">Ежедневники</label>
                        <div class="col-sm-6 col-lg-1 col-md-4 col-xs-9">
                            <div class="form-group">
                                <input type="text" class="form-control" style="background-color:  #d4e062 !important;" placeholder="0" value="<?= $count_everyday ?>" disabled="" id="on_every">
                            </div>
                        </div>

                    </div>

                    <div class="row">

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="listls">Количество л/с (по списку)
                            <?php
                            if ((isset($listls) && ($listls != $on_list) ) || (!isset($listls) && (0 != $on_list) )) {
                                $error_listls = 1;
                                ?>
                                <span class="glyphicon glyphicon-exclamation-sign" style="color: red;" data-toggle="tooltip" data-placement="left" title="Введенные данные не соответствуют количеству работников в смене(<?= $on_list ?>)"></span>
                                <?php
                            }
                            ?>
                            <span class="glyphicon glyphicon-check" style="color: green;" data-toggle="tooltip" data-placement="left" title="Соответствует количеству введенных работников в списке смен"></span>
                        </label>
                        <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                            <div class="form-group">
                                <?php
                                if (isset($listls)) {
                                    if ($error_listls != 0) {
                                        ?>
                                        <input type="text" class="form-control"  placeholder="0"  value="<?= $on_list ?>" style="background-color: #ff00002e !important;" disabled="">
                                        <input type="hidden" class="form-control" id="listls" placeholder="0" name="listls" value="<?= $on_list ?>" >
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" class="form-control"  placeholder="0"  value="<?= $on_list ?>" disabled="">
                                        <input type="hidden" class="form-control" id="listls" placeholder="0" name="listls" value="<?= $on_list ?>">
                                        <?php
                                    }
                                } else {
                                    if ($error_listls != 0) {
                                        ?>
                                        <input type="text" class="form-control"  placeholder="0"  style="background-color: #ff00002e !important;" disabled="" value="<?= $on_list ?>">
                                        <input type="hidden" class="form-control" id="listls" placeholder="0" name="listls"  value="<?= $on_list ?>">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" class="form-control"  placeholder="0"  disabled="" value="<?= $on_list ?>">
                                        <input type="hidden" class="form-control" id="listls" placeholder="0" name="listls" value="<?= $on_list ?>">
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                        </div>

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="count_past_reserve_fio">Из др.подразд.</label>
                        <div class="col-sm-6 col-lg-1 col-md-4 col-xs-9">
                            <div class="form-group">

                                <input type="text" class="form-control" style="background-color:  #d4e062 !important;" placeholder="0" value="<?= $count_past_reserve_fio ?>" disabled="" id="on_reserve">

                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <!-- вакант= из списка смены  -->
                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="vacant_failure">Вакант
                            <span class="glyphicon glyphicon-check" style="color: green;" data-toggle="tooltip" data-placement="left" title="Соответствует количеству введенных вакансий в списке смен"></span>
                            <?php
                            if ($error_vacant != 0) {
                                ?>
                                <span class="glyphicon glyphicon-exclamation-sign" style="color: red;" data-toggle="tooltip" data-placement="left" title="Введенные данные не соответствуют реальным данным(<?= $count_vacant_from_list ?>)"></span>
                                <?php
                            }
                            ?>
                        </label>
                        <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                            <div class="form-group">

                                <?php
                                if (isset($count_vacant_from_list)) {
                                    ?>
                                    <input type="text" class="form-control"  placeholder="0" value="<?= $count_vacant_from_list ?>" disabled="" >
                                    <input type="hidden" class="form-control"  placeholder="0" value="<?= $count_vacant_from_list ?>"   id="vacant" name="vacant">
                                    <?php
                                } else {
                                    ?>
                                    <input type="text" class="form-control"  placeholder="0" disabled="">
                                    <input type="hidden" class="form-control"  placeholder="0"  id="vacant" name="vacant">
                                    <?php
                                }
                                ?>
                            </div>
                            <hr>
                        </div>

                    </div>



                    <div class="row">

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="face">Налицо
                            <span class="glyphicon glyphicon-star" style="color: green;" data-toggle="tooltip" data-placement="left" title="Налицо = по списку - на бол.- в отпуске - в ком. - др.причины + еж. + из.др.подразд. = боевой расчет + наряд"></span>
                            <?php
                            if ((isset($face) && ($face != $on_face_rule) ) || (!isset($face) && (0 != $on_face_rule) )) {
                                $error_face = 1;
                                ?>
                                <span class="glyphicon glyphicon-exclamation-sign" style="color: red;" data-toggle="tooltip" data-placement="left" title="Введенные данные не соответствуют реальным данным(<?= $on_face_rule ?>)"></span>
                                <?php
                            }
                            ?>
                        </label>
                        <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                            <div class="form-group">
                                <?php
                                if (isset($face)) {
                                    if ($error_face != 0) {
                                        ?>
                                        <input type="text" class="form-control" id="face" placeholder="0" name="face" value="<?= $face ?>" style="background-color: #ff00002e !important;">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" class="form-control" id="face" placeholder="0" name="face" value="<?= $face ?>">
                                        <?php
                                    }
                                } else {
                                    if ($error_face != 0) {
                                        ?>
                                        <input type="text" class="form-control" id="face" placeholder="0" name="face" style="background-color: #ff00002e !important;">

                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" class="form-control" id="face" placeholder="0" name="face">
                                        <?php
                                    }
                                }
                                ?>

                            </div>

                        </div>
                        <button type="button" class="btn btn-success " onclick="getCountOnFace();"> <i class="fa fa-refresh fa-lg tilt" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Автоподсчет" ></i></button>


                    </div>





                    <div class="row">

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="calculation">Боевой расчет
                            <?php
                            if ((isset($calc) && ($calc != $count_fio_on_car) ) || (!isset($calc) && (0 != $count_fio_on_car) )) {
                                $error_calc = 1;
                                ?>
                                <span class="glyphicon glyphicon-exclamation-sign" style="color: red;" data-toggle="tooltip" data-placement="left" title="Введенные данные не соответствуют количеству работников, заступивших на технику(<?= $count_fio_on_car ?>)"></span>
                                <?php
                            }
                            ?>
                        </label>
                        <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                            <div class="form-group">
                                <?php
                                if (isset($calc)) {
                                    if ($error_calc != 0) {//red field
                                        ?>
                                        <input type="text" class="form-control" id="calc" placeholder="0" name="calc" value="<?= $calc ?>" style="background-color: #ff00002e !important;">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" class="form-control" id="calc" placeholder="0" name="calc" value="<?= $calc ?>">
                                        <?php
                                    }
                                } else {
                                    if ($error_calc != 0) {//red field
                                        ?>
                                        <input type="text" class="form-control" id="calc" placeholder="0" name="calc" style="background-color: #ff00002e !important;">
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" class="form-control" id="calc" placeholder="0" name="calc">
                                        <?php
                                    }
                                }
                                ?>

                            </div>
                        </div>


                    </div>

                    <div class="row">

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="duty">Наряд</label>
                        <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                            <div class="form-group">
                                <?php
                                if (isset($dutyls)) {
                                    ?>
                                    <input type="text" class="form-control" id="duty" placeholder="0" name="duty"  value="<?= $dutyls ?>">
                                    <?php
                                } else {
                                    ?>
                                    <input type="text" class="form-control" id="duty" placeholder="0" name="duty" >
                                    <?php
                                }
                                ?>

                            </div>

                        </div>

                    </div>

                    <div class="row">

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="fiodisp">Ф.И.О работников в наряде

                        <span class="glyphicon glyphicon-star" style="color: green;" data-toggle="tooltip" data-placement="left" title="указывать должность"></span>
                        </label>
                        <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                            <div class="form-group">
                                <?php
                                if (isset($fiodisp)) {
                                    ?>
                                    <textarea class="form-control" rows="2" cols="22" name="fio_duty" id="fio_duty"><?= $fio_duty ?></textarea>
                                    <?php
                                } else {
                                    ?>
                                    <textarea class="form-control" rows="2" cols="22" name="fio_duty" id="fio_duty"></textarea>
                                    <?php
                                }
                                ?>

                            </div>
                        </div>

                    </div>

                    <!--                    <div class="row">


                                                   <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="fio_duty">Ф.И.О в наряде</label>
                                            <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                                                <div class="form-group">
                    <?php
                    if (isset($fio_duty)) {
                        ?>
                                                                    <textarea class="form-control" rows="2" cols="22" name="fio_duty" id="fio_duty"><? $fio_duty ?></textarea>
                        <?php
                    } else {
                        ?>
                                                                    <textarea class="form-control" rows="2" cols="22" name="fio_duty" id="fio_duty"></textarea>
                        <?php
                    }
                    ?>

                                                </div>
                                            </div>
                                        </div>-->

                    <div class="row">

                        <label class="control-label col-sm-4 col-lg-3 col-xs-9" for="gas">Газодымозащитники</label>
                        <div class="col-sm-6 col-lg-3 col-md-4 col-xs-9">
                            <div class="form-group">
                                <?php
                                if (isset($gas)) {
                                    ?>
                                    <input type="text" class="form-control" id="gas" placeholder="0" name="gas" value="<?= $gas ?>">
                                    <?php
                                } else {
                                    ?>
                                    <input type="text" class="form-control" id="gas" placeholder="0" name="gas">
                                    <?php
                                }
                                ?>

                            </div>

                        </div>
                    </div>



                    <?php
                    if (isset($idmain)) {
                        ?>
                        <input type="hidden" class="form-control"   id="idmain" name="idmain" value="<?= $idmain ?>">
                        <?php
                    }
                    if (isset($post) && ($post == 0)) {//put
                        //echo $post;
                        ?>

                        <input type="hidden" name="_METHOD" value="PUT"/>

                        <?php
                    }
                    ?>
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
        </div>

    </div>
</div>







