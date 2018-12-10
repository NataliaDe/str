
<div>
    <!-- Навигация -->
    <ul class="nav nav-tabs" role="tablist" id="nav-tabs-user">

        <li ><a href="/str/" aria-controls="change1" role="tab" >Построитель запросов</a></li>

        <?php
        if ($_SESSION['uid'] == 1) {//admin rcu
            ?>
            <li class="active"><a href="/str/user" aria-controls="change1" role="tab" >Пользователи</a></li>
            <?php
        }
        ?>

    </ul>
    <!-- Содержимое вкладок -->
    <div class="tab-content">
















