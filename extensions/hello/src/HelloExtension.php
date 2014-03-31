<?php

namespace Pagekit\Hello;

use Pagekit\Extension\Extension;
use Pagekit\Framework\Application;
use Pagekit\System\Event\LinkEvent;
use Pagekit\Widget\Event\RegisterWidgetEvent;


class HelloExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
        parent::boot($app);

        $app->on('system.widget', function(RegisterWidgetEvent $event) {
            $event->register('Pagekit\Hello\HelloWidget');
        });

        $app->on('system.dashboard', function(RegisterWidgetEvent $event) use ($app) {
            $event->register('Pagekit\Hello\HelloWidget');
        });

        $app->on('system.link', function(LinkEvent $event) {
            $event->register('Pagekit\Hello\HelloLink');
        });
    }

    public function enable()
    {
        // run all migrations that are newer than the current version
        if ($version = $this('migrator')->run('extension://hello/migrations', $this('option')->get('hello:version'))) {
            $this('option')->set('hello:version', $version);
        }
    }

    public function disable()
    {
        // do nothing
    }

    public function uninstall()
    {
        // drop all own tables (created in migrations)
        $util = $this('db')->getUtility();
        $util->dropTable('@hello_greetings');
    }
}
