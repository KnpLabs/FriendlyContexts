Feature: Entity Context
  In order to use the entity context
  As a developer
  I need to be able to add it to my configuration

  Background:
    Given a file named "src/Knp/FcTestBundle/Entity/User.php" with:
      """
      <?php
      namespace Knp\FcTestBundle\Entity;

      use Doctrine\Common\Collections\ArrayCollection;
      use Doctrine\ORM\Mapping as ORM;

      /**
       * @ORM\Entity
       */
      class User
      {
        /**
         * @ORM\Column(type="bigint")
         * @ORM\Id
         * @ORM\GeneratedValue
         */
        public $id;

        /**
         * @ORM\Column
         */
        public $Login;

        /**
         * @ORM\Column
         */
        public $Name;
      }
      """
    And a file named "src/Knp/FcTestBundle/Entity/Product.php" with:
      """
      <?php
      namespace Knp\FcTestBundle\Entity;

      use Doctrine\Common\Collections\ArrayCollection;
      use Doctrine\ORM\Mapping as ORM;

      /**
       * @ORM\Entity
       */
      class Product
      {
          /**
           * @ORM\Column(type="bigint")
           * @ORM\Id
           * @ORM\GeneratedValue
           */
          public $id;

          /**
           * @ORM\Column
           */
          public $Name;

          /**
           * @ORM\Column(type="decimal")
           */
          public $Price;

          /**
           * @ORM\ManyToOne(targetEntity="User")
           */
          public $User;
      }
      """
    And I have the following behat configuration:
      """
      default:
        formatters:
          progress:
            paths: false
        extensions:
          Behat\Symfony2Extension: ~
          Behat\MinkExtension:
            default_session: 'symfony2'
            sessions:
              symfony2:
                symfony2: ~
          Knp\FriendlyContexts\Extension: ~
        suites:
          simple:
            type: symfony_bundle
            bundle: KnpFcTestBundle
            contexts:
              - FeatureContext: ~
              - Behat\MinkExtension\Context\MinkContext: ~
              - Knp\FriendlyContexts\Context\EntityContext: ~
      """


  Scenario: I can create records from an entity
    Given a file named "src/Knp/FcTestBundle/Features/list-users.feature" with:
      """
      Feature: list users
        In order to manage users
        As an admin
        I need to see a list of users and their full names

        Scenario:
          Given the following users:
            | login | name            |
            | admin | John Doe        |
            | user  | George Abitbol  |
          And I am on the homepage
          Then I should see "admin is John Doe"
          And I should see "user is George Abitbol"
      """
    And a file named "src/Knp/FcTestBundle/Controller/DefaultController.php" with:
      """
      <?php
      namespace Knp\FcTestBundle\Controller;

      use Knp\FcTestBundle\Entity\User;
      use Symfony\Bundle\FrameworkBundle\Controller\Controller;

      class DefaultController extends Controller
      {
        public function indexAction()
        {
          return $this->render('@KnpFcTest/Default/index.html.twig', array(
            'users' => $this->container->get('doctrine')->getEntityManager()->getRepository(User::class)->findBy(array()),
          ));
        }
      }
      """
    And a file named "src/Knp/FcTestBundle/Resources/views/Default/index.html.twig" with:
      """
      <!DOCTYPE html>
      <html>
        <head></head>
        <body>
          <h1>Users</h1>
          <ul>
          {% for user in users %}
            <li>{{ user.Login }} is {{ user.Name }}</li>
          {% endfor %}
          </ul>
        </body>
      </html>
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ....

      1 scenario (1 passed)
      4 steps (4 passed)
      """

  Scenario: I can associate records
    Given a file named "src/Knp/FcTestBundle/Features/list-products.feature" with:
      """
      Feature: list products
        In order to manage products
        As an admin
        I need to see a list of products and who created them

        Scenario:
          Given the following users:
            | login         | name            |
            | admin         | John Doe        |
            | george.abitol | George Abitbol  |
          And the following products:
            | name        | price  | user          |
            | Phone       | 698.95 | admin         |
            | Tablet      | 890.95 | george.abitol |
            | TV          | 390.00 | george.abitol |
          And I am on the homepage
          Then I should see "Phone created by John Doe"
          Then I should see "Tablet created by George Abitbol"
          And I should see "TV created by George Abitbol"
      """
    And a file named "src/Knp/FcTestBundle/Controller/DefaultController.php" with:
      """
      <?php
      namespace Knp\FcTestBundle\Controller;

      use Knp\FcTestBundle\Entity\Product;
      use Symfony\Bundle\FrameworkBundle\Controller\Controller;

      class DefaultController extends Controller
      {
        public function indexAction()
        {
          return $this->render('@KnpFcTest/Default/index.html.twig', array(
            'products' => $this->container->get('doctrine')->getEntityManager()->getRepository(Product::class)->findBy(array()),
          ));
        }
      }
      """
    And a file named "src/Knp/FcTestBundle/Resources/views/Default/index.html.twig" with:
      """
      <!DOCTYPE html>
      <html>
        <head></head>
        <body>
          <h1>Users</h1>
          <ul>
          {% for product in products %}
            <li>{{ product.Name }} created by {{ product.User.Name }}</li>
          {% endfor %}
          </ul>
        </body>
      </html>
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ......

      1 scenario (1 passed)
      6 steps (6 passed)
      """

  Scenario: I can create and associate entities in bulk
    Given a file named "src/Knp/FcTestBundle/Features/count-users.feature" with:
      """
      Feature: Summaries Products
        In order to manage products
        As an admin
        I need to see a summary

        Scenario:
          Given the following users:
            | login          | name           |
            | george.abitbol | George Abitbol |
          And there is 10 users
          And there is 20 products like:
            | user           |
            | george.abitbol |
          And I am on the homepage
          When I am on the homepage
          Then I should see "20 products created by 11 users"
      """
    And a file named "src/Knp/FcTestBundle/Controller/DefaultController.php" with:
      """
      <?php
      namespace Knp\FcTestBundle\Controller;

      use Knp\FcTestBundle\Entity\Product;
      use Knp\FcTestBundle\Entity\User;
      use Symfony\Bundle\FrameworkBundle\Controller\Controller;

      class DefaultController extends Controller
      {
        public function indexAction()
        {
          $em = $this->container->get('doctrine')->getEntityManager();
          return $this->render('@KnpFcTest/Default/index.html.twig', array(
            'users' => $em->getRepository(User::class)->findBy(array()),
            'products' => $em->getRepository(Product::class)->findBy(array()),
          ));
        }
      }
      """
    And a file named "src/Knp/FcTestBundle/Resources/views/Default/index.html.twig" with:
      """
      <!DOCTYPE html>
      <html>
        <head></head>
        <body>
          <p>{{ products|length }} products created by {{ users|length }} users</p>
        </body>
      </html>
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ......

      1 scenario (1 passed)
      6 steps (6 passed)
      """

  Scenario: I can assert a number of entities were created
    Given a file named "src/Knp/FcTestBundle/Features/list-users.feature" with:
      """
      Feature: list users
        In order to manage users
        As an admin
        I need to be able to create users

        Scenario:
          Given I am on the homepage
          When I fill in the following:
            | Login | george.abitbol |
            | Name  | George Abitbol |
          And I press "Create User"
          Then I should see "User created successfully!"
          And 1 user should have been created
          Then should be 1 user like:
            | Login          | Name           |
            | george.abitbol | George Abitbol |
      """
    And a file named "src/Knp/FcTestBundle/Controller/DefaultController.php" with:
      """
      <?php
      namespace Knp\FcTestBundle\Controller;

      use Knp\FcTestBundle\Entity\User;
      use Symfony\Bundle\FrameworkBundle\Controller\Controller;
      use Symfony\Component\Form\Extension\Core\Type\SubmitType;
      use Symfony\Component\Form\Extension\Core\Type\TextType;
      use Symfony\Component\HttpFoundation\Request;

      class DefaultController extends Controller
      {
        public function indexAction(Request $request)
        {
          $user = new User();

          $form = $this->createFormBuilder($user)
            ->add('Login', TextType::class)
            ->add('Name', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Create User'])
            ->getForm();

          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'User created successfully!'
            );
          }

          return $this->render('@KnpFcTest/Default/index.html.twig', array(
            'form' => $form->createView(),
          ));
        }
      }
      """
    And a file named "src/Knp/FcTestBundle/Resources/views/Default/index.html.twig" with:
      """
      <!DOCTYPE html>
      <html>
        <head></head>
        <body>
          <h1>Create User</h1>
          {% for flash_message in app.session.flashBag.get('success') %}
            <div class="flash-success">{{ flash_message }}</div>
          {% endfor %}
          {{ form(form) }}
        </body>
      </html>
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ......

      1 scenario (1 passed)
      6 steps (6 passed)
      """

  Scenario: I can assert a number of entities were deleted
    Given a file named "src/Knp/FcTestBundle/Features/list-users.feature" with:
      """
      Feature: list users
        In order to manage users
        As an admin
        I need to be able to delete users

        Scenario:
          Given the following users:
            | login | name            |
            | admin | John Doe        |
            | user  | George Abitbol  |
          And I am on the homepage
          When I follow "Delete George Abitbol"
          Then I should see "User deleted successfully!"
          And 1 user should have been deleted
      """
    And a file named "src/Knp/FcTestBundle/Controller/DefaultController.php" with:
      """
      <?php
      namespace Knp\FcTestBundle\Controller;

      use Knp\FcTestBundle\Entity\User;
      use Symfony\Bundle\FrameworkBundle\Controller\Controller;
      use Symfony\Component\HttpFoundation\Request;

      class DefaultController extends Controller
      {
        public function indexAction(Request $request)
        {
          $em = $this->container->get('doctrine')->getEntityManager();
          $repo = $em->getRepository(User::class);

          if ($request->query->has('userid')) {
            $userId = $request->query->get('userid');
            $user = $repo->find($userId);
            $em->remove($user);
            $em->flush();

            $this->addFlash(
                'success',
                'User deleted successfully!'
            );
          }

          return $this->render('@KnpFcTest/Default/index.html.twig', array(
            'users' => $this->container->get('doctrine')->getEntityManager()->getRepository(User::class)->findBy(array()),
          ));
        }
      }
      """
    And a file named "src/Knp/FcTestBundle/Resources/views/Default/index.html.twig" with:
      """
      <!DOCTYPE html>
      <html>
        <head></head>
        <body>
          <h1>Users</h1>
          {% for flash_message in app.session.flashBag.get('success') %}
            <div class="flash-success">{{ flash_message }}</div>
          {% endfor %}
          <ul>
          {% for user in users %}
            <li><a href="/?userid={{ user.id }}">Delete {{ user.Name }}</li>
          {% endfor %}
          </ul>
        </body>
      </html>
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      .....

      1 scenario (1 passed)
      5 steps (5 passed)
      """
