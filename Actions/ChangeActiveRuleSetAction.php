<?php
namespace Actions;
class ChangeActiveRuleSetAction
{
	public function __invoke($data, $agencyRules)
	{
		$agencyRules->update($data);
		return [['status' => 'ok', 'data' => 'updated']];
	}
}