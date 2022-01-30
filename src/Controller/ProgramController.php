<?php

namespace App\Controller;

use App\Entity\Interval;
use App\Entity\Program;
use App\Utility;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProgramController extends AbstractController
{
    public function getAction(Request $request): Response
    {
        $programs = $this
            ->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->json($programs);
    }

    public function postAction(Request $request): Response
    {
        if (!$this->isAdmin($request)) {
            return new Response("Unauthorized", 401);
        }

        $request_parameters = json_decode($request->getContent(), true);

        $start_time_from_request = $request_parameters['interval']['start_time'];
        $end_time_from_request = $request_parameters['interval']['end_time'];
        $sport_from_request = $request_parameters['sport'];
        $max_participants_from_request = $request_parameters['max_participants'];
        $room_from_request = $request_parameters['room'];

        $start_time_extracted = date("H:i", strtotime($start_time_from_request));
        $end_time_extracted = date("H:i", strtotime($end_time_from_request));


        if (!strtotime($start_time_from_request) || !strtotime($end_time_from_request)) {
            return new Response("Invalid datetime for interval", 500);
        }

        if (!(Utility::checkClosingTime($end_time_extracted) && Utility::checkOpeningTime($start_time_extracted))) {
            return new Response("Invalid time for closing/opening time", 500);
        }

        if (!Utility::validateTimeDifference($start_time_extracted, $end_time_extracted)) {
            return new Response("End time is before start time", 500);
        }

        if (!Utility::checkSportKey($sport_from_request)) {
            return new Response("Invalid sport", 500);
        }

        if (!Utility::checkRoomKey($room_from_request)) {
            return new Response("Invalid room", 500);
        }

        $formatted_start_time = date("m-d-Y H:i", strtotime($start_time_from_request));
        $formatted_end_time = date("m-d-Y H:i", strtotime($end_time_from_request));

        $start_date = DateTime::createFromFormat('m-d-Y H:i', $formatted_start_time);
        $end_date = DateTime::createFromFormat('m-d-Y H:i', $formatted_end_time);

        $interval = $this
            ->getDoctrine()
            ->getRepository(Interval::class)
            ->findOneBy([
                'start_datetime' => $start_date,
                'stop_datetime' => $end_date
            ]);

        if (!$interval) {
            $interval = new Interval();
            $interval->setStartDatetime($start_date)
                ->setStopDatetime($end_date);

            $this->getDoctrine()->getManager()->persist($interval);
            $this->getDoctrine()->getManager()->flush();
        }

        $no_overlap = $this->verifyRoomVacancy($room_from_request, $start_date);

        if (!$no_overlap) {
            return new Response("Room is already occupied in that time interval", 500);
        }

        $program = $this
            ->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy([
                'max_participants' => $max_participants_from_request,
                'room' => $room_from_request,
                'sport' => $sport_from_request,
                'time_interval' => $interval->getId()
            ]);

        if (!$program) {
            $program = new Program();
            $program
                ->setMaxParticipants($max_participants_from_request)
                ->setTimeInterval($interval)
                ->setRoom($room_from_request)
                ->setSport($sport_from_request);

            $this->getDoctrine()->getManager()->persist($program);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->json($program);
    }

    public function deleteAction(Request $request): Response
    {
        if (!$this->isAdmin($request)) {
            return new Response("Unauthorized", 401);
        }

        $request_parameters = json_decode($request->getContent(), true);
        $program_id = $request_parameters['id'];

        $program = $this
            ->getDoctrine()
            ->getRepository(Program::class)
            ->findOneBy([
                "id" => $program_id
            ]);

        if (!$program) {
            return new Response("Program with id: {$program_id} not found", 404);
        }

        $this->getDoctrine()->getManager()->remove($program);
        $this->getDoctrine()->getManager()->flush();

        return new Response("Delete Successful");
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function isAdmin(Request $request): bool
    {
        if (Utility::checkAdminKey($request->headers->get("admin-key", false))) {
            return true;
        }
        return false;
    }

    /**
     * @param $room_from_request
     * @param $start_date
     * @return bool
     */
    public function verifyRoomVacancy($room_from_request, $start_date): bool
    {
        $no_overlap = true;
        $program_overlap = $this
            ->getDoctrine()
            ->getRepository(Program::class)
            ->findBy([
                'room' => $room_from_request,
            ]);

        foreach ($program_overlap as $company) {
            if ($start_date <= $company->getTimeInterval()->getStopDatetime()) {
                $no_overlap = false;
                break;
            }
        }
        return $no_overlap;
    }
}
