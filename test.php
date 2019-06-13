<?php
session_start();
?>
<form action="" method="post" id="post_add" enctype="multipart/form-data">
    <table>
        <tr>
            <td colspan="2">pictures:</td>
        </tr>
        <tr>
            <td colspan="2"><input type="file" name="pictures[]" MULTIPLE></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="submit" value="post"></td>
        </tr>
    </table>
</form>
<?php
require_once 'php/functions/uuidv4.php';
if (isset($_POST['submit'])) {
    $uuid = uuidv4();
    upload($_FILES['pictures'], $uuid);

    $session = $_SESSION['uuid'];
    $path = scandir("uploads/post/". $_SESSION['uuid']);
    $count = count($path);
    for ($i = 0; $i < $count; $i++){
        $i += 2;
        echo "<img src='uploads/post/$session/$path[$i]' style='width: 100px'>";
    }
}

function upload($file, $uuid) {

    // count how many files are in the upload
    $arr = count($file['name']);
    mkdir("uploads/post/" . $uuid, 7777);
    for ($i = 0; $i < $arr; $i++) {

        $type = (string) $file['type'][$i];
        // check what type of file it is
        if ($file['type'][$i] == "image/jpeg" ||
            $file['type'][$i] == "image/gif" ||
            $file['type'][$i] == "image/png") {
            $fileType = true;
        } else {
            return "<p class='error'>Wij ondersteunen deze filetype niet</p>";
        }

        if (isset($fileType) && $fileType == true) {

            // get file type
            $fileTypeName = explode(".", $file['name'][$i]);


            // make new path
            $path = "uploads/post/". $uuid. "/" .  $i . "." . $fileTypeName[1];

            // upload file
            move_uploaded_file($file['tmp_name'][$i], $path);
            echo $path . "<br>";
        }
    }
    $_SESSION['uuid'] = $uuid;
}


unset($_POST);
echo "<pre>";
var_dump($_FILES);
echo "</pre>";