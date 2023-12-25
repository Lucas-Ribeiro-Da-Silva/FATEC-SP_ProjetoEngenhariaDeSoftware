<?php
session_start();

if (!isset($_SESSION['nivelfunc']) || $_SESSION['nivelfunc'] !== 'admin') {
    header("Location: index.php");
    exit();
}


function sanitizeInput($conn, $input) {
    $input = trim($input);
    $input = $conn->real_escape_string($input);
    $input = htmlspecialchars($input, ENT_QUOTES);
    return $input;
}


if (isset($_GET['id'])) {
    $funcionario_id = $_GET['id'];


    $host = "localhost";
    $username = "root";
    $dbname = "Gestoquebd";
    $password = "";

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }


    $sql = "SELECT * FROM tb_conta WHERE TB_Funcionario_Id_Funcionario = $funcionario_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();


        $username = sanitizeInput($conn, $row["Login_conta"]); 
        $senha = sanitizeInput($conn, $row["Senha_conta"]); 
    } else {
        echo "Funcionário não encontrado.";
        exit();
    }

    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
        $novo_username = sanitizeInput($conn, $_POST['novo_username']);
        $nova_senha = sanitizeInput($conn, $_POST['nova_senha']);

    
        $update_sql = "UPDATE tb_conta SET Login_conta = '$novo_username', Senha_conta = '$nova_senha' WHERE TB_Funcionario_Id_Funcionario = $funcionario_id";

        if ($conn->query($update_sql) === TRUE) {
            header("Location: funcionarios.php");
        } else {
            echo "Erro na atualização: " . $conn->error;
        }
    }

    $conn->close();
} else {
    echo "ID do funcionário não especificado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Conta</title>
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
            max-width: 600px;
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
            Editar Conta
        </div>
        <div class="form">
            <form method="POST">
                <label for="novo_username">Nome de Usuário:</label>
                <input id="novo_username" type="text" name="novo_username" value="<?php echo $username; ?>" required />

                <label for="nova_senha">Nova Senha:</label>
                <input id="nova_senha" type="text" name="nova_senha" value="<?php echo $senha; ?>" required />

                <button type="submit">Salvar Alterações</button>
                <a href="funcionarios.php" class="btn-cancel">Cancelar Edição</a>
            </form>
        </div>
    </div>
</body>
</html>
