<?php
/**
 * OTemplate - Class used by the controllers to show the required template and its data
 */
class OTemplate {
	private $debug         = false;
	private $l             = null;
	private $templates_dir = '';
	private $template      = null;
	private $action        = '';
	private $module        = '';
	private $type          = 'html';
	private $layout        = '';
	private $params        = [];
	private $css_list      = [];
	private $ext_css_list  = [];
	private $js_list       = [];
	private $ext_js_list   = [];
	private $title         = '';
	private $json          = false;
	private $lang          = '';
	private $translator    = null;
	private $return_types  = [
		'html' => 'text/html',
		'json' => 'application/json',
		'xml'  => 'text/xml'
	];

	/**
	 * Load on startup applications configuration and check if there are translations
	 *
	 * @return void
	 */
	function __construct() {
		global $core;
		$this->debug = ($core->config->getLog('level') == 'ALL');
		if ($this->debug) {
			$this->l = new OLog();
		}

		$this->templates_dir = $core->config->getDir('app_template');
		$this->title = $core->config->getDefaultTitle();

		if ($core->config->getPlugin('translate')) {
			$this->lang = $core->config->getLang();
			$this->translator = new OTranslate();
		}
	}

	/**
	 * Logs internal information of the class
	 *
	 * @param string $str String to be logged
	 *
	 * @return void
	 */
	private function log($str) {
		if ($this->debug) {
			$this->l->debug($str);
		}
	}

	/**
	 * Set the module that is being executed
	 *
	 * @param string $m Name of the module
	 *
	 * @return void
	 */
	public function setModule($m) {
		$this->module = $m;
	}

	/**
	 * Set the action of the module to get its template
	 *
	 * @param string $a Name of the action
	 *
	 * @return void
	 */
	public function setAction($a) {
		$this->action = $a;
	}

	/**
	 * Set the return content-type (html / xml / json)
	 *
	 * @param string $t Content-type to return (html / xml / json)
	 *
	 * @return void
	 */
	public function setType($t) {
		$this->type = $t;
	}

	/**
	 * Set the content of the layout of a requested page or call
	 *
	 * @param string $l Content of the layout
	 *
	 * @return void
	 */
	public function setLayout($l) {
		if ($l === false) {
			$l = '';
		}
		$this->layout = $l;
	}

	/**
	 * Read a layout files content and set it to the current template
	 *
	 * @param string $layout Name of the layout file to be loaded
	 *
	 * @return void
	 */
	public function loadLayout($layout) {
		$this->setLayout( file_get_contents($this->templates_dir.'layout/'.$layout.'.php') );
	}

	/**
	 * Set array of CSS files to be used in the template
	 *
	 * @param string[] $cl Array of CSS file names to be included
	 *
	 * @return void
	 */
	public function setCssList($cl) {
		$this->css_list = $cl;
	}

	/**
	 * Set array of external CSS file URLs to be used in the application (eg in a CDN)
	 *
	 * @param string[] $ecl Array of external CSS file URLs to be included
	 *
	 * @return void
	 */
	public function setExtCssList($ecl) {
		$this->ext_css_list = $ecl;
	}

	/**
	 * Set array of JS files to be used in the application
	 *
	 * @param string[] $jl Array of JS file names to be included
	 *
	 * @return void
	 */
	public function setJsList($jl) {
		$this->js_list = $jl;
	}

	/**
	 * Set array of external JS file URLs to be used in the application (eg in a CDN)
	 *
	 * @param string[] $ejl Array of external JS file URLs to be included
	 *
	 * @return void
	 */
	public function setExtJsList($ejl) {
		$this->ext_js_list = $ejl;
	}

	/**
	 * Set value of the title of the page (<title> tag)
	 *
	 * @param string $t Title of the page (<title> tag)
	 *
	 * @return void
	 */
	public function setTitle($t) {
		$this->title = $t;
	}

	/**
	 * Set code language to be used on translations (eg "es", "en", "eu"...)
	 *
	 * @param string $l Code language for translations (eg "es", "en", "eu"...)
	 *
	 * @return void
	 */
	public function setLang($l) {
		$this->lang = $l;
	}

	/**
	 * Add a parameter to be used in the template (eg {{title}} -> "Osumi")
	 *
	 * @param string $key Key value in the template that will get substituted (eg {{title}})
	 *
	 * @param string|integer|float $value Value to be substituted
	 *
	 * @param string|integer $extra Optional information about the value ('nourlencode' in json files, cut strings if too long...)
	 *
	 * @return void
	 */
	public function add($key, $value, $extra=null) {
		$temp = ['name' => $key, 'value' => $value];
		if (!is_null($extra)) {
			$temp['extra'] = $extra;
		}
		array_push($this->params, $temp);
	}

	/**
	 * Adds a single item to the array of CSS files to be included in the template
	 *
	 * @param string $item Name of a CSS file to be included
	 *
	 * @return void
	 */
	public function addCss($item) {
		array_push($this->css_list, $item);
	}

	/**
	 * Adds a single item to the array of external CSS file URLs to be included in the template
	 *
	 * @param string $item Name of a CSS file URL to be included
	 *
	 * @return void
	 */
	public function addExtCss($item) {
		array_push($this->ext_css_list, $item);
	}

	/**
	 * Adds a single item to the array of JS files to be included in the template
	 *
	 * @param string $item Name of a JS file to be included
	 *
	 * @return void
	 */
	public function addJs($item) {
		array_push($this->js_list, $item);
	}

