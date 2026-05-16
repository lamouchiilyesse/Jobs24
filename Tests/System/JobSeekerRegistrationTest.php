<?php

declare(strict_types=1);

namespace App\Tests\System;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JobSeekerRegistrationTest extends WebTestCase
{
    public function testSuccessfulRegistration(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();
        $router = $client->getContainer()->get('router');
        $faker = \Faker\Factory::create();

        // Request a specific page
        $crawler = $client->request('GET', $router->generate('register_job_seeker'));
        $this->assertResponseIsSuccessful();

        //fake data
        $firstName =  $faker->firstName();
        $lastName =  $faker->lastName();
        $email =  $faker->unique()->safeEmail();
        $password = 'Password123!';

        $form =  $crawler->filter('form[name=job_seeker_form]')->form([
            'job_seeker_form[firstName]' => $firstName,
            'job_seeker_form[lastName]' => $lastName,
            'job_seeker_form[email]' => $email,
            'job_seeker_form[plainPassword]' => $password,
        ]);
        $client->submit($form);

        // 1. Check the IMMEDIATE response (The 302 Redirect)
        // This must happen BEFORE followRedirect()
        $this->assertResponseRedirects('/');

        // 2. Now "drive" to the homepage
        $client->followRedirect();

        // 3. Now check the FINAL page content (The 200 OK)
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('.alert-success');
        $this->assertSelectorTextContains('.alert-success', 'Welcome aboard! Your account is ready.');


            // Check the database
            $container = static::getContainer();
            $user = $container->get('doctrine')->getRepository(\App\Entity\JobSeeker::class)
                ->findOneBy(['email' => $email]) ;
            // Assert that the user was created and has the expected data
            $this->assertNotNull($user);
            $this->assertSame($firstName, $user->getFirstName());
            $this->assertSame($lastName, $user->getLastName());
    }

    public function testEmailNotValid() : void
    {
        $client = static::createClient();
        $router = $client->getContainer()->get('router');

        $crawler = $client->request('GET', $router->generate('register_job_seeker'));
        $this->assertResponseIsSuccessful();

        $form =  $crawler->filter('form[name=job_seeker_form]')->form([
            'job_seeker_form[firstName]' => 'John',
            'job_seeker_form[lastName]' => 'Doe',
            'job_seeker_form[email]' => 'invalid-email',
            'job_seeker_form[plainPassword]' => 'Password123!',
        ]);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('.form-error-message', 'Please enter a valid email address.');

    }
}
