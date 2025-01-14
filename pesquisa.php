<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['add_to_cart'])){

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = $_POST['product_quantity'];

   $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

   if(mysqli_num_rows($check_cart_numbers) > 0){
      $message[] = 'já adicionado ao carrinho!';
   }else{
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
      $message[] = 'produto adicionado ao carrinho!';
   }

};

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>página de pesquisa</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'cabecalho.php'; ?>

<div class="heading">
   <h3>página de pesquisa</h3>
   <p> <a href="principal.php">página principal</a> / pesquisa </p>
</div>

<section class="search-form">
   <form action="" method="post">
      <input type="text" name="search" placeholder="pesquisar produtos..." class="box">
      <input type="submit" name="submit" value="pesquisar" class="btn">
   </form>
</section>

<section class="products" style="padding-top: 0;">

   <div class="box-container">
   <?php
      if (isset($_POST['submit'])) {
         $search_item = mysqli_real_escape_string($conn, $_POST['search']);
         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%$search_item%'") or die('query failed');
      if (mysqli_num_rows($select_products) > 0) {
      while ($fetch_product = mysqli_fetch_assoc($select_products)) {
         $stock = isset($fetch_product['quantity']) ? $fetch_product['quantity'] : 0;
   ?>
   <form action="" method="post" class="box">
      <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="" class="image">
      <div class="name"><?php echo $fetch_product['name']; ?></div>
      <div class="price">$<?php echo $fetch_product['price']; ?>/-</div>
      <div class="quantity">estoque: <?php echo $stock; ?></div>
      <input type="number" class="qty" name="product_quantity" min="1" max="<?php echo $stock; ?>" value="1" <?php if ($stock == 0) echo 'disabled'; ?>>
      <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
      <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
      <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
      <input type="submit" class="btn" value="adicionar ao carrinho" name="add_to_cart" <?php if ($stock == 0) echo 'disabled'; ?>>
   </form>
   <?php
            }
         }else{
            echo '<p class="empty">nenhum resultado encontrado!</p>';
         }
      }else{
         echo '<p class="empty">pesquise algo!</p>';
      }
   ?>
   </div>
  

</section>









<?php include 'rodape.php'; ?>

<script src="js/script.js"></script>

</body>
</html>