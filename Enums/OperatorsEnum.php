<?php

namespace Enums;
require_once 'AsOptionsTrait.php';
enum Operators: int
{
	case isEqual = 0;
	case isNotEqual = 1;
	case isBigger = 2;
	case isLower = 3;

	public function getDescription(): string
	{
		return match ($this) {
			Operators::isEqual => OperatorsDescription::isEqual->value,
			Operators::isNotEqual => OperatorsDescription::isNotEqual->value,
			Operators::isBigger => OperatorsDescription::isBigger->value,
			Operators::isLower => OperatorsDescription::isLower->value,
		};
	}
}

enum OperatorsDescription: string
{
	case isEqual = 'Равно';
	case isNotEqual = 'Не равно';
	case isBigger = 'Больше';
	case isLower = 'Меньше';

	public static function getAsOptions()
	{
		$options = [];
		foreach (self::cases() as $key => $value) {
			$options[$key] = $value->value;
		}
		return $options;
	}
}