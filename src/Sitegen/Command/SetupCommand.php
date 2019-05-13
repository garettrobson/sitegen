<?php
namespace Sitegen\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetupCommand extends QuestionCommand
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getApplication()->addDatabaseConnection(
            $this->fields['name'],
            $this->fields['username'],
            $this->fields['password'],
            $this->fields['hostname']
        )->saveUserSettings();
    }
}
