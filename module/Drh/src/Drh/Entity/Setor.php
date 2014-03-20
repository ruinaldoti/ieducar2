<?php
namespace Drh\Entity;

use Core\Entity\Entity;
use Core\Entity\EntityException;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\SequenceGenerator;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

/**
 * Entidade Setor
 * 
 * @author  Eduardo Junior <ej@eduardojunior.com>
 * @category  Entidade
 * @package  Drh
 * @subpackage  Setor
 * @version  0.1
 * @example  Classe Setor
 * @copyright  Copyright (c) 2014 Eduardo Junior.com (http://www.eduardojunior.com)
 * 
 * @ORM\Entity
 * @ORM\Table(name="""pmidrh"".""setor""")
 * 
 */
class Setor extends Entity
{
	/**
	 * @var  int $id
	 * 
	 * @ORM\Id
	 * @ORM\Column(name="cod_setor", type="integer", nullable=false)
	 * @ORM\GeneratedValue(strategy="SEQUENCE")
	 * @SequenceGenerator(sequenceName="pmidrh.setor_cod_setor_seq", initialValue=1, allocationSize=1)
	 */
	protected $id;
	
}