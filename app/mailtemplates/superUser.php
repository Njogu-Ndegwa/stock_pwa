<?php

function superUserTemplate(String $username, int $code, String $appURL, String $token): String
{
  return "
       <div style='min-width:100%;min-height:100%;max-height:auto;max-width:auto;background:#f5f5f5;'>

       <div style='width:520px;margin:auto;background:#fff;'>

       <h1 style='background:#000;color:white;padding:2px;text-align:center;'>Super User Creation</h1>

       <div>
         <p style='padding:10px;font-size:1.17em;color:black;'>
           Hello <b>". $username ."</b>,<br/>
           Enter the code below on the page to proceed with the creation of the super user for the system.<br/>
           <div style='text-align:center;width:100%;font-size: xx-large;'><b>".$code."</b></div>
         </p>

         <a style='margin: 10px auto;display: flex;flex-direction:column;justify-content: center;text-decoration: none;text-align:center;' href=". $appURL ."/superuser/logintoken/". $token .">
         <p style='padding:10px;font-size:1.17em;color:black;margin:0;'>If browser tab is closed, you can also:</p>
           <button style = 'border:none;font-size:20px;outline:none;background:#00143e;width:280px;border-radius:5px;padding:15px 10px;color:white;margin:0 auto;'>
           Click Here To Proceed
           </button>
         </a>


       </div>
       </div>
       </div>
       ";
}
