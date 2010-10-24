<?php

/**
 * edt actions.
 *
 * @package    edt
 * @subpackage edt
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class edtActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    // On récupère le cookie et on redirige s'il existe
    // default=<filiere>/<promo>
    $default = $request->getCookie('default');
    if(! empty($default))
    {
      $array = explode('/', $default);
      $filiere = $array[0];
      $promo = $array[1];
      $filieres = sfConfig::get('sf_filieres');

      if(isset($filieres[$filiere]['promotions'][$promo]['nom']))
        $this->redirect("@image?filiere=$filiere&promo=$promo&semaine=");
      else
        throw new sfError404Exception('La redirection est incorrecte');
    }
  }

  public function executeIndexPromo(sfWebRequest $request)
  {
    $filieres = sfConfig::get('sf_filieres');
    $this->filiere = $request->getParameter('filiere');
    if(isset($filieres[$this->filiere]['nom']))
      $this->nom_filiere = $filieres[$this->filiere]['nom'];
    else
      throw new sfError404Exception('La filière '.$this->filiere.' n\'existe pas');
  }
  
  public function executeImage(sfWebRequest $request)
  {
    $filieres = sfConfig::get('sf_filieres');
    $this->filiere = $request->getParameter('filiere');
    $this->promo = $request->getParameter('promo');
    
    $this->nom_filiere = $filieres[$this->filiere]['nom'];
    $this->nom_promo = $filieres[$this->filiere]['promotions'][$this->promo]['nom'];

    $semaine = intval($request->getParameter('semaine', AdeTools::getSemaineNumber()));

    $this->semaine = $semaine;
    $this->semaine_suivante = $semaine + 1;
    // Pas de semaine négative !
    $this->semaine_precedente = max(0,$semaine - 1);


    $this->adeImage = new AdeImage(
      array(array('filiere' => $this->filiere, 'promo' => $this->promo )),
      array('idPianoWeek' => $semaine)
    );

    $this->image_path = $this->adeImage->getWebPath();

    // Timestamp du lundi, début de semaine
    $this->timestamp = AdeTools::getTimestamp($this->semaine);
    // Notice
    $this->notice = $this->adeImage->getNotice();
  }

  public function executeError404(sfWebRequest $request)
  {

  }

  public function executeFaq(sfWebRequest $request)
  {
  }

  /**
   *  Set and reset default cookie 
   */
  public function executeSet(sfWebRequest $request)
  {
    $filiere = $request->getParameter('filiere');
    $promo = $request->getParameter('promo');
    $semaine = intval($request->getParameter('semaine', AdeTools::getSemaineNumber()));

    $this->getResponse()->setCookie('default', $filiere.'/'.$promo, '1 year');
    $this->getUser()->setFlash('info', 'Cookie enregistré');
    $this->redirect("@image?filiere=$filiere&promo=$promo&semaine=$semaine");
  }

  public function executeReset(sfWebRequest $request)
  {
    $filiere = $request->getParameter('filiere');
    $promo = $request->getParameter('promo');
    $semaine = intval($request->getParameter('semaine', AdeTools::getSemaineNumber()));

    // Expires yesterday ! => expires now
    $this->getResponse()->setCookie('default', '', 'yesterday');
    $this->getUser()->setFlash('info', 'Cookie effacé');
    $this->redirect("@image?filiere=$filiere&promo=$promo&semaine=$semaine");
  }
}
