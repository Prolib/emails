<?php declare(strict_types = 1);

namespace ProLib\Emails;

final class EmailSender
{

	private string $email;

	private ?string $name;

	private string $module;

	public function __construct(string $email, ?string $name, string $module)
	{
		$this->email = $email;
		$this->name = $name;
		$this->module = $module;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getModule(): string
	{
		return $this->module;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

}
