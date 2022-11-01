<?php

/** @var $pdo PDO */
require_once "../../database.php";
require_once "../../function.php";

//echo '<pre>';
//var_dump($_FILES);
//echo '</pre>';


//echo $_SERVER['REQUEST_METHOD'];
$errors = [];
$title = '';
$description = '';
$price = '';
$product = [
        'image' => ''
];
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    require_once "../../validate_product.php";

    if(empty($errors)){

        $statement = $pdo->prepare("INSERT INTO products (title, image, description, price, create_date)
                    VALUES (:title, :image, :description, :price, :date)");
        $statement->bindVAlue(':title', $title);
        $statement->bindVAlue(':image', $imagePath);
        $statement->bindVAlue(':description', $description);
        $statement->bindVAlue(':price', $price);
        $statement->bindVAlue(':date', date('Y-m-d H:i:s'));
        $statement->execute();
        header('location: index.php');
    }

}


//echo '<pre>';
//var_dump($_SERVER);
//echo '</pre>'

//image=&title=One+plus&description=description&price=123

?>
<?php include_once "../../views/partials/header.php"; ?>

<p>
    <a href="index.php" class="btn btn-secondary">Go back to products</a>
</p>

<h1>Create New Product</h1>

<?php include_once "../../views/products/form.php"; ?>

</body>
</html>
