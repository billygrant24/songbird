<?php
namespace Songbird\Package\Markdown;

use League\Container\ServiceProvider;

class MarkdownServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $provides = [
        'App.Document.Transformer',
        'League\CommonMark\CommonMarkConverter',
    ];

    /**
     * Use the register method to register items with the container via the
     * protected $this->container property or the `getContainer` method
     * from the ContainerAwareTrait.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->getContainer();

        $app->add('League\CommonMark\CommonMarkConverter');
        $app->add('App.Document.Transformer', $app->resolve('Songbird\Package\Markdown\Transformer'));
    }
}