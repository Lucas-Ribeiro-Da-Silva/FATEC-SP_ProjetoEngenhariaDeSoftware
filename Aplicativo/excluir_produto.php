<?php
session_start();
if (!isset($_SESSION['nivelfunc']) || ($_SESSION['nivelfunc'] !== 'admin' && $_SESSION['nivelfunc'] !== 'normal')) {
    header("Location: index.php"); 
    exit();
}
require('../php/conectarbd.php');

$id_produto = $_GET['id'];

try {
    $conn->beginTransaction();


    $verifica_produto = $conn->prepare("SELECT * FROM tb_produtos WHERE Id_Produtos = :id_produto");
    $verifica_produto->bindParam(':id_produto', $id_produto);
    $verifica_produto->execute();
    
    if ($verifica_produto->rowCount() > 0) {
        $del_produto = $conn->prepare("DELETE FROM tb_produtos WHERE Id_Produtos = :id_produto");
        $del_produto->bindParam(':id_produto', $id_produto);
        $del_produto->execute();
    
        $conn->commit();
    
        $msg = "Produto excluído com sucesso!";
        $_SESSION['msg'] = $msg;
        echo "<script>alert('Produto excluído ');</script>";
        header('Location: estoquecrud.php');
        exit;
    } else {
        $conn->rollback();
        $msgErr = "O produto não foi encontrado.";
        $_SESSION['msgErr'] = $msgErr;
        echo "<script>alert('Produto não encontrado');</script>";
        header('Location: estoquecrud.php');
        exit;
    }
} catch (PDOException $e) {
    $conn->rollBack();

    $msgErr = "Erro ao excluir o produto: " . $e->getMessage();
    $_SESSION['msgErr'] = $msgErr;
    echo "<script>alert('Erro ao excluir o produto');</script>";
    header('Location: estoquecrud.php');
    exit;
}
?>
