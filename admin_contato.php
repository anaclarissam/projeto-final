<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_contato.php');
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>mensagens</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_cabecalho.php'; ?>

<section class="messages">

   <h1 class="title"> mensagens </h1>

   <div class="box-container">
   <?php
      $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
      if(mysqli_num_rows($select_message) > 0){
         while($fetch_message = mysqli_fetch_assoc($select_message)){
      
   ?>
   <div class="box">
      <p> user id : <span><?php echo $fetch_message['user_id']; ?></span> </p>
      <p> nome : <span><?php echo $fetch_message['name']; ?></span> </p>
      <p> número : <span><?php echo $fetch_message['number']; ?></span> </p>
      <p> email : <span><?php echo $fetch_message['email']; ?></span> </p>
      <p> mensagem : <span><?php echo $fetch_message['message']; ?></span> </p>
      <a href="admin_contato.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('deletar essa mensagem?');" class="delete-btn">deletar mensagem</a>
   </div>
   <?php
      };
   }else{
      echo '<p class="empty">não há mensagens!</p>';
   }
   ?>
   </div>

</section>









<script src="js/admin_script.js"></script>

</body>
</html>