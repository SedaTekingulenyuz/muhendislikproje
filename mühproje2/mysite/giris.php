<?php require_once('Connections/baglan.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_baglan, $baglan);
$query_Recordset1 = "SELECT * FROM uye";
$Recordset1 = mysql_query($query_Recordset1, $baglan) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['adsoyad'])) {
  $loginUsername=$_POST['adsoyad'];
  $password=$_POST['sifre'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "girisyapildi.php";
  $MM_redirectLoginFailed = "kayitol.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_baglan, $baglan);
  
  $LoginRS__query=sprintf("SELECT adsoyad, sifre FROM uye WHERE adsoyad=%s AND sifre=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $baglan) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GİRİŞ</title>
	
<link rel="stylesheet" href="style.css">
	
 </head>
<body>
 
  <div class="container">
   <img src="images.png"/>
   <form ACTION="<?php echo $loginFormAction; ?>" METHOD="POST">
   <div class="form-input">
   <input type="text" name="adsoyad" placeholder="Kullanıcı Adı">
   </div>
   <div class="form-input">
   <input type="password" name="sifre" placeholder="Şifreniz">
   </div>
   <input type="submit" name="submit" value="GİRİŞ" class="btn-giris"><br/>
   <a href="sifre.php">Şifrenizi Unuttunuz mu?</a>
   </form>
	  <a href="kayitol.php">Kayıt OL.</a>
  
  </div>

	 
</body>
</html>
<?php
mysql_free_result($Recordset1);
?>