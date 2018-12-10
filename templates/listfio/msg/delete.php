
<div class="container">
    <div class="alert alert-danger">

        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <?php
        if(isset($warning)&& !empty($warning)){
            $warn=  implode(',', $warning);
            ?>
           <strong>Обращаем внимание! </strong>Данный работник в настоящее время находится: 
           <u> <?= $warn ?>!</u> При его удалении сведения по строевой записке могут стать неактуальными.<br>
        <?php
        }
        ?>
     
        
        Удалить выбранного работника из БД?
    </div>
</div>
