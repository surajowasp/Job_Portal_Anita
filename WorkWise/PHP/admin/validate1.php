

<?php

include_once('../conn.php');

function test_input($data) {
	
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	$username = test_input($_POST["username"]);
	$password = test_input($_POST["password"]);
	$stmt = $conn->prepare("SELECT * FROM adminlogin where username= ? ");
    $stmp = $stmt->bind_param("s", $username);
	$stmt->execute();
	$result = $stmt->get_result();
	
	if($result->num_rows > 0) {
		$user = $result->fetch_assoc();
		if(($user['username'] == $username) && 
			($password == $user['password'])){
				header("location: admin_home.php");
		}
		else {
			echo "<script language='javascript'>";
			echo "alert('WRONG INFORMATION')";
			echo "</script>";
			die();
		}
	}else {
        echo "alert('Username already exist.')";
    }
}

?>

