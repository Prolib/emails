<?php declare(strict_types = 1);

namespace ProLib\Emails;

use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Mail\IMailer;
use Nette\Mail\Message;
use Nette\SmartObject;

final class Email
{

	use SmartObject;

	private ITemplateFactory $templateFactory;

	private IMailer $mailer;

	private Message $message;

	private EmailSenders $emailSenders;

	/** @var mixed[] */
	private array $parameters = [];

	private string $templateFile;

	private ?string $templateClass;

	private ?string $basePath;

	public function __construct(
		ITemplateFactory $templateFactory,
		IMailer $mailer,
		string $templateFile,
		?string $templateClass,
		EmailSenders $emailSenders
	)
	{
		$this->templateFactory = $templateFactory;
		$this->mailer = $mailer;
		$this->message = new Message();
		$this->emailSenders = $emailSenders;
		$this->templateFile = $templateFile;
		$this->templateClass = $templateClass;

		if ($this->emailSenders->hasSender()) {
			$this->setModule(EmailSenders::DEFAULT_MODULE);
		}
	}

	public function setModule(string $module): self
	{
		$sender = $this->emailSenders->getSender($module);

		$this->message->setFrom($sender->getEmail(), $sender->getName());

		return $this;
	}

	public function setSubject(string $subject): self
	{
		$this->message->setSubject($subject);

		return $this;
	}

	public function setParameters(array $parameters): self
	{
		$this->parameters = $parameters;

		return $this;
	}

	public function addParameter(string $name, $value): self
	{
		$this->parameters[$name] = $value;

		return $this;
	}

	public function addTo(string $email): self
	{
		$this->message->addTo($email);

		return $this;
	}

	public function getMessage(): Message
	{
		return $this->message;
	}

	public function setBasePath(?string $basePath): self
	{
		$this->basePath = $basePath;

		return $this;
	}

	public function send(): void
	{
		$this->message->setHtmlBody($this->getTemplate(), $this->basePath);

		$this->mailer->send($this->message);
	}

	private function getTemplate(): string
	{
		/** @var Template $template */
		$template = $this->templateFactory->createTemplate(null, $this->templateClass);

		return $template->renderToString($this->templateFile, $this->parameters);
	}

}
