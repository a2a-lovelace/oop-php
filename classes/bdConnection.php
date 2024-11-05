class bdConnection {
    private static $instance = null;
    private $connection;
    public $error;
    public $sql;

    // Статичные данные для соединения
    private static $bdLogin = 'your_username';
    private static $bdPass = 'your_password';
    private static $bdHost = 'localhost';
    private static $bdDataBase = 'your_database';

    // создаёт подключения к бд по статик параметрам
    private function __construct() {
        $this->connect();
    }

    // возврат одного представителя, класс bdConection объявить через паттерн singleton
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new bdConnection();
        }
        return self::$instance;
    }

    // Метод КОННЕКТ, соединения
    private function connect() {
        $this->connection = new mysqli(self::$bdHost, self::$bdLogin, self::$bdPass, self::$bdDataBase);
        if ($this->connection->connect_error) {
            $this->error = "Ошибка соединения: " . $this->connection->connect_error;
        }
    }

    // Метод запуска ЗАПРОСОВ SQL (возвращает ассоциативный массив SELECT запроса)
    public function select($sql) {
        $this->sql = $sql;
        $result = $this->connection->query($sql);
        if ($result === false) {
            $this->error = "Ошибка SQL: " . $this->connection->error;
            return false;
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Метод ВСТАВКИ запроса в Базу данных (возвращает id запроса добавления)
    public function insert($sql) {
        $this->sql = $sql;
        if ($this->connection->query($sql) === true) {
            return $this->connection->insert_id;
        } else {
            $this->error = "Ошибка SQL: " . $this->connection->error;
            return false;
        }
    }

    // Метод ОБНОВЛЕНИЯ данных в Базе данных (возвращает id запроса обновления)
    public function update($sql) {
        $this->sql = $sql;
        if ($this->connection->query($sql) === true) {
            return $this->connection->affected_rows;
        } else {
            $this->error = "Ошибка SQL: " . $this->connection->error;
            return false;
        }
    }
}
