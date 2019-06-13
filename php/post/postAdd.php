<?php
session_start();

//tijdelijke login
require_once('../functions/uuidv4.php');
$_SESSION['login'] = ['ID' => uuidv4(), 'username' => "kelvin", 'level' => 2];

// maak een encrypted key
$token = bin2hex(openssl_random_pseudo_bytes(32));
// zet alles in een validation zodat je niet veel session namen gebruik
$_SESSION['validation'] = ["token" => $token, "domain" => $_SERVER['HTTP_HOST']];
?>
<html>
<head>
    <title></title>
</head>
<body>
<?php
if ($_SESSION['login']['level'] == 2) {
    ?>
    <form action="postAddProcess.php" method="post" id="post_add" enctype="multipart/form-data">
        <input type="text" name="domain" value="<?php echo $_SESSION['validation']['domain']; ?>" hidden>
        <input type="text" name="csrf_token" value="<?php echo $_SESSION['validation']['token']; ?>" hidden>
        <input type="text" name="ID" value="<?php echo $_SESSION['login']['ID']; ?>" hidden>
        <table>
            <tr>
                <td colspan="2">Title:</td>
            </tr>
            <tr>
                <td colspan="2"><input type="text" name="title"></td>
            </tr>
            <tr>
                <td colspan="2">Header:</td>
            </tr>
            <tr>
                <td colspan="2"><input type="file" name="header"></td>
            </tr>
            <tr>
                <td colspan="2">text:</td>
            </tr>
            <tr>
                <td colspan="2"><textarea name="body" form="post_add"></textarea></td>
            </tr>
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
} else {
    echo "<h1 class='error'>OOPS!</h1>";
    echo "<p class='error'>U hebt niet genoeg rechten om op deze pagina iets te doen. Klik <a href='../../index.php'>hier</a> om naar de home tegaan.</p>";
}
?>
</body>
</html>