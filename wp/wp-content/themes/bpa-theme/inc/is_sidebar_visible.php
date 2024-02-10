<?php
/**
 * Created by IntelliJ IDEA.
 * User: christoph
 * Date: 03/10/18
 * Time: 12:55
 */

if(!function_exists("is_sidebar_visible")) {


    function is_sidebar_visible(){
        return is_front_page(); //!is_post_type_archive("post") && get_post_type() !="post";
    }
}