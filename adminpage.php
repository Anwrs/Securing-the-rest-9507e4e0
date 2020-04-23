<?php 

include 'config.php';

if (isset($_COOKIE['loggedInUser'])) {
    echo '<h2 style="color:green">Uw bent ingelogd</h2>';
} else {
    header('location:login.php');
}

$stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE id=?");
$stmt->execute([$_COOKIE['loggedInUser']]); 
$user = $stmt->fetch();
echo "Welcome " . $user['username'];

?>

<form action="adminpage.php" method="post">
<button type="submit" name="logout">logout</button>
</form>

<?php

if (isset($_POST['logout'])) {
    setcookie("loggedInUser", "", time() - 3600);
    header("location:login.php");
}