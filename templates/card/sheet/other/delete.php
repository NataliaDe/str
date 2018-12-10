
<form class="form-inline" role="form" id="formFillIll" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/other/<?= $idother ?>">

    <input type="hidden" name="_METHOD" value="DELETE"/>

    <div class="col-lg-12 col-lg-offset-5 col-md-offset-4 col-sm-offset-4">
        <div class="row">

            <div class="form-group">

                <div class="col-sm-offset-3 col-lg-offset-2 col-md-offset-2">
                    <button type="submit" class="btn btn-danger">  Удалить  </button>
                    <br>    <br>
                </div>
            </div>
        </div>
    </div>
</form>



