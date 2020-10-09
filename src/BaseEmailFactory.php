<?php declare(strict_types = 1);

namespace ProLib\Emails;

use Nette\Application\UI\ITemplateFactory;
use Nette\Mail\IMailer;
use Nette\SmartObject;

abstract class BaseEmailFactory
{

	use SmartObject;

	private ITemplateFactory $templateFactory;

	private IMailer $mailer;

	private EmailSenders $emailSenders;

	/**
	 * @internal
	 */
	final public function injectComponents(
		ITemplateFactory $templateFactory,
		IMailer $mailer,
		EmailSenders $emailSenders
	)
	{
		$this->templateFactory = $templateFactory;
		$this->mailer = $mailer;
		$this->emailSenders = $emailSenders;
	}

	protected function create(string $templateFile, ?string $templateClass = null): Email
	{
		return new Email($this->templateFactory, $this->mailer, $templateFile, $templateClass, $this->emailSenders);
	}

}
