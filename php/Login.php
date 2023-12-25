<?php
session_start();

$username = $_POST['user'];
$senha = $_POST['senha'];

$server = "localhost";
$banco = "Gestoquebd";
$user = "root";
$passwd = "";
$tabela = "tb_conta";

try {
    $conx = new PDO('mysql:host=' . $server . ';dbname=' . $banco, $user, $passwd);
    $conx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sel = $conx->prepare("SELECT TB_Funcionario_Id_Funcionario, grupo_perm_conta FROM $tabela WHERE Login_conta = ? AND Senha_conta = ?");

    $sel->execute([$username, $senha]);

    $user = $sel->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['Id_funcionario'] = $user['TB_Funcionario_Id_Funcionario'];

        if ($user['grupo_perm_conta'] == 1) {
            $_SESSION['nivelfunc'] = 'admin';
            header("Location: ../Aplicativo/pagadm.php");
        } else {
            $_SESSION['nivelfunc'] = 'normal';
            header("Location: ../Aplicativo/pagusuario.php");
        }
    } else {
        echo '<script>
            setTimeout(function() {
                window.location.href = "../Aplicativo/index.php";
            }, 5000); // Redireciona após 5 segundos
        </script>';
    }
} catch (PDOException $e) {
    echo 'ERRO: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="icon" href="../img/favicon.ico" type="image/x-icon">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0;
        }

        .error-container {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Erro</h1>
        <p>Nome de usuário ou senha inválidos. Redirecionando em 5 segundos...</p>
    </div>
</body>
</html>