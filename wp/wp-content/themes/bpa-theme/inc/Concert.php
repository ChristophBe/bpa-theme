<?php
/**
 * @property WP_Post $post
 * @property DateTime $time
 * @property string $title
 * @property string $permalink
 * @property bool $tickets_on_sell
 * @property bool $is_project_concert
 * @property Location $location
 */
class Concert
{

    /**
     * @param $post WP_Post
     * @param string $title
     * @param DateTime $time
     * @param string$permalink
     * @param boolean $tickets_on_sell
     * @param boolean $is_project_concert
     * @param Location $location
     */
    public function __construct(WP_Post $post,string $title, DateTime $time,string $permalink, bool $tickets_on_sell, bool $is_project_concert, Location $location)
    {
        $this->post = $post;
        $this->time = $time;
        $this->title = $title;
        $this->permalink = $permalink;
        $this->tickets_on_sell = $tickets_on_sell;
        $this->is_project_concert = $is_project_concert;
        $this->location = $location;
    }


    /**
     * @return string
     */
    public function getPermalink():string
    {
        return $this->permalink;
    }

    /**
     * @return boolean
     */
    public function getTicketsOnSell():bool
    {
        return $this->tickets_on_sell;
    }

    /**
     * @return boolean
     */
    public function isProjectConcert():bool
    {
        return $this->is_project_concert;
    }

    /**
     * @return Location
     */
    public function getLocation():Location
    {
        return $this->location;
    }


    /**
     * @return WP_Post
     */
    public function getPost(): WP_Post
    {
        return $this->post;
    }

    /**
     * @return boolean
     */
    public function isUpcoming():bool
    {
        return $this->time->getTimestamp() > time();
    }

    /**
     * @return string
     */
    public function getTitle():string
    {
        return $this->title;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

}