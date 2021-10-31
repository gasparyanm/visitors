<?php
    if ( !isset( $_SERVER['HTTP_REFERER']) ) die ("Direct access not permitted");

    require './services/ImageService.php';
    require './services/UserDetectorService.php';

    $siteUrl =  $_SERVER['HTTP_REFERER'];

    $userDetectorService = new UserDetectorService($siteUrl);
    $userDetectorService->saveVisitorData();

    $name = './images/banner.jpg';

    $imageService = new ImageService;
    $imageService->setImagePath($name);
    $imageService->showImage();

    exit;
?>
