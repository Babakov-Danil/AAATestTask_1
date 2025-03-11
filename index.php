<?php

define("__BASEDIR__", __DIR__);

use Rules\Compare;
use Actions\CompareAction;
use Models\AgencyRules;
use Models\Hotels;
use Enums\RulesKeys;
use Enums\Operators;
require_once "Models/AgencyRules.php";
require_once "Models/Hotels.php";
require_once "Actions/CompareAction.php";
require_once('./Enums/RulesEnum.php');
require_once('./Enums/OperatorsEnum.php');
require_once('./Rules/Compare.php');

echo '<a href="/rules.php">Edit Rules</a> <BR><BR>';

$hotel_id = $_GET['hotel_id'] ?? 1; // отель для которого делаем проверку

$hotels = new Hotels;
$allHotels = $hotels->getAll();

echo "<label>
<span>Hotel: </span>
<select id='hotel_id'>";
foreach ($allHotels as $key => $value) {
    if ($value['id'] == $hotel_id) {
        echo ("<option selected value='" . $value['id'] . "'>" . $value['name'] . "</option>");
        continue;
    }
    echo ("<option value='" . $value['id'] . "'>" . $value['name'] . "</option>");
}
echo "</select>
</label>
<BR><BR>
<script>
    document.getElementById('hotel_id').onchange = function() {
        window.location = '?hotel_id=' + this.value;
};
</script>
";

$action = new CompareAction(new Compare, new AgencyRules, $hotels, RulesKeys::cases(), Operators::cases());

$action($hotel_id);

?>