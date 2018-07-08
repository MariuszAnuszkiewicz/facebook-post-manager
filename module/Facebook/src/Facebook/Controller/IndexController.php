<?php
namespace Facebook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Facebook\Facebook\Model\AccessTable;
use Facebook\Facebook\Model\Form;
use Facebook\Form\FacebookForm;
use Facebook\Listener\SendListener;


class IndexController extends AbstractActionController
{

    protected $accessTable;

    /**
     * @return array|object
     */
    public function getAccessTable()
    {
        if (!$this->accessTable) {
            $sm = $this->getServiceLocator();
            $this->accessTable = $sm->get('Facebook\Facebook\Model\AccessTable');
        }
        return $this->accessTable;
    }

    /**
     * @return array|ViewModel
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function indexAction()
    {
        $accessId = $this->getAccessTable()->getAccesss(1);
        $accessSecret = $this->getAccessTable()->getAccesss(2);
        $accessToken = $this->getAccessTable()->getAccesss(3);

        $messages = null;
        try {
            $sl = new SendListener();
            $responseGet = $sl->initFacebook($accessId->access_value, $accessSecret->access_value)->get('/me/posts', $accessToken->access_value);
            $message = $responseGet->getGraphEdge();

            for ($i = 0; $i < count($message); $i++) {
                $messages[$i] = $message[$i];
            }

        } catch (Exception $e) {
            echo $e->getMessage();
        }

        return new ViewModel(array(
            'messages' => $messages
        ));
    }

    /**
     * @return array|\Zend\Http\Response
     */
    public function sendAction()
    {

        $accessId = $this->getAccessTable()->getAccesss(1);
        $accessSecret = $this->getAccessTable()->getAccesss(2);
        $accessToken = $this->getAccessTable()->getAccesss(3);

        $form = new FacebookForm();
        $form->get('submit')->setValue('Wyślij');

        $request = $this->getRequest();
        if ($request->isPost()) {
           $ModelForm = new Form();
           $form->setInputFilter($ModelForm->getInputFilter());
           $form->setData($request->getPost());

           if ($form->isValid()) {
               $ModelForm->exchangeArray($form->getData());

               $content = [
                   $request->getPost('link_name'),
                   $request->getPost('message')
               ];
               $serviceManager = $this->getServiceLocator();
               $sendPost = $serviceManager->get('send_post');
               $sendPost->send($content, $accessId->access_value, $accessSecret->access_value, $accessToken->access_value);

           }
        }
        return array('form' => $form);
    }

    /**
     * @return ViewModel
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function deleteAction()
    {
        $accessId = $this->getAccessTable()->getAccesss(1);
        $accessSecret = $this->getAccessTable()->getAccesss(2);
        $accessToken = $this->getAccessTable()->getAccesss(3);

        $postIdfromUrl = $this->params()->fromRoute('id');

        try {

            if (!$postIdfromUrl) {
                return $this->redirect()->toRoute('facebook');
            }

        } catch (Exception $e) {
            return $this->redirect()->toRoute('facebook');
        }
        $serviceManager = $this->getServiceLocator();
        $deletePost = $serviceManager->get('delete_post');
        $messages = $deletePost->getMessages($accessId->access_value, $accessSecret->access_value, $accessToken->access_value);
        $postId = $deletePost->getPostId($accessId->access_value, $accessSecret->access_value, $accessToken->access_value, $postIdfromUrl);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $delete = $request->getPost('delete', 'Delete');
            if ($delete == 'Delete') {
                $serviceManager = $this->getServiceLocator();
                $deletePost = $serviceManager->get('delete_post');
                $deletePost->delete($accessId->access_value, $accessSecret->access_value, $accessToken->access_value, $postIdfromUrl);
            }
            return $this->redirect()->toRoute('facebook');
        }

        return new ViewModel(array(
           'messages' => $messages[$postId[0]]
        ));
    }

    /**
     * @return ViewModel
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function editAction()
    {
        $accessId = $this->getAccessTable()->getAccesss(1);
        $accessSecret = $this->getAccessTable()->getAccesss(2);
        $accessToken = $this->getAccessTable()->getAccesss(3);

        $postIdfromUrl = $this->params()->fromRoute('id');

        try {

            if (!$postIdfromUrl) {
                return $this->redirect()->toRoute('facebook');
            }

        } catch (Exception $e) {
            return $this->redirect()->toRoute('facebook');
        }

        $form = new FacebookForm();
        $form->get('submit')->setValue('Save');

        $request = $this->getRequest();
        $msg = $this->params()->fromPost('message');
        $serviceManager = $this->getServiceLocator();
        $editPost = $serviceManager->get('edit_post');
        $messages = $editPost->getMessages($accessId->access_value, $accessSecret->access_value, $accessToken->access_value);
        $postId = $editPost->getPostId($accessId->access_value, $accessSecret->access_value, $accessToken->access_value, $postIdfromUrl);

        if ($request->getPost('submit')) {

            $editPost->edit($accessId->access_value, $accessSecret->access_value, $accessToken->access_value, $postIdfromUrl, $msg);
            $this->redirect()->toRoute('facebook');

            return new ViewModel(array(
                'messages' => $msg,
                'form' => $form
            ));

        } else {
            return new ViewModel(array(
                'messages' => $messages[$postId[0]],
                'form' => $form
            ));
        }
    }
}

