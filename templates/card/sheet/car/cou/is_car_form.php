
<!--для ЦОУ, если нет техники для заступления - поставить отметку и сохранить-->
<form  role="form" method="POST" action="/str/v2/card/<?= $record_id ?>/ch/<?= $change ?>/is_car">
    <?php
    if ((($is_btn_confirm == 1) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 1) && ($is_open_update == 0) ) || ( ($is_btn_confirm == 0) && ($duty == 0) && ($is_open_update == 0) ) || ($is_btn_confirm == 1 && ($dateduty != date("Y-m-d"))) || ($_SESSION['can_edit'] == 0)) {
        ?>
            <fieldset disabled>
        <?php
    }
    ?>
            Если сегодня техника не заступает на дежурство - необходимо поставить соответствующую отметку и сохранить <br><br>
            <div class="form-group">
                <div class="checkbox checkbox-danger">
            <?php
            if (isset($is_car) && !empty($is_car)) {
                ?>
                        <input id="checkbox" type="checkbox" name="is_car" value='1' checked="">
        <?php
    } else {
        ?>
                        <input id="checkbox" type="checkbox" name="is_car" value='1'>
                        <?php
                    }
                    ?>

                    <label for="checkbox">
                        Сегодня техника не заступает на дежурство
                    </label>
                </div>
            </div>

            <center>
                <div class="row">
                    <div class="form-group">
                        <button type="submit" class="btn btn-danger">Сохранить</button>
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