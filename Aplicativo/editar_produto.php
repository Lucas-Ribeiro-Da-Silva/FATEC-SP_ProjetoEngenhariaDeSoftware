<?php
session_start();
if (!isset($_SESSION['nivelfunc']) || ($_SESSION['nivelfunc'] !== 'admin' && $_SESSION['nivelfunc'] !== 'normal')) {
    header("Location: index.php");
    exit();
}


function sanitizeInput($conn, $input) {

    $input = trim($input);

    $input = $conn->real_escape_string($input);

    $input = htmlspecialchars($input, ENT_QUOTES);
    return $input;
}


$host = "localhost";
$username = "root";
$dbname = "Gestoquebd";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}


if (isset($_GET['id'])) {
    $produto_id = $_GET['id'];

 
    $sql = "SELECT * FROM tb_produtos WHERE Id_Produtos = $produto_id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();


        $nome = sanitizeInput($conn, $row["Nome_Produtos"]);
        $categoria = sanitizeInput($conn, $row["Categoria_Produtos"]);
        $preco = sanitizeInput($conn, $row["Preco_Produtos"]);
        $marca = sanitizeInput($conn, $row["Marca_Produtos"]);
        $quantidade = sanitizeInput($conn, $row["Qtd_Produtos"]);
    } else {
        echo "Produto não encontrado.";
        exit();
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome = sanitizeInput($conn, $_POST['m-produto']);
    $novo_categoria = sanitizeInput($conn, $_POST['m-categoria']);
    $novo_preco = sanitizeInput($conn, $_POST['m-preco']);
    $novo_marca = sanitizeInput($conn, $_POST['m-marca']);
    $novo_quantidade = sanitizeInput($conn, $_POST['m-quantidade']);

    
    date_default_timezone_set('America/Sao_Paulo');
    $dataAtual = date('Y-m-d H:i:s');

    
    $update_sql = "UPDATE tb_produtos SET Nome_Produtos = '$novo_nome', Categoria_Produtos = '$novo_categoria', Preco_Produtos = '$novo_preco', Marca_Produtos = '$novo_marca', Qtd_Produtos = '$novo_quantidade', Data_alt_produtos = '$dataAtual' WHERE Id_Produtos = $produto_id";

    if ($conn->query($update_sql) === TRUE) {
        header("Location: estoquecrud.php");
    } else {
        echo "Erro na atualização do produto: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            width: 100%;
            max-width: 500px;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input {
            width: 95%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .btn-save {
            flex: 1;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
        }
        .btn-cancel {
            flex: 1;
            background-color: #ccc;
            color: #000;
            border: none;
            border-radius: 5px;
            padding: 10px;
            cursor: pointer;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Produto</h1>
        <form method="POST">
            <label for="m-produto">Produto</label>
            <input id="m-produto" type="text" name="m-produto" value="<?php echo $nome; ?>" required />

            <label for="m-categoria">Categoria</label>
            <input id="m-categoria" type="text" name="m-categoria" value="<?php echo $categoria; ?>" required />

            <label for="m-preco">Preço</label>
            <input id="m-preco" type="text" name="m-preco" value="<?php echo $preco; ?>" required pattern="^\d+(\.\d{1,2})?$" />

            <label for="m-marca">Marca</label>
            <input id="m-marca" type="text" name="m-marca" value="<?php echo $marca; ?>" required />

            <label for="m-quantidade">Quantidade</label>
            <input id="m-quantidade" type="number" name="m-quantidade" value="<?php echo $quantidade; ?>" required />

            <div class="btn-container">
                <button type="submit" class="btn-save">Salvar</button>
                <a href="estoquecrud.php" class="btn-cancel">Cancelar Edição</a>
            </div>
        </form>
    </div>
</body>
</html>

