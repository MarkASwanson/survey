<?php
namespace Registration\Model;

class MemberEmailVerificationModel extends \NovumWare\Model\AbstractModel
{
	public $id;
	public $email;
	public $security_key;
	public $time_created;
	public $time_updated;
}