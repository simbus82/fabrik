<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

class fabrikViewCoverflow extends JView
{

	function display($tmpl = 'default')
	{
		$srcs = FabrikHelperHTML::framework();
		$usersConfig = JComponentHelper::getParams('com_fabrik');
		$model = $this->getModel();
		$id = JRequest::getVar('id', $usersConfig->get('visualizationid', JRequest::getInt('visualizationid', 0)));
		$model->setId($id);
		$row = $model->getVisualization();
		$model->setListIds();
		if ($this->get('RequiredFiltersFound'))
		{
			$model->render();
		}
		$params = $model->getParams();
		$this->assign('params', $params);
		$this->assign('containerId', $this->get('ContainerId'));
		$this->assignRef('row', $row);
		$this->assign('showFilters', JRequest::getInt('showfilters', $params->get('show_filters')) === 1 ?  1 : 0);
		$this->assign('filters', $this->get('Filters'));
		$this->assign('filterFormURL', $this->get('FilterFormURL'));
		$tmplpath = JPATH_ROOT . '/plugins/fabrik_visualization/coverflow/views/coverflow/tmpl/' . $tmpl;
		$this->_setPath('template', $tmplpath);
		$srcs[] = 'media/com_fabrik/js/list.js';
		//assign something to Fabrik.blocks to ensure we can clear filters
		$str = "fabrikChart{$this->row->id} = {};";
		$str .= "\n" . "Fabrik.addBlock('vizualization_{$this->row->id}', fabrikChart{$this->row->id});";
		FabrikHelperHTML::addScriptDeclaration($srcs, $str);
		echo parent::display();
	}
}
?>