<?php
namespace Models;
require_once('Model.php');
use Models\Model;

class Hotels extends Model
{
	public function getDataForCompare(int $id)
	{
		$stmt = $this->conn->prepare(
			"SELECT 
				h.id
				,h.name
				,h.stars as hotel_stars
				,cit.id as hotel_city_id
				,cit.country_id as hotel_country_id
				,ha.discount_percent
				,ha.comission_percent
				,ha.is_default
				,ha.company_id
				,aho.is_black
				,aho.is_recomend
				,aho.is_white
				,aho.agency_id
				,a.name as agency_name
			FROM
				hotels AS h
			left join
				cities AS cit ON
					h.city_id = cit.id
			left join
				agency_hotel_options as aho ON
					aho.hotel_id = h.id
			left join
				hotel_agreements AS ha ON
					ha.hotel_id = h.id
			left join
				agencies AS a ON
					a.id = aho.agency_id
			WHERE
				h.id = :hotel_id"
		);

		$stmt->execute(['hotel_id' => $id]);
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
}