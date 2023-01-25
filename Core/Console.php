<?php

	namespace SHORTCODE_ADDONS\Core;

	/**
	 * Description of Cloud
	 *
	 * @author biplo
	 */

	use SHORTCODE_ADDONS\Helper\Database as Database;

	class Console extends Database
	{

		const SHORTCODE_TRANSIENT_AVAILABLE_ELEMENTS = 'shortcode_addons_available_elements';
		const SHORTCODE_TRANSIENT_INSTALLED_ELEMENTS = 'shortcode_addons_installed_elements';
		const SHORTCODE_TRANSIENT_GOOGLE_FONT = 'shortcode_addons_google_font';
		const DOWNLOAD_SHORTCODE_ELEMENTS = 'https://www.oxilabdemos.com/shortcode-addons/Shortcode-Addons/Elements/';

		public $request;
		public $rawdata;
		public $styleid;
		public $childid;
		public $stored_font = [];
		public $storred_public_elements = [];

		/**
		 * Plugin fixed debugging data
		 *
		 * @since 2.0.1
		 */
		public function update_plugin()
		{
			$this->shortcode_elements(true);
			$this->google_fonts(true);
		}

		/**
		 * Plugin fixed
		 *
		 * @since 2.0.1
		 */
		public function fixed_data($agr)
		{
			return hex2bin($agr);
		}

		/**
		 * Plugin fixed debugging data
		 *
		 * @since 2.0.1
		 */
		public function fixed_debug_data($str)
		{
			return bin2hex($str);
		}

		public function rec_ElementsLiles($from = '.')
		{
			if(!is_dir($from)) {
				return false;
			}

			$rootfolder = false;

			$files = [];
			if($dh = opendir($from)) {


				while(false !== ($file = readdir($dh))) {
					// Skip '.' and '..'
					if($file == '.' || $file == '..')
						continue;
					$path = $from . '/' . $file;
					if(!(strpos($path, '/Layouts') || strpos($path, '/Admin') || strpos($path, '/Css') || strpos($path, '/Templates') || strpos($path, '/file'))) {
						if(strpos($path, 'Version.php') !== false) {
							$p = explode('/', $path);

							$key = array_search('Version.php', $p);

							$rootfolder = true;
							$version = include_once $path;
							if(is_array($version)) {
								$vs = ['1..0', 'Custom Elements', false];
								if($version[2] == true) {
									$vs = $version;
								}
								$this->storred_public_elements[$vs[1]][ucfirst($p[($key - 1)])] = [
									'type' => 'shortcode-addons',
									'name' => ucfirst($p[($key - 1)]),
									'homepage' => strtolower($p[($key - 1)]),
									'slug' => 'shortcode-addons',
									'version' => $vs[0],
									'control' => $vs[2]
								];


							}
						} elseif(is_dir($path) && $rootfolder !== true) {
							$files += $this->rec_ElementsLiles($path);

						}
					}


				}
				closedir($dh);
			}

			return $files;
		}

		/**
		 * Shortcode Addons Elements.
		 *
		 * @since 2.0.0
		 */
		public function installed_elements($force_update = false)
		{
			$response = get_transient(self::SHORTCODE_TRANSIENT_INSTALLED_ELEMENTS);
			if(!$response || $force_update) :

				$this->create_upload_folder();
				$this->rec_ElementsLiles(SA_ADDONS_UPLOAD_PATH);
				$response = $this->storred_public_elements;
				set_transient(self::SHORTCODE_TRANSIENT_INSTALLED_ELEMENTS, $response, 30 * DAY_IN_SECONDS);
			endif;
			return $response;
		}

		/**
		 * Get  template google font.
		 * @return mixed
		 *
		 * @since 2.0.0
		 */
		public function google_fonts($force_update = false)
		{
			$response = get_transient(self::SHORTCODE_TRANSIENT_GOOGLE_FONT);
			if(!$response || $force_update) {
				$folder = $this->safe_path(SA_ADDONS_PATH . '/assets/element/');
				$response = json_decode(file_get_contents($folder . 'fonts.json'), true);
				set_transient(self::SHORTCODE_TRANSIENT_GOOGLE_FONT, $response, 30 * DAY_IN_SECONDS);
			}
			return $response;
		}

		/**
		 * Elements in upload folder
		 *
		 * @since 2.0.0
		 */
		public function post_get_elements($elements = '')
		{
			if(!empty($elements)) :
				$this->rawdata = $elements;
			endif;
			$tmp_file = download_url(self::DOWNLOAD_SHORTCODE_ELEMENTS . $this->rawdata . '.zip', $timeout = 500);
			if(is_string($tmp_file)) :
				global $wp_filesystem;
				require_once ABSPATH . 'wp-admin/includes/file.php';
				WP_Filesystem();
				$folder = $wp_filesystem->wp_content_dir() . 'uploads/shortcode-addons/' . $this->rawdata;
				$files = $wp_filesystem->dirlist($folder);
				if(!empty($files)) {
					foreach($files as $file) {
						$wp_filesystem->delete($folder . $file['name'], true);
					}
				}
				$root = $wp_filesystem->wp_content_dir() . 'uploads/shortcode-addons';
				$result = unzip_file($tmp_file, $root);
				if(is_wp_error($result)) {
					return $result;
				}
				$this->installed_elements(true);
				return 'Done';
			endif;
		}

		/**
		 * Elements in upload folder
		 *
		 * @since 2.0.0
		 */
		public function post_elements_template_deactive()
		{
			$settings = json_decode(stripslashes($this->rawdata), true);
			$type = sanitize_title($settings['oxitype']);
			$name = sanitize_text_field($settings['oxideletestyle']);
			$this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->import_table WHERE type = %s and name = %s", $type, $name));
			$this->wpdb->query($this->wpdb->prepare("DELETE FROM $this->import_table WHERE type = %s and name = %s", strtolower($type), strtolower(str_replace('_', '-', $name))));
			return 'Confirm';
		}

		/**
		 * Check Template Active
		 *
		 * @since 2.0.0
		 */
		public function post_elements_template_active($data = '')
		{
			$settings = json_decode(stripslashes($this->rawdata), true);
			$type = sanitize_title($settings['oxitype']);
			$name = sanitize_text_field($settings['oxiactivestyle']);
			$d = $this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->import_table} (type, name) VALUES (%s, %s)", [$type, $name]));
			if($d == 1) :
				return admin_url('admin.php?page=shortcode-addons&oxitype=' . $type . '#' . $name . '');
			else :
				return 'Problem';
			endif;
		}

		/**
		 * Font Loader
		 *
		 * @since 2.1.0
		 */
		public function stored_font()
		{
			$type = 'shortcode-addons';
			$cache = $this->wpdb->get_results($this->wpdb->prepare("SELECT * FROM  $this->import_table WHERE type = %s ", $type), ARRAY_A);

			if(count($cache) == 0) {
				$font = ['Roboto', 'Manjari', 'Gayathri', 'Open+Sans', 'Lato', 'Chilanka', 'Montserrat', 'Roboto+Condensed', 'Source+Sans+Pro'];
				foreach($font as $value) {
					$this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->import_table} ( type, font) VALUES (%s, %s)", ['shortcode-addons', $value]));
					$redirect_id = $this->wpdb->insert_id;
					$this->stored_font[$value] = [
						'id' => $redirect_id,
						'type' => 'shortcode-addons',
						'name' => '',
						'font' => $value
					];
				}
			}
			foreach($cache as $value) {
				$this->stored_font[$value['font']] = $value;
			}
		}

		/**
		 * Get Google font.
		 *
		 * @since 2.1.0
		 */
		public function post_google_font()
		{

			if($this->rawdata != '') :
				$response = [];
				foreach($this->google_fonts() as $val) {
					if(stripos($val['font'], str_replace(' ', '+', $this->rawdata)) !== false) {
						$check = (array_key_exists($val['font'], $this->stored_font) ? 'yes' : 'no');
						$response[$val['font']] = [
							'font' => $val['font'],
							'stored' => $check
						];
					}
				}
			else :
				$this->stored_font();
				$response = [];
				$start_count = ($this->styleid != 1 ? $this->styleid : 0);
				$fetch_count = 10;
				$font_slice_array = array_slice($this->google_fonts(), $start_count, $fetch_count);
				foreach($font_slice_array as $val) {
					$check = (array_key_exists($val['font'], $this->stored_font) ? 'yes' : 'no');
					$response[$val['font']] = [
						'font' => $val['font'],
						'stored' => $check
					];
				}
			endif;
			return json_encode($response);
		}


		/**
		 * Add Google font.
		 *
		 * @since 2.1.0
		 */
		public function post_add_google_font()
		{
			if($this->rawdata != '' && !empty($this->rawdata)) {
				$data = sanitize_text_field($this->rawdata);
				$font = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->import_table WHERE type = %s AND  font = %s ", 'shortcode-addons', $data), ARRAY_A);
				if(is_array($font)) :
					return 'Someone already Saved it';
				else :
					$this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->import_table} ( type, font) VALUES (%s, %s)", ['shortcode-addons', $data]));
					return 'Stored';
				endif;
			}
		}

		/**
		 * Remove Google font.
		 *
		 * @since 2.1.0
		 */
		public function post_remove_google_font()
		{
			$data = sanitize_text_field($this->rawdata);
			$font = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->import_table WHERE type = %s AND  font = %s ", 'shortcode-addons', $data), ARRAY_A);
			if(is_array($font)) :
				$this->wpdb->query($this->wpdb->prepare("DELETE FROM {$this->import_table} WHERE id = %d ", $font['id']));
			endif;
			return 'Done';
		}

		/**
		 * Add Custom font.
		 *
		 * @since 2.1.0
		 */
		public function post_add_custom_font()
		{
			if($this->rawdata != '' && !empty($this->rawdata)) {
				$data = sanitize_text_field($this->rawdata);
				$font = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->import_table WHERE type = %s AND  font = %s AND  name = %s ", 'shortcode-addons', $data, 'custom'), ARRAY_A);
				if(is_array($font)) :
					return 'Someone already Saved it';
				else :
					$this->wpdb->query($this->wpdb->prepare("INSERT INTO {$this->import_table} ( type, name, font) VALUES (%s, %s, %s)", ['shortcode-addons', 'custom', $data]));
				endif;
				return 'Done';
			}
			return 'jamela';
		}

		/**
		 * Google font selection.
		 *
		 * @since 2.1.0
		 */
		public function post_selected_google_font()
		{
			$this->stored_font();
			return json_encode($this->stored_font);
		}

		/**
		 * Plugin fixed debugging data
		 *
		 * @since 2.0.1
		 */
		public function delete_transient()
		{
			delete_transient(self::SHORTCODE_TRANSIENT_INSTALLED_ELEMENTS);
			delete_transient(self::SHORTCODE_TRANSIENT_AVAILABLE_ELEMENTS);
		}

		/**
		 * Get  template Elements List.
		 * @return mixed
		 *
		 * @since 2.0.0
		 */
		public function shortcode_elements($force_update = false)
		{
			$response = get_transient(self::SHORTCODE_TRANSIENT_AVAILABLE_ELEMENTS);
			if(!$response || $force_update) {
				$folder = $this->safe_path(SA_ADDONS_PATH . '/assets/element/');
				$response = json_decode(file_get_contents($folder . 'elements.json'), true);
				set_transient(self::SHORTCODE_TRANSIENT_AVAILABLE_ELEMENTS, $response, 30 * DAY_IN_SECONDS);
				$installed = $this->installed_elements($force_update);
				if(count($installed) > 0) :
					$response = array_merge($response, $installed);
				endif;
			}
			return $response;
		}

	}
