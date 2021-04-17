<?php

/**
 * Library untuk setting menu per user
 * @author Ilham D. Sofyan
 */
class Menuhelper
{
	/**
     * @inheritdoc
     */
    public $linkTemplate = '<a href="{url}">{icon} {label}</a>';
    /**
     * @inheritdoc
     * Styles all labels of items on sidebar by AdminLTE
     */
    public $labelTemplate = '<span class="title">{label}</span>';
    public $submenuTemplate = "\n<ul class='sub-menu' {show}>\n{items}\n</ul>\n";
    public $activateParents = true;
    public $defaultIconHtml = '<i class="fa fa-circle-o"></i> ';
    public $options = [
    	'class' => 'page-sidebar-menu page-header-fixed',
    	'data-keep-expanded' => 'false',
    	'data-auto-scroll' => 'true',
    	'data-slide-speed' => '200',
    	'style' => 'padding-top: 20px'

    ];

    /**
     * @var string the CSS class to be appended to the active menu item.
     */
    public $activeCssClass = 'active';

    /**
     * @var string is prefix that will be added to $item['icon'] if it exist.
     * By default uses for Font Awesome (http://fontawesome.io/)
     */
    public static $iconClassPrefix = 'fa fa-';

    private $noDefaultAction;
    private $noDefaultRoute;

    private $group;
    private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model([
			'master/menu',
		]);
	}

    /**
     * Renders the menu.
     */
    public function run()
    {
        $this->group = $this->CI->session->userdata('group_id');
        $this->items = $this->CI->session->userdata('menus');
        $this->route = $this->CI->helpers->getRoute();
        $this->params = $this->CI->helpers->getQueryParams();
        $this->noDefaultAction = false;
        $this->noDefaultRoute = false;

        $items = $this->normalizeItems($this->items, $hasActiveChild);
        if (!empty($items)) {
            $options = $this->options;
            $tag = $this->CI->helpers->arrayRemove($options, 'tag', 'ul');

            echo Html::tag($tag, $this->renderItems($items), $options);
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderItem($item)
    {
        if (isset($item['items'])) {
            // $labelTemplate = '<a href="{url}">{icon} {label} <span class="pull-right-container"><i class="fa fa-plus pull-right"></i><i class="fa fa-minus pull-right"></i></span></a>';
            // $linkTemplate = '<a href="{url}">{icon} {label} <span class="pull-right-container"><i class="fa fa-plus pull-right"></i><i class="fa fa-minus pull-right"></i></span></a>';
            $labelTemplate = '<a href="{url}" class="nav-link nav-toggle">{icon} {label}<span class="arrow "></span></a>';
            $linkTemplate = '<a href="{url}" class="nav-link nav-toggle">{icon} {label}<span class="arrow "></span></a>';
        } else {
            $labelTemplate = $this->labelTemplate;
            $linkTemplate = $this->linkTemplate;
        }

        $replacements = [
            '{label}' => strtr($this->labelTemplate, ['{label}' => $item['label'],]),
            '{icon}' => empty($item['icon']) ? $this->defaultIconHtml
                : '<i class="' . static::$iconClassPrefix . $item['icon'] . '"></i> ',
            '{url}' => isset($item['url']) ? site_url($item['url']) : 'javascript:void(0);',
        ];

        $template = $linkTemplate;

        return strtr($template, $replacements);
    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            if (!empty($item['assign'])) {
                $compare = array_intersect($this->group, $item['assign']);
                if (!empty($compare)) {
                    $options = [];
                    $tag = $this->CI->helpers->arrayRemove($options, 'tag', 'li');
                    $class = [];

                    if ($item['active']) {
                        $class[] = $this->activeCssClass;
                    }

                    if (!empty($class)) {
                        if (empty($options['class'])) {
                            $options['class'] = implode(' ', $class);
                        } else {
                            $options['class'] .= ' ' . implode(' ', $class);
                        }
                    }
                    $menu = $this->renderItem($item);
                    if (!empty($item['items'])) {
                        $menu .= strtr($this->submenuTemplate, [
                            '{show}' => $item['active'] ? "style='display: block'" : '',
                            '{items}' => $this->renderItems($item['items']),
                        ]);
                        if (isset($options['class'])) {
                            $options['class'] .= ' nav-item';
                        } else {
                            $options['class'] = 'nav-item';
                        }
                    }
                    $lines[] = Html::tag($tag, $menu, $options);
                }
            }
        }
        return implode("\n", $lines);
    }

    /**
     * @inheritdoc
     */
    protected function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if (!isset($item['label'])) {
                $item['label'] = '';
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : true;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $items[$i]['icon'] = isset($item['icon']) ? $item['icon'] : '';
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                if (empty($items[$i]['items']) && $this->hideEmptyItems) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
            }
            if (!isset($item['active'])) {
                if ($this->isItemActive($item)) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
            }
        }
        return array_values($items);
    }

    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item)
    {
        $url = $item['url'];

        # Jika url item adalah string, ubah ke bentuk array
        if (is_string($url)) {
            $this->urlToArray($item);
        }

        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];

            // if (isset($route[0]) && $route[0] !== '/' && Yii::$app->controller) {
            //     $route = ltrim(Yii::$app->controller->module->getUniqueId() . '/' . $route, '/');
            // }

            $route = ltrim($route, '/');

            if ($route != $this->route && $route !== $this->noDefaultRoute && $route !== $this->noDefaultAction) {
                return false;
            }

            unset($item['url']['#']);

            if (count($item['url']) == 1 && !empty($this->params)) {
                return false;
            }

            if (count($item['url']) > 1) {
                foreach (array_splice($item['url'], 1) as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }

            return true;
        }
        return false;
    }

    /**
     * Ubah format url dari string ke array
     * <awal> /path/to/site?param1=param
     * @param  array  &$item [description]
     * @return array         ['/path/to/site', 'param1' => 'param']
     */
    protected function urlToArray(&$item)
    {
        $parse_url = parse_url($item['url']);

        if ($parse_url) {
            $path = $parse_url['path'];
            $params = [];

            # Set Url
            $item['url'] = [$path];

            if (!empty($parse_url['query'])) {
                $query = $parse_url['query'];
                $queries = explode('&', $query);

                foreach ($queries as $key => $value) {
                    $split_equal = explode('=', $value);
                    $params += [$split_equal[0] => $split_equal[1]];
                }

                $item['url'] += $params;
            }
        }
    }
}
