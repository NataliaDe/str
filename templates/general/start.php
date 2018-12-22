
<div >
    <!-- Навигация -->
    <ul class="nav nav-tabs" role="tablist" id="nav-tabs-user">
        <?php
        if(!in_array($_SESSION['ulocorg'], locorg_umchs))  {//Если ЦОУ авторизован - эту вкладку не показываем
                    if ((isset($tab)) && ($tab == 1)) {// active
            ?>
            <li class="active">
                <?php
            } else {
                ?>
            <li>
                <?php
            }
            ?>
            <a href="/str/general/1" aria-controls="general_table" role="tab" >Общая информация</a></li>
            <?php
        }


                    if ((isset($tab)) && ($tab == 5)) {// active
            ?>
            <li class="active">
                <?php
            } else {
                ?>
            <li>
                <?php
            }
            ?>
            <a href="/str/general/5" aria-controls="general_table" role="tab" >Недочеты</a></li>

            <?php

               if ((isset($tab)) && ($tab == 4)) {// active
            ?>
            <li class="active">
                <?php
            } else {
                ?>
            <li>
                <?php
            }
            ?>
            <a href="/str/general/4" aria-controls="general_table" role="tab" >ЦОУ, ШЛЧС</a></li>

        <?php
        if ($_SESSION['ulevel'] == 1) {

            if ((isset($tab)) && ($tab == 2)) {// active
                ?>
                <li class="active">
                    <?php
                } else {
                    ?>
                <li>
                    <?php
                }
                ?>
                <a href="/str/general/2" aria-controls="general_table" role="tab" >Кратко</a></li>
            <?php
        }

            if ((isset($tab)) && ($tab == 3)) {// active
                ?>
                <li class="active">
                    <?php
                } else {
                    ?>
                <li>
                    <?php
                }
                ?>
               <a href="/str/general/3" aria-controls="general_table" role="tab" >Новости <span class="badge" style="background-color: red;">22.12.2018</span></a></li>



    </ul>
    <!-- Содержимое вкладок -->







