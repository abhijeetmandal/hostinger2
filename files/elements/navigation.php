<?php
    $levelSections = $app->levels->getList();
    $articleCategories = $app->articles->getCategories(null, false);
    $forumSections = $app->forum->getSections(null, false);
    $challengeSections = $app->challenges->getList();
    $vip=$app->user->vip;
    
    //below is not required also changing $myprofile with $profile is a bug and renaming to $myprofile is bad code instead used above line
    //$myprofile = new profile($app->user);   
    //    if($myprofile->isVip()){
    //        $vip=true;
    //    }
    
    if($app->user->username=='abhi1302'){
        echo $app->twig->render('navigation_abhi1302.html', array('user' => $app->user, 'articleCategories' => $articleCategories, 'levelSections' => $levelSections, 'forumSections' => $forumSections, 'challengeSections' => $challengeSections,'vip' => $vip));
    }else{
        echo $app->twig->render('navigation.html', array('user' => $app->user, 'articleCategories' => $articleCategories, 'levelSections' => $levelSections, 'forumSections' => $forumSections, 'challengeSections' => $challengeSections,'vip' => $vip));
    }
    
?>