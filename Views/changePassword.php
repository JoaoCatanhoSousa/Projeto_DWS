<?php

/*
 *Importante information 
 !Deprecated method, do not use
 TODO: refactor this code
 ?should this method be used?
*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- *Link para o Arquivo CSS -->
    <link rel="stylesheet" href="/css/style.css"> 
    <!-- *Goocle fonts link -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<%- include('partials/header') %>
<body>
    <div><!-- !ainda tenho que fazer alterações-->
        <div class="loginclass" id="loginclass">
            <h1 class="form-title">Change Password</h1>
            
            <form action="#" class="login-form">
                <div class="input-wrapper">
                    <input type="email" placeholder="Current Password" class="input-field" required>
                    <i class="material-symbols-outlined">lock</i>
                </div>
                <div class="input-wrapper">
                    <input type="password" placeholder="New Password" class="input-field" required>
                    <i class="material-symbols-outlined">lock</i>
                </div>
                <div class="input-wrapper">
                    <input type="password" placeholder="Confirm Password" class="input-field" required>
                    <i class="material-symbols-outlined">lock</i>
                </div>
                
                <button class="login-button">Log in</button>
            </form>
            
        </div>
    </div>
</body>
<%- include('partials/footer') %>
</html>