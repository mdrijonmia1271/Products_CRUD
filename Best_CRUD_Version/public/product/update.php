<?php
/** @var $pdo PDO */
require_once "../../database.php";
require_once "../../function.php";

$id = $_GET['id'] ?? null;
if (!$id){
    header('Location: index.php');
    exit();
}
$statement = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$statement->bindValue(':id', $id);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);

//echo '<pre>';
//var_dump($product);
//echo '</pre>';
//exit();

//echo $_SERVER['REQUEST_METHOD'];
$errors = [];
$title = $product['title'];
$description = $product['description'];
$price = $product['price'];
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    require_once "../../validate_product.php";

    if(empty($errors)){

        $statement = $pdo->prepare("UPDATE products SET title = :title, image = :image, description = :description,
                                           price = :price WHERE id = :id");
        $statement->bindVAlue(':title', $title);
        $statement->bindVAlue(':image', $imagePath);
        $statement->bindVAlue(':description', $description);
        $statement->bindVAlue(':price', $price);
        $statement->bindVAlue(':id', $id);
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
<h1>Update Product <b><?php echo $product['title'] ?></b></h1>

<?php include_once "../../views/products/form.php"; ?>

</body>
</html>


