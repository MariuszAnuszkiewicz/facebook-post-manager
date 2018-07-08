<?php
namespace Facebook\Model;

use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;

class SendPost implements EventManagerAwareInterface
{
    private $content;
    protected $events;

    /**
     * @param array $data
     */
    public function setPost(array $data)
    {
        if (!empty($data)) {
            list($link, $message) = $data;
            if ($message) {
                $this->content = [
                    'link' => $link,
                    'message' => $message
                ];
            }
        }
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        if (!empty($this->content)) {
            return $this->content;
        }
    }

    /**
     * @param array $content
     * @param $accessId
     * @param $accessSecret
     * @param $accessToken
     */
    public function send(array $content, $accessId, $accessSecret, $accessToken)
    {
        try {

            $params = compact('content', 'accessId', 'accessSecret', 'accessToken');
            $result = $this->getEventManager()->trigger(__FUNCTION__, $this, [$params]);
            if ($result != true) {
                throw new \Exception("Send Post failed.");
            }

        } catch (\Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    /**
     * @param EventManagerInterface $events
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


