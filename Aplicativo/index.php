<!DOCTYPE html>
<html lang="PT">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="icon" href="../img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css" rel="stylesheet">
</head>
<body>
  <div class="loader_bg">
    <div class="loader"><img src="../img/gif fematch.gif" alt="#" /></div>
  </div>
  <script>

  var startTime = performance.now();

 
  window.addEventListener("load", function() {

      var endTime = performance.now();
      var loadTime = endTime - startTime;


      console.log("Tempo de carregamento: " + loadTime + " ms");


      setTimeout(function() {
          var loaderBg = document.querySelector(".loader_bg");
          loaderBg.style.display = "none";
      }, 2000); 
  });
  </script>
  <div class="box">
    <div class="img-box">
      <img src="../img/fematchfundo.jpg" alt="Imagem de fundo">
    </div>
    <div class="form-box">
      <h2>Login</h2>
      <form action="../php/Login.php" method="POST">
        <div class="input-group">
          <label for="user">Username</label>
          <input type="text" name="user" id="user" placeholder="Digite o seu nome completo" required>
        </div>
        <div class="input-group">
          <label for="senha">Senha</label>
          <input type="password" name="senha" id="senha" placeholder="Digite sua senha">
        </div>
        <div class="input-group">
          <button type="submit">Login</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>