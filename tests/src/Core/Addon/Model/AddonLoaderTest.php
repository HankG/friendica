<?php

// Copyright (C) 2010-2024, the Friendica project
// SPDX-FileCopyrightText: 2010-2024 the Friendica project
//
// SPDX-License-Identifier: AGPL-3.0-or-later

namespace Friendica\Test\src\Core\Addon\Model;

use Friendica\Core\Addon\Exception\AddonInvalidConfigFileException;
use Friendica\Core\Addon\Model\AddonLoader;
use Friendica\Core\Config\Capability\IManageConfigValues;
use Friendica\Test\MockedTestCase;
use Friendica\Test\Util\VFSTrait;
use org\bovigo\vfs\vfsStream;

class AddonLoaderTest extends MockedTestCase
{
	use VFSTrait;

	protected $structure = [
		'addon' => [
			'testaddon1' => [
				'static' => [],
			],
			'testaddon2' => [
				'static' => [],
			],
			'testaddon3' => [],
		]
	];

	protected $addons = [
		'testaddon1',
		'testaddon2',
		'testaddon3',
	];

	protected $content = <<<EOF
<?php

return [
	\Friendica\Core\Hooks\Capability\BehavioralHookType::STRATEGY => [
		\Psr\Log\LoggerInterface::class => [
			\Psr\Log\NullLogger::class => [''],
		],
	],
];
EOF;

	protected function setUp(): void
	{
		parent::setUp();

		$this->setUpVfsDir();
	}

	public function dataHooks(): array
	{
		return [
			'normal' => [
				'structure' => $this->structure,
				'enabled'   => $this->addons,
				'files'     => [
					'addon/testaddon1/static/hooks.config.php' => $this->content,
				],
				'assertion' => [
					\Friendica\Core\Hooks\Capability\BehavioralHookType::STRATEGY => [
						\Psr\Log\LoggerInterface::class => [
							\Psr\Log\NullLogger::class => [''],
						],
					],
				],
			],
			'double' => [
				'structure' => $this->structure,
				'enabled'   => $this->addons,
				'files'     => [
					'addon/testaddon1/static/hooks.config.php' => $this->content,
					'addon/testaddon2/static/hooks.config.php' => $this->content,
				],
				'assertion' => [
					\Friendica\Core\Hooks\Capability\BehavioralHookType::STRATEGY => [
						\Psr\Log\LoggerInterface::class => [
							\Psr\Log\NullLogger::class => ['', ''],
						],
					],
				],
			],
			'wrongName' => [
				'structure' => $this->structure,
				'enabled'   => $this->addons,
				'files'     => [
					'addon/testaddon1/static/wrong.config.php' => $this->content,
				],
				'assertion' => [
				],
			],
			'doubleNutOnlyOneEnabled' => [
				'structure' => $this->structure,
				'enabled'   => ['testaddon1'],
				'files'     => [
					'addon/testaddon1/static/hooks.config.php' => $this->content,
					'addon/testaddon2/static/hooks.config.php' => $this->content,
				],
				'assertion' => [
					\Friendica\Core\Hooks\Capability\BehavioralHookType::STRATEGY => [
						\Psr\Log\LoggerInterface::class => [
							\Psr\Log\NullLogger::class => [''],
						],
					],
				],
			]
		];
	}

	/**
	 * @dataProvider dataHooks
	 */
	public function testAddonLoader(array $structure, array $enabledAddons, array $files, array $assertion)
	{
		vfsStream::create($structure)->at($this->root);

		foreach ($files as $file => $content) {
			vfsStream::newFile($file)
					 ->withContent($content)
					 ->at($this->root);
		}

		$configArray = [];
		foreach ($enabledAddons as $enabledAddon) {
			$configArray[$enabledAddon] = ['test' => []];
		}

		$config = \Mockery::mock(IManageConfigValues::class);
		$config->shouldReceive('get')->with('addons')->andReturn($configArray)->once();

		$addonLoader = new AddonLoader($this->root->url(), $config);

		self::assertEquals($assertion, $addonLoader->getActiveAddonConfig('hooks'));
	}

	/**
	 * Test the exception in case of a wrong addon content
	 */
	public function testWrongContent()
	{
		$filename     = 'addon/testaddon1/static/hooks.config.php';
		$wrongContent = "<?php return 'wrong';";

		vfsStream::create($this->structure)->at($this->root);

		vfsStream::newFile($filename)
				 ->withContent($wrongContent)
				 ->at($this->root);

		$configArray = [];
		foreach ($this->addons as $enabledAddon) {
			$configArray[$enabledAddon] = ['test' => []];
		}

		$config = \Mockery::mock(IManageConfigValues::class);
		$config->shouldReceive('get')->with('addons')->andReturn($configArray)->once();

		$addonLoader = new AddonLoader($this->root->url(), $config);

		self::expectException(AddonInvalidConfigFileException::class);
		self::expectExceptionMessage(sprintf('Error loading config file %s', $this->root->getChild($filename)->url()));

		$addonLoader->getActiveAddonConfig('hooks');
	}

	/**
	 * Test that nothing happens in case there are wrong addons files, but they're not used
	 */
	public function testNoHooksConfig()
	{
		$filename     = 'addon/testaddon1/static/hooks.config.php';
		$wrongContent = "<?php return 'wrong';";

		vfsStream::create($this->structure)->at($this->root);

		vfsStream::newFile($filename)
				 ->withContent($wrongContent)
				 ->at($this->root);

		$configArray = [];
		foreach ($this->addons as $enabledAddon) {
			$configArray[$enabledAddon] = ['test' => []];
		}

		$config = \Mockery::mock(IManageConfigValues::class);
		$config->shouldReceive('get')->with('addons')->andReturn($configArray)->once();

		$addonLoader = new AddonLoader($this->root->url(), $config);
		self::assertEmpty($addonLoader->getActiveAddonConfig('anythingElse'));
	}
}
