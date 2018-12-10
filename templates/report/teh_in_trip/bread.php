<div class="bread noprint">
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li id="page">Строевая записка</li>

            <?php
            $bread[]='Отчеты';
            $bread[]=$bread_active;
         //  print_r($bread_active);
           //echo $bread_active;
                    
            if (!empty($bread)) {
                $active = array_pop($bread);
                if (!empty($bread)) {
                    foreach ($bread as $key => $value) {
                        ?>
                        <li id="page"><?= $value ?> </li>
                            <?php
                        }
                    }
                    ?>
                <li ><?= $active ?> </li>
                <?php
            }
            ?>

        </ol>
    </div>
</div>