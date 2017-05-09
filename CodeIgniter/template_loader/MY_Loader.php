<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Loader Extend Class
 *
 * Loads views and files
 *
 * @package        CodeIgniter
 * @subpackage    Libraries
 * @author        Kth007#gmail.com
 * @category    Loader
 * @link        http://codeigniter.org.cn/forums/viewthread.php?tid=8949
 */
class MY_Loader extends CI_Loader
{

    /**
     * 视图堆栈
     *
     * @var array
     */
    private $_stacks = array();

    /**
     * 当前处理的视图
     *
     * @var int
     */
    private $_current;

    /**
     * 视图的继承
     *
     * @param string $tplname
     *
     * @access protected
     */
    protected function _extends($tplname)
    {
        $this->_stacks[$this->_current]['extends'] = $tplname;
    }

    /**
     * 开始定义一个区块
     *
     * @param string $block_name
     * @param mixed $config
     *
     * @access protected
     */
    protected function _block($block_name, $config = null)
    {
        $stack =& $this->_stacks[$this->_current];
        if (!empty($stack['blocks_stacks']))
        {
            // 如果存在嵌套的 block，则需要记录下嵌套的关系
            $last = $stack['blocks_stacks'][count($stack['blocks_stacks']) - 1];
            $stack['nested_blocks'][] = array($block_name, $last);
        }
        $this->_stacks[$this->_current]['blocks_config'][$block_name] = $config;
        array_push($stack['blocks_stacks'], $block_name);
        ob_start();
    }

    /**
     * 结束一个区块
     *
     * @access protected
     */
    protected function _endblock()
    {
        $block_name = array_pop($this->_stacks[$this->_current]['blocks_stacks']);
        $this->_stacks[$this->_current]['blocks'][$block_name] = ob_get_clean();
        echo "%block_{$block_name}_{$this->_stacks[$this->_current]['id']}%";
    }


    /**
     * 载入一个视图片段
     *
     * @param string $element_name 视图名称
     * @param array $vars
     *
     * @access protected
     */
    protected function _element($element_name, array $vars = null)
    {
        $file_exists = FALSE;
        $filename = '';
        foreach ($this->_ci_view_paths as $view_file => $cascade)
        {
            $filename = $view_file.$element_name.'.php';
            if ( ! file_exists($filename))
            {
                $file_exists = FALSE;
            }else{
                $file_exists = TRUE;break;
            }
        }
        if(!$file_exists){
            show_error('Unable to load the requested file: '.$filename);
        }else{
            extract($this->_ci_cached_vars);
            if (is_array($vars)) extract($vars);
            include($filename);
        }
    }

    /**
     * Loader
     *
     * 这个函数用来加载视图或者文件.
     * 这个函数改写 CI_Loader 类内函数，使其支持视图继承和多重继承。
     *
     * @access    private
     * @param    array
     * @return    void
     */
    function _ci_load($_ci_data)
    {
        // 设置默认的数据变量
        foreach (array('_ci_view', '_ci_vars', '_ci_path', '_ci_return', '_ci_viewid', '_ci_stack') as $_ci_val)
        {
            $$_ci_val = ( ! isset($_ci_data[$_ci_val])) ? FALSE : $_ci_data[$_ci_val];
        }

        // 设置请求文件的路径
        if ($_ci_path != '')
        {
            $_ci_x = explode('/', $_ci_path);
            $_ci_file = end($_ci_x);
        }
        else
        {
            $_ci_ext = pathinfo($_ci_view, PATHINFO_EXTENSION);
            $_ci_file = ($_ci_ext == '') ? $_ci_view.'.php' : $_ci_view;

            foreach ($this->_ci_view_paths as $view_file => $cascade)
            {
                if (file_exists($view_file.$_ci_file))
                {
                    $_ci_path = $view_file.$_ci_file;
                    $file_exists = TRUE;
                    break;
                }

                if ( ! $cascade)
                {
                    break;
                }
            }
        }

        if ( ! file_exists($_ci_path))
        {
            show_error('Unable to load the requested file: '.$_ci_file);
        }

        // 这允许任何加载使用 $this->load (views, files, etc.)
        // 成为从内部控制器和模型函数访问.

        $_ci_CI =& get_instance();
        foreach (get_object_vars($_ci_CI) as $_ci_key => $_ci_var)
        {
            if ( ! isset($this->$_ci_key))
            {
                $this->$_ci_key =& $_ci_CI->$_ci_key;
            }
        }

        /*
         * 提取缓存和变量   也就是把数组下标变成变量
         *
         * You can either set variables using the dedicated $this->load_vars()
         * function or via the second parameter of this function. We'll merge
         * the two types and cache them so that views that are embedded within
         * other views can have access to these variables.
         */
        if (is_array($_ci_vars))
        {
            $this->_ci_cached_vars = array_merge($this->_ci_cached_vars, $_ci_vars);
        }
        extract($this->_ci_cached_vars);

        //-----by:kth007 20100422------------------------------------------------------------------------------
        if ( ! $_ci_viewid) $_ci_viewid = mt_rand();

        $stack = array(
            'id'            => $_ci_viewid,
            'contents'      => '',
            'extends'       => '',
            'blocks_stacks' => array(),
            'blocks'        => array(),
            'blocks_config' => array(),
            'nested_blocks' => array(),
        );
        array_push($this->_stacks, $stack);
        $this->_current = count($this->_stacks) - 1;
        unset($stack);
        //-----------------------------------------------------------------------------------

        /*
         * 缓冲输出
         *
         * We buffer the output for two reasons:
         * 1. Speed. You get a significant speed boost.
         * 2. So that the final rendered template can be
         * post-processed by the output class.  Why do we
         * need post processing?  For one thing, in order to
         * show the elapsed page load time.  Unless we
         * can intercept the content right before it's sent to
         * the browser and then stop the timer it won't be accurate.
         */
        ob_start();

        // If the PHP installation does not support short tags we'll
        // do a little string replacement, changing the short tags
        // to standard PHP echo statements.

        if ((bool) @ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE)
        {
            echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($_ci_path))));
        }
        else
        {
            include($_ci_path); // include() vs include_once() allows for multiple views with the same name
        }


        log_message('debug', 'File loaded: '.$_ci_path);

