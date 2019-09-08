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
    public function indexAction(Request $request)
    {   
        $entries = array();
        $soldes = array();
        $accountingManager = $this->get('accounting.manager.accounting');
        
        $entriesOfAccounts = $accountingManager->getEntriesByAccountForSynthesis();

        return $this->render('@Accounting/index.html.twig', array(
            'data' => $entriesOfAccounts
        ));
    }
    
    /**
     * @Route("/accounting/account/{id}", name="accounting_account_id", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function account(Request $request, $id = null) {
        $accountingManager = $this->get('accounting.manager.accounting');
        $entriesOfAccount = $accountingManager->getEntriesForAccount($id);
        //var_dump($entriesOfAccount);
        return $this->render('@Accounting/account.html.twig', array(
            'accounting' => $entriesOfAccount
        ));
    }
}
