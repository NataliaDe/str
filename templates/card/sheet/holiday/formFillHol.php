<form class="form-inline" role="form" id="formFillIll" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/holiday/save">

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
                    <th>№ приказа, дата</th>

                </tr>
            </thead>
            <tbody>
                <?php
//echo $countill;
                for ($i = 1; $i <= $counthol; $i++) {
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
                                <textarea cols="25" rows="7" class="form-control" placeholder="№ приказа, дата"  id="prikaz<?= $i ?>" name="prikaz<?= $i ?>">Приказ нач. РОЧС от <?= date("d.m.Y") ?> № </textarea>

                            </div>
                        </td>


                    </tr>

                    <?php
                }
                ?>
            </tbody>

            <?php
            ?>
        </table>
<!--    </div>-->
    <input type="hidden" class="form-control"   id="counthol" name="counthol" value="<?= $counthol ?>">
    
          <center>
        <div class="row">
            <div class="form-group">
           <button type="submit" class="btn btn-success">Сохранить в БД</button>
            </div>
        </div>
    </center>
    
</form>