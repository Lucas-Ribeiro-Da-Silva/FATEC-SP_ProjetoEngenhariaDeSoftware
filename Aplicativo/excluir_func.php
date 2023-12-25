<?php
require('../php/conectarbd.php');

$id_funcionario = $_GET['id'];

try {
    $conn->beginTransaction();

    
    $check_conta = $conn->prepare("SELECT grupo_perm_conta FROM tb_conta WHERE TB_Funcionario_Id_Funcionario = :id_funcionario");
    $check_conta->bindParam(':id_funcionario', $id_funcionario);
    $check_conta->execute();
    $grupo_perm_conta = $check_conta->fetchColumn();

    if ($grupo_perm_conta != 1) {
        $del_conta = $conn->prepare("DELETE FROM tb_conta WHERE TB_Funcionario_Id_Funcionario = :id_funcionario");
        $del_conta->bindParam(':id_funcionario', $id_funcionario);
        $del_conta->execute();
    } else {
   
    }

    $del_funcionario = $conn->prepare("DELETE FROM tb_funcionario WHERE Id_Funcionario = :id_funcionario");
    $del_funcionario->bindParam(':id_funcionario', $id_funcionario);
    $del_funcionario->execute();


    $conn->commit();

    $msg = "Registros excluídos com sucesso!";
    $_SESSION['msg'] = $msg;
    echo "<script>alert('Funcionario excluido ');</script>";
    header('Location: funcionarios.php');
    exit;
} catch (PDOException $e) {
    $conn->rollBack();

    $msgErr = "Erro ao excluir os registros: " . $e->getMessage();
    $_SESSION['msgErr'] = $msgErr;
    echo "<script>alert('Não é possivel excluir um administrador');</script>";
    header('Location: funcionarios.php');
    exit;
}
?>
