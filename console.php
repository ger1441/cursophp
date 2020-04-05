#!/usr/bin/env php
<?php
#!/usr/bin/env php <--Esta linea indica a la terminal hacia donde debe de ir la ejecución

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new \App\Commands\HelloWorldCommand());

$application->run();