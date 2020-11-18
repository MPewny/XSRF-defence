<?php
session_start();
?>
<html>
 <head>
   <title><?php echo $_SERVER['SERVER_NAME']." XSRF protection system";?></title>
   <meta name="plugin_author" value="Marcel Pewny - MPewny">
   <meta name="author_github" value="https://github.com/MPewny">

 </head>
 <style>
   body{
   background:#ffe6ff;
   color:#ff6600;
   font-family: Consolas;
   }

   a{
    color:#ff6600;
   }
   div.main{
   position:absolute;
   top:20px;
   left:10%;
   right:10%;
   padding-right:10%;
   padding-left:10%;
   padding-top:30px;
   padding-bottom:30px;
   background:#b30000;
   border:2px solid #660000;
   }
   div.errorlog{
   width:80%;
   padding:20px;
   border:2px solid red;
   background:#1a0000;
   border-radius:10px;
   opacity: 0.7;
   }

   div.footer{
   width:80%;
   padding-left:20px;
   padding-right:20px;
   padding-top:5px;
   padding-bottom:5px;
   border:2px solid red;
   background:#1a0000;
   border-radius:10px;
   opacity: 0.7;
     }
 </style>
 <body>
 <div class="main">
 <div class="errorlog">
   <?php

     echo "<br> <hr> <br>". $_SESSION['xsrferr'];
    
    unset $_SESSION['xsrfError'];
   ?>
   </div>
  <br>
  <div class="footer">
   <center>Plugin coded by <a href="https://github.com/MPewny">MPewny</a></center></div>
</div>
 </body>
<html>
