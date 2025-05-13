<?php
require_once("init.php");
require_once("check-login.php");

$sql = "SELECT id, concat(fam, ' ', name, ' ', otch) as name FROM students ORDER BY name";
$result = mysqli_query($db_handler,$sql) or die ("Невозможно выполнить SQL запрос в 'get-student.php'!".mysqli_error($db_handler));

$response ='<select>';
while($row = mysqli_fetch_array($result)) {
    $response .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
}
$response .= '</select>';

echo $response;
mysqli_close($db_handler);  
?>