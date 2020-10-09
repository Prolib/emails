<?php declare(strict_types = 1);

namespace ProLib\Emails\DI;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use ProLib\Emails\BaseEmailFactory;
use ProLib\Emails\EmailSenders;

final class EmailsExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'senders' => Expect::arrayOf(Expect::structure([
				'name' => Expect::string(),
				'email' => Expect::string()->required(),
			])),
		]);
	}

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();

		$def = $builder->addDefinition($this->prefix('emailSenders'))
			->setType(EmailSenders::class);

		foreach ($config->senders as $module => $sender) {
			$def->addSetup('add', [$sender->email, $sender->name, $module]);
		}
	}

	public function beforeCompile(): void {
		$builder = $this->getContainerBuilder();

		foreach ($builder->findByType(BaseEmailFactory::class) as $definition) {
			$definition->addSetup('injectComponents');
		}
	}

}
