<?php

include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

function sanitize_input($data) {
   return htmlspecialchars(trim($data));
}

if(isset($_POST['order_btn'])){

   $name = sanitize_input(mysqli_real_escape_string($conn, $_POST['name']));
   $number = sanitize_input($_POST['number']);
   $email = sanitize_input(mysqli_real_escape_string($conn, $_POST['email']));
   $method = sanitize_input(mysqli_real_escape_string($conn, $_POST['method']));
   $address = mysqli_real_escape_string($conn, 'flat no. '. $_POST['flat'].', '. $_POST['street'].', '. $_POST['city'].', '. $_POST['country'].' - '. $_POST['pin_code']);
   $flat = sanitize_input($_POST['flat']);
   $street = sanitize_input($_POST['street']);
   $city = sanitize_input($_POST['city']);
   $state = sanitize_input($_POST['state']);
   $country = sanitize_input($_POST['country']);
   $pin_code = sanitize_input($_POST['pin_code']);   
   $age = sanitize_input($_POST['age']);
   $placed_on = date('d-M-Y');

   if (empty($name) || empty($number) || empty($email) || empty($flat) || empty($street) || empty($city) || empty($state) || empty($country) || empty($pin_code)) {
      $message[] = 'por favor, preencha todos os campos.';
   } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $message[] = 'email inválido!';
   } elseif (!preg_match('/^[0-9]{9,15}$/', $number)) {
      $message[] = 'número de telefone inválido!';
   } elseif ($age < 18) {
      $message[] = 'você precisa ter 18 anos ou mais para fazer um pedido.';
   } else {
      $address = mysqli_real_escape_string($conn, "flat no. $flat, $street, $city, $state, $country - $pin_code");

   $cart_total = 0;
   $cart_products[] = '';

   $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
   if(mysqli_num_rows($cart_query) > 0){
      while($cart_item = mysqli_fetch_assoc($cart_query)){
         $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
         $sub_total = ($cart_item['price'] * $cart_item['quantity']);
         $cart_total += $sub_total;
      }
   }

   $total_products = implode(', ',$cart_products);

   $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

   if($cart_total == 0){
      $message[] = 'seu carrinho está vazio';
   }else{
      if(mysqli_num_rows($order_query) > 0){
         $message[] = 'pedido já realizado!'; 
      }else{
         mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
         $message[] = 'pedido realizado com sucesso!';
         mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      }
   }
   } 
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'cabecalho.php'; ?>

<div class="heading">
   <h3>checkout</h3>
   <p> <a href="principal.php">página principal</a> / checkout </p>
</div>

<section class="display-order">

   <?php  
      $grand_total = 0;
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
         while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
   ?>
   <p> <?php echo $fetch_cart['name']; ?> <span>(<?php echo '$'.$fetch_cart['price'].'/-'.' x '. $fetch_cart['quantity']; ?>)</span> </p>
   <?php
      }
   }else{
      echo '<p class="empty">seu carrinho está vazio</p>';
   }
   ?>
   <div class="grand-total"> soma total : <span>$<?php echo $grand_total; ?>/-</span> </div>

</section>

<section class="checkout">

   <form action="" method="post">
      <h3>faça seu pedido</h3>
      <div class="flex">
         <div class="inputBox">
            <span>seu nome :</span>
            <input type="text" name="name" required placeholder="insira seu nome">
         </div>
         <div class="inputBox">
            <span>idade :</span>
            <input type="number" name="age" required placeholder="insira sua idade" min="18">
        </div>
         <div class="inputBox">
            <span>seu número :</span>
            <input type="number" name="number" required placeholder="insira seu número">
         </div>
         <div class="inputBox">
            <span>seu email :</span>
            <input type="email" name="email" required placeholder="insira seu email">
         </div>
         <div class="inputBox">
            <span>método de pagamento :</span>
            <select name="method">
               <option value="dinheiro na entrega">dinheiro na entrega</option>
               <option value="cartão de crédito">cartão de crédito</option>
               <option value="mbway">mbway</option>
               <option value="boleto">boleto</option>
            </select>
         </div>
         <div class="inputBox">
            <span>endereço :</span>
            <input type="text" name="street" required placeholder="ex. nome da rua">
         </div>
         <div class="inputBox">
            <span>complemento :</span>
            <input type="number" min="0" name="flat" required placeholder="ex. número do apartamento">
         </div>
         <div class="inputBox">
            <span>cidade :</span>
            <input type="text" name="city" required placeholder="ex. guarulhos">
         </div>
         <div class="inputBox">
            <span>estado :</span>
            <input type="text" name="state" required placeholder="ex. são paulo">
         </div>
         <div class="inputBox">
            <span>país :</span>
            <input type="text" name="country" required placeholder="ex. brasil">
         </div>
         <div class="inputBox">
            <span>cep :</span>
            <input type="number" min="0" name="pin_code" required placeholder="ex. 123456">
         </div>
      </div>
      <input type="submit" value="encomendar agora" class="btn" name="order_btn">
   </form>

</section>









<?php include 'rodape.php'; ?>

<script src="js/script.js"></script>

</body>
</html>