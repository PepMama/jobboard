<?php

namespace App\Service;

use App\Entity\Users;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;

class CompanyService
{
    private $companyRepository;
    private $em;

    public function __construct(CompanyRepository $companyRepository,
    EntityManagerInterface $em)
    {
        $this->companyRepository = $companyRepository;
        $this->em = $em;
    }

    public function completeProfileCompanyService(Users $user, array $data): Company
    {
        $company = $this->companyRepository->findOneBy(['user' => $user]);

        if(!$company){
            $company = new Company();
            $company->setUser($user);
            $company->setCreatedAt(new \DateTime());
        }

        $company->setName($data['name']);
        $company->setPhoneNumber($data['phone_number'] ?? $company->getPhoneNumber());
        $company->setWebsite($data['website'] ?? null);
        $company->setLinkedin($data['linkedin'] ?? null);
        $company->setLogo($data['logo'] ?? null);
        if (isset($data['industry'])) {
            $company->setIndustry($data['industry']);
        }
        $company->setCity($data['city'] ?? null);
        $company->setAddress($data['address'] ?? null);
        $company->setPostalCode($data['postal_code'] ?? null);
        $company->setDescription($data['description'] ?? $company->getDescription());
        $company->setUpdatedAt(new \DateTime());

        $this->em->persist($company);
        $this->em->flush();

        return $company;
    }

    public function getCompanyByUser(Users $user): ?Company
    {
        return $this->companyRepository->findOneBy(['user' => $user]);
    }

    public function getCompanyByName(string $name): ?Company
    {
        return $this->companyRepository->findOneBy(['name' => $name]);
    }

}