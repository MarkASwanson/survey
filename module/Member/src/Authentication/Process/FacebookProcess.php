<?php
namespace Authentication\Process;

use Facebook;

/**
 * @method \NovumWare\Process\ProcessResult getFacebookFriendsForCurrentFacebookUser()
 */
class FacebookProcess extends \NovumWare\Process\AbstractProcess
{
	protected function _getFacebookFriendsForCurrentFacebookUser() {
		$facebookFriends = $this->getFacebook()->api([
			'method'	=> 'fql.query',
			'query'		=> 'SELECT profile_url, pic_square FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1=me()) AND is_app_user = 1'
		]);
		return $facebookFriends;
	}


	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return array
	 */
	private function getAuthenticationConfig() {
		$config = $this->getServiceLocator()->get('config');
		if (isset($config['authentication'])) return $config['authentication'];
		else return [];
	}

	/**
	 * @return string|null
	 */
	private function getFacebookConfig() {
		$authenticationConfig = $this->getAuthenticationConfig();
		if (isset($authenticationConfig['facebook'])) return $authenticationConfig['facebook'];
		else return null;
	}

	/**
	 * @return Facebook
	 */
	protected function getFacebook() {
		if (!isset($this->_facebook)) $this->_facebook = new Facebook($this->getFacebookConfig());
		return $this->_facebook;
	}
}
