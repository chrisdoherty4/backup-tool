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

namespace Backup\Cleaner;

/**
 * @class FilesystemCleanerInterface
 * A cleaner for cleaning up old backups.
 *
 * @author Chris Doherty <chris.doherty4@gmail.com>
 */
interface FilesystemCleanerInterface
{
    /**
     * Sets the regex for identifying backups to be considered for cleaning.
     *
     * @param string $regex
     * @return void
     */
    public function setFileRegex($regex);

    /**
     * Specifies the number of backups to keep starting with the latest
     * backup and working backwards.
     *
     * @param int $count The number of backpups to keep.
     * @return void
     */
    public function keep($count);

    /**
     * Specifies a date from which to keep backups.
     *
     * @param DateTime $date The date from which we should keep backups.
     * @return void
     */
    public function keepAfter(\DateTime $date);

    /**
     * Performs the clean of backup files.
     *
     * @return int The number of items cleaned.
     */
    public function clean();
}
