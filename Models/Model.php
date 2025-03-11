<?php
namespace Models;
use PDO;
class Model implements ModelInterface
{

	public $table_name = null;
	protected \PDO|null $conn = null;
	protected $MYSQL_HOST = null;
	protected $MYSQL_USER = null;
	protected $MYSQL_PASSWORD = null;
	protected $MYSQL_DB = null;

	public function __construct()
	{
		$this->MYSQL_HOST = 'mysql';
		$this->MYSQL_USER = $_ENV['MYSQL_USER'];
		$this->MYSQL_PASSWORD = $_ENV['MYSQL_PASSWORD'];
		$this->MYSQL_DB = $_ENV['MYSQL_DATABASE'];

		if (!$this->table_name) {
			$this->table_name = str_replace(strtolower(__NAMESPACE__) . '\\', '', strtolower(static::class));
		}
		$this->conn = $this->connect();
	}

	protected function connect(): PDO
	{
		return new PDO('mysql:host=' . $this->MYSQL_HOST . ';port=3306;dbname=' . $this->MYSQL_DB, $this->MYSQL_USER, $this->MYSQL_PASSWORD);
	}

	public function getAll(): array|false
	{
		$stmt = $this->conn->prepare(
			"SELECT
				*
			FROM
				$this->table_name
		"
		);

		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	/**
	 * @param mixed arg
	 * @return void
	 */
	public function getById(int $id): bool|array
	{
		$stmt = $this->conn->prepare(
			"SELECT 
				*
			FROM
				$this->table_name
			WHERE
				id = :id
		"
		);

		$stmt->execute(['id' => $id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getBuilder()
	{
		return $this->conn;
	}
}

interface ModelInterface
{
	public function __construct();
	public function getAll(): array|false;
	public function getBuilder();

	public function getById(int $id): array|bool;
}

interface StoreInterface
{
	public function store(array $data): bool|string;
}

interface UpdateInterface
{
	public function update(array $data): bool|string;
}
interface FindExistsInterface
{
	public function findExists(array $ids): bool|array;
}