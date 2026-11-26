<?php
class ControllerExtensionThemeMytheme extends Controller {
	public function headerBefore(&$route, &$data, &$code) {
		$data['color_bg'] = $this->color('theme_mytheme_color_bg', '#FAFAFA');
		$data['color_text'] = $this->color('theme_mytheme_color_text', '#111111');
		$data['color_primary'] = $this->color('theme_mytheme_color_primary', '#111111');
		$data['color_card'] = $this->color('theme_mytheme_color_card', '#FFFFFF');
		$data['color_line'] = $this->color('theme_mytheme_color_line', '#E5E5E5');
		$data['color_sale'] = $this->color('theme_mytheme_color_sale', '#DC2626');
		$data['color_ok'] = $this->color('theme_mytheme_color_ok', '#16A34A');
		$data['font_body'] = $this->font('theme_mytheme_font_body', 'Inter');
		$data['font_heading'] = $this->font('theme_mytheme_font_heading', 'Plus Jakarta Sans');
		$data['radius'] = $this->radius('theme_mytheme_radius', 14);
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

	private function radius($key, $fallback) {
		$value = $this->config->get($key);

		if ($value === null || $value === '' || !is_numeric($value)) {
			return $fallback;
		}

		$value = (int)$value;

		if ($value < 0 || $value > 32) {
			return $fallback;
		}

		return $value;
	}
	private function font($key, $fallback) {
		$value = trim((string)$this->config->get($key));

		if (preg_match('/^[A-Za-z0-9 \-]{2,40}$/', $value)) {
			return $value;
		}

		return $fallback;
	}
}
