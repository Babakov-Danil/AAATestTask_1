<?php
namespace Actions;
class ChangeActiveRuleCondition
{
	public function __invoke($data, $rulesConditions)
	{
		$rulesConditions->update($data);
		return [['status' => 'ok', 'data' => 'updated']];
	}
}