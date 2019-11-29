<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestAdmisManager
 *
 * @author lenovo
 */


namespace Menu;
use Menu\Menu;
use Bo\BaseAction;
use Common\CommonManager;
use Menu\MenuQueries;
//use DA\TestAdmisQueries as TestAdmisQueries;


class MenuManager  extends BaseAction implements \Bo\BaseManager  {
    private $commonManager;
    private $menuQueries;
    private $testAdmis;


    public function __construct() {
        $this->commonManager = new CommonManager();
       // $this->testAdmisQueries= new tes
        $this->menuQueries = new MenuQueries();
                

    }
    public function findById($object, $id) {
        return $this->commonManager->findById($object, $id);
    }
    
    //     public function insrcire($listeInscrite, $listInscriptionUpdate) {
    //         return $this->testAdmisQueries->insrcire($listeInscrite, $listInscriptionUpdate);
    //     }
    public function insert($object, $supp = null) {
        return $this->commonManager->insert($object, $supp);
    }
    
    public function remove($entity, $listId, $userId = null) {
        return $this->commonManager->remove($entity, $listId, $userId);
    }
    
    public function restore($entity, $listId, $userId = null) {
        return $this->commonManager->restore($entity, $listId, $userId);
    }
    
    
    
    public function update($object) {
        return $this->commonManager->update($object);
    }
    
    public function view($id) {
        return  $this->testAdmisQueries->view($id);
    }
    
    public function activate($entity, $listId, $userId = null) {//$entity=namePackage\nameClass
        return  $this->commonManager->activate($entity, $listId, $userId);
    }
    
    public function deactivate($entity, $listId, $userId = null) {
        return $this->commonManager->deactivate($entity, $listId, $userId);
    }
    
    public function doArchive($entity, $listId, $userId = null) {
        return $this->commonManager->doArchive($entity, $listId, $userId);
    }
    
    public function undoArchive($entity, $listId, $userId = null) {
        return $this->commonManager->undoArchive($entity, $listId, $userId);
    }
    
    public function delete($entity, $listId, $userId = null) {
        return $this->commonManager->delete($entity, $listId, $userId);
    }
    public function findByCode($entity, $entityCode)
    {
        return $this->commonManager->findByCode($entity, $entityCode);
    }
    
    public function getAllMenuByUser($user){
        return $this->menuQueries->getAllMenuByUser($user);
    }
    
    public function getAllMenus($user){
        return $this->menuQueries->getAllMenus($user);
    }
    //put your code here
}
