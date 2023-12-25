<?php
session_start();


$host = "localhost";
$username = "root";
$dbname = "Gestoquebd";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

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


if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = sanitizeInput($conn, $_GET['search']);
    $sql = "SELECT * FROM tb_produtos WHERE Nome_Produtos LIKE '%$search%' OR Categoria_Produtos LIKE '%$search%' OR Marca_Produtos LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM tb_produtos";
}

$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $novo_nome = sanitizeInput($conn, $_POST['m-produto']);
    $novo_categoria = sanitizeInput($conn, $_POST['m-categoria']);
    $novo_preco = sanitizeInput($conn, $_POST['m-preco']);
    $novo_marca = sanitizeInput($conn, $_POST['m-marca']);
    $novo_quantidade = sanitizeInput($conn, $_POST['m-quantidade']);

 
    date_default_timezone_set('America/Sao_Paulo');

    $dataAtual = date('Y-m-d H:i:s');

   
    $insert_produto_sql = "INSERT INTO tb_produtos (Nome_Produtos, Categoria_Produtos, Preco_Produtos, Marca_Produtos, Qtd_produtos, Data_alt_produtos) VALUES ('$novo_nome', '$novo_categoria', '$novo_preco', '$novo_marca', '$novo_quantidade', '$dataAtual')";

    if ($conn->query($insert_produto_sql) === TRUE) {
        header("Location: estoquecrud.php");
    } else {
        echo "Erro na inclusão do produto: " . $conn->error;
        exit();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Estoque</title>
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
            <span>Cadastro de Estoque</span>
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

            <div id="buttons">
                <button onclick="openModal()" id="new">Incluir</button>
                <button onclick="downloadPDF()" id="new">Exportar PDF</button>
            </div>
        </div>


        <div class="divTable">
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Categoria</th>
                        <th>Quantidade</th>
                        <th>Preço</th>
                        <th>Marca</th>
                        <th class="acao">Data</th>
                        <th class="acao">Editar</th>
                        <th class="acao">Excluir</th>
                    </tr>
                </thead>
                <tbody>
                <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Nome_Produtos"] . "</td>";
                        echo "<td>" . $row["Categoria_Produtos"] . "</td>";
                        echo "<td>" . $row["Qtd_Produtos"] . "</td>";
                        echo "<td>" . $row["Preco_Produtos"] . "</td>";
                        echo "<td>" . $row["Marca_Produtos"] . "</td>";
                        echo "<td class='acao'>" . $row["Data_alt_Produtos"] . "</td>";
                        echo "<td class='acao'><a href='editar_produto.php?id=" . $row["Id_Produtos"] . "'> <i class='bx bx-edit' style='font-size: 24px;'></i></a></td>";
                        echo "<td class='acao'><a href='excluir_produto.php?id=" . $row["Id_Produtos"] . "'> <i class='bx bx-trash' style='font-size: 24px;'></i></a></td>";
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



        <div class="modal-container">
            <div class="modal">
                <form method="POST">
                    <label for="m-produto">Produto</label>
                    <input id="m-produto" type="text" name="m-produto" required />

                    <label for="m-categoria">Categoria</label>
                    <input id="m-categoria" type="text" name="m-categoria" required />

                    <label for="m-preco">Preço</label>
                    <input id="m-preco" type="text" name="m-preco" required pattern="^\d+(\.\d{1,2})?$"/>

                    <label for="m-marca">Marca</label>
                    <input id="m-marca" type="text" name="m-marca" required />

                    <label for="m-quantidade">Quantidade</label>
                    <input id="m-quantidade" type="number" name="m-quantidade" required />

                    <button type="submit">Salvar</button>
                </form>
            </div>
            </div>
    </div>

    
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../js/estoquescript.js"></script>
    <script src="../js/exportarExcel.js"></script>
    <script src="../js/exportarPDF.js"></script>
    
</body>
</html>
