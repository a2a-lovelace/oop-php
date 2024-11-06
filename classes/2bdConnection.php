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

    private function __construct() {
        $this->connect();
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new bdConnection();
        }
        return self::$instance;
    }

    private function connect() {
        $this->connection = new mysqli(self::$bdHost, self::$bdLogin, self::$bdPass, self::$bdDataBase);
        if ($this->connection->connect_error) {
            $this->error = "Ошибка соединения: " . $this->connection->connect_error;
        }
    }
}
