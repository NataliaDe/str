<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <?php
        mb_internal_encoding('utf-8');
        //автоматическое обновление страницы general
        if(isset($delay)){
            ?>
        <meta http-equiv="Refresh" content="<?= $delay ?>" />
        <?php
        }
        ?>

        <link rel="icon" href="/str/favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="/str/favicon.ico" type="image/x-icon" />
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <?php
        if(isset($title_name)){
            ?>
         <title><?= $title_name ?></title>
        <?php
        }
        else{
            ?>
         <title>Строевая записка</title>
        <?php
        }
        ?>

        <!-- Bootstrap -->
        <link href="/str/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="/str/app/css/jasny-bootstrap.min.css" rel="stylesheet">

        <link rel="stylesheet" href="/str/app/css/bootstrapValidator.min.css">

        <!--font-awesome -->
        <link rel="stylesheet" href="/str/app/css/font-awesome/css/font-awesome.min.css">

        <!--datepicker -->
        <link href="/str/app/css/datepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">


        <!-- manual -->
        <link href="/str/app/css/manual.css" rel="stylesheet">
        <link href="/str/app/css/signin.css" rel="stylesheet">
        <link href="/str/app/css/dashbroad.css" rel="stylesheet">

        <!-- DataTable CSS -->
        <link rel="stylesheet" type="text/css" href="/ss/assets/css/jquery.dataTables.css">

        <!-- Chosen CSS -->
        <link rel="stylesheet" href="/str/app/chosen_v1.8.2/chosen.css">



        <!-- select2 css - поиск в выпад списке -->
                <link rel="stylesheet" href="/str/app/js/select2/select2_1.css">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
<?php
           /* spectator can see all republic, can_edit NO. eye - sign of auth spectator */
            if ( isset($_SESSION['login']) && $_SESSION['login'] == 'spectator') {
                ?>
 <link href="/str/app/css/manual_spectrator.css" rel="stylesheet">
<?php
            }
?>

    </head>
    <body>







