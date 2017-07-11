
<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once('vendor/autoload.php');   
include_once 'connection.php';
	
	class User {
		
		private $db;
		private $connection;
				
		function __construct() {
			$this -> db = new DB_Connection();
			$this -> connection = $this->db->getConnection();
	
		}
		public function does_user_exist($email,$encrypted_password)
		{
            $query = "Select * from users where email='$email' and password = '$encrypted_password' ";
			$result = mysqli_query($this->connection, $query);
			if(mysqli_num_rows($result)>0){
										$json['Error'] = 'User already exists';
				echo json_encode($json);

				
			}else{
				$query = "insert into users (email, password) values ( '$email','$encrypted_password')";
				$inserted = mysqli_query($this -> connection, $query);
				
				if($inserted == 1 ){
					 
					$json['success'] = 'Acount created';
				}else{
					$json['error'] = 'Wrong password';
				}
				echo json_encode($json);
				mysqli_close($this->connection);
			}
			
		}
		
	}
	
	
	 $user = new User();
		$data = json_decode(file_get_contents("php://input")); 


	if(isset($data->email,$data->password)) {
		$email = $data->email;
	
		$password = $data->password;
	
		if(!empty($email) && !empty($password)){
		
			$encrypted_password = md5($password);
			
			$user-> does_user_exist($email,$encrypted_password);
			
		}else{
			echo json_encode("you must type both inputs");
		}
		
	}
	
	














?>