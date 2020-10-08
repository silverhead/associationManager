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
use AccountingBundle\Entity\Exercise;

/**
 * Description of ExerciseController
 *
 * @author dominique
 */
class ExerciseController extends Controller {
    const ITEMS_PER_PAGE = 4;
    const PAGE_PARAMETER_NAME = 'pageTab3';
    
    /**
     * @Route("/exercise/", name="exercise_index", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {   
        $exerciseManager = $this->get('accounting.manager.exercise');

        $exercises = $exerciseManager->getExerciseList();

        return $this->render('@Accounting/index_exercise.html.twig', array(
            'data' => $exercises
        ));
    }
    
    /**
     * @Route("/exercise/exercise/add", name="exercices_exercise_add", options = { "expose" = true })
     * @Route("/exercise/exercise/{id}", name="exercices_exercise_id", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exercise(Request $request, $id = null) {
        $formHandler = null;
        $callBackUrl = $this->generateUrl('exercise_index', []);
        $translator = $this->get('translator');
        $exerciseManager = $this->get('accounting.manager.exercise');
        
        $entity = new Exercise();
        if ($id > 0){
            $entity = $exerciseManager->find($id);
        }

        $formHandler = $this->get('accounting.form.exercise');
        $formHandler->setForm($entity);

        if ($formHandler->process($request)) {
            $entity = $formHandler->getData();
            if ($exerciseManager->saveExercise($entity)) {
                $this->addFlash('success', $translator->trans('accounting.exercise.edit.saveSuccessText'));

                if ($request->get('save_and_leave', null) !== null) {
                    if ($callBackUrl !== null) {
                        return $this->redirect($callBackUrl);
                    }

                    return $this->redirect(
                        $this->get('router')->generate('exercise_index', array('exerciseId' => $entity->getId()))
                    );
                }

                if ($request->get('save_and_stay', null) !== null) {
                    return $this->redirectToRoute('exercices_exercise_id', [
                        'id' => $id
                    ]);
                }
            }

            $this->addFlash(
                'error',
                $translator->trans('app.common.errorComming', [
                    '%error%' => '<br />' . implode('<br />', $exerciseManager->getErrors())
            ]));
        }
        
        $breadcrumbs = [
            [
                'href' => $this->redirectToRoute('accounting_index'),
                'title' => $translator->trans('accounting.synthesis.callback'),
                'label' => $translator->trans('accounting.synthesis.title')
            ]
        ];
        
        if ($callBackUrl != null || $callBackUrl != "") {
            $breadcrumbs[] = [
                'href' => $callBackUrl,
                'title' => $translator->trans('accounting.exercise.title'),
                'label' => $translator->trans('accounting.exercise.title')
            ];
        }
        
        $breadcrumbs[] = [
            'label' => $translator->trans('accounting.exercise.edit.title')
        ];
        
        return $this->render('@Accounting/exercise.html.twig', array(
            'formExercise' =>  $formHandler->getForm()->createView(),
            'callBackUrl' => $callBackUrl,
            'breadcrumbs' => $breadcrumbs,
        ));
    }
}
