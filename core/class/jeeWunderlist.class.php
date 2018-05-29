<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

use JohnRivs\Wunderlist\Wunderlist;

require_once __DIR__ . '/../../../../core/php/core.inc.php';
require_once __DIR__ . '/../../3rparty/vendor/autoload.php';

class jeeWunderlist extends eqLogic
{

    public static function removeAccents($string)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', preg_replace('~&([a-z]{1,2})(acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'))), ' '));
    }

    public function UpdateUserDetails()
    {

        // Paramètre API
        $clientId     = $this->getConfiguration('clientId');
        $clientSecret = $this->getConfiguration('clientSecret');
        $accessToken  = $this->getConfiguration('accessToken');

        $wunderlist = new Wunderlist($clientId, $clientSecret, $accessToken);

        //log::add('jeeWunderlist', 'debug', 'Chargement des information de l\'utilisateur');
        $this->setConfiguration('userDetails', $wunderlist->getCurrentUser());
        $this->setConfiguration('userAvatar', $wunderlist->getAvatar());
        $this->setConfiguration('userLists', $wunderlist->getLists());
    }

    /*
     * Fonction exécutée automatiquement toutes les heures par Jeedom
     */

    public static function cron15()
    {
        $eqLogics = eqLogic::byType('jeeWunderlist');
        foreach ($eqLogics as $eqLogic) {
            if ($eqLogic->isEnable) { // Juste les équipements actifs
                log::add('jeeWunderlist', 'debug', 'Cron : ' . $eqLogic->name);

                // Paramètre API /
                $clientId     = $eqLogic->getConfiguration('clientId');
                $clientSecret = $eqLogic->getConfiguration('clientSecret');
                $accessToken  = $eqLogic->getConfiguration('accessToken');

                // Connexion à l'API //
                $wunderlist = new Wunderlist($clientId, $clientSecret, $accessToken);

                // Cherche la Liste //
                $listId = $eqLogic->getConfiguration('listId');
                $listId = intval($listId);
                if ($listId != 0) {
                    // Refresh de la liste //
                    log::add('jeeWunderlist', 'debug', 'Cron : ' . $eqLogic->name . ' - Reload list ID : ' . $listId);
                    $tasks = $wunderlist->getTasks(['list_id' => $listId]);
                    $eqLogic->setConfiguration('tasks', $tasks);
                } else {
                    throw new Exception(__('List ID : ' . $listId . ' unknown.', __FILE__));
                }
            }
        }
    }

    public function postSave()
    {
        if (!$this->getId()) {
            return;
        }

        $addTask = $this->getCmd(null, 'addTask');
        if (!is_object($addTask)) {
            $addTask = new jeeWunderlistCmd();
            $addTask->setLogicalId('addTask');
            $addTask->setIsVisible(0);
            $addTask->setName(__('Ajouter une tâche', __FILE__));
        }
        $addTask->setType('action');
        $addTask->setSubType('message');
        $addTask->setEqLogic_id($this->getId());
        $addTask->save();

        $removeTask = $this->getCmd(null, 'removeTask');
        if (!is_object($removeTask)) {
            $removeTask = new jeeWunderlistCmd();
            $removeTask->setLogicalId('removeTask');
            $removeTask->setIsVisible(0);
            $removeTask->setName(__('Supprimer une tâche', __FILE__));
        }
        $removeTask->setType('action');
        $removeTask->setSubType('message');
        $removeTask->setEqLogic_id($this->getId());
        $removeTask->save();

        $completeTask = $this->getCmd(null, 'completeTask');
        if (!is_object($completeTask)) {
            $completeTask = new jeeWunderlistCmd();
            $completeTask->setLogicalId('completeTask');
            $completeTask->setIsVisible(0);
            $completeTask->setName(__('Réaliser une tâche', __FILE__));
        }
        $completeTask->setType('action');
        $completeTask->setSubType('message');
        $completeTask->setEqLogic_id($this->getId());
        $completeTask->save();
    }

