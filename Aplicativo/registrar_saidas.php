<?php

session_start();
if (!isset($_SESSION['nivelfunc']) || ($_SESSION['nivelfunc'] !== 'admin' && $_SESSION['nivelfunc'] !== 'normal')) {
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


if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM tb_saídas WHERE Data_saidas LIKE '%$search%' OR Qtd_saidas LIKE '%$search%' OR TB_Produtos_Id_Produtos LIKE '%$search%' OR TB_Funcionario_Id_Funcionario LIKE '%$search%'  ";
} else {
    $sql = "SELECT * FROM tb_saídas";
}

$result = $conn->query($sql);




$produto_id = "";
$quantidade_saida = "";
$mensagem = "";
$funcionario_id = $_SESSION['Id_funcionario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $produto_id = $_POST['produto_id'];
    $quantidade_saida = $_POST['quantidade_saida'];

    
    if (!is_numeric($quantidade_saida) || $quantidade_saida <= 0) {
        $mensagem = "A quantidade de saída deve ser um número positivo.";
    } else {
       
        $sqlVerificarEstoque = "SELECT Qtd_Produtos FROM tb_produtos WHERE Id_Produtos = $produto_id";
        $result = $conn->query($sqlVerificarEstoque);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $estoque_disponivel = $row["Qtd_Produtos"];

            if ($quantidade_saida > $estoque_disponivel) {
                $mensagem = "Quantidade de saída excede o estoque disponível.";
                header("refresh:2;url=registrar_saidas.php");
            } else {
                
                $novo_estoque = $estoque_disponivel - $quantidade_saida;
                $sqlAtualizarEstoque = "UPDATE tb_produtos SET Qtd_Produtos = $novo_estoque WHERE Id_Produtos = $produto_id";

               
                  
                
                    
                    date_default_timezone_set('America/Sao_Paulo');
                
                    $dataAtual = date('Y-m-d H:i:s');
                
                
                    $insert_produto_sql = "INSERT INTO tb_saídas (Data_saidas,	Qtd_saidas,	TB_Produtos_Id_Produtos, TB_Funcionario_Id_Funcionario) VALUES ('$dataAtual', '$quantidade_saida', '$produto_id', '$funcionario_id')";
                
                    if ($conn->query($insert_produto_sql) === TRUE) {
                        header("Location: registrar_saidas.php");
                    } else {
                        echo "Erro na inclusão do produto: " . $conn->error;
                        exit();
                    }
                
                    
                

                if ($conn->query($sqlAtualizarEstoque) === TRUE) {
                    $mensagem = "Saída registrada com sucesso. Estoque atualizado.";
                } else {
                    $mensagem = "Erro ao registrar a saída: " . $conn->error;
                }
            }
        } else {
            $mensagem = "Produto não encontrado no estoque.";
        }
    }
}


$sqlProdutos = "SELECT * FROM tb_produtos";
$resultProdutos = $conn->query($sqlProdutos);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Saída de Produtos</title>
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
        width: 90%;
        height: 80%;
        overflow-x: auto;
        overflow: -moz-hidden-unscrollable;
    }

    .navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #333;
        color: #fff;
        padding: 10px;
    }

    .navigation a {
        text-decoration: none;
        color: #fff;
        margin: 0 10px;
    }

    .navigation a i {
        margin-right: 5px;
    }

    .header {
        text-align: center;
        font-size: 24px;
        margin-bottom: 20px;
    }

    #toobar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 10px;
    }

    #search {
        display: flex;
        align-items: center;
    }

    #search h4 {
        margin: 0;
    }

    #search form {
        margin-left: 10px;
    }

    #search input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    #buttons {
        display: flex;
        align-items: center;
    }

    #buttons button {
        margin-left: 10px;
    }

    .divTable table {
        width: 100%;
        border-collapse: collapse;
    }

    .divTable table,
    .divTable th,
    .divTable td {
        border: 1px solid #ddd;
    }

    .divTable th,
    .divTable td {
        padding: 10px;
        text-align: left;
    }

    .divTable th {
        background-color: #f2f2f2;
    }

    table {
        width: 100%;
    }

    .search-form {
    display: flex;
}

#search-input {
    flex: 1;
}

.search-button {
    margin-left: 10px;
}

</style>
    
</head>
<body>
    <div class="container">
        <div class="navigation">
            <a href="<?php echo ($_SESSION['nivelfunc'] === 'admin') ? 'pagadm.php' : 'pagusuario.php'; ?>"><i class='bx bx-home'></i> Home</a>
            <span>Registrar Saídas</span>
            <a href="logout.php"><i class='bx bx-log-out'></i> Sair da Conta</a>
        </div>


        <div id="toobar">
        <div id="search">
            <h4>Pesquisar:</h4>
            <form method="GET" class="search-form">
                <input type="text" name="search" id="search-input" placeholder="Buscar...">
                <input type="submit" value="Buscar" id="new" class="search-button">
            </form>
        </div>
            <form method="POST">
                    <label for="produto_id">Selecione o Produto:</label>
                    <select name="produto_id" id="produto_id" required>
                        <option value="">Selecione um Produto</option>
                        <?php
                        while ($rowProduto = $resultProdutos->fetch_assoc()) {
                            echo "<option value='" . $rowProduto["Id_Produtos"] . "'>" . $rowProduto["Nome_Produtos"] . "</option>";
                        }
                        ?>
                    </select>

                    <label for="quantidade_saida">Quantidade de Saída:</label>
                    <input type="number" name="quantidade_saida" id="quantidade_saida" required>

                    <button type="submit" id="new">Registrar Saída</button>
                </form>

                <div class="mensagem">
                    <?php echo $mensagem; ?>
                </div>
        </div>

        <div class="divTable">
            <table>
                <thead>
                    <tr>
                        <th>Data Saídas</th>
                        <th>Qtd Saídas</th>
                        <th>ID produto</th>
                        <th>ID Funcionário</th>
                        
                    </tr>
                </thead>
                <tbody>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Data_saidas"] . "</td>";
                        echo "<td>" . $row["Qtd_saidas"] . "</td>";
                        echo "<td>" . $row["TB_Produtos_Id_Produtos"] . "</td>";
                        echo "<td>" . $row["TB_Funcionario_Id_Funcionario"] . "</td>";
                       
                    
                        
                        echo "</tr>";
                    }
                } else {
                    echo "Nenhum produto encontrado.";
                }
                ?>
            </tbody>
                </tbody>
            </table>
        </div>

                
    </div>
</body>
</html>
