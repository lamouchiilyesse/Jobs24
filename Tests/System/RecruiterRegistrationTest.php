<?php
namespace App\Tests\System;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

    class RecruiterRegistrationTest extends WebTestCase{
        public function testJobSeekerRegistrationSuccess(): void
        {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();
        $faker = \Faker\Factory::create();
        $router = $client->getContainer()->get('router');

        // Request a specific page
        $crawler = $client->request('GET', $router->generate('register_recruiter'));
        //fake data
        $firstName =  $faker->firstName();
        $lastName =  $faker->lastName();
        $email =  $faker->unique()->safeEmail();
        $companyName =  $faker->company();
        //

        $form =  $crawler->filter('form[name=recruiter_form]')->form([
            'recruiter_form[firstName]' => $firstName,
            'recruiter_form[lastName]' => $lastName,
            'recruiter_form[email]' => $email,
            'recruiter_form[plainPassword]' => 'Password123!',
            'recruiter_form[companyName]' =>$companyName,

        ]);
        $client->submit($form);
        // If this fails, it means your form has errors!
        //$this->assertResponseRedirects('/');
        self::assertResponseRedirects('/');
        // Check the database
        $container = static::getContainer();
        $user = $container->get('doctrine')->getRepository(\App\Entity\Recruiter::class)
            ->findOneBy(['email' => $email]) ;
        // Assert that the user was created and has the expected data
        $this->assertNotNull($user);
        $this->assertSame($firstName, $user->getFirstName());
        $this->assertSame($lastName, $user->getLastName());
        $this->assertSame($companyName, $user->getCompanyName());


        // The password should be hashed, so we check that it's not the plain text value
        $this->assertNotEquals('Password123!', $user->getPassword());

        // Optionally, you could also check that the password is hashed correctly using the password encoder
        $passwordHasher = $container->get('security.user_password_hasher');
        $this->assertTrue($passwordHasher->isPasswordValid($user, 'Password123!'),
            'The password was not hashed correctly or does not match the original password.');


    }
}

