<?php
include 'pages/config.php';

session_start();

$user_id = $_SESSION['user_id'];

if (!isset($user_id)) {
    header('location:pages/login.php');
};

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:pages/login.php');
};

if (isset($_POST['add_to_cart'])) {

    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = $_POST['product_quantity'];

    $select_cart = mysqli_query(
        $conn,
        "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = 'user_id' "
    ) or die('query failed');

    if (mysqli_num_rows($select_cart) > 0) {
        $message[] = 'produtop já adicionado ao carrinho';
    } else {
        mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, image, quantity)
        VALUES('$user_id', '$product_name','$product_price','$product_image','$product_quantity')
        ") or die('query failed no carrinho');
        $message[] = 'produto adicionado ao carrinho!';
    }
};

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Loja On Line</title>
    <link rel="shortcut icon" type="img/x-icon" href="./img/kalangos.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" />
    <link rel="stylesheet" href="css/style.css" />

</head>

<body>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
        }
    }
    ?>
    <div class="container">
        <div class="user-profile">
            <?php
            $select_user = mysqli_query($conn, "SELECT * FROM  `user_info`
                WHERE id = '$user_id'") or die('query failed');
            if (mysqli_num_rows($select_user) > 0) {
                $fetch_user = mysqli_fetch_assoc($select_user);
            };
            ?>

            <p>usuário : <span><?php echo $fetch_user['name']; ?></span></p>
            <p>email : <span><?php echo $fetch_user['email']; ?></span></p>
            <div class="flex">
                <a href="pages/login.php" class="btn">login</a>
                <a href="pages/registro.php" class="option-btn">cadastre-se</a>
                <a href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('tem certeza que deseja sair?')" class="delete-btn">sair</a>
            </div>
        </div>

        <div class="products">
            <h1 class="heading">produtos recentes</h1>
            <div class="box-container">
                <?php
                $select_product = mysqli_query($conn, "SELECT * FROM  `products`")
                    or die('query failed');
                if (mysqli_num_rows($select_product) > 0) {
                    while ($fetch_product = mysqli_fetch_assoc($select_product)) {
                ?>
                        <form action="" method="post" class="box">
                            <img src="img/<?php echo $fetch_product['image']; ?>" alt="">
                            <div class="name"><?php echo $fetch_product['name']; ?></div>
                            <div class="price">R$<?php echo $fetch_product['price']; ?>/-</div>
                            <input type="number" name="product_quantity" min="1" id="" value="1">
                            <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
                            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                            <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
                            <input type="submit" value="adicione ao carrinho" name="add_to_cart" class="btn">
                        </form>
                <?php
                    };
                };

                ?>
            </div>
        </div>
        <div class="shopping-cart">
            <h1 class="heading">carrinho de compra</h1>
            <table>
                <thead>
                    <th>imagem</th>
                    <th>nome</th>
                    <th>preço</th>
                    <th>quantidade</th>
                    <th>valor total</th>
                    <th>ação</th>
                </thead>
            </table>
        </div>

    </div>


    <script src="js/script.js"></script>
</body>

</html>