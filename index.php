<?php

session_start();
require_once dirname(__FILE__) . '/bootstrap.php';

use \RedBeanPHP\Facade as R;
use \Slim\Middleware;


/************************ CONSTANT ******************************/
define(ROSN, 8);//id_organ ROSN
define(UGZ, 9);//id_organ UGZ
define(AVIA, 12);//id_organ AVIACIA
const locorg_umchs= array(1=>145,2=>146,4=>147,5=>148,3=>149,7=>150,6=>151);//id of umchs. declare else in topmenu.php !!!!!
/************************  END CONSTANT ******************************/



/* * ********************* //авторизован ли пользователь   *********************** */
$is_auth = function(\Slim\Route $route) {
//echo "This is middleware!";
    $app = \Slim\Slim::getInstance();
    if (!isset($_SESSION['uid'])) {

        $app->redirect('/str/login');
    } else {
        if (strpos($app->request->getResourceUri(), 'user')) {
            if ($_SESSION['uid'] != 1 && $_SESSION['uid'] != 32) {//admin rcu
                if ($_SESSION['ulevel'] == 1) {
                    $app->redirect('/str/general/2');
                } else
                    $app->redirect('/str/general/3');
            }
        }
        elseif(strpos($app->request->getResourceUri(), 'listfio')){
            if($_SESSION['ulevel'] != 1){//не РЦУ
                if(strpos($app->request->getResourceUri(), 'add') || strpos($app->request->getResourceUri(), 'edit') || strpos($app->request->getResourceUri(), 'delete')){
                    if($_SESSION['is_deny'] == 0){//доступ на ред закрыт
                          $app->redirect('/str/listfio');
                    }

                }
            }

        }

//         if (!strpos($app->request->getResourceUri(), 'builder/basic')) {
//                     if ($app->request->isDelete() || $app->request->isPut() || $app->request->isPost()) {
//            if ($_SESSION['can_edit'] == 0) {
//
//               // $app->redirect('/str/modal');
//            }
//        }
//         }
    }
};

/* * ************ сообщение о выполнении действия *********************** */

function view_msg() {

    if (isset($_SESSION['msg'])) {
        $app = \Slim\Slim::getInstance();
        if ($_SESSION['msg'] == 1) {
            return $app->render('msg/ok.php');
//unset($_SESSION['msg']);не работает
        }
        if ($_SESSION['msg'] == 2) {
            return $app->render('msg/ok_delete.php');
        }
        if ($_SESSION['msg'] == 3) {
            return $app->render('msg/warning_user.php');
        }
    }
}

/* * *********************** ЗАПРОСНИК *************************** */

//запросник УМЧС-вкладка
function basic_query() {
    $cp_all = array(8, 9, 12); //id  РОСН, УГЗ, Авиация
    if ($_SESSION['ulevel'] == 1) {//rcu
        $region = R::getAll('SELECT * FROM ss.regions'); //список 1 -область
        $locorg = R::getAll('SELECT * FROM ss.caption WHERE orgid not IN (' . implode(",", $cp_all) . ') '); //кроме ЦП
        $diviz = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения
//  $diviz = R::getAll('SELECT DISTINCT id, name FROM divizions WHERE id=? OR id=?', array(2, 3)); //pasp, pasch
        /* $diviznum = R::getAll('SELECT rec.id as recid,rec.divizionNum,idDivizion, idLocOrg,d.id, d.name FROM records AS rec inner join divizions AS d
          on rec.idDivizion=d.id '); */
        $data['select'] = 0; //доступны все область
        $data['select_grochs'] = 0; //доступны все ГРОЧС
        $data['select_pasp'] = 0; //доступны все части
    }
    if ($_SESSION['ulevel'] == 2) {//oblast
        $cp = array(9, 10, 11, 12); //id ЦП без РОСН

        //$region = R::getAll('SELECT * FROM ss.regions WHERE id=:id', [':id' => $_SESSION['uregions']]);
          $region = R::getAll('SELECT * FROM ss.regions');//каждой области доступны все области

        //УМЧС
            $locorg = R::getAll('SELECT * FROM ss.caption WHERE orgid not IN (' . implode(",", $cp_all) . ') ');


//$diviz = R::getAll('SELECT DISTINCT idDivizion, idLocOrg,d.id, d.name FROM records AS rec inner join divizions AS d on rec.idDivizion=d.id '); //
//$diviz = R::getAll('SELECT DISTINCT id, name FROM divizions WHERE id=? OR id=?', array(2, 3)); //pasp, pasch
        $diviz = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения

        $data['select'] = 1; //по умолчанию выбрана область
        $data['select_grochs'] = 0; //доступны все ГРОЧС
        $data['select_pasp'] = 0; //доступны все части
    }
    if ($_SESSION['ulevel'] == 3) {//grochs
        $region = R::getAll('SELECT * FROM ss.regions WHERE id=:id', [':id' => $_SESSION['uregions']]);

        $locorg = R::getAll('SELECT * FROM ss.caption WHERE orgid not in (' . implode(",", $cp_all) . ') AND locorg_id= :locorg', [ ':locorg' => $_SESSION['ulocorg']]);


//$diviz = R::getAll('SELECT DISTINCT idDivizion, idLocOrg,d.id, d.name FROM records AS rec inner join divizions AS d on rec.idDivizion=d.id '); //
//$diviz = R::getAll('SELECT DISTINCT id, name FROM divizions WHERE id=? OR id=?', array(2, 3)); //pasp, pasch
        $diviz = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения

        $data['select'] = 1; //по умолчанию выбрана область
        $data['select_grochs'] = 1; //по умолчанию выбран район
        $data['select_pasp'] = 0; //доступны все части
    }
    if ($_SESSION['ulevel'] == 4) {//pasp
        $region = R::getAll('SELECT * FROM ss.regions WHERE id=:id', [':id' => $_SESSION['uregions']]);
        $locorg = R::getAll('SELECT * FROM ss.caption WHERE orgid not in (' . implode(",", $cp_all) . ') AND locorg_id= :locorg', [ ':locorg' => $_SESSION['ulocorg']]); //
//$diviz = R::getAll('SELECT DISTINCT idDivizion, idLocOrg,d.id, d.name FROM records AS rec inner join divizions AS d on rec.idDivizion=d.id '); //
//$diviz = R::getAll('SELECT  divizions.id, divizions.name FROM divizions inner join records ON divizions.id=records.idDivizion WHERE records.id= :urec', [ ':urec' => $_SESSION['urec']]); //pasp, pasch
        $diviz = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org WHERE rec.id=? order by rec.divizion_num ASC', array($_SESSION['urec'])); //все подразделения

        $data['select'] = 1; //по умолчанию выбрана область
        $data['select_grochs'] = 1; //по умолчанию выбран район
        $data['select_pasp'] = 1; //доступны все части
    }
    $data['region'] = $region;
    $data['locorg'] = $locorg;
    $data['diviz'] = $diviz;
    $data['maim'] = R::getAll('select * from str.maim');

//$data['diviznum'] = $diviznum;
    return $data;
}

//запросник РОСН-вклдака
function additional_query() {
    if ($_SESSION['ulevel'] == 1 || ($_SESSION['ulevel'] == 2 && $_SESSION['note']==NULL)) {//rcu либо УМЧС
        $data['select'] = 1;
        $data['select_grochs'] = 0;
        $region = R::getAll('SELECT id,name FROM ss.organs WHERE  id = :rosn', [':rosn' => 8]); // РОСН
        $locorg = R::getAll('select o.name as org_name,o.id as org_id, loc.name as locname, lo.id as locorg_id, '
                        . 'loc.id_region as region from ss.organs as o inner join ss.locorg as lo on o.id=lo.id_organ '
                        . 'inner join ss.locals as loc on loc.id=lo.id_local WHERE  o.id = :rosn', [ ':rosn' => 8]); // ОУ РОСН
        $data['locorg'] = $locorg;
    } elseif ($_SESSION['ulevel'] == 2) {//oblast весь РОСН
        $data['select'] = 1; //по умолчанию выбрана подразделение или ОУ
        $data['select_grochs'] = 0;
        $region = R::getAll('SELECT id,name FROM ss.organs WHERE  id = :rosn2', [':rosn2' => $_SESSION['note']]); // РОСН
        $locorg = R::getAll('select o.name as org_name,o.id as org_id, loc.name as locname, lo.id as locorg_id, '
                        . 'loc.id_region as region from ss.organs as o inner join ss.locorg as lo on o.id=lo.id_organ '
                        . 'inner join ss.locals as loc on loc.id=lo.id_local WHERE  o.id = :rosn ', [ ':rosn' => 8]); // ОУ РОСН
        $data['locorg'] = $locorg;
    } else {//rayon - ОУ
        $data['select'] = 1; //по умолчанию выбрана подразделение или ОУ
        $data['select_grochs'] = 1;
        $region = R::getAll('SELECT id,name FROM ss.organs WHERE  id = :rosn2', [':rosn2' => $_SESSION['note']]); // РОСН
        $locorg = R::getAll('select o.name as org_name,o.id as org_id, loc.name as locname, lo.id as locorg_id, '
                        . 'loc.id_region as region from ss.organs as o inner join ss.locorg as lo on o.id=lo.id_organ '
                        . 'inner join ss.locals as loc on loc.id=lo.id_local WHERE  o.id = :rosn and lo.id = :ou', [ ':rosn' => 8, ':ou' => $_SESSION['ulocorg']]); // ОУ РОСН
        $data['locorg'] = $locorg;
    }
    $data['region'] = $region;
    $data['maim'] = R::getAll('select * from str.maim');
    return $data;
}

function UGZ_query() {
     if ($_SESSION['ulevel'] == 1) {//rcu
        $data['select'] = 1;
        $data['select_grochs'] = 0;
        $region = R::getAll('SELECT id,name FROM ss.organs WHERE  id = :id_organ', [':id_organ' => UGZ]); // UGZ
        $locorg = R::getAll('select o.name as org_name,o.id as org_id, loc.name as locname, lo.id as locorg_id, '
                        . 'loc.id_region as region from ss.organs as o inner join ss.locorg as lo on o.id=lo.id_organ '
                        . 'inner join ss.locals as loc on loc.id=lo.id_local WHERE  o.id = :id_organ', [ ':id_organ' => UGZ]); // ОУ UGZ
        $data['locorg'] = $locorg;
    } elseif ($_SESSION['ulevel'] == 2) {//oblast весь UGZ
        $data['select'] = 1; //по умолчанию выбрана подразделение или ОУ
        $data['select_grochs'] = 0;
        $region = R::getAll('SELECT id,name FROM ss.organs WHERE  id = :id_organ', [':id_organ' => UGZ]); // UGZ
        $locorg = R::getAll('select o.name as org_name,o.id as org_id, loc.name as locname, lo.id as locorg_id, '
                        . 'loc.id_region as region from ss.organs as o inner join ss.locorg as lo on o.id=lo.id_organ '
                        . 'inner join ss.locals as loc on loc.id=lo.id_local WHERE  o.id = :id_organ ', [ ':id_organ' => UGZ]); // ОУ UGZ
        $data['locorg'] = $locorg;
    } else {// уровень 3,4
        $data['select'] = 1; //по умолчанию выбрана подразделение или ОУ
        $data['select_grochs'] = 1;
        $region = R::getAll('SELECT id,name FROM ss.organs WHERE  id = :id_organ', [':id_organ' => UGZ]); // UGZ
        $locorg = R::getAll('select o.name as org_name,o.id as org_id, loc.name as locname, lo.id as locorg_id, '
                        . 'loc.id_region as region from ss.organs as o inner join ss.locorg as lo on o.id=lo.id_organ '
                        . 'inner join ss.locals as loc on loc.id=lo.id_local WHERE  o.id = :id_organ and lo.id = :ou', [ ':id_organ' => UGZ, ':ou' => $_SESSION['ulocorg']]); // ОУ UGZ
        $data['locorg'] = $locorg;
    }
    $data['region'] = $region;
    $data['maim'] = R::getAll('select * from str.maim');
    return $data;
}

//запросник Фвиация-вкладка
function AVIA_query() {
    if ($_SESSION['ulevel'] == 1 || $_SESSION['ulevel'] == 2) {//rcu либо УМЧС
        $region = R::getAll('SELECT * FROM ss.regions where id = ?',array(3)); //г.Минск выбран по умолчанию
        $locorg = R::getAll('SELECT * FROM ss.caption WHERE orgid = :avia ', [':avia' => AVIA]);
        $diviz = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения
//  $diviz = R::getAll('SELECT DISTINCT id, name FROM divizions WHERE id=? OR id=?', array(2, 3)); //pasp, pasch
        /* $diviznum = R::getAll('SELECT rec.id as recid,rec.divizionNum,idDivizion, idLocOrg,d.id, d.name FROM records AS rec inner join divizions AS d
          on rec.idDivizion=d.id '); */
        $data['select'] = 1; //доступны все область
        $data['select_grochs'] = 1; //доступны все ГРОЧС
        $data['select_pasp'] = 0; //доступны все части
    }

    if ($_SESSION['ulevel'] == 3) {//grochs
        $region = R::getAll('SELECT * FROM ss.regions WHERE id=:id', [':id' => $_SESSION['uregions']]);

        $locorg = R::getAll('SELECT * FROM ss.caption WHERE orgid = :avia AND locorg_id= :locorg', [':avia' => AVIA, ':locorg' => $_SESSION['ulocorg']]);


//$diviz = R::getAll('SELECT DISTINCT idDivizion, idLocOrg,d.id, d.name FROM records AS rec inner join divizions AS d on rec.idDivizion=d.id '); //
//$diviz = R::getAll('SELECT DISTINCT id, name FROM divizions WHERE id=? OR id=?', array(2, 3)); //pasp, pasch
        $diviz = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения

        $data['select'] = 1; //по умолчанию выбрана область
        $data['select_grochs'] = 1; //по умолчанию выбран район
        $data['select_pasp'] = 0; //доступны все части
    }
    if ($_SESSION['ulevel'] == 4) {//pasp
        $region = R::getAll('SELECT * FROM ss.regions WHERE id=:id', [':id' => $_SESSION['uregions']]);
        $locorg = R::getAll('SELECT * FROM ss.caption WHERE orgid = :avia AND locorg_id= :locorg', [':avia' => AVIA, ':locorg' => $_SESSION['ulocorg']]); //
//$diviz = R::getAll('SELECT DISTINCT idDivizion, idLocOrg,d.id, d.name FROM records AS rec inner join divizions AS d on rec.idDivizion=d.id '); //
//$diviz = R::getAll('SELECT  divizions.id, divizions.name FROM divizions inner join records ON divizions.id=records.idDivizion WHERE records.id= :urec', [ ':urec' => $_SESSION['urec']]); //pasp, pasch
        $diviz = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org WHERE rec.id=? order by rec.divizion_num ASC', array($_SESSION['urec'])); //все подразделения

        $data['select'] = 1; //по умолчанию выбрана область
        $data['select_grochs'] = 1; //по умолчанию выбран район
        $data['select_pasp'] = 1; //доступны все части
    }
    $data['region'] = $region;
    $data['locorg'] = $locorg;
    $data['diviz'] = $diviz;
    $data['maim'] = R::getAll('select * from str.maim');

//$data['diviznum'] = $diviznum;
    return $data;
}

function cou_query() {

    $id_divizion_cou=array(8,9);

            /*--- where in  locorg isset cou or slhs -----*/
        $id_locorg=R::getAll('SELECT id_loc_org FROM ss.records WHERE id_divizion  IN (' . implode(",", $id_divizion_cou) . ') ');
        $mas_locorg=array();
        foreach ($id_locorg as $l) {
            $mas_locorg[]=$l['id_loc_org'];
        }
         $locorg = R::getAll('SELECT * FROM ss.caption WHERE locorg_id  IN (' . implode(",", $mas_locorg) . ') ');
        /*--- END where in  locorg isset cou or slhs -----*/


    if ($_SESSION['ulevel'] == 1) {//rcu
        $region = R::getAll('SELECT * FROM ss.regions'); //список 1 -область



        $diviz = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org where d.id = ? or d.id = ? order by rec.divizion_num ASC',array(8,9)); //ЦОУ, ШЛЧС

        $data['select'] = 0; //доступны все область
        $data['select_grochs'] = 0; //доступны все ГРОЧС
        $data['select_pasp'] = 0; //доступны все части
    }
    if ($_SESSION['ulevel'] == 2) {//oblast
        $cp = array(9, 10, 11, 12); //id cp without rosn

        //$region = R::getAll('SELECT * FROM ss.regions WHERE id=:id', [':id' => $_SESSION['uregions']]);
          $region = R::getAll('SELECT * FROM ss.regions');//каждой области доступны все области

        //umchs
          //  $locorg = R::getAll('SELECT * FROM ss.caption WHERE orgid not IN (' . implode(",", $cp) . ') ');


//$diviz = R::getAll('SELECT DISTINCT idDivizion, idLocOrg,d.id, d.name FROM records AS rec inner join divizions AS d on rec.idDivizion=d.id '); //
//$diviz = R::getAll('SELECT DISTINCT id, name FROM divizions WHERE id=? OR id=?', array(2, 3)); //pasp, pasch
        $diviz = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения

        $data['select'] = 0; //по умолчанию выбрана область
        $data['select_grochs'] = 0; //доступны все ГРОЧС
        $data['select_pasp'] = 0; //доступны все части
    }
    if ($_SESSION['ulevel'] == 3) {//grochs
        $region = R::getAll('SELECT * FROM ss.regions WHERE id=:id', [':id' => $_SESSION['uregions']]);

       // $locorg = R::getAll('SELECT * FROM ss.caption WHERE orgid not in (' . implode(",", $cp_all) . ') AND locorg_id= :locorg', [ ':locorg' => $_SESSION['ulocorg']]);


//$diviz = R::getAll('SELECT DISTINCT idDivizion, idLocOrg,d.id, d.name FROM records AS rec inner join divizions AS d on rec.idDivizion=d.id '); //
//$diviz = R::getAll('SELECT DISTINCT id, name FROM divizions WHERE id=? OR id=?', array(2, 3)); //pasp, pasch
        $diviz = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения

        $data['select'] = 0; //по умолчанию выбрана область
        $data['select_grochs'] = 0; //по умолчанию выбран район
        $data['select_pasp'] = 0; //доступны все части
    }
    if ($_SESSION['ulevel'] == 4) {//pasp
        $region = R::getAll('SELECT * FROM ss.regions WHERE id=:id', [':id' => $_SESSION['uregions']]);
       // $locorg = R::getAll('SELECT * FROM ss.caption WHERE orgid not in (' . implode(",", $cp_all) . ') AND locorg_id= :locorg', [ ':locorg' => $_SESSION['ulocorg']]); //
//$diviz = R::getAll('SELECT DISTINCT idDivizion, idLocOrg,d.id, d.name FROM records AS rec inner join divizions AS d on rec.idDivizion=d.id '); //
//$diviz = R::getAll('SELECT  divizions.id, divizions.name FROM divizions inner join records ON divizions.id=records.idDivizion WHERE records.id= :urec', [ ':urec' => $_SESSION['urec']]); //pasp, pasch
        $diviz = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org WHERE rec.id=? order by rec.divizion_num ASC', array($_SESSION['urec'])); //все подразделения

        $data['select'] = 0; //по умолчанию выбрана область
        $data['select_grochs'] = 0; //по умолчанию выбран район
        $data['select_pasp'] = 0; //доступны все части
    }
    $data['region'] = $region;
    $data['locorg'] = $locorg;
    $data['diviz'] = $diviz;
    $data['maim'] = R::getAll('select * from str.maim');

//$data['diviznum'] = $diviznum;
    return $data;
}


/* * ****************************** ОТЧЕТ sz1 ********************************* */

//id для ссылки в sz1 отчет по ГРОЧС
function get_id_grochs($id_pasp) {
    return R::getCell('select id_loc_org FROM ss.records WHERE id=?', [$id_pasp]);
}

//id для ссылки в sz1 отчет по области
function get_id_region($id_pasp) {
    return R::getCell('select locals.id_region FROM ss.records inner join ss.locorg on locorg.id=records.id_loc_org '
            . 'inner join ss.locals on locals.id=locorg.id_local WHERE records.id=?', [$id_pasp]);
}

/* * ********************* формирование Bread в зависимости от адресной строки ********************************* */

function getBread() {
    $app = \Slim\Slim::getInstance();
    $bread_array = array();
    if (strpos($app->request->getResourceUri(), 'general')) {
        $bread_array[] = 'Информация о заполнении';
    }
    if (strpos($app->request->getResourceUri(), 'builder')) {
        $bread_array[] = 'Запросы';
    }
//    if (strpos($app->request->getResourceUri(), 'user')) {
//        $bread_array[]='Пользователи';
//    }
    return $bread_array;
}

/* * выполнялось 1 раз */
/* $app->get('/insert_cardchstr', function () use ($app) {
  $cardchstr = R::getAll('select id FROM records ');
  foreach ($cardchstr as $row) {
  $a = R::dispense('cardchstr');
  $a->id_card = $row['id'];
  $a->ch = 0;
  R::store($a);
  }
  }); */

/*  выполнялось 1 раз - добавить всю технику в car   */
//$app->get('/insert_car', function () use ($app) {
//  $car = R::getAll('select id FROM ss.technics where id_record = :id_record',[':id_record'=>747]);
//  foreach ($car as $row) {
//	  for($i=1;$i<=3;$i++){
//		  $a = R::dispense('car');
//          $a->id_teh = $row['id'];
//          $a->ch = $i;
//		  $a->dateduty=date('Y-m-d');
//		  $a->id_tehcls=1;
//		  $a->petrol=0;
//		  $a->powder=0;
//		  $a->diesel=0;
//		  $a->foam=0;
//		  $a->id_to=3;
//		  $a->is_repair=0;
//		  $a->id_type=3;
//		  $a->comments='-';
//		  $a->last_update=date("Y-m-d H:i:s");
//		  $a->id_user=1;
//		  $a->is_new=1;
//          R::store($a);
//	  }
//  }
//  });

$app->get('/', function () use ($app) {
    if (isset($_SESSION['uid'])) {
        if (($_SESSION['note'] != NULL) && ($_SESSION['note'] == 8)) {//только РОСН
            $data['type'] = 2;
           // $app->redirect('/str/builder/basic/inf_ch/2', $data);
        }
        elseif($_SESSION['note'] == UGZ) {
            $data['type'] = 3;
           // $app->redirect('/str/builder/basic/inf_ch/3', $data);
        }
                elseif($_SESSION['note'] == AVIA) {
            $data['type'] = 4;
            //$app->redirect('/str/builder/basic/inf_ch/4', $data);
        }
        else {
            $data['type'] = 1;

        }
         $app->redirect('/str/builder/basic/inf_ch/'.$data['type'], $data);
    } else {
        $app->redirect('/str/login');
    }
});

/* ------------ инф.о заполненности строевой записки по подразделениям, недочеты, новости --------------*/
$app->group('/general', $is_auth, function () use ($app) {

    //выбор списка подразделений в зависимости от авторизованного пользоавтеля
    function getGeneralTable($id_card_with_error=NULL) {
        $cp=array(ROSN,UGZ,AVIA);
        if ($_SESSION['ulevel'] == 1) {//rcu
            //строевая по РБ
            $sql="select * FROM general_table";
            //return R::getAll('select * FROM general_table');
        }
        if ($_SESSION['ulevel'] == 2) {//obl
            //РОСН
            if ($_SESSION['note'] == 8) {
                //строевая по всему РОСН
                $sql="select * FROM general_table WHERE organ_id = ".$_SESSION['note'];
                //return R::getAll('select * FROM general_table WHERE organ_id = ?', array($_SESSION['note']));
            }
               elseif ($_SESSION['note'] == UGZ) {//UGZ
                //строевая по вскму УГЗ
                   $sql="select * FROM general_table WHERE organ_id = ".$_SESSION['note'];
                //return R::getAll('select * FROM general_table WHERE organ_id = ?', array($_SESSION['note']));
            }
            else {
                //строевая по obl
                $sql="select * FROM general_table WHERE id_region= ". $_SESSION['uregions'] ." AND organ_id NOT IN (".  implode(',', $cp).") ";
              //  return R::getAll('select * FROM general_table WHERE id_region=? AND organ_id NOT IN ('.  implode(',', $cp).')', array($_SESSION['uregions']));
            }
        }

        if ($_SESSION['ulevel'] == 3) {//grochs
            //РОСН
            if ($_SESSION['note'] == 8) {
                //строевая по obl
                $sql="select * FROM general_table WHERE organ_id = ". $_SESSION['note']." and id_locorg= ". $_SESSION['ulocorg'];
             //   return R::getAll('select * FROM general_table WHERE organ_id = ? and id_locorg=?', array($_SESSION['note'], $_SESSION['ulocorg']));
            } elseif ($_SESSION['note'] != NULL && $_SESSION['note'] != 8) {//ЦП
            $sql="select * FROM general_table WHERE organ_id = ".$_SESSION['note']." and id_locorg= ".$_SESSION['ulocorg'];
               // return R::getAll('select * FROM general_table WHERE organ_id = ? and id_locorg=?', array($_SESSION['note'], $_SESSION['ulocorg']));
            } else {
                //строевая по grochs
                $sql="select * FROM general_table WHERE id_locorg= ".$_SESSION['ulocorg'];
                //return R::getAll('select * FROM general_table WHERE id_locorg=?', array($_SESSION['ulocorg']));
            }
        }
        if ($_SESSION['ulevel'] == 4) {//pasp
            //строевая по pasp
            $sql="'select * FROM general_table WHERE id_record= ".$_SESSION['urec'];
            //return R::getAll('select * FROM general_table WHERE id_record=?', array($_SESSION['urec']));
        }

        if($id_card_with_error != NULL && !empty($id_card_with_error)){
              if ($_SESSION['ulevel'] == 1) {//rcu
                  $sql=$sql." WHERE id_record IN (".implode (",", $id_card_with_error).")";
              }
              else{
                  $sql=$sql." and id_record IN (".implode (",", $id_card_with_error).")";
              }

        }

        return R::getAll($sql);
    }

    //Общая инф о заполненности строевых ЦОУ
        function getGeneralTableCou() {
        $cp=array(ROSN,UGZ,AVIA);
        if ($_SESSION['ulevel'] == 1) {//rcu
            //строевая по РБ
            return R::getAll('select * FROM general_table_cou');
        }
        if ($_SESSION['ulevel'] == 2) {//obl

                //все ЦОУ по obl
                return R::getAll('select * FROM general_table_cou WHERE id_region=? ', array($_SESSION['uregions']));

        }

        if ($_SESSION['ulevel'] == 3) {//grochs

                //строевая по grochs
                return R::getAll('select * FROM general_table_cou WHERE id_locorg=?', array($_SESSION['ulocorg']));

        }
        if ($_SESSION['ulevel'] == 4) {//pasp
            //строевая по pasp
            return R::getAll('select * FROM general_table_cou WHERE id_record=?', array($_SESSION['urec']));
        }
    }

    //вкладка о заполненности строевой записки
    $app->get('/:tab', function ($tab) use ($app) {

        if (isset($_SESSION['uid'])) {
            //$data['delay'] = 60;   //установить время автоматического обновления страницы - 60сек

            $data['tab'] = $tab;

            $data['locorg_umchs']=locorg_umchs;

            /* ---------------------- Выбор данных из БД -------------------------- */


            /* small table with cou grochs+umchs */

            /* count cou by region */
            $count_cou_by_region = R::getAll('select * from count_cou_by_region');
            $cnt_cou = array();
            foreach ($count_cou_by_region as $value) {
                $cnt_cou[$value['id_region']] = $value['cnt'];
            }

            /* fill cou str */
            $small_table_cou = R::getAll('select * from small_table_cou');
            $fill_cou = array();
            foreach ($small_table_cou as $value) {
                $fill_cou[$value['id_region']] = $value['yes_fill'];
            }

            $data['cnt_cou'] = $cnt_cou;
            $data['fill_cou'] = $fill_cou;


            /* END small table with cou grochs+umchs */


            if ($tab == 1 || $tab == 5 || $tab ==4) {
                $data['time_allow_open'] = time_allow_open(); //Время, после которого у областей нет возможности открыть доступ на редактирование
            }
            if ($tab == 1) { //вкладка общая инф активна
                $data['general'] = getGeneralTable();
            } elseif ($tab == 5) {//вкладка Недочеты
                // Недочеты
                $error = R::getAll("select id_card, case when(t.hsv <> 0) then concat('ШСВ') else concat('да')   end as msg"
                                . " from card_with_error as t where t.shtat_raznost <> 0 or t.listls_raznost <> 0 or t.vacant_raznost <> 0 or t.face_raznost <> 0 or t.br_raznost <> 0 or t.hsv <> 0");
                $data['error'] = $error;
                if (!empty($error)) {//есть подразделения с недочетома
                    foreach ($error as $value) {//id подразделений, где есть недочеты
                        $id_card_with_error[] = $value['id_card'];
                    }
                    $data['general'] = getGeneralTable($id_card_with_error); //выбираем записи только с недочетами
                } else
                    $data['general'] = array(); //нет подразделений с недочетами
            }
            elseif ($tab == 2) { //вкладка кратко активна
                $data['general'] = R::getAll('select * from small_table'); //группировка по областям- УМЧС
                $data['general_2'] = R::getAll('select * from small_table_2'); //РОСН,УГЗ, ГИИ, ИППК,Авиация
            } elseif ($tab == 4) {//вкладка ЦОУ, ШЛЧС
                $data['general'] = getGeneralTableCou();
            }
            /* ---------------------- КОНЕЦ Выбор данных из БД -------------------------- */

            $data['duty_ch'] = duty_ch(); //номер деж смены
            $data['bread'] = getBread();

            $app->render('layouts/header.php', $data);
            $app->render('layouts/menu.php');
            $app->render('bread/bread.php', $data);
            $app->render('general/start.php', $data);

            if ($tab != 3) {//кроме вкладки новостей
                //     if ($data['general']) {
                if ($tab == 1) {//общая информация
                    // $data['count_open'] = R::getAll('SELECT * FROM openupd'); //сколько раз был открыт доступ на редактирование
                    $app->render('general/general_table.php', $data);
                } elseif ($tab == 5) {//вкладка Недочеты
                    $app->render('general/general_table_error.php', $data);
                } elseif ($tab == 2) {// кратко
                    if ($_SESSION['ulevel'] == 1) {
                        $app->render('general/small_table.php', $data);
                    } else
                        $app->redirect('/str/general/1');
                }
                elseif ($tab == 4) {//вкладка ЦОУ, ШЛЧС
                    $app->render('general/cou_table.php', $data);
                }
                //  } else
                //   $app->render('msg/empty_data.php');
            } elseif ($tab == 3) {//вклдка новости
                $app->render('general/news.php', $data);
            }

            $app->render('general/end.php');
            $app->render('layouts/footer.php');
        } else {
            $app->redirect('/str/login');
        }
    });
});
/* ------------ КОНЕЦ  инф.о заполненности строевой записки по подразделениям, недочеты, новости --------------*/



/* * *********** окно - нет права на выполнение этого действия ****************** */
$app->get('/modal', function () use ($app) {
//
    $app->render('layouts/header.php');
    $app->render('layouts/menu.php');
//$app->render('formLogin.php', array('id' => $id));
    $app->render('msg/modal.php');
    $app->render('layouts/footer.php');
});


/* * ***************** АВТОРИЗАЦИЯ, ВЫХОД ******************** */
$app->get('/login', function () use ($app) {

    $data['no_footer'] = 1;
    $app->render('layouts/header.php');
    $app->render('layouts/menuLogin.php');
//$app->render('formLogin.php', array('id' => $id));
    $app->render('login/formLogin.php');
    $app->render('layouts/footer.php', $data);
});

$app->post('/login', function () use ($app, $log) {

    $u = $app->request()->post('login');
    $p = $app->request()->post('password');

    $limited_area=R::getAll("select id_user from limitedarea ");
    $limited_area_arr=array();
    $time_watch='11:00:00';//можно авторизоваться только после 11 00, если в БД не указано иное
    foreach ($limited_area_arr as $value) {
        $limited_area_arr[]=$value['id_user'];
        $time_watch=$value['time_watch'];
    }

    $user = R::findOne('user', 'login = ? and password = ?', [$u, $p]);

    if ($user) {

        if(in_array($user['id'], $limited_area_arr)  && (date("H:i:s") < $time_watch) ){
             $app->redirect('login');
             exit();
        }

        $_SESSION['uid'] = $user['id'];
        $_SESSION['ulevel'] = $user['levels_id'];
        $_SESSION['uregions'] = $user['regions_id']; //id region
        $_SESSION['ulocorg'] = $user['locorg_id']; //idCard
        $_SESSION['urec'] = $user['records_id']; //id record
        $_SESSION['note'] = $user['note']; //идентификатор РОСН
        $_SESSION['login'] = $u;
        $_SESSION['psw'] = $p;
        $_SESSION['can_edit'] = $user['can_edit'];
        $_SESSION['sub'] = $user['sub']; //подразделение ЦП или УМЧС
        $_SESSION['uname'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['is_deny'] = $user['is_deny'];


        /* umchs level=oblast, admin can auth as cou of region */
                if($_SESSION['ulevel']==2 && $_SESSION['note']==NULL && $_SESSION['is_admin']==1 ){
                    /* get id_user of cou region */
                    $user_cou=R::findOne('user', 'regions_id = ? and locorg_id = ?', [$_SESSION['uregions'], locorg_umchs[$_SESSION['uregions']]]);

                    if(!empty($user_cou)){
                        $_SESSION['id_user_region_cou']=$user_cou['id'];
                    }
                }

                /* any user can auth as spectator */
                    $user_spectator=R::findOne('user', 'login = ? and password = ?', ['spectator', 'spectator']);

                    if(!empty($user_spectator)){
                        $_SESSION['id_user_spectator']=$user_spectator['id'];
                    }


//*************************************************************   menu ***************************************

        $cp_umchs_rcu = array(8, 9, 10, 11, 12,  5); //id ЦП+'РЦУ'
        $cp = array(9, 10, 11, 12); //id ЦП без РОСН

        if ($_SESSION['ulevel'] == 1) {//РЦУРЧС

            /*             * ***************  меню УМЧС без ЦП *************** */
            $menu = R::getAll('SELECT * FROM menu WHERE organ_id NOT IN (' . implode(",", $cp_umchs_rcu) . ')  ORDER BY region, locorg, divizion_num ASC');

            /*             * ***************  меню РОСН *************** */
            $menurosn = R::getAll('SELECT * FROM menurosn ORDER BY name ASC');

            /*             * ***************  меню  ЦП без РОСН *************** */
            $menu_cp = R::getAll('SELECT * FROM menu WHERE organ_id  IN (' . implode(",", $cp) . ')  ORDER BY region, locorg, divizion_num ASC');

            if (isset($menu) && !empty($menu)) {
                prepareMenu($menu);
            }

            if (isset($menurosn) && !empty($menurosn)) {//v > v.1.0
                $_SESSION['menurosn'] = $menurosn; //v > v.1.0
            }

            if (isset($menu_cp) && !empty($menu_cp)) {
                prepareMenuCp($menu_cp);
            }
        } else {
            if ($_SESSION['ulevel'] == 2) {//уровень области
                if ($_SESSION['note'] == NULL) {//не ЦП
                    $menu = R::getAll('SELECT * FROM menu WHERE region_id = :reg_id AND organ_id NOT IN (' . implode(",", $cp_umchs_rcu) . ') ORDER BY  locorg ASC', [':reg_id' => $_SESSION['uregions']]
                    );
                } else {//ЦП
                    if ($_SESSION['note'] == 8) {//ROSN
                        $menurosn = R::getAll('SELECT * FROM menurosn ORDER BY name ASC'
                        );
                    }
                    elseif ($_SESSION['note'] == UGZ) {// UGZ
                               $menu_cp = R::getAll('SELECT * FROM menu WHERE organ_id = :organ_id  ORDER BY  locorg ASC', [':organ_id' => UGZ]
                        );
                    }
                }
            } elseif ($_SESSION['ulevel'] == 3) {//уровень района
                if ($_SESSION['note'] == NULL) {//не ЦП
                    $menu = R::getAll('SELECT * FROM menu WHERE locorg_id = :locorg_id AND organ_id NOT IN (' . implode(",", $cp_umchs_rcu) . ') ORDER BY  locorg ASC', [':locorg_id' => $_SESSION['ulocorg']]
                    );
                } else {//ЦП
                    if ($_SESSION['note'] == 8) {//ROSN
                        $menurosn = R::getAll('SELECT * FROM menurosn WHERE locorg_id = :id', [':id' => $_SESSION['ulocorg']]
                        );
                    } else {// ЦП кроме РОСН
                        $menu_cp = R::getAll('SELECT * FROM menu WHERE locorg_id = :locorg_id  ORDER BY  locorg ASC', [':locorg_id' => $_SESSION['ulocorg']]
                        );
                    }
                }
            } elseif ($_SESSION['ulevel'] == 4) {//уровень ПАСП
                if ($_SESSION['note'] == NULL) {//не ЦП
                    $menu = R::getAll('SELECT * FROM menu WHERE record_id = :record_id AND organ_id NOT IN (' . implode(",", $cp_umchs_rcu) . ') ORDER BY  locorg ASC', [':record_id' => $_SESSION['urec']]
                    );
                } else {// ЦП кроме РОСН
                    $menu_cp = R::getAll('SELECT * FROM menu WHERE record_id = :record_id ORDER BY  locorg ASC', [':record_id' => $_SESSION['urec']]
                    );
                }
            }

            if (isset($menu) && !empty($menu)) {
                prepareMenu($menu);
            }
            if (isset($menurosn) && !empty($menurosn)) {
                $_SESSION['menurosn'] = $menurosn;
            }

            if (isset($menu_cp) && !empty($menu_cp)) {
                prepareMenuCp($menu_cp);
            }
        }
        if($_SESSION['ulevel']==1){
            $app->redirect('/str/general/2');
        }
        else
                 $app->redirect('/str/general/3');
    } else {
        $app->redirect('login');
    }
});


/* umchs level=oblast, admin can auth as cou of region */
$app->get('/login_as_cou/:id_user_region_cou', function ($id_user_region_cou) use ($app, $log) {

/* cou of region */
    $user = R::findOne('user', 'id = ? ', [$id_user_region_cou]);

    if ($user) {

        /* save past user */
$_SESSION['past_user']= $_SESSION['uid'] ;

/* rewrite session data  */
        $_SESSION['uid'] = $user['id'];
        $_SESSION['ulevel'] = $user['levels_id'];
        $_SESSION['uregions'] = $user['regions_id']; //id region
        $_SESSION['ulocorg'] = $user['locorg_id']; //idCard
        $_SESSION['urec'] = $user['records_id']; //id record
        $_SESSION['note'] = $user['note']; //идентификатор РОСН
        $_SESSION['login'] = $user['login'];
        $_SESSION['psw'] = $user['password'];
        $_SESSION['can_edit'] = $user['can_edit'];
        $_SESSION['sub'] = $user['sub']; //подразделение ЦП или УМЧС
        $_SESSION['uname'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['is_deny'] = $user['is_deny'];


//*************************************************************   menu ***************************************

        $cp_umchs_rcu = array(8, 9, 10, 11, 12,  5); //id ЦП+'РЦУ'
        $cp = array(9, 10, 11, 12); //id ЦП без РОСН

        if ($_SESSION['ulevel'] == 1) {//РЦУРЧС

            /*             * ***************  меню УМЧС без ЦП *************** */
            $menu = R::getAll('SELECT * FROM menu WHERE organ_id NOT IN (' . implode(",", $cp_umchs_rcu) . ')  ORDER BY region, locorg, divizion_num ASC');

            /*             * ***************  меню РОСН *************** */
            $menurosn = R::getAll('SELECT * FROM menurosn ORDER BY name ASC');

            /*             * ***************  меню  ЦП без РОСН *************** */
            $menu_cp = R::getAll('SELECT * FROM menu WHERE organ_id  IN (' . implode(",", $cp) . ')  ORDER BY region, locorg, divizion_num ASC');

            if (isset($menu) && !empty($menu)) {
                prepareMenu($menu);
            }

            if (isset($menurosn) && !empty($menurosn)) {//v > v.1.0
                $_SESSION['menurosn'] = $menurosn; //v > v.1.0
            }

            if (isset($menu_cp) && !empty($menu_cp)) {
                prepareMenuCp($menu_cp);
            }
        } else {
            if ($_SESSION['ulevel'] == 2) {//уровень области
                if ($_SESSION['note'] == NULL) {//не ЦП
                    $menu = R::getAll('SELECT * FROM menu WHERE region_id = :reg_id AND organ_id NOT IN (' . implode(",", $cp_umchs_rcu) . ') ORDER BY  locorg ASC', [':reg_id' => $_SESSION['uregions']]
                    );
                } else {//ЦП
                    if ($_SESSION['note'] == 8) {//ROSN
                        $menurosn = R::getAll('SELECT * FROM menurosn ORDER BY name ASC'
                        );
                    }
                    elseif ($_SESSION['note'] == UGZ) {// UGZ
                               $menu_cp = R::getAll('SELECT * FROM menu WHERE organ_id = :organ_id  ORDER BY  locorg ASC', [':organ_id' => UGZ]
                        );
                    }
                }
            } elseif ($_SESSION['ulevel'] == 3) {//уровень района
                if ($_SESSION['note'] == NULL) {//не ЦП
                    $menu = R::getAll('SELECT * FROM menu WHERE locorg_id = :locorg_id AND organ_id NOT IN (' . implode(",", $cp_umchs_rcu) . ') ORDER BY  locorg ASC', [':locorg_id' => $_SESSION['ulocorg']]
                    );
                } else {//ЦП
                    if ($_SESSION['note'] == 8) {//ROSN
                        $menurosn = R::getAll('SELECT * FROM menurosn WHERE locorg_id = :id', [':id' => $_SESSION['ulocorg']]
                        );
                    } else {// ЦП кроме РОСН
                        $menu_cp = R::getAll('SELECT * FROM menu WHERE locorg_id = :locorg_id  ORDER BY  locorg ASC', [':locorg_id' => $_SESSION['ulocorg']]
                        );
                    }
                }
            } elseif ($_SESSION['ulevel'] == 4) {//уровень ПАСП
                if ($_SESSION['note'] == NULL) {//не ЦП
                    $menu = R::getAll('SELECT * FROM menu WHERE record_id = :record_id AND organ_id NOT IN (' . implode(",", $cp_umchs_rcu) . ') ORDER BY  locorg ASC', [':record_id' => $_SESSION['urec']]
                    );
                } else {// ЦП кроме РОСН
                    $menu_cp = R::getAll('SELECT * FROM menu WHERE record_id = :record_id ORDER BY  locorg ASC', [':record_id' => $_SESSION['urec']]
                    );
                }
            }

            if (isset($menu) && !empty($menu)) {
                prepareMenu($menu);
            }
            if (isset($menurosn) && !empty($menurosn)) {
                $_SESSION['menurosn'] = $menurosn;
            }

            if (isset($menu_cp) && !empty($menu_cp)) {
                prepareMenuCp($menu_cp);
            }
        }
        if($_SESSION['ulevel']==1){
            $app->redirect('/str/general/2');
        }
        else
                 $app->redirect('/str/general/3');
    } else {
        $app->redirect('login');
    }
});


/* user can auth as spectator */
$app->get('/login_as_spectator/:id_user', function ($id_user) use ($app, $log) {

unset($_SESSION['reg_cp']);
    unset($_SESSION['loc_cp']);
   unset( $_SESSION['pasp_cp']);
   unset( $_SESSION['menurosn'] );


 $user = R::findOne('user', 'id = ? ', [$id_user]);

    if ($user) {

        /* save past user */
$_SESSION['past_user']= $_SESSION['uid'] ;




/* rewrite session data  */
        $_SESSION['uid'] = $user['id'];
        $_SESSION['ulevel'] = $user['levels_id'];
        $_SESSION['uregions'] = $user['regions_id']; //id region
        $_SESSION['ulocorg'] = $user['locorg_id']; //idCard
        $_SESSION['urec'] = $user['records_id']; //id record
        $_SESSION['note'] = $user['note']; //идентификатор РОСН
        $_SESSION['login'] = $user['login'];
        $_SESSION['psw'] = $user['password'];
        $_SESSION['can_edit'] = $user['can_edit'];
        $_SESSION['sub'] = $user['sub']; //подразделение ЦП или УМЧС
        $_SESSION['uname'] = $user['name'];
        $_SESSION['is_admin'] = $user['is_admin'];
        $_SESSION['is_deny'] = $user['is_deny'];



//*************************************************************   menu ***************************************

        $cp_umchs_rcu = array(8, 9, 10, 11, 12,  5); //id ЦП+'РЦУ'
        $cp = array(9, 10, 11, 12); //id ЦП без РОСН

        if ($_SESSION['ulevel'] == 1) {//РЦУРЧС

            /*             * ***************  меню УМЧС без ЦП *************** */
            $menu = R::getAll('SELECT * FROM menu WHERE organ_id NOT IN (' . implode(",", $cp_umchs_rcu) . ')  ORDER BY region, locorg, divizion_num ASC');

            /*             * ***************  меню РОСН *************** */
            $menurosn = R::getAll('SELECT * FROM menurosn ORDER BY name ASC');

            /*             * ***************  меню  ЦП без РОСН *************** */
            $menu_cp = R::getAll('SELECT * FROM menu WHERE organ_id  IN (' . implode(",", $cp) . ')  ORDER BY region, locorg, divizion_num ASC');

            if (isset($menu) && !empty($menu)) {
                prepareMenu($menu);
            }

            if (isset($menurosn) && !empty($menurosn)) {//v > v.1.0
                $_SESSION['menurosn'] = $menurosn; //v > v.1.0
            }

            if (isset($menu_cp) && !empty($menu_cp)) {
                prepareMenuCp($menu_cp);
            }
        } else {
            if ($_SESSION['ulevel'] == 2) {//уровень области
                if ($_SESSION['note'] == NULL || empty($_SESSION['note'] )) {//не ЦП
                    $menu = R::getAll('SELECT * FROM menu WHERE region_id = :reg_id AND organ_id NOT IN (' . implode(",", $cp_umchs_rcu) . ') ORDER BY  locorg ASC', [':reg_id' => $_SESSION['uregions']]
                    );
                } else {//ЦП
                    if ($_SESSION['note'] == 8) {//ROSN
                        $menurosn = R::getAll('SELECT * FROM menurosn ORDER BY name ASC'
                        );
                    }
                    elseif ($_SESSION['note'] == UGZ) {// UGZ
                               $menu_cp = R::getAll('SELECT * FROM menu WHERE organ_id = :organ_id  ORDER BY  locorg ASC', [':organ_id' => UGZ]
                        );
                    }
                }
            } elseif ($_SESSION['ulevel'] == 3) {//уровень района
                if ($_SESSION['note'] == NULL) {//не ЦП
                    $menu = R::getAll('SELECT * FROM menu WHERE locorg_id = :locorg_id AND organ_id NOT IN (' . implode(",", $cp_umchs_rcu) . ') ORDER BY  locorg ASC', [':locorg_id' => $_SESSION['ulocorg']]
                    );
                } else {//ЦП
                    if ($_SESSION['note'] == 8) {//ROSN
                        $menurosn = R::getAll('SELECT * FROM menurosn WHERE locorg_id = :id', [':id' => $_SESSION['ulocorg']]
                        );
                    } else {// ЦП кроме РОСН
                        $menu_cp = R::getAll('SELECT * FROM menu WHERE locorg_id = :locorg_id  ORDER BY  locorg ASC', [':locorg_id' => $_SESSION['ulocorg']]
                        );
                    }
                }
            } elseif ($_SESSION['ulevel'] == 4) {//уровень ПАСП
                if ($_SESSION['note'] == NULL) {//не ЦП
                    $menu = R::getAll('SELECT * FROM menu WHERE record_id = :record_id AND organ_id NOT IN (' . implode(",", $cp_umchs_rcu) . ') ORDER BY  locorg ASC', [':record_id' => $_SESSION['urec']]
                    );
                } else {// ЦП кроме РОСН
                    $menu_cp = R::getAll('SELECT * FROM menu WHERE record_id = :record_id ORDER BY  locorg ASC', [':record_id' => $_SESSION['urec']]
                    );
                }
            }

            if (isset($menu) && !empty($menu)) {
                prepareMenu($menu);
            }
            if (isset($menurosn) && !empty($menurosn)) {
                $_SESSION['menurosn'] = $menurosn;
            }

            if (isset($menu_cp) && !empty($menu_cp)) {
                prepareMenuCp($menu_cp);
            }
        }
        if($_SESSION['ulevel']==1){
            $app->redirect('/str/general/2');
        }
        else
                 $app->redirect('/str/general/3');
    } else {
        $app->redirect('login');
    }
});

$app->get('/logout', function () use ($app) {
    session_destroy();
    unset($_SESSION);
    $app->redirect('login');
});

/* * ********************* МЕНЮ ************************ */

function prepareMenu($menu) {
    $i = 0;
    foreach ($menu as $k => $v) {
        $i++;

        $reg[$v['region_id']] = $v['region'];
        $region_local[$v['region_id']][$v['locorg_id']] = $v['locorg'];
        $loc[$v['locorg_id']] = $v['locorg'];

        $pasp[$v['record_id']] = $v['divizion_name'];
    }

    foreach ($loc as $c => $w) {
        foreach ($menu as $k => $v) {
            if ($v['locorg_id'] == $c) {
                $locals [$v['locorg_id']][$v['record_id']] = $v['divizion_name'];
            }
        }
    }

    /* foreach ($reg as $ke => $re) {
      foreach ($menu as $k => $v) {
      if ($v['region_id'] == $ke) {
      $level1 [$v['region_id']][$v['locorg_id']] = $v['locorg'];
      }
      }
      } */

    /* print_r($reg);
      print_r($loc);
      print_r($locals); */
    $data['reg'] = $reg;
    $data['loc'] = $region_local;
    $data['pasp'] = $locals;
    //$data['level1'] = $level1;
    // $data['menurosn'] = $menurosn; //v > v.1.0

    $_SESSION['reg'] = $reg;
    $_SESSION['loc'] = $region_local;
    $_SESSION['pasp'] = $locals;
    // $_SESSION['level1'] = $level1;
}

//меню ЦП без РОСН
function prepareMenuCp($menu) {
    $i = 0;
    foreach ($menu as $k => $v) {
        $i++;

        $reg[$v['region_id']] = $v['region'];
        $region_local[$v['region_id']][$v['locorg_id']] = $v['locorg'];
        $loc[$v['locorg_id']] = $v['locorg'];

        $pasp[$v['record_id']] = $v['divizion_name'];
    }

    foreach ($loc as $c => $w) {
        foreach ($menu as $k => $v) {
            if ($v['locorg_id'] == $c) {
                $locals [$v['locorg_id']][$v['record_id']] = $v['divizion_name'];
            }
        }
    }

    /* foreach ($reg as $ke => $re) {
      foreach ($menu as $k => $v) {
      if ($v['region_id'] == $ke) {
      $level1 [$v['region_id']][$v['locorg_id']] = $v['locorg'];
      }
      }
      } */

    /* print_r($reg);
      print_r($loc);
      print_r($locals); */
//    $data['reg'] = $reg;
//    $data['loc'] = $region_local;
//    $data['pasp'] = $locals;
    //$data['level1'] = $level1;
    // $data['menurosn'] = $menurosn; //v > v.1.0

    $_SESSION['reg_cp'] = $reg;
    $_SESSION['loc_cp'] = $region_local;
    $_SESSION['pasp_cp'] = $locals;
    // $_SESSION['level1'] = $level1;
}

/* * ******************************** ЗАПРОСНИК ************************************** */
$app->group('/builder',$is_auth, function () use ($app) {

    /*     * *******  Формы запросника  ********* */
    //инф по сменам
    $app->get('/basic/inf_ch/:type', function ($type) use ($app) {//umchs/cp информация по сменам - форма
        array($app, 'is_auth');

        $data['title_name']='Запросы/Инф.по сменам';
        $app->render('layouts/header.php',$data);
        $app->render('layouts/menu.php');
        $data['bread'] = getBread();
        $app->render('bread/bread.php', $data);


        /* $type = 1; //type of query-umchs/cp
          $type = 2; //type of query-rosn
         *         */
        $data['type'] = $type;
                ///какая вкладка активна
$data['active']= 'ch';

        $app->render('query/pzmenu.php', $data);
        if ($type == 1) {//УМЧС
            $data = basic_query();
            $app->render('query/form/form_inf_ch.php', $data);
        } elseif($type==2) { //rosn
            $data = additional_query();
            $app->render('query/form_rosn/form_inf_ch.php', $data);
        }
        elseif($type==3) { //UGZ
            $data = UGZ_query();
            $app->render('query/form_ugz/form_inf_ch.php', $data);
        }
                elseif($type==4) { //Avia
            $data = AVIA_query();
            $app->render('query/form_avia/form_inf_ch.php', $data);
        }

        $app->render('query/pzend.php');
        $app->render('layouts/footer.php');
    });
    //больничные
    $app->get('/basic/inf_ill/:type', function ($type) use ($app) {//umchs/cp информация по ill- форма
        array($app, 'is_auth');

         $data['title_name']='Запросы/Больничные';
        $app->render('layouts/header.php',$data);
        $app->render('layouts/menu.php');
        $data['bread'] = getBread();
        $app->render('bread/bread.php', $data);

//        $data = basic_query();
        /* $type = 1; //type of query-umchs/cp
          $type = 2; //type of query-rosn
         *         */
        $data['type'] = $type;

        ///какая вкладка активна
$data['active']= 'ill';
        $app->render('query/pzmenu.php', $data);
        if ($type == 1) {
            $data = basic_query();
            $app->render('query/form/form_inf_ill.php', $data);
        } elseif($type==2) {
            $data = additional_query();
            $app->render('query/form_rosn/form_inf_ill.php', $data);
        }
         elseif($type==3) { //UGZ
            $data = UGZ_query();
            $app->render('query/form_ugz/form_inf_ill.php', $data);
        }
                       elseif($type==4) { //Avia
            $data = AVIA_query();
            $app->render('query/form_avia/form_inf_ill.php', $data);
        }
        $app->render('query/pzend.php');
        $app->render('layouts/footer.php');
    });
    //отпуска
    $app->get('/basic/inf_holiday/:type', function ($type) use ($app) {//umchs/cp информация по ill- форма
        array($app, 'is_auth');

         $data['title_name']='Запросы/Отпуска';
        $app->render('layouts/header.php',$data);
        $app->render('layouts/menu.php');
        $data['bread'] = getBread();
        $app->render('bread/bread.php', $data);

//        $data = basic_query();
        /* $type = 1; //type of query-umchs/cp
          $type = 2; //type of query-rosn
         *         */
        $data['type'] = $type;
        ///какая вкладка активна
$data['active']= 'holiday';

        $app->render('query/pzmenu.php', $data);
        if ($type == 1) {
            $data = basic_query();
            $app->render('query/form/form_inf_holiday.php', $data);
        } elseif ($type == 2) {//ROSN
            $data = additional_query();
            $app->render('query/form_rosn/form_inf_holiday.php', $data);
        } elseif ($type == 3) { //UGZ
            $data = UGZ_query();
            $app->render('query/form_ugz/form_inf_holiday.php', $data);
        } elseif ($type == 4) { //Avia
            $data = AVIA_query();
            $app->render('query/form_avia/form_inf_holiday.php', $data);
        }

        $app->render('query/pzend.php');
        $app->render('layouts/footer.php');
    });
    //командировки
    $app->get('/basic/inf_trip/:type', function ($type) use ($app) {//umchs/cp информация по ill- форма
        array($app, 'is_auth');

         $data['title_name']='Запросы/Командировки';
        $app->render('layouts/header.php',$data);
        $app->render('layouts/menu.php');
        $data['bread'] = getBread();
        $app->render('bread/bread.php', $data);

//        $data = basic_query();
        /* $type = 1; //type of query-umchs/cp
          $type = 2; //type of query-rosn
         *         */
        $data['type'] = $type;
                ///какая вкладка активна
$data['active']= 'trip';

        $app->render('query/pzmenu.php', $data);
        if ($type == 1) {
            $data = basic_query();
            $app->render('query/form/form_inf_trip.php', $data);
        } elseif ($type == 2) {//ROSN
            $data = additional_query();
            $app->render('query/form_rosn/form_inf_trip.php', $data);
        } elseif ($type == 3) { //UGZ
            $data = UGZ_query();
            $app->render('query/form_ugz/form_inf_trip.php', $data);
        } elseif ($type == 4) { //Avia
            $data = AVIA_query();
            $app->render('query/form_avia/form_inf_trip.php', $data);
        }

        $app->render('query/pzend.php');
        $app->render('layouts/footer.php');
    });
    //др.причины
    $app->get('/basic/inf_other/:type', function ($type) use ($app) {//umchs/cp информация по ill- форма
        array($app, 'is_auth');

         $data['title_name']='Запросы/Др.причины';
        $app->render('layouts/header.php',$data);
        $app->render('layouts/menu.php');
        $data['bread'] = getBread();
        $app->render('bread/bread.php', $data);

//        $data = basic_query();
        /* $type = 1; //type of query-umchs/cp
          $type = 2; //type of query-rosn
         *         */
        $data['type'] = $type;
        ///какая вкладка активна
$data['active']= 'other';

        $app->render('query/pzmenu.php', $data);
        if ($type == 1) {
            $data = basic_query();
            $app->render('query/form/form_inf_other.php', $data);
        } elseif ($type == 2) {//ROSN
            $data = additional_query();
            $app->render('query/form_rosn/form_inf_other.php', $data);
        } elseif ($type == 3) { //UGZ
            $data = UGZ_query();
            $app->render('query/form_ugz/form_inf_other.php', $data);
        } elseif ($type == 4) { //Avia
            $data = AVIA_query();
            $app->render('query/form_avia/form_inf_other.php', $data);
        }
        $app->render('query/pzend.php');
        $app->render('layouts/footer.php');
    });
     //инф по технике СЗ
    $app->get('/basic/inf_car/:type', function ($type) use ($app) {//umchs/cp информация по технике - форма
        array($app, 'is_auth');

         $data['title_name']='Запросы/Инф.по СЗ';
        $app->render('layouts/header.php',$data);
        $app->render('layouts/menu.php');
        $data['bread'] = getBread();
        $app->render('bread/bread.php', $data);


        /* $type = 1; //type of query-umchs/cp
          $type = 2; //type of query-rosn
         *         */
        $data['type'] = $type;
                ///какая вкладка активна
$data['active']= 'car';

        $app->render('query/pzmenu.php', $data);
        if ($type == 1) {//УМЧС/ЦП
            $data = basic_query();
            $app->render('query/form/form_inf_car.php', $data);
        } elseif ($type == 2) { //РОСН
            $data = additional_query();
            $app->render('query/form_rosn/form_inf_car.php', $data);
        } elseif ($type == 3) { //UGZ
            $data = UGZ_query();
            $app->render('query/form_ugz/form_inf_car.php', $data);
        } elseif ($type == 4) { //Avia
            $data = AVIA_query();
            $app->render('query/form_avia/form_inf_car.php', $data);
        }

        $app->render('query/pzend.php');
        $app->render('layouts/footer.php');
    });
     //запросник по технике
    $app->get('/basic/inf_car_big/:type', function ($type) use ($app) {//umchs/cp информация по технике - форма
        array($app, 'is_auth');

         $data['title_name']='Запросы/Техника';
        $app->render('layouts/header.php',$data);
        $app->render('layouts/menu.php');
        $data['bread'] = getBread();
        $app->render('bread/bread.php', $data);


        /* $type = 1; //type of query-umchs/cp
          $type = 2; //type of query-rosn
         *         */
        $data['type'] = $type;
        ///какая вкладка активна
        $data['active'] = 'car_big';

        $data['name_teh']=R::getAll('select * from ss.views');//наименование техники
        $data['vid_teh']=R::getAll('select * from ss.vid');//основная, спец, вспомог

        $app->render('query/pzmenu.php', $data);
        if ($type == 1) {//УМЧС/ЦП
            $data = basic_query();
            $app->render('query/form/form_inf_car_big.php', $data);
        } elseif ($type == 2) { //РОСН
            $data = additional_query();
            $app->render('query/form_rosn/form_inf_car_big.php', $data);
        } elseif ($type == 3) { //UGZ
            $data = UGZ_query();
            $app->render('query/form_ugz/form_inf_car_big.php', $data);
        } elseif ($type == 4) { //Avia
            $data = AVIA_query();
            $app->render('query/form_avia/form_inf_car_big.php', $data);
        }

        $app->render('query/pzend.php');
        $app->render('layouts/footer.php');
    });

         //запросник по технике count
    $app->get('/basic/inf_car_big_count/:type', function ($type) use ($app) {//umchs/cp информация по технике - форма
        array($app, 'is_auth');

         $data['title_name']='Запросы/Техника';
        $app->render('layouts/header.php',$data);
        $app->render('layouts/menu.php');
        $data['bread'] = getBread();
        $app->render('bread/bread.php', $data);


        /* $type = 1; //type of query-umchs/cp
          $type = 2; //type of query-rosn
         *         */
        $data['type'] = $type;
        ///какая вкладка активна
        $data['active'] = 'car_big_count';

        $data['name_teh']=R::getAll('select * from ss.views');//наименование техники
        $data['vid_teh']=R::getAll('select * from ss.vid');//основная, спец, вспомог

        $app->render('query/pzmenu.php', $data);
        if ($type == 1) {//УМЧС/ЦП
            $data = basic_query();
            $app->render('query/form/form_inf_car_big_count.php', $data);
        } elseif ($type == 2) { //РОСН
            $data = additional_query();
            $app->render('query/form_rosn/form_inf_car_big_count.php', $data);
        } elseif ($type == 3) { //UGZ
            $data = UGZ_query();
            $app->render('query/form_ugz/form_inf_car_big_count.php', $data);
        } elseif ($type == 4) { //Avia
            $data = AVIA_query();
            $app->render('query/form_avia/form_inf_car_big_count.php', $data);
        }

        $app->render('query/pzend.php');
        $app->render('layouts/footer.php');
    });

        //инф по сменам ЦОУ, ШЛЧС
    $app->get('/basic/inf_ch_cou/:type', function ($type) use ($app) {//ЦОУ, ШЛЧС инф по сменам - форма
        array($app, 'is_auth');

        $data['title_name'] = 'Запросы/Инф.по сменам ЦОУ, ШЛЧС';
        $app->render('layouts/header.php', $data);
        $app->render('layouts/menu.php');
        $data['bread'] = getBread();
        $app->render('bread/bread.php', $data);


        /* $type = 1; //type of query-umchs/cp
          $type = 2; //type of query-rosn
         *         */
        $data['type'] = $type;

        $data['active'] = 'ch_cou';///какая вкладка активна

        $app->render('query/pzmenu.php', $data);
        if ($type == 1 || $type == 2) {//UMCHS or rosn
            $data = cou_query(); // only cou or slhs
            $app->render('query/cou/form_inf_ch_cou.php', $data);
        }

        $app->render('query/pzend.php');
        $app->render('layouts/footer.php');
    });

    /*     * *******  КОНЕЦ Формы запросника  ********* */


    /*     * ************************************************  РЕЗУЛЬТАТЫ запросов ************************************************************ */

    /* +++++++++++++++++++++ инф по сменам +++++++++++++++++++++ */
    $app->post('/basic/inf_ch/:type', function ($type) use ($app) {//umchs/cp информация по сменам - форма


        if (!isset($_POST['export_to_excel'])) {
             array($app, 'is_auth');
           $data['title_name']='Запросы/Инф.по сменам';
        $app->render('layouts/header.php',$data);
            $app->render('layouts/menu.php');
            $data['bread'] = getBread();
            $app->render('bread/bread.php', $data);


            /* $type = 1; //type of query-umchs/cp
              $type = 2; //type of query-rosn
             *         */
            $data['type'] = $type;
            ///какая вкладка активна
            $data['active'] = 'ch';

            $app->render('query/pzmenu.php', $data);
            /* --------------- форма поиска ---------------- */
            if ($type == 1) {
                $data = basic_query();
                $app->render('query/form/form_inf_ch.php', $data);
            } elseif ($type == 2) {
                $data = additional_query();
                $app->render('query/form_rosn/form_inf_ch.php', $data);
            } elseif ($type == 3) { //UGZ
                $data = UGZ_query();
                $app->render('query/form_ugz/form_inf_ch.php', $data);
            } elseif ($type == 4) { //Avia
                $data = AVIA_query();
                $app->render('query/form_avia/form_inf_ch.php', $data);
            }
        }


         /***********дата, на которую надо выбирать данные *********/
           $date_start = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
            $date_d = new DateTime($date_start);
            $date = $date_d->Format('Y-m-d');

            $today=date("Y-m-d");
              $yesterday=date("Y-m-d", time()-(60*60*24));
               $day_before_yesterday=date("Y-m-d", time()-(60*60*24)-(60*60*24));

               if($date != $today && $date!=$yesterday && $date!= $day_before_yesterday){
                   $date=0;
               }

                  /*********** END дата, на которую надо выбирать данные *********/

        /* ---------  результат поиска УМЧС/ЦП --------- */
        if ($type == 1) {
            /*             * ******  формированеи результата ****** */
            $region = $app->request()->post('region'); //область
            $grochs = $app->request()->post('locorg'); //грочс
            $divizion = $app->request()->post('diviz'); //подразделение
            $main = getInfChBasic($region, $grochs, $divizion,$date);
            $data['main'] = $main;

            if (!empty($data['main'])) {
                /* -------------------------- export to excel ---------------------------- */
                if (isset($_POST['export_to_excel'])) {
                    exportToExcelInfCh($main,$type);


                }
                /* ---------------------- отображение на экран ----------------------------- */ else {
                    $app->render('query/result/inf_ch.php', $data); //result
                    $app->render('query/pzend.php');
                }
            } else {
                $app->render('msg/emtyResult.php', $data); //no result
            }
        }
        /* ----------------  результат поиска РОСН / УГЗ ----------------- */ else {
            /*             * ******  формированеи результата ****** */
            $region = $app->request()->post('region'); //РОСН
            $grochs = $app->request()->post('locorg'); //ОУ

            if ($type == 2)
                $organ = ROSN;
            elseif ($type == 3)
                $organ = UGZ;
             elseif ($type == 4)
                $organ = AVIA;
            $main = getInfChAdditional($region, $grochs, $organ,$date);
            $data['main'] = $main;

            if (!empty($data['main'])) {

                //export to excel
                if (isset($_POST['export_to_excel'])) {
                    exportToExcelInfCh($main,$type);
                }
                //отображение на экран
                else {

                    $app->render('query/result/inf_ch.php', $data); //result
                    $app->render('query/pzend.php');
                }
            } else {

                $app->render('msg/emtyResult.php', $data); //no result
            }
        }
          if (!isset($_POST['export_to_excel'])) {
               $app->render('layouts/footer.php');
          }

    });

    /*     * ****  УМЧС/ЦП - вфбор из БД для отчета инф по сменам **** */
    function getInfChBasic($region, $grochs, $divizion,$date) {

        if (isset($region) && !empty($region)) {
            if (isset($grochs) && !empty($grochs)) {
                if (isset($divizion) && !empty($divizion)) {
                    /* ------------- по ПАСЧ -------------- */
                    $id_pasp = R::getAll('select distinct id_pasp from spr_info_report where id_pasp = ?', array($divizion));
                }
                /* ----------------  по ГРОЧС ----------------- */ else {
                    //выбор id ПАСЧей этого ГРОЧС
                    $id_pasp = R::getAll('select distinct id_pasp from spr_info_report where id_grochs = ?', array($grochs));
                }
            }
            /* --------------- по области ---------------- */ else {
                //исключить подразделения РОСН,УГЗ,Авиация
                $id_pasp = R::getAll('select distinct id_pasp from spr_info_report where region_id = ? and org_id not in (?,?,?)  ', array($region, ROSN,UGZ,AVIA));
            }
        }
        //по РБ
        else {
            //исключить подразделения РОСН,УГЗ,Авиация
            $id_pasp = R::getAll('select distinct id_pasp from spr_info_report where org_id not in (?,?,?)  ', array(ROSN,UGZ,AVIA));
        }
        /* ++++++++  РЕЗУЛЬТАТ ++++++++++ */
        //пробежать по всем ПАСЧ - выбрать инф по деж смене , определить дату и смены, на которую надо позже выбрать отсутствующих
        foreach ($id_pasp as $value) {

               if($date==0){
                 $inf1 = R::getAll('select * from spr_info_report where id_pasp = ?  limit 1', array($value['id_pasp']));
            }
            else{
                  $inf1 = R::getAll('select * from spr_info_report where id_pasp = ? AND dateduty = ? limit 1', array($value['id_pasp'],$date));
            }

            if(!empty($inf1)){
                $main[$value['id_pasp']] = array();

            //оформить инф по деж смене в массив : [id_pasp] => array([shtat_ch]=>25,...)
            foreach ($inf1 as $row) {

                $dateduty = $row['dateduty'];
                $ch = $row['ch'];

                 $main[$value['id_pasp']] ['dateduty'] = $dateduty;
                  $main[$value['id_pasp']] ['ch'] = $ch;

//                 $main[$value['id_pasp']]['dateduty'] = $dateduty;
//                 $main[$value['id_pasp']]['ch'] = $ch;
                $main[$value['id_pasp']] ['id_grochs'] = $row['id_grochs'];
                $main[$value['id_pasp']] ['region_id'] = $row['region_id'];
                $main[$value['id_pasp']] ['region_name'] = $row['region_name'];
                $main[$value['id_pasp']]['name'] = $row['divizion'] . ', ' . $row['organ']; //ПАСЧ-1,Жлобинский РОЧС
                $main[$value['id_pasp']]['divizion_name'] = $row['divizion']; //ПАСЧ-1
                $main[$value['id_pasp']]['grochs_name'] = $row['organ']; //Жлобинский РОЧС
                $main[$value['id_pasp']] ['shtat_ch'] = $row['countls'];
                $main[$value['id_pasp']] ['vacant_ch'] = $row['vacant'];
                $main[$value['id_pasp']] ['face'] = $row['face'];
                $main[$value['id_pasp']] ['calc'] = $row['calc'];
                $main[$value['id_pasp']] ['duty'] = $row['duty'];
                $main[$value['id_pasp']] ['duty_date1'] = $dateduty;
                $main[$value['id_pasp']] ['duty_date2'] = $dateduty;
                $main[$value['id_pasp']] ['gas'] = $row['gas'];
                //отсутствующие
                $main[$value['id_pasp']] ['trip'] = getCountTrip($value['id_pasp'], $ch, $dateduty);
                $main[$value['id_pasp']] ['holiday'] = getCountHoliday($value['id_pasp'], $ch, $dateduty);
                $main[$value['id_pasp']] ['ill'] = getCountIll($value['id_pasp'], $ch, $dateduty);
                $main[$value['id_pasp']] ['other'] = getCountOther($value['id_pasp'], $ch, $dateduty);

                //л/с подразделения
                //по штату   по подразделению c ежедневниками
                $main[$value['id_pasp']] ['shtat'] = R::getCell('select count(l.id) as shtat from str.cardch as c '
                                . ' left join str.listfio as l on l.id_cardch=c.id where c.id_card = ? ', array($value['id_pasp']));
                //вакансия по подразделению
                $main[$value['id_pasp']] ['vacant'] = R::getCell('select  sum(m.vacant) from str.main as m where m.id_card = ? ', array($value['id_pasp']));


                /*                 * *******  ФИО, описание работников в  командировке - текст  ******** */
                $trip = R::getAll('SELECT t.id, t.id_fio,date_format(t.date1,"%d-%m-%Y") AS date1,date_format(t.date2,"%d-%m-%Y") AS date2,'
                                . ' t.place,t.is_cosmr, t.prikaz,l.fio, po.name as position FROM trip AS t '
                                . 'inner join listfio AS l ON t.id_fio=l.id inner join str.position as po on po.id=l.id_position inner join cardch AS c ON l.id_cardch=c.id '
                                . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                                . ' AND (( :today BETWEEN t.date1 and t.date2) or(:today  >= t.date1 and t.date2 is NULL)) ', [':id' => $value['id_pasp'], ':ch' => $ch, ':today' => $dateduty]);
                $main[$value['id_pasp']]['trip_inf'] = $trip;

                /*                 * *******  ФИО, описание работников в  отпуске - текст  ******** */
                $holiday = R::getAll('SELECT h.id, h.id_fio,date_format(h.date1,"%d-%m-%Y") AS date1,date_format(h.date2,"%d-%m-%Y") AS date2,'
                                . ' h.prikaz, l.fio, po.name as position FROM holiday AS h '
                                . 'inner join listfio AS l ON h.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id  inner join str.position as po on po.id=l.id_position '
                                . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                                . ' AND (( :today BETWEEN h.date1 and h.date2) or(:today  >= h.date1 and h.date2 is NULL))', [':id' => $value['id_pasp'], ':ch' => $ch, ':today' => $dateduty]);
                $main[$value['id_pasp']]['holiday_inf'] = $holiday;

                /*                 * *******  ФИО, описание работников на больничном - текст  ******** */
                $ill = R::getAll('SELECT i.id, i.id_fio,date_format(i.date1,"%d-%m-%Y") AS date1,date_format(i.date2,"%d-%m-%Y") AS date2,'
                                . ' i.diagnosis,l.fio, ma.name as maim, po.name as position FROM ill AS i inner join listfio AS l '
                                . 'ON i.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id inner join maim AS ma ON i.maim=ma.id inner join str.position as po on po.id=l.id_position '
                                . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                                . 'AND (( :today BETWEEN i.date1 and i.date2) or(:today  >= i.date1 and i.date2 is NULL)) ', [':id' => $value['id_pasp'], ':ch' => $ch, ':today' => $dateduty]);

                $main[$value['id_pasp']]['ill_inf'] = $ill;

                /*                 * *******  ФИО, описание работников в наряде - текст  ******** */
                $main[$value['id_pasp']]['name'] = $row['divizion'] . ', ' . $row['organ']; //ПАСЧ-1,Жлобинский РОЧС
                $main[$value['id_pasp']]['duty_inf'] = $row['fio_duty'];

                /*                 * *******  ФИО, описание работников др.причины - текст  ******** */
                $other = R::getAll('SELECT o.id, o.id_fio,date_format(o.date1,"%d-%m-%Y") AS date1, date_format(o.date2,"%d-%m-%Y") AS date2,'
                                . ' o.reason, o.note, l.fio, po.name as position FROM other AS o inner join listfio AS l '
                                . 'ON o.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id inner join str.position as po on po.id=l.id_position '
                                . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                                . ' AND (( :today BETWEEN o.date1 and o.date2) or(:today  >= o.date1 and o.date2 is NULL))', [':id' => $value['id_pasp'], ':ch' => $ch, ':today' => $dateduty]);

                $main[$value['id_pasp']]['other_inf'] = $other;

                      /*                 * *******  Ваканты - текст  ******** */
                 $vacant_inf = R::getAll('SELECT   l.fio, po.name as position FROM  listfio AS l '
                                . ' left join cardch AS c ON l.id_cardch=c.id inner join str.position as po on po.id=l.id_position '
                                . ' WHERE (c.id_card = :id AND c.ch = :ch) and l.is_vacant = :is_vacant', [':id' => $value['id_pasp'], ':ch' => $ch,':is_vacant'=>1 ]);

                 $main[$value['id_pasp']]['vacant_inf'] = $vacant_inf;

                 /*+++++++++ ИТОГО по ГРОЧС ++++++++++*/

                 if (isset($itogo[$row['id_grochs']]['shtat']))
                    $itogo[$row['id_grochs']]['shtat']+=$main[$value['id_pasp']] ['shtat'];
                else
                    $itogo[$row['id_grochs']]['shtat'] = $main[$value['id_pasp']] ['shtat'];
                if (isset($itogo[$row['id_grochs']]['vacant']))
                    $itogo[$row['id_grochs']]['vacant']+=$main[$value['id_pasp']] ['vacant'];
                else
                    $itogo[$row['id_grochs']]['vacant'] = $main[$value['id_pasp']] ['vacant'];
                if (isset($itogo[$row['id_grochs']]['shtat_ch']))
                    $itogo[$row['id_grochs']]['shtat_ch'] +=$row['countls'];
                else
                    $itogo[$row['id_grochs']]['shtat_ch'] = $row['countls'];
                if (isset($itogo[$row['id_grochs']]['vacant_ch']))
                    $itogo[$row['id_grochs']]['vacant_ch'] +=$row['vacant'];
                else
                    $itogo[$row['id_grochs']]['vacant_ch'] = $row['vacant'];
                if (isset($itogo[$row['id_grochs']]['face']))
                    $itogo[$row['id_grochs']]['face']+=$row['face'];
                else
                    $itogo[$row['id_grochs']]['face'] = $row['face'];
                if (isset($itogo[$row['id_grochs']]['calc']))
                    $itogo[$row['id_grochs']]['calc']+=$row['calc'];
                else
                    $itogo[$row['id_grochs']]['calc'] = $row['calc'];
                if (isset($itogo[$row['id_grochs']]['trip']))
                    $itogo[$row['id_grochs']]['trip']+=$main[$value['id_pasp']] ['trip'];
                else
                    $itogo[$row['id_grochs']]['trip'] = $main[$value['id_pasp']] ['trip'];
                if (isset($itogo[$row['id_grochs']]['holiday']))
                    $itogo[$row['id_grochs']]['holiday']+=$main[$value['id_pasp']] ['holiday'];
                else
                    $itogo[$row['id_grochs']]['holiday'] = $main[$value['id_pasp']] ['holiday'];
                if (isset($itogo[$row['id_grochs']]['ill']))
                    $itogo[$row['id_grochs']]['ill']+=$main[$value['id_pasp']] ['ill'];
                else
                    $itogo[$row['id_grochs']]['ill'] = $main[$value['id_pasp']] ['ill'];
                if (isset($itogo[$row['id_grochs']]['other']))
                    $itogo[$row['id_grochs']]['other']+=$main[$value['id_pasp']] ['other'];
                else
                    $itogo[$row['id_grochs']]['other'] = $main[$value['id_pasp']] ['other'];
                if (isset($itogo[$row['id_grochs']]['gas']))
                    $itogo[$row['id_grochs']]['gas']+=$row['gas'];
                else
                    $itogo[$row['id_grochs']]['gas'] = $row['gas'];
                if (isset($itogo[$row['id_grochs']]['duty']))
                    $itogo[$row['id_grochs']]['duty']+=$row['duty'];
                else
                    $itogo[$row['id_grochs']]['duty'] = $row['duty'];

                /*+++++++++ ИТОГО по области ++++++++++*/
                  if (isset($itogo_obl[$row['region_id']]['shtat']))
                    $itogo_obl[$row['region_id']]['shtat']+=$main[$value['id_pasp']] ['shtat'];
                else
                    $itogo_obl[$row['region_id']]['shtat'] = $main[$value['id_pasp']] ['shtat'];
                if (isset($itogo_obl[$row['region_id']]['vacant']))
                    $itogo_obl[$row['region_id']]['vacant']+=$main[$value['id_pasp']] ['vacant'];
                else
                    $itogo_obl[$row['region_id']]['vacant'] = $main[$value['id_pasp']] ['vacant'];
                if (isset($itogo_obl[$row['region_id']]['shtat_ch']))
                    $itogo_obl[$row['region_id']]['shtat_ch'] +=$row['countls'];
                else
                    $itogo_obl[$row['region_id']]['shtat_ch'] = $row['countls'];
                if (isset($itogo_obl[$row['region_id']]['vacant_ch']))
                    $itogo_obl[$row['region_id']]['vacant_ch'] +=$row['vacant'];
                else
                    $itogo_obl[$row['region_id']]['vacant_ch'] = $row['vacant'];
                if (isset($itogo_obl[$row['region_id']]['face']))
                    $itogo_obl[$row['region_id']]['face']+=$row['face'];
                else
                    $itogo_obl[$row['region_id']]['face'] = $row['face'];
                if (isset($itogo_obl[$row['region_id']]['calc']))
                    $itogo_obl[$row['region_id']]['calc']+=$row['calc'];
                else
                    $itogo_obl[$row['region_id']]['calc'] = $row['calc'];
                if (isset($itogo_obl[$row['region_id']]['trip']))
                    $itogo_obl[$row['region_id']]['trip']+=$main[$value['id_pasp']] ['trip'];
                else
                    $itogo_obl[$row['region_id']]['trip'] = $main[$value['id_pasp']] ['trip'];
                if (isset($itogo_obl[$row['region_id']]['holiday']))
                    $itogo_obl[$row['region_id']]['holiday']+=$main[$value['id_pasp']] ['holiday'];
                else
                    $itogo_obl[$row['region_id']]['holiday'] = $main[$value['id_pasp']] ['holiday'];
                if (isset($itogo_obl[$row['region_id']]['ill']))
                    $itogo_obl[$row['region_id']]['ill']+=$main[$value['id_pasp']] ['ill'];
                else
                    $itogo_obl[$row['region_id']]['ill'] = $main[$value['id_pasp']] ['ill'];
                if (isset($itogo_obl[$row['region_id']]['other']))
                    $itogo_obl[$row['region_id']]['other']+=$main[$value['id_pasp']] ['other'];
                else
                    $itogo_obl[$row['region_id']]['other'] = $main[$value['id_pasp']] ['other'];
                if (isset($itogo_obl[$row['region_id']]['gas']))
                    $itogo_obl[$row['region_id']]['gas']+=$row['gas'];
                else
                    $itogo_obl[$row['region_id']]['gas'] = $row['gas'];
                if (isset($itogo_obl[$row['region_id']]['duty']))
                    $itogo_obl[$row['region_id']]['duty']+=$row['duty'];
                else
                    $itogo_obl[$row['region_id']]['duty'] = $row['duty'];

                   /*+++++++++ ИТОГО по РБ ++++++++++*/

                   if (isset($itogo_rb['shtat']))
                    $itogo_rb['shtat']+=$main[$value['id_pasp']] ['shtat'];
                else
                    $itogo_rb['shtat'] = $main[$value['id_pasp']] ['shtat'];
                if (isset($itogo_rb['vacant']))
                    $itogo_rb['vacant']+=$main[$value['id_pasp']] ['vacant'];
                else
                    $itogo_rb['vacant'] = $main[$value['id_pasp']] ['vacant'];
                if (isset($itogo_rb['shtat_ch']))
                    $itogo_rb['shtat_ch'] +=$row['countls'];
                else
                    $itogo_rb['shtat_ch'] = $row['countls'];
                if (isset($itogo_rb['vacant_ch']))
                    $itogo_rb['vacant_ch'] +=$row['vacant'];
                else
                    $itogo_rb['vacant_ch'] = $row['vacant'];
                if (isset($itogo_rb['face']))
                    $itogo_rb['face']+=$row['face'];
                else
                    $itogo_rb['face'] = $row['face'];
                if (isset($itogo_rb['calc']))
                    $itogo_rb['calc']+=$row['calc'];
                else
                    $itogo_rb['calc'] = $row['calc'];
                if (isset($itogo_rb['trip']))
                    $itogo_rb['trip']+=$main[$value['id_pasp']] ['trip'];
                else
                    $itogo_rb['trip'] = $main[$value['id_pasp']] ['trip'];
                if (isset($itogo_rb['holiday']))
                    $itogo_rb['holiday']+=$main[$value['id_pasp']] ['holiday'];
                else
                    $itogo_rb['holiday'] = $main[$value['id_pasp']] ['holiday'];
                if (isset($itogo_rb['ill']))
                    $itogo_rb['ill']+=$main[$value['id_pasp']] ['ill'];
                else
                    $itogo_rb['ill'] = $main[$value['id_pasp']] ['ill'];
                if (isset($itogo_rb['other']))
                    $itogo_rb['other']+=$main[$value['id_pasp']] ['other'];
                else
                    $itogo_rb['other'] = $main[$value['id_pasp']] ['other'];
                if (isset($itogo_rb['gas']))
                    $itogo_rb['gas']+=$row['gas'];
                else
                    $itogo_rb['gas'] = $row['gas'];
                if (isset($itogo_rb['duty']))
                    $itogo_rb['duty']+=$row['duty'];
                else
                    $itogo_rb['duty'] = $row['duty'];

            }
        }
        //print_r($itogo);
        $main['itogo']=$itogo;
        $main['itogo_obl']=$itogo_obl;
        $main['itogo_rb']=$itogo_rb;
            }


        if (!empty($main)) {
            return $main;
        } else {
            return array();
        }
    }


    /*------------  cou, slhs - for query, report information by changes - name of podrazdelenia ----------*/
        function getInfChBasicCou($region, $grochs, $divizion, $date) {


            if ($date == 0) {
                $inf1 = R::getAll('select * from spr_info_report_cou where id_pasp = ?  limit 1', array($divizion));
            } else {
                $inf1 = R::getAll('select * from spr_info_report_cou where id_pasp = ? AND dateduty = ? limit 1', array($divizion, $date));
            }

            if (!empty($inf1)) {
                $main[$divizion] = array();

                //оформить инф по деж смене в массив : [id_pasp] => array([shtat_ch]=>25,...)
                foreach ($inf1 as $row) {

                    $dateduty = $row['dateduty'];
                    $ch = $row['ch'];

                    $main[$divizion] ['dateduty'] = $dateduty;
                    $main[$divizion] ['ch'] = $ch;


                    $main[$divizion] ['id_grochs'] = $row['id_grochs'];
                    $main[$divizion] ['region_id'] = $row['region_id'];
                    $main[$divizion] ['region_name'] = $row['region_name'];
                    $main[$divizion]['name'] = $row['divizion'] . ', ' . $row['organ']; //ПАСЧ-1,Жлобинский РОЧС
                    $main[$divizion]['divizion_name'] = $row['divizion']; //ПАСЧ-1
                    $main[$divizion]['grochs_name'] = $row['organ']; //Жлобинский РОЧС
                }
            }



        if (!empty($main)) {
            return $main;
        } else {
            return array();
        }
    }

    /*     * *****  РОСН - выбор из БД для отчета инф по сменам ********* */

    function getInfChAdditional($region, $grochs,$organ,$date) {

        if (isset($region) && !empty($region)) {
            if (isset($grochs) && !empty($grochs)) {

                /* ------------- по ОУ -------------- */

                $id_pasp = R::getAll('select distinct id_pasp from spr_info_report where id_grochs = ?', array($grochs));
            }
            /* --------------- по  всему РОСН ---------------- */ else {
                $id_pasp = R::getAll('select distinct id_pasp from spr_info_report where  org_id = ? ', array($organ));
            }
        } else {
            // нет данных
            $id_pasp = array();
        }
        /* ++++++++  РЕЗУЛЬТАТ ++++++++++ */
        if (isset($id_pasp) && !empty($id_pasp)) {
            //пробежать по всем ПАСЧ - выбрать инф по деж смене , определить дату и смены, на которую надо позже выбрать отсутствующих
            foreach ($id_pasp as $value) {
                         if($date==0){
                 $inf1 = R::getAll('select * from spr_info_report where id_pasp = ?  limit 1', array($value['id_pasp']));
            }
            else{
                  $inf1 = R::getAll('select * from spr_info_report where id_pasp = ? AND dateduty = ? limit 1', array($value['id_pasp'],$date));
            }

                $main[$value['id_pasp']] = array();

                //оформить инф по деж смене в массив : [id_pasp] => array([shtat_ch]=>25,...)
                foreach ($inf1 as $row) {

                    $dateduty = $row['dateduty'];
                    $ch = $row['ch'];

                $main[$value['id_pasp']]['dateduty'] = $dateduty;
                 $main[$value['id_pasp']]['ch'] = $ch;
                      $main[$value['id_pasp']] ['id_grochs'] = $row['id_grochs'];
                       $main[$value['id_pasp']] ['region_id'] = $row['region_id'];
                    $main[$value['id_pasp']] ['region_name'] = $row['region_name'];
                    $main[$value['id_pasp']]['name'] = $row['divizion'] . ', ' . $row['organ']; //ПАСЧ-1,Жлобинский РОЧС
                    $main[$value['id_pasp']]['divizion_name'] = $row['divizion'] ; //ПАСЧ-1
                    $main[$value['id_pasp']]['grochs_name'] =  $row['organ']; //Жлобинский РОЧС
                    $main[$value['id_pasp']] ['shtat_ch'] = $row['countls'];
                    $main[$value['id_pasp']] ['vacant_ch'] = $row['vacant'];
                    $main[$value['id_pasp']] ['face'] = $row['face'];
                    $main[$value['id_pasp']] ['calc'] = $row['calc'];
                    $main[$value['id_pasp']] ['duty'] = $row['duty'];
                    $main[$value['id_pasp']] ['duty_date1'] = $dateduty;
                    $main[$value['id_pasp']] ['duty_date2'] = $dateduty;
                    $main[$value['id_pasp']] ['gas'] = $row['gas'];
                    //отсутствующие
                    $main[$value['id_pasp']] ['trip'] = getCountTrip($value['id_pasp'], $ch, $dateduty);
                    $main[$value['id_pasp']] ['holiday'] = getCountHoliday($value['id_pasp'], $ch, $dateduty);
                    $main[$value['id_pasp']] ['ill'] = getCountIll($value['id_pasp'], $ch, $dateduty);
                    $main[$value['id_pasp']] ['other'] = getCountOther($value['id_pasp'], $ch, $dateduty);

                    //л/с подразделения
                    //по штату   по подразделению с ежедневниками
                    $main[$value['id_pasp']] ['shtat'] = R::getCell('select count(l.id) as shtat from str.cardch as c '
                                    . ' left join str.listfio as l on l.id_cardch=c.id where c.id_card = ? ', array($value['id_pasp']));
                    //вакансия по подразделению
                    $main[$value['id_pasp']] ['vacant'] = R::getCell('select  sum(m.vacant) from str.main as m where m.id_card = ? ', array($value['id_pasp']));


                    /*                     * *******  ФИО, описание работников в  командировке - текст  ******** */
                    $trip = R::getAll('SELECT t.id, t.id_fio,date_format(t.date1,"%d-%m-%Y") AS date1,date_format(t.date2,"%d-%m-%Y") AS date2,'
                                    . ' t.place,t.is_cosmr, t.prikaz,l.fio, po.name as position FROM trip AS t '
                                    . 'inner join listfio AS l ON t.id_fio=l.id inner join str.position as po on po.id=l.id_position inner join cardch AS c ON l.id_cardch=c.id '
                                    . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                                    . ' AND (( :today BETWEEN t.date1 and t.date2) or(:today  >= t.date1 and t.date2 is NULL)) ', [':id' => $value['id_pasp'], ':ch' => $ch, ':today' => $dateduty]);
                    $main[$value['id_pasp']]['trip_inf'] = $trip;

                    /*                     * *******  ФИО, описание работников в  отпуске - текст  ******** */
                    $holiday = R::getAll('SELECT h.id, h.id_fio,date_format(h.date1,"%d-%m-%Y") AS date1,date_format(h.date2,"%d-%m-%Y") AS date2,'
                                    . ' h.prikaz, l.fio, po.name as position FROM holiday AS h '
                                    . 'inner join listfio AS l ON h.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id  inner join str.position as po on po.id=l.id_position '
                                    . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                                    . ' AND (( :today BETWEEN h.date1 and h.date2) or(:today  >= h.date1 and h.date2 is NULL))', [':id' => $value['id_pasp'], ':ch' => $ch, ':today' => $dateduty]);
                    $main[$value['id_pasp']]['holiday_inf'] = $holiday;

                    /*                     * *******  ФИО, описание работников на больничном - текст  ******** */
                    $ill = R::getAll('SELECT i.id, i.id_fio,date_format(i.date1,"%d-%m-%Y") AS date1,date_format(i.date2,"%d-%m-%Y") AS date2,'
                                    . ' i.diagnosis,l.fio, ma.name as maim, po.name as position FROM ill AS i inner join listfio AS l '
                                    . 'ON i.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id inner join maim AS ma ON i.maim=ma.id inner join str.position as po on po.id=l.id_position '
                                    . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                                    . 'AND (( :today BETWEEN i.date1 and i.date2) or(:today  >= i.date1 and i.date2 is NULL)) ', [':id' => $value['id_pasp'], ':ch' => $ch, ':today' => $dateduty]);

                    $main[$value['id_pasp']]['ill_inf'] = $ill;

                    /*                     * *******  ФИО, описание работников в наряде - текст  ******** */
                    $main[$value['id_pasp']]['name'] = $row['divizion'] . ', ' . $row['organ']; //ПАСЧ-1,Жлобинский РОЧС
                    $main[$value['id_pasp']]['duty_inf'] = $row['fio_duty'];

                    /*                     * *******  ФИО, описание работников др.причины - текст  ******** */
                    $other = R::getAll('SELECT o.id, o.id_fio,date_format(o.date1,"%d-%m-%Y") AS date1, date_format(o.date2,"%d-%m-%Y") AS date2,'
                                    . ' o.reason, o.note, l.fio, po.name as position FROM other AS o inner join listfio AS l '
                                    . 'ON o.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id inner join str.position as po on po.id=l.id_position '
                                    . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                                    . ' AND (( :today BETWEEN o.date1 and o.date2) or(:today  >= o.date1 and o.date2 is NULL))', [':id' => $value['id_pasp'], ':ch' => $ch, ':today' => $dateduty]);

                    $main[$value['id_pasp']]['other_inf'] = $other;

                       /*                 * *******  Ваканты - текст  ******** */
                 $vacant_inf = R::getAll('SELECT   l.fio, po.name as position FROM  listfio AS l '
                                . ' left join cardch AS c ON l.id_cardch=c.id inner join str.position as po on po.id=l.id_position '
                                . ' WHERE (c.id_card = :id AND c.ch = :ch) and l.is_vacant = :is_vacant', [':id' => $value['id_pasp'], ':ch' => $ch,':is_vacant'=>1 ]);

                 $main[$value['id_pasp']]['vacant_inf'] = $vacant_inf;

                        /*+++++++++ ИТОГО по РБ ++++++++++*/
        if (isset($itogo_rb['shtat']))
                    $itogo_rb['shtat']+=$main[$value['id_pasp']] ['shtat'];
                else
                    $itogo_rb['shtat'] = $main[$value['id_pasp']] ['shtat'];
                if (isset($itogo_rb['vacant']))
                    $itogo_rb['vacant']+=$main[$value['id_pasp']] ['vacant'];
                else
                    $itogo_rb['vacant'] = $main[$value['id_pasp']] ['vacant'];
                if (isset($itogo_rb['shtat_ch']))
                    $itogo_rb['shtat_ch'] +=$row['countls'];
                else
                    $itogo_rb['shtat_ch'] = $row['countls'];
                if (isset($itogo_rb['vacant_ch']))
                    $itogo_rb['vacant_ch'] +=$row['vacant'];
                else
                    $itogo_rb['vacant_ch'] = $row['vacant'];
                if (isset($itogo_rb['face']))
                    $itogo_rb['face']+=$row['face'];
                else
                    $itogo_rb['face'] = $row['face'];
                if (isset($itogo_rb['calc']))
                    $itogo_rb['calc']+=$row['calc'];
                else
                    $itogo_rb['calc'] = $row['calc'];
                if (isset($itogo_rb['trip']))
                    $itogo_rb['trip']+=$main[$value['id_pasp']] ['trip'];
                else
                    $itogo_rb['trip'] = $main[$value['id_pasp']] ['trip'];
                if (isset($itogo_rb['holiday']))
                    $itogo_rb['holiday']+=$main[$value['id_pasp']] ['holiday'];
                else
                    $itogo_rb['holiday'] = $main[$value['id_pasp']] ['holiday'];
                if (isset($itogo_rb['ill']))
                    $itogo_rb['ill']+=$main[$value['id_pasp']] ['ill'];
                else
                    $itogo_rb['ill'] = $main[$value['id_pasp']] ['ill'];
                if (isset($itogo_rb['other']))
                    $itogo_rb['other']+=$main[$value['id_pasp']] ['other'];
                else
                    $itogo_rb['other'] = $main[$value['id_pasp']] ['other'];
                if (isset($itogo_rb['gas']))
                    $itogo_rb['gas']+=$row['gas'];
                else
                    $itogo_rb['gas'] = $row['gas'];
                if (isset($itogo_rb['duty']))
                    $itogo_rb['duty']+=$row['duty'];
                else
                    $itogo_rb['duty'] = $row['duty'];
                }
            }
            $main['itogo_rb']=$itogo_rb;
        } else
            $main = array();
        return $main;
    }

      /*---------- export to Excel inf  ------------*/
    function exportToExcelInfCh($main,$type) {
          $objPHPExcel = new PHPExcel();
                    $objReader = PHPExcel_IOFactory::createReader("Excel2007");
                    $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/builder_basic/inf_ch.xlsx');
//activate worksheet number 1
                    $objPHPExcel->setActiveSheetIndex(0);
                    $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
                    $r = 8;
                    $i = 0;

                    /*+++++++++++++++++++++ style ++++++++++*/
                        /* Итого по ГРОЧС */
            $style_all_grochs = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => '99CCCC'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

                                  /* Итого по области */
            $style_all_region = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => '00CECE'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

                               /* ИТОГО */
            $style_all = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => 'DFE53E'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

            /*+++++++++++++ end style +++++++++++++*/

                    /* для подсчета итого */
            					if ($type == 1) {
            $itogo_grochs = $main['itogo'];
            $itogo_obl = $main['itogo_obl'];
              unset($main['itogo']);
        unset($main['itogo_obl']);
        }

        $itogo_rb = $main['itogo_rb'];
        unset($main['itogo_rb']);

        $last_id_grochs = 0;
        $last_id_region = 0;
        /* конец для подсчета итого */


        foreach ($main as $value) {

                if ($type == 1) {//кроме РОСН/UGZ
                              /* ++++++++++ итого по ГРОЧС ++++++++++++++ */
                             if ($last_id_grochs != $value['id_grochs'] && $last_id_grochs != 0) {

                        $sheet->setCellValue('A' . $r, '');
                        $sheet->setCellValue('B' . $r,'ИТОГО по Г(Р)ОЧС:' );
                        $sheet->setCellValue('C' . $r, '');
                        $sheet->setCellValue('D' . $r, '');
                        $sheet->setCellValue('E' . $r, $itogo_grochs[$last_id_grochs]['shtat']);
                        $sheet->setCellValue('F' . $r, $itogo_grochs[$last_id_grochs]['vacant']);
                        $sheet->setCellValue('G' . $r, $itogo_grochs[$last_id_grochs]['shtat_ch']);
                        $sheet->setCellValue('H' . $r, $itogo_grochs[$last_id_grochs]['vacant_ch']);
                        $sheet->setCellValue('I' . $r, $itogo_grochs[$last_id_grochs]['face']);
                        $sheet->setCellValue('J' . $r, $itogo_grochs[$last_id_grochs]['calc']);
                        $sheet->setCellValue('K' . $r, $itogo_grochs[$last_id_grochs]['trip']);
                        $sheet->setCellValue('L' . $r, $itogo_grochs[$last_id_grochs]['holiday']);
                        $sheet->setCellValue('M' . $r, $itogo_grochs[$last_id_grochs]['ill']);
                        $sheet->setCellValue('N' . $r, $itogo_grochs[$last_id_grochs]['duty']);
                        $sheet->setCellValue('O' . $r, $itogo_grochs[$last_id_grochs]['other']);
                        $sheet->setCellValue('P' . $r, $itogo_grochs[$last_id_grochs]['gas']);
                        $sheet->getStyleByColumnAndRow(0, $r, 15, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС
                        $r++;
                             }
                                /* ++++ Итого по области ++++ */
                                if ($last_id_region != $value['region_id'] && $last_id_region != 0) {

                                       $sheet->setCellValue('A' . $r, '');
                        $sheet->setCellValue('B' . $r,'ИТОГО по области:' );
                        $sheet->setCellValue('C' . $r, '');
                        $sheet->setCellValue('D' . $r, '');
                        $sheet->setCellValue('E' . $r, $itogo_obl[$last_id_region]['shtat']);
                        $sheet->setCellValue('F' . $r, $itogo_obl[$last_id_region]['vacant']);
                        $sheet->setCellValue('G' . $r, $itogo_obl[$last_id_region]['shtat_ch']);
                        $sheet->setCellValue('H' . $r, $itogo_obl[$last_id_region]['vacant_ch']);
                        $sheet->setCellValue('I' . $r, $itogo_obl[$last_id_region]['face']);
                        $sheet->setCellValue('J' . $r, $itogo_obl[$last_id_region]['calc']);
                        $sheet->setCellValue('K' . $r, $itogo_obl[$last_id_region]['trip']);
                        $sheet->setCellValue('L' . $r, $itogo_obl[$last_id_region]['holiday']);
                        $sheet->setCellValue('M' . $r, $itogo_obl[$last_id_region]['ill']);
                        $sheet->setCellValue('N' . $r, $itogo_obl[$last_id_region]['duty']);
                        $sheet->setCellValue('O' . $r, $itogo_obl[$last_id_region]['other']);
                        $sheet->setCellValue('P' . $r, $itogo_obl[$last_id_region]['gas']);
                        $sheet->getStyleByColumnAndRow(0, $r, 15, $r)->applyFromArray($style_all_region); //Итого по обл
                        $r++;

                                }
                             }

                        $i++;
                        $sheet->setCellValue('A' . $r, $i); //№ п/п
                        $sheet->setCellValue('B' . $r, $value['region_name']);
                        $sheet->setCellValue('C' . $r, $value['divizion_name']);
                        $sheet->setCellValue('D' . $r, $value['grochs_name']);
                        $sheet->setCellValue('E' . $r, $value['shtat']);
                        $sheet->setCellValue('F' . $r, $value['vacant']);
                        $sheet->setCellValue('G' . $r, $value['shtat_ch']);
                        $sheet->setCellValue('H' . $r, $value['vacant_ch']);
                        $sheet->setCellValue('I' . $r, $value['face']);
                        $sheet->setCellValue('J' . $r, $value['calc']);
                        $sheet->setCellValue('K' . $r, $value['trip']);
                        $sheet->setCellValue('L' . $r, $value['holiday']);
                        $sheet->setCellValue('M' . $r, $value['ill']);
                        $sheet->setCellValue('N' . $r, $value['duty']);
                        $sheet->setCellValue('O' . $r, $value['other']);
                        $sheet->setCellValue('P' . $r, $value['gas']);

                        $r++;

                          $last_id_grochs = $value['id_grochs'];
                            $last_id_region = $value['region_id'];
                    }

                       if ($type == 1) {//кроме РОСН/UGZ
                    /* ++++ Итого по ГРОЧС ++++ */
                    if ($last_id_grochs && $last_id_grochs != 0) {
                          $sheet->setCellValue('A' . $r, '');
                        $sheet->setCellValue('B' . $r,'ИТОГО по Г(Р)ОЧС:' );
                        $sheet->setCellValue('C' . $r, '');
                        $sheet->setCellValue('D' . $r, '');
                        $sheet->setCellValue('E' . $r, $itogo_grochs[$value['id_grochs']]['shtat']);
                        $sheet->setCellValue('F' . $r, $itogo_grochs[$value['id_grochs']]['vacant']);
                        $sheet->setCellValue('G' . $r, $itogo_grochs[$value['id_grochs']]['shtat_ch']);
                        $sheet->setCellValue('H' . $r, $itogo_grochs[$value['id_grochs']]['vacant_ch']);
                        $sheet->setCellValue('I' . $r, $itogo_grochs[$value['id_grochs']]['face']);
                        $sheet->setCellValue('J' . $r, $itogo_grochs[$value['id_grochs']]['calc']);
                        $sheet->setCellValue('K' . $r, $itogo_grochs[$value['id_grochs']]['trip']);
                        $sheet->setCellValue('L' . $r, $itogo_grochs[$value['id_grochs']]['holiday']);
                        $sheet->setCellValue('M' . $r, $itogo_grochs[$value['id_grochs']]['ill']);
                        $sheet->setCellValue('N' . $r, $itogo_grochs[$value['id_grochs']]['duty']);
                        $sheet->setCellValue('O' . $r, $itogo_grochs[$value['id_grochs']]['other']);
                        $sheet->setCellValue('P' . $r, $itogo_grochs[$value['id_grochs']]['gas']);
                        $sheet->getStyleByColumnAndRow(0, $r, 15, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС
                        $r++;

                    }
                          /* ++++ Итого по области ++++ */
                    if ($last_id_region && $last_id_region != 0) {
                                         $sheet->setCellValue('A' . $r, '');
                        $sheet->setCellValue('B' . $r,'ИТОГО по области:' );
                        $sheet->setCellValue('C' . $r, '');
                        $sheet->setCellValue('D' . $r, '');
                        $sheet->setCellValue('E' . $r, $itogo_obl[$value['region_id']]['shtat']);
                        $sheet->setCellValue('F' . $r, $itogo_obl[$value['region_id']]['vacant']);
                        $sheet->setCellValue('G' . $r, $itogo_obl[$value['region_id']]['shtat_ch']);
                        $sheet->setCellValue('H' . $r, $itogo_obl[$value['region_id']]['vacant_ch']);
                        $sheet->setCellValue('I' . $r, $itogo_obl[$value['region_id']]['face']);
                        $sheet->setCellValue('J' . $r, $itogo_obl[$value['region_id']]['calc']);
                        $sheet->setCellValue('K' . $r, $itogo_obl[$value['region_id']]['trip']);
                        $sheet->setCellValue('L' . $r, $itogo_obl[$value['region_id']]['holiday']);
                        $sheet->setCellValue('M' . $r, $itogo_obl[$value['region_id']]['ill']);
                        $sheet->setCellValue('N' . $r, $itogo_obl[$value['region_id']]['duty']);
                        $sheet->setCellValue('O' . $r, $itogo_obl[$value['region_id']]['other']);
                        $sheet->setCellValue('P' . $r, $itogo_obl[$value['region_id']]['gas']);
                        $sheet->getStyleByColumnAndRow(0, $r, 15, $r)->applyFromArray($style_all_region); //Итого по обл
                        $r++;
                    }
                    }

                        $sheet->setCellValue('A' . $r, '');
                        $sheet->setCellValue('B' . $r,'ИТОГО:' );
                        $sheet->setCellValue('C' . $r, '');
                        $sheet->setCellValue('D' . $r, '');
                        $sheet->setCellValue('E' . $r, $itogo_rb['shtat']);
                        $sheet->setCellValue('F' . $r, $itogo_rb['vacant']);
                        $sheet->setCellValue('G' . $r, $itogo_rb['shtat_ch']);
                        $sheet->setCellValue('H' . $r, $itogo_rb['vacant_ch']);
                        $sheet->setCellValue('I' . $r, $itogo_rb['face']);
                        $sheet->setCellValue('J' . $r, $itogo_rb['calc']);
                        $sheet->setCellValue('K' . $r, $itogo_rb['trip']);
                        $sheet->setCellValue('L' . $r, $itogo_rb['holiday']);
                        $sheet->setCellValue('M' . $r, $itogo_rb['ill']);
                        $sheet->setCellValue('N' . $r, $itogo_rb['duty']);
                        $sheet->setCellValue('O' . $r, $itogo_rb['other']);
                        $sheet->setCellValue('P' . $r, $itogo_rb['gas']);
                        $sheet->getStyleByColumnAndRow(0, $r, 15, $r)->applyFromArray($style_all); //Итого по РБ
                        $r++;


                    /*--------------- вкладка отсутствующие ----------------*/
                    //activate worksheet number 2
                    $objPHPExcel->setActiveSheetIndex(1);
                    $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
                    $r = 8;
                    $i = 0;

                          foreach ($main as $key => $value) {
                        /* ------------- вывод работников в командировке ----------------- */
                        if (!empty($main[$key]['trip_inf'])) {
                            foreach ($main[$key]['trip_inf'] as $trip_inf) {
                                $i++;
                                $sheet->setCellValue('A' . $r, $i); //№ п/п
                                $sheet->setCellValue('B' . $r, $value['region_name']);
                                $sheet->setCellValue('C' . $r, $value['divizion_name']);
                                $sheet->setCellValue('D' . $r, $value['grochs_name']);
                                $sheet->setCellValue('E' . $r, $trip_inf['fio']);
                                $sheet->setCellValue('F' . $r, $trip_inf['position']);
                                $sheet->setCellValue('G' . $r, 'командировка');
                                $sheet->setCellValue('H' . $r, $trip_inf['date1']);
                                $sheet->setCellValue('I' . $r, (($trip_inf['date2']) != NULL) ? $trip_inf['date2'] : '-');
                                $sheet->setCellValue('J' . $r, '-');
                                $sheet->setCellValue('K' . $r, '-');
                                $sheet->setCellValue('L' . $r, (($trip_inf['prikaz']) != NULL) ? $trip_inf['prikaz'] : 'не указано');
                               $is_cosmr=($trip_inf['is_cosmr'] == 1) ?  'согласовано с ЦОСМР' : '';
                                $sheet->setCellValue('M' . $r, (($trip_inf['place']) != NULL) ? $trip_inf['place'] : 'не указано'  .  chr(10). $is_cosmr);
                                $sheet->setCellValue('n' . $r, '-');
                                $r++;
                            }
                        }

                        /* ---------------------- отпуск -------------- */
                        if (!empty($main[$key]['holiday_inf'])) {

                            foreach ($main[$key]['holiday_inf'] as $holiday_inf) {
                                $i++;
                                $sheet->setCellValue('A' . $r, $i); //№ п/п
                                $sheet->setCellValue('B' . $r, $value['region_name']);
                                $sheet->setCellValue('C' . $r, $value['divizion_name']);
                                $sheet->setCellValue('D' . $r, $value['grochs_name']);
                                $sheet->setCellValue('E' . $r, $holiday_inf['fio']);
                                $sheet->setCellValue('F' . $r, $holiday_inf['position']);
                                $sheet->setCellValue('G' . $r, 'отпуск');
                                $sheet->setCellValue('H' . $r, $holiday_inf['date1']);
                                $sheet->setCellValue('I' . $r, (($holiday_inf['date2']) != NULL) ? $holiday_inf['date2'] : '-');
                                $sheet->setCellValue('J' . $r, '-');
                                $sheet->setCellValue('K' . $r, '-');
                                $sheet->setCellValue('L' . $r, (($holiday_inf['prikaz']) != NULL) ? $holiday_inf['prikaz'] : 'не указано');
                                $sheet->setCellValue('M' . $r, '-');
                                $sheet->setCellValue('n' . $r, '-');
                                $r++;
                            }
                        }


                        /* --------------------- больные ------------- */
                        if (!empty($main[$key]['ill_inf'])) {

                            foreach ($main[$key]['ill_inf'] as $ill_inf) {
                                $i++;
                                $sheet->setCellValue('A' . $r, $i); //№ п/п
                                $sheet->setCellValue('B' . $r, $value['region_name']);
                                $sheet->setCellValue('C' . $r, $value['divizion_name']);
                                $sheet->setCellValue('D' . $r, $value['grochs_name']);
                                $sheet->setCellValue('E' . $r, $ill_inf['fio']);
                                $sheet->setCellValue('F' . $r, $ill_inf['position']);
                                $sheet->setCellValue('G' . $r, 'больничный');
                                $sheet->setCellValue('H' . $r, $ill_inf['date1']);
                                $sheet->setCellValue('I' . $r, (($ill_inf['date2']) != NULL) ? $ill_inf['date2'] : '-');
                                $sheet->setCellValue('J' . $r, $ill_inf['maim']);
                                $sheet->setCellValue('K' . $r, (($ill_inf['diagnosis']) != NULL) ? $ill_inf['diagnosis'] : 'не указано');
                                $sheet->setCellValue('L' . $r, '-');
                                $sheet->setCellValue('M' . $r, '-');
                                $sheet->setCellValue('n' . $r, '-');
                                $r++;
                            }
                        }

                        /* ------------------- вывод работников в наряде ---------- */
                        if (!empty($main[$key]['duty_inf'])) {
                            $i++;
                            $sheet->setCellValue('A' . $r, $i); //№ п/п
                            $sheet->setCellValue('B' . $r, $value['region_name']);
                            $sheet->setCellValue('C' . $r, $value['divizion_name']);
                            $sheet->setCellValue('D' . $r, $value['grochs_name']);
                            $sheet->setCellValue('E' . $r, $main[$key]['duty_inf']);
                            $sheet->setCellValue('F' . $r, '-');
                            $sheet->setCellValue('G' . $r, 'наряд');
                            $sheet->setCellValue('H' . $r, '?');
                            $sheet->setCellValue('I' . $r, '?');
                            $sheet->setCellValue('J' . $r, '-');
                            $sheet->setCellValue('K' . $r, '-');
                            $sheet->setCellValue('L' . $r, '-');
                            $sheet->setCellValue('M' . $r, '-');
                            $sheet->setCellValue('N' . $r, '-');
                            $r++;
                        }


                        /* -------------- др причины ------------ */
                        if (!empty($main[$key]['other_inf'])) {
                            foreach ($main[$key]['other_inf'] as $other_inf) {
                                $i++;
                                $sheet->setCellValue('A' . $r, $i); //№ п/п
                                $sheet->setCellValue('B' . $r, $value['region_name']);
                                $sheet->setCellValue('C' . $r, $value['divizion_name']);
                                $sheet->setCellValue('D' . $r, $value['grochs_name']);
                                $sheet->setCellValue('E' . $r, $other_inf['fio']);
                                $sheet->setCellValue('F' . $r, $other_inf['position']);
                                $sheet->setCellValue('G' . $r, 'др.причины');
                                $sheet->setCellValue('H' . $r, $other_inf['date1']);
                                $sheet->setCellValue('I' . $r, (($other_inf['date2']) != NULL) ? $other_inf['date2'] : 'не указано');
                                $sheet->setCellValue('J' . $r, '-');
                                $sheet->setCellValue('K' . $r, '-');
                                $sheet->setCellValue('L' . $r, '-');
                                $sheet->setCellValue('M' . $r, '-');

                                $reas = (($other_inf['reason']) != NULL) ? $other_inf['reason'] : '';
                                $not = (($other_inf['note']) != NULL) ? ', ' . $other_inf['note'] : '';

                                $sheet->setCellValue('N' . $r, $reas . chr(10) . $not);
                                $r++;
                            }
                        }
                    }
                    /* Сохранить в файл */
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="inf_ch.xlsx"');
                    header('Cache-Control: max-age=0');
                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                    $objWriter->save('php://output');
    }

    /* +++++++++++++++++++++++ инф по больным +++++++++++++++++++ */
    $app->post('/basic/inf_ill/:type', function ($type) use ($app) {//umchs/cp информация по сменам - форма
        if (!isset($_POST['export_to_excel'])) {
            array($app, 'is_auth');

           $data['title_name']='Запросы/Больничные';
        $app->render('layouts/header.php',$data);
            $app->render('layouts/menu.php');
            $data['bread'] = getBread();
            $app->render('bread/bread.php', $data);


            /* $type = 1; //type of query-umchs/cp
              $type = 2; //type of query-rosn
             *         */
            $data['type'] = $type;
            ///какая вкладка активна
            $data['active'] = 'ill';

            $app->render('query/pzmenu.php', $data);
            /* --------------- форма поиска ---------------- */
            if ($type == 1) {
                $data = basic_query();
                $app->render('query/form/form_inf_ill.php', $data);
            } elseif($type==2) {
                $data = additional_query();
                $app->render('query/form_rosn/form_inf_ill.php', $data);
            } elseif ($type == 3) { //UGZ
                $data = UGZ_query();
                $app->render('query/form_ugz/form_inf_ill.php', $data);
            } elseif ($type == 4) { //Avia
                $data = AVIA_query();
                $app->render('query/form_avia/form_inf_ill.php', $data);
            }
        }

        /* POST  data - общие для РОСН и УМЧС/ЦП */
        $d_s = $app->request()->post('date_start');
        $date_start = (isset($d_s) && !empty($d_s)) ? date("Y-m-d", strtotime($d_s)) : 0;
        $d_e = $app->request()->post('date_end');
        $date_end = (isset($d_e) && !empty($d_e)) ? date("Y-m-d", strtotime($d_e)) : 0;
        $maim = $app->request()->post('maim');
         $ch = (isset($_POST['ch']) && !empty($_POST['ch'])) ? $_POST['ch'] : 0;

        if($date_start != 0  && $date_end != 0){
            $data['date_start']=$date_start;
            $data['date_end']=$date_end;
        }
        if($ch != 0){
            $data['ch']=$ch;
        }
        if(!empty($maim)){
            $data['maim_request']=R::getCell('select name from maim where id = ?',array($maim));
        }

        /* ---------  результат поиска УМЧС/ЦП --------- */
        if ($type == 1) {

            $region = $app->request()->post('region'); //область
            $grochs = $app->request()->post('locorg'); //грочс
            $divizion = $app->request()->post('diviz'); //подразделение

            /*             * ******  формированеи результата ****** */
            $main = getInfChIll($region, $grochs, $divizion, $date_start, $date_end, $maim,$ch);
            $main=  deleteDublicateAbsent($main);
            $data['main'] = $main;


            if (!empty($data['main'])) {
                /* -------------------------- export to excel ---------------------------- */
                if (isset($_POST['export_to_excel'])) {
                    exportToExcelInfIll($main,$date_start, $date_end,$type,$data);
                }
                /* ------------------ отображение на экран ----------------------------- */ else {
                    $app->render('query/result/inf_ill.php', $data); //result
                    $app->render('query/pzend.php');
                }
            } else {
                $app->render('msg/emtyResult.php', $data); //no result
            }
        }
        /* ----------------  результат поиска РОСН/UGZ  ----------------- */ else {

            $region = $app->request()->post('region'); //РОСН
            $grochs = $app->request()->post('locorg'); //ОУ

            /*             * ******  формированеи результата ****** */
                        if ($type == 2)
                $organ = ROSN;
            elseif ($type == 3)
                $organ = UGZ;
            elseif ($type == 4)
                $organ = AVIA;
            $main = getInfChIllAdditional($region, $grochs, $date_start, $date_end, $maim,$organ,$ch);
            $main=  deleteDublicateAbsent($main);
            $data['main'] = $main;
            /*             * ******  отображение результата ******* */
            if (!empty($data['main'])) {
                  /* -------------------------- export to excel ---------------------------- */
                if (isset($_POST['export_to_excel'])) {
                    exportToExcelInfIll($main,$date_start, $date_end,$type,$data);
                }
                /* ------------------ отображение на экран ----------------------------- */ else {
                    $app->render('query/result/inf_ill.php', $data); //result
                    $app->render('query/pzend.php');
                }
            } else {
                $app->render('msg/emtyResult.php', $data); //no result
            }
        }

        if (!isset($_POST['export_to_excel'])) {
            $app->render('layouts/footer.php');
        }
    });

    /*     * ****  УМЧС/ЦП - вфбор из БД для отчета инф по больным **** */

    function getInfChIll($region, $grochs, $divizion, $date_start, $date_end, $maim,$ch=0) {


        if (isset($region) && !empty($region)) {
            if (isset($grochs) && !empty($grochs)) {
                if (isset($divizion) && !empty($divizion)) {
                    /* ------------- по ПАСЧ -------------- */
                    $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_ill where id_pasp = ?', array($divizion));
                }
                /* ----------------  по ГРОЧС ----------------- */ else {
                    //выбор id ПАСЧей этого ГРОЧС
                    $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_ill where id_grochs = ?', array($grochs));
                }
            }
            /* --------------- по области ---------------- */ else {
                //исключить подразделения РОСН, УГЗ,Авиация
                $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_ill where region_id = ? and org_id not in (?,?,?)  ', array($region, ROSN,UGZ,AVIA));
            }
        }
        //по РБ
        else {
            //исключить подразделения РОСН, УГЗ,Авиация
            $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_ill where org_id not in (?,?,?) ', array(ROSN,UGZ,AVIA));
        }
        /* ++++++++  РЕЗУЛЬТАТ ++++++++++ */
        $param = array(); //параметры запроса
        //пробежать по всем ПАСЧ - выбрать инф по деж смене , определить дату и смены, на которую надо позже выбрать отсутствующих
        foreach ($id_pasp as $value) {

            $sql = 'select * from builder_basic_inf_ill where id_pasp=? ';
            $param = array($value['id_pasp']);

            if (!empty($maim) && isset($maim)) {//вид травмы выбран
                $q_maim = ' AND maim_id = ? ';
                $param[] = $maim;
            }

            if (isset($q_maim)) { //добавляем maim
                $sql = $sql . $q_maim;
            }

            /* --- выбрана смен --- */

            if ( isset($ch) && $ch != 0 ) {
                $q_ch = ' AND ch = ? ';
                $param[] = $ch;
            }

            if (isset($q_ch)) { //добавляем ch
                $sql = $sql . $q_ch;
            }
            /* --- КОНЕЦ выбрана смен --- */



            //если выбрана начальная дата и конечная дата
            if (($date_start != 0) && ($date_end != 0)) {
                $d_start = ' AND i_id not in (select id from ill where date2 < ? or date1 > ?)  ';
                $param[] = $date_start;
                $param[] = $date_end;

                if (isset($d_start))//добавляем начальную дату
                    $sql = $sql . $d_start;
            }

            $sql=$sql.' order by fio, date1';

            $inf1 = R::getAll($sql, $param);

            $main[$value['id_pasp']] = array();
            if (!empty($inf1)) {
                //оформить инф по деж смене в массив : [id_pasp] => array([shtat_ch]=>25,...)
                foreach ($inf1 as $row) {
                      $main[$value['id_pasp']] ['id_grochs'] = $row['id_grochs'];
                       $main[$value['id_pasp']] ['region_id'] = $row['region_id'];
                    $main[$value['id_pasp']] ['region_name'] = $row['region_name'];
                    $main[$value['id_pasp']] ['name'] = $row['organ']; //Жлобинский РОЧС
                    $main[$value['id_pasp']] ['name_div'] = $row['divizion']; //ПАСЧ-1
                    $main[$value['id_pasp']] ['id_fio'] = $row['id_fio'];
                    $main[$value['id_pasp']] ['fio'] = $row['fio'];
                    $main[$value['id_pasp']] ['ch'] = $row['ch'];
                    $main[$value['id_pasp']] ['position'] = $row['position'];
                    $main[$value['id_pasp']] ['date1'] = $row['date1'];
                    $main[$value['id_pasp']] ['date2'] = $row['date2'];
                    $main[$value['id_pasp']] ['maim'] = $row['maim'];
                    $main[$value['id_pasp']] ['diagnosis'] = $row['diagnosis'];

                    $m[] = $main;
                    unset($main);
                }
            }
        }
        if (!empty($m)) {
            return $m;
        } else {
            return array();
        }
    }

    /*     * ****  РОСН - вфбор из БД для отчета инф по больным **** */

    function getInfChIllAdditional($region, $grochs, $date_start, $date_end, $maim,$organ,$ch=0) {


        if (isset($region) && !empty($region)) {
            if (isset($grochs) && !empty($grochs)) {

                /* ----------------  по ОУ ----------------- */
                //выбор id ПАСЧей этого ГРОЧС
                $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_ill where id_grochs = ?', array($grochs));
            }
            /* --------------- по всему РОСН ---------------- */ else {
                //исключить подразделения РОСН
                $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_ill where  org_id = ? ', array($organ));
            }
        }
        //по РБ
        else {
            //исключить подразделения РОСН
            $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_ill where org_id = ? ', array($organ));
        }
        /* ++++++++  РЕЗУЛЬТАТ ++++++++++ */
        $param = array(); //параметры запроса
        //пробежать по всем ПАСЧ - выбрать инф по деж смене , определить дату и смены, на которую надо позже выбрать отсутствующих
        foreach ($id_pasp as $value) {

            $sql = 'select * from builder_basic_inf_ill where id_pasp=? ';
            $param = array($value['id_pasp']);

            if (!empty($maim) && isset($maim)) {//вид травмы выбран
                $q_maim = ' AND maim_id = ? ';
                $param[] = $maim;
            }

            if (isset($q_maim)) { //добавляем maim
                $sql = $sql . $q_maim;
            }


            /* --- выбрана смен --- */

            if ( isset($ch) && $ch != 0 ) {
                $q_ch = ' AND ch = ? ';
                $param[] = $ch;
            }

            if (isset($q_ch)) { //добавляем ch
                $sql = $sql . $q_ch;
            }
            /* --- КОНЕЦ выбрана смен --- */



            //если выбрана начальная дата и конечная дата
            if (($date_start != 0) && ($date_end != 0)) {
                $d_start = ' AND i_id not in (select id from ill where date2 < ? or date1 > ?) ';
                $param[] = $date_start;
                $param[] = $date_end;

                if (isset($d_start))//добавляем начальную дату
                    $sql = $sql . $d_start;
            }

            $sql=$sql.' order by fio, date1';

            $inf1 = R::getAll($sql, $param);

            $main[$value['id_pasp']] = array();
            if (!empty($inf1)) {
                //оформить инф по деж смене в массив : [id_pasp] => array([shtat_ch]=>25,...)
                foreach ($inf1 as $row) {
                    $main[$value['id_pasp']] ['region_name'] = $row['region_name'];
                    $main[$value['id_pasp']] ['name'] = $row['organ']; //Жлобинский РОЧС
                    $main[$value['id_pasp']] ['name_div'] = $row['divizion']; //ПАСЧ-1
                    $main[$value['id_pasp']] ['fio'] = $row['fio'];
                    $main[$value['id_pasp']] ['id_fio'] = $row['id_fio'];
                    $main[$value['id_pasp']] ['ch'] = $row['ch'];
                    $main[$value['id_pasp']] ['position'] = $row['position'];
                    $main[$value['id_pasp']] ['date1'] = $row['date1'];
                    $main[$value['id_pasp']] ['date2'] = $row['date2'];
                    $main[$value['id_pasp']] ['maim'] = $row['maim'];
                    $main[$value['id_pasp']] ['diagnosis'] = $row['diagnosis'];

                    $m[] = $main;
                    unset($main);
                }
            }
        }
        if (!empty($m)) {
            return $m;
        } else {
            return array();
        }
    }

        /*---------- export to Excel inf ill ------------*/
    function exportToExcelInfIll($main, $date_strat, $date_end,$type,$data) {
        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/builder_basic/inf_ill.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 8;
        $i = 0;

           /*+++++++++++++++++++++ style ++++++++++*/
                        /* Итого по ГРОЧС */
            $style_all_grochs = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => '99CCCC'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

                                  /* Итого по области */
            $style_all_region = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => '00CECE'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

                               /* ИТОГО */
            $style_all = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => 'DFE53E'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

            /*+++++++++++++ end style +++++++++++++*/

        /* ---всего по ГРОЧС  --- */
        $all_g = 0;
        /* ---всего по области --- */
        $all_r = 0;

        $last_id_grochs = 0;
        $last_id_region = 0;
        $k = 0; //кол-во больных


            $ch=(isset($data['ch']))? (', смена '.$data['ch']) :'';//номер выбранной смены
            $maim_request=(isset($data['maim_request']))? (', вид травмы: '.$data['maim_request']) :'';//вид травмы

        if ($date_strat != 0 && $date_end != 0) {
            $sheet->setCellValue('A2', 'c ' . $date_strat . ' по ' . $date_end.$ch.$maim_request);
        }
                else{
            $sheet->setCellValue('A2', $ch.$maim_request);
        }

        foreach ($main as $key => $value) {
            foreach ($value as $key2 => $row) {
                if (!empty($value[$key2])) {

                       if ($type == 1) {//кроме РОСН/UGZ
                        /* ++++++++++ итого по ГРОЧС ++++++++++++++ */
                        if ($last_id_grochs != $row['id_grochs'] && $last_id_grochs != 0) {
                            $sheet->setCellValue('A' . $r, '');
                            $sheet->setCellValue('B' . $r,'ИТОГО по Г(Р)ОЧС:' );
                            $sheet->setCellValue('C' . $r, $all_g);
                            $sheet->setCellValue('D' . $r, '');
                            $sheet->setCellValue('E' . $r, '');
                            $sheet->setCellValue('F' . $r, '');
                            $sheet->setCellValue('G' . $r, '');
                            $sheet->setCellValue('H' . $r, '');
                            $sheet->setCellValue('I' . $r, '');
                            $sheet->setCellValue('J' . $r, '');

                              $sheet->getStyleByColumnAndRow(0, $r, 9, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС
                            $r++;

                            $all_g = 0; //обнулсть
                        }

                        /* ++++ Итого по области ++++ */
                        if ($last_id_region != $row['region_id'] && $last_id_region != 0) {
                            $sheet->setCellValue('A' . $r, '');
                            $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                            $sheet->setCellValue('C' . $r, $all_r);
                            $sheet->setCellValue('D' . $r, '');
                            $sheet->setCellValue('E' . $r, '');
                            $sheet->setCellValue('F' . $r, '');
                            $sheet->setCellValue('G' . $r, '');
                            $sheet->setCellValue('H' . $r, '');
                            $sheet->setCellValue('I' . $r, '');
                            $sheet->setCellValue('J' . $r, '');
                             $sheet->getStyleByColumnAndRow(0, $r, 9, $r)->applyFromArray($style_all_region); //Итого по области
                            $r++;

                            $all_r = 0; //обнулсть
                        }
                          $all_g+=1;
                    $all_r+=1;

                    $last_id_grochs = $row['id_grochs'];
                    $last_id_region = $row['region_id'];
                    }

                    $i++;
                    $sheet->setCellValue('A' . $r, $i); //№ п/п
                    $sheet->setCellValue('B' . $r, $row['region_name']);
                    $sheet->setCellValue('C' . $r, $row['name']);
                    $sheet->setCellValue('D' . $r, $row['name_div']);
                    $sheet->setCellValue('E' . $r, $row['fio']);
                    $sheet->setCellValue('F' . $r, $row['position']);
                    $sheet->setCellValue('G' . $r, $row['date1']);
                    $sheet->setCellValue('H' . $r, $row['date2']);
                    $sheet->setCellValue('I' . $r, $row['maim']);
                    $sheet->setCellValue('J' . $r, $row['diagnosis']);
                    $r++;

                    $k++; //itogo

                }
            }
        }

            if ($type == 1) {//кроме РОСН/UGZ
            /* ++++ Итого по ГРОЧС ++++ */
            if ($last_id_grochs && $last_id_grochs != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                $sheet->setCellValue('C' . $r, $all_g);
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, '');
                $sheet->setCellValue('F' . $r, '');
                $sheet->setCellValue('G' . $r, '');
                $sheet->setCellValue('H' . $r, '');
                $sheet->setCellValue('I' . $r, '');
                $sheet->setCellValue('J' . $r, '');
                  $sheet->getStyleByColumnAndRow(0, $r, 9, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС
                $r++;

                $all_g = 0; //обнулсть
            }

            /* ++++ Итого по области ++++ */
            if ($last_id_region && $last_id_region != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                $sheet->setCellValue('C' . $r, $all_r);
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, '');
                $sheet->setCellValue('F' . $r, '');
                $sheet->setCellValue('G' . $r, '');
                $sheet->setCellValue('H' . $r, '');
                $sheet->setCellValue('I' . $r, '');
                $sheet->setCellValue('J' . $r, '');
                 $sheet->getStyleByColumnAndRow(0, $r, 9, $r)->applyFromArray($style_all_region); //Итого по области
                $r++;

                $all_r = 0; //обнулсть
            }
        }
        if ($k != 0) {
            $sheet->setCellValue('A' . $r, '');
            $sheet->setCellValue('B' . $r, 'ИТОГО:');
            $sheet->setCellValue('C' . $r, $k);
            $sheet->setCellValue('D' . $r, '');
            $sheet->setCellValue('E' . $r, '');
            $sheet->setCellValue('F' . $r, '');
            $sheet->setCellValue('G' . $r, '');
            $sheet->setCellValue('H' . $r, '');
            $sheet->setCellValue('I' . $r, '');
            $sheet->setCellValue('J' . $r, '');
             $sheet->getStyleByColumnAndRow(0, $r, 9, $r)->applyFromArray($style_all); //Итого
            $r++;
        }

        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="inf_ill.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    /* +++++++++++++++++++ инф по отпускам +++++++++++++++++ */
    $app->post('/basic/inf_holiday/:type', function ($type) use ($app) {//umchs/cp информация по сменам - форма
        if (!isset($_POST['export_to_excel'])) {
            array($app, 'is_auth');

           $data['title_name']='Запросы/Отпуска';
        $app->render('layouts/header.php',$data);
            $app->render('layouts/menu.php');
            $data['bread'] = getBread();
            $app->render('bread/bread.php', $data);


            /* $type = 1; //type of query-umchs/cp
              $type = 2; //type of query-rosn
             *         */
            $data['type'] = $type;
            ///какая вкладка активна
            $data['active'] = 'holiday';

            $app->render('query/pzmenu.php', $data);
            /* --------------- форма поиска ---------------- */
            if ($type == 1) {
                $data = basic_query();
                $app->render('query/form/form_inf_holiday.php', $data);
            } elseif($type==2) {//ROSN
                $data = additional_query();
                $app->render('query/form_rosn/form_inf_holiday.php', $data);
            } elseif ($type == 3) { //UGZ
                $data = UGZ_query();
                $app->render('query/form_ugz/form_inf_holiday.php', $data);
            } elseif ($type == 4) { //Avia
                $data = AVIA_query();
                $app->render('query/form_avia/form_inf_holiday.php', $data);
            }
        }
        /* POST  data - общие для РОСН и УМЧС/ЦП */
        $d_s = $app->request()->post('date_start');
        $date_start = (isset($d_s) && !empty($d_s)) ? date("Y-m-d", strtotime($d_s)) : 0;
        $d_e = $app->request()->post('date_end');
        $date_end = (isset($d_e) && !empty($d_e)) ? date("Y-m-d", strtotime($d_e)) : 0;
           $ch = (isset($_POST['ch']) && !empty($_POST['ch'])) ? $_POST['ch'] : 0;

             if($date_start != 0  && $date_end != 0){
            $data['date_start']=$date_start;
            $data['date_end']=$date_end;
        }

                if($ch != 0){
            $data['ch']=$ch;
        }


        /* ---------  результат поиска УМЧС --------- */
        if ($type == 1) {

            $region = $app->request()->post('region'); //область
            $grochs = $app->request()->post('locorg'); //грочс
            $divizion = $app->request()->post('diviz'); //подразделение

            /*             * ******  формированеи результата ****** */
            $main = getInfChHoliday($region, $grochs, $divizion, $date_start, $date_end,$ch);
             $main=  deleteDublicateAbsent($main);
            $data['main'] = $main;

            /*             * ******  отображение результата ******* */
            if (!empty($data['main'])) {
                /* -------------------------- export to excel ---------------------------- */
                if (isset($_POST['export_to_excel'])) {
                    exportToExcelInfHol($main, $date_start, $date_end,$type,$data);
                }
                /* ------------------ отображение на экран ----------------------------- */ else {
                    $app->render('query/result/inf_holiday.php', $data); //result
                    $app->render('query/pzend.php');
                }
            } else {
                $app->render('msg/emtyResult.php', $data); //no result
            }
        }
        /* ----------------  результат поиска РОСН/UGZ  ----------------- */ else {

            $region = $app->request()->post('region'); //РОСН
            $grochs = $app->request()->post('locorg'); //ОУ

            /*             * ******  формированеи результата ****** */
                               if ($type == 2)
                $organ = ROSN;
            elseif ($type == 3)
                $organ = UGZ;
            elseif($type==4)
                $organ=AVIA;
            $main = getInfChHolidayAdditional($region, $grochs, $date_start, $date_end,$organ,$ch);
             $main=  deleteDublicateAbsent($main);
            $data['main'] = $main;

            if (!empty($data['main'])) {
                /* -------------------------- export to excel ---------------------------- */
                if (isset($_POST['export_to_excel'])) {
                    exportToExcelInfHol($main, $date_start, $date_end,$type,$data);
                }
                /* ------------------ отображение на экран ----------------------------- */ else {
                    $app->render('query/result/inf_holiday.php', $data); //result
                    $app->render('query/pzend.php');
                }
            } else {
                $app->render('msg/emtyResult.php', $data); //no result
            }
        }


        if (!isset($_POST['export_to_excel'])) {
            $app->render('layouts/footer.php');
        }
    });

    /*     * ****  УМЧС/ЦП - вфбор из БД для отчета инф по отпускам **** */

    function getInfChHoliday($region, $grochs, $divizion, $date_start, $date_end,$ch=0) {


        if (isset($region) && !empty($region)) {
            if (isset($grochs) && !empty($grochs)) {
                if (isset($divizion) && !empty($divizion)) {
                    /* ------------- по ПАСЧ -------------- */
                    $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_hol where id_pasp = ?', array($divizion));
                }
                /* ----------------  по ГРОЧС ----------------- */ else {
                    //выбор id ПАСЧей этого ГРОЧС
                    $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_hol where id_grochs = ?', array($grochs));
                }
            }
            /* --------------- по области ---------------- */ else {
                //исключить подразделения РОСН,УГЗ,Авиация
                $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_hol where region_id = ? and org_id not in (?,?,?) ', array($region, ROSN,UGZ,AVIA));
            }
        }
        //по РБ
        else {
            //исключить подразделения РОСН, УГЗ, Авиация
            $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_hol where org_id not in (?,?,?)  ', array(ROSN,UGZ,AVIA));
        }
        /* ++++++++  РЕЗУЛЬТАТ ++++++++++ */
        $param = array(); //параметры запроса
        //пробежать по всем ПАСЧ - выбрать инф по деж смене , определить дату и смены, на которую надо позже выбрать отсутствующих
        foreach ($id_pasp as $value) {

            $sql = 'select * from builder_basic_inf_hol where id_pasp=? ';
            $param = array($value['id_pasp']);

            //если выбрана начальная дата и конечная дата
            if (($date_start != 0) && ($date_end != 0)) {
                $d_start = ' AND  h_id not in (select id from holiday where date2 < ? or date1 > ?) ';
                $param[] = $date_start;
                $param[] = $date_end;

                if (isset($d_start))//добавляем начальную дату
                    $sql = $sql . $d_start;
            }

                    /* --- выбрана смен --- */

            if ( isset($ch) && $ch != 0 ) {
                $q_ch = ' AND ch = ? ';
                $param[] = $ch;
            }

            if (isset($q_ch)) { //добавляем ch
                $sql = $sql . $q_ch;
            }
            /* --- КОНЕЦ выбрана смен --- */


              $sql=$sql.' order by fio, date1';

            $inf1 = R::getAll($sql, $param);

            $main[$value['id_pasp']] = array();
            if (!empty($inf1)) {
                //оформить инф по деж смене в массив : [id_pasp] => array([shtat_ch]=>25,...)
                foreach ($inf1 as $row) {
                          $main[$value['id_pasp']] ['id_grochs'] = $row['id_grochs'];
                       $main[$value['id_pasp']] ['region_id'] = $row['region_id'];
                    $main[$value['id_pasp']] ['region_name'] = $row['region_name'];
                    $main[$value['id_pasp']] ['name'] = $row['organ']; //Жлобинский РОЧС
                    $main[$value['id_pasp']] ['name_div'] = $row['divizion']; //ПАСЧ-1
                    $main[$value['id_pasp']] ['fio'] = $row['fio'];
                    $main[$value['id_pasp']] ['ch'] = $row['ch'];
                    $main[$value['id_pasp']] ['id_fio'] = $row['id_fio'];
                    $main[$value['id_pasp']] ['position'] = $row['position'];
                    $main[$value['id_pasp']] ['date1'] = $row['date1'];
                    $main[$value['id_pasp']] ['date2'] = $row['date2'];
                    $main[$value['id_pasp']] ['prikaz'] = $row['prikaz'];

                    $m[] = $main;
                    unset($main);
                }
            }
        }
        if (!empty($m)) {
            return $m;
        } else {
            return array();
        }
    }

    /*     * ****  РОСН - вфбор из БД для отчета инф по отпускам **** */

    function getInfChHolidayAdditional($region, $grochs, $date_start, $date_end,$organ,$ch=0) {


        if (isset($region) && !empty($region)) {
            if (isset($grochs) && !empty($grochs)) {

                /* ----------------  по ОУ----------------- */
                //выбор id ПАСЧей этого ГРОЧС
                $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_hol where id_grochs = ?', array($grochs));
            }
            /* --------------- по всему РОСН---------------- */ else {
                $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_hol where  org_id = ? ', array($organ));
            }
        }
        //по РБ
        else {
            $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_hol where org_id = ? ', array($organ));
        }
        /* ++++++++  РЕЗУЛЬТАТ ++++++++++ */
        $param = array(); //параметры запроса
        //пробежать по всем ПАСЧ - выбрать инф по деж смене , определить дату и смены, на которую надо позже выбрать отсутствующих
        foreach ($id_pasp as $value) {

            $sql = 'select * from builder_basic_inf_hol where id_pasp=? ';
            $param = array($value['id_pasp']);

            //если выбрана начальная дата и конечная дата
            if (($date_start != 0) && ($date_end != 0)) {
                $d_start = ' AND h_id not in (select id from holiday where date2 < ? or date1 > ?)';
                $param[] = $date_start;
                $param[] = $date_end;

                if (isset($d_start))//добавляем начальную дату
                    $sql = $sql . $d_start;
            }

                    /* --- выбрана смен --- */

            if ( isset($ch) && $ch != 0 ) {
                $q_ch = ' AND ch = ? ';
                $param[] = $ch;
            }

            if (isset($q_ch)) { //добавляем ch
                $sql = $sql . $q_ch;
            }
            /* --- КОНЕЦ выбрана смен --- */

              $sql=$sql.' order by fio, date1';

            $inf1 = R::getAll($sql, $param);

            $main[$value['id_pasp']] = array();
            if (!empty($inf1)) {
                //оформить инф по деж смене в массив : [id_pasp] => array([shtat_ch]=>25,...)
                foreach ($inf1 as $row) {
                    $main[$value['id_pasp']] ['region_name'] = $row['region_name'];
                    $main[$value['id_pasp']] ['name'] = $row['organ']; //Жлобинский РОЧС
                    $main[$value['id_pasp']] ['name_div'] = $row['divizion']; //ПАСЧ-1
                    $main[$value['id_pasp']] ['fio'] = $row['fio'];
                    $main[$value['id_pasp']] ['ch'] = $row['ch'];
                    $main[$value['id_pasp']] ['id_fio'] = $row['id_fio'];
                    $main[$value['id_pasp']] ['position'] = $row['position'];
                    $main[$value['id_pasp']] ['date1'] = $row['date1'];
                    $main[$value['id_pasp']] ['date2'] = $row['date2'];
                    $main[$value['id_pasp']] ['prikaz'] = $row['prikaz'];

                    $m[] = $main;
                    unset($main);
                }
            }
        }
        if (!empty($m)) {
            return $m;
        } else {
            return array();
        }
    }

            /*---------- export to Excel inf holiday ------------*/
    function exportToExcelInfHol($main,$date_strat, $date_end,$type,$data) {
        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/builder_basic/inf_holiday.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 8;
        $i = 0;

          /*+++++++++++++++++++++ style ++++++++++*/
                        /* Итого по ГРОЧС */
            $style_all_grochs = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => '99CCCC'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

                                  /* Итого по области */
            $style_all_region = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => '00CECE'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

                               /* ИТОГО */
            $style_all = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => 'DFE53E'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

            /*+++++++++++++ end style +++++++++++++*/

        /* ---всего по ГРОЧС  --- */
$all_g = 0;
/* ---всего по области --- */
$all_r = 0;

$last_id_grochs = 0;
$last_id_region = 0;
$k = 0; //кол-во отпусков


   $ch=(isset($data['ch']))? (', смена '.$data['ch']) :'';//номер выбранной смены


        if($date_strat != 0 && $date_end != 0){
            $sheet->setCellValue('A2', 'c '.$date_strat.' по '.$date_end.$ch);
        }
                else{
            $sheet->setCellValue('A2', $ch);
        }

        foreach ($main as $key => $value) {
            foreach ($value as $key2 => $row) {
                if (!empty($value[$key2])) {
                   if ($type == 1) {//кроме РОСН/UGZ
                        /* ++++++++++ итого по ГРОЧС ++++++++++++++ */
                        if ($last_id_grochs != $row['id_grochs'] && $last_id_grochs != 0) {
                            $sheet->setCellValue('A' . $r, '');
                            $sheet->setCellValue('B' . $r,'ИТОГО по Г(Р)ОЧС:' );
                            $sheet->setCellValue('C' . $r, $all_g);
                            $sheet->setCellValue('D' . $r, '');
                            $sheet->setCellValue('E' . $r, '');
                            $sheet->setCellValue('F' . $r, '');
                            $sheet->setCellValue('G' . $r, '');
                            $sheet->setCellValue('H' . $r, '');
                            $sheet->setCellValue('I' . $r, '');
                              $sheet->getStyleByColumnAndRow(0, $r, 8, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС
                            $r++;

                            $all_g = 0; //обнулсть
                        }

                        /* ++++ Итого по области ++++ */
                        if ($last_id_region != $row['region_id'] && $last_id_region != 0) {
                            $sheet->setCellValue('A' . $r, '');
                            $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                            $sheet->setCellValue('C' . $r, $all_r);
                            $sheet->setCellValue('D' . $r, '');
                            $sheet->setCellValue('E' . $r, '');
                            $sheet->setCellValue('F' . $r, '');
                            $sheet->setCellValue('G' . $r, '');
                            $sheet->setCellValue('H' . $r, '');
                            $sheet->setCellValue('I' . $r, '');
                             $sheet->getStyleByColumnAndRow(0, $r, 8, $r)->applyFromArray($style_all_region); //Итого по области
                            $r++;

                            $all_r = 0; //обнулсть
                        }
                                               $all_g+=1;
                    $all_r+=1;

                    $last_id_grochs = $row['id_grochs'];
                    $last_id_region = $row['region_id'];
                    }
                    $i++;
                    $sheet->setCellValue('A' . $r, $i); //№ п/п
                    $sheet->setCellValue('B' . $r, $row['region_name']);
                    $sheet->setCellValue('C' . $r, $row['name']);
                    $sheet->setCellValue('D' . $r, $row['name_div']);
                    $sheet->setCellValue('E' . $r, $row['fio']);
                    $sheet->setCellValue('F' . $r, $row['position']);
                    $sheet->setCellValue('G' . $r, $row['date1']);
                    $sheet->setCellValue('H' . $r, $row['date2']);
                    $sheet->setCellValue('I' . $r, $row['prikaz']);
                    $r++;

                    $k++; //itogo

                }
            }
        }
         if ($type == 1) {//кроме РОСН/UGZ
            /* ++++ Итого по ГРОЧС ++++ */
            if ($last_id_grochs && $last_id_grochs != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                $sheet->setCellValue('C' . $r, $all_g);
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, '');
                $sheet->setCellValue('F' . $r, '');
                $sheet->setCellValue('G' . $r, '');
                $sheet->setCellValue('H' . $r, '');
                $sheet->setCellValue('I' . $r, '');
                  $sheet->getStyleByColumnAndRow(0, $r, 8, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС
                $r++;

                $all_g = 0; //обнулсть
            }

            /* ++++ Итого по области ++++ */
            if ($last_id_region && $last_id_region != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                $sheet->setCellValue('C' . $r, $all_r);
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, '');
                $sheet->setCellValue('F' . $r, '');
                $sheet->setCellValue('G' . $r, '');
                $sheet->setCellValue('H' . $r, '');
                $sheet->setCellValue('I' . $r, '');
                 $sheet->getStyleByColumnAndRow(0, $r, 8, $r)->applyFromArray($style_all_region); //Итого по области
                $r++;

                $all_r = 0; //обнулсть
            }
        }
        if ($k != 0) {
            $sheet->setCellValue('A' . $r, '');
            $sheet->setCellValue('B' . $r, 'ИТОГО:');
            $sheet->setCellValue('C' . $r, $k);
            $sheet->setCellValue('D' . $r, '');
            $sheet->setCellValue('E' . $r, '');
            $sheet->setCellValue('F' . $r, '');
            $sheet->setCellValue('G' . $r, '');
            $sheet->setCellValue('H' . $r, '');
            $sheet->setCellValue('I' . $r, '');
             $sheet->getStyleByColumnAndRow(0, $r, 8, $r)->applyFromArray($style_all); //Итого
            $r++;
        }
        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="inf_holiday.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    /* +++++++++++++++++++ инф по командировкам ++++++++++++++++++++ */
    $app->post('/basic/inf_trip/:type', function ($type) use ($app) {//umchs/cp информация по сменам - форма
        if (!isset($_POST['export_to_excel'])) {
            array($app, 'is_auth');

           $data['title_name']='Запросы/Командировки';
        $app->render('layouts/header.php',$data);
            $app->render('layouts/menu.php');
            $data['bread'] = getBread();
            $app->render('bread/bread.php', $data);


            /* $type = 1; //type of query-umchs/cp
              $type = 2; //type of query-rosn
             *         */
            $data['type'] = $type;
            ///какая вкладка активна
            $data['active'] = 'trip';

            $app->render('query/pzmenu.php', $data);
            /* --------------- форма поиска ---------------- */
            if ($type == 1) {
                $data = basic_query();
                $app->render('query/form/form_inf_trip.php', $data);
            } elseif($type==2) {//ROSN
                $data = additional_query();
                $app->render('query/form_rosn/form_inf_trip.php', $data);
            } elseif ($type == 3) { //UGZ
                $data = UGZ_query();
                $app->render('query/form_ugz/form_inf_trip.php', $data);
            } elseif ($type == 4) { //Avia
                $data = AVIA_query();
                $app->render('query/form_avia/form_inf_trip.php', $data);
            }
        }

        /* POST  data - общие для РОСН и УМЧС/ЦП */
        $d_s = $app->request()->post('date_start');
        $date_start = (isset($d_s) && !empty($d_s)) ? date("Y-m-d", strtotime($d_s)) : 0;
        $d_e = $app->request()->post('date_end');
        $date_end = (isset($d_e) && !empty($d_e)) ? date("Y-m-d", strtotime($d_e)) : 0;
        $ch = (isset($_POST['ch']) && !empty($_POST['ch'])) ? $_POST['ch'] : 0;

           if($date_start != 0  && $date_end != 0){
            $data['date_start']=$date_start;
            $data['date_end']=$date_end;
        }


                if($ch != 0){
            $data['ch']=$ch;
        }

        /* ---------  результат поиска УМЧС/Aviacia --------- */
        if ($type == 1) {

            $region = $app->request()->post('region'); //область
            $grochs = $app->request()->post('locorg'); //грочс
            $divizion = $app->request()->post('diviz'); //подразделение

            /*             * ******  формированеи результата ****** */
            $main = getInfChTrip($region, $grochs, $divizion, $date_start, $date_end,$ch);
             $main=  deleteDublicateAbsent($main);
            $data['main'] = $main;

            /*             * ******  отображение результата ******* */
            if (!empty($data['main'])) {
                /* -------------------------- export to excel ---------------------------- */
                if (isset($_POST['export_to_excel'])) {
                    exportToExcelInfTrip($main, $date_start, $date_end,$type,$data);
                }
                /* ------------------ отображение на экран ----------------------------- */ else {
                    $app->render('query/result/inf_trip.php', $data); //result
                    $app->render('query/pzend.php');
                }
            } else {
                $app->render('msg/emtyResult.php', $data); //no result
            }
        }
        /* ----------------  результат поиска РОСН ----------------- */ else {

            $region = $app->request()->post('region'); //РОСН
            $grochs = $app->request()->post('locorg'); //ОУ

            /*             * ******  формированеи результата ****** */
                                    if ($type == 2)
                $organ = ROSN;
            elseif ($type == 3)
                $organ = UGZ;
            elseif($type==4)
                $organ=AVIA;
            $main = getInfChTripAdditional($region, $grochs, $date_start, $date_end, $organ,$ch);
            $main = deleteDublicateAbsent($main);
            $data['main'] = $main;
            /*             * ******  отображение результата ******* */
            if (!empty($data['main'])) {
                /* -------------------------- export to excel ---------------------------- */
                if (isset($_POST['export_to_excel'])) {
                    exportToExcelInfTrip($main, $date_start, $date_end,$type, $data);
                }
                /* ------------------ отображение на экран ----------------------------- */ else {
                    $app->render('query/result/inf_trip.php', $data); //result
                    $app->render('query/pzend.php');
                }
            } else {
                $app->render('msg/emtyResult.php', $data); //no result
            }
        }

    if (!isset($_POST['export_to_excel'])) {
            $app->render('layouts/footer.php');
        }
    });

    /*     * ****  УМЧС/ЦП - вфбор из БД для отчета инф по командировкам **** */

    function getInfChTrip($region, $grochs, $divizion, $date_start, $date_end,$ch=0) {


        if (isset($region) && !empty($region)) {
            if (isset($grochs) && !empty($grochs)) {
                if (isset($divizion) && !empty($divizion)) {
                    /* ------------- по ПАСЧ -------------- */
                    $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_trip where id_pasp = ?', array($divizion));
                }
                /* ----------------  по ГРОЧС ----------------- */ else {
                    //выбор id ПАСЧей этого ГРОЧС
                    $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_trip where id_grochs = ?', array($grochs));
                }
            }
            /* --------------- по области ---------------- */ else {
                //исключить подразделения РОСН
                $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_trip where region_id = ? and org_id not in (?,?,?) ', array($region, ROSN,UGZ,AVIA));
            }
        }
        //по РБ
        else {
            //исключить подразделения РОСН
            $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_trip where org_id not in (?,?,?) ', array(ROSN,UGZ,AVIA));
        }
        /* ++++++++  РЕЗУЛЬТАТ ++++++++++ */
        $param = array(); //параметры запроса
        //пробежать по всем ПАСЧ - выбрать инф по деж смене , определить дату и смены, на которую надо позже выбрать отсутствующих
        foreach ($id_pasp as $value) {

            $sql = 'select * from builder_basic_inf_trip where id_pasp=? ';
            $param = array($value['id_pasp']);

            //если выбрана начальная дата и конечная дата
            if (($date_start != 0) && ($date_end != 0)) {
                $d_start = ' AND  t_id not in (select id from trip where date2 < ? or date1 > ?) ';
                $param[] = $date_start;
                $param[] = $date_end;

                if (isset($d_start))//добавляем начальную дату
                    $sql = $sql . $d_start;
            }
               /* --- выбрана смен --- */

            if ( isset($ch) && $ch != 0 ) {
                $q_ch = ' AND ch = ? ';
                $param[] = $ch;
            }
            if (isset($q_ch)) { //добавляем ch
                $sql = $sql . $q_ch;
            }
            /* --- КОНЕЦ выбрана смен --- */

            $sql=$sql.' order by fio, date1';

            $inf1 = R::getAll($sql, $param);

            $main[$value['id_pasp']] = array();
            if (!empty($inf1)) {
                //оформить инф по деж смене в массив : [id_pasp] => array([shtat_ch]=>25,...)
                foreach ($inf1 as $row) {
                     $main[$value['id_pasp']] ['id_grochs'] = $row['id_grochs'];
                       $main[$value['id_pasp']] ['region_id'] = $row['region_id'];
                    $main[$value['id_pasp']] ['region_name'] = $row['region_name'];
                    $main[$value['id_pasp']] ['name'] = $row['organ']; //Жлобинский РОЧС
                    $main[$value['id_pasp']] ['name_div'] = $row['divizion']; //ПАСЧ-1
                    $main[$value['id_pasp']] ['fio'] = $row['fio'];
                     $main[$value['id_pasp']] ['ch'] = $row['ch'];
                    $main[$value['id_pasp']] ['id_fio'] = $row['id_fio'];
                    $main[$value['id_pasp']] ['position'] = $row['position'];
                    $main[$value['id_pasp']] ['date1'] = $row['date1'];
                    $main[$value['id_pasp']] ['date2'] = $row['date2'];
                    $main[$value['id_pasp']] ['prikaz'] = $row['prikaz'];
                    $main[$value['id_pasp']] ['place'] = $row['place'];
                    $main[$value['id_pasp']] ['is_cosmr'] = $row['is_cosmr'];
                    $main[$value['id_pasp']] ['type_trip'] = $row['type_trip'];
                    $main[$value['id_pasp']] ['note'] = $row['note'];

                    $m[] = $main;
                    unset($main);
                }
            }
        }
        if (!empty($m)) {
            return $m;
        } else {
            return array();
        }
    }

    /*     * ****  РОСН - вфбор из БД для отчета инф по командировкам **** */

    function getInfChTripAdditional($region, $grochs, $date_start, $date_end,$organ,$ch=0) {


        if (isset($region) && !empty($region)) {
            if (isset($grochs) && !empty($grochs)) {

                /* ----------------  по ОУ----------------- */
                //выбор id ПАСЧей этого ГРОЧС
                $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_trip where id_grochs = ?', array($grochs));
            }
            /* --------------- по всему РОСН---------------- */ else {
                $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_trip where  org_id = ? ', array($organ));
            }
        }
        //по РБ
        else {
            $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_trip where org_id = ? ', array($organ));
        }
        /* ++++++++  РЕЗУЛЬТАТ ++++++++++ */
        $param = array(); //параметры запроса
        //пробежать по всем ПАСЧ - выбрать инф по деж смене , определить дату и смены, на которую надо позже выбрать отсутствующих
        foreach ($id_pasp as $value) {

            $sql = 'select * from builder_basic_inf_trip where id_pasp=? ';
            $param = array($value['id_pasp']);

            //если выбрана начальная дата и конечная дата
            if (($date_start != 0) && ($date_end != 0)) {
                $d_start = ' AND t_id not in (select id from trip where date2 < ? or date1 > ?)';
                $param[] = $date_start;
                $param[] = $date_end;

                if (isset($d_start))//добавляем начальную дату
                    $sql = $sql . $d_start;
            }
               /* --- выбрана смен --- */

            if ( isset($ch) && $ch != 0 ) {
                $q_ch = ' AND ch = ? ';
                $param[] = $ch;
            }

            if (isset($q_ch)) { //добавляем ch
                $sql = $sql . $q_ch;
            }
            /* --- КОНЕЦ выбрана смен --- */

                        $sql=$sql.' order by fio, date1';

            $inf1 = R::getAll($sql, $param);

            $main[$value['id_pasp']] = array();
            if (!empty($inf1)) {
                //оформить инф по деж смене в массив : [id_pasp] => array([shtat_ch]=>25,...)
                foreach ($inf1 as $row) {
                    $main[$value['id_pasp']] ['region_name'] = $row['region_name'];
                    $main[$value['id_pasp']] ['name'] = $row['organ']; //Жлобинский РОЧС
                    $main[$value['id_pasp']] ['name_div'] = $row['divizion']; //ПАСЧ-1
                    $main[$value['id_pasp']] ['fio'] = $row['fio'];
                     $main[$value['id_pasp']] ['ch'] = $row['ch'];
                    $main[$value['id_pasp']] ['id_fio'] = $row['id_fio'];
                    $main[$value['id_pasp']] ['position'] = $row['position'];
                    $main[$value['id_pasp']] ['date1'] = $row['date1'];
                    $main[$value['id_pasp']] ['date2'] = $row['date2'];
                    $main[$value['id_pasp']] ['prikaz'] = $row['prikaz'];
                    $main[$value['id_pasp']] ['place'] = $row['place'];
                    $main[$value['id_pasp']] ['type_trip'] = $row['type_trip'];
                    $main[$value['id_pasp']] ['is_cosmr'] = $row['is_cosmr'];
                    $main[$value['id_pasp']] ['note'] = $row['note'];

                    $m[] = $main;
                    unset($main);
                }
            }
        }
        if (!empty($m)) {
            return $m;
        } else {
            return array();
        }
    }

                /*---------- export to Excel inf trip ------------*/
    function exportToExcelInfTrip($main, $date_strat, $date_end,$type, $data) {
        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/builder_basic/inf_trip.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 8;
        $i = 0;

        /* +++++++++++++++++++++ style ++++++++++ */
        /* Итого по ГРОЧС */
        $style_all_grochs = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => '99CCCC'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* Итого по области */
        $style_all_region = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => '00CECE'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* ИТОГО */
        $style_all = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'DFE53E'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* +++++++++++++ end style +++++++++++++ */

        /* ---всего по ГРОЧС  --- */
        $all_g = 0;
        /* ---всего по области --- */
        $all_r = 0;

        $last_id_grochs = 0;
        $last_id_region = 0;
        $k = 0; //кол-во командировок

           $ch=(isset($data['ch']))? (', смена '.$data['ch']) :'';//номер выбранной смены

        if ($date_strat != 0 && $date_end != 0) {
            $sheet->setCellValue('A2', 'c ' . $date_strat . ' по ' . $date_end.$ch);
        }
        else{
            $sheet->setCellValue('A2', $ch);
        }
        foreach ($main as $key => $value) {
            foreach ($value as $key2 => $row) {
                if (!empty($value[$key2])) {

                   if ($type == 1) {//кроме РОСН/UGZ
                        /* ++++++++++ итого по ГРОЧС ++++++++++++++ */
                        if ($last_id_grochs != $row['id_grochs'] && $last_id_grochs != 0) {
                            $sheet->setCellValue('A' . $r, '');
                            $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                            $sheet->setCellValue('C' . $r, $all_g);
                            $sheet->setCellValue('D' . $r, '');
                            $sheet->setCellValue('E' . $r, '');
                            $sheet->setCellValue('F' . $r, '');
                            $sheet->setCellValue('G' . $r, '');
                            $sheet->setCellValue('H' . $r, '');
                            $sheet->setCellValue('I' . $r, '');
                            $sheet->setCellValue('J' . $r, '');
                            $sheet->setCellValue('K' . $r, '');
                            $sheet->setCellValue('L' . $r, '');
                            $sheet->setCellValue('M' . $r, '');
                            $sheet->getStyleByColumnAndRow(0, $r, 12, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС
                            $r++;

                            $all_g = 0; //обнулсть
                        }

                        /* ++++ Итого по области ++++ */
                        if ($last_id_region != $row['region_id'] && $last_id_region != 0) {
                            $sheet->setCellValue('A' . $r, '');
                            $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                            $sheet->setCellValue('C' . $r, $all_r);
                            $sheet->setCellValue('D' . $r, '');
                            $sheet->setCellValue('E' . $r, '');
                            $sheet->setCellValue('F' . $r, '');
                            $sheet->setCellValue('G' . $r, '');
                            $sheet->setCellValue('H' . $r, '');
                            $sheet->setCellValue('I' . $r, '');
                            $sheet->setCellValue('J' . $r, '');
                            $sheet->setCellValue('K' . $r, '');
                            $sheet->setCellValue('L' . $r, '');
                            $sheet->setCellValue('M' . $r, '');
                            $sheet->getStyleByColumnAndRow(0, $r, 12, $r)->applyFromArray($style_all_region); //Итого по области
                            $r++;

                            $all_r = 0; //обнулсть
                        }
                                            $all_g+=1;
                    $all_r+=1;

                    $last_id_grochs = $row['id_grochs'];
                    $last_id_region = $row['region_id'];
                    }

                    $i++;
                    $sheet->setCellValue('A' . $r, $i); //№ п/п
                    $sheet->setCellValue('B' . $r, $row['region_name']);
                    $sheet->setCellValue('C' . $r, $row['name']);
                    $sheet->setCellValue('D' . $r, $row['name_div']);
                    $sheet->setCellValue('E' . $r, $row['fio']);
                    $sheet->setCellValue('F' . $r, $row['position']);
                    $sheet->setCellValue('G' . $r, $row['date1']);
                    $sheet->setCellValue('H' . $r, $row['date2']);
                    $sheet->setCellValue('I' . $r, $row['type_trip']);
                    $sheet->setCellValue('J' . $r, $row['place']);
                    $sheet->setCellValue('K' . $r, $row['prikaz']);
                    $sheet->setCellValue('L' . $r, ($row['is_cosmr'] == 1) ? 'да' : 'нет');
                    $sheet->setCellValue('M' . $r, $row['note']);
                    $r++;

                    $k++; //itogo

                }
            }
        }

        if ($type == 1) {//кроме РОСН/UGZ
            /* ++++ Итого по ГРОЧС ++++ */
            if ($last_id_grochs && $last_id_grochs != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                $sheet->setCellValue('C' . $r, $all_g);
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, '');
                $sheet->setCellValue('F' . $r, '');
                $sheet->setCellValue('G' . $r, '');
                $sheet->setCellValue('H' . $r, '');
                $sheet->setCellValue('I' . $r, '');
                $sheet->setCellValue('J' . $r, '');
                $sheet->setCellValue('K' . $r, '');
                $sheet->setCellValue('L' . $r, '');
                $sheet->setCellValue('M' . $r, '');
                $sheet->getStyleByColumnAndRow(0, $r, 12, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС
                $r++;

                $all_g = 0; //обнулсть
            }

            /* ++++ Итого по области ++++ */
            if ($last_id_region && $last_id_region != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                $sheet->setCellValue('C' . $r, $all_r);
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, '');
                $sheet->setCellValue('F' . $r, '');
                $sheet->setCellValue('G' . $r, '');
                $sheet->setCellValue('H' . $r, '');
                $sheet->setCellValue('I' . $r, '');
                $sheet->setCellValue('J' . $r, '');
                $sheet->setCellValue('K' . $r, '');
                $sheet->setCellValue('L' . $r, '');
                $sheet->setCellValue('M' . $r, '');
                $sheet->getStyleByColumnAndRow(0, $r, 12, $r)->applyFromArray($style_all_region); //Итого по области
                $r++;

                $all_r = 0; //обнулсть
            }
        }
        if ($k != 0) {
            $sheet->setCellValue('A' . $r, '');
            $sheet->setCellValue('B' . $r, 'ИТОГО:');
            $sheet->setCellValue('C' . $r, $k);
            $sheet->setCellValue('D' . $r, '');
            $sheet->setCellValue('E' . $r, '');
            $sheet->setCellValue('F' . $r, '');
            $sheet->setCellValue('G' . $r, '');
            $sheet->setCellValue('H' . $r, '');
            $sheet->setCellValue('I' . $r, '');
            $sheet->setCellValue('J' . $r, '');
            $sheet->setCellValue('K' . $r, '');
            $sheet->setCellValue('L' . $r, '');
            $sheet->setCellValue('M' . $r, '');
            $sheet->getStyleByColumnAndRow(0, $r, 12, $r)->applyFromArray($style_all); //Итого
            $r++;
        }

        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="inf_trip.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    /* +++++++++++++++++++ инф по др причинам ++++++++++++++++++++ */
    $app->post('/basic/inf_other/:type', function ($type) use ($app) {//umchs/cp информация по сменам - форма

                if (!isset($_POST['export_to_excel'])) {
        array($app, 'is_auth');

        $data['title_name']='Запросы/Др.причины';
        $app->render('layouts/header.php',$data);
        $app->render('layouts/menu.php');
        $data['bread'] = getBread();
        $app->render('bread/bread.php', $data);


        /* $type = 1; //type of query-umchs/cp
          $type = 2; //type of query-rosn
         *         */
        $data['type'] = $type;
         ///какая вкладка активна
        $data['active'] = 'other';

        $app->render('query/pzmenu.php', $data);
        /* --------------- форма поиска ---------------- */
        if ($type == 1) {
            $data = basic_query();
            $app->render('query/form/form_inf_other.php', $data);
        } elseif($type==2) {//ROSN
            $data = additional_query();
            $app->render('query/form_rosn/form_inf_other.php', $data);
        } elseif ($type == 3) { //UGZ
                $data = UGZ_query();
                $app->render('query/form_ugz/form_inf_other.php', $data);
            } elseif ($type == 4) { //Avia
                $data = AVIA_query();
                $app->render('query/form_avia/form_inf_other.php', $data);
            }
                }

        /* POST  data - общие для РОСН и УМЧС/ЦП */
        $d_s = $app->request()->post('date_start');
        $date_start = (isset($d_s) && !empty($d_s)) ? date("Y-m-d", strtotime($d_s)) : 0;
        $d_e = $app->request()->post('date_end');
        $date_end = (isset($d_e) && !empty($d_e)) ? date("Y-m-d", strtotime($d_e)) : 0;
         $ch = (isset($_POST['ch']) && !empty($_POST['ch'])) ? $_POST['ch'] : 0;

             if($date_start != 0  && $date_end != 0){
            $data['date_start']=$date_start;
            $data['date_end']=$date_end;
        }

                if($ch != 0){
            $data['ch']=$ch;
        }

        /* ---------  результат поиска УМЧС/Aviaciz --------- */
        if ($type == 1) {

            $region = $app->request()->post('region'); //область
            $grochs = $app->request()->post('locorg'); //грочс
            $divizion = $app->request()->post('diviz'); //подразделение

            /*             * ******  формированеи результата ****** */

            $main = getInfChOther($region, $grochs, $divizion, $date_start, $date_end,$ch);
             $main=  deleteDublicateAbsent($main);
$data['main'] =$main;

            /*             * ******  отображение результата ******* */
            if (!empty($data['main'])) {
                   /* -------------------------- export to excel ---------------------------- */
                if (isset($_POST['export_to_excel'])) {
                    exportToExcelInfOther($main, $date_start, $date_end,$type,$data);
                }
                /* ------------------ отображение на экран ----------------------------- */
 else {
         $app->render('query/result/inf_other.php', $data); //result
                $app->render('query/pzend.php');
 }

            } else {
                $app->render('msg/emtyResult.php', $data); //no result
            }
        }
        /* ----------------  результат поиска РОСН ----------------- */ else {

            $region = $app->request()->post('region'); //РОСН
            $grochs = $app->request()->post('locorg'); //ОУ

            /*             * ******  формированеи результата ****** */
                                  if ($type == 2)
                $organ = ROSN;
            elseif ($type == 3)
                $organ = UGZ;
            elseif ($type == 4)
                $organ = AVIA;
            $main = getInfChOtherAdditional($region, $grochs, $date_start, $date_end,$organ,$ch);
             $main=  deleteDublicateAbsent($main);

$data['main'] =$main;
            /*             * ******  отображение результата ******* */
            if (!empty($data['main'])) {
                  /* -------------------------- export to excel ---------------------------- */
                if (isset($_POST['export_to_excel'])) {
                    exportToExcelInfOther($main, $date_start, $date_end,$type,$data);
                }
                /* ------------------ отображение на экран ----------------------------- */
                else{
                      $app->render('query/result/inf_other.php', $data); //result
                $app->render('query/pzend.php');
                }
            } else {
                $app->render('msg/emtyResult.php', $data); //no result
            }
        }

     if (!isset($_POST['export_to_excel'])) {
            $app->render('layouts/footer.php');
        }
    });

    /*     * ****  УМЧС/ЦП - вфбор из БД для отчета инф по др причинам **** */

    function getInfChOther($region, $grochs, $divizion, $date_start, $date_end,$ch=0) {
        if (isset($region) && !empty($region)) {
            if (isset($grochs) && !empty($grochs)) {
                if (isset($divizion) && !empty($divizion)) {
                    /* ------------- по ПАСЧ -------------- */
                    $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_other where id_pasp = ?', array($divizion));
                }
                /* ----------------  по ГРОЧС ----------------- */ else {
                    //выбор id ПАСЧей этого ГРОЧС
                    $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_other where id_grochs = ?', array($grochs));
                }
            }
            /* --------------- по области ---------------- */ else {
                //исключить подразделения РОСН, УГЗ, Авиация
                $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_other where region_id = ? and org_id not in (?,?,?) ', array($region, ROSN,UGZ,AVIA));
            }
        }
        //по РБ
        else {
            //исключить подразделения ROSN,UGZ,AVIA
            $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_other where org_id not in (?,?,?) ', array(ROSN,UGZ,AVIA));
        }
        /* ++++++++  РЕЗУЛЬТАТ ++++++++++ */
        $param = array(); //параметры запроса
        //пробежать по всем ПАСЧ - выбрать инф по деж смене , определить дату и смены, на которую надо позже выбрать отсутствующих
        foreach ($id_pasp as $value) {

            $sql = 'select * from builder_basic_inf_other where id_pasp = ? ';
            $param = array($value['id_pasp']);

            //если выбрана начальная дата и конечная дата
            if (($date_start != 0) && ($date_end != 0)) {
                $d_start = ' AND  o_id not in (select id from other where date2 < ? or date1 > ?) ';
                $param[] = $date_start;
                $param[] = $date_end;

                if (isset($d_start))//добавляем начальную дату
                    $sql = $sql . $d_start;
            }

                           /* --- выбрана смен --- */

            if ( isset($ch) && $ch != 0 ) {
                $q_ch = ' AND ch = ? ';
                $param[] = $ch;
            }

            if (isset($q_ch)) { //добавляем ch
                $sql = $sql . $q_ch;
            }
            /* --- КОНЕЦ выбрана смен --- */

              $sql=$sql.' order by fio, date1';

            $inf1 = R::getAll($sql, $param);

            // print_r($inf1);

            $main[$value['id_pasp']] = array();
            if (!empty($inf1)) {
                //оформить инф по деж смене в массив : [id_pasp] => array([shtat_ch]=>25,...)
                foreach ($inf1 as $row) {
                          $main[$value['id_pasp']] ['id_grochs'] = $row['id_grochs'];
                       $main[$value['id_pasp']] ['region_id'] = $row['region_id'];
                    $main[$value['id_pasp']] ['region_name'] = $row['region_name'];
                    $main[$value['id_pasp']] ['name'] = $row['organ']; //Жлобинский РОЧС
                    $main[$value['id_pasp']] ['name_div'] = $row['divizion']; //ПАСЧ-1
                    $main[$value['id_pasp']] ['fio'] = $row['fio'];
                    $main[$value['id_pasp']] ['ch'] = $row['ch'];
                    $main[$value['id_pasp']] ['id_fio'] = $row['id_fio'];
                    $main[$value['id_pasp']] ['position'] = $row['position'];
                    $main[$value['id_pasp']] ['date1'] = $row['date1'];
                    $main[$value['id_pasp']] ['date2'] = $row['date2'];
                    $main[$value['id_pasp']] ['reason'] = $row['reason'];

                    $m[] = $main;
                    unset($main);
                }
            }
        }
        if (!empty($m)) {
            return $m;
        } else {
            return array();
        }
    }

    /*     * ****  РОСН - вфбор из БД для отчета инф по др причинам**** */

    function getInfChOtherAdditional($region, $grochs, $date_start, $date_end,$organ,$ch=0) {

        if (isset($region) && !empty($region)) {
            if (isset($grochs) && !empty($grochs)) {

                /* ----------------  по ОУ----------------- */
                //выбор id ПАСЧей этого ГРОЧС
                $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_other where id_grochs = ?', array($grochs));
            }
            /* --------------- по всему РОСН---------------- */ else {
                $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_other where  org_id = ? ', array($organ));
            }
        }
        //по РБ
        else {
            $id_pasp = R::getAll('select distinct id_pasp from builder_basic_inf_other where org_id = ? ', array($organ));
        }
        /* ++++++++  РЕЗУЛЬТАТ ++++++++++ */
        $param = array(); //параметры запроса
        //пробежать по всем ПАСЧ - выбрать инф по деж смене , определить дату и смены, на которую надо позже выбрать отсутствующих
        foreach ($id_pasp as $value) {

            $sql = 'select * from builder_basic_inf_other where id_pasp=? ';
            $param = array($value['id_pasp']);

            //если выбрана начальная дата и конечная дата
            if (($date_start != 0) && ($date_end != 0)) {
                $d_start = ' AND o_id not in (select id from other where date2 < ? or date1 > ?)';
                $param[] = $date_start;
                $param[] = $date_end;

                if (isset($d_start))//добавляем начальную дату
                    $sql = $sql . $d_start;
            }
                           /* --- выбрана смен --- */

            if ( isset($ch) && $ch != 0 ) {
                $q_ch = ' AND ch = ? ';
                $param[] = $ch;
            }

            if (isset($q_ch)) { //добавляем ch
                $sql = $sql . $q_ch;
            }
            /* --- КОНЕЦ выбрана смен --- */

              $sql=$sql.' order by fio, date1';

            $inf1 = R::getAll($sql, $param);

            $main[$value['id_pasp']] = array();
            if (!empty($inf1)) {
                //оформить инф по деж смене в массив : [id_pasp] => array([shtat_ch]=>25,...)
                foreach ($inf1 as $row) {
                    $main[$value['id_pasp']] ['region_name'] = $row['region_name'];
                    $main[$value['id_pasp']] ['name'] = $row['organ']; //Жлобинский РОЧС
                    $main[$value['id_pasp']] ['name_div'] = $row['divizion']; //ПАСЧ-1
                    $main[$value['id_pasp']] ['fio'] = $row['fio'];
                    $main[$value['id_pasp']] ['ch'] = $row['ch'];
                    $main[$value['id_pasp']] ['id_fio'] = $row['id_fio'];
                    $main[$value['id_pasp']] ['position'] = $row['position'];
                    $main[$value['id_pasp']] ['date1'] = $row['date1'];
                    $main[$value['id_pasp']] ['date2'] = $row['date2'];
                    $main[$value['id_pasp']] ['reason'] = $row['reason'];

                    $m[] = $main;
                    unset($main);
                }
            }
        }
        if (!empty($m)) {
            return $m;
        } else {
            return array();
        }
    }

                    /*---------- export to Excel inf trip ------------*/
    function exportToExcelInfOther($main,$date_strat, $date_end,$type,$data) {
        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/builder_basic/inf_other.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 8;
        $i = 0;

         /* +++++++++++++++++++++ style ++++++++++ */
        /* Итого по ГРОЧС */
        $style_all_grochs = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => '99CCCC'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* Итого по области */
        $style_all_region = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => '00CECE'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* ИТОГО */
        $style_all = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'DFE53E'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* +++++++++++++ end style +++++++++++++ */

        /* ---всего по ГРОЧС  --- */
        $all_g = 0;
        /* ---всего по области --- */
        $all_r = 0;

        $last_id_grochs = 0;
        $last_id_region = 0;
        $k = 0; //кол-во др.прич

         $ch=(isset($data['ch']))? (', смена '.$data['ch']) :'';//номер выбранной смены

        if($date_strat != 0 && $date_end != 0){
            $sheet->setCellValue('A2', 'c '.$date_strat.' по '.$date_end.$ch);
        }
        else{
             $sheet->setCellValue('A2', $ch);
        }
        foreach ($main as $key => $value) {
            foreach ($value as $key2 => $row) {
                if (!empty($value[$key2])) {

                    if ($type == 1) {//кроме РОСН/UGZ
                        /* ++++++++++ итого по ГРОЧС ++++++++++++++ */
                        if ($last_id_grochs != $row['id_grochs'] && $last_id_grochs != 0) {
                            $sheet->setCellValue('A' . $r, '');
                            $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                            $sheet->setCellValue('C' . $r, $all_g);
                            $sheet->setCellValue('D' . $r, '');
                            $sheet->setCellValue('E' . $r, '');
                            $sheet->setCellValue('F' . $r, '');
                            $sheet->setCellValue('G' . $r, '');
                            $sheet->setCellValue('H' . $r, '');
                            $sheet->setCellValue('I' . $r, '');
                            $sheet->getStyleByColumnAndRow(0, $r, 8, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС
                            $r++;

                            $all_g = 0; //обнулсть
                        }

                        /* ++++ Итого по области ++++ */
                        if ($last_id_region != $row['region_id'] && $last_id_region != 0) {
                            $sheet->setCellValue('A' . $r, '');
                            $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                            $sheet->setCellValue('C' . $r, $all_r);
                            $sheet->setCellValue('D' . $r, '');
                            $sheet->setCellValue('E' . $r, '');
                            $sheet->setCellValue('F' . $r, '');
                            $sheet->setCellValue('G' . $r, '');
                            $sheet->setCellValue('H' . $r, '');
                            $sheet->setCellValue('I' . $r, '');
                            $sheet->getStyleByColumnAndRow(0, $r, 8, $r)->applyFromArray($style_all_region); //Итого по области
                            $r++;

                            $all_r = 0; //обнулсть
                        }
                                            $all_g+=1;
                    $all_r+=1;

                    $last_id_grochs = $row['id_grochs'];
                    $last_id_region = $row['region_id'];
                    }

                    $i++;
                    $sheet->setCellValue('A' . $r, $i); //№ п/п
                    $sheet->setCellValue('B' . $r, $row['region_name']);
                    $sheet->setCellValue('C' . $r, $row['name']);
                    $sheet->setCellValue('D' . $r, $row['name_div']);
                    $sheet->setCellValue('E' . $r, $row['fio']);
                    $sheet->setCellValue('F' . $r, $row['position']);
                    $sheet->setCellValue('G' . $r, $row['date1']);
                    $sheet->setCellValue('H' . $r, $row['date2']);
                    $sheet->setCellValue('I' . $r, $row['reason']);
                    $r++;

                    $k++; //itogo

                }
            }
        }
         if ($type == 1) {//кроме РОСН/UGZ
            /* ++++ Итого по ГРОЧС ++++ */
            if ($last_id_grochs && $last_id_grochs != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                $sheet->setCellValue('C' . $r, $all_g);
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, '');
                $sheet->setCellValue('F' . $r, '');
                $sheet->setCellValue('G' . $r, '');
                $sheet->setCellValue('H' . $r, '');
                $sheet->setCellValue('I' . $r, '');
                $sheet->getStyleByColumnAndRow(0, $r, 8, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС
                $r++;

                $all_g = 0; //обнулсть
            }

            /* ++++ Итого по области ++++ */
            if ($last_id_region && $last_id_region != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                $sheet->setCellValue('C' . $r, $all_r);
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, '');
                $sheet->setCellValue('F' . $r, '');
                $sheet->setCellValue('G' . $r, '');
                $sheet->setCellValue('H' . $r, '');
                $sheet->setCellValue('I' . $r, '');
                $sheet->setCellValue('J' . $r, '');
                $sheet->getStyleByColumnAndRow(0, $r, 8, $r)->applyFromArray($style_all_region); //Итого по области
                $r++;

                $all_r = 0; //обнулсть
            }
        }
        if ($k != 0) {
            $sheet->setCellValue('A' . $r, '');
            $sheet->setCellValue('B' . $r, 'ИТОГО:');
            $sheet->setCellValue('C' . $r, $k);
            $sheet->setCellValue('D' . $r, '');
            $sheet->setCellValue('E' . $r, '');
            $sheet->setCellValue('F' . $r, '');
            $sheet->setCellValue('G' . $r, '');
            $sheet->setCellValue('H' . $r, '');
            $sheet->setCellValue('I' . $r, '');
            $sheet->getStyleByColumnAndRow(0, $r, 8, $r)->applyFromArray($style_all); //Итого
            $r++;
        }
        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="inf_other.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

       /* ++++++++++++++++++++++++++++++++++++++++ инф по технике СЗ ++++++++++++++++++++++++++++++++++++++++++++++++++
      Техника, которая заступила в др ПАСЧ и (или) зафиксирована в командировке,
     * отображается и учитывается при подсчете в том ПАСЧ, куда заступила на деж-во
     *         */
    $app->post('/basic/inf_car/:type', function ($type) use ($app) {//umchs/cp информация по сменам - форма


        if (!isset($_POST['export_to_excel'])) {
             array($app, 'is_auth');

             $data['title_name']='Запросы/Инф.по СЗ';
        $app->render('layouts/header.php',$data);
            $app->render('layouts/menu.php');
            $data['bread'] = getBread();
            $app->render('bread/bread.php', $data);


            /* $type = 1; //type of query-umchs/cp
              $type = 2; //type of query-rosn
             *         */
            $data['type'] = $type;
            ///какая вкладка активна
            $data['active'] = 'car';

            $app->render('query/pzmenu.php', $data);
            /* --------------- форма поиска ---------------- */
            if ($type == 1) {
                $data = basic_query();
                $app->render('query/form/form_inf_car.php', $data);
            } elseif($type==2) {//ROSN
                $data = additional_query();
                $app->render('query/form_rosn/form_inf_car.php', $data);
            } elseif ($type == 3) { //UGZ
                $data = UGZ_query();
                $app->render('query/form_ugz/form_inf_car.php', $data);
            } elseif ($type == 4) { //Avia
                $data = AVIA_query();
                $app->render('query/form_avia/form_inf_car.php', $data);
            }
        }


           /*         * *********дата, на которую надо выбирать данные ******** */
        $d = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
        $date_d = new DateTime($d);
        $date_start = $date_d->Format('Y-m-d');

        $today = date("Y-m-d");
        $yesterday = date("Y-m-d", time() - (60 * 60 * 24));
        $day_before_yesterday = date("Y-m-d", time() - (60 * 60 * 24) - (60 * 60 * 24));

        //если дата выходит за пределы трех дней, то формируем запрос за последнюю заполненную смену
        if ($date_start != $today && $date_start != $yesterday && $date_start != $day_before_yesterday) {
            $date_start = 0;
        }

        /*         * ********* END дата, на которую надо выбирать данные ******** */

        /* ---------  результат поиска УМЧС/ЦП --------- */
        if ($type == 1) {
            /*             * ******  формированеи результата ****** */
            $region = $app->request()->post('region'); //область
            $grochs = $app->request()->post('locorg'); //грочс
            $divizion = $app->request()->post('diviz'); //подразделение
            $main = getInfCar($region, $grochs, $divizion,$type, $date_start);
            $data['main'] = $main;

            if (!empty($data['main'])) {
                /* -------------------------- export to excel ---------------------------- */
                if (isset($_POST['export_to_excel'])) {
                    exportToExcelInfCar($main,$type);


                }
                /* ---------------------- отображение на экран ----------------------------- */ else {
                    $app->render('query/result/inf_car.php', $data); //result
                    $app->render('query/pzend.php');
                }
            } else {
                $app->render('msg/emtyResult.php', $data); //no result
            }
        }
        /* ----------------  результат поиска РОСН/UGZ  ----------------- */ else {
            /*             * ******  формированеи результата ****** */
            $region = $app->request()->post('region'); //РОСН
            $grochs = $app->request()->post('locorg'); //ОУ

            $main =  getInfCar($region, $grochs, 0, $type,$date_start);
  $data['main'] =$main;

            if (!empty($data['main'])) {

                //export to excel
                if (isset($_POST['export_to_excel'])) {
                   exportToExcelInfCar($main,$type);
                }
                //отображение на экран
                else {

                    $app->render('query/result/inf_car.php', $data); //result
                    $app->render('query/pzend.php');
                }
            } else {

                $app->render('msg/emtyResult.php', $data); //no result
            }
        }
          if (!isset($_POST['export_to_excel'])) {
               $app->render('layouts/footer.php');
          }

    });

      /*     * ****  УМЧС/ЦП - вфбор из БД для отчета инф по технике **** */

    function getInfCar($region, $grochs, $divizion, $type, $date_start) {

        /* ---- инф по л/с ---- */
        if ($type == 1) {
            $main = getInfChBasic($region, $grochs, $divizion,$date_start);
        } else {//ROSN
            if ($type == 2)
                $organ = ROSN;
            elseif ($type == 3)
                $organ = UGZ;
            elseif($type==4)
                $organ=AVIA;
            $main = getInfChAdditional($region, $grochs, $organ,$date_start);
        }


        /* ------- основная техника, специальная --------- */
        foreach ($main as $id_pasp => $value) {
            if(is_int($id_pasp)){
                unset($main[$id_pasp]['holiday_inf']);
            unset($main[$id_pasp]['trip_inf']);
            unset($main[$id_pasp]['ill_inf']);
            unset($main[$id_pasp]['other_inf']);
            unset($main[$id_pasp]['duty_inf']);

            $osn_reserve = 0; //осн в резерве
            $spec_reserve = 0; //спец в резерве
            $to1 = 0; //ТО1
            $to2 = 0; //ТО2
            $repair = 0; //ремонт

            $mas_osn_car = array();
            $mas_spec_car = array();

            //своя техника
            $own_car = getOwnCar($id_pasp, $value['ch'], $value['dateduty']);
            foreach ($own_car as $own) {

                if ($own['teh_cls_id'] == 1) {//основная
                    $mas_osn_car[] = $own['mark'];
                } elseif ($own['teh_cls_id'] == 2) {//специал
                    $mas_spec_car[] = $own['mark'];
                }

                /* ---- основная в резерве ---- */
                if ($own['teh_cls_id'] == 1 && $own['id_type'] == 2) {
                    $osn_reserve++;
                }
                /* ---- спец в резерве ---- */ elseif ($own['teh_cls_id'] == 2 && $own['id_type'] == 2) {
                    $spec_reserve++;
                }
                /* ---- ТО-1 ---- */ elseif ($own['id_to'] == 1) {
                    $to1++;
                }
                /* ---- ТО-2 ---- */ elseif ($own['id_to'] == 2) {
                    $to2++;
                }
                /* ---- ремонт ---- */ elseif ($own['is_repair'] == 1) {
                    $repair++;
                }
            }
            //техника из др ПАСЧ
            $car_from_other_pasp = getCarInReserve($id_pasp, $value['dateduty'], $value['ch']);
            foreach ($car_from_other_pasp as $cf) {
                if ($cf['teh_cls_id'] == 1) {//основная
                    $mas_osn_car[] = $cf['mark'];
                } elseif ($cf['teh_cls_id'] == 2) {//специал
                    $mas_spec_car[] = $own['mark'];
                }

                /* ---- основная в резерве ---- */
                if ($cf['teh_cls_id'] == 1 && $cf['id_type'] == 2) {
                    $osn_reserve++;
                }
                /* ---- спец в резерве ---- */ elseif ($cf['teh_cls_id'] == 2 && $cf['id_type'] == 2) {
                    $spec_reserve++;
                }
                /* ---- ТО-1 ---- */ elseif ($cf['id_to'] == 1) {
                    $to1++;
                }
                /* ---- ТО-2 ---- */ elseif ($cf['id_to'] == 2) {
                    $to2++;
                }
                /* ---- ремонт ---- */ elseif ($cf['is_repair'] == 1) {
                    $repair++;
                }
            }
            $list_osn_car = (!empty($mas_osn_car)) ? $mas_osn_car : '';
            $list_spec_car = (!empty($mas_spec_car)) ? $mas_spec_car : '';
            $main[$id_pasp]['osn_car'] = $list_osn_car;
            $main[$id_pasp]['spec_car'] = $list_spec_car;
            $main[$id_pasp]['osn_reserve'] = $osn_reserve;
            $main[$id_pasp]['spec_reserve'] = $spec_reserve;
            $main[$id_pasp]['to1'] = $to1;
            $main[$id_pasp]['to2'] = $to2;
            $main[$id_pasp]['repair'] = $repair;

            /* ------- склад ------- */
            $main[$id_pasp]['asv'] = 0;
            $main[$id_pasp]['powder'] = 0;
            $main[$id_pasp]['foam'] = 0;
            $storage = R::getAll('select s.asv, s.powder, s.foam  from storage as s left join'
                            . ' cardch as c on c.id=s.id_cardch where s.dateduty = ? and c.id_card = ? and c.ch = ? ', array($value['dateduty'], $id_pasp, $value['ch']));

            foreach ($storage as $st) {
                $main[$id_pasp]['asv'] = $st['asv'];
                $main[$id_pasp]['powder'] = $st['powder'];
                $main[$id_pasp]['foam'] = $st['foam'];
            }

            /* ------ начальник дс ------ */
            $main[$id_pasp]['fio_head'] = R::getCell('select concat(l.fio," (", p.name,")") from str.main as m left join str.listfio as l'
                            . ' on l.id=m.id_fio left join position as p on p.id=l.id_position where m.dateduty = ? and m.id_card = ? and m.ch = ? ', array($value['dateduty'], $id_pasp, $value['ch']));
            }

        }

        $m = $main;

        if (!empty($m)) {
            return $m;
        } else {
            return array();
        }
    }

    /*---------- export to Excel inf car ------------*/
   function exportToExcelInfCar($main,$type) {
        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/builder_basic/inf_car.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 8;
        $i = 0;

        $last_id_grochs = 0;
        $last_id_region = 0;

        /*         * * ИТОГО ** */
        $shtat = 0;
        $face = 0;
        $calc = 0;
        $duty = 0;
        $trip = 0;
        $hol = 0;
        $ill = 0;
        $other = 0;

        $osn_res = 0;
        $spec_res = 0;
        $to1 = 0;
        $to2 = 0;
        $repair = 0;
        $asv = 0;
        $powder = 0;
        $foam = 0;

        $c_osn_rb=0;
$c_spec_rb=0;

        /*         * * ИТОГО  по ГРОЧС** */
        $shtat_g = 0;
        $face_g = 0;
        $calc_g = 0;
        $duty_g = 0;
        $trip_g = 0;
        $hol_g = 0;
        $ill_g = 0;
        $other_g = 0;

        $osn_res_g = 0;
        $spec_res_g = 0;
        $to1_g = 0;
        $to2_g = 0;
        $repair_g = 0;
        $asv_g = 0;
        $powder_g = 0;
        $foam_g = 0;

        $c_osn_grochs=0;
$c_spec_grochs=0;


        /*         * * ИТОГО  по области** */
        $shtat_r = 0;
        $face_r = 0;
        $calc_r = 0;
        $duty_r = 0;
        $trip_r = 0;
        $hol_r = 0;
        $ill_r = 0;
        $other_r = 0;

        $osn_res_r = 0;
        $spec_res_r = 0;
        $to1_r = 0;
        $to2_r = 0;
        $repair_r = 0;
        $asv_r = 0;
        $powder_r = 0;
        $foam_r = 0;

        $c_osn_obl=0;
$c_spec_obl=0;

        /*+++++++++++++++++++++ style ++++++++++*/
                        /* Итого по ГРОЧС */
            $style_all_grochs = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => '99CCCC'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

                                  /* Итого по области */
            $style_all_region = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => '00CECE'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

                               /* ИТОГО */
            $style_all = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => 'DFE53E'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

            /*+++++++++++++ end style +++++++++++++*/

                unset($main['itogo']);
    unset($main['itogo_obl']);
    unset($main['itogo_rb']);

    if (isset($main) && !empty($main)) {
            foreach ($main as $key => $value) {
                $dateduty = $value['dateduty']; //дата, на которую сформирован отчет

                $date_d = new DateTime($dateduty);
                $date_result = $date_d->Format('d-m-Y');

                continue;
            }
        }

        foreach ($main as $key => $row) {

            $i++;

            if ($type == 1) {//кроме РОСН/UGZ
                if ($last_id_grochs != $row['id_grochs'] && $last_id_grochs != 0) {
                    /* ++++ Итого по ГРОЧС ++++ */
                    $sheet->setCellValue('A' . $r, '');
                    $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                    $sheet->setCellValue('C' . $r, '');
                    $sheet->setCellValue('D' . $r, $shtat_g);
                    $sheet->setCellValue('E' . $r, $face_g);
                    $sheet->setCellValue('F' . $r, $calc_g);
                    $sheet->setCellValue('G' . $r, $duty_g);
                    $sheet->setCellValue('H' . $r, $trip_g);
                    $sheet->setCellValue('I' . $r, $hol_g);
                    $sheet->setCellValue('J' . $r, $ill_g);
                    $sheet->setCellValue('K' . $r, $other_g);
                    $sheet->setCellValue('L' . $r, '');
                    $sheet->setCellValue('M' . $r, $c_osn_grochs);
                    $sheet->setCellValue('N' . $r, '');
                    $sheet->setCellValue('O' . $r, $c_spec_grochs);
                    $sheet->setCellValue('P' . $r, $osn_res_g);
                    $sheet->setCellValue('Q' . $r, $spec_res_g);
                    $sheet->setCellValue('R' . $r, $to1_g);
                    $sheet->setCellValue('S' . $r, $to2_g);
                    $sheet->setCellValue('T' . $r, $repair_g);
                    $sheet->setCellValue('U' . $r, $asv_g);
                    $sheet->setCellValue('V' . $r, $powder_g);
                    $sheet->setCellValue('W' . $r, $foam_g);
                    $sheet->setCellValue('X' . $r, '');

                    /*                     * * ИТОГО  по ГРОЧС обнулить** */
                    $shtat_g = 0;
                    $face_g = 0;
                    $calc_g = 0;
                    $duty_g = 0;
                    $trip_g = 0;
                    $hol_g = 0;
                    $ill_g = 0;
                    $other_g = 0;

                    $osn_res_g = 0;
                    $spec_res_g = 0;
                    $to1_g = 0;
                    $to2_g = 0;
                    $repair_g = 0;
                    $asv_g = 0;
                    $powder_g = 0;
                    $foam_g = 0;

                    $c_osn_grochs=0;
                    $c_spec_grochs=0;

                      $sheet->getStyleByColumnAndRow(0, $r, 23, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС

                    $r++;
                }

                 if ($last_id_region != $row['region_id'] && $last_id_region != 0) {
                      $sheet->setCellValue('A' . $r, '');
                    $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                    $sheet->setCellValue('C' . $r, '');
                    $sheet->setCellValue('D' . $r, $shtat_r);
                    $sheet->setCellValue('E' . $r, $face_r);
                    $sheet->setCellValue('F' . $r, $calc_r);
                    $sheet->setCellValue('G' . $r, $duty_r);
                    $sheet->setCellValue('H' . $r, $trip_r);
                    $sheet->setCellValue('I' . $r, $hol_r);
                    $sheet->setCellValue('J' . $r, $ill_r);
                    $sheet->setCellValue('K' . $r, $other_r);
                    $sheet->setCellValue('L' . $r, '');
                    $sheet->setCellValue('M' . $r, $c_osn_obl);
                    $sheet->setCellValue('N' . $r, '');
                    $sheet->setCellValue('O' . $r,$c_spec_obl);
                    $sheet->setCellValue('P' . $r, $osn_res_r);
                    $sheet->setCellValue('Q' . $r, $spec_res_r);
                    $sheet->setCellValue('R' . $r, $to1_r);
                    $sheet->setCellValue('S' . $r, $to2_r);
                    $sheet->setCellValue('T' . $r, $repair_r);
                    $sheet->setCellValue('U' . $r, $asv_r);
                    $sheet->setCellValue('V' . $r, $powder_r);
                    $sheet->setCellValue('W' . $r, $foam_r);
                    $sheet->setCellValue('X' . $r, '');

                    /*                     * * ИТОГО  по области обнулить** */
                    $shtat_r = 0;
                        $face_r = 0;
                        $calc_r = 0;
                        $duty_r = 0;
                        $trip_r = 0;
                        $hol_r = 0;
                        $ill_r = 0;
                        $other_r = 0;
                        $osn_res_r = 0;
                        $spec_res_r = 0;
                        $to1_r = 0;
                        $to2_r = 0;
                        $repair_r = 0;
                        $asv_r = 0;
                        $powder_r = 0;
                        $foam_r = 0;

                        $c_osn_obl=0;
                        $c_spec_obl=0;

                          $sheet->getStyleByColumnAndRow(0, $r, 23, $r)->applyFromArray($style_all_region); //Итого по области

                    $r++;
                 }
            }

            $sheet->setCellValue('A2', 'Результат на '.$date_result);

            $sheet->setCellValue('A' . $r, $i); //№ п/п
            $sheet->setCellValue('B' . $r, $row['region_name']);
            $sheet->setCellValue('C' . $r, $row['name']);
            $sheet->setCellValue('D' . $r, $row['shtat']);
            $sheet->setCellValue('E' . $r, $row['face']);
            $sheet->setCellValue('F' . $r, $row['calc']);
            $sheet->setCellValue('G' . $r, $row['duty']);
            $sheet->setCellValue('H' . $r, $row['trip']);
            $sheet->setCellValue('I' . $r, $row['holiday']);
            $sheet->setCellValue('J' . $r, $row['ill']);
            $sheet->setCellValue('K' . $r, $row['other']);

            /*             * ** основная техника ** */
            if (!empty($row['osn_car'])) {
                     $c_osn = count($row['osn_car']); //кол-во осн техн
                $osn_car = implode(',', $row['osn_car']);
            } else {
                     $c_osn = 0; //кол-во осн техн
                $osn_car = '';
            }
            $sheet->setCellValue('L' . $r, $osn_car);
            $sheet->setCellValue('M' . $r, $c_osn);

            /*             * ***  специальная техника ** */
            if (!empty($row['spec_car'])) {
                  $c_spec = count($row['spec_car']); //кол-во спец техн
                $spec_car = implode(', ', $row['spec_car']);
            } else {
                  $c_spec = 0; //кол-во спец техн
                $spec_car = '';
            }
            $sheet->setCellValue('N' . $r, $spec_car);
            $sheet->setCellValue('O' . $r, $c_spec);

            $sheet->setCellValue('P' . $r, $row['osn_reserve']);
            $sheet->setCellValue('Q' . $r, $row['spec_reserve']);
            $sheet->setCellValue('R' . $r, $row['to1']);
            $sheet->setCellValue('S' . $r, $row['to2']);
            $sheet->setCellValue('T' . $r, $row['repair']);
            $sheet->setCellValue('U' . $r, $row['asv']);
            $sheet->setCellValue('V' . $r, $row['powder']);
            $sheet->setCellValue('W' . $r, $row['foam']);
            $sheet->setCellValue('X' . $r, $row['fio_head']);

            $r++;

            /*             * ******* ИТОГО по ГРОЧС подсчет ************ */
            $shtat_g+=$row['shtat'];
            $face_g+=$row['face'];
            $calc_g+=$row['calc'];
            $duty_g+=$row['duty'];
            $trip_g+=$row['trip'];
            $hol_g+=$row['holiday'];
            $ill_g+=$row['ill'];
            $other_g+=$row['other'];
            $osn_res_g+=$row['osn_reserve'];
            $spec_res_g+=$row['spec_reserve'];
            $to1_g+=$row['to1'];
            $to2_g+=$row['to2'];
            $repair_g+=$row['repair'];
            $asv_g+=$row['asv'];
            $powder_g+=str_replace(",", ".", $row['powder']);
            $foam_g+=str_replace(",", ".", $row['foam']);

            $c_osn_grochs+=$c_osn;
            $c_spec_grochs+=$c_spec;

                      /*             * ******* ИТОГО по области подсчет ************ */
            $shtat_r+=$row['shtat'];
            $face_r+=$row['face'];
            $calc_r+=$row['calc'];
            $duty_r+=$row['duty'];
            $trip_r+=$row['trip'];
            $hol_r+=$row['holiday'];
            $ill_r+=$row['ill'];
            $other_r+=$row['other'];
            $osn_res_r+=$row['osn_reserve'];
            $spec_res_r+=$row['spec_reserve'];
            $to1_r+=$row['to1'];
            $to2_r+=$row['to2'];
            $repair_r+=$row['repair'];
            $asv_r+=$row['asv'];
            $powder_r+=str_replace(",", ".", $row['powder']);
            $foam_r+=str_replace(",", ".", $row['foam']);

            $c_osn_obl+=$c_osn;
            $c_spec_obl+=$c_spec;

              /*                     * ******* ИТОГО ************ */
                    $shtat+=$row['shtat'];
                    $face+=$row['face'];
                    $calc+=$row['calc'];
                    $duty+=$row['duty'];
                    $trip+=$row['trip'];
                    $hol+=$row['holiday'];
                    $ill+=$row['ill'];
                    $other+=$row['other'];

                    $osn_res+=$row['osn_reserve'];
                    $spec_res+=$row['spec_reserve'];
                    $to1+=$row['to1'];
                    $to2+=$row['to2'];
                    $repair+=$row['repair'];
                    $asv+=$row['asv'];
                    $powder+=str_replace(",", ".", $row['powder']);
                    $foam+=str_replace(",", ".", $row['foam']);

                    $c_osn_rb+=$c_osn;
                    $c_spec_rb+=$c_spec;

            $last_id_grochs = $row['id_grochs'];
            $last_id_region = $row['region_id'];
        }

         if ($type == 1) {//кроме РОСН/UGZ
            /* ++++ Итого по ГРОЧС ++++ */
            if ($last_id_grochs && $last_id_grochs != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                $sheet->setCellValue('C' . $r, '');
                $sheet->setCellValue('D' . $r, $shtat_g);
                $sheet->setCellValue('E' . $r, $face_g);
                $sheet->setCellValue('F' . $r, $calc_g);
                $sheet->setCellValue('G' . $r, $duty_g);
                $sheet->setCellValue('H' . $r, $trip_g);
                $sheet->setCellValue('I' . $r, $hol_g);
                $sheet->setCellValue('J' . $r, $ill_g);
                $sheet->setCellValue('K' . $r, $other_g);
                $sheet->setCellValue('L' . $r, '');
                $sheet->setCellValue('M' . $r, $c_osn_grochs);
                $sheet->setCellValue('N' . $r, '');
                $sheet->setCellValue('O' . $r, $c_spec_grochs);
                $sheet->setCellValue('P' . $r, $osn_res_g);
                $sheet->setCellValue('Q' . $r, $spec_res_g);
                $sheet->setCellValue('R' . $r, $to1_g);
                $sheet->setCellValue('S' . $r, $to2_g);
                $sheet->setCellValue('T' . $r, $repair_g);
                $sheet->setCellValue('U' . $r, $asv_g);
                $sheet->setCellValue('V' . $r, $powder_g);
                $sheet->setCellValue('W' . $r, $foam_g);
                $sheet->setCellValue('X' . $r, '');


                $sheet->getStyleByColumnAndRow(0, $r, 23, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС

                /*                 * * ИТОГО  по ГРОЧС обнулить** */
                $shtat_g = 0;
                $face_g = 0;
                $calc_g = 0;
                $duty_g = 0;
                $trip_g = 0;
                $hol_g = 0;
                $ill_g = 0;
                $other_g = 0;

                $osn_res_g = 0;
                $spec_res_g = 0;
                $to1_g = 0;
                $to2_g = 0;
                $repair_g = 0;
                $asv_g = 0;
                $powder_g = 0;
                $foam_g = 0;

                $c_osn_grochs=0;
                $c_spec_grochs=0;

                $r++;
            }

               /* ++++ Итого по области ++++ */
            if ($last_id_region && $last_id_region != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                $sheet->setCellValue('C' . $r, '');
                $sheet->setCellValue('D' . $r, $shtat_r);
                $sheet->setCellValue('E' . $r, $face_r);
                $sheet->setCellValue('F' . $r, $calc_r);
                $sheet->setCellValue('G' . $r, $duty_r);
                $sheet->setCellValue('H' . $r, $trip_r);
                $sheet->setCellValue('I' . $r, $hol_r);
                $sheet->setCellValue('J' . $r, $ill_r);
                $sheet->setCellValue('K' . $r, $other_r);
                $sheet->setCellValue('L' . $r, '');
                $sheet->setCellValue('M' . $r, $c_osn_obl);
                $sheet->setCellValue('N' . $r, '');
                $sheet->setCellValue('O' . $r, $c_spec_obl);
                $sheet->setCellValue('P' . $r, $osn_res_r);
                $sheet->setCellValue('Q' . $r, $spec_res_r);
                $sheet->setCellValue('R' . $r, $to1_r);
                $sheet->setCellValue('S' . $r, $to2_r);
                $sheet->setCellValue('T' . $r, $repair_r);
                $sheet->setCellValue('U' . $r, $asv_r);
                $sheet->setCellValue('V' . $r, $powder_r);
                $sheet->setCellValue('W' . $r, $foam_r);
                $sheet->setCellValue('X' . $r, '');

                $sheet->getStyleByColumnAndRow(0, $r, 23, $r)->applyFromArray($style_all_region); //Итого по области

                /*                 * * ИТОГО  по области обнулить** */
                               $shtat_r = 0;
                        $face_r = 0;
                        $calc_r = 0;
                        $duty_r = 0;
                        $trip_r = 0;
                        $hol_r = 0;
                        $ill_r = 0;
                        $other_r = 0;

                        $osn_res_r = 0;
                        $spec_res_r = 0;
                        $to1_r = 0;
                        $to2_r = 0;
                        $repair_r = 0;
                        $asv_r = 0;
                        $powder_r = 0;
                        $foam_r = 0;

                        $c_osn_obl=0;
                        $c_spec_obl=0;

                $r++;
            }
        }
        /************** ИТОГО ****************/
          $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО:');
                $sheet->setCellValue('C' . $r, '');
                $sheet->setCellValue('D' . $r, $shtat);
                $sheet->setCellValue('E' . $r, $face);
                $sheet->setCellValue('F' . $r, $calc);
                $sheet->setCellValue('G' . $r, $duty);
                $sheet->setCellValue('H' . $r, $trip);
                $sheet->setCellValue('I' . $r, $hol);
                $sheet->setCellValue('J' . $r, $ill);
                $sheet->setCellValue('K' . $r, $other);
                $sheet->setCellValue('L' . $r, '');
                  $sheet->setCellValue('M' . $r, $c_osn_rb);
                $sheet->setCellValue('N' . $r, '');
                  $sheet->setCellValue('O' . $r, $c_spec_rb);
                $sheet->setCellValue('P' . $r, $osn_res);
                $sheet->setCellValue('Q' . $r, $spec_res);
                $sheet->setCellValue('R' . $r, $to1);
                $sheet->setCellValue('S' . $r, $to2);
                $sheet->setCellValue('T' . $r, $repair);
                $sheet->setCellValue('U' . $r, $asv);
                $sheet->setCellValue('V' . $r, $powder);
                $sheet->setCellValue('W' . $r, $foam);
                $sheet->setCellValue('X' . $r, '');

            $sheet->getStyleByColumnAndRow(0, $r, 23, $r)->applyFromArray($style_all); //ИТОГО

        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="inf_car.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

       /* ++++++++++++++++++++++++++++++++++++++ запросник по технике +++++++++++++++++++++++++++++++++++++++++++++++++++
      Техника, которая заступила в др ПАСЧ и (или) зафиксирована в командировке,
     * отображается и учитывается при подсчете в том ПАСЧ, куда заступила на деж-во
     *         */

    $app->post('/basic/inf_car_big/:type', function ($type) use ($app) {

        if (!isset($_POST['export_to_excel'])) {
            array($app, 'is_auth');

            $data['title_name']='Запросы/Техника';
        $app->render('layouts/header.php',$data);
            $app->render('layouts/menu.php');
            $data['bread'] = getBread();
            $app->render('bread/bread.php', $data);

            /* $type = 1; //type of query-umchs/cp
              $type = 2; //type of query-rosn
             *         */
            $data['type'] = $type;
            ///какая вкладка активна
            $data['active'] = 'car_big';

            //классификаторы
            $data['name_teh'] = R::getAll('select * from ss.views'); //наименование техники
            $data['vid_teh'] = R::getAll('select * from ss.vid'); //основная, спец, вспомог

            $app->render('query/pzmenu.php', $data);
            /* ---------------------------------------------- форма поиска --------------------------------------- */
            if ($type == 1) {
                $data = basic_query();
                $app->render('query/form/form_inf_car_big.php', $data);
            } elseif ($type == 2) {//ROSN
                $data = additional_query();
                $app->render('query/form_rosn/form_inf_car_big.php', $data);
            } elseif ($type == 3) { //UGZ
                $data = UGZ_query();
                $app->render('query/form_ugz/form_inf_car_big.php', $data);
            } elseif ($type == 4) { //Avia
                $data = AVIA_query();
                $app->render('query/form_avia/form_inf_car_big.php', $data);
            }
        }
          /* ---------------------------------- КОНЕЦ  форма поиска ------------------------------------- */

        /* ------------ запрошенные параметры: наим техники, вид техники, состояние ---------- */
        $query_name_teh='';
        $query_vid_teh='';
        $query_name_state_teh='';
        $technic_name = $app->request()->post('technic_name'); //наим техники
        $vid_teh = $app->request()->post('vid_teh'); //vid техники
         $state_teh = $app->request()->post('state_teh'); //state техники

            /*         * *********дата, на которую надо выбирать данные ******** */
        $d = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
        $date_d = new DateTime($d);
        $date_start = $date_d->Format('Y-m-d');

        $today = date("Y-m-d");
        $yesterday = date("Y-m-d", time() - (60 * 60 * 24));
        $day_before_yesterday = date("Y-m-d", time() - (60 * 60 * 24) - (60 * 60 * 24));

        //если дата выходит за пределы трех дней, то формируем запрос за последнюю заполненную смену
        if ($date_start != $today && $date_start != $yesterday && $date_start != $day_before_yesterday) {
            $date_start = 0;
        }

        $data['date_start']=$date_start;

        /*         * ********* END дата, на которую надо выбирать данные ******** */

        if (!empty($technic_name) && !empty($vid_teh)) {
            $vid_teh='';
        }
//        unset($query_name_teh);
//        unset($query_vid_teh);
        if (!empty($technic_name))
            $query_name_teh = R::getCell('select name from ss.views where id = ?', array($technic_name));
        if (!empty($vid_teh))
            $query_vid_teh = R::getCell('select name from str.carcls where id = ?', array($vid_teh));

         if (!empty($state_teh)) {
            if ($state_teh == 1)
                $query_name_state_teh = 'б/р';
            elseif ($state_teh == 2)
                $query_name_state_teh = 'резерв';
            elseif ($state_teh == 3)
                $query_name_state_teh = 'ТО';
            elseif ($state_teh == 4)
                $query_name_state_teh = 'ремонт';
        }

        $data['query_name_teh'] = $query_name_teh;
        $data['query_vid_teh'] = $query_vid_teh;
         $data['query_name_state_teh'] = $query_name_state_teh;
        /* ------------ КОНЕЦ  запрошенные параметры ---------- */

        /* ---------  результат поиска УМЧС --------- */
        if ($type == 1) {

            $region = $app->request()->post('region'); //область
            $grochs = $app->request()->post('locorg'); //грочс
            $divizion = $app->request()->post('diviz'); //подразделение
            $main = get_inf_query_car($region, $grochs, $divizion, $type, $technic_name, $vid_teh,$state_teh,$date_start);
            $data['main'] = $main;

            if (!empty($data['main'])) {
                /* -------------------------- export to excel ---------------------------- */
                if (isset($_POST['export_to_excel'])) {
                    exportToExcelQueryCar($main, $type,$data);
                }
                /* ---------------------- отображение на экран ----------------------------- */ else {
                    $app->render('query/result/inf_car_big.php', $data); //result
                    $app->render('query/pzend.php');
                }
            } else {
                $app->render('msg/emtyResult.php', $data); //no result
            }
        }
        /* ----------------  результат поиска РОСН/UGZ  ----------------- */ else {

            $region = $app->request()->post('region'); //РОСН
            $grochs = $app->request()->post('locorg'); //ОУ

            $main = get_inf_query_car($region, $grochs, 0, $type, $technic_name, $vid_teh,$state_teh,$date_start);
            $data['main'] = $main;

            if (!empty($data['main'])) {
                //export to excel
                if (isset($_POST['export_to_excel'])) {
                  exportToExcelQueryCar($main, $type,$data);
                }
                //отображение на экран
                else {
                    $app->render('query/result/inf_car_big.php', $data); //result
                    $app->render('query/pzend.php');
                }
            } else {
                $app->render('msg/emtyResult.php', $data); //no result
            }
        }
        if (!isset($_POST['export_to_excel'])) {
            $app->render('layouts/footer.php');
        }
    });

    /* -- формирует инф о технике по УМЧС: б/р, резерв, то,ремонт . техника, которая уехала в ком-ку формируется отд.функцией. Здесь учитывает технику, которая приехала -- */
    function get_inf_query_car($region, $grochs, $divizion, $type, $technic_name, $vid_teh,$state_teh,$date_start) {

        /* ---------- наименование и вид техники - если выбраны на форме ------------- */
         $sql = '';
        if (!empty($technic_name)) {//АЦ, АБР....
            $sql = ' and tr.id_view = ' . $technic_name;
        } elseif (!empty($vid_teh)) {//основная, спец.....
            $sql = ' and tr.teh_cls_id= ' . $vid_teh;
        }

            if(!empty ($state_teh)){//б/р, резерв, ТО, ремонт

              if ($state_teh == 1){//боевая
                   $state_id = array(1);
                    $sql =$sql. ' and tr.id_type IN ( ' . implode(',', $state_id).')';
              }

            elseif ($state_teh == 2){//резерв
                 $state_id = array(2);
                 $sql = $sql.' and tr.id_type IN ( ' . implode(',', $state_id).')';
            }

            elseif ($state_teh == 3){//ТО
                 $state_id = array(1,2);
                 $sql = $sql.' and tr.id_to IN ( ' . implode(',', $state_id).')';
            }

            elseif ($state_teh == 4){//ремонт
                 $state_id = array(1);
                $sql = $sql.' and tr.is_repair IN ( ' . implode(',', $state_id).')';
            }

        }
        /* ---------- END наименование и вид техники - если выбраны на форме ------------- */

        /* ------------------------------------------------ УМЧС ---------------------------------------------------------- */
        if ($type == 1) {


                    /* --- ЦОУ, ШЛЧС--- */
            if (getIdDivizion($divizion) == 8 || getIdDivizion($divizion) == 9) {//ЦОУ
                $main = getInfChBasicCou($region, $grochs, $divizion, $date_start); // инф по подразд: ниам и т.д, ПАСЧ, по которым надо отобразить инф
            }
            /* --- ЦОУ, ШЛЧС--- */ else {
                $main = getInfChBasic($region, $grochs, $divizion, $date_start); // инф по подразд: ниам и т.д, ПАСЧ, по которым надо отобразить инф
                            //эти поля не нужны
            unset($main['itogo']);
            unset($main['itogo_rb']);
            unset($main['itogo_obl']);
            }
           // print_r($main);
           // exit();




            foreach ($main as $key => $value) {            //для каждого ПАСЧ формируем массив данных:  б/р, резерв, то,ремонт
                if (is_int($key)) {
                    // key - id подразделения(ПАСЧ), для которого ищем информацию
                    $id_teh = R::getAssoc("CALL query_car('{$key}','{$value['dateduty']}', '{$value['ch']}');"); //id_teh, которая сег числится в подразд

                    $result[$key] = select_data_for_query_car($id_teh, $sql, $value['dateduty'], $value['ch']); //выбор инф по б/р, резерв, ТО, ремонт

                    /* ---- из др ПАСЧ ------- */
                    $id_teh_addit = R::getAssoc("CALL additional_car_for_query_car('{$value['dateduty']}', '{$key}');"); //id_teh, которая сег пришла из др.подразд
                    $result[$key]['additional_car'] = select_data_for_query_car($id_teh_addit, $sql, $value['dateduty'], $value['ch']); //марка и количество техники
                    /* ---- END из др ПАСЧ ------- */

                    /* ---- командировка ------- */
                    $id_teh_absent = R::getAssoc("CALL absent_car_for_query_car('{$value['dateduty']}', '{$key}');"); //id_teh, которая сег числится в ком/др.подразд
                    $result[$key]['absent_car'] = select_absent_car_for_query_car($id_teh_absent, $sql, $value['dateduty'], $value['ch']); //марка и количество техники в ком-ке
                    /* ---- END командировка ------- */

                    /* --------- если строка по нулям - исключить из массива ----------- */
                    $sum = $result[$key]['br_count'] + $result[$key]['res_count'] + $result[$key]['to1_count'] + $result[$key]['to2_count'] + $result[$key]['repair_count'] +
                            $result[$key]['additional_car']['br_count'] + $result[$key]['additional_car']['res_count'] + $result[$key]['additional_car']['to1_count'] +
                            $result[$key]['additional_car']['to2_count'] + $result[$key]['additional_car']['repair_count'] +
                            $result[$key]['absent_car']['br_count'] + $result[$key]['absent_car']['res_count'] + $result[$key]['absent_car']['to1_count'] +
                            $result[$key]['absent_car']['to2_count'] + $result[$key]['absent_car']['repair_count'];
                    if ($sum == 0) {
                        unset($result[$key]);
                    }
                    //print_r($result[$key]);
                    //exit();
                    /* --------- END если строка по нулям - исключить из массива ----------- */ else {
                        $result[$key]['id_grochs'] = $value['id_grochs'];
                        $result[$key]['region_id'] = $value['region_id'];
                        $result[$key]['region_name'] = $value['region_name'];
                        $result[$key]['name'] = $value['name'];
                        $result[$key]['divizion_name'] = $value['divizion_name'];
                        $result[$key]['grochs_name'] = $value['grochs_name'];
                        $result[$key]['ch'] = $value['ch'];
                    }
                }
            }
        }
        /* ------------------------------------------------ КОНЕЦ УМЧС ---------------------------------------------------------- */

        /* ------------------------------------------------ РОСН, УГЗ, Авиация ---------------------------------------------------------- */ else {
            if ($type == 2)
                $organ = ROSN;
            elseif ($type == 3)
                $organ = UGZ;
            elseif ($type == 4)
                $organ = AVIA;
            $main = getInfChAdditional($region, $grochs, $organ, $date_start); // инф по подразд: ниам и т.д, id подразделений, инф по которым надо выводить

            foreach ($main as $key => $value) {
                if (is_int($key)) {
                    //это поле ну нужно
                    unset($main['itogo_rb']);

                    $id_teh = R::getAssoc("CALL query_car('{$key}','{$value['dateduty']}', '{$value['ch']}');"); //id_teh, которая сег числится в подразд  с учетом из др ПАСЧ

                    $result[$key] = select_data_for_query_car($id_teh, $sql, $value['dateduty'], $value['ch']); //выбор инф по б/р, резерв, ТО, ремонт


                    /* ---- из др ПАСЧ ------- */
                    $id_teh_addit = R::getAssoc("CALL additional_car_for_query_car('{$value['dateduty']}', '{$key}');"); //id_teh, которая сег пришла из др.подразд
                    $result[$key]['additional_car'] = select_data_for_query_car($id_teh_addit, $sql, $value['dateduty'], $value['ch']); //марка и количество техники
                    /* ---- END из др ПАСЧ ------- */

                    /* ---- командировка ------- */
                    $id_teh_absent = R::getAssoc("CALL absent_car_for_query_car('{$value['dateduty']}', '{$key}');"); //id_teh, которая сег числится в ком/др.подразд
                    $result[$key]['absent_car'] = select_absent_car_for_query_car($id_teh_absent, $sql, $value['dateduty'], $value['ch']); //марка и количество техники в ком-ке
                    /* ---- END командировка ------- */

                    /* --------- если строка по нулям - исключить из массива ----------- */
                    $sum = $result[$key]['br_count'] + $result[$key]['res_count'] + $result[$key]['to1_count'] + $result[$key]['to2_count'] + $result[$key]['repair_count'] +
                            $result[$key]['additional_car']['br_count'] + $result[$key]['additional_car']['res_count'] + $result[$key]['additional_car']['to1_count'] +
                            $result[$key]['additional_car']['to2_count'] + $result[$key]['additional_car']['repair_count'] +
                            $result[$key]['absent_car']['br_count'] + $result[$key]['absent_car']['res_count'] + $result[$key]['absent_car']['to1_count'] +
                            $result[$key]['absent_car']['to2_count'] + $result[$key]['absent_car']['repair_count'];
                    if ($sum == 0) {
                        unset($result[$key]);
                    }
                    //print_r($result[$key]);
                    //exit();
                    /* --------- END если строка по нулям - исключить из массива ----------- */ else {
                        $result[$key]['id_grochs'] = $value['id_grochs'];
                        $result[$key]['region_id'] = $value['region_id'];
                        $result[$key]['region_name'] = $value['region_name'];
                        $result[$key]['name'] = $value['name'];
                        $result[$key]['divizion_name'] = $value['divizion_name'];
                        $result[$key]['grochs_name'] = $value['grochs_name'];
                        $result[$key]['ch'] = $value['ch'];
                    }
                }
            }
        }
        /* ------------------------------------------------ КОНЕЦ  РОСН, УГЗ, Авиация ---------------------------------------------------------- */
        if (isset($result) && !empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    /*------------------ инф о технике для запросника по технике: б/р, резерв, то, ремонт -------------------------*/
    function  select_data_for_query_car($id_teh, $sql, $dateduty, $ch) {
                        $id_teh_mas = array();
                $br_mas = array();
                $res_mas = array();
                $to1_mas=array();
                $to2_mas=array();
                $repair_mas=array();

        if (!empty($id_teh)) {
            foreach ($id_teh as $i) {
                $id_teh_mas[] = $i;
            }
        } else {
            $id_teh_mas[] = 0;
        }

        /*         * ***** б/р ****** */
        if (!empty($sql)) {
            $br = R::getAll('select * from tehrecord as tr where tr.dateduty=? and tr.ch=? and tr.id_teh in (' . implode(',', $id_teh_mas) . ') and tr.id_type=? ' . $sql, array($dateduty, $ch, 1));
        } else {
            $br = R::getAll('select * from tehrecord as tr where tr.dateduty=? and tr.ch=? and tr.id_teh in (' . implode(',', $id_teh_mas) . ') and tr.id_type=? ', array($dateduty, $ch, 1));
        }


        if (!empty($br)) {
            foreach ($br as $b) {
                $br_mas[] = $b['mark'];
            }
            $result['br_name'] = $br_mas;
            $result['br_count'] = count($br_mas);
        } else {
            $result['br_name'] = array();
            $result['br_count'] = 0;
        }
        /*         * ***** END б/р ****** */

        /*         * ***** резерв ****** */
        if (!empty($sql)) {
            $res = R::getAll('select * from tehrecord as tr where tr.dateduty=? and tr.ch=? and tr.id_teh in (' . implode(',', $id_teh_mas) . ') and tr.id_type=? ' . $sql, array($dateduty, $ch, 2));
        } else {
            $res = R::getAll('select * from tehrecord as tr where tr.dateduty=? and tr.ch=? and tr.id_teh in (' . implode(',', $id_teh_mas) . ') and tr.id_type=?', array($dateduty, $ch, 2));
        }


        if (!empty($res)) {
            foreach ($res as $b) {
                $res_mas[] = $b['mark'];
            }
            $result['res_name'] = $res_mas;
            $result['res_count'] = count($res_mas);
        } else {
            $result['res_name'] = array();
            $result['res_count'] = 0;
        }
        /*         * ***** END резерв ****** */

        /*         * ***** ТО-1 ****** */
        if (!empty($sql)) {
            $to1 = R::getAll('select * from tehrecord as tr where tr.dateduty=? and tr.ch=? and tr.id_teh in (' . implode(',', $id_teh_mas) . ') and tr.id_to=? ' . $sql, array($dateduty, $ch, 1));
        } else {
            $to1 = R::getAll('select * from tehrecord as tr where tr.dateduty=? and tr.ch=? and tr.id_teh in (' . implode(',', $id_teh_mas) . ') and tr.id_to=?', array($dateduty, $ch, 1));
        }


        if (!empty($to1)) {
            foreach ($to1 as $b) {
                $to1_mas[] = $b['mark'];
            }
            $result['to1_name'] = $to1_mas;
            $result['to1_count'] = count($to1_mas);
        } else {
            $result['to1_name'] = array();
            $result['to1_count'] = 0;
        }
        /*         * ***** END ТО-1 ****** */

        /*         * ***** ТО-2 ****** */
        if (!empty($sql)) {
            $to2 = R::getAll('select * from tehrecord as tr where tr.dateduty=? and tr.ch=? and tr.id_teh in (' . implode(',', $id_teh_mas) . ') and tr.id_to=? ' . $sql, array($dateduty, $ch, 2));
        } else {
            $to2 = R::getAll('select * from tehrecord as tr where tr.dateduty=? and tr.ch=? and tr.id_teh in (' . implode(',', $id_teh_mas) . ') and tr.id_to=?', array($dateduty, $ch, 2));
        }


        if (!empty($to2)) {
            foreach ($to2 as $b) {
                $to2_mas[] = $b['mark'];
            }
            $result['to2_name'] = $to2_mas;
            $result['to2_count'] = count($to2_mas);
        } else {
            $result['to2_name'] = array();
            $result['to2_count'] = 0;
        }
        /*         * ***** END ТО-2 ****** */

        /*         * ***** ремонт ****** */
        if (!empty($sql)) {
            $repair = R::getAll('select * from tehrecord as tr where tr.dateduty=? and tr.ch=? and tr.id_teh in (' . implode(',', $id_teh_mas) . ') and tr.is_repair=? ' . $sql, array($dateduty, $ch, 1));
        } else {
            $repair = R::getAll('select * from tehrecord as tr where tr.dateduty=? and tr.ch=? and tr.id_teh in (' . implode(',', $id_teh_mas) . ') and tr.is_repair=?', array($dateduty, $ch, 1));
        }


        if (!empty($repair)) {
            foreach ($repair as $b) {
                $repair_mas[] = $b['mark'];
            }
            $result['repair_name'] = $repair_mas;
            $result['repair_count'] = count($repair_mas);
        } else {
            $result['repair_name'] = array();
            $result['repair_count'] = 0;
        }
        /*         * ***** END ремонт****** */
        return $result;
    }
    /* ------------- END инф о технике для запросника по технике: б/р, резерв, то, ремонт ---------------------- */

        /*------------------ инф о технике для запросника по технике: б/р, резерв, то, ремонт -------------------------*/
    function  select_absent_car_for_query_car($id_teh, $sql, $dateduty, $ch) {
                        $id_teh_mas = array();
                $absent_mas = array();

        if (!empty($id_teh)) {
            foreach ($id_teh as $i) {
                $id_teh_mas[] = $i;
            }
        } else {
            $id_teh_mas[] = 0;
        }

        /*         * ***** командировка****** */
        if (!empty($sql)) {
            $absent = R::getAll('select * from tehrecord as tr where tr.dateduty=? and tr.ch=? and tr.id_teh in (' . implode(',', $id_teh_mas) . ')  ' . $sql, array($dateduty, $ch));
        } else {
              $absent = R::getAll('select * from tehrecord as tr where tr.dateduty=? and tr.ch=? and tr.id_teh in (' . implode(',', $id_teh_mas) . ')  ', array($dateduty, $ch));
        }
        if (!empty(  $absent)) {
            foreach (   $absent as $b) {
                $absent_mas[] = $b['mark'];
            }
            $result['absent_name'] = $absent_mas;
            $result['absent_count'] = count($absent_mas);
        } else {
            $result['absent_name'] = array();
            $result['absent_count'] = 0;
        }
        /*         * ***** END командировка ****** */
        return $result;
    }
    /* ------------- END инф о технике для запросника по технике: б/р, резерв, то, ремонт ---------------------- */

    /*----------------------------- экспорт в Excel запроса по технике --------------------------------------*/
    function exportToExcelQueryCar($main, $type, $data) {
        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/builder_basic/query_car.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 10;
        $i = 0;

           $last_id_grochs = 0;
    $last_id_region = 0;
    $grochs_br = 0; //итого по ГРОЧС
    $region_br = 0; //итого по области
    $rb_br = 0; //итого по РБ
    $grochs_res = 0; //итого по ГРОЧС
    $region_res = 0; //итого по области
    $rb_res = 0; //итого по РБ
    $grochs_to1 = 0; //итого по ГРОЧС
    $region_to1 = 0; //итого по области
    $rb_to1 = 0; //итого по РБ
    $grochs_to2 = 0; //итого по ГРОЧС
    $region_to2 = 0; //итого по области
    $rb_to2 = 0; //итого по РБ
    $grochs_repair = 0; //итого по ГРОЧС
    $region_repair = 0; //итого по области
    $rb_repair = 0; //итого по РБ

    $grochs_absent = 0; //итого по ГРОЧС
    $region_absent = 0; //итого по области
    $rb_absent = 0; //итого по РБ

    $grochs_vsego = 0; //всего по ГРОЧС
    $region_vsego = 0; //всего по области
    $rb_vsego = 0; //всего по РБ

        /*+++++++++++++++++++++ style ++++++++++*/
                        /* Итого по ГРОЧС */
            $style_all_grochs = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => '99CCCC'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

                                  /* Итого по области */
            $style_all_region = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => '00CECE'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

                               /* ИТОГО */
            $style_all = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => 'DFE53E'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

            /*+++++++++++++ end style +++++++++++++*/

//                unset($main['itogo']);
//    unset($main['itogo_obl']);
//    unset($main['itogo_rb']);

//         $sheet->setCellValue('A' . 2, 'наименование техники: '.(!empty( $data['query_name_teh'])) ?  $data['query_name_teh'] : 'все'
//                 .', вид техники: '.(!empty($data['query_vid_teh'])) ? $data['query_vid_teh'] : 'все');
            $name_teh=(!empty( $data['query_name_teh'])) ?  $data['query_name_teh'] : 'все';
            $vid_teh=(!empty($data['query_vid_teh'])) ? $data['query_vid_teh'] : 'все';
         $sheet->setCellValue('A' . 2, 'наименование техники: '.$name_teh .', вид техники: '.$vid_teh);



        foreach ($main as $key => $row) {
            $i++;
             $vsego = 0; //всего техники в ПАСЧ

            if ($type == 1) {//кроме РОСН/UGZ
                if ($last_id_grochs != $row['id_grochs'] && $last_id_grochs != 0) {
                    /* ++++ Итого по ГРОЧС ++++ */
                    $sheet->setCellValue('A' . $r, '');
                    $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                    $sheet->setCellValue('C' . $r, '');
                    $sheet->setCellValue('D' . $r, '');
                    $sheet->setCellValue('E' . $r, $grochs_br);
                    $sheet->setCellValue('F' . $r, '');
                    $sheet->setCellValue('G' . $r, $grochs_res);
                    $sheet->setCellValue('H' . $r, '');
                    $sheet->setCellValue('I' . $r, $grochs_to1);
                    $sheet->setCellValue('J' . $r, '');
                    $sheet->setCellValue('K' . $r, $grochs_to2);
                    $sheet->setCellValue('L' . $r, '');
                    $sheet->setCellValue('M' . $r, $grochs_repair);
                    $sheet->setCellValue('N' . $r, '');
                    $sheet->setCellValue('O' . $r, $grochs_absent);
                    $sheet->setCellValue('P' . $r, $grochs_vsego);

                    /*                     * * ИТОГО  по ГРОЧС обнулить** */
                                       $grochs_br = 0; //обнулить
                    $grochs_res = 0; //обнулить
                    $grochs_to1 = 0; //обнулить
                    $grochs_to2 = 0; //обнулить
                    $grochs_repair = 0; //обнулить
                    $grochs_absent = 0; //обнулить
                    $grochs_vsego = 0;

                      $sheet->getStyleByColumnAndRow(0, $r, 15, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС

                    $r++;
                }

                 if ($last_id_region != $row['region_id'] && $last_id_region != 0) {
                      $sheet->setCellValue('A' . $r, '');
                    $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                    $sheet->setCellValue('C' . $r, '');
                    $sheet->setCellValue('D' . $r, '');
                    $sheet->setCellValue('E' . $r, $region_br);
                    $sheet->setCellValue('F' . $r, '');
                    $sheet->setCellValue('G' . $r, $region_res);
                    $sheet->setCellValue('H' . $r, '');
                    $sheet->setCellValue('I' . $r, $region_to1);
                    $sheet->setCellValue('J' . $r, '');
                    $sheet->setCellValue('K' . $r, $region_to2);
                    $sheet->setCellValue('L' . $r, '');
                    $sheet->setCellValue('M' . $r, $region_repair);
                    $sheet->setCellValue('N' . $r, '');
                    $sheet->setCellValue('O' . $r,$region_absent);
                    $sheet->setCellValue('P' . $r, $region_vsego);

                    /*                     * * ИТОГО  по области обнулить** */
                    $region_br = 0; //обнулить
                    $region_res = 0;
                    $region_to1 = 0;
                    $region_to2 = 0;
                    $region_repair = 0;
                    $region_absent = 0;
                    $region_vsego = 0;

                          $sheet->getStyleByColumnAndRow(0, $r, 15, $r)->applyFromArray($style_all_region); //Итого по области

                    $r++;
                 }
            }

             if (!empty($row['region_name'])) {
                $sheet->setCellValue('A' . $r, $i); //№ п/п
            $sheet->setCellValue('B' . $r, $row['region_name']);
            $sheet->setCellValue('C' . $r, $row['name'].', смена'.$row['ch']);

            /*-----------------   боевой расчет ----------------------*/
            $sheet->setCellValue('D' . $r, implode(',', $row['br_name']).chr(10) .chr(10).  implode(',', $row['additional_car']['br_name']));
            $sheet->setCellValue('E' . $r, $row['br_count'] + $row['additional_car']['br_count']);

            /*-------------------------  резерв ----------------------*/
            $sheet->setCellValue('F' . $r, implode(',', $row['res_name']).chr(10) .chr(10).  implode(',', $row['additional_car']['res_name']));
            $sheet->setCellValue('G' . $r, $row['res_count'] + $row['additional_car']['res_count']);

            /*------------------   ТО-1  --------------------------*/
            $sheet->setCellValue('H' . $r, implode(',', $row['to1_name']).chr(10) .chr(10).  implode(',', $row['additional_car']['to1_name']));
            $sheet->setCellValue('I' . $r,$row['to1_count'] + $row['additional_car']['to1_count']);

             /*------------------   ТО-2  --------------------------*/
            $sheet->setCellValue('J' . $r, implode(',', $row['to2_name']).chr(10) .chr(10).   implode(',', $row['additional_car']['to2_name']));
            $sheet->setCellValue('K' . $r, $row['to2_count'] + $row['additional_car']['to2_count']);

                         /*------------------   Ремонт  --------------------------*/
            $sheet->setCellValue('L' . $r, implode(',', $row['repair_name']).chr(10) .chr(10).   implode(',', $row['additional_car']['repair_name']));
            $sheet->setCellValue('M' . $r, $row['repair_count'] + $row['additional_car']['repair_count']);

            /*-----------------    командировка    ---------------------*/
            $sheet->setCellValue('N' . $r, implode(',', $row['absent_car']['absent_name']));
            $sheet->setCellValue('O' . $r, $row['absent_car']['absent_count']);

            /* ----------------- всего по ПАСЧ ------------------- */
                //б.р+рез+то+ремонт+команд
                $vsego = $row['br_count'] + $row['additional_car']['br_count'] + $row['res_count'] + $row['additional_car']['res_count'] + $row['to1_count'] + $row['additional_car']['to1_count'] +
                        $row['to2_count'] + $row['additional_car']['to2_count'] + $row['repair_count'] + $row['additional_car']['repair_count'] +
                        $row['absent_car']['absent_count'];
                $sheet->setCellValue('P' . $r, $vsego);
            $r++;
             }

            /*             * ******* ИТОГО по ГРОЧС подсчет ************ */
            $grochs_br+=$row['br_count'] + $row['additional_car']['br_count'];
            $grochs_res+=$row['res_count'] + $row['additional_car']['res_count'];
            $grochs_to1+=$row['to1_count'] + $row['additional_car']['to1_count'];
            $grochs_to2+=$row['to2_count'] + $row['additional_car']['to2_count'];
            $grochs_repair+=$row['repair_count'] + $row['additional_car']['repair_count'];
            $grochs_absent+=$row['absent_car']['absent_count'];

            //б.р+рез+то+ремонт+команд
            $grochs_vsego+=$row['br_count'] + $row['additional_car']['br_count'] + $row['res_count'] + $row['additional_car']['res_count'] + $row['to1_count'] + $row['additional_car']['to1_count'] +
                    $row['to2_count'] + $row['additional_car']['to2_count'] + $row['repair_count'] + $row['additional_car']['repair_count'] +
                    $row['absent_car']['absent_count'];

                      /*             * ******* ИТОГО по области подсчет ************ */
          $region_br+=$row['br_count'] + $row['additional_car']['br_count'];
            $region_res+=$row['res_count'] + $row['additional_car']['res_count'];
            $region_to1+=$row['to1_count'] + $row['additional_car']['to1_count'];
            $region_to2+=$row['to2_count'] + $row['additional_car']['to2_count'];
            $region_repair+=$row['repair_count'] + $row['additional_car']['repair_count'];
            $region_absent+=$row['absent_car']['absent_count'];
            $region_vsego+=$row['br_count'] + $row['additional_car']['br_count'] + $row['res_count'] + $row['additional_car']['res_count'] + $row['to1_count'] + $row['additional_car']['to1_count'] +
                    $row['to2_count'] + $row['additional_car']['to2_count'] + $row['repair_count'] + $row['additional_car']['repair_count'] +
                    $row['absent_car']['absent_count'];

              /*                     * ******* ИТОГО ************ */
                    $rb_br+=$row['br_count'] + $row['additional_car']['br_count'];
            $rb_res+=$row['res_count'] + $row['additional_car']['res_count'];
            $rb_to1+=$row['to1_count'] + $row['additional_car']['to1_count'];
            $rb_to2+=$row['to2_count'] + $row['additional_car']['to2_count'];
            $rb_repair+=$row['repair_count'] + $row['additional_car']['repair_count'];
            $rb_absent+=$row['absent_car']['absent_count'];
            $rb_vsego+=$row['br_count'] + $row['additional_car']['br_count'] + $row['res_count'] + $row['additional_car']['res_count'] + $row['to1_count'] + $row['additional_car']['to1_count'] +
                    $row['to2_count'] + $row['additional_car']['to2_count'] + $row['repair_count'] + $row['additional_car']['repair_count'] +
                    $row['absent_car']['absent_count'];

            $last_id_grochs = $row['id_grochs'];
            $last_id_region = $row['region_id'];
        }

         if ($type == 1) {//кроме РОСН/UGZ
            /* ++++ Итого по ГРОЧС ++++ */
            if ($last_id_grochs && $last_id_grochs != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                $sheet->setCellValue('C' . $r, '');
                    $sheet->setCellValue('D' . $r, '');
                    $sheet->setCellValue('E' . $r, $grochs_br);
                    $sheet->setCellValue('F' . $r, '');
                    $sheet->setCellValue('G' . $r, $grochs_res);
                    $sheet->setCellValue('H' . $r, '');
                    $sheet->setCellValue('I' . $r, $grochs_to1);
                    $sheet->setCellValue('J' . $r, '');
                    $sheet->setCellValue('K' . $r, $grochs_to2);
                    $sheet->setCellValue('L' . $r, '');
                    $sheet->setCellValue('M' . $r, $grochs_repair);
                    $sheet->setCellValue('N' . $r, '');
                    $sheet->setCellValue('O' . $r, $grochs_absent);
                    $sheet->setCellValue('P' . $r, $grochs_vsego);


                $sheet->getStyleByColumnAndRow(0, $r, 15, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС

                /*                 * * ИТОГО  по ГРОЧС обнулить** */
        $grochs_br = 0; //обнулить
        $grochs_res = 0; //обнулить
        $grochs_to1 = 0; //обнулить
        $grochs_to2 = 0; //обнулить
        $grochs_repair = 0; //обнулить
        $grochs_absent = 0; //обнулить
        $grochs_vsego = 0; //обнулить

                $r++;
            }

               /* ++++ Итого по области ++++ */
            if ($last_id_region && $last_id_region != 0) {
               $sheet->setCellValue('A' . $r, '');
                    $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                    $sheet->setCellValue('C' . $r, '');
                    $sheet->setCellValue('D' . $r, '');
                    $sheet->setCellValue('E' . $r, $region_br);
                    $sheet->setCellValue('F' . $r, '');
                    $sheet->setCellValue('G' . $r, $region_res);
                    $sheet->setCellValue('H' . $r, '');
                    $sheet->setCellValue('I' . $r, $region_to1);
                    $sheet->setCellValue('J' . $r, '');
                    $sheet->setCellValue('K' . $r, $region_to2);
                    $sheet->setCellValue('L' . $r, '');
                    $sheet->setCellValue('M' . $r, $region_repair);
                    $sheet->setCellValue('N' . $r, '');
                    $sheet->setCellValue('O' . $r,$region_absent);
                    $sheet->setCellValue('P' . $r, $region_vsego);

                $sheet->getStyleByColumnAndRow(0, $r, 15, $r)->applyFromArray($style_all_region); //Итого по области

                /*                 * * ИТОГО  по области обнулить** */
        $region_br = 0; //обнулить
        $region_res = 0;
        $region_to1 = 0;
        $region_to2 = 0;
        $region_repair = 0;
        $region_absent = 0;
        $region_vsego = 0;

                $r++;
            }
        }
        /************** ИТОГО ****************/
          $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО:');
                $sheet->setCellValue('C' . $r, '');
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, $rb_br);
                $sheet->setCellValue('F' . $r, '');
                $sheet->setCellValue('G' . $r, $rb_res);
                $sheet->setCellValue('H' . $r, '');
                $sheet->setCellValue('I' . $r, $rb_to1);
                $sheet->setCellValue('J' . $r, '');
                $sheet->setCellValue('K' . $r, $rb_to2);
                $sheet->setCellValue('L' . $r, '');
                  $sheet->setCellValue('M' . $r, $rb_repair);
                $sheet->setCellValue('N' . $r, '');
                  $sheet->setCellValue('O' . $r, $rb_absent);
                $sheet->setCellValue('P' . $r, $rb_vsego);

            $sheet->getStyleByColumnAndRow(0, $r, 15, $r)->applyFromArray($style_all); //ИТОГО

        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="query_car.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
      /*----------------------------- END экспорт в Excel запроса по технике --------------------------------------*/

    /*----------- Удаление лишних больничных/отпуск/ком-ки/др.причины  одного и того же работника ----------*/
    function deleteDublicateAbsent($main) {
                  $id_fio_all=array();
 foreach ($main as $key => $value) {

    foreach ($value as $key2 => $row) {
        if (!empty($value[$key2])) {
            $id_fio_all[] = $row['id_fio'];//массив из id работников(работники могут иметь несколько больничных)
        }

    }
}

$id_fio_count = array_count_values($id_fio_all); //сколько каждого работника в массиве
//print_r($id_fio_count);
$id_fio_check=array();
foreach ($id_fio_count as $key => $value) {
    if ($value <= 1) {//если 1 больничный
        unset($id_fio_count[$key]);
    } else {
        $id_fio_check[] = $key; //id работников, у которых несколько больничных-их надо проверить на повтор данных
    }
}
//print_r($id_fio_count);
//print_r($id_fio_arr);
//print_r($main);
$mas_id_main=array();
foreach ($main as $key => $value) {

    foreach ($value as $key2 => $row) {
        if (!empty($value[$key2])) {
            if (in_array($row['id_fio'], $id_fio_check)) {
                 //поместить в массив работника все его больничны [id работника][key, под которым больничный числится в msin массиве]=>массив данных по больничному
                $mas_id_main[$row['id_fio']][$key] = $main[$key];
            }
        }
    }
}
//echo '<br>';
//print_r($mas_id_main);
//echo '<br>';
foreach ($mas_id_main as $key => $value) {
    $dat1 = 0;
    $dat2 = 0;
    $key_ill = 0;
    $last_key_ill = 0;
    //value-все больничные работника
    foreach ($value as $k => $v2) {
       // print_r($v2);
        foreach ($v2 as $key2 => $value2) {

  if(isset($value2['date1'])&& isset($value2['date2'])){
               if (($dat1 == $value2['date1'] && $dat2 != $value2['date2'])|| ($dat1 != $value2['date1'] && $dat2 >= $value2['date1'])|| ($dat1 == $value2['date1'] && $dat2 == $value2['date2'])) {

			   if($dat1 == $value2['date1'] && $dat2 == $value2['date2']){
				     $dat1 = $value2['date1'];
                    $dat2 = $value2['date2'];
					$key_for_delete[] = $last_key_ill;

			   }

                elseif ($value2['date2'] >= $dat2) {

					if($dat2=$value2['date1']){

					}
					else{

                  //запомнить больничный, который пересекается с текущим , а дата2 меньше даты2 текущего
                    $key_for_delete[] = $last_key_ill;
					}

                    $dat1 = $value2['date1'];
                    $dat2 = $value2['date2'];

                }
                elseif ($value2['date2'] < $dat2) {
				/* 	if($dat1=$value2['date2'] && $dat2>$dat1){

					}
					else{ */
					   $key_for_delete[] =$k;
				//	}

            }
            } else {

                $dat1 = $value2['date1'];
                $dat2 = $value2['date2'];
            }
            }


            $last_key_ill = $k; //запомнить больничный
        }
    }
}
//echo '<br>';
//print_r($mas_id_main);
//echo '<br>';
//echo '<br>';
//print_r($key_for_delete);
if(isset($key_for_delete) && !empty($key_for_delete)){
    foreach ($key_for_delete as $value) {
    unset($main[$value]);
}
}
return $main;
    }
    /*---------- КОНЕЦ Удаление лишних больничных/отпуск/ком-ки/др.причины  одного и того же работника ----------*/



    /*-------------------- Запросы Кол-во техники ------------------------------------*/
        $app->post('/basic/inf_car_big_count/:type', function ($type) use ($app) {

        if (!isset($_POST['export_to_excel'])) {
            array($app, 'is_auth');

            $data['title_name'] = 'Запросы/Техника (количество)';
            $app->render('layouts/header.php', $data);
            $app->render('layouts/menu.php');
            $data['bread'] = getBread();
            $app->render('bread/bread.php', $data);

//            /* $type = 1; //type of query-umchs/cp
//              $type = 2; //type of query-rosn
//             *         */
            $data['type'] = $type;

            $data['active'] = 'car_big_count'; //какая вкладка активна
            //классификаторы
            $data['name_teh'] = R::getAll('select * from ss.views'); //наименование техники
            $data['vid_teh'] = R::getAll('select * from ss.vid'); //основная, спец, вспомог

            $app->render('query/pzmenu.php', $data);

            /* ---------------------------------------------- форма поиска --------------------------------------- */
            if ($type == 1) {
                $data = basic_query();
                $app->render('query/form/form_inf_car_big_count.php', $data);
            } elseif ($type == 2) {//ROSN
                $data = additional_query();
                $app->render('query/form_rosn/form_inf_car_big_count.php', $data);
            } elseif ($type == 3) { //UGZ
                $data = UGZ_query();
                $app->render('query/form_ugz/form_inf_car_big_count.php', $data);
            } elseif ($type == 4) { //Avia
                $data = AVIA_query();
                $app->render('query/form_avia/form_inf_car_big_count.php', $data);
            }
                /* ---------------------------------- КОНЕЦ  форма поиска ------------------------------------- */
        }


        /* ------------ запрошенные параметры: date, наим техники, вид техники, состояние ---------- */
        $query_name_teh = '';
        $query_vid_teh = '';
        $query_name_state_teh = '';
        $technic_name = $app->request()->post('technic_name'); //наим техники АЦ
        $vid_teh = $app->request()->post('vid_teh'); //vid техники основная
        $state_teh = $app->request()->post('state_teh'); //state техники боевая

                if (!empty($technic_name) && !empty($vid_teh)) {//приоритет у наименования техники - АЦ,....
            $vid_teh = '';
        }

        /*         * *********дата, на которую надо выбирать данные ******** */
        $d = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
        $date_d = new DateTime($d);
        $date_start = $date_d->Format('Y-m-d');

        $today = date("Y-m-d");
        $yesterday = date("Y-m-d", time() - (60 * 60 * 24));
        $day_before_yesterday = date("Y-m-d", time() - (60 * 60 * 24) - (60 * 60 * 24));

        //если дата выходит за пределы трех дней, то формируем запрос за последнюю заполненную смену
       // if ($date_start != $today && $date_start != $yesterday && $date_start != $day_before_yesterday) {
           // $date_start = 0;
        //}
        $data['date_start'] = $date_start;

        /*         * ********* END дата, на которую надо выбирать данные ******** */



        /*----------- параметры поиска - текстом выводим на экран в результат поиска ---------------*/
        if (!empty($technic_name))
            $query_name_teh = R::getCell('select name from ss.views where id = ?', array($technic_name));
        if (!empty($vid_teh))
            $query_vid_teh = R::getCell('select name from str.carcls where id = ?', array($vid_teh));

        if (!empty($state_teh)) {
            if ($state_teh == 1)
                $query_name_state_teh = 'б/р';
            elseif ($state_teh == 2)
                $query_name_state_teh = 'резерв';
            elseif ($state_teh == 3)
                $query_name_state_teh = 'ТО';
            elseif ($state_teh == 4)
                $query_name_state_teh = 'ремонт';
        }

        $data['query_name_teh'] = $query_name_teh; //АЦ
        $data['query_vid_teh'] = $query_vid_teh; //основная
        $data['query_name_state_teh'] = $query_name_state_teh; //резерв

        /*----------- КОНЕЦ параметры поиска - текстом выводим на экран в результат поиска ---------------*/


        if ($type == 1) {//UMCHS

            $region = $app->request()->post('region'); //область
            $grochs = $app->request()->post('locorg'); //грочс
            $divizion = $app->request()->post('diviz'); //подразделение
        } elseif ($type == 2) {//ROSN
             $id_organ=8;
            $region = $app->request()->post('region'); //РОСН
            $grochs = $app->request()->post('locorg'); //ОУ
        } elseif ($type == 3) {//UGZ
             $id_organ=9;
            $region = $app->request()->post('region'); //UGZ
            $grochs = $app->request()->post('locorg'); //ОУ
        } elseif ($type == 4) {//AVIA
             $id_organ=12;
            $region = $app->request()->post('region'); //oblast
            $grochs = $app->request()->post('locorg'); //Avia
            $divizion = $app->request()->post('diviz'); //часть
        }

        /* ------------ КОНЕЦ  запрошенные параметры ---------- */


       // результат поиска УМЧС без ЦОУ, ШЛЧС!!!!

        /*----------------------------- только родная техника - та, которая уехала в командировку - та, которая приехала из др.ПАСЧ ------------------------------------*/
            $sql = " SELECT   count(c.id_teh) as co, "
                    . "     `reg`.`name`        AS `region_name`,"
                    . "`reg`.`id`          AS `region_id`,  `re`.`id_loc_org` AS `id_grochs`,"
                    . " `re`.`id`         AS `id_pasp`,"
                    . " (CASE WHEN (`org`.`id` = 8) THEN `org`.`name` WHEN (`org`.`id` = 7) THEN CONCAT(`org`.`name`,' №',`locor`.`no`,' ',REPLACE(`loc`.`name`,'ий','ого'),' ',`orgg`.`name`) "
                    . "  ELSE CONCAT(`loc`.`name`,' ',`org`.`name`) END) AS `organ`,"
                    . "  `org`.`id`         AS `org_id`,"
                    . " (CASE WHEN (`org`.`id` = 8) THEN CONCAT(`org`.`name`,' - ',`loc`.`name`) WHEN (`re`.`divizion_num` = 0) THEN `d`.`name` "
                    . "ELSE CONCAT(`d`.`name`,'-',`re`.`divizion_num`) END) AS `divizion` "

                    . " FROM str.car AS c LEFT JOIN ss.technics AS t ON t.id=c.id_teh"
                    . " LEFT JOIN ss.records AS re ON re.id=t.id_record "
                    . " LEFT JOIN ss.locorg AS locor ON locor.id=re.id_loc_org"
                    . " LEFT JOIN ss.locals AS loc ON loc.id=locor.id_local"
                    . " left join ss.views as vie on vie.id=t.id_view"
                    ."  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                    ." LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                    ."   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                    ."   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "
                    . " WHERE c.dateduty = ' " . $date_start . "'"

                    . " AND  c.`id_teh` NOT IN "
                    . " (SELECT  tr.`id_teh`  FROM  str.`tripcar` AS tr WHERE (( ' " . $date_start . " ' BETWEEN tr.date1 AND tr.date2) OR( ' " . $date_start . " '  >= tr.date1 AND tr.date2 IS NULL)) )"
                    . " AND  c.`id_teh` NOT IN "
                    . "  (SELECT  res.`id_teh`  FROM  str.reservecar AS res WHERE (( ' " . $date_start . " ' BETWEEN res.date1 AND res.date2) OR( ' " . $date_start . " '  >= res.date1 AND res.date2 IS NULL)) ) "
                    . " and d.id not in (8,9)";


            //марка техники
                      $sql_mark = " SELECT   t.mark as mark, `re`.`id` AS `id_pasp` "
                    . " FROM str.car AS c LEFT JOIN ss.technics AS t ON t.id=c.id_teh"
                    . " LEFT JOIN ss.records AS re ON re.id=t.id_record "
                    . " LEFT JOIN ss.locorg AS locor ON locor.id=re.id_loc_org"
                    . " LEFT JOIN ss.locals AS loc ON loc.id=locor.id_local"
                    . " left join ss.views as vie on vie.id=t.id_view"
                    ."  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                    ." LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                    ."   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                    ."   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "
                    . " WHERE c.dateduty = ' " . $date_start . "'"

                    . " AND  c.`id_teh` NOT IN "
                    . " (SELECT  tr.`id_teh`  FROM  str.`tripcar` AS tr WHERE (( ' " . $date_start . " ' BETWEEN tr.date1 AND tr.date2) OR( ' " . $date_start . " '  >= tr.date1 AND tr.date2 IS NULL)) )"
                    . " AND  c.`id_teh` NOT IN "
                    . "  (SELECT  res.`id_teh`  FROM  str.reservecar AS res WHERE (( ' " . $date_start . " ' BETWEEN res.date1 AND res.date2) OR( ' " . $date_start . " '  >= res.date1 AND res.date2 IS NULL)) ) "
                    . " and d.id not in (8,9)";


                        if ($type == 1) {//UMCHS
            $sql = $sql . ' AND locor.`id_organ` NOT IN(8,9,12) '; //кроме РОСН, УГЗ, Авиации
             $sql_mark = $sql_mark . ' AND locor.`id_organ` NOT IN(8,9,12) '; //кроме РОСН, УГЗ, Авиации
        } else {// only РОСН, УГЗ, Авиации
            $sql = $sql . ' AND locor.`id_organ` =  ' . $id_organ;
             $sql_mark = $sql_mark . ' AND locor.`id_organ` =  ' . $id_organ;
        }


        /* ---------- наименование, состояние и вид техники - если выбраны на форме ------------- */

        if (!empty($technic_name)) {//АЦ, АБР....
            $t_n=' and t.id_view = ' . $technic_name;
            $sql = $sql.$t_n;
            $sql_mark = $sql_mark.$t_n;
        } elseif (!empty($vid_teh)) {//основная, спец.....
            $v_t= ' and vie.id_vid = ' . $vid_teh;
            $sql =$sql.$v_t;
             $sql_mark =$sql_mark.$v_t;
        }

            if(!empty ($state_teh)){//б/р, резерв, ТО, ремонт

              if ($state_teh == 1){//боевая
                   $state_id = array(1);
                   $state_br= ' and c.id_type IN ( ' . implode(',', $state_id).')';
                    $sql =$sql.$state_br;
                    $sql_mark =$sql_mark.$state_br;
              }

            elseif ($state_teh == 2){//резерв
                 $state_id = array(2);
                 $state_res=' and c.id_type IN ( ' . implode(',', $state_id).')';
                 $sql = $sql.$state_res;
                 $sql_mark = $sql_mark.$state_res;
            }

            elseif ($state_teh == 3){//ТО
                 $state_id = array(1,2);
                 $state_to=' and c.id_to IN ( ' . implode(',', $state_id).')';
                 $sql = $sql.$state_to;
                 $sql_mark = $sql_mark.$state_to;
            }

            elseif ($state_teh == 4){//ремонт
                 $state_id = array(1);
                 $state_rep=' and c.is_repair IN ( ' . implode(',', $state_id).')';
                $sql = $sql.$state_rep;
                $sql_mark = $sql_mark.$state_rep;
            }

        }

                /* ---------- END наименование и вид техники - если выбраны на форме ------------- */

        /* ------------------------ по какой области/подразделению ищем --------------------------- */
        if ($type == 1 || $type == 4) {//UMCHS, Avia
            //область/грочс/часть
            if (!empty($region)) {
                if (!empty($grochs)) {
                    if (!empty($divizion)){ //pasp
                        $sql = $sql . ' and re.id = ' . $divizion;
                      $sql_mark = $sql_mark . ' and re.id = ' . $divizion;
                    }
                    else{ //rochs
                        $sql = $sql . ' and locor.id = ' . $grochs;
                         $sql_mark = $sql_mark . ' and locor.id = ' . $grochs;
                    }
                } else{ //oblast
                    $sql = $sql . ' and reg.id = ' . $region;
                    $sql_mark = $sql_mark . ' and reg.id = ' . $region;
                }
            }
        }
        else {//ROSN, UGZ
            if (!empty($region)) {
                if (!empty($grochs)) {
                    $sql = $sql . ' and locor.id = ' . $grochs;
                     $sql_mark = $sql_mark . ' and locor.id = ' . $grochs;
                } else{ //oblast
                    $sql = $sql . ' and org.id = ' . $region;
                     $sql_mark = $sql_mark . ' and org.id = ' . $region;
                }
            }
        }

        //print_r($sql);
        //exit();

        /* ------------------------ КОНЕЦ по какой области/подразделению ищем --------------------------- */

        $sql=$sql."  group by re.id  ORDER BY `reg`.`name`,`locor`.`id`,`loc`.`name`,`re`.`divizion_num`";
        $res=R::getAll($sql);

        $data['res']=$res;

        $sql_mark = $sql_mark . "   ORDER BY `reg`.`name`,`locor`.`id`,`loc`.`name`,`re`.`divizion_num`";
        $res_mark = R::getAll($sql_mark);


        //массив из марок техники по каждой ПАСЧ
        $res_mark_array=array();
        foreach ($res_mark as $value) {
            $res_mark_array[$value['id_pasp']][]=$value['mark'];
        }
        $data['res_mark_array'] = $res_mark_array;

        /*----------------------------- КОНЕЦ только родная техника - та, которая уехала в командировку - та, которая приехала из др.ПАСЧ ------------------------------------*/

        /* ------------------------------------------------ Техника из др подразделения ------------------------------------------------------------- */
            $sql_teh_from_other_pasp = " SELECT  res.id_card as id_pasp, count(res.`id_teh` ) as co, "
                      . "     `reg`.`name`        AS `region_name`,"
                    . "`reg`.`id`          AS `region_id`,  `re`.`id_loc_org` AS `id_grochs`,"

                    . " (CASE WHEN (`org`.`id` = 8) THEN `org`.`name` WHEN (`org`.`id` = 7) THEN CONCAT(`org`.`name`,' №',`locor`.`no`,' ',REPLACE(`loc`.`name`,'ий','ого'),' ',`orgg`.`name`) "
                    . "  ELSE CONCAT(`loc`.`name`,' ',`org`.`name`) END) AS `organ`,"
                    . "  `org`.`id`         AS `org_id`,"
                    . " (CASE WHEN (`org`.`id` = 8) THEN CONCAT(`org`.`name`,' - ',`loc`.`name`) WHEN (`re`.`divizion_num` = 0) THEN `d`.`name` "
                    . "ELSE CONCAT(`d`.`name`,'-',`re`.`divizion_num`) END) AS `divizion` "

                    . "   FROM  str.reservecar AS res "
                    . " left join str.car as c ON c.id_teh=res.id_teh  and c.dateduty= ' " . $date_start . " '"
                    . " left join ss.technics as t on t.id=res.id_teh"
                    . " left join ss.views as vie on vie.id=t.id_view  "
                    . "left join ss.records as re ON re.id=res.id_card"
                    ." left join ss.locorg as locor on locor.id=re.id_loc_org "
                    . "left join ss.locals as loc on loc.id=locor.id_local"
                     ."  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                    ." LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                    ."   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                    ."   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "

                    . "  WHERE (( ' " . $date_start . " ' BETWEEN res.date1 AND res.date2) OR( ' " . $date_start . " '  >= res.date1 AND res.date2 IS NULL)) "
                    . " and re.id_divizion not in (8,9)";


            //марка техники из др.подразделения
               $sql_teh_mark_from_other_pasp = " SELECT  res.id_card as id_pasp, t.mark as mark "

                    . "   FROM  str.reservecar AS res "
                    . " left join str.car as c ON c.id_teh=res.id_teh  and c.dateduty= ' " . $date_start . " '"
                    . " left join ss.technics as t on t.id=res.id_teh"
                    . " left join ss.views as vie on vie.id=t.id_view  "
                    . "left join ss.records as re ON re.id=res.id_card"
                    ." left join ss.locorg as locor on locor.id=re.id_loc_org "
                    . "left join ss.locals as loc on loc.id=locor.id_local"
                     ."  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                    ." LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                    ."   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                    ."   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "

                    . "  WHERE (( ' " . $date_start . " ' BETWEEN res.date1 AND res.date2) OR( ' " . $date_start . " '  >= res.date1 AND res.date2 IS NULL)) "
                    . " and re.id_divizion not in (8,9)";


            if (isset($t_n)) {//АЦ, АБР....
               $sql_teh_from_other_pasp=$sql_teh_from_other_pasp . $t_n;
                $sql_teh_mark_from_other_pasp=$sql_teh_mark_from_other_pasp . $t_n;
            } elseif (isset($v_t)) {//основная, спец.....
               $sql_teh_from_other_pasp= $sql_teh_from_other_pasp . $v_t;
               $sql_teh_mark_from_other_pasp= $sql_teh_mark_from_other_pasp . $v_t;
            }


            if(isset ($state_br)){//б/р, резерв, ТО, ремонт
                  $sql_teh_from_other_pasp= $sql_teh_from_other_pasp.$state_br;
                  $sql_teh_mark_from_other_pasp= $sql_teh_mark_from_other_pasp.$state_br;
            }
            elseif (isset($state_res)){//резерв
                $sql_teh_from_other_pasp= $sql_teh_from_other_pasp.$state_res;
                $sql_teh_mark_from_other_pasp= $sql_teh_mark_from_other_pasp.$state_res;
            }

            elseif (isset ($state_to)){//ТО
               $sql_teh_from_other_pasp=  $sql_teh_from_other_pasp.$state_to;
                $sql_teh_mark_from_other_pasp=  $sql_teh_mark_from_other_pasp.$state_to;
            }

            elseif (isset ($state_rep)){//ремонт
                $sql_teh_from_other_pasp=$sql_teh_from_other_pasp.$state_rep;
                  $sql_teh_mark_from_other_pasp=$sql_teh_mark_from_other_pasp.$state_rep;
            }



            if ($type == 1 || $type == 4) {//UMCHS, Avia
            //область/грочс/часть
            if (!empty($region)) {
                if (!empty($grochs)) {
                    if (!empty($divizion)){ //pasp
                        $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' and re.id = ' . $divizion;
                     $sql_teh_mark_from_other_pasp = $sql_teh_mark_from_other_pasp . ' and re.id = ' . $divizion;

                    }
                    else{ //rochs
                        $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' and re.id_loc_org = ' . $grochs;
                          $sql_teh_mark_from_other_pasp = $sql_teh_mark_from_other_pasp . ' and re.id_loc_org = ' . $grochs;
                    }
                } else{ //oblast
                    $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' and loc.id_region = ' . $region;
                     $sql_teh_mark_from_other_pasp = $sql_teh_mark_from_other_pasp . ' and loc.id_region = ' . $region;
                }
            }
        }
        else {//ROSN, UGZ
            if (!empty($region)) {
                if (!empty($grochs)) {
                    $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' and re.id_loc_org = ' . $grochs;
                      $sql_teh_mark_from_other_pasp = $sql_teh_mark_from_other_pasp . ' and re.id_loc_org = ' . $grochs;
                } else{ //oblast
                    $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' and org.id = ' . $region;
                     $sql_teh_mark_from_other_pasp = $sql_teh_mark_from_other_pasp . ' and org.id = ' . $region;
                }
            }
        }

        $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . " group by res.id_card ";


        $teh_from_other_card = R::getAll($sql_teh_from_other_pasp);



        $teh_from_other_card_array = array(); //в массив ключ - это ПАСЧ
        foreach ($teh_from_other_card as $value) {
            $teh_from_other_card_array[$value['id_pasp']] = $value;
        }

        $data['teh_from_other_card_array'] = $teh_from_other_card_array;

//макрка техники
        $teh_mark_from_other_card = R::getAll($sql_teh_mark_from_other_pasp);

        $teh_mark_from_other_card_array = array(); //в массив ключ - это ПАСЧ
        foreach ($teh_mark_from_other_card as $value) {
            $teh_mark_from_other_card_array[$value['id_pasp']][] = $value['mark'];
        }

        $data['teh_mark_from_other_card_array'] = $teh_mark_from_other_card_array;



        /* ----------------- КОНЕЦ Техника из др подразделения ----------------------- */



            if (!empty($res) || !empty($teh_from_other_card)) {

            if (isset($_POST['export_to_excel'])) {//export to excel
                exportToExcelQueryCarBigCount($res,$teh_from_other_card_array,$type,$data);
            } else {//отображение на экран
                $app->render('query/result/inf_car_big_count.php', $data); //result
                $app->render('query/pzend.php');
            }
        } else {
            $app->render('msg/emtyResult.php', $data); //no result
        }


        if (!isset($_POST['export_to_excel'])) {
            $app->render('layouts/footer.php');
        }
    });
    /*-------------------- END кол-во техники ------------------------------------*/


        /*----------------------------- экспорт в Excel запроса по технике count --------------------------------------*/
    function exportToExcelQueryCarBigCount($res, $teh_from_other_card_array, $type, $data) {
        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/builder_basic/query_car_count.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 10;
        $i = 0;
        $last_id_grochs = 0;
        $last_id_region = 0;
        $grochs_all = 0; //итого по ГРОЧС
        $region_all = 0; //итого по области
        $rb_all = 0; //итого по РБ

        $id_native_teh = array();
        foreach ($res as $value) {//родная техника записать id техники в массив
            $id_native_teh[] = $value['id_pasp'];
        }
        foreach ($teh_from_other_card_array as $key => $value) {
            if (!in_array($key, $id_native_teh)) {//добавить в массив информацию о подразделении, где есть только чужая техника, а родной нет
                $res[] = $value;
                unset($teh_from_other_card_array[$key]);
            }
        }

        /* +++++++++++++++++++++ style ++++++++++ */
        /* Итого по ГРОЧС */
        $style_all_grochs = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => '99CCCC'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* Итого по области */
        $style_all_region = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => '00CECE'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* ИТОГО */
        $style_all = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'DFE53E'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* +++++++++++++ end style +++++++++++++ */

//                unset($main['itogo']);
//    unset($main['itogo_obl']);
//    unset($main['itogo_rb']);
//         $sheet->setCellValue('A' . 2, 'наименование техники: '.(!empty( $data['query_name_teh'])) ?  $data['query_name_teh'] : 'все'
//                 .', вид техники: '.(!empty($data['query_vid_teh'])) ? $data['query_vid_teh'] : 'все');
        $name_teh = (!empty($data['query_name_teh'])) ? $data['query_name_teh'] : 'все';
        $vid_teh = (!empty($data['query_vid_teh'])) ? $data['query_vid_teh'] : 'все';
        $state_teh = (!empty($query_name_state_teh)) ? $query_name_state_teh : 'все';

        $date = new DateTime($data['date_start']);
        $date_start = $date->Format('d-m-Y');

        $sheet->setCellValue('A' . 1, 'Результат запроса за ' . $date_start);
        $sheet->setCellValue('A' . 2, 'наименование техники: ' . $name_teh . ', вид техники: ' . $vid_teh . ', состояние техники:' . $state_teh);

        //родная техника
        foreach ($res as $value) {
            $i++;

            /* --------------------------------------  ИТОГО-------------------------------------------- */

            if ($type == 1) {//кроме РОСН/UGZ
                if ($value['id_grochs'] != $last_id_grochs && $last_id_grochs != 0) {//итого по ГРОЧС
                    /* ++++ Итого по ГРОЧС ++++ */
                    $sheet->setCellValue('A' . $r, '');
                    $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                    $sheet->setCellValue('C' . $r, '');
                    $sheet->setCellValue('D' . $r, '');
                    $sheet->setCellValue('E' . $r, $grochs_all);

                    $grochs_all = 0; //обнулить

                    $sheet->getStyleByColumnAndRow(0, $r, 4, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС

                    $r++;
                }
                if ($value['region_id'] != $last_id_region && $last_id_region != 0) {//итого по region
                    $sheet->setCellValue('A' . $r, '');
                    $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                    $sheet->setCellValue('C' . $r, '');
                    $sheet->setCellValue('D' . $r, '');
                    $sheet->setCellValue('E' . $r, $region_all);

                    $region_all = 0; //обнулить

                    $sheet->getStyleByColumnAndRow(0, $r, 4, $r)->applyFromArray($style_all_region); //Итого по области

                    $r++;
                }
            }
            /*  ---------------------------- END ИТОГО ----------------------------------------- */



                $co_from_other_pasp = (isset($teh_from_other_card_array[$value['id_pasp']]['co'])) ? $teh_from_other_card_array[$value['id_pasp']]['co'] : 0; //кол-во техники, которая пришла  из др подразд

            if (( isset($value['co']) && $value['co'] != 0 ) || $co_from_other_pasp != 0) {

                $sheet->setCellValue('A' . $r, $i); //№ п/п
                $sheet->setCellValue('B' . $r, $value['region_name']);
                $sheet->setCellValue('C' . $r, $value['organ'] . ', ' . $value['divizion']);

                $all_teh_arr = array(); //массив марок
                if (isset($data['res_mark_array'][$value['id_pasp']])) {//марка родной техники
                    foreach ($data['res_mark_array'][$value['id_pasp']] as $mark) {
                        //echo $mark . '<br>';
                        $all_teh_arr[] = $mark;
                    }
                }
                if (isset($data['teh_mark_from_other_card_array'][$value['id_pasp']])) {//марки техники из др пасч
                    foreach ($data['teh_mark_from_other_card_array'][$value['id_pasp']] as $mark) {
                        // echo '<b><i>' . $mark . '</i></b><br>';
                        $all_teh_arr[] = $mark;
                    }
                }
                //print_r($all_teh_arr);
                $all_teh_arr_string = implode(chr(10), $all_teh_arr); //через , все марки - чтобы поместить в ячецку

                $sheet->setCellValue('D' . $r, $all_teh_arr_string);

                //  echo $all_teh_arr_string;
                // exit();

                $count = $value['co'] + $co_from_other_pasp;
                $sheet->setCellValue('E' . $r, $count);

                $grochs_all+=$value['co'] + $co_from_other_pasp;
                $region_all+=$value['co'] + $co_from_other_pasp;
                $rb_all+=$value['co'] + $co_from_other_pasp;

                $r++;
            }

            $last_id_grochs = $value['id_grochs'];
            $last_id_region = $value['region_id'];
        }

        /* -------------------------------------------------  ИТОГО------------------------------------------------------------ */
        if ($type == 1) {//кроме РОСН/UGZ
            /* ++++ Итого по ГРОЧС ++++ */
            if ($last_id_grochs && $last_id_grochs != 0) {
                /* ++++ Итого по ГРОЧС ++++ */
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                $sheet->setCellValue('C' . $r, '');
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, $grochs_all);

                $grochs_all = 0; //обнулить

                $sheet->getStyleByColumnAndRow(0, $r, 4, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС

                $r++;
            }
            /* ++++ Итого по области ++++ */
            if ($last_id_region && $last_id_region != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                $sheet->setCellValue('C' . $r, '');
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, $region_all);

                $region_all = 0; //обнулить

                $sheet->getStyleByColumnAndRow(0, $r, 4, $r)->applyFromArray($style_all_region); //Итого по области

                $r++;
            }
        }

        //ИТОГО
        $sheet->setCellValue('A' . $r, '');
        $sheet->setCellValue('B' . $r, 'ИТОГО:');
        $sheet->setCellValue('C' . $r, '');
        $sheet->setCellValue('D' . $r, '');
        $sheet->setCellValue('E' . $r, $rb_all);
        $sheet->getStyleByColumnAndRow(0, $r, 4, $r)->applyFromArray($style_all); //ИТОГО

        /* -------------------------------------------- END ИТОГО----------------------------------------------- */

        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="query_car_count.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    /*----------------------------- END экспорт в Excel запроса по технике  count --------------------------------------*/

});


/* * ************************ ПОЛЬЗОВАТЕЛИ: добавить, ред, удалить ********************************* */
$app->group('/user',$is_auth, function () use ($app) {

    function set_user($sub, $user, $psw) {//сохранить пользователя
        /*
          psw=0  при редактировании
         *          */
        $app = \Slim\Slim::getInstance();
        $region = $app->request()->post('region');
        $fiouser = $app->request()->post('fiouser');
        $loginuser = $app->request()->post('loginuser');
        $can_edit = $app->request()->post('can_edit');
        $is_admin = $app->request()->post('is_admin');
        $locorg = $app->request()->post('locorg');
        $diviz = $app->request()->post('diviz');

        if (empty($region))
            $region = NULL;
        if (empty($locorg))
            $locorg = NULL;
        if (empty($diviz))
            $diviz = NULL;

        if (isset($can_edit) && !empty($can_edit))
            $can_edit = 1;
        else
            $can_edit = 0;
                if (isset($is_admin) && !empty($is_admin))
            $is_admin = 1;
        else
            $is_admin = 0;


        if ($sub == 1) {//rcu
            $level = 1;
            $region = 3; //g Minsk
        } elseif ($sub == 0) {//umchs, ЦП без РОСН
            if (isset($region) && $region != NULL) {
                if (isset($locorg) && !empty($locorg)) {

                    if (isset($diviz) && $diviz != NULL) {
                        $level = 4;
                    } else {
                        $level = 3;
                    }

                    $sub = R::getCell('select o.sub from ss.locorg as locor left join ss.organs as o on o.id=locor.id_organ  where locor.id = ? ', array($locorg));

                    if ($sub == 2) {//ЦП-УГЗ, Авиация
                        $user->note = R::getCell('select o.id from ss.locorg as locor left join ss.organs as o on o.id=locor.id_organ  where locor.id = ? ', array($locorg));

                       if($region == 3  && $user->note==9){//г.Минск УГЗ только на уровне 2. Остальные подразд.УГЗ можно создать на ур 3,4
                           // this user can see all UGZ
                           $level=2;
                           unset($locorg);
                           unset($diviz);
                       }
                    }
                } else {
// $user->levels_id = 2;
                    $level = 2;
                }
            }
        } elseif ($sub == 2) {//cp-ROSN
//
            $note = $app->request()->post('note'); //id organ
            if (isset($note) && !empty($note)) {//выбран орган
                if (isset($locorg) && !empty($locorg)) {
                    $level = 3;

//определить область ОУ
                    $region = R::getCell('select locals.id_region from ss.locorg inner join ss.locals on locorg.id_local=locals.id WHERE locorg.id=?', array($locorg));
                    /* foreach ($obl as $value) {
                      $region = $value['idRegion'];
                      } */
                } else {
                    $level = 2;
                    $region = 3; //g Minsk
                }
                $user->note = $note;
            }
        }
        $user->locorg_id = $locorg;
        $user->levels_id = $level;
        $user->name = $fiouser;
        $user->regions_id = $region;
        $user->locorg_id = $locorg;
        $user->records_id = $diviz;
        $user->login = $loginuser;
        if (!empty($psw)) {
            $user->password = $psw;
            //echo $psw;
        }
        $user->can_edit = $can_edit;
        $user->is_admin = $is_admin;
        $user->sub = $sub;
        R::store($user);
    }

    $app->get('/', function () use ($app) {// список пользователей
        array($app, 'is_auth');

        $list_user = R::getAll('SELECT * FROM listuser where uid !=? ', array(1)
        );
        $data['list_user'] = $list_user;
        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadUser.php', $data);
        $app->render('user/tabmenu.php', $data);
        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение
        $app->render('user/listUser.php', $data);
        $app->render('user/tabend.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->get('/new/:sub', function ($sub) use ($app) {//форма добавления пользователя
        array($app, 'is_auth');
        $data = basic_query();
        $data['sub'] = $sub;

        $data['type_query'] = 0; //post
        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadUser.php', $data);
        $app->render('user/tabmenu.php', $data);
        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение
        if ($sub == 1 || $sub == 0) {//rcu
// echo 'rcu';
             $data['locorg'] = R::getAll('SELECT * FROM ss.caption WHERE orgid != ? ',array(8)); //кроме РОСН
        $data['diviz'] = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения
            $app->render('user/userUmchs.php', $data);
        } elseif ($sub == 2) {//ROSN
            $data['note'] = 8;
            $organs = R::getAll('SELECT * FROM ss.organs WHERE  sub = :sub and id = :rosn', [':sub' => 2, ':rosn' => 8]); //cp organs
            $locorg = R::getAll('select o.name as orgname,o.id as organid, loc.name as locname, lo.id as locorgid, '
                            . 'loc.id_region as region from ss.organs as o inner join ss.locorg as lo on o.id=lo.id_organ '
                            . 'inner join ss.locals as loc on loc.id=lo.id_local WHERE  o.sub = :sub and o.id = :rosn', [':sub' => 2, ':rosn' => 8]);
            $data['organs'] = $organs;
            $data['locorg'] = $locorg;
            $app->render('user/userUmchs.php', $data);
        }

        $app->render('user/tabend.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/new/:sub', function ($sub) use ($app) {//add new user
        array($app, 'is_auth');
//$data = basic_query();
        $data['sub'] = $sub;

        $login = $app->request()->post('loginuser');
        $psw = $app->request()->post('pswuser');
        $is_user = R::getAll('SELECT * FROM user WHERE  (login = :login AND password = :psw) OR password = :psw ', [':login' => $login, ':psw' => $psw]); //есть польз с таким логином и паролем
        if (isset($is_user) && !empty($is_user)) {//вывод сообщения о невозможности создать пользователя с таким логином и паролем
            $_SESSION['msg'] = 3; //такой логин уже существует
            $app->redirect('/str/user/new/' . $sub);
        } else {//такого логина и пароля еще нет
            $user = R::dispense('user');
            set_user($sub, $user, $psw); //сохранить поля

            $_SESSION['msg'] = 1; //ok
            $app->redirect('/str/user/');
        }
    });

    $app->get('/:id', function ($id) use ($app) {// форма редактир пользователя
        array($app, 'is_auth');

        $data = basic_query(); //
        $user = R::load('user', $id);
        $data['user'] = $user;
        $data['id'] = $id;
        $data['sub'] = $user->sub;
        $data['note'] = $user->note;

        $data['type_query'] = 1; //put


        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadUser.php', $data);
        $app->render('user/tabmenu.php', $data);
        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение

        if (($user->sub == 0) || (($user->sub == 2) && ($user->note != 8 ) && (!empty($user->note) ))) {//umchs, УП без РОСН
              $data['locorg'] = R::getAll('SELECT * FROM ss.caption WHERE orgid != ? ',array(8)); //кроме РОСН
        $data['diviz'] = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения
            if ($user->levels_id == 2) {//oblast
                $data['select'] = 1; //по умолчанию выбрана область
                $data['select_grochs'] = 0; //доступны все ГРОЧС
                $data['select_pasp'] = 0; //доступны все части
            }
            if ($user->levels_id == 3) {//grochs
                $data['select'] = 1; //по умолчанию выбрана область
                $data['select_grochs'] = 1; //по умолчанию выбран район
                $data['select_pasp'] = 0; //доступны все части
            }
            if ($user->levels_id == 4) {//pasp
                $data['select'] = 1; //по умолчанию выбрана область
                $data['select_grochs'] = 1; //по умолчанию выбран район
                $data['select_pasp'] = 1; //доступны все части
            }
            $app->render('user/userUmchs.php', $data);
        }
        if ($user->sub == 1) {//rcu
            $app->render('user/userUmchs.php', $data);
        }
        if (($user->sub == 2) && ($user->note == 8)) {//ROSN
            $organs = R::getAll('SELECT * FROM ss.organs WHERE  sub = :sub', [':sub' => 2]); //cp organs
            $locorg = R::getAll('select o.name as orgname,o.id as organid, loc.name as locname, lo.id as locorgid, '
                            . 'loc.id_region as region from ss.organs as o inner join ss.locorg as lo on o.id=lo.id_organ '
                            . 'inner join ss.locals as loc on loc.id=lo.id_local WHERE  o.sub = :sub', [':sub' => 2]);
            $data['organs'] = $organs;
            $data['locorg'] = $locorg;

            if ($user->levels_id == 2) {//oblast
                $data['select_organ'] = 1; //по умолчанию выбран organ
                $data['select_grochs'] = 0; //не выбран ГРОЧС
            }
            if ($user->levels_id == 3) {//grochs
                $data['select_organ'] = 1; //по умолчанию выбран organ
                $data['select_grochs'] = 1; // выбран ГРОЧС
            }

            $app->render('user/userUmchs.php', $data);
        }



        $app->render('user/tabend.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->put('/:id', function ($id) use ($app) {// редактир пользователя
        $login = $app->request()->post('loginuser');

        $u = R::load('user', $id);
        $psw = $u->password;
        $is_user = R::getAll('SELECT * FROM user WHERE  (login = :login AND password = :psw)  AND id != :id', [':login' => $login, ':psw' => $psw, ':id' => $id]); //есть польз с таким логином и паролем
        if (isset($is_user) && !empty($is_user)) {//вывод сообщения о невозможности создать пользователя с таким логином и паролем
            $_SESSION['msg'] = 3; //такой логин уже существует
            $app->redirect('/str/user/' . $id);
        } else {//такого логина и пароля еще нет
            $user = R::load('user', $id);
            $n = $user->note;
            if (!empty($n) && ($n != 8)) {
                $sub = 0;
            } else {
                $sub = $user->sub;
            }

            set_user($sub, $user, 0); //сохранить поля
            $_SESSION['msg'] = 1; //ok
            $app->redirect('/str/user/');
        }
    });
    $app->get('/delete/:id', function ($id) use ($app) {// сообщение об удалении пользователя
        array($app, 'is_auth'); //авторизован ли пользователь
        $data['id'] = $id;
        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadUser.php');
        $app->render('user/tabmenu.php');
        $app->render('msg/delete.php');
        $app->render('user/formDelete.php', $data);
        $app->render('user/backUser.php');
        $app->render('user/tabend.php');
        $app->render('layouts/footer.php');
    });

    $app->delete('/:id', function ($id) use ($app) {// удаление пользователя
        array($app, 'is_auth'); //авторизован ли пользователь
        $user = R::load('user', $id);
//обнулить id_user о всех таблицах
        R::exec('UPDATE countstr SET id_user=0 WHERE id_user = ?', array($id));
        R::exec('UPDATE holiday SET id_user=0 WHERE id_user = ?', array($id));
        R::exec('UPDATE ill SET id_user=0 WHERE id_user = ?', array($id));
        R::exec('UPDATE trip SET id_user=0 WHERE id_user = ?', array($id));
        R::exec('UPDATE other SET id_user=0 WHERE id_user = ?', array($id));
        R::exec('UPDATE main SET id_user=0 WHERE id_user = ?', array($id));
        R::exec('UPDATE car SET id_user=0 WHERE id_user = ?', array($id));


        R::trash($user); ///delete ill from DB
        $_SESSION['msg'] = 2; //ok_delete
        $app->redirect('/str/user/');
    });
});

/* * ******************* СПИСКИ СМЕН: добавить, ред, удалить работника *************************** */
$app->group('/listfio',$is_auth, function () use ($app, $log) {


    function getNamePasp() {
        if ($_SESSION['ulevel'] == 3) {//grochs
            return R::getAll('SELECT r.id, case when(r.divizion_num=0) then d.name else concat(d.name, " № ", r.divizion_num) end as divizion_name from ss.records as r
            left join ss.divizions as d ON r.id_divizion=d.id  where r.id_loc_org = ? order by d.name, r.divizion_num ', array($_SESSION['ulocorg'])
            );
        } elseif ($_SESSION['ulevel'] == 4) {//pasch
            return R::getAll('SELECT r.id, case when(r.divizion_num=0) then d.name else concat(d.name, " № ", r.divizion_num) end as divizion_name from ss.records as r
          left join ss.divizions as d ON r.id_divizion=d.id  where r.id = ? order by d.name, r.divizion_num ', array($_SESSION['urec'])
            );
        }
        //ROSN
        elseif ($_SESSION['ulevel'] == 2 && $_SESSION['note'] == 8) {
            return R::getAll('SELECT id, name as divizion_name from menurosn ');
        }
              elseif ($_SESSION['ulevel'] == 2 && $_SESSION['note'] == 9) {// UGZ
                return R::getAll('SELECT r.id, case when(r.divizion_num=0) then  concat(d.name, "-",loc.name ) else concat(d.name, " № ", r.divizion_num, "-",loc.name) end as divizion_name from ss.records as r
            left join ss.divizions as d ON r.id_divizion=d.id left join ss.locorg as locor on locor.id=r.id_loc_org '
          .'   left join ss.locals as loc on loc.id=locor.id_local  where locor.id_organ = ? order by d.name, r.divizion_num ', array(UGZ)
            );
        }
    }

    function getCardCh() {
        //  if ($_SESSION['ulevel'] == 3) {//grochs

        if ($_SESSION['ulevel'] == 2) {
                 if ( $_SESSION['note'] == 8) {     //ROSN
                        return R::getAll('SELECT c.id, c.id_card, c.ch from cardch as c left join ss.records as r ON c.id_card=r.id left join ss.locorg as locor ON locor.id=r.id_loc_org'
                            . '  where locor.id_organ = ? order by c.ch ', array(8));
                 }
                   elseif ( $_SESSION['note'] == UGZ) {     //UGZ
                      return R::getAll('SELECT c.id, c.id_card, c.ch from cardch as c left join ss.records as r ON c.id_card=r.id'
                            . '   order by c.ch ');
                 }

        } else {
            return R::getAll('SELECT c.id, c.id_card, c.ch from cardch as c left join ss.records as r ON c.id_card=r.id'
                            . '  where r.id_loc_org = ? order by c.ch ', array($_SESSION['ulocorg']));
        }

        /* } elseif ($_SESSION['ulevel'] == 4) {//pasch
          return R::getAll('SELECT c.id, c.id_card, c.ch from cardchstr as c left join records as r ON c.id_card=r.id'
          . '  where r.id = ? order by c.ch ', array($_SESSION['urec'])
          ); */
    }

    function getListRank() {
        return R::getAll('SELECT * from rank order by name asc');
    }

    function getListPosition() {
        return R::getAll('SELECT * from position');
    }


//форма добавления работника-смена, пасч
    $app->get('/add', function () use ($app) {
        array($app, 'is_auth');
//список ПАСЧ
        $data['pasp'] = getNamePasp();
//смена+record.id
        $data['cardch'] = getCardCh();
        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadListFio.php');
        $app->render('listfio/addForm.php', $data);
        $app->render('layouts/footer.php');
    });

//форма добавления работника - фио раюотников
    $app->post('/add', function () use ($app) {
        array($app, 'is_auth');

        $id_record = $app->request()->post('id_record'); //pasp
        $id_cardch = $app->request()->post('id_cardch'); //cardch
        $count_empl = $app->request()->post('count_empl'); //cardch
        $data['count_empl'] = $count_empl;
        $data['id_cardch'] = $id_cardch;
// name ПАСЧ
        $data['pasp'] = R::getCell('SELECT (CASE WHEN (d.id = ?) then concat(d.name," - ", loc.name) '
                        . ' WHEN (r.divizion_num = 0) THEN d.name   ELSE CONCAT(d.name," № ",r.divizion_num) END)  as divizion_name '
                        . ' from ss.records as r left join ss.divizions as d ON r.id_divizion=d.id left join ss.locorg as locor on locor.id=r.id_loc_org'
                        . ' left join ss.locals as loc on loc.id=locor.id_local  where r.id = ? ', array(4,$id_record));
        $data['ch'] = R::getCell('SELECT c.ch from cardch as c '
                        . '  where c.id = ? ', array($id_cardch)
        );

        if ($data['ch'] == 0) {//ежедневников не контролировать

        } else {
            /* ------ по штату из КУСиС ------- */
            $data['on_shtat'] = getShtatFromKUSiS($data['ch'], $id_record);

            /* -------- по списку смены из списка смены без ежедневников  --------- */
            $data['on_list'] = R::getCell('select count(l.id) as shtat from str.cardch as c '
                            . ' left join str.listfio as l on l.id_cardch=c.id where c.id_card = ? and c.ch = ? ', array($id_record, $data['ch']));
        }

        $data['rank'] = getListRank();
        $data['position'] = getListPosition();

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadListFio.php');
        $app->render('listfio/formListFio.php', $data);
        $app->render('layouts/footer.php');
    });

//добавление работника
    $app->post('/', function () use ($app) {
        array($app, 'is_auth');
//список ПАСЧ
        $id_cardch = $app->request()->post('id_cardch'); //cardch
        $count_empl = $app->request()->post('count_empl');

        for ($i = 0; $i < $count_empl; $i++) {

            $fio = $app->request()->post('fio' . $i);
            $id_rank = $app->request()->post('id_rank' . $i);
            $id_position = $app->request()->post('id_position' . $i);
              $is_vacant= $app->request()->post('is_vacant' . $i);
                 $is_nobody= $app->request()->post('is_nobody' . $i);
            //vacant select
            if($is_vacant==1){
                   $listfio = R::dispense('listfio');
                $listfio->fio = 'ВАКАНТ';
                $listfio->is_vacant = 1;
                $listfio->id_rank = $id_rank;
                $listfio->id_position = $id_position;
                $listfio->id_cardch = $id_cardch;
                R::store($listfio);
            }
             elseif ($is_nobody == 1) {//нет работников
                if (R::getCell('select ch from cardch where id=?', array($id_cardch)) != 0) {//должен быть ежедневником
                    $id_card_for_nobody = R::getCell('select id_card from cardch where id=?', array($id_cardch));
                    $id_cardch_for_nobody = R::getCell('select id from cardch where id_card = ? and ch = ?', array($id_card_for_nobody, 0));
                } else
                    $id_cardch_for_nobody = $id_cardch;

                if (!isset($id_cardch_for_nobody) || empty($id_cardch_for_nobody))
                    $id_cardch_for_nobody = $id_cardch;

                $listfio = R::dispense('listfio');
                $listfio->fio = 'НЕТ РАБОТНИКОВ';
                $listfio->is_vacant = 0;
                $listfio->is_nobody = 1;
                $listfio->id_rank = $id_rank;
                $listfio->id_position = $id_position;
                $listfio->id_cardch = $id_cardch_for_nobody;
                R::store($listfio);
            }
            else{
                     if (isset($fio) && !empty($fio)) {
                $listfio = R::dispense('listfio');
                $listfio->fio = $fio;
                $listfio->is_vacant = 0;
                $listfio->id_rank = $id_rank;
                $listfio->id_position = $id_position;
                $listfio->id_cardch = $id_cardch;
                R::store($listfio);
            }
           }
        }
        $app->redirect('/str/listfio');
    });

//форма ред работника
    $app->get('/edit/:id', function ($id) use ($app) {
        array($app, 'is_auth');
//инф по работнику
        $data['empl'] = R::getAll('SELECT c.id, c.id_card, c.ch, l.fio,l.is_vacant,l.is_nobody, ra.id AS rank, pos.id AS position from str.cardch as c left join str.listfio as l ON c.id=l.id_cardch'
                        . ' left join str.rank AS ra ON l.id_rank=ra.id left join str.position AS pos ON l.id_position=pos.id where l.id = ? ', array($id)
        );

        $data['id_empl'] = $id;
//список ПАСЧ
        $data['pasp'] = getNamePasp();
//смена+record.id
        $data['cardch'] = getCardCh();
        $data['rank'] = getListRank();
        $data['position'] = getListPosition();
        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadListFio.php');
        $app->render('listfio/editForm.php', $data);
        $app->render('layouts/footer.php');
    });

//редактирование работника
    $app->put('/:id', function ($id) use ($app, $log) {
        array($app, 'is_auth');
//список ПАСЧ
        $id_cardch = $app->request()->post('id_cardch'); //cardch
        $fio = $app->request()->post('fio');
        $id_rank = $app->request()->post('id_rank');
        $id_position = $app->request()->post('id_position');
   $is_vacant = $app->request()->post('is_vacant');
    $is_nobody= $app->request()->post('is_nobody');

           $log_array_old = json_decode(R::load('listfio', $id));//что было до редs

        //vacant select
        if ($is_vacant == 1) {
            $listfio = R::load('listfio', $id);
            $listfio->fio = 'ВАКАНТ';
            $listfio->is_vacant = 1;
            $listfio->id_rank = $id_rank;
            $listfio->id_position = $id_position;
            $listfio->id_cardch = $id_cardch;
            R::store($listfio);
        }
        elseif ($is_nobody == 1) {//нет работников
            if (R::getCell('select ch from cardch where id=?', array($id_cardch)) != 0) {//должен быть ежедневником
                $id_card_for_nobody = R::getCell('select id_card from cardch where id=?', array($id_cardch));
                $id_cardch_for_nobody = R::getCell('select id from cardch where id_card = ? and ch = ?', array($id_card_for_nobody, 0));
            } else
                $id_cardch_for_nobody = $id_cardch;

            if (!isset($id_cardch_for_nobody) || empty($id_cardch_for_nobody))
                $id_cardch_for_nobody = $id_cardch;


            $listfio = R::load('listfio', $id);
            $listfio->fio = 'НЕТ РАБОТНИКОВ';
            $listfio->is_vacant = 0;
            $listfio->is_nobody = 1;
            $listfio->id_rank = $id_rank;
            $listfio->id_position = $id_position;
            $listfio->id_cardch = $id_cardch_for_nobody;
            R::store($listfio);
        }

        else {
            if (isset($fio) && !empty($fio)) {
                $listfio = R::load('listfio', $id);
                $listfio->fio = $fio;
                $listfio->is_vacant = 0;
                $listfio->is_nobody = 0;
                $listfio->id_rank = $id_rank;
                $listfio->id_position = $id_position;
                $listfio->id_cardch = $id_cardch;
                R::store($listfio);
            }
        }

        $log_array = json_decode(R::load('listfio', $id));//что стало после рад
        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Редактирование listfiostr - запись с id=' . $id . '- Новые данные:: '.json_encode($log_array,JSON_UNESCAPED_UNICODE). '- Старые данные:: '.json_encode($log_array_old,JSON_UNESCAPED_UNICODE));
        $app->redirect('/str/listfio');
    });

    //предупреждение об удалении работника
    $app->get('/delete/:id', function ($id) use ($app) {
        array($app, 'is_auth'); //авторизован ли пользователь
        $data['id_empl'] = $id;
        $today=date("Y-m-d");
        $yesterday= date("Y-m-d", time()-(60*60*24));
        $time=date("H:i:s");
        $warning=array();

        /*--------- Проверить числится ли человек где-ниб ----------*/

        /* +++ больничные +++ */
        $is_ill = R::getCell('SELECT id FROM ill WHERE id_fio = ? AND (( ? BETWEEN date1 and date2) or( ? >= date1 and date2 is NULL)) ', array($id, $today, $today));
        if(!empty($is_ill)){
            $warning[]='на больничном';
        }
        /* +++ КОНЕЦ больничные +++ */

        /* +++ Отпуска +++ */
        $is_hol = R::getCell('SELECT id FROM holiday WHERE id_fio = ? AND (( ? BETWEEN date1 and date2) or( ? >= date1 and date2 is NULL)) ', array($id, $today, $today));
                if(!empty($is_hol)){
            $warning[]='в отпуске';
        }
        /* +++ КОНЕЦ Отпуска +++ */

        /* +++ Командировка +++ */
        $is_trip = R::getCell('SELECT id FROM trip WHERE id_fio = ? AND (( ? BETWEEN date1 and date2) or( ? >= date1 and date2 is NULL)) ', array($id, $today, $today));
                if(!empty($is_trip)){
            $warning[]='в командировке';
        }
        /* +++ КОНЕЦ Командировка +++ */

        /* +++ Др.причины +++ */
        $is_other = R::getCell('SELECT id FROM other WHERE id_fio = ? AND (( ? BETWEEN date1 and date2) or( ? >= date1 and date2 is NULL)) ', array($id, $today, $today));
                if(!empty($is_other)){
            $warning[]='отсутствует по другой причине';
        }
        /* +++ КОНЕЦ Др.причины +++ */

        /* +++ Боевой расчет (после 12 ночи и до 08 утра считать что смена дежурная ) +++ */
        $is_br = R::getCell('SELECT fc.id FROM fiocar as fc left join car as c on c.id=fc.id_tehstr WHERE'
                        . ' id_fio = ? AND (c.dateduty = ? OR (c.dateduty = ? and ? < ? )) ', array($id, $today, $yesterday, $time, '08:00:00'));
                if(!empty($is_br)){
            $warning[]='в боевом расчете';
        }

        /* +++ КОНЕЦ Боевой расчет+++ */
 $data['warning']=$warning;
        /*--------- END Проверить числится ли человек где-ниб ----------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadListFio.php');
        $app->render('listfio/msg/delete.php', $data); //delete ?
        $app->render('listfio/delete.php', $data);
        $app->render('listfio/back.php', $data);
        $app->render('layouts/footer.php');
    });

    //удаление работника- каскадное
    $app->delete('/delete/:id', function ($id) use ($app, $log) {//msg delete ill by id
        array($app, 'is_auth'); //авторизован ли пользователь
        $listfio = R::load('listfio', $id);
         $log_array = json_decode($listfio);

        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Удаление listfiostr - запись с id=' . $id . '- Данные:: ' . json_encode($log_array,JSON_UNESCAPED_UNICODE));
        R::trash($listfio); ///delete from DB
        $app->redirect('/str/listfio');
    });

    /*----------------------------------- Закрыть больничный -----------------------------------*/
    //форма закрытия больничного
     $app->get('/close_ill/:id', function ($id) use ($app) {
        array($app, 'is_auth'); //авторизован ли пользователь
        $data['id_ill'] = $id;//ill.id
                //информация о больном
$data['ill']=R::getAll('select l.fio, p.name as position, date_format(i.date1,"%d-%m-%Y") AS date1,date_format(i.date2,"%d-%m-%Y") AS date2, i.id  from ill as i '
        . 'left join listfio as l on l.id=i.id_fio left join position as p on p.id=l.id_position where i.id = ? ', array($id));

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadListFio.php');
        $app->render('msg/close_ill/danger.php', $data); //предупрежд сообщение
        $app->render('listfio/close_ill/close.php', $data);
        $app->render('listfio/back.php', $data);
        $app->render('layouts/footer.php');
    });

    //закрытие больничного
    $app->post('/close_ill/:id', function ($id) use ($app) {
        array($app, 'is_auth');

        $date1 = $app->request()->post('date1');
        if (empty($date1))
            $date1 = NULL;
        else
            $date1 = date("Y-m-d", strtotime($date1));
        $date2 = $app->request()->post('date2');
        if (empty($date2))
            $date2 = NULL;
        else
            $date2 = date("Y-m-d", strtotime($date2));

        /* ----------- date2 должна быть больше date1 ----------- */
        if ((($date2 > $date1) && ($date1 != NULL)) || (($date1 != NULL) && ($date2 == NULL))) {

            /* ----- определить дату дежурства этой смены, чтобы на нее этот работник оcтался на больничном-эту дату нельзя указать как date2 ------ */
            $id_fio = R::getCell('select id_fio from ill where id = ?', array($id));
            $id_cardch = R::getCell('select id_cardch from listfio where id = ?', array($id_fio));
            $id_card = R::getCell('select id_card from cardch where id = ?', array($id_cardch));
            $id_ch = R::getCell('select ch from cardch where id = ?', array($id_cardch));
            //дата, на которую работник должен быть еще на больничном-ею нельзя закрыть больничный
            $last_dateduty = R::getCell('select dateduty from main where id_card = ? and ch = ? order by dateduty limit ?', array($id_card, $id_ch, 1));


            if (($date2 >= $last_dateduty) || ($date2 == NULL)) {
                $ill = R::load('ill', $id);
                $ill->date2 = $date2;
                $ill->last_update = date("Y-m-d H:i:s");
                R::store($ill);

                if($_SESSION['ulevel'] == 1){
                     $app->redirect('/str/listfio/ill');
                }
                else
                $app->redirect('/str/listfio');

            }
            else {
            $app->redirect('/str/listfio/close_ill/' . $id);
        }
        } else {
            $app->redirect('/str/listfio/close_ill/' . $id);
        }
    });

        /*----------------------------------- END Закрыть больничный -----------------------------------*/

        /*----------------------------------- Отозвать из отпуска -----------------------------------*/

        //Отозвать из отпуска
    $app->get('/close_hol/next/:id', function ($id) use ($app) {
        array($app, 'is_auth');

        //отпуск закрывается вчерашним днем
        $hol = R::load('holiday', $id);
        $hol->date2 = date("Y-m-d", time() - (60 * 60 * 24));
        $hol->last_update = date("Y-m-d H:i:s");
        R::store($hol);

       if ($_SESSION['ulevel'] == 1) {
            $app->redirect('/str/listfio/holiday');
       }
       else
       $app->redirect('/str/listfio');
    });
    //форма Отозвать из отпуска
     $app->get('/close_hol/:id', function ($id) use ($app) {
        array($app, 'is_auth'); //авторизован ли пользователь
        $data['id_hol'] = $id;//hol.id

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadListFio.php');
        $app->render('listfio/close_hol/danger.php', $data); //предупрежд сообщение
        $app->render('listfio/back.php', $data);
        $app->render('layouts/footer.php');
    });

    /*----------------------------------- END Отозвать из отпуска -----------------------------------*/

        $app->get('(/:vid_absent)', function ($vid_absent = NULL) use ($app) {// список смен
        array($app, 'is_auth');

        $data['locorg_umchs']=locorg_umchs;//для ЦоУ. Видит другое сообщение, если доступ на ред смен закрыт.

        /* ------- ФИО, кто сегодня на больничном-им можно закрыть больничный --------- */
        $today = date("Y-m-d");
        $is_ill = R::getAll('select id_fio,id from ill as i where (? BETWEEN i.date1 and i.date2) or(? >= i.date1 and i.date2 is NULL)', array($today, $today));
        $data['is_ill'] = $is_ill;

        //работники на больничном
        if (isset($is_ill) && !empty($is_ill)) {
            foreach ($is_ill as $i) {
                $ill[] = $i['id_fio'];
                $id_of_ill[$i['id_fio']] = $i['id']; //массив фио=>ill.id
            }
        } else {
            $ill = array();
        }
        /* -------END ФИО, кто сегодня на больничном-им можно закрыть больничный --------- */

        /* ------- ФИО, кого можно отозвать из отпуска --------- */
        $is_hol = R::getAll('select id_fio,id from holiday as i where ( (? BETWEEN i.date1 and i.date2) or(? >= i.date1 and i.date2 is NULL)) AND (? <> i.date1) ', array($today, $today, $today));
        $data['is_hol'] = $is_hol;

        //работники в отпуске
        if (isset($is_hol) && !empty($is_hol)) {
            foreach ($is_hol as $i) {
                $hol[] = $i['id_fio'];
                $id_of_hol[$i['id_fio']] = $i['id']; //массив фио=>hol.id
            }
        } else {
            $hol = array();
        }
        /* -------END ФИО, кого можно отозвать из отпуска  --------- */

        $listfio = array();

        if ($_SESSION['ulevel'] == 1 && $_SESSION['is_admin'] == 1) {//РЦУ
            if (isset($vid_absent) && $vid_absent == 'ill') {//больничные
                if (!empty($ill))
                    $listfio = R::getAll('SELECT * from list_of_change WHERE id_fio IN (' . implode(",", $ill) . ')');
            }
            elseif (isset($vid_absent) && $vid_absent == 'holiday') {//отозвать из отпуска
                if (!empty($hol))
                    $listfio = R::getAll('SELECT * from list_of_change WHERE id_fio IN (' . implode(",", $hol) . ')');
            }
        }
        elseif ($_SESSION['ulevel'] == 2) {

            if ($_SESSION['note'] == 8) {//ROSN видит всех работников всех РОСН
                $listfio = R::getAll('SELECT * from list_of_change where id_organ = ? ', array(8));
            } elseif ($_SESSION['note'] == NULL) {//Область УМЧС  работников по области, которым можно закрыть бол/отпуск. Без АВИАЦИИ, РОСН,УГЗ
                $cp = array(ROSN, UGZ, AVIA);


                if (isset($vid_absent) && $vid_absent == 'ill') {//больничные
                    if (!empty($ill))
                        $listfio = R::getAll('SELECT * from list_of_change WHERE id_fio IN (' . implode(",", $ill) . ')and id_region = ? and id_organ NOT IN (' . implode(",", $cp) . ')', array($_SESSION['uregions']));
                }
                elseif (isset($vid_absent) && $vid_absent == 'holiday') {//отозвать из отпуска
                    if (!empty($hol))
                        $listfio = R::getAll('SELECT * from list_of_change WHERE id_fio IN (' . implode(",", $hol) . ')and id_region = ? and id_organ NOT IN (' . implode(",", $cp) . ')', array($_SESSION['uregions']));
                } else //просто список всей области
                    $listfio = R::getAll('SELECT * from list_of_change WHERE id_region = ? and id_organ NOT IN (' . implode(",", $cp) . ')', array($_SESSION['uregions']));
            }
            elseif ($_SESSION['note'] == UGZ) {// UGZ-г.Минск видит всех рабоников УГЗ
                if (!empty($ill) || !empty($hol))
                    $listfio = R::getAll('SELECT * from list_of_change WHERE  id_organ = ?', array(UGZ));
            }
        } elseif ($_SESSION['ulevel'] == 3) {//grochs
            $listfio = R::getAll('SELECT * from list_of_change where id_loc_org = ?  ', array($_SESSION['ulocorg'])
            );
        } elseif ($_SESSION['ulevel'] == 4) {//pasp
            $listfio = R::getAll('SELECT * from list_of_change where record_id = ?  ', array($_SESSION['urec'])
            );
        }
        $data['list_fio'] = $listfio;

        //редактировать работника, если он заступил начальником смены нельзя
        $data['not_edit'] = R::getAll('SELECT id_fio from main'
                        // . '  where is_duty = ? ', array(1)
        );


        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadListFio.php');

        if ($_SESSION['ulevel'] == 1) {
            if (isset($vid_absent) && $vid_absent == 'ill') {
                $app->render('listfio/absent/listIll.php', $data);
            } elseif (isset($vid_absent) && $vid_absent == 'holiday') {
                $app->render('listfio/absent/listHoliday.php', $data);
            } else {
                $app->render('listfio/listFio.php', $data);
            }
        } else {

            if (isset($vid_absent) && $vid_absent == 'ill') {
                $app->render('listfio/absent/listIll.php', $data);
            } elseif (isset($vid_absent) && $vid_absent == 'holiday') {
                $app->render('listfio/absent/listHoliday.php', $data);
            } else {
                if ($_SESSION['ulevel'] == 3 || $_SESSION['ulevel'] == 4 || $_SESSION['note'] == UGZ || $_SESSION['note'] == ROSN || $_SESSION['note'] == AVIA)
                    $app->render('listfio/listFio.php', $data);//можно редактировать
                else//область видит весь список
                    $app->render('listfio/listFioAll.php', $data);//просто просмотр
            }
        }

        $app->render('layouts/footer.php');
    });


    /*-------- открыть доступ на ред списка смен --------*/

     $app->get('/open/table', function () use ($app) {

                 array($app, 'is_auth'); //авторизован ли пользователь

if(in_array($_SESSION['ulocorg'], locorg_umchs))  {//Если авторизован ЦОУ области - может открыть доступ только себе
    $data['user']=R::getAll('select id, name, is_deny from user where regions_id = ? and note is null  '
        . ' and can_edit = ? AND id = ?', array($_SESSION['uregions'],1,$_SESSION['uid']));
}
 else {
      //users текущей области без РОСН, УГЗ,Авиации
$data['user']=R::getAll('select id, name, is_deny from user where regions_id = ? and note is null  '
        . ' and can_edit = ?', array($_SESSION['uregions'],1));
}


//users, которым уже открыт доступ




        $app->render('layouts/header.php');
        $app->render('layouts/menu.php');
        $app->render('bread/breadListFio.php');
$app->render('listfio/open/table.php',$data);

        $app->render('layouts/footer.php');

    });


        //открыть
     $app->get('/open/:id', function ($id) use ($app) {

        array($app, 'is_auth'); //авторизован ли пользователь

        $user = R::load('user', $id);
        $user->is_deny = 1;
        R::store($user);

        if($_SESSION['ulevel']==1)
             $app->redirect('/str/user');
        else
         $app->redirect('/str/listfio/open/table');

    });

      //закрыть
     $app->get('/close/:id', function ($id) use ($app) {

        array($app, 'is_auth'); //авторизован ли пользователь

        $user = R::load('user', $id);
        $user->is_deny = 0;
        R::store($user);

                if($_SESSION['ulevel']==1)
             $app->redirect('/str/user');
        else
         $app->redirect('/str/listfio/open/table');

    });

    /*-------- КОНЕЦ открыть доступ на ред списка смен --------*/

});


/* ----------------------------------------------------------------------------------------------------------------------  v1.0 ----------------------------------------------------------------------------------------------------------------------- */

/* * ***************  Сообщение о том, какая смена сегодня дежурит, кто внес информацию ******************* */

function bread($id) {//bread crumb
    /* if (($id == 160) || ($id == 162) || ($id == 163)) {//ROSN
      $record = R::getAll('SELECT * FROM record WHERE idCard = :idC limit 1', [':idC' => $id]
      ); */
//} else {
    /*  $record = R::getAll('SELECT * FROM card WHERE id_record = :idRec limit 1', [':idRec' => $id]
      ); //для формирования bread */
// }
    // $data['record'] = $record;
    $data['record_id'] = $id;
    $sign = R::getCell('select orgid from ss.card where id_record = ? limit 1', array($id));
    if ($sign == 8) {//ROSN
        $data['bread_rosn'] = R::getAll('select * from menurosn where id = ?', array($id));
      $data['id_grochs'] = R::getCell('select id_card from ss.card where id_record = ?', array($id));
    } elseif (($sign == 9) || ($sign == 10) || ($sign == 11) || ($sign == 12)) {
        $data['id_grochs'] = R::getCell('select id_card from ss.card where id_record = ?', array($id));
        $data['region'] = R::getCell('select region from ss.card  where id_record = ?', array($id));
        $data['bread_cp'] = 1;
    } else {//умчс
        $data['id_grochs'] = R::getCell('select id_card from ss.card where id_record = ?', array($id));
        $data['region'] = R::getCell('select region from ss.card  where id_record = ?', array($id));
    }

    $today = date("Y-m-d");
    $yesterday= date("Y-m-d", time()-(60*60*24));

    for ($i = 1; $i <= 3; $i++) {

                /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
             $inf = R::getAll("select * from maincou where id_card = ? and ch = ? and dateduty = ? limit 1",array($id,$i,$today));
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
             $inf = R::getAssoc("CALL get_main('{$id}','{$i}', '{$today}');");
        }

        if (!empty($inf)) {

            foreach ($inf as $key => $value) {
                if ($value['is_duty'] == 1) {

                                    /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){

            $who_insert = R::getAll('SELECT u.name, m.last_update  FROM maincou as m inner join user as u on u.id=m.id_user WHERE m.id= :m_id limit 1', [':m_id' => $value['id']]);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
          $who_insert = R::getAll('SELECT u.name, m.last_update  FROM main as m inner join user as u on u.id=m.id_user WHERE m.id= :m_id', [':m_id' => $value['id']]);
        }

                    foreach ($who_insert as $w) {
                        $inf[$key]['name'] = $w['name'];
                        $inf[$key]['last_update'] = $w['last_update'];
                    }
                    $inf[$key]['ch'] = $i; //номер деж смены
                    $data['inf'] = $inf;
                    break;
                }
            }
        }

    }


    return $data;
}

/* * ******************  //кто авторизован, имеет ли право на просмотр данной информации  ********************* */

function auth($id) {
    $app = \Slim\Slim::getInstance();
    if ($_SESSION['ulevel'] == 2) {//область
        if ($_SESSION['note'] == 8) {//ROSN
            /*  if (($id == 160) || ($id == 162) || ($id == 163)) {//ROSN
              //отобразить
              } else {
              $app->redirect('/str');
              } */
            $organid = R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);

            if ($organid != $_SESSION['note'])
                $app->redirect('/str');
        }
          elseif ($_SESSION['note'] == UGZ) {//UGZ
            $organid = R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);

            if ($organid != $_SESSION['note'])
                $app->redirect('/str');
        }
        else {

            $region = R::getCell('SELECT region FROM ss.card WHERE id_record =:id', [':id' => $id]);

            if ($region != $_SESSION['uregions'])
                $app->redirect('/str');
        }
    }
    if ($_SESSION['ulevel'] == 3) {//район
        /*  if ($_SESSION['note'] == 8) {//ROSN
          if ($id != $_SESSION['ulocorg']) {
          //echo 'ss='.$_SESSION['ulocorg'];
          //echo 'dd'.$loc;
          $app->redirect('/str');
          }
          } else { */
        $loc = R::getCell('SELECT id_card FROM ss.card WHERE id_record =:id', [':id' => $id]);
        if ($loc != $_SESSION['ulocorg']) {
//echo 'ss='.$_SESSION['ulocorg'];
//echo 'dd'.$loc;
//$app->redirect('/str');
            $app->redirect('/str');
        }
// }
    }

    if ($_SESSION['ulevel'] == 4) {//ПАСЧ
        if ($id != $_SESSION['urec'])
            $app->redirect('/str');
    }
}

/* * ******************** у смены  is_duty=1 или 0  на сегодня**********************
  $sign=0 - вернуть duty
 * 1 - вернуть is_open_update
 *  */

function is_duty($id, $change, $sign) {

    $is_main = R::getAssoc("CALL get_main('{$id}','{$change}', 0);");

    if (!empty($is_main)) {//isset
        foreach ($is_main as $row) {
            if ($row['is_duty'] == 0) {
                $duty = 0; //смена не дежурная
            } else {
                $duty = 1; //смена  дежурная
            }
            if ($row['open_update'] == 0)
                $open_update = 0;
            else
                $open_update = 1;
        }
    } else {
        $duty = 0; //смена не дежурная
        $open_update = 0;
    }

    if ($sign == 0)
        return $duty;
    else
        return $open_update;
}

//ЦОУ
function is_duty_cou($id, $change, $sign) {

    $is_main = R::getAll("select is_duty, open_update from maincou where id_card = ? and ch = ? limit 1 ",array($id,$change));

    if (!empty($is_main)) {//isset
        foreach ($is_main as $row) {
            if ($row['is_duty'] == 0) {
                $duty = 0; //смена не дежурная
            } else {
                $duty = 1; //смена  дежурная
            }
            if ($row['open_update'] == 0)
                $open_update = 0;
            else
                $open_update = 1;
        }
    } else {
        $duty = 0; //смена не дежурная
        $open_update = 0;
    }


    if ($sign == 0)
        return $duty;
    else
        return $open_update;
}

//доступна ли кнопка "Подтвердить данные"
function is_btn_confirm($change) {

    /*     * ****************  вариант 2 - определяем заступающую смену по дате  ********************* */
    $row = R::getRow('SELECT * FROM dutych');
    $start_date = $row['start_date'];
    $start_ch = $row['start_ch'];



    //определяем разность в днях: сег-$start_date
    $raznost = date_diff(new DateTime(), new DateTime($start_date))->days;
    //смена, которая сег д.заступать
    $duty_ch = $start_ch + $raznost;

    if ($duty_ch > 3) {
        $duty_ch = 1;
    }
    if ($raznost != 0) {
        //обновить $start_date, $start_ch в БД
        R::exec('update dutych  set start_date = ?, start_ch = ? ', array(date('Y-m-d'), $duty_ch));
    }
    if ($duty_ch == $change)
        $is_btn_confirm = 1; //кнопка доступна
    else {
        $is_btn_confirm = 0; //кнопка не доступна
    }
    return $is_btn_confirm;
}

//Время, после которого у областей нет возможности открыть доступ на редактирование
function time_allow_open() {
    $time_allow = R::getCell('select is_allow_open from dutych where start_date = :today', [':today' => date('Y-m-d')]);
    if (isset($time_allow) && !empty($time_allow)) {
        $time_allow = $time_allow;
    } else
        $time_allow = '11:00:00';
    return $time_allow;
}

//номер дежурной смены
function duty_ch() {
    $ch = R::getCell('select start_ch from dutych where start_date = :today', [':today' => date('Y-m-d')]);
    if (isset($ch) && !empty($ch)) {
        $ch = $ch;
    } else
        $ch = 1;
    return $ch;
}

/* * ***********************  Выбор id ФИО по различным критериям для формирования списка раболтников в выпадающем меню  ********************************* */

/* список ФИО смены
  $sign: 1-своя cardch
 * 0 - не своя
  $is_every: 0 - смены 0-3
 * 1 - 1-3
 *          */

function getListFio($id, $change, $sign, $is_every,$is_nobody=NULL) {
   $cp_arr=array(ROSN,UGZ,AVIA);

    $id_cardch = getIdCardCh($id, $change);
   // $id_loc_org_mas = R::getAll('select r.id_loc_org, l.id_organ from ss.records as r left join ss.locorg as l on l.id=r.id_loc_org where r.id=? ', array($id));
     $id_loc_org_mas = R::getAll('select r.id_loc_org, l.id_organ,loc.id_region from ss.records as r left join ss.locorg as l on l.id=r.id_loc_org '
             . ' left join ss.locals as loc on loc.id=l.id_local  where r.id=? ', array($id));
    $today = date("Y-m-d");

    foreach ($id_loc_org_mas as $value) {
        $id_loc_org = $value['id_loc_org'];
        $id_organ = $value['id_organ'];
        $id_region = $value['id_region'];
    }

    $sql = 'SELECT l.id, l.fio FROM listfio as l LEFT JOIN cardch as c on l.id_cardch=c.id LEFT JOIN ss.records as r on r.id=c.id_card LEFT JOIN ss.locorg as locor on locor.id=r.id_loc_org'
            . ' LEFT JOIN ss.locals as loc on loc.id=locor.id_local  ';
    if ($sign == 1) {//своя смена
        $sql = $sql . '  WHERE c.id =  ? ';
        $param[] = $id_cardch;
    } elseif ($sign == 0) {//чужая
        //ROSN
        if ($id_organ == 8 || $id_organ==9 || $id_organ==12) {//выбор в пределах РОСН(id_organ)/УГЗ/Авиации
            $sql = $sql . '  WHERE ( c.id <> ? AND locor.id_organ = ?) and c.id not in(select id from cardch where c.id_card = ? and ch = ?)';
            $param[] = $id_cardch;
            $param[] = $id_organ;
            $param[] = $id;
            $param[] = 0; //отнять ежедневников этого РОСН(своего РОСН)
        } else {//выбор в пределах  REGION

          //  $sql = $sql . '  WHERE c.id <> ? AND r.id_loc_org = ? and c.id not in(select id from cardch where c.id_card = ? and ch = ?) ';//в пределах ГРОЧС(id-loc_org)

             // выбираем только УМЧС.Работников РОСН,УГЗ,Авиации не выбирать
              $sql = $sql . '  WHERE c.id <> ? AND loc.id_region = ? and c.id not in(select id from cardch where c.id_card = ? and ch = ?) AND locor.id_organ not in ('. implode(",", $cp_arr) . ') ';
            $param[] = $id_cardch;
            //$param[] = $id_loc_org;  //выбор ФИО в пределах своего ГРОЧС
            $param[] = $id_region;  //выбор ФИО в пределах своего  REGION
            $param[] = $id;
            $param[] = 0; //отнять ежедневников этого ПАСЧ(своего ПАСЧ)

        }
    }
    if ($is_every == 1) {
        $sql = $sql . ' AND c.ch <> ?';
        $param[] = 0;
    }



    //исключить "нет работников"
    if($is_nobody != NULL){
                $sql = $sql . ' AND l.is_nobody = ?';
            $param[] = 0;
            }

    $result = R::getAll($sql, $param);

    if (!empty($result)) {
        foreach ($result as $value) {
            $list_fio[] = $value['id'];
        }
    } else
        $list_fio = array();
    return $list_fio;
}

// список ФИО  больные на сегодня
function getListFioIll($date) {
    $result = R::getAll('SELECT  id_fio from ill where  (( ? BETWEEN date1 and date2) or( ?  >= date1 and date2 is NULL))', array($date, $date));
    if (!empty($result)) {
        foreach ($result as $value) {
            $list_fio[] = $value['id_fio'];
        }
    } else
        $list_fio = array();
    return $list_fio;
}

//holiday
function getListFioHoliday($date) {
    $result = R::getAll('SELECT  id_fio from holiday where (( ? BETWEEN date1 and date2) or( ?  >= date1 and date2 is NULL))', array($date, $date));
    if (!empty($result)) {
        foreach ($result as $value) {
            $list_fio[] = $value['id_fio'];
        }
    } else
        $list_fio = array();
    return $list_fio;
}

//other
function getListFioOther($date) {
    $result = R::getAll('SELECT  id_fio from other where (( ? BETWEEN date1 and date2) or( ?  >= date1 and date2 is NULL))', array($date, $date));
    if (!empty($result)) {
        foreach ($result as $value) {
            $list_fio[] = $value['id_fio'];
        }
    } else
        $list_fio = array();
    return $list_fio;
}

//trip
function getListFioTrip($date) {
    $result = R::getAll('SELECT  id_fio from trip where (( ? BETWEEN date1 and date2) or( ?  >= date1 and date2 is NULL))', array($date, $date));
    if (!empty($result)) {
        foreach ($result as $value) {
            $list_fio[] = $value['id_fio'];
        }
    } else
        $list_fio = array();
    return $list_fio;
}

/* reserve
  $sign: 1-своя cardch
 * 0 - не своя
 * 2 - важно знать, работник какого ПАСЧ
  $date: 0- любая дата
 *          */

function getListFioReserve($id, $change, $sign, $date) {
    $id_cardch = getIdCardCh($id, $change);
    $today = date("Y-m-d");
    if ($sign == 2) {
        $result = R::getAll('SELECT r.id_fio  FROM reservefio as r left join listfio as l on r.id_fio=l.id left join cardch as c on c.id=l.id_cardch WHERE '
                        . 'r.id_cardch <> :id_cardch AND r.date_reserve = :date AND c.id_card = :id AND (c.ch = :ch OR c.ch = :every) ', [':id_cardch' => $id_cardch, ':date' => $date, ':id' => $id, ':ch' => $change, ':every' => 0]);
    } else {
        $sql = 'SELECT id_fio FROM reservefio  ';
        if ($sign == 1) {//своя смена
            $sql = $sql . '  WHERE id_cardch =  ? ';
        } elseif ($sign == 0) {//чужая
            $sql = $sql . '  WHERE id_cardch <> ? ';
        }

        $param[] = $id_cardch;
        if ($date != 0) {
            $sql = $sql . ' AND date_reserve = ?';
            $param[] = $today;
        }

        $result = R::getAll($sql, $param);
    }


    if (!empty($result)) {
        foreach ($result as $value) {
            $list_fio[] = $value['id_fio'];
        }
    } else
        $list_fio = array();
    return $list_fio;
}

/* Списко ФИО, задействованных в main - начальник смены
  $sign: 1-своя cardch
 * 0 - не своя
  $date: 0- любая дата
 *          */

function getListFioMain($id, $change, $sign, $date) {
    $id_cardch = getIdCardCh($id, $change);
    $today = date("Y-m-d");

    if ($sign == 1) {//своя смена
        $sql = 'SELECT id_fio FROM main WHERE id_card  =  ? AND ch = ?  ';
        //$sql = $sql . '  WHERE id_card  =  ? AND ch = ? ';
    } elseif ($sign == 0) {//чужая
        $sql = 'SELECT id_fio FROM main WHERE id not in (select id from main WHERE id_card  =  ? AND ch = ? )  ';
        //    $sql = $sql . '  WHERE id_card  <> ? AND ch <> ? ';
    }
    $param[] = $id;
    $param[] = $change;
    if ($date != 0) {
        $sql = $sql . ' AND dateduty = ?';
        $param[] = $today;
    }
    $result = R::getAll($sql, $param);

    if (!empty($result)) {
        foreach ($result as $value) {
            $list_fio[] = $value['id_fio'];
        }
    } else
        $list_fio = array();
    return $list_fio;
}


/* Списко ФИО, задействованных в maincou ЦОУ
  $sign: 1-своя cardch
 * 0 - не своя
  $date: 0- любая дата
 *          */

function getListFioMainCou($id, $change, $sign, $date) {
    $id_cardch = getIdCardCh($id, $change);
    $today = date("Y-m-d");

    if ($sign == 1) {//своя смена
        $sql = 'SELECT id_fio FROM maincou WHERE id_card  =  ? AND ch = ?  ';
        //$sql = $sql . '  WHERE id_card  =  ? AND ch = ? ';
    } elseif ($sign == 0) {//чужая
        $sql = 'SELECT id_fio FROM maincou WHERE id not in (select id from maincou WHERE id_card  =  ? AND ch = ? )  ';
        //    $sql = $sql . '  WHERE id_card  <> ? AND ch <> ? ';
    }
    $param[] = $id;
    $param[] = $change;
    if ($date != 0) {
        $sql = $sql . ' AND dateduty = ?';
        $param[] = $today;
    }
    $result = R::getAll($sql, $param);

    if (!empty($result)) {
        foreach ($result as $value) {
            $list_fio[] = $value['id_fio'];
        }
    } else
        $list_fio = array();
    return $list_fio;
}

/*  employees  maincou by position*/
function getListFioByPosMainCou($id, $change, $sign, $date, $id_pos_duty) {
    $id_cardch = getIdCardCh($id, $change);
    $today = date("Y-m-d");

    if ($sign == 1) {//своя смена
        $sql = 'SELECT id_fio FROM maincou WHERE id_card  =  ? AND ch = ? AND id_pos_duty = ? ';
        //$sql = $sql . '  WHERE id_card  =  ? AND ch = ? ';
    } elseif ($sign == 0) {//чужая
        $sql = 'SELECT id_fio FROM maincou WHERE id not in (select id from maincou WHERE id_card  =  ? AND ch = ? AND id_pos_duty = ? )  ';
        //    $sql = $sql . '  WHERE id_card  <> ? AND ch <> ? ';
    }
    $param[] = $id;
    $param[] = $change;
    $param[]=$id_pos_duty;
    if ($date != 0) {
        $sql = $sql . ' AND dateduty = ?';
        $param[] = $today;
    }
    $result = R::getAll($sql, $param);

    if (!empty($result)) {
        foreach ($result as $value) {
            $list_fio[] = $value['id_fio'];
        }
    } else
        $list_fio = array();
    return $list_fio;
}


/*  employees  maincou by position - fio text*/
function getListFioTextByPosMainCou($id, $change, $sign, $date, $id_pos_duty) {
    $id_cardch = getIdCardCh($id, $change);
    $today = date("Y-m-d");

    if ($sign == 1) {//your change
        $sql = 'SELECT fio_text FROM maincou WHERE id_card  =  ? AND ch = ? AND id_pos_duty = ? ';
        //$sql = $sql . '  WHERE id_card  =  ? AND ch = ? ';
    } elseif ($sign == 0) {//foreign change
        $sql = 'SELECT fio_text FROM maincou WHERE id not in (select id from maincou WHERE id_card  =  ? AND ch = ? AND id_pos_duty = ? )  ';
        //    $sql = $sql . '  WHERE id_card  <> ? AND ch <> ? ';
    }
    $param[] = $id;
    $param[] = $change;
    $param[]=$id_pos_duty;
    if ($date != 0) {
        $sql = $sql . ' AND dateduty = ?';
        $param[] = $today;
    }
    $result = R::getAll($sql, $param);

    if (!empty($result)) {
        foreach ($result as $value) {
            $list_fio[] = $value['fio_text'];
        }
    } else
        $list_fio = array();
    return $list_fio;
}

/* Список ФИО ежедневники
  $sign: 1-своя cardch
 * 0 - не своя
  $date: 0- любая дата
 * $simple - просто выбор всех ежедневников смены
 *          */

function getListFioEveryday($id, $change, $sign, $date, $simple,$is_nobody=NULL) {
    $id_cardch = getIdCardCh($id, $change);
    $today = date("Y-m-d");

    if ($simple == 1) {//просто выбор всех ежедневников смены
        $sql = 'SELECT l.id FROM listfio as l inner join cardch as c on l.id_cardch=c.id  ';
        $sql = $sql . '  WHERE c.ch =  ? ';
        $param[] = 0;

        $sql = $sql . ' AND c.id_card =  ? ';
        $param[] = $id;
    } else {
        $sql = 'SELECT l.id FROM listfio as l inner join everydayfio as e on l.id=e.id_fio  ';
        if ($sign == 1) {//своя смена
            $sql = $sql . '  WHERE e.id_cardch =  ? ';
        } elseif ($sign == 0) {//чужая
            $sql = $sql . '  WHERE e.id_cardch <> ? ';
        }
        $param[] = $id_cardch;

        if ($date != 0) {
            $sql = $sql . ' AND e.date_duty = ?';
            $param[] = $today;
        }
    }

    //исключить "нет работников"
    if($is_nobody != NULL){
                $sql = $sql . ' AND l.is_nobody = ?';
            $param[] = 0;
            }

    $result = R::getAll($sql, $param);

    if (!empty($result)) {
        foreach ($result as $value) {
            $list_fio[] = $value['id'];
        }
    } else
        $list_fio = array();
    return $list_fio;
}

/* работники на технике */

function getListFioCar($date) {
    $result = R::getAll('SELECT  f.id_fio  from fiocar as f inner join car as c on f.id_tehstr=c.id WHERE c.dateduty = :date ', [':date' => $date]);
    if (!empty($result)) {
        foreach ($result as $value) {
            $list_fio[] = $value['id_fio'];
        }
    } else
        $list_fio = array();
    return $list_fio;
}

//формула для вычитания элементов массива $array2 из $array1
function minusArray($array1, $array2) {
    foreach ($array2 as $key => $value) {
        if (in_array($value, $array1)) {
            //delete
            foreach ($array1 as $key1 => $value1) {
                if ($value1 == $value)
                    unset($array1[$key1]);
            }
        }
    }
    return $array1;
}

//формула для записи элементов массива $array2 в $array1
function plusArray($array1, $array2) {
    foreach ($array2 as $key => $value) {
        if (!in_array($value, $array1)) {
            //+
            $array1[] = $value;
        }
    }
    return $array1;
}

//получить ФИО по id
function getFioById($array, $id, $change) {
   // print_r($array);

    $cardch = getIdCardCh($id, $change);
    if (!empty($array)) {//с учетом РОСН!!!!
//        return R::getAll('SELECT l.id, case when(o.id=8) then case when(c.id = :cardch) then l.fio else concat(l.fio," ",lo.name," ",o.name) end else case when(c.id = :cardch) then l.fio '
//                        //  . ' case when(c.id = :cardch) then l.fio else concat(l.fio," ",d.name,"№",re.divizion_num) end as fio  '
//                        . ' else case when(re.divizion_num = 0) then concat(l.fio," ",d.name) else concat(l.fio," ",d.name,"№",re.divizion_num) end end end as fio, po.name as slug from listfio as l '
//                        . ' left join cardch as c on l.id_cardch=c.id left join ss.records as re on c.id_card=re.id left join ss.divizions as d on d.id=re.id_divizion'
//                        . '		left join ss.locorg as locor on locor.id=re.id_loc_org left join ss.locals as lo on lo.id=locor.id_local '
//                        . ' left join ss.organs as o on o.id=locor.id_organ left join str.position as po on l.id_position=po.id  WHERE l.id IN (' . implode(",", $array) . ')', [':cardch' => $cardch]);
        //выбор ФИО по их id($array)
        $fio= R::getAll('SELECT * FROM get_fio_by_id WHERE id IN (' . implode(",", $array) . ')');

        //если работник этой же смены - ему не выводить принадлежность
        foreach ($fio as $key=> $value) {
            if($cardch==$value['id_cardch']){
                $fio[$key]['pasp']=' ';
                $fio[$key]['locorg_name']=' ';
            }

        }
        return $fio;
    } else
        return array();
}


//получить ФИО по id для ЦОУ
function getFioByIdMainCou($array, $id, $change) {
   // print_r($array);

    $cardch = getIdCardCh($id, $change);
    if (!empty($array)) {//с учетом РОСН!!!!
//        return R::getAll('SELECT l.id, case when(o.id=8) then case when(c.id = :cardch) then l.fio else concat(l.fio," ",lo.name," ",o.name) end else case when(c.id = :cardch) then l.fio '
//                        //  . ' case when(c.id = :cardch) then l.fio else concat(l.fio," ",d.name,"№",re.divizion_num) end as fio  '
//                        . ' else case when(re.divizion_num = 0) then concat(l.fio," ",d.name) else concat(l.fio," ",d.name,"№",re.divizion_num) end end end as fio, po.name as slug from listfio as l '
//                        . ' left join cardch as c on l.id_cardch=c.id left join ss.records as re on c.id_card=re.id left join ss.divizions as d on d.id=re.id_divizion'
//                        . '		left join ss.locorg as locor on locor.id=re.id_loc_org left join ss.locals as lo on lo.id=locor.id_local '
//                        . ' left join ss.organs as o on o.id=locor.id_organ left join str.position as po on l.id_position=po.id  WHERE l.id IN (' . implode(",", $array) . ')', [':cardch' => $cardch]);
        //выбор ФИО по их id($array)
        $fio= R::getAll('SELECT * FROM get_fio_by_id WHERE id IN (' . implode(",", $array) . ')');

        //если работник этой же смены - ему не выводить принадлежность
        foreach ($fio as $key=> $value) {
            if($cardch==$value['id_cardch']){
                $fio[$key]['pasp']=' ';
                $fio[$key]['locorg_name']=' ';
            }

        }
        return $fio;
    } else
        return array();
}

//список ежедневников, которые на определенную дату заступили в смену $id_cardch
function getListEverydayFio($id_cardch, $today) {
    return R::getAll('select e.id_fio, l.fio AS fio from '
                    . 'everydayfiostr as e inner join listfiostr as l ON e.id_fio=l.id inner join cardchstr AS c ON l.id_cardch=c.id inner join records AS rec ON c.id_card=rec.id inner join'
                    . ' divizions AS d ON rec.id_divizion=d.id inner join positionstr AS pos ON l.id_position=pos.id where '
                    . 'e.id_cardch = :id_cardch AND e.date_duty = :today', [':id_cardch' => $id_cardch, ':today' => $today]);
}

//список Заступают из др подразделения
function getPresentReserveFio($id, $change) {
    $today = date("Y-m-d");
    /* ФИО чужих ПАСЧ без "нет работников" */
    $list_fio = getListFio($id, $change, 0, 0,$is_nobody=1);

    //больные
    $list_ill = getListFioIll($today);
    //отпуск
    $list_hol = getListFioHoliday($today);
    //other
    $list_other = getListFioOther($today);
    //reserve
    $list_reserve = getListFioReserve($id, $change, 0, $today);
    //main
    $list_main = getListFioMain($id, $change, 0, $today);

        //maincou
    $list_maincou = getListFioMainCou($id, $change, 0, $today);

    //everyday
    $list_everyday = getListFioEveryday($id, $change, 0, $today, 0);

    //fio in car today
    $list_on_car = getListFioCar($today);
    // plus reserve
    $list_plus_reserve = getListFioReserve($id, $change, 1, $today);


    //вычислить по формуле
    $result = minusArray($list_fio, $list_ill);
    $result = minusArray($result, $list_hol);
    $result = minusArray($result, $list_other);
    $result = minusArray($result, $list_reserve);
    $result = minusArray($result, $list_main);
    $result = minusArray($result, $list_maincou);//ЦОУ
    $result = minusArray($result, $list_everyday);
    $result = minusArray($result, $list_on_car);
    $result = plusArray($result, $list_plus_reserve);
    //получить ФИО

    return getFioById($result, $id, $change);
}

//список Заступают ежедневники, учитываем "нет работников"
function getPresentEverydayFio($id, $change) {
    $today = date("Y-m-d");
    /* ФИО everyday  своего ПАСЧ */
    $list_fio = getListFioEveryday($id, $change, 1, 0, 1);

    //reserve
    $list_reserve = getListFioReserve($id, $change, 0, $today);

    //вычислить по формуле
    $result = minusArray($list_fio, $list_reserve);
    //получить ФИО
    return getFioById($result, $id, $change);
}

//Список начальник смены
function getPresentHeadFio($id, $change,$is_nobody=NULL) {
    $today = date("Y-m-d");
    /* ФИО своего ПАСЧ без ежедневников */
    $list_fio = getListFio($id, $change, 1, 1);

    //больные
    $list_ill = getListFioIll($today);
    //отпуск
    $list_hol = getListFioHoliday($today);
    //other
    $list_other = getListFioOther($today);
    //trip
    $list_trip = getListFioTrip($today);
    //reserve
    $list_reserve_minus = getListFioReserve($id, $change, 0, $today);
    /* +++++++++++++++++++++++ */
    //reserve
    $list_reserve_plus = getListFioReserve($id, $change, 1, $today);
    //everyday
    $list_everyday = getListFioEveryday($id, $change, 1, $today, 0,$is_nobody);


    //вычислить по формуле
    $result = minusArray($list_fio, $list_ill);
    $result = minusArray($result, $list_hol);
    $result = minusArray($result, $list_other);
    $result = minusArray($result, $list_trip);
    $result = minusArray($result, $list_reserve_minus);
    $result = plusArray($result, $list_reserve_plus);
    $result = plusArray($result, $list_everyday);
    //получить ФИО
    return getFioById($result, $id, $change);
}

//список ФИО для вкладок отсутствующие, кроме командировки
function getPresentAbsent($id, $change) {
    $today = date("Y-m-d");
    //ФИО текущей смены без ежедневников
    $list_fio = getListFio($id, $change, 1, 1);
    //резерв на сегодня
    $list_reserve = getListFioReserve($id, $change, 0, $today);
    //на машинах на сегодня
    $list_car = getListFioCar($today);
    //кто на сегодня заступил как начальник смены
    $list_main = getListFioMain($id, $change, 1, $today);

        //кто на сегодня заступил  в ЦОУ maincou
    $list_maincou = getListFioMainCou($id, $change, 1, $today);

    //вычислить по формуле
    $result = minusArray($list_fio, $list_reserve);
    $result = minusArray($result, $list_car);
    $result = minusArray($result, $list_main);
     $result = minusArray($result, $list_maincou);

    //получить ФИО
    return getFioById($result, $id, $change);
}

// список ФИО для вкладки командировки
function getPresentAbsentTrip($id, $change) {
    $today = date("Y-m-d");
    //ФИО текущей смены без ежедневников
    $list_fio = getListFio($id, $change, 1, 1);
    //резерв на сегодня
    $list_reserve_plus = getListFioReserve($id, $change, 2, $today);
    //на машинах на сегодня
    $list_car = getListFioCar($today);
    //кто на сегодня заступил как начальник смены
    $list_main = getListFioMain($id, $change, 1, $today);

        //кто на сегодня заступил в ЦОУ maincou
    $list_maincou = getListFioMainCou($id, $change, 1, $today);

    //вычислить по формуле
    $result = minusArray($list_fio, $list_car);
    $result = plusArray($result, $list_reserve_plus);
    $result = minusArray($result, $list_main);
    $result = minusArray($result, $list_maincou);

    //получить ФИО
    return getFioById($result, $id, $change);
}

/* * ************************************************************************************END************************************************************************************************************* */

/* *****************************************  Count l/s in ideal  *******************************************************************/

   /*------------- по штату выбираем из КУСиС для данной смены! ----------------*/
function getShtatFromKUSiS($change,$id){
          if($change == 1)
            $number_of_ch='change_one';
        elseif($change == 2)
            $number_of_ch='change_two';
         elseif($change == 3)
            $number_of_ch='change_three';

      return R::getCell('select st.'.$number_of_ch.' from ss.staff as st where st.id_record = ? ',array($id));
}

//сколько реально в б.р(на технике)
function getCountCalc($id, $change, $last_data) {
     /* +++++ Список техники этого ПАСЧ для заполнения +++++ */
        $own_car = getOwnCar($id, $change, $last_data);
        $data['own_car'] = $own_car;
        //ФИО на этой технике
        $fio_array = array();
        foreach ($own_car as $value) {
            $fio_array[] = $value['tehstr_id'];
        }
        $fio_1= getFioOnCar($fio_array, $id, $change);

        /* +++++ Список техники, заступающей из др ПАСЧ для заполнения +++++ */
        $car_in_reserve = getCarInReserve($id, $last_data, $change);
        $data['car_in_reserve'] = $car_in_reserve;
        //ФИО на этой технике
        $fio_array = array();
        foreach ($car_in_reserve as $value) {
            $fio_array[] = $value['tehstr_id'];
        }
        $fio_2 = getFioOnCar($fio_array, $id, $change);
        return count($fio_1)+count($fio_2);
}

/* real count of men on list from list of change - vacant - "no worker" */
function getCountOnList($id, $change) {
       /*-------- по списку смены из списка смены без ежедневников и без ВАКАНСИЙ ---------*/
                $on_list = R::getCell('select count(l.id) as shtat from str.cardch as c '
                                . ' left join str.listfio as l on l.id_cardch=c.id where c.id_card = ? and c.ch = ? and l.is_vacant = ? and is_nobody = ?', array($id, $change,0,0));
                if(empty($on_list ))
                    $on_list =0;
                return $on_list;
}

// vacant on list in change
function getCountVacantOnList($id, $change) {
       /*-------- on list in change from list of change - vacant ---------*/
                $on_list = R::getCell('select count(l.id) as shtat from str.cardch as c '
                                . ' left join str.listfio as l on l.id_cardch=c.id where c.id_card = ? and c.ch = ? and l.is_vacant = ?', array($id, $change,1));
                if(empty($on_list ))
                    $on_list =0;
                return $on_list;
}
/* ***************************************** END Count l/s in ideal *******************************************************************/

/* FOR COU  real count of men on list from list of change with vacant - "no worker" in all changes */
function getCountOnListAllForCou($id, $change) {
       /*-------- on list from list of change with everyday_worker with vacant ---------*/
                $on_list = R::getCell('select count(l.id) as shtat from str.cardch as c '
                                . ' left join str.listfio as l on l.id_cardch=c.id where c.id_card = ?  and  is_nobody = ?', array($id, 0));
                if(empty($on_list ))
                    $on_list =0;
                return $on_list;
}


/*------------------- тип ПАСЧ (ЦОУ, ШЛЧС, ПАСЧБ ПАСП...) ------------------------*/

function getIdDivizion($id) {
    return R::getCell('SELECT id_divizion FROM menu WHERE record_id = ? limit 1',array($id));
}

/*------------------- тип ПАСЧ (ЦОУ, ШЛЧС, ПАСЧБ ПАСП...) ------------------------*/


$app->group('/v1/card', $is_auth, function () use ($app, $log) {

//main
    $app->get('/:id/ch/:change/main', function ($id, $change) use ($app) {//sheet other reasons for  change
        array($app, 'is_auth');
        auth($id); //кто авторизован, имеет ли право на просмотр данной информации

        /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 ){//ЦОУ
             $app->redirect('/str/v2/card/' . $id . '/ch/' . $change . '/main');
        }
        elseif(getIdDivizion($id) == 9){//ШЛЧС
            $app->redirect('/str/v2/card/' . $id . '/ch/' . $change . '/main_sch');
        }
        /*--- ЦОУ, ШЛЧС---*/

        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 5; //main
        $today = date("Y-m-d");
        $duty = is_duty($id, $change, 0); //смена дежурная или нет
        $data['duty'] = $duty;
        $data['is_open_update'] = is_duty($id, $change, 1); //открыт ли доступ на ред
        $data['duty_ch'] = duty_ch();

        /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/
       // $data['is_check']=$id;

        /*         * **************************  Списки ФИО ************************************ */
        //список Заступают из др подразделения
        $data['present_reserve_fio'] = getPresentReserveFio($id, $change);

        //список Заступали из др подразделения
        $data['past_reserve_fio'] = getFioById(getListFioReserve($id, $change, 1, 0), $id, $change);
        // список Заступают ежедневники, учитываем "нет работников"
        $data['present_everyday_fio'] = getPresentEverydayFio($id, $change);
        // список Заступали ежедневники, не учитывать "нет работников"
        $data['past_everyday_fio'] = getFioById(getListFioEveryday($id, $change, 1, 0, 0), $id, $change);
        // список ФИО начальника смены, учитываем "нет работников"
        $data['present_head_fio'] = getPresentHeadFio($id, $change);
        // Заступал ФИО начальника смены
        $data['past_head_fio'] = getFioById(getListFioMain($id, $change, 1, 0), $id, $change);

        //кнопка "Подтвердить данные"
        $data['is_btn_confirm'] = is_btn_confirm($change);

        /*------------- по штату выбираем из карточки для данной смены! ----------------*/
        $data['count_ls_shtat']=  getShtatFromKUSiS($change, $id);

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);
        $app->render('card/sheet/start.php', $data);
        $app->render('card/sheet/main/main.php', $data);

        /*         * *********************** сообщение о выполнении операции ********************************* */
        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение

        /*         * ***************************************** */
        //get main
        $main = R::getAssoc("CALL get_main('{$id}','{$change}', 0);");
        if (isset($main) && !empty($main)) {//есть запись
            ///выбор последней даты
            foreach ($main as $value) {
                //format 10-01-2018 to 2018-01-10
                /*  $date = new DateTime($value['dateduty']);
                  $last_data = $date->Format('Y-m-d'); */
                $last_data = $value['dateduty'];
            }

            /* ------------ кол-во больных, отпусков, командировок,др.причины ------------ */
            $data['count_ill'] = getCountIll($id, $change, $last_data);
            $data['count_holiday'] = getCountHoliday($id, $change, $last_data);
            $data['count_trip'] = getCountTrip($id, $change, $last_data);
            $data['count_other'] = getCountOther($id, $change, $last_data);

            $data['main'] = $main;

            $data['post'] = 0; //put data main...при хранении инф за месяц =1: не обновляем, а добавляем запись
        } else {
            //empty form
            //выводим пустую формы
            //insert row
            //кол-во больных, отпусков, командировок,др.причины
            $data['count_ill'] = 0;
            $data['count_holiday'] = 0;
            $data['count_trip'] = 0;
            $data['count_other'] = 0;
            			 $last_data = date('Y-m-d');

        }
        		 /* -------- по списку смены из списка смены без ежедневников и без "нет работников" --------- */
            $data ['on_list'] = getCountOnList($id, $change);
                /* ------------------- vacant from list of change  ---------------------- */
            $data['count_vacant_from_list'] = getCountVacantOnList($id, $change);
			 /* ------------------- сколько человек в б.р.(на технике) ---------------------- */
            $data['count_fio_on_car'] = getCountCalc($id, $change, $last_data);

        $app->render('card/sheet/main/formFillMain.php', $data); //view data
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/:id/ch/:change/main', function ($id, $change) use ($app, $log) {// form main for fill
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 5; //main
//insert
        $dateduty = $app->request()->post('dateduty');
        $reserve = $app->request()->post('reserve');
        if (empty($reserve))
            $reserve = array();
        $everydayfio = $app->request()->post('everydayfio');
        if (empty($everydayfio))
            $everydayfio = array();
        if (empty($dateduty))
            $dateduty = NULL;
        else
            $dateduty = date("Y-m-d", strtotime($dateduty));

        //ФИО начальника смены
        $id_head_fio = $app->request()->post('id_fio');

        $countls = $app->request()->post('countls');
        $listls = $app->request()->post('listls');
        $vacant=$app->request()->post('vacant');
        //$vacant = $countls - $listls;
        $face = $app->request()->post('face');
        $calc = $app->request()->post('calc');
       // $countduty = $face - $calc;
        $countduty = $app->request()->post('duty');

        //если не указан начальник смены
        if(empty($id_head_fio) || !isset($id_head_fio)){

            /*---- поставить начальником выбранного ежедневника( любого) ---*/
            if(!empty($everydayfio)){
                foreach ($everydayfio as $value) {
                    $id_head_fio=$value;
                }
            }
            /*------- поставить начальником работника из др ПАСЧ ------*/
            elseif(!empty($reserve)){
                foreach ($reserve as $value) {
                    $id_head_fio=$value;
                }
            }
            else{
                 $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/main');
             exit();
            }

        }

        $id_main=R::getCell('select id from main where id_card = ? and ch = ? ORDER BY dateduty DESC LIMIT ?',array($id,$change,1));
        if (isset($id_main) && !empty($id_main)) {
            $main = R::load('main', $id);
        } else {
            $main = R::dispense('main');
        }

        $main->id_card = $id;
        $main->ch = $change;
        $main->dateduty = $dateduty;
        $main->id_fio = $id_head_fio;
        $main->is_duty = 0;
        $main->countls = $countls;
        $main->listls = $listls;
        $main->face = $face;
        $main->vacant = $vacant;
        $main->calc = $calc;
        $main->gas = $app->request()->post('gas');
        $main->duty = $countduty;
        $main->countdisp = $app->request()->post('countdisp');
       // $main->fiodisp = $app->request()->post('fiodisp');
        $main->fio_duty = $app->request()->post('fio_duty');
        $main->last_update = date("Y-m-d H:i:s");
        $main->id_user = $_SESSION['uid'];

        R::store($main);

        if (isset($reserve)) {
            setReserve($reserve, $id, $change, $dateduty, $id_head_fio, $log); //заступающие из другич частей
        }
        if (isset($everydayfio)) {
            setEverydayFio($everydayfio, $id, $change, $dateduty, $id_head_fio, $log); //заступающие ежедневники
        }

        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/main');
    });

    $app->put('/:id/ch/:change/main', function ($id, $change) use ($app, $log) {//update main information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 5; //main
//update
        $id_main = $app->request()->post('idmain'); //id of mainstr
        $dateduty = $app->request()->post('dateduty');

        /*         * ***** ежедневники ****** */
        $everyday_add = $app->request()->post('everyday_add');
        $everydayfio = $app->request()->post('everydayfio');
        //добавить к ежедневникам disabled
        if (!empty($everyday_add) && isset($everyday_add))
            $everydayfio[] = $everyday_add;
        if (empty($everydayfio))
            $everydayfio = array();
        //print_r($everydayfio);
        /*         * ********** работники из др.подразделения ************* */
        $reserve_add = $app->request()->post('reserve_add');
        $reserve = $app->request()->post('reserve');
        //добавить к ежедневникам disabled
        if (!empty($reserve_add) && isset($reserve_add))
            $reserve[] = $reserve_add;
        if (empty($reserve))
            $reserve = array();
        //print_r($reserve);
        //ФИО начальника смены
        $id_head_fio = $app->request()->post('id_fio');
        if (empty($dateduty))
            $dateduty = NULL;
        else
            $dateduty = date("Y-m-d", strtotime($dateduty));

          //если не указан начальник смены
        if(empty($id_head_fio) || !isset($id_head_fio)){

            /*---- поставить начальником выбранного ежедневника( любого) ---*/
            if(!empty($everydayfio)){
                foreach ($everydayfio as $value) {
                    $id_head_fio=$value;
                }
            }
            /*------- поставить начальником работника из др ПАСЧ ------*/
            elseif(!empty($reserve)){
                foreach ($reserve as $value) {
                    $id_head_fio=$value;
                }
            }
            else{
                 $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/main');
             exit();
            }

        }

        $countls = $app->request()->post('countls');
        $listls = $app->request()->post('listls');
        //$vacant = $countls - $listls;
         $vacant=$app->request()->post('vacant');
        $face = $app->request()->post('face');
        $calc = $app->request()->post('calc');
        //$countduty = $face - $calc;
         $countduty=$app->request()->post('duty');

        $main = R::load('main', $id_main);

        $old_dateduty=$main->dateduty;//дата прошлого дежурства
        $old_ch=$main->ch;//смена прощлого дежурства


        $main->dateduty = $dateduty;
        $main->id_fio = $id_head_fio;
        $main->countls = $countls;
        $main->listls = $listls;
        $main->face = $face;
        $main->vacant = $vacant;
        $main->calc = $calc;
        $main->gas = $app->request()->post('gas');
        $main->duty = $countduty;
        $main->countdisp = $app->request()->post('countdisp');
        //$main->fiodisp = $app->request()->post('fiodisp');
        $main->fio_duty = $app->request()->post('fio_duty');
        $main->last_update = date("Y-m-d H:i:s");
        $main->id_user = $_SESSION['uid'];
        R::store($main);


        /*---- поставить на учет всех отсутствующих, кто отсутствовал прошлое дежурство (вдруг надо продлить даты) ----*/

        if($old_dateduty != $dateduty ){//новое дежурство

//            //выбор больных, кто болен был на прошлую дату дежкрства и не стоит на учете
//        $old_ill = R::getAll('SELECT i.id '
//                        . ' FROM ill AS i inner join listfio AS l '
//                        . 'ON i.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id '
//                        . ' WHERE (c.id_card = :id AND c.ch = :ch) '
//                        . 'AND ( (:today = date_format(i.date_insert,"%Y-%m-%d" )) or ( :today BETWEEN i.date1 and i.date2) or(:today  >= i.date1 and i.date2 is NULL)) '
//                . ' AND i.deregister = :deregister ', [':id' => $id, ':ch' => $old_ch, ':today' => $old_dateduty,':deregister'=>0]);
//
//
//        foreach ($old_ill as $v) {
//         $id_old_ill[]=$v['id'];
//        }
//                //поставить их на учет
//         R::exec('update ill set deregister = ?, last_update = ?, id_user = ? WHERE id IN('.$id_old_ill.')', array(1,  date("Y-m-d H:i:s"),$_SESSION['uid']));

                        //выбор отпусков, кто в отпуске был на прошлую дату дежкрства и не стоит на учете
        $old_hol = R::getAll('SELECT h.id '
                        . ' FROM holiday AS h inner join listfio AS l '
                        . 'ON h.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id '
                        . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                        . 'AND ( (:today = date_format(h.date_insert,"%Y-%m-%d" )) or ( :today BETWEEN h.date1 and h.date2) or(:today  >= h.date1 and h.date2 is NULL)) '
                . ' AND h.deregister = :deregister ', [':id' => $id, ':ch' => $old_ch, ':today' => $old_dateduty,':deregister'=>0]);

       // print_r($old_hol);
        //exit();

        if(!empty($old_hol)){
        foreach ($old_hol as $v) {
         $id_old_hol[]=$v['id'];
        }
                //поставить их на учет
         R::exec('update holiday set deregister = ?, last_update = ?, id_user = ? WHERE id IN('.  implode(',', $id_old_hol).')', array(1,  date("Y-m-d H:i:s"),$_SESSION['uid']));
        }

        }

        /*----- КОНЕЦ поставить на учет всех отсутствующих ------*/


        /*    Если техника на дату дежурства смены находится в ремонте - отметить ее сразу как ремонт с датами, установленными в пред смене.     */
        if($old_dateduty != $dateduty){// Выполнить только 1 раз при изменении dateduty

            $yesterday=date("Y-m-d", time()-(60*60*24));

              $teh_in_repaire = R::getAll('SELECT c.id_teh, c.is_repair, c.start_repaire, c.end_repaire, c.id_reason_repaire, c.comments '
                        . ' FROM car AS c inner join ss.technics AS t '
                        . 'ON c.id_teh=t.id  '
                        . ' WHERE (t.id_record = :id AND c.is_repair = :is_repair) AND c.dateduty = :yesterday '
                        . 'AND  (( :today BETWEEN c.start_repaire and c.end_repaire) or (:today  >= c.start_repaire and c.end_repaire is NULL) or (c.start_repaire is NULL and c.end_repaire is NULL) ) ', [':id' => $id, ':is_repair' => 1,':yesterday'=>$yesterday, ':today' => $dateduty]);


                 // print_r($teh_in_repaire);

              if(isset($teh_in_repaire)&& !empty($teh_in_repaire)){
                  foreach ($teh_in_repaire as $value) {
                      $teh_in_repaire_arr[$value['id_teh']]=array('is_repair'=>$value['is_repair'],'start_repaire'=>$value['start_repaire'],'end_repaire'=>$value['end_repaire'],'id_reason_repaire'=>$value['id_reason_repaire'],'comments'=>$value['comments']);
                  }

                 //print_r($teh_in_repaire_arr);
                //  exit();

                  foreach ($teh_in_repaire_arr as $key => $value) {//поменять состояние техники, если она в ремонте
                       R::exec('update car set is_repair = ?, start_repaire = ?, end_repaire = ?,id_reason_repaire = ?, comments = ?, last_update = ?, id_user = ? WHERE id_teh = ? and dateduty = ?', array($value['is_repair'],$value['start_repaire'], $value['end_repaire'],$value['id_reason_repaire'], $value['comments'], date("Y-m-d H:i:s"),$_SESSION['uid'], $key,$old_dateduty));
                  }

              }

        }



        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Редактирование main - запись с id=' . $id_main . '- Данные:: ' . $main);
        if (isset($reserve)) {
            setReserve($reserve, $id, $change, $dateduty, $id_head_fio, $log); //заступающие из другич частей
        }
        if (isset($everydayfio)) {
            setEverydayFio($everydayfio, $id, $change, $dateduty, $id_head_fio, $log); //заступающие ежедневники
        }
        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/main');
        //print_r($reserve);
    });

    /*обработать массив ФИО, кто заступает из других частей
если работаем с ЦОУ -  $id_head_fio будет массивом (все ФИО, кто заступает в maincou)
     *      */
    function setReserve($reserve, $id, $change, $dateduty, $id_head_fio, $log) {
        $id_cardch = getIdCardCh($id, $change);

        $fioteh_bd = R::getAll('SELECT * FROM reservefio WHERE id_cardch = :id_cardch', ['id_cardch' => $id_cardch]);

        /*         * ********************* проверить, не удалили ли из reserve того, кто выбран как нач-к смены *************** */
        if (!empty($fioteh_bd)) {
            foreach ($fioteh_bd as $key => $value) {
                $mas_from_bd[] = $value['id_fio'];
            }


            if (isset($id_head_fio) && !empty($id_head_fio) && is_array($id_head_fio)) {//ЦОУ
                foreach ($id_head_fio as $f) {
                    //раболтник был в reserve
                    if (in_array($f, $mas_from_bd)) {
                        //теперь его удалили, хотя выбрали как начальника смены
                        if (!in_array($f, $reserve)) {
                            //добавить обратно работника в массив
                            $reserve[] = $f;
                        }
                    }
                }
            } elseif (isset($id_head_fio) && !empty($id_head_fio)) {
                //раболтник был в reserve
                if (in_array($id_head_fio, $mas_from_bd)) {
                    //теперь его удалили, хотя выбрали как начальника смены
                    if (!in_array($id_head_fio, $reserve)) {
                        //добавить обратно работника в массив
                        $reserve[] = $id_head_fio;
                    }
                }
            }
        }
        //  print_r($reserve);
        /*         * *************** */

        //обновить информацию в fiotehstr. Если удален работник др ПАСЧ, то удалить его с машины, если он был на нее назначен
        update_fiotehstr($dateduty, $reserve, $fioteh_bd, $change);

        //если не выбрано ни одного ФИО
        if (empty($reserve)) {
            if (isset($fioteh_bd) && !empty($fioteh_bd)) {
                foreach ($fioteh_bd as $key_bd => $value_bd) {

                    $f = R::load('reservefio', $value_bd['id']);
                    R::trash($f);
                }
            }
        } else {
            if (isset($fioteh_bd) && !empty($fioteh_bd)) {

                //ищем совпадения из формы и из БД-если найдены-ничего не выполнять-оставляем их в БД
                foreach ($fioteh_bd as $key_bd => $value_bd) {
                    foreach ($reserve as $key => $value) {
                        if ($value_bd['id_fio'] == $value) {
                            $reservefio = R::load('reservefio', $value_bd['id']);
                            //обновить дату на сегодня, т.к.работник был зарезервирован на прошлую дату заступления этой смены
                            $reservefio->date_reserve = $dateduty;
                            R::store($reservefio);
                            unset($reserve[$key]);
                            unset($fioteh_bd[$key_bd]);
                            break;
                        }
                    }
                }
                //замена фио в БД на ФИО из формы, если совпадения не найдены
                if (!empty($fioteh_bd)) {
                    foreach ($fioteh_bd as $key_bd => $value_bd) {
                        foreach ($reserve as $key => $value) {

                            $reservefio = R::load('reservefio', $value_bd['id']);
                            $reservefio->id_fio = $value;
                            $reservefio->date_reserve = $dateduty;
                            R::store($reservefio);
                            unset($reserve[$key]);
                            unset($fioteh_bd[$key_bd]);
                            break;
                        }
                    }
                }
//если на форме было > фио, чем в БД- добавить оставшихся
                if (!empty($reserve)) {

                    foreach ($reserve as $key => $value) {

                        $reservefio = R::dispense('reservefio');
                        $reservefio->id_cardch = $id_cardch;
                        $reservefio->id_fio = $value;
                        $reservefio->date_reserve = $dateduty;
                        R::store($reservefio);
                    }
                }
//удалить из БД оставшихся-лишних
                if (!empty($fioteh_bd)) {

                    foreach ($fioteh_bd as $key_bd => $value_bd) {

                        $fiotehstr = R::load('reservefio', $value_bd['id']);
                        R::trash($fiotehstr);
                    }
                }
            } else {//insert
                if (!empty($reserve)) {
                    foreach ($reserve as $key => $value) {
                        $reservefio = R::dispense('reservefio');
                        $reservefio->id_cardch = $id_cardch;
                        $reservefio->id_fio = $value;
                        $reservefio->date_reserve = $dateduty;
                        R::store($reservefio);
                    }
                }
            }
        }
    }

    /* обработать массив ФИО, кто заступают ежедневники
    если работаем с ЦОУ -  $id_head_fio будет массивом (все ФИО, кто заступает в maincou) */

    function setEverydayFio($everydayfio, $id, $change, $dateduty, $id_head_fio, $log) {
        $id_cardch = getIdCardCh($id, $change);

        $fioteh_bd = R::getAll('SELECT * FROM everydayfio WHERE id_cardch = :id_cardch', ['id_cardch' => $id_cardch]);

        /*         * ********************* проверить, не удалили ли из ежедневников того, кто выбран как нач-к смены *************** */
        if (!empty($fioteh_bd)) {
            foreach ($fioteh_bd as $key => $value) {
                $mas_from_bd[] = $value['id_fio'];
            }

                        if (isset($id_head_fio) && !empty($id_head_fio) && is_array($id_head_fio)) {//ЦОУ
                foreach ($id_head_fio as $f) {
                    //раболтник был в reserve
                    if (in_array($f, $mas_from_bd)) {
                        //теперь его удалили, хотя выбрали как начальника смены
                        if (!in_array($f, $everydayfio)) {
                            //добавить обратно работника в массив
                             $everydayfio[] = $f;
                        }
                    }
                }
            } elseif (isset($id_head_fio) && !empty($id_head_fio)) {
            //раболтник был в everyday
            if (in_array($id_head_fio, $mas_from_bd)) {
                //теперь его удалили, хотя выбрали как начальника смены
                if (!in_array($id_head_fio, $everydayfio)) {
                    //добавить обратно работника в массив
                    $everydayfio[] = $id_head_fio;
                }
            }
            }
        }
        /*         * *************** */

        //обновить информацию в fiocar.Если удален ежедневник, то удалить его с машины, если он был на нее назначен
        update_fiotehstr($dateduty, $everydayfio, $fioteh_bd, $change);


        //если не выбрано ни одного ФИО
        if (empty($everydayfio)) {
            if (isset($fioteh_bd) && !empty($fioteh_bd)) {
                foreach ($fioteh_bd as $key_bd => $value_bd) {

                    $e = R::load('everydayfio', $value_bd['id']);
                    R::trash($e);
                }
            }
        } else {
            if (isset($fioteh_bd) && !empty($fioteh_bd)) {

                //ищем совпадения из формы и из БД-если найдены-ничего не выполнять-оставляем их в БД
                foreach ($fioteh_bd as $key_bd => $value_bd) {
                    foreach ($everydayfio as $key => $value) {
                        if ($value_bd['id_fio'] == $value) {
                            //обновить дату на сегодня, т.к.работник был зарезервирован на прошлую дату заступления этой смены
                            $e = R::load('everydayfio', $value_bd['id']);
                            $e->date_duty = $dateduty;
                            R::store($e);
                            unset($everydayfio[$key]);
                            unset($fioteh_bd[$key_bd]);
                            break;
                        }
                    }
                }

                //замена фио в БД на ФИО из формы, если совпадения не найдены
                if (!empty($fioteh_bd)) {
                    foreach ($fioteh_bd as $key_bd => $value_bd) {
                        foreach ($everydayfio as $key => $value) {

                            $e = R::load('everydayfio', $value_bd['id']);
                            $e->id_fio = $value;
                            $e->date_duty = $dateduty;
                            R::store($e);
                            unset($everydayfio[$key]);
                            unset($fioteh_bd[$key_bd]);
                            break;
                        }
                    }
                }
//если на форме было > фио, чем в БД- добавить оставшихся
                if (!empty($everydayfio)) {

                    foreach ($everydayfio as $key => $value) {

                        $e = R::dispense('everydayfio');
                        $e->id_cardch = $id_cardch;
                        $e->id_fio = $value;
                        $e->date_duty = $dateduty;
                        R::store($e);
                    }
                }
//удалить из БД оставшихся-лишних
                if (!empty($fioteh_bd)) {

                    foreach ($fioteh_bd as $key_bd => $value_bd) {

                        $f = R::load('everydayfio', $value_bd['id']);
                        R::trash($f);
                    }
                }
            } else {//insert
                if (!empty($everydayfio)) {

                    foreach ($everydayfio as $key => $value) {
                        $e = R::dispense('everydayfio');
                        $e->id_cardch = $id_cardch;
                        $e->id_fio = $value;
                        $e->date_duty = $dateduty;
                        R::store($e);
                    }
                }
            }
        }
    }

    //обновить информацию в fiocar - удалить работников др.ПАСЧ с машин, если их удалили из reservefio
    function update_fiotehstr($dateduty, $reserve, $fioteh_bd, $change) {
        $fio_from_bd = array();
        if (!empty($fioteh_bd)) {
            foreach ($fioteh_bd as $key_bd => $value_bd) {
                $fio_from_bd[] = $value_bd['id_fio'];
            }
        }
        //ФИО, кто был до этого в БД
        $result = array_diff($fio_from_bd, $reserve);
        if (!empty($result)) {
            //ФИО, кто назначен на машины в этой смене сегодня
            $fioteh = R::getAll('SELECT ft.id, ft.id_fio  FROM fiocar AS ft inner join car AS t ON ft.id_tehstr=t.id  WHERE  t.ch = :change AND t.dateduty = :d ', [ ':change' => $change, ':d' => $dateduty]);

            //удалим из fiotehstr тех, кого удалили из reservefio
            foreach ($fioteh as $value) {
                if (in_array($value['id_fio'], $result)) {
                    $fiotehstr = R::load('fiocar', $value['id']);
                    R::trash($fiotehstr);
                }
            }
        }
    }

//confirm Подтверждение данных
    $app->get('/:id/ch/:change/confirm', function ($id, $change) use ($app) {//confirm msg
        array($app, 'is_auth');
        auth($id);
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 5; //main

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/main/main.php', $data);
        $app->render('card/sheet/confirm/warning.php', $data);
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });
    $app->get('/:id/ch/:change/confirm/next', function ($id, $change) use ($app, $log) {//confirm  проверка соответствия формулам, заполненность вкладок
        array($app, 'is_auth');


                /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
             $app->redirect('/str/v2/card/' . $id . '/ch/' . $change . '/confirm/next');
        }
        /*--- ЦОУ, ШЛЧС---*/

        auth($id);
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 5; //main
        $today = date("Y-m-d");

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);
        $app->render('card/sheet/start.php', $data);
        $app->render('card/sheet/main/main.php', $data);
//заполнены ли вкладки главная, техника, склад
        $mas_error['main'] = getErrorMain($today, $id, $change);
        $mas_error['teh'] = getErrorTeh($today, $id, $change);
        $mas_error['storage'] = getErrorStorage($today, $id, $change);

        if (in_array(1, $mas_error)) {//хоть 1 вкладка не заполнена
            $msg_m = $mas_error['main'] == 1 ? '<strong>Главная</strong>' : '';
            $msg_t = $mas_error['teh'] == 1 ? '<strong>Техника</strong>' : '';
            $msg_s = $mas_error['storage'] == 1 ? '<strong>Склад</strong>' : '';
//$data['mas_error']=$mas_error;
            $data['msg'] = 'Не заполнена(обновлена) информация на вкладках: ' . $msg_m . ' ' . $msg_t . ' ' . $msg_s;
            $app->render('card/sheet/confirm/msg_empty_sheet.php', $data);
        } else {//все вкладки заполнены
            //выполнить подсчет больных на сегодня, т.е. дата1<=дата заступления смены<=дата2
            $c_ill = getCountIll($id, $change, $today);
            //выполнить подсчет отпусков на сегодня, т.е. дата1<=дата заступления смены<=дата2
            $c_hol = getCountHoliday($id, $change, $today);
            //выполнить подсчет др.причин на сегодня, т.е. дата1<=дата заступления смены<=дата2
            $c_other = getCountOther($id, $change, $today);
            //выполнить подсчет командировок на сегодня, т.е. дата1<=дата заступления смены<=дата2
            $c_trip = getCountTrip($id, $change, $today);

            //получить id main
            $main_id = R::getAssoc("CALL get_main('{$id}','{$change}', '{$today}');");
            if (!empty($main_id)) {
                foreach ($main_id as $value) {
                    $id_main = $value['id'];
                }
            }

            $mainstr = R::load('main', $id_main);

            /*             * *****************************************************************    проверка формул    ********************************************************************* */
                        $error_field = array();

            /*   --------------  формула 1: по штату = по штату КУСиС ----------------- */
               //по штату КУСиС
              $shtat_KUSiS=  getShtatFromKUSiS($change, $id);
            if($shtat_KUSiS != $mainstr->countls)
                  $error_field['shtat'] = 1; //ошибка


            /* ----------- формула 2: по списку= кол-ву работников(без ВАКАНТОВ, без ежедневников) из списка смен --------------- */
            // из списка смены
            $on_list = getCountOnList($id, $change);
            if ($on_list != $mainstr->listls)
                $error_field['on_list'] = 1; //ошибка

            /*-------------- формула3: вакантов=кол-во ВАКАНТОВ из списка смены --------------*/
         //вакантов из списка смен
            $count_vacant_from_list= getCountVacantOnList($id, $change);
                 if ($count_vacant_from_list != $mainstr->vacant)
                $error_field['vacant'] = 1; //ошибка

                    /* ----------формула 4: налицо($mainstr->face)=список($mainstr->listls)-больной-командировка-отпуск-др.причины+ежедневники+др.ПАСЧ ---------- */
            //ежедневник
            $count_everyday_fio = count(getFioById(getListFioEveryday($id, $change, 1, 0, 0,$is_nobody=1), $id, $change));
            //из др.ПАСЧ
            $count_reserve_fio = count(getFioById(getListFioReserve($id, $change, 1, 0), $id, $change));

            if ($mainstr->face != ($mainstr->listls - $c_ill - $c_hol - $c_trip - $c_other + $count_everyday_fio + $count_reserve_fio))
                $error_field['face'] = 1; //ошибка

                /* -------------------------------- формула5: б.р.($mainstr->calc)=кол-ву работников на машинах --------------------------------------- */
            //  сколько человек на технике
            $last_data = date("Y-m-d");
            $count_fio_on_car= getCountCalc($id, $change, $last_data);

            if ($mainstr->calc != $count_fio_on_car)
                $error_field['calc'] = 1; //ошибка

                /*                 * *********************************************************    КОНЕЦ проверки формул   ********************************************************************** */

            if (!in_array(1, $error_field)) {//формулы выполняется
                //update is_duty=0 у всех смен этой карточки
                setDutyCh($id, $change);
                $cardch = getIdCardCh($id, $change);
                //выбираем последнюю запись countstr запись этой карточки, смены
                $last_countstr = R::getCell('SELECT id FROM countstr WHERE id_cardch = :cardch order by dateduty DESC LIMIT 1', ['cardch' => $cardch]);

                if (isset($last_countstr) && !empty($last_countstr)) {//если не храним за месяц
                    //update countstr
                    $countstr = R::load('countstr', $last_countstr);
                    $countstr->c_ill = $c_ill;
                    $countstr->c_hol = $c_hol;
                    $countstr->c_trip = $c_trip;
                    $countstr->c_other = $c_other;
                    $countstr->dateduty = date("Y-m-d");
                    $countstr->last_update = date("Y-m-d H:i:s");
                    $countstr->id_user = $_SESSION['uid'];
                    R::store($countstr);
                } else {
                    //insert into countstr
                    $countstr = R::dispense('countstr');
                    $countstr->id_cardch = $cardch;
                    $countstr->c_ill = $c_ill;
                    $countstr->c_hol = $c_hol;
                    $countstr->c_trip = $c_trip;
                    $countstr->c_other = $c_other;
                    $countstr->dateduty = date("Y-m-d");
                    $countstr->last_update = date("Y-m-d H:i:s");
                    $countstr->id_user = $_SESSION['uid'];
                    R::store($countstr);
                }

                //update is_duty=1 у данной смены
                $mainstr->last_update = date("Y-m-d H:i:s");
                $mainstr->id_user = $_SESSION['uid'];
                $mainstr->is_duty = 1;
                R::store($mainstr);

                //тех, кто заступают из других ПАСЧ - занести в командировку в своих ПАСЧ
                setTripFromReserve($id, $change, $today, $log);
                //ту технику, которая заступают из других ПАСЧ - занести в командировку в своих ПАСЧ
               // setReserveToTripCar($id, $change, $today, $log);

                $app->render('card/sheet/confirm/success.php', $data);
            } else {
                //$data['error_field']=$error_field;
                $app->render('card/sheet/confirm/danger.php', $data);
            }
        }
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

//выбор main для change карточки with dateduty=today
    function getIdMain($datte, $id, $change) {
        $main_id = R::getAssoc("CALL get_main('{$id}','{$change}', '{$datte}');");
        if (!empty($main_id)) {
            foreach ($main_id as $value) {
                $m_id = $value['id'];
            }
        } else {
            $m_id = array();
        }

        return $m_id;
    }

//если не заполнена вкладка - ошибка
//если будем хранить за месяц- удалить все, что связано с $two_day_before_yesterday
    function getErrorMain($today, $id, $change) {
        $id_main = R::getAssoc("CALL get_main('{$id}','{$change}', '{$today}');");
        if (isset($id_main) && !empty($id_main)) {//
            $mas_error['main'] = 0;
        }
        //ошибка-вкладка не заполнена
        else
            $mas_error['main'] = 1;

        return $mas_error['main'];
    }

    //если не заполнена вкладка - ошибка  - ЦОУ
    function getErrorMainCou($today, $id, $change) {
        $id_main = R::getCell("select id from maincou where id_card = ? and ch = ? and dateduty = ? limit 1",array($id,$change, $today));
        if (isset($id_main) && !empty($id_main)) {//
            $mas_error['main'] = 0;
        }
        //ошибка-вкладка не заполнена
        else
            $mas_error['main'] = 1;

        return $mas_error['main'];
    }


    function getErrorTeh($today, $id, $change) {
        //обновлена ли вкладка техника сегодня
        $own_car = getOwnCar($id, $change, $today); //машины свои
        $car_from_reserve = getCarInReserve($id, $today, $change); //из др пасч
        $k = 0;
        if (!empty($own_car)) {

            foreach ($own_car as $value) {
                if ($value['dateduty'] == $today)
                    $k++;
            }
            if ($k != 0)
                $mas_error['teh'] = 0; //нет ошибки-вкладка обновлена
            else
                $mas_error['teh'] = 1; //ошибка-вкладка не обновлена
        }
        elseif (!empty($car_from_reserve)) {
            foreach ($car_from_reserve as $value) {
                if ($value['dateduty'] == $today)
                    $k++;
            }
            if ($k != 0)
                $mas_error['teh'] = 0; //нет ошибки-вкладка обновлена
             else
                $mas_error['teh'] = 1; //ошибка-вкладка не обновлена
        }
        else {
            $mas_error['teh'] = 1;
        }
        return $mas_error['teh'];
    }

    function getErrorStorage($today, $id, $change) {
        //обновлена ли вкладка storage сегодня
        $id_storage = R::getCell('SELECT st.id FROM storage AS st inner join cardch AS c ON st.id_cardch=c.id  '
                        . 'WHERE c.id_card = :id AND c.ch = :change'
                        . ' AND st.dateduty = :today', [':id' => $id, ':change' => $change, 'today' => $today]);

        if (isset($id_storage) && !empty($id_storage)) {
            $mas_error['storage'] = 0; //нет ошибки-вкладка обновлена
        } else {
            $mas_error['storage'] = 1; //ошибка
        }
        return $mas_error['storage'];
    }

    function getCountIll($id, $change, $today) {
        $ill = R::getCell('SELECT count(i.id) AS c_ill FROM ill AS i inner join listfio AS l '
                        . 'ON i.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id'
                        . ' WHERE (c.id_card = ? AND c.ch = ?) '
                        . 'AND (( ? BETWEEN i.date1 and i.date2) or( ?  >= i.date1 and i.date2 is NULL))', array($id, $change, $today, $today));

        return (isset($ill) && !empty($ill)) ? $ill : 0;
    }

    function getCountHoliday($id, $change, $today) {
        $holiday = R::getCell('SELECT count(h.id) AS c_hol FROM holiday AS h inner join listfio AS l '
                        . 'ON h.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id'
                        . ' WHERE (c.id_card = ? AND c.ch = ?) '
                        . 'AND (( ? BETWEEN h.date1 and h.date2) or( ?  >= h.date1 and h.date2 is NULL))', array($id, $change, $today, $today));

        return (isset($holiday) && !empty($holiday)) ? $holiday : 0;
    }

    function getCountOther($id, $change, $today) {
        $other = R::getCell('SELECT count(o.id) AS c_other FROM other AS o inner join listfio AS l '
                        . 'ON o.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id'
                        . ' WHERE (c.id_card = ? AND c.ch = ?) '
                        . 'AND (( ? BETWEEN o.date1 and o.date2) or( ?  >= o.date1 and o.date2 is NULL))', array($id, $change, $today, $today));

        return (isset($other) && !empty($other)) ? $other : 0;
    }

    function getCountTrip($id, $change, $today) {
        $trip = R::getCell('SELECT count(t.id) AS c_trip FROM trip AS t inner join listfio AS l '
                        . 'ON t.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id'
                        . ' WHERE (c.id_card = ? AND c.ch = ?) '
                        . 'AND (( ? BETWEEN t.date1 and t.date2) or( ?  >= t.date1 and t.date2 is NULL))', array($id, $change, $today, $today));

        return (isset($trip) && !empty($trip)) ? $trip : 0;
    }

    /*-------  кол-во ежедневников --------*/
     function getCountEveryday($id, $change, $today) {
         $id_cardch=  getIdCardCh($id, $change);
        $other = R::getCell('SELECT count(e.id_fio) AS c_other FROM everydayfio AS e inner join cardch AS c ON e.id_cardch=c.id '
                        . ' WHERE e.id_cardch = ? '
                        . 'AND e.date_duty = ? ', array($id_cardch, $today));

        return (isset($other) && !empty($other)) ? $other : 0;
    }

    function getIdCardCh($id, $change) {
        return R::getCell('SELECT id FROM cardch WHERE id_card = :id '
                        . 'AND ch = :change', ['id' => $id, 'change' => $change]); //получить cardchstr.id
    }

    function setDutyCh($id, $change) {
//        if ($change == 1)
//            $change_down = 3;
//        elseif ($change == 2) {
//            $change_down = 1;
//        } else {
//            $change_down = 2;
//        }
        R::exec('update main set is_duty = ? WHERE is_duty = ? AND  id_card = ? ', array(0, 1, $id));
        //  $main_id = R::getAssoc("CALL get_main('{$id}','{$change_down}', 0);");
//        if (!empty($main_id)) {
//            foreach ($main_id as $value) {
//                $m_id = $value['id'];
//            }
//            R::exec('update main set is_duty = ? WHERE id = ? ', array(0, $m_id));
//        }
    }



    //тех работников, кто заступают из других ПАСЧ - занести в командировку в своих ПАСЧ
    function setTripFromReserve($id, $change, $today, $log) {
        $current_cardch = getIdCardCh($id, $change);
        //ФИО, которые надо отправить в командировку из своей ПАСЧ
        $reserve = R::getAll('SELECT id_fio FROM  reservefio  WHERE id_cardch = :current_cardch AND date_reserve = :today', [':current_cardch' => $current_cardch, ':today' => $today]);

        if (!empty($reserve)) {

            $fio = array();
            foreach ($reserve as $value) {
                $fio[] = $value['id_fio'];
            }
            //$fio_array=implode(",", $fio);
            //исключить ежедневников-не добавлять в командировки, т.к. нет для них смены-не к чему привязать
            $everyday_fio = R::getAll('SELECT l.id from listfio AS l inner join cardch AS c ON l.id_cardch=c.id WHERE l.id IN (' . implode(",", $fio) . ') AND c.ch != :everyday', [ ':everyday' => 0]);

            if (!empty($everyday_fio)) {

                $place_arr = R::getAll('SELECT concat( "смена ",c.ch) as ch ,'
                                . '(CASE WHEN (o.id = 8) THEN CONCAT("") WHEN (o.id = 6) THEN CONCAT("") WHEN (o.id = 9) THEN CONCAT("") '
                                . ' WHEN (o.id = 12) THEN CONCAT("")  WHEN (rec.divizion_num = 0) THEN d.name ELSE CONCAT(d.name," № ",rec.divizion_num) END)  AS pasp, '
                                . ' (CASE WHEN (o.id = 8) THEN CONCAT(o.name,"-",lo.name) WHEN (o.id = 9)  THEN CONCAT(o.name,"-",lo.name) '
                                . ' WHEN (o.id = 12) THEN o.name  WHEN (o.id = 7) THEN CONCAT(o.name," №",locor.no," ", REPLACE(lo.name,"ий","ого")," ",orgg.name)'
                                . ' ELSE CONCAT(lo.name," ",o.name) END)  AS locorg_name '
                                . ' FROM cardch  AS c inner join ss.records AS rec ON c.id_card=rec.id '
                                . '  inner join ss.divizions AS d ON rec.id_divizion=d.id '
                                . 'inner join ss.locorg as locor on locor.id=rec.id_loc_org '
                                . ' inner join ss.locals as lo on lo.id=locor.id_local '
                                . ' left join ss.organs as o on o.id=locor.id_organ '
                                . ' LEFT JOIN ss.organs orgg ON locor.oforg = orgg.id WHERE c.id = ?', array($current_cardch));

                if(!empty($place_arr)){
                    foreach ($place_arr as $row) {
                        $place='Дежурство в: '.$row['ch'].' '.$row['pasp'].' '.$row['locorg_name'];
                    }
                }


                foreach ($everyday_fio as $value) {

                    $id_trip_by_fio = R::getCell('SELECT id FROM trip WHERE id_fio = ?  AND (( ? BETWEEN date1 and date2) or(?  >= date1 and date2 is NULL)) ', array($value['id'], $today,$today));

                    if (empty($id_trip_by_fio)) {//автоматически insert в командировкe в своем ПАСЧ
                        $trip = R::dispense('trip');
                        $trip->id_fio = $value['id'];
                        $trip->date1 = $today;
                        $trip->date2 = $today;
                        $trip->place = $place;
                        $trip->is_cosmr = 0;
                        $trip->last_update = date("Y-m-d H:i:s");
                        $trip->id_user = $_SESSION['uid'];
                        $trip->deregister = 0;
                        $trip->id_type=4;//авто
                        $trip->date_insert = date("Y-m-d H:i:s");
                        R::store($trip);


                        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Создание trip(из чужого ПАСЧ) - Данные:: ' . $trip);
                    }

                }

            }
        }
    }

    //те машины, которые заступают из других ПАСЧ - занести в командировку в своих ПАСЧ
    function setReserveToTripCar($id, $change, $today, $log) {
        $current_cardch = getIdCardCh($id, $change);
        //машины, которые надо отправить в командировку из своей ПАСЧ
        $reserve = R::getAll('SELECT * FROM  reservecar  WHERE id_card = :id AND (( :today BETWEEN date1 AND date2) or(:today  >= date1 and date2 is NULL) )', [':id' => $id, ':today' => $today]);

        if (!empty($reserve)) {

            $rosn = R::getCell('select concat(name," ", organ) as place from menurosn where id = :id', [':id' => $id]);

            //ROSN
            if (isset($rosn) && !empty($rosn)) {
                $place = $rosn;
            } else {
                //определить название ПАСЧ, в которую заступает сегодня машина, чтобы указать название ПАСЧ как место командировки
                $place = R::getCell('SELECT concat("(",d.name,"№",rec.divizion_num," смена ",c.ch,")") AS place  FROM cardch  AS c inner join ss.records AS rec ON c.id_card=rec.id'
                                . ' inner join ss.divizions AS d ON rec.id_divizion=d.id WHERE c.id = ?', array($current_cardch));


                 //определить название ГРОЧС, в которую заступает сегодня машина, чтобы указать название ПАСЧ как место командировки
                $place_locorg = R::getCell("SELECT (CASE WHEN (`org`.`id` = 8) THEN `org`.`name` WHEN (`org`.`id` = 7) THEN CONCAT(`org`.`name`,' №',`locor`.`no`,' ',REPLACE(`loc`.`name`,'ий','ого'),' ',`orgg`.`name`) "
                    . "  ELSE CONCAT(`loc`.`name`,' ',`org`.`name`) END) AS `place_locorg`"

." from cardch  AS c left join `ss`.`records` `r` ON c.id_card=r.id"
." LEFT JOIN `ss`.`divizions` `d` ON `r`.`id_divizion` = `d`.`id`"
." LEFT JOIN `ss`.`locorg` `locor` ON `r`.`id_loc_org` = `locor`.`id`"
." LEFT JOIN `ss`.`locals` `loc` ON `locor`.`id_local` = `loc`.`id`"
." LEFT JOIN `ss`.`regions` `reg` ON `loc`.`id_region` = `reg`.`id`"
." LEFT JOIN `ss`.`organs` `org` ON `locor`.`id_organ` = `org`.`id`"
." LEFT JOIN `ss`.`organs` `orgg` ON `locor`.`oforg` = `orgg`.`id`  WHERE c.id = ?", array($current_cardch));

                if(empty($place_locorg))
                    $place_locorg='';
            }

            foreach ($reserve as $value) {

                $id_trip_by_car = R::getCell('SELECT id FROM tripcar WHERE id_teh = ?  AND (( ? BETWEEN date1 and date2) or(?  >= date1 and date2 is NULL)) ', array($value['id_teh'], $today, $today));

                //родной id_card этой техники
                $native_id_card = R::getCell('SELECT r.id FROM ss.technics as t left join ss.records AS r ON t.id_record=r.id WHERE t.id = :id_teh', [':id_teh' => $value['id_teh']]);

                if (empty($id_trip_by_car)) {//автоматически insert в командировкe в своем ПАСЧ
                    $trip = R::dispense('tripcar');
                    $trip->id_teh = $value['id_teh'];
//                    $trip->date1 = $today;
//                    $trip->date2 = $today;
                    $trip->date1 = $value['date1'];// = дате, указанной в reservecar
                    $trip->date2 = $value['date2'];// = дате, указанной в reservecar
                    $trip->place = $place.chr(10).$place_locorg;
                    $trip->prikaz = $value['prikaz'];
                    $trip->id_card = $native_id_card;//откуда
                    $trip->ch = $change;
                    $trip->last_update = date("Y-m-d H:i:s");
                    $trip->id_user = $_SESSION['uid'];
                    $trip->deregister = 0;
                    $trip->is_auto_create=1;//индикатор того, что командировка создана автоматически
                    $trip->to_card=$native_id_card;//куда откомандировали
                    $trip->date_insert = date("Y-m-d H:i:s");
                    R::store($trip);
                    $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Создание tripcar(из чужого ПАСЧ) - Данные:: ' . $trip);
                }
            }
        }
    }

//ill
    $app->get('/:id/ch/:change/ill', function ($id, $change) use ($app) {//sheet ill for  change
        array($app, 'is_auth'); //авторизован ли пользователь
        auth($id); //просмотр карточек своего подразделения
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 1; //ill
        $today = date("Y-m-d");

                /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
             $duty = is_duty_cou($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty_cou($id, $change, 1); //открыт ли доступ на ред
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
             $duty = is_duty($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty($id, $change, 1); //открыт ли доступ на ред
        }

        $data['duty'] = $duty;

        $data['is_btn_confirm'] = is_btn_confirm($change);
        $data['duty_ch'] = duty_ch(); //номер деж смены

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $main = R::getAssoc("CALL get_main('{$id}','{$change}', 0);");
        if (isset($main) && !empty($main)) {
            foreach ($main as $value) {
                $dateduty = $value['dateduty'];
            }
        } else {
            $dateduty = $today;
        }
        $data['dateduty'] = $dateduty;

        $maim = R::getAll('SELECT * FROM maim '); //классификатор maim
        $data['maim'] = $maim;


//список доступных ФИО смены
        $data['listfio'] = getPresentAbsent($id, $change);

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/ill/ill.php', $data);

        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение
//выбор больных для change карточки на момент даты дежурства смены!!!
        $ill = R::getAll('SELECT i.id, i.id_fio,date_format(i.date1,"%d-%m-%Y") AS date1,date_format(i.date2,"%d-%m-%Y") AS date2,'
                        . ' i.diagnosis, i.deregister, date_format(i.date_insert,"%Y-%m-%d") AS date_insert,l.fio, i.maim, p.name as position_name, r.name as rank_name FROM ill AS i inner join listfio AS l '
                        . 'ON i.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id inner join maim AS ma ON i.maim=ma.id inner join position as p on p.id=l.id_position'
                . ' inner join rank as r on r.id=l.id_rank '
                        . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                        . 'AND ( (:today = date_format(i.date_insert,"%Y-%m-%d" )) or ( :today BETWEEN i.date1 and i.date2) or(:today  >= i.date1 and i.date2 is NULL)) OR i.deregister = :deregister ', [':id' => $id, ':ch' => $change, ':today' => $dateduty,':deregister'=>1]);

        if (isset($ill) && !empty($ill)) {//есть больные для вывода на экран, их можно ред*
            $data['tooltip'] = 1; //подсказка для ввода кол-ва больных
        } else {
            $data['tooltip'] = 0; //нет подсказки для ввода кол-ва больных
        }
//выводим форму с вводом количества больных
        $app->render('card/sheet/ill/formCountIll.php', $data);

        if (isset($ill) && !empty($ill)) {//есть больные для вывода на экран, их можно ред*
            //вывод
            $data['post'] = 0; //put data ill
            $data['ill'] = $ill;
            $app->render('card/sheet/ill/tableIll.php', $data); //view data
        }
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });
    /* ----------- форма заполнения больных -------------- */
    $app->post('/:id/ch/:change/ill', function ($id, $change) use ($app) {// form ill for fill
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['countill'] = $app->request()->post('countill');
        $data['sign'] = 1; //ill
        $duty = is_duty($id, $change, 0); //смена дежурная или нет
        $data['duty'] = $duty;
        $data['is_open_update'] = is_duty($id, $change, 1); //открыт ли доступ на ред
        $data['is_btn_confirm'] = is_btn_confirm($change);
        $maim = R::getAll('SELECT * FROM maim '); //классификатор maim
        $data['maim'] = $maim;

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

//список доступных ФИО смены
        $data['listfio'] = getPresentAbsent($id, $change);

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/ill/ill.php', $data);
        $app->render('card/sheet/ill/formFillIll.php', $data);
        $app->render('card/sheet/ill/backFormCount.php', $data);
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/:id/ch/:change/ill/save', function ($id, $change) use ($app) {//insert ill information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['countill'] = $app->request()->post('countill'); //кол-во больных для сохранения в БД
        $data['sign'] = 1; //ill
        $msg_ok = 0; //признак того, что запрос был выполнен успешно
        /*         * **********************  все подсчеты countstr выполнять при подтверждении данных!!!!! */

//insert
        for ($i = 1; $i <= $data['countill']; $i++) {

            $date1 = $app->request()->post('date1' . $i);
            if (empty($date1))
                $date1 = NULL;
            else
                $date1 = date("Y-m-d", strtotime($date1));
            $date2 = $app->request()->post('date2' . $i);
            if (empty($date2))
                $date2 = NULL;
            else
                $date2 = date("Y-m-d", strtotime($date2));

            // на учете или нет
//            if ((date("Y-m-d") >= $date1) && (date("Y-m-d") <= $date2)) {//да
//                $deregister = 0;
//            } else {//нет
//                $deregister = 1;
//            }
            $deregister = 0;
            /* если , будем хранить данные за месяц, а потом затираем, то расширится метод.
              если есть данные, подлежащие затиранию, то делаем update
              если нет - insert */

            //если не указана ФИО
            $id_fio = $app->request()->post('id_fio' . $i);
            if (empty($id_fio) || !isset($id_fio)) {
                continue;
            }

            if($date1 != NULL){
            $ill = R::dispense('ill');
            $ill->id_fio = $app->request()->post('id_fio' . $i);
            $ill->date1 = $date1;
            $ill->date2 = $date2;
            $ill->maim = $app->request()->post('maim' . $i);
            $ill->diagnosis = $app->request()->post('diagnosis' . $i);
            // $ill->dateduty = date("Y-m-d");
            $ill->last_update = date("Y-m-d H:i:s");
            $ill->id_user = $_SESSION['uid'];
            $ill->deregister = $deregister;
            $ill->date_insert = date("Y-m-d");
            R::store($ill);
            //если был выполнен запрос, то вывести сообщение
            $msg_ok = 1;
            }
        }

        if ($msg_ok == 1) {
            $_SESSION['msg'] = 1; //ok
        }

        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/ill');
    });

    $app->put('/:id/ch/:change/ill/save', function ($id, $change) use ($app, $log) {//update ill information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['countill'] = $app->request()->post('countill');
        $data['sign'] = 1; //ill
//update
        for ($i = 1; $i <= $data['countill']; $i++) {
            $id_ill = $app->request()->post('idill' . $i); //id of illstr
            $date1 = $app->request()->post('date1' . $i);
            if (empty($date1))
                $date1 = NULL;
            else
                $date1 = date("Y-m-d", strtotime($date1));
            $date2 = $app->request()->post('date2' . $i);
            if (empty($date2))
                $date2 = NULL;
            else
                $date2 = date("Y-m-d", strtotime($date2));

            // на учете или нет
//            if ((date("Y-m-d") >= $date1) && (date("Y-m-d") <= $date2)) {//да
//                $deregister = 0;
//            } else {//нет
//                $deregister = 1;
//            }
            $deregister = 0;

            //  $deregister = $app->request()->post('deregister' . $i);
            if($date1 != NULL){
                  $ill = R::load('ill', $id_ill);
            $ill->date1 = $date1;
            $ill->date2 = $date2;
            $ill->last_update = date("Y-m-d H:i:s");
            $ill->maim = $app->request()->post('maim' . $i);
            $ill->diagnosis = $app->request()->post('diagnosis' . $i);
            $ill->id_user = $_SESSION['uid'];
            $ill->deregister = $deregister;
            R::store($ill);

             $log_array = json_decode(R::load('ill', $id_ill));//что стало после рад
            $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Редактирование illstr - запись с id=' . $id_ill . '- Данные:: ' . json_encode($log_array,JSON_UNESCAPED_UNICODE));
            }

        }

        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/ill');
    });

    // предупреждение о  том, что пользоыватель будет удален из БД
    $app->get('/:id/ch/:change/ill/:idill', function ($id, $change, $idill) use ($app) {//msg delete ill by id
        array($app, 'is_auth'); //авторизован ли пользователь
        auth($id); //просмотр карточек своего подразделения
        $data = bread($id);

        $data['change'] = $change;
        $data['sign'] = 1; //ill
        $data['idill'] = $idill;


                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/ill/ill.php', $data);
        $app->render('msg/delete.php', $data); //delete user?
        $app->render('card/sheet/ill/delete.php', $data);
        $app->render('card/sheet/ill/backFormCount.php', $data);

        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->delete('/:id/ch/:change/ill/:idill', function ($id, $change, $idill) use ($app, $log) {//delete ill from DB by id
        array($app, 'is_auth'); //авторизован ли пользователь
        $ill = R::load('ill', $idill);

         $log_array = json_decode($ill);//что стало после рад
        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Удаление illstr - запись с id=' . $idill . '- Данные:: ' . json_encode($log_array,JSON_UNESCAPED_UNICODE));
        R::trash($ill); ///delete ill from DB

        $_SESSION['msg'] = 2; //ok_delete
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/ill');
    });

//holiday
    $app->get('/:id/ch/:change/holiday', function ($id, $change) use ($app) {//sheet holiday for  change
        array($app, 'is_auth'); //авторизован ли пользователь
        auth($id); //просмотр карточек своего подразделения
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 2; //holiday
        $today = date("Y-m-d");


                /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
             $duty = is_duty_cou($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty_cou($id, $change, 1); //открыт ли доступ на ред
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
             $duty = is_duty($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty($id, $change, 1); //открыт ли доступ на ред
        }
        $data['duty'] = $duty;

        $data['listfio'] = getPresentAbsent($id, $change); //список доступных ФИО смены
        $data['is_btn_confirm'] = is_btn_confirm($change); //кнопка "Подтвердить данные"
        $data['duty_ch'] = duty_ch(); //номер деж смены


                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $main = R::getAssoc("CALL get_main('{$id}','{$change}', 0);");
        if (isset($main) && !empty($main)) {
            foreach ($main as $value) {
                $dateduty = $value['dateduty'];
            }
        } else {
            $dateduty = $today;
        }
        $data['dateduty'] = $dateduty; //дата дежурства, на которую надо вывести инф о работниках

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/holiday/holiday.php', $data);

        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение
//выбор holiday для change карточки with deregister=0(на учете)
        $holiday = R::getAll('SELECT h.id, h.id_fio,date_format(h.date1,"%d-%m-%Y") AS date1,date_format(h.date2,"%d-%m-%Y") AS date2,'
                        . ' h.prikaz, h.deregister,date_format(h.date_insert,"%Y-%m-%d") AS date_insert, l.fio, p.name as position_name, r.name as rank_name FROM holiday AS h '
                        . 'inner join listfio AS l ON h.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id inner join position as p on p.id=l.id_position'
                . ' inner join rank as r on r.id=l.id_rank '
                        . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                        . ' AND  ( ( (:today = date_format(h.date_insert,"%Y-%m-%d" )) or ( :today BETWEEN h.date1 and h.date2) or(:today  >= h.date1 and h.date2 is NULL))'
                . ' OR h.deregister = :deregister )', [':id' => $id, ':ch' => $change, ':today' => $dateduty,':deregister'=>1]);

        if (isset($holiday) && !empty($holiday)) {//есть отпуска для вывода на экран, их можно ред*
            $data['tooltip'] = 1; //подсказка для ввода кол-ва отпускников
        } else {
            $data['tooltip'] = 0; //нет подсказки для ввода кол-ва отпускников
        }
//выводим форму с вводом количества отпускников
        $app->render('card/sheet/holiday/formCountHol.php', $data);

        if (isset($holiday) && !empty($holiday)) {//есть отпуска для вывода на экран, их можно ред*
            //вывод
            $data['post'] = 0; //put data ill
            $data['holiday'] = $holiday;
            $app->render('card/sheet/holiday/tableHol.php', $data); //view data
        }
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/:id/ch/:change/holiday', function ($id, $change) use ($app) {//form for fill holiday for  change
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['counthol'] = $app->request()->post('counthol');
        $data['sign'] = 2; //holiday
        $data['listfio'] = getPresentAbsent($id, $change); //список доступных ФИО смены

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/holiday/holiday.php', $data);
        $app->render('card/sheet/holiday/formFillHol.php', $data);
        $app->render('card/sheet/holiday/backFormCount.php', $data);
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/:id/ch/:change/holiday/save', function ($id, $change) use ($app) {//insert holiday information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['counthol'] = $app->request()->post('counthol');
        $data['sign'] = 2; //holiday
        $msg_ok = 0; //признак того, что запрос был выполнен успешно
//insert
        for ($i = 1; $i <= $data['counthol']; $i++) {

            $date1 = $app->request()->post('date1' . $i);
            if (empty($date1))
                $date1 = NULL;
            else
                $date1 = date("Y-m-d", strtotime($date1));
            $date2 = $app->request()->post('date2' . $i);
            if (empty($date2))
                $date2 = NULL;
            else
                $date2 = date("Y-m-d", strtotime($date2));

            // на учете или нет
//            if ((date("Y-m-d") >= $date1) && (date("Y-m-d") <= $date2)) {//да
//                $deregister = 0;
//            } else {//нет
//                $deregister = 1;
//            }
            $deregister = 0;

            /* если , будем хранить данные за месяц, а потом затираем, то расширится метод.
              если есть данные, подлежащие затиранию, то делаем update
              если нет - insert */

                   //если не указана ФИО
            $id_fio = $app->request()->post('id_fio' . $i);
            if (empty($id_fio) || !isset($id_fio)) {
                continue;
            }
if($date1 != NULL){
          $hol = R::dispense('holiday');
            $hol->id_fio = $app->request()->post('id_fio' . $i);
            $hol->prikaz = $app->request()->post('prikaz' . $i);
            $hol->date1 = $date1;
            $hol->date2 = $date2;
            //$hol->dateduty = date("Y-m-d");
            $hol->last_update = date("Y-m-d H:i:s");
            $hol->id_user = $_SESSION['uid'];
            $hol->deregister = $deregister;
            $hol->date_insert = date("Y-m-d H:i:s");
            R::store($hol);
        //если был выполнен запрос, то вывести сообщение
            $msg_ok = 1;
}

        }

        if ($msg_ok == 1) {
            $_SESSION['msg'] = 1; //ok
        }
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/holiday');
    });

    $app->put('/:id/ch/:change/holiday/save', function ($id, $change) use ($app, $log) {//update holiday information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['counthol'] = $app->request()->post('counthol');
        $data['sign'] = 2; //holiday
//insert
        for ($i = 1; $i <= $data['counthol']; $i++) {
            $id_hol = $app->request()->post('idhol' . $i); //id of illstr
            $date1 = $app->request()->post('date1' . $i);
            if (empty($date1))
                $date1 = NULL;
            else
                $date1 = date("Y-m-d", strtotime($date1));
            $date2 = $app->request()->post('date2' . $i);
            if (empty($date2))
                $date2 = NULL;
            else
                $date2 = date("Y-m-d", strtotime($date2));

            // на учете или нет
//            if ((date("Y-m-d") >= $date1) && (date("Y-m-d") <= $date2)) {//да
//                $deregister = 0;
//            } else {//нет
//                $deregister = 1;
//            }
          //  $deregister = 0;
if($date1 != NULL){
    $hol = R::load('holiday', $id_hol);
            $hol->prikaz = $app->request()->post('prikaz' . $i);
            $hol->date1 = $date1;
            $hol->date2 = $date2;
            //$hol->deregister = $deregister;
            $hol->last_update = date("Y-m-d H:i:s");
            $hol->id_user = $_SESSION['uid'];
            R::store($hol);
            $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Редактирование holidaystr - запись с id=' . $id_hol . '- Данные:: ' . $hol);
}

        }

        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/holiday');
    });

    $app->get('/:id/ch/:change/holiday/:idhol', function ($id, $change, $idhol) use ($app) {//msg delete hol by id
        array($app, 'is_auth'); //авторизован ли пользователь
        auth($id); //просмотр карточек своего подразделения
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 2; //holiday
        $data['idhol'] = $idhol;

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/holiday/holiday.php', $data);
        $app->render('msg/delete.php', $data);
        $app->render('card/sheet/holiday/delete.php', $data);
        $app->render('card/sheet/holiday/backFormCount.php', $data);

        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->delete('/:id/ch/:change/holiday/:idhol', function ($id, $change, $idhol) use ($app, $log) {//delete hol from DB by id
        array($app, 'is_auth'); //авторизован ли пользователь
        $hol = R::load('holiday', $idhol);
        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Удаление holidaystr - запись с id=' . $idhol . '- Данные:: ' . $hol);

        R::trash($hol); ///delete ill from DB

        $_SESSION['msg'] = 2; //ok_delete
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/holiday');
    });

    // предупреждение снять с учета
    $app->get('/:id/ch/:change/holiday/deregister/:idhol', function ($id, $change, $idhol) use ($app) {
        array($app, 'is_auth'); //авторизован ли пользователь
        auth($id); //просмотр карточек своего подразделения
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 2; //holiday
        $data['idhol'] = $idhol;

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/holiday/holiday.php', $data);
        $app->render('card/sheet/holiday/deregister/deregister_msg.php', $data);
        $app->render('card/sheet/holiday/deregister/deregister_btn.php', $data);
        $app->render('card/sheet/holiday/backFormCount.php', $data);

        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    //снять с учета
    $app->put('/:id/ch/:change/holiday/deregister/:idhol', function ($id, $change,$id_hol) use ($app, $log) {//update

        array($app, 'is_auth'); //авторизован ли пользователь

            $hol = R::load('holiday', $id_hol);
            $hol->deregister = 0;
            $hol->last_update = date("Y-m-d H:i:s");
            $hol->id_user = $_SESSION['uid'];
            R::store($hol);
            $log->info('Сессия - ' . $_SESSION['uid'] . ' :: снят с учета holidaystr - запись с id=' . $id_hol . '- Старые Данные:: ' . $hol);

        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/holiday');

    });



//trip
    $app->get('/:id/ch/:change/trip', function ($id, $change) use ($app) {//sheet trip for  change
        array($app, 'is_auth'); //авторизован ли пользователь
        auth($id); //просмотр карточек своего подразделения
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 3; //trip
        $today = date("Y-m-d");


                /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
             $duty = is_duty_cou($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty_cou($id, $change, 1); //открыт ли доступ на ред
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
             $duty = is_duty($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty($id, $change, 1); //открыт ли доступ на ред
        }


        $data['duty'] = $duty;

        $data['is_btn_confirm'] = is_btn_confirm($change); //кнопка "Подтвердить данные"

        $data['listfio'] = getPresentAbsentTrip($id, $change); //список ФИО смены - классификатор

        $data['duty_ch'] = duty_ch(); //номер деж смены


                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

          $data['type_trip']=R::getAll('select * from typetrip where id <> ?',array(4));
            $data['vid_document']=R::getAll('select * from str.viddocument ');//вид документы
            $data['vid_position']=R::getAll('select * from str.vidposition ');//должность, чей приказ

        $main = R::getAssoc("CALL get_main('{$id}','{$change}', 0);");
        if (isset($main) && !empty($main)) {
            foreach ($main as $value) {
                $dateduty = $value['dateduty'];
            }
        } else {
            $dateduty = $today;
        }
        $data['dateduty'] = $dateduty; //дата дежурства, на которую надо вывести инф о работниках

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/trip/trip.php', $data);

        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение
//выбор командировок для change карточки with deregister=0(на учете)
        $trip = R::getAll('SELECT t.id, t.id_fio,date_format(t.date1,"%d-%m-%Y") AS date1,date_format(t.date2,"%d-%m-%Y") AS date2,'
                        . ' t.place,t.is_cosmr, t.prikaz, t.deregister,date_format(t.date_insert,"%Y-%m-%d") AS date_insert, t.id_viddocument, t.id_vidposition, t.prikaz_date, t.prikaz_number,'
                . ' l.fio,'
                . ' p.name as position_name, r.name as rank_name, tt.name as type_trip, tt.id as id_type,t.note FROM trip AS t '
                        . 'inner join listfio AS l ON t.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id inner join position as p on p.id=l.id_position'
                . ' inner join rank as r on r.id=l.id_rank inner join typetrip as tt on tt.id=t.id_type '
                        . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                        . ' AND ( (:today = date_format(t.date_insert,"%Y-%m-%d" )) or ( :today BETWEEN t.date1 and t.date2) or(:today  >= t.date1 and t.date2 is NULL)) ', [':id' => $id, ':ch' => $change, ':today' => $dateduty]);

        if (isset($trip) && !empty($trip)) {//есть командировки для вывода на экран, их можно ред*
            $data['tooltip'] = 1; //подсказка для ввода кол-ва командировок
        } else {
            $data['tooltip'] = 0; //нет подсказки для ввода кол-ва  командировок
        }
//выводим форму с вводом количества командировок
        $app->render('card/sheet/trip/formCountTrip.php', $data);

        if (isset($trip) && !empty($trip)) {//есть командировки для вывода на экран, их можно ред*
            //вывод
            $data['trip'] = $trip;
            $app->render('card/sheet/trip/tableTrip.php', $data); //view data
        }
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/:id/ch/:change/trip', function ($id, $change) use ($app) {//form trip for fill for  change
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['counttrip'] = $app->request()->post('counttrip');
        $data['sign'] = 3; //trip

        /* ------- для развертывания меню ------ */
        $data['grochs_active'] = get_id_grochs($id);
        $data['region_active'] = get_id_region($id);
        $data['pasp_active'] = $id;
        $data['organ_active'] = R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /* ------- END для развертывания меню ------ */

        $data['listfio'] = getPresentAbsentTrip($id, $change); //список ФИО смены - классификатор
        $data['type_trip'] = R::getAll('select * from typetrip where id <> ?', array(4));


        $data['vid_document'] = R::getAll('select * from str.viddocument '); //вид документы
        $data['vid_position'] = R::getAll('select * from str.vidposition '); //должность, чей приказ

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/holiday/holiday.php', $data);
        $app->render('card/sheet/trip/formFillTrip.php', $data);
        $app->render('card/sheet/trip/backFormCount.php', $data);
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/:id/ch/:change/trip/save', function ($id, $change) use ($app) {//insert trip information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['counttrip'] = $app->request()->post('counttrip');
        $data['sign'] = 3; //trip
        $msg_ok = 0; //признак того, что запрос был выполнен успешно
//insert
        for ($i = 1; $i <= $data['counttrip']; $i++) {

            $date1 = $app->request()->post('date1' . $i);
            if (empty($date1))
                $date1 = NULL;
            else
                $date1 = date("Y-m-d", strtotime($date1));
            $date2 = $app->request()->post('date2' . $i);
            if (empty($date2))
                $date2 = NULL;
            else
                $date2 = date("Y-m-d", strtotime($date2));

            $is_cosmr = $app->request()->post('is_cosmr' . $i);
            $is_cosmr = ($is_cosmr == 1) ? $is_cosmr : 0;

            $deregister = 0;

            /* ---- Основание командировки - соединить поля --- */
            $id_vid_doc = $app->request()->post('id_viddocument' . $i);
            $vid_doc_name = R::getCell('SELECT name FROM viddocument WHERE id = ?', array($id_vid_doc)); //приказ

            $id_vid_position = $app->request()->post('id_vidposition' . $i);
            $vid_pos_name = R::getCell('SELECT name FROM vidposition WHERE id = ?', array($id_vid_position)); //начальника РОЧС

            $prikaz_date = $app->request()->post('prikaz_date' . $i); //от даты

            $prikaz_number = $app->request()->post('prikaz_number' . $i); //№ номер

            $prikaz = $vid_doc_name . ' ' . $vid_pos_name . ' от ' . $prikaz_date . ' № ' . $prikaz_number;



            /* ---- Основание командировки - соединить поля --- */


            $trip = R::dispense('trip');
            /* если , будем хранить данные за месяц, а потом затираем, то расширится метод.
              если есть данные, подлежащие затиранию, то делаем update
              если нет - insert */

                    //если не указана ФИО
            $id_fio = $app->request()->post('id_fio' . $i);
            if (empty($id_fio) || !isset($id_fio)) {
                continue;
            }
            if($date1 != NULL){
                  $trip->id_fio = $app->request()->post('id_fio' . $i);
            $trip->prikaz = $prikaz;

            $trip->id_viddocument=$id_vid_doc;
            $trip->id_vidposition=$id_vid_position;
            $trip->prikaz_date=$prikaz_date;
            $trip->prikaz_number=$prikaz_number;

            $trip->date1 = $date1;
            $trip->date2 = $date2;
            $trip->place = $app->request()->post('place' . $i);
            $trip->is_cosmr = $is_cosmr;
            $trip->last_update = date("Y-m-d H:i:s");
            $trip->id_user = $_SESSION['uid'];
            $trip->deregister = $deregister;
            $trip->id_type=$app->request()->post('id_type' . $i);
            $trip->note=$app->request()->post('note' . $i);
            $trip->date_insert = date("Y-m-d H:i:s");
            R::store($trip);
            //если был выполнен запрос, то вывести сообщение
            $msg_ok = 1;
            }


        }

        if ($msg_ok == 1) {
            $_SESSION['msg'] = 1; //ok
        }
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/trip');
    });


    $app->put('/:id/ch/:change/trip/save', function ($id, $change) use ($app, $log) {//update trip information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['counttrip'] = $app->request()->post('counttrip');
        $data['sign'] = 3; //trip
//put
        for ($i = 1; $i <= $data['counttrip']; $i++) {
            $id_trip = $app->request()->post('idtrip' . $i); //id of tripstr
            $date1 = $app->request()->post('date1' . $i);
            if (empty($date1))
                $date1 = NULL;
            else
                $date1 = date("Y-m-d", strtotime($date1));
            $date2 = $app->request()->post('date2' . $i);
            if (empty($date2))
                $date2 = NULL;
            else
                $date2 = date("Y-m-d", strtotime($date2));

            $is_cosmr = $app->request()->post('is_cosmr' . $i);
            $is_cosmr = ($is_cosmr == 1) ? $is_cosmr : 0;

            $deregister = 0;

               /* ---- Основание командировки - соединить поля --- */
            $id_vid_doc = $app->request()->post('id_viddocument' . $i);
            $vid_doc_name = R::getCell('SELECT name FROM viddocument WHERE id = ?', array($id_vid_doc)); //приказ

            $id_vid_position = $app->request()->post('id_vidposition' . $i);
            $vid_pos_name = R::getCell('SELECT name FROM vidposition WHERE id = ?', array($id_vid_position)); //начальника РОЧС

            $prikaz_date = $app->request()->post('prikaz_date' . $i); //от даты

            $prikaz_number = $app->request()->post('prikaz_number' . $i); //№ номер

            $prikaz = $vid_doc_name . ' ' . $vid_pos_name . ' от ' . $prikaz_date . ' № ' . $prikaz_number;



            /* ---- Основание командировки - соединить поля --- */

            if ($date1 != NULL) {
                $trip = R::load('trip', $id_trip);

                $trip->prikaz = $prikaz;

                $trip->id_viddocument = $id_vid_doc;
                $trip->id_vidposition = $id_vid_position;
                $trip->prikaz_date = $prikaz_date;
                $trip->prikaz_number = $prikaz_number;

                $trip->date1 = $date1;
                $trip->date2 = $date2;
                $trip->place = $app->request()->post('place' . $i);
                $trip->is_cosmr = $is_cosmr;
                $trip->deregister = $deregister;
                $trip->last_update = date("Y-m-d H:i:s");
                $trip->id_type = $app->request()->post('id_type' . $i);
                $trip->note = $app->request()->post('note' . $i);
                $trip->id_user = $_SESSION['uid'];
                R::store($trip);
                $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Редактирование tripstr - запись с id=' . $id_trip . '- Данные:: ' . $trip);
            }
        }
        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/trip');
    });

    $app->get('/:id/ch/:change/trip/:idtrip', function ($id, $change, $idtrip) use ($app) {//msg delete trip by id
        array($app, 'is_auth'); //авторизован ли пользователь
        auth($id); //просмотр карточек своего подразделения
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 3; //trip
        $data['idtrip'] = $idtrip;

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/trip/trip.php', $data);
        $app->render('msg/delete.php', $data);
        $app->render('card/sheet/trip/delete.php', $data);
        $app->render('card/sheet/trip/backFormCount.php', $data);
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->delete('/:id/ch/:change/trip/:idtrip', function ($id, $change, $idtrip) use ($app, $log) {//delete trip from DB by id
        array($app, 'is_auth'); //авторизован ли пользователь
        $trip = R::load('trip', $idtrip);
        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Удаление tripstr - запись с id=' . $idtrip . '- Данные:: ' . $trip);
        R::trash($trip); ///delete trip from DB

        $_SESSION['msg'] = 2; //ok_delete
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/trip');
    });


//other reasons
    $app->get('/:id/ch/:change/other', function ($id, $change) use ($app) {//sheet other reasons for  change
        array($app, 'is_auth'); //авторизован ли пользователь
        auth($id); //просмотр карточек своего подразделения
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 4; //other reasons
        $today = date("Y-m-d");


                /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
             $duty = is_duty_cou($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty_cou($id, $change, 1); //открыт ли доступ на ред
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
             $duty = is_duty($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty($id, $change, 1); //открыт ли доступ на ред
        }

        $data['duty'] = $duty;

        $data['is_btn_confirm'] = is_btn_confirm($change); //кнопка "Подтвердить данные"
        $data['listfio'] = getPresentAbsent($id, $change); //список доступных ФИО смены
        $data['duty_ch'] = duty_ch(); //номер деж смены

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $main = R::getAssoc("CALL get_main('{$id}','{$change}', 0);");
        if (isset($main) && !empty($main)) {
            foreach ($main as $value) {
                $dateduty = $value['dateduty'];
            }
        } else {
            $dateduty = $today;
        }
        $data['dateduty'] = $dateduty; //дата дежурства, на которую надо вывести инф о работниках

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/other/other.php', $data);

        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение
//выбор other для change карточки with deregister=0(на учете)
        $other = R::getAll('SELECT o.id, o.id_fio,date_format(o.date1,"%d-%m-%Y") AS date1, date_format(o.date2,"%d-%m-%Y") AS date2,'
                        . ' o.reason, o.note, date_format(o.date_insert,"%Y-%m-%d") AS date_insert,l.fio, p.name as position_name, r.name as rank_name FROM other AS o inner join listfio AS l '
                        . 'ON o.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id inner join position as p on p.id=l.id_position'
                . ' inner join rank as r on r.id=l.id_rank '
                        . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                        . ' AND ( (:today = date_format(o.date_insert,"%Y-%m-%d" )) or ( :today BETWEEN o.date1 and o.date2) or(:today  >= o.date1 and o.date2 is NULL))', [':id' => $id, ':ch' => $change, ':today' => $dateduty]);

        if (isset($other) && !empty($other)) {//есть other для вывода на экран, их можно ред*
            $data['tooltip'] = 1; //подсказка для ввода кол-ва other
        } else {
            $data['tooltip'] = 0; //нет подсказки для ввода кол-ва other
        }
//выводим форму с вводом количества other
        $app->render('card/sheet/other/formCountOther.php', $data);

        if (isset($other) && !empty($other)) {//есть other для вывода на экран, их можно ред*
            //вывод
            $data['other'] = $other;
            $app->render('card/sheet/other/tableOther.php', $data); //view data
        }
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/:id/ch/:change/other', function ($id, $change) use ($app) {//sheet other reasons for  change
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['countother'] = $app->request()->post('countother');
        $data['sign'] = 4; //other reasons

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $data['listfio'] = getPresentAbsent($id, $change); //список доступных ФИО смены
        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/other/other.php', $data);
        $app->render('card/sheet/other/formFillOther.php', $data);
        $app->render('card/sheet/other/backFormCount.php', $data);
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/:id/ch/:change/other/save', function ($id, $change) use ($app) {//insert other information
        array($app, 'is_auth'); //авторизован ли пользователь$data = bread($id);
        $data['change'] = $change;
        $data['countother'] = $app->request()->post('countother');
        $data['sign'] = 4; //other reasons
        $msg_ok = 0; //признак того, что запрос был выполнен успешно
//insert
        for ($i = 1; $i <= $data['countother']; $i++) {

            $date1 = $app->request()->post('date1' . $i);
            if (empty($date1))
                $date1 = NULL;
            else
                $date1 = date("Y-m-d", strtotime($date1));
            $date2 = $app->request()->post('date2' . $i);
            if (empty($date2))
                $date2 = NULL;
            else
                $date2 = date("Y-m-d", strtotime($date2));

            // на учете или нет
//            if ((date("Y-m-d") >= $date1) && (date("Y-m-d") <= $date2)) {//да
//                $deregister = 0;
//            } else {//нет
//                $deregister = 1;
//            }
            $deregister = 0;

            $other = R::dispense('other');
            /* если , будем хранить данные за месяц, а потом затираем, то расширится метод.
              если есть данные, подлежащие затиранию, то делаем update
              если нет - insert */

                    //если не указана ФИО
            $id_fio = $app->request()->post('id_fio' . $i);
            if (empty($id_fio) || !isset($id_fio)) {
                continue;
            }
            if($date1 != NULL){
                    $other->id_fio = $app->request()->post('id_fio' . $i);
            $other->date1 = $date1;
            $other->date2 = $date2;
            $other->reason = $app->request()->post('reasonother' . $i);
            $other->note = $app->request()->post('noteother' . $i);
            $other->dateduty = date("Y-m-d");
            $other->last_update = date("Y-m-d H:i:s");
            $other->id_user = $_SESSION['uid'];
            $other->deregister = $deregister;
            $other->date_insert = date("Y-m-d H:i:s");
            R::store($other);
        //если был выполнен запрос, то вывести сообщение
            $msg_ok = 1;
            }

        }

        if ($msg_ok == 1) {
            $_SESSION['msg'] = 1; //ok
        }
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/other');
    });

    $app->put('/:id/ch/:change/other/save', function ($id, $change) use ($app, $log) {//update other information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['countother'] = $app->request()->post('countother');
        $data['sign'] = 4; //other reasons
//put
        for ($i = 1; $i <= $data['countother']; $i++) {
            $id_other = $app->request()->post('idother' . $i); //id of otherstr
            $date1 = $app->request()->post('date1' . $i);
            if (empty($date1))
                $date1 = NULL;
            else
                $date1 = date("Y-m-d", strtotime($date1));
            $date2 = $app->request()->post('date2' . $i);
            if (empty($date2))
                $date2 = NULL;
            else
                $date2 = date("Y-m-d", strtotime($date2));

            // на учете или нет
//            if ((date("Y-m-d") >= $date1) && (date("Y-m-d") <= $date2)) {//да
//                $deregister = 0;
//            } else {//нет
//                $deregister = 1;
//            }
            $deregister = 0;

            //$deregister = $app->request()->post('deregister' . $i);
            if($date1 != NULL){
                         $other = R::load('other', $id_other);
            $other->date1 = $date1;
            $other->date2 = $date2;
            $other->note = $app->request()->post('noteother' . $i);
            $other->reason = $app->request()->post('reasonother' . $i);
            $other->last_update = date("Y-m-d H:i:s");
            $other->deregister = $deregister;
            $other->id_user = $_SESSION['uid'];
            R::store($other);
            $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Редактирование otherstr - запись с id=' . $id_other . '- Данные:: ' . $other);
            }

        }
        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/other');
    });

    $app->get('/:id/ch/:change/other/:idother', function ($id, $change, $idother) use ($app) {//msg delete other by id
        array($app, 'is_auth'); //авторизован ли пользователь
        auth($id); //просмотр карточек своего подразделения
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 4; //other reasons
        $data['idother'] = $idother;

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/other/other.php', $data);
        $app->render('msg/delete.php', $data);
        $app->render('card/sheet/other/delete.php', $data);
        $app->render('card/sheet/other/backFormCount.php', $data);

        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->delete('/:id/ch/:change/other/:idother', function ($id, $change, $idother) use ($app, $log) {//delete other from DB by id
        array($app, 'is_auth'); //авторизован ли пользователь
        $other = R::load('other', $idother);
        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Удаление other - запись с id=' . $idother . '- Данные:: ' . $other);

        R::trash($other); ///delete other from DB

        $_SESSION['msg'] = 2; //ok_delete
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/other');
    });


//car

    function getTypeTeh() {
        $type[1]='боевая';
        $type[2]='резерв';
        $type[3]='ТО-1';
        $type[4]='ТО-2';
        $type[5]='ремонт';
        return $type;
    }


    $app->post('/:id/ch/:change/car', function ($id, $change) use ($app) {//insert car information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['countcar'] = $app->request()->post('countcar');
        $data['sign'] = 6; //car
        //
         //проверяем, нет ли повтора ФИО-1чел на 1 машину
        for ($i = 1; $i <= $data['countcar']; $i++) {
            $fio = $app->request()->post('fio' . $i);
            if (!empty($fio)) {
                foreach ($fio as $key => $value) {
                    $array_of_fio[] = $value;
                }
                $count_value = array_count_values($array_of_fio);
            }
        }

        if (max($count_value) > 1) {

                    /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

            $app->render('layouts/header.php');
            $app->render('layouts/menu.php');
            $app->render('msg/modal.php', $data);
            $app->render('layouts/footer.php');
        } else {
            //insert
            for ($i = 1; $i <= $data['countcar']; $i++) {
                $fio = $app->request()->post('fio' . $i); //array

                                /*-------- определение типа техники ---------*/
                $type=$app->request()->post('type' . $i);//тип техники
                if ($type == 1 || $type == 2) {//боевая, резерв
                    $id_type = $type;
                    $id_to = 3;
                    $is_repaire = 0;
                } elseif ($type == 3) {//to-1
                    $id_type = 3;
                    $id_to = 1;
                    $is_repaire = 0;
                } elseif ($type == 4) {//to-2
                    $id_type = 3;
                    $id_to = 2;
                    $is_repaire = 0;
                } elseif ($type == 5) {//ремонт
                    $id_type = 3;
                    $id_to = 3;
                    $is_repaire = 1;
                }
    /*-------- END определение типа техники ---------*/


                /*----------- если ремонт - заполнить нужные поля --------------*/

                if ($type == 5) {//ремонт
                    $id_reason_repaire = $app->request()->post('reason_repaire' . $i);
                    $date1 = $app->request()->post('date1' . $i);
                    $date2 = $app->request()->post('date2' . $i);
                    $start_repaire = (!isset($date1) || empty($date1) ) ? NULL :   date("Y-m-d", strtotime($date1));
                    $end_repaire = (!isset($date2) || empty($date2) ) ? NULL :  date("Y-m-d", strtotime($date2));
                } else {
                    $id_reason_repaire = 1; //нет неисправности
                    $start_repaire = NULL;
                    $end_repaire = NULL;
                }

                /*----------- END если ремонт - заполнить нужные поля --------------*/

                $teh = R::dispense('tehstr');
                $teh->id_teh = $app->request()->post('idcar' . $i);
                $teh->ch = $change;
                $teh->dateduty = date("Y-m-d");
                $teh->last_update = date("Y-m-d H:i:s");
                $teh->id_user = $_SESSION['uid'];
                $teh->numbsign = $app->request()->post('numbsign' . $i);
                $teh->petrol = $app->request()->post('petrol' . $i);
                $teh->powder = $app->request()->post('powder' . $i);
                $teh->diesel = $app->request()->post('diesel' . $i);
                $teh->foam = $app->request()->post('foam' . $i);
                $teh->id_to = $id_to;
                $teh->is_repair = $is_repaire;
                $teh->id_type = $id_type;
                $teh->comments = $app->request()->post('comments' . $i);

                $teh->id_reason_repaire=$id_reason_repaire;
                $teh->start_repaire=$start_repaire;
                $teh->end_repaire=$end_repaire;
                $id_car = R::store($teh);

                //добавить ФИО на технику
                setFioTeh($i, $id_car, $fio);
            }

            $_SESSION['msg'] = 1; //ok
            $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/car');
        }
    });

    $app->put('/:id/ch/:change/car/', function ($id, $change) use ($app, $log) {//update car information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $duty = is_duty($id, $change, 0); //смена дежурная или нет
        $data['duty'] = $duty;
        $data['change'] = $change;
        $data['is_open_update'] = is_duty($id, $change, 1); //открыт ли доступ на ред
        $data['countcar'] = $app->request()->post('countcar');

        $data['sign'] = 6; //car
        //проверяем, нет ли повтора ФИО-1чел на 1 машину
        for ($i = 1; $i <= $data['countcar']; $i++) {
            $fio = $app->request()->post('fio' . $i);
            if (!empty($fio)) {
                foreach ($fio as $key => $value) {
                    $array_of_fio[] = $value;
                }
                $count_value = array_count_values($array_of_fio);
            }
        }

        if (isset($count_value) && !empty($count_value) && max($count_value) > 1) {
            $app->render('layouts/header.php');
            $app->render('layouts/menu.php');
            $app->render('msg/modal.php', $data);
            $app->render('layouts/footer.php');
        } else {//save
//insert
            for ($i = 1; $i <= $data['countcar']; $i++) {
                $id_car = $app->request()->post('idcar' . $i); //id of car
                $fio = $app->request()->post('fio' . $i); //array of fio on car

                /*-------- определение типа техники ---------*/
                $type=$app->request()->post('type' . $i);//тип техники
                if ($type == 1 || $type == 2) {//боевая, резерв
                    $id_type = $type;
                    $id_to = 3;
                    $is_repaire = 0;
                } elseif ($type == 3) {//to-1
                    $id_type = 3;
                    $id_to = 1;
                    $is_repaire = 0;
                } elseif ($type == 4) {//to-2
                    $id_type = 3;
                    $id_to = 2;
                    $is_repaire = 0;
                } elseif ($type == 5) {//ремонт
                    $id_type = 3;
                    $id_to = 3;
                    $is_repaire = 1;
                }
    /*-------- END определение типа техники ---------*/


                /*----------- если ремонт - заполнить нужные поля --------------*/

                if ($type == 5) {//ремонт
                    $id_reason_repaire = $app->request()->post('reason_repaire' . $i);
                    $date1 = $app->request()->post('date1' . $i);
                    $date2 = $app->request()->post('date2' . $i);
                    $start_repaire = (!isset($date1) || empty($date1) ) ? NULL :   date("Y-m-d", strtotime($date1));
                    $end_repaire = (!isset($date2) || empty($date2) ) ? NULL :  date("Y-m-d", strtotime($date2));
                } else {
                    $id_reason_repaire = 1; //нет неисправности
                    $start_repaire = NULL;
                    $end_repaire = NULL;
                }

                /*----------- END если ремонт - заполнить нужные поля --------------*/

                $teh = R::load('car', $id_car);
                $teh->last_update = date("Y-m-d H:i:s");
                $teh->id_user = $_SESSION['uid'];
                $teh->petrol = $app->request()->post('petrol' . $i);
                $teh->powder = $app->request()->post('powder' . $i);
                $teh->diesel = $app->request()->post('diesel' . $i);
                $teh->foam = $app->request()->post('foam' . $i);
                $teh->id_to = $id_to;
                $teh->is_repair = $is_repaire;
                $teh->id_type = $id_type;
                $teh->comments = $app->request()->post('comments' . $i);
                $teh->dateduty = date("Y-m-d");
                $teh->is_new = 0;

                $teh->id_reason_repaire=$id_reason_repaire;
                $teh->start_repaire=$start_repaire;
                $teh->end_repaire=$end_repaire;

                R::store($teh);

                /*  установить ФИО на технику  */
                setFioTeh($id_car, $fio);


                $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Редактирование car - запись с id=' . $id_car . '- Данные:: ' . $teh);
            }

            $_SESSION['msg'] = 1; //ok
            $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/car');
        }
    });

    /*  +++++++++++ техника в командировке +++++++++++++ */
    $app->get('/:id/ch/:change/car/trip', function ($id, $change) use ($app) {
        array($app, 'is_auth');
        auth($id);
        $data = bread($id);
        $data['change'] = $change;
        $data['id_card'] = $id;
        $data['sign'] = 6; //car
        $today = date("Y-m-d");


                /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
             $duty = is_duty_cou($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty_cou($id, $change, 1); //открыт ли доступ на ред
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
             $duty = is_duty($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty($id, $change, 1); //открыт ли доступ на ред
        }

        $data['duty'] = $duty;


                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        /*         * **************************  кнопка "Подтвердить данные" ************************************ */
        //кнопка "Подтвердить данные"
        $data['is_btn_confirm'] = is_btn_confirm($change);

        /*         * * определить dateduty ** */
        $main = R::getAssoc("CALL get_main('{$id}','{$change}', '0');");
        if (isset($main) && !empty($main)) {
            foreach ($main as $value) {
                $dateduty = $value['dateduty'];
            }
        } else {
            $dateduty = $today;
        }
        $data['dateduty'] = $dateduty;
        /*         * * Список техники, которую можно отправить в командировку ** */
        $data['list_car_for_trip'] = getListCarForTrip($id, $today);

        /*         * * выбор техники в командировке на dateduty ** */
        $data['tripcar'] = R::getAll('select * from tripcar  where id_card = :id_card and (( :date BETWEEN date1 and date2) or( :date  >= date1 and date2 is NULL)) ', [':id_card' => $id, ':date' => $dateduty]);
        // mark техники
        $data['list_car'] = getNameTehForTripCar(getTeh($id, 1));


        /* адрес, КУДА отправляем в командировку */

         //классификатор областей
        $data['regions'] = R::getAll('select * from ss.regions');
        //классификатор Г(Р)ОЧС, кроме РОСН
        $grochs = R::getAll('select organ as name, id_card as id_grochs, region as id_region  from ss.maintable where orgid <> :id_org_rosn', [':id_org_rosn' => 8]);
        $grochs_rosn = R::getAll('select concat(l.name," ",o.name) as name, locor.id as id_grochs, re.id as id_region  from '
                        . ' ss.locorg as locor left join ss.organs as o on locor.id_organ=o.id left join ss.locals as l on l.id=locor.id_local '
                        . ' left join ss.regions as re on l.id_region=re.id where o.id = :id_org_rosn', [':id_org_rosn' => 8]);
        $data['grochs'] = array_merge_recursive($grochs, $grochs_rosn); //умчс+РОСН
        //классификатор ПАСЧ
        $data['pasp'] = R::getAll('select distinct(id_record), id_card as id_grochs,case when(divizion_num=0) then divizion_name else concat(divizion_name,"№ ", divizion_num) '
                        . ' end as name from ss.card  where  id_record <> :id order by divizion_num ', [ ':id' => $id]);

        /* КОНЕЦ адрес, КУДА отправляем в командировку */




        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/car/car.php', $data);

        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение
        //bread
        $data['name'] = 'Командировки';
        $app->render('card/sheet/car/trip/breadTripCar.php', $data);
        //форма добавления техники в командировку
        $app->render('card/sheet/car/trip/formAddTrip.php', $data);

        if (isset($data['tripcar']) && !empty($data['tripcar'])) {
            //таблица техники в командировке
            $app->render('card/sheet/car/trip/tableTripCar.php', $data);
        }

        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/:id/ch/:change/car/trip', function ($id, $change) use ($app,$log) {//insert car information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 6; //car
        $today = date("Y-m-d");

        $id_teh = $app->request()->post('id_teh');
        $date1 = $app->request()->post('date1');
        if (empty($date1))
            $date1 = NULL;
        else
            $date1 = date("Y-m-d", strtotime($date1));
        $date2 = $app->request()->post('date2');
        if (empty($date2))
            $date2 = NULL;
        else
            $date2 = date("Y-m-d", strtotime($date2));


        if(isset($_POST['id_pasp']) && !empty($_POST['id_pasp']))
        $to_card=$_POST['id_pasp'];//куда откомандирована техника
        else
            $to_card=0;//не указана ПАСЧ

        // на учете или нет
//        if ((date("Y-m-d") >= $date1) && (date("Y-m-d") <= $date2)) {//да
//            $deregister = 0;
//        } else {//нет
//            $deregister = 1;
//        }
        $deregister = 0;


           //куда откомандировали - текстом
        if($to_card != 0){
                    $place_arr = R::getAll('select   reg.name as region,(CASE WHEN (org.id = 8) THEN concat(loc.name, " ",org.name) WHEN (org.id = 7) THEN CONCAT(org.name," №",locor.no," ",'
                        . ' REPLACE(loc.name,"ий","ого")," ",orgg.name) ELSE CONCAT(loc.name," ",org.name) END) AS organ,'
                        . ' case when(rec.divizion_num=0) then diviz.name else concat(diviz.name,"№ ", rec.divizion_num)  end as pasp'

                        . ' from  ss.records as rec  left join ss.locorg as locor on locor.id=rec.id_loc_org'
                        . ' left JOIN ss.organs org ON locor.id_organ = org.id left JOIN ss.organs orgg ON locor.oforg = orgg.id'
                        . ' left JOIN ss.locals loc ON locor.id_local = loc.id left JOIN ss.regions reg ON loc.id_region = reg.id'
                        . ' LEFT JOIN ss.divizions diviz ON rec.id_divizion = diviz.id WHERE rec.id = :id ', [':id' => $to_card]);


                     foreach ($place_arr as $p) {
            $place=$p['organ'].chr(10).$p['pasp'];
        }

        }
        else
            $place='';




        if (!empty($id_teh) && ($date1 != NULL)) {
            //insert
            $teh = R::dispense('tripcar');
            $teh->id_teh = $id_teh;
            $teh->date1 = $date1;
            $teh->date2 = $date2;
            $teh->place = $place;
            $teh->prikaz = $app->request()->post('prikaz');
            $teh->note = $app->request()->post('note');
            $teh->deregister = $deregister;
            $teh->ch = $change;
            $teh->id_card = $id;
            $teh->to_card = $to_card;//куда откомандирована техника
            $teh->date_insert = date("Y-m-d H:i:s");
            $teh->last_update = date("Y-m-d H:i:s");
            $teh->id_user = $_SESSION['uid'];
          $new_id_tripcar=R::store($teh);



            if($to_card != 0){// добавляем в "заступает из др подразделения", если еще не добавлена


                    /*         * * определить dateduty ** */
        $main = R::getAssoc("CALL get_main('{$id}','{$change}', '0');");
        if (isset($main) && !empty($main)) {
            foreach ($main as $value) {
                $dateduty = $value['dateduty'];
            }
        } else {
            $dateduty = $today;
        }
        $data['dateduty'] = $dateduty;


        /*         * * выбор техники в командировке на dateduty ** */
         $id_reservecar = R::getCell('select id from reservecar  where id_teh = :id_teh and id_card = :id_card and (( :date BETWEEN date1 and date2) or( :date  >= date1 and date2 is NULL)) ', [':id_teh' => $id_teh,':id_card' => $to_card, ':date' => $dateduty]);

                if (empty($id_reservecar)) {//автоматически insert в "заступает из др подразделения"
                    $res = R::dispense('reservecar');
                    $res->id_teh = $id_teh;
                    $res->date1 = $date1;// = дате, указанной в tripcar
                    $res->date2 = $date2;// = дате, указанной в tripcar
                    $res->place = $place;
                    $res->prikaz =  $app->request()->post('prikaz');
                    $res->id_card = $to_card;//куда откомандировали
                    $res->ch = $change;
                    $res->last_update = date("Y-m-d H:i:s");
                    $res->id_user = $_SESSION['uid'];
                    $res->deregister = 0;
                    $res->is_auto_create=1;//индикатор того, что создана автоматически
                    $res->date_insert = date("Y-m-d H:i:s");
                    $res->id_tripcar=$new_id_tripcar;
                   $id_reservecar_new= R::store($res);
                    $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Создание reservecar(auto) - Данные:: ' . $res);

                    					                   if(isset($id_reservecar_new)){//записать id_reservecar in tripcar
                        $trip=R::load('tripcar', $new_id_tripcar);
                        $trip->id_reservecar=$id_reservecar_new;
                        R::store($trip);

                    }
                }
            }
        }


        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/car/trip');
    });

    $app->put('/:id/ch/:change/car/trip', function ($id, $change) use ($app) {//insert car information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 6; //car
        $c = $app->request()->post('count');
        for ($i = 1; $i <= $c; $i++) {

            $date1 = $app->request()->post('date1' . $i);
            if (empty($date1))
                $date1 = NULL;
            else
                $date1 = date("Y-m-d", strtotime($date1));
            $date2 = $app->request()->post('date2' . $i);
            if (empty($date2))
                $date2 = NULL;
            else
                $date2 = date("Y-m-d", strtotime($date2));

            // на учете или нет
//            if ((date("Y-m-d") >= $date1) && (date("Y-m-d") <= $date2)) {//да
//                $deregister = 0;
//            } else {//нет
//                $deregister = 1;
//            }
            $deregister = 0;

            if (($date1 != NULL)) {
                $id_tripcar = $app->request()->post('id_tripcar' . $i);
                //insert
                $teh = R::load('tripcar', $id_tripcar);
                $teh->date1 = $date1;
                $teh->date2 = $date2;
               // $teh->place = $app->request()->post('place' . $i);
                $teh->prikaz = $app->request()->post('prikaz' . $i);
                $teh->note = $app->request()->post('note' . $i);
                $teh->deregister = $deregister;
                $teh->last_update = date("Y-m-d H:i:s");
                $teh->id_user = $_SESSION['uid'];
                R::store($teh);
            }
        }
        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/car/trip');
    });

    //предупреждающее сообщение об удалении
    $app->get('/:id/ch/:change/car/trip/:id_tripcar', function ($id, $change, $id_tripcar) use ($app) {//msg delete other by id
        array($app, 'is_auth'); //авторизован ли пользователь
        auth($id); //просмотр карточек своего подразделения
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 6; //car
        $data['id_tripcar'] = $id_tripcar;

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/car/car.php', $data);
        $app->render('msg/delete.php', $data);
        $app->render('card/sheet/car/trip/delete.php', $data); //button delete
        $app->render('card/sheet/car/trip/backFormCount.php', $data);

        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->delete('/:id/ch/:change/car/trip/:id_tripcar', function ($id, $change, $id_tripcar) use ($app, $log) {//delete other from DB by id
        array($app, 'is_auth'); //авторизован ли пользователь
        $tripcar = R::load('tripcar', $id_tripcar);

        //удалить из reservecar
        R::exec('delete from reservecar  where id_teh = ? and  id_tripcar = ? and is_auto_create = ? ', array($tripcar->id_teh, $tripcar->id, 1));

        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Удаление reservecar - автоматически при удалении tripcar');
        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Удаление tripcar - запись с id=' . $id_tripcar . '- Данные:: ' . $tripcar);

        R::trash($tripcar); ///delete tripcar from DB

        $_SESSION['msg'] = 2; //ok_delete
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/car/trip');

        //Удалить из reservecar, fiocar - это выполняют триггеры
    });


    /*  +++++++++++ техника из др подразделения +++++++++++++ */
    $app->get('/:id/ch/:change/car/reserve', function ($id, $change) use ($app) {
        array($app, 'is_auth');
        auth($id);
        $data = bread($id);
        $data['change'] = $change;
        $data['id_card'] = $id;
        $data['sign'] = 6; //car
        $today = date("Y-m-d");

                /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
             $duty = is_duty_cou($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty_cou($id, $change, 1); //открыт ли доступ на ред
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
             $duty = is_duty($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty($id, $change, 1); //открыт ли доступ на ред
        }

        $data['duty'] = $duty;

        //кнопка "Подтвердить данные"
        $data['is_btn_confirm'] = is_btn_confirm($change);


                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        /*         * * определить dateduty ** */
        $main = R::getAssoc("CALL get_main('{$id}','{$change}', '0');");
        if (isset($main) && !empty($main)) {
            foreach ($main as $value) {
                $dateduty = $value['dateduty'];
            }
        } else {
            $dateduty = $today;
        }
        $data['dateduty'] = $dateduty;
        //классификатор областей
        $data['regions'] = R::getAll('select * from ss.regions');
        //классификатор Г(Р)ОЧС, кроме РОСН
        $grochs = R::getAll('select organ as name, id_card as id_grochs, region as id_region  from ss.maintable where orgid <> :id_org_rosn', [':id_org_rosn' => 8]);
        $grochs_rosn = R::getAll('select concat(l.name," ",o.name) as name, locor.id as id_grochs, re.id as id_region  from '
                        . ' ss.locorg as locor left join ss.organs as o on locor.id_organ=o.id left join ss.locals as l on l.id=locor.id_local '
                        . ' left join ss.regions as re on l.id_region=re.id where o.id = :id_org_rosn', [':id_org_rosn' => 8]);
        $data['grochs'] = array_merge_recursive($grochs, $grochs_rosn); //умчс+РОСН
        //классификатор ПАСЧ
        $data['pasp'] = R::getAll('select distinct(id_record), id_card as id_grochs,case when(divizion_num=0) then divizion_name else concat(divizion_name,"№ ", divizion_num) '
                        . ' end as name from ss.card  where  id_record <> :id order by divizion_num ', [ ':id' => $id]);

        //список техники, доступной для выбора на сегодня
        $data['car_for_reserve'] = getListCarForReserve($id, $today);



        /*         * * выбор техники из reserve на dateduty ** */
        //инф по car в reserve
        $data['table_with_reserve'] = R::getAll('select r.is_auto_create,  reg.name as region,(CASE WHEN (org.id = 8) THEN concat(loc.name, " ",org.name) WHEN (org.id = 7) THEN CONCAT(org.name," №",locor.no," ",'
                        . ' REPLACE(loc.name,"ий","ого")," ",orgg.name) ELSE CONCAT(loc.name," ",org.name) END) AS organ,'
                        . ' case when(rec.divizion_num=0) then diviz.name else concat(diviz.name,"№ ", rec.divizion_num)  end as pasp,'
                        . ' case when(t.numbsign is not null) then concat(t.mark,"(",t.numbsign,")") else t.mark end as car, t.id_record as from_card,'
                        . ' r.date1, r.date2,r.prikaz, r.note, r.date_insert,  r.id from str.reservecar as r left join ss.technics as t on t.id=r.id_teh'
                        . ' left join ss.records as rec on rec.id=t.id_record left join ss.locorg as locor on locor.id=rec.id_loc_org'
                        . ' left JOIN ss.organs org ON locor.id_organ = org.id left JOIN ss.organs orgg ON locor.oforg = orgg.id'
                        . ' left JOIN ss.locals loc ON locor.id_local = loc.id left JOIN ss.regions reg ON loc.id_region = reg.id'
                        . ' LEFT JOIN ss.divizions diviz ON rec.id_divizion = diviz.id WHERE r.id_card = :id and (( :date BETWEEN date1 and date2) or( :date  >= date1 and date2 is NULL)) ', [':id' => $id, ':date' => $dateduty]);

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/car/car.php', $data);

        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение
        //bread
        $data['name'] = 'Из другого подразделения';
        $app->render('card/sheet/car/trip/breadTripCar.php', $data);
        //форма добавления техники из др ПАСЧ
        $app->render('card/sheet/car/reserve/formAddReserve.php', $data);

        if (isset($data['table_with_reserve']) && !empty($data['table_with_reserve'])) {
            //таблица техники, которая заступила из др ПАСЧ
            $app->render('card/sheet/car/reserve/tableReserveCar.php', $data);
        }


        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/:id/ch/:change/car/reserve', function ($id, $change) use ($app,$log) {//insert car_reserve information

        /* id - куда техника приехала
         *  */
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 6; //car
         $today = date("Y-m-d");

        $id_teh = $app->request()->post('id_teh');
        $date1 = $app->request()->post('date1');
        if (empty($date1))
            $date1 = NULL;
        else
            $date1 = date("Y-m-d", strtotime($date1));
        $date2 = $app->request()->post('date2');
        if (empty($date2))
            $date2 = NULL;
        else
            $date2 = date("Y-m-d", strtotime($date2));

        // на учете или нет
//        if ((date("Y-m-d") >= $date1) && (date("Y-m-d") <= $date2)) {//да
//            $deregister = 0;
//        } else {//нет
//            $deregister = 1;
//        }
        $deregister = 0;


          //* куда приехала - текстом */
        $place='';

                    $place_arr = R::getAll('select   reg.name as region,(CASE WHEN (org.id = 8) THEN concat(loc.name, " ",org.name) WHEN (org.id = 7) THEN CONCAT(org.name," №",locor.no," ",'
                        . ' REPLACE(loc.name,"ий","ого")," ",orgg.name) ELSE CONCAT(loc.name," ",org.name) END) AS organ,'
                        . ' case when(rec.divizion_num=0) then diviz.name else concat(diviz.name,"№ ", rec.divizion_num)  end as pasp'

                        . ' from  ss.records as rec  left join ss.locorg as locor on locor.id=rec.id_loc_org'
                        . ' left JOIN ss.organs org ON locor.id_organ = org.id left JOIN ss.organs orgg ON locor.oforg = orgg.id'
                        . ' left JOIN ss.locals loc ON locor.id_local = loc.id left JOIN ss.regions reg ON loc.id_region = reg.id'
                        . ' LEFT JOIN ss.divizions diviz ON rec.id_divizion = diviz.id WHERE rec.id = :id ', [':id' => $id]);


                     foreach ($place_arr as $p) {
            $place=$p['organ'].chr(10).$p['pasp'];
        }
          /*  END  куда приехала - текстом   */




        if (!empty($id_teh) && ($date1 != NULL)) {
            //insert reservecar
            $res = R::dispense('reservecar');
            $res->id_teh = $id_teh;
            $res->date1 = $date1;
            $res->date2 = $date2;
            $res->prikaz = $app->request()->post('prikaz');
            $res->note = $app->request()->post('note');
            $res->deregister = $deregister;
            $res->ch = $change;
            $res->id_card = $id;//куда  приехала
            $res->place=$place;//куда  приехала текстом
            $res->date_insert = date("Y-m-d H:i:s");
            $res->last_update = date("Y-m-d H:i:s");
            $res->id_user = $_SESSION['uid'];
            $new_id_reservecar=R::store($res);


            //insert into tripcar
                        /*         * * определить dateduty ** */
        $main = R::getAssoc("CALL get_main('{$id}','{$change}', '0');");
        if (isset($main) && !empty($main)) {
            foreach ($main as $value) {
                $dateduty = $value['dateduty'];
            }
        } else {
            $dateduty = $today;
        }
        $data['dateduty'] = $dateduty;


        /*         * * выбор техники в командировке на dateduty ** */
         $id_tripcar = R::getCell('select id from tripcar  where id_teh = :id_teh and to_card = :to_card and (( :date BETWEEN date1 and date2) or( :date  >= date1 and date2 is NULL)) ', [':id_teh' => $id_teh,':to_card' => $id, ':date' => $dateduty]);



                if (empty($id_tripcar)) {//автоматически insert в "trip"
  $teh = R::dispense('tripcar');
            $teh->id_teh = $id_teh;
            $teh->date1 = $date1;
            $teh->date2 = $date2;
            $teh->place = $place;
            $teh->prikaz = $app->request()->post('prikaz');
            $teh->note = $app->request()->post('note');
            $teh->deregister = $deregister;
            $teh->ch = $change;
            $teh->id_card = $app->request()->post('id_pasp');//откуда откомандирована
            $teh->to_card = $id;//куда откомандирована техника
            $teh->date_insert = date("Y-m-d H:i:s");
            $teh->last_update = date("Y-m-d H:i:s");
            $teh->id_user = $_SESSION['uid'];
            $teh->is_auto_create = 1;
            $teh->id_reservecar=$new_id_reservecar;
           $id_tripcar_new= R::store($teh);
                    $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Создание tripcar(auto) - Данные:: ' . $teh);

                    if(isset($id_tripcar_new)){//записать id_tripcar in reservecar
                        $reservecar=R::load('reservecar', $new_id_reservecar);
                        $reservecar->id_tripcar=$id_tripcar_new;
                        R::store($reservecar);

                    }

                }
        }

        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/car/reserve');
    });

    $app->put('/:id/ch/:change/car/reserve', function ($id, $change) use ($app) {//update car information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 6; //car
        $c = $app->request()->post('count');
        for ($i = 1; $i <= $c; $i++) {

            $id_reserve = $app->request()->post('id_reserve' . $i);
            $date1 = $app->request()->post('date1' . $i);
            if (empty($date1))
                $date1 = NULL;
            else
                $date1 = date("Y-m-d", strtotime($date1));
            $date2 = $app->request()->post('date2' . $i);
            if (empty($date2))
                $date2 = NULL;
            else
                $date2 = date("Y-m-d", strtotime($date2));

            $deregister = 0;

            $prikaz= $app->request()->post('prikaz' . $i);
            $note=$app->request()->post('note' . $i);

            if (!empty($id_reserve) && ($date1 != NULL)) {
                //update
                $teh = R::load('reservecar', $id_reserve);
                $teh->date1 = $date1;
                $teh->date2 = $date2;
                $teh->prikaz = $prikaz;
                $teh->note = $note;
                $teh->deregister = $deregister;
                $teh->last_update = date("Y-m-d H:i:s");
                $teh->id_user = $_SESSION['uid'];

                $id_teh=$teh->id_teh;

                R::store($teh);


                //update tripcar
 R::exec("update tripcar set date1 = ?, date2 = ?, prikaz = ?, note = ?, last_update = ?, id_user = ? "
." where id_teh = ? and id_reservecar = ? ", array($date1, $date2,$prikaz,$note,date("Y-m-d H:i:s"), $_SESSION['uid'],$id_teh,$id_reserve));

            }
        }
        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/car/reserve');
    });

    //предупреждающее сообщение об удалении
    $app->get('/:id/ch/:change/car/reserve/:id_reservecar', function ($id, $change, $id_reservecar) use ($app) {//msg delete other by id
        array($app, 'is_auth'); //авторизован ли пользователь
        auth($id); //просмотр карточек своего подразделения
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 6; //car
        $data['id_reservecar'] = $id_reservecar;


                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/car/car.php', $data);
        $app->render('msg/delete.php', $data);
        $app->render('card/sheet/car/reserve/delete.php', $data); //button delete
        $app->render('card/sheet/car/reserve/backFormCount.php', $data);

        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });
    $app->delete('/:id/ch/:change/car/reserve/:id_reservecar', function ($id, $change, $id_reservecar) use ($app, $log) {//delete other from DB by id
        array($app, 'is_auth'); //авторизован ли пользователь
        $reservecar = R::load('reservecar', $id_reservecar);

         //удалить из tripcar
        R::exec('delete from tripcar  where id_teh = ? and id_reservecar = ? ', array($reservecar->id_teh, $reservecar->id));

        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Удаление tripcar - автоматически при удалении reservecar');


        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Удаление reservecar - запись с id=' . $id_reservecar . '- Данные:: ' . $reservecar);

        R::trash($reservecar); ///delete tripcar from DB

        $_SESSION['msg'] = 2; //ok_delete
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/car/reserve');

        //Удалить из reservecar, fiocar - это выполняют триггеры
    });


     $app->get('/:id/ch/:change/car(/:sort)', function ($id, $change,$sort=0) use ($app) {//sheet car
        array($app, 'is_auth');
        auth($id);
        $data = bread($id);
        $data['change'] = $change;
        $data['id_card'] = $id;
        $data['sign'] = 6; //car
        $today = date("Y-m-d");

                /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
             $duty = is_duty_cou($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty_cou($id, $change, 1); //открыт ли доступ на ред

              //заступает ли техника. для ЦОУ, если нет техники для заступления - поставить отметку и сохранить
              $data['is_car']=R::getCell('select id from carcou where id_card = ? and ch = ?',array($id,$change));

        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
             $duty = is_duty($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty($id, $change, 1); //открыт ли доступ на ред
        }

        $data['duty'] = $duty;

        //кнопка "Подтвердить данные"
        $data['is_btn_confirm'] = is_btn_confirm($change);
        $data['duty_ch'] = duty_ch(); //номер деж смены

        $data['type_teh']=  getTypeTeh();


                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);

         /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
            $app->render('card/sheet/start_cou.php', $data);
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
              $app->render('card/sheet/start.php', $data);
        }
        $app->render('card/sheet/car/car.php', $data);

        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение

        $to = R::getAll('SELECT * FROM carto order by id desc'); //классификатор ТО
        $type = R::getAll('SELECT * FROM cartype order by id desc'); //классификатор боев/резерв
        $reason_repaire=R::getAll('SELECT * FROM reasonrepaire order by name asc'); //классификатор причины неисправностей
        $data['to'] = $to;
        $data['type'] = $type;
        $data['reason_repaire']=$reason_repaire;
        // $data['listfio'] = getListFio($id, $change, 3);


                 /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
       $main = R::getAll('select * from maincou where id_card = ? and ch = ? limit 1',array($id,$change));
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
            $main = R::getAssoc("CALL get_main('{$id}','{$change}', '0');");
        }


        if (isset($main) && !empty($main)) {
            foreach ($main as $value) {
                $dateduty = $value['dateduty'];
            }
        } else {
            $dateduty = $today;
        }
        $data['dateduty'] = $dateduty;

        /* +++++++ Классификатор ФИО - доступные ФИО , не учитываем "нет работников"+++++++ */
        $data['present_car_fio'] = getPresentHeadFio($id, $change,$is_nobody=1);


        /* +++++ Список техники этого ПАСЧ для заполнения +++++ */

        if($sort==1)
        $own_car = getOwnCar($id, $change, $dateduty,$sort);
        else
                  $own_car = getOwnCar($id, $change, $dateduty);

        $data['own_car'] = $own_car;
        $teh_array = array();
        foreach ($own_car as $value) {
            $teh_array[] = $value['tehstr_id'];
        }
        $data['fio_on_own_car'] = getFioOnCar($teh_array, $id, $change);  //ФИО на этой технике


        /* +++++ Список техники, заступающей из др ПАСЧ для заполнения +++++ */
        $car_in_reserve = getCarInReserve($id, $dateduty, $change);
        $data['car_in_reserve'] = $car_in_reserve;
        $teh_array = array();
        foreach ($car_in_reserve as $value) {
            $teh_array[] = $value['tehstr_id'];
        }
        $data['fio_on_reserve_car'] = getFioOnCar($teh_array, $id, $change); //ФИО на этой технике


        /* +++++ Список техники, которая в резерве/командировке для отображения +++++ */
        $data['own_car_in_trip'] = getOwnCarInTrip($id, $dateduty, $change);

        $data['post'] = 0; //put data car...при хранении инф за месяц =1: не обновляем, а добавляем запись

        /* ---------------- ссылка на карточку учета сил и средств ОПЧС ---------------------*/
           $sign = R::getCell('select lo.id_organ from ss.locorg as lo left join ss.records as re on re.id_loc_org=lo.id where re.id = ? limit 1', array($id));//РОСН или нет
        if ($sign == 8) {//РОСН
              $data['id_region'] = 3;//г.Минск
            $data['id_grochs'] = 160;//г.Минск РОСН
        } else {
            $data['id_region'] = get_id_region($id);
            $data['id_grochs'] = get_id_grochs($id);
        }
             /* ---------------- END ссылка на карточку учета сил и средств ОПЧС ---------------------*/

        $app->render('card/sheet/car/carButton.php', $data);
        $app->render('card/sheet/car/tableCar.php', $data); //view data
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    /* +++++++++ Списки техники ++++++++++ */

    //техника подразделения не зависимо от смены - id. car
    /* $sign=1 - свой ПАСЧ
     *  0 - чужой */
    function getTeh($id, $sign) {
        if ($sign == 1)
            $result = R::getAll('select t.id from ss.technics as t where t.id_record= ?', array($id));
        else
            $result = R::getAll('select t.id from ss.technics as t where t.id_record <> ?', array($id));
        if (!empty($result)) {
            foreach ($result as $value) {
                $list[] = $value['id'];
            }
        } else
            $list = array();
        return $list;
    }

    //марка техники для классификатора командировка
    function getNameTehForTripCar($massive) {

     if(!empty($massive))
        return R::getAll('select t.id, t.mark, t.numbsign from ss.technics as t where t.id IN (' . implode(',', $massive) . ')');
	else
		return array();
    }

    // tripcar(dateduty)
    /* $sign=1 - свой ПАСЧ
     *  0 - любой */
    function getListTripCar($id, $date, $sign) {
        if ($sign == 1)
            $result = R::getAll('select * from tripcar  where (id_card = :id_card) and (( :date BETWEEN date1 and date2) or( :date  >= date1 and date2 is NULL)) ', [':id_card' => $id, ':date' => $date]);
        else {
            $result = R::getAll('select * from tripcar  where  (( :date BETWEEN date1 and date2) or( :date  >= date1 and date2 is NULL)) ', [ ':date' => $date]);
        }
        if (!empty($result)) {
            foreach ($result as $value) {
                $list[] = $value['id_teh'];
            }
        } else
            $list = array();
        return $list;
    }

    // reserve
    /* $sign=1 - свой ПАСЧ
     * 2 - техника текущего id заступает в др ПАСЧ
     *  0 - любой      */
    function getListReserveCar($id, $date, $sign) {
        if ($sign == 1)
            $result = R::getAll('select * from reservecar  where id_card = :id_card and (( :date BETWEEN date1 and date2) or( :date  >= date1 and date2 is NULL)) ', [':id_card' => $id, ':date' => $date]);
        elseif ($sign == 0) {
            $result = R::getAll('select * from reservecar  where  (( :date BETWEEN date1 and date2) or( :date  >= date1 and date2 is NULL)) ', [ ':date' => $date]);
        } else {
            $result = R::getAll('select * from reservecar as r left join ss.technics as t ON t.id=r.id_teh  where (r.id_card <> :id_card) AND '
                            . ' (( :date BETWEEN date1 and date2) or( :date  >= date1 and date2 is NULL)) AND t.id_record = :id_card', [':id_card' => $id, ':date' => $date]);
        }


        if (!empty($result)) {
            foreach ($result as $value) {
                $list[] = $value['id_teh'];
            }
        } else
            $list = array();
        return $list;
    }

    //классификатор техники, доступной в списке заступает из др ПАСЧ
    function getListCarForReserve($id, $date) {
        $id_teh = minusArray(getTeh($id, 0), getListReserveCar($id, $date, 0));

        if (!empty($id_teh)) {
            return R::getAll('select case when(c.numbsign is not null) then concat(c.mark,"(",c.numbsign,")") else c.mark end as car,'
                            . ' c.tid  as id_teh, id_record from ss.card as c where c.tid IN (' . implode(",", $id_teh) . ')');
        } else
            return array();
    }

    //классификатор техники для командировка
    function getListCarForTrip($id, $date) {
        $car = getTeh($id, 1);
        $tripcar = getListTripCar($id, $date, 1);

        return getNameTehForTripCar(minusArray($car, $tripcar));
    }

//список техники своего ПАСЧ для заполнения
    function getOwnCar($id, $change, $date,$sort=NULL) {
        $list = getTeh($id, 1);
        $trip = getListTripCar($id, $date, 1);
        $reserve = getListReserveCar($id, $date, 0);
        $result = minusArray($list, $trip);
        $res = minusArray($result, $reserve);

        if (!empty($res)) {
            //получить всю инф о технике для данной смены ПАСЧ
            if($sort==1)
            return R::getAll('SELECT * FROM tehrecord WHERE ch = :ch AND  id_teh IN (' . implode(",", $res) . ') order by id_type, id_to', [':ch' => $change]);
            else
                 return R::getAll('SELECT * FROM tehrecord WHERE ch = :ch AND  id_teh IN (' . implode(",", $res) . ')', [':ch' => $change]);
        } else
            return array();
    }

    //ФИО на машинах
    function getFioOnCar($teh_array, $id, $change) {
        if (!empty($teh_array)) {
            //без РОСН !!!
//            return R::getAll('select lf.id, fc.id_tehstr as tehstr_id, case  when(c.id_card = :id) then  case when( c.ch = :ch) then lf.fio  when(c.ch = :ch_everyday ) then concat(lf.fio,"(","ежедневник",")") '
//                            . ' else  concat(lf.fio," - ",d.name,"№",re.divizion_num)  end else  concat(lf.fio," - ",d.name,"№",re.divizion_num)  end as fio  from str.fiocar AS fc '
//                            . '   left join str.listfio AS lf  ON lf.id=fc.id_fio  left join str.cardch as c on c.id=lf.id_cardch  left join ss.records as re on c.id_card=re.id '
//                            . ' left join ss.divizions as d on d.id=re.id_divizion WHERE fc.id_tehstr IN (' . implode(",", $fio_array) . ')', [':id' => $id, ':ch' => $change, ':ch_everyday' => 0]);

            $id_cardch = getIdCardCh($id, $change);

//            return R::getAll('select lf.id, fc.id_tehstr as tehstr_id,  '
//                            . ' case  when(c.id_card = 868) then  case when( c.ch = 3) then lf.fio  when(c.ch = 0 ) '
//                            . '  then concat(lf.fio,"(","ежедневник",")")  else  concat(lf.fio," ",lo.name," ",o.name) end else  case when(o.id=8) then case when(c.id = 3074) then lf.fio '
//                            . ' else concat(lf.fio," ",lo.name," ",o.name) end  else concat(lf.fio," - ",d.name,"№",re.divizion_num)  end end  as fio, po.name as slug '
//                            . ' from str.fiocar AS fc   left join str.listfio AS lf  ON lf.id=fc.id_fio  left join str.cardch as c on c.id=lf.id_cardch  left join ss.records as re on c.id_card=re.id '
//                            . ' left join ss.divizions as d on d.id=re.id_divizion '
//                            . ' left join ss.locorg as locor on locor.id=re.id_loc_org left join ss.locals as lo on lo.id=locor.id_local '
//                            . ' left join ss.organs as o on o.id=locor.id_organ'
//                            . ' left join str.position as po on po.id=lf.id_position WHERE fc.id_tehstr IN (' . implode(",", $fio_array) . ')', [':id' => $id, ':ch' => $change, ':ch_everyday' => 0, ':cardch' => $id_cardch]);


            $fio = R::getAll('SELECT * FROM get_fio_on_car WHERE tehstr_id IN (' . implode(",", $teh_array) . ')');

            //если работник этой же смены - ему не выводить принадлежность
            foreach ($fio as $key => $value) {
                if ($id_cardch == $value['id_cardch']) {
                    $fio[$key]['pasp'] = ' ';
                    $fio[$key]['locorg_name'] = ' ';
                }
            }
            return $fio;
        } else
            return array();
    }

    //техника в командировке/в др ПАСЧ- для отображения в своем ПАСЧ
    function getOwnCarInTrip($id, $date, $change) {
        $trip = getListTripCar($id, $date, 1);
        $reserve = getListReserveCar($id, $date, 2);
        $result = plusArray($trip, $reserve);
        $res = array_unique($result);
        if (!empty($res)) {
            //получить всю инф о технике для данной смены ПАСЧ
            return R::getAll('SELECT mark, numbsign FROM tehrecord WHERE ch = :ch AND  id_teh IN (' . implode(",", $res) . ')', [':ch' => $change]);
        } else
            return array();
    }

    //техника, заступающая из др ПАСЧ, которую можно заполнять
    function getCarInReserve($id, $date, $change) {
        $result = getListReserveCar($id, $date, 1);
        if (!empty($result)) {
            //получить всю инф о технике. Брать id_car  того номера смены(чужой ПАСЧ), которая сегодня заступает
            $r = R::getAll('SELECT * FROM tehrecord WHERE ch = :ch AND  id_teh IN (' . implode(",", $result) . ')', [':ch' => $change]);
        } else
            $r = array();
        return $r;
    }

    /* +++++++++++++ Конец Списки техники +++++++++++++++++ */


    /* Заступление работников на технику. 1 работник может быть только на 1 единице */

    function setFioTeh($id_car, $fio) {
        //$fioteh = R::getCell('SELECT count(id) FROM fiotehstr WHERE id_tehstr = :id_tehstr', ['id_tehstr' => $id_car]);
        $fioteh_bd = R::getAll('SELECT * FROM fiocar WHERE id_tehstr = :id_tehstr', [':id_tehstr' => $id_car]);

        //если не выбрано ни одного ФИО на машине
        if (empty($fio)) {
            //но в БД были работники на этой машине
            if (isset($fioteh_bd) && !empty($fioteh_bd)) {
                foreach ($fioteh_bd as $key_bd => $value_bd) {
                    $f = R::load('fiocar', $value_bd['id']);
                    R::trash($f);
                }
            }
        } else {//на машине выбраны работники
            if (isset($fioteh_bd) && !empty($fioteh_bd)) {

                //ищем совпадения из формы и из БД-если найдены-ничего не выполнять-оставляем их в БД
                foreach ($fioteh_bd as $key_bd => $value_bd) {
                    foreach ($fio as $key => $value) {
                        if ($value_bd['id_fio'] == $value) {
                            unset($fio[$key]);
                            unset($fioteh_bd[$key_bd]);
                            break;
                        }
                    }
                }
                //замена фио в БД на ФИО из формы, если совпадения не найдены
                if (!empty($fioteh_bd)) {
                    foreach ($fioteh_bd as $key_bd => $value_bd) {
                        foreach ($fio as $key => $value) {

                            $fiotehstr = R::load('fiocar', $value_bd['id']);
                            $fiotehstr->id_fio = $value;
                            R::store($fiotehstr);
                            unset($fio[$key]);
                            unset($fioteh_bd[$key_bd]);
                            break;
                        }
                    }
                }
//если на форме было > фио, чем в БД- добавить оставшихся
                if (!empty($fio)) {

                    foreach ($fio as $key => $value) {

                        $fiotehstr = R::dispense('fiocar');
                        $fiotehstr->id_tehstr = $id_car;
                        $fiotehstr->id_fio = $value;
                        R::store($fiotehstr);
                    }
                }
//удалить из БД оставшихся-лишних
                if (!empty($fioteh_bd)) {

                    foreach ($fioteh_bd as $key_bd => $value_bd) {

                        $fiotehstr = R::load('fiocar', $value_bd['id']);
                        R::trash($fiotehstr);
                    }
                }
            } else {//insert
                if (!empty($fio)) {
                    foreach ($fio as $key => $value) {
                        $fiotehstr = R::dispense('fiocar');
                        $fiotehstr->id_tehstr = $id_car;
                        $fiotehstr->id_fio = $value;
                        R::store($fiotehstr);
                    }
                }
            }
        }
    }

//storage
    $app->get('/:id/ch/:change/storage', function ($id, $change) use ($app) {//sheet storage
        array($app, 'is_auth');
        auth($id);
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 7; //storage
        $today = date("Y-m-d");

                /*--- ЦОУ, ШЛЧС---*/
        if(getIdDivizion($id) == 8 || getIdDivizion($id) == 9){
             $duty = is_duty_cou($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty_cou($id, $change, 1); //открыт ли доступ на ред
        }
        /*--- ЦОУ, ШЛЧС---*/
        else{
             $duty = is_duty($id, $change, 0); //смена дежурная или нет
              $data['is_open_update'] = is_duty($id, $change, 1); //открыт ли доступ на ред
        }

        $data['duty'] = $duty;

        $data['is_btn_confirm'] = is_btn_confirm($change); //кнопка "Подтвердить данные"
        $data['duty_ch'] = duty_ch(); //номер деж смены


                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);
        $app->render('card/sheet/start.php', $data);
        $app->render('card/sheet/storage/storage.php', $data);

        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение

        $main = R::getAssoc("CALL get_main('{$id}','{$change}', '0');");
        if (isset($main) && !empty($main)) {
            foreach ($main as $value) {
                $dateduty = $value['dateduty'];
            }
        } else {
            $dateduty = $today;
        }
        $data['dateduty'] = $dateduty;

        $storage = R::getAll('SELECT st.id, st.asv, st.foam, st.powder FROM storage AS st inner join cardch AS c ON st.id_cardch=c.id  '
                        . 'WHERE c.id_card = :id AND c.ch = :change ', [':id' => $id, ':change' => $change]
        ); //выбор склада для change карточки with dateduty

        if (isset($storage) && !empty($storage)) {
            $data['post'] = 0; //put data storage
            $data['storage'] = $storage;
            $app->render('card/sheet/storage/tableStorage.php', $data); //view data
        } else {
            //выводим пустую формы
            $app->render('card/sheet/storage/formFillStorage.php', $data);
        }

        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/:id/ch/:change/storage', function ($id, $change) use ($app) {//insert storage information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 7; //storage
        $cardch = getIdCardCh($id, $change);
//insert
        $storage = R::dispense('storage');
        $storage->id_cardch = $cardch;
        $storage->dateduty = date("Y-m-d");
        $storage->last_update = date("Y-m-d H:i:s");
        $storage->id_user = $_SESSION['uid'];
// $storage->kip = $app->request()->post('kip');
        $storage->asv = $app->request()->post('asv');
        $storage->powder = $app->request()->post('powder');
        $storage->foam = $app->request()->post('foam');
        R::store($storage);

        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/storage');
    });

    $app->put('/:id/ch/:change/storage', function ($id, $change) use ($app, $log) {//update car information
        array($app, 'is_auth'); //авторизован ли пользователь
        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 7; //storage
//update
        $id_storage = $app->request()->post('idstorage'); //id of storage

        $storage = R::load('storage', $id_storage);
        $storage->last_update = date("Y-m-d H:i:s");
        $storage->id_user = $_SESSION['uid'];
// $storage->kip = $app->request()->post('kip');
        $storage->asv = $app->request()->post('asv');
        $storage->powder = $app->request()->post('powder');
        $storage->foam = $app->request()->post('foam');
        $storage->dateduty = date("Y-m-d");
        R::store($storage);

        $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Редактирование storagestr - запись с id=' . $id_storage . '- Данные:: ' . $storage);

        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/storage');
    });
});



/* * ************************* ОТКРЫТЬ ДОСТУП НА РЕД********************************* */
$app->group('/open_update', $is_auth, function () use ($app, $log) {

    //подразделение, где открываем доступ/закрываем
    function getBreadArray($id_main) {
        return R::getAll('select * FROM generalstr WHERE id_main=?', array($id_main));
    }

//проверка, есть ли права на выполнение операции
    $app->get('/:id_main', function ($id_main) use ($app) {
        $data['bread_array'] = getBreadArray($id_main);
        $data['id_main'] = $id_main;
        $app->render('layouts/header.php');
        $app->render('layouts/menu.php');
        $app->render('bread/breadOpenUpdate.php', $data);
        // $app->render('layouts/pz_container.php');
        // $is_radmin = R::getAll('SELECT * FROM radmins WHERE pssw = ?', array($_SESSION['psw']));
        if ($_SESSION['is_admin'] == 1) {//есть правао
            //предепреждение
            $app->render('msg/open_update.php');
            $app->render('open_update/open_update.php', $data);
            $app->render('open_update/back.php');
            $app->render('layouts/footer.php');
        } else {//нет права
            $app->redirect('/str/modal');
        }
    });
    //открытие доступа
    $app->get('/open/:id_main', function ($id_main) use ($app, $log) {
        // $id_main = $app->request()->post('id_main');
        //$id_radmin = R::getCell('SELECT id_admin FROM radmins WHERE pssw = ?', array($_SESSION['psw']));
        if ($_SESSION['is_admin'] == 1) {//есть правао
            $main = R::load('main', $id_main);
            $main->who_open = $_SESSION['uid'];
            $main->open_update = 1;
            R::store($main);
            $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Открыт доступ на редактирование main - запись с id=' . $id_main . '- Данные:: ' . $main);
            $app->redirect('/str/general/1');
        }
    });
});
/* * ************************* ЗАКРЫТЬ ДОСТУП НА РЕД********************************* */
$app->group('/close_update', $is_auth, function () use ($app, $log) {
    //предупреждение
    $app->get('/:id_main', function ($id_main) use ($app, $log) {

        $data['bread_array'] = getBreadArray($id_main);
        $data['id_main'] = $id_main;
        $app->render('layouts/header.php');
        $app->render('layouts/menu.php');
        $app->render('bread/breadCloseUpdate.php', $data);

        if ($_SESSION['is_admin'] == 1) {//есть правао
//вывод сообщения при неудачи
            if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
                $data['close_update'] = 1;
                $app->render('card/sheet/confirm/danger.php', $data);
                unset($_SESSION['msg']); //сбросить сообщение
            } else {
                //предепреждение
                $app->render('msg/close_update.php');
            }

            $app->render('close_update/close_update.php', $data);
            $app->render('open_update/back.php');
            $app->render('layouts/footer.php');
        } else {//нет права
            $app->redirect('/str/modal');
        }
    });

    $app->get('/close/error/:id_main', function ($id_main) use ($app) {

        $data['bread_array'] = getBreadArray($id_main);
        $data['id_main'] = $id_main;
        $app->render('layouts/header.php');
        $app->render('layouts/menu.php');
        $app->render('bread/breadCloseUpdate.php', $data);

        $app->render('close_update/danger.php');
        $app->render('layouts/footer.php');
    });

    //закрытие доступа
    $app->get('/close/:id_main', function ($id_main) use ($app, $log) {
        if ($_SESSION['is_admin'] == 1) {//есть правао
            $data['id_main'] = $id_main;
            $mainstr = R::load('main', $id_main);
            $dateduty = $mainstr->dateduty;
            $id_fio = $mainstr->id_fio;
            $id_card = $mainstr->id_card;
            $ch = $mainstr->ch;

            //выполнить подсчет больных на сегодня, т.е. дата1<=дата заступления смены<=дата2
            $c_ill = getCountIll($id_card, $ch, $dateduty);
            //выполнить подсчет отпусков на сегодня, т.е. дата1<=дата заступления смены<=дата2
            $c_hol = getCountHoliday($id_card, $ch, $dateduty);
            //выполнить подсчет др.причин на сегодня, т.е. дата1<=дата заступления смены<=дата2
            $c_other = getCountOther($id_card, $ch, $dateduty);
            //выполнить подсчет командировок на сегодня, т.е. дата1<=дата заступления смены<=дата2
            $c_trip = getCountTrip($id_card, $ch, $dateduty);

            //проверить формулу 2: по списку=налицо+больные+командировки+отпуска+др.причины
$a=1;
 if ($a==1) {
          //  if ($mainstr->listls == ($mainstr->face + $c_ill + $c_hol + $c_trip + $c_other)) {//формула выполняется
                //update is_duty=0 у всех смен этой карточки и выбираем cardchstr.id
                // setDutyCh($id);
                $cardch = getIdCardCh($id_card, $ch);
                //выбираем последнюю запись countstr запись этой карточки, смены
                $last_countstr = R::getCell('SELECT id FROM countstr WHERE id_cardch = :cardch order by dateduty DESC LIMIT 1', ['cardch' => $cardch]);

                if (isset($last_countstr) && !empty($last_countstr)) {//если не храним за месяц
                    //update countstr
                    $countstr = R::load('countstr', $last_countstr);
                    $countstr->c_ill = $c_ill;
                    $countstr->c_hol = $c_hol;
                    $countstr->c_trip = $c_trip;
                    $countstr->c_other = $c_other;
                    $countstr->last_update = date("Y-m-d H:i:s");
                    R::store($countstr);
                } else {
                    //insert into countstr
                    $countstr = R::dispense('countstr');
                    $countstr->id_cardch = $cardch;
                    $countstr->c_ill = $c_ill;
                    $countstr->c_hol = $c_hol;
                    $countstr->c_trip = $c_trip;
                    $countstr->c_other = $c_other;
                    $countstr->dateduty = date("Y-m-d");
                    $countstr->last_update = date("Y-m-d H:i:s");
                    $countstr->id_user = $_SESSION['uid'];
                    R::store($countstr);
                }

                $mainstr->last_update = date("Y-m-d H:i:s");
                $mainstr->open_update = 0;
                $mainstr->who_open = 0;
                R::store($mainstr);
                $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Закрыт доступ на редактирование main - запись с id=' . $id_main . '- Данные:: ' . $mainstr);
                $app->redirect('/str/general/1');
            } else {
//                $_SESSION['msg'] = 1;
//                $app->redirect('/str/close_update/' . $id_main);
                $app->redirect('/str/close_update/close/error/' . $id_main);
            }
        } else {//нет права
            $app->redirect('/str/modal');
        }
    });
});



/* * ***************************** ОТЧЕТЫ ****************************** */
$app->group('/v1/report',$is_auth, function () use ($app) {


    /*     * ************************* Spravochnaya inf for spec donesenia ************************************ */

        $app->post('/spr_info/grochs/:id/:id_pasp_active', function ($id,$id_pasp_active) use ($app) {

        //определяем, РОСН это или нет
        $sign = R::getCell('select id_organ from ss.locorg where id = ? limit 1', array($id));
        if ($sign == 8) {//РОСН
            // если авторизован РЦУ или РОСН уровня 2(видит весь РОСН)-формируем инф по всему РОСН
            if (($_SESSION['ulevel'] == 1) || ($_SESSION['ulevel'] == 2)) {
                $id_pasp = R::getAll('select distinct id_pasp from spr_info_report where  org_id = ? ', array(8));
            } else {
                //по конкретному ОУ РОСН
                $id_pasp = R::getAll('select distinct id_pasp from spr_info_report where id_grochs = ?', array($id));
            }
        } else {
            //выбор id ПАСЧей этого ГРОЧС
            $id_pasp = R::getAll('select distinct id_pasp from spr_info_report where id_grochs = ?', array($id));
        }
        /***********дата, на которую надо выбирать данные *********/
           $date_start = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
            $date_d = new DateTime($date_start);
            $date = $date_d->Format('Y-m-d');

            $today=date("Y-m-d");
              $yesterday=date("Y-m-d", time()-(60*60*24));
               $day_before_yesterday=date("Y-m-d", time()-(60*60*24)-(60*60*24));

               if($date != $today && $date!=$yesterday && $date!= $day_before_yesterday){
                   $date=0;
               }

                  /*********** END дата, на которую надо выбирать данные *********/

        //пробежать по всем ПАСЧ - выбрать инф по деж смене , определить дату и смены, на которую надо позже выбрать отсутствующих

        foreach ($id_pasp as $value) {

            if($date==0){
                 $inf1 = R::getAll('select * from spr_info_report where id_pasp = ?  limit 1', array($value['id_pasp']));
            }
            else{
                  $inf1 = R::getAll('select * from spr_info_report where id_pasp = ? AND dateduty = ? limit 1', array($value['id_pasp'],$date));

                  if(empty($inf1)){
                      $inf1 = R::getAll('select * from spr_info_report where id_pasp = ? AND dateduty = ? limit 1', array($value['id_pasp'],$yesterday));
                  }
            }


            $main[$value['id_pasp']] = array();

            //оформить инф по деж смене в массив : [id_pasp] => array([shtat_ch]=>25,...)
            foreach ($inf1 as $row) {

                $dateduty = $row['dateduty'];
                $ch = $row['ch'];

//                 $main[$value['id_pasp']]['dateduty'] = $dateduty;
//                 $main[$value['id_pasp']]['ch'] = $ch;
                $main[$value['id_pasp']] ['ch'] =  $ch;
                $main[$value['id_pasp']]['name'] = $row['divizion'] . ', ' . $row['organ']; //ПАСЧ-1,Жлобинский РОЧС
                $main[$value['id_pasp']] ['shtat_ch'] = $row['countls'];
                $main[$value['id_pasp']] ['vacant_ch'] = $row['vacant'];
                $main[$value['id_pasp']] ['face'] = $row['face'];
                $main[$value['id_pasp']] ['calc'] = $row['calc'];
                $main[$value['id_pasp']] ['duty'] = $row['duty'];
                $main[$value['id_pasp']] ['gas'] = $row['gas'];
                $main[$value['id_pasp']] ['duty_date1'] = $dateduty;
                $main[$value['id_pasp']] ['duty_date2'] = $dateduty;
                //отсутствующие
                $main[$value['id_pasp']] ['trip'] = getCountTrip($value['id_pasp'], $ch, $dateduty);
                $main[$value['id_pasp']] ['holiday'] = getCountHoliday($value['id_pasp'], $ch, $dateduty);
                $main[$value['id_pasp']] ['ill'] = getCountIll($value['id_pasp'], $ch, $dateduty);
                $main[$value['id_pasp']] ['other'] = getCountOther($value['id_pasp'], $ch, $dateduty);

                //л/с подразделения
                //по штату   по подразделению c ежедневниками
                $main[$value['id_pasp']] ['shtat'] = R::getCell('select count(l.id) as shtat from str.cardch as c '
                                . ' left join str.listfio as l on l.id_cardch=c.id where c.id_card = ? ', array($value['id_pasp']));
                //вакансия по подразделению
                $main[$value['id_pasp']] ['vacant'] = R::getCell('select  sum(m.vacant) from str.main as m where m.id_card = ? ', array($value['id_pasp']));


                /*                 * *******  ФИО, описание работников в  командировке - текст  ******** */
                $trip = R::getAll('SELECT t.id, t.id_fio,date_format(t.date1,"%d-%m-%Y") AS date1,date_format(t.date2,"%d-%m-%Y") AS date2,'
                                . ' t.place,t.is_cosmr, t.prikaz,l.fio, po.name as position FROM trip AS t '
                                . 'inner join listfio AS l ON t.id_fio=l.id inner join str.position as po on po.id=l.id_position inner join cardch AS c ON l.id_cardch=c.id '
                                . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                                . ' AND (( :today BETWEEN t.date1 and t.date2) or(:today  >= t.date1 and t.date2 is NULL)) ', [':id' => $value['id_pasp'], ':ch' => $ch, ':today' => $dateduty]);
                $main[$value['id_pasp']]['trip_inf'] = $trip;

                /*                 * *******  ФИО, описание работников в  отпуске - текст  ******** */
                $holiday = R::getAll('SELECT h.id, h.id_fio,date_format(h.date1,"%d-%m-%Y") AS date1,date_format(h.date2,"%d-%m-%Y") AS date2,'
                                . ' h.prikaz, l.fio, po.name as position FROM holiday AS h '
                                . 'inner join listfio AS l ON h.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id  inner join str.position as po on po.id=l.id_position '
                                . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                                . ' AND (( :today BETWEEN h.date1 and h.date2) or(:today  >= h.date1 and h.date2 is NULL))', [':id' => $value['id_pasp'], ':ch' => $ch, ':today' => $dateduty]);
                $main[$value['id_pasp']]['holiday_inf'] = $holiday;

                /*                 * *******  ФИО, описание работников на больничном - текст  ******** */
                $ill = R::getAll('SELECT i.id, i.id_fio,date_format(i.date1,"%d-%m-%Y") AS date1,date_format(i.date2,"%d-%m-%Y") AS date2,'
                                . ' i.diagnosis,l.fio, ma.name as maim, po.name as position FROM ill AS i inner join listfio AS l '
                                . 'ON i.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id inner join maim AS ma ON i.maim=ma.id inner join str.position as po on po.id=l.id_position '
                                . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                                . 'AND (( :today BETWEEN i.date1 and i.date2) or(:today  >= i.date1 and i.date2 is NULL)) ', [':id' => $value['id_pasp'], ':ch' => $ch, ':today' => $dateduty]);

                $main[$value['id_pasp']]['ill_inf'] = $ill;

                /*                 * *******  ФИО, описание работников в наряде - текст  ******** */
                $main[$value['id_pasp']]['name'] = $row['divizion'] . ', ' . $row['organ']; //ПАСЧ-1,Жлобинский РОЧС
                $main[$value['id_pasp']]['duty_inf'] = $row['fio_duty'];

                /*                 * *******  ФИО, описание работников др.причины - текст  ******** */
                $other = R::getAll('SELECT o.id, o.id_fio,date_format(o.date1,"%d-%m-%Y") AS date1, date_format(o.date2,"%d-%m-%Y") AS date2,'
                                . ' o.reason, o.note, l.fio, po.name as position FROM other AS o inner join listfio AS l '
                                . 'ON o.id_fio=l.id inner join cardch AS c ON l.id_cardch=c.id inner join str.position as po on po.id=l.id_position '
                                . ' WHERE (c.id_card = :id AND c.ch = :ch) '
                                . ' AND (( :today BETWEEN o.date1 and o.date2) or(:today  >= o.date1 and o.date2 is NULL))', [':id' => $value['id_pasp'], ':ch' => $ch, ':today' => $dateduty]);

                $main[$value['id_pasp']]['other_inf'] = $other;

                /*                 * *******  Ваканты - текст  ******** */
                 $vacant_inf = R::getAll('SELECT   l.fio, po.name as position FROM  listfio AS l '
                                . ' left join cardch AS c ON l.id_cardch=c.id inner join str.position as po on po.id=l.id_position '
                                . ' WHERE (c.id_card = :id AND c.ch = :ch) and l.is_vacant = :is_vacant', [':id' => $value['id_pasp'], ':ch' => $ch,':is_vacant'=>1 ]);

                 $main[$value['id_pasp']]['vacant_inf'] = $vacant_inf;
                //bread
                $data['bread_active'] = $row['organ'];
            }
        }
        // echo $data['bread_active'];
        //$data['b']=1;
        // print_r($id_pasp);
        //print_r($main);
        // print_r($text);
        $data['main'] = $main;

        /*------- for fold up menu ------*/
        $data['grochs_active']=  get_id_grochs($id_pasp_active);
        $data['region_active']=  get_id_region($id_pasp_active);
         $data['pasp_active']=$id_pasp_active;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id_pasp_active]);
        /*------- END for fold up menu ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php',$data);
        $app->render('report/spr_inf/bread.php', $data);

         $data['id_grochs']=$id;
        $app->render('report/spr_inf/form.php', $data);//форма с выбором даты


        $app->render('report/spr_inf/show_inf.php', $data);
        $app->render('layouts/footer.php');
    });

         // form with selection date
         $app->get('/spr_info/grochs/:id/:id_pasp', function ($id,$id_pasp) use ($app) {
            //bread
        $data['bread_active'] = 'Выбор даты';

        $data['id_grochs']=$id;


        /*------- for fold up menu ------*/
        $data['grochs_active']=  get_id_grochs($id_pasp);
        $data['region_active']=  get_id_region($id_pasp);
         $data['pasp_active']=$id_pasp;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id_pasp]);
        /*------- END for fold up menu ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php',$data);
        $app->render('report/spr_inf/bread.php', $data);
        $app->render('report/spr_inf/form.php', $data);
        $app->render('layouts/footer.php');
    });

 /*     * *************************  END Spravochnaya inf for spec donesenia ************************************ */


    /*     * ***************************** Большой ОТЧЕТ по технике для Министра ****************************** */
       $app->group('/big_report_teh', function () use ($app) {//report

        /*--------- выполняет запрос к БД для выбора информации по видам техники ---------*/
        function get_data_by_id_teh($date, $array_of_teh) {
            //формирует б/р, резерв, ТО, ремонт
            if (!empty($array_of_teh)) {
                    $mas = R::getAll('SELECT  COUNT(c.id) AS br, COUNT(c1.id) AS reserv, COUNT(c2.id) AS too, COUNT(c3.id) AS repair,'
                                    . ' t.id AS id_teh,t.id_view FROM  ss.technics AS t LEFT JOIN str.car AS c ON (c.id_teh=t.id AND  (c.id_type = 1) AND c.dateduty=:date )'
                                    . ' LEFT JOIN str.car AS c1 ON (c1.id_teh=t.id AND  (c1.id_type = 2) AND c1.dateduty=:date) '
                                    . ' LEFT JOIN str.car AS c2 ON (c2.id_teh=t.id AND  (c2.id_to <> 3) AND c2.dateduty=:date)'
                                    . ' LEFT JOIN str.car AS c3 ON (c3.id_teh=t.id AND  (c3.is_repair=1) AND c3.dateduty=:date) WHERE t.id IN(' . implode(",", $array_of_teh) . ')  GROUP BY t.id_view', [':date' => $date]);

                    foreach ($mas as $value) {
                        $arr[$value['id_view']] = $value;
                    }
                    return $arr;
                    //print_r($arr);

            } else
                return array();
            // print_r (array());
        }

        //форма с выбором даты
         $app->get('/', function () use ($app) {
            //bread
            $data['bread_active'] = 'Техника (общий)';
            $app->render('layouts/header.php');
            $app->render('layouts/menu.php');
            $app->render('report/big_report_teh/bread.php', $data);
            $app->render('report/big_report_teh/form.php', $data);
            $app->render('layouts/footer.php');
        });
        //результат (УГЗ г.Минска не учитывать)
         $app->post('/', function () use ($app) {

            $date_start = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
            $date_d = new DateTime($date_start);
            $date = $date_d->Format('Y-m-d');

// Не учитываем  ЦОУ, ШЛЧС в технике и в л/с

            /* ---------------------------------------- УМЧС кол-во техники ------------------------------------------- */

            //list of regions
            $region = R::getAll('select * from ss.regions');
            foreach ($region as $value) {
                $array_of_teh = array();
                //выбор техники, которая на сег числится в области
                $teh = R::getAssoc("CALL umchs_teh_for_ministry('{$date}','{$value['id']}');");

                if (!empty($teh)) {
                    foreach ($teh as $t) {
                        $array_of_teh[] = $t;
                    }
                    //print_r($teh);
                } else {
                    $array_of_teh = array();
                }
                $result[$value['id']] = get_data_by_id_teh($date, $array_of_teh); //результат по конкретной области
            }
            // print_r($result);
            /* ---------------------------------------- КОНЕЦ УМЧС кол-во техники ------------------------------------------- */

            /* ------------------------------------- РОСН  кол-во техники ---------------------------------------------------- */

            $teh_rosn = R::getAssoc("CALL rosn_teh_for_ministry('{$date}', 8);");
            $array_of_teh = array();
            if (!empty($teh_rosn)) {
                foreach ($teh_rosn as $t) {
                    $array_of_teh[] = $t;
                }
                //print_r($teh);
            } else {
                $array_of_teh = array();
            }
            $result_rosn = get_data_by_id_teh($date, $array_of_teh); //результат по конкретной области
            /* --------------------------------------- END РОСН кол-во техники --------------------------------------------- */

            /* ------------------------------------- УГЗ кол-во техники---------------------------------------------------- */

            $teh_ugz = R::getAssoc("CALL ugz_teh_for_ministry('{$date}', 9, 8);"); //ugz ИППК
            $array_of_teh = array();
            if (!empty($teh_ugz)) {
                foreach ($teh_ugz as $t) {
                    $array_of_teh[] = $t;
                }
                //print_r($teh);
            } else {
                $array_of_teh = array();
            }
            $result_ugz_ippk = get_data_by_id_teh($date, $array_of_teh); //результат IPPK

            $teh_ugz = R::getAssoc("CALL ugz_teh_for_ministry('{$date}', 9, 22);"); //ugz Гомель
            $array_of_teh = array();
            if (!empty($teh_ugz)) {
                foreach ($teh_ugz as $t) {
                    $array_of_teh[] = $t;
                }
                //print_r($teh);
            } else {
                $array_of_teh = array();
            }
            $result_ugz_gii = get_data_by_id_teh($date, $array_of_teh); //результат гомель УГЗ

            $teh_ugz = R::getAssoc("CALL ugz_teh_for_ministry('{$date}', 9, 123);"); //ugz Минск
            $array_of_teh = array();
            if (!empty($teh_ugz)) {
                foreach ($teh_ugz as $t) {
                    $array_of_teh[] = $t;
                }
                //print_r($teh);
            } else {
                $array_of_teh = array();
            }
            $result_ugz_minsk = get_data_by_id_teh($date, $array_of_teh); //результат Минск УГЗ

            /* --------------------------------------- END УГЗ  кол-во техники--------------------------------------------- */


            /* ------------------------------------- Авиация кол-во техники ---------------------------------------------------- */

            $teh_avia = R::getAssoc("CALL rosn_teh_for_ministry('{$date}', 12);");
            $array_of_teh = array();
            if (!empty($teh_avia)) {
                foreach ($teh_avia as $t) {
                    $array_of_teh[] = $t;
                }
                //print_r($teh);
            } else {
                $array_of_teh = array();
            }
            $result_avia = get_data_by_id_teh($date, $array_of_teh); //результат по Avia

            /* --------------------------------------- END Авиация кол-во техники --------------------------------------------- */


            /* --- экспорт в Excel -- */
            $objPHPExcel = new PHPExcel();
            $objReader = PHPExcel_IOFactory::createReader("Excel2007");
            $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/teh_for_command/full_str.xlsx');

//activate worksheet number 1
            $objPHPExcel->setActiveSheetIndex(0);
            $sheet = $objPHPExcel->getActiveSheet();

            /*------ формат заголовка на 17 июля 2018г. -----*/
            $for_day = new DateTime($date);
            $day = $for_day->Format('d');
            $month=$for_day->Format('m');
            $year=$for_day->Format('Y');
            $dd=substr($day, 0,1);//первая цифра дня

            if($dd==0)//если 03 января, то выводить 3 января
            $day=substr($day, 1,1);

                        switch ($month) {
                case '01': $name_month='января';
                    break;
                                case '02': $name_month='февраля';
                    break;
                                case '03': $name_month='марта';
                    break;
                                case '04': $name_month='апреля';
                    break;
                                case '05': $name_month='мая';
                    break;
                                case '06': $name_month='июня';
                    break;
                                case '07': $name_month='июля';
                    break;
                                case '08': $name_month='августа';
                    break;
                                case '09': $name_month='сентября';
                    break;
                                case '10': $name_month='октября';
                    break;
                                case '11': $name_month='ноября';
                    break;
                                case '12': $name_month='декабря';
                    break;

                default: $name_month='января';
                    break;
            }


            $sheet->setCellValue('A1', 'Строевая записка по подразделениям МЧС Республики Беларусь на ' . $day.' '.$name_month.' '.$year.' г.'); //заголовок
            //$view_teh = array(6, 21, 18, 23, 13, 38, 39, 40, 65, 42); //порядок следования техники(ПНС,АР...) - variant1
            $view_teh = array(1, 2, 3, 6, 21, 18, 19, 10, 12, 51, 20, 22, 23, 27, 48, 42); //порядок следования техники(АЦ,АБР...)

            /* ---------------------------------------- вывод кол-во техники УМЧС  ------------------------------------------- */
            $reg = array(1, 2, 4, 5, 3, 6, 7); //порядок следования областей

            if (!empty($result)) {
                foreach ($reg as $i) {
                    if ($i == 1) {//brest
                        //начальная строка и столбец для записи
                        $r = 5;
                        $c = 2;
                    }
                    if ($i == 2) {//витебск
                        //начальная строка и столбец для записи
                        $r = 5;
                        $c = 11;
                    }
                    if ($i == 4) {//гомель
                        //начальная строка и столбец для записи
                        $r = 5;
                        $c = 20;
                    }
                    if ($i == 5) {//гродно
                        //начальная строка и столбец для записи
                        $r = 5;
                        $c = 29;
                    }
                    if ($i == 3) {//минск
                        //начальная строка и столбец для записи
                        $r = 5;
                        $c = 38;
                    }
                    if ($i == 6) {//минская обл
                        //начальная строка и столбец для записи
                        $r = 5;
                        $c = 49;
                    }
                    if ($i == 7) {//могилев
                        //начальная строка и столбец для записи
                        $r = 28;
                        $c = 2;
                    }

                    foreach ($view_teh as $j) {
                        $last_c = $c;
                        if (isset($result[$i][$j]) && !empty($result[$i][$j])) {//ПНС

                           // print_r($result[$i][$j]);

                            //delete value with 0
//                            foreach ($result[$i][$j] as $key=> $value) {
//                               // echo $value  ;
//                                if($value==0){
//
//                                    unset($result[$i][$j][$key]);
//                                }
//                            }
                           // print_r($result[$i][$j]);
                            //exit();

                             if (isset($result[$i][$j]) && !empty($result[$i][$j])) {//ПНС
							 if($result[$i][$j]['br'] != 0)
                                $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result[$i][$j]['br']);
                                $c+=2; //след колонка
								 if($result[$i][$j]['reserv'] != 0)
                                $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result[$i][$j]['reserv']);
                                if ($i == 3) {//минск
                                    $c+=3; //след колонка
                                } else
                                    $c+=2; //след колонка
								 if($result[$i][$j]['too'] != 0)
                                $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result[$i][$j]['too']);
                                if ($i == 3) {//минск
                                    $c+=3; //след колонка
                                } else
                                    $c+=2; //след колонка
								 if($result[$i][$j]['repair'] != 0)
                                $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result[$i][$j]['repair']);
                            }
                        }
                        $r++; //след строка
                        $c = $last_c;
                    }
                }
            } else {//нет данных по РБ
            }
            /* ---------------------------------------- КОНЕЦ вывод кол-во техники УМЧС ------------------------------------------- */

            /* ----------------------------------------  вывод кол-во техники ROSN ------------------------------------------- */

            //начальная строка и столбец для записи
            $r = 28;
            $c = 11;
            if (!empty($result_rosn)) {
                foreach ($view_teh as $j) {
                    $last_c = $c;
                    if (isset($result_rosn[$j]) && !empty($result_rosn[$j])) {//ПНС


                                                    //delete value with 0
//                            foreach ($result_rosn[$j] as $key=> $value) {
//                               // echo $value  ;
//                                if($value==0){
//
//                                    unset($result_rosn[$j][$key]);
//                                }
//                            }

                              if (isset($result_rosn[$j]) && !empty($result_rosn[$j])) {//ПНС
                                  if($result_rosn[$j]['br'] != 0)
                                                         $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_rosn[$j]['br']);
                        $c+=2; //след колонка
						 if($result_rosn[$j]['reserv'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_rosn[$j]['reserv']);

                        $c+=2; //след колонка
						 if($result_rosn[$j]['too'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_rosn[$j]['too']);

                        $c+=2; //след колонка
						 if($result_rosn[$j]['repair'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_rosn[$j]['repair']);

                              }

                    }
                    $r++; //след строка
                    $c = $last_c;
                }
            }

            /* ---------------------------------------- КОНЕЦ вывод кол-во техники ROSN ------------------------------------------- */

            /* ----------------------------------------  вывод кол-во техники УГЗ Борисов ------------------------------------------- */

            //начальная строка и столбец для записи
            $r = 28;
            $c = 20;
            if (!empty($result_ugz_ippk)) {
                foreach ($view_teh as $j) {
                    $last_c = $c;
                    if (isset($result_ugz_ippk[$j]) && !empty($result_ugz_ippk[$j])) {//ПНС


                                                                            //delete value with 0
//                            foreach ($result_ugz_ippk[$j] as $key=> $value) {
//                               // echo $value  ;
//                                if($value==0){
//
//                                    unset($result_ugz_ippk[$j][$key]);
//                                }
//                            }

                                   if (isset($result_ugz_ippk[$j]) && !empty($result_ugz_ippk[$j])) {//ПНС

							  if($result_ugz_ippk[$j]['br'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_ippk[$j]['br']);
                            $c+=2; //след колонка
							 if($result_ugz_ippk[$j]['reserv'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_ippk[$j]['reserv']);

                            $c+=2; //след колонка
							 if($result_ugz_ippk[$j]['too'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_ippk[$j]['too']);

                            $c+=2; //след колонка
							 if($result_ugz_ippk[$j]['repair'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_ippk[$j]['repair']);
                        }
                    }
                    $r++; //след строка
                    $c = $last_c;
                }
            }

            /* ---------------------------------------- КОНЕЦ вывод  кол-во техники УГЗ Борисов ------------------------------------------- */

            /* ----------------------------------------  вывод кол-во техники УГЗ Гомель ------------------------------------------- */

            //начальная строка и столбец для записи
            $r = 28;
            $c = 29;
            if (!empty($result_ugz_gii)) {
                foreach ($view_teh as $j) {
                    $last_c = $c;
                    if (isset($result_ugz_gii[$j]) && !empty($result_ugz_gii[$j])) {//ПНС


                                                                            //delete value with 0
//                            foreach ($result_ugz_gii[$j] as $key=> $value) {
//                               // echo $value  ;
//                                if($value==0){
//
//                                    unset($result_ugz_gii[$j][$key]);
//                                }
//                            }

                          if (isset($result_ugz_gii[$j]) && !empty($result_ugz_gii[$j])) {//ПНС

							   if($result_ugz_gii[$j]['br'] != 0)
                                   $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_gii[$j]['br']);
                        $c+=2; //след колонка
						if($result_ugz_gii[$j]['reserv'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_gii[$j]['reserv']);

                        $c+=2; //след колонка
						if($result_ugz_gii[$j]['too'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_gii[$j]['too']);

                        $c+=2; //след колонка
						if($result_ugz_gii[$j]['repair'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_gii[$j]['repair']);
                              }

                    }
                    $r++; //след строка
                    $c = $last_c;
                }
            }

            /* ---------------------------------------- КОНЕЦ вывод кол-во техники УГЗ Гомель ------------------------------------------- */


            /* ----------------------------------------  вывод  кол-во техники  УГЗ Минск ------------------------------------------- */

            //начальная строка и столбец для записи
            $r = 28;
            $c = 38;
            if (!empty($result_ugz_minsk)) {
                foreach ($view_teh as $j) {
                    $last_c = $c;
                    if (isset($result_ugz_minsk[$j]) && !empty($result_ugz_minsk[$j])) {//ПНС


                                                                            //delete value with 0
//                            foreach ($result_ugz_minsk[$j] as $key=> $value) {
//                               // echo $value  ;
//                                if($value==0){
//
//                                    unset($result_ugz_minsk[$j][$key]);
//                                }
//                            }

                          if (isset($result_ugz_minsk[$j]) && !empty($result_ugz_minsk[$j])) {//ПНС

								   if($result_ugz_minsk[$j]['br'] != 0)
                                    $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_minsk[$j]['br']);
                        $c+=2; //след колонка
						if($result_ugz_minsk[$j]['reserv'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_minsk[$j]['reserv']);

                        $c+=2; //след колонка
						if($result_ugz_minsk[$j]['too'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_minsk[$j]['too']);

                        $c+=2; //след колонка
						if($result_ugz_minsk[$j]['repair'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_minsk[$j]['repair']);
                               }


                    }
                    $r++; //след строка
                    $c = $last_c;
                }
            }

            /* ---------------------------------------- КОНЕЦ вывод кол-во техники  УГЗ Минск ------------------------------------------- */


            /* ----------------------------------------  вывод  кол-во техники  AVIA ------------------------------------------- */

            //начальная строка и столбец для записи
            $r = 28;
            $c = 47;
            if (!empty($result_avia)) {
                foreach ($view_teh as $j) {
                    $last_c = $c;
                    if (isset($result_avia[$j]) && !empty($result_avia[$j])) {//ПНС

                                                                                                    //delete value with 0
//                        foreach ($result_avia[$j] as $key => $value) {
//                            // echo $value  ;
//                            if ($value == 0) {
//
//                                unset($result_avia[$j][$key]);
//                            }
//                        }

                        if (isset($result_avia[$j]) && !empty($result_avia[$j])) {//ПНС

						if($result_avia[$j]['br'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_avia[$j]['br']);
                            $c+=1; //след колонка

							if($result_avia[$j]['reserv'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_avia[$j]['reserv']);

                            $c+=1; //след колонка
							if($result_avia[$j]['too'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_avia[$j]['too']);

                            $c+=1; //след колонка
							if($result_avia[$j]['repair'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_avia[$j]['repair']);
                        }
                    }
                    $r++; //след строка
                    $c = $last_c;
                }
            }

            /* ---------------------------------------- КОНЕЦ вывод  кол-во техники AVIA ------------------------------------------- */

            /* -------------------------------------  по списку, налицо, б/р, наряд  цифры указаны БЕЗ ЦОУ и ПТЦ ----------------------------------------- */

            /* ------------  УМЧС  л/с --------------- */

            $umchs_ls = R::getAssoc("CALL umchs_ls_for_ministry('{$date}');");

            if (isset($umchs_ls) && !empty($umchs_ls)) {//отобразить
                $array_of_ls = array();

                foreach ($umchs_ls as $u) {
                    $array_of_ls[$u['region_id']] = $u;
                }


                foreach ($reg as $i) {
                    if ($i == 1) {//brest
                        //начальная строка и столбец для записи
                        $r = 22;
                        $list_c = 2;
                        $face_c = 3;
                        $br_c = 4;
                        $duty_c = 5;
                    }
                    if ($i == 2) {//витебск
                        //начальная строка и столбец для записи
                        $r = 22;
                        $list_c = 11;
                        $face_c = 12;
                        $br_c = 13;
                        $duty_c = 14;
                    }
                    if ($i == 4) {//гомель
                        //начальная строка и столбец для записи
                        $r = 22;
                        $list_c = 20;
                        $face_c = 21;
                        $br_c = 22;
                        $duty_c = 23;
                    }
                    if ($i == 5) {//гродно
                        //начальная строка и столбец для записи
                        $r = 22;
                        $list_c = 29;
                        $face_c = 30;
                        $br_c = 31;
                        $duty_c = 32;
                    }
                    if ($i == 3) {//минск
                        //начальная строка и столбец для записи
                        $r = 22;
                        $list_c = 38;
                        $face_c = 39;
                        $br_c = 40;
                        $duty_c = 41;
                    }
                    if ($i == 6) {//минская обл
                        //начальная строка и столбец для записи
                        $r = 22;
                        $list_c = 49;
                        $face_c = 50;
                        $br_c = 51;
                        $duty_c = 52;
                    }
                    if ($i == 7) {//могилев
                        //начальная строка и столбец для записи
                        $r = 45;
                        $list_c = 2;
                        $face_c = 3;
                        $br_c = 4;
                        $duty_c = 5;
                    }

                    if (isset($array_of_ls[$i]) && !empty($array_of_ls[$i])) {
                        $sheet->setCellValueExplicitByColumnAndRow($list_c, $r, $array_of_ls[$i]['on_list']); //по списку
                        $sheet->setCellValueExplicitByColumnAndRow($face_c, $r, $array_of_ls[$i]['face']); //налицо
                        $sheet->setCellValueExplicitByColumnAndRow($br_c, $r, $array_of_ls[$i]['calc']); //б/р
                        $sheet->setCellValueExplicitByColumnAndRow($duty_c, $r, $array_of_ls[$i]['duty']); //наряд
                        $sheet->setCellValueExplicitByColumnAndRow($br_c, $r + 1, $array_of_ls[$i]['gas']); //газодымозащитники
                    }
                }
            }

            /* ------------  КОНЕЦ  УМЧС  л/с  --------------- */



            /* ------------ ROSN, AVIA л/с  --------------- */

            $rosn_ls = R::getAssoc("CALL rosn_ls_for_ministry('{$date}');");

            if (isset($rosn_ls) && !empty($rosn_ls)) {//отобразить
                $array_of_ls = array();

                foreach ($rosn_ls as $u) {
                    $array_of_ls[$u['organ_id']] = $u;
                }


                $organs = array(8, 12);
                foreach ($organs as $i) {
                    if ($i == 8) {//rosn
                        //начальная строка и столбец для записи
                        $r = 45;
                        $list_c = 11;
                        $face_c = 12;
                        $br_c = 13;
                        $duty_c = 14;
                    }
                    if ($i == 12) {//avia
                        //начальная строка и столбец для записи
                        $r = 45;
                        $list_c = 47;
                        $face_c = 48;
                        $br_c = 49;
                        $duty_c = 50;
                    }


                    if (isset($array_of_ls[$i]) && !empty($array_of_ls[$i])) {
                        $sheet->setCellValueExplicitByColumnAndRow($list_c, $r, $array_of_ls[$i]['on_list']); //по списку
                        $sheet->setCellValueExplicitByColumnAndRow($face_c, $r, $array_of_ls[$i]['face']); //налицо
                        $sheet->setCellValueExplicitByColumnAndRow($br_c, $r, $array_of_ls[$i]['calc']); //б/р
                        $sheet->setCellValueExplicitByColumnAndRow($duty_c, $r, $array_of_ls[$i]['duty']); //наряд
                        $sheet->setCellValueExplicitByColumnAndRow($br_c, $r + 1, $array_of_ls[$i]['gas']); //газодымозащитники
                    }
                }
            }

            /* ------------  КОНЕЦ  ROSN, avia л/с  --------------- */


            /* ------------ UGZ л/с  --------------- */

            $ugz_ls = R::getAssoc("CALL ugz_ls_for_ministry('{$date}',9);");

            if (isset($ugz_ls) && !empty($ugz_ls)) {//отобразить
                $array_of_ls = array();

                foreach ($ugz_ls as $u) {
                    $array_of_ls[$u['local_id']] = $u;
                }


                $loc = array(8, 22, 123); //id_local of UGZ - ippk borisov, gomel, minsk
                foreach ($loc as $i) {
                    if ($i == 8) {//ippk borisov
                        //начальная строка и столбец для записи
                        $r = 45;
                        $list_c = 20;
                        $face_c = 21;
                        $br_c = 22;
                        $duty_c = 23;
                    }
                    if ($i == 22) {//gomel
                        //начальная строка и столбец для записи
                        $r = 45;
                        $list_c = 29;
                        $face_c = 30;
                        $br_c = 31;
                        $duty_c = 32;
                    }
                    if ($i == 123) {//минск
                        //начальная строка и столбец для записи
                        $r = 45;
                        $list_c = 38;
                        $face_c = 39;
                        $br_c = 40;
                        $duty_c = 41;
                    }


                    if (isset($array_of_ls[$i]) && !empty($array_of_ls[$i])) {
                        $sheet->setCellValueExplicitByColumnAndRow($list_c, $r, $array_of_ls[$i]['on_list']); //по списку
                        $sheet->setCellValueExplicitByColumnAndRow($face_c, $r, $array_of_ls[$i]['face']); //налицо
                        $sheet->setCellValueExplicitByColumnAndRow($br_c, $r, $array_of_ls[$i]['calc']); //б/р
                        $sheet->setCellValueExplicitByColumnAndRow($duty_c, $r, $array_of_ls[$i]['duty']); //наряд
                        $sheet->setCellValueExplicitByColumnAndRow($br_c, $r + 1, $array_of_ls[$i]['gas']); //газодымозащитники
                    }
                }
            }

            /* ------------  КОНЕЦ  UGZ л/с  --------------- */


            /* -------------------------------------   КОНЕЦ по списку, налицо, б/р, наряд  цифры указаны БЕЗ ЦОУ ----------------------------------------- */


            /* ----------------------------------------------------------   бол, отпуск, комндировки, др.причины  цифры указаны  С ЦОУ ---------------------------------------------------------------------------------------- */

            /* ------------  УМЧС absent --------------- */

            $umchs_absent = R::getAssoc("CALL umchs_absent_for_ministry('{$date}');");

            if (isset($umchs_absent) && !empty($umchs_absent)) {//отобразить
                $array_of_ls = array();

                foreach ($umchs_absent as $u) {
                    $array_of_ls[$u['region_id']][$u['sign']] = $u['c']; //кол-во отсутствующих положить в массив с ключом области и номера отсутствующего(1-отпуск...)
                }


                foreach ($reg as $i) {
                    if ($i == 1) {//brest
                        //начальная строка и столбец для записи
                        $r = 22;
                        $hol_c = 6;
                        $ill_c = 7;
                        $other_c = 8;
                        $trip_c = 10;
                    }
                    if ($i == 2) {//витебск
                        //начальная строка и столбец для записи
                        $r = 22;
                        $hol_c = 15;
                        $ill_c = 16;
                        $other_c = 17;
                        $trip_c = 19;
                    }
                    if ($i == 4) {//гомель
                        //начальная строка и столбец для записи
                        $r = 22;
                        $hol_c = 24;
                        $ill_c = 25;
                        $other_c = 26;
                        $trip_c = 28;
                    }
                    if ($i == 5) {//гродно
                        //начальная строка и столбец для записи
                        $r = 22;
                        $hol_c = 33;
                        $ill_c = 34;
                        $other_c = 35;
                        $trip_c = 37;
                    }
                    if ($i == 3) {//минск
                        //начальная строка и столбец для записи
                        $r = 22;
                        $hol_c = 42;
                        $ill_c = 43;
                        $other_c = 44;
                        $trip_c = 48;
                    }
                    if ($i == 6) {//минская обл
                        //начальная строка и столбец для записи
                        $r = 22;
                        $hol_c = 53;
                        $ill_c = 54;
                        $other_c = 55;
                        $trip_c = 56;
                    }
                    if ($i == 7) {//могилев
                        //начальная строка и столбец для записи
                        $r = 45;
                        $hol_c = 6;
                        $ill_c = 7;
                        $other_c = 8;
                        $trip_c = 10;
                    }


                    for ($j = 1; $j <= 4; $j++) {//все виды отсутствующих
                        if (isset($array_of_ls[$i][$j]) && !empty($array_of_ls[$i][$j])) {
                            if ($j == 1) {//holiday
                                $sheet->setCellValueExplicitByColumnAndRow($hol_c, $r, $array_of_ls[$i][$j]);
                            } elseif ($j == 2) {// ill
                                $sheet->setCellValueExplicitByColumnAndRow($ill_c, $r, $array_of_ls[$i][$j]);
                            } elseif ($j == 3) {//other
                                $sheet->setCellValueExplicitByColumnAndRow($other_c, $r, $array_of_ls[$i][$j]);
                            } elseif ($j == 4) {//trip
                                $sheet->setCellValueExplicitByColumnAndRow($trip_c, $r, $array_of_ls[$i][$j]);
                            }
                        }
                    }
                }
            }

            /* ------------  КОНЕЦ  УМЧС absent --------------- */



            /* ------------ ROSN absent --------------- */

            $rosn_absent = R::getAssoc("CALL rosn_avia_absent_for_ministry('{$date}',8);");

            if (isset($rosn_absent) && !empty($rosn_absent)) {//отобразить
                $array_of_ls = array();

                foreach ($rosn_absent as $u) {
                    $array_of_ls[$u['sign']] = $u['c'];
                }

                // rosn начальная строка и столбец для записи
                $r = 45;
                $hol_c = 15;
                $ill_c = 16;
                $other_c = 17;
                $trip_c = 19;


                for ($j = 1; $j <= 4; $j++) {//все виды отсутствующих
                    if (isset($array_of_ls[$j]) && !empty($array_of_ls[$j])) {
                        if ($j == 1) {//holiday
                            $sheet->setCellValueExplicitByColumnAndRow($hol_c, $r, $array_of_ls[$j]);
                        } elseif ($j == 2) {// ill
                            $sheet->setCellValueExplicitByColumnAndRow($ill_c, $r, $array_of_ls[$j]);
                        } elseif ($j == 3) {//other
                            $sheet->setCellValueExplicitByColumnAndRow($other_c, $r, $array_of_ls[$j]);
                        } elseif ($j == 4) {//trip
                            $sheet->setCellValueExplicitByColumnAndRow($trip_c, $r, $array_of_ls[$j]);
                        }
                    }
                }
            }

            /* ------------  КОНЕЦ  ROSN absent --------------- */

            /* ------------  AVIA absent --------------- */
            unset($rosn_absent);
            $rosn_absent = R::getAssoc("CALL rosn_avia_absent_for_ministry('{$date}',12);");

            if (isset($rosn_absent) && !empty($rosn_absent)) {//отобразить
                $array_of_ls = array();

                foreach ($rosn_absent as $u) {
                    $array_of_ls[$u['sign']] = $u['c'];
                }

                // rosn начальная строка и столбец для записи
                $r = 45;
                $hol_c = 51;
                $ill_c = 52;
                $other_c = 53;
                $trip_c = 55;


                for ($j = 1; $j <= 4; $j++) {//все виды отсутствующих
                    if (isset($array_of_ls[$j]) && !empty($array_of_ls[$j])) {
                        if ($j == 1) {//holiday
                            $sheet->setCellValueExplicitByColumnAndRow($hol_c, $r, $array_of_ls[$j]);
                        } elseif ($j == 2) {// ill
                            $sheet->setCellValueExplicitByColumnAndRow($ill_c, $r, $array_of_ls[$j]);
                        } elseif ($j == 3) {//other
                            $sheet->setCellValueExplicitByColumnAndRow($other_c, $r, $array_of_ls[$j]);
                        } elseif ($j == 4) {//trip
                            $sheet->setCellValueExplicitByColumnAndRow($trip_c, $r, $array_of_ls[$j]);
                        }
                    }
                }
            }

            /* ------------  КОНЕЦ   avia absent --------------- */


            /* ------------ UGZ absent --------------- */

            $ugz_absent = R::getAssoc("CALL ugz_absent_for_ministry('{$date}',9);");

            if (isset($ugz_absent) && !empty($ugz_absent)) {//отобразить
                $array_of_ls = array();

                foreach ($ugz_absent as $u) {
                    $array_of_ls[$u['local_id']][$u['sign']] = $u['c'];
                }


                $loc = array(8, 22, 123); //id_local of UGZ - ippk borisov, gomel, minsk
                foreach ($loc as $i) {
                    if ($i == 8) {//ippk borisov
                        //начальная строка и столбец для записи
                        $r = 45;
                        $hol_c = 24;
                        $ill_c = 25;
                        $other_c = 26;
                        $trip_c = 28;
                    }
                    if ($i == 22) {//gomel
                        //начальная строка и столбец для записи
                        $r = 45;
                        $hol_c = 33;
                        $ill_c = 34;
                        $other_c = 35;
                        $trip_c = 37;
                    }
                    if ($i == 123) {//минск
                        //начальная строка и столбец для записи
                        $r = 45;
                        $hol_c = 42;
                        $ill_c = 43;
                        $other_c = 44;
                        $trip_c = 46;
                    }


                    for ($j = 1; $j <= 4; $j++) {//все виды отсутствующих
                        if (isset($array_of_ls[$i][$j]) && !empty($array_of_ls[$i][$j])) {
                            if ($j == 1) {//holiday
                                $sheet->setCellValueExplicitByColumnAndRow($hol_c, $r, $array_of_ls[$i][$j]);
                            } elseif ($j == 2) {// ill
                                $sheet->setCellValueExplicitByColumnAndRow($ill_c, $r, $array_of_ls[$i][$j]);
                            } elseif ($j == 3) {//other
                                $sheet->setCellValueExplicitByColumnAndRow($other_c, $r, $array_of_ls[$i][$j]);
                            } elseif ($j == 4) {//trip
                                $sheet->setCellValueExplicitByColumnAndRow($trip_c, $r, $array_of_ls[$i][$j]);
                            }
                        }
                    }
                }
            }

            /* ------------  КОНЕЦ  UGZ absent --------------- */

            /* -------------------------------------   КОНЕЦ   бол, отпуск, комндировки, др.причины  цифры указаны С ЦОУ ----------------------------------------- */



            /* Сохранить в файл */
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="teh_for_command.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        });
    });


           $app->group('/big_report_teh2', function () use ($app) {//report

        //форма с выбором даты
         $app->get('/', function () use ($app) {
            //bread
            $data['bread_active'] = 'Техника (руководство)';
            $app->render('layouts/header.php');
            $app->render('layouts/menu.php');
            $app->render('report/big_report_teh/bread.php', $data);
            $app->render('report/big_report_teh/form2.php', $data);
            $app->render('layouts/footer.php');
        });
        //результат (УГЗ г.Минска не учитывать)
         $app->post('/', function () use ($app) {

            $date_start = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
            $date_d = new DateTime($date_start);
            $date = $date_d->Format('Y-m-d');

// Не учитываем  ЦОУ, ШЛЧС в технике и в л/с

            /* ---------------------------------------- УМЧС кол-во техники ------------------------------------------- */

            //list of regions
            $region = R::getAll('select * from ss.regions');
            foreach ($region as $value) {
                $array_of_teh = array();
                //выбор техники, которая на сег числится в области
                $teh = R::getAssoc("CALL umchs_teh_for_ministry('{$date}','{$value['id']}');");

                if (!empty($teh)) {
                    foreach ($teh as $t) {
                        $array_of_teh[] = $t;
                    }
                    //print_r($teh);
                } else {
                    $array_of_teh = array();
                }
                $result[$value['id']] = get_data_by_id_teh($date, $array_of_teh); //результат по конкретной области
            }
            // print_r($result);
            /* ---------------------------------------- КОНЕЦ УМЧС кол-во техники ------------------------------------------- */

            /* ------------------------------------- РОСН  кол-во техники ---------------------------------------------------- */

            $teh_rosn = R::getAssoc("CALL rosn_teh_for_ministry('{$date}', 8);");
            $array_of_teh = array();
            if (!empty($teh_rosn)) {
                foreach ($teh_rosn as $t) {
                    $array_of_teh[] = $t;
                }
                //print_r($teh);
            } else {
                $array_of_teh = array();
            }
            $result_rosn = get_data_by_id_teh($date, $array_of_teh); //результат по конкретной области
            /* --------------------------------------- END РОСН кол-во техники --------------------------------------------- */

            /* ------------------------------------- УГЗ кол-во техники---------------------------------------------------- */

            $teh_ugz = R::getAssoc("CALL ugz_teh_for_ministry('{$date}', 9, 8);"); //ugz ИППК
            $array_of_teh = array();
            if (!empty($teh_ugz)) {
                foreach ($teh_ugz as $t) {
                    $array_of_teh[] = $t;
                }
                //print_r($teh);
            } else {
                $array_of_teh = array();
            }
            $result_ugz_ippk = get_data_by_id_teh($date, $array_of_teh); //результат IPPK

            $teh_ugz = R::getAssoc("CALL ugz_teh_for_ministry('{$date}', 9, 22);"); //ugz Гомель
            $array_of_teh = array();
            if (!empty($teh_ugz)) {
                foreach ($teh_ugz as $t) {
                    $array_of_teh[] = $t;
                }
                //print_r($teh);
            } else {
                $array_of_teh = array();
            }
            $result_ugz_gii = get_data_by_id_teh($date, $array_of_teh); //результат гомель УГЗ

            $teh_ugz = R::getAssoc("CALL ugz_teh_for_ministry('{$date}', 9, 123);"); //ugz Минск
            $array_of_teh = array();
            if (!empty($teh_ugz)) {
                foreach ($teh_ugz as $t) {
                    $array_of_teh[] = $t;
                }
                //print_r($teh);
            } else {
                $array_of_teh = array();
            }
            $result_ugz_minsk = get_data_by_id_teh($date, $array_of_teh); //результат Минск УГЗ

            /* --------------------------------------- END УГЗ  кол-во техники--------------------------------------------- */


            /* ------------------------------------- Авиация кол-во техники ---------------------------------------------------- */

            $teh_avia = R::getAssoc("CALL rosn_teh_for_ministry('{$date}', 12);");
            $array_of_teh = array();
            if (!empty($teh_avia)) {
                foreach ($teh_avia as $t) {
                    $array_of_teh[] = $t;
                }
                //print_r($teh);
            } else {
                $array_of_teh = array();
            }
            $result_avia = get_data_by_id_teh($date, $array_of_teh); //результат по Avia

            /* --------------------------------------- END Авиация кол-во техники --------------------------------------------- */


            /* --- экспорт в Excel -- */
            $objPHPExcel = new PHPExcel();
            $objReader = PHPExcel_IOFactory::createReader("Excel2007");
            $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/teh_for_command/full_str_2.xlsx');
//activate worksheet number 1
            $objPHPExcel->setActiveSheetIndex(0);
            $sheet = $objPHPExcel->getActiveSheet();

            /*------ формат заголовка на 17 июля 2018г. -----*/
            $for_day = new DateTime($date);
            $day = $for_day->Format('d');
            $month=$for_day->Format('m');
            $year=$for_day->Format('Y');
            $dd=substr($day, 0,1);//первая цифра дня

            if($dd==0)//если 03 января, то выводить 3 января
            $day=substr($day, 1,1);

                        switch ($month) {
                case '01': $name_month='января';
                    break;
                                case '02': $name_month='февраля';
                    break;
                                case '03': $name_month='марта';
                    break;
                                case '04': $name_month='апреля';
                    break;
                                case '05': $name_month='мая';
                    break;
                                case '06': $name_month='июня';
                    break;
                                case '07': $name_month='июля';
                    break;
                                case '08': $name_month='августа';
                    break;
                                case '09': $name_month='сентября';
                    break;
                                case '10': $name_month='октября';
                    break;
                                case '11': $name_month='ноября';
                    break;
                                case '12': $name_month='декабря';
                    break;

                default: $name_month='января';
                    break;
            }


            $sheet->setCellValue('A1', 'Строевая записка по подразделениям МЧС Республики Беларусь на ' . $day.' '.$name_month.' '.$year.' г.'); //заголовок
            //$view_teh = array(6, 21, 18, 23, 13, 38, 39, 40, 65, 42); //порядок следования техники(ПНС,АР...) - variant1
            $view_teh = array(1, 2, 3, 6, 21, 18, 19, 10, 12, 51, 20, 22, 23, 27, 48, 42); //порядок следования техники(АЦ,АБР...)

            /* ---------------------------------------- вывод кол-во техники УМЧС  ------------------------------------------- */
            $reg = array(1, 2, 4, 5, 3, 6, 7); //порядок следования областей

            if (!empty($result)) {
                foreach ($reg as $i) {
                    if ($i == 1) {//brest
                        //начальная строка и столбец для записи
                        $r = 5;
                        $c = 2;
                    }
                    if ($i == 2) {//витебск
                        //начальная строка и столбец для записи
                        $r = 5;
                        $c = 6;
                    }
                    if ($i == 4) {//гомель
                        //начальная строка и столбец для записи
                        $r = 5;
                        $c = 10;
                    }
                    if ($i == 5) {//гродно
                        //начальная строка и столбец для записи
                        $r = 5;
                        $c = 14;
                    }
                    if ($i == 3) {//минск
                        //начальная строка и столбец для записи
                        $r = 5;
                        $c = 18;
                    }
                    if ($i == 6) {//минская обл
                        //начальная строка и столбец для записи
                        $r = 5;
                        $c = 22;
                    }
                    if ($i == 7) {//могилев
                        //начальная строка и столбец для записи
                        $r = 25;
                        $c = 2;
                    }

                    foreach ($view_teh as $j) {
                        $last_c = $c;
                        if (isset($result[$i][$j]) && !empty($result[$i][$j])) {//ПНС

                           // print_r($result[$i][$j]);

                            //delete value with 0
//                            foreach ($result[$i][$j] as $key=> $value) {
//                               // echo $value  ;
//                                if($value==0){
//
//                                    unset($result[$i][$j][$key]);
//                                }
//                            }
                           // print_r($result[$i][$j]);
                            //exit();

                             if (isset($result[$i][$j]) && !empty($result[$i][$j])) {//ПНС
							 if($result[$i][$j]['br'] != 0)
                                $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result[$i][$j]['br']);
                                $c+=1; //след колонка
								 if($result[$i][$j]['reserv'] != 0)
                                $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result[$i][$j]['reserv']);

                                    $c+=1; //след колонка
								 if($result[$i][$j]['too'] != 0)
                                $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result[$i][$j]['too']);

                                    $c+=1; //след колонка
								 if($result[$i][$j]['repair'] != 0)
                                $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result[$i][$j]['repair']);
                            }
                        }
                        $r++; //след строка
                        $c = $last_c;
                    }
                }
            } else {//нет данных по РБ
            }
            /* ---------------------------------------- КОНЕЦ вывод кол-во техники УМЧС ------------------------------------------- */

            /* ----------------------------------------  вывод кол-во техники ROSN ------------------------------------------- */

            //начальная строка и столбец для записи
            $r = 25;
            $c = 6;
            if (!empty($result_rosn)) {
                foreach ($view_teh as $j) {
                    $last_c = $c;
                    if (isset($result_rosn[$j]) && !empty($result_rosn[$j])) {//ПНС


                                                    //delete value with 0
//                            foreach ($result_rosn[$j] as $key=> $value) {
//                               // echo $value  ;
//                                if($value==0){
//
//                                    unset($result_rosn[$j][$key]);
//                                }
//                            }

                              if (isset($result_rosn[$j]) && !empty($result_rosn[$j])) {//ПНС
                                  if($result_rosn[$j]['br'] != 0)
                                                         $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_rosn[$j]['br']);
                        $c+=1; //след колонка
						 if($result_rosn[$j]['reserv'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_rosn[$j]['reserv']);

                        $c+=1; //след колонка
						 if($result_rosn[$j]['too'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_rosn[$j]['too']);

                        $c+=1; //след колонка
						 if($result_rosn[$j]['repair'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_rosn[$j]['repair']);

                              }

                    }
                    $r++; //след строка
                    $c = $last_c;
                }
            }

            /* ---------------------------------------- КОНЕЦ вывод кол-во техники ROSN ------------------------------------------- */

            /* ----------------------------------------  вывод кол-во техники УГЗ Борисов ------------------------------------------- */

            //начальная строка и столбец для записи
            $r = 25;
            $c = 10;
            if (!empty($result_ugz_ippk)) {
                foreach ($view_teh as $j) {
                    $last_c = $c;
                    if (isset($result_ugz_ippk[$j]) && !empty($result_ugz_ippk[$j])) {//ПНС


                                                                            //delete value with 0
//                            foreach ($result_ugz_ippk[$j] as $key=> $value) {
//                               // echo $value  ;
//                                if($value==0){
//
//                                    unset($result_ugz_ippk[$j][$key]);
//                                }
//                            }

                                   if (isset($result_ugz_ippk[$j]) && !empty($result_ugz_ippk[$j])) {//ПНС

							  if($result_ugz_ippk[$j]['br'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_ippk[$j]['br']);
                            $c+=1; //след колонка
							 if($result_ugz_ippk[$j]['reserv'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_ippk[$j]['reserv']);

                            $c+=1; //след колонка
							 if($result_ugz_ippk[$j]['too'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_ippk[$j]['too']);

                            $c+=1; //след колонка
							 if($result_ugz_ippk[$j]['repair'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_ippk[$j]['repair']);
                        }
                    }
                    $r++; //след строка
                    $c = $last_c;
                }
            }

            /* ---------------------------------------- КОНЕЦ вывод  кол-во техники УГЗ Борисов ------------------------------------------- */

            /* ----------------------------------------  вывод кол-во техники УГЗ Гомель ------------------------------------------- */

            //начальная строка и столбец для записи
            $r = 25;
            $c = 14;
            if (!empty($result_ugz_gii)) {
                foreach ($view_teh as $j) {
                    $last_c = $c;
                    if (isset($result_ugz_gii[$j]) && !empty($result_ugz_gii[$j])) {//ПНС


                                                                            //delete value with 0
//                            foreach ($result_ugz_gii[$j] as $key=> $value) {
//                               // echo $value  ;
//                                if($value==0){
//
//                                    unset($result_ugz_gii[$j][$key]);
//                                }
//                            }

                          if (isset($result_ugz_gii[$j]) && !empty($result_ugz_gii[$j])) {//ПНС

							   if($result_ugz_gii[$j]['br'] != 0)
                                   $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_gii[$j]['br']);
                        $c+=1; //след колонка
						if($result_ugz_gii[$j]['reserv'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_gii[$j]['reserv']);

                        $c+=1; //след колонка
						if($result_ugz_gii[$j]['too'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_gii[$j]['too']);

                        $c+=1; //след колонка
						if($result_ugz_gii[$j]['repair'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_gii[$j]['repair']);
                              }

                    }
                    $r++; //след строка
                    $c = $last_c;
                }
            }

            /* ---------------------------------------- КОНЕЦ вывод кол-во техники УГЗ Гомель ------------------------------------------- */


            /* ----------------------------------------  вывод  кол-во техники  УГЗ Минск ------------------------------------------- */

            //начальная строка и столбец для записи
            $r = 25;
            $c = 18;
            if (!empty($result_ugz_minsk)) {
                foreach ($view_teh as $j) {
                    $last_c = $c;
                    if (isset($result_ugz_minsk[$j]) && !empty($result_ugz_minsk[$j])) {//ПНС


                                                                            //delete value with 0
//                            foreach ($result_ugz_minsk[$j] as $key=> $value) {
//                               // echo $value  ;
//                                if($value==0){
//
//                                    unset($result_ugz_minsk[$j][$key]);
//                                }
//                            }

                          if (isset($result_ugz_minsk[$j]) && !empty($result_ugz_minsk[$j])) {//ПНС

								   if($result_ugz_minsk[$j]['br'] != 0)
                                    $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_minsk[$j]['br']);
                        $c+=1; //след колонка
						if($result_ugz_minsk[$j]['reserv'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_minsk[$j]['reserv']);

                        $c+=1; //след колонка
						if($result_ugz_minsk[$j]['too'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_minsk[$j]['too']);

                        $c+=1; //след колонка
						if($result_ugz_minsk[$j]['repair'] != 0)
                        $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_ugz_minsk[$j]['repair']);
                               }


                    }
                    $r++; //след строка
                    $c = $last_c;
                }
            }

            /* ---------------------------------------- КОНЕЦ вывод кол-во техники  УГЗ Минск ------------------------------------------- */


            /* ----------------------------------------  вывод  кол-во техники  AVIA ------------------------------------------- */

            //начальная строка и столбец для записи
            $r = 25;
            $c = 22;
            if (!empty($result_avia)) {
                foreach ($view_teh as $j) {
                    $last_c = $c;
                    if (isset($result_avia[$j]) && !empty($result_avia[$j])) {//ПНС

                                                                                                    //delete value with 0
//                        foreach ($result_avia[$j] as $key => $value) {
//                            // echo $value  ;
//                            if ($value == 0) {
//
//                                unset($result_avia[$j][$key]);
//                            }
//                        }

                        if (isset($result_avia[$j]) && !empty($result_avia[$j])) {//ПНС

						if($result_avia[$j]['br'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_avia[$j]['br']);
                            $c+=1; //след колонка

							if($result_avia[$j]['reserv'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_avia[$j]['reserv']);

                            $c+=1; //след колонка
							if($result_avia[$j]['too'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_avia[$j]['too']);

                            $c+=1; //след колонка
							if($result_avia[$j]['repair'] != 0)
                            $sheet->setCellValueExplicitByColumnAndRow($c, $r, $result_avia[$j]['repair']);
                        }
                    }
                    $r++; //след строка
                    $c = $last_c;
                }
            }

            /* ---------------------------------------- КОНЕЦ вывод  кол-во техники AVIA ------------------------------------------- */


            /* Сохранить в файл */
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="teh_for_command_2.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('php://output');
        });
    });


    /*     * ***************************** END Большой ОТЧЕТ по технике для Министра ****************************** */


     /*     * *************************  Техника в командировке ************************************ */
         //форма с выбором даты
         $app->get('/teh_in_trip', function () use ($app) {
            //bread
            $data['bread_active'] = 'Техника в командировке';

            $data['title_name']='Отчеты/Техника в командировке';
        $app->render('layouts/header.php',$data);
            $app->render('layouts/menu.php');
            $app->render('report/teh_in_trip/bread.php', $data);
            $app->render('report/teh_in_trip/form.php', $data);
            $app->render('layouts/footer.php');
        });

         $app->post('/teh_in_trip', function () use ($app) {
  $date_start = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
            $date_d = new DateTime($date_start);
            $date = $date_d->Format('Y-m-d');
            //$date=  date("2018-03-28");
            //echo $date;

            if($_SESSION['note'] != NULL){//ROSN,UGZ, AVIA
                 $teh_in_trip=R::getAll('SELECT * FROM teh_in_trip WHERE ((? BETWEEN date1 AND date2) OR( ?  >= date1 AND date2 IS NULL)) AND organ_id = ?',array($date,$date,$_SESSION['note']));
            }
            elseif($_SESSION['ulevel']==1){//по РБ
              $teh_in_trip=R::getAll('SELECT * FROM teh_in_trip WHERE ((? BETWEEN date1 AND date2) OR( ?  >= date1 AND date2 IS NULL))',array($date,$date));
            }
            else{//по области
                 $teh_in_trip=R::getAll('SELECT * FROM teh_in_trip WHERE ((? BETWEEN date1 AND date2) OR( ?  >= date1 AND date2 IS NULL)) AND region_id =?',array($date,$date,$_SESSION['uregions']));
            }

            //print_r($teh_in_trip);

            /*------- Export to Excel ---------*/
             $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/teh_in_trip.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 8;
        $i = 0;

          /*+++++++++++++++++++++ style ++++++++++*/
                        /* Итого по ГРОЧС */
            $style_all_grochs = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => '99CCCC'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

                                  /* Итого по области */
            $style_all_region = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => '00CECE'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

                               /* ИТОГО */
            $style_all = array(
// Заполнение цветом
                'fill' => array(
                    'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                    'color' => array(
                        'rgb' => 'DFE53E'
                    )
                ),
                // Шрифт
                'font' => array(
                    'bold' => true
                )
            );

            /*+++++++++++++ end style +++++++++++++*/

        /* ---всего по ГРОЧС  --- */
$all_g = 0;
/* ---всего по области --- */
$all_r = 0;

$last_id_grochs = 0;
$last_id_region = 0;
$k = 0; //кол-во по РБ

            $sheet->setCellValue('A2', 'на '.$date);

            foreach ($teh_in_trip as $row) {

                 //  if ($type == 1) {//кроме РОСН/UGZ
                        /* ++++++++++ итого по ГРОЧС ++++++++++++++ */
                        if ($last_id_grochs != $row['id_grochs'] && $last_id_grochs != 0) {
                            $sheet->setCellValue('A' . $r, '');
                            $sheet->setCellValue('B' . $r,'ИТОГО по Г(Р)ОЧС:' );
                            $sheet->setCellValue('C' . $r, $all_g);
                            $sheet->setCellValue('D' . $r, '');
                            $sheet->setCellValue('E' . $r, '');
                            $sheet->setCellValue('F' . $r, '');
                            $sheet->setCellValue('G' . $r, '');
                            $sheet->setCellValue('H' . $r, '');
                              $sheet->getStyleByColumnAndRow(0, $r, 7, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС
                            $r++;

                            $all_g = 0; //обнулсть
                        }

                        /* ++++ Итого по области ++++ */
                        if ($last_id_region != $row['region_id'] && $last_id_region != 0) {
                            $sheet->setCellValue('A' . $r, '');
                            $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                            $sheet->setCellValue('C' . $r, $all_r);
                            $sheet->setCellValue('D' . $r, '');
                            $sheet->setCellValue('E' . $r, '');
                            $sheet->setCellValue('F' . $r, '');
                            $sheet->setCellValue('G' . $r, '');
                            $sheet->setCellValue('H' . $r, '');
                             $sheet->getStyleByColumnAndRow(0, $r, 7, $r)->applyFromArray($style_all_region); //Итого по области
                            $r++;

                            $all_r = 0; //обнулсть
                        }
                                               $all_g+=1;
                    $all_r+=1;

                    $last_id_grochs = $row['id_grochs'];
                    $last_id_region = $row['region_id'];
                    //}
                    $i++;
                    $sheet->setCellValue('A' . $r, $i); //№ п/п
                    $sheet->setCellValue('B' . $r, $row['region_name']);
                    $sheet->setCellValue('C' . $r, $row['locorg_name'].chr(10) .$row['pasp']);
                    $sheet->setCellValue('D' . $r, $row['teh']);
                    $sheet->setCellValue('E' . $r, $row['place']);
                    $sheet->setCellValue('F' . $r, $row['date1']);
                    $sheet->setCellValue('G' . $r, $row['date2']);
                    $sheet->setCellValue('H' . $r, $row['is_take']);
                    $r++;

                    $k++; //itogo


            }

       //  if ($type == 1) {//кроме РОСН/UGZ
            /* ++++ Итого по ГРОЧС ++++ */
            if ($last_id_grochs && $last_id_grochs != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по Г(Р)ОЧС:');
                $sheet->setCellValue('C' . $r, $all_g);
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, '');
                $sheet->setCellValue('F' . $r, '');
                $sheet->setCellValue('G' . $r, '');
                $sheet->setCellValue('H' . $r, '');
                  $sheet->getStyleByColumnAndRow(0, $r, 7, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС
                $r++;

                $all_g = 0; //обнулсть
            }

            /* ++++ Итого по области ++++ */
            if ($last_id_region && $last_id_region != 0) {
                $sheet->setCellValue('A' . $r, '');
                $sheet->setCellValue('B' . $r, 'ИТОГО по области:');
                $sheet->setCellValue('C' . $r, $all_r);
                $sheet->setCellValue('D' . $r, '');
                $sheet->setCellValue('E' . $r, '');
                $sheet->setCellValue('F' . $r, '');
                $sheet->setCellValue('G' . $r, '');
                $sheet->setCellValue('H' . $r, '');
                 $sheet->getStyleByColumnAndRow(0, $r, 7, $r)->applyFromArray($style_all_region); //Итого по области
                $r++;

                $all_r = 0; //обнулсть
            }
       // }
        if ($k != 0) {
            $sheet->setCellValue('A' . $r, '');
            $sheet->setCellValue('B' . $r, 'ИТОГО:');
            $sheet->setCellValue('C' . $r, $k);
            $sheet->setCellValue('D' . $r, '');
            $sheet->setCellValue('E' . $r, '');
            $sheet->setCellValue('F' . $r, '');
            $sheet->setCellValue('G' . $r, '');
            $sheet->setCellValue('H' . $r, '');
             $sheet->getStyleByColumnAndRow(0, $r, 7, $r)->applyFromArray($style_all); //Итого
            $r++;
        }
        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="teh_in_trip.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');

        });

     /*     * *************************  END Техника в командировке ************************************ */


             /*     * *************************  Техника деж смены с работниками на ней (от ГРОДНО) ************************************ */
         /*----------------- форма ------------------*/
    $app->get('/teh_br', function () use ($app) {

        if ($_SESSION['ulevel'] != 1 && $_SESSION['note'] != NULL) {

            if ($_SESSION['note'] == ROSN) {
                $data = additional_query();
            } elseif ($_SESSION['note'] == UGZ) {
                $data = UGZ_query();
            } elseif ($_SESSION['note'] == AVIA) {
                $data = AVIA_query();
            } else {//UMCHS
                $data = basic_query();
            }
        } else {//RCU, UMCHS
            $data['region'] = R::getAll('SELECT * FROM ss.regions'); //список область
            $data['locorg'] = R::getAll('SELECT * FROM ss.caption '); // вместе с  ЦП
                $data['diviz'] = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения
                $data['select']=0;
                      $data['select'] = 0; //доступны все область
        $data['select_grochs'] = 0; //доступны все ГРОЧС
        $data['select_pasp'] = 0; //доступны все части
        }
         /*----------------- END форма ------------------*/

        //bread
        $data['bread_active'] = 'Техника (Гродно)';
      $data['title_name']='Отчеты/Техника (Гродно)';
        $app->render('layouts/header.php',$data);
            $app->render('layouts/menu.php');
            $app->render('report/teh_in_trip/bread.php', $data);
            $app->render('report/teh_br/form.php', $data);
            $app->render('layouts/footer.php');
        });

         $app->post('/teh_br', function () use ($app) {

        /* -------------------------------- анализ входных данных --------------------------------------------------- */
        $result = array();
        if (isset($_POST['diviz']) && !empty($_POST['diviz'])) {

            $query = R::getAll('select distinct id_record, regionn as region_name, f as locorg_name, '
                            . ' case when (divizion_num =0) then divizion_name else concat(divizion_name," - ",divizion_num) end
as divizion_name, disloc  from ss.card WHERE id_record = ?  order by local_name, divizion_num asc', array($_POST['diviz']));
        } elseif (isset($_POST['locorg']) && !empty($_POST['locorg'])) {
            $id_organ = R::getCell('select id_organ from ss.locorg where id = ?', array($_POST['locorg']));

            if ($id_organ == ROSN || $id_organ == UGZ) {
                //выбор всех подразделений этого органа
                $query = R::getAll('select distinct id_record, regionn as region_name, f as locorg_name, '
                                . ' case when (divizion_num =0) then divizion_name else concat(divizion_name," - ",divizion_num) end
as divizion_name, disloc  from ss.card WHERE orgid = ?  order by local_name, divizion_num asc', array($id_organ));
            } else {// УМЧС
                //выбор всех ПАСЧ этого ГРОЧС
                $query = R::getAll('select distinct id_record, regionn as region_name, f as locorg_name, '
                                . ' case when (divizion_num =0) then divizion_name else concat(divizion_name," - ",divizion_num) end
as divizion_name, disloc  from ss.card WHERE id_card = ?  order by local_name, divizion_num asc', array($_POST['locorg']));
            }
        } elseif (isset($_POST['region']) && !empty($_POST['region'])) {//по области
            //выбор всех ГРОЧС области
            //выбор всех ПАСЧ этого ГРОЧС
            $query = R::getAll('select distinct id_record, regionn as region_name, f as locorg_name, '
                            . ' case when (divizion_num =0) then divizion_name else concat(divizion_name," - ",divizion_num) end
as divizion_name, disloc  from ss.card WHERE region = ?  order by local_name, divizion_num asc', array($_POST['region']));
        }
        /* -------------------------------- КОНЕЦ анализ входных данных --------------------------------------------------- */


            /*         * *********дата, на которую надо выбирать данные ******** */
        $d = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
        $date_d = new DateTime($d);
        $date_start = $date_d->Format('Y-m-d');

        $today = date("Y-m-d");
        $yesterday = date("Y-m-d", time() - (60 * 60 * 24));
        $day_before_yesterday = date("Y-m-d", time() - (60 * 60 * 24) - (60 * 60 * 24));

        //если дата выходит за пределы трех дней, то формируем запрос за последнюю заполненную смену
        if ($date_start != $today && $date_start != $yesterday && $date_start != $day_before_yesterday) {
            $date_start = 0;
        }

        $data['date_start']=$date_start;

        /*         * ********* END дата, на которую надо выбирать данные ******** */


        foreach ($query as $row) {
            /* ---------------- общие сведения - район, область --------------------- */
            $id_pasp = $row['id_record']; // id of pasp
            $region_name = $row['region_name'];
            $locorg_name = $row['locorg_name'];
            $pasp_name = $row['divizion_name'];
            $disloc = $row['disloc'];


            /* ---------------- КОНЕЦ общие сведения - район, область --------------------- */

            if($date_start==0){
                $main = R::getAll('SELECT id_card, ch, dateduty FROM str.main WHERE id_card = ? order by dateduty desc limit ?', array($id_pasp, 1)); // выбор последней деж смены  ПАСЧ
            }
            else{
                $main = R::getAll('SELECT id_card, ch, dateduty FROM str.main WHERE id_card = ? and dateduty = ?', array($id_pasp, $date_start)); // выбор деж смены  ПАСЧ по дате
            }


            /* ------------------------- техника, находящаяся в подразд на дату дежурства ------------------------------------ */
            if(!empty($main)){

                 $result[$region_name][$locorg_name][$pasp_name]['disloc'] = $disloc;

                 foreach ($main as $value) {

                $result[$region_name][$locorg_name][$pasp_name]['dateduty'] = $value['dateduty'];
                $result[$region_name][$locorg_name][$pasp_name]['ch'] = $value['ch'];

                $id_teh_native = R::getAssoc("CALL query_car('{$value['id_card']}','{$value['dateduty']}', '{$value['ch']}');"); //id_teh родной техники, которая сег числится в подразд

                /* ---- из др ПАСЧ ------- */
                $id_teh_addit = R::getAssoc("CALL additional_car_for_query_car('{$value['dateduty']}', '{$value['id_card']}');"); //id_teh, которая сег пришла из др.подразд
                /* ---- END из др ПАСЧ ------- */

                $id_teh = array_merge($id_teh_native, $id_teh_addit);

                if (!empty($id_teh)) {
                    foreach ($id_teh as $t) {

                        //выбрать инф по машине
                        $inf_teh = R::getAll('SELECT tech.id_record AS id_pasp,t.ch AS ch,t.dateduty AS dateduty,
t.id_teh AS id_teh,
tech.mark AS mark,
tech.numbsign AS numbsign,
p.name as position_name, l.fio
FROM car t
LEFT JOIN ss.technics tech ON t.id_teh = tech.id
left join str.fiocar as fc on fc.id_tehstr=t.id
left join str.listfio as l on l.id=fc.id_fio
left join str.position as p on p.id=l.id_position WHERE t.id_teh = ? AND t.dateduty = ? AND t.ch = ?', array($t, $value['dateduty'], $value['ch']));


                        if (!empty($inf_teh)) {

                            foreach ($inf_teh as $inf) {
                                $mark = $inf['mark'];
                                $numbsign = $inf['numbsign'];
                                $result[$region_name][$locorg_name][$pasp_name]['teh'][$inf['id_teh']]['br'] [] = array('fio' => $inf['fio'], 'position_name' => $inf['position_name']);
                            }
                            $result[$region_name][$locorg_name][$pasp_name]['teh'][$inf['id_teh']]['mark'] = $mark;
                            $result[$region_name][$locorg_name][$pasp_name]['teh'][$inf['id_teh']]['numbsign'] = $numbsign;
                        }
                    }
                }
            }
            }

        }

        /* -------------------------END  техника, находящаяся в подразд на дату дежурства ------------------------------------ */


        /* ------- Export to Excel --------- */
        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/teh_br.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 8;
        $i = 0;


        /* устанавливаем бордер ячейкам */
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);

        foreach ($result as $obl => $row) {//область



            foreach ($row as $locorg => $pasp) {//РОЧС

                $c_cell_locorg = 0; //кол-во ячеек для объединения "наименование отдела по ЧС"

                if($date_start != 0){
                       $sheet->setCellValue('A2', 'Результат на '.$date_start);
                }
 else {
     $sheet->setCellValue('A2', 'Результат за последнюю заполненную смену ');
 }


                $sheet->setCellValue('B' . $r, $locorg);

                $old_r = $r; //запомнить начальную ячейку для области


                                    /* ------------------------------------------- обработка информации по ПАСЧ---------------------------------------------------- */
                foreach ($pasp as $pasp_n => $pasp_value) {

                    $c_cell_pasp = 0; //кол-во ячеек для объединения "подразделение"

                    $sheet->setCellValue('C' . $r, $pasp_n . ', ' . $pasp_value['disloc']);
                    $sheet->setCellValue('D' . $r, 'смена ' . $pasp_value['ch'] . chr(10) . $pasp_value['dateduty']);


                    if (!empty($pasp_value['teh'])) {
                        $new_r = $r; //номер строки для вывода машины
                        foreach ($pasp_value['teh'] as $vp) {
                            $c_fio = count($vp['br']); //кол-во работников на одной машине
                            $c_cell_locorg+=$c_fio;
                            $c_cell_pasp+=$c_fio;

                            if ($c_fio > 1) {
                                $end_cell = $new_r + $c_fio - 1;
                                $sheet->mergeCells('E' . $new_r . ':E' . $end_cell);
                                $sheet->mergeCells('F' . $new_r . ':F' . $end_cell);
                            }

                            /* ------------------ марка, ном знак --------------------- */
                            $sheet->setCellValue('E' . $new_r, $vp['mark']);
                            $sheet->setCellValue('F' . $new_r, $vp['numbsign']);
                            /* ------------------ КОНЕЦ марка, ном знак --------------------- */

                            /* ------------------ должность, ФИО --------------------- */
                            if (!empty($vp['br'])) {
                                $fio_r = $new_r; //номер строки для вывода работника на машину
                                foreach ($vp['br'] as $br) {

                                    $sheet->setCellValue('G' . $fio_r, $br['position_name']);
                                    $sheet->setCellValue('H' . $fio_r, $br['fio']);
                                    $fio_r++; //след работник
                                }
                            }
                            /* ------------------ КОНЕЦ  должность, ФИО --------------------- */

                            if ($c_fio == 0) {
                                $new_r++; //след машина
                            } else
                                $new_r+=$c_fio; //след машина
                        }
                    }

                    else {//если нет даты заступления смены - нет техники- выводим пустую строку
                        $c_cell_locorg++;
                        $c_cell_pasp++;
                        $new_r++;
                    }

                    /* ----- объединить "ПАСЧ" ------ */
                    if ($c_cell_pasp == 0) {
                        $end_pasp = $r + $c_cell_pasp;
                    } else {
                        $end_pasp = $r + $c_cell_pasp - 1;
                    }
                    $sheet->mergeCells('C' . $r . ':C' . $end_pasp);
                    $sheet->mergeCells('D' . $r . ':D' . $end_pasp);

                    /* ----- КОНЕЦ объединить "ПАСЧ" -------- */

                    $r+=$c_cell_pasp; //номер строки для вывода следующей ПАСЧ
                }

                /* ------------------------------------------- КОНЕЦ обработка информации по ПАСЧ---------------------------------------------------- */


                /* -----  объединить "наименование отдела по ЧС" , область-------- */

                if ($c_cell_locorg == 0) {
                    $end_locorg = $old_r + $c_cell_locorg;
                } else {
                    $end_locorg = $old_r + $c_cell_locorg - 1;
                }
                    $sheet->setCellValue('A' . $old_r, $obl);
                $sheet->mergeCells('B' . $old_r . ':B' . $end_locorg);
                $sheet->mergeCells('A' . $old_r . ':A' . $end_locorg);

                /* ----- КОНЕЦ объединить "наименование отдела по ЧС", область -------- */
            }
        }

        $sheet->getStyleByColumnAndRow(0, 8, 8, $end_locorg)->applyFromArray($styleArray);


        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="teh_br.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    });

    /*     * *************************  END Техника деж смены с работниками на ней (от ГРОДНО)  ************************************ */



      /* --------------------------- Неисправности техники ----------------------- */
    //форма с выбором даты
    $app->get('/teh_repaire', function () use ($app) {
        //bread
        $data['bread_active'] = 'Неисправности техники';

        $data['title_name'] = 'Отчеты/Неисправности техники';


        $data['name_teh'] = R::getAll('select * from ss.views'); //наименование техники


        $app->render('layouts/header.php', $data);
        $app->render('layouts/menu.php');
        $app->render('report/teh_in_trip/bread.php', $data);
        $app->render('report/teh_repaire/form.php', $data);
        $app->render('layouts/footer.php');
    });

    $app->post('/teh_repaire', function () use ($app) {


        /* ------ обработка входных данных ----- */

        $date_start = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
        $date_d = new DateTime($date_start);
        $date = $date_d->Format('Y-m-d');
        $date_for_excel = $date_d->format('d-m-Y');


        $name_teh = (isset($_POST['technic_name']) && !empty($_POST['technic_name'])) ? $_POST['technic_name'] : 0; //id вида техники: АЦ, АБР...

        /* ------ END обработка входных данных ----- */


        //$sql = 'SELECT * FROM teh_repaire WHERE start_repaire <= ? OR start_repaire is null ';
        $sql = 'SELECT * FROM teh_repaire WHERE dateduty = ?  ';
        $param[] = $date;

        //$sql = 'SELECT * FROM teh_repaire ';

        if ($name_teh != 0) {//вид техники выбран
            $sql = $sql . ' and id_view = ? ';
            // $sql = $sql . ' where id_view = ? ';
            $param[] = $name_teh;

            $name_of_view_teh = R::getCell('select name from ss.views where id = ?', array($name_teh)); //название вида техники
        } else {
            $name_of_view_teh = 'единицах техники';
        }


        // $teh = R::getAll($sql);
        $teh = R::getAll($sql, $param);
        $mas_teh = array(); //массив вида [id_region]=>array(все машины)
        foreach ($teh as $t) {
            $mas_teh[$t['region_id']][] = $t;
        }


        /* ------- Export to Excel --------- */

        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/teh_repaire/teh_repaire.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 4;
        $i = 0;

        $itogo = 0; //по РБы


        /* +++++++++++++++++++++ style +++++++++++++++++++ */

        /* Название области */
        $style_name_oblast = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'ccffff'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* Итого по РБ */
        $style_itogo = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'ffff99'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true,
                'size' => 24
            )
        );

        $style_all = array(
// Заполнение цветом
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            // Шрифт
            'font' => array(
                'size' => 14
            )
        );

        /* другим цветом РОСН, УГЗ, Авиация */
        $style_rosn = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'ffff5d'
                )
            )
        );
        /* ++++++++++++++++++++++ end style +++++++++++++++++++ */


        $cp = array(8, 9, 12); //РОСН, УГЗ, Авиация

        $sheet->setCellValue('A1', 'Сведения о неисправных ' . $name_of_view_teh . ' МЧС РБ на ' . $date_for_excel);

        for ($j = 1; $j <= 7; $j++) {//7 областей
            if (isset($mas_teh[$j]) && !empty($mas_teh[$j])) {
                $k = 0; //№ п/п в пределах области

                switch ($j) {
                    case 1: $name_oblast = 'Брестская область';
                        break;
                    case 2: $name_oblast = 'Витебская область';
                        break;
                    case 3: $name_oblast = 'г. Минск';
                        break;
                    case 4: $name_oblast = 'Гомельская область';
                        break;
                    case 5: $name_oblast = 'Гродненская область';
                        break;
                    case 6: $name_oblast = 'Минская область';
                        break;
                    case 7: $name_oblast = 'Могилевская область';
                        break;
                    default : $name_oblast = 'область';
                }

                $sheet->setCellValue('A' . $r, $name_oblast); //oblast
                $sheet->mergeCells('A' . $r . ':F' . $r);
                $sheet->getStyleByColumnAndRow(0, $r, 5, $r)->applyFromArray($style_name_oblast); //наименование области
                $r++;


                foreach ($mas_teh[$j] as $row) {

                    $k++;
                    $sheet->setCellValue('A' . $r, $k); //№ п/п

                    if (in_array($row['organ_id'], $cp)) {////РОСН, УГЗ, Авиация
                        $sheet->getStyleByColumnAndRow(0, $r, 5, $r)->applyFromArray($style_rosn);
                    }

                    $sheet->setCellValue('B' . $r, $row['divizion_name'] . ', ' . $row['locorg']);
                    $sheet->setCellValue('C' . $r, $row['mark']);
                    $sheet->setCellValue('D' . $r, $row['start_repaire']);
                    $sheet->setCellValue('E' . $r, $row['reason_repaire']);
                    $sheet->setCellValue('F' . $r, $row['end_repaire']);
                    $r++;
                }
                $itogo+=$k; //itogo po RB
            }
        }

        $sheet->mergeCells('A' . $r . ':F' . $r);
        $sheet->setCellValue('A' . $r, 'ВСЕГО: ' . $itogo);  //itogo
        $sheet->getStyleByColumnAndRow(0, $r, 5, $r)->applyFromArray($style_itogo); //итого по РБ

        $sheet->getStyleByColumnAndRow(0, 4, 5, $r)->applyFromArray($style_all); //рамка



        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="teh_repaire.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    });

    /* ------------------------- END Неисправности техники ---------------------- */




             /*     * *************************  Мин боевой расчет ************************************ */
         /*----------------- форма ------------------*/
    $app->get('/min_br', function () use ($app) {

        if ($_SESSION['ulevel'] != 1 && $_SESSION['note'] != NULL) {

            if ($_SESSION['note'] == ROSN) {
                $data = additional_query();
            } elseif ($_SESSION['note'] == UGZ) {
                $data = UGZ_query();
            } elseif ($_SESSION['note'] == AVIA) {
                $data = AVIA_query();
            } else {//UMCHS
                $data = basic_query();
            }
        } else {//RCU, UMCHS
            $data['region'] = R::getAll('SELECT * FROM ss.regions'); //список область
            $data['locorg'] = R::getAll('SELECT * FROM ss.caption '); // вместе с  ЦП
                $data['diviz'] = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения
                $data['select']=0;
                      $data['select'] = 0; //доступны все область
        $data['select_grochs'] = 0; //доступны все ГРОЧС
        $data['select_pasp'] = 0; //доступны все части
        }
         /*----------------- END форма ------------------*/

        //bread
        $data['bread_active'] = 'Мин.боевой расчет';
      $data['title_name']='Мин.боевой расчет';
        $app->render('layouts/header.php',$data);
            $app->render('layouts/menu.php');
            $app->render('report/teh_in_trip/bread.php', $data);
            $app->render('report/min_br/form.php', $data);
            $app->render('layouts/footer.php');
        });

     $app->post('/min_br', function () use ($app) {

        /* ------ обработка входных данных ----- */

        $date_start = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
        $date_d = new DateTime($date_start);
        $date = $date_d->Format('Y-m-d');
        $date_for_excel = $date_d->format('d-m-Y');


      //native_all_calc - общий бр из КУСиС для ПАСЧ, учитывая только родные машины. Если заступила чужая машина-ее б.р. не учитваем
        $sql = 'select native_all_calc, dateduty, divizion_name, locorg, region_id, locorg_id,record_id,region_name,organ_id,'

 .' sum(calculation) as calculation, sum(br) as br , case when( sum(calculation)>sum(br)) then 1 else 0 end as is_vivod'

 .' from br  where dateduty = ?  ';//вся боевая техника, где мин б.р. не равен бр
        $param[] = $date;


        if (isset($_POST['diviz']) && !empty($_POST['diviz'])) {

            $sql=$sql.' AND record_id = ?';
          $param[]=$_POST['diviz'];
        } elseif (isset($_POST['locorg']) && !empty($_POST['locorg'])) {
            $id_organ = R::getCell('select id_organ from ss.locorg where id = ?', array($_POST['locorg']));

            if ($id_organ == ROSN || $id_organ == UGZ) {
                //выбор всех подразделений этого органа

                $sql=$sql.' AND organ_id = ?';
                $param[]=$id_organ;
            } else {// УМЧС
                //выбор всех ПАСЧ этого ГРОЧС
                $sql=$sql.' AND locorg_id = ?';
                $param[]=$_POST['locorg'];
            }
        } elseif (isset($_POST['region']) && !empty($_POST['region'])) {//по области
            //выбор всех ГРОЧС области
            //выбор всех ПАСЧ этого ГРОЧС
            $sql=$sql.' AND region_id = ?';
            $param[]=$_POST['region'];
        }



        /* ------ END обработка входных данных ----- */




//        echo $sql;
//        print_r($param);
//        exit();

$sql=$sql.' group by record_id ';
        $teh = R::getAll($sql, $param);

        //оставить только те ПАСЧ, где calculation>br
        foreach ($teh as $key=>$value) {
            if( $value['br']>= $value['native_all_calc'])
                unset($teh[$key]);
        }


        $mas_teh = array(); //массив вида [id_region]=>array(все машины)
        foreach ($teh as $t) {
            $mas_teh[$t['region_id']][] = $t;
        }


        /* ------- Export to Excel --------- */

        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/min_br.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 4;
        $i = 0;

        $itogo = 0; //по РБы


        /* +++++++++++++++++++++ style +++++++++++++++++++ */

        /* Название области */
        $style_name_oblast = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'ccffff'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* Итого по РБ */
        $style_itogo = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'ffff99'
                )
            ),
            // Шрифт
            'font' => array(
                'bold' => true,
                'size' => 24
            )
        );

        $style_all = array(
// Заполнение цветом
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            ),
            // Шрифт
            'font' => array(
                'size' => 14
            )
        );

        /* другим цветом РОСН, УГЗ, Авиация */
        $style_rosn = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'ffff5d'
                )
            )
        );
        /* ++++++++++++++++++++++ end style +++++++++++++++++++ */


        $cp = array(8, 9, 12); //РОСН, УГЗ, Авиация

        $sheet->setCellValue('A1', 'Сведения о несоответствии минимального боевого расчета на ' . $date_for_excel);

        for ($j = 1; $j <= 7; $j++) {//7 областей
            if (isset($mas_teh[$j]) && !empty($mas_teh[$j])) {
                $k = 0; //№ п/п в пределах области

                switch ($j) {
                    case 1: $name_oblast = 'Брестская область';
                        break;
                    case 2: $name_oblast = 'Витебская область';
                        break;
                    case 3: $name_oblast = 'г. Минск';
                        break;
                    case 4: $name_oblast = 'Гомельская область';
                        break;
                    case 5: $name_oblast = 'Гродненская область';
                        break;
                    case 6: $name_oblast = 'Минская область';
                        break;
                    case 7: $name_oblast = 'Могилевская область';
                        break;
                    default : $name_oblast = 'область';
                }

                $sheet->setCellValue('A' . $r, $name_oblast); //oblast
                $sheet->mergeCells('A' . $r . ':D' . $r);
                $sheet->getStyleByColumnAndRow(0, $r, 3, $r)->applyFromArray($style_name_oblast); //наименование области
                $r++;


                foreach ($mas_teh[$j] as $row) {

                    $k++;
                    $sheet->setCellValue('A' . $r, $k); //№ п/п

                    if (in_array($row['organ_id'], $cp)) {////РОСН, УГЗ, Авиация
                        $sheet->getStyleByColumnAndRow(0, $r, 3, $r)->applyFromArray($style_rosn);
                    }

                    $sheet->setCellValue('B' . $r, $row['divizion_name'] . ', ' . $row['locorg']);
                    $sheet->setCellValue('C' . $r, $row['calculation']);
                    $sheet->setCellValue('D' . $r, $row['br']);
                    $r++;
                }
                $itogo+=$k; //itogo po RB
            }
        }

        $sheet->mergeCells('A' . $r . ':D' . $r);
        $sheet->setCellValue('A' . $r, 'ВСЕГО: ' . $itogo);  //itogo
        $sheet->getStyleByColumnAndRow(0, $r, 3, $r)->applyFromArray($style_itogo); //итого по РБ

        $sheet->getStyleByColumnAndRow(0, 4, 3, $r)->applyFromArray($style_all); //рамка



        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="min_br.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    });

    /*     * *************************  END Мин боевой расчет  ************************************ */



                 /*     * *************************  Подробный отчет ТЕХНИКА, АСВ, П,ПО (от МОГИЛЕВ) ************************************ */
         /*----------------- форма ------------------*/
    $app->get('/detail_teh', function () use ($app) {

        if ($_SESSION['ulevel'] != 1 && $_SESSION['note'] != NULL) {

            if ($_SESSION['note'] == ROSN) {
                $data = additional_query();
            } elseif ($_SESSION['note'] == UGZ) {
                $data = UGZ_query();
            } elseif ($_SESSION['note'] == AVIA) {
                $data = AVIA_query();
            } else {//UMCHS
                $data = basic_query();
            }
        } else {//RCU, UMCHS
            $data['region'] = R::getAll('SELECT * FROM ss.regions'); //список область
            $data['locorg'] = R::getAll('SELECT * FROM ss.caption '); // вместе с  ЦП
                $data['diviz'] = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения
                $data['select']=0;
                      $data['select'] = 0; //доступны все область
        $data['select_grochs'] = 0; //доступны все ГРОЧС
        $data['select_pasp'] = 0; //доступны все части
        }
         /*----------------- END форма ------------------*/

        //bread
        $data['bread_active'] = 'Техника+Склад';
      $data['title_name']='Техника+Склад';
        $app->render('layouts/header.php',$data);
            $app->render('layouts/menu.php');
            $app->render('report/teh_in_trip/bread.php', $data);
            $app->render('report/detail_teh/form.php', $data);
            $app->render('layouts/footer.php');
        });

         $app->post('/detail_teh', function () use ($app) {

        /*         * *********дата, на которую надо выбирать данные ******** */
        $d = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
        $date_d = new DateTime($d);
        $date_start = $date_d->Format('Y-m-d');

        $today = date("Y-m-d");
        $yesterday = date("Y-m-d", time() - (60 * 60 * 24));
        $day_before_yesterday = date("Y-m-d", time() - (60 * 60 * 24) - (60 * 60 * 24));

        //если дата выходит за пределы трех дней, то формируем запрос за последнюю заполненную смену
       // if ($date_start != $today && $date_start != $yesterday && $date_start != $day_before_yesterday) {
           // $date_start = 0;
        //}
        $data['date_start'] = $date_start;

        /*         * ********* END дата, на которую надо выбирать данные ******** */

             $grochs = $app->request()->post('locorg'); //грочс
            $id_organ = R::getCell('select id_organ from ss.locorg where id = ?', array($grochs));


        if ($id_organ == 8) {//ROSN
            $region = $app->request()->post('region'); //РОСН
            $grochs = $app->request()->post('locorg'); //ОУ
        } elseif ($id_organ == 9) {//UGZ
            $region = $app->request()->post('region'); //UGZ
            $grochs = $app->request()->post('locorg'); //ОУ
        } elseif ($id_organ == 12) {//AVIA
            $region = $app->request()->post('region'); //oblast
            $grochs = $app->request()->post('locorg'); //Avia
            $divizion = $app->request()->post('diviz'); //часть
        } else {//UMCHS
            $region = $app->request()->post('region'); //область
            $grochs = $app->request()->post('locorg'); //грочс
            $divizion = $app->request()->post('diviz'); //подразделение
        }


        /* ------------ КОНЕЦ  запрошенные параметры ---------- */


       // результат поиска УМЧС без ЦОУ, ШЛЧС!!!!

        /*----------------------------- только родная техника - та, которая уехала в командировку - та, которая приехала из др.ПАСЧ ------------------------------------*/
            $sql = " SELECT   count(c.id_teh) as co, "
                    . "     `reg`.`name`        AS `region_name`,"
                    . "`reg`.`id`          AS `region_id`,  `re`.`id_loc_org` AS `id_grochs`,"
                    . " `re`.`id`         AS `id_pasp`,"
                    . " (CASE WHEN (`org`.`id` = 8) THEN `org`.`name` WHEN (`org`.`id` = 7) THEN CONCAT(`org`.`name`,' №',`locor`.`no`,' ',REPLACE(`loc`.`name`,'ий','ого'),' ',`orgg`.`name`) "
                    . "  ELSE CONCAT(`loc`.`name`,' ',`org`.`name`) END) AS `organ`,"
                    . "  `org`.`id`         AS `org_id`,"
                    . " (CASE WHEN (`org`.`id` = 8) THEN CONCAT(`org`.`name`,' - ',`loc`.`name`) WHEN (`re`.`divizion_num` = 0) THEN `d`.`name` "
                    . "ELSE CONCAT(`d`.`name`,'-',`re`.`divizion_num`) END) AS `divizion` "

                    . " FROM str.car AS c LEFT JOIN ss.technics AS t ON t.id=c.id_teh"
                    . " LEFT JOIN ss.records AS re ON re.id=t.id_record "
                    . " LEFT JOIN ss.locorg AS locor ON locor.id=re.id_loc_org"
                    . " LEFT JOIN ss.locals AS loc ON loc.id=locor.id_local"
                    . " left join ss.views as vie on vie.id=t.id_view"
                    ."  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                    ." LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                    ."   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                    ."   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "
                    . " WHERE c.dateduty = ' " . $date_start . "'"

                    . " AND  c.`id_teh` NOT IN "
                    . " (SELECT  tr.`id_teh`  FROM  str.`tripcar` AS tr WHERE (( ' " . $date_start . " ' BETWEEN tr.date1 AND tr.date2) OR( ' " . $date_start . " '  >= tr.date1 AND tr.date2 IS NULL)) )"
                    . " AND  c.`id_teh` NOT IN "
                    . "  (SELECT  res.`id_teh`  FROM  str.reservecar AS res WHERE (( ' " . $date_start . " ' BETWEEN res.date1 AND res.date2) OR( ' " . $date_start . " '  >= res.date1 AND res.date2 IS NULL)) ) "
                    . " and d.id not in (8,9)";


            //марка техники
                      $sql_mark = " SELECT   t.mark as mark, `re`.`id` AS `id_pasp`, c.id_type, vie.id_vid, c.id_to, c.is_repair,c.powder,c.foam "
                    . " FROM str.car AS c LEFT JOIN ss.technics AS t ON t.id=c.id_teh"
                    . " LEFT JOIN ss.records AS re ON re.id=t.id_record "
                    . " LEFT JOIN ss.locorg AS locor ON locor.id=re.id_loc_org"
                    . " LEFT JOIN ss.locals AS loc ON loc.id=locor.id_local"
                    . " left join ss.views as vie on vie.id=t.id_view"
                    ."  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                    ." LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                    ."   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                    ."   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "
                    . " WHERE c.dateduty = ' " . $date_start . "'"

                    . " AND  c.`id_teh` NOT IN "
                    . " (SELECT  tr.`id_teh`  FROM  str.`tripcar` AS tr WHERE (( ' " . $date_start . " ' BETWEEN tr.date1 AND tr.date2) OR( ' " . $date_start . " '  >= tr.date1 AND tr.date2 IS NULL)) )"
                    . " AND  c.`id_teh` NOT IN "
                    . "  (SELECT  res.`id_teh`  FROM  str.reservecar AS res WHERE (( ' " . $date_start . " ' BETWEEN res.date1 AND res.date2) OR( ' " . $date_start . " '  >= res.date1 AND res.date2 IS NULL)) ) "
                    . " and d.id not in (8,9)";


                      if($id_organ==8 || $id_organ==9 || $id_organ == 12){// only РОСН, УГЗ, Авиации
                           $sql = $sql . ' AND locor.`id_organ` =  ' . $id_organ;
             $sql_mark = $sql_mark . ' AND locor.`id_organ` =  ' . $id_organ;
                      }

                       else {//UMCHS
            $sql = $sql . ' AND locor.`id_organ` NOT IN(8,9,12) '; //кроме РОСН, УГЗ, Авиации
             $sql_mark = $sql_mark . ' AND locor.`id_organ` NOT IN(8,9,12) '; //кроме РОСН, УГЗ, Авиации
        }


        /* ------------------------ по какой области/подразделению ищем --------------------------- */

                if($id_organ == 8 || $id_organ == 9) {//ROSN, UGZ
            if (!empty($region)) {
                if (!empty($grochs)) {
                    if($region==3){//minsk за все подразд РОСН/УГЗ

                    }
                    else{//по ОУ
                        $sql = $sql . ' and locor.id = ' . $grochs;
                     $sql_mark = $sql_mark . ' and locor.id = ' . $grochs;
                    }

                } else{ //oblast
                    $sql = $sql . ' and org.id = ' . $region;
                     $sql_mark = $sql_mark . ' and org.id = ' . $region;
                }
            }
        }
        else{//UMCHS, Avia
            //область/грочс/часть
            if (!empty($region)) {
                if (!empty($grochs)) {
                    if (!empty($divizion)){ //pasp
                        $sql = $sql . ' and re.id = ' . $divizion;
                      $sql_mark = $sql_mark . ' and re.id = ' . $divizion;
                    }
                    else{ //rochs
                        $sql = $sql . ' and locor.id = ' . $grochs;
                         $sql_mark = $sql_mark . ' and locor.id = ' . $grochs;
                    }
                } else{ //oblast
                    $sql = $sql . ' and reg.id = ' . $region;
                    $sql_mark = $sql_mark . ' and reg.id = ' . $region;
                }
            }
        }

        //print_r($sql);
        //exit();

        /* ------------------------ КОНЕЦ по какой области/подразделению ищем --------------------------- */

        $sql=$sql."  group by re.id  ORDER BY `reg`.`name`,`locor`.`id`,`loc`.`name`,`re`.`divizion_num`";
        $res=R::getAll($sql);

        $data['res']=$res;

       // print_r($res);exit();

        $sql_mark = $sql_mark . "   ORDER BY `reg`.`name`,`locor`.`id`,`loc`.`name`,`re`.`divizion_num`";
        $res_mark = R::getAll($sql_mark);


        //массив из марок техники по каждой ПАСЧ
        $res_mark_array=array();
        foreach ($res_mark as $value) {
            $res_mark_array[$value['id_pasp']][]=array('mark'=>$value['mark'],'vid'=>$value['id_vid'],'id_type'=>$value['id_type'],'id_to'=>$value['id_to'],'is_repair'=>$value['is_repair'],'powder'=>$value['powder'],'foam'=>$value['foam']);
        }
        $data['res_mark_array'] = $res_mark_array;

 //echo '<b>Общие сведения</b>';echo '<br>';
                     // print_r($res);
// echo '<br>';echo '<b>Mark</b>';echo '<br>';
//        print_r($res_mark);
 //echo '<br>';echo '<b>Mark array</b>';echo '<br>';
      //   print_r($res_mark_array);
// echo '<br>';echo '<b>Из др ПАСЧ</b>';echo '<br>';
     //   exit();

        /*----------------------------- КОНЕЦ только родная техника - та, которая уехала в командировку - та, которая приехала из др.ПАСЧ ------------------------------------*/



        /* ------------------------------------------------ Техника из др подразделения ------------------------------------------------------------- */
            $sql_teh_from_other_pasp = " SELECT  res.id_card as id_pasp, count(res.`id_teh` ) as co, "
                      . "     `reg`.`name`        AS `region_name`,"
                    . "`reg`.`id`          AS `region_id`,  `re`.`id_loc_org` AS `id_grochs`,"

                    . " (CASE WHEN (`org`.`id` = 8) THEN `org`.`name` WHEN (`org`.`id` = 7) THEN CONCAT(`org`.`name`,' №',`locor`.`no`,' ',REPLACE(`loc`.`name`,'ий','ого'),' ',`orgg`.`name`) "
                    . "  ELSE CONCAT(`loc`.`name`,' ',`org`.`name`) END) AS `organ`,"
                    . "  `org`.`id`         AS `org_id`,"
                    . " (CASE WHEN (`org`.`id` = 8) THEN CONCAT(`org`.`name`,' - ',`loc`.`name`) WHEN (`re`.`divizion_num` = 0) THEN `d`.`name` "
                    . "ELSE CONCAT(`d`.`name`,'-',`re`.`divizion_num`) END) AS `divizion` "

                    . "   FROM  str.reservecar AS res "
                    . " left join str.car as c ON c.id_teh=res.id_teh  and c.dateduty= ' " . $date_start . " '"
                    . " left join ss.technics as t on t.id=res.id_teh"
                    . " left join ss.views as vie on vie.id=t.id_view  "
                    . "left join ss.records as re ON re.id=res.id_card"
                    ." left join ss.locorg as locor on locor.id=re.id_loc_org "
                    . "left join ss.locals as loc on loc.id=locor.id_local"
                     ."  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                    ." LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                    ."   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                    ."   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "

                    . "  WHERE (( ' " . $date_start . " ' BETWEEN res.date1 AND res.date2) OR( ' " . $date_start . " '  >= res.date1 AND res.date2 IS NULL)) "
                    . " and re.id_divizion not in (8,9)";


            //марка техники из др.подразделения
               $sql_teh_mark_from_other_pasp = " SELECT  res.id_card as id_pasp, t.mark as mark, c.id_type, vie.id_vid, c.id_to, c.is_repair, c.powder,c.foam "

                    . "   FROM  str.reservecar AS res "
                    . " left join str.car as c ON c.id_teh=res.id_teh  and c.dateduty= ' " . $date_start . " '"
                    . " left join ss.technics as t on t.id=res.id_teh"
                    . " left join ss.views as vie on vie.id=t.id_view  "
                    . "left join ss.records as re ON re.id=res.id_card"
                    ." left join ss.locorg as locor on locor.id=re.id_loc_org "
                    . "left join ss.locals as loc on loc.id=locor.id_local"
                     ."  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                    ." LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                    ."   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                    ."   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "

                    . "  WHERE (( ' " . $date_start . " ' BETWEEN res.date1 AND res.date2) OR( ' " . $date_start . " '  >= res.date1 AND res.date2 IS NULL)) "
                    . " and re.id_divizion not in (8,9)";


                 if($id_organ==8 || $id_organ == 9) {//ROSN, UGZ
            if (!empty($region)) {
                if (!empty($grochs)) {
                    $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' and re.id_loc_org = ' . $grochs;
                      $sql_teh_mark_from_other_pasp = $sql_teh_mark_from_other_pasp . ' and re.id_loc_org = ' . $grochs;
                } else{ //oblast
                    $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' and org.id = ' . $region;
                     $sql_teh_mark_from_other_pasp = $sql_teh_mark_from_other_pasp . ' and org.id = ' . $region;
                }
            }
        }
            else {//UMCHS, Avia
            //область/грочс/часть
            if (!empty($region)) {
                if (!empty($grochs)) {
                    if (!empty($divizion)){ //pasp
                        $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' and re.id = ' . $divizion;
                     $sql_teh_mark_from_other_pasp = $sql_teh_mark_from_other_pasp . ' and re.id = ' . $divizion;

                    }
                    else{ //rochs
                        $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' and re.id_loc_org = ' . $grochs;
                          $sql_teh_mark_from_other_pasp = $sql_teh_mark_from_other_pasp . ' and re.id_loc_org = ' . $grochs;
                    }
                } else{ //oblast
                    $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' and loc.id_region = ' . $region;
                     $sql_teh_mark_from_other_pasp = $sql_teh_mark_from_other_pasp . ' and loc.id_region = ' . $region;
                }
            }
        }


        $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . " group by res.id_card ";


        $teh_from_other_card = R::getAll($sql_teh_from_other_pasp);



        $teh_from_other_card_array = array(); //в массив ключ - это ПАСЧ
        foreach ($teh_from_other_card as $value) {
            $teh_from_other_card_array[$value['id_pasp']] = $value;
        }

        $data['teh_from_other_card_array'] = $teh_from_other_card_array;

//макрка техники
        $teh_mark_from_other_card = R::getAll($sql_teh_mark_from_other_pasp);

        $teh_mark_from_other_card_array = array(); //в массив ключ - это ПАСЧ
        foreach ($teh_mark_from_other_card as $value) {
            $teh_mark_from_other_card_array[$value['id_pasp']][] = array('mark'=>$value['mark'],'vid'=>$value['id_vid'],'id_type'=>$value['id_type'],'id_to'=>$value['id_to'],'is_repair'=>$value['is_repair'],'powder'=>$value['powder'],'foam'=>$value['foam']);
        }

        $data['teh_mark_from_other_card_array'] = $teh_mark_from_other_card_array;

//                print_r($teh_from_other_card);
//        echo '<br>'; echo '<b>Mark из др ПАСЧ</b>';echo '<br>';
//        print_r($teh_mark_from_other_card);
       // echo '<br>'; echo '<b>Mark array из др ПАСЧ</b>';echo '<br>';
       //  print_r($teh_mark_from_other_card_array);
      //  exit();

        /* ----------------- КОНЕЦ Техника из др подразделения ----------------------- */



         /*-----------------------------  уехала в командировку ------------------------------------*/
            $sql_trip = " SELECT   count(c.id_teh) as co, "
                    . "     `reg`.`name`        AS `region_name`,"
                    . "`reg`.`id`          AS `region_id`,  `re`.`id_loc_org` AS `id_grochs`,"
                    . " `re`.`id`         AS `id_pasp`,"
                    . " (CASE WHEN (`org`.`id` = 8) THEN `org`.`name` WHEN (`org`.`id` = 7) THEN CONCAT(`org`.`name`,' №',`locor`.`no`,' ',REPLACE(`loc`.`name`,'ий','ого'),' ',`orgg`.`name`) "
                    . "  ELSE CONCAT(`loc`.`name`,' ',`org`.`name`) END) AS `organ`,"
                    . "  `org`.`id`         AS `org_id`,"
                    . " (CASE WHEN (`org`.`id` = 8) THEN CONCAT(`org`.`name`,' - ',`loc`.`name`) WHEN (`re`.`divizion_num` = 0) THEN `d`.`name` "
                    . "ELSE CONCAT(`d`.`name`,'-',`re`.`divizion_num`) END) AS `divizion` "

                    . " FROM str.car AS c LEFT JOIN ss.technics AS t ON t.id=c.id_teh"
                    . " LEFT JOIN ss.records AS re ON re.id=t.id_record "
                    . " LEFT JOIN ss.locorg AS locor ON locor.id=re.id_loc_org"
                    . " LEFT JOIN ss.locals AS loc ON loc.id=locor.id_local"
                    . " left join ss.views as vie on vie.id=t.id_view"
                    ."  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                    ." LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                    ."   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                    ."   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "
                    . " WHERE c.dateduty = ' " . $date_start . "'"

                    . " AND  c.`id_teh` NOT IN "
                    . " (SELECT  tr.`id_teh`  FROM  str.`tripcar` AS tr WHERE (( ' " . $date_start . " ' BETWEEN tr.date1 AND tr.date2) OR( ' " . $date_start . " '  >= tr.date1 AND tr.date2 IS NULL)) )"

                    . " and d.id not in (8,9)";


            //марка техники
                      $sql_mark_trip = " SELECT   t.mark as mark, `re`.`id` AS `id_pasp`, c.id_type, vie.id_vid, c.id_to, c.is_repair,c.powder,c.foam "
                    . " FROM str.car AS c LEFT JOIN ss.technics AS t ON t.id=c.id_teh"
                    . " LEFT JOIN ss.records AS re ON re.id=t.id_record "
                    . " LEFT JOIN ss.locorg AS locor ON locor.id=re.id_loc_org"
                    . " LEFT JOIN ss.locals AS loc ON loc.id=locor.id_local"
                    . " left join ss.views as vie on vie.id=t.id_view"
                    ."  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                    ." LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                    ."   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                    ."   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "
                    . " WHERE c.dateduty = ' " . $date_start . "'"

                    . " AND  c.`id_teh`  IN "
                    . " (SELECT  tr.`id_teh`  FROM  str.`tripcar` AS tr WHERE (( ' " . $date_start . " ' BETWEEN tr.date1 AND tr.date2) OR( ' " . $date_start . " '  >= tr.date1 AND tr.date2 IS NULL)) )"

                    . " and d.id not in (8,9)";


                      if($id_organ==8 || $id_organ==9 || $id_organ == 12){// only РОСН, УГЗ, Авиации
                           $sql_trip = $sql_trip . ' AND locor.`id_organ` =  ' . $id_organ;
             $sql_mark_trip = $sql_mark_trip . ' AND locor.`id_organ` =  ' . $id_organ;
                      }

                       else {//UMCHS
            $sql_trip = $sql_trip . ' AND locor.`id_organ` NOT IN(8,9,12) '; //кроме РОСН, УГЗ, Авиации
             $sql_mark_trip = $sql_mark_trip . ' AND locor.`id_organ` NOT IN(8,9,12) '; //кроме РОСН, УГЗ, Авиации
        }


        /* ------------------------ по какой области/подразделению ищем --------------------------- */

                if($id_organ == 8 || $id_organ == 9) {//ROSN, UGZ
            if (!empty($region)) {
                if (!empty($grochs)) {
                    $sql_trip = $sql_trip . ' and locor.id = ' . $grochs;
                     $sql_mark_trip = $sql_mark_trip . ' and locor.id = ' . $grochs;
                } else{ //oblast
                    $sql_trip = $sql_trip . ' and org.id = ' . $region;
                     $sql_mark_trip = $sql_mark_trip . ' and org.id = ' . $region;
                }
            }
        }
        else{//UMCHS, Avia
            //область/грочс/часть
            if (!empty($region)) {
                if (!empty($grochs)) {
                    if (!empty($divizion)){ //pasp
                        $sql_trip = $sql_trip . ' and re.id = ' . $divizion;
                      $sql_mark_trip = $sql_mark_trip . ' and re.id = ' . $divizion;
                    }
                    else{ //rochs
                        $sql_trip = $sql_trip . ' and locor.id = ' . $grochs;
                         $sql_mark_trip = $sql_mark_trip . ' and locor.id = ' . $grochs;
                    }
                } else{ //oblast
                    $sql_trip = $sql_trip . ' and reg.id = ' . $region;
                    $sql_mark_trip = $sql_mark_trip . ' and reg.id = ' . $region;
                }
            }
        }

        //print_r($sql);
        //exit();

        /* ------------------------ КОНЕЦ по какой области/подразделению ищем --------------------------- */

        $sql_trip=$sql_trip."  group by re.id  ORDER BY `reg`.`name`,`locor`.`id`,`loc`.`name`,`re`.`divizion_num`";
        $res_trip=R::getAll($sql_trip);

        $data['res_trip']=$res_trip;

        $sql_mark_trip = $sql_mark_trip . "   ORDER BY `reg`.`name`,`locor`.`id`,`loc`.`name`,`re`.`divizion_num`";
        $res_mark_trip = R::getAll($sql_mark_trip);


        //массив из марок техники по каждой ПАСЧ
        $res_mark_array_trip=array();
        foreach ($res_mark_trip as $value) {
            $res_mark_array_trip[$value['id_pasp']][]=array('mark'=>$value['mark'],'vid'=>$value['id_vid'],'id_type'=>$value['id_type'],'id_to'=>$value['id_to'],'is_repair'=>$value['is_repair'],'powder'=>$value['powder'],'foam'=>$value['foam']);
        }
        $data['res_mark_array_trip'] = $res_mark_array_trip;

//echo '<br>'; echo '<b>Общие сведения trip</b>';echo '<br>';
//                      print_r($res);
// echo '<br>';echo '<b>Mark</b>';echo '<br>';
//        print_r($res_mark);
 //echo '<br>';echo '<b>Mark array trip</b>';echo '<br>';
       //  print_r($res_mark_array_trip);
// echo '<br>';echo '<b>Из др ПАСЧ</b>';echo '<br>';
     //   exit();

        /*----------------------------- КОНЕЦ  уехала в командировку  ------------------------------------*/


        //storage
        $storage=R::getAll("select cc.id_card as id_pasp, st.asv, st.foam, st.powder from storage as st left join cardch as cc on st.id_cardch=cc.id WHERE st.dateduty = ?",array($date_start));
        $data['storage']=$storage;

            if (!empty($res) || !empty($teh_from_other_card)) {

//export to excel
                exportToExcelDetailTeh($res,$teh_from_other_card_array,$id_organ,$data);

        } else {
            $app->render('msg/emtyResult.php', $data); //no result
        }


    });

        function exportToExcelDetailTeh($res, $teh_from_other_card_array, $id_organ, $data) {


          //  print_r($data['res_mark_array']);
         //  exit();
        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/detail_teh/detail_teh.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 5;
        $i = 0;
        $last_id_grochs = 0;
        $last_id_region = 0;

        $itogo['grochs'] = array('br_osn' => 0, 'br_spec' => 0, 'br_ing' => 0, 'br_vsp' => 0, 'res_osn' => 0, 'res_spec' => 0, 'res_ing' => 0, 'res_vsp' => 0, 'to1' => 0, 'to2' => 0, 'is_repair' => 0, 'trip' => 0,'powder_br'=>0,'powder_res'=>0,'foam_br'=>0,'foam_res'=>0,'storage_asv'=>0,'storage_powder'=>0,'storage_foam'=>0);
         $itogo['region'] = array('br_osn' => 0, 'br_spec' => 0, 'br_ing' => 0, 'br_vsp' => 0, 'res_osn' => 0, 'res_spec' => 0, 'res_ing' => 0, 'res_vsp' => 0, 'to1' => 0, 'to2' => 0, 'is_repair' => 0, 'trip' => 0,'powder_br'=>0,'powder_res'=>0,'foam_br'=>0,'foam_res'=>0,'storage_asv'=>0,'storage_powder'=>0,'storage_foam'=>0);
          $itogo['rb'] = array('br_osn' => 0, 'br_spec' => 0, 'br_ing' => 0, 'br_vsp' => 0, 'res_osn' => 0, 'res_spec' => 0, 'res_ing' => 0, 'res_vsp' => 0, 'to1' => 0, 'to2' => 0, 'is_repair' => 0, 'trip' => 0,'powder_br'=>0,'powder_res'=>0,'foam_br'=>0,'foam_res'=>0,'storage_asv'=>0,'storage_powder'=>0,'storage_foam'=>0);

        $id_native_teh = array();
        foreach ($res as $value) {//родная техника записать id техники в массив
            $id_native_teh[] = $value['id_pasp'];
        }
        foreach ($teh_from_other_card_array as $key => $value) {
            if (!in_array($key, $id_native_teh)) {//добавить в массив информацию о подразделении, где есть только чужая техника, а родной нет
                $res[] = $value;
                unset($teh_from_other_card_array[$key]);
            }
        }

        /* +++++++++++++++++++++ style ++++++++++ */
        /* Итого по ГРОЧС */
        $style_all_grochs = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => '99CCCC'
                )
            ),
                        // Выравнивание
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* Итого по области */
        $style_all_region = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => '00CECE'
                )
            ),
                        // Выравнивание
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* ИТОГО */
        $style_all = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'DFE53E'
                )
            ),
                        // Выравнивание
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* +++++++++++++ end style +++++++++++++ */

//                unset($main['itogo']);
//    unset($main['itogo_obl']);
//    unset($main['itogo_rb']);
//         $sheet->setCellValue('A' . 2, 'наименование техники: '.(!empty( $data['query_name_teh'])) ?  $data['query_name_teh'] : 'все'
//                 .', вид техники: '.(!empty($data['query_vid_teh'])) ? $data['query_vid_teh'] : 'все');


        $date = new DateTime($data['date_start']);
        $date_start = $date->Format('d-m-Y');

        $sheet->setCellValue('A' . 1, 'Результат запроса за ' . $date_start);

        //родная техника
        foreach ($res as $value) {
            $i++;

            /* --------------------------------------  ИТОГО-------------------------------------------- */

         //   if ($id_organ != 8 && $id_organ != 9) {//кроме РОСН/UGZ
                if ($value['id_grochs'] != $last_id_grochs && $last_id_grochs != 0) {//итого по ГРОЧС
                    /* ++++ Итого по ГРОЧС ++++ */
                    $sheet->setCellValue('A' . $r, 'ИТОГО по Г(Р)ОЧС:');

                    $sheet->setCellValue('B' . $r, ($itogo['grochs']['br_osn'] != 0) ? $itogo['grochs']['br_osn']:'');
                    $sheet->setCellValue('C' . $r, ($itogo['grochs']['br_spec'] != 0) ? $itogo['grochs']['br_spec']:'');
                    $sheet->setCellValue('D' . $r, ($itogo['grochs']['br_ing'])?$itogo['grochs']['br_ing']:'');
                    $sheet->setCellValue('E' . $r, ($itogo['grochs']['br_vsp'])?$itogo['grochs']['br_vsp']:'');

                    $sheet->setCellValue('I' . $r, ($itogo['grochs']['res_osn'])?$itogo['grochs']['res_osn']:'');
                    $sheet->setCellValue('J' . $r, ($itogo['grochs']['res_spec'])?$itogo['grochs']['res_spec']:'');
                    $sheet->setCellValue('K' . $r, ($itogo['grochs']['res_ing'])?$itogo['grochs']['res_ing']:'');
                    $sheet->setCellValue('L' . $r, ($itogo['grochs']['res_vsp'])?$itogo['grochs']['res_vsp']:'');

                    $sheet->setCellValue('S' . $r, ($itogo['grochs']['to1'])?$itogo['grochs']['to1']:'');
                    $sheet->setCellValue('T' . $r, ( $itogo['grochs']['to2'])?$itogo['grochs']['to2']:'');
                    $sheet->setCellValue('U' . $r,( $itogo['grochs']['is_repair'])? $itogo['grochs']['is_repair']:'');
                    $sheet->setCellValue('V' . $r, ($itogo['grochs']['trip'])?$itogo['grochs']['trip']:'');

                    $sheet->setCellValue('G' . $r, ($itogo['grochs']['powder_br'])?$itogo['grochs']['powder_br']:'');
                    $sheet->setCellValue('H' . $r, ($itogo['grochs']['foam_br'])?$itogo['grochs']['foam_br']:'');
                    $sheet->setCellValue('N' . $r, ($itogo['grochs']['powder_res'])?$itogo['grochs']['powder_res']:'');
                    $sheet->setCellValue('O' . $r, ($itogo['grochs']['foam_res'])?$itogo['grochs']['foam_res']:'');

                      $sheet->setCellValue('P' . $r, ($itogo['grochs']['storage_asv'])?$itogo['grochs']['storage_asv']:'');
                     $sheet->setCellValue('Q' . $r, ($itogo['grochs']['storage_powder'])?$itogo['grochs']['storage_powder']:'');
                     $sheet->setCellValue('R' . $r, ($itogo['grochs']['storage_foam'])?$itogo['grochs']['storage_foam']:'');

                    //обнулить
                    $itogo['grochs'] = array('br_osn' => 0, 'br_spec' => 0, 'br_ing' => 0, 'br_vsp' => 0, 'res_osn' => 0, 'res_spec' => 0, 'res_ing' => 0, 'res_vsp' => 0, 'to1' => 0, 'to2' => 0, 'is_repair' => 0, 'trip' => 0,'powder_br'=>0,'powder_res'=>0,'foam_br'=>0,'foam_res'=>0,'storage_asv'=>0,'storage_powder'=>0,'storage_foam'=>0);


                    $sheet->getStyleByColumnAndRow(0, $r, 21, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС

                    $r++;
                }
                if ($value['region_id'] != $last_id_region && $last_id_region != 0) {//итого по region
                    $sheet->setCellValue('A' . $r, 'ИТОГО по области:');

                $sheet->setCellValue('B' . $r, ($itogo['region']['br_osn'])?$itogo['region']['br_osn']:'');
                $sheet->setCellValue('C' . $r,( $itogo['region']['br_spec'])? $itogo['region']['br_spec']:'');
                $sheet->setCellValue('D' . $r, ($itogo['region']['br_ing'])?$itogo['region']['br_ing']:'');
                $sheet->setCellValue('E' . $r, ($itogo['region']['br_vsp'])?$itogo['region']['br_vsp']:'');

                $sheet->setCellValue('I' . $r, ($itogo['region']['res_osn'])?$itogo['region']['res_osn']:'');
                $sheet->setCellValue('J' . $r, ($itogo['region']['res_spec'])?$itogo['region']['res_spec']:'');
                $sheet->setCellValue('K' . $r, ($itogo['region']['res_ing'])?$itogo['region']['res_ing']:'');
                $sheet->setCellValue('L' . $r, ($itogo['region']['res_vsp'])?$itogo['region']['res_vsp']:'');

                $sheet->setCellValue('S' . $r, ($itogo['region']['to1'])?$itogo['region']['to1']:'');
                $sheet->setCellValue('T' . $r, ($itogo['region']['to2'])?$itogo['region']['to2']:'');
                $sheet->setCellValue('U' . $r, ($itogo['region']['is_repair'])?$itogo['region']['is_repair']:'');
                $sheet->setCellValue('V' . $r, ($itogo['region']['trip'])?$itogo['region']['trip']:'');


                    $sheet->setCellValue('G' . $r, ($itogo['region']['powder_br'])?$itogo['region']['powder_br']:'');
                    $sheet->setCellValue('H' . $r, ($itogo['region']['foam_br'])?$itogo['region']['foam_br']:'');
                    $sheet->setCellValue('N' . $r, ($itogo['region']['powder_res'])?$itogo['region']['powder_res']:'');
                    $sheet->setCellValue('O' . $r, ($itogo['region']['foam_res'])?$itogo['region']['foam_res']:'');



                                         $sheet->setCellValue('P' . $r, ($itogo['region']['storage_asv'])?$itogo['region']['storage_asv']:'');
                     $sheet->setCellValue('Q' . $r, ($itogo['region']['storage_powder'])?$itogo['region']['storage_powder']:'');
                     $sheet->setCellValue('R' . $r, ($itogo['region']['storage_foam'])?$itogo['region']['storage_foam']:'');


                    $itogo['rb']['br_osn']+=$itogo['region']['br_osn'];
                    $itogo['rb']['br_spec']+=$itogo['region']['br_spec'];
                    $itogo['rb']['br_ing']+=$itogo['region']['br_ing'];
                    $itogo['rb']['br_vsp']+=$itogo['region']['br_vsp'];

                    $itogo['rb']['res_osn']+=$itogo['region']['res_osn'];
                    $itogo['rb']['res_spec']+=$itogo['region']['res_spec'];
                    $itogo['rb']['res_ing']+=$itogo['region']['res_ing'];
                    $itogo['rb']['res_vsp']+=$itogo['region']['res_vsp'];

                    $itogo['rb']['to1']+=$itogo['region']['to1'];
                    $itogo['rb']['to2']+=$itogo['region']['to2'];
                    $itogo['rb']['is_repair']+=$itogo['region']['is_repair'];
                    $itogo['rb']['trip']+=$itogo['region']['trip'];

                                    $itogo['rb']['powder_br']+=$itogo['region']['powder_br'];
                $itogo['rb']['powder_res']+=$itogo['region']['powder_res'];
                 $itogo['rb']['foam_br']+=$itogo['region']['foam_br'];
                $itogo['rb']['foam_res']+=$itogo['region']['foam_res'];


                                $itogo['rb']['storage_asv']+=$itogo['region']['storage_asv'];
                $itogo['rb']['storage_powder']+=$itogo['region']['storage_powder'];
                 $itogo['rb']['storage_foam']+=$itogo['region']['storage_foam'];


                    $itogo['region'] = array('br_osn' => 0, 'br_spec' => 0, 'br_ing' => 0, 'br_vsp' => 0, 'res_osn' => 0, 'res_spec' => 0, 'res_ing' => 0, 'res_vsp' => 0, 'to1' => 0, 'to2' => 0, 'is_repair' => 0, 'trip' => 0,'powder_br'=>0,'powder_res'=>0,'foam_br'=>0,'foam_res'=>0,'storage_asv'=>0,'storage_powder'=>0,'storage_foam'=>0);

                    //$region_all = 0; //обнулить

                    $sheet->getStyleByColumnAndRow(0, $r, 21, $r)->applyFromArray($style_all_region); //Итого по области

                    $r++;
                }
          //  }
            /*  ---------------------------- END ИТОГО ----------------------------------------- */


            // print_r($data['teh_mark_from_other_card_array']);
            //exit();

            $co_from_other_pasp = (isset($teh_from_other_card_array[$value['id_pasp']]['co'])) ? $teh_from_other_card_array[$value['id_pasp']]['co'] : 0; //кол-во техники, которая пришла  из др подразд

            if (( isset($value['co']) && $value['co'] != 0 ) || $co_from_other_pasp != 0) {

                $sheet->setCellValue('A' . $r, $value['region_name'] . ',' . chr(10) . $value['organ'] . ',' . chr(10) . $value['divizion']);


                // print_r($data['res_mark_array'][$value['id_pasp']]);
                // exit();

                $all_teh_arr = array(); //массив марок


                if (isset($data['res_mark_array'][$value['id_pasp']])) {//марка родной техники
                    foreach ($data['res_mark_array'][$value['id_pasp']] as $mark) {
                        //echo $mark . '<br>';
                        $all_teh_arr[$mark['id_type']][$mark['vid']][] = $mark['mark'];
                        $all_teh_arr['to'][$mark['id_to']][] = $mark['mark'];
                        $all_teh_arr['repair'][$mark['is_repair']][] = $mark['mark'];
                         $all_teh_arr['powder'][$mark['id_type']][] = $mark['powder'];
                         $all_teh_arr['foam'][$mark['id_type']][] = $mark['foam'];

                    }
                }
                if (isset($data['teh_mark_from_other_card_array'][$value['id_pasp']])) {//марки техники из др пасч
                    foreach ($data['teh_mark_from_other_card_array'][$value['id_pasp']] as $mark) {
                        // echo '<b><i>' . $mark . '</i></b><br>';
                        $all_teh_arr[$mark['id_type']][$mark['vid']][] = $mark['mark'];
                        $all_teh_arr['to'][$mark['id_to']][] = $mark['mark'];
                        $all_teh_arr['repair'][$mark['is_repair']][] = $mark['mark'];
                        $all_teh_arr['powder'][$mark['id_type']][] = $mark['powder'];
                         $all_teh_arr['foam'][$mark['id_type']][] = $mark['foam'];

                    }
                }

                                if (isset($data['res_mark_array_trip'][$value['id_pasp']])) {//марки техники в командировке
                    foreach ($data['res_mark_array_trip'][$value['id_pasp']] as $mark) {
                        $all_teh_arr['trip'][] = $mark['mark'];
                    }
                }

            //   print_r($all_teh_arr['foam']);
              //  exit();


                  //print_r($all_teh_arr['powder'][2]);
                 // exit();
                //б/р основная  1-1
                if (isset($all_teh_arr[1][1]) && !empty($all_teh_arr[1][1])) {
                    $all_teh_arr_string = implode(';'.chr(10), $all_teh_arr[1][1]); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('B' . $r, $all_teh_arr_string);

                    $itogo['grochs']['br_osn']+=count($all_teh_arr[1][1]); //itogo grochs
                    $itogo['region']['br_osn']+=count($all_teh_arr[1][1]); //itogo region
                }


                //б/р спец  1-2
                if (isset($all_teh_arr[1][2]) && !empty($all_teh_arr[1][2])) {
                    $all_teh_arr_string = implode(';'.chr(10), $all_teh_arr[1][2]); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('C' . $r, $all_teh_arr_string);

                    $itogo['grochs']['br_spec']+=count($all_teh_arr[1][2]); //itogo grochs
                    $itogo['region']['br_spec']+=count($all_teh_arr[1][2]); //itogo region
                }


                //б/р инж  1-4
                if (isset($all_teh_arr[1][4]) && !empty($all_teh_arr[1][4])) {
                    $all_teh_arr_string = implode(';'.chr(10), $all_teh_arr[1][4]); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('D' . $r, $all_teh_arr_string);

                    $itogo['grochs']['br_ing']+=count($all_teh_arr[1][4]); //itogo grochs
                    $itogo['region']['br_ing']+=count($all_teh_arr[1][4]); //itogo region
                }


                //б/р вспомог  1-3
                if (isset($all_teh_arr[1][3]) && !empty($all_teh_arr[1][3])) {
                    $all_teh_arr_string = implode(';'.chr(10), $all_teh_arr[1][3]); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('E' . $r, $all_teh_arr_string);

                    $itogo['grochs']['br_vsp']+=count($all_teh_arr[1][3]); //itogo grochs
                    $itogo['region']['br_vsp']+=count($all_teh_arr[1][3]); //itogo region
                }



                //резерв основная  2-1
                if (isset($all_teh_arr[2][1]) && !empty($all_teh_arr[2][1])) {
                    $all_teh_arr_string = implode(';'.chr(10), $all_teh_arr[2][1]); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('I' . $r, $all_teh_arr_string);

                    $itogo['grochs']['res_osn']+=count($all_teh_arr[2][1]); //itogo grochs
                    $itogo['region']['res_osn']+=count($all_teh_arr[2][1]); //itogo region
                }


                //резерв спец  2-2
                if (isset($all_teh_arr[2][2]) && !empty($all_teh_arr[2][2])) {
                    $all_teh_arr_string = implode(';'.chr(10), $all_teh_arr[2][2]); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('G' . $r, $all_teh_arr_string);

                    $itogo['grochs']['res_spec']+=count($all_teh_arr[2][2]); //itogo grochs
                    $itogo['region']['res_spec']+=count($all_teh_arr[2][2]); //itogo region
                }


                //резерв инж  2-4
                if (isset($all_teh_arr[2][4]) && !empty($all_teh_arr[2][4])) {
                    $all_teh_arr_string = implode(';'.chr(10), $all_teh_arr[2][4]); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('K' . $r, $all_teh_arr_string);

                    $itogo['grochs']['res_ing']+=count($all_teh_arr[2][4]); //itogo grochs
                    $itogo['region']['res_ing']+=count($all_teh_arr[2][4]); //itogo region
                }


                //резерв вспомог  2-3
                if (isset($all_teh_arr[2][3]) && !empty($all_teh_arr[2][3])) {
                    $all_teh_arr_string = implode(';'.chr(10), $all_teh_arr[2][3]); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('L' . $r, $all_teh_arr_string);

                    $itogo['grochs']['res_vsp']+=count($all_teh_arr[2][3]); //itogo grochs
                    $itogo['region']['res_vsp']+=count($all_teh_arr[2][3]); //itogo region
                }



                //TO-1
                //TO-1  to-1
                if (isset($all_teh_arr['to'][1]) && !empty($all_teh_arr['to'][1])) {
                    $all_teh_arr_string = implode(';'.chr(10), $all_teh_arr['to'][1]); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('S' . $r, $all_teh_arr_string);

                    $itogo['grochs']['to1']+=count($all_teh_arr['to'][1]); //itogo grochs
                    $itogo['region']['to1']+=count($all_teh_arr['to'][1]); //itogo region
                }


                //TO-2
                //TO-2  to-2
                if (isset($all_teh_arr['to'][2]) && !empty($all_teh_arr['to'][2])) {
                    $all_teh_arr_string = implode(';'.chr(10), $all_teh_arr['to'][2]); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('T' . $r, $all_teh_arr_string);

                    $itogo['grochs']['to2']+=count($all_teh_arr['to'][2]); //itogo grochs
                    $itogo['region']['to2']+=count($all_teh_arr['to'][2]); //itogo region
                }


                //ремонт
                //ремонт is_repair=1
                if (isset($all_teh_arr['repair'][1]) && !empty($all_teh_arr['repair'][1])) {
                    $all_teh_arr_string = implode(';'.chr(10), $all_teh_arr['repair'][1]); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('U' . $r, $all_teh_arr_string);

                    $itogo['grochs']['is_repair']+=count($all_teh_arr['repair'][1]); //itogo grochs
                    $itogo['region']['is_repair']+=count($all_teh_arr['repair'][1]); //itogo region
                }

                //trip
                                if (isset($all_teh_arr['trip']) && !empty($all_teh_arr['trip'])) {
                    $all_teh_arr_string = implode(';'.chr(10), $all_teh_arr['trip']); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('V' . $r, $all_teh_arr_string);

                    $itogo['grochs']['trip']+=count($all_teh_arr['trip']); //itogo grochs
                    $itogo['region']['trip']+=count($all_teh_arr['trip']); //itogo region
                }


                //порошок б/р
                                if (isset($all_teh_arr['powder'][1]) && !empty($all_teh_arr['powder'][1])) {
                    $all_teh_arr_string = array_sum($all_teh_arr['powder'][1]); //через , все марки - чтобы поместить в ячецку

                    $sheet->setCellValue('G' . $r, ($all_teh_arr_string == 0)?'':$all_teh_arr_string);//сумма порошка в б/р

                    $itogo['grochs']['powder_br']+=$all_teh_arr_string; //itogo grochs
                    $itogo['region']['powder_br']+=$all_teh_arr_string; //itogo region

                }
                              //порошок резерв
                                if (isset($all_teh_arr['powder'][2]) && !empty($all_teh_arr['powder'][2])) {
                    $all_teh_arr_string = array_sum($all_teh_arr['powder'][2]); //сумма порошка в резерве

                    $sheet->setCellValue('N' . $r, ($all_teh_arr_string == 0)?'':$all_teh_arr_string);

                    $itogo['grochs']['powder_res']+=$all_teh_arr_string; //itogo grochs
                    $itogo['region']['powder_res']+=$all_teh_arr_string; //itogo region

                }
//пенообразователь б/р
                                if (isset($all_teh_arr['foam'][1]) && !empty($all_teh_arr['foam'][1])) {
                    $all_teh_arr_string = array_sum($all_teh_arr['foam'][1]); //сумма пенообразователя в б/р

                    $sheet->setCellValue('H' . $r,($all_teh_arr_string == 0)?'':$all_teh_arr_string);

                    $itogo['grochs']['foam_br']+=$all_teh_arr_string; //itogo grochs
                    $itogo['region']['foam_br']+=$all_teh_arr_string; //itogo region

                }
                //пенообразователь резерв
                                                if (isset($all_teh_arr['foam'][2]) && !empty($all_teh_arr['foam'][2])) {
                    $all_teh_arr_string = array_sum($all_teh_arr['foam'][2]); //сумма пенообразователя в reserve

                    $sheet->setCellValue('O' . $r, ($all_teh_arr_string == 0)?'':$all_teh_arr_string);

                    $itogo['grochs']['foam_res']+=$all_teh_arr_string; //itogo grochs
                    $itogo['region']['foam_res']+=$all_teh_arr_string; //itogo region

                }


//storage
                foreach ($data['storage'] as $st) {
                  if($value['id_pasp']==$st['id_pasp']){
                      $sheet->setCellValue('P' . $r, $st['asv']);
                       $sheet->setCellValue('Q' . $r, $st['powder']);
                        $sheet->setCellValue('R' . $r, $st['foam']);

                        $itogo['grochs']['storage_asv']+=$st['asv']; //itogo grochs
                         $itogo['region']['storage_asv']+=$st['asv']; //itogo region


                        $itogo['grochs']['storage_powder']+=str_replace(',','.',$st['powder']); //itogo grochs
                    $itogo['region']['storage_powder']+=str_replace(',','.',$st['powder']); //itogo region
                    //echo $st['powder'];

                      $itogo['grochs']['storage_foam']+=str_replace(',','.',$st['foam']); //itogo storage
                    $itogo['region']['storage_foam']+=str_replace(',','.',$st['foam']); //itogo region
                  }
                }

                //print_r($all_teh_arr_string);
                // exit();
                //  echo $all_teh_arr_string;
                // exit();
//                $count = $value['co'] + $co_from_other_pasp;
//                $sheet->setCellValue('E' . $r, $count);
//
//                $grochs_all+=$value['co'] + $co_from_other_pasp;
//                $region_all+=$value['co'] + $co_from_other_pasp;
//                $rb_all+=$value['co'] + $co_from_other_pasp;

                $r++;
            }

            $last_id_grochs = $value['id_grochs'];
            $last_id_region = $value['region_id'];
        }


        /* -------------------------------------------------  ИТОГО------------------------------------------------------------ */
      //  if ($id_organ != 8 && $id_organ != 9) {//кроме РОСН/UGZ
            /* ++++ Итого по ГРОЧС ++++ */
            if ($last_id_grochs && $last_id_grochs != 0) {
                /* ++++ Итого по ГРОЧС ++++ */
                $sheet->setCellValue('A' . $r, 'ИТОГО по Г(Р)ОЧС:');

                      $sheet->setCellValue('B' . $r, ($itogo['grochs']['br_osn'] != 0) ? $itogo['grochs']['br_osn']:'');
                    $sheet->setCellValue('C' . $r, ($itogo['grochs']['br_spec'] != 0) ? $itogo['grochs']['br_spec']:'');
                    $sheet->setCellValue('D' . $r, ($itogo['grochs']['br_ing'])?$itogo['grochs']['br_ing']:'');
                    $sheet->setCellValue('E' . $r, ($itogo['grochs']['br_vsp'])?$itogo['grochs']['br_vsp']:'');

                    $sheet->setCellValue('I' . $r, ($itogo['grochs']['res_osn'])?$itogo['grochs']['res_osn']:'');
                    $sheet->setCellValue('J' . $r, ($itogo['grochs']['res_spec'])?$itogo['grochs']['res_spec']:'');
                    $sheet->setCellValue('K' . $r, ($itogo['grochs']['res_ing'])?$itogo['grochs']['res_ing']:'');
                    $sheet->setCellValue('L' . $r, ($itogo['grochs']['res_vsp'])?$itogo['grochs']['res_vsp']:'');

                    $sheet->setCellValue('S' . $r, ($itogo['grochs']['to1'])?$itogo['grochs']['to1']:'');
                    $sheet->setCellValue('T' . $r, ( $itogo['grochs']['to2'])?$itogo['grochs']['to2']:'');
                    $sheet->setCellValue('U' . $r,( $itogo['grochs']['is_repair'])? $itogo['grochs']['is_repair']:'');
                    $sheet->setCellValue('V' . $r, ($itogo['grochs']['trip'])?$itogo['grochs']['trip']:'');


                    $sheet->setCellValue('G' . $r, ($itogo['grochs']['powder_br'])?$itogo['grochs']['powder_br']:'');
                    $sheet->setCellValue('H' . $r, ($itogo['grochs']['foam_br'])?$itogo['grochs']['foam_br']:'');
                    $sheet->setCellValue('N' . $r, ($itogo['grochs']['powder_res'])?$itogo['grochs']['powder_res']:'');
                    $sheet->setCellValue('O' . $r, ($itogo['grochs']['foam_res'])?$itogo['grochs']['foam_res']:'');


                     $sheet->setCellValue('P' . $r, ($itogo['grochs']['storage_asv'])?$itogo['grochs']['storage_asv']:'');
                     $sheet->setCellValue('Q' . $r, ($itogo['grochs']['storage_powder'])?$itogo['grochs']['storage_powder']:'');
                     $sheet->setCellValue('R' . $r, ($itogo['grochs']['storage_foam'])?$itogo['grochs']['storage_foam']:'');


                //$sheet->setCellValue('D' . $r, '');
                //$sheet->setCellValue('E' . $r, $grochs_all);
                //обнулить
                $itogo['grochs'] = array('br_osn' => 0, 'br_spec' => 0, 'br_ing' => 0, 'br_vsp' => 0, 'res_osn' => 0, 'res_spec' => 0, 'res_ing' => 0, 'res_vsp' => 0, 'to1' => 0, 'to2' => 0, 'is_repair' => 0, 'trip' => 0,'powder_br'=>0,'powder_res'=>0,'foam_br'=>0,'foam_res'=>0,'storage_asv'=>0,'storage_powder'=>0,'storage_foam'=>0);


                $sheet->getStyleByColumnAndRow(0, $r, 21, $r)->applyFromArray($style_all_grochs); //Итого по ГРОЧС

                $r++;
            }
            /* ++++ Итого по области ++++ */
            if ($last_id_region && $last_id_region != 0) {
                $sheet->setCellValue('A' . $r, 'ИТОГО по области:');

                $sheet->setCellValue('B' . $r, ($itogo['region']['br_osn'])?$itogo['region']['br_osn']:'');
                $sheet->setCellValue('C' . $r,( $itogo['region']['br_spec'])? $itogo['region']['br_spec']:'');
                $sheet->setCellValue('D' . $r, ($itogo['region']['br_ing'])?$itogo['region']['br_ing']:'');
                $sheet->setCellValue('E' . $r, ($itogo['region']['br_vsp'])?$itogo['region']['br_vsp']:'');

                $sheet->setCellValue('I' . $r, ($itogo['region']['res_osn'])?$itogo['region']['res_osn']:'');
                $sheet->setCellValue('J' . $r, ($itogo['region']['res_spec'])?$itogo['region']['res_spec']:'');
                $sheet->setCellValue('K' . $r, ($itogo['region']['res_ing'])?$itogo['region']['res_ing']:'');
                $sheet->setCellValue('L' . $r, ($itogo['region']['res_vsp'])?$itogo['region']['res_vsp']:'');

                $sheet->setCellValue('S' . $r, ($itogo['region']['to1'])?$itogo['region']['to1']:'');
                $sheet->setCellValue('T' . $r, ($itogo['region']['to2'])?$itogo['region']['to2']:'');
                $sheet->setCellValue('U' . $r, ($itogo['region']['is_repair'])?$itogo['region']['is_repair']:'');
                $sheet->setCellValue('V' . $r, ($itogo['region']['trip'])?$itogo['region']['trip']:'');

                    $sheet->setCellValue('G' . $r, ($itogo['region']['powder_br'])?$itogo['region']['powder_br']:'');
                    $sheet->setCellValue('H' . $r, ($itogo['region']['foam_br'])?$itogo['region']['foam_br']:'');
                    $sheet->setCellValue('N' . $r, ($itogo['region']['powder_res'])?$itogo['region']['powder_res']:'');
                    $sheet->setCellValue('O' . $r, ($itogo['region']['foam_res'])?$itogo['region']['foam_res']:'');


                                         $sheet->setCellValue('P' . $r, ($itogo['region']['storage_asv'])?$itogo['region']['storage_asv']:'');
                     $sheet->setCellValue('Q' . $r, ($itogo['region']['storage_powder'])?$itogo['region']['storage_powder']:'');
                     $sheet->setCellValue('R' . $r, ($itogo['region']['storage_foam'])?$itogo['region']['storage_foam']:'');



// подсчет итого по РБ
                $itogo['rb']['br_osn']+=$itogo['region']['br_osn'];
                $itogo['rb']['br_spec']+=$itogo['region']['br_spec'];
                $itogo['rb']['br_ing']+=$itogo['region']['br_ing'];
                $itogo['rb']['br_vsp']+=$itogo['region']['br_vsp'];

                $itogo['rb']['res_osn']+=$itogo['region']['res_osn'];
                $itogo['rb']['res_spec']+=$itogo['region']['res_spec'];
                $itogo['rb']['res_ing']+=$itogo['region']['res_ing'];
                $itogo['rb']['res_vsp']+=$itogo['region']['res_vsp'];

                $itogo['rb']['to1']+=$itogo['region']['to1'];
                $itogo['rb']['to2']+=$itogo['region']['to2'];
                $itogo['rb']['is_repair']+=$itogo['region']['is_repair'];
                $itogo['rb']['trip']+=$itogo['region']['trip'];
                $itogo['rb']['powder_br']+=$itogo['region']['powder_br'];
                $itogo['rb']['powder_res']+=$itogo['region']['powder_res'];
                 $itogo['rb']['foam_br']+=$itogo['region']['foam_br'];
                $itogo['rb']['foam_res']+=$itogo['region']['foam_res'];

                $itogo['rb']['storage_asv']+=$itogo['region']['storage_asv'];
                $itogo['rb']['storage_powder']+=$itogo['region']['storage_powder'];
                 $itogo['rb']['storage_foam']+=$itogo['region']['storage_foam'];

//обнулить
                $itogo['region'] = array('br_osn' => 0, 'br_spec' => 0, 'br_ing' => 0, 'br_vsp' => 0, 'res_osn' => 0, 'res_spec' => 0, 'res_ing' => 0, 'res_vsp' => 0, 'to1' => 0, 'to2' => 0, 'is_repair' => 0, 'trip' => 0,'powder_br'=>0,'powder_res'=>0,'foam_br'=>0,'foam_res'=>0,'storage_asv'=>0,'storage_powder'=>0,'storage_foam'=>0);

                $sheet->getStyleByColumnAndRow(0, $r, 21, $r)->applyFromArray($style_all_region); //Итого по области

                $r++;
            }
        //}

        //ИТОГО

        $sheet->setCellValue('A' . $r, 'ИТОГО:');
        $sheet->setCellValue('B' . $r, ($itogo['rb']['br_osn'])?$itogo['rb']['br_osn']:'');
        $sheet->setCellValue('C' . $r, ($itogo['rb']['br_spec'])?$itogo['rb']['br_spec']:'');
        $sheet->setCellValue('D' . $r, ($itogo['rb']['br_ing'])?$itogo['rb']['br_ing']:'');
        $sheet->setCellValue('E' . $r, ($itogo['rb']['br_vsp'])?$itogo['rb']['br_vsp']:'');

        $sheet->setCellValue('I' . $r, ($itogo['rb']['res_osn'])?$itogo['rb']['res_osn']:'');
        $sheet->setCellValue('J' . $r, ($itogo['rb']['res_spec'])?$itogo['rb']['res_spec']:'');
        $sheet->setCellValue('K' . $r, ($itogo['rb']['res_ing'])?$itogo['rb']['res_ing']:'');
        $sheet->setCellValue('L' . $r, ($itogo['rb']['res_vsp'])?$itogo['rb']['res_vsp']:'');

        $sheet->setCellValue('S' . $r, ($itogo['rb']['to1'])?$itogo['rb']['to1']:'');
        $sheet->setCellValue('T' . $r, ($itogo['rb']['to2'])?$itogo['rb']['to2']:'');
        $sheet->setCellValue('U' . $r, ($itogo['rb']['is_repair'])?$itogo['rb']['is_repair']:'');
        $sheet->setCellValue('V' . $r, ($itogo['rb']['trip'])?$itogo['rb']['trip']:'');

                             $sheet->setCellValue('G' . $r, ($itogo['rb']['powder_br'])?$itogo['rb']['powder_br']:'');
                    $sheet->setCellValue('H' . $r, ($itogo['rb']['foam_br'])?$itogo['rb']['foam_br']:'');
                    $sheet->setCellValue('N' . $r, ($itogo['rb']['powder_res'])?$itogo['rb']['powder_res']:'');
                    $sheet->setCellValue('O' . $r, ($itogo['rb']['foam_res'])?$itogo['rb']['foam_res']:'');

                      $sheet->setCellValue('P' . $r, ($itogo['rb']['storage_asv'])?$itogo['rb']['storage_asv']:'');
                     $sheet->setCellValue('Q' . $r, ($itogo['rb']['storage_powder'])?$itogo['rb']['storage_powder']:'');
                     $sheet->setCellValue('R' . $r, ($itogo['rb']['storage_foam'])?$itogo['rb']['storage_foam']:'');

        $sheet->getStyleByColumnAndRow(0, $r, 21, $r)->applyFromArray($style_all); //ИТОГО

        /* -------------------------------------------- END ИТОГО----------------------------------------------- */

        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="detail_teh.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }


    //по области общее кол-во техники
        $app->get('/detail_teh/region', function () use ($app) {



            $data['region'] = R::getAll('SELECT * FROM ss.regions'); //список область
             $data['region'][]=array('id'=>8,'name'=>'РОСН');
             $data['region'][]=array('id'=>9,'name'=>'УГЗ');
             $data['region'][]=array('id'=>12,'name'=>'Авиация');

              $data['region'][]=array('id'=>160,'name'=>'РОСН г.Минск');
               $data['region'][]=array('id'=>163,'name'=>'РОСН г.Пинск');

               $data['region'][]=array('id'=>169,'name'=>'УГЗ г.Минск');
               $data['region'][]=array('id'=>171,'name'=>'УГЗ г.Гомель');
               $data['region'][]=array('id'=>170,'name'=>'УГЗ г.Борисов');


            $data['select'] = 0; //доступны все область


         /*----------------- END форма ------------------*/

        //bread
        $data['bread_active'] = 'Техника+Склад (Могилев, область)';
      $data['title_name']='Техника+Склад';
        $app->render('layouts/header.php',$data);
            $app->render('layouts/menu.php');
            $app->render('report/teh_in_trip/bread.php', $data);
            $app->render('report/detail_teh/region/form.php', $data);
            $app->render('layouts/footer.php');
        });

            //по области общее кол-во техники
       $app->post('/detail_teh/region', function () use ($app) {

        /*         * *********дата, на которую надо выбирать данные ******** */
        $d = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
        $date_d = new DateTime($d);
        $date_start = $date_d->Format('Y-m-d');

        $today = date("Y-m-d");
        $yesterday = date("Y-m-d", time() - (60 * 60 * 24));
        $day_before_yesterday = date("Y-m-d", time() - (60 * 60 * 24) - (60 * 60 * 24));


        $data['date_start'] = $date_start;

        /*         * ********* END дата, на которую надо выбирать данные ******** */

        $region = $app->request()->post('region'); //oblast


        /* ------------ КОНЕЦ  запрошенные параметры ---------- */


        $cp = array(8, 9, 12);

        $cp_locorg = array(160, 163, 169, 170, 171);


        // результат поиска УМЧС без ЦОУ, ШЛЧС!!!!

        /* ----------------------------- только родная техника - та, которая уехала в командировку - та, которая приехала из др.ПАСЧ ------------------------------------ */
        $sql = " SELECT   count(c.id_teh) as co, vie.name,vie.id as vie_id, "
                . "     `reg`.`name`        AS `region_name`, c.`id_type`, vie.`id_vid`, c.`id_to`, c.`is_repair`,"
                . "  `re`.`id_loc_org` AS `id_grochs`,"
                . "  `org`.`id`         AS `org_id`,sum(c.powder) as powder, sum(c.foam) as foam"
                . " FROM str.car AS c LEFT JOIN ss.technics AS t ON t.id=c.id_teh"
                . " LEFT JOIN ss.records AS re ON re.id=t.id_record "
                . " LEFT JOIN ss.locorg AS locor ON locor.id=re.id_loc_org"
                . " LEFT JOIN ss.locals AS loc ON loc.id=locor.id_local"
                . " left join ss.views as vie on vie.id=t.id_view"
                . "  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                . " LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                . "   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                . "   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "
                . " WHERE c.dateduty = ' " . $date_start . "'"
                . " AND  c.`id_teh` NOT IN "
                . " (SELECT  tr.`id_teh`  FROM  str.`tripcar` AS tr WHERE (( ' " . $date_start . " ' BETWEEN tr.date1 AND tr.date2) OR( ' " . $date_start . " '  >= tr.date1 AND tr.date2 IS NULL)) )"
                . " AND  c.`id_teh` NOT IN "
                . "  (SELECT  res.`id_teh`  FROM  str.reservecar AS res WHERE (( ' " . $date_start . " ' BETWEEN res.date1 AND res.date2) OR( ' " . $date_start . " '  >= res.date1 AND res.date2 IS NULL)) ) "
                . " and d.id not in (8,9)";



        if (in_array($region, $cp)) {//РОСН, УГЗ, Авиацмя
            $sql = $sql . ' AND locor.`id_organ` =  ' . $region;
        } elseif (in_array($region, $cp_locorg)) {//РОСН, УГЗ, Авиацмя по районам
            $sql = $sql . ' AND locor.`id` =  ' . $region;
        } else {
            $sql = $sql . ' AND reg.`id` =  ' . $region;
            $sql = $sql . ' AND locor.`id_organ` NOT IN(8,9,12) '; //кроме РОСН, УГЗ, Авиации
        }

        $sql = $sql . "  group by reg.id, vie.id, vie.id_vid,c.id_type,  c.id_to, c.is_repair  ORDER BY `vie`.`name`";
        $res = R::getAll($sql);

        $data['res'] = $res;



        //echo '<b>Общие сведения</b>';echo '<br>';
        //  print_r($res);
// echo '<br>';echo '<b>Mark</b>';echo '<br>';
//        print_r($res_mark);
        //echo '<br>';echo '<b>Mark array</b>';echo '<br>';
        //   print_r($res_mark_array);
// echo '<br>';echo '<b>Из др ПАСЧ</b>';echo '<br>';
        //exit();

        /* ----------------------------- КОНЕЦ только родная техника - та, которая уехала в командировку - та, которая приехала из др.ПАСЧ ------------------------------------ */



        /* ------------------------------------------------ Техника из др подразделения ------------------------------------------------------------- */


        $sql_teh_from_other_pasp = " SELECT   count(res.`id_teh` ) as co, vie.name, vie.id as vie_id,"
                . "     `reg`.`name`        AS `region_name`,  c.id_type,vie.id_vid, c.id_to, c.is_repair, "
                . "`reg`.`id`          AS `region_id`,  `re`.`id_loc_org` AS `id_grochs`,"
                . "  `org`.`id`         AS `org_id`,sum(c.powder) as powder, sum(c.foam) as foam"
                . "   FROM  str.reservecar AS res "
                . " left join str.car as c ON c.id_teh=res.id_teh  and c.dateduty= ' " . $date_start . " '"
                . " left join ss.technics as t on t.id=res.id_teh"
                . " left join ss.views as vie on vie.id=t.id_view  "
                . "left join ss.records as re ON re.id=res.id_card"
                . " left join ss.locorg as locor on locor.id=re.id_loc_org "
                . "left join ss.locals as loc on loc.id=locor.id_local"
                . "  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                . " LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                . "   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                . "   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "
                . "  WHERE (( ' " . $date_start . " ' BETWEEN res.date1 AND res.date2) OR( ' " . $date_start . " '  >= res.date1 AND res.date2 IS NULL)) "
                . " and re.id_divizion not in (8,9)";


        if (in_array($region, $cp)) {//РОСН, УГЗ, Авиацмя
            $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' AND locor.`id_organ` =  ' . $region;
        } elseif (in_array($region, $cp_locorg)) {//РОСН, УГЗ, Авиацмя по районам
            $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' AND locor.`id` =  ' . $region;
        } else {
            $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' AND reg.`id` =  ' . $region;
            $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . ' AND locor.`id_organ` NOT IN(8,9,12) '; //кроме РОСН, УГЗ, Авиации
        }


        $sql_teh_from_other_pasp = $sql_teh_from_other_pasp . " group by  reg.id, vie.id, vie.id_vid,c.id_type,  c.id_to, c.is_repair   ORDER BY vie.name ";


        $teh_from_other_card = R::getAll($sql_teh_from_other_pasp);




        $data['teh_from_other_card'] = $teh_from_other_card;




        //print_r($teh_from_other_card);
//        echo '<br>'; echo '<b>Mark из др ПАСЧ</b>';echo '<br>';
//        print_r($teh_mark_from_other_card);
        // echo '<br>'; echo '<b>Mark array из др ПАСЧ</b>';echo '<br>';
        //  print_r($teh_mark_from_other_card_array);
        // exit();

        /* ----------------- КОНЕЦ Техника из др подразделения ----------------------- */



        /* -----------------------------  уехала в командировку ------------------------------------ */


        $sql_trip = " SELECT   count(c.id_teh) as co, vie.name, vie.id as vie_id,"
                . "     `reg`.`name`        AS `region_name`,  c.id_type,vie.id_vid, c.id_to, c.is_repair, "
                . "`reg`.`id`          AS `region_id`,  `re`.`id_loc_org` AS `id_grochs`,"
                . "  `org`.`id`         AS `org_id`"
                . " FROM str.car AS c LEFT JOIN ss.technics AS t ON t.id=c.id_teh"
                . " LEFT JOIN ss.records AS re ON re.id=t.id_record "
                . " LEFT JOIN ss.locorg AS locor ON locor.id=re.id_loc_org"
                . " LEFT JOIN ss.locals AS loc ON loc.id=locor.id_local"
                . " left join ss.views as vie on vie.id=t.id_view"
                . "  LEFT JOIN `ss`.`divizions` `d`  on  `d`.`id` = `re`.`id_divizion` "
                . " LEFT JOIN `ss`.`organs` `org`    ON `locor`.`id_organ` = `org`.`id`"
                . "   LEFT JOIN `ss`.`organs` `orgg`     ON `locor`.`oforg` = `orgg`.`id` "
                . "   LEFT JOIN `ss`.`regions` `reg`    ON `reg`.`id` = `loc`.`id_region` "
                . " WHERE c.dateduty = ' " . $date_start . "'"
                . " AND  c.`id_teh`  IN "
                . " (SELECT  tr.`id_teh`  FROM  str.`tripcar` AS tr WHERE (( ' " . $date_start . " ' BETWEEN tr.date1 AND tr.date2) OR( ' " . $date_start . " '  >= tr.date1 AND tr.date2 IS NULL)) )"
                . " and d.id not in (8,9)";



        if (in_array($region, $cp)) {//РОСН, УГЗ, Авиацмя
            $sql_trip = $sql_trip . ' AND locor.`id_organ` =  ' . $region;
        } elseif (in_array($region, $cp_locorg)) {//РОСН, УГЗ, Авиацмя по районам
            $sql_trip = $sql_trip . ' AND locor.`id` =  ' . $region;
        } else {
            $sql_trip = $sql_trip . ' AND reg.`id` =  ' . $region;
            $sql_trip = $sql_trip . ' AND locor.`id_organ` NOT IN(8,9,12) '; //кроме РОСН, УГЗ, Авиации
        }



        $sql_trip = $sql_trip . "  group by reg.id, vie.id, vie.id_vid,c.id_type,  c.id_to, c.is_repair   ORDER BY vie.name";
        $res_trip = R::getAll($sql_trip);

        $data['res_trip'] = $res_trip;


        $data['res_trip'] = $res_trip;

//echo '<br>'; echo '<b>Общие сведения trip</b>';echo '<br>';
        //print_r($res_trip);
// echo '<br>';echo '<b>Mark</b>';echo '<br>';
//        print_r($res_mark);
        //echo '<br>';echo '<b>Mark array trip</b>';echo '<br>';
        //  print_r($res_mark_array_trip);
// echo '<br>';echo '<b>Из др ПАСЧ</b>';echo '<br>';
        //  exit();

        /* ----------------------------- КОНЕЦ  уехала в командировку  ------------------------------------ */


        //storage
        $sql_storage = "select sum(st.asv) as asv,"
                . " SUM(  cast(REPLACE(st.powder, ',', '.') as DECIMAL(10,3)) ) as powder,"
                . " SUM(  cast(REPLACE(st.foam, ',', '.') as DECIMAL(10,3)) ) as foam"
                . " from storage as st left join cardch as cc on st.id_cardch=cc.id "
                . " left join ss.records as r on r.id=cc.id_card left join ss.locorg as locor on locor.id=r.id_loc_org"
                . " left join ss.locals as loc on loc.id=locor.id_local left join ss.regions as reg on reg.id=loc.id_region"
                . " WHERE st.dateduty = '" . $date_start . "'";

        if (in_array($region, $cp)) {//РОСН, УГЗ, Авиацмя
            $sql_storage = $sql_storage . ' AND locor.`id_organ` =  ' . $region;
        } elseif (in_array($region, $cp_locorg)) {//РОСН, УГЗ, Авиацмя по районам
            $sql_storage = $sql_storage . ' AND locor.`id` =  ' . $region;
        } else {
            $sql_storage = $sql_storage . ' AND reg.`id` =  ' . $region;
            $sql_storage = $sql_storage . ' AND locor.`id_organ` NOT IN(8,9,12) '; //кроме РОСН, УГЗ, Авиации
        }



        $sql_storage = $sql_storage . "  group by reg.id";


        $res_storage = R::getAll($sql_storage);


        $data['storage'] = $res_storage;

        if (!empty($res) || !empty($teh_from_other_card)) {

//export to excel
            exportToExcelDetailTehRegion($res, $teh_from_other_card, $region, $data);
        } else {
            $app->render('msg/emtyResult.php', $data); //no result
        }
    });

    //экспорт в excel данных по области
      function exportToExcelDetailTehRegion($res, $teh_from_other_card, $region, $data) {


        //  print_r($data['storage']);
        // exit();
        $objPHPExcel = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader("Excel2007");
        $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/detail_teh/detail_teh_by_region.xlsx');
//activate worksheet number 1
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
        $r = 5;
        $i = 0;

        /* +++++++++++++++++++++ style ++++++++++ */

        /* ИТОГО */
        $style_all = array(
// Заполнение цветом
            'fill' => array(
                'type' => PHPExcel_STYLE_FILL::FILL_SOLID,
                'color' => array(
                    'rgb' => 'DFE53E'
                )
            ),
            // Выравнивание
            'alignment' => array(
                'horizontal' => PHPExcel_STYLE_ALIGNMENT::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_STYLE_ALIGNMENT::VERTICAL_CENTER,
            ),
            // Шрифт
            'font' => array(
                'bold' => true
            )
        );

        /* +++++++++++++ end style +++++++++++++ */



        $date = new DateTime($data['date_start']);
        $date_start = $date->Format('d-m-Y');

        $sheet->setCellValue('A' . 1, 'Результат запроса за ' . $date_start);

        $name_teh = array();

        $all_teh_arr['powder'][1] = 0;
        $all_teh_arr['powder'][2] = 0;

        $all_teh_arr['foam'][1] = 0;
        $all_teh_arr['foam'][2] = 0;
        // print_r($res);
        // echo '<br><br>';
        /* -------------------------- родная техника ------------------------------- */
        if (isset($res) && !empty($res)) {
            foreach ($res as $value) {

                $name_teh[$value['vie_id']] = $value['name']; //АБР


                      $cp = array(8, 9, 12);

        $cp_locorg = array(160, 163, 169, 170, 171);

           if (in_array($region, $cp)) {//РОСН, УГЗ, Авиацмя
               if($region == 8)
           $region_name ='РОСН';
               elseif($region == 9)
           $region_name ='УГЗ';
                              if($region == 12)
           $region_name ='Авиация';

        } elseif (in_array($region, $cp_locorg)) {//РОСН, УГЗ, Авиацмя по районам

                         if($region == 160)
           $region_name ='РОСН г.Минск';
               elseif($region == 163)
           $region_name ='РОСН г.Пинск';
                              elseif($region == 169)
           $region_name ='УГЗ г.Минск';
                               elseif($region == 170)
           $region_name ='УГЗ г.Борисов';
                                elseif($region == 171)
           $region_name ='УГЗ г.Гомель';


        } else {
            $region_name = $value['region_name'];
        }



                //$all_teh_arr[$value['vie_id']][] = $value['co'];//кол-во АБР в б/р осн
                $all_teh_arr['all'][$value['vie_id']][$value['id_type']][$value['id_vid']] = $value['co']; //кол-во АБР в б/р осн
                $all_teh_arr['to'][$value['vie_id']][$value['id_to']] = $value['co']; //TO
                $all_teh_arr['repair'][$value['vie_id']][$value['is_repair']] = $value['co']; //repair
                //порошок, пенообразователь
               if($value['id_type'] != 3){//только резерв либо боевая
					 $all_teh_arr['powder'][$value['id_type']]+=$value['powder'];
                $all_teh_arr['foam'][$value['id_type']]+=$value['foam'];
				}
            }
        }


        /* ----------------- техника из др ПАСЧ ----------------------------------------- */
        if (isset($teh_from_other_card) && !empty($teh_from_other_card)) {
            foreach ($teh_from_other_card as $value) {

                $all_teh_arr['all'][$value['vie_id']][$value['id_type']][$value['id_vid']] += $value['co']; //кол-во АБР в б/р осн
                $all_teh_arr['to'][$value['vie_id']][$value['id_to']] += $value['co']; //TO
                $all_teh_arr['repair'][$value['vie_id']][$value['is_repair']] += $value['co']; //repair
                //порошок, пенообразователь
              				if($value['id_type'] != 3){//только резерв либо боевая
					 $all_teh_arr['powder'][$value['id_type']]+=$value['powder'];
                $all_teh_arr['foam'][$value['id_type']]+=$value['foam'];
				}
            }
        }



        /* --------в командировке ------- */
        if (isset($data['res_trip']) && !empty($data['res_trip'])) {
            foreach ($data['res_trip'] as $value) {

                $all_teh_arr['trip'][$value['vie_id']] = $value['co'];
            }
        }


        // print_r($all_teh_arr['trip']);
        // echo $all_teh_arr['all'][2][1][1];
        // print_r($name_teh);
        // echo '<br><br>';
        // print_r($all_teh_arr);
        // exit();

        $sheet->setCellValue('A' . $r, $region_name);


        $massive = array();
        $itogo = array('br_osn' => 0, 'br_spec' => 0, 'br_ing' => 0, 'br_vsp' => 0, 'res_osn' => 0, 'res_spec' => 0, 'res_ing' => 0, 'res_vsp' => 0, 'to1' => 0, 'to2' => 0, 'repair' => 0, 'trip' => 0);

        foreach ($name_teh as $key => $name) {

            /* ------ б/р ------ */
            if (isset($all_teh_arr['all'][$key][1][1]) && $all_teh_arr['all'][$key][1][1] != 0) {//б/р основная 1-1
                $massive['br_osn'][] = $name . ' - ' . $all_teh_arr['all'][$key][1][1];

                $itogo['br_osn']+=$all_teh_arr['all'][$key][1][1];
            }

            if (isset($all_teh_arr['all'][$key][1][2]) && $all_teh_arr['all'][$key][1][2] != 0) {//б/р спец 1-2
                $massive['br_spec'][] = $name . ' - ' . $all_teh_arr['all'][$key][1][2];

                $itogo['br_spec']+=$all_teh_arr['all'][$key][1][2];
            }

            if (isset($all_teh_arr['all'][$key][1][4]) && $all_teh_arr['all'][$key][1][4] != 0) {//б/р инж 1-4
                $massive['br_ing'][] = $name . ' - ' . $all_teh_arr['all'][$key][1][4];

                $itogo['br_ing']+=$all_teh_arr['all'][$key][1][4];
            }

            if (isset($all_teh_arr['all'][$key][1][3]) && $all_teh_arr['all'][$key][1][3] != 0) {//б/р вспомог 1-3
                $massive['br_vsp'][] = $name . ' - ' . $all_teh_arr['all'][$key][1][3];

                $itogo['br_vsp']+=$all_teh_arr['all'][$key][1][3];
            }


            /* ------ резерв ------ */
            if (isset($all_teh_arr['all'][$key][2][1]) && $all_teh_arr['all'][$key][2][1] != 0) {//res основная 2-1
                $massive['res_osn'][] = $name . ' - ' . $all_teh_arr['all'][$key][2][1];

                $itogo['res_osn']+=$all_teh_arr['all'][$key][2][1];
            }

            if (isset($all_teh_arr['all'][$key][2][2]) && $all_teh_arr['all'][$key][2][2] != 0) {//res спец 2-2
                $massive['res_spec'][] = $name . ' - ' . $all_teh_arr['all'][$key][2][2];

                $itogo['res_spec']+=$all_teh_arr['all'][$key][2][2];
            }

            if (isset($all_teh_arr['all'][$key][2][4]) && $all_teh_arr['all'][$key][2][4] != 0) {//res инж 2-4
                $massive['res_ing'][] = $name . ' - ' . $all_teh_arr['all'][$key][2][4];

                $itogo['res_ing']+=$all_teh_arr['all'][$key][2][4];
            }

            if (isset($all_teh_arr['all'][$key][2][3]) && $all_teh_arr['all'][$key][2][3] != 0) {//res вспомог 2-3
                $massive['res_vsp'][] = $name . ' - ' . $all_teh_arr['all'][$key][2][3];

                $itogo['res_vsp']+=$all_teh_arr['all'][$key][2][3];
            }


            /* ------ TO ------- */

            if (isset($all_teh_arr['to'][$key][1]) && $all_teh_arr['to'][$key][1] != 0) {//TO-1
                $massive['to1'][] = $name . ' - ' . $all_teh_arr['to'][$key][1];

                $itogo['to1']+=$all_teh_arr['to'][$key][1];
            }

            if (isset($all_teh_arr['to'][$key][2]) && $all_teh_arr['to'][$key][2] != 0) {//TO-2
                $massive['to2'][] = $name . ' - ' . $all_teh_arr['to'][$key][2];

                $itogo['to2']+=$all_teh_arr['to'][$key][2];
            }


            /* ------ ремонт ------- */

            if (isset($all_teh_arr['repair'][$key][1]) && $all_teh_arr['repair'][$key][1] != 0) {//repair
                $massive['repair'][] = $name . ' - ' . $all_teh_arr['repair'][$key][1];

                $itogo['repair']+=$all_teh_arr['repair'][$key][1];
            }

            /* ------ командировка ------- */

            if (isset($all_teh_arr['trip'][$key]) && $all_teh_arr['trip'][$key] != 0) {//trip
                $massive['trip'][] = $name . ' - ' . $all_teh_arr['trip'][$key];

                $itogo['trip']+=$all_teh_arr['trip'][$key];
            }
        }


        /* ---------------- вывод ---------------- */

        /* ------ б/р ------ */
        if (isset($massive['br_osn']) && !empty($massive['br_osn'])) {//б/р основная 1-1
            $string = implode(';' . chr(10), $massive['br_osn']);
            $sheet->setCellValue('B' . $r, $string);
        }


        if (isset($massive['br_spec']) && !empty($massive['br_spec'])) {//б/р spec 1-2
            $string = implode(';' . chr(10), $massive['br_spec']);
            $sheet->setCellValue('C' . $r, $string);
        }

        if (isset($massive['br_ing']) && !empty($massive['br_ing'])) {//б/р ing 1-2
            $string = implode(';' . chr(10), $massive['br_ing']);
            $sheet->setCellValue('D' . $r, $string);
        }

        if (isset($massive['br_vsp']) && !empty($massive['br_vsp'])) {//б/р вспомог 1-2
            $string = implode(';' . chr(10), $massive['br_vsp']);
            $sheet->setCellValue('E' . $r, $string);
        }


        /* ------ резерв ------ */
        if (isset($massive['res_osn']) && !empty($massive['res_osn'])) {//res основная 2-1
            $string = implode(';' . chr(10), $massive['res_osn']);
            $sheet->setCellValue('I' . $r, $string);
        }


        if (isset($massive['res_spec']) && !empty($massive['res_spec'])) {//res spec 2-2
            $string = implode(';' . chr(10), $massive['res_spec']);
            $sheet->setCellValue('J' . $r, $string);
        }

        if (isset($massive['res_ing']) && !empty($massive['res_ing'])) {//res ing 2-2
            $string = implode(';' . chr(10), $massive['res_ing']);
            $sheet->setCellValue('K' . $r, $string);
        }

        if (isset($massive['res_vsp']) && !empty($massive['res_vsp'])) {//res вспомог 1-2
            $string = implode(';' . chr(10), $massive['res_vsp']);
            $sheet->setCellValue('L' . $r, $string);
        }

        /* ------ TO ------- */
        if (isset($massive['to1']) && !empty($massive['to1'])) {//TO-1
            $string = implode(';' . chr(10), $massive['to1']);
            $sheet->setCellValue('S' . $r, $string);
        }
        if (isset($massive['to2']) && !empty($massive['to2'])) {//TO-2
            $string = implode(';' . chr(10), $massive['to2']);
            $sheet->setCellValue('T' . $r, $string);
        }

        /* ------ ремонт ------- */
        if (isset($massive['repair']) && !empty($massive['repair'])) {//repair
            $string = implode(';' . chr(10), $massive['repair']);
            $sheet->setCellValue('U' . $r, $string);
        }

        /* ------ командировка ------- */
        if (isset($massive['trip']) && !empty($massive['trip'])) {//trip
            $string = implode(';' . chr(10), $massive['trip']);
            $sheet->setCellValue('V' . $r, $string);
        }





        $r++;
        /* ---------- itogo ------------ */
        $sheet->setCellValue('A' . $r, 'ИТОГО');
        $sheet->setCellValue('B' . $r, $itogo['br_osn']);
        $sheet->setCellValue('C' . $r, $itogo['br_spec']);
        $sheet->setCellValue('D' . $r, $itogo['br_ing']);
        $sheet->setCellValue('E' . $r, $itogo['br_vsp']);

        $sheet->setCellValue('I' . $r, $itogo['res_osn']);
        $sheet->setCellValue('J' . $r, $itogo['res_spec']);
        $sheet->setCellValue('K' . $r, $itogo['res_ing']);
        $sheet->setCellValue('L' . $r, $itogo['res_vsp']);

        $sheet->setCellValue('S' . $r, $itogo['to1']);
        $sheet->setCellValue('T' . $r, $itogo['to2']);
        $sheet->setCellValue('U' . $r, $itogo['repair']);
        $sheet->setCellValue('V' . $r, $itogo['trip']);

        $sheet->getStyleByColumnAndRow(0, $r, 21, $r)->applyFromArray($style_all); //ИТОГО

        /* порошок, пенообразователь */
        $sheet->setCellValue('G' . ($r - 1), ($all_teh_arr['powder'][1] != 0) ? $all_teh_arr['powder'][1] : '' );
        $sheet->setCellValue('H' . ($r - 1), ($all_teh_arr['foam'][1] != 0) ? $all_teh_arr['foam'][1] : '' );
        $sheet->setCellValue('N' . ($r - 1), ($all_teh_arr['powder'][2] != 0) ? $all_teh_arr['powder'][2] : '' );
        $sheet->setCellValue('O' . ($r - 1), ($all_teh_arr['foam'][2] != 0) ? $all_teh_arr['foam'][2] : '' );

        //itogo порошок, пенообразователь
        $sheet->setCellValue('G' . $r, ($all_teh_arr['powder'][1] != 0) ? $all_teh_arr['powder'][1] : '' );
        $sheet->setCellValue('H' . $r, ($all_teh_arr['foam'][1] != 0) ? $all_teh_arr['foam'][1] : '' );
        $sheet->setCellValue('N' . $r, ($all_teh_arr['powder'][2] != 0) ? $all_teh_arr['powder'][2] : '' );
        $sheet->setCellValue('O' . $r, ($all_teh_arr['foam'][2] != 0) ? $all_teh_arr['foam'][2] : '' );

        //склад
        foreach ($data['storage'] as $row) {

            $sheet->setCellValue('P' . ($r - 1), $row['asv']);
            $sheet->setCellValue('Q' . ($r - 1), $row['powder']);
            $sheet->setCellValue('R' . ($r - 1), $row['foam']);

            //itogo
            $sheet->setCellValue('P' . $r, $row['asv']);
            $sheet->setCellValue('Q' . $r, $row['powder']);
            $sheet->setCellValue('R' . $r, $row['foam']);
        }

        /* ---------- END itogo ------------ */



        /* Сохранить в файл */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="detail_teh_by_region.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    /*     * *************************  END Подробный отчет ТЕХНИКА, АСВ, П,ПО (от МОГИЛЕВ)   ************************************ */

/* count positions */
    $app->get('/count_position', function () use ($app) {


            $data['region'] = R::getAll('SELECT * FROM ss.regions'); //list of regions
        $data['region'][] = array('id' => 8, 'name' => 'РОСН');
        $data['region'][] = array('id' => 9, 'name' => 'УГЗ');
        $data['region'][] = array('id' => 12, 'name' => 'Авиация');

//        $data['region'][] = array('id' => 160, 'name' => 'РОСН г.Минск');
//        $data['region'][] = array('id' => 163, 'name' => 'РОСН г.Пинск');

//        $data['region'][] = array('id' => 169, 'name' => 'УГЗ г.Минск');
//        $data['region'][] = array('id' => 171, 'name' => 'УГЗ г.Гомель');
//        $data['region'][] = array('id' => 170, 'name' => 'УГЗ г.Борисов');


         $data['locorg'] = R::getAll('SELECT * FROM ss.caption '); // вместе с  ЦП
        $data['diviz'] = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения


        $data['position']=R::getAll("select * from position");

        //bread
        $data['bread_active'] = 'Отчет по должностям';
        $data['title_name'] = 'Отчет по должностям';
        $app->render('layouts/header.php', $data);
        $app->render('layouts/menu.php');
        $app->render('report/teh_in_trip/bread.php', $data);
        $app->render('report/count_position/form.php', $data);
        $app->render('layouts/footer.php');
    });


  $app->post('/count_position', function () use ($app) {

        $region = $app->request()->post('region'); //oblast
        $grochs = $app->request()->post('locorg'); //grochs
        $divizion = $app->request()->post('diviz'); //pasp

        $pos_search = (isset($_POST['position_search']) && !empty($_POST['position_search'])) ? $_POST['position_search'] : 0;
        //print_r($pos_search);

        $cp = array(8, 9, 12);
        $where = 0;
//        echo $region.'<br>';
//         echo $grochs.'<br>';
//          echo $divizion.'<br>';
//exit();
        $head = array();
        $head_pos= array();

        /* request inf */
        if (isset($divizion) && !empty($divizion)) {
            $head_diviz = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org WHERE rec.id = ' . $divizion . ' order by rec.divizion_num ASC');

            foreach ($head_diviz as $v) {
                $head[] = $v['name'];
            }
        }
        if (isset($grochs) && !empty($grochs)) {//by grochs
            $head_locorg = R::getAll('SELECT * FROM ss.caption WHERE locorg_id = ' . $grochs);
            foreach ($head_locorg as $v) {
                $head[] = $v['locor'];
            }
        }

        if (isset($region) && !empty($region)) {

            if (in_array($region, $cp)) {//rosn,ugz,avia

                switch ($region) {
                    case 8:
                        $head[] = 'РОСН';

                        break;
                    case 9:
                        $head[] = 'УГЗ';
                        break;

                    default:
                        $head[] = 'Авиация';
                        break;
                }
            } else {// region

                $head_region = R::getAll('SELECT name,id FROM ss.regions WHERE id = ' . $region);

                foreach ($head_region as $v) {
                    if($v['id'] != 3){
                    $head[] = $v['name'].' обл.';
                    }
                    else{
                        $head[] = $v['name'];
                    }
                }
            }
        }

        if ($pos_search != 0) {
            $head_position = R::getAll('SELECT name FROM position WHERE id IN ( ' . implode(',', $pos_search).')');
            foreach ($head_position as $v) {
                $head_pos[] = $v['name'];
            }
        }
        /* END request inf */


        $sql = "SELECT COUNT(l.`id`) AS cnt, po.`name` AS name_pos , po.`id` "
            . " FROM POSITION AS po LEFT JOIN listfio AS l ON po.`id`=l.`id_position` "
            . " LEFT JOIN cardch AS c ON l.`id_cardch`=c.`id` "
            . " LEFT JOIN  ss.`records` AS rec  ON rec.`id`=c.`id_card`"
            . " LEFT JOIN ss.`locorg` AS locor ON locor.`id`=rec.`id_loc_org`"
            . " LEFT JOIN ss.`locals` AS loc ON loc.`id`=locor.`id_local` ";

        if (isset($region) && !empty($region)) {
            $where = 1;
            $sql = $sql . ' WHERE ';
            if (isset($divizion) && !empty($divizion)) {//by pasp
                $sql = $sql . ' rec.`id` =  ' . $divizion;

            } elseif (isset($grochs) && !empty($grochs)) {//by grochs
                $sql = $sql . '  locor.`id` =  ' . $grochs;

            } else {//by oblast
                if (in_array($region, $cp)) {//rosn,ugz,avia
                    $sql = $sql . ' locor.`id_organ` =  ' . $region;

                } else {// region
                    $sql = $sql . ' loc.`id_region` =  ' . $region;
                    $sql = $sql . ' AND locor.`id_organ` NOT IN(8,9,12) '; //without rosn,ugz,avia

                }
            }
        } else {
            $head[] = 'по республике';
        }

        if ($pos_search != 0) {
            if ($where == 0) {
                $where = 1;
                $sql = $sql . ' WHERE ';
            } else {
                $sql = $sql . ' AND ';
            }

            $sql = $sql . ' po.id  IN(' . implode(',', $pos_search) . ')';
        }

        $sql = $sql . "  group by po.`id`  ORDER BY `po`.`name`";
//echo $sql;
//exit();
        $res = R::getAll($sql);
        $data['res'] = $res;

        $data['head']=$head;
        $data['head_pos']=$head_pos;

        /* show on screen */
        if (!isset($_POST['export_to_excel'])) {
            /* form */
            $data['region'] = R::getAll('SELECT * FROM ss.regions'); //list of regions
            $data['region'][] = array('id' => 8, 'name' => 'РОСН');
            $data['region'][] = array('id' => 9, 'name' => 'УГЗ');
            $data['region'][] = array('id' => 12, 'name' => 'Авиация');

            $data['locorg'] = R::getAll('SELECT * FROM ss.caption '); // вместе с  ЦП
            $data['diviz'] = R::getAll('SELECT case when (locor.id_organ=6) then d.name when (rec.divizion_num=0) then d.name else
            concat(d.name," №" ,rec.divizion_num)  end as name, id_loc_org as idlocorg,
            rec.id as recid FROM ss.records AS rec inner join ss.divizions AS d
            on rec.id_divizion=d.id inner join ss.locorg as locor on locor.id=rec.id_loc_org order by rec.divizion_num ASC'); //все подразделения
            $data['select'] = 0; //available all regions
            $data['select'] = 0; //доступны все область
            $data['select_grochs'] = 0; //доступны все ГРОЧС
            $data['select_pasp'] = 0; //доступны все части

            $data['position'] = R::getAll("select * from position");

            //bread
            $data['bread_active'] = 'Отчет по должностям';
            $data['title_name'] = 'Отчет по должностям';
            $app->render('layouts/header.php', $data);
            $app->render('layouts/menu.php');
            $app->render('report/teh_in_trip/bread.php', $data);
            $app->render('report/count_position/form.php', $data);
            $app->render('report/count_position/result.php', $data);
            $app->render('layouts/footer.php');
        }
        /* show on screen */ else {
            /* export_to_excel */
            exportToExcelCountPosition($res,$head,$head_pos);
        }
    });
});



/* ----------------------------------------------------------------------------------------------------------------------  v2.0 ----------------------------------------------------------------------------------------------------------------------- */



$app->group('/v2/card', $is_auth, function () use ($app, $log) {

//main COU
    $app->get('/:id/ch/:change/main', function ($id, $change) use ($app) {//sheet other reasons for  change
        array($app, 'is_auth');

     $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 5; //main
        $today = date("Y-m-d");
        $duty = is_duty_cou($id, $change, 0); //change is duty or not
        $data['duty'] = $duty;
        $data['is_open_update'] = is_duty_cou($id, $change, 1); //открыт ли доступ на ред
        $data['duty_ch'] = duty_ch();

        /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/


         /* id_divizion, id_organ, cou_with_slhs */
         $param=R::getAll('SELECT rec.id_divizion, l.id_organ, rec.cou_with_slhs FROM ss.records as rec LEFT JOIN ss.locorg as l ON l.id=rec.id_loc_org WHERE rec.id = ?',array($id));
         foreach ($param as $value) {
             $data['id_diviz']=$value['id_divizion'];
             $data['id_organ']=$value['id_organ'];
             $data['cou_with_slhs']=$value['cou_with_slhs'];
         }
         /* END id_divizion, id_organ, cou_with_slhs */


        /*         * **************************  Списки ФИО ************************************ */
        //список Заступают из др подразделения
        $data['present_reserve_fio'] = getPresentReserveFio($id, $change);

        //список Заступали из др подразделения
        $data['past_reserve_fio'] = getFioById(getListFioReserve($id, $change, 1, 0), $id, $change);

        // список Заступают ежедневники, учитываем "нет работников"
        $data['present_everyday_fio'] = getPresentEverydayFio($id, $change);

        // список Заступали ежедневники
        $data['past_everyday_fio'] = getFioById(getListFioEveryday($id, $change, 1, 0, 0), $id, $change);

        // список ФИО  смены, учитываем "нет работников"
        $data['present_head_fio'] = getPresentHeadFio($id, $change);


        /*-------------------- ЗАСТУПАЛИ прошлый раз  ---------------------------*/

        // Заступал ФИО начальника смены
        $data['past_head_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 1), $id, $change);

        // Заступал оперативным дежурным ЦОУ
        $data['past_od_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 2), $id, $change);

        // Заступал инженером ТКС
        $data['past_eng_tks_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 7), $id, $change);

        // Заступал инженером связи
        $data['past_eng_connect_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 8), $id, $change);

        // Заступал мастером связи
        $data['past_master_connect_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 9), $id, $change);

        // Заступал стажером
        $data['past_trainee_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 11), $id, $change);

        // last inspector onip
        $data['past_inspector_inip_fio'] = getListFioTextByPosMainCou($id, $change, 1, 0, 13);


        // last on garnison
        $data['past_garnison_fio'] =getListFioTextByPosMainCou($id, $change, 1, 0, 14);


        /* ----- связь многие ко многим ---- */

        // Заступал зам ОД
        $data['past_z_od_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 3), $id, $change);

        // Заступал старшим  помощником ОД
        $data['past_st_pom_od_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 4), $id, $change);

        // Заступал помощником ОД
        $data['past_pom_od_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 5), $id, $change);

        // Заступал диспетчера
        $data['past_disp_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 6), $id, $change);

        // Заступал водители
        $data['past_driver_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 10), $id, $change);

        // Заступал другие
        $data['past_others_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 12), $id, $change);

        /* -------------------- END ЗАСТУПАЛИ прошлый раз  --------------------------- */


  /* ------------------- vacant from list of change  ---------------------- */
            $data['count_vacant_from_list'] = getCountVacantOnList($id, $change);

            /* all employees COU with vacant with everyday */
         $data['count_shtat'] =  getCountOnListAllForCou($id, $change);


        //кнопка "Подтвердить данные"
        $data['is_btn_confirm'] = is_btn_confirm($change);



        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);
        $app->render('card/sheet/start_cou.php', $data);
        $app->render('card/sheet/main/main.php', $data);

        /*         * *********************** сообщение о выполнении операции ********************************* */
        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение

        /*         * ***************************************** */
        //get main
        $main = R::getAll('select * from maincou where id_card = ? and ch = ?',array($id,$change));
        if (isset($main) && !empty($main)) {//есть запись
            ///выбор последней даты
            foreach ($main as $value) {
                //format 10-01-2018 to 2018-01-10
                /*  $date = new DateTime($value['dateduty']);
                  $last_data = $date->Format('Y-m-d'); */
                $last_data = $value['dateduty'];
            }

            /* ------------ кол-во больных, отпусков, командировок,др.причины ------------ */
            $data['count_ill'] = getCountIll($id, $change, $last_data);
            $data['count_holiday'] = getCountHoliday($id, $change, $last_data);
            $data['count_trip'] = getCountTrip($id, $change, $last_data);
            $data['count_other'] = getCountOther($id, $change, $last_data);

            $data['main'] = $main;

            $data['post'] = 0; //put data main...при хранении инф за месяц =1: не обновляем, а добавляем запись
        } else {
            //empty form
            //выводим пустую формы
            //insert row
            //кол-во больных, отпусков, командировок,др.причины
            $data['count_ill'] = 0;
            $data['count_holiday'] = 0;
            $data['count_trip'] = 0;
            $data['count_other'] = 0;
            			 $last_data = date('Y-m-d');

        }
        		 /* -------- по списку смены из списка смены без ежедневников --------- */
            //$data ['on_list'] = getCountOnList($id, $change);
                /* ------------------- вакантов из списка смен ---------------------- */
            //$data['count_vacant_from_list'] = getCountVacantOnList($id, $change);
			 /* ------------------- сколько человек в б.р.(на технике) ---------------------- */
           // $data['count_fio_on_car'] = getCountCalc($id, $change, $last_data);



        /* form for cou umchs */
        if($data['id_organ']==4){
            $app->render('card/sheet/main/cou/formMain.php', $data); //view data
        }
        /* form for cou with slhs */
        elseif($data['cou_with_slhs']==1){
             $app->render('card/sheet/main/cou/formMainCouSlhs.php', $data); //view data
        }
         /* form for cou grochs */
        elseif($data['cou_with_slhs']==0){
             $app->render('card/sheet/main/cou/formMainCouGrochs.php', $data); //view data
        }


        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');


    });

    //main COU save
    $app->post('/:id/ch/:change/main', function ($id, $change) use ($app, $log) {// form main for fill
        array($app, 'is_auth'); //авторизован ли пользователь
        //  $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 5; //main
//insert
        $dateduty = $app->request()->post('dateduty');

        if (empty($dateduty))
            $dateduty = NULL;
        else
            $dateduty = date("Y-m-d", strtotime($dateduty));

        $reserve = $app->request()->post('reserve');
        if (empty($reserve))
            $reserve = array();

        $everydayfio = $app->request()->post('everydayfio');
        if (empty($everydayfio))
            $everydayfio = array();


        /* ----- ФИО начальника смены - 1  --- */
        $head_ch = $app->request()->post('head_ch');
        // insert or update
        if(isset($head_ch))
        saveMainCouOne($id, $change, 1, $dateduty, $head_ch);


        /* ---- ОД ЦОУ - 2 ----- */
        $od = $app->request()->post('od');
        // insert or update or delete
         if(isset($od))
        saveMainCouOne($id, $change, 2, $dateduty, $od);


        // Зам ОД ЦОУ - 3
        $z_od = $app->request()->post('z_od');
         if(!isset($z_od)){
             $z_od=array();
         }
        saveMainCouTwo($id, $change, 3, $dateduty, $z_od); //check and insert or update


        //  Старший помощник ОД ЦОУ - 4
        $st_pom_od = $app->request()->post('st_pom_od');
         if(!isset($st_pom_od)){
             $st_pom_od=array();
         }
 saveMainCouTwo($id, $change, 4, $dateduty, $st_pom_od); //check and insert or update


        //  помощник ОД ЦОУ - 5
        $pom_od = $app->request()->post('pom_od');
         if(!isset($pom_od)){
           $pom_od=array();
         }

  saveMainCouTwo($id, $change, 5, $dateduty, $pom_od); //check and insert or update


        //  Диспетчера - 6
        $disp = $app->request()->post('disp');
        if (!isset($disp)) {
            $disp = array();
        }
        saveMainCouTwo($id, $change, 6, $dateduty, $disp); //check and insert or update


        /* ----  Инженер ТКС - 7 --- */
        $eng_tks = $app->request()->post('eng_tks');
         if(isset($eng_tks))
        saveMainCouOne($id, $change, 7, $dateduty, $eng_tks); // insert or update


        /* -------  Инженер связи - 8 ----- */
        $eng_connect = $app->request()->post('eng_connect');
         if(isset($eng_connect))
        saveMainCouOne($id, $change, 8, $dateduty, $eng_connect); // insert or update


        //  Мастер связи - 9
        $master_connect = $app->request()->post('master_connect');
         if(isset($master_connect))
        saveMainCouOne($id, $change, 9, $dateduty, $master_connect); // insert or update


        //  Водители - 10
        $driver = $app->request()->post('driver');
         if(!isset($driver)){
             $driver=array();
         }
 saveMainCouTwo($id, $change, 10, $dateduty, $driver); //check and insert or update


        /* --------  стажер - 11 -------- */
        $trainee = $app->request()->post('trainee');
         if(isset($trainee))
        saveMainCouOne($id, $change, 11, $dateduty, $trainee); // insert or update


        //  Другие - 12
        $others = $app->request()->post('others');
         if(!isset($others)){
$others=array();
         }
 saveMainCouTwo($id, $change, 12, $dateduty, $others); //check and insert or update


        /* ------ inspector onip - 13 ---- */
        $inspector_inip = $app->request()->post('inspector_inip');
         if(isset($inspector_inip))
        saveMainCouOneText($id, $change, 13, $dateduty, $inspector_inip); // insert or update


        /* -----------  on garnison - 14 ----------- */
        $garnison = $app->request()->post('garnison');
         if(isset($garnison))
        saveMainCouOneText($id, $change, 14, $dateduty, $garnison); // insert or update



        //$app->render('layouts/header.php');

        // echo $head_ch;
        //print_r($reserve);

        //   echo $od;
        //  print_r($everydayfio);


          /* ------------------------------------ КОНЕЦ все ФИО, выбранные на форме в массив ------------------------------------------- */

            $id_head_fio_old = array($od, $head_ch, $eng_tks, $eng_connect, $trainee, $master_connect, $inspector_inip, $garnison);
            $id_head_fio_new=array();
            foreach ($id_head_fio_old as $element) {
                if (!empty($element))
                    $id_head_fio_new[] = $element;
            }

            if (is_array($z_od) && !empty($z_od)) {
                $id_head_fio_new = array_merge($id_head_fio_new, $z_od);
            }

            if (is_array($st_pom_od) && !empty($st_pom_od)) {
                $id_head_fio_new = array_merge($id_head_fio_new, $st_pom_od);
            }
            if (is_array($pom_od) && !empty($pom_od)) {
                $id_head_fio_new = array_merge($id_head_fio_new, $pom_od);
            }

            if (is_array($disp) && !empty($disp)) {
                $id_head_fio_new = array_merge($id_head_fio_new, $disp);
            }

            if (is_array($driver) && !empty($driver)) {
                $id_head_fio_new = array_merge($id_head_fio_new, $driver);
            }
            if (is_array($others) && !empty($others)) {
                $id_head_fio_new = array_merge($id_head_fio_new, $others);
            }

            /* ------------------------------------ КОНЕЦ все ФИО, выбранные на форме в массив ------------------------------------------- */

        if (isset($reserve)) {
             setReserve($reserve, $id, $change, $dateduty, $id_head_fio_new, $log); //заступающие из другич частей
        }
        if (isset($everydayfio)) {
              setEverydayFio($everydayfio, $id, $change, $dateduty, $id_head_fio_new, $log); //заступающие ежедневники
        }

        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v2/card/' . $id . '/ch/' . $change . '/main');
    });

    //main ШЛЧС
    $app->get('/:id/ch/:change/main_sch', function ($id, $change) use ($app) {//sheet other reasons for  change
        array($app, 'is_auth');

     $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 5; //main
        $today = date("Y-m-d");
        $duty = is_duty_cou($id, $change, 0); //смена дежурная или нет
        $data['duty'] = $duty;
        $data['is_open_update'] = is_duty_cou($id, $change, 1); //открыт ли доступ на ред
        $data['duty_ch'] = duty_ch();

        /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/


        /*         * **************************  Списки ФИО ************************************ */
        //список Заступают из др подразделения
        $data['present_reserve_fio'] = getPresentReserveFio($id, $change);

        //список Заступали из др подразделения
        $data['past_reserve_fio'] = getFioById(getListFioReserve($id, $change, 1, 0), $id, $change);

        // список Заступают ежедневники
        $data['present_everyday_fio'] = getPresentEverydayFio($id, $change);

        // список Заступали ежедневники
        $data['past_everyday_fio'] = getFioById(getListFioEveryday($id, $change, 1, 0, 0), $id, $change);

        // список ФИО  смены
        $data['present_head_fio'] = getPresentHeadFio($id, $change);


        /*-------------------- ЗАСТУПАЛИ прошлый раз  ---------------------------*/

        // Заступал зам начальника ШЛЧС
        $data['past_z_head_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 15), $id, $change);

        // Заступал ст помощник нач-ка ШЛЧС
        $data['past_st_pom_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 16), $id, $change);

        // Заступал помощник нач-ка ШЛЧС
        $data['past_pom_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 17), $id, $change);

        // Заступал стажер
        $data['past_trainee_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 18), $id, $change);


        // Заступал driver
        $data['past_driver_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 20), $id, $change);


        /* ----- связь многие ко многим ---- */

        // Заступал others
        $data['past_others_fio'] = getFioById(getListFioByPosMainCou($id, $change, 1, 0, 19), $id, $change);


        /* -------------------- END ЗАСТУПАЛИ прошлый раз  --------------------------- */


        //кнопка "Подтвердить данные"
        $data['is_btn_confirm'] = is_btn_confirm($change);


        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);
        $app->render('card/sheet/start_cou.php', $data);
        $app->render('card/sheet/main/main.php', $data);

        /*         * *********************** сообщение о выполнении операции ********************************* */
        view_msg();
        unset($_SESSION['msg']); //сбросить сообщение

        /*         * ***************************************** */
        //get main
        $main = R::getAll('select * from maincou where id_card = ? and ch = ?',array($id,$change));
        if (isset($main) && !empty($main)) {//есть запись
            ///выбор последней даты
            foreach ($main as $value) {
                //format 10-01-2018 to 2018-01-10
                /*  $date = new DateTime($value['dateduty']);
                  $last_data = $date->Format('Y-m-d'); */
                $last_data = $value['dateduty'];
            }

            /* ------------ кол-во больных, отпусков, командировок,др.причины ------------ */
            $data['count_ill'] = getCountIll($id, $change, $last_data);
            $data['count_holiday'] = getCountHoliday($id, $change, $last_data);
            $data['count_trip'] = getCountTrip($id, $change, $last_data);
            $data['count_other'] = getCountOther($id, $change, $last_data);

            $data['main'] = $main;

            $data['post'] = 0; //put data main...при хранении инф за месяц =1: не обновляем, а добавляем запись
        } else {
            //empty form
            //выводим пустую формы
            //insert row
            //кол-во больных, отпусков, командировок,др.причины
            $data['count_ill'] = 0;
            $data['count_holiday'] = 0;
            $data['count_trip'] = 0;
            $data['count_other'] = 0;
            			 $last_data = date('Y-m-d');

        }
        		 /* -------- по списку смены из списка смены без ежедневников --------- */
            //$data ['on_list'] = getCountOnList($id, $change);
                /* ------------------- вакантов из списка смен ---------------------- */
            //$data['count_vacant_from_list'] = getCountVacantOnList($id, $change);
			 /* ------------------- сколько человек в б.р.(на технике) ---------------------- */
           // $data['count_fio_on_car'] = getCountCalc($id, $change, $last_data);

        $app->render('card/sheet/main/cou/formMainSch.php', $data); //view data
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');


    });

        //main ШЛЧС save
    $app->post('/:id/ch/:change/main_sch', function ($id, $change) use ($app, $log) {// form main for fill
        array($app, 'is_auth'); //авторизован ли пользователь
        //  $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 5; //main
//insert
        $dateduty = $app->request()->post('dateduty');

        if (empty($dateduty))
            $dateduty = NULL;
        else
            $dateduty = date("Y-m-d", strtotime($dateduty));

        $reserve = $app->request()->post('reserve');
        if (empty($reserve))
            $reserve = array();

        $everydayfio = $app->request()->post('everydayfio');
        if (empty($everydayfio))
            $everydayfio = array();


        /* ----- зам начальника ШЛЧС - 15  --- */
        $z_head_sch = $app->request()->post('z_head_sch');
        // insert or update
        saveMainCouOne($id, $change, 15, $dateduty, $z_head_sch);


        /* ---- ст помощник нач-ка ШЛЧС- 16 ----- */
        $st_pom_sch = $app->request()->post('st_pom_sch');
        // insert or update or delete
        saveMainCouOne($id, $change, 16, $dateduty, $st_pom_sch);


                /* ---- помощник нач-ка ШЛЧС- 17 ----- */
        $pom_sch = $app->request()->post('pom_sch');
        // insert or update or delete
        saveMainCouOne($id, $change, 17, $dateduty, $pom_sch);


        /* --------  стажер - 18 -------- */
        $trainee_sch = $app->request()->post('trainee_sch');
        saveMainCouOne($id, $change, 18, $dateduty, $trainee_sch); // insert or update
        //  Другие - 19
        $others_sch = $app->request()->post('others_sch');
        saveMainCouTwo($id, $change, 19, $dateduty, $others_sch); //check and insert or update


        /* ------  Водитель ШЛЧС - 20 ---- */
        $driver_sch = $app->request()->post('driver_sch');
        saveMainCouOne($id, $change, 20, $dateduty, $driver_sch); // insert or update



          /* ------------------------------------ КОНЕЦ все ФИО, выбранные на форме в массив ------------------------------------------- */
            $id_head_fio_old = array($z_head_sch, $st_pom_sch, $pom_sch, $trainee_sch, $driver_sch);
            foreach ($id_head_fio_old as $element) {
                if (!empty($element))
                    $id_head_fio_new[] = $element;
            }

            if (is_array($others_sch) && !empty($others_sch)) {
                $id_head_fio_new = array_merge($id_head_fio_new, $others_sch);
            }

            /* ------------------------------------ КОНЕЦ все ФИО, выбранные на форме в массив ------------------------------------------- */

        if (isset($reserve)) {
             setReserve($reserve, $id, $change, $dateduty, $id_head_fio_new, $log); //заступающие из другич частей
        }
        if (isset($everydayfio)) {
              setEverydayFio($everydayfio, $id, $change, $dateduty, $id_head_fio_new, $log); //заступающие ежедневники
        }

        $_SESSION['msg'] = 1; //ok
        $app->redirect('/str/v2/card/' . $id . '/ch/' . $change . '/main_sch');
    });


    //заступление на смену
    $app->get('/:id/ch/:change/confirm/next', function ($id, $change) use ($app, $log) {//confirm  проверка соответствия формулам, заполненность вкладок

        $data = bread($id);
        $data['change'] = $change;
        $data['sign'] = 5; //main
        $today = date("Y-m-d");

                /*------- для развертывания меню ------*/
        $data['grochs_active']=  get_id_grochs($id);
        $data['region_active']=  get_id_region($id);
         $data['pasp_active']=$id;
         $data['organ_active']=R::getCell('SELECT orgid FROM ss.card WHERE id_record =:id', [':id' => $id]);
        /*------- END для развертывания меню ------*/

        $app->render('layouts/header.php');
        $app->render('layouts/menu.php', $data);
        $app->render('bread/breadCard.php', $data);
        $app->render('card/sheet/start_cou.php', $data);
        $app->render('card/sheet/main/main.php', $data);

//заполнены ли вкладки главная, техника, склад
        $mas_error['main'] = getErrorMainCou($today, $id, $change);
        $mas_error['teh'] = getErrorTeh($today, $id, $change);
        $mas_error['storage'] = 0;//вкладка не существует для ЦОУ

        if (in_array(1, $mas_error)) {//хоть 1 вкладка не заполнена
            $msg_m = $mas_error['main'] == 1 ? '<strong>Главная</strong>' : '';


            /*--------------- заполнена ли вкладка техники----------------------*/

            if($mas_error['teh'] == 1){//нет техники в таблице car

               $c=R::findOne('carcou', 'id_card = ? and ch = ? and dateduty = ?', [$id, $change, $today]);
                if(isset($c) && !empty($c)){  //есть отметка о том, что техника сег не заступает
                   //можно заступить
                     $msg_t = '';
                }
                else{//нет
                  //заступать нельзя
                     $msg_t = '<strong>Техника</strong>' ;
                }
            }
            else{
                $msg_t = '';
            }

            /*--------------- КОНЕЦ заполнена ли вкладка техники----------------------*/


            $msg_s = $mas_error['storage'] == 1 ? '<strong>Склад</strong>' : '';

            if (isset($msg_m) && !empty($msg_m) && isset($msg_t) && !empty($msg_t) && isset($msg_s) && !empty($msg_s)) {
                $data['msg'] = 'Не заполнена(обновлена) информация на вкладках: ' . $msg_m . ' ' . $msg_t . ' ' . $msg_s;
                $app->render('card/sheet/confirm/msg_empty_sheet.php', $data);
            } else {//все вкладки заполнены, техника стоит отметка "техника не заступает"
                //update is_duty=0 у всех смен этой карточки
                R::exec('update maincou set is_duty = ? WHERE is_duty = ? AND  id_card = ? ', array(0, 1, $id));

                //update is_duty=1 у данной смены
                R::exec('update maincou set is_duty = ?, last_update = ?, id_user = ? WHERE id_card = ? AND  ch = ? and dateduty = ? ', array(1, date("Y-m-d H:i:s"), $_SESSION['uid'], $id, $change, $today));


                //тех, кто заступают из других ПАСЧ - занести в командировку в своих ПАСЧ
                setTripFromReserve($id, $change, $today, $log);
                //ту технику, которая заступают из других ПАСЧ - занести в командировку в своих ПАСЧ
                setReserveToTripCar($id, $change, $today, $log);

                $app->render('card/sheet/confirm/success.php', $data);
            }
        } else {//все вкладки заполнены


                //update is_duty=0 у всех смен этой карточки
                  R::exec('update maincou set is_duty = ? WHERE is_duty = ? AND  id_card = ? ', array(0, 1, $id));

                //update is_duty=1 у данной смены
                   R::exec('update maincou set is_duty = ?, last_update = ?, id_user = ? WHERE id_card = ? AND  ch = ? and dateduty = ? ', array(1,  date("Y-m-d H:i:s"),$_SESSION['uid'], $id, $change,$today));


                //тех, кто заступают из других ПАСЧ - занести в командировку в своих ПАСЧ
                setTripFromReserve($id, $change, $today, $log);
                //ту технику, которая заступают из других ПАСЧ - занести в командировку в своих ПАСЧ
                setReserveToTripCar($id, $change, $today, $log);

                $app->render('card/sheet/confirm/success.php', $data);

        }
        $app->render('card/sheet/end.php', $data);
        $app->render('layouts/footer.php');
    });

    //is_car
    $app->post('/:id/ch/:change/is_car', function ($id, $change) use ($app, $log) {// form main for fill
        array($app, 'is_auth'); //авторизован ли пользователь

        $is_car = $app->request()->post('is_car');

        $dateduty=date("Y-m-d");

        echo $is_car;

        if($is_car == 1){

            $c=R::findOne('carcou', 'id_card = ? and ch = ?', [$id, $change]);
            if(isset($c)){
               //insert or update
                $c->dateduty=$dateduty;

            }
            else{
                //create
                $c=R::dispense('carcou');
                $c->id_card=$id;
                $c->ch=$change;
                $c->dateduty=$dateduty;
            }
            R::store($c);
        }
        else{
            //delete
             $c=R::findOne('carcou', 'id_card = ? and ch = ?', [$id, $change]);
             R::trash($c);
        }


        $app->redirect('/str/v1/card/' . $id . '/ch/' . $change . '/car');
    });



    /* -------------- ОТКРЫТЬ ДОСТУП НА РЕД ----------------- */

//проверка, есть ли права на выполнение операции
    $app->get('/open_update/:id', function ($id) use ($app) {
        $data['bread_array'] = R::getAll('select region, locorg_name, divizion FROM general_table_cou WHERE id_record=?', array($id));
        $data['id'] = $id;
        $app->render('layouts/header.php');
        $app->render('layouts/menu.php');
        $app->render('bread/breadOpenUpdate.php', $data);
        // $app->render('layouts/pz_container.php');
        // $is_radmin = R::getAll('SELECT * FROM radmins WHERE pssw = ?', array($_SESSION['psw']));
        if ($_SESSION['is_admin'] == 1) {//есть правао
            //предепреждение
            $app->render('msg/open_update.php');
            $app->render('open_update/cou/open_update.php', $data);
            $app->render('open_update/cou/back.php');
            $app->render('layouts/footer.php');
        } else {//нет права
            $app->redirect('/str/modal');
        }
    });
    //открытие доступа
    $app->get('/open_update/open/:id', function ($id) use ($app, $log) {
        // $id_main = $app->request()->post('id_main');
        //$id_radmin = R::getCell('SELECT id_admin FROM radmins WHERE pssw = ?', array($_SESSION['psw']));
        if ($_SESSION['is_admin'] == 1) {//есть правао
            R::exec('update maincou set open_update = ?, who_open = ?  WHERE is_duty = ? AND id_card = ? AND  dateduty = ? ', array(1, $_SESSION['uid'], 1, $id, date('Y-m-d')));

            $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Открыт доступ на редактирование maincou - для карточки с id_card= ' . $id);
            $app->redirect('/str/general/4');
        } else {//нет права
            $app->redirect('/str/modal');
        }
    });

    /* ----------------- END ОТКРЫТЬ ДОСТУП НА РЕД ----------------- */


    /* ----------------- ЗАКРЫТЬ ДОСТУП НА РЕД ----------------- */

    //предупреждение
    $app->get('/close_update/:id', function ($id) use ($app, $log) {

        $data['bread_array'] =  R::getAll('select region, locorg_name, divizion FROM general_table_cou WHERE id_record=?', array($id));
        $data['id'] = $id;
        $app->render('layouts/header.php');
        $app->render('layouts/menu.php');
        $app->render('bread/breadCloseUpdate.php', $data);

        if ($_SESSION['is_admin'] == 1) {//есть правао
            //предепреждение
            $app->render('msg/close_update.php');

            $app->render('close_update/cou/close_update.php', $data);
            $app->render('open_update/cou/back.php');
            $app->render('layouts/footer.php');
        } else {//нет права
            $app->redirect('/str/modal');
        }
    });


    //закрытие доступа
    $app->get('/close_update/close/:id', function ($id) use ($app, $log) {
        if ($_SESSION['is_admin'] == 1) {//есть правао
            R::exec('update maincou set open_update = ?, who_open = ?  WHERE is_duty = ? AND id_card = ? AND  dateduty = ? ', array(0, $_SESSION['uid'], 1, $id, date('Y-m-d')));

            $log->info('Сессия - ' . $_SESSION['uid'] . ' :: Закрыт доступ на редактирование maincou - для карточки с id_card= ' . $id);
            $app->redirect('/str/general/4');
        } else {//нет права
            $app->redirect('/str/modal');
        }
    });

    /* ----------------- END ЗАКРЫТЬ ДОСТУП НА РЕД -----------------*/




        /* +++++++++++++++++++++ инф по сменам +++++++++++++++++++++ */
        $app->post('/builder/basic/inf_ch_cou/:type', function ($type) use ($app) {// результат запросника информация по сменам ЦОУ, ШЛЧС

        if (!isset($_POST['export_to_excel'])) {
            array($app, 'is_auth');
            $data['title_name'] = 'Запросы/Инф.по сменам ЦОУ, ШЛЧС';
            $app->render('layouts/header.php', $data);
            $app->render('layouts/menu.php');
            $data['bread'] = getBread();
            $app->render('bread/bread.php', $data);

            $data['type'] = $type;

            $data['active'] = 'ch_cou'; ///какая вкладка активна

            $app->render('query/pzmenu.php', $data);

            if ($type == 1 || $type == 2) {//УМЧС
                $data = cou_query(); //только подразд ЦОУ и ШЛЧС - классификаторы
                $app->render('query/cou/form_inf_ch_cou.php', $data);
            }
        }

        /*  ---------- дата, на которую надо выбирать данные ----------*/
        $date_start = (isset($_POST['date_start']) && !empty($_POST['date_start'])) ? $_POST['date_start'] : date("Y-m-d");
        $date_d = new DateTime($date_start);
        $date = $date_d->Format('Y-m-d');

        $today = date("Y-m-d");
        $yesterday = date("Y-m-d", time() - (60 * 60 * 24));
        $day_before_yesterday = date("Y-m-d", time() - (60 * 60 * 24) - (60 * 60 * 24));

        if ($date != $today && $date != $yesterday && $date != $day_before_yesterday) {
            $date = 0;
        }
        /* ------------ END дата, на которую надо выбирать данные ------------ */


        /*----------------  формированеи результата  -------------------*/

        $region = $app->request()->post('region'); //область
        $grochs = $app->request()->post('locorg'); //грочс
        $divizion = $app->request()->post('diviz'); //подразделение


          $main = array();
          $head_info = array();
        if (!empty($region) && !empty($grochs) && !empty($divizion)) {//должно быть выбрано подразделение обязательно!

            $head_info = getInfChBasicCou($region, $grochs, $divizion, $date); //information about name for caption of table
            //print_r($head_info);
           // exit();
            foreach ($head_info as $h) {
                $dateduty_head = $h['dateduty']; //дата деж смены
                $change = $h['ch']; //номер смены
                $name_head = $h['name'];//наим подраздs
            }

            $head='Результат запроса за ' . $dateduty_head . ', ' . $name_head;// export to excel

            //position and fio for change
            $main = R::getAll('select * from inf_ch_cou where id_card = ? and dateduty = ? order by p_id ', array($divizion, $dateduty_head));
            //print_r($main);
            //exit();
        } else {
            $main = array();
            $head_info = array();
        }

        $data['main'] = $main;
        $data['head_info'] = $head_info;

        if (!empty($data['main']) && !empty($data['head_info'])) {

            foreach ($main as $m) {
                $mas_pos_fio[$m['p_name']][] = $m['id_fio']; //массив ключ - наим должности, значение - массив ФИО(id_fio), заступивших на эту должность
            }
//print_r($mas_pos_fio);
//            exit();
            foreach ($mas_pos_fio as $key => $value) {

                if (!empty($value) &&  ($key=='Ответственный по гарнизону' || $key=='Инспектор ОНиП')) {

 $new_mas_pos_fio[$key][0] =array('id'=>'','id_cardch'=>'','is_every'=>'','fio'=>$value[0],'slug'=>'','pasp'=>'','locorg_name'=>'','is_nobody'=>'');
                }
                else{
                      $new_mas_pos_fio[$key] = getFioById($value, $divizion, $change); //ключ - наим должности, значение - массив ФИО, заступивших на эту должность
                }


//                if (!empty($value) && !in_array('', $value) ) {
//                    //print_r($value);echo '<br>';
//                    $new_mas_pos_fio[$key] = getFioById($value, $divizion, $change); //ключ - наим должности, значение - массив ФИО, заступивших на эту должность
//                }
            }
            //print_r($new_mas_pos_fio);
            //exit();

            $data['main_cou'] = $new_mas_pos_fio;


            /* ------------- по штату в деж смене выбираем из карточки для данной смены! ---------------- */
            $shtat['shtat_ch'] = getShtatFromKUSiS($change, $divizion);

            /* ------------------- вакантов в деж смене -  из списка смен ---------------------- */
            $shtat['vacant_ch'] = getCountVacantOnList($divizion, $change);

            /* ------------------- сколько человек в б.р.(на технике) ---------------------- */
            $data['count_fio_on_car'] = getCountCalc($divizion, $change, $dateduty_head);

            /*--------------- отсутствующие ----------------*/
            $absent ['trip'] = getCountTrip($divizion, $change, $dateduty_head);
            $absent['holiday'] = getCountHoliday($divizion, $change, $dateduty_head);
            $absent ['ill'] = getCountIll($divizion, $change, $dateduty_head);
            $absent['other'] = getCountOther($divizion, $change, $dateduty_head);

            $data['absent'] = $absent;


            /* ----------------- л/с подразделения ------------- */
            //по штату   по подразделению c ежедневниками
            $shtat ['shtat'] = R::getCell('select count(l.id) as shtat from str.cardch as c '
                            . ' left join str.listfio as l on l.id_cardch=c.id where c.id_card = ?  ', array($divizion));
            //вакансия по подразделению
            $shtat ['vacant'] = R::getCell('select count(l.id) as shtat from str.cardch as c '
                            . ' left join str.listfio as l on l.id_cardch=c.id where c.id_card = ? AND l.is_vacant = ? ', array($divizion, 1));

            /* ----------------- END л/с подразделения ------------- */
           $data['shtat'] = $shtat;


            /* -------------------------- export to excel ---------------------------- */
            if (isset($_POST['export_to_excel'])) {
                exportToExcelInfChCou($new_mas_pos_fio, $shtat,$absent,$data['count_fio_on_car'],$head);
            }
            /* ---------------------- view on screen  ----------------------------- */ else {
                $app->render('query/result/cou/inf_ch_cou.php', $data); //result
                $app->render('query/pzend.php');
            }
        } else {
            $app->render('msg/emtyResult.php', $data); //no result
        }


        if (!isset($_POST['export_to_excel'])) {
            $app->render('layouts/footer.php');
        }
    });
});

/*-------------- сохранить данные в main_cou связи 1 к 1 ----------------*/

function saveMainCouOne($id, $change, $id_pos_duty, $dateduty, $id_fio) {


    $id_main = R::getCell('select id from maincou where id_card = ? and ch = ? and id_pos_duty = ? ORDER BY dateduty DESC LIMIT ?', array($id, $change, $id_pos_duty, 1));

    if (isset($id_main) && !empty($id_main)) {
        $main = R::load('maincou', $id_main);

        if (empty($id_fio)) {//никто сег на эту должность не заступает
            R::trash($main);

            return;
        }
    } else {

        if (!empty($id_fio)) {//заступает
            $main = R::dispense('maincou');
        } else {
            return;
        }
    }

    $main->id_card = $id;
    $main->ch = $change;
    $main->dateduty = $dateduty;
    $main->id_fio = $id_fio;
    $main->id_pos_duty = $id_pos_duty;
    $main->last_update = date("Y-m-d H:i:s");
    $main->id_user = $_SESSION['uid'];

    R::store($main);

    return;
}

/*-------------- сохранить данные в main_cou  связи 1 к 1 ----------------*/



/*-------------- сохранить данные в main_cou связи многие ко многим ----------------*/

function saveMainCouTwo($id, $change, $id_pos_duty, $dateduty,$reserve) {

        $fioteh_bd = R::getAll('SELECT * FROM maincou WHERE id_card = :id_card AND ch = :ch AND id_pos_duty = :id_pos_duty ', [':id_card' => $id, ':ch' => $change, ':id_pos_duty' => $id_pos_duty]);



        //если не выбрано ни одного ФИО
        if (empty($reserve)) {
            if (isset($fioteh_bd) && !empty($fioteh_bd)) {
                foreach ($fioteh_bd as $key_bd => $value_bd) {

                    $f = R::load('maincou', $value_bd['id']);
                    R::trash($f);
                }
            }
        } else {
            if (isset($fioteh_bd) && !empty($fioteh_bd)) {

                //ищем совпадения из формы и из БД-если найдены-ничего не выполнять-оставляем их в БД
                foreach ($fioteh_bd as $key_bd => $value_bd) {
                    foreach ($reserve as $key => $value) {
                        if ($value_bd['id_fio'] == $value) {
                            $reservefio = R::load('maincou', $value_bd['id']);
                            //обновить дату на сегодня, т.к.работник был зарезервирован на прошлую дату заступления этой смены
                            $reservefio->dateduty = $dateduty;
                            R::store($reservefio);
                            unset($reserve[$key]);
                            unset($fioteh_bd[$key_bd]);
                            break;
                        }
                    }
                }
                //замена фио в БД на ФИО из формы, если совпадения не найдены
                if (!empty($fioteh_bd)) {
                    foreach ($fioteh_bd as $key_bd => $value_bd) {
                        foreach ($reserve as $key => $value) {

                            $reservefio = R::load('maincou', $value_bd['id']);
                            $reservefio->id_fio = $value;
                            $reservefio->dateduty = $dateduty;
                            $reservefio->last_update=date("Y-m-d H:i:s");
                            $reservefio->id_user=$_SESSION['uid'];
                            R::store($reservefio);
                            unset($reserve[$key]);
                            unset($fioteh_bd[$key_bd]);
                            break;
                        }
                    }
                }
//если на форме было > фио, чем в БД- добавить оставшихся
                if (!empty($reserve)) {

                    foreach ($reserve as $key => $value) {

                        $reservefio = R::dispense('maincou');
                        $reservefio->id_card = $id;
                        $reservefio->ch = $change;
                        $reservefio->id_pos_duty=$id_pos_duty;
                        $reservefio->id_fio = $value;
                        $reservefio->dateduty = $dateduty;
                        $reservefio->last_update=date("Y-m-d H:i:s");
                        $reservefio->id_user=$_SESSION['uid'];
                        R::store($reservefio);
                    }
                }
//удалить из БД оставшихся-лишних
                if (!empty($fioteh_bd)) {

                    foreach ($fioteh_bd as $key_bd => $value_bd) {

                        $fiotehstr = R::load('maincou', $value_bd['id']);
                        R::trash($fiotehstr);
                    }
                }
            } else {//insert
                if (!empty($reserve)) {
                    foreach ($reserve as $key => $value) {
                        $reservefio = R::dispense('maincou');
                        $reservefio->id_card = $id;
                        $reservefio->ch = $change;
                        $reservefio->id_pos_duty=$id_pos_duty;
                        $reservefio->id_fio = $value;
                        $reservefio->dateduty = $dateduty;
                        $reservefio->last_update=date("Y-m-d H:i:s");
                        $reservefio->id_user=$_SESSION['uid'];
                        R::store($reservefio);
                    }
                }
            }
        }

}

/*-------------- сохранить данные в main_cou  связи многие ко многим ----------------*/


/*-------------- save data in main_cou connection 1 to 1 fio - text ----------------*/

function saveMainCouOneText($id, $change, $id_pos_duty, $dateduty, $id_fio) {


    $id_main = R::getCell('select id from maincou where id_card = ? and ch = ? and id_pos_duty = ? ORDER BY dateduty DESC LIMIT ?', array($id, $change, $id_pos_duty, 1));

    if (isset($id_main) && !empty($id_main)) {
        $main = R::load('maincou', $id_main);

        if (empty($id_fio)) {//nobody does't go on this position
            R::trash($main);

            return;
        }
    } else {

        if (!empty($id_fio)) {//goes
            $main = R::dispense('maincou');
        } else {
            return;
        }
    }

    $main->id_card = $id;
    $main->ch = $change;
    $main->dateduty = $dateduty;
    $main->fio_text = $id_fio;
    $main->id_pos_duty = $id_pos_duty;
    $main->last_update = date("Y-m-d H:i:s");
    $main->id_user = $_SESSION['uid'];

    R::store($main);

    return;
}

/*-------------- сохранить данные в main_cou  связи 1 к 1 ----------------*/



      /*---------- export to Excel inf ch ЦОУ ------------*/
    function exportToExcelInfChCou($main, $shtat, $absent, $count_fio_on_car,$head) {

    $objPHPExcel = new PHPExcel();
    $objReader = PHPExcel_IOFactory::createReader("Excel2007");
    $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/builder_basic/cou/inf_ch_cou.xlsx');
//activate worksheet number 1
    $objPHPExcel->setActiveSheetIndex(0);
    $sheet = $objPHPExcel->getActiveSheet();
//начальная строка для записи
    $r = 8;
    $i = 0;

    /* +++++++++++++++++++++ style ++++++++++ */

                $style_all = array(
// Заполнение цветом
                'borders' => array(
                  'allborders' => array(
                      'style'=>  PHPExcel_Style_Border::BORDER_THIN
                    )
                )

            );

    /* +++++++++++++ end style +++++++++++++ */

    $sheet->setCellValue('A' . 2, $head);

    /* ---------------все должности и ФИО на этих должностях------------ */

    $itogo = 0;
    foreach ($main as $key => $value) {

        $sheet->setCellValue('A' . $r, $key);

        $itogo+=count($value);

        $fio = '';
        foreach ($value as $row) {

              if ($row['slug'] == '') {
                $fio = $fio . $row['fio'] . ' ' . $row['pasp'] . ' ' . $row['locorg_name']. chr(10);
            } else {
                $fio = $fio . $row['fio'] . ' ' . $row['pasp'] . ' ' . $row['locorg_name'] . ' (' . mb_strtolower($row['slug']) . ')' . chr(10);
            }

            //mb_strtolower($row['slug'])
        }

        $sheet->setCellValue('B' . $r, $fio);

        $sheet->setCellValue('C' . $r, count($value));
        $r++;
    }

    /* ---------------  КОНЕЦ все должности и ФИО на этих должностях------------ */


    $sheet->setCellValue('A' . $r, 'ИТОГО');
    $sheet->mergeCells('A'.$r.':B'.$r);
    $sheet->setCellValue('C' . $r, $itogo);


    /* ------      л/с в смене ----- */
    $sheet->setCellValue('D' . $r, $shtat['shtat_ch']);
    $sheet->setCellValue('E' . $r, $shtat['vacant_ch']);
    /* ---------------      END                 л/с в смене ------------ */

    $sheet->setCellValue('F' . $r, $count_fio_on_car);


    /* ---------   отсутствующие-------- */
    $sheet->setCellValue('G' . $r, $absent['trip']);
    $sheet->setCellValue('H' . $r, $absent['holiday']);
    $sheet->setCellValue('I' . $r, $absent['ill']);
    $sheet->setCellValue('J' . $r, $absent['other']);
    /* ------------ END           отсутствующие ----------- */


    /* -----------   л/с подразделения ---------- */
    $sheet->setCellValue('K' . $r, $shtat['shtat']);
    $sheet->setCellValue('L' . $r, $shtat['vacant']);
    /* -------------    END                 л/с подразделения ------- */


   $sheet->getStyleByColumnAndRow(0, 8, 11, $r)->applyFromArray($style_all);

    /* Сохранить в файл */
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="inf_ch_cou.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
}

/*---------- END export to Excel inf ch ЦОУ ------------*/



      /*---------- export to Excel count position ------------*/
    function exportToExcelCountPosition($result,$head,$head_pos) {

    $objPHPExcel = new PHPExcel();
    $objReader = PHPExcel_IOFactory::createReader("Excel2007");
    $objPHPExcel = $objReader->load(__DIR__ . '/tmpl/count_position.xlsx');
//activate worksheet number 1
    $objPHPExcel->setActiveSheetIndex(0);
    $sheet = $objPHPExcel->getActiveSheet();
//start row
    $r = 9;
    $i = 0;

    /* +++++++++++++++++++++ style ++++++++++ */

                $style_all = array(
// full with color
                'borders' => array(
                  'allborders' => array(
                      'style'=>  PHPExcel_Style_Border::BORDER_THIN
                    )
                )

            );

    /* +++++++++++++ end style +++++++++++++ */

    //$sheet->setCellValue('A' . 2, $head);


    $itogo = 0;
    $k=0;

    $sheet->setCellValue('A2', implode(', ', $head));
    $sheet->setCellValue('A3', 'Должности: '.implode(', ', $head_pos));

    foreach ($result as  $value) {
$k++;
        $sheet->setCellValue('A' . $r, $k);

        $itogo+=$value['cnt'];

        $sheet->setCellValue('B' . $r, $value['name_pos']);
         $sheet->setCellValue('C' . $r, $value['cnt']);

        $r++;
    }

$sheet->setCellValue('A' . $r, 'ИТОГО');
$sheet->setCellValue('C' . $r, $itogo);

   $sheet->getStyleByColumnAndRow(0, 9, 2, $r)->applyFromArray($style_all);

    // save in file */
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="count_position.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
}

/*---------- END export to Excel count position ------------*/


$app->run();
