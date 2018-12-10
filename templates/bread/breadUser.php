<div class="bread noprint">
  <div class="container-fluid">
        <ol class="breadcrumb">
            <li id="page">Строевая записка</li>
            <li class="active">Пользователи </li>

            <?php
            if (isset($sub)) {
                if ($type_query == 0) {//post
                    ?>
                    <li>Создать </li>
                    <?php
                } elseif ($type_query == 1) {
                    ?>
                    <li>Редактировать </li>
                    <?php
                }
                ?>

                <?php
                if ($sub == 0) {//umchs
                    ?>
                    <li class="active">Пользователь УМЧС </li>
                    <?php
                }


                if ($sub == 1) {//рцу
                    ?>
                    <li class="active">Пользователь РЦУРЧС</li>
                    <?php
                }

                if ($sub == 2) {//цп
                    ?>
                    <li class="active">Пользователь ЦП </li>
                        <?php
                    }
                }
                ?>

        </ol>
    </div>
</div>