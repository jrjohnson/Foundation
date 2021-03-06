<?php
namespace Foundation\VC;

/**
 * Foundation base controller
 * Extends LightVC controller
 * 
 * @package Foundation\vc
 */
class Controller extends \Lvc_PageController
{

    /**
     * Specifies the layout to be used in displaying the view
     * @var string
     */
    protected $layout = 'default';

    /**
     * Any actions parameters which were passed in by the Lvc_Router from the URL
     * @var array
     */
    protected $actionParams = array();

    /**
     * Stores the actionParams and then executes the parent runAction
     * @param string $actionName the action name to run.
     * @param array $actionParams the parameters to pass to the action.
     * @return void
     * */
    public function runAction($actionName, &$actionParams = array())
    {
        $this->actionParams = $actionParams;
        parent::runAction($actionName, $actionParams);
    }

    /**
     * Add a css file to be loaded
     * @param string $cssFile
     * @return null
     */
    public function addCss($cssFile)
    {
        $this->layoutVars['requiredCss'][$cssFile] = true;
    }

    /**
     * Add a javascript file to be loaded
     * @param string $jsFile
     * @return null
     */
    public function addScript($jsFile)
    {
        $this->layoutVars['requiredJs'][$jsFile] = true;
    }

    /**
     * Get ControllerAuthObject
     * @return ControllerAuth or null;
     */
    public static function getControllerAuth()
    {
        return null;
    }

    /**
     * Overridden to user FoundationVC_Config
     * @see Lvc_PageController::loadView()
     */
    protected function loadView($controllerViewName)
    {
        $view = Config::getControllerView($controllerViewName, $this->viewVars);
        if (is_null($view)) {
            throw new \Lvc_Exception(
                'Unable to load controller view "' . $controllerViewName .
                '" for controller "' . $this->controllerName . '"'
            );
        } else {
            $view->setController($this);
            $viewContents = $view->getOutput();
        }

        if ($this->useLayoutOverride) {
            $this->layout = $this->layoutOverride;
        }
        if (!empty($this->layout)) {
            // Use an explicit name for this data so we don't override some other variable...
            $this->layoutVars[Config::getLayoutContentVarName()] = $viewContents;
            $layoutView = Config::getLayoutView($this->layout, $this->layoutVars);
            if (is_null($layoutView)) {
                throw new \Lvc_Exception(
                    'Unable to load layout view "' . $this->layout . '" for controller "' . $this->controllerName . '"'
                );
            } else {
                $layoutView->setController($this);
                $layoutView->output();
            }
        } else {
            echo($viewContents);
        }
        $this->hasLoadedView = true;
    }

    /**
     * Overridden to user FoundationVC_Config
     * @see Lvc_PageController::requestAction()
     */
    protected function requestAction(
        $actionName,
        $actionParams = array(),
        $controllerName = null,
        $controllerParams = null,
        $layout = null
    ) {
        if (empty($controllerName)) {
            $controllerName = $this->controllerName;
        }
        if (is_null($controllerParams)) {
            $controllerParams = $this->params;
        }
        $controller = Config::getController($controllerName);
        if (is_null($controller)) {
            throw new \Lvc_Exception('Unable to load controller "' . $controllerName . '"');
        }
        $controller->setControllerParams($controllerParams);
        $controller->setLayoutOverride($layout);

        return $controller->getActionOutput($actionName, $actionParams);
    }
}
