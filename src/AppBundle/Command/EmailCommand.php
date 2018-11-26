<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EmailCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:test-email')
            ->setDescription('Test sending e-mail.')
            ->setHelp('This command send a e-mail for test.')
            ->addArgument("to_email", InputArgument::REQUIRED, "To e-mail : ")
            ->addArgument("from_email", InputArgument::OPTIONAL, "From e-mail : ")
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeLn("Test sending e-mail");

        $container = $this->getContainer();

        $toEmail = $input->getArgument('to_email');

        $message = \Swift_Message::newInstance()
            ->setSubject('Testing e-mail')
            ->setFrom($container->getParameter('sendermail'))
            ->setTo($toEmail)
            ->setCharset('UTF-8')
            ->setContentType('text/html')
            ->setBody("test e-mail");

        $fromEmail = $input->getArgument('from_email');
        if (null !== $fromEmail){
            $message->setFrom($fromEmail);
        }

        $mailer = $container->get('mailer');
        $result = $mailer->send($message);

        $output->writeLn("Result : " . $result);
    }
}
