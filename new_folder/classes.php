<?php
class dbConnection {
	private $user;
	private $password;
	private $db;
	private $host;
	private $sql_last;
	private $error_last;
	
	public function __construct($user = '*', $password = '*', $db = '*', $host = 'localhost') {
		$this->user = $user;
		$this->password = $password;
		$this->db = $db;
		$this->host = $host;
		$this->sql_last = Null;
		$this->error_last = Null;
	}
	
	private function bindParams(mysqli_stmt $stmt, string $types, array $params) {
		$refs = [];
		foreach ($params as $key => $value) {
			$refs[$key] = &$params[$key];
		}
		array_unshift($refs, $types);
		return call_user_func_array([$stmt, 'bind_param'], $refs);
	}
	
	public function select($table, $columns = '*', $condition = Null) {
		$conn = new mysqli($this->host, $this->user, $this->password, $this->db);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
        $sql = "SELECT $columns FROM $table";
        if ($condition !== Null) {
            $sql .= " WHERE ".$condition['structure'];
        }
        $sql .= ";";

        $this->sql_last = $sql;
		$stmt = $conn->prepare($sql);
		
		if (!$stmt) {
			$this->error_last = $conn->error;
			$conn->close();
			return null;
		}
		
		if ($condition !== Null) {
			$this->bindParams($stmt, $condition['types'], $condition['values']);
		}
		
        $pre_res = $stmt->execute();
		if ($pre_res) {
			$res = $stmt->get_result();
			$data = $res->fetch_all(MYSQLI_ASSOC);
		} else {
			$data = Null;
		}
		$stmt->close();
        $conn->close();
        return $data;//$data;
	}
	
	public function update($table, $set, $condition = Null) {
		$conn = new mysqli($this->host, $this->user, $this->password, $this->db);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
        $sql = "UPDATE $table SET ".$set['structure'];
        if ($condition !== Null) {
            $sql .= " WHERE ".$condition['structure'];
        }
        $sql .= ";";

        $this->sql_last = $sql;
		$stmt = $conn->prepare($sql);
		
		if (!$stmt) {
			$this->error_last = $conn->error;
			$conn->close();
			return null;
		}
		
		if ($condition !== Null) {
			$this->bindParams($stmt, $set['types'].$condition['types'], array_merge($set['values'], $condition['values']));
		} else {
			$this->bindParams($stmt, $set['types'], $set['values']);
		}
		$pre_res = $stmt->execute();
		if ($pre_res) {
			$rows = $conn->affected_rows;
		} else {
			$rows = Null;
		}
		$stmt->close();
        $conn->close();
        return $rows;
	}
	
	public function insert($table, $columns, $values) {
		$conn = new mysqli($this->host, $this->user, $this->password, $this->db);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		$sql = "INSERT INTO $table($columns) VALUES (".$values['structure'].");";
		//return $sql;
		$this->sql_last = $sql;
		$stmt = $conn->prepare($sql);
		
		if (!$stmt) {
			$this->error_last = $conn->error;
			$conn->close();
			return $this->error_last;//'not stmt';//null;
		}
		
		$this->bindParams($stmt, $values['types'], $values['values']);
        	$pre_res = $stmt->execute();
		if ($pre_res) {
			$id = $conn->insert_id;
		} else {
			$id = Null;
		}
		$stmt->close();
        $conn->close();
        return $id;
	}
	
	public function delete($table, $condition = Null) {
		$conn = new mysqli($this->host, $this->user, $this->password, $this->db);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}
		
        $sql = "DELETE FROM $table";
        if ($condition !== Null) {
            $sql .= " WHERE ".$condition['structure'];
        }
        $sql .= ";";

        $this->sql_last = $sql;
	$stmt = $conn->prepare($sql);
	
	if (!$stmt) {
		$this->error_last = $conn->error;
		$conn->close();
		return null;
	}
	
	if ($condition !== Null) {
		$this->bindParams($stmt, $condition['types'], $condition['values']);
	}
	
	$res = $stmt->execute();
	if ($res) {
		return $res;
	} else {
		return False;
	}
	}

}

class User {
		private $id;
		private $username;
		private $email;
		private $password;
		private $fio;
		private $role;
		private $dbConnection;
		
public function __construct($username, $email, $password, $f, $i, $o, $role = "user") {
        $this->id = null;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;//password_hash($password, PASSWORD_DEFAULT);
        $this->fio = ["f" => $f, "i" => $i, "o" => $o];
        $this->role = $role;
        $this->dbConnection = new dbConnection();
    }

