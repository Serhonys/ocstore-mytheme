<?php
class ControllerExtensionThemeMytheme extends Controller {
	public function headerBefore(&$route, &$data, &$code) {
		$data['color_bg'] = $this->color('theme_mytheme_color_bg', '#f4f1ec');
		$data['color_text'] = $this->color('theme_mytheme_color_text', '#1c1917');
		$data['color_primary'] = $this->color('theme_mytheme_color_primary', '#9a3412');
		$data['color_card'] = $this->color('theme_mytheme_color_card', '#fffcf8');
		$data['font_body'] = $this->font('theme_mytheme_font_body', 'Manrope');
		$data['font_heading'] = $this->font('theme_mytheme_font_heading', 'Fraunces');
		$data['tagline'] = trim(strip_tags((string)$this->config->get('theme_mytheme_tagline')));

		$phone = trim(strip_tags((string)$this->config->get('theme_mytheme_phone')));

		if ($phone !== '') {
			$data['telephone'] = $phone;
		}
	}

	private function color($key, $fallback) {
		$value = trim((string)$this->config->get($key));

		if (preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $value)) {
			return $value;
		}

		return $fallback;
	}

	private function font($key, $fallback) {
		$value = trim((string)$this->config->get($key));

		if (preg_match('/^[A-Za-z0-9 \-]{2,40}$/', $value)) {
			return $value;
		}

		return $fallback;
	}
}
