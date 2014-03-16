<?php
use Core\Test\ControllerTestCase;
use Usuario\Controller\JuridicaController;
use Usuario\Entity\Juridica;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;

/**
 * @group  Controller
 */
class JuridicaControllerTest extends ControllerTestCase
{
	/**
	 * Namespace completa do Controller
	 * @var string JuridicaController
	 */
	protected $controllerFQDN = 'Usuario\Controller\JuridicaController';

	/**
	 * Nome da rota. geralmente o nome do modulo
	 * @var string usuario
	 */
	protected $controllerRoute = 'usuario';

	/**
	 * Testa a pagina inicial, que deve mostar as pessoas juridicas
	 */
	public function testJuridicaIndexAction()
	{
		$pA = $this->buildJuridica();
		$pA->setNome("Steve Jobs");
		$pA->setTipo("J");
		$pA->setSituacao("A");
		$pA->setOrigemGravacao("M");
		$pA->setOperacao("I");
		$pA->setIdSisCad(1);

		$pB = $this->buildJuridica();
		$pB->setFantasia('Apple.com');
		$pB->setNome("Steve Jobs");
		$pB->setTipo("J");
		$pB->setSituacao("A");
		$pB->setOrigemGravacao("M");
		$pB->setOperacao("I");
		$pB->setIdSisCad(1);

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

		//	Faz a comparacao dos dados
		$controllerData = $variables['dados'];
		$this->assertEquals($pA->getFantasia(), $controllerData[0]->getFantasia());
		$this->assertEquals($pB->getFantasia(), $controllerData[1]->getFantasia());
	}

	/**
	 * Testa a tela de inclusao de um novo registro
	 * @return void 
	 */
	public function testJuridicaSaveActionNewRequest()
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

		$nome = $form->get('nome');
		$this->assertEquals('nome', $nome->getName());
		$this->assertEquals('text', $nome->getAttribute('type'));

		// $situacao = $form->get('situacao');
		// $this->assertEquals('situacao', $situacao->getName());
		// $this->assertEquals('Zend\Form\Element\Select', $situacao->getAttribute('type'));		

		$cnpj = $form->get('cnpj');
		$this->assertEquals('cnpj', $cnpj->getName());
		$this->assertEquals('text', $cnpj->getAttribute('type'));

		$insc_estadual = $form->get('insc_estadual');
		$this->assertEquals('insc_estadual', $insc_estadual->getName());
		$this->assertEquals('text', $insc_estadual->getAttribute('type'));

		$fantasia = $form->get('fantasia');
		$this->assertEquals('fantasia', $fantasia->getName());
		$this->assertEquals('text', $fantasia->getAttribute('type'));

