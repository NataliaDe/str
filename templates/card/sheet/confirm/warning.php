
<div class="container">
    <div class="alert alert-warning">

        <strong>Внимание!</strong>&nbsp;После выполнения данного действия редактирование данных будет недоступно. Смена <?= $change ?> будет установлена как дежурная. Вы действительно хотите подтвердить все введенные данне?
    </div>
</div>

<div class="container noprint">

    <div class="col-lg-12 col-lg-offset-2">
        <div class="row">
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-10 col-md-offset-4 col-lg-offset-3">
                    <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/confirm/next" >  <button type="button" class="btn btn-success">Продолжить</button></a>

                </div>
            </div>
        </div>
    </div>
</div>
<br>

<div class="container noprint">

    <div class="col-lg-12 col-lg-offset-2">
        <div class="row">
            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-10 col-md-offset-4 col-lg-offset-3">
                    <a href="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/main" >  <button type="button" class="btn btn-warning">Назад</button></a>
                </div>
            </div>
        </div>
    </div>
</div>





