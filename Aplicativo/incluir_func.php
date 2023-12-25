<?php
session_start();

if (!isset($_SESSION['nivelfunc']) || $_SESSION['nivelfunc'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$host = "localhost";
$username = "root";
$dbname = "Gestoquebd";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function sanitizeInput($conn, $input) {
        $input = trim($input);
        $input = $conn->real_escape_string($input);
        $input = htmlspecialchars($input, ENT_QUOTES);
        return $input;
    }

    $novo_nome = sanitizeInput($conn, $_POST['novo_nome']);
    $novo_cpf = sanitizeInput($conn, $_POST['novo_cpf']);
    $novo_email = sanitizeInput($conn, $_POST['novo_email']);
    $novo_telefone = sanitizeInput($conn, $_POST['novo_telefone']);
    $novo_cargo = sanitizeInput($conn, $_POST['novo_cargo']);
    $novo_username = sanitizeInput($conn, $_POST['novo_username']);
    $nova_senha = sanitizeInput($conn, $_POST['nova_senha']);

    $insert_funcionario_sql = "INSERT INTO tb_funcionario (Nome_Funcionario, CPF_Funcionario, Email_Funcionario, Telefone_Funcionario, Cargo_Funcionario) VALUES ('$novo_nome', '$novo_cpf', '$novo_email', '$novo_telefone', '$novo_cargo')";

    if ($conn->query($insert_funcionario_sql) === TRUE) {
        $funcionario_id = $conn->insert_id; 
    } else {
        echo "Erro na inclusão do funcionário: " . $conn->error;
        exit();
    }



    $insert_conta_sql = "INSERT INTO tb_conta (Login_conta, Senha_conta, TB_Funcionario_Id_Funcionario) VALUES ('$novo_username', '$nova_senha', $funcionario_id)";

    if ($conn->query($insert_conta_sql) === TRUE) {
        header("Location: funcionarios.php");
    } else {
        echo "Erro na inclusão da conta: " . $conn->error;
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE,edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incluir Funcionário</title>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .form {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }
        .form .left, .form .right {
            width: 48%;
        }
        .form label {
            display: block;
            font-weight: bold;
        }
        .form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form button {
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .form .left, .form .right {
                width: 100%;
                margin-right: 0;
         
           }
           
        }
        a{
            background-color: #007BFF;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Incluir Funcionário
        </div>
        <form method="POST" class="form">
            <div class="left">
                <label for="novo_nome">Nome:</label>
                <input id="novo_nome" type="text" name="novo_nome" required />

                <label for="novo_cpf">CPF:</label>
                <input id="novo_cpf" type="text" name="novo_cpf" required pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" placeholder="123.456.789-01" />

                <label for="novo_email">E-mail:</label>
                <input id="novo_email" type="email" name="novo_email" required placeholder="email@provedora.com" />

                <label for="novo_telefone">Telefone:</label>
                <input id="novo_telefone" type="text" name="novo_telefone" required pattern="\(\d{2}\) \d{5}-\d{4}" placeholder="(99) 99999-9999"  />

                <label for="novo_cargo">Cargo:</label>
                <input id="novo_cargo" type="text" name="novo_cargo" required />
            </div>
            <div class="right">
                <label for="novo_username">Nome de Usuário:</label>
                <input id="novo_username" type="text" name="novo_username" required />

                <label for="nova_senha">Senha:</label>
                <input id="nova_senha" type="password" name="nova_senha" required />
            </div>
            <button type="submit">Incluir Funcionário</button>
            <a href="funcionarios.php" class="btn-cancel">Cancelar Incluir</a>
        </form>
    </div>
</body>
</html>
