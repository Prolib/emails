<?php

declare(strict_types=1);

namespace ProLib\Emails;

use Nette\Application\UI\ITemplateFactory;
use Nette\Mail\IMailer;

abstract class BaseEmail {

	/** @var ITemplateFactory */
	private $templateFactory;

	/** @var array */
	private $from = [null, null];

	/** @var string */
	protected $baseDir;

	/** @var IMailer */
	private $mailer;

	public function injectComponents(string $baseDir, ITemplateFactory $templateFactory, IMailer $mailer) {
		$this->templateFactory = $templateFactory;
		$this->mailer = $mailer;
		$this->baseDir = $baseDir;
	}

	abstract protected function getTemplateFile(): string;

	public function setDefaultFrom(string $from, $name): void {
		$this->from = [$from, $name];
	}

	public function create(): Email {
		$email = new Email($this->templateFactory, $this->mailer, $this->getTemplateFile(), ...$this->from);

		return $email;
	}
	
}
