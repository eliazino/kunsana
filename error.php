<!DOCTYPE html>
<html lang="en">
  <head>
    <title>error!!</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="dep/css/bootstrap.min.css" rel="stylesheet">
    <link href="dep/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="dep/css/font-awesome.min.css">
    <link rel="stylesheet" href="dep/css/fonts.css">
  </head>

  <body class="for_error">
    
    <div class="main">
      <div class="container">
       <ul>
        <li>
          <h1>Oops!</h1>
          <div class="no"><?php 
		  $errors = ['400', '401', '403', '404'];
		  echo (in_array($_REQUEST['t'],$errors))? $_REQUEST['t'] : '404'; ?></div>
          <p>An error there, but you can return back <a href="index">home</a></p>


        </li>
        <li>
          <div class="error_img"><img  class="img-responsive" src="dep/img/error.png" alt="error!!!"></div>
        </li>


      </ul>

      </div>
     
    </div>
    <script src="dep/js/jquery-latest.min.js"></script>
    <script src="dep/js/bootstrap.min.js"></script>
  </body>
</html>
