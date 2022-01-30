<?php

namespace App\Controller;

use App\Entity\Bookings;
use App\Entity\Program;
use App\Utility;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingController extends AbstractController
{
    public function getAction(Request $request): Response
    {
        $intervals = $this
            ->getDoctrine()
            ->getRepository(Bookings::class)
            ->findAll();

        return $this->json($intervals);
    }

    public function postAction(Request $request): Response
    {
        $request_parameters = json_decode($request->getContent(), true);

        if (!Utility::validateCNP($request_parameters['CNP'])) {
            return new Response("Invalid CNP", 401);
        }

        $request_parameters = json_decode($request->getContent(), true);
        $program_id = $request_parameters['program_id'];
        $user_cnp = $request_parameters['user_cnp'];

        $program = $this->getDoctrine()->getRepository(Program::class)->findOneBy([
            "id" => $program_id
        ]);

        $existingBookings = $this->getDoctrine()->getRepository(Bookings::class)->findBy(
            ['program_id' => $program_id]
        );

        $existing_class_bookings_count = $this->getDoctrine()
            ->getRepository(Bookings::class)
            ->getBookingsCountByProgram($program_id);

        if ($existing_class_bookings_count >= $program->getMaxParticipants()) {
            return new Response("Class is already full", 500);
        }

        $existingBookings = $this->getDoctrine()->getRepository(Bookings::class)->findBy(
            ['user_cnp' => $user_cnp]
        );

        foreach ($existingBookings as $booking) {
            $bookingProgram = $booking->getProgram();
            $start_time_new_booking = DateTime::createFromFormat('H:i', $program->getTimeInterval()->getStartDate())->format("d-M-Y H:i:s");
            $start_time_existing_booking = DateTime::createFromFormat('H:i', $booking->getProgram()->getTimeInterval()->getStartDate())->format("d-M-Y H:i:s");
            if ($start_time_existing_booking) {

            }

        }

        return $this->json();
    }
}