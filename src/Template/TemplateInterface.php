<?php
namespace Songbird\Template;

interface TemplateInterface
{
    /**
     * Render a template.
     *
     * @param string $content
     * @param array  $data
     *
     * @return mixed
     */
    public function render($content, $data = null);
}