
<div>
    <!-- Навигация -->
    <ul class="nav nav-tabs" role="tablist" id="nav-tabs-user">

        <li class="active"><a href="/str/" aria-controls="change1" role="tab" >Запросы</a></li>

        <?php
        if ($_SESSION['uid'] == 1 || $_SESSION['uid'] == 32) {//admin rcu
            ?>
            <li><a href="/str/user" aria-controls="change1" role="tab" >Пользователи</a></li>
            <?php
        }
        ?>

    </ul>
    <!-- Содержимое вкладок -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" >

            <ul class="nav nav-tabs noprint" id="tabs">



                <?php
                /* ---------  авторизован РЦУ -------- */

                if ($_SESSION['ulevel'] == 1) {
                    //вкладка УМЧС/ЦП - открыта по умолчанию
                    if ($type == 1) {//basic
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_ch/1" >УМЧС</a></li>
                    <?php
                    //вкладка РОСН
                    if ($type == 2) {//basic
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_ch/2" >РОСН</a></li>
                    <?php
                    //вкладка УГЗ
                    if ($type == 3) {//basic
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_ch/3" >УГЗ</a></li>
                    <?php
                                        //вкладка Авиация
                    if ($type == 4) {//basic
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_ch/4" >Авиация</a></li>
                        <?php
                }
                /* ---------  авторизована область-------- */ elseif ($_SESSION['ulevel'] == 2) {
                    if ($_SESSION['note'] == 8) {//РОСН
                        //вкладка РОСН
                        ?>
                        <li class="active">  <a href="/str/builder/basic/inf_ch/2" >РОСН</a></li>
                        <?php
                    } elseif ($_SESSION['note'] == UGZ) {//UGZ
                        //вкладка РОСН
                        ?>
                        <li class="active">  <a href="/str/builder/basic/inf_ch/3" >УГЗ</a></li>
                        <?php
                    }
                     elseif ($_SESSION['note'] == AVIA) {//Avia
                        //вкладка РОСН
                        ?>
                        <li class="active">  <a href="/str/builder/basic/inf_ch/4" >Авиация</a></li>
                        <?php
                    }
                    
                    else {//УМЧС
                       
                           //вкладка УМЧС/ЦП - открыта по умолчанию
                    if ($type == 1) {//basic
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_ch/1" >УМЧС</a></li>
                    <?php
                    //вкладка РОСН
                    if ($type == 2) {//basic
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_ch/2" >РОСН</a></li>
                    <?php
                    //вкладка УГЗ
                    if ($type == 3) {//basic
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_ch/3" >УГЗ</a></li>
                    <?php
                                        //вкладка Авиация
                    if ($type == 4) {//basic
                        ?>
                        <li class="active">
                            <?php
                        } else {
                            ?>
                        <li>
                            <?php
                        }
                        ?>
                        <a href="/str/builder/basic/inf_ch/4" >Авиация</a></li>
                        <?php
                    }
                }
                /* ---------  авторизована ГРОЧС-------- */ elseif ($_SESSION['ulevel'] == 3) {
                    if ($_SESSION['note'] == 8) {//РОСН
                        //вкладка РОСН
                        ?>
                        <li class="active">  <a href="/str/builder/basic/inf_ch/2" >РОСН</a></li>
                        <?php
                    } elseif ($_SESSION['note'] == UGZ) {//UGZ
                        //вкладка РОСН
                        ?>
                        <li class="active">  <a href="/str/builder/basic/inf_ch/3" >УГЗ</a></li>
                        <?php
                    }
                      elseif ($_SESSION['note'] == AVIA) {//Avia
                        //вкладка РОСН
                        ?>
                        <li class="active">  <a href="/str/builder/basic/inf_ch/4" >Авиация</a></li>
                        <?php
                    }
                    else {//УМЧС
                        ?>
                        <li class="active"> <a href="/str/builder/basic/inf_ch/1" >УМЧС</a></li>
                        <?php
                    }
                }
                /* ---------  авторизована ПАСЧ-------- */ elseif ($_SESSION['ulevel'] == 4) {
                    if ($_SESSION['note'] == UGZ) {//UGZ
                        //вкладка РОСН
                        ?>
                        <li class="active">  <a href="/str/builder/basic/inf_ch/3" >УГЗ</a></li>
                        <?php
                    } 
                      elseif ($_SESSION['note'] == AVIA) {//Avia
                        //вкладка РОСН
                        ?>
                        <li class="active">  <a href="/str/builder/basic/inf_ch/4" >Авиация</a></li>
                        <?php
                    }
                    else {
                        ?>
                        <li class="active"> <a href="/str/builder/basic/inf_ch/1" >УМЧС</a></li>
                        <?php
                    }
                }
                ?>
            </ul>















