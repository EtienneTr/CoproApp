<?php
/**
 * Created by PhpStorm.
 * User: Etienne
 * Date: 18/12/2017
 * Time: 16:59
 */

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use AppBundle\Service\ChargeManager;
use Symfony\Component\Templating\EngineInterface;

class ChargeToPayCommand extends Command
{
    private $manager;
    private $mailer;
    private $templating;

    public function __construct(EngineInterface $templating, ChargeManager $manager, \Swift_Mailer $mailer)
    {
        $this->manager = $manager;
        $this->mailer = $mailer;
        $this->templating = $templating;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:charge-toPay')
            ->setDescription('List all charges to pay at a current date.')
            ->setHelp('This command allows you to create a user...')

            //args
            ->addArgument('dueDate', InputArgument::OPTIONAL, 'Due date to check.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dueDate = new \Datetime("now");
        $inputDate = $input->getArgument('dueDate');
        if($inputDate)
        {
            $dueDate = new \Datetime($inputDate);
        } 

        $output->writeln('Recherche d\'échéance à Date : '.$dueDate->format('d/m/Y').'');

        $chargesToPay = $this->manager->getToPayFromDate($dueDate);
        $length = count($chargesToPay);
        if($length <= 0)
        {
            $output->write("Aucun résultats");
        }
        else
        {
            $output->writeln("Liste des ".$length." résultats");

            $this->sendMail($chargesToPay);
        }

        foreach ($chargesToPay as $charges)
        {
            $output->write('Titre : '.$charges->getTitle());
            $output->write(' , montant : '.$charges->getAmount());
            $output->writeln(' , date d\'échéance : '.$charges->getDueOn()->format('d/m/Y'));
        }

    }

    #Create a specific service later
    private function sendMail($chargesArray)
    {
        $template = '@App/charges/mail_admin.html.twig';

        $message = (new \Swift_Message('Liste of charges'))
        ->setFrom('no-reply@coproapp.com')
        #real email configured in config-dev
        ->setTo('admin@exemple.com')
        ->setBody($this->templating->render($template, array('charges' => $chargesArray)))
        ->setContentType('text/html');

        $this->mailer->send($message);

    }
}