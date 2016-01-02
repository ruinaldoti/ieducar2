<?php
/**
 * Created by Vim.
 * User: eduardojunior
 * Date: 30/12/15
 * Time: 22:00
 */
namespace Escola\Entity;

use Core\Test\EntityTestCase;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @group Entity
 */
class CursoTest extends EntityTestCase
{
	public fuction setup()
	{
		parent::setup();
	}

	/**
	 * Check if filters exists
	 */
	public function testGetInputFilter()
	{
		$curso = new Curso();
		$if = $instituicao->getInputFilter();
		$this->assertInstanceOf('Zend\InputFilter\InputFilter', $if);

		return $if;
	}

	/**
	 * @depends testGetInputFilter
	 */
	public function testInputFilterValid($if)
	{
		$this->assertEquals(17, $if->count());
		$this->assertTrue($if->has('id'));
		$this->assertTrue($if->has('nome'));
		$this->assertTrue($if->has('sigla'));
		$this->assertTrue($if->has('quantidadeEtapa'));
		$this->assertTrue($if->has('cargaHoraria'));
		$this->assertTrue($if->has('atoPoderPublico'));
		$this->assertTrue($if->has('objetivo'));
		$this->assertTrue($if->has('publicoAlvo'));
		$this->assertTrue($if->has('ativo'));
		$this->assertTrue($if->has('padraoAnoEscolar'));
		$this->assertTrue($if->has('horaFalta'));
		$this->assertTrue($if->has('multiSeriado'));
		$this->assertTrue($if->has('instituicao'));
		$this->assertTrue($if->has('nivelEnsino'));
		$this->assertTrue($if->has('tipoEnsino'));
		$this->assertTrue($if->has('tipoRegime'));
		$this->assertTrue($if->has('cursoHabilitacoes'));
		// TODO: continue this
	}

	public function testInsert()
	{
		$curso = $this->buildCurso();
		$this->em->persist($curso);
		$this->em->flush();
		$this->assertNotNull($curso->getId());
		$this->assertEquals(1, $curso->getId());

		// searching record in db
		$savedCurso = $this->em->find(get_class($curso), $curso->getId());
		$this->assertInstanceOf(get_class($savedCurso),$savedCurso);
		$this->assertEquals($curso->getId(), $savedCurso->getId());
		$this->assertEquals('Curso Teste', $savedCurso->getNome());
		$this->assertEquals('CT', $savedCurso->getSigla());
		$this->assertEquals(4, $savedCurso->getQuantidadeEtapa());
		$this->assertEquals(60.0, $savedCurso->getCargaHoraria());
		$this->assertEquals('ato', $savedCurso->getAtoPoderPublico());
		$this->assertEquals('Objetivo do curso', $savedCurso->getObjetivo());
		$this->assertEquals('Infantil', $savedCurso->getPublicoAlvo());
		$this->assertEquals(true, $savedCurso->getAtivo());
		$this->assertEquals(false, $savedCurso->getPadraoAnoEscolar());
		$this->assertEquals(50.0, $savedCurso->getHoraFalta());
		$this->assertEquals(false, $savedCurso->getMultiSeriado());
		$this->assertEquals($curso->getInstituicao(), $savedCurso->getInstituicao());
		$this->assertEquals($curso->getNivelEnsino(), $savedCurso->getNivelEnsino());
		$this->assertEquals($curso->getTipoEnsino(), $savedCurso->getTipoEnsino());
		$this->assertEquals($curso->getTipoRegime(), $savedCurso->getTipoRegime());
		$this->assertEquals($curso->getCursoHabilitacao(), $savedCurso->getCursoHabilitacao());
		
		//		
	}

	/**
	 * @expectedException Core\Entity\EntityException	 
	 */
	public function testInputFilterInvalidNome()
	{
		$curso = $this->buildCurso();
		$curso->setNome('qwertyuiop[]\asdfghjkl;zxcvbnm,/1234567890-=qwertyuiiop[]\asdfghkjk;;;vbnm,../qwertryuioppasdfhjhlxzc,xzmkjasdoioqwe qweoioi wqe oiasodi osadi osadioa sdoas oi oasid o oi osad oioi oi dasoi oido oi oisdoio asd oi oadoi oia dsoi oiasdoi oi dosaidoi asdois osadoi oisdoi oi asdoi o asdoi oia sdoi asdoi asdoi oi adoi sadoaisdoi sadoi sado asdoi odoasi dsooioiasd oiasd osdoai sadoi oiasdoasi doasdoid oia odiasodi oia doi oi dasodi oi asdoi oi oi asdoi oiasd oio i doasi oasido asdio ');
		$this->em->persist($curso);
		$this->em->flush();
	}

