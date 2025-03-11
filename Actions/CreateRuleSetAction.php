<?php
namespace Actions;
class CreateRuleSetAction
{
	public function __invoke($data, $agencyRules)
	{
		if ($agencyRules->findExists(['agency_id' => $data['agency_id'], 'hotel_id' => $data['hotel_id']])) {
			return [['status' => 'error', 'data' => 'rule set already exists'], 400];
		}
		$row_id = $agencyRules->store($data);
		$data['id'] = $row_id;
		$data['active'] = 1;
		return [['status' => 'ok', 'data' => $data]];
	}
}