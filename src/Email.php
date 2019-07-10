<?php declare(strict_types=1);

namespace ProLib\Emails;

use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Nette\SmartObject;

/**
 * @internal
 */
final class Email {

	use SmartObject;

	/** @var ITemplateFactory */
	private $templateFactory;

	/** @var IMailer */
	private $mailer;

	/** @var Message */
	private $message;

	/** @var array */
	private $parameters = [];

	/** @var string */
	private $from;

	/** @var string */
	private $name;

	/** @var string */
	private $templateFile;

	public function __construct(ITemplateFactory $templateFactory, IMailer $mailer, string $templateFile, ?string $from, ?string $fromName) {
		$this->templateFactory = $templateFactory;
		$this->mailer = $mailer;
		$this->message = new Message();
		if ($from) {
			$this->setFrom($from, $fromName);
		}
		$this->templateFile = $templateFile;
	}

	public function setSubject(string $subject) {
		$this->message->setSubject($subject);

		return $this;
	}

	public function addParameter(string $name, $value) {
		$this->parameters[$name] = $value;

		return $this;
	}

	public function addTo(string $email) {
		$this->message->addTo($email);

		return $this;
	}

	public function setFrom(string $from, ?string $name = null) {
		$this->message->setFrom($this->from = $from, $this->name = $name);

		return $this;
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
		$template->setFile($this->templateFile);
		$template->setParameters($this->parameters);
		$template->_name = $this->name;
		$template->_from = $this->from;

		return (string) $template;
	}

}
