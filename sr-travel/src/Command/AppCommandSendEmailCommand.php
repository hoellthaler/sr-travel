<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand(
    name: 'app:send-email', // Hier den Namen des Commands anpassen
    description: 'Send an email to the specified recipient',
)]
class AppCommandSendEmailCommand extends Command
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        parent::__construct();
        $this->mailer = $mailer;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('recipient', InputArgument::REQUIRED, 'The email address of the recipient')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description (optional)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $recipient = $input->getArgument('recipient');

        // E-Mail zusammenstellen
        $email = (new Email())
            ->from('your_email@example.com') // Hier deine E-Mail-Adresse eintragen
            ->to($recipient)
            ->subject('Test Email from Symfony Command')
            ->text('This is a test email sent from the Symfony command!');

        // E-Mail versenden
        $this->mailer->send($email);

        // Ausgabe der Erfolgsnachricht
        $io->success('Email sent to: ' . $recipient);

        return Command::SUCCESS;
    }
}
