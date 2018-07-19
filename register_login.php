<?php

	function SignIn()
	{
		if(!empty($_POST['email']))
		{		
			include ("connectPDO.php");
			$username = $_POST['email'];
			$password = $_POST['pwd'];
			
			$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND active = 1");
			$stmt->execute(array($username));
			$result = $stmt->fetchAll();
					
			if(sizeof($result) > 0) {   
				$password = hash('sha512', $password, FALSE);
				foreach($result as $row) {
					$dbpassword = $row['password'];
					$role = $row['role'];
					if($dbpassword == $password) {
						if($role == 1) //αν διαπιστωθεί ότι είναι απόφοιτος
						{ 
							session_start();
							$_SESSION['student'] = $row['id'];  
							header('Location: alumni/alumni_index.php');
							exit;
						}
						else if($role == 2 || $role == 3) // αν διαπιστωθεί ότι είναι διαχειριστής
						{
							session_start();
							$_SESSION['name'] = $row['id'];  
							header('Location: admin/admin_index.php');
							exit;	
						}
					}		
				}
				die(header("location: register_login_form.php?loginFailed=true&reason=password&java=1"));
			}
			else
			{
				die(header("location:register_login_form.php?loginFailed=true&reason=username&java=2"));
			}
		}
		else
		{
			die(header("location:register_login_form.php?loginFailed=true&reason=blank&java=3"));
		}
			
	}
	
	SignIn();
?>