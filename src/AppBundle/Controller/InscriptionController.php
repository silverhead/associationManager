<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InscriptionController extends Controller
{
    /**
     * @Route("inscription/{step}", name="inscription", defaults= {"step"= "identity"})
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function inscripitonAction(Request $request, $step)
    {
        $response = null;

        switch ($step){
            case 'coordinates':
                $response = $this->coordinates();
                break;
            case 'connection':
                $response = $this->connection();
                break;
            case 'subscription':
                $response = $this->subscription();
                break;
            case 'paymentchoice':
                $response = $this->paymentchoice();
                break;
            default:
                $response = $this->identity();
                break;
        }

        return $response;
    }

    public function identity()
    {
        return $this->render("inscription/identity.html.twig");
    }

    public function coordinates()
    {
        return $this->render("inscription/coordinates.html.twig");
    }

    public function connection()
    {
        return $this->render("inscription/connection.html.twig");
    }

    public function subscription()
    {
        return $this->render("inscription/subscription.html.twig");
    }

    public function paymentchoice()
    {
        return $this->render("inscription/paymentChoice.html.twig");
    }
}
