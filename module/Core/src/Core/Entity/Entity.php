<?php
namespace Core\Entity;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\InputFilter\Exception\InvalidArgumentException;

abstract class Entity implements InputFilterAwareInterface
{

	/**
     * Filters
     * 
     * @var InputFilter
     */
    protected $inputFilter = null;

	/**
	 * Magic getter to expose protected properties.
	 * 
	 * @param string $property
	 * @return  mixed
	 */
	public function __get($property)
	{
		return $this->$property;
	}

	/**
	 * Magic setter to save protected properties.
	 * 
	 * @param  string $property
	 * @return  mixed $value
	 */
	public function __set($property, $value)
	{		
		$this->$property = $this->valid($property, $value);
	}

	/**
	 * Convert the object to an array
	 * 
	 * @return  array
	 */
	public function getArrayCopy()
	{
		return get_object_vars($this);
	}

	/**
     * @param InputFilterInterface $inputFilter
     * @return void
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new EntityException("Not used");
    }

    /**
     * Entity filters
     *
     * @return InputFilter
     */
    public function getInputFilter() {}


    /**
     * Filter and validate data
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function valid($key, $value)
    {
        if (!$this->getInputFilter())
            return $value;

        try {
            $filter = $this->getInputFilter()->get($key);
        } catch(InvalidArgumentException $e) {
            //não existe filtro para esse campo            
            return $value;
        }    

        $filter->setValue($value);
        if(!$filter->isValid()) 
            throw new EntityException("Input inválido: $key = $value");

        return $filter->getValue($key);
    }
	
}