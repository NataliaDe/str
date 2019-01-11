<br>
<div class="row">

  <div class="col-lg-6">
    <div class="input-group">
              <span class="input-group-btn">
        <button class="btn btn-primary" type="button" ><i class="fa fa-search" aria-hidden="true" ></i></button>
      </span>
      <input type="text" class="form-control" placeholder="Введите фамилию работника" id="studentSearch">

    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->


<br><br><br>

<div id="search-by-fio">
    <?php
    //echo dirname(__FILE__). '/result.php';
   include dirname(__FILE__) . '/result.php';
    ?>
</div>

<?php


