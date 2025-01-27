<?php

namespace App\Command;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add-person',
    description: 'Create a new person',
)]
class AddPersonCommand extends Command
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('firstName', InputArgument::OPTIONAL, 'First name of the person')
            ->addArgument('lastName', InputArgument::OPTIONAL, 'Last name of the person')
            ->addArgument('mail', InputArgument::OPTIONAL, 'Mail of the person')
            ->addOption('confirm', null, InputOption::VALUE_NONE, 'Confirm the creation')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $firstName = $input->getArgument('firstName');
        $lastName = $input->getArgument('lastName');
        $mail = $input->getArgument('mail');

        $person = new Person();
        $person->setFisrtName($firstName);
        $person->setLastName($lastName);
        $person->setMail($mail);

        // Confirm before saving (if the --confirm option is set)
        if ($input->getOption('confirm')) {
            if (!$io->confirm('Do you want to save this person?')) {
                $io->warning('Command aborted.');
                return Command::SUCCESS;
            }
        }

        // Save the person
        $this->entityManager->persist($person);
        $this->entityManager->flush();

        $io->success(sprintf('Person "%s %s" has been created successfully.', $firstName, $lastName));

        return Command::SUCCESS;
    }
}
