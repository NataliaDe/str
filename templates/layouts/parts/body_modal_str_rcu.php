<style>
    .card-text{
        font-size: 14px;
    }
</style>

<div class="modal-header"><button class="close danger" type="button" data-dismiss="modal">×</button>
    <h4 class="modal-title"><?= $head ?></h4>
</div>
<div class="modal-body">
    <div class="container">
        <!-- <h2>Card Image Overlay</h2> -->
        <!-- <p>Turn an image into a card background and use .card-img-overlay to overlay the card's text:</p> -->
<!--        <div class="card img-fluid" style="width:500px">-->
<!--            <img class="card-img-top" src="/str/app/images/General-icon.png" alt="Card image" style="width:100%">-->
<!--            <div class="card-img-overlay">-->


                <?php
                if (isset($main) && !empty($main)) {
                    foreach ($main as $row) {

                        if ($row['id_pos_duty'] == 1)
                            $god[] = mb_strtolower($row['rank_name']) . ' ' . $row['fio'];

                        elseif ($row['id_pos_duty'] == 2)
                            $od[] = mb_strtolower($row['rank_name']) . ' ' . $row['fio'];
                        elseif ($row['id_pos_duty'] == 3)
                            $z_od[] = mb_strtolower($row['rank_name']) . ' ' . $row['fio'];
                        elseif ($row['id_pos_duty'] == 4)
                            $st_pom_od[] = mb_strtolower($row['rank_name']) . ' ' . $row['fio'];
//                    elseif ($row['id_pos_duty'] == 6)
//                        $insp[] = $row['id_fio'];
//                    elseif ($row['id_pos_duty'] == 8)
//                        $other[] = $row['id_fio'];
                        elseif ($row['id_pos_duty'] == 10)
                            $st_ing_otsio[] = mb_strtolower($row['rank_name']) . ' ' . $row['fio'];
                        elseif ($row['id_pos_duty'] == 11)
                            $ing_connect[] = mb_strtolower($row['rank_name']) . ' ' . $row['fio'];

                         elseif ($row['id_pos_duty'] == 13)
                            $monitoring[] = mb_strtolower($row['rank_name']) . ' ' . $row['fio'];

                        ?>

                        <?php
                    }
                }
                else{
                    $empty_ch='смена не заступила';
                }


                if (isset($empty_ch) && !empty($empty_ch)) {
                   ?>
                <center><p style="color: red; font-size: 15px "><?=$empty_ch?></p></center>
                <?php
                }
                ?>


                <h4 class="card-title">Дежурная смена: #<?= $last_ch ?></h4>
                <h4 class="card-title">Телефон (ст. помощник оперативного дежурного): (017) 209-27-03</h4><br>
                <p class="card-text">Главный оперативный дежурный: <b><?=(isset($god) && !empty($god)) ? implode(', ', $god) : '-'?></b></p>
                <p class="card-text">Оперативный дежурный: <b><?=(isset($od) && !empty($od)) ? implode(', ', $od) : '-'?></b></p>
                <p class="card-text">Заместитель оперативного дежурного: <b><?=(isset($z_od) && !empty($z_od)) ? implode(', ', $z_od) : '-'?></b></p>
                <p class="card-text">Старший помощник оперативного дежурного: <b><?=(isset($st_pom_od) && !empty($st_pom_od)) ? implode(', ', $st_pom_od) : '-'?></b></p>
                <p class="card-text">Инженеры связи: <b><?=(isset($ing_connect) && !empty($ing_connect)) ? implode(', ', $ing_connect) : '-'?></b></p>
                <p class="card-text">Инженеры ОТСиО: <b><?=(isset($st_ing_otsio) && !empty($st_ing_otsio)) ? implode(', ', $st_ing_otsio) : '-'?></b></p>


                <p class="card-text">Инженеры мониторинга: <b><?=(isset($monitoring) && !empty($monitoring)) ? implode(', ', $monitoring) : '-'?></b></p>
    

<!--            </div>
        </div>-->
    </div>
</div>

<div class="modal-footer">

    <button class="btn btn-success" type="button" data-dismiss="modal">Закрыть</button></div>