<?php
/* foreach ($record as $k => $r) {
  if (($r['idCard'] == 160) || ($r['idCard'] == 162) || ($r['idCard'] == 163)) {//ROSN
  $p = $r['localName'] . ' ' . $r['divizionName'];
  } else {
  $p = $r['divizionName'] . ' № ' . $r['divizionNum'];
  }
  } */
?>

<div>
    <!-- Навигация -->
    <ul class="nav nav-tabs" role="tablist" id="nav-tabs-user">
        <li ><a href="/str/v1/card/<?= $record_id ?>/ch/1/main" aria-controls="change1" role="tab" >1 смена</a></li>
        <li class="active"><a href="/str/v1/card/<?= $record_id ?>/ch/2/main" aria-controls="change2" role="tab">2 смена</a></li>
        <li><a href="/str/v1/card/<?= $record_id ?>/ch/3/main" aria-controls="change3" role="tab" >3 смена</a></li>

    </ul>
    <!-- Содержимое вкладок -->
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="change2">

            <ul class="nav nav-tabs noprint" id="tabs">

                <li class="active"><a href="#main" data-toggle="tab"><i class="fa fa-calendar-check-o fa-lg" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Главная"></i></a></li>
                <li><a href="/str/v1/card/<?= $record_id ?>/ch/2/ill" ><i class="fa fa-bed fa-lg" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Больничные"></i></a></li>
                <li><a href="/str/v1/card/<?= $record_id ?>/ch/2/holiday" ><i class="fa fa-bicycle fa-lg" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Отпуска"></i></a></li>
                <li><a href="/str/v1/card/<?= $record_id ?>/ch/2/trip" ><i class="fa fa-suitcase fa-lg" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Командировки"></i></a></li>
                <li><a href="/str/v1/card/<?= $record_id ?>/ch/2/other" ><i class="fa fa-exclamation-triangle fa-lg" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Другие причины"></i></a></li>
                <li><a href="/str/v1/card/<?= $record_id ?>/ch/2/car" ><i class="fa fa-car fa-lg" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="Техника"></i></a></li>
                <li><a href="/str/v1/card/<?= $record_id ?>/ch/2/storage" ><i class="fa fa-archive fa-lg" aria-hidden="true"  data-toggle="tooltip" data-placement="left" title="Склад"></i></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="main">
                    ...
                </div>
            </div>

        </div>

    </div>
</div>



