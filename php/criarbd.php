<?php
$host = "localhost";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $dbName = "GestoqueBD";
    $sql = "CREATE DATABASE IF NOT EXISTS $dbName";
    $pdo->exec($sql);
    echo "Banco de dados 'GestoqueBD' criado com sucesso!<br>";

    $pdo->exec("USE $dbName");

    $sql = "CREATE TABLE IF NOT EXISTS TB_Funcionario (
        Id_Funcionario INT NOT NULL AUTO_INCREMENT,
        Nome_Funcionario VARCHAR(45) NOT NULL,
        CPF_Funcionario VARCHAR(14) NOT NULL,
        Email_Funcionario VARCHAR(100) NOT NULL,
        Telefone_Funcionario VARCHAR(15) NOT NULL,
        Cargo_Funcionario TINYTEXT NOT NULL,
        PRIMARY KEY (Id_Funcionario))";

    $pdo->exec($sql);
     echo "Tabela 'TB_Funcionario' criada com sucesso!<br>";

  $sql = "CREATE TABLE IF NOT EXISTS `Tb_Conta` (
    `ID_Conta` INT NOT NULL AUTO_INCREMENT,
    `Login_conta` VARCHAR(45) NOT NULL,
    `Senha_conta` VARCHAR(45) NOT NULL,
    `grupo_perm_conta` TINYINT NOT NULL,
    `TB_Funcionario_Id_Funcionario` INT NOT NULL,
    PRIMARY KEY (`ID_Conta`),
    INDEX `fk_Tb_Permissões_TB_Funcionario_idx` (`TB_Funcionario_Id_Funcionario`),
    CONSTRAINT `fk_Tb_Permissões_TB_Funcionario`
      FOREIGN KEY (`TB_Funcionario_Id_Funcionario`)
      REFERENCES `TB_Funcionario` (`Id_Funcionario`)
      ON DELETE NO ACTION
      ON UPDATE NO ACTION
    ) ENGINE = InnoDB";

$pdo->exec($sql);
  echo "Tabela 'Tb_Conta' criada com sucesso!<br>";

  $sql = "CREATE TABLE IF NOT EXISTS `TB_Produtos` (
    `Id_Produtos` INT NOT NULL AUTO_INCREMENT,
    `Nome_Produtos` VARCHAR(100) NOT NULL,
    `Categoria_Produtos` VARCHAR(45) NOT NULL,
    `Preco_Produtos` DECIMAL(10,2) NOT NULL,
    `Marca_Produtos` VARCHAR(45) NOT NULL,
    `Qtd_Produtos` INT NULL,
    `Data_alt_Produtos` VARCHAR(45) NULL,
    PRIMARY KEY (`Id_Produtos`)
    ) ENGINE = InnoDB;";
    $pdo->exec($sql);
  echo "Tabela 'TB_Produtos' criada com sucesso!<br>";

  $sql = "CREATE TABLE IF NOT EXISTS `TB_Saídas` (
    `id_Saídas` INT NOT NULL AUTO_INCREMENT,
    `Data_saidas` VARCHAR(45) NULL,
    `Qtd_saidas` INT NULL,
    `TB_Produtos_Id_Produtos` INT NOT NULL,
    `TB_Funcionario_Id_Funcionario` INT NOT NULL,
    PRIMARY KEY (`id_Saídas`, `TB_Produtos_Id_Produtos`, `TB_Funcionario_Id_Funcionario`),
    INDEX `fk_TB_Saídas_TB_Produtos1_idx` (`TB_Produtos_Id_Produtos` ASC),
    INDEX `fk_TB_Saídas_TB_Funcionario1_idx` (`TB_Funcionario_Id_Funcionario` ASC),
    CONSTRAINT `fk_TB_Saídas_TB_Produtos1`
      FOREIGN KEY (`TB_Produtos_Id_Produtos`)
      REFERENCES `TB_Produtos` (`Id_Produtos`)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
    CONSTRAINT `fk_TB_Saídas_TB_Funcionario1`
      FOREIGN KEY (`TB_Funcionario_Id_Funcionario`)
      REFERENCES `TB_Funcionario` (`Id_Funcionario`)
      ON DELETE CASCADE
      ON UPDATE CASCADE
  ) ENGINE = InnoDB;";
  $pdo->exec($sql);
  echo "Tabela 'TB_Saídas' criada com sucesso!<br>";


  $sql = "INSERT INTO `TB_Funcionario` (`Nome_Funcionario`, `CPF_Funcionario`, `Email_Funcionario`, `Telefone_Funcionario`, `Cargo_Funcionario`) VALUES ('Admin', '000.000.000-00', 'Admin@email.com', '(00) 00000-0000', 'Admin')";
  $pdo->exec($sql);

  $sql = "INSERT INTO `Tb_Conta` (`Login_conta`, `Senha_conta`, `grupo_perm_conta`, `TB_Funcionario_Id_Funcionario`) VALUES ('admin', 'admin', '1', '1')";
  $pdo->exec($sql);

    $pdo = null;
} catch (PDOException $e) {
    die("Erro ao criar o banco de dados: " . $e->getMessage());
}
?>