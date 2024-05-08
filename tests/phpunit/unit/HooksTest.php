<?php

namespace MediaWiki\Extension\PageMagic\Tests\Unit;

use MediaWiki\Extension\PageMagic\Hooks;
use MediaWiki\Extension\PageMagic\PageMagic;
use MediaWikiUnitTestCase;
use Parser;

/**
 * @covers \MediaWiki\Extension\PageMagic\Hooks
 */
class HooksTest extends MediaWikiUnitTestCase {

	public function testMagicWordRegistration() {
		$parser = $this->createNoOpMock( Parser::class, [ 'setFunctionHook' ] );
		// Lucky for us, the name of the magic word and the implementing PHP
		// function are the same
		$functions = [
			'fullpagenamefromid',
			'pageidfromrevisionid',
			'fullpagenamefromrevisionid',
		];
		// Avoid withConsecutive(), deprecated and will be removed
		$parser->expects( $this->exactly( 3 ) )
			->method( 'setFunctionHook' )
			->with(
				$this->callback( static function ( $param ) use ( &$functions ) {
					return ( $param === $functions[0] );
				} ),
				$this->callback( function ( $param ) use ( &$functions ) {
					$this->assertIsArray( $param );
					$this->assertSame( PageMagic::class, $param[0] );
					return ( $param[1] === array_shift( $functions ) );
				} ),
				Parser::SFH_NO_HASH
			);
		( new Hooks() )->onParserFirstCallInit( $parser );
	}
}