    public function preUpdate()
    {
        if ($this->getConfiguration('clientId') == '') {
            throw new \Exception(__('Le <strong>Client ID</strong> ne peut etre vide', __FILE__));
        }
        if ($this->getConfiguration('clientSecret') == '') {
            throw new \Exception(__('Le <strong>Client Secret</strong> ne peut etre vide', __FILE__));
        }
        if ($this->getConfiguration('accessToken') == '') {
            throw new \Exception(__('L\'<strong>Access Token</strong> ne peut etre vide', __FILE__));
        }

        $this->UpdateUserDetails();
    }

}

class jeeWunderlistCmd extends cmd
{
    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function preSave()
    {
        if ($this->getSubtype() == 'message') {
            $this->setDisplay('title_disable', 1);
        }
    }

    public function execute($_options = array())
    {
        // L'équipement
        $eqLogic = $this->getEqLogic();

        // Paramètre API
        $clientId     = $eqLogic->getConfiguration('clientId');
        $clientSecret = $eqLogic->getConfiguration('clientSecret');
        $accessToken  = $eqLogic->getConfiguration('accessToken');

        // Connexion à l'API
        $wunderlist = new Wunderlist($clientId, $clientSecret, $accessToken);

        // Cherche la Liste
        $listId = $eqLogic->getConfiguration('listId');
        $listId = intval($listId);
        if ($listId != 0) {
            // Refresh de la liste
            log::add('jeeWunderlist', 'debug', 'Reload list ID : ' . $listId);
            $tasks = $wunderlist->getTasks(['list_id' => $listId]);
            $eqLogic->setConfiguration('tasks', $tasks);
        } else {
            throw new \Exception(__('List ID : ' . $listId . ' unknown.', __FILE__));
        }

        /*         * ************************************
          Ajout d'une tâche
         * ************************************* */
        if ($this->logicalId == 'addTask') {
            // On ajoute uniquement si pas déjà dans la liste
            foreach ($tasks as $task) {
                if (jeeWunderlist::removeAccents($task['title']) == jeeWunderlist::removeAccents($_options['message'])) {
                    log::add('jeeWunderlist', 'info', 'Skip adding task "' . $_options['message'] . '" to the list, because already exist.');

                    return;
                }
            }
            log::add('jeeWunderlist', 'info', 'Add task "' . $_options['message'] . '" to list ID : ' . $listId);
            $wunderlist->createTask(['list_id' => $listId, 'title' => $_options['message']]);
        }

        /*         * ************************************
          Suppression d'une tâche
         * ************************************* */
        if ($this->logicalId == 'removeTask') {
            // On cherche l'ID de la task dans la liste
            foreach ($tasks as $task) {
                if (jeeWunderlist::removeAccents($task['title']) == jeeWunderlist::removeAccents($_options['message'])) {
                    log::add('jeeWunderlist', 'info', 'Delete task "' . $_options['message'] . '" from the list ID : ' . $listId);
                    $wunderlist->deleteTask($task['id']);

                    return;
                }
            }
            log::add('jeeWunderlist', 'info', 'Task "' . $_options['message'] . '" not found in list ID : ' . $listId);
        }

        /*         * ************************************
          Réaliser une tâche
         * ************************************* */
        if ($this->logicalId == 'completeTask') {
            // On cherche l'ID de la task dans la liste
            foreach ($tasks as $task) {
                if (jeeWunderlist::removeAccents($task['title']) == jeeWunderlist::removeAccents($_options['message'])) {
                    log::add('jeeWunderlist', 'info', 'Completing task "' . $_options['message'] . '" from the list ID : ' . $listId);
                    $wunderlist->updateTask($task['id'], ['completed' => true]);

                    return;
                }
            }
            log::add('jeeWunderlist', 'info', 'Task "' . $_options['message'] . '" not found in list ID : ' . $listId);
        }
    }

}
