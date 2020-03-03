<?php

namespace Analog\Elementor\Tags;

use Elementor\Core\DynamicTags\Tag;
use Elementor\Modules\DynamicTags\Module;

class Light_Background extends Tag {
	public function get_name() {
		return 'ang_light_bg';
	}

	public function get_title() {
		return __( 'Light Background', 'ang' );
	}

	public function get_group() {
		return 'ang_classes';
	}

	public function get_categories() {
		return array( Module::TEXT_CATEGORY );
	}

	public function render() {
		echo 'sk-light-bg';
	}
}
