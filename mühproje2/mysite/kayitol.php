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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="kayitzatenvar.php";
  $loginUsername = $_POST['adsoyad'];
  $LoginRS__query = sprintf("SELECT adsoyad FROM uye WHERE adsoyad=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_baglan, $baglan);
  $LoginRS=mysql_query($LoginRS__query, $baglan) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form")) {
  $insertSQL = sprintf("INSERT INTO uye (adsoyad, `kullanici adi`, sifre, email) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_POST['adsoyad'], "text"),
                       GetSQLValueString($_POST['kullaniciadi'], "text"),
                       GetSQLValueString($_POST['sifre'], "text"),
                       GetSQLValueString($_POST['email'], "text"));

  mysql_select_db($database_baglan, $baglan);
  $Result1 = mysql_query($insertSQL, $baglan) or die(mysql_error());

  $insertGoTo = "giris.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_baglan, $baglan);
$query_kayitekle = "SELECT * FROM uye";
$kayitekle = mysql_query($query_kayitekle, $baglan) or die(mysql_error());
$row_kayitekle = mysql_fetch_assoc($kayitekle);
$totalRows_kayitekle = mysql_num_rows($kayitekle);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<title>KAYIT OL</title>
	
  <link rel="stylesheet" href="kayit.css">
	
</head>
<body>
	 
 
  <div class="container">
   <img src="images.png"/>
   <form action="<?php echo $editFormAction; ?>" method="POST" name="form">
	   <div class="form-input">
   <input type="text" name="adsoyad" placeholder="Adı Soyadı">
   </div>
   <div class="form-input">
   <input type="text" name="kullaniciadi" placeholder="Kullanıcı Adı">
   </div>
   <div class="form-input">
   <input type="password" name="sifre" placeholder="Şifreniz">
   </div>
	   <div class="form-input">
   <input type="password2" name="sifre" placeholder="Şifreniz Tekrar">
   </div>
	   <div class="form-input">
   <input type="text" name="email" placeholder="E-mail">
   </div>
	 
   <input type="submit" name="submit" value="KAYIT" class="btn-kayit"><br/>
   <input type="hidden" name="MM_insert" value="form">
   </form> 
	  
</div>

	 
</body>
</html>
<?php
mysql_free_result($kayitekle);
?>