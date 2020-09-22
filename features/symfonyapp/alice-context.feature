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
        public $login;

        /**
         * @ORM\Column
         */
        public $name;
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
          public $name;

          /**
           * @ORM\Column(type="decimal")
           */
          public $price;

          /**
           * @ORM\ManyToOne(targetEntity="User")
           */
          public $user;
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
          Knp\FriendlyContexts\Extension:
            alice:
              fixtures:
                Users: features/fixtures/users.yml
                Products: features/fixtures/products.yml
              dependencies:
                Products: [Users]
        suites:
          simple:
            type: symfony_bundle
            bundle: KnpFcTestBundle
            contexts:
              - FeatureContext: ~
              - Behat\MinkExtension\Context\MinkContext: ~
              - Knp\FriendlyContexts\Context\AliceContext: ~
      """
    And a file named "features/fixtures/users.yml" with:
      """
      Knp\FcTestBundle\Entity\User:
        user-john:
          login: john.doe
          name: John Doe
        user-admin:
          login: admin
          name: Admin
      """
    And a file named "features/fixtures/products.yml" with:
      """
      Knp\FcTestBundle\Entity\Product:
        phone:
          name: Phone
          price: 999.99
          user: "@user-john"
        tablet:
          name: Tablet
          price: 499.99
          user: "@user-john"
        TV:
          name: TV
          price: 299.99
          user: "@user-john"
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
          $userRepo = $em->getRepository(User::class);
          $productRepo = $em->getRepository(Product::class);
          return $this->render('@KnpFcTest/Default/index.html.twig', array(
            'users' => $userRepo->findBy(array()),
            'products' => $productRepo->findBy(array()),
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
          <h1>Summary</h1>
          <h2>Users ({{ users|length }})
          <ul>
          {% for user in users %}
            <li>{{ user.login }} is {{ user.name }}</li>
          {% endfor %}
          </ul>
          <h2>Products ({{ products|length }})
          <ul>
          {% for product in products %}
            <li>{{ product.name }} created by {{ product.user.name }}</li>
          {% endfor %}
          </ul>
        </body>
      </html>
      """


  Scenario: I can import all Alice Fixtures
    Given a file named "src/Knp/FcTestBundle/Features/summary.feature" with:
      """
      @alice(*)
      Feature: Summary
        In order to manage users and products
        As an admin
        I need to see a summary of all

        Scenario:
          Given I am on the homepage
          Then I should see "Users (2)"
          And I should see "Products (3)"
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ...

      1 scenario (1 passed)
      3 steps (3 passed)
      """

  Scenario: I specifcy the Alice fixtures to load
    Given a file named "src/Knp/FcTestBundle/Features/summary.feature" with:
      """
      @alice(Users)
      Feature: Summary
        In order to manage users and products
        As an admin
        I need to see a summary of all

        Scenario:
          Given I am on the homepage
          Then I should see "Users (2)"
          And I should see "Products (0)"
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ...

      1 scenario (1 passed)
      3 steps (3 passed)
      """

  Scenario: I can set dependencies between Alice fixture files
    Given a file named "src/Knp/FcTestBundle/Features/summary.feature" with:
      """
      @alice(Products)
      Feature: Users
        In order to manage users and products
        As an admin
        I need to see a summary of all

        Scenario:
          Given I am on the homepage
          Then I should see "Users (2)"
          And I should see "Products (3)"
      """
    When I run "behat --no-colors -f progress"
    Then it should pass with:
      """
      ...

      1 scenario (1 passed)
      3 steps (3 passed)
      """
