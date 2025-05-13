<?php
require_once("init.php");
require_once("check-login.php");

if (isset($_GET['page'])) { $page = $_GET['page']; } else { $page = 1; } 
if (isset($_GET['rows'])) { $limit = $_GET['rows']; } else { $limit = 10; }
if (isset($_GET['sidx'])) { $sidx = $_GET['sidx'];  } else { $sidx = 1; }
if (isset($_GET['sord'])) { $sord = $_GET['sord']; } else { $sord=""; } 
if(!$sidx) $sidx =1;

$ops = array(
    'eq'=>'=', //equal
    'ne'=>'<>',//not equal
    'lt'=>'<', //less than
    'le'=>'<=',//less than or equal
    'gt'=>'>', //greater than
    'ge'=>'>=',//greater than or equal
    'bw'=>'LIKE', //begins with
    'bn'=>'NOT LIKE', //doesn't begin with
    'in'=>'LIKE', //is in
    'ni'=>'NOT LIKE', //is not in
    'ew'=>'LIKE', //ends with
    'en'=>'NOT LIKE', //doesn't end with
    'cn'=>'LIKE', // contains
    'nc'=>'NOT LIKE'  //doesn't contain
);
function getWhereClause($col, $oper, $val){
    global $ops;
    if($oper == 'bw' || $oper == 'bn') $val .= '%';
    if($oper == 'ew' || $oper == 'en' ) $val = '%'.$val;
    if($oper == 'cn' || $oper == 'nc' || $oper == 'in' || $oper == 'ni') $val = '%'.$val.'%';
    return " WHERE $col {$ops[$oper]} '$val' ";
}
$where = ""; //if there is no search request sent by jqgrid, $where should be empty
$searchField = isset($_GET['searchField']) ? $_GET['searchField'] : false;
$searchOper = isset($_GET['searchOper']) ? $_GET['searchOper']: false;
$searchString = isset($_GET['searchString']) ? $_GET['searchString'] : false;
if (isset($_GET['_search']) && $_GET['_search'] == 'true') {
    $where = getWhereClause($searchField,$searchOper,$searchString);
}
// if (isset($_GET['_search']) && $_GET['_search'] == 'true') {
//         $Where = stripslashes($_GET['filters']);
// }

if (!$sidx) $sidx = 1;

$totalrows = isset($_REQUEST["totalrows"]) ? $_REQUEST["totalrows"]: false;

if($totalrows) {

    $limit = $totalrows;

}
$sql=  "SELECT COUNT(*) AS count 
        FROM journal  j 
        left join students s on j.student_id = s.id
        left join prepod p on j.prepod_id = p.id 
        left join predmets pr on j.predmet_id = pr.id
        left join city c on s.city_id = c.id 
        left join sgroups g on s.group_id = g.id $where";
$result = mysqli_query($db_handler,$sql);
$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
$count = $row['count'];

if ($count > 0 ) {

    $var = @($count/$limit);
    $totalpages = ceil ($var);

} else {

    $totalpages = 0;

}

if ($page > $totalpages) $page=$totalpages;

if ($limit < 0) $limit = 0;

$start = $limit*$page - $limit;
if ($start < 0) $start = 0;

$sql = "SELECT  j.id, 
                j.day, 
                g.name as grp, 
                pr.name as predmet, 
                concat(s.fam, ' ', s.name, ' ', s.otch) as student, 
                c.name as city, 
                concat(p.fam, ' ', p.name, ' ', p.otch) as prepod, 
                case when j.pres=1 then 'присутствовал' else 'отсутствовал' end as 'present', 
                case when isnull(j.mark) then 'нет оценки' else j.mark end as 'mark'
        FROM journal j 
        left join students s on j.student_id = s.id
        left join prepod p on j.prepod_id = p.id 
        left join predmets pr on j.predmet_id = pr.id
        left join city c on s.city_id = c.id 
        left join sgroups g on s.group_id = g.id $where ORDER BY $sidx $sord LIMIT $start , $limit";

$result = mysqli_query($db_handler,$sql) or die ("Невозможно выполнить SQL запрос! ".mysqli_error($db_handler));

$responce = new stdClass();
$responce -> page = $page;
$responce -> total = $totalpages;
$responce -> records = $count;

$i = 0;
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $responce -> rows[$i]["id"] = $row["id"];
    $responce -> rows[$i]['cell'] = array(  $row["id"],
                                            $row["day"],
                                            $row["grp"],
                                            $row["predmet"],
                                            $row["student"],
                                            $row["city"],
                                            $row["prepod"],
                                            $row["present"],
                                            $row["mark"]
                                        );
    $i++;
}

echo json_encode($responce);
mysqli_close($db_handler);
?>
