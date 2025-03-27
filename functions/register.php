<?php

// Include configuration
include '../config/config.php';
include '../config/db.php';
$conn = OpenCon();

// Getting value from the POST form
$username = $_POST['username'];
$pass = $_POST['password'];
$pass_conf = $_POST['password_conf'];


// If password was not the same as the password confirmation, redirect it to the failed message page
if($pass != $pass_conf){
	header('Location: ' .$base_url . '/register.php?message=failed'); 
} else {

    // Hashing password that will be stored on the database
    $pass_conf = password_hash($_POST['password_conf'], PASSWORD_BCRYPT);

    // Generate random value for project_owner_id
    $random_val=rand();
    $project_owner_id = md5($random_val);

    $sql = "INSERT INTO users (username, pass, project_owner_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $pass_conf, $project_owner_id);
    $stmt->execute();
    CloseCon($stmt);
    // Redirect to success page
    header('Location: ' .$base_url . '/index.php?message=success'); 
}


// //check apakah user dengan username tersebut ada di table users
// $query = "select * from users where username = ? limit 1";
// $stmt = $mysqli->stmt_init();
// $stmt->prepare($query);
// $stmt->bind_param('s', $user['username']);
// $stmt->execute();
// $result = $stmt->get_result();
// $row = $result->fetch_array(MYSQLI_ASSOC);

// //jika username sudah ada, maka return kembali ke halaman register.
// if($row != null){
// 	$_SESSION['error'] = 'Username yang anda masukkan sudah ada di database.';
// 	header("Location: ../register.php");

// }else{
// 	//username unik. simpan di database.
// 	$query = "insert into users (nama, username, password) values  (?,?,?)";
// 	$stmt = $mysqli->stmt_init();
// 	$stmt->prepare($query);
// 	$stmt->bind_param('sss', $user['nama'],$user['username'],$user['password']);
// 	$stmt->execute();
// 	$result = $stmt->get_result();
// 	var_dump($result);

// 	$_SESSION['message']  = 'Berhasil register ke dalam sistem. Silakan login dengan username dan password yang sudah dibuat.';
// 	header("Location: register	.php");
// }

?>
