<?php
// /src/Controller/GetUserController.php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use App\Repository\UserRepository;

class GetUserController extends AbstractController
{
    private $repo;
    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function __invoke(): array
    {
        $data = $this->repo->findMembers();
        return $data;
    }
}