	/**
	 * Adds a single item to the array of external JS file URLs to be included in the template
	 *
	 * @param string $item Name of a JS file URL to be included
	 *
	 * @return void
	 */
	public function addExtJs($item) {
		array_push($this->ext_js_list, $item);
	}

	/**
	 * Add a php file that can have its own logic into a substitution key on the template
	 *
	 * @param string $where Key value in the template that will get substituted (eg {{users}})
	 *
	 * @param string $name Name of the partial file that will be loaded
	 *
	 * @param array $values Array of information that will be loaded into the partial
	 *
	 * @return void
	 */
	public function addPartial($where, $name, $values=[]) {
		$partial_file = $this->templates_dir.'partials/'.$name.'.php';
		if (file_exists($partial_file)) {
			ob_start();
			include($partial_file);
			$output = ob_get_contents();
			ob_end_clean();
		}
		else {
			$output = 'ERROR: No existe el archivo '.$name;
		}
		$this->add($where, $output, array_key_exists('extra', $values) ? $values['extra'] : null);
	}

	/**
	 * Similar to addPartial but instead of making a substitution on the template this function returns the processed partial
	 *
	 * @param string $name Name of the partial file that will be loaded
	 *
	 * @param array $values Array of information that will be loaded into the partial
	 *
	 * @return string Returns the partial processed with given parameters
	 */
	public function readPartial($name, $values=[]) {
		$filename = $this->templates_dir.'partials/'.$name.'.php';
		if (!file_exists($filename)) {
			return '';
		}
		ob_start();
		include($filename);
		$output = ob_get_contents();
		ob_end_clean();

		foreach ($values as $key => $value) {
			if (!is_object($value) && !is_array($value)) {
				$output = str_replace(['{{'.$key.'}}'], $value, $output);
			}
		}

		return $output;
	}

	/**
	 * Loads all the information (css, js, given parameters, translations) into the module/actions template
	 *
	 * @return string Returns the processed template with all the information
	 */
	public function process() {
		global $core;
		$this->log('[OTemplate] - Process');
		$this->log('Type: '.$this->type);
		$this->template     = file_get_contents($this->templates_dir.$this->module.'/'.$this->action.'.php');
		$this->css_list     = array_merge($this->css_list, $core->config->getCssList());
		$this->ext_css_list = array_merge($this->ext_css_list, $core->config->getExtCssList());
		$this->js_list      = array_merge($this->js_list, $core->config->getJsList());
		$this->ext_js_list  = array_merge($this->ext_js_list, $core->config->getExtJsList());

		$layout   = $this->layout;
		$str_body = $this->template;

		// If type is html, add 'title', 'css' and 'js'
		if ($this->type==='html') {
			// Add title
			$layout = str_replace(['{{title}}'], $this->title, $layout);

			// Add css
			$str_css = '';
			$this->log('CSS: '.count($this->css_list));

			foreach ($this->css_list as $css_item) {
				$str_css .= '<link rel="stylesheet" media="screen" type="text/css" href="/css/'.$css_item.'.css" />'."\n";
			}

			// Add external css
			$this->log('Ext CSS: '.count($this->ext_css_list));

			foreach ($this->ext_css_list as $ext_css_item) {
				$str_css .= '<link rel="stylesheet" media="screen" type="text/css" href="'.$ext_css_item.'" />'."\n";
			}

			$layout = str_replace(['{{css}}'], $str_css, $layout);

			// Add js
			$str_js = '';
			$this->log('JS: '.count($this->js_list));

			foreach ($this->js_list as $js_item) {
				$str_js .= '<script src="/js/'.$js_item.'.js"></script>'."\n";
			}

			// Add external js
			$this->log('Ext JS: '.count($this->ext_js_list));

			foreach ($this->ext_js_list as $ext_js_item) {
				$str_js .= '<script src="'.$ext_js_item.'"></script>'."\n";
			}

			$layout = str_replace(['{{js}}'], $str_js, $layout);
		}

		// Add parameters to the body
		$this->log('Params:');
		$this->log(var_export($this->params, true));

		foreach ($this->params as $param) {
			$sub_value = ($this->type!=='html') ? urlencode($param['value']) : $param['value'];
			if (isset($param['extra']) && $param['extra'] === 'nourlencode') {
				$sub_value = $param['value'];
			}

			$str_body = str_replace(['{{'.$param['name'].'}}'], $sub_value, $str_body);
			$layout = str_replace(['{{'.$param['name'].'}}'], $sub_value, $layout);
		}

		// Add body to the layout
		if ($this->type==='html') {
			$layout = str_replace(['{{body}}'], $str_body, $layout);
		}
		else {
			$layout = $str_body;
		}

		// Add translations
		if (!is_null($this->translator) && $this->translator->getPage()!='') {
			// Add page specific translations
			$trads = $this->translator->getTranslations();
			foreach ($trads as $trad=>$obj) {
				$layout = str_replace(['{{trans_'.$trad.'}}'], $obj[$this->lang], $layout);
			}
			// Add global translations
			$this->translator->setPage('general');
			$trads = $this->translator->getTranslations();
			foreach ($trads as $trad=>$obj) {
				$layout = str_replace(['{{trans_general_'.$trad.'}}'], $obj[$this->lang], $layout);
			}
		}

		// If type is not html is most likely it's and API call so tell the browsers not to cache it
		if ($this->type!=='html') {
			header('Cache-Control: no-cache, must-revalidate');
			header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		}
		header('Content-type: '.$this->return_types[$this->type]);

		return $layout;
	}
}