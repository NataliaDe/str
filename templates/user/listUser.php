<br>


<div class="container" id="container-query-result">
    <div class="col-lg-12">

        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Создать пользователя
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                <li> <a href="/str/user/new/1">РЦУРЧС</a></li>
                <li> <a href="/str/user/new/0">УМЧС/ЦП(кроме РОСН)</a></li>
                <li> <a href="/str/user/new/2">РОСН</a></li>
            </ul>
        </div>
        <br>
        <div class="table-responsive"  id="tbl-query-result">
            <br><br>
            <table class="table table-condensed   table-bordered" id="tbluser">
                <!-- строка 1 -->
                <thead>
                    <tr>
                        <th>Имя<br>пользователя</th>
                        <th>Логин</th>
                        <th>Может<br> заполнять/ред.<br>Админ</th>
                        <th >Уровень</th>
                        <th>Область</th>
                        <th>Г(Р)ОЧС</th>
                        <th>ПАСЧ</th>
                        <th>Ред.</th>
                        <th>Уд.</th>
                           <th>Открыть/закрыть доступ</th>

                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Имя<br>пользователя</th>
                        <th>Логин</th>
                        <th>Можжет<br> заполнять/ред.</th>
                        <th >Уровень</th>
                        <th>Область</th>
                        <th>Г(Р)ОЧС</th>
                        <th>ПАСЧ</th>
                        <th></th>
                        <th></th>
                           <th></th>

                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    foreach ($list_user as $row) {
                        if ($row['level_id'] == 1) {
                            ?>
                            <tr class="danger">
                                <?php
                            } elseif ($row['level_id'] == 2) {
                                ?>
                            <tr class="warning">
                                <?php
                            } elseif ($row['level_id'] == 3) {
                                ?>
                            <tr class="info">
                                <?php
                            } elseif ($row['level_id'] == 4) {
                                ?>
                            <tr class="success">
                                <?php
                            } else {
                                ?>
                            <tr>
                                <?php
                            }
                            ?>

                            <td><?= $row['name'] ?></td>
                            <td><?= $row['login'] ?><br><?= $row['password'] ?></td>
                            <td><?= $row['can_edit'] ?><br><?= $row['is_admin'] ?></td>
                            <td><?= $row['level'] ?></td>
                            <td><?= $row['region'] ?></td>
                            <td><?= $row['locorg'] ?></td>
                            <td><?= $row['divizion'] ?></td>
                            <td> <a href="/str/user/<?= $row['uid'] ?>"> <button class="btn btn-xs btn-primary " type="button"><i class="fa fa-pencil" aria-hidden="true"></i></button></a></td>
                            <td><a href="/str/user/delete/<?= $row['uid'] ?>"> <button class="btn btn-xs btn-primary" type="button"><i class="fa fa-trash" aria-hidden="true"></i></button></a></td>


                                                    <td>
                            <?php
                       
                            if($row['is_deny'] == 0){//доступ закрыт-можно открыть
                           ?>
                                       <a href="/str/listfio/open/<?= $row['uid'] ?>">закрыт <button type="button"  class="btn btn-xs btn-danger"  data-toggle="tooltip" data-placement="bottom" title="Открыть"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button></a>     
                            <?php
                            }
                            else{
                                ?>
                                       <a href="/str/listfio/close/<?= $row['uid'] ?>">открыт <button type="button" class="btn btn-xs btn-success"  data-toggle="tooltip" data-placement="bottom" title="Закрыть"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button></a>     
                                       <?php
                            }
                            ?>
                            
                        </td>
                            
                        </tr>

                        <?php
                    }
                    ?>

                </tbody>
            </table>

        </div>
    </div>
</div>