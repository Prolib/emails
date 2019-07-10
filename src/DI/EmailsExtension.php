<?php declare(strict_types = 1);

namespace ProLib\Emails\DI;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use ProLib\Emails\BaseEmailFactory;

final class EmailsExtension extends CompilerExtension {

	public function getConfigSchema(): Schema {
		return Expect::structure([
			'sender' => Expect::structure([
				'from' => Expect::string()->required(),
				'name' => Expect::string(),
			]),
		]);
	}

	public function beforeCompile(): void {
		$builder = $this->getContainerBuilder();
		$config = $this->getConfig();

		foreach ($builder->findByType(BaseEmailFactory::class) as $definition) {
			$definition->addSetup('setSender', [$config->sender->from, $config->sender->name]);
			$definition->addSetup('injectComponents');
		}
	}

}
