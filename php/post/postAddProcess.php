<?php
session_start();
require_once "../functions/errorCode.php";
require_once('../../config_beroeps2.inc.php');
require_once('../functions/uuidv4.php');

/*
 * INIT varables
 */
$body = $_POST["body"];
$title = $_POST["title"];
$header = $_FILES["header"];
$validation = $_SESSION["validation"];
$tokenP = $_POST["csrf_token"];
$submit = $_POST["submit"];
$domainP = $_POST["domain"];
$ID = $_POST["ID"];

// check if your logged in with the fitting level
if ( $_SESSION["login"]["level"] == 2 ) {

    $login = true;

} else {

    echo '<h1 class="error">OOPS!</h1>';
    echo '<p class="error">U hebt niet genoeg rechten om op deze pagina iets te doen. Klik <a href="../../index.php">hier</a> om naar de home tegaan.</p>';

}

/*
 * check if user submitted the form
 */
if (isset($login) && $login) {
    if(isset($submit) && !empty($submit)) {

        $empSubmit = true;

    } else {

        echo '<h1>Oops!</h1>';
        echo '<p>U hebt uw post nog niet verzonde.</p>';

    }
}
/*
 * check if the tokens match up
 */
if (isset($empSubmit) && $empSubmit) {
    if (isset($validation["token"]) && $validation["token"] == $tokenP) {

        $valToken = true;

    } else {

        echo errorCode( 'pap1010' );

    }
}
/*
 * check if the domains match up
 */
if (isset($valToken) && $valToken) {
    if (isset($validation["domain"]) && $validation["domain"] == $domainP) {

        $valDomain = true;

    } else {

        echo errorCode( 'pap1020' );
    }
}
/*
 * check if it is an uuid
 */
if (isset($valDomain) && $valDomain) {
    if (isset($ID) && $ID == $ID) {

        $splitID = str_split($ID);
        if ($splitID[14] == 4) {
            if ($splitID[19] == 8 ||
                $splitID[19] == 9 ||
                $splitID[19] == 'a' ||
                $splitID[19] == 'b' ) {
            } else {
                echo errorCode( 'pap1020' );
            }
        } else {
            echo errorCode( 'pap1020' );
        }

    } else {

        echo errorCode( 'pap1030' );
    }
}
/*
 * check if user filled in:
 * title
 * text
 * files
 *
 * if validation was succesfull
 */
if (isset($valToken) && $valToken) {

    // check if title is not empty
    if (isset($title) && !empty($title)) {

        $empTitle = true;
    } else {

        echo '<p class="error">We hebben geen titel ontvangen.</p>';

    }

    if (isset($body) && !empty($body)) {

        $empText = true;

    } else {

        echo '<p class="error">We hebben geen text ontvangen.</p>';

    }

    if (isset($header) && !empty($header["name"])) {

        $empHeader = true;

    } else {

        echo "<p class='error'>We hebben geen header ontvangen.</p>";

    }
}
/*
 *  Execute the statment
 */
if (isset($empTitle) && $empTitle &&
    isset($empText) && $empText &&
    isset($empHeader) && $empHeader) {

    $query = "INSERT INTO `exblogpost` (`ID`, `body`, `userID`) VALUES (?, ?, ?)";

    // maak prepare statment opnieuw
    if ($stmt = mysqli_stmt_init($mysqli)) {
        $stmtInitVal = true;
    } else {
        echo '<p class="error">statment kon niet voorberijd worden.</p>';
    }

    // voorberijden op prepare statment
    if (isset($stmtInitVal) && $stmtInitVal == true && mysqli_stmt_prepare($stmt, $query)) {

        // Convert string so you cant sql inject
        $mrstBody = mysqli_real_escape_string($mysqli, $body);

        // make an id
        $uuid = uuidv4();

        // bind questionmarks in the query with an parameter
        mysqli_stmt_bind_param($stmt, "sss", $uuid,$mrstBody, $ID);

        if (mysqli_stmt_execute($stmt)) {
            echo "<h1>Succes!</h1>";
            echo "<p>Uw post toegevoegd aan uw blog.</p>";
        } else {
            echo errorCode( 'pap1040' );
        }
    } else {
        echo errorCode( 'pap1050' );
    }

}
echo "<pre>";
var_dump($_POST, $_FILES);
echo "</pre>";