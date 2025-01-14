<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>pedidos</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'cabecalho.php'; ?>

<div class="heading">
   <h3>seus pedidos</h3>
   <p> <a href="principal.php">página principal</a> / pedidos </p>
</div>

<section class="placed-orders">

   <h1 class="title">pedidos feitos</h1>

   <div class="box-container">

      <?php
         $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die('query failed');
         if(mysqli_num_rows($order_query) > 0){
            while($fetch_orders = mysqli_fetch_assoc($order_query)){
      ?>
      <div class="box">
         <p> efetuado em : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
         <p> nome : <span><?php echo $fetch_orders['name']; ?></span> </p>
         <p> número : <span><?php echo $fetch_orders['number']; ?></span> </p>
         <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> endereço : <span><?php echo $fetch_orders['address']; ?></span> </p>
         <p> forma de pagamento : <span><?php echo $fetch_orders['method']; ?></span> </p>
         <p> seus pedidos : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
         <p> preço total : <span>$<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
         <p> estado de pagamento : <span style="color:<?php if($fetch_orders['payment_status'] == 'pendente'){ echo 'red'; }else{ echo 'green'; } ?>;"><?php echo $fetch_orders['payment_status']; ?></span> </p>
         </div>
      <?php
       }
      }else{
         echo '<p class="empty">nenhum pedido foi feito ainda!</p>';
      }
      ?>
   </div>

</section>








<?php include 'rodape.php'; ?>

<script src="js/script.js"></script>

</body>
</html>