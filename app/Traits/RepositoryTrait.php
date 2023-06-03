<?php

namespace App\Traits;

use App\Repositories\ObjectBookingRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\SlotRepository;

trait RepositoryTrait
{
    private $slotRepository;
    private $bookingRepository;
    private $objectRepository;

    public function bookingRepository(ObjectBookingRepository $repo){
        return $this->bookingRepository = $repo;
    }

    public function slotRepository(SlotRepository $repo){
        $this->slotRepository = $repo;
        return $this->slotRepository;
    }

    public function objectRepository(ObjectRepository $repo){
        return $this->objectRepository = $repo;
    }

    public function getRepository($name){
        switch ($name){
            case "ObjectRepository":
                $repo = $this->objectRepository(new ObjectRepository());
                break;
            case "SlotRepository":
                $repo = $this->slotRepository(new SlotRepository());
                break;
            case "ObjectBookingRepository":
                $repo = $this->bookingRepository(new ObjectBookingRepository());
                break;
            default:
                return "Something went wrong ... ";
        }
        return $repo;
    }
}