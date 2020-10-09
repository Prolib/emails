<?php declare(strict_types = 1);

namespace ProLib\Emails;

use Assert\Assertion;

final class EmailSenders
{

	public const DEFAULT_MODULE = 'default';

	/** @var EmailSender[] */
	private array $senders = [];

	public function add(string $email, ?string $name, string $module = self::DEFAULT_MODULE): self
	{
		$this->senders[$module] = new EmailSender($email, $name, $module);

		return $this;
	}

	public function addSender(EmailSender $sender): self
	{
		$this->senders[$sender->getModule()] = $sender;

		return $this;
	}

	public function getSender(string $module = self::DEFAULT_MODULE): EmailSender
	{
		Assertion::keyIsset($this->senders, $module, 'Email sender with module "%s" was not found');

		return $this->senders[$module];
	}

	public function hasSender(string $module = self::DEFAULT_MODULE): bool
	{
		return isset($this->senders[$module]);
	}

}
