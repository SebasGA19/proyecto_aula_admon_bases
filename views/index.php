<?php
include_once "database.php";

session_start();

if (isset($_SESSION["id"])) {
    js_redirect("dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["login"])) {
        $user_id = login($_POST["username"], $_POST["password"]);
        if ($user_id !== null) {
            $_SESSION["id"] = $user_id;
            js_redirect("/dashboard.php");
            exit();
        }
    } else if (isset($_POST["register"])) {
        $user_id = register_client(
            $_POST["ciudad_residencia"],
            $_POST["cedula"],
            $_POST["nombres"],
            $_POST["apellidos"],
            $_POST["direccion"],
            $_POST["telefono"],
            $_POST["correo"],
            $_POST["contrasena"],
            $_POST["confirm-password"]
        );
        if ($user_id) {
            echo "Registration succeed";
        }
    }
}
?>
<!DOCTYPE html>
<!-- Created By CodingNepal -->
<html lang="en" dir="ltr">
   <head>
      <meta charset="utf-8">
      <title>Login and Registration Form in HTML | CodingNepal</title>
      <link rel="stylesheet" href="style.css">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
   <body>
      <div class="wrapper">
         <div class="title-text">
            <div class="title login">
               Login Form
            </div>
            <div class="title signup">
               Signup Form
            </div>
         </div>
         <div class="form-container">
            <div class="slide-controls">
               <input type="radio" name="slide" id="login" checked>
               <input type="radio" name="slide" id="signup">
               <label for="login" class="slide login">Login</label>
               <label for="signup" class="slide signup">Signup</label>
               <div class="slider-tab"></div>
            </div>
            <div class="form-inner">
               <form method="post" class="login">
                  <div class="field">
                     <input type="email" placeholder="Correo" id="username" name="username" required>
                  </div>
                  <div class="field">
                     <input type="password" placeholder="Password" name="password" required>
                  </div>
                  <div class="pass-link">
                     <a href="#">Forgot password?</a>
                  </div>
                  <div class="field btn">
                     <div class="btn-layer"></div>
                     <input type="submit" value="Login" name="login">
                  </div>
                  <div class="signup-link">
                     Not a member? <a href="">Signup now</a>
                  </div>
               </form>
               <form method="post" class="signup">
                  <div class="field">
                     <input type="text" placeholder="Ciudad Residencia" name="ciudad_residencia" required >
                  </div>
                  <div class="field"><input type="text" placeholder="Cedula" name="cedula" required >
                  </div>
                  <div class="field">
                     <input type="text" placeholder="Nombres" name="nombres" required >
                  </div>
                  <div class="field">
                     <input type="text" placeholder="Apellidos" name="apellidos" required >
                  </div>
                  <div class="field">
                     <input type="text" placeholder="Direccion" name="direccion" required >
                  </div>
                  <div class="field">
                     <input type="text" placeholder="Telefono" name="telefono" required >
                  </div>
                  <div class="field">
                     <input type="email" placeholder="Correo" name="correo" required>
                  </div>
                  <div class="field">
                     <input type="password" placeholder="Contraseña" name="contrasena" required>
                  </div>
                  <div class="field">
                     <input type="password" placeholder="Confirmar contraseña" name="confirm-password" required>
                  </div>
                  <div class="field btn">
                     <div class="btn-layer"></div>
                     <input type="submit" value="Signup" name="register">
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