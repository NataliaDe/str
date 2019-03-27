

<div  class="col-lg-12 col-md-11 col-sm-11 col-xs-11" >
    <form class="form-inline" role="form" id="formEditTrip" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/car/trip">
  <?php

           if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
                ?>
                <fieldset disabled>
                    <?php
                }
                ?>
        <div class="table" id="conttabl">
            <br>  <br>
            <b>Техника в командировке</b>
            <table class="table table-condensed   table-bordered" id="tblfillill">
                <!-- строка 1 -->
                <thead>
                    <tr >
                        <th >Техника</th>
                        <th >Дата начала</th>
                        <th>Дата окончания</th>
                        <th>Место<br>(куда)</th>
                        <th>Основание командирования,<br>дата</th>
                        <th>Примечание</th>
                        <th>Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($tripcar as $value) {
                        $i++;
                        $dat1 = new DateTime($value['date1']);
                        $d1 = $dat1->Format('d-m-Y');
                        $dat2 = new DateTime($value['date2']);
                        $d2 = $dat2->Format('d-m-Y');
                        $dat3 = new DateTime($value['date_insert']);
                        $d_insert = $dat3->Format('Y-m-d');
                        ?>
                        <tr>
                            <td>
                                <div class="form-group">
                                        <?php
                                        foreach ($list_car as $l) {
                                            if ($l['id'] == $value['id_teh']) {
                                   $mark= $l['mark'] ;
                                      $numbsign=' ('.$l['numbsign'].')';
                                            }
                                        }
                                        if(empty($mark))
                                            $mark='';
                                        ?>
                                    <div class="form-group">
                                         <textarea cols="15" rows="4" class="form-control" disabled="disabled"><?= $mark ?> <?= $numbsign ?></textarea>


                                </div>

                                </div>
                            </td>
                            <td>

                                <!-- Инициализация виджета "Bootstrap datetimepicker" -->
                                <div class="form-group">
                                    <div class="input-group date" id="date1<?= $i ?>">


    <?php
    if ($value['date1'] == NULL) {
        ?>
                                            <input type="text" class="form-control" name="date1<?= $i ?>"/>
                                            <?php
                                        } else {
                                            ?>
                                            <input type="text" class="form-control" name="date1<?= $i ?>" value="<?= $d1 ?>"/>
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
                                    <div class="input-group date" id="date2<?= $i ?>">

    <?php
    if ($value['date2'] == NULL) {
        ?>
                                            <input type="text" class="form-control" name="date2<?= $i ?>"/>
                                            <?php
                                        } else {
                                            ?>
                                            <input type="text" class="form-control" name="date2<?= $i ?>" value="<?= $d2 ?>"/>
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
                                 <a href="/str/v1/card/<?= $value['to_card'] ?>/ch/<?= $change ?>/car/reserve" target="_blank" data-toggle="tooltip" data-placement="left" title="Перейти"  ><textarea cols="20" rows="5" class="form-control"   id="place" name="place<?= $i ?>" disabled="disabled"><?= $value['place'] ?></textarea></a>

                                </div>
                            </td>

                            <td>
                                <div class="form-group">
                                    <textarea cols="20" rows="5" class="form-control" placeholder="№ приказа, дата"  id="prikaz" name="prikaz<?= $i ?>"><?= $value['prikaz'] ?></textarea>

                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <textarea cols="20" rows="5" class="form-control" placeholder="Примечание"  id="note" name="note<?= $i ?>"><?= $value['note'] ?></textarea>

                                </div>
                            </td>

                            <td>
    <?php
    //удалить можно только ту технику из командировки,которая была добавлена сегодня а технику добавленную несколько дней назад-нет
    //если техника добавлена в командировку автоматически - ее удалить нельзя
    if ($d_insert == date("Y-m-d") && $value['is_auto_create']==0) {
        ?>
                                    <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/car/trip/<?= $value['id'] ?>"><button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="bottom" title="Удалить"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></a>
                                    <?php
                                }
                                if($value['is_auto_create']==1){
                                    echo 'Командировка создана автоматически';

                                }
                                ?>


                            </td>

                        </tr>
                <input type="hidden" name="id_tripcar<?= $i ?>" value="<?= $value['id'] ?>">
    <?php

}
?>
                <input type="hidden" name="count" value="<?= $i ?>">


                </tbody>


            </table>
                  <input type="hidden" name="_METHOD" value="PUT"/>
        </div>
      <center>
        <div class="row">
            <div class="form-group">
                 <button type="submit" class="btn btn-primary">Сохранить изменения</button>
            </div>
        </div>
    </center>
        <?php
if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
    ?>
                </fieldset>
                    <?php
                }
                ?>
    </form>
</div>

