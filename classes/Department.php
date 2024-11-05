class Department {
    private $id;
    private $name;
    private $bdConnection;

    // Конструктор
    public function __construct() {
        $this->bdConnection = bdConnection::getInstance();
    }

    // Метод запроса департамента по идентификатору (id)
    public function getNameById($id) {
        $sql = "SELECT name FROM departments WHERE id = $id";
        $result = $this->bdConnection->select($sql);
        return $result ? $result[0]['name'] : null; // возвращаем либо название, либо null
    }

    // Метод вызова списка департаментов
    public function getList() {
        $sql = "SELECT * FROM departments";
        $result = $this->bdConnection->select($sql);
        return $result ? $result : []; // возращает список департаментов либо пустой список
    }

    // Метод запроса списка департаментов, которые ассоциированы с пользователем
    public function getByUserId($idUser) {
        $sql = "SELECT d.* FROM departments d JOIN users_departments ud ON d.id = ud.department_id WHERE ud.user_id = $idUser";
        $result = $this->bdConnection->select($sql);
        return $result ? $result : []; // возращает список департаментов либо пустой список
    }

    // Метод запроса списка пользователей, которые ассоциированы с департаментом
    public function getUserList($idDep) {
        $sql = "SELECT u.* FROM users u JOIN users_departments ud ON u.id = ud.user_id WHERE ud.department_id = $idDep";
        $result = $this->bdConnection->select($sql);
        return $result ? $result : []; // возращает список пользователей этого департамента либо пустой список
    }

    // Метод создания департамента
    public function create($name) {
        $sql = "INSERT INTO departments (name) VALUES ('$name')";
        $departmentId = $this->bdConnection->insert($sql);
        return $departmentId; // возрат идентификатора
    }

    // Метод переименовывания департамента
    public function set($id, $name) {
        $sql = "UPDATE departments SET name = '$name' WHERE id = $id";
        $result = $this->bdConnection->update($sql);
        return $result !== false; // возвращает true при успехе переименовывания
    }

    // Метод добавления пользователя к департаменту
    public function addUserToDepartment($idDep, $idUser, $part = null) {
        $sql = "INSERT INTO users_departments (department_id, user_id, part) VALUES ($idDep, $idUser, '$part')";
        $result = $this->bdConnection->insert($sql);
        return $result; // возврат добавленных данных
    }

    // Метод удаления пользователя из департамента
    public function deleteUserFromDepartment($idDep, $idUser) {
        $sql = "DELETE FROM users_departments WHERE department_id = $idDep AND user_id = $idUser";
        $result = $this->bdConnection->update($sql);
        return $result !== false; // возвращает true при успехе удаления
    }
}