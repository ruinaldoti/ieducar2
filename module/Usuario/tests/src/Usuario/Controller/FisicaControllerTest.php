<?php
use Core\Test\ControllerTestCase;
use Usuario\Controller\FisicaController;
use Usuario\Entity\Fisica;
use Usuario\Entity\Raca;
use Core\Entity\tipoLogradouro;
use Core\Entity\Uf;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group  Controller
 */
class FisicaControllerTest extends ControllerTestCase
{
	/**
	 * Namespace completa do Controller
	 * @var string FisicaController
	 */
	protected $controllerFQDN = 'Usuario\Controller\FisicaController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string usuario
	 */
	protected $controllerRoute = 'usuario';

	/**
	 * Testa a pagina inicial, que deve mostrar as pessoas fisicas
	 */
	public function testFisicaIndexAction()
	{
		//	cria pessoas fisicas para testar
		$pA = $this->buildFisica();
		$pA->setCpf("111.111.111-11");
		$pB = $this->buildFisica();
		$pB->setNome("GOLD");
		$pB->setCpf("222.222.222-22");
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($pA);
		$em->persist($pB);
		$em->flush();

		//	Invoca a rota index
		$this->routeMatch->setParam('action', 'index');
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica o response
		$response = $this->controller->getResponse();		
		$this->assertEquals(200, $response->getStatusCode());

		//	Testa se um ViewModel foi retornado
		$this->assertInstanceOf('Zend\View\Model\ViewModel', $result);
		

		//	Testa os dados da View
		$variables = $result->getVariables();

		$this->assertArrayHasKey('dados', $variables);

		//	Faz a comparação dos dados		
		$paginator = $variables["dados"];
		$this->assertEquals('Zend\Paginator\Paginator', get_class($paginator));		
		$this->assertEquals($pA->getNome(), $paginator->getItem(1)->getNome());
		$this->assertEquals($pB->getNome(), $paginator->getItem(2)->getNome());

	}

	/**
	 * Testa a tela de inclusao de um novo registro
	 * @return void
	 */
	public function testFisicaSaveActionNewRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a resposta
		$response = $this->controller->getResponse();
		$this->assertEquals(200, $response->getStatusCode());

		//	Testa se recebeu um ViewModel
		$this->assertInstanceOf('Zend\View\Model\ViewModel', $result);

		//	Verifica se existe um form
		$variables = $result->getVariables();
		$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
		$form = $variables['form'];
		//	Testa os itens do formulario
		$id = $form->get('id');
		$this->assertEquals('id', $id->getName());
		$this->assertEquals('hidden', $id->getAttribute('type'));

		$dataNasc = $form->get('dataNasc');
		$this->assertEquals('dataNasc', $dataNasc->getName());
		$this->assertEquals('date', $dataNasc->getAttribute('type'));

		$sexo = $form->get('sexo');
		$this->assertEquals('sexo', $sexo->getName());
		$this->assertEquals('Zend\Form\Element\Select', $sexo->getAttribute('type'));

		$dataUniao = $form->get('dataUniao');
		$this->assertEquals('dataUniao', $dataUniao->getName());
		$this->assertEquals('text', $dataUniao->getAttribute('type'));

		$nacionalidade = $form->get('nacionalidade');
		$this->assertEquals('nacionalidade', $nacionalidade->getName());
		$this->assertEquals('Zend\Form\Element\Select', $nacionalidade->getAttribute('type'));

		$dataChegadaBrasil = $form->get('dataChegadaBrasil');
		$this->assertEquals('dataChegadaBrasil', $dataChegadaBrasil->getName());
		$this->assertEquals('text', $dataChegadaBrasil->getAttribute('type'));

		$ultimaEmpresa = $form->get('ultimaEmpresa');
		$this->assertEquals('ultimaEmpresa', $ultimaEmpresa->getName());
		$this->assertEquals('text', $ultimaEmpresa->getAttribute('type'));

		$nomeMae = $form->get('nomeMae');
		$this->assertEquals('nomeMae', $nomeMae->getName());
		$this->assertEquals('text', $nomeMae->getAttribute('type'));

