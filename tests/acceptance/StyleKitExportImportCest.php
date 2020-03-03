<?php

// @codingStandardsIgnoreFile

class StyleKitExportImportCest {
	public function _before( AcceptanceTester $I ) {
		$I->loginAsAdmin();
	}

    public function exportStyleKit( AcceptanceTester $I ) {
		$I->amOnAdminPage( 'edit.php?post_type=ang_tokens' );
		$I->moveMouseOver( '.column-title.has-row-actions' );
		$I->see( 'Export Style Kit' );
		$I->click( 'Export Style Kit' );

		$I->wait(3);

		$link = $I->grabAttributeFrom( '.export-template a', 'href' );
		$url = parse_url( $link );
		parse_str( $url['query'], $params );
		$kit_id = $params['kit_id'];

	    $name = 'analog-' . $kit_id . '-' . date( 'Y-m-d' ) . '.json';
	    $I->seeFileFound( $name, $_ENV['DOWNLOAD_LOCATION'] );
    }
}
