
<form class="form-inline" role="form" id="formFillTrip" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/trip/save">
    <?php
    if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm == 1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
        ?>
        <fieldset disabled>
            <?php
        }
        ?>
        <!--        <div class="table-responsive" id="conttabl">-->
        <br><br><br>
        <table class="table table-condensed   table-bordered tbl_show_inf" >
            <!-- строка 1 -->
            <thead>
                <tr >
                    <th>№п/п</th>
                    <th >Ф.И.О</th>
                    <th >Дата начала</th>
                    <th>Дата окончания</th>
                    <th>Место и цель<br>командирования</th>
                    <th>Основание командирования, дата</th>
                    <th>Примечание/<br>Вид</th>
                    <th>Удалить</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                foreach ($trip as $row) {
                    $i++;
                    ?>
                    <tr> 
                        <td><?= $i ?></td>


                        <td>
                            <div class="form-group">
                                <?php
                                echo $row['fio'] . ',<br>' . '<i>' . $row['position_name'] . '</i>' . ',<br>' . '<i>' . $row['rank_name'] . '</i>';
                                ?>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <div class="checkbox checkbox-danger">
                                    <?php
                                    if ($row['is_cosmr'] == 1) {
                                        ?>
                                        <input id="checkbox<?= $i ?>" type="checkbox" name="is_cosmr<?= $i ?>" value='1' checked="">
                                        <?php
                                    } else {
                                        ?>
                                        <input id="checkbox<?= $i ?>" type="checkbox" name="is_cosmr<?= $i ?>" value='1'>
                                        <?php
                                    }
                                    ?>

                                    <label for="checkbox<?= $i ?>">
                                        Согласовано с ЦОСМР
                                    </label>
                                </div>
                            </div>

                        </td>
                        <td>
                            <div class="input-group date" id="date1<?= $i ?>">
                                <?php
                                if ($row['date1'] == '00.00.0000') {
                                    ?>
                                    <input type="text" class="form-control" name="date1<?= $i ?>" style="width: auto;"/>
                                    <?php
                                } else {
                                    ?>
                                    <input type="text" class="form-control" name="date1<?= $i ?>" value="<?= $row['date1'] ?>" style="width: auto;"/>
                                    <?php
                                }
                                ?>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>

                            </div>
                        </td>

                        <td>
                            <div class="form-group">
                                <div class="input-group date" id="date2<?= $i ?>">
                                    <?php
                                    if ($row['date2'] == '00.00.0000') {
                                        ?>
                                        <input type="text" class="form-control" name="date2<?= $i ?>" style="width: auto;"/>
                                        <?php
                                    } else {
                                        ?>
                                        <input type="text" class="form-control" name="date2<?= $i ?>" value="<?= $row['date2'] ?>" style="width: auto;"/>
                                        <?php
                                    }
                                    ?>

                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="form-group">
                                <textarea cols="25" rows="7" class="form-control"  id="place<?= $i ?>" name="place<?= $i ?>"><?= $row['place'] ?></textarea>

                            </div>
                        </td>

                        <!-------------------------------     Основание командирования, ------------------------------------------------->
                        <td>
                            <?php
                                if (!isset($row['id_viddocument'])) {
                                    ?>
                                    <div class="form-group">
                                        <textarea cols="24" rows="7" class="form-control" disabled="" ><?= $row['prikaz'] ?></textarea>
                                    </div>
                                    <?php
                                }
                                ?>
                            

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
    <!--                                <textarea cols="25" rows="7" class="form-control" placeholder="Основание командирования, дата"  id="prikaz<?= $i ?>" name="prikaz<?= $i ?>">Приказ нач. РОЧС от <?= date("d.m.Y") ?> № </textarea>-->

                                        <select class="form-control" name="id_viddocument<?= $i ?>" >

                                            <?php
                                            foreach ($vid_document as $ty) {
                                                if ($ty['id'] == $row['id_viddocument']) {
                                                    printf("<p><option value='%s' selected><label>%s</label></option></p>", $ty['id'], $ty['name']);
                                                } else
                                                    printf("<p><option value='%s'><label>%s</label></option></p>", $ty['id'], $ty['name']);
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-lg-12">
                                    <div class="form-group">

                                        <select class="form-control" name="id_vidposition<?= $i ?>" >

                                            <?php
                                            foreach ($vid_position as $ty) {
                                                if ($ty['id'] == $row['id_vidposition']) {
                                                    printf("<p><option value='%s' selected><label>%s</label></option></p>", $ty['id'], $ty['name']);
                                                } else
                                                    printf("<p><option value='%s'><label>%s</label></option></p>", $ty['id'], $ty['name']);
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                            </div>


                            <!-- Дата приказа  -->
                            <div class="form-group">

                                от  <div class="input-group date" id="prikaz_date<?= $i ?>"  >
                                    <input type="text" class="form-control"  name="prikaz_date<?= $i ?>" style="width: 100px;" value="<?= $row['prikaz_date'] ?>" />
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar" onclick="getPrikazDate(<?= $i ?>);"></span>
                                    </span>
                                </div>
                            </div>


                            <div class="form-group ">
                                № <input name="prikaz_number<?= $i ?>" class="form-control prikaz_number" style="width:141px;" value="<?= $row['prikaz_number'] ?>"  >
                            </div>

                        </td>
                        <!-------------------------------   END  Основание командирования, ------------------------------------------------->

                        <td>
                            <div class="form-group">
                                <textarea cols="25" rows="5" class="form-control"  id="note<?= $i ?>" name="note<?= $i ?>"><?= $row['note'] ?></textarea>

                            </div>
                            <br>
                            <div class="form-group">

                                <select class="form-control" name="id_type<?= $i ?>" >

                                    <?php
                                    foreach ($type_trip as $ty) {
                                        if ($ty['id'] == $row['id_type']) {
                                            printf("<p><option value='%s' selected><label>%s</label></option></p>", $ty['id'], $ty['name']);
                                        } else {
                                            printf("<p><option value='%s'><label>%s</label></option></p>", $ty['id'], $ty['name']);
                                        }
                                    }
                                    ?>

                                </select>
                            </div>
                        </td>

                        <td>
                            <?php
                            //удалить можно только что добавленного работника, а работника добавленного несколько дней назад-нет
                            if ($row['date_insert'] == date("Y-m-d")) {
                                ?>
                                <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/trip/<?= $row['id'] ?>"><button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="bottom" title="Удалить"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></a>
                                <?php
                            }
                            ?>
                        </td>


                <input type="hidden" class="form-control"   id="idtrip<?= $i ?>" name="idtrip<?= $i ?>" value="<?= $row['id'] ?>">
                </tr>
                <?php
            }
            ?>

            </tbody>
        </table>
        <!--        </div>-->

        <input type="hidden" class="form-control"   id="counttrip" name="counttrip" value="<?= $i ?>">
        <input type="hidden" name="_METHOD" value="PUT"/>


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