<?php


use HelgeSverre\Brreg\Services\BrregDataService;

class BrregServiceTest extends \PHPUnit\Framework\TestCase
{

    public function buildService()
    {
        var_dump(class_exists(BrregDataService::class));
        return new BrregDataService(
            new \League\Fractal\Manager(),
            new \GuzzleHttp\Client()
        );

        static $service = null;

        if ($service == null) {
            $service = new BrregDataService(
                new \League\Fractal\Manager(),
                new \GuzzleHttp\Client()
            );
        }

        return $service;
    }

    /** @test */
    public function it_returns_false_if_the_company_not_found()
    {
        $this->assertFalse($this->buildService()->getCompanyData(999999999999));
    }

    /** @test */
    public function it_returns_empty_array_if_search_yields_no_results()
    {
        $results = $this->buildService()->searchByName("xxxxxxxxxxxxxx");
        $this->assertEmpty($results["data"]);
    }

    /** @test */
    public function it_can_return_array_of_company_data_when_searching_for_valid_company_name()
    {
        // There is at least 50 + companies who's name starts with "webutvikler",
        // if this for some reason is not the case in the future, use a more generic term that yields more results
        $companies = $this->buildService()->searchByName("webutvikler");

        $this->assertArrayHasKey("data", $companies);
        $this->assertTrue(is_array($companies["data"]));
    }


    /** @test */
    public function it_returns_the_requested_number_of_results()
    {
        $companies = $this->buildService()->searchByName("proff", 0, 17);
        $this->assertCount(17, $companies["data"]);
    }

    /** @test */
    public function it_returns_expected_array_items_when_returning_valid_company_data()
    {
        $companies = $this->buildService()->searchByName("Webutvikler");

        $firstCompanyInList = $companies["data"][0];

        $this->assertArrayHasKey("name", $firstCompanyInList);
        $this->assertArrayHasKey("registration_number", $firstCompanyInList);
        $this->assertArrayHasKey("registration_date", $firstCompanyInList);
        $this->assertArrayHasKey("employee_count", $firstCompanyInList);
        $this->assertArrayHasKey("bankrupt", $firstCompanyInList);
        $this->assertArrayHasKey("address", $firstCompanyInList);
    }


    /** @test */
    public function it_can_sanitize_company_numbers_with_whitespace()
    {
        $expected = "123123123";

        $this->assertSame($expected, $this->buildService()->sanitizeRegistrationNumber("123 123 123"));
        $this->assertSame($expected, $this->buildService()->sanitizeRegistrationNumber("123 123123"));
        $this->assertSame($expected, $this->buildService()->sanitizeRegistrationNumber("123123 123"));
        $this->assertSame($expected, $this->buildService()->sanitizeRegistrationNumber("   123 123 123    "));
    }
}