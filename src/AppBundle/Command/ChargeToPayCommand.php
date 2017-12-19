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

class ChargeToPayCommand extends Command
{
    private $manager;

    public function __construct( ChargeManager $manager)
    {
        $this->manager = $manager;

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
        }

        foreach ($chargesToPay as $charges)
        {
            $output->write('Titre : '.$charges->getTitle());
            $output->write(' , montant : '.$charges->getAmount());
            $output->writeln(' , date d\'échéance : '.$charges->getDueOn()->format('d/m/Y'));
        }

    }
}