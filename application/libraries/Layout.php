<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout
{
    public $data = array();
    public $view = null;
    public $viewFolder = null;
    public $layoutsFodler = 'layouts';
    public $layout = 'main';

    /**
     * [$view_js description]
     * @var [mixed]
     */
    public $view_js;
    public $view_css = '';
    public $title = '';

    /**
     * For set $this of codeigniter
     * @var [type]
     */
    private $ci;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->title = getenv('APP_NAME');
    }

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function setLayoutFolder($layoutFolder)
    {
        $this->layoutsFodler = $layoutFolder;
    }

    public function render($view, $data = [])
    {
        $controller = str_replace('Controller', '', $this->ci->router->fetch_class());
        $slug_controller = camelToSlug($controller, '-');

        $method = $this->ci->router->fetch_method();
        $viewFolder = !($this->viewFolder) ? $slug_controller .'/'. $view : $this->viewFolder;
        // $view = !($this->view) ? $method : $this->view;

        $loadedData = array();
        $loadedData['view'] = $viewFolder;
        $loadedData['data'] = $data;
        $loadedData['title'] = $this->title;

        if (!empty($this->view_js)) {
            if (is_string($this->view_js)) {
                $view_js = $slug_controller .'/'. $this->view_js;

            } elseif (is_array($this->view_js)) {
                foreach ($this->view_js as $key => $js) {
                    $view_js[] = $slug_controller .'/'. $js;
                }

            }

            $loadedData = array_merge($loadedData, ['view_js' => $view_js]);
        }

        if (!empty($this->view_css)) {
            if (is_string($this->view_css)) {
                $view_css = $slug_controller .'/'. $this->view_css;

            } elseif (is_array($this->view_css)) {
                foreach ($this->view_css as $key => $css) {
                    $view_css[] = $slug_controller .'/'. $css;
                }

            }

            $loadedData = array_merge($loadedData, ['view_css' => $view_css]);
        }

        $layoutPath = '/'.$this->layoutsFodler.'/'.$this->layout;
        $this->ci->load->view($layoutPath, $loadedData);
    }

    public function renderPartial($view, $data = [])
    {
        $controller = str_replace('Controller', '', $this->ci->router->fetch_class());
        $slug_controller = camelToSlug($controller, '-');

        $method = $this->ci->router->fetch_method();
        $viewFolder = !($this->viewFolder) ? $slug_controller .'/'. $view : $this->viewFolder;

        $loadedData = array();
        $loadedData['data'] = $data;

        $this->ci->load->view($viewFolder, $data);
    }
}
