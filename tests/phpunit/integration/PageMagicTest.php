<?php

namespace MediaWiki\Extension\PageMagic\Tests\Integration;

use MediaWiki\Extension\PageMagic\PageMagic;
use MediaWikiIntegrationTestCase;
use Parser;

/**
 * @covers \MediaWiki\Extension\PageMagic\PageMagic
 * @group Database
 */
class PageMagicTest extends MediaWikiIntegrationTestCase {

	public function testPageNameFromId_none() {
		$parser = $this->createNoOpMock( Parser::class );
		$result = PageMagic::fullpagenamefromid( $parser, null );
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

		$parser = $this->createNoOpMock( Parser::class );
		$result = PageMagic::fullpagenamefromid( $parser, $testId );
		$this->assertSame( '', $result );
	}

	public function testPageNameFromId_present() {
		$page = $this->getExistingTestPage( 'Talk:Example page' );
		$pageId = $page->getTitle()->getId();

		$parser = $this->createNoOpMock( Parser::class );
		$result = PageMagic::fullpagenamefromid( $parser, $pageId );
		$this->assertSame( 'Talk:Example page', $result );
	}

	public function testPageIdFromRev_none() {
		$parser = $this->createNoOpMock( Parser::class );
		$result = PageMagic::pageidfromrevisionid( $parser, null );
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

		$parser = $this->createNoOpMock( Parser::class );
		$result = PageMagic::pageidfromrevisionid( $parser, $testId );
		$this->assertSame( '', $result );
	}

	public function testPageIdFromRev_present() {
		$page = $this->getExistingTestPage( 'Talk:Example page' );
		$revId = $page->getRevisionRecord()->getId();

		$parser = $this->createNoOpMock( Parser::class );
		$result = PageMagic::pageidfromrevisionid( $parser, $revId );
		$this->assertSame( $page->getId(), $result );
	}

	public function testPageNameFromRev_none() {
		$parser = $this->createNoOpMock( Parser::class );
		$result = PageMagic::fullpagenamefromrevisionid( $parser, null );
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

		$parser = $this->createNoOpMock( Parser::class );
		$result = PageMagic::fullpagenamefromrevisionid( $parser, $testId );
		$this->assertSame( '', $result );
	}

	public function testPageNameFromRev_present() {
		$page = $this->getExistingTestPage( 'Talk:Example page' );
		$revId = $page->getRevisionRecord()->getId();

		$parser = $this->createNoOpMock( Parser::class );
		$result = PageMagic::fullpagenamefromrevisionid( $parser, $revId );
		$this->assertSame( 'Talk:Example page', $result );
	}

}
