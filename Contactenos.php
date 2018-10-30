<?php
function ValidateEmail($email)
{
   $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
   return preg_match($pattern, $email);
}
function ReplaceVariables($code)
{
   foreach ($_POST as $key => $value)
   {
      if (is_array($value))
      {
         $value = implode(",", $value);
      }
      $name = "$" . $key;
      $code = str_replace($name, $value, $code);
   }
   $code = str_replace('$ipaddress', $_SERVER['REMOTE_ADDR'], $code);
   return $code;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'layoutgrid1')
{
   $mailto = 'ivaniamelendez02@gmail.com';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $subject = 'Website form';
   $message = 'Values submitted from web site form:';
   $success_url = './Gracias.html';
   $error_url = '';
   $error = '';
   $eol = "\n";
   $boundary = md5(uniqid(time()));

   $header  = 'From: '.$mailfrom.$eol;
   $header .= 'Reply-To: '.$mailfrom.$eol;
   $header .= 'MIME-Version: 1.0'.$eol;
   $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
   $header .= 'X-Mailer: PHP v'.phpversion().$eol;
   if (!ValidateEmail($mailfrom))
   {
      $error .= "The specified email address is invalid!\n<br>";
   }

   if (!empty($error))
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $error, $errorcode);
      echo $errorcode;
      exit;
   }

   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha_code", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response");
   $message .= $eol;
   $message .= "IP Address : ";
   $message .= $_SERVER['REMOTE_ADDR'];
   $message .= $eol;
   $logdata = '';
   foreach ($_POST as $key => $value)
   {
      if (!in_array(strtolower($key), $internalfields))
      {
         if (!is_array($value))
         {
            $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
         }
         else
         {
            $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
         }
      }
   }
   $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
   $body .= '--'.$boundary.$eol;
   $body .= 'Content-Type: text/plain; charset=ISO-8859-1'.$eol;
   $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
   $body .= $eol.stripslashes($message).$eol;
   if (!empty($_FILES))
   {
       foreach ($_FILES as $key => $value)
       {
          if ($_FILES[$key]['error'] == 0)
          {
             $body .= '--'.$boundary.$eol;
             $body .= 'Content-Type: '.$_FILES[$key]['type'].'; name='.$_FILES[$key]['name'].$eol;
             $body .= 'Content-Transfer-Encoding: base64'.$eol;
             $body .= 'Content-Disposition: attachment; filename='.$_FILES[$key]['name'].$eol;
             $body .= $eol.chunk_split(base64_encode(file_get_contents($_FILES[$key]['tmp_name']))).$eol;
          }
      }
   }
   $body .= '--'.$boundary.'--'.$eol;
   if ($mailto != '')
   {
      mail($mailto, $subject, $body, $header);
   }
   $successcode = file_get_contents($success_url);
   $successcode = ReplaceVariables($successcode);
   echo $successcode;
   exit;
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Page</title>
<meta name="generator" content="WYSIWYG Web Builder 14 - http://www.wysiwygwebbuilder.com">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="font-awesome.min.css" rel="stylesheet">
<link href="Untitled2_autobackup.css" rel="stylesheet">
<link href="Contactenos.css" rel="stylesheet">
</head>
<body>
<div id="wb_LayoutGrid11">
<div id="LayoutGrid11">
<div class="row">
<div class="col-1">
<div id="wb_Breadcrumb4" style="display:inline-block;width:100%;z-index:0;vertical-align:top;">
<ul id="Breadcrumb4">
<li><a href="./index.html"><i class="fa fa-home">&nbsp;</i>INICIO</a></li>
</ul>

</div>
</div>
<div class="col-2">
<div id="wb_Breadcrumb6" style="display:inline-block;width:100%;z-index:1;vertical-align:top;">
<ul id="Breadcrumb6">
<li><a href="./Tenis.html">CALZADO</a></li>
<li><a href="./Muebles.html">MADERA</a></li>
</ul>

</div>
</div>
<div class="col-3">
<div id="wb_Breadcrumb5" style="display:inline-block;width:100%;z-index:2;vertical-align:top;">
<ul id="Breadcrumb5">
<li class="active" aria-current="page"><i class="fa fa-envelope-o">&nbsp;</i>Contactanos</li>
</ul>

</div>
</div>
</div>
</div>
</div>
<div id="wb_LayoutGrid1">
<form name="LayoutGrid1" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" id="LayoutGrid1">
<input type="hidden" name="formid" value="layoutgrid1">
<div class="row">
<div class="col-1">
<div id="wb_Heading1" style="display:inline-block;width:100%;z-index:3;">
<h1 id="Heading1">CONTACTANOS</h1>
</div>
<hr id="Line1" style="display:block;width: 100%;z-index:4;">
<input type="text" id="Editbox1" style="display:block;width: 100%;height:32px;z-index:5;" name="nombre" value="" spellcheck="false" placeholder="nombre">
<hr id="Line2" style="display:block;width: 100%;z-index:6;">
<input type="text" id="Editbox2" style="display:block;width: 100%;height:32px;z-index:7;" name="email" value="" spellcheck="false" placeholder="email">
<hr id="Line3" style="display:block;width: 100%;z-index:8;">
<input type="text" id="Editbox3" style="display:block;width: 100%;height:32px;z-index:9;" name="celular" value="" spellcheck="false" placeholder="numero de celular">
<hr id="Line4" style="display:block;width: 100%;z-index:10;">
<textarea name="mensaje" id="TextArea1" style="display:block;width: 100%;;height:100px;z-index:11;" rows="2" cols="101" spellcheck="false" placeholder="mensaje"></textarea>
<hr id="Line5" style="display:block;width: 100%;z-index:12;">
<input type="submit" id="Button1" name="" value="Enviar" style="display:inline-block;width:84px;height:45px;z-index:13;">
</div>
</div>
</form>
</div>
<div id="wb_LayoutGrid10">
<div id="LayoutGrid10">
<div class="row">
<div class="col-1">
<div id="wb_Breadcrumb2" style="display:inline-block;width:531px;height:37px;z-index:14;vertical-align:top;">
<ul id="Breadcrumb2">
<li><a href="./index.html">Inicio</a></li>
<li><a href="./Tenis.html">Calzado</a></li>
<li><a href="./Muebles.html">Madera</a></li>
<li class="active" aria-current="page">Contactanos</li>
</ul>

</div>
<div id="wb_Text8">
<span style="color:#656565;font-family:Arial;font-size:13px;">variedadesarihany@gmail.com</span>
</div>
</div>
</div>
</div>
</div>
</body>
</html>
