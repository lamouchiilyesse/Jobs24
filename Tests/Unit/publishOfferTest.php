<?php
namespace App\Tests\Unit;
use App\Adapter\inMemory\Repository\OfferRepository;
use App\Entity\Offer;
use App\Gateway\OfferGateway;
use App\UseCase\PublishOffer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

/**
 * Class publishOfferTest
 * @package App\Tests\Unit
 */
class publishOfferTest extends TestCase
{
   public function setUp(): void
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
        $this->useCase = new PublishOffer(
            new OfferRepository(),
            $validator
        );
    }

    public function testSuccessfulOfferPublish()
    {

        $offer= new Offer();
        $offer->setTitle("Software Engineer");
        $offer->setJobDescription("We are looking for a skilled software engineer to join our team.");
        $offer->setCompanyDescription("Tech Company");
        $offer->setRemote(true);
        $offer->setMaxSalary(80000);
        $offer->setMinSalary(60000);
        $offer->setSoftSkills("Teamwork, Communication");
        $offer->setProfile("Bachelor's degree in Computer Science or related field");
        $offer->setmissions("Develop and maintain software applications");
        $offer->setTasks("Write clean and efficient code, Collaborate with cross-functional teams");

        $this->assertEquals($offer, $this->useCase->execute($offer));

    }

    public function testBadOfferPublish()
    {
        $useCase = new PublishOffer(
            new OfferRepository(),
            Validation::createValidator()
        );
        $offer= new Offer();
        $offer->setTitle("");
        $this->expectException(\InvalidArgumentException::class);
        $offer->setJobDescription("We are looking for a skilled software engineer to join our team.");
        $offer->setCompanyDescription("Tech Company");
        $offer->setRemote(true);
        $offer->setMaxSalary(80000);
        $offer->setMinSalary(60000);
        $offer->setSoftSkills("Teamwork, Communication");
        $offer->setProfile("Bachelor's degree in Computer Science or related field");
        $offer->setmissions("Develop and maintain software applications");
        $offer->setTasks("Write clean and efficient code, Collaborate with cross-functional teams");

        $this->useCase->execute($offer) ;
    }

    public function testMinimumSalaryGreaterThenMax()
    {
        $useCase = new PublishOffer(
            new OfferRepository(),
            Validation::createValidator()
        );
        $offer= new Offer();
        $offer->setTitle("Fullstack developer");
        $this->expectException(\InvalidArgumentException::class);
        $offer->setJobDescription("We are looking for a skilled software engineer to join our team.");
        $offer->setCompanyDescription("Tech Company");
        $offer->setRemote(true);
        $offer->setMaxSalary(1000);
        $offer->setMinSalary(60000);
        $this->expectException(\InvalidArgumentException::class);
        $offer->setSoftSkills("Teamwork, Communication");
        $offer->setProfile("Bachelor's degree in Computer Science or related field");
        $offer->setmissions("Develop and maintain software applications");
        $offer->setTasks("Write clean and efficient code, Collaborate with cross-functional teams");

        $this->useCase->execute($offer) ;
    }
}