		$capital_social = $form->get('capital_social');
		$this->assertEquals('capital_social', $capital_social->getName());
		$this->assertEquals('text', $capital_social->getAttribute('type'));
	}

	/**
	 * Testa a tela de alteracao de um registro
	 */
	public function testJuridicaSaveActionUpdateFormRequest()
	{
		$juridica = $this->buildJuridica();
		$juridica->setNome("Steve Jobs");
		$juridica->setTipo("J");
		$juridica->setSituacao("A");
		$juridica->setOrigemGravacao("M");
		$juridica->setOperacao("I");
		$juridica->setIdSisCad(1);
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($juridica);
		$em->flush();

		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $juridica->getId());
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
		$cnpj = $form->get('cnpj');
		$this->assertEquals('id', $id->getName());
		$this->assertEquals($juridica->getId(), $id->getValue());
		$this->assertEquals($juridica->getCnpj(), $cnpj->getValue());
	}

	/**
	 * Testa a inclusao de uma nova pessoa juridica
	 */
	public function testJuridicaSaveActionPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', '');		
		$this->request->getPost()->set('nome', 'Garrincha');
		$this->request->getPost()->set('situacao', 'A');
		$this->request->getPost()->set('cnpj', '52.476.528/0001-35');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);
		//	Verifica a resposta
		$response = $this->controller->getResponse();		
		//	a pagina redireciona, estao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals('Location: /usuario/juridica', $headers->get('Location'));
	}


	/**
	 * Testa o update de uma pessoa juridica
	 */
	public function testJuridicaUpdateAction()
	{
		$juridica = $this->buildJuridica();
		$juridica->setNome('Bill Gates');
		$juridica->setTipo("J");
		$juridica->setSituacao("A");
		$juridica->setOrigemGravacao("M");
		$juridica->setOperacao("I");
		$juridica->setIdSisCad(1);
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($juridica);
    	$em->flush();
				
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');
		$this->routeMatch->setParam('id', $juridica->getId());

		$this->request->setMethod('post');
		$this->request->getPost()->set('id', $juridica->getId());
		$this->request->getPost()->set('nome', 'Alan Turing');
		$this->request->getPost()->set('situacao', 'I');
		$this->request->getPost()->set('cnpj', '52.476.528/0001-35');

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		$response = $this->controller->getResponse();
		//	a pagina rediriciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();

		$this->assertEquals(
			'Location: /usuario/juridica', $headers->get('Location')
		);
	}

	/**
	 * Tenta salvar com dados invalidos
	 */
	public function testJuridicaSaveActionInvalidPostRequest()
	{
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'save');

		$this->request->setMethod('post');
		$this->request->getPost()->set('cnpj', '52.476.528/0001-3522222');
		
		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica se existe um form		
		$variables = $result->getVariables();
		$this->assertInstanceOf('Zend\Form\Form', $variables['form']);
		$form = $variables['form'];

		//	testa os erros do formulario
		$cnpj = $form->get('cnpj');
		$cnpjErrors = $cnpj->getMessages();		
		$this->assertEquals(
			"The input is more than 14 characters long", $cnpjErrors['stringLengthTooLong']
		);
	}

	/**
	 * Testa a exclusao sem passar o id da pessoa
	 * @expectedException Exception
	 * @expectedExceptionMessage Código Obrigatório
	 */
	public function testJuridicaInvalidDeleteAction()
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
	public function testJuridicaDeleteAction()
	{
		$juridica = $this->buildJuridica();
		$juridica->setNome("Steve Jobs");
		$juridica->setTipo("J");
		$juridica->setSituacao("A");
		$juridica->setOrigemGravacao("M");
		$juridica->setOperacao("I");
		$juridica->setIdSisCad(1);
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($juridica);
    	$em->flush();		
		
		//	Dispara a acao
		$this->routeMatch->setParam('action', 'delete');
		$this->routeMatch->setParam('id', $juridica->getId());

		$result = $this->controller->dispatch(
			$this->request, $this->response
		);

		//	Verifica a reposta
		$response = $this->controller->getResponse();

		//	A pagina redireciona, entao o status = 302
		$this->assertEquals(302, $response->getStatusCode());
		$headers = $response->getHeaders();
		$this->assertEquals(
			'Location: /usuario/juridica', $headers->get('Location')
		);
	}

	/**
	 * Testa a exlusao passando um id inexistente
	 * @expectedException Exception
	 * @expectedExceptionMessage Registro não encontrado
	 */
	public function testJuridicaInvalidIdDeleteAction()
	{
		$juridica = $this->buildJuridica();
		$juridica->setNome("Steve Jobs");
		$juridica->setTipo("J");
		$juridica->setSituacao("A");
		$juridica->setOrigemGravacao("M");
		$juridica->setOperacao("I");
		$juridica->setIdSisCad(1);
		$em = $this->serviceManager->get('Doctrine\ORM\EntityManager');
		$em->persist($juridica);
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
			'Location: /usuario/juridica', $headers->get('Location')
		);	
	}

	private function buildJuridica()
	{
		/**
		 * Dados Juridica
		 */
		$juridica = new Juridica;		
		$juridica->setCnpj('52.476.528/0001-35');
		$juridica->setInscEstadual('866498342');
		$juridica->setFantasia('Eduardojunior.com');
		$juridica->setCapitalSocial('capital social');
		
		return $juridica;
	}
}