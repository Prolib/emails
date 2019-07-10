<?php declare(strict_types = 1);

namespace ProLib\Emails;

use Nette\Application\UI\ITemplateFactory;
use Nette\Mail\IMailer;
use Nette\SmartObject;

abstract class BaseEmailFactory {

	use SmartObject;

	/** @var ITemplateFactory */
	private $templateFactory;

	/** @var array */
	private $sender = [null, null];

	/** @var IMailer */
	private $mailer;

	/**
	 * @internal
	 */
	final public function injectComponents(ITemplateFactory $templateFactory, IMailer $mailer) {
		$this->templateFactory = $templateFactory;
		$this->mailer = $mailer;
	}

	/**
	 * @internal
	 */
	public function setSender(string $from, ?string $name = null): void {
		$this->sender = [$from, $name];
	}

	protected function create(string $templateFile): Email {
		return new Email($this->templateFactory, $this->mailer, $templateFile, ...$this->sender);
	}

}
