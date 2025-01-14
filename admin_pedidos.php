<?php

include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

if(isset($_POST['update_order'])){

   $order_update_id = $_POST['order_id'];
   $update_payment = $_POST['update_payment'];
   mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
   $message[] = 'o status do pagamento foi atualizado!';

}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
   header('location:admin_pedidos.php');
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

   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_cabecalho.php'; ?>

<section class="orders">

   <h1 class="title">pedidos feitos</h1>

   <div class="box-container">
      <?php
      $select_orders = mysqli_query($conn, "SELECT * FROM `orders`") or die('query failed');
      if(mysqli_num_rows($select_orders) > 0){
         while($fetch_orders = mysqli_fetch_assoc($select_orders)){
      ?>
      <div class="box">
         <p> user id : <span><?php echo $fetch_orders['user_id']; ?></span> </p>
         <p> efetuado em : <span><?php echo $fetch_orders['placed_on']; ?></span> </p>
         <p> nome : <span><?php echo $fetch_orders['name']; ?></span> </p>
         <p> número : <span><?php echo $fetch_orders['number']; ?></span> </p>
         <p> email : <span><?php echo $fetch_orders['email']; ?></span> </p>
         <p> endereço : <span><?php echo $fetch_orders['address']; ?></span> </p>
         <p> produtos totais : <span><?php echo $fetch_orders['total_products']; ?></span> </p>
         <p> preço total : <span>$<?php echo $fetch_orders['total_price']; ?>/-</span> </p>
         <p> método de pagamento : <span><?php echo $fetch_orders['method']; ?></span> </p>
         <form action="" method="post">
            <input type="hidden" name="order_id" value="<?php echo $fetch_orders['id']; ?>">
            <select name="update_payment">
               <option value="" selected disabled><?php echo $fetch_orders['payment_status']; ?></option>
               <option value="pendente">pendente</option>
               <option value="concluído">concluído</option>
            </select>
            <input type="submit" value="update" name="update_order" class="option-btn">
            <a href="admin_pedidos.php?delete=<?php echo $fetch_orders['id']; ?>" onclick="return confirm('deletar esse pedido?');" class="delete-btn">deletar</a>
         </form>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">nenhum pedido foi feito ainda!</p>';
      }
      ?>
   </div>

</section>










<script src="js/admin_script.js"></script>

</body>
</html>