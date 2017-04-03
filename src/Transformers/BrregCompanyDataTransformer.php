<?php


namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class BrregCompanyDataTransformer extends TransformerAbstract
{
    public function transform($company)
    {
        return [
            "name" => $company->navn,
            "registration_number" => $company->organisasjonsnummer,
            "registration_date" => $company->registreringsdatoEnhetsregisteret,
            "employee_count" => $company->antallAnsatte,
            "address" => (isset($company->forretningsadresse) ? [
                "address" => $company->forretningsadresse->adresse,
                "postal_code" => $company->forretningsadresse->postnummer,
                "city" => $company->forretningsadresse->poststed,
                "country" => $company->forretningsadresse->land,
            ] : null),
            "bankrupt" => $company->konkurs == "N" ? 0 : 1
        ];

    }

}