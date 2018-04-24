<?php

namespace Vidia\AdminBundle\Controller;

use AppBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Vidia\AuthBundle\Repository\SessionRepository;

class BaseController extends Controller
{
    public function getUsersQuantity()
    {
        /**
         * @var UserRepository $userRepository
         */
        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

        return $userRepository->getUsersQuantity();
    }

    public function getUsers(Request $request)
    {
        if ($request->query->get('online')) {
            $users = $this->getUsersOnline();
        } else {
            $users = $this->findBy('AppBundle:User', []);
        }

        return $users;
    }

    public function findBy($entity, $criteria, $orderBy = [])
    {
        $repository = $this->getDoctrine()->getRepository($entity);

        $result = $repository->findBy($criteria, $orderBy);

        return $result;
    }

    public function findOneBy($entity, $criteria)
    {
        $repository = $this->getDoctrine()->getRepository($entity);

        $result = $repository->findOneBy($criteria);

        return $result;
    }

    public function getUsersOnlineCount()
    {
        /**
         * @var SessionRepository $sessionRepository
         */
        $sessionRepository = $this->getDoctrine()->getRepository('VidiaAuthBundle:Session');

        return $sessionRepository->getUsersOnlineCount();
    }

    public function getUsersOnline()
    {
        /**
         * @var UserRepository $userRepository
         */
        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

        return $userRepository->getUsersOnline();
    }

    public function getUserRegistryStat()
    {
        /**
         * @var UserRepository $userRepository
         */
        $userRepository = $this->getDoctrine()->getRepository('AppBundle:User');

        return $userRepository->getUserRegistryStat();
    }

    public function getMemoryUsage()
    {
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        $memory_usage = $mem[2] / $mem[1] * 100;

        return intval($memory_usage);
    }

    public function getSpaceUsage()
    {
        $disktotal = disk_total_space('/');
        $diskfree = disk_free_space('/');
        $diskuse = round(100 - (($diskfree / $disktotal) * 100));

        return $diskuse;
    }

    public function getServerUptime()
    {
        $str = @file_get_contents('/proc/uptime');
        $num = floatval($str);
        $secs = intval(fmod($num, 60));
        $num = intdiv($num, 60);
        $mins = $num % 60;
        $num = intdiv($num, 60);
        $hours = $num % 24;
        $num = intdiv($num, 24);
        $days = $num;

        if ($days > 0) {
            $uptime = $days . ' days, ' . $hours.':'.$mins.':'.$secs;
        }
        else {
            $uptime =  $hours.':'.$mins.':'.$secs;
        }

        return $uptime;
    }
}