	public function testUpdate()
	{
		$curso = $this->buildCurso();
		$this->em->persist($curso);
		$this->em->flush();
		$savedCurso = $this->em->find(get_class($curso), $curso->getId());

		$this->assertEqual('Curso Teste', $savedCurso->getNome());
		$savedCurso->setNome('Curso Outro Nome');
		$this->em->flush();
		$savedCurso = $this->em->find(get_class($curso), $savedCurso->getId());
		$this->assertEqual('Curso Outro Nome', $savedCurso->getNome());
	}

	public function testDelete()
	{
		$curso = $this->buildCurso();
		$this->em->persist($curso);
		$this->em->flush();
		$id = $curso->getId();
		$savedCurso = $this->em->find(get_class($curso), $id);
		$this->em->remove($savedCurso);
		$this->em->flush();
		$savedCurso = $this->em->find(get_class($curso), $id);
		$this->assertNull($savedCurso);
	}

	public function testRemoveHabilitacoes()
    {
        $habilitacoes = $this->buildCursoHabilitacoes();
        $curso = $this->buildCurso();
        $curso->addCursoHabilitacoes($habilitacoes);
        $this->em->persist($curso);
        $this->em->flush();
        $id = $curso->getId();

        $savedCurso = $this->em->find('Escola\Entity\Curso', $id);
        $savedCurso->removeCursoHabilitacoes($habilitacoes);

        $this->em->flush();

        $savedCurso = $this->em->find('Escola\Entity\Curso', $id);

        //Verfify colletion is empty
        $this->assertTrue($savedCurso->getCursoHabilitacoes()->isEmpty());
    }

	private function buildCurso()
	{
		$curso = new Curso();
		$curso->setNome('Curso Teste');
		$curso->setSigla('CT');
		$curso->setQuantidadeEtapa(4);
		$curso->setCargaHoraria(60.0);
		$curso->setAtoPoderPublico('ato');
		$curso->setObjetivo('Objetivo do curso');
		$curso->setPublicoAlvo('Infantil');
		$curso->setAtivo(true);
		$curso->setPadraoAnoEscolar(false);
		$curso->setHoraFalta(50.0);
		$curso->setMultiSeriado(false);
		
		$instituicao = $this->buildInstituicao();
		$curso->setInstituicao($curso);
		
		$nivelEnsino = $this->buildNivelEnsino();
		$curso->setNivelEnsino($nivelEnsino);

		$tipoEnsino = $this->buildTipoEnsino();
		$curso->setTipoEnsino($tipoEnsino);

		$tipoRegime = $this->buildTipoRegime();
		$curso->setTipoRegime($tipoRegime);

		$cursoHabilitacaoes = $this->buildCursoHabilitacoes();
		$curso->addCursoHabilitacao($cursoHabilitacoes);

		return $curso;
	}

	private function buildInstituicao()
    {
        $instituicao = new Instituicao();
        $instituicao->setNome('Prefeitura Municipal Modelo');
        $instituicao->setResponsavel('Secretaria Municipal Modelo');

        return $instituicao;
	}

	private function buildNivelEnsino()
	{
		$nivelEnsino = new NivelEnsino();
		$nivelEnsino->setNome('Nivel 1');
		$nivelEnsino->setDescricao('Descricao nivel de ensino');
		$instituicao = $this->buildInstituicao();
		$nivelEnsino->setInstituicao($instituicao);

		return $nivelEnsino;
	}

	private function buildTipoEnsino()
	{
		$tipoEnsino = new TipoEnsino();
		$tipoEnsino->setNome('Tipo Ensino');
		$tipoEnsino->setAtivo(true);
		$instituicao = $this->buildInstituicao();
		$tipoEnsino->setInstituicao($instituicao);

		return $tipoEnsino;
	}

	private function buildTipoRegime()
	{
		$tipoRegime = new TipoRegime();
		$tipoRegime->setNome('Tipo Regime 1');
		$tipoRegime->setAtivo(true);
		$instituicao = $this->buildInstituicao();
		$tipoRegime->setInstituicao($instituicao);

		return $tipoRegime;
	}

	private function buildCursosHabilitacoes()
	{
		$cursoHabilitacoes = new ArrayCollection(); 
		$habilitacao = new \Escola\Entity\Habilitacao();
        $habilitacao->setNome('Habilitacao Nome');
		$habilitacao->setDescricao('Desc Habilitacao');
		$habilitacao->setAtivo(true);
		$instituicao = $this->buildInstituicao();
		$habilitacao->setInstituicao($instituicao);

        $cursoHabilitacoes->add($habilitacao);

        return $cursoHabilitacoes;
	}

}
