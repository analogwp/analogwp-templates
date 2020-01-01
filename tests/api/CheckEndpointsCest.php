<?php

// @codingStandardsIgnoreFile

class CheckEndpointsCest {
	public function checkRemoteLibrary( ApiTester $I ) {
		$I->sendGET( 'https://analogwp.com/wp-json/analogwp/v1/info' );
		$I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
		$I->seeResponseIsJson();
		$I->seeResponseMatchesJsonType([
			'timestamp' => 'integer',
			'library' => 'array'
		]);

		// Test Blocks.
		$I->seeResponseMatchesJsonType(
			[
				'id' => 'integer:>0',
				'siteID' => 'integer',
				'title' => 'string',
				'thumbnail' => 'string:url',
				'published' => 'integer',
				'tags' => 'array',
				'popularityIndex' => 'integer',
				'is_pro' => 'boolean',
				'requiredVersion' => 'string|null'
			],
			'$.library.blocks[0]'
		);

		// Test StyleKits.
		$I->seeResponseMatchesJsonType(
			[
				'id' => 'integer:>0',
				'title' => 'string',
				'slug' => 'string',
				'image' => 'string:url',
				'site_id' => 'integer',
				'is_pro' => 'boolean',
			],
			'$.library.stylekits[0]'
		);

		// Test Templates.
		$I->seeResponseMatchesJsonType(
			[
				'id' => 'string',
				'site_id' => 'string',
				'title' => 'string',
				'thumbnail' => 'string:url',
				'published' => 'integer',
				'url' => 'string:url',
				'type' => 'string',
				'tags' => 'array',
				'page_template' => 'string',
				'popularityIndex' => 'integer',
				'is_pro' => 'boolean',
				'version' => 'string|null',
			],
			'$.library.templates[0]'
		);

		// Test template_kits.
		$I->seeResponseMatchesJsonType(
			[
				'title' => 'string',
				'site_id' => 'integer',
				'thumbnail' => 'string:url',
			],
			'$.library.template_kits[0]'
		);
	}
}
