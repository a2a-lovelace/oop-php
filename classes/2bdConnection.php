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
}
