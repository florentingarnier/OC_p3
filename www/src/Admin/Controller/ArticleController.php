<?php

namespace src\Admin\Controller;

use app\AbstractController;
use app\Database;
use app\Request;
use src\Front\Model\BilletModel;

class ArticleController extends AbstractController
{
    /**
     * @param Request $request
     * @return \app\Response
     *
     * List all article in Back Office
     */
    public function listAction(Request $request)
    {
        $billets = null;

        try{
            if ($this->user->getRoles() === 'ADMIN' ||
                $this->user->getRoles() === 'SUPER_ADMIN'){
                $billetModel = new BilletModel(new Database());

                $billets = $billetModel->findAll();

            } else throw new \Exception("Accès refusés");

        }catch(\Exception $exception)
        {
            $request->setSession('message', 'danger', '<strong>Erreur : </strong>' .$exception->getMessage());
            return $this->router->redirect('admin', 'index');
        }
        return $this->render(':listArticle', ['billets' => $billets]);
    }

    /**
     * @param Request $request
     * @return \app\Response
     *
     * Write an article and store in Database
     */
    public function writeAction(Request $request)
    {
        try{
            if ($this->user->getRoles() === 'ADMIN' ||
                $this->user->getRoles() === 'SUPER_ADMIN') {
                if ($request->isPost()) {
                    $billet = new BilletModel(new Database());

                    $billet
                        ->setTitle($request->getPost('title'))
                        ->setContent($request->getPost('content'))
                        ->save($billet);

                    return $this->router->redirect('article', 'index');
                }
                return $this->render(':write');
            } else {
                throw new \Exception("Accès refusés");
            }
        } catch (\Exception $exception) {
            $request->setSession('message', 'danger', '<strong>Erreur : </strong>' .$exception->getMessage());
            return $this->router->redirect('admin', 'index');
        }

    }

    /**
     * @param Request $request
     * @return \app\Response
     *
     * Update an article
     */
    public function updateAction(Request $request)
    {
        try{
            if ($this->user->getRoles() === 'ADMIN' ||
                $this->user->getRoles() === 'SUPER_ADMIN') {

                $billetModel = new BilletModel(new Database());

                $billet = $billetModel->findOne($request->getParam('id'));

                if ($request->isPost()) {


                    $billet
                        ->setTitle($request->getPost('title'))
                        ->setContent($request->getPost('content'))
                        ->save($billet);

                    return $this->router->redirect('article', 'index');
                }
                return $this->render(':write', ['billet' => $billet]);
            } else {
                throw new \Exception("Accès refusés");
            }
        }catch (\Exception $exception){
            $request->setSession('message', 'danger', '<strong>Erreur : </strong>' .$exception->getMessage());
            return $this->router->redirect('admin', 'index');
        }
    }

    /**
     * @param Request $request
     * @return \app\Response
     *
     * Delete article in Database
     */
    public function deleteAction(Request $request)
    {
        try{
            if ($this->user->getRoles() === 'ADMIN' ||
                $this->user->getRoles() === 'SUPER_ADMIN'){

                $billetModel = new BilletModel(new Database());

                $billet = $billetModel->findOne($request->getParam('id'));
                if ($billet){
                    $billet->delete();
                } else {
                    throw new \Exception('L\'article que vous souhaitez supprimer n\'éxiste pas');
                }

            } else {
                throw new \Exception("Accès refusés");
            }
        } catch (\Exception $exception){
            $request->setSession('message', 'danger', '<strong>Erreur : </strong>' .$exception->getMessage());
            return $this->router->redirect('admin_article', 'list');
        }

        return $this->router->redirect('admin_article', 'list');
    }

}