<?php
// Connection configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'perpus';


$conn = new mysqli($host, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


function getBooks($conn) {
    $sql = "SELECT * FROM buku";
    $result = $conn->query($sql);

    $books = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }
    }
    return $books;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['add_book'])) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $published_year = $_POST['published_year'];
        $isbn = $_POST['isbn'];

        $sql = "INSERT INTO buku (title, author, published_year, isbn) VALUES ('$title', '$author', '$published_year', '$isbn')";
        $conn->query($sql);
    }

    
    elseif (isset($_POST['edit_book'])) {
        $id = $_POST['book_id'];
        $sql = "SELECT * FROM buku WHERE id=$id";
        $result = $conn->query($sql);
        $book_to_edit = $result->fetch_assoc();
    }

    
    elseif (isset($_POST['update_book'])) {
        $id = $_POST['edit_book_id'];
        $title = $_POST['edit_title'];
        $author = $_POST['edit_author'];
        $published_year = $_POST['edit_published_year'];
        $isbn = $_POST['edit_isbn'];

        $sql = "UPDATE buku SET title='$title', author='$author', published_year='$published_year', isbn='$isbn' WHERE id=$id";
        $conn->query($sql);
    }

   
    elseif (isset($_POST['delete_book'])) {
        $id = $_POST['book_id'];
        $sql = "DELETE FROM buku WHERE id=$id";
        $conn->query($sql);
    }
}


$books = getBooks($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <h1>List Buku</h1>
    <ul>
        <?php foreach ($books as $book): ?>
            <li>
                <?= $book['title'] ?> by <?= $book['author'] ?> (<?= $book['published_year'] ?>) - ISBN: <?= $book['isbn'] ?>
                <form class="edit-book-form" method="post" action="">
                    <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                    <input type="submit" class="edit-button" name="edit_book" value="Edit">
                    <input type="submit" class="delete-button" name="delete_book" value="Delete">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    
    <h2>Tambahkan Buku Baru</h2>
    <form class="add-book-form" method="post" action="">
        <label for="judul">Judul:</label>
        <input type="text" name="title" required><br>
        <label for="penulis">Penulis:</label>
        <input type="text" name="author" required><br>
        <label for="tahun_terbit">Tahun Terbit:</label>
        <input type="text" name="published_year" required><br>
        <label for="isbn">ISBN:</label>
        <input type="text" name="isbn" required><br>
        <input type="submit" class="add-book-button" name="add_book" value="Add Book">
    </form>

    
    <?php if (isset($book_to_edit)): ?>
        <h2>Edit Book</h2>
        <form class="edit-book-form" method="post" action="">
            <input type="hidden" name="edit_book_id" value="<?= $book_to_edit['id'] ?>">
            <label for="edit_title">Title:</label>
            <input type="text" name="edit_title" value="<?= $book_to_edit['title'] ?>" required><br>
            <label for="edit_author">Author:</label>
            <input type="text" name="edit_author" value="<?= $book_to_edit['author'] ?>" required><br>
            <label for="edit_published_year">Published Year:</label>
            <input type="text" name="edit_published_year" value="<?= $book_to_edit['published_year'] ?>" required><br>
            <label for="edit_isbn">ISBN:</label>
            <input type="text" name="edit_isbn" value="<?= $book_to_edit['isbn'] ?>" required><br>
            <input type="submit" class="update-book-button" name="update_book" value="Update Book">
        </form>
    <?php endif; ?>
</body>
</html>
