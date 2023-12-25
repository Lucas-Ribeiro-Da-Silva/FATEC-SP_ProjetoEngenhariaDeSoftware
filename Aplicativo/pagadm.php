<?php
session_start();

if (!isset($_SESSION['nivelfunc']) || $_SESSION['nivelfunc'] !== 'admin') {
    
    header("Location: index.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrador</title>
  <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="../css/style.css">
  <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background: #f4f4f4;
    }

    .container {
      display: flex;
      justify-content: space-between;
    }

    .sidebar {
      width: 20%;
      background: #333;
      padding: 20px;
      color: #fff;
    }

    .sidebar a {
      display: block;
      text-decoration: none;
      color: #fff;
      margin: 10px 0;
    }

    .content {
      width: 80%;
      padding: 20px;
    }

    .logout {
      float: right;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="sidebar">
      <h1>Menu</h1>
      <a href="funcionarios.php">Funcionários</a>
      <a href="estoquecrud.php">Estoque</a>
      <a href="registrar_saidas.php">Registro de Saídas</a>
      <a class="logout" href="logout.php">Sair da Conta</a>
    </div>
    <div class="content">
   
      <div class="img-box"> <img src="../img/fematchfundo.jpg" alt="teste"></div>
    </div>
  </div>
</body>

</html>