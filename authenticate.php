
<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'nagad_uat';

$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {
	
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if ( !isset($_POST['name'], $_POST['pass']) ) {

	exit('Please fill both the name and password fields!');
}

if ($stmt = $con->prepare('SELECT user_info_key, pass FROM user_info WHERE name = ?')) {

	$stmt->bind_param('s', $_POST['name']);

	$stmt->execute();

	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_info_key, $pass);
        $stmt->fetch();
        if ($_POST['pass'] === $pass) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['name'];
            $_SESSION['user_info_key'] = $user_info_key;
            header('Location: dashboard.php');
        } else {
            echo 'Incorrect username and/or password!';
        }
    } else {

        echo 'Incorrect username and/or password!';
    }
	$stmt->close();
}
?>