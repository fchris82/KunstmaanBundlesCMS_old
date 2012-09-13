<?php

/*
 * Copyright (c) 2012 Kunstmaan (http://www.kunstmaan.be)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Wim Vandersmissen <wim.vandersmissen@kunstmaan.be>
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace Kunstmaan\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\ORM\EntityManager;

use Kunstmaan\AdminBundle\Entity\Role;

class CreateRoleCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     */
    protected function configure()
    {
        $this->setName('kuma:role:create')
            ->setDescription('Create a role.')
            ->setDefinition(array(
                new InputArgument('role', InputArgument::REQUIRED, 'The role'),
            ))
            ->setHelp(<<<EOT
The <info>kuma:role:create</info> command creates a role:

  <info>php app/console kuma:role:create ROLE_ADMIN</info>

<comment>Note:</comment> The ROLE_ prefix will be added if you don't provide it

  <info>php app/console kuma:role:create ADMIN</info>

will create ROLE_ADMIN.

EOT
            );
    }

    /**
     * Executes the current command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var EntityManager $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $roleName = strtoupper($input->getArgument('role'));
        if ('ROLE_' != substr($roleName, 0, 5)) {
            $roleName = 'ROLE_' . $roleName;
        }

        $role = new Role($roleName);
        $em->persist($role);
        $em->flush();

        $output->writeln(sprintf('Created role <comment>%s</comment>', $roleName));
    }
}
