<?php
session_start();
// koneksi data base
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';
// coba hubungkan data base
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if ( mysqli_connect_errno() ) {

	// Jika ada kesalahan dengan koneksi, hentikan skrip dan tampilkan kesalahan.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

// siapkan sql
if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	// var string
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	// mengecek account di data base
	$stmt->store_result();


    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Verivikasi password
        if (password_verify($_POST['password'], $password)) {
            // Verification succes
            // buat sesi untuk mengetahui yang masuk, mengingat data di server
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('Location: home.php');
        } else {
            // incorrect pass
            echo 'passsword or username salah cok!';
        }
    } else {
        // Incorrect user
        echo 'Incorrect username and/or password!';
    }


	$stmt->close();
}
?>
   