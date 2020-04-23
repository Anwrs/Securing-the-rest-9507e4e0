<?php 
$host = '127.0.0.1';
$db   = 'netland';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
$fout = 0;
?>

<a href="index.php">Terug</a>
<form action="login.php" method="post">
    <h1>Login paneel Netland</h1>
    <input type="text" name="username" id="">
    <input type="password" name="password" id="">
    <button type="submit" name="login">Login</button>
</form>

<?php 
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE username=? AND password=?");
    $stmt->execute([$username,$password]); 
    $user = $stmt->fetch();
    if ($user) {
        $_COOKIE['loggedInUser'] = $user['id'];
        echo '<h2 style="color:green">Uw bent ingelogd</h2>';
    } else {
        echo '<h2 style="color:red">Username and Password do not match</h2>';
    }
}