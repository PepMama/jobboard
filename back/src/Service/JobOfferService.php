<?php

namespace App\Service;

use App\Entity\JobOffer;
use App\Entity\Users;
use App\Repository\CompanyRepository;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;

class JobOfferService
{
    private JobOfferRepository $jobOfferRepository;
    private CompanyRepository $companyRepository;
    private EntityManagerInterface $em;

    public function __construct(
        JobOfferRepository $jobOfferRepository,
        CompanyRepository $companyRepository,
        EntityManagerInterface $em
    ) {
        $this->jobOfferRepository = $jobOfferRepository;
        $this->companyRepository = $companyRepository;
        $this->em = $em;
    }

    public function modelJson(JobOffer $offer): array
    {
        return [
            'id' => $offer->getId(),
            'title' => $offer->getTitle(),
            'description' => $offer->getDescription(),
            'state' => $offer->getState(),
            'contractType' => $offer->getContractType(),
            'salary' => $offer->getSalary(),
            'city' => $offer->getCity(),
            'remote' => $offer->getRemote(),
            'startDate' => $offer->getStartDate()?->format('Y-m-d'),
            'createdAt' => $offer->getCreatedAt()?->format('Y-m-d H:i:s'),
        ];
    }

    public function manageOffer(Users $user, array $data): JobOffer|array
    {
        $company = $this->companyRepository->findOneBy(['user' => $user]);
        if (!$company) {
            return ['error' => 'Entreprise introuvable'];
        }

        if (!empty($data['id'])) {
            $offer = $this->jobOfferRepository->find($data['id']);
            if (!$offer) {
                return ['error' => 'Offre non trouvée'];
            }
            if ($offer->getCompany() !== $company) {
                return ['error' => 'Accès interdit à cette offre'];
            }
        } else {
            $offer = new JobOffer();
            $offer->setCompany($company);
        }

        $offer->setTitle($data['title'] ?? '');
        $offer->setDescription($data['description'] ?? '');
        $offer->setState($data['state'] ?? '');
        $offer->setContractType($data['contractType'] ?? '');
        $offer->setSalary(isset($data['salary']) ? (int)$data['salary'] : null);
        $offer->setCity($data['city'] ?? null);
        $offer->setRemote($data['remote'] ?? false);
        $offer->setStartDate(new \DateTime($data['startDate']));

        $this->em->persist($offer);
        $this->em->flush();

        return $offer;
    }

    public function getAllOffers(Users $user): array
    {
        $company = $this->companyRepository->findOneBy(['user' => $user]);
        if (!$company) {
            return [];
        }

        $offers = $this->jobOfferRepository->findBy(['company' => $company]);

        return array_map([$this, 'modelJson'], $offers);
    }

    public function getOffer(Users $user, int $id): array
    {
        $company = $this->companyRepository->findOneBy(['user' => $user]);
        if (!$company) {
            return ['error' => 'Entreprise introuvable'];
        }

        $offer = $this->jobOfferRepository->find($id);
        if (!$offer) {
            return ['error' => 'Offre introuvable'];
        }
        if ($offer->getCompany() !== $company) {
            return ['error' => 'Accès interdit à cette offre'];
        }

        return $this->modelJson($offer);
    }

    public function deleteOffer(Users $user, int $id): array
    {
        $company = $this->companyRepository->findOneBy(['user' => $user]);
        if (!$company) {
            return ['error' => 'Entreprise introuvable'];
        }

        $offer = $this->jobOfferRepository->find($id);
        if (!$offer || $offer->getCompany() !== $company) {
            return ['error' => 'Offre introuvable ou accès interdit'];
        }

        $this->em->remove($offer);
        $this->em->flush();

        return ['message' => 'Offre supprimée avec succès'];
    }

    public function getOffersForStudents(?string $keyword = null, ?string $city = null, ?int $studentId = null): array
    {
        $qb = $this->em
            ->getRepository(JobOffer::class)
            ->createQueryBuilder('o')
            ->join('o.company', 'c')
            ->addSelect('c')
            ->where('o.state = :state')
            ->setParameter('state', 'visible');

        if ($keyword) {
            $qb->andWhere('LOWER(o.title) LIKE :keyword OR LOWER(o.description) LIKE :keyword')
                ->setParameter('keyword', '%' . strtolower($keyword) . '%');
        }

        if ($city) {
            $qb->andWhere('LOWER(o.city) LIKE :city')
                ->setParameter('city', '%' . strtolower($city) . '%');
        }

        // Exclure les offres likées par l'étudiant
        if ($studentId !== null) {
            $subQb = $this->em->createQueryBuilder()
                ->select('IDENTITY(lo.jobOffer)')
                ->from('App\Entity\LikesOffer', 'lo')
                ->where('lo.student = :studentId');

            $qb->andWhere($qb->expr()->notIn('o.id', $subQb->getDQL()))
                ->setParameter('studentId', $studentId);
        }

        $offers = $qb->getQuery()->getResult();

        return array_map(function (JobOffer $offer) {
            $company = $offer->getCompany();
            return [
                'id' => $offer->getId(),
                'title' => $offer->getTitle(),
                'contractType' => $offer->getContractType(),
                'city' => $offer->getCity(),
                'salary' => $offer->getSalary(),
                'startDate' => $offer->getStartDate()?->format('d-m-Y'),
                'description' => $offer->getDescription(),
                'company' => [
                    'name' => $company->getName(),
                    'logo' => $company->getLogo(),
                ]
            ];
        }, $offers);
    }

}
