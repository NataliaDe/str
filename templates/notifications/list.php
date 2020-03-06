

    <div class="wrapper notifications-page">
        <div class="row">
            <?php
            if (isset($all_notifications) && !empty($all_notifications)) {

                foreach ($all_notifications as $value) {

                    ?>
            <div class="col-sm-12">
                <button id="<?=$value['id']?>" href="#" class="notify notif-block <?=($value['is_see'] == 0) ? 'new-notif': ''?>   dispatch" style="cursor: default">
                    <div class="date-notif">
                        <span><?= date('d.m.Y H:i:s', strtotime($value['date_action'])) ?></span>

                    </div>
                    <?= $value['msg_show'] ?>


                </button>
            </div>
            <?php
                }
                ?>


                <?php
            }
            else{
                ?>
              <div class="col-sm-12">
            У Вас нет уведомлений
              </div>
            <?php
            }

            ?>


        </div>
    </div>