		$nomePai = $form->get('nomePai');
		$this->assertEquals('nomePai', $nomePai->getName());
		$this->assertEquals('text', $nomePai->getAttribute('type'));

		$nomeConjuge = $form->get('nomeConjuge');
		$this->assertEquals('nomeConjuge', $nomeConjuge->getName());
		$this->assertEquals('text', $nomeConjuge->getAttribute('type'));

		$nomeResponsavel = $form->get('nomeResponsavel');
		$this->assertEquals('nomeResponsavel', $nomeResponsavel->getName());
		$this->assertEquals('text', $nomeResponsavel->getAttribute('type'));

		$justificativaProvisorio = $form->get('justificativaProvisorio');
		$this->assertEquals('justificativaProvisorio', $justificativaProvisorio->getName());
		$this->assertEquals('text', $justificativaProvisorio->getAttribute('type'));

		$cpf = $form->get('cpf');
		$this->assertEquals('cpf', $cpf->getName());
		$this->assertEquals('text', $cpf->getAttribute('type'));

		$idmunNascimento = $form->get('idmunNascimento');
		$this->assertEquals('idmunNascimento', $idmunNascimento->getName());
		$this->assertEquals('Zend\Form\Element\Select', $idmunNascimento->getAttribute('type'));

		$idpaisEstrangeiro = $form->get('idpaisEstrangeiro');
		$this->assertEquals('idpaisEstrangeiro', $idpaisEstrangeiro->getName());
		$this->assertEquals('Zend\Form\Element\Select', $idpaisEstrangeiro->getAttribute('type'));

		$idesco = $form->get('idesco');
		$this->assertEquals('idesco', $idesco->getName());
		$this->assertEquals('Zend\Form\Element\Select', $idesco->getAttribute('type'));

		$ideciv = $form->get('ideciv');
		$this->assertEquals('ideciv', $ideciv->getName());
		$this->assertEquals('Zend\Form\Element\Select', $ideciv->getAttribute('type'));

		$idocup = $form->get('idocup');
		$this->assertEquals('idocup', $idocup->getName());
		$this->assertEquals('text', $idocup->getAttribute('type'));

