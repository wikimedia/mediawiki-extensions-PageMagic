<?php

namespace MediaWiki\Extension\PageMagic\Tests\Integration;

use MediaWiki\MediaWikiServices;
use MediaWikiIntegrationTestCase;
use ParserOptions;

/**
 * Since normal parser tests do not have access to page IDs and revision IDs,
 * need a custom integration test to test the parsing.
 *
 * @covers \MediaWiki\Extension\PageMagic\PageMagic
 * @group Database
 */
class ParserTest extends MediaWikiIntegrationTestCase {

	private function getParsedOutput( string $input ): string {
		// Cannot use $this->getServiceContainer() in 1.35 which is supported
		$parser = MediaWikiServices::getInstance()->getParser();

		$title = $this->getExistingTestPage( 'PageMagicParserTest' )->getTitle();

		$parserOptions = ParserOptions::newFromAnon();
		$parserOutput = $parser->parse( $input, $title, $parserOptions );
		return $parserOutput->getText( [ 'unwrap' => true ] );
	}

	public function testPageNameFromId_none() {
		$result = $this->getParsedOutput( '{{fullpagenamefromid:}}' );
		$this->assertSame( '', $result );
	}

	public function testPageNameFromId_missing() {
		// Test with 1 more than the highest existing page id
		$maxPageId = $this->db->newSelectQueryBuilder()
			->select( 'MAX(page_id)' )
			->from( 'page' )
			->caller( __METHOD__ )
			->fetchField();
		$testId = (int)$maxPageId + 100;

		$result = $this->getParsedOutput( "{{fullpagenamefromid:$testId}}" );
		$this->assertSame( '', $result );
	}

	public function testPageNameFromId_present() {
		$page = $this->getExistingTestPage( 'Talk:Example page' );
		$pageId = $page->getTitle()->getId();

		$result = $this->getParsedOutput( "{{fullpagenamefromid:$pageId}}" );
		$this->assertSame( "<p>Talk:Example page\n</p>", $result );
	}

	public function testPageIdFromRev_none() {
		$result = $this->getParsedOutput( '{{pageidfromrevisionid:}}' );
		$this->assertSame( '', $result );
	}

	public function testPageIdFromRev_missing() {
		// Test with 1 more than the highest existing rev id
		$maxRevId = $this->db->newSelectQueryBuilder()
			->select( 'MAX(rev_id)' )
			->from( 'revision' )
			->caller( __METHOD__ )
			->fetchField();
		$testId = (int)$maxRevId + 100;

		$result = $this->getParsedOutput( "{{pageidfromrevisionid:$testId}}" );
		$this->assertSame( '', $result );
	}

	public function testPageIdFromRev_present() {
		$page = $this->getExistingTestPage( 'Talk:Example page' );
		$revId = $page->getRevisionRecord()->getId();

		$result = $this->getParsedOutput( "{{pageidfromrevisionid:$revId}}" );
		$this->assertSame( "<p>{$page->getId()}\n</p>", $result );
	}

	public function testPageNameFromRev_none() {
		$result = $this->getParsedOutput( '{{fullpagenamefromrevisionid:}}' );
		$this->assertSame( '', $result );
	}

	public function testPageNameFromRev_missing() {
		// Test with 1 more than the highest existing rev id
		$maxRevId = $this->db->newSelectQueryBuilder()
			->select( 'MAX(rev_id)' )
			->from( 'revision' )
			->caller( __METHOD__ )
			->fetchField();
		$testId = (int)$maxRevId + 100;

		$result = $this->getParsedOutput( "{{fullpagenamefromrevisionid:$testId}}" );
		$this->assertSame( '', $result );
	}

	public function testPageNameFromRev_present() {
		$page = $this->getExistingTestPage( 'Talk:Example page' );
		$revId = $page->getRevisionRecord()->getId();

		$result = $this->getParsedOutput( "{{fullpagenamefromrevisionid:$revId}}" );
		$this->assertSame( "<p>Talk:Example page\n</p>", $result );
	}

}
