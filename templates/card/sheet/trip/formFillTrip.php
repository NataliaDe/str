<form class="form-inline" role="form" id="formFillTrip" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/trip/save">

    <!--    <div class="table-responsive" id="conttabl">-->
    <b>Заполните поля формы:</b>
    <table class="table table-condensed   table-bordered tbl_show_inf" >
        <!-- строка 1 -->
        <thead>
            <tr >
                <th>№п/п</th>
                <th >Ф.И.О</th>
                <th >Дата начала</th>
                <th>Дата окончания</th>
                <th>Место и цель<br>командирования</th>

                <th style="width:200px;">Основание командирования, дата</th>
                <th>Примечание/<br>Вид</th>

            </tr>
        </thead>
        <tbody>
            <?php
//echo $countill;
            for ($i = 1; $i <= $counttrip; $i++) {
                ?>
                <tr>
                    <td><?= $i . '.' ?></td>
                    <td>
                        <div class="form-group">
                            <select class="form-control" name="id_fio<?= $i ?>" >

                                <?php
                                foreach ($listfio as $l) {

                                    printf("<p><option value='%s'><label>%s</label></option></p>", $l['id'], $l['fio']);
                                }
                                ?>

                            </select>
                        </div>
                        <br><br><br>
                        <div class="form-group">
                            <div class="checkbox checkbox-danger">
                                <input id="checkbox<?= $i ?>" type="checkbox" name="is_cosmr<?= $i ?>" value='1'>
                                <label for="checkbox<?= $i ?>">
                                    Согласовано с ЦОСМР
                                </label>
                            </div>
                        </div>
                    </td>
                    <td>

                        <!-- Инициализация виджета "Bootstrap datetimepicker" -->
                        <div class="form-group">
                            <div class="input-group date" id="date1<?= $i ?>">
                                <input type="text" class="form-control"  name="date1<?= $i ?>" style="width: auto;" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>

                    </td>



                    <td>
                        <div class="form-group">
                            <div class="input-group date" id="date2<?= $i ?>">
                                <input type="text" class="form-control" name="date2<?= $i ?>" style="width: auto;" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="form-group">
                            <textarea cols="30" rows="7" class="form-control" placeholder="место командирования"  id="place<?= $i ?>" name="place<?= $i ?>"></textarea>

                        </div>
                    </td>

                    <!-------------------------------     Основание командирования, ------------------------------------------------->
                    <td>

                        <div class="col-lg-12">
                            <div class="form-group">
    <!--                                <textarea cols="25" rows="7" class="form-control" placeholder="Основание командирования, дата"  id="prikaz<?= $i ?>" name="prikaz<?= $i ?>">Приказ нач. РОЧС от <?= date("d.m.Y") ?> № </textarea>-->

                                <select class="form-control" name="id_viddocument<?= $i ?>" >

                                    <?php
                                    foreach ($vid_document as $ty) {

                                        printf("<p><option value='%s'><label>%s</label></option></p>", $ty['id'], $ty['name']);
                                    }
                                    ?>

                                </select>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">

                                    <select class="form-control" name="id_vidposition<?= $i ?>" >

                                        <?php
                                        foreach ($vid_position as $ty) {

                                            printf("<p><option value='%s'><label>%s</label></option></p>", $ty['id'], $ty['name']);
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                        </div>




                        <!-- Дата приказа  -->
                        <div class="form-group">
                            от <div class="input-group date" id="prikaz_date<?= $i ?>" >
                                <input type="text" class="form-control"  name="prikaz_date<?= $i ?>" style="width: 100px;" />
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar" onclick="getPrikazDate(<?= $i ?>);"></span>
                                </span>
                            </div>
                        </div>
                        <div class="form-group ">
                            № <input name="prikaz_number<?= $i ?>" class="form-control prikaz_number" style="width:141px;" >
                        </div>


                    </td>
                    <!-------------------------------   END  Основание командирования, ------------------------------------------------->

                    <td>
                        <div class="form-group">
                            <textarea cols="25" rows="5" class="form-control"  id="note<?= $i ?>" name="note<?= $i ?>"></textarea>

                        </div>
                        <br>
                        <div class="form-group">
                            <select class="form-control" name="id_type<?= $i ?>" >

                                <?php
                                foreach ($type_trip as $ty) {

                                    printf("<p><option value='%s'><label>%s</label></option></p>", $ty['id'], $ty['name']);
                                }
                                ?>

                            </select>
                        </div>
                    </td>

                </tr>

                <?php
            }
            ?>
        </tbody>


    </table>
    <!--    </div>-->
    <input type="hidden" class="form-control"   id="counttrip" name="counttrip" value="<?= $counttrip ?>">

    <center>
        <div class="row">
            <div class="form-group">
                <button type="submit" class="btn btn-success">Сохранить в БД</button>
            </div>
        </div>
    </center>

</form>