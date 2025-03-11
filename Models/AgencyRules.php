<?php
namespace Models;

use Throwable;
require_once('Model.php');
use Models\Model;

class AgencyRules extends Model implements StoreInterface, UpdateInterface, FindExistsInterface
{
	public $table_name = 'agency_rules';

	public function getRulesByHotelId(int $id): array|bool
	{
		$stmt = $this->conn->prepare(
			"SELECT
						ar.id as ruleset_id
						,ar.name
						,ar.manager_text
						,ar.agency_id
						,ar.hotel_id
						,ar.active as ruleset_active
						,arc.*
					FROM
						$this->table_name as ar
					INNER JOIN
						agency_rules_condition as arc ON
							arc.rule_id = ar.id AND
							arc.active = 1
					WHERE 
						ar.hotel_id = :hotel_id AND
						ar.active = 1
					"
		);

		$stmt->execute(["hotel_id" => $id]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function findExists(array $ids): bool|array
	{
		$query = $this->conn->prepare(
			"SELECT 
				id 
			FROM 
				$this->table_name
			WHERE
				agency_id = :agency_id AND
				hotel_id = :hotel_id"
		);
		$res = $query->execute($ids);
		return $query->fetch(\PDO::FETCH_ASSOC);
	}

	public function store(array $data): bool|string
	{
		$this->conn->beginTransaction();
		try {
			$stmt = $this->conn->prepare(
				"INSERT INTO 
					$this->table_name
						(name, manager_text, agency_id, hotel_id) 
					VALUES (:name, :manager_text, :agency_id, :hotel_id)
			"
			);
			$stmt->execute($data);
			$last_id = $this->conn->lastInsertId();
			$this->conn->commit();

			return $last_id;
		} catch (\Exception $e) {
			$this->conn->rollBack();
			return false;
		}
	}

	public function update(array $data): bool|string
	{
		$this->conn->beginTransaction();
		try {
			$stmt = $this->conn->prepare(
				"UPDATE 
					$this->table_name
				SET
					active = :active
				WHERE
					id = :id
			"
			);
			$stmt->execute($data);
			$this->conn->commit();
			return true;
		} catch (\Exception $e) {
			$this->conn->rollBack();
			return false;
		}
	}
}