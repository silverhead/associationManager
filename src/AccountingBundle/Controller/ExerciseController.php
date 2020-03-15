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

/**
 * Description of ExerciseController
 *
 * @author dominique
 */
class ExerciseController extends Controller {
    const ITEMS_PER_PAGE = 4;
    const PAGE_PARAMETER_NAME = 'pageTab3';

    private $_em;
    private $_exerciseRepo;
    
    /**
     * @Route("/accounting/exercises/", name="accounting_exercises_index", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {   
        $exercises = array();
       
        $exercises = $accountingManager->getExercises();

        return $this->render('@Accounting/exercises.html.twig', array(
            'data' => $exercises
        ));
    }
    
    /**
     * @Route("/accounting/exercise/{id}", name="accounting_exercise_id", options = { "expose" = true })
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exercise(Request $request, $id = null) {
        $formHandler = null;
        $callBackUrl = $this->generateUrl('accounting_exercise_id', ['id' => $id]);
        $translator = $this->get('translator');
        
        $entity = new Exercise();
        $formHandler = $this->get('accounting.form.exercise');
        $formHandler->setForm($entity, $id);

        if ($formHandler->process($request)) {
            $entity = $formHandler->getData($exercise);
            if ($accountingManager->saveExercise($entity)) {
                $this->addFlash('success', $translator->trans('accounting.exercise.edit.saveSuccessText'));

                if ($request->get('save_and_leave', null) !== null) {
                    if ($callBackUrl !== null) {
                        return $this->redirect($callBackUrl);
                    }

                    return $this->redirect(
                        $this->get('router')->generate('accounting_index', array('exerciseId' => $entity->getId()))
                    );
                }

                if ($request->get('save_and_stay', null) !== null) {
                    return $this->redirectToRoute('accounting_exercise_id', [
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
        
        return $this->render('@Accounting/exercise.html.twig', array(
            'callBackUrl' => $callBackUrl
        ));
    }
}
