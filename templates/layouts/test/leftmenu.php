     <div class="col-sm-3 col-md-2 sidebar">
<!--          <ul class="nav nav-sidebar">
            <li class="active"><a href="#">Overview</a></li>
            <li><a href="#">Reports</a></li>
            <li><a href="#">Analytics</a></li>
            <li><a href="#">Export</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="">Nav item</a></li>
            <li><a href="">Nav item again</a></li>
            <li><a href="">One more nav</a></li>
            <li><a href="">Another nav item</a></li>
            <li><a href="">More navigation</a></li>
          </ul>
          <ul class="nav nav-sidebar">
            <li><a href="">Nav item again</a></li>
            <li><a href="">One more nav</a></li>
            <li><a href="">Another nav item</a></li>
          </ul>-->



            <div class="container" id="container-menu">

                <ol class="tree">
                    <li class="menu-li">
                        <?php
                        foreach ($_SESSION['reg'] as $id_region => $name_region) {
                            ?>
                        <li class="menu-li">  <label for="folder4" id="label-checkbox"><?= $name_region ?></label> <input type="checkbox" id="folder4" class="input-li" checked/> 

                            <ol class="sub-tree">
                                <?php
                                foreach ($_SESSION['loc'][$id_region] as $grochs => $name_grochs) {
                                    ?>
                                    <li class="menu-li">  <label for="subfolder5" id="label-checkbox"><?= $name_grochs ?></label>  <input type="checkbox" id="subfolder4"  class="input-li"/>
                                        <ol class="pasp-ul">
                                            <?php
                                            foreach ($_SESSION['pasp'][$grochs] as $pasp => $name_pasp) {
                                                ?>

                                                <li class="menu-li"><label for="subfolder5-subfolder1"> <a href="/str/v1/card/<?= $pasp ?>/ch/1/main" class="menu-a-pasp" ><?= $name_pasp ?></a> </label> <input type="checkbox" id="subfolder4" class="input-li"/>
                                                    <?php
                                                }
                                                ?>
                                        </ol>
                                        <?php
                                    }
                                    ?>
                            </ol>
                            <?php
                        }
                        ?>
                    </li>


                </ol>

            </div>

        </div>