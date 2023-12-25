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


    $sql = "SELECT * FROM tb_funcionario WHERE Id_Funcionario = $funcionario_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        $nome = sanitizeInput($conn, $row["Nome_Funcionario"]);
        $cpf = sanitizeInput($conn, $row["CPF_Funcionario"]);
        $email = sanitizeInput($conn, $row["Email_Funcionario"]);
        $telefone = sanitizeInput($conn, $row["Telefone_Funcionario"]);
        $cargo = sanitizeInput($conn, $row["Cargo_Funcionario"]);
    } else {
        echo "Funcionário não encontrado.";
        exit();
    }


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $novo_nome = sanitizeInput($conn, $_POST['novo_nome']);
        $novo_cpf = sanitizeInput($conn, $_POST['novo_cpf']);
        $novo_email = sanitizeInput($conn, $_POST['novo_email']);
        $novo_telefone = sanitizeInput($conn, $_POST['novo_telefone']);
        $novo_cargo = sanitizeInput($conn, $_POST['novo_cargo']);

        $update_sql = "UPDATE tb_funcionario SET Nome_Funcionario = '$novo_nome', CPF_Funcionario = '$novo_cpf', Email_Funcionario = '$novo_email', Telefone_Funcionario = '$novo_telefone', Cargo_Funcionario = '$novo_cargo' WHERE Id_Funcionario = $funcionario_id";

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
    <title>Editar Funcionário</title>
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
            Editar Funcionário
        </div>
        <div class="form">
            <form method="POST">
                <label for="novo_nome">Nome:</label>
                <input id="novo_nome" type="text" name="novo_nome" value="<?php echo $nome; ?>" required />

                <label for="novo_cpf">CPF:</label>
                <input id="novo_cpf" type="text" name="novo_cpf" value="<?php echo $cpf; ?>" required pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" placeholder="123.456.789-01"  />

                <label for="novo_email">E-mail:</label>
                <input id="novo_email" type="email" name="novo_email" value="<?php echo $email; ?>" required />

                <label for="novo_telefone">Telefone:</label>
                <input id="novo_telefone" type="text" name="novo_telefone" value="<?php echo $telefone; ?>" required pattern="\(\d{2}\) \d{5}-\d{4}" placeholder="(99) 99999-9999" />

                <label for="novo_cargo">Cargo:</label>
                <input id="novo_cargo" type="text" name="novo_cargo" value="<?php echo $cargo; ?>" required />

                <button type="submit">Salvar Alterações</button>
                <a href="editar_func_p2.php?id=<?php echo $funcionario_id; ?>" class="edit-account-button">Editar Conta</a>
                <a href="funcionarios.php" class="btn-cancel">Cancelar Edição</a>
            </form>
            
        </div>
    </div>
</body>
</html>
