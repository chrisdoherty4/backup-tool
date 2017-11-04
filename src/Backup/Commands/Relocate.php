<?php

/* 
 * Copyright (C) 2017 Chris Doherty
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Backup\Commands;

use \Symfony\Component\Console\Input\InputInterface;
use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Input\InputArgument;
use \Backup\Providers\FileSystem\MountManager;

/**
 * @class Relocate
 *
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
class Relocate extends AbstractCommand
{
    /**
     * The file system containing the file to be moved.
     * 
     * @var \League\Filesystem\Filesystem
     */
    private $mountManager = null;

    /**
     * Initialises command with appropriate dependencies.
     * 
     * @param \Backup\Providers\FileSystem\MountManager $mountManager
     * @param array                                     $ftpConfig
     */
    public function __construct(
        MountManager $mountManager,
        array $ftpConfig
    ) {
        parent::__construct();
        
        $this->mountManager = $mountManager;

        $this->mountManager->mountFilesystem(
            'destination',
            $this->mountManager->getFtpInstance($ftpConfig)
        );
    }

    /**
     * Configures the command attributes.
     */
    public function configure()
    {
        $this->setName("relocate:ftp")
            ->setTitle('Relocate - FTP')
            ->setDescription("Relocate a backup to an FTP server.")
            ->addArgument(
                'backup_path', InputArgument::REQUIRED, 'The fully '
                . 'qualified backup path. The filename portion of the path can '
                . 'include a wildcard (*) to match multiple backup files.'
            );
    }

    /**
     * Using a provided path to the backup file, takes the backup file and
     * pushes to an FTP server.
     * 
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($this->getHeader());
        
        $path = $input->getArgument('backup_path');

        $base = $this->extractParentPath($path);

        if ($base) {
            $this->mountManager->mountFilesystem(
                'source',
                $this->mountManager->getLocalInstance($base)
            );
        } else {
            $output->writeln("<error>The path provided is invalid</>");
            return;
        }
        
        $backups = $this->getBackups($path);
        
        $total = count($backups);

        $output->writeln(
            "<info>Found ".count($backups)." backup(s)</>"
        );

        if (!$total) {
            $output->writeln(
                "<comment>If you're expecting backups to be "
                . "present, check the path (".$path.")</>"
            );
        }

        if ($total) {
            $output->writeln("<info>Transferring ".$total." backup(s)</>");

            $complete = $this->relocateBackups($backups);

            if ($complete == $total) {
                $output->writeln(
                    "<info>Successfully transfered all "
                    . "backups</>"
                );
            } else {
                $output->writeln(
                    "<error>Failed to transfer " .
                    ($total - $complete) . " backup(s)</>"
                );
            }
        }
    }

    /**
     * Retrieves all the files for transfer based on the backup path defined
     * in the environment file.
     *
     * @param  string $backupPath A string that references a directory with
     *                            backups. This can have a * wildcard representing any character.
     * @return array An array of files that match the backup path string.
     */
    private function getBackups($backupPath)
    {
        $backups = array_values(glob($backupPath));

        array_walk(
            $backups, function (&$path, $key) {
                $path = substr($path, strrpos($path, '/')+1, strlen($path));
            }
        );
        
        return $backups;
    }

    /**
     * Extracts the parent path from a path to a file.
     * 
     * @param  strnig $path
     * @return string
     */
    private function extractParentPath($path)
    {
        return realpath(substr($path, 0, strrpos($path, '/')));
    }

    /**
     * Moves backups from source to destination as specified in the mount
     * manager.
     * 
     * @param  array $backups An array of backup files.
     * @return int The number of successful transfers.
     */
    private function relocateBackups($backups)
    {
        $successful = 0;

        foreach ($backups as $file) {
            try {
                $this->mountManager->write(
                    'destination://'.$file, 
                    $this->mountManager->read('source://'.$file)
                );

                $this->mountManager->delete('source://'.$file);

                $successful+= 1;
            }
            catch (\RuntimeException $e) {
                // Do nothing, we don't really care much.
            }
        }

        return $successful;
    }
}