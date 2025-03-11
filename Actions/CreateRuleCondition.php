<?php
namespace Actions;

class CreateRuleCondition
{
	public function __invoke($data, $rulesConditions)
	{
		$row_id = $rulesConditions->store($data);
		$data['id'] = $row_id;
		$data['active'] = 1;
		return [['status' => 'ok', 'data' => $data]];
	}
}