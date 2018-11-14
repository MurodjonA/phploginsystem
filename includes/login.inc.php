<?php
session_start();
	if (isset($_POST['submit'])) {
		include_once 'dbh.inc.php';

		$uname = mysqli_real_escape_string($conn, $_POST['uname']);
		$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);

		//Error handling and Check if it is empty

		if (empty($uname) || empty($pwd)) {
			header("Location: ../index.php?login=empty");
			exit();
		} else {
			$sql = "SELECT * FROM users WHERE user_uid = '$uname' OR user_email = '$uname';";
			$result = mysqli_query($conn, $sql);
			$resultCheck = mysqli_num_rows($result);
			if ($resultCheck < 1) {
				header("Location: ../index.php?login=erroruname");
				exit();
			} else {
				if ($row = mysqli_fetch_assoc($result)) {
					//De-hashing password
					$hashedPwdCheck = password_verify($pwd, $row['user_pwd']);
					//echo "$hashedPwdCheck";
					if ($hashedPwdCheck == false) {
						header("Location: ../index.php?login?errorpwd");
						exit();
					} elseif ($hashedPwdCheck == true) {
						//Log in the user here
						$_SESSION ['u_id'] = $row ['user_id'];
						$_SESSION['u_first'] = $row['user_first'];
						$_SESSION['u_last'] = $row['user_last'];
						$_SESSION['u_email'] = $row['user_email'];
						$_SESSION['u_uid'] = $row['user_uid'];
						header("Location: ../index.php?login=success");
						exit();
					}
				}
			}
		}
	} else {
		header("Location: ../index.php?login=error");
		exit();
	}