<?php
use Enums\Operators;
use Enums\OperatorsDescription;
use Enums\RulesKeys;
use Models\Agencies;
use Models\Hotels;
use Models\AgencyRules;
use Models\AgencyRulesCondition;
use Actions\CreateRuleSetAction;
use Actions\ChangeActiveRuleSetAction;
use Actions\CreateRuleCondition;
use Actions\ChangeActiveRuleCondition;
require_once('./Actions/CreateRuleSetAction.php');
require_once('./Actions/ChangeActiveRuleSetAction.php');
require_once('./Actions/CreateRuleCondition.php');
require_once('./Actions/ChangeActiveRuleCondition.php');
require_once('./Enums/RulesEnum.php');
require_once('./Enums/OperatorsEnum.php');
require_once('./Models/Agencies.php');
require_once('./Models/Hotels.php');
require_once('./Models/AgencyRules.php');
require_once('./Models/AgencyRulesCondition.php');

function response(mixed $response, int $code = 200)
{
	header("Status: $code");
	echo json_encode($response);
	die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$_POST = json_decode(file_get_contents('php://input'), true);

	switch (array_keys($_POST)[0]) {
		case 'createRuleSet':
			$action = new CreateRuleSetAction;
			response(...$action($_POST['createRuleSet'], new AgencyRules()));
			break;
		case 'changeActiveRuleSet':
			$action = new ChangeActiveRuleSetAction;
			response(...$action($_POST['changeActiveRuleSet'], new AgencyRules()));
			break;
		case 'createRuleCondition':
			$action = new CreateRuleCondition;
			response(...$action($_POST['createRuleCondition'], new AgencyRulesCondition()));
			break;
		case 'changeActiveRuleCondition':
			$action = new ChangeActiveRuleCondition;
			response(...$action($_POST['changeActiveRuleCondition'], new AgencyRulesCondition()));
			break;
		default:
			response(['status' => 'error', 'data' => 'no method'], 400);
			break;
	}

	response([]);
}

$agencyIdOptions = new Agencies()->getAll();
$hotelsIdOptions = new Hotels()->getAll();

$rules = new AgencyRules()->getAll();
$rulesConditions = new AgencyRulesCondition()->getAll();
[$rulesTypes, $allowedOperators, $allowedTypes] = RulesKeys::getAsOptions();
$operations = OperatorsDescription::getAsOptions();

// $operationsType = OperatorsDescription::getAsOptions();
?>

<!DOCTYPE html>

<head>
	<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<html>

<body>

	<div id="app">
		<header>
			<a href="/">Check hotels</a>
		</header>
		<div>
			<span>
				Create new Rule Set
			</span>
			<form method="POST" class="form">
				<label>
					Rule Name:
					<input type="text" v-model="newRuleSetForm.name" placeholder="Rule Name" aria-label="label"
						required>
				</label>

				<label>
					Text for Manager:
					<input type="text" v-model="newRuleSetForm.manager_text" placeholder="Text fir manager"
						aria-label="label" required>
				</label>

				<label>
					Agency:
					<select v-model="newRuleSetForm.agency_id" aria-label="label" required>
						<option v-for="item in agencyIdOptions" :key="item" :value="item.id">
							{{ item.name }}
						</option>
					</select>
				</label>

				<label>
					Hotel:
					<select v-model="newRuleSetForm.hotel_id" aria-label="label" required>
						<option v-for="item in hotelIdOptions" :key="item" :value="item.id">
							{{ item.name }}
						</option>
					</select>
				</label>

				<div>
					<button @click.prevent="createRuleSet" :disabled="!ruleSetFormAccepted"
						type="submit">Submit</button>
				</div>
			</form>
		</div>
		<hr />
		<div>
			<span>
				Rule Sets:
			</span>
			<table>
				<thead>
					<tr>
						<th>id</th>
						<th>name</th>
						<th>manager_text</th>
						<th>agency_id</th>
						<th>hotel_id</th>
						<th>active</th>
						<th>actions</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(item, index) in currentRules" :key="index">
						<td>{{item.id}}</td>
						<td>{{item.name}}</td>
						<td>{{item.manager_text}}</td>
						<td>{{item.agency_id}}</td>
						<td>{{item.hotel_id}}</td>
						<td>{{!!item.active}}</td>
						<td><button @click="changeActiveRuleSet(index, item.id, !item.active)">{{item.active ? 'Disable'
								:
								'Enable'}}</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<hr />
		<div>
			<span>Create new Condition</span>
			<form method="POST" class="form">
				<label>
					For Rule:
					<select v-model="newRuleConditionForm.rule_id" aria-label="label">
						<option v-for="item in currentRules" :key="item" :value="item.id">
							{{ item.name }} (id: {{ item.id }})
						</option>
					</select>
				</label>

				<label>
					Rule Type:
					<select v-model="newRuleConditionForm.rule_type" aria-label="label"
						@change="this.newRuleConditionForm.rule_value = null">
						<option v-for="(item, index) in rulesTypes" :key="item" :value="index">
							{{ item }}
						</option>
					</select>
				</label>

				<label>
					Operation Type:
					<select v-model="newRuleConditionForm.rule_operator" aria-label="label">
						<option v-for="(item, index) in operationsTypes[newRuleConditionForm.rule_type]" :key="item"
							:value="item.index">
							{{ item.value }}
						</option>
					</select>
				</label>

				<label>
					Value:
					<input v-if="allowedTypes[newRuleConditionForm.rule_type] == 'integer'"
						v-model="newRuleConditionForm.rule_value" placeholder="Value" required />
					<select v-model="newRuleConditionForm.rule_value" v-else>
						<option :value="true">True</option>
						<option :value="false">False</option>
					</select>
				</label>

				<div>
					<button @click.prevent="createRuleCondition" :disabled="!ruleConditionFormAccepted"
						type="submit">Submit</button>
				</div>
			</form>
		</div>
		<hr />
		<div>
			<span>
				Rule Sets:
			</span>
			<table>
				<thead>
					<tr>
						<th>id</th>
						<th>rule_id</th>
						<th>rule_type</th>
						<th>rule_operator</th>
						<th>rule_value</th>
						<th>active</th>
						<th>actions</th>
					</tr>
				</thead>
				<tbody>
					<tr v-for="(item, index) in currentRulesCondition" :key="index">
						<td>{{item.id}}</td>
						<td>{{item.rule_id}}</td>
						<td>{{rulesTypes[item.rule_type]}}</td>
						<td>{{allOperations[item.rule_operator]}}</td>
						<td>{{item.rule_value}}</td>
						<td>{{!!item.active}}</td>
						<td><button @click="changeActiveRuleCondition(index, item.id, !item.active)">{{item.active ?
								'Disable' :
								'Enable'}}</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

