<?php declare(strict_types = 1);

namespace ProLib\Emails;

use Nette\Application\UI\ITemplateFactory;
use Nette\Mail\IMailer;

abstract class BaseEmailFactory {

	/** @var ITemplateFactory */
	private $templateFactory;

	/** @var array */
	private $from = [null, null];

	/** @var IMailer */
	private $mailer;

	public function injectComponents(ITemplateFactory $templateFactory, IMailer $mailer) {
		$this->templateFactory = $templateFactory;
		$this->mailer = $mailer;
	}

	/**
	 * @internal
	 * @param string $from
	 * @param string|null $name
	 */
	public function setDefaultFrom(string $from, ?string $name): void {
		$this->from = [$from, $name];
	}

	protected function create(string $templateFile): Email {
		$email = new Email($this->templateFactory, $this->mailer, $templateFile, ...$this->from);

		return $email;
	}

}
