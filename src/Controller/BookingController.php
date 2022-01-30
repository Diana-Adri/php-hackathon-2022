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
        $user_cnp = $request_parameters['CNP'];

        $program = $this->getDoctrine()->getRepository(Program::class)->findOneBy([
            "id" => $program_id
        ]);

        if (!$program) {
            return new Response("Program does not exist", 500);
        }

        $existingBookings = $this->getDoctrine()->getRepository(Bookings::class)->findBy(
            ['Program' => $program]
        );

        $user_existing_booking = $this->getDoctrine()->getRepository(Bookings::class)->findBy(
            ['Program' => $program,
                'user_cnp' => $user_cnp]
        );

        if ($user_existing_booking) {
            return new Response("User already booked in class", 500);
        }

        $existing_class_bookings_count = $this->getDoctrine()
            ->getRepository(Bookings::class)
            ->getBookingsCountByProgram($program_id);

        if ($existing_class_bookings_count >= $program->getMaxParticipants()) {
            return new Response("Class is already full", 500);
        }

        $existingBookings = $this->getDoctrine()->getRepository(Bookings::class)->findBy(
            ['user_cnp' => $user_cnp]
        );

        $time_overlap = false;
        foreach ($existingBookings as $booking) {
            $start_time_new_booking = DateTime::createFromFormat('d-M-Y H:i:s', $program->getTimeInterval()->getStartDatetime()->format("d-M-Y H:i:s"))->format("d-M-Y H:i:s");
            $start_time_existing_booking = DateTime::createFromFormat('d-M-Y H:i:s', $booking->getProgram()->getTimeInterval()->getStopDatetime()->format("d-M-Y H:i:s"))->format("d-M-Y H:i:s");

            if ($start_time_new_booking < $start_time_existing_booking) {
                $time_overlap = true;
            }
        }

        if ($time_overlap) {
            return new Response("Already booked a class in time frame", 500);
        }

        $booking = new Bookings();
        $booking
            ->setProgram($program)
            ->setUserCnp($user_cnp);

        $this->getDoctrine()->getManager()->persist($booking);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($booking);
    }
}