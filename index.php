<?php
require_once("init.php");
$errors=array();
if(!isset($_SESSION['loginned'])) $_SESSION['loginned']=false;
if(!$_SESSION['loginned']){
        if(isset($_REQUEST['submit-btn'])){
                $_SESSION['userId']=0;					
                $_SESSION['userLogin']=""; 
                $_SESSION['userName']=""; 
                $_SESSION['userIsEdit']=0;					
                $_SESSION['userIsAdmin']=0;													
                $_SESSION['userIsPay']=0;													
                if(isset($_REQUEST['login']) && isset($_REQUEST['password']) && $_REQUEST['login']!="" && $_REQUEST['password']!=""){				
                        $QueryString="SELECT * from users where login='".$_REQUEST['login']."' and password='".md5($_REQUEST['password'])."'";  
                        $QueryResult=mysqli_query($db_handler, $QueryString);
                        $RowCount=mysqli_num_rows($QueryResult);
                        if ($RowCount>0) {
                                $row=array();
                                $row = mysqli_fetch_array($QueryResult,MYSQLI_ASSOC);
                                $_SESSION['loginned']=true;
                                $_SESSION['userId']=$row['id'];
                                $_SESSION['userLogin']=$row['login'];
                                $_SESSION['userName']=$row['lastname']." ".$row['firstname'];
                                $_SESSION['userIsEdit']=$row['isedit'];
                                $_SESSION['userIsAdmin']=$row['isadmin'];
                                $_SESSION['userIsPay']=$row['ispay'];
                                $_SESSION['userSessionID']=$row['session_id'] + 1;
                                $_SESSION['userPassword']=$_REQUEST['password'];
                                $QueryString2="update users set session_id =".$_SESSION['userSessionID']." where id=".$_SESSION['userId'];
                                $QueryResult2=mysqli_query($db_handler, $QueryString2);
                        }else{
                                $errors[]='Указано неверное сочетание логина и пароля или срок действия истёк/не наступил';
                        }
                }else{
                        $errors[]='Не указан логин или пароль';
                }
        }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>БД СТУДЕНТ</title>

<style>
    .auth-form							{margin-left:auto; margin-right:auto; padding:150px; color:#525252; }
    .auth-form h1						{font-size:60px; font-weight:normal;}
    .errors								{margin-top:20px; color:#d05f5f; padding:0px;}
</style> 

</head>
<body>
<?php
if($_SESSION['loginned']) {
    header(substr('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],0,strrpos("Location: http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],'/')) . '/journal.php');
} else { ?>
    <div class="auth-form">        	
        <div>
            <table>
                <tr>
                    <td width='260px' rowspan='2'>
                        <img src='logo-big.png'>
                    </td>
                    <td width='*'>
                        <h1>База данных СТУДЕНТ</h1>
                    </td>
                </tr>
                <tr>
                    <td>
                        <form action='#' method='POST'>
                            <table>
                                <tr>
                                    <td width='48px'>Логин</td><td width='146px'><input type='text' name='login'></td>
                                </tr>
                                <tr>
                                    <td>Пароль</td><td><input type='password' name='password'></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td><td><input name='submit-btn' type='submit' value='Войти'></td>
                                </tr>
                            </table>
                        </form>
                    </td>                    
                </tr>
            </table>                                                       
        </div>
        <div>
            <ul class='errors'>
            <?php foreach($errors as $error) {?>
                <li><?php echo $error; ?></li>
            <?php } ?>
            </ul>
        </div>
    </div>
<?php } ?>        
</body>
</html>
