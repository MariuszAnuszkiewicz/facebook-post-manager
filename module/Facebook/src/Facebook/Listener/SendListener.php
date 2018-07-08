<?php
namespace Facebook\Listener;

use Facebook\Exceptions\FacebookSDKException;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Facebook\Model\SendPost;
use Facebook\Model\DeletePost;
use Facebook\Model\EditPost;
use Zend\Db\TableGateway\TableGateway;
use Facebook\Facebook\Model\AccessTable;


class SendListener implements ListenerAggregateInterface
{
    const VER = 'v3.0';
    const EXIST = 'That content of post exist.';
    const SUCCESS_POST = 'Post was send successfully.';
    private $facebook;
    protected $listeners = [];

    public function initFacebook($accessId, $accessSecret)
    {
        try {
            $this->facebook = new \Facebook\Facebook([
                'app_id' => $accessId,
                'app_secret' => $accessSecret,
                'default_graph_version' => self::VER,
            ]);
        } catch (\Facebook\Exceptions\FacebookResponseException $error) {
            echo 'Graph returned an error: ' . $error->getMessage();
        } catch (\Facebook\Exceptions\FacebookSDKException $error) {
            echo 'Facebook SDK returned an error: ' . $error->getMessage();
        }
        return $this->facebook;
    }

    public function attach(EventManagerInterface $events)
    {
        $sharedEvents  = $events->getSharedManager();
        $this->listeners[] = $sharedEvents->attach(SendPost::class, 'send', [$this, 'onSendPost'], 100);
        $this->listeners[] = $sharedEvents->attach(DeletePost::class, 'delete', [$this, 'onDeletePost'], 90);
        $this->listeners[] = $sharedEvents->attach(EditPost::class, 'edit', [$this, 'onEditPost'], 80);
    }

    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    public function onSendPost($events)
    {
        $status = true;
        $params = $events->getParams()[0];

        $fetchData = $this->initFacebook($params['accessId'], $params['accessSecret'])->get('/me/posts', $params['accessToken']);
        $message = $fetchData->getGraphEdge();

        try {

            $send = new SendPost();
            $send->setPost($params['content']);
            if (empty($params)) {
                throw new Exception("array data is empty");
                $status = false;
            } else {
                $response = $this->initFacebook($params['accessId'], $params['accessSecret'])->post('/me/feed', $send->getPost(), $params['accessToken']);
                if ($response) {
                    print self::SUCCESS_POST;
                    $status = true;
                }
            }

        } catch (\Exception $error) {
            echo $error->getMessage();
        } catch (FacebookSDKException $e) {
            echo $e->getMessage();
        }
        return $status;
    }

    public function onDeletePost($events)
    {
        $status = true;
        $params = $events->getParams()[0];
        $idPost = $params['id'];

        try {

            $response = $this->initFacebook($params['accessId'], $params['accessSecret'])->delete('/' . $idPost, array(), $params['accessToken']);
            if ($response) {
                $status = true;
            }

        } catch (\Exception $error) {
            echo $error->getMessage();
        } catch (FacebookSDKException $e) {
            echo $e->getMessage();
        }
        return $status;
    }

    public function onEditPost($events)
    {
        $status = true;
        $params = $events->getParams()[0];
        $idPost = $params['id'];
        $message = $params['message'];

        try {

            $response = $this->initFacebook($params['accessId'], $params['accessSecret'])->post('/' . $idPost, array('message' => $message), $params['accessToken']);
            if ($response) {
                $status = true;
            }

        } catch (\Exception $error) {
            echo $error->getMessage();
        } catch (FacebookSDKException $e) {
            echo $e->getMessage();
        }
        return $status;
    }
}