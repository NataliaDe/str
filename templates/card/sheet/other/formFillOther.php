<form class="form-inline" role="form" id="formFillOther" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/other/save">

<!--    <div class="table-responsive" id="conttabl">-->
        <b>Заполните поля формы:</b>
        <table class="table table-condensed   table-bordered tbl_show_inf">
            <!-- строка 1 -->
            <thead>
                     <tr>
                        <th rowspan="2">№п/п</th>
                        <th rowspan="2">Ф.И.О</th>
                        <th colspan="2" >Дата отсутствия</th>
                          <th rowspan="2">Причина</th>
                        <th rowspan="2">Примечание</th>
                    </tr>
                    <tr >
                        <th>c</th>
                        <th >по</th>
                    </tr>
        
            </thead>
            <tbody>
                <?php
//echo $countill;
                for ($i = 1; $i <= $countother; $i++) {
                    ?>
                    <tr>
                        <td><?= $i . '.' ?></td>
                         <td>
                            <div class="form-group">
                                <select class="form-control" name="id_fio<?= $i ?>" >
                                       
                                        <?php
                                        foreach ($listfio as $l) {
                                                printf("<p><option value='%s' ><label>%s</label></option></p>", $l['id'], $l['fio']);
                                        }
                                        ?>

                                    </select>
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
                                <textarea cols="35" rows="7" class="form-control" placeholder="Причина"  id="reasonother<?= $i ?>" name="reasonother<?= $i ?>"></textarea>

                            </div>
                        </td>

                        <td>
                            <div class="form-group">
                                <textarea cols="25" rows="7" class="form-control" placeholder="Примечание"  id="noteother<?= $i ?>" name="noteother<?= $i ?>"></textarea>

                            </div>
                        </td>
                    </tr>

                    <?php
                }
                ?>
            </tbody>

        </table>
<!--    </div>-->
    <input type="hidden" class="form-control"   id="countother" name="countother" value="<?= $countother ?>">

          <center>
        <div class="row">
            <div class="form-group">
                 <button type="submit" class="btn btn-success">Сохранить в БД</button>
            </div>
        </div>
    </center>
    
</form>