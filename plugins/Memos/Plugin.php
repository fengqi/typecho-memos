<?php

namespace TypechoPlugin\Memos;

use Typecho\Plugin\PluginInterface;
use Typecho\Plugin\Exception;
use Typecho\Widget\Helper\Form;
use Typecho\Widget\Helper\Form\Element\Text;
use Typecho\Widget\Helper\Form\Element\Radio;
use Utils\Helper;

/**
 * Memos 插件
 *
 * @package Memos
 * @author fengqi
 * @version 0.0.1
 * @link https://fengqi.me
 */
class Plugin implements PluginInterface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Exception
     */
    public static function activate()
    {
        // todo
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Exception
     */
    public static function deactivate()
    {
        // todo
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Form $form 配置面板
     * @return void
     */
    public static function config(Form $form)
    {
        $openApi = new Text('open_api', NULL, NULL, _t('OpenAPI'), _t('点击个人设置，复制OpenAPI下方输入框内的完整链接。'));
        $form->addInput($openApi);

        $visibility = array("PRIVATE" => "仅自己可见", "PROTECTED" => "登录用户可见", "PUBLIC" => "所有人可见");
        $visibility = new Radio('visibility', $visibility, "PUBLIC", _t('选择可见性'), "仅展示选择的可见性的memo。");
        $form->addInput($visibility);

        $pageSize = new Text('page_size', NULL, NULL, _t('每页显示条数'), _t('建议20条为佳。'));
        $form->addInput($pageSize);
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Form $form)
    {
        // todo
    }
}