<?
    include('token.php');
    if($_GET["username"].$_GET["password"] == $access_token){
        header('Location: index.html');
    } else {
        echo "access denied";
    }
?>