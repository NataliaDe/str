<?php
if (isset($unseen_notifications) && !empty($unseen_notifications)) {

    foreach ($unseen_notifications as $value) {

        ?>
        <div class="notification_list">
            <p><?= $value['msg_show'] ?></p>

            <p>
                <small><?= date('d.m.Y H:i:s', strtotime($value['date_action'])) ?></small>
            </p>
            <hr>
        </div>
        <?php
    }
}

?>