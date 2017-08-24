<?php

declare(strict_types=1);

namespace ProLib\Emails;

use Nette\Application\UI\ITemplateFactory;

abstract class BaseEmail {

	/** @var ITemplateFactory */
	private $templateFactory;

	/** @var array */
	private $from = [null, null];

	public function __construct(ITemplateFactory $templateFactory) {
		$this->templateFactory = $templateFactory;
	}

	public function setDefaultFrom(string $from, $name): void {
		$this->from = [$from, $name];
	}

	public function create(): Email {
		$email = new Email($this->templateFactory, ...$this->from);

		return $email;
	}
	
}
