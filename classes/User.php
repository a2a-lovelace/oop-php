class User {
    private $id;
    private $username;
    private $email;
    private $password;
    private $fio = [];
    private $role;
    private $bdConnection;

    // Конструктор соединения
    public function __construct() {
        $this->bdConnection = bdConnection::getInstance();
    }

    // Метод РЕГИСТРАЦИЯ
    public function register($username, $email, $password, $fio, $role) {
        // проверка ПОЧТЫ при регистрации
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Введет неверный формат почты";
        }

        // проверка ПАРОЛЯ при регистрации, можно добавить проверку сложности
        if (strlen($password) < 8) {
            return "Создайте пароль минимум 8 знаков, пожалуйста";
        }

        // Хэш = потому что нельзя хранить открытыми пароли, нужно превращать их в хэш (закодировать)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // В SQL (базу данных) надо добавить зарегистророванного пользователя
        $sql = "INSERT INTO users (username, email, password, fio, role) VALUES ('$username', '$email', '$hashedPassword', '" . json_encode($fio) . "', '$role')";
        
        // Подаем запрос на вставку пользователя в базу
        $userId = $this->bdConnection->insert($sql);
        if ($userId === false) {
            return $this->bdConnection->error;
        }
        return $userId; // возвращаем уникальный идентификатор ID
    }

    // Метод ЛОГИН (запускаем только тех, кто есть в базе)
    public function login($username, $password) {
        $sql = "SELECT * FROM users WHERE username = '$username'";
        $user = $this->bdConnection->select($sql);
        if ($user && password_verify($password, $user[0]['password'])) {
            // Если все хорошо - запустим сессию, сохраним данные о сессии
            session_start();
            $_SESSION['user_id'] = $user[0]['id'];
            $_SESSION['username'] = $user[0]['username'];
            return true; // Успех, пользователь смог зайти
        }
        return false; // Пользователю не удалось войти
    }

    // Метод ЛОГАУТ, то есть выход из сессии
    public function logout() {
        session_start();
        session_destroy(); // осуществлен выход
    }

    // Метод запроса данных по уникальному идентификатору
    public function get($id) {
        $sql = "SELECT * FROM users WHERE id = $id";
        $user = $this->bdConnection->select($sql);
        return $user ? $user[0] : null; // возвратим либо данные, либо ничего (null)
    }
}