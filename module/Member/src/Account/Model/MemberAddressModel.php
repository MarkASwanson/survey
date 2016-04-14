<?php
namespace Account\Model;

class MemberAddressModel extends \NovumWare\Model\AbstractModel
{
	public $id;
	public $member_id;
	public $name;
	public $address;
	public $city;
	public $region;
	public $postal_code;
	public $country;
	public $time_created;
	public $time_updated;
}