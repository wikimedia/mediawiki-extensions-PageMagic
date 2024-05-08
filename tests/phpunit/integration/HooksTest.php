<?php

namespace MediaWiki\Extension\PageMagic\Tests\Integration;

use MediaWiki\MediaWikiServices;
use MediaWikiIntegrationTestCase;

/**
 * @covers \MediaWiki\Extension\PageMagic\Hooks
 */
class HooksTest extends MediaWikiIntegrationTestCase {

	public function testMagicWordRegistration() {
		// Cannot use $this->getServiceContainer() in 1.35 which is supported
		$parser = MediaWikiServices::getInstance()->getParser();
		$allFunctionHooks = $parser->getFunctionHooks();

		$this->assertContains( 'fullpagenamefromid', $allFunctionHooks );
		$this->assertContains( 'pageidfromrevisionid', $allFunctionHooks );
		$this->assertContains( 'fullpagenamefromrevisionid', $allFunctionHooks );
	}
}
