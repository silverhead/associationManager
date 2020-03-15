<?php

namespace AccountingBundle\Controller;

use AppBundle\QueryHelper\FilterQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\QueryHelper\OrderQuery;
use Symfony\Component\HttpFoundation\Response;
use AccountingBundle\Entity\Entry;
use AccountingBundle\Entity\Solde;

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
        $sumOfBalance = 0;
        foreach ($entriesOfAccounts as $account) {
            $sumOfBalance += $account->getCalculatedLastSolde()->getAmount();
        }
        return $this->render('@Accounting/index.html.twig', array(
            'data' => $entriesOfAccounts,
            'sumOfBalance' => number_format($sumOfBalance/100, 2, ',', ' ')
        ));
    }
    
    /**
     * @Route("/accounting/account/{id}", name="accounting_account_id", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function account(Request $request, $id = null) {
        $accountingManager = $this->get('accounting.manager.accounting');
        $entriesOfAccount = $accountingManager->getEntriesForAccount($id, null, null);
        $formHandler = null;
        $callBackUrl = $this->generateUrl('accounting_account_id', ['id' => $id]);
        $translator = $this->get('translator');
        
        if ($entriesOfAccount->getSoldes() == null || count($entriesOfAccount->getSoldes()) == 0) {
            $entity = new Solde();
            $formHandler = $this->get('accounting.form.solde');
            $formHandler->setForm($entity, $id);
        
            if ($formHandler->process($request)) {
                $entity = $formHandler->getData($entriesOfAccount);
                if ($accountingManager->saveSolde($entity)) {
                    $this->addFlash('success', $translator->trans('accounting.account.solde.edit.saveSuccessText'));

                    if ($request->get('save_and_leave', null) !== null) {
                        if ($callBackUrl !== null) {
                            return $this->redirect($callBackUrl);
                        }

                        return $this->redirect(
                            $this->get('router')->generate('accounting_index', array('accountId' => $entity->getAccountableAccount()->getId()))
                        );
                    }

                    if ($request->get('save_and_stay', null) !== null) {
                        return $this->redirectToRoute('accounting_account_id', [
                            'id' => $id
                        ]);
                    }
                }

                $this->addFlash(
                    'error',
                    $translator->trans('app.common.errorComming', [
                        '%error%' => '<br />' . implode('<br />', $accountingManager->getErrors())
                ]));
            }
        }
        $dateDebut = $request->get('dateDebut');
        $dateDebut = $request->get('dateFin');
        $exerciseList = $accountingManager->getExerciseList(null, null);

        return $this->render('@Accounting/account.html.twig', array(
            'accounting' => $entriesOfAccount,
            'formSolde' =>  $formHandler != null ? $formHandler->getForm()->createView() : null,
	    'exerciseList' => $exerciseList,
            'callBackUrl' => $callBackUrl
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
        }
        if ($accountId != null) {
            $accountableAccount = $accountingManager->getEntriesForAccount($accountId);
            $entity->setAccountableAccount($accountableAccount);
        }
        
        $pageH = $this->get('app.handler.page_historical');
        $callBackUrl = $this->generateUrl('accounting_account_id', ['id' => $accountId], );
        $translator = $this->get('translator');
        
        $formHandler->setForm($entity);
        
        if ($formHandler->process($request)) {
            $entity = $formHandler->getData($accountableAccount);
            
            //var_dump($entity);exit;
            
            if ($accountingManager->saveEntry($entity)) {
                $this->addFlash('success', $translator->trans('accounting.account.edit.saveSuccessText'));

                if ($request->get('save_and_leave', null) !== null) {
                    if ($callBackUrl !== null) {
                        return $this->redirect($callBackUrl);
                    }

                    return $this->redirect(
                        $this->get('router')->generate('accounting_index', array('accountId' => $entity->getAccountableAccount()->getId()))
                    );
                }

                if ($request->get('save_and_stay', null) !== null) {
                    return $this->redirectToRoute('accounting_account_entry_edit', [
                        'id' => $entity->getId(),
                        'accountId' => $entity->getAccountableAccount()->getId()
                    ]);
                }
            }

            $this->addFlash(
                'error',
                $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $accountingManager->getErrors())
            ]));
        }
        $breadcrumbs = [
            [
                'href' => $this->redirectToRoute('accounting_index'),
                'title' => $translator->trans('accounting.synthesis.callback'),
                'label' => $translator->trans('accounting.synthesis.title')
            ]
        ];
        
        if ($callBackUrl != null) {
            $breadcrumbs[] = [
                'href' => $callBackUrl,
                'title' => $translator->trans('accounting.account.title'),
                'label' => $translator->trans('accounting.account.title')
            ];
        }
        
        $breadcrumbs[] = [
            'label' => $translator->trans('accounting.account.edit.title')
        ];

        return $this->render('@Accounting/edit_account.html.twig', array(
            'formEntry' =>  $formHandler->getForm()->createView(),
            'breadcrumbs' => $breadcrumbs,
            'callBackUrl' => $callBackUrl
        ));
    }

    /**
     * @Route("/accounting/account/{accountId}/soldes", name="accounting_account_soldes")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function soldelistAction(Request $request, $accountId, $id = null) {
        $accountingManager = $this->get('accounting.manager.accounting');
        $formHandler = $this->get('accounting.form.solde');
        
        if ($id != null) {
            $entity = $accountingManager->getSoldeById($id);
        } else {
            $entity = new Solde();
        }
        if ($accountId != null) {
            $accountableAccount = $accountingManager->getSoldesForAccount($accountId, null, null);
            $entity->setAccountableAccount($accountableAccount);
        }
        
        $pageH = $this->get('app.handler.page_historical');
        $callBackUrl = $this->generateUrl('accounting_account_id', ['id' => $accountId], );
        $translator = $this->get('translator');
        
        $formHandler->setForm($entity, $accountId);
        
        if ($formHandler->process($request)) {
            $entity = $formHandler->getData($accountableAccount);
            
            //var_dump($entity);exit;
            
            if ($accountingManager->saveSolde($entity)) {
                $this->addFlash('success', $translator->trans('accounting.account.edit.saveSuccessText'));

                if ($request->get('save_and_leave', null) !== null) {
                    if ($callBackUrl !== null) {
                        return $this->redirect($callBackUrl);
                    }

                    return $this->redirect(
                        $this->get('router')->generate('accounting_index', array('accountId' => $entity->getAccountableAccount()->getId()))
                    );
                }

                if ($request->get('save_and_stay', null) !== null) {
                    return $this->redirectToRoute('accounting_account_solde_edit', [
                        'id' => $entity->getId(),
                        'accountId' => $entity->getAccountableAccount()->getId()
                    ]);
                }
            }

            $this->addFlash(
                'error',
                $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $accountingManager->getErrors())
            ]));
        }
        $breadcrumbs = [
            [
                'href' => $this->redirectToRoute('accounting_index'),
                'title' => $translator->trans('accounting.synthesis.callback'),
                'label' => $translator->trans('accounting.synthesis.title')
            ]
        ];
        
        if ($callBackUrl != null) {
            $breadcrumbs[] = [
                'href' => $callBackUrl,
                'title' => $translator->trans('accounting.account.title'),
                'label' => $translator->trans('accounting.account.title')
            ];
        }
        
        $breadcrumbs[] = [
            'label' => $translator->trans('accounting.account.solde.edit.title')
        ];

        return $this->render('@Accounting/edit_solde.html.twig', array(
            'formSolde' =>  $formHandler->getForm()->createView(),
            'accountableAccount' => $accountableAccount,
            'breadcrumbs' => $breadcrumbs,
            'callBackUrl' => $callBackUrl
        ));
    }

    /**
     * @Route("/accounting/account/{accountId}/solde/add", name="accounting_account_solde_add")
     * @Route("/accounting/account/{accountId}/solde/edit/{id}", name="accounting_account_solde_edit", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function soldeeditAction(Request $request, $accountId, $id = null) {
        $accountingManager = $this->get('accounting.manager.accounting');
        $formHandler = $this->get('accounting.form.solde');
        
        if ($id != null) {
            $entity = $accountingManager->getSoldeById($id);
        } else {
            $entity = new Solde();
        }
        if ($accountId != null) {
            $accountableAccount = $accountingManager->getSoldesForAccount($accountId);
            $entity->setAccountableAccount($accountableAccount);
        }
        
        $pageH = $this->get('app.handler.page_historical');
        $callBackUrl = $this->generateUrl('accounting_account_id', ['id' => $accountId], );
        $translator = $this->get('translator');
        
        $formHandler->setForm($entity, $accountId);
        
        if ($formHandler->process($request)) {
            $entity = $formHandler->getData($accountableAccount);
            
            //var_dump($entity);exit;
            
            if ($accountingManager->saveEntry($entity)) {
                $this->addFlash('success', $translator->trans('accounting.account.edit.saveSuccessText'));

                if ($request->get('save_and_leave', null) !== null) {
                    if ($callBackUrl !== null) {
                        return $this->redirect($callBackUrl);
                    }

                    return $this->redirect(
                        $this->get('router')->generate('accounting_index', array('accountId' => $entity->getAccountableAccount()->getId()))
                    );
                }

                if ($request->get('save_and_stay', null) !== null) {
                    return $this->redirectToRoute('accounting_account_solde_edit', [
                        'id' => $entity->getId(),
                        'accountId' => $entity->getAccountableAccount()->getId()
                    ]);
                }
            }

            $this->addFlash(
                'error',
                $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $accountingManager->getErrors())
            ]));
        }
        $breadcrumbs = [
            [
                'href' => $this->redirectToRoute('accounting_index'),
                'title' => $translator->trans('accounting.synthesis.callback'),
                'label' => $translator->trans('accounting.synthesis.title')
            ]
        ];
        
        if ($callBackUrl != null) {
            $breadcrumbs[] = [
                'href' => $callBackUrl,
                'title' => $translator->trans('accounting.account.title'),
                'label' => $translator->trans('accounting.account.title')
            ];
        }
        
        $breadcrumbs[] = [
            'label' => $translator->trans('accounting.account.edit.title')
        ];

        return $this->render('@Accounting/edit_solde.html.twig', array(
            'formSolde' =>  $formHandler->getForm()->createView(),
            'accountableAccount' => $accountableAccount, //$accountingManager->getSoldesForAccount($accountId),
            'breadcrumbs' => $breadcrumbs,
            'callBackUrl' => $callBackUrl
        ));
    }
 }
