<?php

function forgotPasswordSuperUser(String $username, int $code, String $appURL, String $token): String
{
  return "
       <div style='min-width:100%;min-height:100%;max-height:auto;max-width:auto;background:#f5f5f5;'>

       <div style='width:520px;margin:auto;background:#fff;'>

       <h1 style='background:#000;color:white;padding:2px;text-align:center;'>Login Token</h1>

       <div>
         <p style='padding:10px;font-size:1.17em;color:black;'>
           Hello <b>". $username ."</b>,<br/>
           A forgot password request for your super user account has been received. Click on the button below to go to the password reset page<br/>
         </p>

         <a style='margin: 10px auto;display: flex;flex-direction:column;justify-content: center;text-decoration: none;text-align:center;' href=". $appURL ."/superuser/resetpassword/". $token .">
           <button style = 'border:none;font-size:20px;outline:none;background:#000;width:280px;border-radius:5px;padding:15px 10px;color:white;margin:0 auto;'>
           Click Here To Reset
           </button>
         </a>


       </div>
       </div>
       </div>
       ";
}