		$refCodReligiao = $form->get('refCodReligiao');
		$this->assertEquals('refCodReligiao', $refCodReligiao->getName());
		$this->assertEquals('Zend\Form\Element\Select', $refCodReligiao->getAttribute('type'));
	}

	/**
	 * Testa a tela de alteracao de um registro
	 */
	public function testFisicaSaveActionUpdateFormRequest()
	{
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $fisica->getId());
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a resposta
		$response = $this->controller->getResponse();
		$this->assertEquals(200, $response->getStatusCode());

		//	Testa se recebeu um ViewModel
		$this->assertInstanceOf('Zend\View\Model\ViewModel', $result);		
		$variables = $result->getVariables();

		//	Verifica se existe um form
		$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
		$form = $variables['form'];

		//	Testa os itens do formulario
		$id = $form->get('id');
		$cpf = $form->get('cpf');
		$this->assertEquals('id', $id->getName());
		$this->assertEquals($fisica->getId(), $id->getValue());
		$this->assertEquals($fisica->getCpf(), $cpf->getValue());
	}

	/**
	 * Testa a inclusao de uma nova pessoa fisica
	 */
	public function testFisicaSaveActionPostRequest()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		//	Cadastra uma raça
		$raca = $this->buildRaca();
		$em->persist($raca);
		// Cadastra um tipo de logradouro
		$tipoLogradouro = $this->buildTipoLogradouro();
		$em->persist($tipoLogradouro);
		// Cadastra um Uf
		$uf = $this->buildUf();
		$em->persist($uf);
		
		$em->flush();		

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');
		$this->request->getPost()->set('sexo', 'M');
		$this->request->getPost()->set('nome', 'Garrincha');
		$this->request->getPost()->set('url', 'www.eduardojunior.com');		
		$this->request->getPost()->set('email', 'ej@eduardojunior.com');
		$this->request->getPost()->set('situacao', 'A');
		$this->request->getPost()->set('nacionalidade', "1");
		$this->request->getPost()->set('raca', $raca->getId());
		$this->request->getPost()->set('cpf', '');
		$this->request->getPost()->set('tipoLogradouro', $tipoLogradouro->getId());
		$this->request->getPost()->set('uf', $uf->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /usuario/fisica', $headers->get('Location'));
	}

	/**
	 * Testa o update de uma pessoa fisica
	 */
	public function testFisicaUpdateAction()
	{
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		//	Cadastra uma raça
		$raca = $this->buildRaca();		
		$em->persist($raca);
		// Cadastra um tipo de logradouro
		$tipoLogradouro = $this->buildTipoLogradouro();
		$em->persist($tipoLogradouro);
		// Cadastra um Uf
		$uf = $this->buildUf();
		$em->persist($uf);
		

		$fisica = $this->buildFisica();
		$fisica->setNome('Bill Gates');
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
    	$em->flush();
				
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $fisica->getId());

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', $fisica->getId());
		$this->request->getPost()->set('nome', 'Alan Turing');
		$this->request->getPost()->set('url', '');
		$this->request->getPost()->set('tipo', 'J');
		$this->request->getPost()->set('email', '');
		$this->request->getPost()->set('situacao', 'I');
		$this->request->getPost()->set('sexo', 'M');
		$this->request->getPost()->set('cpf', '222.222.222-22');
		$this->request->getPost()->set('raca', $raca->getId());
		$this->request->getPost()->set('tipoLogradouro', $tipoLogradouro->getId());
		$this->request->getPost()->set('uf', $uf->getId());



		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		$response = $this->controller->getResponse();
		//	a pagina rediriciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();

		$this->assertEquals(
			'Location: /usuario/fisica', $headers->get('Location')
		);
	}

	/**
	 * Tenta salvar com dados invalidos
	 */
	public function testFisicaSaveActionInvalidPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('cpf', '222.222.222-222');
		
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica se existe um form		
		$variables = $result->getVariables();
		$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
		$form = $variables['form'];

		//	testa os erros do formulario
		$cpf = $form->get('cpf');
		$cpfErrors = $cpf->getMessages();		
		$this->assertEquals(
			"The input is more than 11 characters long", $cpfErrors['stringLengthTooLong']
		);
	}

	/**
	 * Testa a exclusao sem passar o id da pessoa
	 * @expectedException Exception
	 * @expectedExceptionMessage Código Obrigatório
	 */
	public function testFisicaInvalidDeleteAction()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a resposta
		$response = $this->controller->getResponse();
	}


	/**
	 * Testa a exclusao de uma pessoa
	 */
	public function testFisicaDeleteAction()
	{
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
    	$em->flush();		
		
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', $fisica->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /usuario/fisica', $headers->get('Location')
		);
	}

	/**
	 * Testa a exlusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testFisicaInvalidIdDeleteAction()
	{
		$fisica = $this->buildFisica();
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($fisica);
    	$em->flush();		
		
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', 2);

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /usuario/fisica', $headers->get('Location')
		);	
	}

	private function buildFisica()
	{	
    	/**
    	 * Dados fisica
    	 */    	
		$fisica = new Fisica;		
		$fisica->setSexo("M");
		$fisica->setOrigemGravacao("M");
		$fisica->setOperacao("I");
		$fisica->setIdsisCad(1);
		$fisica->setNome('Steve Jobs');
		$fisica->setSituacao('A');
		$fisica->setOrigemGravacao('U');
		$fisica->setOperacao('I');
		$fisica->setIdsisCad(1);
		$fisica->setCpf('111.111.111-11');

    	return $fisica;
	}

	private function buildTipoLogradouro()
	{
		$tipoLogradouro = new tipoLogradouro;
		$tipoLogradouro->setDescricao('Rua');

		return $tipoLogradouro;
	}

	private function buildUf()
	{
		$uf = new Uf;
		$uf->setUf('BA');
		$uf->setNome('Bahia');
		$uf->setCep1('44900');
		$uf->setCep2('44905');

		return $uf;
	}

	public function buildRaca()
	{
		$raca = new Raca;
		$raca->setNome('Branca');

		return $raca;
	}

}