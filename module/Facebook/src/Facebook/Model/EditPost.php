<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 02.07.2018
 * Time: 15:39
 */

namespace Facebook\Model;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Facebook\Listener\SendListener;


class EditPost implements EventManagerAwareInterface
{

    protected $events;

    /**
     * @param $accessId
     * @param $accessSecret
     * @param $accessToken
     * @param $id
     */
    public function edit($accessId, $accessSecret, $accessToken, $id, $message)
    {
        try {

            $params = compact('accessId', 'accessSecret', 'accessToken', 'id', 'message');
            $result = $this->getEventManager()->trigger(__FUNCTION__, $this, [$params]);
            if ($result != true) {
                throw new \Exception("Edit Post failed.");
            }

        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }

    }

    /**
     * @param $accessId
     * @param $accessSecret
     * @param $accessToken
     */
    public function getMessages($accessId, $accessSecret, $accessToken)
    {
        $sl = new SendListener();
        $fetchData = $sl->initFacebook($accessId, $accessSecret)->get('/me/posts', $accessToken);
        $message = $fetchData->getGraphEdge();

        $messages = null;

        for ($i = 0; $i < count($message); $i++) {
            $messages[] = $message[$i]['message'];
        }
        return $messages;
    }

    /**
     * @param $accessId
     * @param $accessSecret
     * @param $accessToken
     * @param $postIdfromUrl
     * @return array|null
     * @throws \Facebook\Exceptions\FacebookSDKException
     */
    public function getPostId($accessId, $accessSecret, $accessToken, $postIdfromUrl)
    {
        $sl = new SendListener();
        $fetchData = $sl->initFacebook($accessId, $accessSecret)->get('/me/posts', $accessToken);
        $message = $fetchData->getGraphEdge();

        $postId = null;
        $postIdCollections = null;

        for ($i = 0; $i < count($message); $i++) {
            $postIdCollections[$i] = $message[$i]['id'];
        }

        foreach($postIdCollections as $key => $value) {
            if ($postIdfromUrl == $value) {
                $postId[] = $key;
            }
        }
        return $postId;
    }

    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return void
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers([
            __CLASS__,
            get_class($this)
        ]);
        $this->events = $events;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (null === $this->events) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }
}