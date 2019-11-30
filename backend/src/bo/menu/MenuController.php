<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestAdmisController
 *
 * @author lenovo
 */

namespace DA;

require_once '../../common/app.php';

use Bo\BaseAction;
use Bo\BaseController;
use Common\CommonManager;
use Exceptions\ConstraintException;
use Exception;
use Menu\Menu;
use Menu\MenuManager as MenuManager;


class MenuController extends BaseAction implements BaseController {
    
    private $commonManager;
    private $testAdmiss;
    private $testCandidat;
    private $menu;
    private $menuManager;
    
    public function __construct($request) {
        $file = dirname(dirname(dirname(__FILE__))) . '/lang/trad_fr.ini';
        $this->parameters = parse_ini_file($file);
        $this->commonManager = new CommonManager();
        $this->menu=new Menu();
        $this->menuManager = new MenuManager();
        try {
            if (isset($request['ACTION'])) {
                switch ($request['ACTION']) {
                    case \App::ACTION_GET_SESSION:
                        $this->doGetSession($request);
                        break;
                    case \App::ACTION_LIST:
                        $this->doList($request);
                        break;
                    case \App::ACTION_INSERT:
                        $this->doInsert($request);
                        break;
                    case \App::ACTION_UPDATE:
                        $this->doUpdate($request);
                        break;
                    case \App::ACTION_GENERATE_MENU:
                        $this->doGenerateMenu($request);
                        break;
                    case \App::ACTION_GET_ALL_MENU_BY_USER:
                        $this->doGetAllMenuByUser($request);
                        break;
                    default: throw new Exception($this->parameters['NO_ACTION']);
                }
            } else
                throw new Exception($this->parameters['NO_ACTION']);
        } catch (Exception $e) {
            $this->doLogInfo($e->getMessage());
            $this->doError('-1', $e->getMessage());
        }
    }
    
