<?php


namespace HelgeSverre\Brreg\Transformers;


use League\Fractal\TransformerAbstract;

class BrregCompanyDataTransformer extends TransformerAbstract
{
    public function transform($company)
    {
        return [
            "name" => $company->navn,
            "registration_number" => $company->organisasjonsnummer,
            "registration_number_pretty" => implode(" ", str_split($company->organisasjonsnummer, 3)),
            "registration_date" => $company->registreringsdatoEnhetsregisteret,
            "employee_count" => $company->antallAnsatte,
            "visiting_address" => (isset($company->forretningsadresse)) ? $this->parseAddress($company->forretningsadresse) : null,
            "mailing_address" => (isset($company->postadresse)) ? $this->parseAddress($company->postadresse) : null,
            "bankrupt" => $company->konkurs == "N" ? 0 : 1,
            "vat_registered" => $company->registrertIMvaregisteret == "J" ? 1 : 0,
            "business_sectors" => $this->parseBusinessSector($company),
            "business_type" => $company->organisasjonsform,
            "website" => isset($company->hjemmeside) ? $company->hjemmeside : null
        ];

    }

    private function parseAddress($address)
    {
        // No address
        if (!$address) {
            return null;
        }

        $addressInfo = [];


        if (isset($address->adresse)) {
            $addressInfo["address"] = $address->adresse;
        }

        if (isset($address->postnummer)) {
            $addressInfo["postal_code"] = $address->postnummer;
        }

        if (isset($address->poststed)) {
            $addressInfo["city"] = $address->poststed;
        }

        if (isset($address->land)) {
            $addressInfo["country"] = $address->land;
        }

        return $addressInfo;
    }

    private function parseBusinessSector($company)
    {
        $sectors = [];

        // There can be up to 3 business sectors defined afaik
        for ($i = 1; $i <= 3; $i++) {
            $field = "naeringskode{$i}";

            if (!isset($company->$field)) {
                break;
            }

            $sectors[] = $company->$field->kode . " - " . $company->$field->beskrivelse;
        }

        return $sectors;
    }
}
