<?php

require_once '../bootstrap.php';

use Models\Book\BookCollection;
use Models\Book\BookModel;

if (isset($_POST['search'])) {
    $books = new BookCollection([], false);
    $books->getByAuthor($_POST['search']);
}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Search book by author</title>
    <style>
        body {
            text-align: center;
        }
        input {
            padding: 5px;
            border-radius: 5px;
            border: 1px solid gray;
        }
        table {
            border-collapse: collapse;
            margin: 20px auto;
        }
        th, td {
            padding: 10px;
            border: 1px solid lightgrey;
        }
    </style>
</head>
<body>
<form action="" method="post">
    <input type="text" name="search" id="search" placeholder="Type the name of the author">
    <input type="submit" name="submit" value="Search">
</form>
    <?php if(isset($books) && count($books) > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Book Name</th>
            <th>Author Name</th>
            <th>Last Modified</th>
        </tr>
        <?php /** @var BookModel $book */
        foreach ($books as $book): ?>
        <tr>
            <td><?= $book->getId() ?></td>
            <td><?= $book->getName() ?></td>
            <td><?= $book->getAuthor() ?></td>
            <td><?= $book->getDate() ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</body>
</html>
