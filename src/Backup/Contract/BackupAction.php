<?php

/* 
 * Copyright (C) 2017 chrisdoherty
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

/**
 * @namespace Backup
 */
namespace Backup\Contract;

/**
 * BackupAction
 * 
 * Interface defining an object that has a backup process associated with it.
 */
interface BackupAction {
    /**
     * The entry point for a backup. 
     * 
     * @param type $name If the backup can be given a name, this denotes the
     *  name it'll be. 
     */
    public function backup($name);
}
