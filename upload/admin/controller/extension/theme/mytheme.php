<?php
class ControllerExtensionThemeMytheme extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/theme/mytheme');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		$store_id = isset($this->request->get['store_id']) ? (int)$this->request->get['store_id'] : 0;

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('theme_mytheme', $this->request->post, $store_id);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme', true));
		}

		$data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/theme/mytheme', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store_id, true)
		);

		$data['action'] = $this->url->link('extension/theme/mytheme', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store_id, true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme', true);

		$data['store_id'] = $store_id;

		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default'),
			'href'     => $this->url->link('extension/theme/mytheme', 'user_token=' . $this->session->data['user_token'] . '&store_id=0', true)
		);

		foreach ($this->model_setting_store->getStores() as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name'],
				'href'     => $this->url->link('extension/theme/mytheme', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $store['store_id'], true)
			);
		}

		$setting_info = $this->model_setting_setting->getSetting('theme_mytheme', $store_id);

		$fields = array(
			'theme_mytheme_status'                     => 1,
			'theme_mytheme_product_limit'              => 15,
			'theme_mytheme_product_description_length' => 100,
			'theme_mytheme_image_category_width'       => 80,
			'theme_mytheme_image_category_height'      => 80,
			'theme_mytheme_image_thumb_width'          => 640,
			'theme_mytheme_image_thumb_height'         => 640,
			'theme_mytheme_image_popup_width'          => 1200,
			'theme_mytheme_image_popup_height'         => 1200,
			'theme_mytheme_image_product_width'        => 480,
			'theme_mytheme_image_product_height'       => 600,
			'theme_mytheme_image_additional_width'     => 96,
			'theme_mytheme_image_additional_height'    => 96,
			'theme_mytheme_image_related_width'        => 480,
			'theme_mytheme_image_related_height'       => 600,
			'theme_mytheme_image_compare_width'        => 120,
			'theme_mytheme_image_compare_height'       => 120,
			'theme_mytheme_image_wishlist_width'       => 80,
			'theme_mytheme_image_wishlist_height'      => 80,
			'theme_mytheme_image_cart_width'           => 80,
			'theme_mytheme_image_cart_height'          => 80,
			'theme_mytheme_image_location_width'       => 268,
			'theme_mytheme_image_location_height'      => 80,
			'theme_mytheme_color_bg'                   => '#FAFAFA',
			'theme_mytheme_color_text'                 => '#111111',
			'theme_mytheme_color_primary'              => '#111111',
			'theme_mytheme_color_card'                 => '#FFFFFF',
			'theme_mytheme_color_line'                 => '#E5E5E5',
			'theme_mytheme_color_sale'                 => '#DC2626',
			'theme_mytheme_color_ok'                   => '#16A34A',
			'theme_mytheme_radius'                     => 14,
			'theme_mytheme_font_body'                  => 'Inter',
			'theme_mytheme_font_heading'               => 'Plus Jakarta Sans',
			'theme_mytheme_phone'                      => '',
			'theme_mytheme_tagline'                    => ''
		);

		foreach ($fields as $key => $default) {
			if (isset($this->request->post[$key])) {
				$data[$key] = $this->request->post[$key];
			} elseif (isset($setting_info[$key])) {
				$data[$key] = $setting_info[$key];
			} else {
				$data[$key] = $default;
			}
		}

		$error_keys = array(
			'product_limit',
			'product_description_length',
			'image_category',
			'image_thumb',
			'image_popup',
			'image_product',
			'image_additional',
			'image_related',
			'image_compare',
			'image_wishlist',
			'image_cart',
			'image_location'
		);

		foreach ($error_keys as $key) {
			$data['error_' . $key] = isset($this->error[$key]) ? $this->error[$key] : '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/theme/mytheme', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/theme/mytheme')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['theme_mytheme_product_limit'])) {
			$this->error['product_limit'] = $this->language->get('error_limit');
		}

		if (empty($this->request->post['theme_mytheme_product_description_length'])) {
			$this->error['product_description_length'] = $this->language->get('error_limit');
		}

		$images = array(
			'image_category',
			'image_thumb',
			'image_popup',
			'image_product',
			'image_additional',
			'image_related',
			'image_compare',
			'image_wishlist',
			'image_cart',
			'image_location'
		);

		foreach ($images as $image) {
			$width = isset($this->request->post['theme_mytheme_' . $image . '_width']) ? $this->request->post['theme_mytheme_' . $image . '_width'] : '';
			$height = isset($this->request->post['theme_mytheme_' . $image . '_height']) ? $this->request->post['theme_mytheme_' . $image . '_height'] : '';

			if (!$width || !$height) {
				$this->error[$image] = $this->language->get('error_' . $image);
			}
		}

		$colors = array(
			'theme_mytheme_color_bg',
			'theme_mytheme_color_text',
			'theme_mytheme_color_primary',
			'theme_mytheme_color_card',
			'theme_mytheme_color_line',
			'theme_mytheme_color_sale',
			'theme_mytheme_color_ok'
		);

		foreach ($colors as $color) {
			if (empty($this->request->post[$color]) || !preg_match('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $this->request->post[$color])) {
				$this->error['warning'] = $this->language->get('error_color');
			}
		}

		if (!isset($this->request->post['theme_mytheme_radius']) || $this->request->post['theme_mytheme_radius'] === '' || !is_numeric($this->request->post['theme_mytheme_radius'])) {
			$this->error['warning'] = $this->language->get('error_radius');
		}

		foreach (array('theme_mytheme_font_body', 'theme_mytheme_font_heading') as $font) {
			if (empty($this->request->post[$font]) || !preg_match('/^[A-Za-z0-9 \-]{2,40}$/', $this->request->post[$font])) {
				$this->error['warning'] = $this->language->get('error_font');
			}
		}

		return !$this->error;
	}

	public function install() {
		$this->load->model('setting/event');

		$this->model_setting_event->deleteEventByCode('mytheme');

		$this->model_setting_event->addEvent('mytheme', 'catalog/view/common/header/before', 'extension/theme/mytheme/headerBefore', 1, 0);
	}

	public function uninstall() {
		$this->load->model('setting/event');

		$this->model_setting_event->deleteEventByCode('mytheme');
	}
}
