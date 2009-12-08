<?php
/**
 * This file is part of the Galahad Framework Extension.
 * 
 * The Galahad Framework Extension is free software: you can redistribute 
 * it and/or modify it under the terms of the GNU General Public License 
 * as published by the Free Software Foundation, either version 3 of the 
 * License, or (at your option) any later version.
 * 
 * The Galahad Framework Extension is distributed in the hope that it will
 * be useful, but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU 
 * General Public License for more details.
 * 
 * @category  Galahad
 * @package   Galahad_Tool
 * @copyright Copyright (c) 2009 Chris Morrell <http://cmorrell.com>
 * @license   GPL <http://www.gnu.org/licenses/>
 * @version   0.3
 */

/**
 * @see Zend_Tool_Project_Context_Filesystem_File
 */
require_once 'Zend/Tool/Project/Context/Filesystem/File.php';

/**
 * @see Zend_CodeGenerator_Php_File
 */
require_once 'Zend/CodeGenerator/Php/File.php';

/**
 * @see Zend_Filter_Word_DashToCamelCase
 */
require_once 'Zend/Filter/Word/DashToCamelCase.php';

/**
 * Context for creating a DataMapper file
 * 
 * @category   Galahad
 * @package    Galahad_Tool
 * @copyright  Copyright (c) 2009 Chris Morrell <http://cmorrell.com>
 * @license    GPL <http://www.gnu.org/licenses/>
 */
class Galahad_Tool_Project_Context_DataMapperFile extends Zend_Tool_Project_Context_Filesystem_File 
{
    /**
     * @var string
     */
    protected $_filesystemName = 'DataMapperName';
    
    /** @var string */
    protected $_dataMapperName = 'dataMapperName';
    
    /**
     * init()
     *
     * @return Galahad_Tool_Project_Context_DataMapperFile
     */
    public function init()
    {
        $this->_dataMapperName = $this->_resource->getAttribute('dataMapperName');
        
        $filter = new Zend_Filter_Word_DashToCamelCase();
        $this->_filesystemName = $filter->filter($this->_dataMapperName) . '.php';
        
        parent::init();
        return $this;
    }
    
    /**
     * getPersistentAttributes
     *
     * @return array
     */
    public function getPersistentAttributes()
    {
        return array(
			'dataMapperName' => $this->getDataMapperName()
        );
    }
    
    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'DataMapperFile';
    }
    
    /**
     * getDataMapperName()
     *
     * @return string
     */
    public function getDataMapperName()
    {
        return $this->_dataMapperName;
    }
  
    /**
     * getContents()
     *
     * @return string
     */
    public function getContents()
    {
        $moduleName = 'Default';
        $parent = $this->_resource->getParentResource()->getParentResource()->getParentResource()->getContext();
        if ($parent instanceof Zend_Tool_Project_Context_Zf_ModuleDirectory) {
            $moduleName = ucfirst($parent->getModuleName());
            // $className = ($this->_moduleName ? ucfirst($this->_moduleName) : 'Default');
        }
        
        $filter = new Zend_Filter_Word_DashToCamelCase();
        $className = $moduleName . '_Model_DataMapper_' . $filter->filter($this->_dataMapperName);
        
        /*

        abstract public function fetchAll();
		abstract public function fetchByPrimary($primaryKey);
		abstract public function save(Galahad_Model_Entity $entity);
		abstract public function delete(Galahad_Model_Entity $entity);
		abstract public function deleteByPrimary($primaryKey);
		
        */
        
        $codeGenFile = new Zend_CodeGenerator_Php_File(array(
            'fileName' => $this->getPath(),
            'classes' => array(
        		// Zend_CodeGenerator_Php_Parameter::
                new Zend_CodeGenerator_Php_Class(array(
                    'name' => $className,
                    'extendedClass' => 'Galahad_Model_DataMapper',
                	'methods' => array(
                		array(
                			'name' => 'fetchAll',
                			'body' => "\t\t// Fetch all entities",
                		),
                		array(
                			'name' => 'fetchByPrimary',
                			'parameters' => array(
                				array(
                					'name' => 'primaryKey',
                				),
                			),
                			'body' => "\t\t// Fetch entities by primary key(s)",
                		),
                		array(
                			'name' => 'save',
                			'parameters' => array(
                				array(
                					'name' => 'entity',
                					'type' => 'Galahad_Model_Entity',
                				),
                			),
                			'body' => "\t\t// Save entity",
                		),
                		array(
                			'name' => 'delete',
                			'parameters' => array(
                				array(
                					'name' => 'entity',
                					'type' => 'Galahad_Model_Entity',
                				),
                			),
                			'body' => "\t\t// Delete entity",
                		),
                		array(
                			'name' => 'deleteByPrimary',
                			'parameters' => array(
                				array(
                					'name' => 'primaryKey',
                				),
                			),
                			'body' => "\t\t// Delete entity by primary key",
                		),
                	),
                )),
            ),
        ));
        
        // store the generator into the registry so that the addProperty command can use the same object later
        Zend_CodeGenerator_Php_File::registerFileCodeGenerator($codeGenFile); // REQUIRES filename to be set
        return $codeGenFile->generate();
    }
    
    /**
     * getCodeGenerator()
     *
     * @return Zend_CodeGenerator_Php_Class
     */
    public function getCodeGenerator()
    {
        $codeGenFile = Zend_CodeGenerator_Php_File::fromReflectedFileName($this->getPath());
        $codeGenFileClasses = $codeGenFile->getClasses();
        $class = array_shift($codeGenFileClasses);
        return $class;
    }
    
}
