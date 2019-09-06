<?php

namespace AccountingBundle\Controller;

use AppBundle\QueryHelper\FilterQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\QueryHelper\OrderQuery;
use Symfony\Component\HttpFoundation\Response;
use AccountingBundle\Form\Model\AccountingListFilterModel;

class AccountingController extends Controller
{
    const ITEMS_PER_PAGE = 4;
    const PAGE_PARAMETER_NAME = 'pageTab3';

    private $_em;
    private $_entryRepo;
    
    /**
     * @Route("/accounting/", name="accounting_index", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, $anchor = null)
    {   
        $data = array();
        $entryManager = $this->get('accounting.manager.entry');
        //error_log($entryManager);
        $data = $entryManager->getEntriesByAccountForDashboard();

        return $this->render('@Accounting/index.html.twig', array(
            'data' => $data
        ));
        //return $this->render('/accounting/index.html.twig', array());
    }
}
