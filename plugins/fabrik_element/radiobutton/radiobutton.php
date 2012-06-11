<?php
/**
 * Plugin element to a series of radio buttons
 * @package fabrikar
 * @author Rob Clayburn
 * @copyright (C) Rob Clayburn
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

class plgFabrik_ElementRadiobutton extends plgFabrik_ElementList
{

	protected $hasLabel = false;

	public function setId($id)
	{
		parent::setId($id);
		$params = $this->getParams();
		//set elementlist params from radio params
		$params->set('element_before_label', (bool) $params->get('radio_element_before_label', true));
		$params->set('allow_frontend_addto', (bool) $params->get('allow_frontend_addtoradio', false));
		$params->set('allowadd-onlylabel', (bool) $params->get('rad-allowadd-onlylabel', true));
		$params->set('savenewadditions', (bool) $params->get('rad-savenewadditions', false));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plgFabrik_ElementList::getIndEmailValue()
	 */

	protected function getIndEmailValue($value, $data = array(), $repeatCounter = 0)
	{
		if (empty($value))
		{
			return '';
		}
		$labels = $this->getSubOptionLabels();
		$values = $this->getSubOptionValues();
		$key = array_search($value[0], $values);
		$val = ($key === false) ? $value[0] : $labels[$key];
		return $val;
	}

	/**
	 * (non-PHPdoc)
	 * @see plgFabrik_Element::elementJavascript()
	 */

	function elementJavascript($repeatCounter)
	{
		$params = $this->getParams();
		$id = $this->getHTMLId($repeatCounter);
		$data = $this->getFormModel()->_data;
		$arVals = $this->getSubOptionValues();
		$arTxt = $this->getSubOptionLabels();
		$opts = $this->getElementJSOptions($repeatCounter);
		$opts->value  = $this->getValue($data, $repeatCounter);
		$opts->defaultVal = $this->getDefaultValue($data);
		$opts->data = empty($arVals) ? array() : array_combine($arVals, $arTxt);
		$opts->allowadd = $params->get('allow_frontend_addtoradio', false) ? true : false;
		$opts = json_encode($opts);
		JText::script('PLG_ELEMENT_RADIO_ENTER_VALUE_LABEL');
		return "new FbRadio('$id', $opts)";
	}

	/**
	 * (non-PHPdoc)
	 * @see plgFabrik_Element::prepareFilterVal()
	 */

	function prepareFilterVal($val)
	{
		$values = $this->getSubOptionValues();
		$labels = $this->getSubOptionLabels();
		for ($i = 0; $i < count($labels); $i++)
		{
			if (strtolower($labels[$i]) == strtolower($val))
			{
				$val = $values[$i];
				return $val;
			}
		}
		return $val;
	}

	/**
	 * (non-PHPdoc)
	 * @see plgFabrik_Element::getEmptyDataValue()
	 */

	function getEmptyDataValue(&$data)
	{
		$params = $this->getParams();
		$element = $this->getElement();
		if (!array_key_exists($element->name, $data))
		{
			$sel = $this->getSubInitialSelection();
			$sel = JArrayHelper::getValue($sel, 0, '');
			$arVals = $this->getSubOptionValues();
			$data[$element->name] = array(JArrayHelper::getValue($arVals, $sel, ''));
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see plgFabrik_Element::getFilterValue()
	 */

	function getFilterValue($value, $condition, $eval)
	{
		$value = $this->prepareFilterVal($value);
		$return = parent::getFilterValue($value, $condition, $eval);
		return $return;
	}

	public function canToggleValue()
	{
		return count($this->getSubOptionValues()) < 3 ? true : false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plgFabrik_ElementList::getValue()
	 */

	function getValue($data, $repeatCounter = 0, $opts = array())
	{
		$v = parent::getValue($data, $repeatCounter, $opts);
		// $$$ rob see http://fabrikar.com/forums/showthread.php?t=25965
		if (is_array($v) && count($v) == 1)
		{
			$v = $v[0];
		}
		return $v;
	}
}
?>