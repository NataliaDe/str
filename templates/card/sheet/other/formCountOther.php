
<form class="form-inline" role="form" id="formCountOther" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/other">
 <p  style="color:red;" > <strong>Внимание! При указании работника на данной вкладке дальнейший выбор 
        его для заступления в подразделении/смене будет НЕ ДОСТУПЕН!</strong></p>
        <br>   
 <?php
    if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
        ?>
        <fieldset disabled>
            <?php
        }
        ?>
        <div class="form-group">
            <label for="countеother">&nbsp;&nbsp;&nbsp;Количество работников,отсутствующих по др.причинам</label>

            <?php
            if ($tooltip == 1) {
                ?>
                <input type="text" class="form-control" placeholder="Количество работников,отсутствующих по др.причинам" id="countother" name="countother"   data-toggle="tooltip" data-placement="bottom" title="Без учета представленных в таблице" >
                <?php
            } else {
                ?>
                <input type="text" class="form-control" placeholder="Количество работников,отсутствующих по др.причинам"  id="countother" name="countother"  >
                <?php
            }
            ?>

        </div>
        <button class="btn btn-success " type="submit">OK</button>


        <?php
      if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm==1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
            ?>
        </fieldset>
        <?php
    }
    ?>


</form>













