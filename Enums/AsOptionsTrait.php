<?php
namespace Enums;
trait AsOptions
{
	public static function getAsOptions()
	{
		$options = [];
		$allowedOperations = [];
		$allowedTypes = [];
		foreach (self::cases() as $key => $value) {
			$options[$key] = $value->value;
			$allowedOperations[$key] = $value->allowedOperations();
			$allowedTypes[$key] = $value->allowedType();
		}
		return [$options, $allowedOperations, $allowedTypes];
	}
}