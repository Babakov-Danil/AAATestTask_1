<?php
namespace Enums;

require_once 'AsOptionsTrait.php';
enum RulesKeys: string
{
    use AsOptions;
    case CountryCompare = 'Если страна отеля'; // =, !=, int
    case CityCompare = 'Если город отеля'; // =, !=, int
    case StarsCompare = 'Если звездность отеля'; // =, !=, int
    case DiscountOrComissionCompare = 'Если в договоре комиссия или скидка'; // =, !=, >, <, int
    case IsDefaultCompare = 'Если договор по умолчанию'; // =, bool
    case CompanyIdCompare = 'Если компания в договоре с отелем'; // =, !=, int
    case IsBlackCompare = 'Если в черном списке'; // =, bool
    case IsRecomendedCompare = 'Если рекомендованный отель'; // =, bool
    case IsWhiteCompare = 'Если в белом списке'; // =, bool

    public function allowedOperations()
    {
        return match ($this) {
            RulesKeys::CountryCompare, RulesKeys::StarsCompare, RulesKeys::CompanyIdCompare, RulesKeys::CityCompare => [
                ['index' => Operators::isEqual->value, 'value' => Operators::isEqual->getDescription()],
                ['index' => Operators::isNotEqual->value, 'value' => Operators::isNotEqual->getDescription()]
            ],
            RulesKeys::DiscountOrComissionCompare => [
                ['index' => Operators::isEqual->value, 'value' => Operators::isEqual->getDescription()],
                ['index' => Operators::isNotEqual->value, 'value' => Operators::isNotEqual->getDescription()],
                ['index' => Operators::isBigger->value, 'value' => Operators::isBigger->getDescription()],
                ['index' => Operators::isLower->value, 'value' => Operators::isLower->getDescription()],
            ],
            RulesKeys::IsDefaultCompare, RulesKeys::IsBlackCompare, RulesKeys::IsRecomendedCompare, RulesKeys::IsWhiteCompare => [
                ['index' => Operators::isEqual->value, 'value' => Operators::isEqual->getDescription()]
            ],
        };
    }

    public function allowedType()
    {
        return match ($this) {
            RulesKeys::CountryCompare, RulesKeys::CityCompare, RulesKeys::StarsCompare, RulesKeys::DiscountOrComissionCompare, RulesKeys::CompanyIdCompare => 'integer',
            RulesKeys::IsDefaultCompare, RulesKeys::IsBlackCompare, RulesKeys::IsRecomendedCompare, RulesKeys::IsWhiteCompare => 'bool',
        };
    }
}
