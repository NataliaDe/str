
<form class="form-inline" role="form" id="formFillIll" method="POST" action="/str/v1/card/<?= $record_id ?>/ch/<?= $change ?>/holiday/deregister/<?= $idhol ?>">

    <input type="hidden" name="_METHOD" value="PUT"/>

    <center>
        <div class="row">

            <div class="form-group">

                    <button type="submit" class="btn btn-danger">  Снять с учета  </button>
                    <br>   
           
            </div>
        </div>
    </center>
</form>



