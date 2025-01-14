<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_stock_query = mysqli_query($conn, "SELECT quantity FROM `products` WHERE name = '$product_name'") or die('query failed');
   $product_data = mysqli_fetch_assoc($check_stock_query);

   if ($product_data['quantity'] >= $product_quantity) {
      $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

      if (mysqli_num_rows($check_cart_numbers) > 0) {
         $message[] = 'já adicionado ao carrinho!';
      } else {
         mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');

         $new_quantity = $product_data['quantity'] - $product_quantity;
         mysqli_query($conn, "UPDATE `products` SET quantity = '$new_quantity' WHERE name = '$product_name'") or die('query failed');

         $message[] = 'produto adicionado ao carrinho!';
        }
   } else {
      $message[] = 'estoque insuficiente!';
   }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'cabecalho.php'; ?>

<div class="heading">
   <h3>nossa loja</h3>
   <p> <a href="principal.php">página principal</a> / shop </p>
</div>

<section class="products">

   <h1 class="title">produtos mais recentes</h1>

   <div class="box-container">

      <?php  
         $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
      <form action="" method="post" class="box">
         <img class="image" src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="">
         <div class="name"><?php echo $fetch_products['name']; ?></div>
         <div class="price">$<?php echo $fetch_products['price']; ?>/-</div>
         <div class="stock">estoque: <?php echo $fetch_products['quantity']; ?></div>
         <input type="number" min="1" max="<?php echo $fetch_products['quantity']; ?>" name="product_quantity" value="1" class="qty">
         <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
         <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
         <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
         <input type="submit" value="adicionar ao carrinho" name="add_to_cart" class="btn">
      </form>
      <?php
         }
      }else{
         echo '<p class="empty">ainda não há produtos adicionados!</p>';
      }
      ?>
   </div>

</section>








<?php include 'rodape.php'; ?>

<script src="js/script.js"></script>

</body>
</html>