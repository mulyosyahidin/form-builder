<?php
namespace MT_Form;
class Builder {
	protected $form_id = '';

	protected
	function _button($value = '', $attributes = array(), $type = 'button') {
		if( is_array($value) ) {
			if( isset($value['type']) ) {
				unset($value['type']);
			}

			$attributes = $this->_stringify_attributes($value);
			$button = '<input type="'. $type .'"'. $attributes .' />';
		}
		else {
			$attributes = $this->_stringify_attributes($attributes);
			$button = '<input type="'. $type .'" value="'. $value .'"'. $attributes .' />';
		}

		return $button;
	}

	protected
	function _current_url() {
		$protocol = ( isset($_SERVER['HTTPS']) ? 'https' : 'http' );

		return $protocol .'://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	}

	public
	function _date($type, $name = '', $value = '', $min = '', $max = '', $attributes = array()) {
		$add_attr = array();
		if($min !== '') {
			$add_attr['min'] = $min;
		}

		if($max !== '') {
			$add_attr['max'] = $max;
		}

		$attributes = array_merge($add_attr, $attributes);

		return $this->_input($name, $value, $attributes, $type);
	}

	protected
	function _input($name = '', $value = '', $attributes = array(), $type = 'text') {
		if( is_array($name) ) {
			unset($name['type']);
			$type = array('type' => $type);
			$add_attributes = array_merge($type, $name);
			$data = $this->_stringify_attributes($add_attributes);

			$input = '<input'. $data .' />';
		}
		else {
			$attributes = $this->_stringify_attributes($attributes);

			$input = '<input type="'. $type .'" name="'. $name .'" '. (($type === 'image') ? 'src' : 'value') .'="'. $value .'"'. $attributes .' />';
		}

		return $input;
	}

	protected
	function _set_url($url) {
		$protocol = ( isset($_SERVER['HTTPS']) ? 'https' : 'http' );
		$url = ltrim($url, '/');

		return $protocol .'://'. $_SERVER['HTTP_HOST'] .'/'. $url;
	}

	protected
	function _stringify_attributes($attributes, $js = FALSE) {
		if( is_object($attributes) && count($attributes) > 0 ) {
			$attributes = (array)$attributes;
		}

		if( is_array($attributes) ) {
			$atts = '';
			if( count($attributes) === 0 ) {
				return $atts;
			}

			foreach($attributes as $key => $val) {
				if( $js ) {
					$atts .= $key .'='. $val .',';
				}
				else {
					$atts .= ' '. $key .'="'. $val .'"';
				}
			}

			return rtrim($atts, ',');
		}
		else if( is_string($attributes) && strlen($attributes) > 0 ) {
			return ' '. $attributes;
		}

		return $attributes;
	}

	public
	function checkbox($name = '', $value = '', $checked = FALSE, $attributes = array()) {
		$checked = ( ($checked === TRUE) ? array('checked' => 'checked') : array() );
		$name = ( is_array($name) ? array_merge($checked, $name) : $name );
		$attributes = ( is_array($name) ? $attributes : array_merge($checked, $attributes) );

		return $this->_input($name, $value, $attributes, 'checkbox');
	}

	public
	function close() {
		return '</form>';
	}

	public
	function color($name = '', $value = '', $attributes = array()) {
		return $this->_input($name, $value, $attributes, 'color');
	}

	public
	function datalist($id = '', $options = array(), $attributes = array(), $input_attributes = array(), $support_old_browser = FALSE, $old_browser_attributes = array()) {
		$type = isset($input_attributes['type']) ? $input_attributes['type'] : 'text';
		$name = isset($input_attributes['name']) ? $input_attributes['name'] : $id;

		unset($input_attributes['name']);
		unset($input_attributes['type']);
		unset($attributes['id']);

		$attributes = $this->_stringify_attributes($attributes);
		$input_attributes = $this->_stringify_attributes($input_attributes);
		$old_browser_attributes = $this->_stringify_attributes($old_browser_attributes);
		$datalist = '<input list="'. $id .'" type="'. $type .'" name="'. $name .'"'. $input_attributes .' />';
		$datalist .= '<datalist id="'. $id .'"'. $attributes .'>';
		$datalist .= ( ($support_old_browser === TRUE) ? '<select name="'. $name .'"'. $old_browser_attributes .'>' : '' );
		foreach($options as $key => $val) {
			$datalist .= '<option value="'. $key .'">'. $val .'</option>';
		}

		$datalist .= ( ($support_old_browser === TRUE) ? '</select>' : '' );
		foreach($options as $key => $val) {
			$datalist .= '<option value="'. $key .'">'. $val .'</option>';
		}

		$datalist .= ( ($support_old_browser === TRUE) ? '</select>' : '' );
		$datalist .= '</datalist>';

		return $datalist;
	}

	public
	function date($name = '', $value = '', $min = '', $max = '', $attributes = array()) {
		return $this->_date('date', $name = '', $value = '', $min = '', $max = '', $attributes = array());
	}

	public
	function datetime($name = '', $value = '', $min = '', $max = '', $attributes = array()) {
		return $this->_date('datetime', $name = '', $value = '', $min = '', $max = '', $attributes = array());
	}

	public
	function datetime_local($name = '', $value = '', $min = '', $max = '', $attributes = array()) {
		return $this->_date('datetime-local', $name = '', $value = '', $min = '', $max = '', $attributes = array());
	}

	public
	function dropdown($name = '', $options = array(), $selected = '', $attributes = array()) {
		$attributes = $this->_stringify_attributes($attributes);
		$select = '<select name="'. $name .'"'. $attributes .'>';
		foreach($options as $key => $val) {
			$key = empty($key) ? $val : $key;
			$val = empty($val) ? $key : $val;
			$select .= '<option value="'. $key .'"'. ( is_array($selected) ? (in_array($key, $selected) ? ' selected="selected"' : '' ) : (($key === $selected) ? ' selected="selected"' : '' ) ) .'>'. $val .'</option>';
		}

		$select .= '</select>';

		return $select;
	}

	public
	function email($name = '', $value = '', $attributes = array()) {
		return $this->_input($name, $value, $attributes, 'email');
	}

	public
	function fieldset($caption = '', $content = '', $attributes = array()) {
		$attributes = $this->_stringify_attributes($attributes);

		$fieldset = '<fieldset'. $attributes .'>';
		$fieldset .= (($caption !== '') ? '<legend>'. $title .'</legend>' : '');
		$fieldset .= $content;
		$fieldset .= '</fieldset>';

		return $fieldset;
	}

	public
	function file($name = '', $value = '', $attributes = array()) {
		return $this->_input($name, $value, $attributes, 'file');
	}

	public
	function image($name = '', $src = '', $attributes = array()) {
		return $this->_input($name, $src, $attributes, 'image');
	}

	public
	function keygen($keygen_attributes = array(), $attributes = array()) {
		$keygen = '<keygen';
		$attributes = $this->_stringify_attributes($attributes);
		foreach($keygen_attributes as $key => $val) {
			$key = empty($key) ? $val : $key;
			$val = empty($val) ? $key : $val;
			$tags = array('autofocus', 'challenge', 'disabled', 'form', 'keytype', 'name');
			if( !in_array($key, $tags) ) {
				unset($keygen_attributes[$key]);
			}

			if( isset($keygen_attributes['autofocus']) AND $keygen_attributes['autofocus'] === TRUE ) {
				$keygen_attributes['autofocus'] = 'autofocus';
			}
			else if($keygen_attributes['autofocus'] !== TRUE) {
				unset($keygen_attributes['autofocus']);
			}

			if( isset($keygen_attributes['challenge']) AND $keygen_attributes['challenge'] === TRUE ) {
				$keygen_attributes['challenge'] = 'challenge';
			}
			else if($keygen_attributes['challenge'] !== TRUE) {
				unset($keygen_attributes['challenge']);
			}

			if( isset($keygen_attributes['disabled']) AND $keygen_attributes['disabled'] === TRUE) {
				$keygen_attributes['disabled'] = 'disabled';
			}
			else if($keygen_attributes['disabled'] !== TRUE) {
				unset($keygen_attributes['disabled']);
			}

			if( isset($keygen_attributes['keytype']) ) {
				$algorithms = array('dsa', 'ec', 'rsa');
				if( !in_array($keygen_attributes['keytype'], $algorithms) ) {
					$keygen_attributes['keytype'] = 'rsa';
				}
			}
		}

		$keygen .= $this->_stringify_attributes($keygen_attributes);
		$keygen .= $attributes .' />';

		return $keygen;
	}

	public
	function label($text = '', $target = '', $attributes = array()) {
		$target = ( ($target) ? array('for' => $target) : array() );
		$attributes = array_merge($target, $attributes);
		$attributes = $this->_stringify_attributes($attributes);
		$label = '<label'. $attributes .'>'. $text .'</label>';

		return $label;
	}

	public
	function month($name = '', $value = '', $min = '', $max = '', $attributes = array()) {
		return $this->_date('month', $name = '', $value = '', $min = '', $max = '', $attributes = array());
	}

	public
	function multiselect($name = '', $options = array(), $selected = array(), $attributes = array()) {
		$attributes = array_merge(array('multiple' => 'multiple'), $attributes);

		return $this->dropdown($name, $options, $selected, $attributes);
	}

	public
	function number($name = '', $value = '', $min = '', $max = '', $attributes = array()) {
		return $this->_date('number', $name = '', $value = '', $min = '', $max = '', $attributes = array());
	}

	public
	function open($action = '', $method = 'POST', $attributes = array(), $is_multipart = FALSE) {
		if( !$action ) {
			$action = $this->_current_url();
		}
		else if(strpos($action, '://') > 6) {
			$action = $this->_set_url($action);
		}
		else if(strpos($action, '://') === FALSE) {
			$action = $this->_set_url($action);
		}

		$accept_method = array('GET', 'POST');
		$method = strtoupper($method);
		$method = in_array($method, $accept_method) ? $method : 'POST';

		$is_multipart = ( ($is_multipart === TRUE) ? array('enctype' => 'multipart/form-data') : array() );

		$this->form_id = isset($attributes['id']) ? $attributes['id'] : '';

		$attributes = array_merge($is_multipart, $attributes);
		$attributes = $this->_stringify_attributes($attributes);

		$form = '<form action="'. $action .'" method="'. $method .'"'. $attributes .'>';

	return $form;
	}

	public
	function password($name = '', $value = '', $attributes = array()) {
		return $this->_input($name, $value, $attributes, 'password');
	}

	public
	function radio($name = '', $value = '', $checked = FALSE, $attributes = array()) {
		$checked = ( ($checked === TRUE) ? array('checked' => 'checked') : array() );
		$name = is_array($name) ? array_merge($checked, $name) : $name;
		$attributes = is_array($name) ? $attributes : array_merge($checked, $attributes);

		return $this->_input($name, $value, $attributes, 'radio');
	}

	public
	function range($name = '', $value = '', $min = '', $max = '', $attributes = array()) {
		return $this->_date('range', $name = '', $value = '', $min = '', $max = '', $attributes = array());
	}

	public
	function reset($value = '', $attributes = array()) {
		return $this->_button($value, $attributes, 'reset');
	}

	public
	function search($name = '', $value = '', $attributes = array()) {
		return $this->_input($name, $value, $attributes, 'search');
	}

	public
	function submit($value = '', $attributes = array()) {
		return $this->_button($value, $attributes, 'submit');
	}

	public
	function tel($name = '', $value = '', $attributes = array()) {
		return $this->_input($name, $value, $attributes, 'tel');
	}

	public
	function text($name = '', $value = '', $attributes = array()) {
		return $this->_input($name, $value, $attributes, 'text');
	}

	public
	function textarea($name = '', $value = '', $attributes = array()) {
$textarea = '<textarea';
		if( is_array($name) ) {
			$content = isset($name['value']) ? $name['value'] : '';
			unset($name['value']);
			$attributes = $this->_stringify_attributes($name);
			$textarea .= ' '. $attributes .'>'. $content;
		}
		else {
			$attributes = $this->_stringify_attributes($attributes);
			$textarea .= ' name="'. $name .'"'. $attributes .'>'. $value;
		}

		$textarea .= '</textarea>';

		return $textarea;
	}

	public
	function time($name = '', $value = '', $min = '', $max = '', $attributes = array()) {
		return $this->_date('time', $name = '', $value = '', $min = '', $max = '', $attributes = array());
	}

	public
	function url($name = '', $value = '', $attributes = array()) {
		return $this->_input($name, $value, $attributes, 'url');
	}

	public
	function week($name = '', $value = '', $min = '', $max = '', $attributes = array()) {
		return $this->_date('week', $name = '', $value = '', $min = '', $max = '', $attributes = array());
	}

}