    public function register() {
        $table = "users";
        $columns = "id";
        $condition = array('structure' => 'email = ?',
							'types' => 's',
							'values' => [$this->email]);
        $check = $this->dbConnection->select($table, $columns, $condition);

        if (empty($check)) {
            $columns = "username, email, password_hash, fio, role";
            $fio_str = $this->fio['f'] . " " . $this->fio['i'] . " " . $this->fio['o'];
			$pass_hash = password_hash($this->password, PASSWORD_DEFAULT);
			$values = array('structure' => "?, ?, ?, ?, ?",
							'types' => "sssss",
							'values' => [$this->username, $this->email, $pass_hash, $fio_str, $this->role]);
            $id = $this->dbConnection->insert($table, $columns, $values);
            $this->id = $id;
            if (session_status() === PHP_SESSION_NONE) {
	    	session_start();
	    }
            $_SESSION['username'] = $this->username;
            $_SESSION['user_id'] = $this->id;
            return $id;
            //return empty($check);
        } else {
            return "User with this email already exists";
        }
    }

    public function login() {
        $table = "users";
        $columns = "*";//"id, password_hash, username";
        $condition = array('structure' => 'email = ?',
							'types' => 's',
							'values' => [$this->email]);
							
        $res = $this->dbConnection->select($table, $columns, $condition);
		#return $this->password." ".$res[0]['password_hash'];

        if (!empty($res)) {
            $row = $res[0];
            if (password_verify($this->password, $row['password_hash'])) {
                $this->id = $row['id'];
                $this->username = $row['username'];
				$this->fio = $row['fio'];
				$this->role = $row['role'];
                session_start();
                $_SESSION['username'] = $this->username;
                $_SESSION['user_id'] = $this->id;
				$_SESSION['user_role'] = $this->role;
                return $this->id;
            }
        }
        return false;
    }

    public function logout() {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
        session_unset();
        session_destroy();
    }
}

class Toolkit {
	private $dbConnection;
	
	public function __construct() {
		$this->dbConnection = new dbConnection();
	}
	
	public function getDepById($d_id) {
		$table = 'departments';
		$columns = 'id, name';
		$condition = array('structure' => 'id = ?',
							'types' => 'i',
							'values' => [$d_id]);
		$res = $this->dbConnection->select($table, $columns, $condition);
		if (!empty($res)) {
            		$row = $res[0];
			return $row;
		}
		return false;
	}
	
	public function getDepList() {
		$table = 'departments';
		$columns = '*';
		$res = $this->dbConnection->select($table, $columns);
		if (!empty($res)) {
            		//$row = $res[0];
			return $res;//$row;
		}
		return false;
	}
	
	public function getUsersDepById($u_id) {
		$table = 'users join users_departments on users.id = users_departments.user_id join departments on departments.id = users_departments.department_id';
		$columns = 'users.id, users.username, users.email, users.fio, users.role, departments.name, users_departments.post';
		$condition = array('structure' => 'users.id = ?',
							'types' => 'i',
							'values' => [$u_id]);
		$res = $this->dbConnection->select($table, $columns, $condition);
		if (!empty($res)) {
			return $res;
		}
		return false;
	}
	
	public function createDep($depname) {
		$table = 'departments';
		$columns = 'name';
		$values = array('structure' => "?",
				'types' => "s",
				'values' => [$depname]);
		$id = $this->dbConnection->insert($table, $columns, $values);
		if (!empty($id)) {
			return $id;
		}
		return false;
	}
	
	public function setDepName($depid, $depname) {
		$table = 'departments';
		$set = array('structure' => 'name = ?',
							'types' => 's',
							'values' => [$depname]);
		$condition = array('structure' => 'id = ?',
				'types' => 'i',
				'values' => [$depid]);
		$res = $this->dbConnection->update($table, $set, $condition);
		if (!empty($res)) {
			return $res;
		}
		return false;
	}
	
	public function addUserPos($uid, $depid, $posname) {
		
		
		$table1 = "users";
	        $columns1 = "id";
	        $condition1 = array('structure' => 'id = ?',
					'types' => 'i',
					'values' => [$uid]);
					
		$res1 = $this->dbConnection->select($table1, $columns1, $condition1);
		
		$table2 = "departments";
	        $columns2 = "id";
	        $condition2 = array('structure' => 'id = ?',
					'types' => 'i',
					'values' => [$depid]);
					
		$res2 = $this->dbConnection->select($table2, $columns2, $condition2);
		
		
		if ((!empty($res1))&&(!empty($res2))) {
			$table = 'users_departments';
			$columns = 'user_id, department_id, post';
			$values = array('structure' => "?, ?, ?",
					'types' => "iis",
					'values' => [$uid, $depid, $posname]);
			$id = $this->dbConnection->insert($table, $columns, $values);
			if (!empty($id)) {
				return $id;
			}
			else {
				return False;
			}
		}
		else {
			return False; }
	}
	
	public function delUserDep($uid, $depid) {
		$table = 'users_departments';
		$condition = array('structure' => 'user_id = ? AND department_id = ?',
				'types' => 'ii',
				'values' => [$uid, $depid]);
		$res = $this->dbConnection->delete($table, $condition);
		if ($res) {
			return $res;
		}
		return False;
	}
}