<?php

class Table_IndexController extends Zend_Controller_Action
{

    protected $_session = null;

    public function init()
    {
        $this->_session = new Zend_Session_Namespace(Application_Service_AzureBlob::WASA_SERVICE);
    }

    public function indexAction()
    {
        return $this->_helper->redirector('list', 'index', 'table');
    }

    public function listAction()
    {
        $azureBlob = new Application_Service_AzureBlob(
            $this->_session->creds['account_name'], 
            $this->_session->creds['account_key']
        );
        $tableList = $azureBlob->listTables();
        $tables = $tableList->getTables();
        
        $this->view->tables = $tables;
    }

    public function browseAction()
    {
        $table = $this->getRequest()->getParam('table', null);
        $azureBlob = new Application_Service_AzureBlob(
            $this->_session->creds['account_name'], 
            $this->_session->creds['account_key']
        );
        $entities = $azureBlob->listEntities($table);
        $this->view->assign(array (
            'table' => $table,
            'entities' => $entities,
        ));
    }

    public function addEntityAction()
    {
        $table = $this->getRequest()->getParam('table', null);
        $this->view->table = $table;
    }

    public function createEntityAction()
    {
        $table = $this->getRequest()->getParam('table', null);
        $partitionKey = $this->getRequest()->getParam('partition', null);
        $rowKey = $this->getRequest()->getParam('row', null);
        
        $azureBlob = new Application_Service_AzureBlob(
            $this->_session->creds['account_name'], 
            $this->_session->creds['account_key']
        );
        $azureBlob->createEntity($table, $partitionKey, $rowKey);
        return $this->_helper->redirector('browse', 'index', 'table', array ('table' => $table));
    }

    public function removeEntityAction()
    {
        $table = $this->getRequest()->getParam('table', null);
        $partitionKey = $this->getRequest()->getParam('partition', null);
        $rowKey = $this->getRequest()->getParam('row', null);
        
        $azureBlob = new Application_Service_AzureBlob(
            $this->_session->creds['account_name'], 
            $this->_session->creds['account_key']
        );
        $azureBlob->removeEntity($table, $partitionKey, $rowKey);
        return $this->_helper->redirector('browse', 'index', 'table', array ('table' => $table));
    }


}











