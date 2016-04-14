<?php
namespace Authentication\Model;

class MemberPasswordResetModel extends \NovumWare\Model\AbstractModel
{
	public $id;
	public $email;
	public $security_key;
	public $time_created;
	public $time_updated;
}