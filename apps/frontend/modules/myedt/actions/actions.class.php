<?php

/**
 * myedt actions.
 *
 * @package    edt
 * @subpackage myedt
 * @author     Théophile Helleboid, Michael Muré
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class myedtActions extends sfActions
{

  public function executeImage(sfWebRequest $request)
  {
//     $this->getRequest()->setParameter('filiere', "info");
//     $this->getRequest()->setParameter('promo', '1a');
//     $this->getRequest()->setParameter('semaine', 30);
    $this->forward("edt", "image");
  }

  public function executeImport(sfWebRequest $request)
  {

  }

  public function executeCreateFromImport(sfWebRequest $request)
  {
    // projectId=19
    // idPianoWeek=31
    // idPianoDay=0,1,2,3,4
    // idTree=147,148,149,113,116,117,118
    $url = $request->getParameter('url');
    $patterns = array(
      'projectId'   => "@projectId=([\d]+)@",
      'idPianoWeek' => "@idPianoWeek=([\d]+)@",
      'idPianoDay'  => "@idPianoDay=([\d,]+)@",
      'idTree'      => "@idTree=([\d,]+)@",
    );
    
    foreach($patterns as $id => $pattern)
    {
      preg_match($pattern, $url, $matches[$id]);
    }
    $projectId = $matches['projectId'][1];
    $idPianoWeek = $matches['idPianoWeek'][1];
    $idPianoDay = $matches['idPianoDay'][1];
    $idTree = $matches['idTree'][1];

    $filiere = Doctrine_Core::getTable('Filiere')
      ->createQuery('f')
      ->where('f.url = "perso"')
      ->execute()->getFirst();

    $promotion = new Promotion();
    // $promotion->setProjectId($projectId);
    // $promotion->setIdPianoWeek($idPianoWeek);
    $promotion->setIdPianoDay($idPianoDay);
    $promotion->setIdTree($idTree);
    $promotion->setFiliereId($filiere->getId());
    $promotion->setNom($request->getParameter('nom'));
    $promotion->setUrl($request->getParameter('nom'));
    $promotion->save();

    $this->redirect('@image?filiere=perso&promo='.$request->getParameter('nom').'&semaine=');

   // https://www.emploisdutemps.uha.fr/ade/imageEt?&&&width=800&height=600&lunchName=REPAS&displayMode=1057855&showLoad=false&ttl=1283427991552&displayConfId=8

  }

  public function executeIndex(sfWebRequest $request)
  {
    $this->promotions = Doctrine_Core::getTable('Promotion')
      ->createQuery('a')
      ->execute();
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new PromotionForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new PromotionForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($promotion = Doctrine_Core::getTable('Promotion')->find(array($request->getParameter('id'))), sprintf('Object promotion does not exist (%s).', $request->getParameter('id')));
    $this->form = new PromotionForm($promotion);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($promotion = Doctrine_Core::getTable('Promotion')->find(array($request->getParameter('id'))), sprintf('Object promotion does not exist (%s).', $request->getParameter('id')));
    $this->form = new PromotionForm($promotion);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($promotion = Doctrine_Core::getTable('Promotion')->find(array($request->getParameter('id'))), sprintf('Object promotion does not exist (%s).', $request->getParameter('id')));
    $promotion->delete();

    $this->redirect('myedt/index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $promotion = $form->save();

      $this->redirect('myedt/edit?id='.$promotion->getId());
    }
  }
}