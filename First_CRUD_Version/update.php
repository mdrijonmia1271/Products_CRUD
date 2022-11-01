<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud','root','');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    if(!$title){
        $errors [] = 'Product title is required';
    }
    if(!$price){
        $errors [] = 'Producr price is required';
    }
    if (!is_dir('images')){
        mkdir('images');
    }

    if(empty($errors)){
        $image = $_FILES ['image'] ?? null;
        $imagePath = $product['image'];


        if ($image && $image['tmp_name']){

            if ($product['image']){
                unlink($product['image']);
            }
            $imagePath = 'images/'.randomString(8).'/'.$image['name'];
            mkdir(dirname($imagePath));
            move_uploaded_file($image['tmp_name'], $imagePath);
        }
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
function randomString($n){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i = 0; $i < $n; $i++){
        $index = rand(0, strlen($characters) - 1);
        $str .= $characters[$index];
    }
    return $str;
}

//echo '<pre>';
//var_dump($_SERVER);
//echo '</pre>'

//image=&title=One+plus&description=description&price=123

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
    <title>Bootstrap demo</title>
</head>
<body>
<p>
    <a href="index.php" class="btn btn-secondary">Go back to products</a>
</p>
<h1>Update Product <b><?php echo $product['title'] ?></b></h1>
<?php if(!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>
            <div><?php echo $error ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<form action="" method="post" enctype="multipart/form-data">

    <?php if ($product['image']): ?>
        <img src="<?php echo $product['image']; ?>" class="update-image">
    <?php endif; ?>

    <div class="form-group">
        <label>Product Image</label>
        <br>
        <input type="file" name="image">
    </div>
    <br>
    <div class="form-group">
        <label>Product Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo $title ?>">
    </div>
    <div class="form-group">
        <label>Product Description</label>
        <textarea name="description" class="form-control"><?php echo $description ?></textarea>
    </div>
    <div class="form-group">
        <label>Product Price</label>
        <input type="number" step=".01" name="price" value="<?php echo $price ?>" class="form-control">
    </div>
    <br>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

</body>
</html>


