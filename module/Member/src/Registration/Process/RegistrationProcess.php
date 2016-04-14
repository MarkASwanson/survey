<?php
namespace Registration\Process;

use NovumWare\Helpers\NovumWareHelpers;
use NovumWare\Process\ProcessException;
use NovumWare\Process\ProcessResult;
use Registration\Constants\EmailConstants;
use Registration\Constants\MessageConstants;
use Registration\Constants\StatusConstants;
use Registration\Model\MemberModel;

/**
 * @method \NovumWare\Process\ProcessResult registerNewMember(\Registration\Model\MemberModel $memberModel)
 * @method \NovumWare\Process\ProcessResult verifyEmail(string $email, string $securityKey)
 * @method \NovumWare\Process\ProcessResult resendEmailVerificationEmail(string $email)
 * @method \NovumWare\Process\ProcessResult registerNewMemberWithoutEmailVerification(\Registration\Model\MemberModel $memberModel)
 */
class RegistrationProcess extends \NovumWare\Process\AbstractProcess
{
	/**
	 * Register a new member and send an email verification email.
	 *
	 * @param \Registration\Model\MemberModel $memberModel
	 * @return \NovumWare\Process\ProcessResult ->data = Security key to use to verify email.
	 */
	protected function _registerNewMember(MemberModel $memberModel) {
		$memberMapper = $this->getMemberMapper();
		$existingMemberModel = $memberMapper->fetchOneForEmail($memberModel->email);
		if ($existingMemberModel) throw new ProcessException('A member already exists with that email address');

		$memberModel->password = NovumWareHelpers::encryptPassword($memberModel->password);

		$memberMapper->insertModel($memberModel);
		$this->createEmailVerificationEmail($memberModel);
	}

	/**
	 * Create an email verification key and send a verification email.
	 *
	 * @param \Registration\Model\MemberModel $memberModel
	 */
	private function createEmailVerificationEmail(MemberModel $memberModel) {
		$memberEmailVerificationsMapper = $this->getMemberEmailVerificationsMapper();
		$memberEmailVerificationModel = $memberEmailVerificationsMapper->createModelFromData();
		$memberEmailVerificationModel->email = $memberModel->email;
		$memberEmailVerificationModel->security_key = NovumWareHelpers::generateKey(32);
		$this->getMemberEmailVerificationsMapper()->insertModel($memberEmailVerificationModel);
		$this->sendEmailVerificationEmail($memberEmailVerificationModel);
	}

	/**
	 * @param string $email
	 * @return ProcessResult
	 * @throws ProcessException
	 */
	public function _resendEmailVerificationEmail($email) {
		$memberEmailVerificationModel = $this->getMemberEmailVerificationsMapper()->fetchOneForEmail($email);
		if (!$memberEmailVerificationModel) throw new ProcessException('Either that email has not been registered, or it has already been verified');
		$this->sendEmailVerificationEmail($memberEmailVerificationModel);
	}

	/**
	 * @param \Registration\Model\MemberEmailVerificationModel $memberEmailVerificationModel
	 * @return void
	 */
	private function sendEmailVerificationEmail(\Registration\Model\MemberEmailVerificationModel $memberEmailVerificationModel) {
		$verificationLink = $this->urlCanonical('register/verify-email', array(
															'email' => $memberEmailVerificationModel->email,
															'securityKey' => $memberEmailVerificationModel->security_key
														));
		// $verificationLink = $this->url('register/verify-email', array(
// 															'email' => $memberEmailVerificationModel->email,
// 															'securityKey' => $memberEmailVerificationModel->security_key
// 														), array('force_canonical'=>true));
		$this->getEmailsProcess()->sendEmailFromTemplate($memberEmailVerificationModel->email, EmailConstants::VERIFY_EMAIL_SUBJECT, EmailConstants::VERIFY_EMAIL_TEMPLATE, ['verificationLink'=>$verificationLink]);
	}

	/**
	 * Verify an email address with a security key.
	 *
	 * @param string $email Email address to verify
	 * @param string $securityKey Security key to verify against
	 * @return \NovumWare\Process\ProcessResult
	 */
	protected function _verifyEmail($email, $securityKey) {
		$memberEmailVerificationsMapper = $this->getMemberEmailVerificationsMapper();
		$memberEmailVerificationModel = $memberEmailVerificationsMapper->fetchOneForEmailAndSecurityKey($email, $securityKey);
		if (!$memberEmailVerificationModel) { throw new ProcessException(MessageConstants::ERROR_COULD_NOT_VERIFY_EMAIL); }

		$memberMapper = $this->getMemberMapper();
		$memberModel = $memberMapper->fetchOneForEmail($email);
		$this->initMember($memberModel);

		$memberEmailVerificationsMapper->deleteModel($memberEmailVerificationModel);
	}

	protected function _registerNewMemberWithoutEmailVerification(MemberModel $memberModel) {
		$memberModel->password = NovumWareHelpers::encryptPassword($memberModel->password);

		$this->getMemberMapper()->insertModel($memberModel);
		$this->initMember($memberModel);
	}


	// ========================================================================= HELPER METHODS =========================================================================
	/**
	 * @param \Registration\Model\MemberModel $memberModel
	 */
	private function initMember(MemberModel $memberModel) {
		$memberModel->status = StatusConstants::MEMBER_ACTIVE;
		$this->getMemberMapper()->updateModel($memberModel);
	}


	// ========================================================================= FACTORY METHODS =========================================================================
	/**
	 * @return \Registration\Mapper\MemberMapper
	 */
	protected function getMemberMapper() {
		if (!isset($this->memberMapper)) $this->memberMapper = $this->getServiceLocator()->get('Registration\Mapper\memberMapper');
		return $this->memberMapper;
	}

	/**
	 * @return \Registration\Mapper\MemberEmailVerificationsMapper
	 */
	protected function getMemberEmailVerificationsMapper() {
		if (!isset($this->memberEmailVerificationsMapper)) $this->memberEmailVerificationsMapper = $this->getServiceLocator()->get('Registration\Mapper\MemberEmailVerificationsMapper');
		return $this->memberEmailVerificationsMapper;
	}

	/**
	 * @return \NovumWare\Process\EmailsProcess
	 */
	protected function getEmailsProcess() {
		if (!isset($this->emailsProcess)) $this->emailsProcess = $this->getServiceLocator()->get('NovumWare\Process\EmailsProcess');
		return $this->emailsProcess;
	}

}
