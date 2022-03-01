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

if (isset($_POST['update_cart'])) {
    $update_quantity = $_POST['cart_quantity'];
    $update_id = $_POST['cart_id'];
    mysqli_query($conn, "UPDATE `cart` SET quantity = '$update_quantity' WHERE id = '$update_id'") or die('query failed no update');
    $message[] = 'quantidade atualizada com sucesso!';
};

if (isset($_POST['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `cart` WHERE id = '$remove_id'") or die('query failed ao remover');
    header('location: index.php');
};

if (isset($_POST['delete_all'])) {
    mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed ao deletar tudo');
    header('location: index.php');
};

?>

<!DOCTYPE html>
<html lang="en">

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
            echo '<div class="message" onclick="this.remove();">' . $message . '</div>';
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
                            <div class="price">R$<?php echo $fetch_product['price']; ?></div>
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
                <tbody>
                    <?php
                    $grand_total = 0;
                    $cart_query = mysqli_query($conn, "SELECT * FROM  `cart`
                WHERE user_id = '$user_id'") or die('query failed');
                    if (mysqli_num_rows($cart_query) > 0) {
                        while ($fetch_cart = mysqli_fetch_assoc($cart_query)) {
                    ?>
                            <tr>
                                <td>
                                    <img src="img/<?php echo $fetch_cart['image']; ?>" height="100" alt="">
                                </td>
                                <td><?php echo $fetch_cart['name']; ?></td>
                                <td><?php echo $fetch_cart['price']; ?></td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['quantity']; ?>">
                                        <input type="number" value="<?php echo $fetch_cart['quantity']; ?>" min="1" name="cart_quantity" id="">
                                        <input type="submit" value="adicionar" name="update_cart" class="option-btn">
                                    </form>
                                </td>
                                <td>R$ <?php echo $sub_total = number_format((int)$fetch_cart['price'] * (int)$fetch_cart['quantity']); ?></td>
                                <td>
                                    <a href="index.php?remove=<?php echo $fetch_cart['id']; ?>" class="delete-btn" onclick="return confirm('remover ítem do carrinho?')">remover</a>
                                </td>
                            </tr>

                    <?php
                            (float)$grand_total += (float)$sub_total;
                        };
                    } else {
                        echo '<tr><td style="padding:20px; text-transform=capitalize;" colspan="6">sem item adicionado</td></tr>';
                    };
                    ?>
                    <tr class="table-bottom">
                        <td colspan="4">total geral : </td>
                        <td>R$ <?php echo $grand_total; ?></td>
                        <td>
                            <a href="index.php?delete_all" onclick="return confirm('limpar o carrinho?')" class="delete-btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?>">apagar tudo</a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="cart-btn">
                <a href="#" class="btn <?php echo ($grand_total > 1) ? '' : 'disabled'; ?> ">finalizar compra</a>
            </div>
        </div>

    </div>


    <script src="js/script.js"></script>
</body>

</html>