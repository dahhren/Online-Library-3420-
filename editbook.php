<?php
session_start();

include 'includes/library.php';
$pdo = connectDB(); //connect to database

if (!isset($_SESSION)) {
  session_start();
}
$signed = false;
if(isset($_SESSION['username'])) {
  $signed = true;
}

if ($signed) {
$query = "SELECT * FROM `libraryusers` WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt -> execute([$_SESSION['id']]);
$results = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM `bookdetails` WHERE `id` = ?");
$stmt->execute([$_SESSION['id']]);
$book = $stmt->fetchAll();

}

$errors = array();


$id = $_SESSION['id'] ?? null;
$title = $_POST['title'] ?? null;
$author = $_POST['author'] ?? null;
$genre = $_POST['genre'] ?? null;
$rating = $_POST['rating'] ?? null;
$pdate = $_POST['pdate'] ?? null;
$isbn = $_POST['isbn'] ?? null;
$description = $_POST['description'] ?? null;
$format = $_POST['format'] ?? null;
$coverimg = $_POST['coverimg'] ?? null;
$curl = $_POST['curl'] ?? null;
$ebook = $_POST['ebook'] ?? null;
$coverimg = $_FILES['coverimg']['name'] ?? null;
$ebook = $_FILES['ebook']['name'] ?? null;

function createFilename($file, $path, $prefix, $uniqueID)
{
    $filename = $_FILES[$file]['name'];
    $exts = explode(".", $filename);
    $ext = $exts[count($exts) - 1];
    $filename = $prefix . $uniqueID . "." . $ext;
    $newname = $path . $filename;
    return $newname;
}

// check for errors when uploading a file, taken from course notes
function checkErrors($file, $limit)
{
    //modified from http://www.php.net/manual/en/features.file-upload.php
    try {
        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (!isset($_FILES[$file]['error']) || is_array($_FILES[$file]['error'])) {
            throw new RuntimeException('Invalid parameters.');
        }

        // Check Error value.
        switch ($_FILES[$file]['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        // You should also check filesize here.
        if ($_FILES[$file]['size'] > $limit) {
            throw new RuntimeException('Exceeded filesize limit.');
        }

        // Check the File type
        if (
            $file == 'cover_image'
            and exif_imagetype($_FILES[$file]['tmp_name']) != IMAGETYPE_GIF
            and exif_imagetype($_FILES[$file]['tmp_name']) != IMAGETYPE_JPEG
            and exif_imagetype($_FILES[$file]['tmp_name']) != IMAGETYPE_PNG
        ) {

            throw new RuntimeException('Invalid file format.');
        }

        return "";

    } catch (RuntimeException $e) {

        return $e->getMessage();

    }

}

// upload file to server, create new name based on user id and isbn
// checks for errors when uploading file and moving file to www_data
function upload_file($file, $isbn, $errors)
{
    $uniqueID = '_user' . $_SESSION['id'] . '_' . $isbn; //use database autonumber for unique value?
    $path = '../../../www_data/'; //location file should go, relative to addbook
    $fileroot = 'cover_image'; //base filename

    if (is_uploaded_file($_FILES[$file]['tmp_name'])) {

        $results = checkErrors($file, 102400000);
        if (strlen($results) > 0) {
            echo $results; //this should be handled more gracefully
        } else {
            $newname = createFilename($file, $path, $file, $uniqueID);
            if (!move_uploaded_file($_FILES[$file]['tmp_name'], $newname)) {
                echo "Failed to move uploaded file."; //this should be handled more gracefully
                $errors['file'] = true;
            }
            return createFilename($file, '/www_data/', $file, $uniqueID);
        }
    } else {
        $results = checkErrors('coverimg', 102400000);
        echo $results;
    }
}


if (isset($_POST['submit'])) { //only do this code if the form has been submitted

    //only do this if there weren't any errors
    if (count($errors) === 0) {

        $coverimg = upload_file('coverimg', $isbn, $errors);
        $ebook = upload_file('ebook', $isbn, $errors);

        // get book format extension
        $period = explode(".", $_FILES['ebook']['name']);
        $ebook = end($period);

        // remove html tags from description
        $description = strip_tags($description);
        
        $query = "UPDATE bookdetails SET `title` = ?, `author` = ?, `rating` = ?, `genre` = ?, `publication` = ?, `isbn` = ?, `description` = ?, `bookformat` = ?, `coverimg` = ?, `coverurl` = ?, `ebook` = ? WHERE `bookdetails`.`bookid` = ?"; 
        $stmt = $pdo->prepare($query)->execute([$title, $author, $rating, $genre, $pdate, $isbn, $description, $format, $coverimg, $curl, $ebook]);

        //send the user to the thanks page.
        header("Location:bookedited.php");
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <?php
      $page_title = "Edit Book";
      include 'includes/metadata.php';
    ?>
  </head>
  <body>
    <?php include 'includes/header.php';?>
    <section class="account">
    <form id="processform" name="process" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate>
      <h2>Edit Book Details</h2>
        <div>
            <label for="title">Title: </label>
            <input type="text" placeholder="The Great Gatsby" name="title" id="title" required/>
        </div>
        <div><span class="error <?=!isset($errors['name']) ? 'hidden' : "";?>">Please enter your Name</span></div>

        <div>
            <label for="author">Author: </label>
            <input type="text" placeholder="F. Scott Fitzgerald" name="author" id="author" required/>
        </div>
        <div><span class="error <?=!isset($errors['username']) ? 'hidden' : "";?>">Please create a username</span></div>

        <div>
            <label for="rating">Rating (0-10): </label>
            <input type="number" placeholder="4" name="rating" id="rating" min="0" max="9" required/>
        </div>
          <div><span class="error <?=!isset($errors['email']) ? 'hidden' : "";?>">Please enter a valid E-Mail address</span></div>

          <div>
            <label for="genre">Genre: </label>
            <input type="text" placeholder="Tragedy" name="genre" id="genre" required/>
        </div>
        
        <div>
            <label for="pdate">Publication Date: </label>
            <input type="date" name="pdate" id="pdate" required/>
        </div>

        <div>
            <label for="isbn">ISBN: </label>
            <input type="number" placeholder="9780333791035" name="isbn" id="isbn" required/>
        </div>

        <div>
            <label for="description">Description: </label>
            <textarea name="description" id="description" cols="25" rows="10"></textarea>
        </div>

        <div class="format">
            <label for="format">Book Format:</label>
                <select name="format" id="book_format">
                <option value="">Select a format</option>
                <option value="hardcover">Hardcover</option>
                <option value="paperback">Paperback</option>
                <option value="epub">EPub</option>
                <option value="mobi">Mobi</option>
                <option value="pdf">PDF</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div>
            <label for="coverimg">Cover Image: </label>
            <input type="file" name="coverimg" id="coverimg" required/>
        </div>

        <div>
            <label for="curl">Cover Image URL: </label>
            <input type="url" placeholder="https://upload.wikimedia.org/wikipedia/commons/7/7a/The_Great_Gatsby_Cover_1925_Retouched.jpg"  id="curl" name="curl">
        </div>

        <div>
            <label for="ebook">Ebook Upload: </label>
            <input type="file" name="ebook" id="ebook" />
        </div>

        <div><button type="submit" name="submit">Save Changes</button></div>
        
        </form>
        </section>
        <?php include 'includes/footer.php';?>
  </body>
</html>