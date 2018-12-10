
<form class="form-inline" role="form" id="formFillIll" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/other/save">
    <?php
  if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
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
                    <tr>
                        <th rowspan="2">№п/п</th>
                        <th rowspan="2">Ф.И.О</th>
                        <th colspan="2" >Дата отсутствия</th>
                          <th rowspan="2">Причина</th>
                        <th rowspan="2">Примечание</th>
<!--                         <th rowspan="2">Снять с учета</th>-->
                        <th rowspan="2">Удалить</th>
                    </tr>
                    <tr >
                        <th>c</th>
                        <th >по</th>
                     

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    foreach ($other as $row) {
                        $i++;
                        ?>
                        <tr>
                            <td><?= $i ?></td>

                           <td>
                                <div class="form-group">
                <?php
                                echo $row['fio'].',<br>'.'<i>'.$row['position_name'].'</i>'.',<br>'.'<i>'.$row['rank_name'].'</i>';
                             ?>
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
                                        <input type="text" class="form-control" name="date1<?= $i ?>" value="<?= $row['date1'] ?>"  style="width: auto;"/>
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
                                    <textarea cols="35" rows="7" class="form-control"  id="reasonother<?= $i ?>" name="reasonother<?= $i ?>"><?= $row['reason'] ?></textarea>

                                </div>
                            </td>

                            <td>
                                <div class="form-group">
                                    <textarea cols="25" rows="7" class="form-control"  id="noteother<?= $i ?>" name="noteother<?= $i ?>"><?= $row['note'] ?></textarea>

                                </div>
                            </td>
<!--<td>-->
                               <!-- <div class="btn-group" data-toggle="buttons">-->

                                    <?php
                                   // if ($row['deregister'] == 1) {
                                        ?>
                                     <!--   <div class="radio radio-danger">
                                            <input type="radio" name="deregister<? $i ?>" checked="checked"  id="radio5" value="1">
                                            <label for="radio5">
                                                Да
                                            </label>
                                        </div>

                                        <div class="radio radio-danger">
                                            <input type="radio" name="deregister<?$i ?>"   id="radio5" value="0">
                                            <label for="radio5">
                                                Нет
                                            </label>
                                        </div>-->


                                        <?php
                                   // } else {//если 0, не снят с учета
                                        ?>

<!--
                                        <div class="radio radio-danger">
                                            <input type="radio" name="deregister<?= $i ?>"   id="radio5" value="1">
                                            <label for="radio5">
                                                Да
                                            </label>
                                        </div>

                                        <div class="radio radio-danger">
                                            <input type="radio" name="deregister<?= $i ?>"   id="radio6" value="0" checked="checked">
                                            <label for="radio6">
                                                Нет
                                            </label>
                                        </div>-->
                                        <?php
                                   // }
                                    ?>
                                <!--</div>-->
<!--                            </td>-->
                            <td>
                                   <?php
                                //удалить можно только что добавленного работника, а работника добавленного несколько дней назад-нет
                                if($row['date_insert'] == date("Y-m-d")) {
                                    ?>
                                <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/other/<?= $row['id'] ?>"><button type="button" class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="bottom" title="Удалить"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></a>
                            <?php
                                }
                                ?>
                            </td>


                    <input type="hidden" class="form-control"   id="idother<?= $i ?>" name="idother<?= $i ?>" value="<?= $row['id'] ?>">
                    </tr>
                    <?php
                }
                ?>

                </tbody>
            </table>
<!--        </div>-->

        <input type="hidden" class="form-control"   id="countother" name="countother" value="<?= $i ?>">
        <input type="hidden" name="_METHOD" value="PUT"/>


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