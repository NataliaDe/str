<!--Modal-->
<div id="myModal" class="modal fade">
    <div class="modal-dialog " id="modal-about">
        <div class="modal-content instruct">
            <div class="modal-header"><button class="close danger" type="button" data-dismiss="modal">×</button>
                <h4 class="modal-title">Справка</h4>
                Программное средство «Строевая записка» (Fenix_new)
                   <img src="/str/app/images/qr_rcu.png" width="55">
            </div>
            <div class="modal-body">

                <?php
                if (isset($_GET["file"]))
                    $filename = $_GET["file"];
                else {
                     $filename = "instruct.doc";
                     $filename1 = "ukazanie.doc";
                     $filename2 = "obnovlenie str 3.0.doc";
                }
                if (strpos($filename, "/") !== false)
                    die("Hack atempt detected!");
                if ($fileext = substr($filename, strrpos($filename, ".")) !== ".doc")
                    die("Поддерживается только чтение вордовских документов");

                $p = '/str/assets/doc/';
                $path = $p . $filename;
                $path1 = $p . $filename1;
                $path2 = $p . $filename2;
//echo $path;

                echo "Скачать <a href='$path'>руководство пользователя</a><br> ";
                echo "Скачать <a href='$path1'>Указание: письмо о внедрении в ОЭ (исх.№ 1/54/350.вн.)</a><br><br> ";
                 echo "Скачать <a href='$path2'>Письмо об обновлении ПС (исх.№ 589 от 01.04.2019)</a><br><br> ";
                ?>

            <p class="modal-header"></p>
                <b>Контактная информация:</b><br>
                <!--                руководитель - Шульга Максим Константинович, 8(017) 209 27 51<br>-->
                разработчик - Дещеня Наталья Александровна, 8(017) 209 27 48<br>
                 руководитель - Шульга Максим Константинович, 8(017) 209 27 51<br>
                Шилько Сергей Чеславович 8(017) 209 27 11<br>

            </div>

            <div class="modal-footer">

                <div class="copyright">
                    <span class='glyphicon glyphicon-copyright-mark'></span>«Республиканский центр управления и реагирования на чрезвычайные ситуации МЧС Республики Беларусь»
                </div>
                <br>
                <button class="btn btn-success" type="button" data-dismiss="modal">Закрыть</button></div>
        </div>
    </div>
</div>
