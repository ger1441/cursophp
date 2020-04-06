<?php
namespace App\Commands;

use App\Models\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    protected function configure()
    {
        $this->addArgument('email',InputArgument::REQUIRED,'The email of the user');
        $this->addArgument('password', InputArgument::OPTIONAL, 'The pass of the user');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new User();
        $user->email = $input->getArgument('email');
        $user->password = $input->getArgument('password') ? password_hash($input->getArgument('password'), PASSWORD_DEFAULT) : password_hash('mypass', PASSWORD_DEFAULT);
        $user->save();

        $output->writeln('User Create');

        return 0;
    }
}