</body>

<script>
	const { createApp } = Vue

	createApp({
		data() {
			return {
				newRuleSetForm: {
					name: 'Test',
					manager_text: 'Some text for manager',
					agency_id: 1,
					hotel_id: 1
				},
				newRuleConditionForm: {
					rule_id: 1,
					rule_type: 0,
					rule_operator: 0,
					rule_value: null
				},
				agencyIdOptions: (<?= json_encode($agencyIdOptions) ?>),
				hotelIdOptions: (<?= json_encode($hotelsIdOptions) ?>),
				currentRules: <?= json_encode($rules) ?>,
				currentRulesCondition: <?= json_encode($rulesConditions) ?>,
				rulesTypes: <?= json_encode($rulesTypes) ?>,
				operationsTypes: <?= json_encode($allowedOperators) ?>,
				allowedTypes: <?= json_encode($allowedTypes) ?>,
				allOperations: <?= json_encode($operations) ?>,
			}
		},
		methods: {
			async fetch(data) {
				let res = await fetch('#', {
					method: "POST",
					headers: {
						'Content-Type': 'application/json'
					},
					body: JSON.stringify(data)
				})
				res = await res.json()
				return res
			},
			async createRuleSet(e) {
				const res = await this.fetch({ createRuleSet: this.newRuleSetForm })

				if (res.status == 'error') {
					alert('Store Error, ' + res.data);
					return;
				}
				alert('Success. New row: ' + res.data.id)
				this.currentRules.push(res.data);
			},
			async changeActiveRuleSet(index, id, isActive) {
				const res = await this.fetch({ changeActiveRuleSet: { id: id, active: Number(isActive) } })
				if (res.status == 'error') {
					alert('Update Error, ' + res.data);
					return;
				}
				alert('Successfull updated: ' + id)
				this.currentRules[ index ].active = isActive;
				console.log(res)
			},
			async changeActiveRuleCondition(index, id, isActive) {
				const res = await this.fetch({ changeActiveRuleCondition: { id: id, active: Number(isActive) } })
				if (res.status == 'error') {
					alert('Update Error, ' + res.data);
					return;
				}
				alert('Successfull updated: ' + id)
				this.currentRulesCondition[ index ].active = isActive;
				console.log(res)
			},
			async createRuleCondition(e) {
				this.newRuleConditionForm.rule_value = Number(this.newRuleConditionForm.rule_value)
				const res = await this.fetch({ createRuleCondition: this.newRuleConditionForm })
				if (res.status == 'error') {
					alert('Update Error, ' + res.data);
					return;
				}
				alert('Successfull updated: ' + res.data.id)
				this.currentRulesCondition.push(res.data);
			}
		},
		computed: {
			ruleSetFormAccepted() {
				return Object.values(this.newRuleSetForm).every(el => el !== null && el !== '')
			},
			ruleConditionFormAccepted() {
				return Object.values(this.newRuleConditionForm).every(el => el !== null && el !== '')
			}
		},
	}).mount('#app')
</script>

<style scoped>
	.form {
		display: flex;
		flex-direction: column;
		gap: .5rem;
		margin-top: 1rem;
	}

	.rules {
		display: flex;
		flex-direction: row;
		gap: 1rem;
	}

	table {
		border-collapse: collapse;
		border: 2px solid rgb(140 140 140);
		font-family: sans-serif;
		font-size: 0.8rem;
		letter-spacing: 1px;
	}

	caption {
		caption-side: bottom;
		padding: 10px;
		font-weight: bold;
	}

	thead,
	tfoot {
		background-color: rgb(228 240 245);
	}

	th,
	td {
		border: 1px solid rgb(160 160 160);
		padding: 8px 10px;
	}

	td:last-of-type {
		text-align: center;
	}

	tbody>tr:nth-of-type(even) {
		background-color: rgb(237 238 242);
	}

	tfoot th {
		text-align: right;
	}

	tfoot td {
		font-weight: bold;
	}
</style>

</html>