<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['send'])){

   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $number = $_POST['number'];
   $msg = mysqli_real_escape_string($conn, $_POST['message']);

   $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');

   if(mysqli_num_rows($select_message) > 0){
      $message[] = 'mensagem já enviada!';
   }else{
      mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES('$user_id', '$name', '$email', '$number', '$msg')") or die('query failed');
      $message[] = 'mensagem enviada com sucesso!';
   }

}

?>

<!DOCTYPE html>
<html lang="pt-bre">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contato</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'cabecalho.php'; ?>

<div class="heading">
   <h3>entre em contato conosco</h3>
   <p> <a href="principal.php">página principal</a> / contato </p>
</div>

<section class="contact">

   <form action="" method="post">
      <h3>diga alguma coisa!</h3>
      <input type="text" name="name" required placeholder="insira seu nome" class="box">
      <input type="email" name="email" required placeholder="insira seu email" class="box">
      <input type="number" name="number" required placeholder="insira seu número" class="box">
      <textarea name="message" class="box" placeholder="insira sua mensagem" id="" cols="30" rows="10"></textarea>
      <input type="submit" value="enviar mensagem" name="send" class="btn">
   </form>

</section>








<?php include 'rodape.php'; ?>

<script src="js/script.js"></script>

</body>
</html>