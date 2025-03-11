<?php

namespace Rules;

interface EqualInterface
{
	/**
	 * @param array $db_value
	 * @param mixed $rule_value
	 * @return bool
	 */
	public function isEqual(array $db_value, mixed $rule_value): bool;
}

interface NotEqualInterface
{
	/**
	 * @param array $db_value
	 * @param mixed $rule_value
	 * @return bool
	 */
	public function isNotEqual(array $db_value, mixed $rule_value): bool;
}

interface BiggerInterface
{
	/**
	 * @param array $db_value
	 * @param mixed $rule_value
	 * @return bool
	 */
	public function isBigger(array $db_value, mixed $rule_value): bool;
}

interface LowerInterface
{
	/**
	 * @param array $db_value
	 * @param mixed $rule_value
	 * @return bool
	 */
	public function isLower(array $db_value, mixed $rule_value): bool;
}

interface CompareInterface
{
	public function setName(string $name): void;

	public function handle(array $db_value, string $operator, mixed $value): bool;
}