    public function doInsert($request)
    {
        try {
            if(isset($request['ACTION']) &&  isset($request['userId']) && isset($request['name']) && isset($request['title']) && isset($request['text']) &&
                isset($request['parent']) && isset($request['type']) && isset($request['actions']) && isset($request['methode']) && isset($request['url'])){
                    
                    if($request['userId']!='' && $request['name']!='' && $request['title']!='' && $request['text']!='' &&
                        $request['parent']!='' && $request['type']!='' && $request['actions']!='' && $request['methode']!='' && $request['url']!='' ){
                            $user = $this->commonManager->findById("User\User", $request['userId']);
                            
                            $this->menu->setName($request['name']);
                            $this->menu->setTitle($request['title']);
                            $this->menu->setText($request['text']);
                            if($request['parent']!="ALL"){
                                $parent=$this->commonManager->findById("Menu\Menu", $request['parent']);
                                $this->menu->setParent($parent);
                            }
                            $this->menu->setType($request['type']);
                            $this->menu->setAction($request['actions']);
                            $this->menu->setMethode($request['methode']);
                            $this->menu->setUrl($request['url']);
                            $this->menu->setUser($user);
                            $this->menu->setGenerate(1);
                            
                            $menu= $this->commonManager->insert($this->menu);
                            
                            if($menu!=null){
                                $this->doSuccess($menu->getId(), $this->parameters['INSERT']);
                                $this->doLogInfo('***************************************** Fin ajout Test *****************************************');
                                
                            }
                            else {
                                $this->doLogError($this->parameters['CODE_104_ADMIN']);
                                throw new ConstraintException($this->parameters['CODE_104']);
                            }
                            
                    }else {
                        $this->doLogError($this->parameters['CODE_101_ADMIN']);
                        throw new ConstraintException('Certains champs sont vides');
                    }
                    
            }else {
                $this->doLogError($this->parameters['CODE_100_ADMIN']);
                throw new ConstraintException($this->parameters['CODE_100']);
            }
            //            }else {
            //                $this->doLogError($this->parameters['CODE_100_ADMIN']);
            //                throw new ConstraintException($this->parameters['CODE_100']);
            //            }
            $this->doLogInfo("Fin doGenerateMenu");
        } catch (ConstraintException $e) {
            $this->doLogInfo($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            $this->doError('-1', $e->getMessage());
        } catch (Exception $e) {
            $this->doLogInfo($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            $this->doError('-1', $this->parameters['ERREUR_SERVEUR']);
        }
        
    }
    
    // isset($request['userId']) && isset($request['note'])&& isset($request['commentaire']) &&
    //  && $request['note'] != '' && $request['commentaire'] != ''&& $request['userId'] != ''
    public function doGenerateMenu($request){
        try {
            $chemin='C:/xampp/htdocs/';
            $repUser='ussdgenerate/';
            $numTel="221776470760";
            $hrefFils="";
            $this->doLogInfo("Debut du doGenerateMenu");
            $this->doLogInfo('List des parametres:' . $this->doGetListParam());
            if (isset($request['ACTION']) && isset($request['userId']) ){
                $user = $this->menuManager->findById("User\User", $request['userId']);
                $listMenu=$this->menuManager->getAllMenuByUser($user);
//                 var_dump($listMenu);
//                 $listMenuJ=json_encode($listMenu);
//                 $this->doLogInfo('List des menus:' . $listMenuJ);
                if ($listMenu != null) {
                    foreach ($listMenu as $unMenu) {
//                         var_dump($unMenu["menuId"]);
                        $menuG=$this->commonManager->findById("Menu\Menu", $unMenu["id"]);
                        $this->doLogInfo('menuId:' . $menuG->getId());
                        if($menuG->getParent()==null){
                        $nameParent=$menuG->getName();
                        $textParent=$menuG->getText();
                        foreach ($listMenu as $newMenu) {
                            if($menuG->getId()==$newMenu["parent_id"]){
                                $titleFils=$newMenu["title"];
                                $nameFils=$newMenu["name"];
                                $textFils=$newMenu["text"];
                                
                                $file = $chemin.$repUser.$nameFils.'.php';
                                // Open the file to get existing content
                                //                         $current = file_get_contents($file);
                                // Append a new person to the file
                                //                         $current = "<?php
                                $current = "<?xml version='1.0' encoding='utf-8'?>
                                <!doctype html><html>
                                <head><meta charset='utf-8'>
                                <title>$nameFils</title>
                                </head><body>
                                <h3>$textFils</h3>
                                </body></html>
                                 ";
                            // Write the contents back to the file
                            file_put_contents($file, $current);
                            
                                $hrefFils.='<a href="'.$nameFils.'.php?response="'.$numTel.'|'.$titleFils.'" >'.$titleFils.'</a><br/>';
                            }
                        }
                        
                        
                        $file = $chemin.$repUser.$nameParent.'.php';
                        // Open the file to get existing content
//                         $current = file_get_contents($file);
                        // Append a new person to the file
//                         $current = "<?php 
                        $current = "<?xml version='1.0' encoding='utf-8'?>
                        <!doctype html><html>
                        <head><meta charset='utf-8'>
                        <title>$nameParent</title>
                        </head><body>
                        <h3>$textParent</h3>
                            $hrefFils
                        </body></html>
                         ";
                            // Write the contents back to the file
                        file_put_contents($file, $current);
                        }
                        else {//A revoir
                            //cas fils seulement
                            foreach ($listMenu as $newMenu) {
                                if($menuG->getId()==$newMenu["parent_id"]){
                                    $titleFils=$newMenu["title"];
                                    $nameFils=$newMenu["name"];
                                    $textFils=$newMenu["text"];
                                    
                                    $file = $chemin.$repUser.$nameFils.'.php';
                                    // Open the file to get existing content
                                    //                         $current = file_get_contents($file);
                                    // Append a new person to the file
                                    //                         $current = "<?php
                                    $current = "<?xml version='1.0' encoding='utf-8'?>
                                <!doctype html><html>
                                <head><meta charset='utf-8'>
                                <title>$nameFils</title>
                                </head><body>
                                <h3>$textFils</h3>
                                </body></html>
                                 ";
                                    // Write the contents back to the file
                                    file_put_contents($file, $current);
                                    
                                    $hrefFils.='<a href="'.$nameFils.'.php?response="'.$numTel.'|'.$titleFils.'" >'.$titleFils.'</a><br/>';
                                }
                            }
                        }
                        
                    }
                }
//                 $name=$request['name'];
                
//                 $this->doResult( 'Fichier '.$file.' cr�� avec succ�s');
                $this->doSuccess(1, $this->parameters['GENERATED']);
                
            }else {
                $this->doLogError($this->parameters['CODE_100_ADMIN']);
                throw new ConstraintException($this->parameters['CODE_100']);
            }
            //            }else {
            //                $this->doLogError($this->parameters['CODE_100_ADMIN']);
            //                throw new ConstraintException($this->parameters['CODE_100']);
            //            }
            $this->doLogInfo("Fin doGenerate Menu");
        } catch (ConstraintException $e) {
            $this->doLogInfo($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            $this->doError('-1', $e->getMessage());
        } catch (Exception $e) {
            $this->doLogInfo($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            $this->doError('-1', $this->parameters['ERREUR_SERVEUR']);
        }
    }
    
    public function doGetAllMenuByUser($request){
        $this->doLogInfo("Debut doGetAllMenuByUser");
        $this->doLogInfo('List des parametres:' . $this->doGetListParam());
        try {
            $this->doLogInfo('List des parametres:' . $this->doGetListParam());
            if (isset($request['ACTION']) &&  isset($request['userId']) ) {
                $user = $this->commonManager->findById("User\User", $request['userId']);
                $listMenu = $this->menuManager->getAllMenuByUser($user);
//                 var_dump($listMenu);
//                 $listMenu=json_encode($listMenu);
//                 $this->doLogInfo('List menu:' . $listMenu);
                
                if ($listMenu != NULL) {
                    $this->doSuccessO($listMenu);
                    $this->doLogInfo("Fin doGetAllMenuByUser");
                } else {
                    $this->doLogError($this->parameters['CODE_110_ADMIN']);
                    throw new ConstraintException($this->parameters['CODE_110']);
                    $this->doLogInfo("Erreur liste des menus vides");
                }
            }
            
        } catch (ConstraintException $e) {
            $this->doLogError($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            $this->doError('-1', $e->getMessage());
            $this->doLogError("Fin doGetAllMenuByUser");
        } catch (Exception $e) {
            $this->doLogError($e->getMessage() . ' ' . $e->getFile() . ' ' . $e->getLine());
            $this->doError('-1', $this->parameters['ERREUR_SERVEUR']);
            $this->doLogError("Fin doGetAllMenuByUser ");
        }
    }
    
    public function doRestore($request)
    {}

    public function doDeactivate($request)
    {}

    public function doRemove($request)
    {}

    public function doView($request)
    {}

    public function doUpdate($request)
    {}

    public function doActivate($request)
    {}

    public function doList($request)
    {}

    public function dofindById($request)
    {}

    
    
    //put your code here
}

$oMenuController = new MenuController($_REQUEST);

