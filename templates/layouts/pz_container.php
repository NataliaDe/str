<div class="container" id="pz_container">
    <div class="row">
        <div class="col-lg-1 ">
            <?php
            if ((isset($sign)) && ($sign == 5)) {//отображаем только на стр main
                ?>
                <div class="dropdown">


                    <a id="dLabel" role="button" data-toggle="dropdown" class="btn btn-xs btn-primary" data-target="#" href="/page.html">
                        Отчеты <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                        <!--<li><a href="#">Some action</a></li>
                        <li><a href="#">Some other action</a></li
                        <li class="divider"></li>>-->
                        <li class="dropdown-submenu">
                            <a tabindex="-1" href="#">СЗ1</a>
                            <ul class="dropdown-menu">

                                <?php
                                //** БЛОК отображение СЗ1 в зависимости от авторизованного пользователя *************

                                if ($_SESSION['sub'] == 2) {//ЦП
                                } elseif ($_SESSION['sub'] == 0) {//umchs
                                    if (($_SESSION['ulevel'] == 4) || ($_SESSION['ulevel'] == 2) || ($_SESSION['ulevel'] == 3)) {//pasch
                                        ?>
                                        <li><a tabindex="-1" href="/str/v1/report/xls/sz1/grochs/<?= $id_grochs ?>">Г(Р)ОЧС</a></li>
                                        <?php
                                    }
                                    if ($_SESSION['ulevel'] == 2) {
                                        ?>
                                        <li><a href="/str/v1/report/xls/sz1/region/<?= $id_region ?>">Область</a></li>
                                        <?php
                                    }
                                } else {//rcu
                                    ?>
                                    <li><a tabindex="-1" href="/str/v1/report/xls/sz1/grochs/<?= $id_grochs ?>">Г(Р)ОЧС</a></li>
                                    <li><a href="/str/v1/report/xls/sz1/region/<?= $id_region ?>">Область</a></li>
                                    <li><a href="/str/v1/report/xls/sz1/rb">РБ</a></li>
                                    <?php
                                }
                                ?>

                                <!--<li class="dropdown-submenu">
                                    <a href="#">Even More..</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">3rd level</a></li>
                                        <li><a href="#">3rd level</a></li>
                                    </ul>
                                </li>-->


                            </ul>
                        </li>
                    </ul>
                </div>
            <?php }
            ?>

        </div>
        <div class="col-lg-2 ">
            <a href="/str"> <button class="btn-xs btn-primary" type="button" >Запросы</button></a>
        </div>

        <?php
        if ((($_SESSION['ulevel'] == 3) || ($_SESSION['ulevel'] == 4))) {
            ?>
            <div class="col-lg-2 ">
                <a href="/str/listfio"> <button class="btn-xs btn-warning" type="button" >Список смены</button></a>
            </div>
            <?php
        }
        ?>
    </div>
</div>