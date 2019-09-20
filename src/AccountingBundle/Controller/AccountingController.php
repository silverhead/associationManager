<?php

namespace AccountingBundle\Controller;

use AppBundle\QueryHelper\FilterQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\QueryHelper\OrderQuery;
use Symfony\Component\HttpFoundation\Response;
use AccountingBundle\Entity\Entry;

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
    
    /**
     * @Route("/accounting/account/{accountId}/entry/add", name="accounting_account_entry_add")
     * @Route("/accounting/account/{accountId}/entry/edit/{id}", name="accounting_account_entry_edit", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function entryeditAction(Request $request, $accountId, $id = null) {
        $accountingManager = $this->get('accounting.manager.accounting');
        $formHandler = $this->get('accounting.form.entry');
        
        if ($id != null) {
            $entity = $accountingManager->getEntryById($id);
        } else {
            $entity = new Entry();
            if ($accountId != null) {
                $accountableAccount = $accountingManager->getEntriesForAccount($accountId);
                $entity->setAccountableAccount($accountableAccount);
                //$entity->setAccountableAccountId($accountId);
            }
        }

        $formHandler->setForm($entity);
        
        if ($formHandler->process($request)) {
            $translator = $this->get('translator');
            $entity = $formHandler->getData($accountableAccount);
            
            //var_dump($entity);exit;
            
            if ($accountingManager->save($entity)) {
                $this->addFlash('success', $translator->trans('accounting.account.edit.saveSuccessText'));

                if ($request->get('save_and_leave', null) !== null) {
                    //if ($callBackUrl !== null) {
                    //    return $this->redirect($callBackUrl);
                    //}

                    //return $this->redirect(
                    //    $this->generateUrl('user_manager').'#users'
                    //);
                }

                if ($request->get('save_and_stay', null) !== null) {
                    return $this->redirectToRoute('user_edit', [
                        'id' => $entity->getId()
                    ]);
                }
            }

            $this->addFlash(
                'error',
                $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $accountingManager->getErrors())
            ]));
        }
        
        
        //var_dump($entriesOfAccount);
        return $this->render('@Accounting/edit_account.html.twig', array(
            'formEntry' =>  $formHandler->getForm()->createView(),
        ));
    }
}
