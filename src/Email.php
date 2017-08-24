<?php

declare(strict_types=1);

namespace ProLib\Emails;

use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Mail\IMailer;
use Nette\Mail\Message;

/**
 * @internal
 */
final class Email {

	/** @var ITemplateFactory */
	private $templateFactory;

	/** @var IMailer */
	private $mailer;

	/** @var Message */
	private $message;

	/** @var array */
	private $parameters = [];

	public function __construct(ITemplateFactory $templateFactory, IMailer $mailer, ?string $from, ?string $fromName) {
		$this->templateFactory = $templateFactory;
		$this->mailer = $mailer;
		$this->message = new Message();
		if ($from) {
			$this->message->setFrom($from, $fromName);
		}
	}

	public function addParameter(string $name, $value): void {
		$this->parameters[$name] = $value;
	}

	public function addTo(string $email): void {
		$this->message->addTo($email);
	}

	public function setFrom(string $from, ?string $name = null) {
		$this->message->setFrom($from, $name);
	}

	public function getMessage(): Message {
		return $this->message;
	}

	public function send(): void {
		$this->message->setHtmlBody($this->getTemplate());

		$this->mailer->send($this->message);
	}

	private function getTemplate(): string {
		/** @var Template $template */
		$template = $this->templateFactory->createTemplate();
		$template->setParameters($this->parameters);

		return (string) $template;
	}

}
