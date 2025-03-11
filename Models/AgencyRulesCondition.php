<?php
namespace Models;
require_once('Model.php');
use Models\Model;

class AgencyRulesCondition extends Model implements StoreInterface, UpdateInterface
{
	public $table_name = 'agency_rules_condition';

	public function store(array $data): bool|string
	{
		$this->conn->beginTransaction();
		try {
			$stmt = $this->conn->prepare(
				"INSERT INTO 
					$this->table_name
						(rule_id, rule_type, rule_operator, rule_value) 
					VALUES (:rule_id, :rule_type, :rule_operator, :rule_value)
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