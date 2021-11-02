<?php
function accountCreation(String $companyName, String $activationKey, String $appURL, String $token)
{
  return "
       <div style='min-width:100%;min-height:100%;max-height:auto;max-width:auto;background:#f5f5f5;'>

       <div style='width:520px;margin:auto;background:#fff;'>

       <h1 style='background:#000;color:white;padding:2px;text-align:center;'>Login Token</h1>

       <div>
         <p style='padding:10px;font-size:1.17em;color:black;'>
            This is the activation email for <b>". $companyName ."</b> <br/>
            Your activation key is:<br/>
            <div style='text-align:center;width:100%;font-size: xx-large;'><b>".$activationKey."</b></div>
         </p>

         <a style='margin: 10px auto;display: flex;flex-direction:column;justify-content: center;text-decoration: none;text-align:center;' href=". $appURL ."/company/activation/". $token .">
           <button style = 'border:none;font-size:20px;outline:none;background:#000;width:280px;border-radius:5px;padding:15px 10px;color:white;margin:0 auto;'>
           Click Here To Go to Activation Form
           </button>
         </a>


       </div>
       </div>
       </div>
       ";
}
