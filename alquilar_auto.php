<?php
include_once "nav_bar.php";
include_once "database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (rent_vehicle($_GET["id"], $_SESSION["id"], $_POST["numero_dias"], $_POST["numero_semanas"])) {
        js_redirect("/vehiculos.php");
    } else {
        echo ":(";
    }
}
?>
<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Alquilar vehiculo</title>
      <link rel="stylesheet" href="css/style.css">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
   <body>
      <div class="wrapper">
         <div class="title-text">
            <div class="title login">
               Formulario de alquiler de autos
            </div>
         </div>
         <div class="form-container">
            
            <div class="form-inner">
               <form class="login" method="post">
                  <div class="field">
                     <input type="number" min="0" placeholder="Cantidad de semanas" name="numero_semanas" required>
                  </div>
                  <div class="field">
                     <input type="number" min="0" placeholder="Cantidad de dias" name="numero_dias" required>
                  </div>
                  <div class="field btn">
                     <div class="btn-layer"></div>
                     <input type="submit" value="Alquilar">
                  </div>
               </form>
            </div>
         </div>
      </div>
      <script>
         const loginText = document.querySelector(".title-text .login");
         const loginForm = document.querySelector("form.login");
         const loginBtn = document.querySelector("label.login");
         const signupBtn = document.querySelector("label.signup");
         const signupLink = document.querySelector("form .signup-link a");
         signupBtn.onclick = (()=>{
           loginForm.style.marginLeft = "-50%";
           loginText.style.marginLeft = "-50%";
         });
         loginBtn.onclick = (()=>{
           loginForm.style.marginLeft = "0%";
           loginText.style.marginLeft = "0%";
         });
         signupLink.onclick = (()=>{
           signupBtn.click();
           return false;
         });
      </script>
   </body>
</html>