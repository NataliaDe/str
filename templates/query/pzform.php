
<!--<div class="container">-->
<div class="col-lg-12">

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php
                    if ($active == 'ch') {
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_ch/<?= $type ?>">Информация по сменам <span class="sr-only">(current)</span></a></li>


                    <?php
                    if ($type == 1 || $type == 2) {
                        if ($active == 'ch_cou') {
                            ?>
                            <li class="active">
                                <?php
                            } else {
                                ?>
                            <li>
                                <?php
                            }
                            ?>
                            <a href="/str/builder/basic/inf_ch_cou/<?= $type ?>">Информация по сменам ЦОУ, ШЛЧС <span class="sr-only">(current)</span></a></li>
                        <?php
                    }

                    if ($active == 'ill') {
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_ill/<?= $type ?>">Больничные <span class="sr-only">(current)</span></a></li>

                    <?php
                    if ($active == 'holiday') {
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_holiday/<?= $type ?>">Отпуска <span class="sr-only">(current)</span></a></li>
                    <?php
                    if ($active == 'trip') {
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_trip/<?= $type ?>"> Командировки <span class="sr-only">(current)</span></a></li>
                    <?php
                    if ($active == 'other') {
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_other/<?= $type ?>"> Другие причины <span class="sr-only">(current)</span></a></li>

                    <?php
                    if ($active == 'car') {
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_car/<?= $type ?>"> Информация по СЗ <span class="sr-only">(current)</span></a></li>

                    <?php
                    if ($active == 'car_big') {
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
<!--                        <a href="/str/builder/basic/inf_car_big/<?= $type ?>"> Техника <span class="sr-only">(current)</span></a></li>-->

                          <?php
                    if ($active == 'car_big_count') {
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_car_big_count/<?= $type ?>"> Техника <span class="sr-only">(current)</span></a></li>
                </ul>

            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</div>
