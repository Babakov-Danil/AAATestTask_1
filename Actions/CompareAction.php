<?php
namespace Actions;

class CompareAction
{
	private $compare;
	private $agencyRules;
	private $hotels;
	private $rulesEnum;
	private $operationsEnum;
	public function __construct($compare, $agencyRules, $hotels, $rulesEnum, $operationsEnum)
	{
		$this->compare = $compare;
		$this->agencyRules = $agencyRules;
		$this->hotels = $hotels;
		$this->rulesEnum = $rulesEnum;
		$this->operationsEnum = $operationsEnum;
	}
	public function __invoke(int $hotel_id)
	{
		$rulesByHotelId = $this->agencyRules->getRulesByHotelId($hotel_id);
		$hotelByAgency = $this->hotels->getDataForCompare($hotel_id);
		$rules = [];
		foreach ($rulesByHotelId as $key => $value) {
			if (!array_key_exists($value['agency_id'], $rules)) {
				$rules[$value['agency_id']] = [];
			}
			if (!array_key_exists($value['ruleset_id'], $rules[$value['agency_id']])) {
				$rules[$value['agency_id']][$value['ruleset_id']] = [
					'rule_name' => $value['name'],
					'manager_text' => $value['manager_text'],
					'agency_id' => $value['agency_id'],
					'hotel_id' => $value['hotel_id'],
					'conditions' => []
				];
			}
			$rules[$value['agency_id']][$value['ruleset_id']]['conditions'][] = [
				'rule_type' => $value['rule_type'],
				'rule_operator' => $value['rule_operator'],
				'rule_value' => $value['rule_value'],
			];
		}

		if (!count($rules) || !count($hotelByAgency)) {
			echo ('No data');
			die();
		}

		$render = [];

		foreach ($hotelByAgency as $hotelValue) {
			if (!array_key_exists($hotelValue['agency_id'], $rules)) {
				continue;
			}

			foreach ($rules[$hotelValue['agency_id']] as $agency_id => $rule_data) {
				# code...

				$response = false;
				$conditions = [];
				foreach ($rule_data['conditions'] as $key => $condition) {
					$compareType = $this->rulesEnum[$condition['rule_type']]->name;
					$compareOperation = $this->operationsEnum[$condition['rule_operator']]->name;
					$compareValue = $condition['rule_value'];
					$this->compare->setRule($compareType);
					$conditions[] = $this->rulesEnum[$condition['rule_type']]->value;
					$response = $this->compare->handle($hotelValue, $compareOperation, $compareValue);
				}

				if ($response) {
					$render[] = $rule_data['manager_text'] . " (" . $rule_data['rule_name'] . ": " . implode(', ', $conditions) . ") для " . $hotelValue['agency_name'];
					$render[] = "<BR>";
				}
			}
		}

		if (!count($render)) {
			echo "No data";
			die();
		}

		foreach ($render as $renderValue) {
			echo $renderValue;
		}
	}

}