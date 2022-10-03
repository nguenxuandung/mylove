<?php

namespace App\View\Helper;

use Cake\View\Helper;

/**
 * @property \Cake\View\Helper\HtmlHelper $Html
 * @property \Cake\View\Helper\UrlHelper $Url
 */
class AssetsHelper extends Helper
{
    public $helpers = ['Html', 'Url'];

    public function css($url)
    {
        $css = $this->Html->css($url, ['fullBase' => true]);

        static $assets_cdn_url;
        if (!isset($assets_cdn_url)) {
            $assets_cdn_url = get_option('assets_cdn_url');
        }

        if (!empty($assets_cdn_url)) {
            $css = str_replace($_SERVER['HTTP_HOST'], $assets_cdn_url, $css);
        }

        return $css;
    }

    public function script($url)
    {
        $script = $this->Html->script($url, ['fullBase' => true]);

        static $assets_cdn_url;
        if (!isset($assets_cdn_url)) {
            $assets_cdn_url = get_option('assets_cdn_url');
        }

        if (!empty($assets_cdn_url)) {
            $script = str_replace($_SERVER['HTTP_HOST'], $assets_cdn_url, $script);
        }

        return $script;
    }

    public function image($url)
    {
        if (empty($url)) {
            return '';
        }

        $image = $this->Html->image($url, ['fullBase' => true]);

        static $assets_cdn_url;
        if (!isset($assets_cdn_url)) {
            $assets_cdn_url = get_option('assets_cdn_url');
        }

        if (!empty($assets_cdn_url)) {
            $image = str_replace($_SERVER['HTTP_HOST'], $assets_cdn_url, $image);
        }

        return $image;
    }

    public function url($url)
    {
        if (empty($url)) {
            return '';
        }

        $url = $this->Url->build($url, ['fullBase' => true]);

        static $assets_cdn_url;
        if (!isset($assets_cdn_url)) {
            $assets_cdn_url = get_option('assets_cdn_url');
        }

        if (!empty($assets_cdn_url)) {
            $url = str_replace($_SERVER['HTTP_HOST'], $assets_cdn_url, $url);
        }

        return $url;
    }

    public function favicon()
    {
        $url = get_option('favicon_url', '/favicon.ico');
        $url = $this->Url->build($url, ['fullBase' => true]);

        static $assets_cdn_url;
        if (!isset($assets_cdn_url)) {
            $assets_cdn_url = get_option('assets_cdn_url');
        }

        if (!empty($assets_cdn_url)) {
            $url = str_replace($_SERVER['HTTP_HOST'], $assets_cdn_url, $url);
        }

        return "<link href='$url' type='image/x-icon' rel='icon'/>" .
            "<link href='$url' type='image/x-icon' rel='shortcut icon'/>";
    }
}