//-----by:kth007 20100422-----------------------------------------------------------------------------------------------------
        $stack = $this->_stacks[$this->_current];
        $stack['contents'] = ob_get_clean();

        // 如果有继承视图，则用继承视图中定义的块内容替换当前视图的块内容
        if (is_array($_ci_stack))
        {
            foreach ($_ci_stack['blocks'] as $block_name => $contents)
            {
                if (isset($stack['blocks_config'][$block_name]))
                {
                    switch (strtolower($stack['blocks_config'][$block_name]))
                    {
                        case 'append':
                            $stack['blocks'][$block_name] .= $contents;
                            break;
                        case 'replace':
                        default:
                            $stack['blocks'][$block_name] = $contents;
                    }
                }
                else
                {
                    $stack['blocks'][$block_name] = $contents;
                }
            }
        }
        // 如果有嵌套 block，则替换内容
        while (list($child, $parent) = array_pop($stack['nested_blocks']))
        {
            $stack['blocks'][$parent] = str_replace("%block_{$child}_{$_ci_viewid}%",
                $stack['blocks'][$child], $stack['blocks'][$parent]);
            unset($stack['blocks'][$child]);
        }
        // 保存对当前视图堆栈的修改
        $this->_stacks[$this->_current] = $stack;

        if ($stack['extends'])
        {
            //私有继承.
            $filename = "{$stack['extends']}.php";

            return $this->_ci_load(array(
                '_ci_view' => $filename,
                //'_ci_vars' => $this->_ci_cached_vars,
                '_ci_return' => $_ci_return,
                '_ci_viewid'=>$_ci_viewid,
                '_ci_stack'=>$this->_stacks[$this->_current],
            ));
        }
        else
        {
            // 最后一个视图一定是没有 extends 的
            $last = array_pop($this->_stacks);
            foreach ($last['blocks'] as $block_name => $contents)
            {
                $last['contents'] = str_replace("%block_{$block_name}_{$last['id']}%",
                    $contents, $last['contents']);
            }
            $this->_stacks = array();


            if ($_ci_return === TRUE)
            {
                @ob_end_clean();
                return $last['contents'];
            }

            if (ob_get_level() > $this->_ci_ob_level + 1)
            {
                ob_end_flush();
            }
            else
            {
                $_ci_CI->output->append_output($last['contents']);
                @ob_end_clean();
            }

        }
//--------------------------------------------------------------------------------------------


        // Return the file data if requested
        /*        if ($_ci_return === TRUE)
                {
                    $buffer = ob_get_contents();
                    @ob_end_clean();
                    return $buffer;
                }
        */
        /*
         * Flush the buffer... or buff the flusher?
         *
         * In order to permit views to be nested within
         * other views, we need to flush the content back out whenever
         * we are beyond the first level of output buffering so that
         * it can be seen and included properly by the first included
         * template and any subsequent ones. Oy!
         *
         */
        /*        if (ob_get_level() > $this->_ci_ob_level + 1)
                {
                    ob_end_flush();
                }
                else
                {
                    $_ci_CI->output->append_output(ob_get_contents());
                    @ob_end_clean();
                }
        */


    }

}

/* End of file MY_Loader.php */
/* Location: ./application/core/MY_Loader.php */