<?php
namespace Acl;

use Acl\Constants\RoleConstants;

class Acl extends \NovumWare\Zend\Permissions\Acl\Acl
{
	protected function defineRoles() {
		$this->addRole(RoleConstants::GUEST);
		$this->addRole(RoleConstants::ADMIN);
	}

	protected function defineResources() {
		$this->addResource('Authentication\Authentication');
		$this->addResource('Survey\Question');
		$this->addResource('Survey\Answer');
	}

	protected function definePermissions() {
		$this->allow(RoleConstants::GUEST, 'Authentication\Authentication');
		$this->allow(RoleConstants::GUEST, 'Survey\Question');
		$this->allow(RoleConstants::GUEST, 'Survey\Answer');

		$this->allow(RoleConstants::ADMIN);
	}
}