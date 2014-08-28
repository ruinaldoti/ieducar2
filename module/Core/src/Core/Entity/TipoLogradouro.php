<?php
namespace Core\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade TipoLogradouro
 * 
 * @author Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Core
 * @subpackage  TipoLogradouro
 * @version  0.1
 * @example  Classe TipoLogradouro
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="urbano.tipo_logradouro")
 */
class TipoLogradouro extends Entity
{
	/**
	 * @var string $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="idtlog", type="string", nullable=false, length=5)
	 */
	protected $id;

	/**
	 * @var string $descricao
	 * @ORM\Column(type="string", length=40, nullable=false)
	 */
	protected $descricao;
}