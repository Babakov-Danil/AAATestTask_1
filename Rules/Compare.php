<?php

namespace Rules;

use Enums\Operators;

require_once 'CompareInterface.php';

abstract class AbstractCompare implements CompareInterface, BiggerInterface, LowerInterface, EqualInterface, NotEqualInterface
{
	public $name = '';
	public $allowedOperators = [];

	/**
	 * Задаёт название ключа
	 * @param string|array $name
	 * @return void
	 */
	public function setName(string|array $name): void
	{
		$exploded = explode(',', $name);
		if (count($exploded) > 1) {
			$this->name = $exploded;
			return;
		}
		$this->name = $exploded[0];
	}

	//Проверяет возможно ли вызвыать метод
	public function handle(array $db_value, string $operator, mixed $value): bool
	{
		if (in_array($operator, $this->allowedOperators)) {
			return call_user_func_array([$this, $operator], [$db_value, $value]);
		}
		return false;
	}

	// Метод равенства
	public function isEqual(array $db_value, mixed $rule_value): bool
	{
		if (is_array($this->name)) {
			foreach ($this->name as $name) {
				if ($db_value[$name] == $rule_value) {
					return true;
				}
			}
			return false;
		}

		return $db_value[$this->name] == $rule_value;
	}

	// Метод неравенства
	public function isNotEqual(array $db_value, mixed $rule_value): bool
	{

		if (is_array($this->name)) {
			foreach ($this->name as $name) {
				if ($db_value[$name] != $rule_value) {
					return true;
				}
			}
			return false;
		}

		return $db_value[$this->name] != $rule_value;
	}

	// Метод если больше
	public function isBigger(array $db_value, mixed $rule_value): bool
	{
		if (is_array($this->name)) {
			$compare = false;
			foreach ($this->name as $name) {
				if ($db_value[$name] > $rule_value) {
					return true;
				}
			}
			return false;
		}

		return $db_value[$this->name] > $rule_value;
	}

	// Меод если меньше
	public function isLower(array $db_value, mixed $rule_value): bool
	{
		if (is_array($this->name)) {
			$compare = false;
			foreach ($this->name as $name) {
				if ($db_value[$name] < $rule_value) {
					return true;
				}
			}
			return false;
		}

		return $db_value[$this->name] < $rule_value;
	}
}

// Порождающий класс
class Compare
{
	private $rule;

	//Указываем какой тип сравнения используем
	public function setRule(string $rule): void
	{
		$this->rule = new (__NAMESPACE__ . '\\' . $rule)();
		// var_dump($rule);
		// var_dump($this->rule);
	}

	public function handle(array $db_value, string $operator, mixed $value)
	{
		return $this->rule->handle($db_value, $operator, $value);
	}
}

class CountryCompare extends AbstractCompare
{
	public function __construct()
	{
		$this->setName('hotel_country_id');
		$this->allowedOperators = [Operators::isEqual->name, Operators::isNotEqual->name];
	}
}

class CityCompare extends AbstractCompare
{
	public function __construct()
	{
		$this->setName('hotel_city_id');
		$this->allowedOperators = [Operators::isEqual->name, Operators::isNotEqual->name];
	}
}
class StarsCompare extends AbstractCompare
{
	public function __construct()
	{
		$this->setName('hotel_stars');
		$this->allowedOperators = [Operators::isEqual->name, Operators::isNotEqual->name];
	}
}

class DiscountOrComissionCompare extends AbstractCompare
{
	public function __construct()
	{
		$this->setName('discount_percent,comission_percent');
		$this->allowedOperators = [Operators::isEqual->name, Operators::isNotEqual->name, Operators::isBigger->name, Operators::isLower->name];
	}
}

class IsDefaultCompare extends AbstractCompare
{
	public function __construct()
	{
		$this->setName('is_default');
		$this->allowedOperators = [Operators::isEqual->name];
	}
}

class CompanyIdCompare extends AbstractCompare
{
	public function __construct()
	{
		$this->setName('company_id');
		$this->allowedOperators = [Operators::isEqual->name, Operators::isNotEqual->name];
	}
}

class IsBlackCompare extends AbstractCompare
{
	public function __construct()
	{
		$this->setName('is_black');
		$this->allowedOperators = [Operators::isEqual->name];
	}
}

class IsRecomendedCompare extends AbstractCompare
{
	public function __construct()
	{
		$this->setName('is_recomended');
		$this->allowedOperators = [Operators::isEqual->name];
	}
}

class IsWhiteCompare extends AbstractCompare
{
	public function __construct()
	{
		$this->setName('is_white');
		$this->allowedOperators = [Operators::isEqual->name];
	}
}