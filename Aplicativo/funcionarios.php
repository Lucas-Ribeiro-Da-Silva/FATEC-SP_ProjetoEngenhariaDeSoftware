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


$sql = "SELECT * FROM tb_funcionario";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Funcionários</title>
    <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../css/style.css">
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <style>
        
        .navigation {
            background: #333;
            padding: 10px;
            color: #fff;
        }

        .navigation a {
            text-decoration: none;
            color: #fff;
            margin-right: 10px;
        }

        .navigation a i {
            margin-right: 5px;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="navigation">
            <a href="pagadm.php"><i class='bx bx-home'></i> Home</a>
            <a href="logout.php"><i class='bx bx-log-out'></i> Sair da Conta</a>
        </div>

        <div class="header">
            <span>Cadastro de Funcionários</span>
            <button id="new">Incluir</button>
        </div>

        <div class="divTable">
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Cargo</th>
                        <th class="acao">Editar</th>
                        <th class="acao">Excluir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {

                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["Nome_Funcionario"] . "</td>";
                            echo "<td>" . $row["CPF_Funcionario"] . "</td>";
                            echo "<td>" . $row["Email_Funcionario"] . "</td>";
                            echo "<td>" . $row["Telefone_Funcionario"] . "</td>";
                            echo "<td>" . $row["Cargo_Funcionario"] . "</td>";

                            echo "<td class='acao'><a href='editar_func_p1.php?id=" . $row["Id_Funcionario"] . "'><i class='bx bx-edit' style='font-size: 24px;'></i></a></td>";
                            echo "<td class='acao'><a href='excluir_func.php?id=" . $row["Id_Funcionario"] . "'><i class='bx bx-trash' style='font-size: 24px;'></i></a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>Nenhum funcionário encontrado.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

          <div class="modal-container">
            <div class="modal">
                <form>
                    <label for="m-nome">Nome</label>
                    <input id="m-nome" type="text" required />
                    <label for=" ">CPF</label>
                    <input id=" " type="cpf" required />
                    <label for=" ">E-mail</label>
                    <input id=" " type="text" required />
                    <label for=" ">Telefone</label>
                    <input id=" " type="tel" required />
                    <label for="m-funcao">Cargo</label>
                    <input id="m-funcao" type="text" required />

                    <button id="btnSalvar">Salvar</button>
                </form>
            </div>
        </div>

    </div>
    <script src="./js/funcionariosscript.js"></script>
</body>

</html>

<script>
document.getElementById("new").addEventListener("click", function() {
    window.location.href = "incluir_func.php";
});
</script>
