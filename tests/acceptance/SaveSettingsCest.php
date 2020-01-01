<?php

// @codingStandardsIgnoreFile

class SaveSettingsCest {
    public function _before(AcceptanceTester $I) {
    	$I->loginAsAdmin();
    }

    public function shouldSaveSettings(AcceptanceTester $I) {
    	// Ensure settings can be saved.
    	$I->amOnAdminPage( 'admin.php?page=ang-settings' );

    	$I->amGoingTo( 'save page settings without any changes' );
    	$I->click( 'Save changes' );
    	$I->see( 'Your settings have been saved.' );

    	// Ensure tab click is working fine.
    	$I->click( 'Misc', '.nav-tab-wrapper' );
	    $I->see( 'Save changes' );
    }
}
